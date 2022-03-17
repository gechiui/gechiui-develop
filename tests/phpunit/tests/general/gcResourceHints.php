<?php

/**
 * @group general
 * @group template
 * @ticket 34292
 * @covers ::gc_resource_hints
 */
class Tests_General_gcResourceHints extends GC_UnitTestCase {
	private $old_gc_scripts;
	private $old_gc_styles;

	public function set_up() {
		parent::set_up();
		$this->old_gc_scripts = isset( $GLOBALS['gc_scripts'] ) ? $GLOBALS['gc_scripts'] : null;
		$this->old_gc_styles  = isset( $GLOBALS['gc_styles'] ) ? $GLOBALS['gc_styles'] : null;

		remove_action( 'gc_default_scripts', 'gc_default_scripts' );
		remove_action( 'gc_default_styles', 'gc_default_styles' );

		$GLOBALS['gc_scripts']                  = new GC_Scripts();
		$GLOBALS['gc_scripts']->default_version = get_bloginfo( 'version' );
		$GLOBALS['gc_styles']                   = new GC_Styles();
		$GLOBALS['gc_styles']->default_version  = get_bloginfo( 'version' );
	}

	public function tear_down() {
		$GLOBALS['gc_scripts'] = $this->old_gc_scripts;
		$GLOBALS['gc_styles']  = $this->old_gc_styles;
		parent::tear_down();
	}

	public function test_should_have_defaults_on_frontend() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n";

		$this->expectOutputString( $expected );

		gc_resource_hints();
	}

	public function test_dns_prefetching() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n" .
					"<link rel='dns-prefetch' href='//www.gechiui.com' />\n" .
					"<link rel='dns-prefetch' href='//google.com' />\n" .
					"<link rel='dns-prefetch' href='//make.gechiui.com' />\n";

		add_filter( 'gc_resource_hints', array( $this, 'add_dns_prefetch_domains' ), 10, 2 );

		$actual = get_echo( 'gc_resource_hints' );

		remove_filter( 'gc_resource_hints', array( $this, 'add_dns_prefetch_domains' ) );

		$this->assertSame( $expected, $actual );
	}

	public function add_dns_prefetch_domains( $hints, $method ) {
		if ( 'dns-prefetch' === $method ) {
			$hints[] = 'http://www.gechiui.com';
			$hints[] = 'https://www.gechiui.com';
			$hints[] = 'htps://www.gechiui.com'; // Invalid URLs should be skipped.
			$hints[] = 'https://google.com';
			$hints[] = '//make.gechiui.com';
			$hints[] = 'https://www.gechiui.com/plugins/';
		}

		return $hints;
	}

	/**
	 * @ticket 37652
	 */
	public function test_preconnect() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n" .
					"<link rel='preconnect' href='//www.gechiui.com' />\n" .
					"<link rel='preconnect' href='https://make.gechiui.com' />\n" .
					"<link rel='preconnect' href='http://google.com' />\n" .
					"<link rel='preconnect' href='http://w.org' />\n";

		add_filter( 'gc_resource_hints', array( $this, 'add_preconnect_domains' ), 10, 2 );

		$actual = get_echo( 'gc_resource_hints' );

		remove_filter( 'gc_resource_hints', array( $this, 'add_preconnect_domains' ) );

		$this->assertSame( $expected, $actual );
	}

	public function add_preconnect_domains( $hints, $method ) {
		if ( 'preconnect' === $method ) {
			$hints[] = '//www.gechiui.com';
			$hints[] = 'https://make.gechiui.com';
			$hints[] = 'htps://example.com'; // Invalid URLs should be skipped.
			$hints[] = 'http://google.com';
			$hints[] = 'w.org';
		}

		return $hints;
	}

	public function test_prerender() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n" .
					"<link rel='prerender' href='https://make.gechiui.com/great-again' />\n" .
					"<link rel='prerender' href='http://jobs.gechiui.net' />\n" .
					"<link rel='prerender' href='//core.trac.gechiui.com' />\n";

		add_filter( 'gc_resource_hints', array( $this, 'add_prerender_urls' ), 10, 2 );

		$actual = get_echo( 'gc_resource_hints' );

		remove_filter( 'gc_resource_hints', array( $this, 'add_prerender_urls' ) );

		$this->assertSame( $expected, $actual );
	}

	public function add_prerender_urls( $hints, $method ) {
		if ( 'prerender' === $method ) {
			$hints[] = 'https://make.gechiui.com/great-again';
			$hints[] = 'http://jobs.gechiui.net';
			$hints[] = '//core.trac.gechiui.com';
			$hints[] = 'htps://www.gechiui.com'; // Invalid URLs should be skipped.
		}

		return $hints;
	}

	public function test_parse_url_dns_prefetch() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n" .
					"<link rel='dns-prefetch' href='//make.gechiui.com' />\n";

		add_filter( 'gc_resource_hints', array( $this, 'add_dns_prefetch_long_urls' ), 10, 2 );

		$actual = get_echo( 'gc_resource_hints' );

		remove_filter( 'gc_resource_hints', array( $this, 'add_dns_prefetch_long_urls' ) );

		$this->assertSame( $expected, $actual );
	}

	public function add_dns_prefetch_long_urls( $hints, $method ) {
		if ( 'dns-prefetch' === $method ) {
			$hints[] = 'http://make.gechiui.com/gc-includes/css/editor.css';
		}

		return $hints;
	}

	public function test_dns_prefetch_styles() {
		$expected = "<link rel='dns-prefetch' href='//fonts.googleapis.com' />\n" .
					"<link rel='dns-prefetch' href='//s.w.org' />\n";

		$args = array(
			'family' => 'Open+Sans:400',
			'subset' => 'latin',
		);

		gc_enqueue_style( 'googlefonts', add_query_arg( $args, '//fonts.googleapis.com/css' ) );

		$actual = get_echo( 'gc_resource_hints' );

		gc_dequeue_style( 'googlefonts' );

		$this->assertSame( $expected, $actual );

	}

	public function test_dns_prefetch_scripts() {
		$expected = "<link rel='dns-prefetch' href='//fonts.googleapis.com' />\n" .
					"<link rel='dns-prefetch' href='//s.w.org' />\n";

		$args = array(
			'family' => 'Open+Sans:400',
			'subset' => 'latin',
		);

		gc_enqueue_script( 'googlefonts', add_query_arg( $args, '//fonts.googleapis.com/css' ) );

		$actual = get_echo( 'gc_resource_hints' );

		gc_dequeue_style( 'googlefonts' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @ticket 37385
	 */
	public function test_dns_prefetch_scripts_does_not_include_registered_only() {
		$expected   = "<link rel='dns-prefetch' href='//s.w.org' />\n";
		$unexpected = "<link rel='dns-prefetch' href='//www.gechiui.com' />\n";

		gc_register_script( 'jquery-elsewhere', 'https://www.gechiui.com/gc-includes/js/jquery/jquery.js' );

		$actual = get_echo( 'gc_resource_hints' );

		gc_deregister_script( 'jquery-elsewhere' );

		$this->assertSame( $expected, $actual );
		$this->assertStringNotContainsString( $unexpected, $actual );
	}

	/**
	 * @ticket 37502
	 */
	public function test_deregistered_scripts_are_ignored() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n";

		gc_enqueue_script( 'test-script', 'http://example.org/script.js' );
		gc_deregister_script( 'test-script' );

		$actual = get_echo( 'gc_resource_hints' );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * @ticket 37652
	 */
	public function test_malformed_urls() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n";

		// Errant colon.
		add_filter( 'gc_resource_hints', array( $this, 'add_malformed_url_errant_colon' ), 10, 2 );
		$actual = get_echo( 'gc_resource_hints' );
		remove_filter( 'gc_resource_hints', array( $this, 'add_malformed_url_errant_colon' ) );
		$this->assertSame( $expected, $actual );

		// Unsupported Scheme.
		add_filter( 'gc_resource_hints', array( $this, 'add_malformed_url_unsupported_scheme' ), 10, 2 );
		$actual = get_echo( 'gc_resource_hints' );
		remove_filter( 'gc_resource_hints', array( $this, 'add_malformed_url_unsupported_scheme' ) );
		$this->assertSame( $expected, $actual );
	}

	public function add_malformed_url_errant_colon( $hints, $method ) {
		if ( 'preconnect' === $method ) {
			$hints[] = '://core.trac.gechiui.com/ticket/37652';
		}

		return $hints;
	}

	public function add_malformed_url_unsupported_scheme( $hints, $method ) {
		if ( 'preconnect' === $method ) {
			$hints[] = 'git://develop.git.gechiui.com/';
		}

		return $hints;
	}

	/**
	 * @ticket 38121
	 */
	public function test_custom_attributes() {
		$expected = "<link rel='dns-prefetch' href='//s.w.org' />\n" .
					"<link rel='preconnect' href='https://make.gechiui.com' />\n" .
					"<link crossorigin as='image' pr='0.5' href='https://example.com/foo.jpeg' rel='prefetch' />\n" .
					"<link crossorigin='use-credentials' as='style' href='https://example.com/foo.css' rel='prefetch' />\n" .
					"<link href='http://www.gechiui.com' rel='prerender' />\n";

		add_filter( 'gc_resource_hints', array( $this, 'add_url_with_attributes' ), 10, 2 );

		$actual = get_echo( 'gc_resource_hints' );

		remove_filter( 'gc_resource_hints', array( $this, 'add_url_with_attributes' ) );

		$this->assertSame( $expected, $actual );
	}

	public function add_url_with_attributes( $hints, $method ) {
		// Ignore hints with missing href attributes.
		$hints[] = array(
			'rel' => 'foo',
		);

		if ( 'preconnect' === $method ) {
			// Should ignore rel attributes.
			$hints[] = array(
				'rel'  => 'foo',
				'href' => 'https://make.gechiui.com/great-again',
			);
		} elseif ( 'prefetch' === $method ) {
			$hints[] = array(
				'crossorigin',
				'as'   => 'image',
				'pr'   => 0.5,
				'href' => 'https://example.com/foo.jpeg',
			);
			$hints[] = array(
				'crossorigin' => 'use-credentials',
				'as'          => 'style',
				'href'        => 'https://example.com/foo.css',
			);
		} elseif ( 'prerender' === $method ) {
			// Ignore invalid attributes.
			$hints[] = array(
				'foo'  => 'bar',
				'bar'  => 'baz',
				'href' => 'http://www.gechiui.com',
			);
		}

		return $hints;
	}
}
