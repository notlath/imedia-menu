<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class Registry {

	/** @var array<string, ContentBlock> */
	private array $blocks = array();

	public function __construct() {
		$this->registerDefaults();
	}

	public function register( ContentBlock $block ): void {
		$this->blocks[ $block->type() ] = $block;
	}

	public function get( string $type ): ?ContentBlock {
		return $this->blocks[ $type ] ?? null;
	}

	public function getAll(): array {
		return $this->blocks;
	}

	public function render( array $block ): string {
		$type   = $block['type'] ?? '';
		$config = $block['config'] ?? array();
		$styles = $block['styles'] ?? array();

		$handler = $this->get( $type );

		if ( $handler === null ) {
			return sprintf(
				'<!-- iMedia Menu: Unknown block type "%s" -->',
				esc_html( $type )
			);
		}

		$html = $handler->render( $config, $styles );

		return apply_filters( 'imedia_menu_content_block_html', $html, $block, null );
	}

	private function registerDefaults(): void {
		$defaults = array(
			new MenuLinksBlock(),
			new HeadingBlock(),
			new TextBlock(),
			new IconBlock(),
			new ImageBlock(),
			new BannerBlock(),
			new GutenbergBlock(),
			new WidgetBlock(),
			new HtmlBlock(),
			new ShortcodeBlock(),
			new PostListingBlock(),
			new TaxonomyListingBlock(),
			new SearchBlock(),
			new DividerBlock(),
		);

		foreach ( $defaults as $block ) {
			$this->register( $block );
		}
	}
}
