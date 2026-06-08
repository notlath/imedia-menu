const { useState, useEffect, useCallback } = window.React;
const { apiFetch } = window.wp;

function ImediaMenuLocationModule( props ) {
	const { location } = props.attrs || {};
	const [ menuHtml, setMenuHtml ] = useState( '' );
	const [ loading, setLoading ] = useState( false );

	const fetchMenu = useCallback( async () => {
		if ( ! location ) {
			setMenuHtml( '' );
			return;
		}
		setLoading( true );
		try {
			const response = await apiFetch( {
				path: `/imedia-menu/v1/render-menu?location=${ encodeURIComponent( location ) }`,
				method: 'GET',
			} );
			setMenuHtml( response.html || '' );
		} catch ( err ) {
			setMenuHtml( '<p>Failed to load menu.</p>' );
		} finally {
			setLoading( false );
		}
	}, [ location ] );

	useEffect( () => {
		const controller = new AbortController();
		fetchMenu();
		return () => controller.abort();
	}, [ fetchMenu ] );

	if ( ! location ) {
		return (
			<div className="imm-divi-placeholder">
				<p>Select a menu location in module settings.</p>
			</div>
		);
	}

	if ( loading ) {
		return <div className="imm-divi-loading">Loading menu…</div>;
	}

	return (
		<div
			className="imm-divi-menu-preview"
			dangerouslySetInnerHTML={ { __html: menuHtml } }
		/>
	);
}

window.immDiviModule = ImediaMenuLocationModule;
