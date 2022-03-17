<?php
/**
 * Unit tests covering GC_REST_Global_Styles_Controller functionality.
 *
 * @package GeChiUI
 * @subpackage REST API
 */

/**
 * @covers GC_REST_Global_Styles_Controller
 * @group restapi-global-styles
 * @group restapi
 */
class GC_REST_Global_Styles_Controller_Test extends GC_Test_REST_Controller_Testcase {
	/**
	 * @var int
	 */
	protected static $admin_id;

	/**
	 * @var int
	 */
	protected static $subscriber_id;

	/**
	 * @var int
	 */
	protected static $global_styles_id;

	/**
	 * @var int
	 */
	protected static $post_id;

	private function find_and_normalize_global_styles_by_id( $global_styles, $id ) {
		foreach ( $global_styles as $style ) {
			if ( $style['id'] === $id ) {
				unset( $style['_links'] );
				return $style;
			}
		}

		return null;
	}

	public function set_up() {
		parent::set_up();
		switch_theme( 'tt1-blocks' );
	}

	/**
	 * Create fake data before our tests run.
	 *
	 * @param GC_UnitTest_Factory $factory Helper that lets us create fake data.
	 */
	public static function gcSetupBeforeClass( $factory ) {
		self::$admin_id = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);

		self::$subscriber_id = $factory->user->create(
			array(
				'role' => 'subscriber',
			)
		);

		// This creates the global styles for the current theme.
		self::$global_styles_id = $factory->post->create(
			array(
				'post_content' => '{"version": ' . GC_Theme_JSON::LATEST_SCHEMA . ', "isGlobalStylesUserThemeJSON": true }',
				'post_status'  => 'publish',
				'post_title'   => 'Custom Styles',
				'post_type'    => 'gc_global_styles',
				'post_name'    => 'gc-global-styles-tt1-blocks',
				'tax_input'    => array(
					'gc_theme' => 'tt1-blocks',
				),
			)
		);

		self::$post_id = $factory->post->create();
	}

	/**
	 *
	 */
	public static function gcTearDownAfterClass() {
		self::delete_user( self::$admin_id );
		self::delete_user( self::$subscriber_id );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::register_routes
	 * @ticket 54596
	 */
	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey(
			'/gc/v2/global-styles/(?P<id>[\/\w-]+)',
			$routes,
			'Single global style based on the given ID route does not exist'
		);
		$this->assertCount(
			2,
			$routes['/gc/v2/global-styles/(?P<id>[\/\w-]+)'],
			'Single global style based on the given ID route does not have exactly two elements'
		);
		$this->assertArrayHasKey(
			'/gc/v2/global-styles/themes/(?P<stylesheet>[^\/:<>\*\?"\|]+(?:\/[^\/:<>\*\?"\|]+)?)',
			$routes,
			'Theme global styles route does not exist'
		);
		$this->assertCount(
			1,
			$routes['/gc/v2/global-styles/themes/(?P<stylesheet>[^\/:<>\*\?"\|]+(?:\/[^\/:<>\*\?"\|]+)?)'],
			'Theme global styles route does not have exactly one element'
		);
	}

	public function test_context_param() {
		$this->markTestSkipped( 'Controller does not implement context_param().' );
	}

	public function test_get_items() {
		$this->markTestSkipped( 'Controller does not implement get_items().' );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54516
	 */
	public function test_get_theme_item_no_user() {
		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/tt1-blocks' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_global_styles', $response, 401 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54516
	 */
	public function test_get_theme_item_permission_check() {
		gc_set_current_user( self::$subscriber_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/tt1-blocks' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_global_styles', $response, 403 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54516
	 */
	public function test_get_theme_item_invalid() {
		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/invalid' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_theme_not_found', $response, 404 );
	}

	/**
	 * @dataProvider data_get_theme_item_invalid_theme_dirname
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54596
	 *
	 * @param string $theme_dirname Theme directory to test.
	 * @param string $expected      Expected error code.
	 */
	public function test_get_theme_item_invalid_theme_dirname( $theme_dirname, $expected ) {
		gc_set_current_user( self::$admin_id );
		switch_theme( $theme_dirname );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/' . $theme_dirname );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( $expected, $response, 404 );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_get_theme_item_invalid_theme_dirname() {
		return array(
			'+'                      => array(
				'theme_dirname' => 'my+theme+',
				'expected'      => 'rest_theme_not_found',
			),
			':'                      => array(
				'theme_dirname' => 'my:theme:',
				'expected'      => 'rest_no_route',
			),
			'<>'                     => array(
				'theme_dirname' => 'my<theme>',
				'expected'      => 'rest_no_route',
			),
			'*'                      => array(
				'theme_dirname' => 'my*theme*',
				'expected'      => 'rest_no_route',
			),
			'?'                      => array(
				'theme_dirname' => 'my?theme?',
				'expected'      => 'rest_no_route',
			),
			'"'                      => array(
				'theme_dirname' => 'my"theme?"',
				'expected'      => 'rest_no_route',
			),
			'| (invalid on Windows)' => array(
				'theme_dirname' => 'my|theme|',
				'expected'      => 'rest_no_route',
			),
			// Themes deep in subdirectories.
			'2 subdirectories deep'  => array(
				'theme_dirname' => 'subdir/subsubdir/mytheme',
				'expected'      => 'rest_global_styles_not_found',
			),
		);
	}

	/**
	 * @dataProvider data_get_theme_item
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54596
	 *
	 * @param string $theme Theme directory to test.
	 */
	public function test_get_theme_item( $theme ) {
		gc_set_current_user( self::$admin_id );
		switch_theme( $theme );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/' . $theme );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$links    = $response->get_links();
		$this->assertArrayHasKey( 'settings', $data, 'Data does not have "settings" key' );
		$this->assertArrayHasKey( 'styles', $data, 'Data does not have "styles" key' );
		$this->assertArrayHasKey( 'self', $links, 'Links do not have a "self" key' );
		$this->assertStringContainsString( '/gc/v2/global-styles/themes/' . $theme, $links['self'][0]['href'] );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_get_theme_item() {
		return array(
			'alphabetic'                     => array( 'mytheme' ),
			'alphanumeric'                   => array( 'mythemev1' ),
			'àáâãäåæç'                       => array( 'àáâãäåæç' ),
			'space'                          => array( 'my theme' ),
			'-_.'                            => array( 'my_theme-0.1' ),
			'[]'                             => array( 'my[theme]' ),
			'()'                             => array( 'my(theme)' ),
			'{}'                             => array( 'my{theme}' ),
			'&=#@!$,^~%'                     => array( 'theme &=#@!$,^~%' ),
			'all combined'                   => array( 'thémé {}&=@!$,^~%[0.1](-_-)' ),

			// Themes in a subdirectory.
			'subdir: alphabetic'             => array( 'subdir/mytheme' ),
			'subdir: alphanumeric in theme'  => array( 'subdir/mythemev1' ),
			'subdir: alphanumeric in subdir' => array( 'subdirv1/mytheme' ),
			'subdir: alphanumeric in both'   => array( 'subdirv1/mythemev1' ),
			'subdir: àáâãäåæç in theme'      => array( 'subdir/àáâãäåæç' ),
			'subdir: àáâãäåæç in subdir'     => array( 'àáâãäåæç/mythemev1' ),
			'subdir: àáâãäåæç in both'       => array( 'àáâãäåæç/àáâãäåæç' ),
			'subdir: space in theme'         => array( 'subdir/my theme' ),
			'subdir: space in subdir'        => array( 'sub dir/mytheme' ),
			'subdir: space in both'          => array( 'sub dir/my theme' ),
			'subdir: -_. in theme'           => array( 'subdir/my_theme-0.1' ),
			'subdir: -_. in subdir'          => array( 'sub_dir-0.1/mytheme' ),
			'subdir: -_. in both'            => array( 'sub_dir-0.1/my_theme-0.1' ),
			'subdir: all combined in theme'  => array( 'subdir/thémé {}&=@!$,^~%[0.1](-_-)' ),
			'subdir: all combined in subdir' => array( 'sűbdīr {}&=@!$,^~%[0.1](-_-)/mytheme' ),
			'subdir: all combined in both'   => array( 'sűbdīr {}&=@!$,^~%[0.1](-_-)/thémé {}&=@!$,^~%[0.1](-_-)' ),
		);
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_theme_item
	 * @ticket 54595
	 */
	public function test_get_theme_item_fields() {
		gc_set_current_user( self::$admin_id );
		$request = new GC_REST_Request( 'GET', '/gc/v2/global-styles/themes/tt1-blocks' );
		$request->set_param( '_fields', 'settings' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertArrayHasKey( 'settings', $data );
		$this->assertArrayNotHasKey( 'styles', $data );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 * @ticket 54516
	 */
	public function test_get_item_no_user() {
		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_view', $response, 401 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 * @ticket 54516
	 */
	public function test_get_item_invalid_post() {
		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$post_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_global_styles_not_found', $response, 404 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 * @ticket 54516
	 */
	public function test_get_item_permission_check() {
		gc_set_current_user( self::$subscriber_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_view', $response, 403 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 * @ticket 54516
	 */
	public function test_get_item_no_user_edit() {
		gc_set_current_user( 0 );
		$request = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$global_styles_id );
		$request->set_param( 'context', 'edit' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_forbidden_context', $response, 401 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 * @ticket 54516
	 */
	public function test_get_item_permission_check_edit() {
		gc_set_current_user( self::$subscriber_id );
		$request = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$global_styles_id );
		$request->set_param( 'context', 'edit' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_forbidden_context', $response, 403 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item
	 */
	public function test_get_item() {
		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$links    = $response->get_links();

		$this->assertEquals(
			array(
				'id'       => self::$global_styles_id,
				'title'    => array(
					'raw'      => 'Custom Styles',
					'rendered' => 'Custom Styles',
				),
				'settings' => new stdClass(),
				'styles'   => new stdClass(),
			),
			$data
		);

		$this->assertArrayHasKey( 'self', $links );
		$this->assertStringContainsString( '/gc/v2/global-styles/' . self::$global_styles_id, $links['self'][0]['href'] );
	}

	public function test_create_item() {
		$this->markTestSkipped( 'Controller does not implement create_item().' );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::update_item
	 * @ticket 54516
	 */
	public function test_update_item() {
		gc_set_current_user( self::$admin_id );
		$request = new GC_REST_Request( 'PUT', '/gc/v2/global-styles/' . self::$global_styles_id );
		$request->set_body_params(
			array(
				'title' => 'My new global styles title',
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertEquals( 'My new global styles title', $data['title']['raw'] );
	}


	/**
	 * @covers GC_REST_Global_Styles_Controller::update_item
	 * @ticket 54516
	 */
	public function test_update_item_no_user() {
		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'PUT', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_edit', $response, 401 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::update_item
	 * @ticket 54516
	 */
	public function test_update_item_invalid_post() {
		gc_set_current_user( self::$admin_id );
		$request  = new GC_REST_Request( 'PUT', '/gc/v2/global-styles/' . self::$post_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_global_styles_not_found', $response, 404 );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::update_item
	 * @ticket 54516
	 */
	public function test_update_item_permission_check() {
		gc_set_current_user( self::$subscriber_id );
		$request  = new GC_REST_Request( 'PUT', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_edit', $response, 403 );
	}

	public function test_delete_item() {
		$this->markTestSkipped( 'Controller does not implement delete_item().' );
	}

	public function test_prepare_item() {
		$this->markTestSkipped( 'Controller does not implement prepare_item().' );
	}

	/**
	 * @covers GC_REST_Global_Styles_Controller::get_item_schema
	 * @ticket 54516
	 */
	public function test_get_item_schema() {
		$request    = new GC_REST_Request( 'OPTIONS', '/gc/v2/global-styles/' . self::$global_styles_id );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertCount( 4, $properties, 'Schema properties array does not have exactly 4 elements' );
		$this->assertArrayHasKey( 'id', $properties, 'Schema properties array does not have "id" key' );
		$this->assertArrayHasKey( 'styles', $properties, 'Schema properties array does not have "styles" key' );
		$this->assertArrayHasKey( 'settings', $properties, 'Schema properties array does not have "settings" key' );
		$this->assertArrayHasKey( 'title', $properties, 'Schema properties array does not have "title" key' );
	}
}
