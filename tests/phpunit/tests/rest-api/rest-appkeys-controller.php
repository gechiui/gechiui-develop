<?php
/**
 * Unit tests covering GC_REST_AppKeys_Controller functionality.
 *
 * @package    GeChiUI
 * @subpackage REST API
 */

/**
 * @group restapi
 */
class GC_Test_REST_AppKeys_Controller extends GC_Test_REST_Controller_Testcase {

	/**
	 * Subscriber user ID.
	 *
	 * @since 5.6.0
	 *
	 * @var int
	 */
	private static $subscriber_id;

	/**
	 * Administrator user id.
	 *
	 * @since 5.6.0
	 *
	 * @var int
	 */
	private static $admin;

	/**
	 * Set up class test fixtures.
	 *
	 * @since 5.6.0
	 *
	 * @param GC_UnitTest_Factory $factory GeChiUI unit test factory.
	 */
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$subscriber_id = $factory->user->create(
			array(
				'role' => 'subscriber',
			)
		);
		self::$admin         = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);

		if ( is_multisite() ) {
			grant_super_admin( self::$admin );
		}
	}

	/**
	 * Clean up test fixtures.
	 *
	 * @since 5.6.0
	 */
	public static function gcTearDownAfterClass() {
		self::delete_user( self::$subscriber_id );
		self::delete_user( self::$admin );
	}

	public function set_up() {
		parent::set_up();

		add_filter( 'gc_is_appkeys_available', '__return_true' );
	}

	public function tear_down() {
		unset( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $GLOBALS['gc_rest_appkey_status'], $GLOBALS['gc_rest_appkey_uuid'] );
		parent::tear_down();
	}

	/**
	 * @ticket 42790
	 */
	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();

		$this->assertArrayHasKey( '/gc/v2/users/(?P<user_id>(?:[\\d]+|me))/appkeys', $routes );
		$this->assertCount( 3, $routes['/gc/v2/users/(?P<user_id>(?:[\\d]+|me))/appkeys'] );
		$this->assertArrayHasKey( '/gc/v2/users/(?P<user_id>(?:[\\d]+|me))/appkeys/(?P<uuid>[\\w\\-]+)', $routes );
		$this->assertCount( 3, $routes['/gc/v2/users/(?P<user_id>(?:[\\d]+|me))/appkeys/(?P<uuid>[\\w\\-]+)'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_context_param() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid = $item['uuid'];

		// Collection.
		$request  = new GC_REST_Request( 'OPTIONS', '/gc/v2/users/me/appkeys' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertSame( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
		// Single.
		$request  = new GC_REST_Request( 'OPTIONS', '/gc/v2/users/me/appkeys/' . $uuid );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertSame( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_disabled() {
		gc_set_current_user( self::$admin );
		add_filter( 'gc_is_appkeys_available', '__return_false' );

		$response = rest_do_request( '/gc/v2/users/me/appkeys' );
		$this->assertErrorResponse( 'appkeys_disabled', $response, 501 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_disabled_for_user() {
		gc_set_current_user( self::$admin );
		add_filter( 'gc_is_appkeys_available_for_user', '__return_false' );

		$response = rest_do_request( '/gc/v2/users/me/appkeys' );
		$this->assertErrorResponse( 'appkeys_disabled_for_user', $response, 501 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$response = rest_do_request( '/gc/v2/users/me/appkeys' );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 1, $response->get_data() );
		$this->check_response( $response->get_data()[0], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_self_user_id_admin() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 1, $response->get_data() );
		$this->check_response( $response->get_data()[0], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 1, $response->get_data() );
		$this->check_response( $response->get_data()[0], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_other_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 1, $response->get_data() );
		$this->check_response( $response->get_data()[0], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );

		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$this->assertErrorResponse( 'rest_cannot_list_appkeys', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_logged_out() {
		$response = rest_do_request( '/gc/v2/users/me/appkeys' );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_items_invalid_user_id() {
		gc_set_current_user( self::$admin );

		$response = rest_do_request( '/gc/v2/users/0/appkeys' );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( '/gc/v2/users/me/appkeys/' . $uuid );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_self_user_id_admin() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_other_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$this->assertErrorResponse( 'rest_cannot_read_appkey', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_logged_out() {
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( sprintf( '/gc/v2/users/me/appkeys/%s', $uuid ) );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_invalid_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$response = rest_do_request( '/gc/v2/users/0/appkeys/' . $uuid );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_invalid_password_uuid() {
		gc_set_current_user( self::$admin );
		$response = rest_do_request( sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, '123456abcdef' ) );
		$this->assertErrorResponse( 'rest_appkey_not_found', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item() {
		gc_set_current_user( self::$admin );

		$app_id  = gc_generate_uuid4();
		$request = new GC_REST_Request( 'POST', '/gc/v2/users/me/appkeys' );
		$request->set_body_params(
			array(
				'name'   => 'App',
				'app_id' => $app_id,
			)
		);
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );

		$passwords = GC_AppKeys::get_user_appkeys( self::$admin );
		$this->assertCount( 1, $passwords );
		$this->check_response( $response->get_data(), $passwords[0], true );
		$this->assertSame( 'App', $response->get_data()['name'] );
		$this->assertSame( $app_id, $response->get_data()['app_id'] );
		$this->assertNull( $response->get_data()['last_used'] );
		$this->assertNull( $response->get_data()['last_ip'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item_self_user_id_admin() {
		gc_set_current_user( self::$admin );

		$request = new GC_REST_Request( 'POST', sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );

		$passwords = GC_AppKeys::get_user_appkeys( self::$admin );
		$this->assertCount( 1, $passwords );
		$this->check_response( $response->get_data(), $passwords[0], true );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );

		$request = new GC_REST_Request( 'POST', sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );

		$passwords = GC_AppKeys::get_user_appkeys( self::$subscriber_id );
		$this->assertCount( 1, $passwords );
		$this->check_response( $response->get_data(), $passwords[0], true );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item_other_user_id() {
		gc_set_current_user( self::$admin );

		$request = new GC_REST_Request( 'POST', sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );

		$passwords = GC_AppKeys::get_user_appkeys( self::$subscriber_id );
		$this->assertCount( 1, $passwords );
		$this->check_response( $response->get_data(), $passwords[0], true );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );

		$request = new GC_REST_Request( 'POST', sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_cannot_create_appkeys', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_create_item_invalid_user_id() {
		gc_set_current_user( self::$admin );

		$request = new GC_REST_Request( 'POST', sprintf( '/gc/v2/users/%d/appkeys', 0 ) );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 51939
	 */
	public function test_create_item_records_app_passwords_in_use() {
		gc_set_current_user( self::$admin );

		$this->assertFalse( GC_AppKeys::is_in_use() );

		$request = new GC_REST_Request( 'POST', '/gc/v2/users/me/appkeys' );
		$request->set_body_params( array( 'name' => 'App' ) );
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );
		$this->assertTrue( GC_AppKeys::is_in_use() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), GC_AppKeys::get_user_appkey( self::$admin, $item['uuid'] ) );
		$this->assertSame( 'New App', $response->get_data()['name'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_self_user_id_admin() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), GC_AppKeys::get_user_appkey( self::$admin, $item['uuid'] ) );
		$this->assertSame( 'New App', $response->get_data()['name'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), GC_AppKeys::get_user_appkey( self::$subscriber_id, $item['uuid'] ) );
		$this->assertSame( 'New App', $response->get_data()['name'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_other_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data(), GC_AppKeys::get_user_appkey( self::$subscriber_id, $item['uuid'] ) );
		$this->assertSame( 'New App', $response->get_data()['name'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_cannot_edit_appkey', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_logged_out() {
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/me/appkeys/%s', $uuid ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_invalid_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', '/gc/v2/users/0/appkeys/' . $uuid );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_update_item_invalid_password_uuid() {
		gc_set_current_user( self::$admin );
		$request = new GC_REST_Request( 'PUT', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, '123456abcdef' ) );
		$request->set_body_params( array( 'name' => 'New App' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_appkey_not_found', $response, 404 );
	}

	/**
	 * @ticket 51583
	 * @ticket 51941
	 */
	public function test_update_item_cannot_overwrite_app_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_body_params( array( 'app_id' => gc_generate_uuid4() ) );
		$response = rest_do_request( $request );
		$this->assertSame( '', $response->get_data()['app_id'] );

		$app_id = gc_generate_uuid4();

		list( , $item ) = GC_AppKeys::create_new_appkey(
			self::$admin,
			array(
				'name'   => 'App 2',
				'app_id' => $app_id,
			)
		);

		$uuid    = $item['uuid'];
		$request = new GC_REST_Request( 'PUT', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_body_params( array( 'app_id' => gc_generate_uuid4() ) );
		$response = rest_do_request( $request );
		$this->assertSame( $app_id, $response->get_data()['app_id'] );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/users/me/appkeys/' . $uuid );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertArrayHasKey( 'deleted', $response->get_data() );
		$this->assertTrue( $response->get_data()['deleted'] );
		$this->assertArrayHasKey( 'previous', $response->get_data() );
		$this->check_response( $response->get_data()['previous'], $item );

		$this->assertNull( GC_AppKeys::get_user_appkey( self::$admin, $uuid ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_self_user_id_admin() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item ['uuid'];
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data()['previous'], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data()['previous'], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_other_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$subscriber_id, $uuid ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->check_response( $response->get_data()['previous'], $item );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, $uuid ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_cannot_delete_appkey', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_logged_out() {
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/me/appkeys/%s', $uuid ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_invalid_user_id() {
		gc_set_current_user( self::$admin );
		list( , $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid     = $item['uuid'];
		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/users/0/appkeys/' . $uuid );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_item_invalid_password_uuid() {
		gc_set_current_user( self::$admin );
		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys/%s', self::$admin, '123456abcdef' ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_appkey_not_found', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items() {
		gc_set_current_user( self::$admin );
		GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App 1' ) );
		GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App 2' ) );

		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/users/me/appkeys' );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertArrayHasKey( 'deleted', $response->get_data() );
		$this->assertTrue( $response->get_data()['deleted'] );
		$this->assertArrayHasKey( 'count', $response->get_data() );
		$this->assertSame( 2, $response->get_data()['count'] );

		$this->assertCount( 0, GC_AppKeys::get_user_appkeys( self::$admin ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_self_user_id_admin() {
		gc_set_current_user( self::$admin );
		GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 0, GC_AppKeys::get_user_appkeys( self::$admin ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_self_user_id_subscriber() {
		gc_set_current_user( self::$subscriber_id );
		GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 0, GC_AppKeys::get_user_appkeys( self::$admin ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_other_user_id() {
		gc_set_current_user( self::$admin );
		GC_AppKeys::create_new_appkey( self::$subscriber_id, array( 'name' => 'App' ) );

		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys', self::$subscriber_id ) );
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 0, GC_AppKeys::get_user_appkeys( self::$admin ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_other_user_id_invalid_permission() {
		gc_set_current_user( self::$subscriber_id );

		$request  = new GC_REST_Request( 'DELETE', sprintf( '/gc/v2/users/%d/appkeys', self::$admin ) );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_cannot_delete_appkeys', $response, 403 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_logged_out() {
		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/users/me/appkeys' );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_delete_items_invalid_user_id() {
		gc_set_current_user( self::$admin );

		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/users/0/appkeys' );
		$response = rest_do_request( $request );
		$this->assertErrorResponse( 'rest_user_invalid_id', $response, 404 );
	}

	/**
	 * @ticket 42790
	 */
	public function test_prepare_item() {
		gc_set_current_user( self::$admin );
		list( $password, $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'App' ) );

		$uuid                 = $item['uuid'];
		$item['uuid']         = $uuid;
		$item['new_password'] = $password;

		$request = new GC_REST_Request( 'GET', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_param( 'context', 'edit' );
		$request->set_url_params(
			array(
				'user_id' => 'me',
				'uuid'    => $uuid,
			)
		);
		$prepared = ( new GC_REST_AppKeys_Controller() )->prepare_item_for_response( $item, $request );
		$this->assertNotWPError( $prepared );
		$this->check_response( $prepared->get_data(), $item, true );

		$request = new GC_REST_Request( 'GET', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_param( 'context', 'view' );
		$request->set_url_params(
			array(
				'user_id' => 'me',
				'uuid'    => $uuid,
			)
		);
		$prepared = ( new GC_REST_AppKeys_Controller() )->prepare_item_for_response( $item, $request );
		$this->assertNotWPError( $prepared );
		$this->check_response( $prepared->get_data(), $item );

		GC_AppKeys::record_appkey_usage( self::$admin, $uuid );

		$item         = GC_AppKeys::get_user_appkey( self::$admin, $uuid );
		$item['uuid'] = $uuid;

		$request = new GC_REST_Request( 'GET', '/gc/v2/users/me/appkeys/' . $uuid );
		$request->set_param( 'context', 'view' );
		$request->set_url_params(
			array(
				'user_id' => 'me',
				'uuid'    => $uuid,
			)
		);
		$prepared = ( new GC_REST_AppKeys_Controller() )->prepare_item_for_response( $item, $request );
		$this->assertNotWPError( $prepared );
		$this->check_response( $prepared->get_data(), $item );
	}

	/**
	 * Checks the password response matches the exepcted format.
	 *
	 * @since 5.6.0
	 *
	 * @param array $response The response data.
	 * @param array $item     The created password item.
	 * @param bool  $password If the password is expected.
	 */
	protected function check_response( $response, $item, $password = false ) {
		$this->assertArrayHasKey( 'uuid', $response );
		$this->assertArrayHasKey( 'app_id', $response );
		$this->assertArrayHasKey( 'name', $response );
		$this->assertArrayHasKey( 'created', $response );
		$this->assertArrayHasKey( 'last_used', $response );
		$this->assertArrayHasKey( 'last_ip', $response );

		$this->assertSame( $item['uuid'], $response['uuid'] );
		$this->assertSame( $item['app_id'], $response['app_id'] );
		$this->assertSame( $item['name'], $response['name'] );
		$this->assertSame( gmdate( 'Y-m-d\TH:i:s', $item['created'] ), $response['created'] );

		if ( $item['last_used'] ) {
			$this->assertSame( gmdate( 'Y-m-d\TH:i:s', $item['last_used'] ), $response['last_used'] );
		} else {
			$this->assertNull( $response['last_used'] );
		}

		if ( $item['last_ip'] ) {
			$this->assertSame( $item['last_ip'], $response['last_ip'] );
		} else {
			$this->assertNull( $response['last_ip'] );
		}

		if ( $password ) {
			$this->assertArrayHasKey( 'password', $response );
		} else {
			$this->assertArrayNotHasKey( 'password', $response );
		}
	}

	/**
	 * @ticket 42790
	 */
	public function test_get_item_schema() {
		$request    = new GC_REST_Request( 'OPTIONS', '/gc/v2/users/me/appkeys' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];

		$this->assertArrayHasKey( 'uuid', $properties );
		$this->assertArrayHasKey( 'app_id', $properties );
		$this->assertArrayHasKey( 'name', $properties );
		$this->assertArrayHasKey( 'password', $properties );
		$this->assertArrayHasKey( 'created', $properties );
		$this->assertArrayHasKey( 'last_used', $properties );
		$this->assertArrayHasKey( 'last_ip', $properties );
		$this->assertCount( 7, $properties );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item() {
		$password = $this->setup_app_password_authenticated_request();
		$response = rest_do_request( '/gc/v2/users/me/appkeys/introspect' );
		$this->assertNotWPError( $response->as_error() );

		$this->assertSame( $password['uuid'], $response->get_data()['uuid'] );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item_specific_user() {
		$password = $this->setup_app_password_authenticated_request();
		$response = rest_do_request( '/gc/v2/users/' . self::$admin . '/appkeys/introspect' );

		$this->assertSame( $password['uuid'], $response->get_data()['uuid'] );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item_logged_out() {
		$response = rest_do_request( '/gc/v2/users/me/appkeys/introspect' );
		$this->assertErrorResponse( 'rest_not_logged_in', $response, 401 );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item_wrong_user() {
		$this->setup_app_password_authenticated_request();
		$response = rest_do_request( '/gc/v2/users/' . self::$subscriber_id . '/appkeys/introspect' );
		$this->assertErrorResponse( 'rest_cannot_introspect_app_password_for_non_authenticated_user', $response, 403 );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item_no_app_password_used() {
		gc_set_current_user( self::$admin );
		$response = rest_do_request( '/gc/v2/users/me/appkeys/introspect' );
		$this->assertErrorResponse( 'rest_no_authenticated_app_password', $response, 404 );
	}

	/**
	 * @ticket 52275
	 */
	public function test_introspect_item_password_invalid() {
		$this->setup_app_password_authenticated_request();
		add_action(
			'appkey_did_authenticate',
			static function() {
				$GLOBALS['gc_rest_appkey_uuid'] = 'invalid_uuid';
			}
		);

		$response = rest_do_request( '/gc/v2/users/me/appkeys/introspect' );
		$this->assertErrorResponse( 'rest_appkey_not_found', $response, 500 );
	}

	/**
	 * @ticket 53658
	 *
	 * @covers ::gc_is_appkeys_supported
	 */
	public function test_gc_is_appkeys_supported_with_https_only() {
		$_SERVER['HTTPS'] = 'on';
		$this->assertTrue( gc_is_appkeys_supported() );
	}

	/**
	 * @ticket 53658
	 *
	 * @covers ::gc_is_appkeys_supported
	 */
	public function test_gc_is_appkeys_supported_with_local_environment_only() {
		putenv( 'GC_ENVIRONMENT_TYPE=local' );

		$actual = gc_is_appkeys_supported();

		// Revert to default behaviour so that other tests are not affected.
		putenv( 'GC_ENVIRONMENT_TYPE' );

		$this->assertTrue( $actual );
	}

	/**
	 * @dataProvider data_gc_is_appkeys_available
	 *
	 * @ticket 53658
	 *
	 * @covers ::gc_is_appkeys_available
	 *
	 * @param bool|string $expected The expected value.
	 * @param string|null $callback Optional. The callback for the `gc_is_appkeys_available` hook.
	 *                              Default: null.
	 */
	public function test_gc_is_appkeys_available( $expected, $callback = null ) {
		remove_filter( 'gc_is_appkeys_available', '__return_true' );

		if ( $callback ) {
			add_filter( 'gc_is_appkeys_available', $callback );
		}

		if ( 'default' === $expected ) {
			putenv( 'GC_ENVIRONMENT_TYPE=local' );
			$expected = gc_is_appkeys_supported();
		}

		$actual = gc_is_appkeys_available();

		if ( 'default' === $expected ) {
			// Revert to default behaviour so that other tests are not affected.
			putenv( 'GC_ENVIRONMENT_TYPE' );
		}

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_gc_is_appkeys_available() {
		return array(
			'availability not forced'   => array(
				'expected' => 'default',
			),
			'availability forced true'  => array(
				'expected' => true,
				'callback' => '__return_true',
			),
			'availability forced false' => array(
				'expected' => false,
				'callback' => '__return_false',
			),
		);
	}

	/**
	 * Sets up a REST API request to be authenticated using an App Password.
	 *
	 * @since 5.7.0
	 *
	 * @return array The created App Password.
	 */
	private function setup_app_password_authenticated_request() {
		list( $password, $item ) = GC_AppKeys::create_new_appkey( self::$admin, array( 'name' => 'Test' ) );

		$_SERVER['PHP_AUTH_USER'] = get_userdata( self::$admin )->user_login;
		$_SERVER['PHP_AUTH_PW']   = $password;

		$GLOBALS['current_user'] = null;

		add_filter( 'appkey_is_api_request', '__return_true' );

		return $item;
	}
}
