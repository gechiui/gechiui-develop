<?php

/**
 * This class is designed to make use of MockFS, a Virtual in-memory filesystem compatible with GC_Filesystem
 */
abstract class GC_Filesystem_UnitTestCase extends GC_UnitTestCase {
	public function set_up() {
		parent::set_up();
		add_filter( 'filesystem_method_file', array( $this, 'filter_abstraction_file' ) );
		add_filter( 'filesystem_method', array( $this, 'filter_fs_method' ) );
		GC_Filesystem();
	}

	public function tear_down() {
		global $gc_filesystem;
		remove_filter( 'filesystem_method_file', array( $this, 'filter_abstraction_file' ) );
		remove_filter( 'filesystem_method', array( $this, 'filter_fs_method' ) );
		unset( $gc_filesystem );

		parent::tear_down();
	}

	public function filter_fs_method( $method ) {
		return 'MockFS';
	}
	public function filter_abstraction_file( $file ) {
		return dirname( dirname( __DIR__ ) ) . '/includes/mock-fs.php';
	}

	public function test_is_MockFS_sane() {
		global $gc_filesystem;
		$this->assertInstanceOf( 'GC_Filesystem_MockFS', $gc_filesystem );

		$gc_filesystem->init( '/' );

		// Test creation/exists checks.
		$this->assertFalse( $gc_filesystem->is_dir( '/test/' ) );
		$gc_filesystem->mkdir( '/test' );
		$this->assertTrue( $gc_filesystem->exists( '/test' ) );
		$this->assertTrue( $gc_filesystem->is_dir( '/test/' ) );
		$this->assertFalse( $gc_filesystem->is_file( '/test' ) );
		// $this->assertFalse( true );
	}
}
