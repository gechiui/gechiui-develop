<?php
/**
 * GC_Customize_Selective_Refresh tests.
 *
 * @package GeChiUI
 */

/**
 * Tests for the GC_Customize_Selective_Refresh class.
 *
 * @group customize
 */
class Test_GC_Customize_Selective_Refresh extends GC_UnitTestCase {

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
		require_once ABSPATH . GCINC . '/class-gc-customize-manager.php';
		$GLOBALS['gc_customize'] = new GC_Customize_Manager();
		$this->gc_customize      = $GLOBALS['gc_customize'];
		if ( isset( $this->gc_customize->selective_refresh ) ) {
			$this->selective_refresh = $this->gc_customize->selective_refresh;
		}
	}

	/**
	 * Test GC_Customize_Selective_Refresh::__construct().
	 *
	 * @see GC_Customize_Selective_Refresh::__construct()
	 */
	public function test_construct() {
		$this->assertSame( $this->selective_refresh, $this->gc_customize->selective_refresh );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::register_scripts().
	 *
	 * @see GC_Customize_Selective_Refresh::register_scripts()
	 */
	public function test_register_scripts() {
		$scripts = new GC_Scripts();
		$handles = array(
			'customize-selective-refresh',
			'customize-preview-nav-menus',
			'customize-preview-widgets',
		);
		foreach ( $handles as $handle ) {
			$this->assertArrayHasKey( $handle, $scripts->registered );
		}
	}

	/**
	 * Test GC_Customize_Selective_Refresh::partials().
	 *
	 * @see GC_Customize_Selective_Refresh::partials()
	 */
	public function test_partials() {
		$this->assertIsArray( $this->selective_refresh->partials() );
	}

	/**
	 * Test CRUD methods for partials.
	 *
	 * @see GC_Customize_Selective_Refresh::get_partial()
	 * @see GC_Customize_Selective_Refresh::add_partial()
	 * @see GC_Customize_Selective_Refresh::remove_partial()
	 */
	public function test_crud_partial() {
		$partial = $this->selective_refresh->add_partial( 'foo' );
		$this->assertSame( $this->selective_refresh, $partial->component );
		$this->assertInstanceOf( 'GC_Customize_Partial', $partial );
		$this->assertSame( $partial, $this->selective_refresh->get_partial( $partial->id ) );
		$this->assertArrayHasKey( $partial->id, $this->selective_refresh->partials() );

		$this->selective_refresh->remove_partial( $partial->id );
		$this->assertEmpty( $this->selective_refresh->get_partial( $partial->id ) );
		$this->assertArrayNotHasKey( $partial->id, $this->selective_refresh->partials() );

		$partial = new GC_Customize_Partial( $this->selective_refresh, 'bar' );
		$this->assertSame( $partial, $this->selective_refresh->add_partial( $partial ) );
		$this->assertSame( $partial, $this->selective_refresh->get_partial( 'bar' ) );
		$this->assertSameSets( array( 'bar' ), array_keys( $this->selective_refresh->partials() ) );

		add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_customize_dynamic_partial_args' ), 10, 2 );
		add_filter( 'customize_dynamic_partial_class', array( $this, 'filter_customize_dynamic_partial_class' ), 10, 3 );

		$partial = $this->selective_refresh->add_partial( 'recognized-class' );
		$this->assertInstanceOf( 'Tested_Custom_Partial', $partial );
		$this->assertSame( '.recognized', $partial->selector );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::init_preview().
	 *
	 * @see GC_Customize_Selective_Refresh::init_preview()
	 */
	public function test_init_preview() {
		$this->selective_refresh->init_preview();
		$this->assertSame( 10, has_action( 'template_redirect', array( $this->selective_refresh, 'handle_render_partials_request' ) ) );
		$this->assertSame( 10, has_action( 'gc_enqueue_scripts', array( $this->selective_refresh, 'enqueue_preview_scripts' ) ) );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::enqueue_preview_scripts().
	 *
	 * @see GC_Customize_Selective_Refresh::enqueue_preview_scripts()
	 */
	public function test_enqueue_preview_scripts() {
		$scripts = gc_scripts();
		$this->assertNotContains( 'customize-selective-refresh', $scripts->queue );
		$this->selective_refresh->enqueue_preview_scripts();
		$this->assertContains( 'customize-selective-refresh', $scripts->queue );
		$this->assertSame( 1000, has_action( 'gc_footer', array( $this->selective_refresh, 'export_preview_data' ) ) );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::export_preview_data().
	 *
	 * @see GC_Customize_Selective_Refresh::export_preview_data()
	 */
	public function test_export_preview_data() {
		$user_id = self::factory()->user->create( array( 'role' => 'administrator' ) );
		gc_set_current_user( $user_id );
		$user = new GC_User( $user_id );
		do_action( 'customize_register', $this->gc_customize );
		$user->remove_cap( 'top_secret_clearance' );
		$this->gc_customize->add_setting(
			'top_secret_message',
			array(
				'capability' => 'top_secret_clearance', // The administrator role lacks this.
			)
		);
		$this->selective_refresh->add_partial(
			'blogname',
			array(
				'selector' => '#site-title',
			)
		);
		$this->selective_refresh->add_partial(
			'top_secret_message',
			array(
				'settings' => array( 'top_secret_message' ),
			)
		);
		ob_start();
		$this->selective_refresh->export_preview_data();
		$html = ob_get_clean();
		$this->assertTrue( (bool) preg_match( '/_customizePartialRefreshExports = ({.+})/s', $html, $matches ) );
		$exported_data = json_decode( $matches[1], true );
		$this->assertIsArray( $exported_data );
		$this->assertArrayHasKey( 'partials', $exported_data );
		$this->assertIsArray( $exported_data['partials'] );
		$this->assertArrayHasKey( 'blogname', $exported_data['partials'] );
		$this->assertArrayNotHasKey( 'top_secret_message', $exported_data['partials'] );
		$this->assertSame( '#site-title', $exported_data['partials']['blogname']['selector'] );
		$this->assertArrayHasKey( 'renderQueryVar', $exported_data );
		$this->assertArrayHasKey( 'l10n', $exported_data );
	}

	/**
	 * Test GC_Customize_Selective_Refresh::add_dynamic_partials().
	 *
	 * @see GC_Customize_Selective_Refresh::add_dynamic_partials()
	 */
	public function test_add_dynamic_partials() {
		$partial_ids = array( 'recognized', 'recognized-class', 'unrecognized', 'already-added' );

		$partials = $this->selective_refresh->add_dynamic_partials( $partial_ids );
		$this->assertEmpty( $partials );

		$this->selective_refresh->add_partial( 'already-added' );

		add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_customize_dynamic_partial_args' ), 10, 2 );
		add_filter( 'customize_dynamic_partial_class', array( $this, 'filter_customize_dynamic_partial_class' ), 10, 3 );

		$partials = $this->selective_refresh->add_dynamic_partials( $partial_ids );
		$this->assertSameSets( array( 'recognized', 'recognized-class' ), gc_list_pluck( $partials, 'id' ) );

		$this->assertInstanceOf( 'Tested_Custom_Partial', $this->selective_refresh->get_partial( 'recognized-class' ) );
		$this->assertNotInstanceOf( 'Tested_Custom_Partial', $this->selective_refresh->get_partial( 'recognized' ) );
		$this->assertSame( '.recognized', $this->selective_refresh->get_partial( 'recognized' )->selector );
	}

	/**
	 * Filter customize_dynamic_partial_args.
	 *
	 * @see Test_GC_Customize_Selective_Refresh::test_add_dynamic_partials()
	 *
	 * @param false|array $partial_args The arguments to the GC_Customize_Partial constructor.
	 * @param string      $partial_id   ID for dynamic partial.
	 * @return false|array Dynamic partial args.
	 */
	public function filter_customize_dynamic_partial_args( $partial_args, $partial_id ) {
		$this->assertTrue( false === $partial_args || is_array( $partial_args ) );
		$this->assertIsString( $partial_id );

		if ( preg_match( '/^recognized/', $partial_id ) ) {
			$partial_args = array(
				'selector' => '.recognized',
			);
		}

		return $partial_args;
	}

	/**
	 * Filter customize_dynamic_partial_class.
	 *
	 * @see Test_GC_Customize_Selective_Refresh::test_add_dynamic_partials()
	 *
	 * @param string $partial_class GC_Customize_Partial or a subclass.
	 * @param string $partial_id    ID for dynamic partial.
	 * @param array  $partial_args  The arguments to the GC_Customize_Partial constructor.
	 * @return string
	 */
	public function filter_customize_dynamic_partial_class( $partial_class, $partial_id, $partial_args ) {
		$this->assertIsArray( $partial_args );
		$this->assertIsString( $partial_id );
		$this->assertIsString( $partial_class );

		if ( 'recognized-class' === $partial_id ) {
			$partial_class = 'Tested_Custom_Partial';
		}

		return $partial_class;
	}

	/**
	 * Test GC_Customize_Selective_Refresh::is_render_partials_request().
	 *
	 * @see GC_Customize_Selective_Refresh::is_render_partials_request()
	 */
	public function test_is_render_partials_request() {
		$this->assertFalse( $this->selective_refresh->is_render_partials_request() );
		$_POST[ GC_Customize_Selective_Refresh::RENDER_QUERY_VAR ] = '1';
		$this->assertTrue( $this->selective_refresh->is_render_partials_request() );
	}

	/**
	 * Tear down.
	 */
	public function tear_down() {
		$this->gc_customize = null;
		unset( $GLOBALS['gc_customize'] );
		unset( $GLOBALS['gc_scripts'] );
		parent::tear_down();
	}
}

require_once ABSPATH . GCINC . '/customize/class-gc-customize-partial.php';

/**
 * Class Tested_Custom_Partial
 */
class Tested_Custom_Partial extends GC_Customize_Partial {

	/**
	 * Type.
	 *
	 * @var string
	 */
	public $type = 'custom';
}
