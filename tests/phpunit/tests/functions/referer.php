<?php

/**
 * Test gc_get_referer().
 *
 * @group functions.php
 * @covers ::gc_get_referer
 * @covers ::gc_get_raw_referer
 */
class Tests_Functions_Referer extends GC_UnitTestCase {

	public function set_up() {
		parent::set_up();

		$_SERVER['HTTP_REFERER']      = '';
		$_SERVER['REQUEST_URI']       = '';
		$_REQUEST['_gc_http_referer'] = '';
	}

	public function tear_down() {
		$_SERVER['HTTP_REFERER']      = '';
		$_SERVER['REQUEST_URI']       = '';
		$_REQUEST['_gc_http_referer'] = '';

		parent::tear_down();
	}

	public function _fake_subfolder_install() {
		return 'http://' . GC_TESTS_DOMAIN . '/subfolder';
	}

	public function filter_allowed_redirect_hosts( $hosts ) {
		$hosts[] = 'another.' . GC_TESTS_DOMAIN;

		return $hosts;
	}

	public function test_from_request_relative_referrer() {
		$_REQUEST['_gc_http_referer'] = addslashes( '/test.php?id=123' );
		$_SERVER['REQUEST_URI']       = addslashes( '/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );
	}

	public function test_from_request_same_url() {
		$_REQUEST['_gc_http_referer'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/test.php?id=123' );
		$_SERVER['REQUEST_URI']       = addslashes( '/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );
	}

	public function test_from_request_different_resource() {
		$_REQUEST['_gc_http_referer'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/another.php?id=123' );
		$_SERVER['REQUEST_URI']       = addslashes( '/test.php?id=123' );
		$this->assertSame( 'http://' . GC_TESTS_DOMAIN . '/another.php?id=123', gc_get_referer() );
	}

	public function test_from_request_different_query_args() {
		$_REQUEST['_gc_http_referer'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/test.php?another=555' );
		$_SERVER['REQUEST_URI']       = addslashes( '/test.php?id=123' );
		$this->assertSame( 'http://' . GC_TESTS_DOMAIN . '/test.php?another=555', gc_get_referer() );
	}

	/**
	 * @ticket 19856
	 */
	public function test_from_request_subfolder_install() {
		add_filter( 'site_url', array( $this, '_fake_subfolder_install' ) );

		$_REQUEST['_gc_http_referer'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/subfolder/test.php?id=123' );
		$_SERVER['REQUEST_URI']       = addslashes( '/subfolder/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );

		remove_filter( 'site_url', array( $this, '_fake_subfolder_install' ) );
	}

	/**
	 * @ticket 19856
	 */
	public function test_from_request_subfolder_install_different_resource() {
		add_filter( 'site_url', array( $this, '_fake_subfolder_install' ) );

		$_REQUEST['_gc_http_referer'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/subfolder/another.php?id=123' );
		$_SERVER['REQUEST_URI']       = addslashes( '/subfolder/test.php?id=123' );
		$this->assertSame( 'http://' . GC_TESTS_DOMAIN . '/subfolder/another.php?id=123', gc_get_referer() );

		remove_filter( 'site_url', array( $this, '_fake_subfolder_install' ) );
	}

	public function test_relative_referrer() {
		$_REQUEST['HTTP_REFERER'] = addslashes( '/test.php?id=123' );
		$_SERVER['REQUEST_URI']   = addslashes( '/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );
	}

	public function test_same_url() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/test.php?id=123' );
		$_SERVER['REQUEST_URI']  = addslashes( '/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );
	}

	public function test_different_resource() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://' . GC_TESTS_DOMAIN . '/another.php?id=123' );
		$_SERVER['REQUEST_URI']  = addslashes( '/test.php?id=123' );
		$this->assertSame( 'http://' . GC_TESTS_DOMAIN . '/another.php?id=123', gc_get_referer() );
	}

	/**
	 * @ticket 19856
	 * @ticket 27152
	 */
	public function test_different_server() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://another.' . GC_TESTS_DOMAIN . '/test.php?id=123' );
		$_SERVER['REQUEST_URI']  = addslashes( '/test.php?id=123' );
		$this->assertFalse( gc_get_referer() );
	}

	/**
	 * @ticket 19856
	 * @ticket 27152
	 */
	public function test_different_server_allowed_redirect_host() {
		add_filter( 'allowed_redirect_hosts', array( $this, 'filter_allowed_redirect_hosts' ) );
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://another.' . GC_TESTS_DOMAIN . '/test.php?id=123' );
		$_SERVER['REQUEST_URI']  = addslashes( '/test.php?id=123' );
		$this->assertSame( 'http://another.' . GC_TESTS_DOMAIN . '/test.php?id=123', gc_get_referer() );
		remove_filter( 'allowed_redirect_hosts', array( $this, 'filter_allowed_redirect_hosts' ) );
	}

	/**
	 * @ticket 27152
	 */
	public function test_raw_referer_empty() {
		$this->assertFalse( gc_get_raw_referer() );
	}

	/**
	 * @ticket 27152
	 */
	public function test_raw_referer() {
		$_SERVER['HTTP_REFERER'] = addslashes( 'http://example.com/foo?bar' );
		$this->assertSame( 'http://example.com/foo?bar', gc_get_raw_referer() );
	}

	/**
	 * @ticket 27152
	 */
	public function test_raw_referer_from_request() {
		$_REQUEST['_gc_http_referer'] = addslashes( 'http://foo.bar/baz' );
		$this->assertSame( 'http://foo.bar/baz', gc_get_raw_referer() );
	}

	/**
	 * @ticket 27152
	 */
	public function test_raw_referer_both() {
		$_SERVER['HTTP_REFERER']      = addslashes( 'http://example.com/foo?bar' );
		$_REQUEST['_gc_http_referer'] = addslashes( 'http://foo.bar/baz' );
		$this->assertSame( 'http://foo.bar/baz', gc_get_raw_referer() );
	}
}
