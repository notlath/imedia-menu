(function() {
var React = window.React;
var apiFetch = window.wp && window.wp.apiFetch;

function ImediaMenuLocationModule(props) {
  var location = props.attrs && props.attrs.location;
  var _useState = React.useState(''),
      menuHtml = _useState[0],
      setMenuHtml = _useState[1];
  var _useState2 = React.useState(false),
      loading = _useState2[0],
      setLoading = _useState2[1];

  var fetchMenu = React.useCallback(function() {
    if (!location) {
      setMenuHtml('');
      return;
    }
    setLoading(true);
    apiFetch({
      path: '/imedia-menu/v1/render-menu?location=' + encodeURIComponent(location),
      method: 'GET'
    }).then(function(response) {
      setMenuHtml(response.html || '');
    }).catch(function() {
      setMenuHtml('<p>Failed to load menu.</p>');
    }).finally(function() {
      setLoading(false);
    });
  }, [location]);

  React.useEffect(function() {
    fetchMenu();
  }, [fetchMenu]);

  if (!location) {
    return React.createElement('div', { className: 'imm-divi-placeholder' },
      React.createElement('p', null, 'Select a menu location in module settings.')
    );
  }

  if (loading) {
    return React.createElement('div', { className: 'imm-divi-loading' }, 'Loading menu\u2026');
  }

  return React.createElement('div', {
    className: 'imm-divi-menu-preview',
    dangerouslySetInnerHTML: { __html: menuHtml }
  });
}

window.immDiviModule = ImediaMenuLocationModule;
})();
