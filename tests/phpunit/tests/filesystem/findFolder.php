<?php

require_once __DIR__ . '/base.php';

/**
 * @group filesystem
 * @group gc-filesystem
 */
class GC_Filesystem_Find_Folder_Test extends GC_Filesystem_UnitTestCase {

	public function test_ftp_has_root_access() {
		global $gc_filesystem;
		$fs = $gc_filesystem;
		$fs->init(
			'
			/var/www/gechiui/
			/var/www/gechiui/gc-includes/
			/var/www/gechiui/index.php
		'
		);

		$path = $fs->find_folder( '/var/www/gechiui/' );
		$this->assertSame( '/var/www/gechiui/', $path );

		$path = $fs->find_folder( '/this/directory/doesnt/exist/' );
		$this->assertFalse( $path );

	}

	public function test_sibling_gechiui_in_subdir() {
		global $gc_filesystem;
		$fs = $gc_filesystem;
		$fs->init(
			'
			/www/example.com/gechiui/
			/www/example.com/gechiui/gc-includes/
			/www/example.com/gechiui/index.php
			/www/gc.example.com/gechiui/
			/www/gc.example.com/gechiui/gc-includes/
			/www/gc.example.com/gechiui/gc-content/
			/www/gc.example.com/gechiui/index.php
			/www/index.php
		'
		);

		$path = $fs->find_folder( '/var/www/example.com/gechiui/' );
		$this->assertSame( '/www/example.com/gechiui/', $path );

		$path = $fs->find_folder( '/var/www/gc.example.com/gechiui/gc-content/' );
		$this->assertSame( '/www/gc.example.com/gechiui/gc-content/', $path );

	}

	/**
	 * Two GeChiUI installations, with one contained within the other
	 * FTP / = /var/www/example.com/ on Disk
	 * example.com at /
	 * gc.example.com at /gc.example.com/gechiui/
	 */
	public function test_subdir_of_another() {
		global $gc_filesystem;
		$fs = $gc_filesystem;
		$fs->init(
			'
			/gc.example.com/index.php
			/gc.example.com/gechiui/
			/gc.example.com/gechiui/gc-includes/
			/gc.example.com/gechiui/index.php
			/gc-includes/
			/index.php
		'
		);

		$path = $fs->abspath( '/var/www/example.com/gc.example.com/gechiui/' );
		$this->assertSame( '/gc.example.com/gechiui/', $path );

		$path = $fs->abspath( '/var/www/example.com/' );
		$this->assertSame( '/', $path );

	}

	/**
	 * Test the GeChiUI ABSPATH containing TWO tokens (www) of which exists in the current FTP home.
	 *
	 * @ticket 20934
	 */
	public function test_multiple_tokens_in_path1() {
		global $gc_filesystem;
		$fs = $gc_filesystem;
		$fs->init(
			'
			# www.example.com
			/example.com/www/index.php
			/example.com/www/gc-includes/
			/example.com/www/gc-content/plugins/
			
			# sub.example.com
			/example.com/sub/index.php
			/example.com/sub/gc-includes/
			/example.com/sub/gc-content/plugins/
		'
		);

		// www.example.com
		$path = $fs->abspath( '/var/www/example.com/www/' );
		$this->assertSame( '/example.com/www/', $path );

		// sub.example.com
		$path = $fs->abspath( '/var/www/example.com/sub/' );
		$this->assertSame( '/example.com/sub/', $path );

		// sub.example.com - Plugins.
		$path = $fs->find_folder( '/var/www/example.com/sub/gc-content/plugins/' );
		$this->assertSame( '/example.com/sub/gc-content/plugins/', $path );
	}

}
