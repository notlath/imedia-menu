<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\PanelLayout;

use IMedia\Menu\Frontend\PanelLayout\FlyoutLayout;
use PHPUnit\Framework\TestCase;

final class FlyoutLayoutTest extends TestCase {

	private FlyoutLayout $layout;

	protected function setUp(): void {
		$this->layout = new FlyoutLayout();
	}

	public function testRenderAlwaysReturnsEmptyString(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array(
								'span'   => 12,
								'blocks' => array(
									array(
										'type'   => 'text',
										'config' => array(
											'content' => 'ThisShouldNotRender',
										),
									),
								),
							),
						),
					),
				),
			),
		);

		$this->assertSame( '', $this->layout->render( $panel, 1 ) );
	}

	public function testRenderWithEmptyConfigReturnsEmptyString(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(),
		);

		$this->assertSame( '', $this->layout->render( $panel, 1 ) );
	}

	public function testRequiredStylesheetIsNull(): void {
		$this->assertNull( $this->layout->requiredStylesheet() );
	}
}
