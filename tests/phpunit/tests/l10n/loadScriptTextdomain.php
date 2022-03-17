<?php

/**
 * @group l10n
 * @group i18n
 */
class Tests_L10n_LoadScriptTextdomain extends GC_UnitTestCase {

	/**
	 * @ticket 45528
	 * @ticket 46336
	 * @ticket 46387
	 * @ticket 49145
	 *
	 * @dataProvider data_test_resolve_relative_path
	 */
	public function test_resolve_relative_path( $translation_path, $handle, $src, $textdomain, $filter = array() ) {
		if ( ! empty( $filter ) ) {
			add_filter( $filter[0], $filter[1], 10, isset( $filter[2] ) ? $filter[2] : 1 );
		}
		gc_enqueue_script( $handle, $src, array(), null );

		$expected = file_get_contents( DIR_TESTDATA . $translation_path );
		$this->assertSame( $expected, load_script_textdomain( $handle, $textdomain, DIR_TESTDATA . '/languages' ) );
	}

	public function data_test_resolve_relative_path() {
		return array(
			// @ticket 45528
			array(
				'/languages/en_US-813e104eb47e13dd4cc5af844c618754.json',
				'test-example-root',
				'/gc-includes/js/script.js',
				'default',
			),
			// Assets on a CDN.
			array(
				'/languages/en_US-813e104eb47e13dd4cc5af844c618754.json',
				'test-example-cdn',
				'https://my-cdn.com/gechiui/gc-includes/js/script.js',
				'default',
				array( 'load_script_textdomain_relative_path', array( $this, 'relative_path_from_cdn' ), 2 ),
			),
			// Test for GeChiUI installs in a subdirectory.
			array(
				'/languages/en_US-813e104eb47e13dd4cc5af844c618754.json',
				'test-example-subdir',
				'/gc/gc-includes/js/script.js',
				'default',
				array(
					'site_url',
					static function ( $site_url ) {
						return $site_url . '/gc';
					},
				),
			),
			// @ticket 46336
			array(
				'/languages/plugins/internationalized-plugin-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json',
				'plugin-example-1',
				'https://plugins.example.com/my-plugin/js/script.js',
				'internationalized-plugin',
				array(
					'plugins_url',
					static function () {
						return 'https://plugins.example.com';
					},
				),
			),
			// @ticket 46387
			array(
				'/languages/plugins/internationalized-plugin-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json',
				'plugin-example-2',
				'https://content.example.com/plugins/my-plugin/js/script.js',
				'internationalized-plugin',
				array(
					'content_url',
					static function () {
						return 'https://content.example.com';
					},
				),
			),
			// @ticket 49145
			array(
				'/languages/plugins/internationalized-plugin-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json',
				'test-when-no-content_url-host',
				'https://content.example.com/plugins/my-plugin/js/script.js',
				'internationalized-plugin',
				array(
					'content_url',
					static function () {
						return '/';
					},
				),
			),
			// @ticket 49145
			array(
				'/languages/plugins/internationalized-plugin-en_US-2f86cb96a0233e7cb3b6f03ad573be0b.json',
				'test-when-no-plugins_url-host',
				'https://plugins.example.com/my-plugin/js/script.js',
				'internationalized-plugin',
				array(
					'plugins_url',
					static function () {
						return '/';
					},
				),
			),
			// @ticket 49145
			array(
				'/languages/en_US-813e104eb47e13dd4cc5af844c618754.json',
				'test-when-no-site_url-host',
				'/gc/gc-includes/js/script.js',
				'default',
				array(
					'site_url',
					static function () {
						return '/gc';
					},
				),
			),
		);
	}

	public function relative_path_from_cdn( $relative, $src ) {
		if ( 0 === strpos( $src, 'https://my-cdn.com/gechiui/' ) ) {
			return substr( $src, strlen( 'https://my-cdn.com/gechiui/' ) );
		}

		return $relative;
	}
}
