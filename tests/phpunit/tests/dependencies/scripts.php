<?php
/**
 * @group dependencies
 * @group scripts
 * @covers ::gc_enqueue_script
 * @covers ::gc_register_script
 * @covers ::gc_print_scripts
 * @covers ::gc_script_add_data
 * @covers ::gc_add_inline_script
 * @covers ::gc_set_script_translations
 */
class Tests_Dependencies_Scripts extends GC_UnitTestCase {
	protected $old_gc_scripts;

	protected $gc_scripts_print_translations_output;

	public function set_up() {
		parent::set_up();
		$this->old_gc_scripts = isset( $GLOBALS['gc_scripts'] ) ? $GLOBALS['gc_scripts'] : null;
		remove_action( 'gc_default_scripts', 'gc_default_scripts' );
		remove_action( 'gc_default_scripts', 'gc_default_packages' );
		$GLOBALS['gc_scripts']                  = new GC_Scripts();
		$GLOBALS['gc_scripts']->default_version = get_bloginfo( 'version' );

		$this->gc_scripts_print_translations_output  = <<<JS
<script type='text/javascript' id='__HANDLE__-js-translations'>
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	gc.i18n.setLocaleData( localeData, domain );
} )( "__DOMAIN__", __JSON_TRANSLATIONS__ );
</script>
JS;
		$this->gc_scripts_print_translations_output .= "\n";
	}

	public function tear_down() {
		$GLOBALS['gc_scripts'] = $this->old_gc_scripts;
		add_action( 'gc_default_scripts', 'gc_default_scripts' );
		parent::tear_down();
	}

	/**
	 * Test versioning
	 *
	 * @ticket 11315
	 */
	public function test_gc_enqueue_script() {
		gc_enqueue_script( 'no-deps-no-version', 'example.com', array() );
		gc_enqueue_script( 'empty-deps-no-version', 'example.com' );
		gc_enqueue_script( 'empty-deps-version', 'example.com', array(), 1.2 );
		gc_enqueue_script( 'empty-deps-null-version', 'example.com', array(), null );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='http://example.com?ver=$ver' id='no-deps-no-version-js'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com?ver=$ver' id='empty-deps-no-version-js'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com?ver=1.2' id='empty-deps-version-js'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='empty-deps-null-version-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 42804
	 */
	public function test_gc_enqueue_script_with_html5_support_does_not_contain_type_attribute() {
		add_theme_support( 'html5', array( 'script' ) );

		$GLOBALS['gc_scripts']                  = new GC_Scripts();
		$GLOBALS['gc_scripts']->default_version = get_bloginfo( 'version' );

		gc_enqueue_script( 'empty-deps-no-version', 'example.com' );

		$ver      = get_bloginfo( 'version' );
		$expected = "<script src='http://example.com?ver=$ver' id='empty-deps-no-version-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Test the different protocol references in gc_enqueue_script
	 *
	 * @global GC_Scripts $gc_scripts
	 * @ticket 16560
	 */
	public function test_protocols() {
		// Init.
		global $gc_scripts;
		$base_url_backup      = $gc_scripts->base_url;
		$gc_scripts->base_url = 'http://example.com/gechiui';
		$expected             = '';
		$ver                  = get_bloginfo( 'version' );

		// Try with an HTTP reference.
		gc_enqueue_script( 'jquery-http', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
		$expected .= "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js?ver=$ver' id='jquery-http-js'></script>\n";

		// Try with an HTTPS reference.
		gc_enqueue_script( 'jquery-https', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
		$expected .= "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js?ver=$ver' id='jquery-https-js'></script>\n";

		// Try with an automatic protocol reference (//).
		gc_enqueue_script( 'jquery-doubleslash', '//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
		$expected .= "<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js?ver=$ver' id='jquery-doubleslash-js'></script>\n";

		// Try with a local resource and an automatic protocol reference (//).
		$url = '//my_plugin/script.js';
		gc_enqueue_script( 'plugin-script', $url );
		$expected .= "<script type='text/javascript' src='$url?ver=$ver' id='plugin-script-js'></script>\n";

		// Try with a bad protocol.
		gc_enqueue_script( 'jquery-ftp', 'ftp://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
		$expected .= "<script type='text/javascript' src='{$gc_scripts->base_url}ftp://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js?ver=$ver' id='jquery-ftp-js'></script>\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );

		// Cleanup.
		$gc_scripts->base_url = $base_url_backup;
	}

	/**
	 * Test script concatenation.
	 */
	public function test_script_concatenation() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/directory/' );

		gc_enqueue_script( 'one', '/directory/script.js' );
		gc_enqueue_script( 'two', '/directory/script.js' );
		gc_enqueue_script( 'three', '/directory/script.js' );

		gc_print_scripts();
		$print_scripts = get_echo( '_print_scripts' );

		$ver      = get_bloginfo( 'version' );
		$expected = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=one,two,three&amp;ver={$ver}'></script>\n";

		$this->assertSame( $expected, $print_scripts );
	}

	/**
	 * Testing `gc_script_add_data` with the data key.
	 *
	 * @ticket 16024
	 */
	public function test_gc_script_add_data_with_data_key() {
		// Enqueue and add data.
		gc_enqueue_script( 'test-only-data', 'example.com', array(), null );
		gc_script_add_data( 'test-only-data', 'data', 'testing' );
		$expected  = "<script type='text/javascript' id='test-only-data-js-extra'>\n/* <![CDATA[ */\ntesting\n/* ]]> */\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-only-data-js'></script>\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Testing `gc_script_add_data` with the conditional key.
	 *
	 * @ticket 16024
	 */
	public function test_gc_script_add_data_with_conditional_key() {
		// Enqueue and add conditional comments.
		gc_enqueue_script( 'test-only-conditional', 'example.com', array(), null );
		gc_script_add_data( 'test-only-conditional', 'conditional', 'gt IE 7' );
		$expected = "<!--[if gt IE 7]>\n<script type='text/javascript' src='http://example.com' id='test-only-conditional-js'></script>\n<![endif]-->\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Testing `gc_script_add_data` with both the data & conditional keys.
	 *
	 * @ticket 16024
	 */
	public function test_gc_script_add_data_with_data_and_conditional_keys() {
		// Enqueue and add data plus conditional comments for both.
		gc_enqueue_script( 'test-conditional-with-data', 'example.com', array(), null );
		gc_script_add_data( 'test-conditional-with-data', 'data', 'testing' );
		gc_script_add_data( 'test-conditional-with-data', 'conditional', 'lt IE 9' );
		$expected  = "<!--[if lt IE 9]>\n<script type='text/javascript' id='test-conditional-with-data-js-extra'>\n/* <![CDATA[ */\ntesting\n/* ]]> */\n</script>\n<![endif]-->\n";
		$expected .= "<!--[if lt IE 9]>\n<script type='text/javascript' src='http://example.com' id='test-conditional-with-data-js'></script>\n<![endif]-->\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Testing `gc_script_add_data` with an anvalid key.
	 *
	 * @ticket 16024
	 */
	public function test_gc_script_add_data_with_invalid_key() {
		// Enqueue and add an invalid key.
		gc_enqueue_script( 'test-invalid', 'example.com', array(), null );
		gc_script_add_data( 'test-invalid', 'invalid', 'testing' );
		$expected = "<script type='text/javascript' src='http://example.com' id='test-invalid-js'></script>\n";

		// Go!
		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );

		// No scripts left to print.
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Testing 'gc_register_script' return boolean success/failure value.
	 *
	 * @ticket 31126
	 */
	public function test_gc_register_script() {
		$this->assertTrue( gc_register_script( 'duplicate-handler', 'http://example.com' ) );
		$this->assertFalse( gc_register_script( 'duplicate-handler', 'http://example.com' ) );
	}

	/**
	 * @ticket 35229
	 */
	public function test_gc_register_script_with_handle_without_source() {
		$expected  = "<script type='text/javascript' src='http://example.com?ver=1' id='handle-one-js'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com?ver=2' id='handle-two-js'></script>\n";

		gc_register_script( 'handle-one', 'http://example.com', array(), 1 );
		gc_register_script( 'handle-two', 'http://example.com', array(), 2 );
		gc_register_script( 'handle-three', false, array( 'handle-one', 'handle-two' ) );

		gc_enqueue_script( 'handle-three' );

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 35643
	 */
	public function test_gc_enqueue_script_footer_alias() {
		gc_register_script( 'foo', false, array( 'bar', 'baz' ), '1.0', true );
		gc_register_script( 'bar', home_url( 'bar.js' ), array(), '1.0', true );
		gc_register_script( 'baz', home_url( 'baz.js' ), array(), '1.0', true );

		gc_enqueue_script( 'foo' );

		$header = get_echo( 'gc_print_head_scripts' );
		$footer = get_echo( 'gc_print_footer_scripts' );

		$this->assertEmpty( $header );
		$this->assertStringContainsString( home_url( 'bar.js' ), $footer );
		$this->assertStringContainsString( home_url( 'baz.js' ), $footer );
	}

	/**
	 * Test mismatch of groups in dependencies outputs all scripts in right order.
	 *
	 * @ticket 35873
	 *
	 * @covers GC_Dependencies::add
	 * @covers GC_Dependencies::enqueue
	 * @covers GC_Dependencies::do_items
	 */
	public function test_group_mismatch_in_deps() {
		$scripts = new GC_Scripts;
		$scripts->add( 'one', 'one', array(), 'v1', 1 );
		$scripts->add( 'two', 'two', array( 'one' ) );
		$scripts->add( 'three', 'three', array( 'two' ), 'v1', 1 );

		$scripts->enqueue( array( 'three' ) );

		$this->expectOutputRegex( '/^(?:<script[^>]+><\/script>\\n){7}$/' );

		$scripts->do_items( false, 0 );
		$this->assertContains( 'one', $scripts->done );
		$this->assertContains( 'two', $scripts->done );
		$this->assertNotContains( 'three', $scripts->done );

		$scripts->do_items( false, 1 );
		$this->assertContains( 'one', $scripts->done );
		$this->assertContains( 'two', $scripts->done );
		$this->assertContains( 'three', $scripts->done );

		$scripts = new GC_Scripts;
		$scripts->add( 'one', 'one', array(), 'v1', 1 );
		$scripts->add( 'two', 'two', array( 'one' ), 'v1', 1 );
		$scripts->add( 'three', 'three', array( 'one' ) );
		$scripts->add( 'four', 'four', array( 'two', 'three' ), 'v1', 1 );

		$scripts->enqueue( array( 'four' ) );

		$scripts->do_items( false, 0 );
		$this->assertContains( 'one', $scripts->done );
		$this->assertNotContains( 'two', $scripts->done );
		$this->assertContains( 'three', $scripts->done );
		$this->assertNotContains( 'four', $scripts->done );

		$scripts->do_items( false, 1 );
		$this->assertContains( 'one', $scripts->done );
		$this->assertContains( 'two', $scripts->done );
		$this->assertContains( 'three', $scripts->done );
		$this->assertContains( 'four', $scripts->done );
	}

	/**
	 * @ticket 35873
	 */
	public function test_gc_register_script_with_dependencies_in_head_and_footer() {
		gc_register_script( 'parent', '/parent.js', array( 'child-head' ), null, true );            // In footer.
		gc_register_script( 'child-head', '/child-head.js', array( 'child-footer' ), null, false ); // In head.
		gc_register_script( 'child-footer', '/child-footer.js', array(), null, true );              // In footer.

		gc_enqueue_script( 'parent' );

		$header = get_echo( 'gc_print_head_scripts' );
		$footer = get_echo( 'gc_print_footer_scripts' );

		$expected_header  = "<script type='text/javascript' src='/child-footer.js' id='child-footer-js'></script>\n";
		$expected_header .= "<script type='text/javascript' src='/child-head.js' id='child-head-js'></script>\n";
		$expected_footer  = "<script type='text/javascript' src='/parent.js' id='parent-js'></script>\n";

		$this->assertSame( $expected_header, $header );
		$this->assertSame( $expected_footer, $footer );
	}

	/**
	 * @ticket 35956
	 */
	public function test_gc_register_script_with_dependencies_in_head_and_footer_in_reversed_order() {
		gc_register_script( 'child-head', '/child-head.js', array(), null, false );                      // In head.
		gc_register_script( 'child-footer', '/child-footer.js', array(), null, true );                   // In footer.
		gc_register_script( 'parent', '/parent.js', array( 'child-head', 'child-footer' ), null, true ); // In footer.

		gc_enqueue_script( 'parent' );

		$header = get_echo( 'gc_print_head_scripts' );
		$footer = get_echo( 'gc_print_footer_scripts' );

		$expected_header  = "<script type='text/javascript' src='/child-head.js' id='child-head-js'></script>\n";
		$expected_footer  = "<script type='text/javascript' src='/child-footer.js' id='child-footer-js'></script>\n";
		$expected_footer .= "<script type='text/javascript' src='/parent.js' id='parent-js'></script>\n";

		$this->assertSame( $expected_header, $header );
		$this->assertSame( $expected_footer, $footer );
	}

	/**
	 * @ticket 35956
	 */
	public function test_gc_register_script_with_dependencies_in_head_and_footer_in_reversed_order_and_two_parent_scripts() {
		gc_register_script( 'grandchild-head', '/grandchild-head.js', array(), null, false );             // In head.
		gc_register_script( 'child-head', '/child-head.js', array(), null, false );                       // In head.
		gc_register_script( 'child-footer', '/child-footer.js', array( 'grandchild-head' ), null, true ); // In footer.
		gc_register_script( 'child2-head', '/child2-head.js', array(), null, false );                     // In head.
		gc_register_script( 'child2-footer', '/child2-footer.js', array(), null, true );                  // In footer.
		gc_register_script( 'parent-footer', '/parent-footer.js', array( 'child-head', 'child-footer', 'child2-head', 'child2-footer' ), null, true ); // In footer.
		gc_register_script( 'parent-header', '/parent-header.js', array( 'child-head' ), null, false );   // In head.

		gc_enqueue_script( 'parent-footer' );
		gc_enqueue_script( 'parent-header' );

		$header = get_echo( 'gc_print_head_scripts' );
		$footer = get_echo( 'gc_print_footer_scripts' );

		$expected_header  = "<script type='text/javascript' src='/child-head.js' id='child-head-js'></script>\n";
		$expected_header .= "<script type='text/javascript' src='/grandchild-head.js' id='grandchild-head-js'></script>\n";
		$expected_header .= "<script type='text/javascript' src='/child2-head.js' id='child2-head-js'></script>\n";
		$expected_header .= "<script type='text/javascript' src='/parent-header.js' id='parent-header-js'></script>\n";

		$expected_footer  = "<script type='text/javascript' src='/child-footer.js' id='child-footer-js'></script>\n";
		$expected_footer .= "<script type='text/javascript' src='/child2-footer.js' id='child2-footer-js'></script>\n";
		$expected_footer .= "<script type='text/javascript' src='/parent-footer.js' id='parent-footer-js'></script>\n";

		$this->assertSame( $expected_header, $header );
		$this->assertSame( $expected_footer, $footer );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_returns_bool() {
		$this->assertFalse( gc_add_inline_script( 'test-example', 'console.log("before");', 'before' ) );
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		$this->assertTrue( gc_add_inline_script( 'test-example', 'console.log("before");', 'before' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_unknown_handle() {
		$this->assertFalse( gc_add_inline_script( 'test-invalid', 'console.log("before");', 'before' ) );
		$this->assertSame( '', get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_before() {
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );

		$expected  = "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_after() {
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected  = "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_before_and_after() {
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected  = "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 44551
	 */
	public function test_gc_add_inline_script_before_for_handle_without_source() {
		gc_register_script( 'test-example', '' );
		gc_enqueue_script( 'test-example' );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );

		$expected = "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 44551
	 */
	public function test_gc_add_inline_script_after_for_handle_without_source() {
		gc_register_script( 'test-example', '' );
		gc_enqueue_script( 'test-example' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected = "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 44551
	 */
	public function test_gc_add_inline_script_before_and_after_for_handle_without_source() {
		gc_register_script( 'test-example', '' );
		gc_enqueue_script( 'test-example' );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected  = "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_multiple() {
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected  = "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_localized_data_is_added_first() {
		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_localize_script( 'test-example', 'testExample', array( 'foo' => 'bar' ) );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		$expected  = "<script type='text/javascript' id='test-example-js-extra'>\n/* <![CDATA[ */\nvar testExample = {\"foo\":\"bar\"};\n/* ]]> */\n</script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_before_with_concat() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/directory/' );

		gc_enqueue_script( 'one', '/directory/one.js' );
		gc_enqueue_script( 'two', '/directory/two.js' );
		gc_enqueue_script( 'three', '/directory/three.js' );

		gc_add_inline_script( 'one', 'console.log("before one");', 'before' );
		gc_add_inline_script( 'two', 'console.log("before two");', 'before' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' id='one-js-before'>\nconsole.log(\"before one\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/directory/one.js?ver={$ver}' id='one-js'></script>\n";
		$expected .= "<script type='text/javascript' id='two-js-before'>\nconsole.log(\"before two\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/directory/two.js?ver={$ver}' id='two-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/directory/three.js?ver={$ver}' id='three-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_before_with_concat2() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/directory/' );

		gc_enqueue_script( 'one', '/directory/one.js' );
		gc_enqueue_script( 'two', '/directory/two.js' );
		gc_enqueue_script( 'three', '/directory/three.js' );

		gc_add_inline_script( 'one', 'console.log("before one");', 'before' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' id='one-js-before'>\nconsole.log(\"before one\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/directory/one.js?ver={$ver}' id='one-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/directory/two.js?ver={$ver}' id='two-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/directory/three.js?ver={$ver}' id='three-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_after_with_concat() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/directory/' );

		gc_enqueue_script( 'one', '/directory/one.js' );
		gc_enqueue_script( 'two', '/directory/two.js' );
		gc_enqueue_script( 'three', '/directory/three.js' );
		gc_enqueue_script( 'four', '/directory/four.js' );

		gc_add_inline_script( 'two', 'console.log("after two");' );
		gc_add_inline_script( 'three', 'console.log("after three");' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=one&amp;ver={$ver}'></script>\n";
		$expected .= "<script type='text/javascript' src='/directory/two.js?ver={$ver}' id='two-js'></script>\n";
		$expected .= "<script type='text/javascript' id='two-js-after'>\nconsole.log(\"after two\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/directory/three.js?ver={$ver}' id='three-js'></script>\n";
		$expected .= "<script type='text/javascript' id='three-js-after'>\nconsole.log(\"after three\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/directory/four.js?ver={$ver}' id='four-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 14853
	 */
	public function test_gc_add_inline_script_after_and_before_with_concat_and_conditional() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/gc-admin/js/', '/gc-includes/js/' ); // Default dirs as in gc-includes/script-loader.php.

		$expected_localized  = "<!--[if gte IE 9]>\n";
		$expected_localized .= "<script type='text/javascript' id='test-example-js-extra'>\n/* <![CDATA[ */\nvar testExample = {\"foo\":\"bar\"};\n/* ]]> */\n</script>\n";
		$expected_localized .= "<![endif]-->\n";

		$expected  = "<!--[if gte IE 9]>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";
		$expected .= "<![endif]-->\n";

		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_localize_script( 'test-example', 'testExample', array( 'foo' => 'bar' ) );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_add_inline_script( 'test-example', 'console.log("after");' );
		gc_script_add_data( 'test-example', 'conditional', 'gte IE 9' );

		$this->assertSame( $expected_localized, get_echo( 'gc_print_scripts' ) );
		$this->assertSame( $expected, $gc_scripts->print_html );
		$this->assertTrue( $gc_scripts->do_concat );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_after_with_concat_and_core_dependency() {
		global $gc_scripts;

		gc_default_scripts( $gc_scripts );

		$gc_scripts->base_url  = '';
		$gc_scripts->do_concat = true;

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=jquery-core,jquery-migrate&amp;ver={$ver}'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";

		gc_enqueue_script( 'test-example', 'http://example.com', array( 'jquery' ), null );
		gc_add_inline_script( 'test-example', 'console.log("after");' );

		gc_print_scripts();
		$print_scripts = get_echo( '_print_scripts' );

		$this->assertSame( $expected, $print_scripts );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_after_with_concat_and_conditional_and_core_dependency() {
		global $gc_scripts;

		gc_default_scripts( $gc_scripts );

		$gc_scripts->base_url  = '';
		$gc_scripts->do_concat = true;

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=jquery-core,jquery-migrate&amp;ver={$ver}'></script>\n";
		$expected .= "<!--[if gte IE 9]>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-after'>\nconsole.log(\"after\");\n</script>\n";
		$expected .= "<![endif]-->\n";

		gc_enqueue_script( 'test-example', 'http://example.com', array( 'jquery' ), null );
		gc_add_inline_script( 'test-example', 'console.log("after");' );
		gc_script_add_data( 'test-example', 'conditional', 'gte IE 9' );

		gc_print_scripts();
		$print_scripts = get_echo( '_print_scripts' );

		$this->assertSame( $expected, $print_scripts );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_before_with_concat_and_core_dependency() {
		global $gc_scripts;

		gc_default_scripts( $gc_scripts );
		gc_default_packages( $gc_scripts );

		$gc_scripts->base_url  = '';
		$gc_scripts->do_concat = true;

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=jquery-core,jquery-migrate&amp;ver={$ver}'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";

		gc_enqueue_script( 'test-example', 'http://example.com', array( 'jquery' ), null );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );

		gc_print_scripts();
		$print_scripts = get_echo( '_print_scripts' );

		$this->assertSame( $expected, $print_scripts );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_before_after_concat_with_core_dependency() {
		global $gc_scripts;

		gc_default_scripts( $gc_scripts );
		gc_default_packages( $gc_scripts );

		$gc_scripts->base_url  = '';
		$gc_scripts->do_concat = true;

		if ( PHP_VERSION_ID >= 80100 ) {
			/*
			 * For the time being, ignoring PHP 8.1 "null to non-nullable" deprecations coming in
			 * via hooked in filter functions until a more structural solution to the
			 * "missing input validation" conundrum has been architected and implemented.
			 */
			$this->expectDeprecation();
			$this->expectDeprecationMessageMatches( '`Passing null to parameter \#[0-9]+ \(\$[^\)]+\) of type [^ ]+ is deprecated`' );
		}

		$ver       = get_bloginfo( 'version' );
		$suffix    = gc_scripts_get_suffix();
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=jquery-core,jquery-migrate,regenerator-runtime,gc-polyfill,gc-dom-ready,gc-hooks&amp;ver={$ver}'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example-js-before'>\nconsole.log(\"before\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/dist/i18n{$suffix}.js' id='gc-i18n-js'></script>\n";
		$expected .= "<script type='text/javascript' id='gc-i18n-js-after'>\n";
		$expected .= "gc.i18n.setLocaleData( { 'text direction\u0004ltr': [ 'ltr' ] } );\n";
		$expected .= "</script>\n";
		$expected .= "<script type='text/javascript' id='gc-a11y-js-translations'>\n";
		$expected .= "( function( domain, translations ) {\n";
		$expected .= "	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;\n";
		$expected .= "	localeData[\"\"].domain = domain;\n";
		$expected .= "	gc.i18n.setLocaleData( localeData, domain );\n";
		$expected .= "} )( \"default\", { \"locale_data\": { \"messages\": { \"\": {} } } } );\n";
		$expected .= "</script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/dist/a11y{$suffix}.js' id='gc-a11y-js'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example2.com' id='test-example2-js'></script>\n";
		$expected .= "<script type='text/javascript' id='test-example2-js-after'>\nconsole.log(\"after\");\n</script>\n";

		gc_enqueue_script( 'test-example', 'http://example.com', array( 'jquery' ), null );
		gc_add_inline_script( 'test-example', 'console.log("before");', 'before' );
		gc_enqueue_script( 'test-example2', 'http://example2.com', array( 'gc-a11y' ), null );
		gc_add_inline_script( 'test-example2', 'console.log("after");', 'after' );

		// Effectively ignore the output until retrieving it later via `getActualOutput()`.
		$this->expectOutputRegex( '`.`' );

		gc_print_scripts();
		_print_scripts();
		$print_scripts = $this->getActualOutput();

		/*
		 * We've replaced gc-a11y.js with @gechiui/a11y package (see #45066),
		 * and `gc-polyfill` is now a dependency of the packaged gc-a11y.
		 * The packaged scripts contain various version numbers, which are not exposed,
		 * so we will remove all version args from the output.
		 */
		$print_scripts = preg_replace(
			'~js\?ver=([^"\']*)~', // Matches `js?ver=X.X.X` and everything to single or double quote.
			'js',                  // The replacement, `js` without the version arg.
			$print_scripts         // Printed scripts.
		);

		$this->assertSameIgnoreEOL( $expected, $print_scripts );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_customize_dependency() {
		global $gc_scripts;

		gc_default_scripts( $gc_scripts );
		gc_default_packages( $gc_scripts );

		$gc_scripts->base_url  = '';
		$gc_scripts->do_concat = true;

		if ( PHP_VERSION_ID >= 80100 ) {
			/*
			 * For the time being, ignoring PHP 8.1 "null to non-nullable" deprecations coming in
			 * via hooked in filter functions until a more structural solution to the
			 * "missing input validation" conundrum has been architected and implemented.
			 */
			$this->expectDeprecation();
			$this->expectDeprecationMessageMatches( '`Passing null to parameter \#[0-9]+ \(\$[^\)]+\) of type [^ ]+ is deprecated`' );
		}

		$expected_tail  = "<script type='text/javascript' src='/customize-dependency.js' id='customize-dependency-js'></script>\n";
		$expected_tail .= "<script type='text/javascript' id='customize-dependency-js-after'>\n";
		$expected_tail .= "tryCustomizeDependency()\n";
		$expected_tail .= "</script>\n";

		$handle = 'customize-dependency';
		gc_enqueue_script( $handle, '/customize-dependency.js', array( 'customize-controls' ), null );
		gc_add_inline_script( $handle, 'tryCustomizeDependency()' );

		// Effectively ignore the output until retrieving it later via `getActualOutput()`.
		$this->expectOutputRegex( '`.`' );

		gc_print_scripts();
		_print_scripts();
		$print_scripts = $this->getActualOutput();

		$tail = substr( $print_scripts, strrpos( $print_scripts, "<script type='text/javascript' src='/customize-dependency.js' id='customize-dependency-js'>" ) );
		$this->assertSame( $expected_tail, $tail );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_after_for_core_scripts_with_concat_is_limited_and_falls_back_to_no_concat() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/gc-admin/js/', '/gc-includes/js/' ); // Default dirs as in gc-includes/script-loader.php.

		gc_enqueue_script( 'one', '/gc-includes/js/script.js' );
		gc_enqueue_script( 'two', '/gc-includes/js/script2.js', array( 'one' ) );
		gc_add_inline_script( 'one', 'console.log("after one");', 'after' );
		gc_enqueue_script( 'three', '/gc-includes/js/script3.js' );
		gc_enqueue_script( 'four', '/gc-includes/js/script4.js' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-includes/js/script.js?ver={$ver}' id='one-js'></script>\n";
		$expected .= "<script type='text/javascript' id='one-js-after'>\nconsole.log(\"after one\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script2.js?ver={$ver}' id='two-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script3.js?ver={$ver}' id='three-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script4.js?ver={$ver}' id='four-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 36392
	 */
	public function test_gc_add_inline_script_before_third_core_script_prints_two_concat_scripts() {
		global $gc_scripts;

		$gc_scripts->do_concat    = true;
		$gc_scripts->default_dirs = array( '/gc-admin/js/', '/gc-includes/js/' ); // Default dirs as in gc-includes/script-loader.php.

		gc_enqueue_script( 'one', '/gc-includes/js/script.js' );
		gc_enqueue_script( 'two', '/gc-includes/js/script2.js', array( 'one' ) );
		gc_enqueue_script( 'three', '/gc-includes/js/script3.js' );
		gc_add_inline_script( 'three', 'console.log("before three");', 'before' );
		gc_enqueue_script( 'four', '/gc-includes/js/script4.js' );

		$ver       = get_bloginfo( 'version' );
		$expected  = "<script type='text/javascript' src='/gc-admin/load-scripts.php?c=0&amp;load%5Bchunk_0%5D=one,two&amp;ver={$ver}'></script>\n";
		$expected .= "<script type='text/javascript' id='three-js-before'>\nconsole.log(\"before three\");\n</script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script3.js?ver={$ver}' id='three-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script4.js?ver={$ver}' id='four-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'test-example', '/gc-includes/js/script.js', array(), null );
		gc_set_script_translations( 'test-example', 'default', DIR_TESTDATA . '/languages' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'default',
				'test-example',
				file_get_contents( DIR_TESTDATA . '/languages/en_US-813e104eb47e13dd4cc5af844c618754.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script.js' id='test-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_for_plugin() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'plugin-example', '/gc-content/plugins/my-plugin/js/script.js', array(), null );
		gc_set_script_translations( 'plugin-example', 'internationalized-plugin', DIR_TESTDATA . '/languages/plugins' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'internationalized-plugin',
				'plugin-example',
				file_get_contents( DIR_TESTDATA . '/languages/plugins/internationalized-plugin-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-content/plugins/my-plugin/js/script.js' id='plugin-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_for_theme() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'theme-example', '/gc-content/themes/my-theme/js/script.js', array(), null );
		gc_set_script_translations( 'theme-example', 'internationalized-theme', DIR_TESTDATA . '/languages/themes' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'internationalized-theme',
				'theme-example',
				file_get_contents( DIR_TESTDATA . '/languages/themes/internationalized-theme-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-content/themes/my-theme/js/script.js' id='theme-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_with_handle_file() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'script-handle', '/gc-admin/js/script.js', array(), null );
		gc_set_script_translations( 'script-handle', 'admin', DIR_TESTDATA . '/languages/' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'admin',
				'script-handle',
				file_get_contents( DIR_TESTDATA . '/languages/admin-en_US-script-handle.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-admin/js/script.js' id='script-handle-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_i18n_dependency() {
		global $gc_scripts;

		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'test-example', '/gc-includes/js/script.js', array(), null );
		gc_set_script_translations( 'test-example', 'default', DIR_TESTDATA . '/languages/' );

		$script = $gc_scripts->registered['test-example'];

		$this->assertContains( 'gc-i18n', $script->deps );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_when_translation_file_does_not_exist() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_enqueue_script( 'test-example', '/gc-admin/js/script.js', array(), null );
		gc_set_script_translations( 'test-example', 'admin', DIR_TESTDATA . '/languages/' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'admin',
				'test-example',
				'{ "locale_data": { "messages": { "": {} } } }',
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-admin/js/script.js' id='test-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_after_register() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_register_script( 'test-example', '/gc-includes/js/script.js', array(), null );
		gc_set_script_translations( 'test-example', 'default', DIR_TESTDATA . '/languages' );

		gc_enqueue_script( 'test-example' );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'default',
				'test-example',
				file_get_contents( DIR_TESTDATA . '/languages/en_US-813e104eb47e13dd4cc5af844c618754.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script.js' id='test-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * @ticket 45103
	 */
	public function test_gc_set_script_translations_dependency() {
		gc_register_script( 'gc-i18n', '/gc-includes/js/dist/gc-i18n.js', array(), null );
		gc_register_script( 'test-dependency', '/gc-includes/js/script.js', array(), null );
		gc_set_script_translations( 'test-dependency', 'default', DIR_TESTDATA . '/languages' );

		gc_enqueue_script( 'test-example', '/gc-includes/js/script2.js', array( 'test-dependency' ), null );

		$expected  = "<script type='text/javascript' src='/gc-includes/js/dist/gc-i18n.js' id='gc-i18n-js'></script>\n";
		$expected .= str_replace(
			array(
				'__DOMAIN__',
				'__HANDLE__',
				'__JSON_TRANSLATIONS__',
			),
			array(
				'default',
				'test-dependency',
				file_get_contents( DIR_TESTDATA . '/languages/en_US-813e104eb47e13dd4cc5af844c618754.json' ),
			),
			$this->gc_scripts_print_translations_output
		);
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script.js' id='test-dependency-js'></script>\n";
		$expected .= "<script type='text/javascript' src='/gc-includes/js/script2.js' id='test-example-js'></script>\n";

		$this->assertSameIgnoreEOL( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Testing `gc_enqueue_code_editor` with file path.
	 *
	 * @ticket 41871
	 * @covers ::gc_enqueue_code_editor
	 */
	public function test_gc_enqueue_code_editor_when_php_file_will_be_passed() {
		$real_file              = GC_PLUGIN_DIR . '/hello.php';
		$gc_enqueue_code_editor = gc_enqueue_code_editor( array( 'file' => $real_file ) );
		$this->assertNonEmptyMultidimensionalArray( $gc_enqueue_code_editor );

		$this->assertSameSets( array( 'codemirror', 'csslint', 'jshint', 'htmlhint' ), array_keys( $gc_enqueue_code_editor ) );
		$this->assertSameSets(
			array(
				'autoCloseBrackets',
				'autoCloseTags',
				'continueComments',
				'direction',
				'extraKeys',
				'indentUnit',
				'indentWithTabs',
				'inputStyle',
				'lineNumbers',
				'lineWrapping',
				'matchBrackets',
				'matchTags',
				'mode',
				'styleActiveLine',
				'gutters',
			),
			array_keys( $gc_enqueue_code_editor['codemirror'] )
		);
		$this->assertEmpty( $gc_enqueue_code_editor['codemirror']['gutters'] );

		$this->assertSameSets(
			array(
				'errors',
				'box-model',
				'display-property-grouping',
				'duplicate-properties',
				'known-properties',
				'outline-none',
			),
			array_keys( $gc_enqueue_code_editor['csslint'] )
		);

		$this->assertSameSets(
			array(
				'boss',
				'curly',
				'eqeqeq',
				'eqnull',
				'es3',
				'expr',
				'immed',
				'noarg',
				'nonbsp',
				'onevar',
				'quotmark',
				'trailing',
				'undef',
				'unused',
				'browser',
				'globals',
			),
			array_keys( $gc_enqueue_code_editor['jshint'] )
		);

		$this->assertSameSets(
			array(
				'tagname-lowercase',
				'attr-lowercase',
				'attr-value-double-quotes',
				'doctype-first',
				'tag-pair',
				'spec-char-escape',
				'id-unique',
				'src-not-empty',
				'attr-no-duplication',
				'alt-require',
				'space-tab-mixed-disabled',
				'attr-unsafe-chars',
			),
			array_keys( $gc_enqueue_code_editor['htmlhint'] )
		);
	}

	/**
	 * Testing `gc_enqueue_code_editor` with `compact`.
	 *
	 * @ticket 41871
	 * @covers ::gc_enqueue_code_editor
	 */
	public function test_gc_enqueue_code_editor_when_generated_array_by_compact_will_be_passed() {
		$file                   = '';
		$gc_enqueue_code_editor = gc_enqueue_code_editor( compact( 'file' ) );
		$this->assertNonEmptyMultidimensionalArray( $gc_enqueue_code_editor );

		$this->assertSameSets( array( 'codemirror', 'csslint', 'jshint', 'htmlhint' ), array_keys( $gc_enqueue_code_editor ) );
		$this->assertSameSets(
			array(
				'continueComments',
				'direction',
				'extraKeys',
				'indentUnit',
				'indentWithTabs',
				'inputStyle',
				'lineNumbers',
				'lineWrapping',
				'mode',
				'styleActiveLine',
				'gutters',
			),
			array_keys( $gc_enqueue_code_editor['codemirror'] )
		);
		$this->assertEmpty( $gc_enqueue_code_editor['codemirror']['gutters'] );

		$this->assertSameSets(
			array(
				'errors',
				'box-model',
				'display-property-grouping',
				'duplicate-properties',
				'known-properties',
				'outline-none',
			),
			array_keys( $gc_enqueue_code_editor['csslint'] )
		);

		$this->assertSameSets(
			array(
				'boss',
				'curly',
				'eqeqeq',
				'eqnull',
				'es3',
				'expr',
				'immed',
				'noarg',
				'nonbsp',
				'onevar',
				'quotmark',
				'trailing',
				'undef',
				'unused',
				'browser',
				'globals',
			),
			array_keys( $gc_enqueue_code_editor['jshint'] )
		);

		$this->assertSameSets(
			array(
				'tagname-lowercase',
				'attr-lowercase',
				'attr-value-double-quotes',
				'doctype-first',
				'tag-pair',
				'spec-char-escape',
				'id-unique',
				'src-not-empty',
				'attr-no-duplication',
				'alt-require',
				'space-tab-mixed-disabled',
				'attr-unsafe-chars',
			),
			array_keys( $gc_enqueue_code_editor['htmlhint'] )
		);
	}

	/**
	 * Testing `gc_enqueue_code_editor` with `array_merge`.
	 *
	 * @ticket 41871
	 * @covers ::gc_enqueue_code_editor
	 */
	public function test_gc_enqueue_code_editor_when_generated_array_by_array_merge_will_be_passed() {
		$gc_enqueue_code_editor = gc_enqueue_code_editor(
			array_merge(
				array(
					'type'       => 'text/css',
					'codemirror' => array(
						'indentUnit' => 2,
						'tabSize'    => 2,
					),
				),
				array()
			)
		);

		$this->assertNonEmptyMultidimensionalArray( $gc_enqueue_code_editor );

		$this->assertSameSets( array( 'codemirror', 'csslint', 'jshint', 'htmlhint' ), array_keys( $gc_enqueue_code_editor ) );
		$this->assertSameSets(
			array(
				'autoCloseBrackets',
				'continueComments',
				'direction',
				'extraKeys',
				'gutters',
				'indentUnit',
				'indentWithTabs',
				'inputStyle',
				'lineNumbers',
				'lineWrapping',
				'lint',
				'matchBrackets',
				'mode',
				'styleActiveLine',
				'tabSize',
			),
			array_keys( $gc_enqueue_code_editor['codemirror'] )
		);

		$this->assertSameSets(
			array(
				'errors',
				'box-model',
				'display-property-grouping',
				'duplicate-properties',
				'known-properties',
				'outline-none',
			),
			array_keys( $gc_enqueue_code_editor['csslint'] )
		);

		$this->assertSameSets(
			array(
				'boss',
				'curly',
				'eqeqeq',
				'eqnull',
				'es3',
				'expr',
				'immed',
				'noarg',
				'nonbsp',
				'onevar',
				'quotmark',
				'trailing',
				'undef',
				'unused',
				'browser',
				'globals',
			),
			array_keys( $gc_enqueue_code_editor['jshint'] )
		);

		$this->assertSameSets(
			array(
				'tagname-lowercase',
				'attr-lowercase',
				'attr-value-double-quotes',
				'doctype-first',
				'tag-pair',
				'spec-char-escape',
				'id-unique',
				'src-not-empty',
				'attr-no-duplication',
				'alt-require',
				'space-tab-mixed-disabled',
				'attr-unsafe-chars',
			),
			array_keys( $gc_enqueue_code_editor['htmlhint'] )
		);
	}

	/**
	 * Testing `gc_enqueue_code_editor` with `array`.
	 *
	 * @ticket 41871
	 * @covers ::gc_enqueue_code_editor
	 */
	public function test_gc_enqueue_code_editor_when_simple_array_will_be_passed() {
		$gc_enqueue_code_editor = gc_enqueue_code_editor(
			array(
				'type'       => 'text/css',
				'codemirror' => array(
					'indentUnit' => 2,
					'tabSize'    => 2,
				),
			)
		);

		$this->assertNonEmptyMultidimensionalArray( $gc_enqueue_code_editor );

		$this->assertSameSets( array( 'codemirror', 'csslint', 'jshint', 'htmlhint' ), array_keys( $gc_enqueue_code_editor ) );
		$this->assertSameSets(
			array(
				'autoCloseBrackets',
				'continueComments',
				'direction',
				'extraKeys',
				'gutters',
				'indentUnit',
				'indentWithTabs',
				'inputStyle',
				'lineNumbers',
				'lineWrapping',
				'lint',
				'matchBrackets',
				'mode',
				'styleActiveLine',
				'tabSize',
			),
			array_keys( $gc_enqueue_code_editor['codemirror'] )
		);

		$this->assertSameSets(
			array(
				'errors',
				'box-model',
				'display-property-grouping',
				'duplicate-properties',
				'known-properties',
				'outline-none',
			),
			array_keys( $gc_enqueue_code_editor['csslint'] )
		);

		$this->assertSameSets(
			array(
				'boss',
				'curly',
				'eqeqeq',
				'eqnull',
				'es3',
				'expr',
				'immed',
				'noarg',
				'nonbsp',
				'onevar',
				'quotmark',
				'trailing',
				'undef',
				'unused',
				'browser',
				'globals',
			),
			array_keys( $gc_enqueue_code_editor['jshint'] )
		);

		$this->assertSameSets(
			array(
				'tagname-lowercase',
				'attr-lowercase',
				'attr-value-double-quotes',
				'doctype-first',
				'tag-pair',
				'spec-char-escape',
				'id-unique',
				'src-not-empty',
				'attr-no-duplication',
				'alt-require',
				'space-tab-mixed-disabled',
				'attr-unsafe-chars',
			),
			array_keys( $gc_enqueue_code_editor['htmlhint'] )
		);
	}

	/**
	 * @ticket 52534
	 * @covers ::gc_localize_script
	 *
	 * @dataProvider data_gc_localize_script_data_formats
	 *
	 * @param mixed  $l10n_data Localization data passed to gc_localize_script().
	 * @param string $expected  Expected transformation of localization data.
	 * @param string $warning   Optional. Whether a PHP native warning/error is expected. Default false.
	 */
	public function test_gc_localize_script_data_formats( $l10n_data, $expected, $warning = false ) {
		if ( $warning ) {
			if ( PHP_VERSION_ID < 80000 ) {
				$this->expectWarning();
			} else {
				$this->expectError();
			}
		}

		if ( ! is_array( $l10n_data ) ) {
			$this->setExpectedIncorrectUsage( 'GC_Scripts::localize' );
		}

		gc_enqueue_script( 'test-example', 'example.com', array(), null );
		gc_localize_script( 'test-example', 'testExample', $l10n_data );

		$expected  = "<script type='text/javascript' id='test-example-js-extra'>\n/* <![CDATA[ */\nvar testExample = {$expected};\n/* ]]> */\n</script>\n";
		$expected .= "<script type='text/javascript' src='http://example.com' id='test-example-js'></script>\n";

		$this->assertSame( $expected, get_echo( 'gc_print_scripts' ) );
	}

	/**
	 * Data provider for test_gc_localize_script_data_formats().
	 *
	 * @return array[] {
	 *     Array of arguments for test.
	 *
	 *     @type mixed  $l10n_data Localization data passed to gc_localize_script().
	 *     @type string $expected  Expected transformation of localization data.
	 *     @type string $warning   Optional. Whether a PHP native warning/error is expected.
	 * }
	 */
	public function data_gc_localize_script_data_formats() {
		return array(
			// Officially supported formats.
			array( array( 'array value, no key' ), '["array value, no key"]' ),
			array( array( 'foo' => 'bar' ), '{"foo":"bar"}' ),
			array( array( 'foo' => array( 'bar' => 'foobar' ) ), '{"foo":{"bar":"foobar"}}' ),
			array( array( 'foo' => 6.6 ), '{"foo":"6.6"}' ),
			array( array( 'foo' => 6 ), '{"foo":"6"}' ),

			// Unofficially supported format.
			array( 'string', '"string"' ),

			// Unsupported formats.
			array( 1.5, '1.5', true ),
			array( 1, '1', true ),
			array( false, '[""]' ),
		);
	}
}
