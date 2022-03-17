<?php
/**
 * @group themes
 */
class Tests_Theme_CustomHeader extends GC_UnitTestCase {

	public static $post;

	protected static $header_video_id;

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$post = self::factory()->post->create(
			array(
				'post_status' => 'publish',
			)
		);

		$file                  = DIR_TESTDATA . '/uploads/small-video.mp4';
		self::$header_video_id = $factory->attachment->create_upload_object( $file );
	}

	public function set_up() {
		parent::set_up();

		require_once ABSPATH . GCINC . '/class-gc-customize-manager.php';
		$GLOBALS['gc_customize'] = new GC_Customize_Manager();
		$this->customize_manager = $GLOBALS['gc_customize'];

		gc_dequeue_script( 'gc-custom-header' );
	}

	public function tear_down() {
		$this->customize_manager = null;
		unset( $GLOBALS['gc_customize'] );

		remove_theme_support( 'custom-header' );
		remove_theme_mod( 'header_image' );
		remove_theme_mod( 'header_image_data' );
		remove_theme_mod( 'header_video' );
		remove_theme_mod( 'external_header_video' );

		parent::tear_down();
	}

	public function test_add_and_remove_theme_support() {
		$this->add_theme_support();
		$this->assertTrue( current_theme_supports( 'custom-header' ) );
		remove_theme_support( 'custom-header' );
		$this->assertFalse( current_theme_supports( 'custom-header' ) );
	}

	public function test_get_header_image_without_registered_default() {
		$this->add_theme_support();
		$image = get_header_image();
		$this->assertFalse( has_header_image() );
		$this->assertEmpty( $image );
	}

	public function test_get_header_image_with_registered_default() {
		$default = 'http://localhost/default-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );

		$image = get_header_image();
		$this->assertTrue( has_header_image() );
		$this->assertSame( $default, $image );
	}

	public function test_get_header_image_from_theme_mod() {
		$default = 'http://localhost/default-header.jpg';
		$custom  = 'http://localhost/custom-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );

		set_theme_mod( 'header_image', $custom );
		$image = get_header_image();
		$this->assertSame( $custom, $image );
		$this->assertTrue( has_header_image() );

		set_theme_mod( 'header_image', 'remove-header' );
		$image = get_header_image();
		$this->assertFalse( has_header_image() );
		$this->assertFalse( $image );
	}

	public function test_get_header_image_tag_without_registered_default_image() {
		$this->add_theme_support();
		$html = get_header_image_tag();
		$this->assertEmpty( $html );
	}

	public function test_get_header_image_tag_with_registered_default_image() {
		$default = 'http://localhost/default-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );

		$html = get_header_image_tag();
		$this->assertStringStartsWith( '<img ', $html );
		$this->assertStringContainsString( sprintf( 'src="%s"', $default ), $html );
	}

	/**
	 * @ticket 38633
	 */
	public function test_get_header_image_tag_with_registered_default_image_and_remove_header_theme_mod() {
		$default = 'http://localhost/default-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );

		set_theme_mod( 'header_image', 'remove-header' );
		$html = get_header_image_tag();
		$this->assertEmpty( $html );
	}

	public function test_get_header_image_tag_with_registered_default_image_and_custom_theme_mod() {
		$default = 'http://localhost/default-header.jpg';
		$custom  = 'http://localhost/custom-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );

		set_theme_mod( 'header_image', $custom );
		$html = get_header_image_tag();
		$this->assertStringStartsWith( '<img ', $html );
		$this->assertStringContainsString( sprintf( 'src="%s"', $custom ), $html );
	}

	public function test_get_custom_header_markup_without_registered_default_image() {
		$this->add_theme_support();

		$html = get_custom_header_markup();
		$this->assertFalse( has_custom_header() );
		$this->assertEmpty( $html );

		// The container should always be returned in the Customizer preview.
		$this->set_customize_previewing( true );
		$html = get_custom_header_markup();
		$this->assertSame( '<div id="gc-custom-header" class="gc-custom-header"></div>', $html );
	}

	public function test_get_custom_header_markup_with_registered_default_image() {
		$default = 'http://localhost/default-header.jpg';
		$this->add_theme_support( array( 'default-image' => $default ) );
		$html = get_custom_header_markup();
		$this->assertTrue( has_custom_header() );
		$this->assertStringStartsWith( '<div id="gc-custom-header" class="gc-custom-header">', $html );
		$this->assertStringContainsString( sprintf( 'src="%s"', $default ), $html );
	}

	public function test_get_header_video_url() {
		$this->add_theme_support( array( 'video' => true ) );

		$this->assertFalse( has_header_video() );
		set_theme_mod( 'header_video', self::$header_video_id );
		$this->assertTrue( has_header_video() );
		$this->assertSame( gc_get_attachment_url( self::$header_video_id ), get_header_video_url() );
	}

	public function test_get_external_header_video_url() {
		$external = 'http://example.com/custom-video.mp4';
		$this->add_theme_support( array( 'video' => true ) );

		$this->assertFalse( has_header_video() );
		set_theme_mod( 'external_header_video', $external );
		$this->assertTrue( has_header_video() );
		$this->assertSame( $external, get_header_video_url() );
	}

	public function test_get_header_video_url_prefers_local_video() {
		$external = 'http://example.com/custom-video.mp4';
		$this->add_theme_support( array( 'video' => true ) );

		set_theme_mod( 'header_video', self::$header_video_id );
		set_theme_mod( 'external_header_video', $external );
		$this->assertSame( gc_get_attachment_url( self::$header_video_id ), get_header_video_url() );
	}

	public function test_get_custom_header_markup_with_video_and_without_an_image() {
		$custom = 'http://localhost/custom-video.mp4';
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);

		set_theme_mod( 'external_header_video', $custom );
		$html = get_custom_header_markup();
		$this->assertTrue( has_header_video() );
		$this->assertTrue( has_custom_header() );
		$this->assertSame( '<div id="gc-custom-header" class="gc-custom-header"></div>', $html );
	}

	public function test_header_script_is_not_enqueued_by_the_custom_header_markup_without_video() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);

		ob_start();
		the_custom_header_markup();
		ob_end_clean();
		$this->assertFalse( gc_script_is( 'gc-custom-header', 'enqueued' ) );

		set_theme_mod( 'header_image', 'http://localhost/custom-header.jpg' );

		ob_start();
		the_custom_header_markup();
		ob_end_clean();
		$this->assertFalse( gc_script_is( 'gc-custom-header', 'enqueued' ) );
	}

	public function test_header_script_is_not_enqueued_by_the_custom_header_markup_when_active_callback_is_false() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_false',
			)
		);
		set_theme_mod( 'external_header_video', 'http://localhost/custom-video.mp4' );

		ob_start();
		the_custom_header_markup();
		ob_end_clean();
		$this->assertFalse( gc_script_is( 'gc-custom-header', 'enqueued' ) );
	}

	public function test_header_script_is_enqueued_by_the_custom_header_markup_without_video_when_previewing_in_customizer() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);
		$this->set_customize_previewing( true );

		ob_start();
		the_custom_header_markup();
		ob_end_clean();
		$this->assertTrue( gc_script_is( 'gc-custom-header', 'enqueued' ) );
	}

	public function test_header_script_is_enqueued_by_the_custom_header_markup_with_video() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);
		set_theme_mod( 'external_header_video', 'http://localhost/custom-video.mp4' );

		ob_start();
		the_custom_header_markup();
		ob_end_clean();
		$this->assertTrue( gc_script_is( 'gc-custom-header', 'enqueued' ) );
	}

	/**
	 * @ticket 38738
	 */
	public function test_video_header_callback_front_page_from_front_page() {
		$this->add_theme_support(
			array(
				'video' => true,
			)
		);

		$this->go_to( home_url() );

		$result = is_header_video_active();

		$this->assertTrue( $result );
	}

	/**
	 * @ticket 38738
	 */
	public function test_video_header_callback_front_page_from_elsewhere() {
		$this->add_theme_support(
			array(
				'video' => true,
			)
		);

		$this->go_to( get_permalink( self::$post ) );

		$result = is_header_video_active();

		$this->assertFalse( $result );
	}

	/**
	 * @ticket 38738
	 */
	public function test_video_header_callback_globally_from_front_page() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);

		$this->go_to( home_url() );

		$result = is_header_video_active();

		$this->assertTrue( $result );
	}

	/**
	 * @ticket 38738
	 */
	public function test_video_header_callback_globally_from_elsewhere() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);

		$this->go_to( get_permalink( self::$post ) );

		$result = is_header_video_active();

		$this->assertTrue( $result );
	}

	/**
	 * @ticket 38738
	 */
	public function test_video_header_callback_globally_with_negative_filter() {
		$this->add_theme_support(
			array(
				'video'                 => true,
				'video-active-callback' => '__return_true',
			)
		);

		$this->go_to( get_permalink( self::$post ) );

		add_filter( 'is_header_video_active', '__return_false' );
		$result = is_header_video_active();
		remove_filter( 'is_header_video_active', '__return_false' );

		$this->assertFalse( $result );
	}

	/**
	 * Adds arguments directly to the $_gc_theme_features global. Calling
	 * add_theme_support( 'custom-header' ) will poison subsequent tests since
	 * it defines constants.
	 */
	private function add_theme_support( $args = array() ) {
		global $_gc_theme_features;

		$_gc_theme_features['custom-header'][0] = gc_parse_args(
			$args,
			array(
				'default-image'          => '',
				'random-default'         => false,
				'width'                  => 0,
				'height'                 => 0,
				'flex-height'            => false,
				'flex-width'             => false,
				'default-text-color'     => '',
				'header-text'            => true,
				'uploads'                => true,
				'gc-head-callback'       => '',
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
				'video'                  => false,
				'video-active-callback'  => 'is_front_page',
			)
		);
	}

	private function set_customize_previewing( $value ) {
		$class    = new ReflectionClass( 'GC_Customize_Manager' );
		$property = $class->getProperty( 'previewing' );
		$property->setAccessible( true );
		$property->setValue( $this->customize_manager, $value );
	}
}