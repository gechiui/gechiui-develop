<?php
/**
 * @group http
 * @group external-http
 * @group functions.php
 * @covers ::gc_remote_fopen
 */
class Tests_Functions_gcRemoteFopen extends GC_UnitTestCase {

	/**
	 * @ticket 48845
	 */
	public function test_gc_remote_fopen_empty() {
		$this->assertFalse( gc_remote_fopen( '' ) );
	}

	/**
	 * @ticket 48845
	 */
	public function test_gc_remote_fopen_bad_url() {
		$this->assertFalse( gc_remote_fopen( 'gc.com' ) );
	}

	/**
	 * @ticket 48845
	 */
	public function test_gc_remote_fopen() {
		// This URL gives a direct 200 response.
		$url      = 'https://asdftestblog1.files.gechiui.com/2007/09/2007-06-30-dsc_4700-1.jpg';
		$response = gc_remote_fopen( $url );

		$this->assertIsString( $response );
		$this->assertSame( 40148, strlen( $response ) );
	}
}
