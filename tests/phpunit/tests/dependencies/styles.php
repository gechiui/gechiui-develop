<?php
/**
 * @group dependencies
 * @group scripts
 * @covers ::gc_enqueue_style
 * @covers ::gc_register_style
 * @covers ::gc_print_styles
 * @covers ::gc_style_add_data
 * @covers ::gc_add_inline_style
 */
class Tests_Dependencies_Styles extends GC_UnitTestCase {
	private $old_gc_styles;
	private $old_gc_scripts;

	public function set_up() {
		parent::set_up();

		if ( empty( $GLOBALS['gc_styles'] ) ) {
			$GLOBALS['gc_styles'] = null;
		}

		$this->old_gc_styles = $GLOBALS['gc_styles'];

		if ( empty( $GLOBALS['gc_scripts'] ) ) {
			$GLOBALS['gc_scripts'] = null;
		}

		$this->old_gc_styles = $GLOBALS['gc_scripts'];

		remove_action( 'gc_default_styles', 'gc_default_styles' );
		remove_action( 'gc_print_styles', 'print_emoji_styles' );

		$GLOBALS['gc_styles']                  = new GC_Styles();
		$GLOBALS['gc_styles']->default_version = get_bloginfo( 'version' );

		$GLOBALS['gc_scripts']                  = new GC_Scripts();
		$GLOBALS['gc_scripts']->default_version = get_bloginfo( 'version' );
	}

	public function tear_down() {
		$GLOBALS['gc_styles']  = $this->old_gc_styles;
		$GLOBALS['gc_scripts'] = $this->old_gc_scripts;

		add_action( 'gc_default_styles', 'gc_default_styles' );
		add_action( 'gc_print_styles', 'print_emoji_styles' );

		if ( current_theme_supports( 'gc-block-styles' ) ) {
			remove_theme_support( 'gc-block-styles' );
		}

		parent::tear_down();
	}

	/**
	 * Test versioning
	 *
	 * @ticket 11315
	 */
	public function test_gc_enqueue_style() {
		gc_enqueue_style( 'no-deps-no-version', 'example.com' );
		gc_enqueue_style( 'no-deps-version', 'example.com', array(), 1.2 );
		gc_enqueue_style( 'no-deps-null-version', 'example.com', array(), null );
		gc_enqueue_style( 'no-deps-null-version-print-media', 'example.com', array(), null, 'print' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<link rel='stylesheet' id='no-deps-no-version-css'  href='http://example.com?ver=$ver' type='text/css' media='all' />\n";
		$expected .= "<link rel='stylesheet' id='no-deps-version-css'  href='http://example.com?ver=1.2' type='text/css' media='all' />\n";
		$expected .= "<link rel='stylesheet' id='no-deps-null-version-css'  href='http://example.com' type='text/css' media='all' />\n";
		$expected .= "<link rel='stylesheet' id='no-deps-null-version-print-media-css'  href='http://example.com' type='text/css' media='print' />\n";

		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );

		// No styles left to print.
		$this->assertSame( '', get_echo( 'gc_print_styles' ) );
	}

	/**
	 * @ticket 42804
	 */
	public function test_gc_enqueue_style_with_html5_support_does_not_contain_type_attribute() {
		add_theme_support( 'html5', array( 'style' ) );

		$GLOBALS['gc_styles']                  = new GC_Styles();
		$GLOBALS['gc_styles']->default_version = get_bloginfo( 'version' );

		gc_enqueue_style( 'no-deps-no-version', 'example.com' );

		$ver      = get_bloginfo( 'version' );
		$expected = "<link rel='stylesheet' id='no-deps-no-version-css'  href='http://example.com?ver=$ver' media='all' />\n";

		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );
	}

	/**
	 * Test the different protocol references in gc_enqueue_style
	 *
	 * @global GC_Styles $gc_styles
	 * @ticket 16560
	 */
	public function test_protocols() {
		// Init.
		global $gc_styles;
		$base_url_backup     = $gc_styles->base_url;
		$gc_styles->base_url = 'http://example.com/gechiui';
		$expected            = '';
		$ver                 = get_bloginfo( 'version' );

		// Try with an HTTP reference.
		gc_enqueue_style( 'reset-css-http', 'http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css' );
		$expected .= "<link rel='stylesheet' id='reset-css-http-css'  href='http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css?ver=$ver' type='text/css' media='all' />\n";

		// Try with an HTTPS reference.
		gc_enqueue_style( 'reset-css-https', 'http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css' );
		$expected .= "<link rel='stylesheet' id='reset-css-https-css'  href='http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css?ver=$ver' type='text/css' media='all' />\n";

		// Try with an automatic protocol reference (//).
		gc_enqueue_style( 'reset-css-doubleslash', '//yui.yahooapis.com/2.8.1/build/reset/reset-min.css' );
		$expected .= "<link rel='stylesheet' id='reset-css-doubleslash-css'  href='//yui.yahooapis.com/2.8.1/build/reset/reset-min.css?ver=$ver' type='text/css' media='all' />\n";

		// Try with a local resource and an automatic protocol reference (//).
		$url = '//my_plugin/style.css';
		gc_enqueue_style( 'plugin-style', $url );
		$expected .= "<link rel='stylesheet' id='plugin-style-css'  href='$url?ver=$ver' type='text/css' media='all' />\n";

		// Try with a bad protocol.
		gc_enqueue_style( 'reset-css-ftp', 'ftp://yui.yahooapis.com/2.8.1/build/reset/reset-min.css' );
		$expected .= "<link rel='stylesheet' id='reset-css-ftp-css'  href='{$gc_styles->base_url}ftp://yui.yahooapis.com/2.8.1/build/reset/reset-min.css?ver=$ver' type='text/css' media='all' />\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );

		// No styles left to print.
		$this->assertSame( '', get_echo( 'gc_print_styles' ) );

		// Cleanup.
		$gc_styles->base_url = $base_url_backup;
	}

	/**
	 * Test if inline styles work
	 *
	 * @ticket 24813
	 */
	public function test_inline_styles() {

		$style  = ".thing {\n";
		$style .= "\tbackground: red;\n";
		$style .= '}';

		$expected  = "<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "<style id='handle-inline-css' type='text/css'>\n";
		$expected .= "$style\n";
		$expected .= "</style>\n";

		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );
		gc_add_inline_style( 'handle', $style );

		// No styles left to print.
		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );
	}

	/**
	 * Test if inline styles work with concatination
	 *
	 * @global GC_Styles $gc_styles
	 * @ticket 24813
	 */
	public function test_inline_styles_concat() {

		global $gc_styles;

		$gc_styles->do_concat    = true;
		$gc_styles->default_dirs = array( '/gc-admin/', '/gc-includes/css/' ); // Default dirs as in gc-includes/script-loader.php.

		$style  = ".thing {\n";
		$style .= "\tbackground: red;\n";
		$style .= '}';

		$expected  = "<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "<style id='handle-inline-css' type='text/css'>\n";
		$expected .= "$style\n";
		$expected .= "</style>\n";

		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );
		gc_add_inline_style( 'handle', $style );

		gc_print_styles();
		$this->assertSame( $expected, $gc_styles->print_html );

	}

	/**
	 * Test normalizing relative links in CSS.
	 *
	 * @dataProvider data_normalize_relative_css_links
	 *
	 * @ticket 54243
	 * @ticket 54922
	 *
	 * @covers ::_gc_normalize_relative_css_links
	 *
	 * @param string $css      Given CSS to test.
	 * @param string $expected Expected result.
	 */
	public function test_normalize_relative_css_links( $css, $expected ) {
		$this->assertSame(
			$expected,
			_gc_normalize_relative_css_links( $css, site_url( 'gc-content/themes/test/style.css' ) )
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_normalize_relative_css_links() {
		return array(
			'Double quotes, same path'                     => array(
				'css'      => 'p {background:url( "image0.svg" );}',
				'expected' => 'p {background:url( "/gc-content/themes/test/image0.svg" );}',
			),
			'Single quotes, same path, prefixed with "./"' => array(
				'css'      => 'p {background-image: url(\'./image2.png\');}',
				'expected' => 'p {background-image: url(\'/gc-content/themes/test/image2.png\');}',
			),
			'Single quotes, one level up, prefixed with "../"' => array(
				'css'      => 'p {background-image: url(\'../image1.jpg\');}',
				'expected' => 'p {background-image: url(\'/gc-content/themes/test/../image1.jpg\');}',
			),
			'External URLs, shouldn\'t change'             => array(
				'css'      => 'p {background-image: url(\'http://foo.com/image2.png\');}',
				'expected' => 'p {background-image: url(\'http://foo.com/image2.png\');}',
			),
			'An HTML ID'                                   => array(
				'css'      => 'clip-path: url(#image1);',
				'expected' => 'clip-path: url(#image1);',
			),
		);
	}

	/**
	 * Test if multiple inline styles work
	 *
	 * @ticket 24813
	 */
	public function test_multiple_inline_styles() {

		$style1  = ".thing1 {\n";
		$style1 .= "\tbackground: red;\n";
		$style1 .= '}';

		$style2  = ".thing2 {\n";
		$style2 .= "\tbackground: blue;\n";
		$style2 .= '}';

		$expected  = "<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "<style id='handle-inline-css' type='text/css'>\n";
		$expected .= "$style1\n";
		$expected .= "$style2\n";
		$expected .= "</style>\n";

		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );
		gc_add_inline_style( 'handle', $style1 );
		gc_add_inline_style( 'handle', $style2 );

		// No styles left to print.
		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );

	}

	/**
	 * Test if a plugin doing it the wrong way still works
	 *
	 * @expectedIncorrectUsage gc_add_inline_style
	 * @ticket 24813
	 */
	public function test_plugin_doing_inline_styles_wrong() {

		$style  = "<style id='handle-inline-css' type='text/css'>\n";
		$style .= ".thing {\n";
		$style .= "\tbackground: red;\n";
		$style .= "}\n";
		$style .= '</style>';

		$expected  = "<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "$style\n";

		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );

		gc_add_inline_style( 'handle', $style );

		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );

	}

	/**
	 * Test to make sure <style> tags aren't output if there are no inline styles.
	 *
	 * @ticket 24813
	 */
	public function test_unnecessary_style_tags() {

		$expected = "<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";

		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );

		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );

	}

	/**
	 * Test to make sure that inline styles attached to conditional
	 * stylesheets are also conditional.
	 */
	public function test_conditional_inline_styles_are_also_conditional() {
		$expected = <<<CSS
<!--[if IE]>
<link rel='stylesheet' id='handle-css'  href='http://example.com?ver=1' type='text/css' media='all' />
<style id='handle-inline-css' type='text/css'>
a { color: blue; }
</style>
<![endif]-->

CSS;
		gc_enqueue_style( 'handle', 'http://example.com', array(), 1 );
		gc_style_add_data( 'handle', 'conditional', 'IE' );
		gc_add_inline_style( 'handle', 'a { color: blue; }' );

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_styles' ) );
	}

	/**
	 * Testing 'gc_register_style' return boolean success/failure value.
	 *
	 * @ticket 31126
	 */
	public function test_gc_register_style() {
		$this->assertTrue( gc_register_style( 'duplicate-handler', 'http://example.com' ) );
		$this->assertFalse( gc_register_style( 'duplicate-handler', 'http://example.com' ) );
	}

	/**
	 * @ticket 35229
	 */
	public function test_gc_add_inline_style_for_handle_without_source() {
		$style = 'a { color: blue; }';

		$expected  = "<link rel='stylesheet' id='handle-one-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "<link rel='stylesheet' id='handle-two-css'  href='http://example.com?ver=1' type='text/css' media='all' />\n";
		$expected .= "<style id='handle-three-inline-css' type='text/css'>\n";
		$expected .= "$style\n";
		$expected .= "</style>\n";

		gc_register_style( 'handle-one', 'http://example.com', array(), 1 );
		gc_register_style( 'handle-two', 'http://example.com', array(), 1 );
		gc_register_style( 'handle-three', false, array( 'handle-one', 'handle-two' ) );

		gc_enqueue_style( 'handle-three' );
		gc_add_inline_style( 'handle-three', $style );

		$this->assertSame( $expected, get_echo( 'gc_print_styles' ) );
	}

	/**
	 * @ticket 35921
	 * @dataProvider data_styles_with_media
	 */
	public function test_gc_enqueue_style_with_media( $expected, $media ) {
		gc_enqueue_style( 'handle', 'http://example.com', array(), 1, $media );
		$this->assertStringContainsString( $expected, get_echo( 'gc_print_styles' ) );
	}

	public function data_styles_with_media() {
		return array(
			array(
				"media='all'",
				'all',
			),
			array(
				"media='(orientation: portrait)'",
				'(orientation: portrait)',
			),
			array(
				"media='(max-width: 640px)'",
				'(max-width: 640px)',
			),
			array(
				"media='print and (min-width: 25cm)'",
				'print and (min-width: 25cm)',
			),
			array(
				"media='screen and (color), projection and (color)'",
				'screen and (color), projection and (color)',
			),
			array(
				"media='not screen and (color)'",
				'not screen and (color)',
			),
		);
	}

	/**
	 * Tests that visual block styles are enqueued in the editor even when there is not theme support for 'gc-block-styles'.
	 *
	 * Visual block styles should always be enqueued when editing to avoid the appearance of a broken editor.
	 *
	 * @covers ::gc_enqueue_style
	 */
	public function test_block_styles_for_editing_without_theme_support() {
		// Confirm we are without theme support by default.
		$this->assertFalse( current_theme_supports( 'gc-block-styles' ) );

		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertFalse( gc_style_is( 'gc-block-library-theme' ) );
		gc_enqueue_style( 'gc-edit-blocks' );
		$this->assertTrue( gc_style_is( 'gc-block-library-theme' ) );
	}

	/**
	 * Tests that visual block styles are enqueued when there is theme support for 'gc-block-styles'.
	 *
	 * Visual block styles should always be enqueued when editing to avoid the appearance of a broken editor.
	 *
	 * @covers ::gc_common_block_scripts_and_styles
	 */
	public function test_block_styles_for_editing_with_theme_support() {
		add_theme_support( 'gc-block-styles' );

		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertFalse( gc_style_is( 'gc-block-library-theme' ) );
		gc_common_block_scripts_and_styles();
		$this->assertTrue( gc_style_is( 'gc-block-library-theme' ) );
	}

	/**
	 * Tests that visual block styles are not enqueued for viewing when there is no theme support for 'gc-block-styles'.
	 *
	 * Visual block styles should not be enqueued unless a theme opts in.
	 * This way we avoid style conflicts with existing themes.
	 *
	 * @covers ::gc_enqueue_style
	 */
	public function test_no_block_styles_for_viewing_without_theme_support() {
		// Confirm we are without theme support by default.
		$this->assertFalse( current_theme_supports( 'gc-block-styles' ) );

		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertFalse( gc_style_is( 'gc-block-library-theme' ) );
		gc_enqueue_style( 'gc-block-library' );
		$this->assertFalse( gc_style_is( 'gc-block-library-theme' ) );
	}

	/**
	 * Tests that visual block styles are enqueued for viewing when there is theme support for 'gc-block-styles'.
	 *
	 * Visual block styles should be enqueued when a theme opts in.
	 *
	 * @covers ::gc_common_block_scripts_and_styles
	 */
	public function test_block_styles_for_viewing_with_theme_support() {
		add_theme_support( 'gc-block-styles' );

		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertFalse( gc_style_is( 'gc-block-library-theme' ) );
		gc_common_block_scripts_and_styles();
		$this->assertTrue( gc_style_is( 'gc-block-library-theme' ) );
	}

	/**
	 * Tests that the main "style.css" file gets enqueued when the site doesn't opt in to separate core block assets.
	 *
	 * @ticket 50263
	 *
	 * @covers ::gc_default_styles
	 */
	public function test_block_styles_for_viewing_without_split_styles() {
		add_filter( 'should_load_separate_core_block_assets', '__return_false' );
		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertSame(
			'/' . GCINC . '/css/dist/block-library/style.css',
			$GLOBALS['gc_styles']->registered['gc-block-library']->src
		);
	}

	/**
	 * Tests that the "common.css" file gets enqueued when the site opts in to separate core block assets.
	 *
	 * @ticket 50263
	 *
	 * @covers ::gc_default_styles
	 */
	public function test_block_styles_for_viewing_with_split_styles() {
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );
		gc_default_styles( $GLOBALS['gc_styles'] );

		$this->assertSame(
			'/' . GCINC . '/css/dist/block-library/common.css',
			$GLOBALS['gc_styles']->registered['gc-block-library']->src
		);
	}
}
