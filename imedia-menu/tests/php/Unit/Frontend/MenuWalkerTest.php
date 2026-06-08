<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend;

use IMedia\Menu\Enums\MenuOrientation;
use IMedia\Menu\Enums\PanelLayoutType;
use IMedia\Menu\Frontend\MenuWalker;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class MenuWalkerTest extends TestCase {

	private function makeWalker( ?array $settings = null, string $location = 'primary' ): MenuWalker {
		return new MenuWalker( 1, $settings, $location );
	}

	public function testDefaultOrientationIsHorizontal(): void {
		$walker = $this->makeWalker();

		$this->assertSame( MenuOrientation::Horizontal, $walker->getOrientation() );
	}

	public function testOrientationParsedFromSettings(): void {
		$walker = $this->makeWalker( array( 'orientation' => 'vertical' ) );

		$this->assertSame( MenuOrientation::Vertical, $walker->getOrientation() );
	}

	public function testInvalidOrientationFallsBackToHorizontal(): void {
		$walker = $this->makeWalker( array( 'orientation' => 'sideways' ) );

		$this->assertSame( MenuOrientation::Horizontal, $walker->getOrientation() );
	}

	public function testLocationIsStored(): void {
		$walker = $this->makeWalker( null, 'footer' );

		$this->assertSame( 'footer', $walker->getLocation() );
	}

	public function testDefaultTriggerIsHover(): void {
		$walker = $this->makeWalker();

		$this->assertSame( 'hover', $walker->getTriggerType() );
	}

	public function testAccordionForcesClickTrigger(): void {
		$walker = $this->makeWalker( array( 'orientation' => 'accordion' ) );

		$this->assertSame( 'click', $walker->getTriggerType() );
	}

	public function testAccordionOverridesExplicitHoverTrigger(): void {
		$walker = $this->makeWalker( array(
			'orientation'  => 'accordion',
			'trigger_type' => 'hover',
		) );

		$this->assertSame( 'click', $walker->getTriggerType() );
	}

	public function testHorizontalDoesNotForceTrigger(): void {
		$walker = $this->makeWalker( array( 'trigger_type' => 'click' ) );

		$this->assertSame( 'click', $walker->getTriggerType() );
	}

	public function testStartLvlIncludesOrientationDataAttribute(): void {
		$walker = $this->makeWalker( array( 'orientation' => 'vertical' ) );
		$output  = '';

		$walker->start_lvl( $output, 0, null );

		$this->assertStringContainsString( 'data-orientation="vertical"', $output );
		$this->assertStringContainsString( 'imm-sub--vertical', $output );
	}

	public function testStartLvlHorizontalIsDefault(): void {
		$walker = $this->makeWalker();
		$output  = '';

		$walker->start_lvl( $output, 0, null );

		$this->assertStringContainsString( 'data-orientation="horizontal"', $output );
		$this->assertStringContainsString( 'imm-sub--horizontal', $output );
	}

	public function testDisplayElementSkipsFlyoutPanel(): void {
		$walker = $this->makeWalker();

		$panel          = new \stdClass();
		$panel->ID      = 10;
		$panel->is_enabled = 1;
		$panel->layout_type = 'flyout';
		$panel->css_class   = '';

		$reflection = new ReflectionClass( $walker );
		$panelsProp = $reflection->getProperty( 'panels' );
		$panelsProp->setValue( $walker, array( 10 => $panel ) );

		$element          = new \stdClass();
		$element->ID      = 10;
		$element->title   = 'Flyout Item';
		$element->url     = '#';
		$element->classes = array( 'menu-item' );

		$children = array();
		$output   = '';
		$args     = (object) array( 'walker' => $walker );

		$walker->display_element( $element, $children, 1, 0, $args, $output );

		$this->assertStringNotContainsString( 'imm-panel', $output );
		$this->assertStringContainsString( 'Flyout Item', $output );
	}

	public function testDisplayElementRendersStandardPanel(): void {
		$walker = $this->makeWalker();

		$panel             = new \stdClass();
		$panel->ID         = 11;
		$panel->is_enabled = 1;
		$panel->layout_type = 'columns';
		$panel->panel_width  = 'full';
		$panel->styles       = array();
		$panel->animation_type = '';

		$reflection = new ReflectionClass( $walker );
		$panelsProp = $reflection->getProperty( 'panels' );
		$panelsProp->setValue( $walker, array( 11 => $panel ) );
		$panelsProp->setValue( $walker, array( 11 => $panel ) );

		$element          = new \stdClass();
		$element->ID      = 11;
		$element->title   = 'Standard Item';
		$element->url     = '#';
		$element->classes = array( 'menu-item' );

		$children = array();
		$output   = '';
		$args     = (object) array( 'walker' => $walker );

		$walker->display_element( $element, $children, 1, 0, $args, $output );

		$this->assertStringContainsString( 'imm-panel', $output );
		$this->assertStringContainsString( 'imm-panel--full', $output );
	}

	public function testDisplayElementRendersGridPanel(): void {
		$walker = $this->makeWalker();

		$panel             = new \stdClass();
		$panel->ID         = 12;
		$panel->is_enabled = 1;
		$panel->layout_type = 'grid';
		$panel->panel_width  = 'contained';
		$panel->styles       = array();
		$panel->animation_type = '';

		$reflection = new ReflectionClass( $walker );
		$panelsProp = $reflection->getProperty( 'panels' );
		$panelsProp->setValue( $walker, array( 11 => $panel ) );
		$panelsProp->setValue( $walker, array( 12 => $panel ) );

		$element          = new \stdClass();
		$element->ID      = 12;
		$element->title   = 'Grid Item';
		$element->url     = '#';
		$element->classes = array( 'menu-item' );

		$children = array();
		$output   = '';
		$args     = (object) array( 'walker' => $walker );

		$walker->display_element( $element, $children, 1, 0, $args, $output );

		$this->assertStringContainsString( 'imm-panel', $output );
		$this->assertStringContainsString( 'imm-panel--contained', $output );
	}

	public function testDisabledPanelFallsThroughToParent(): void {
		$walker = $this->makeWalker();

		$panel             = new \stdClass();
		$panel->ID         = 13;
		$panel->is_enabled = 0;
		$panel->layout_type = 'flyout';
		$panel->css_class   = '';

		$reflection = new ReflectionClass( $walker );
		$panelsProp = $reflection->getProperty( 'panels' );
		$panelsProp->setValue( $walker, array( 11 => $panel ) );
		$panelsProp->setValue( $walker, array( 13 => $panel ) );

		$element          = new \stdClass();
		$element->ID      = 13;
		$element->title   = 'Disabled';
		$element->url     = '#';
		$element->classes = array( 'menu-item' );

		$children = array();
		$output   = '';
		$args     = (object) array( 'walker' => $walker );

		$walker->display_element( $element, $children, 1, 0, $args, $output );

		$this->assertStringNotContainsString( 'imm-panel', $output );
	}

	public function testNoPanelFallsThroughToParent(): void {
		$walker = $this->makeWalker();

		$element          = new \stdClass();
		$element->ID      = 99;
		$element->title   = 'No Panel';
		$element->url     = '#';
		$element->classes = array( 'menu-item' );

		$children = array();
		$output   = '';
		$args     = (object) array( 'walker' => $walker );

		$walker->display_element( $element, $children, 1, 0, $args, $output );

		$this->assertStringNotContainsString( 'imm-panel', $output );
	}
}
