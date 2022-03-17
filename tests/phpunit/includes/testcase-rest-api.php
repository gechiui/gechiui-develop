<?php

abstract class GC_Test_REST_TestCase extends GC_UnitTestCase {
	protected function assertErrorResponse( $code, $response, $status = null ) {

		if ( is_a( $response, 'GC_REST_Response' ) ) {
			$response = $response->as_error();
		}

		$this->assertWPError( $response );
		$this->assertSame( $code, $response->get_error_code() );

		if ( null !== $status ) {
			$data = $response->get_error_data();
			$this->assertArrayHasKey( 'status', $data );
			$this->assertSame( $status, $data['status'] );
		}
	}
}
