<?php

/**
 * Test list_files().
 *
 * @group functions.php
 * @covers ::list_files
 */
class Tests_Functions_ListFiles extends GC_UnitTestCase {

	public function test_list_files_returns_a_list_of_files() {
		$admin_files = list_files( ABSPATH . 'gc-admin/' );
		$this->assertIsArray( $admin_files );
		$this->assertNotEmpty( $admin_files );
		$this->assertContains( ABSPATH . 'gc-admin/index.php', $admin_files );
	}

	public function test_list_files_can_exclude_files() {
		$admin_files = list_files( ABSPATH . 'gc-admin/', 100, array( 'index.php' ) );
		$this->assertNotContains( ABSPATH . 'gc-admin/index.php', $admin_files );
	}
}
