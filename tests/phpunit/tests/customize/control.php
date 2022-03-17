<?php
/**
 * Test_GC_Customize_Control tests.
 *
 * @package GeChiUI
 */

/**
 * Tests for the Test_GC_Customize_Control class.
 *
 * @todo This is missing dedicated tests for all but one of the methods.
 *
 * @group customize
 */
class Test_GC_Customize_Control extends GC_UnitTestCase {

	/**
	 * Manager.
	 *
	 * @var GC_Customize_Manager
	 */
	public $gc_customize;

	/**
	 * Set up.
	 */
	public function set_up() {
		parent::set_up();
		gc_set_current_user( $this->factory()->user->create( array( 'role' => 'administrator' ) ) );
		require_once ABSPATH . GCINC . '/class-gc-customize-manager.php';
		$GLOBALS['gc_customize'] = new GC_Customize_Manager();
		$this->gc_customize      = $GLOBALS['gc_customize'];
	}

	/**
	 * Test GC_Customize_Control::check_capabilities().
	 *
	 * @see GC_Customize_Control::check_capabilities()
	 */
	public function test_check_capabilities() {
		do_action( 'customize_register', $this->gc_customize );
		$control = new GC_Customize_Control(
			$this->gc_customize,
			'blogname',
			array(
				'settings' => array( 'blogname' ),
			)
		);
		$this->assertTrue( $control->check_capabilities() );

		$control = new GC_Customize_Control(
			$this->gc_customize,
			'blogname',
			array(
				'settings' => array( 'blogname', 'non_existing' ),
			)
		);
		$this->assertFalse( $control->check_capabilities() );

		$this->gc_customize->add_setting(
			'top_secret_message',
			array(
				'capability' => 'top_secret_clearance',
			)
		);
		$control = new GC_Customize_Control(
			$this->gc_customize,
			'blogname',
			array(
				'settings' => array( 'blogname', 'top_secret_clearance' ),
			)
		);
		$this->assertFalse( $control->check_capabilities() );

		$control = new GC_Customize_Control(
			$this->gc_customize,
			'no_setting',
			array(
				'settings' => array(),
			)
		);
		$this->assertTrue( $control->check_capabilities() );

		$control = new GC_Customize_Control(
			$this->gc_customize,
			'no_setting',
			array(
				'settings'   => array(),
				'capability' => 'top_secret_clearance',
			)
		);
		$this->assertFalse( $control->check_capabilities() );

		$control = new GC_Customize_Control(
			$this->gc_customize,
			'no_setting',
			array(
				'settings'   => array(),
				'capability' => 'edit_theme_options',
			)
		);
		$this->assertTrue( $control->check_capabilities() );
	}

	/**
	 * @ticket 38164
	 */
	public function test_dropdown_pages() {
		do_action( 'customize_register', $this->gc_customize );

		$this->assertInstanceOf( 'GC_Customize_Nav_Menus', $this->gc_customize->nav_menus );
		$nav_menus_created_posts_setting = $this->gc_customize->get_setting( 'nav_menus_created_posts' );
		$this->assertInstanceOf( 'GC_Customize_Filter_Setting', $nav_menus_created_posts_setting );
		$page_on_front_control = $this->gc_customize->get_control( 'page_on_front' );

		// Ensure the add-new-toggle is absent if allow_addition param is not set.
		$page_on_front_control->allow_addition = false;
		ob_start();
		$page_on_front_control->maybe_render();
		$content = ob_get_clean();
		$this->assertStringNotContainsString( 'add-new-toggle', $content );

		// Ensure the add-new-toggle is absent if allow_addition param is set.
		$page_on_front_control->allow_addition = true;
		ob_start();
		$page_on_front_control->maybe_render();
		$content = ob_get_clean();
		$this->assertStringContainsString( 'add-new-toggle', $content );

		// Ensure that dropdown-pages delect is rendered even if there are no pages published (yet).
		foreach ( get_pages() as $page ) {
			gc_delete_post( $page->ID );
		}
		$page_on_front_control->allow_addition = true;
		ob_start();
		$page_on_front_control->maybe_render();
		$content = ob_get_clean();
		$this->assertStringContainsString( '<option value="0">', $content, 'Dropdown-pages renders select even without any pages published.' );

		// Ensure that auto-draft pages are included if they are among the nav_menus_created_posts.
		$auto_draft_page_id = $this->factory()->post->create(
			array(
				'post_type'   => 'page',
				'post_status' => 'auto-draft',
				'post_title'  => 'Auto Draft Page',
			)
		);
		$this->factory()->post->create(
			array(
				'post_type'   => 'page',
				'post_status' => 'auto-draft',
				'post_title'  => 'Orphan Auto Draft Page',
			)
		);
		$auto_draft_post_id = $this->factory()->post->create(
			array(
				'post_type'   => 'post',
				'post_status' => 'auto-draft',
				'post_title'  => 'Auto Draft Post',
			)
		);
		$this->gc_customize->set_post_value( $nav_menus_created_posts_setting->id, array( $auto_draft_page_id, $auto_draft_post_id ) );
		$nav_menus_created_posts_setting->preview();
		ob_start();
		$page_on_front_control->maybe_render();
		$content = ob_get_clean();
		$this->assertStringContainsString( sprintf( '<option value="%d">Auto Draft Page</option>', $auto_draft_page_id ), $content );
		$this->assertStringNotContainsString( 'Auto Draft Post', $content );
		$this->assertStringNotContainsString( 'Orphan Auto Draft Page', $content );
	}

	/**
	 * Tear down.
	 */
	public function tear_down() {
		$this->gc_customize = null;
		unset( $GLOBALS['gc_customize'] );
		parent::tear_down();
	}
}
