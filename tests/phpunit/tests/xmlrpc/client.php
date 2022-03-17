<?php
require_once ABSPATH . GCINC . '/class-IXR.php';
require_once ABSPATH . GCINC . '/class-gc-http-ixr-client.php';

/**
 * @group xmlrpc
 */
class Tests_XMLRPC_Client extends GC_XMLRPC_UnitTestCase {

	/**
	 * @ticket 26947
	 */
	public function test_ixr_client_allows_query_strings() {
		$client = new IXR_Client( 'http://example.com/server.php?this-is-needed=true#not-this' );
		$this->assertSame( 'example.com', $client->server );
		$this->assertSame( 80, $client->port );
		$this->assertSame( '/server.php?this-is-needed=true', $client->path );
	}

	/**
	 * @ticket 26947
	 */
	public function test_gc_ixr_client_allows_query_strings() {
		$client = new GC_HTTP_IXR_Client( 'http://example.com/server.php?this-is-needed=true#not-this' );
		$this->assertSame( 'example.com', $client->server );
		$this->assertFalse( $client->port );
		$this->assertSame( '/server.php?this-is-needed=true', $client->path );
	}
}

