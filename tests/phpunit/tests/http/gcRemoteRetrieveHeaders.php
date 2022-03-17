<?php

/**
 * @group http
 * @covers ::gc_remote_retrieve_headers
 */
class Tests_HTTP_gcRemoteRetrieveHeaders extends GC_UnitTestCase {

	/**
	 * Valid response
	 */
	public function test_remote_retrieve_headers_valid_response() {
		$headers  = 'headers_data';
		$response = array( 'headers' => $headers );

		$result = gc_remote_retrieve_headers( $response );
		$this->assertSame( $headers, $result );
	}

	/**
	 * Response is a GC_Error
	 */
	public function test_remote_retrieve_headers_is_error() {
		$response = new GC_Error( 'Some error' );

		$result = gc_remote_retrieve_headers( $response );
		$this->assertSame( array(), $result );
	}

	/**
	 * Response does not contain 'headers'
	 */
	public function test_remote_retrieve_headers_invalid_response() {
		$response = array( 'no_headers' => 'set' );

		$result = gc_remote_retrieve_headers( $response );
		$this->assertSame( array(), $result );
	}
}
