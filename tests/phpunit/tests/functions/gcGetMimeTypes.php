<?php

/**
 * Test gc_get_mime_types().
 *
 * @group functions.php
 * @covers ::gc_get_mime_types
 */
class Tests_Functions_gcGetMimeTypes extends GC_UnitTestCase {

	/**
	 * @ticket 47701
	 */
	public function test_all_mime_match() {
		$mime_types_start = gc_get_mime_types();

		$this->assertIsArray( $mime_types_start );
		$this->assertNotEmpty( $mime_types_start );

		add_filter( 'mime_types', '__return_empty_array' );
		$mime_types_empty = gc_get_mime_types();
		$this->assertSame( array(), $mime_types_empty );

		remove_filter( 'mime_types', '__return_empty_array' );
		$mime_types = gc_get_mime_types();
		$this->assertIsArray( $mime_types );
		$this->assertNotEmpty( $mime_types );
		// Did it revert to the original after filter remove?
		$this->assertSame( $mime_types_start, $mime_types );
	}
}
