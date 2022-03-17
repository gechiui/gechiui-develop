<?php
/**
 * @group upload
 * @group media
 */
class Tests_Upload extends GC_UnitTestCase {

	public $siteurl;

	public function set_up() {
		parent::set_up();
		$this->reset_options();
	}

	private function reset_options() {
		// System defaults.
		update_option( 'upload_path', 'gc-content/uploads' );
		update_option( 'upload_url_path', '' );
		update_option( 'uploads_use_yearmonth_folders', 1 );
	}

	public function test_upload_dir_default() {
		// gc_upload_dir() with default parameters.
		$info   = gc_upload_dir();
		$subdir = date_format( date_create( 'now' ), '/Y/m' );

		$this->assertSame( get_option( 'siteurl' ) . '/gc-content/uploads' . $subdir, $info['url'] );
		$this->assertSame( ABSPATH . 'gc-content/uploads' . $subdir, $info['path'] );
		$this->assertSame( $subdir, $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

	public function test_upload_dir_relative() {
		// gc_upload_dir() with a relative upload path that is not 'gc-content/uploads'.
		update_option( 'upload_path', 'foo/bar' );
		$info   = _gc_upload_dir();
		$subdir = date_format( date_create( 'now' ), '/Y/m' );

		$this->assertSame( get_option( 'siteurl' ) . '/foo/bar' . $subdir, $info['url'] );
		$this->assertSame( ABSPATH . 'foo/bar' . $subdir, $info['path'] );
		$this->assertSame( $subdir, $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

	/**
	 * @ticket 5953
	 */
	public function test_upload_dir_absolute() {
		$path = get_temp_dir() . 'gc-unit-test';

		// gc_upload_dir() with an absolute upload path.
		update_option( 'upload_path', $path );

		// Doesn't make sense to use an absolute file path without setting the url path.
		update_option( 'upload_url_path', '/baz' );

		// Use `_gc_upload_dir()` directly to bypass caching and work with the changed options.
		// It doesn't create the /year/month directories.
		$info   = _gc_upload_dir();
		$subdir = date_format( date_create( 'now' ), '/Y/m' );

		$this->assertSame( '/baz' . $subdir, $info['url'] );
		$this->assertSame( $path . $subdir, $info['path'] );
		$this->assertSame( $subdir, $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

	public function test_upload_dir_no_yearnum() {
		update_option( 'uploads_use_yearmonth_folders', 0 );

		// Use `_gc_upload_dir()` directly to bypass caching and work with the changed options.
		$info = _gc_upload_dir();

		$this->assertSame( get_option( 'siteurl' ) . '/gc-content/uploads', $info['url'] );
		$this->assertSame( ABSPATH . 'gc-content/uploads', $info['path'] );
		$this->assertSame( '', $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

	public function test_upload_path_absolute() {
		update_option( 'upload_url_path', 'http://' . GC_TESTS_DOMAIN . '/asdf' );

		// Use `_gc_upload_dir()` directly to bypass caching and work with the changed options.
		// It doesn't create the /year/month directories.
		$info   = _gc_upload_dir();
		$subdir = date_format( date_create( 'now' ), '/Y/m' );

		$this->assertSame( 'http://' . GC_TESTS_DOMAIN . '/asdf' . $subdir, $info['url'] );
		$this->assertSame( ABSPATH . 'gc-content/uploads' . $subdir, $info['path'] );
		$this->assertSame( $subdir, $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

	public function test_upload_dir_empty() {
		// Upload path setting is empty - it should default to 'gc-content/uploads'.
		update_option( 'upload_path', '' );

		// Use `_gc_upload_dir()` directly to bypass caching and work with the changed options.
		// It doesn't create the /year/month directories.
		$info   = _gc_upload_dir();
		$subdir = date_format( date_create( 'now' ), '/Y/m' );

		$this->assertSame( get_option( 'siteurl' ) . '/gc-content/uploads' . $subdir, $info['url'] );
		$this->assertSame( ABSPATH . 'gc-content/uploads' . $subdir, $info['path'] );
		$this->assertSame( $subdir, $info['subdir'] );
		$this->assertFalse( $info['error'] );
	}

}
