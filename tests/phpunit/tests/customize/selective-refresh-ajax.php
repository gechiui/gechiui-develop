<?php
/**
 * GC_Customize_Selective_Refresh Ajax tests.
 *
 * @package    GeChiUI
 * @subpackage UnitTests
 */

/**
 * Tests for the GC_Customize_Selective_Refresh class Ajax.
 *
 * Note that this is intentionally not extending GC_Ajax_UnitTestCase because it
 * is not admin ajax.
 *
 * @since      4.5.0
 * @group      ajax
 */
class Test_GC_Customize_Selective_Refresh_Ajax extends GC_UnitTestCase {

	/**
	 * Manager.
	 *
	 * @var GC_Customize_Manager
	 */
	public $gc_customize;

	/**
	 * Component.
	 *
	 * @var GC_Customize_Selective_Refresh
	 */
	public $selective_refresh;

	/**
	 * Set up the test fixture.
	 */
	public function set_up() {
		parent::set_up();

		// Define gc_doing_ajax so that gc_die() will be used instead of die().
		add_filter( 'gc_doing_ajax', '__return_true' );
		add_filter( 'gc_die_ajax_handler', array( $this, 'get_gc_die_handler' ), 1, 1 );

		require_once ABSPATH . GCINC . '/class-gc-customize-manager.php';
		$GLOBALS['gc_customize'] = new GC_Customize_Manager();
		$this->gc_customize      = $GLOBALS['gc_customize'];
		if ( isset( $this->gc_customize->selective_refresh ) ) {
			$this->selective_refresh = $this->gc_customize->selective_refresh;
		}
	}

	/**
	 * Do Customizer boot actions.
	 */
	private function do_customize_boot_actions() {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		do_action( 'setup_theme' );
		do_action( 'after_setup_theme' );
		do_action( 'init' );
		do_action( 'customize_register', $this->gc_customize );
		$this->gc_customize->customize_preview_init();
		do_action( 'gc', $GLOBALS['gc'] );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request().
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_for_unauthenticated_user() {
		$_POST[ GC_Customize_Selective_Refresh::RENDER_QUERY_VAR ] = '1';

		// Check current_user_cannot_customize.
		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			unset( $e );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertFalse( $output['success'] );
		$this->assertSame( 'expected_customize_preview', $output['data'] );

		// Check expected_customize_preview.
		gc_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$_REQUEST['nonce'] = gc_create_nonce( 'preview-customize_' . $this->gc_customize->theme()->get_stylesheet() );
		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			unset( $e );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertFalse( $output['success'] );
		$this->assertSame( 'expected_customize_preview', $output['data'] );

		// Check missing_partials.
		$this->do_customize_boot_actions();
		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			unset( $e );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertFalse( $output['success'] );
		$this->assertSame( 'missing_partials', $output['data'] );

		// Check missing_partials.
		$_POST['partials'] = 'bad';
		$this->do_customize_boot_actions();
		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertFalse( $output['success'] );
		$this->assertSame( 'malformed_partials', $output['data'] );
	}

	/**
	 * Set the current user to be an admin, add the preview nonce, and set the query var.
	 */
	private function setup_valid_render_partials_request_environment() {
		gc_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$_REQUEST['nonce'] = gc_create_nonce( 'preview-customize_' . $this->gc_customize->theme()->get_stylesheet() );
		$_POST[ GC_Customize_Selective_Refresh::RENDER_QUERY_VAR ] = '1';
		$this->do_customize_boot_actions();
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() for an unrecognized partial.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_for_unrecognized_partial() {
		$this->setup_valid_render_partials_request_environment();
		$context_data = array();
		$placements   = array( $context_data );

		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'foo' => $placements,
				)
			)
		);

		ob_start();
		try {
			$this->expected_partial_ids = array( 'foo' );
			add_filter( 'customize_render_partials_response', array( $this, 'filter_customize_render_partials_response' ), 10, 3 );
			add_action( 'customize_render_partials_before', array( $this, 'handle_action_customize_render_partials_before' ), 10, 2 );
			add_action( 'customize_render_partials_after', array( $this, 'handle_action_customize_render_partials_after' ), 10, 2 );
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertTrue( $output['success'] );
		$this->assertIsArray( $output['data'] );
		$this->assertArrayHasKey( 'contents', $output['data'] );
		$this->assertArrayHasKey( 'errors', $output['data'] );
		$this->assertArrayHasKey( 'foo', $output['data']['contents'] );
		$this->assertNull( $output['data']['contents']['foo'] );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() for a partial that does not render.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_for_non_rendering_partial() {
		$this->setup_valid_render_partials_request_environment();
		gc_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$this->gc_customize->add_setting( 'home' );
		$this->gc_customize->selective_refresh->add_partial( 'foo', array( 'settings' => array( 'home' ) ) );
		$context_data = array();
		$placements   = array( $context_data );

		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'foo' => $placements,
				)
			)
		);

		$count_customize_render_partials_before = has_action( 'customize_render_partials_before' );
		$count_customize_render_partials_after  = has_action( 'customize_render_partials_after' );
		ob_start();
		try {
			$this->expected_partial_ids = array( 'foo' );
			add_filter( 'customize_render_partials_response', array( $this, 'filter_customize_render_partials_response' ), 10, 3 );
			add_action( 'customize_render_partials_before', array( $this, 'handle_action_customize_render_partials_before' ), 10, 2 );
			add_action( 'customize_render_partials_after', array( $this, 'handle_action_customize_render_partials_after' ), 10, 2 );
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$this->assertEquals( $count_customize_render_partials_before + 1, has_action( 'customize_render_partials_before' ) );
		$this->assertEquals( $count_customize_render_partials_after + 1, has_action( 'customize_render_partials_after' ) );
		$output = json_decode( ob_get_clean(), true );
		$this->assertSame( array( false ), $output['data']['contents']['foo'] );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() for a partial the user doesn't have the capability to edit.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_rendering_disallowed_partial() {
		$this->setup_valid_render_partials_request_environment();
		gc_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$this->gc_customize->add_setting(
			'secret_message',
			array(
				'capability' => 'top_secret_clearance',
			)
		);
		$this->gc_customize->selective_refresh->add_partial( 'secret_message', array( 'settings' => 'secret_message' ) );

		$context_data      = array();
		$placements        = array( $context_data );
		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'secret_message' => $placements,
				)
			)
		);

		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertNull( $output['data']['contents']['secret_message'] );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() for a partial for which an associated setting does not exist.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_rendering_partial_with_missing_settings() {
		$this->setup_valid_render_partials_request_environment();
		gc_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$this->gc_customize->selective_refresh->add_partial( 'bar', array( 'settings' => 'bar' ) );

		$context_data      = array();
		$placements        = array( $context_data );
		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'bar' => $placements,
				)
			)
		);

		ob_start();
		try {
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$output = json_decode( ob_get_clean(), true );
		$this->assertNull( $output['data']['contents']['bar'] );
	}

	/**
	 * Get the rendered blogname.
	 *
	 * @param GC_Customize_Partial $partial Partial.
	 * @param array                $context Context data.
	 * @return string
	 */
	public function render_callback_blogname( $partial, $context ) {
		$this->assertIsArray( $context );
		$this->assertInstanceOf( 'GC_Customize_Partial', $partial );
		return get_bloginfo( 'name', 'display' );
	}

	/**
	 * Get the rendered blogdescription.
	 *
	 * @param GC_Customize_Partial $partial Partial.
	 * @param array                $context Context data.
	 * @return string
	 */
	public function render_callback_blogdescription( $partial, $context ) {
		$this->assertIsArray( $context );
		$this->assertInstanceOf( 'GC_Customize_Partial', $partial );
		$x = get_bloginfo( 'description', 'display' );
		return $x;
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() for a partial that does render.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_with_single_valid_placement() {
		$this->setup_valid_render_partials_request_environment();

		$this->gc_customize->selective_refresh->add_partial(
			'test_blogname',
			array(
				'settings'        => array( 'blogname' ),
				'render_callback' => array( $this, 'render_callback_blogname' ),
			)
		);

		$context_data = array();
		$placements   = array( $context_data );

		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'test_blogname' => $placements,
				)
			)
		);

		$count_customize_render_partials_before = has_action( 'customize_render_partials_before' );
		$count_customize_render_partials_after  = has_action( 'customize_render_partials_after' );
		ob_start();
		try {
			$this->expected_partial_ids = array( 'test_blogname' );
			add_filter( 'customize_render_partials_response', array( $this, 'filter_customize_render_partials_response' ), 10, 3 );
			add_action( 'customize_render_partials_before', array( $this, 'handle_action_customize_render_partials_before' ), 10, 2 );
			add_action( 'customize_render_partials_after', array( $this, 'handle_action_customize_render_partials_after' ), 10, 2 );
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$this->assertEquals( $count_customize_render_partials_before + 1, has_action( 'customize_render_partials_before' ) );
		$this->assertEquals( $count_customize_render_partials_after + 1, has_action( 'customize_render_partials_after' ) );
		$output = json_decode( ob_get_clean(), true );
		$this->assertSame( array( get_bloginfo( 'name', 'display' ) ), $output['data']['contents']['test_blogname'] );
		$this->assertArrayHasKey( 'setting_validities', $output['data'] );
	}

	/**
	 * Filter customize_dynamic_partial_args.
	 *
	 * @param array  $partial_args Partial args.
	 * @param string $partial_id   Partial ID.
	 *
	 * @return array|false Args.
	 */
	public function filter_customize_dynamic_partial_args( $partial_args, $partial_id ) {
		if ( 'test_dynamic_blogname' === $partial_id ) {
			$partial_args = array(
				'settings'        => array( 'blogname' ),
				'render_callback' => array( $this, 'render_callback_blogname' ),
			);
		}
		return $partial_args;
	}

	/**
	 * Filter customize_render_partials_response.
	 *
	 * @param array                          $response            Response.
	 * @param GC_Customize_Selective_Refresh $component Selective refresh component.
	 * @param array                          $partial_placements  Placements' context data for the partials rendered in the request.
	 *                                                            The array is keyed by partial ID, with each item being an array of
	 *                                                            the placements' context data.
	 * @return array Response.
	 */
	public function filter_customize_render_partials_response( $response, $component, $partial_placements ) {
		$this->assertIsArray( $response );
		$this->assertInstanceOf( 'GC_Customize_Selective_Refresh', $component );
		if ( isset( $this->expected_partial_ids ) ) {
			$this->assertSameSets( $this->expected_partial_ids, array_keys( $partial_placements ) );
		}
		return $response;
	}

	/**
	 * Expected partial IDs.
	 *
	 * @var array
	 */
	protected $expected_partial_ids;

	/**
	 * Handle 'customize_render_partials_before' action.
	 *
	 * @param GC_Customize_Selective_Refresh $component          Selective refresh component.
	 * @param array                          $partial_placements Partial IDs.
	 */
	public function handle_action_customize_render_partials_after( $component, $partial_placements ) {
		$this->assertInstanceOf( 'GC_Customize_Selective_Refresh', $component );
		if ( isset( $this->expected_partial_ids ) ) {
			$this->assertSameSets( $this->expected_partial_ids, array_keys( $partial_placements ) );
		}
	}

	/**
	 * Handle 'customize_render_partials_after' action.
	 *
	 * @param GC_Customize_Selective_Refresh $component          Selective refresh component.
	 * @param array                          $partial_placements Partial IDs.
	 */
	public function handle_action_customize_render_partials_before( $component, $partial_placements ) {
		$this->assertInstanceOf( 'GC_Customize_Selective_Refresh', $component );
		if ( isset( $this->expected_partial_ids ) ) {
			$this->assertSameSets( $this->expected_partial_ids, array_keys( $partial_placements ) );
		}
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request()dynamic partials are recognized.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_for_dynamic_partial() {
		$this->setup_valid_render_partials_request_environment();
		add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_customize_dynamic_partial_args' ), 10, 2 );

		$context_data = array();
		$placements   = array( $context_data );

		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'test_dynamic_blogname' => $placements,
				)
			)
		);

		$count_customize_render_partials_before = has_action( 'customize_render_partials_before' );
		$count_customize_render_partials_after  = has_action( 'customize_render_partials_after' );
		ob_start();
		try {
			$this->expected_partial_ids = array( 'test_dynamic_blogname' );
			add_filter( 'customize_render_partials_response', array( $this, 'filter_customize_render_partials_response' ), 10, 3 );
			add_action( 'customize_render_partials_before', array( $this, 'handle_action_customize_render_partials_before' ), 10, 2 );
			add_action( 'customize_render_partials_after', array( $this, 'handle_action_customize_render_partials_after' ), 10, 2 );
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$this->assertEquals( $count_customize_render_partials_before + 1, has_action( 'customize_render_partials_before' ) );
		$this->assertEquals( $count_customize_render_partials_after + 1, has_action( 'customize_render_partials_after' ) );
		$output = json_decode( ob_get_clean(), true );
		$this->assertSame( array( get_bloginfo( 'name', 'display' ) ), $output['data']['contents']['test_dynamic_blogname'] );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::handle_render_partials_request() to multiple partials can be requested at once.
	 *
	 * @see GC_Customize_Selective_Refresh::handle_render_partials_request()
	 */
	public function test_handle_render_partials_request_for_multiple_partials_placements() {
		$this->setup_valid_render_partials_request_environment();

		$this->gc_customize->selective_refresh->add_partial(
			'test_blogname',
			array(
				'settings'        => array( 'blogname' ),
				'render_callback' => array( $this, 'render_callback_blogname' ),
			)
		);
		$this->gc_customize->selective_refresh->add_partial(
			'test_blogdescription',
			array(
				'settings'        => array( 'blogdescription' ),
				'render_callback' => array( $this, 'render_callback_blogdescription' ),
			)
		);

		$placement_context_data = array();

		$_POST['partials'] = gc_slash(
			gc_json_encode(
				array(
					'test_blogname'        => array( $placement_context_data ),
					'test_blogdescription' => array( $placement_context_data, $placement_context_data ),
				)
			)
		);

		$count_customize_render_partials_before = has_action( 'customize_render_partials_before' );
		$count_customize_render_partials_after  = has_action( 'customize_render_partials_after' );
		ob_start();
		try {
			$this->expected_partial_ids = array( 'test_blogname', 'test_blogdescription' );
			add_filter( 'customize_render_partials_response', array( $this, 'filter_customize_render_partials_response' ), 10, 3 );
			add_action( 'customize_render_partials_before', array( $this, 'handle_action_customize_render_partials_before' ), 10, 2 );
			add_action( 'customize_render_partials_after', array( $this, 'handle_action_customize_render_partials_after' ), 10, 2 );
			$this->selective_refresh->handle_render_partials_request();
		} catch ( GCDieException $e ) {
			$this->assertSame( '', $e->getMessage() );
		}
		$this->assertEquals( $count_customize_render_partials_before + 1, has_action( 'customize_render_partials_before' ) );
		$this->assertEquals( $count_customize_render_partials_after + 1, has_action( 'customize_render_partials_after' ) );
		$output = json_decode( ob_get_clean(), true );
		$this->assertSame( array( get_bloginfo( 'name', 'display' ) ), $output['data']['contents']['test_blogname'] );
		$this->assertSame( array_fill( 0, 2, get_bloginfo( 'description', 'display' ) ), $output['data']['contents']['test_blogdescription'] );
	}

	/**
	 * Tear down.
	 */
	public function tear_down() {
		$this->expected_partial_ids = null;
		$this->gc_customize         = null;
		unset( $GLOBALS['gc_customize'] );
		unset( $GLOBALS['gc_scripts'] );
		parent::tear_down();
	}
}
