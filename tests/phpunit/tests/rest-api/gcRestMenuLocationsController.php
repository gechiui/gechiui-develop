<?php
/**
 * GC_REST_Menu_Locations_Controller tests.
 *
 * @package GeChiUI
 * @subpackage REST_API
 * @since 5.9.0
 */

/**
 * Tests for REST API for Menu locations.
 *
 * @group restapi
 *
 * @coversDefaultClass GC_REST_Menu_Locations_Controller
 */
class Tests_REST_GcRestMenuLocationsController extends GC_Test_REST_Controller_Testcase {

	/**
	 * @var int
	 */
	protected static $admin_id;

	/**
	 * Create fake data before our tests run.
	 *
	 * @param GC_UnitTest_Factory $factory Helper that lets us create fake data.
	 */
	public static function gcSetUpBeforeClass( $factory ) {
		self::$admin_id = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);
	}

	/**
	 * Set up.
	 */
	public function set_up() {
		parent::set_up();

		// Unregister all nav menu locations.
		foreach ( array_keys( get_registered_nav_menus() ) as $location ) {
			unregister_nav_menu( $location );
		}
	}

	/**
	 * Register nav menu locations.
	 *
	 * @param array $locations Location slugs.
	 */
	public function register_nav_menu_locations( $locations ) {
		foreach ( $locations as $location ) {
			register_nav_menu( $location, ucfirst( $location ) );
		}
	}

	/**
	 * @ticket 40878
	 * @covers ::register_routes
	 */
	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey( '/gc/v2/menu-locations', $routes );
		$this->assertCount( 1, $routes['/gc/v2/menu-locations'] );
		$this->assertArrayHasKey( '/gc/v2/menu-locations/(?P<location>[\w-]+)', $routes );
		$this->assertCount( 1, $routes['/gc/v2/menu-locations/(?P<location>[\w-]+)'] );
	}

	/**
	 * @ticket 40878
	 * @covers ::get_context_param
	 */
	public function test_context_param() {
		// Collection.
		$request  = new GC_REST_Request( 'OPTIONS', '/gc/v2/menu-locations' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertSame( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
		$menu = 'primary';
		$this->register_nav_menu_locations( array( $menu ) );
		$request  = new GC_REST_Request( 'OPTIONS', '/gc/v2/menu-locations/' . $menu );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertSame( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
	}

	/**
	 * @ticket 40878
	 * @covers ::get_items
	 */
	public function test_get_items() {
		$menus = array( 'primary', 'secondary' );
		$this->register_nav_menu_locations( array( 'primary', 'secondary' ) );
		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/menu-locations' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = array_values( $data );
		$this->assertCount( 2, $data );
		$names        = gc_list_pluck( $data, 'name' );
		$descriptions = gc_list_pluck( $data, 'description' );
		$this->assertSame( $menus, $names );
		$menu_descriptions = array_map( 'ucfirst', $names );
		$this->assertSame( $menu_descriptions, $descriptions );
	}

	/**
	 * @ticket 40878
	 * @covers ::get_item
	 */
	public function test_get_item() {
		$menu = 'primary';
		$this->register_nav_menu_locations( array( $menu ) );

		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/menu-locations/' . $menu );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( $menu, $data['name'] );
	}

	/**
	 * @ticket 40878
	 * @covers ::get_item
	 */
	public function test_get_item_invalid() {
		$menu = 'primary';
		$this->register_nav_menu_locations( array( $menu ) );

		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/menu-locations/invalid' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_menu_location_invalid', $response, 404 );
	}

	/**
	 * The test_create_item() method does not exist for menu locations.
	 */
	public function test_create_item() {}

	/**
	 * The test_update_item() method does not exist for menu locations.
	 */
	public function test_update_item() {}

	/**
	 * The test_delete_item() method does not exist for menu locations.
	 */
	public function test_delete_item() {}

	/**
	 * The test_prepare_item() method does not exist for menu locations.
	 */
	public function test_prepare_item() {}

	/**
	 * @ticket 40878
	 * @covers ::get_item_schema
	 */
	public function test_get_item_schema() {
		gc_set_current_user( self::$admin_id );
		$request    = new GC_REST_Request( 'OPTIONS', '/gc/v2/menu-locations' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertSame( 3, count( $properties ) );
		$this->assertArrayHasKey( 'name', $properties );
		$this->assertArrayHasKey( 'description', $properties );
		$this->assertArrayHasKey( 'menu', $properties );
	}


	/**
	 * @ticket 40878
	 * @covers ::get_items
	 * @covers ::get_items_permissions_check
	 */
	public function test_get_items_menu_location_context_without_permission() {
		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/menu-locations' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_cannot_view', $response, rest_authorization_required_code() );
	}

	/**
	 * @ticket 40878
	 * @covers ::get_item
	 * @covers ::get_item_permissions_check
	 */
	public function test_get_item_menu_location_context_without_permission() {
		$menu = 'primary';
		$this->register_nav_menu_locations( array( $menu ) );

		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/menu-locations/' . $menu );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_cannot_view', $response, rest_authorization_required_code() );
	}
}
