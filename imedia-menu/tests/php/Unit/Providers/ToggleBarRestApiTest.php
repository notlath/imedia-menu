<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Providers;

use IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository;
use IMedia\Menu\Providers\RestApiServiceProvider;
use PHPUnit\Framework\TestCase;

final class ToggleBarRestApiTest extends TestCase {

	private RestApiServiceProvider $provider;
	private ToggleBarRepository $repository;

	protected function setUp(): void {
		unset( $GLOBALS['_imedia_menu_options']['imedia_menu_toggle_bar'] );
		unset( $GLOBALS['_wp_registered_nav_menus'] );
		unset( $GLOBALS['_nav_menu_locations'] );

		$GLOBALS['_wp_registered_nav_menus'] = array(
			'primary' => 'Primary',
			'footer'  => 'Footer',
		);
		$GLOBALS['_nav_menu_locations'] = array(
			'primary' => 1,
			'footer'  => 2,
		);

		$this->provider   = new RestApiServiceProvider();
		$this->repository = new ToggleBarRepository();
	}

	public function testGetToggleBarReturnsBlocksForLocation(): void {
		$this->repository->save( 'primary', array(
			array(
				'id'       => 'b1',
				'type'     => 'logo',
				'align'    => 'left',
				'settings' => array( 'logo_id' => 42, 'url' => '/', 'target' => '_self' ),
			),
		) );

		$request = new \WP_REST_Request( 'GET', '/imedia-menu/v1/toggle-bar/primary' );
		$request->set_param( 'slug', 'primary' );

		$response = $this->provider->getToggleBar( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( 'primary', $data['slug'] );
		$this->assertCount( 1, $data['blocks'] );
		$this->assertSame( 'logo', $data['blocks'][0]['type'] );
	}

	public function testGetToggleBarReturnsEmptyForLocationWithNoBlocks(): void {
		$request = new \WP_REST_Request( 'GET', '/imedia-menu/v1/toggle-bar/primary' );
		$request->set_param( 'slug', 'primary' );

		$response = $this->provider->getToggleBar( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertEmpty( $data['blocks'] );
	}

	public function testGetToggleBarRejectsInvalidLocation(): void {
		$request = new \WP_REST_Request( 'GET', '/imedia-menu/v1/toggle-bar/invalid' );
		$request->set_param( 'slug', 'invalid' );

		$response = $this->provider->getToggleBar( $request );

		$this->assertSame( 400, $response->get_status() );
	}

	public function testSaveToggleBarPersistsBlocks(): void {
		$request = new \WP_REST_Request( 'POST', '/imedia-menu/v1/toggle-bar/primary' );
		$request->set_param( 'slug', 'primary' );
		$request->set_body( wp_json_encode( array(
			'blocks' => array(
				array(
					'id'       => 'b1',
					'type'     => 'spacer',
					'align'    => 'left',
					'settings' => array( 'width' => '30px' ),
				),
			),
		) ) );

		$response = $this->provider->saveToggleBar( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertTrue( $data['success'] );

		$blocks = $this->repository->get( 'primary' );
		$this->assertCount( 1, $blocks );
		$this->assertSame( 'spacer', $blocks[0]['type'] );
	}

	public function testSaveToggleBarRejectsInvalidLocation(): void {
		$request = new \WP_REST_Request( 'POST', '/imedia-menu/v1/toggle-bar/invalid' );
		$request->set_param( 'slug', 'invalid' );
		$request->set_body( wp_json_encode( array(
			'blocks' => array(
				array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
			),
		) ) );

		$response = $this->provider->saveToggleBar( $request );

		$this->assertSame( 400, $response->get_status() );
	}

	public function testDeleteToggleBarRemovesBlocks(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
		) );

		$request = new \WP_REST_Request( 'DELETE', '/imedia-menu/v1/toggle-bar/primary' );
		$request->set_param( 'slug', 'primary' );

		$response = $this->provider->deleteToggleBar( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertTrue( $data['success'] );
		$this->assertEmpty( $this->repository->get( 'primary' ) );
	}
}