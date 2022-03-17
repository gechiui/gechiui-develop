<?php
/**
 * Unit tests covering GC_REST_Widgets_Controller_Test functionality.
 *
 * @package GeChiUI
 * @subpackage REST_API
 * @since 5.8.0
 */

/**
 * Tests for REST API for Widgets.
 *
 * @since 5.8.0
 *
 * @see GC_Test_REST_Controller_Testcase
 * @group restapi
 * @covers GC_REST_Widgets_Controller
 */
class GC_Test_REST_Widgets_Controller extends GC_Test_REST_Controller_Testcase {
	/**
	 * @var int
	 */
	public $menu_id;

	/**
	 * @var int
	 */
	protected static $superadmin_id;

	/**
	 * @var int
	 */
	protected static $admin_id;

	/**
	 * @var int
	 */
	protected static $admin_id_without_unfiltered_html;

	/**
	 * @var int
	 */
	protected static $editor_id;

	/**
	 * @var int
	 */
	protected static $subscriber_id;

	/**
	 * @var int
	 */
	protected static $author_id;

	/**
	 * @var int
	 */
	protected static $per_page = 50;

	/**
	 * Create fake data before our tests run.
	 *
	 * @param GC_UnitTest_Factory $factory Helper that lets us create fake data.
	 */
	public static function gcSetUpBeforeClass( $factory ) {
		self::$superadmin_id = $factory->user->create(
			array(
				'role'       => 'administrator',
				'user_login' => 'superadmin',
			)
		);
		if ( is_multisite() ) {
			update_site_option( 'site_admins', array( 'superadmin' ) );
		}
		self::$admin_id      = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);
		self::$editor_id     = $factory->user->create(
			array(
				'role' => 'editor',
			)
		);
		self::$author_id     = $factory->user->create(
			array(
				'role' => 'author',
			)
		);
		self::$subscriber_id = $factory->user->create(
			array(
				'role' => 'subscriber',
			)
		);
	}

	public function set_up() {
		global $gc_widget_factory;

		parent::set_up();

		gc_set_current_user( self::$admin_id );

		// Re-register core widgets.
		gc_widgets_init();

		// Register a non-multi widget for testing.
		gc_register_widget_control(
			'testwidget',
			'GC test widget',
			static function () {
				// check if anything's been sent.
				if ( isset( $_POST['update_testwidget'] ) ) {
					$settings = get_option( 'widget_testwidget', array() );

					$settings['id']    = $_POST['test_id'];
					$settings['title'] = $_POST['test_title'];

					update_option( 'widget_testwidget', $settings );
				}

				echo 'GC test widget form';
			},
			100,
			200
		);
		gc_register_sidebar_widget(
			'testwidget',
			'GC test widget',
			static function () {
				$settings = gc_parse_args(
					get_option( 'widget_testwidget', array() ),
					array(
						'id'    => 'Default id',
						'title' => 'Default text',
					)
				);
				echo '<h1>' . $settings['id'] . '</h1><span>' . $settings['title'] . '</span>';
			},
			array(
				'description' => 'A non-multi widget for testing.',
			)
		);
	}

	public function clean_up_global_scope() {
		global
			$gc_widget_factory,
			$gc_registered_sidebars,
			$gc_registered_widgets,
			$gc_registered_widget_controls,
			$gc_registered_widget_updates,
			$_gc_sidebars_widgets;

		$gc_registered_sidebars        = array();
		$gc_registered_widgets         = array();
		$gc_registered_widget_controls = array();
		$gc_registered_widget_updates  = array();
		$gc_widget_factory->widgets    = array();
		$_gc_sidebars_widgets          = array();

		update_option( 'sidebars_widgets', array() );

		parent::clean_up_global_scope();
	}

	private function setup_widget( $id_base, $number, $settings ) {
		$this->setup_widgets( $id_base, array( $number => $settings ) );
	}

	private function setup_widgets( $id_base, $settings ) {
		global $gc_widget_factory;

		$option_name = "widget_$id_base";
		update_option( $option_name, $settings );

		$widget_object = $gc_widget_factory->get_widget_object( $id_base );
		foreach ( array_keys( $settings ) as $number ) {
			$widget_object->_set( $number );
			$widget_object->_register_one( $number );
		}
	}

	private function setup_sidebar( $id, $attrs = array(), $widgets = array() ) {
		global $gc_registered_sidebars;
		update_option(
			'sidebars_widgets',
			array_merge(
				(array) get_option( 'sidebars_widgets', array() ),
				array(
					$id => $widgets,
				)
			)
		);
		$gc_registered_sidebars[ $id ] = array_merge(
			array(
				'id'            => $id,
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
			),
			$attrs
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey( '/gc/v2/widgets', $routes );
		$this->assertArrayHasKey( '/gc/v2/widgets/(?P<id>[\w\-]+)', $routes );
	}

	/**
	 * @ticket 41683
	 */
	public function test_context_param() {
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_items_no_widgets() {
		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( array(), $data );
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_items_no_permission() {
		gc_set_current_user( 0 );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 401 );
	}

	/**
	 * @ticket 53915
	 */
	public function test_get_items_no_permission_show_in_rest() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name'         => 'Test sidebar',
				'show_in_rest' => true,
			),
			array( 'text-1', 'testwidget' )
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = $this->remove_links( $data );
		$this->assertSameIgnoreEOL(
			array(
				array(
					'id'       => 'text-1',
					'id_base'  => 'text',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<div class="textwidget">Custom text test</div>',
				),
				array(
					'id'       => 'testwidget',
					'id_base'  => 'testwidget',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<h1>Default id</h1><span>Default text</span>',
				),
			),
			$data
		);
	}

	/**
	 * @ticket 53915
	 */
	public function test_get_items_without_show_in_rest_are_removed_from_the_list() {
		gc_set_current_user( self::$author_id );
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name'         => 'Test sidebar 1',
				'show_in_rest' => true,
			),
			array( 'text-1', 'testwidget' )
		);
		$this->setup_sidebar(
			'sidebar-2',
			array(
				'name'         => 'Test sidebar 2',
				'show_in_rest' => false,
			),
			array( 'text-1', 'testwidget' )
		);
		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = $this->remove_links( $data );
		$this->assertSameIgnoreEOL(
			array(
				array(
					'id'       => 'text-1',
					'id_base'  => 'text',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<div class="textwidget">Custom text test</div>',
				),
				array(
					'id'       => 'testwidget',
					'id_base'  => 'testwidget',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<h1>Default id</h1><span>Default text</span>',
				),
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_items_wrong_permission_author() {
		gc_set_current_user( self::$author_id );
		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 403 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_items() {
		add_filter( 'pre_http_request', array( $this, 'mocked_rss_response' ) );
		global $gc_widget_factory;

		$gc_widget_factory->widgets['GC_Widget_RSS']->widget_options['show_instance_in_rest'] = false;

		$block_content = '<!-- gc:paragraph --><p>Block test</p><!-- /gc:paragraph -->';

		$this->setup_widget(
			'rss',
			1,
			array(
				'title' => 'RSS test',
				'url'   => 'https://www.gechiui.com/news/feed',
			)
		);
		$this->setup_widget(
			'block',
			1,
			array(
				'content' => $block_content,
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'block-1', 'rss-1', 'testwidget' )
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = $this->remove_links( $data );
		$this->assertSameSets(
			array(
				array(
					'id'       => 'block-1',
					'id_base'  => 'block',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<p>Block test</p>',
				),
				array(
					'id'       => 'rss-1',
					'id_base'  => 'rss',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<a class="rsswidget rss-widget-feed" href="https://www.gechiui.com/news/feed"><img class="rss-widget-icon" style="border:0" width="14" height="14" src="http://example.org/gc-includes/images/rss.png" alt="RSS" loading="lazy" /></a> <a class="rsswidget rss-widget-title" href="https://www.gechiui.com/news">RSS test</a><ul><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/12/introducing-learn-gechiui/\'>Introducing Learn GeChiUI</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/12/simone/\'>GeChiUI 5.6 “Simone”</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/12/state-of-the-word-2020/\'>State of the Word 2020</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/12/the-month-in-gechiui-november-2020/\'>The Month in GeChiUI: November 2020</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/12/gechiui-5-6-release-candidate-2/\'>GeChiUI 5.6 Release Candidate 2</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/11/gechiui-5-6-release-candidate/\'>GeChiUI 5.6 Release Candidate</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/11/gechiui-5-6-beta-4/\'>GeChiUI 5.6 Beta 4</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/11/gechiui-5-6-beta-3/\'>GeChiUI 5.6 Beta 3</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/11/the-month-in-gechiui-october-2020/\'>The Month in GeChiUI: October 2020</a></li><li><a class=\'rsswidget\' href=\'https://www.gechiui.com/news/2020/10/gechiui-5-5-3-maintenance-release/\'>GeChiUI 5.5.3 Maintenance Release</a></li></ul>',
				),
				array(
					'id'       => 'testwidget',
					'id_base'  => 'testwidget',
					'sidebar'  => 'sidebar-1',
					'rendered' => '<h1>Default id</h1><span>Default text</span>',
				),
			),
			$data
		);

		$gc_widget_factory->widgets['GC_Widget_RSS']->widget_options['show_instance_in_rest'] = true;
	}

	public function mocked_rss_response() {
		$single_value_headers = array(
			'content-type' => 'application/rss+xml; charset=UTF-8',
			'link'         => '<https://www.gechiui.com/news/gc-json/>; rel="https://api.w.org/"',
		);

		return array(
			'headers'  => new Requests_Utility_CaseInsensitiveDictionary( $single_value_headers ),
			'body'     => file_get_contents( DIR_TESTDATA . '/feed/gechiui-org-news.xml' ),
			'response' => array(
				'code'    => 200,
				'message' => 'OK',
			),
			'cookies'  => array(),
			'filename' => null,
		);
	}

	/**
	 * Test a GET request in edit context. In particular, we expect rendered_form to be served correctly.
	 *
	 * @ticket 41683
	 */
	public function test_get_items_edit_context() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'testwidget' )
		);

		$request            = new GC_REST_Request( 'GET', '/gc/v2/widgets' );
		$request['context'] = 'edit';
		$response           = rest_get_server()->dispatch( $request );
		$data               = $response->get_data();
		$data               = $this->remove_links( $data );
		$this->assertSameIgnoreEOL(
			array(
				array(
					'id'            => 'text-1',
					'id_base'       => 'text',
					'sidebar'       => 'sidebar-1',
					'rendered'      => '<div class="textwidget">Custom text test</div>',
					'rendered_form' => '<input id="widget-text-1-title" name="widget-text[1][title]" class="title sync-input" type="hidden" value="">' . "\n" .
									'			<textarea id="widget-text-1-text" name="widget-text[1][text]" class="text sync-input" hidden>Custom text test</textarea>' . "\n" .
									'			<input id="widget-text-1-filter" name="widget-text[1][filter]" class="filter sync-input" type="hidden" value="on">' . "\n" .
									'			<input id="widget-text-1-visual" name="widget-text[1][visual]" class="visual sync-input" type="hidden" value="on">',
					'instance'      => array(
						'encoded' => base64_encode(
							serialize(
								array(
									'text' => 'Custom text test',
								)
							)
						),
						'hash'    => gc_hash(
							serialize(
								array(
									'text' => 'Custom text test',
								)
							)
						),
						'raw'     => array(
							'text' => 'Custom text test',
						),
					),
				),
				array(
					'id'            => 'testwidget',
					'id_base'       => 'testwidget',
					'sidebar'       => 'sidebar-1',
					'rendered'      => '<h1>Default id</h1><span>Default text</span>',
					'rendered_form' => 'GC test widget form',
					'instance'      => null,
				),
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_item() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1' )
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets/text-1' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSameSets(
			array(
				'id'       => 'text-1',
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'rendered' => '<div class="textwidget">Custom text test</div>',
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_item_no_permission() {
		gc_set_current_user( 0 );

		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1' )
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets/text-1' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 401 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_item_wrong_permission_author() {
		gc_set_current_user( self::$author_id );
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets/text-1' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 403 );
	}

	/**
	 * @ticket 53915
	 */
	public function test_get_item_no_permission_show_in_rest() {
		gc_set_current_user( 0 );

		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name'         => 'Test sidebar',
				'show_in_rest' => true,
			),
			array( 'text-1' )
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/widgets/text-1' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSameSets(
			array(
				'id'       => 'text-1',
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'rendered' => '<div class="textwidget">Custom text test</div>',
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'encoded' => base64_encode(
						serialize(
							array(
								'text' => 'Updated text test',
							)
						)
					),
					'hash'    => gc_hash(
						serialize(
							array(
								'text' => 'Updated text test',
							)
						)
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'text-2', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated text test',
				'title'  => '',
				'filter' => false,
			),
			get_option( 'widget_text' )[2]
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_malformed_instance() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'encoded' => base64_encode(
						serialize(
							array(
								'text' => 'Updated text test',
							)
						)
					),
					'hash'    => 'badhash',
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_invalid_widget', $response, 400 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_bad_instance() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_invalid_widget', $response, 400 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_using_raw_instance() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'block',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array(
						'content' => '<!-- gc:paragraph --><p>Block test</p><!-- /gc:paragraph -->',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'block-7', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSets(
			array(
				'content' => '<!-- gc:paragraph --><p>Block test</p><!-- /gc:paragraph -->',
			),
			get_option( 'widget_block' )[7]
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_raw_instance_not_supported() {
		global $gc_widget_factory;

		$gc_widget_factory->widgets['GC_Widget_Text']->widget_options['show_instance_in_rest'] = false;

		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array(
						'title' => 'Updated text test',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_invalid_widget', $response, 400 );

		$gc_widget_factory->widgets['GC_Widget_Text']->widget_options['show_instance_in_rest'] = true;
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_using_form_data() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'   => 'text',
				'sidebar'   => 'sidebar-1',
				'form_data' => 'widget-text[2][text]=Updated+text+test',
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'text-2', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated text test',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_multiple_in_a_row() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array( 'text' => 'Text 1' ),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'text-2', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Text 1',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array( 'text' => 'Text 2' ),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'text-3', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Text 2',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);

		$sidebar = rest_do_request( '/gc/v2/sidebars/sidebar-1' );
		$this->assertContains( 'text-2', $sidebar->get_data()['widgets'] );
		$this->assertContains( 'text-3', $sidebar->get_data()['widgets'] );
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_second_instance() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			)
		);

		$request = new GC_REST_Request( 'POST', '/gc/v2/widgets' );
		$request->set_body_params(
			array(
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array(
						'text' => 'Updated text test',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertSame( 'text-2', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated text test',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/text-1' );
		$request->set_body_params(
			array(
				'id'       => 'text-1',
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array(
						'text' => 'Updated text test',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( 'text-1', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated text test',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item_reassign_sidebar() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);
		$this->setup_sidebar(
			'sidebar-2',
			array(
				'name' => 'Test sidebar',
			),
			array()
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/text-1' );
		$request->set_body_params(
			array(
				'sidebar' => 'sidebar-2',
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$error    = $response->as_error();
		$this->assertNotWPError( $error, $error ? $error->get_error_message() : '' );
		$this->assertSame( 'sidebar-2', $response->get_data()['sidebar'] );

		$sidebar1 = rest_do_request( '/gc/v2/sidebars/sidebar-1' );
		$this->assertNotContains( 'text-1', $sidebar1->get_data()['widgets'] );

		$sidebar2 = rest_do_request( '/gc/v2/sidebars/sidebar-2' );
		$this->assertContains( 'text-1', $sidebar2->get_data()['widgets'] );
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item_shouldnt_require_id_base() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/text-1' );
		$request->set_body_params(
			array(
				'id'       => 'text-1',
				'instance' => array(
					'raw' => array(
						'text' => 'Updated text test',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( 'text-1', $data['id'] );
		$this->assertSame( 'sidebar-1', $data['sidebar'] );
		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated text test',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);
	}

	/**
	 * @group multisite
	 */
	public function test_store_html_as_admin() {
		if ( is_multisite() ) {
			$this->assertSame(
				'<div class="textwidget">alert(1)</div>',
				$this->update_text_widget_with_raw_html( '<script>alert(1)</script>' )
			);
		} else {
			$this->assertSame(
				'<div class="textwidget"><script>alert(1)</script></div>',
				$this->update_text_widget_with_raw_html( '<script>alert(1)</script>' )
			);
		}
	}

	/**
	 * @group multisite
	 */
	public function test_store_html_as_superadmin() {
		gc_set_current_user( self::$superadmin_id );
		if ( is_multisite() ) {
			$this->assertSame(
				'<div class="textwidget"><script>alert(1)</script></div>',
				$this->update_text_widget_with_raw_html( '<script>alert(1)</script>' )
			);
		} else {
			$this->assertSame(
				'<div class="textwidget"><script>alert(1)</script></div>',
				$this->update_text_widget_with_raw_html( '<script>alert(1)</script>' )
			);
		}
	}

	protected function update_text_widget_with_raw_html( $html ) {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1' )
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/text-1' );
		$request->set_body_params(
			array(
				'id'       => 'text-1',
				'id_base'  => 'text',
				'instance' => array(
					'raw' => array(
						'text' => $html,
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		return $data['rendered'];
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item_legacy_widget() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'testwidget' )
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/testwidget' );
		$request->set_body_params(
			array(
				'id'        => 'testwidget',
				'name'      => 'GC test widget',
				'form_data' => 'test_id=My+test+id&test_title=My+test+title&update_testwidget=true',
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = $this->remove_links( $data );
		$this->assertSame(
			array(
				'id'            => 'testwidget',
				'id_base'       => 'testwidget',
				'sidebar'       => 'sidebar-1',
				'rendered'      => '<h1>My test id</h1><span>My test title</span>',
				'rendered_form' => 'GC test widget form',
				'instance'      => null,
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_create_item_legacy_widget() {
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array()
		);

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/testwidget' );
		$request->set_body_params(
			array(
				'id'        => 'testwidget',
				'sidebar'   => 'sidebar-1',
				'name'      => 'GC test widget',
				'form_data' => 'test_id=My+test+id&test_title=My+test+title&update_testwidget=true',
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$data     = $this->remove_links( $data );
		$this->assertSame(
			array(
				'id'            => 'testwidget',
				'id_base'       => 'testwidget',
				'sidebar'       => 'sidebar-1',
				'rendered'      => '<h1>My test id</h1><span>My test title</span>',
				'rendered_form' => 'GC test widget form',
				'instance'      => null,
			),
			$data
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item_no_permission() {
		gc_set_current_user( 0 );

		$request = new GC_REST_Request( 'PUT', '/gc/v2/sidebars/sidebar-1' );
		$request->set_body_params(
			array(
				'widgets' => array(),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 401 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_update_item_wrong_permission_author() {
		gc_set_current_user( self::$author_id );

		$request = new GC_REST_Request( 'PUT', '/gc/v2/sidebars/sidebar-1' );
		$request->set_body_params(
			array(
				'widgets' => array(),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 403 );
	}

	/**
	 * Tests if the endpoint correctly handles "slashable" characters such as " or '.
	 */
	public function test_update_item_slashing() {
		$this->setup_widget( 'text', 1, array( 'text' => 'Custom text test' ) );
		$this->setup_sidebar( 'sidebar-1', array( 'name' => 'Test sidebar' ), array( 'text-1', 'rss-1' ) );

		$request = new GC_REST_Request( 'PUT', '/gc/v2/widgets/text-1' );
		$request->set_body_params(
			array(
				'id'       => 'text-1',
				'id_base'  => 'text',
				'sidebar'  => 'sidebar-1',
				'instance' => array(
					'raw' => array(
						'text' => 'Updated \\" \\\' text test',
					),
				),
			)
		);
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSameSetsWithIndex(
			array(
				'text'   => 'Updated \\" \\\' text test',
				'title'  => '',
				'filter' => false,
			),
			$data['instance']['raw']
		);

		$this->assertSame(
			'<div class="textwidget">Updated \\" \\\' text test</div>',
			$data['rendered']
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_delete_item() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/widgets/text-1' );
		$response = rest_do_request( $request );

		$this->assertSameIgnoreEOL(
			array(
				'id'            => 'text-1',
				'id_base'       => 'text',
				'sidebar'       => 'gc_inactive_widgets',
				'rendered'      => '',
				'rendered_form' => '<input id="widget-text-1-title" name="widget-text[1][title]" class="title sync-input" type="hidden" value="">' . "\n" .
								'			<textarea id="widget-text-1-text" name="widget-text[1][text]" class="text sync-input" hidden>Custom text test</textarea>' . "\n" .
								'			<input id="widget-text-1-filter" name="widget-text[1][filter]" class="filter sync-input" type="hidden" value="on">' . "\n" .
								'			<input id="widget-text-1-visual" name="widget-text[1][visual]" class="visual sync-input" type="hidden" value="on">',
				'instance'      => array(
					'encoded' => base64_encode(
						serialize(
							array(
								'text' => 'Custom text test',
							)
						)
					),
					'hash'    => gc_hash(
						serialize(
							array(
								'text' => 'Custom text test',
							)
						)
					),
					'raw'     => array(
						'text' => 'Custom text test',
					),
				),
			),
			$response->get_data()
		);
	}

	/**
	 * @ticket 41683
	 */
	public function test_delete_item_force() {
		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request = new GC_REST_Request( 'DELETE', '/gc/v2/widgets/text-1' );
		$request->set_query_params( array( 'force' => true ) );
		$response = rest_do_request( $request );

		$this->assertSameIgnoreEOL(
			array(
				'deleted'  => true,
				'previous' => array(

					'id'            => 'text-1',
					'id_base'       => 'text',
					'sidebar'       => 'sidebar-1',
					'rendered'      => '<div class="textwidget">Custom text test</div>',
					'rendered_form' => '<input id="widget-text-1-title" name="widget-text[1][title]" class="title sync-input" type="hidden" value="">' . "\n" .
									'			<textarea id="widget-text-1-text" name="widget-text[1][text]" class="text sync-input" hidden>Custom text test</textarea>' . "\n" .
									'			<input id="widget-text-1-filter" name="widget-text[1][filter]" class="filter sync-input" type="hidden" value="on">' . "\n" .
									'			<input id="widget-text-1-visual" name="widget-text[1][visual]" class="visual sync-input" type="hidden" value="on">',
					'instance'      => array(
						'encoded' => base64_encode(
							serialize(
								array(
									'text' => 'Custom text test',
								)
							)
						),
						'hash'    => gc_hash(
							serialize(
								array(
									'text' => 'Custom text test',
								)
							)
						),
						'raw'     => array(
							'text' => 'Custom text test',
						),
					),

				),
			),
			$response->get_data()
		);

		$response = rest_do_request( '/gc/v2/widgets/text-1' );
		$this->assertSame( 404, $response->get_status() );

		$this->assertArrayNotHasKey( 'text-1', get_option( 'sidebars_widgets' )['sidebar-1'] );
		$this->assertArrayNotHasKey( 1, get_option( 'widget_text' ) );
	}

	/**
	 * @ticket 41683
	 */
	public function test_delete_item_logged_out() {
		gc_set_current_user( 0 );

		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/widgets/text-1' );
		$response = rest_do_request( $request );

		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 401 );
	}

	/**
	 * @ticket 41683
	 */
	public function test_delete_item_author() {
		gc_set_current_user( self::$author_id );

		$this->setup_widget(
			'text',
			1,
			array(
				'text' => 'Custom text test',
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-1', 'rss-1' )
		);

		$request  = new GC_REST_Request( 'DELETE', '/gc/v2/widgets/text-1' );
		$response = rest_do_request( $request );

		$this->assertErrorResponse( 'rest_cannot_manage_widgets', $response, 403 );
	}

	/**
	 * @ticket 53557
	 */
	public function test_delete_item_multiple() {
		$this->setup_widgets(
			'text',
			array(
				2 => array( 'text' => 'Text widget' ),
				3 => array( 'text' => 'Text widget' ),
				4 => array( 'text' => 'Text widget' ),
			)
		);
		$this->setup_sidebar(
			'sidebar-1',
			array(
				'name' => 'Test sidebar',
			),
			array( 'text-2', 'text-3', 'text-4' )
		);

		$request = new GC_REST_Request( 'POST', '/batch/v1' );
		$request->set_body_params(
			array(
				'requests' => array(
					array(
						'method' => 'DELETE',
						'path'   => '/gc/v2/widgets/text-2?force=1',
					),
					array(
						'method' => 'DELETE',
						'path'   => '/gc/v2/widgets/text-3?force=1',
					),
					array(
						'method' => 'DELETE',
						'path'   => '/gc/v2/widgets/text-4?force=1',
					),
				),
			)
		);
		$response = rest_do_request( $request );

		$this->assertSame(
			array(
				'sidebar-1' => array(),
			),
			gc_get_sidebars_widgets()
		);
		$this->assertSame(
			array(
				'_multiwidget' => 1,
			),
			get_option( 'widget_text' )
		);
	}

	/**
	 * The test_prepare_item() method does not exist for sidebar.
	 */
	public function test_prepare_item() {
	}

	/**
	 * @ticket 41683
	 */
	public function test_get_item_schema() {
		gc_set_current_user( self::$admin_id );
		$request    = new GC_REST_Request( 'OPTIONS', '/gc/v2/widgets' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];

		$this->assertSame( array( 'v1' => true ), $data['endpoints'][0]['allow_batch'] );

		$this->assertCount( 7, $properties );
		$this->assertArrayHasKey( 'id', $properties );
		$this->assertArrayHasKey( 'id_base', $properties );
		$this->assertArrayHasKey( 'sidebar', $properties );
		$this->assertArrayHasKey( 'rendered', $properties );
		$this->assertArrayHasKey( 'rendered_form', $properties );
		$this->assertArrayHasKey( 'instance', $properties );
		$this->assertArrayHasKey( 'form_data', $properties );
	}

	/**
	 * Helper to remove links key.
	 *
	 * @param array $data Array of data.
	 *
	 * @return array
	 */
	protected function remove_links( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}
		$count = 0;
		foreach ( $data as $item ) {
			if ( is_array( $item ) && isset( $item['_links'] ) ) {
				unset( $data[ $count ]['_links'] );
			}
			$count ++;
		}

		return $data;
	}
}
