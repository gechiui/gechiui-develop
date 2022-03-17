<?php

/**
 * @group xmlrpc
 */
class Tests_XMLRPC_gc_getTaxonomies extends GC_XMLRPC_UnitTestCase {

	public function test_invalid_username_password() {
		$result = $this->myxmlrpcserver->gc_getTaxonomies( array( 1, 'username', 'password' ) );
		$this->assertIXRError( $result );
		$this->assertSame( 403, $result->code );
	}

	public function test_taxonomy_validated() {
		$this->make_user_by_role( 'editor' );

		$result = $this->myxmlrpcserver->gc_getTaxonomies( array( 1, 'editor', 'editor' ) );
		$this->assertNotIXRError( $result );
	}
}
