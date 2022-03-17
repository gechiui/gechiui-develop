<?php

/**
 * @group gc
 * @covers GC::parse_request
 */
class Tests_GC_ParseRequest extends GC_UnitTestCase {
	/**
	 * @var GC
	 */
	protected $gc;

	public function set_up() {
		parent::set_up();
		$this->gc = new GC();
	}

	/**
	 * Test that PHP 8.1 "passing null to non-nullable" deprecation notice
	 * is not thrown when the home URL has no path/trailing slash (default setup).
	 *
	 * Note: This does not test the actual functioning of the parse_request() method.
	 * It just and only tests for/against the deprecation notice.
	 *
	 * @ticket 53635
	 */
	public function test_no_deprecation_notice_when_home_url_has_no_path() {
		// Make sure rewrite rules are not empty.
		$this->set_permalink_structure( '/%year%/%monthnum%/%postname%/' );

		// Make sure the test will function independently of whatever the test user set in gc-tests-config.php.
		add_filter(
			'home_url',
			static function ( $url ) {
				return 'http://example.org';
			}
		);

		$this->gc->parse_request();
		$this->assertSame( '', $this->gc->request );
	}
}
