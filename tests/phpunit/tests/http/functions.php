<?php

/**
 * @group http
 * @group external-http
 */
class Tests_HTTP_Functions extends GC_UnitTestCase {

	/**
	 * @covers ::gc_remote_head
	 */
	public function test_head_request() {
		// This URL gives a direct 200 response.
		$url      = 'https://asdftestblog1.files.gechiui.com/2007/09/2007-06-30-dsc_4700-1.jpg';
		$response = gc_remote_head( $url );

		$this->skipTestOnTimeout( $response );

		$headers = gc_remote_retrieve_headers( $response );

		$this->assertIsArray( $response );

		$this->assertSame( 'image/jpeg', $headers['content-type'] );
		$this->assertSame( '40148', $headers['content-length'] );
		$this->assertSame( 200, gc_remote_retrieve_response_code( $response ) );
	}

	/**
	 * @covers ::gc_remote_head
	 */
	public function test_head_redirect() {
		// This URL will 301 redirect.
		$url      = 'https://asdftestblog1.gechiui.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';
		$response = gc_remote_head( $url );

		$this->skipTestOnTimeout( $response );
		$this->assertSame( 301, gc_remote_retrieve_response_code( $response ) );
	}

	/**
	 * @covers ::gc_remote_head
	 */
	public function test_head_404() {
		$url      = 'https://asdftestblog1.files.gechiui.com/2007/09/awefasdfawef.jpg';
		$response = gc_remote_head( $url );

		$this->skipTestOnTimeout( $response );
		$this->assertSame( 404, gc_remote_retrieve_response_code( $response ) );
	}

	/**
	 * @covers ::gc_remote_get
	 * @covers ::gc_remote_retrieve_headers
	 * @covers ::gc_remote_retrieve_response_code
	 */
	public function test_get_request() {
		$url = 'https://asdftestblog1.files.gechiui.com/2007/09/2007-06-30-dsc_4700-1.jpg';

		$response = gc_remote_get( $url );

		$this->skipTestOnTimeout( $response );

		$headers = gc_remote_retrieve_headers( $response );

		$this->assertIsArray( $response );

		// Should return the same headers as a HEAD request.
		$this->assertSame( 'image/jpeg', $headers['content-type'] );
		$this->assertSame( '40148', $headers['content-length'] );
		$this->assertSame( 200, gc_remote_retrieve_response_code( $response ) );
	}

	/**
	 * @covers ::gc_remote_get
	 * @covers ::gc_remote_retrieve_headers
	 * @covers ::gc_remote_retrieve_response_code
	 */
	public function test_get_redirect() {
		// This will redirect to asdftestblog1.files.gechiui.com.
		$url = 'https://asdftestblog1.gechiui.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';

		$response = gc_remote_get( $url );

		$this->skipTestOnTimeout( $response );

		$headers = gc_remote_retrieve_headers( $response );

		// Should return the same headers as a HEAD request.
		$this->assertSame( 'image/jpeg', $headers['content-type'] );
		$this->assertSame( '40148', $headers['content-length'] );
		$this->assertSame( 200, gc_remote_retrieve_response_code( $response ) );
	}

	/**
	 * @covers ::gc_remote_get
	 */
	public function test_get_redirect_limit_exceeded() {
		// This will redirect to asdftestblog1.files.gechiui.com.
		$url = 'https://asdftestblog1.gechiui.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';

		// Pretend we've already redirected 5 times.
		$response = gc_remote_get( $url, array( 'redirection' => -1 ) );

		$this->skipTestOnTimeout( $response );
		$this->assertWPError( $response );
	}

	/**
	 * @ticket 33711
	 *
	 * @covers ::gc_remote_head
	 * @covers ::gc_remote_retrieve_cookies
	 * @covers ::gc_remote_retrieve_cookie
	 * @covers ::gc_remote_retrieve_cookie_value
	 */
	public function test_get_response_cookies() {
		$url = 'https://login.gechiui.com/gc-login.php';

		$response = gc_remote_head( $url );

		$this->skipTestOnTimeout( $response );

		$cookies = gc_remote_retrieve_cookies( $response );

		$this->assertNotEmpty( $cookies );

		$cookie = gc_remote_retrieve_cookie( $response, 'gechiui_test_cookie' );
		$this->assertInstanceOf( 'GC_Http_Cookie', $cookie );
		$this->assertSame( 'gechiui_test_cookie', $cookie->name );
		$this->assertSame( 'GC Cookie check', $cookie->value );

		$value = gc_remote_retrieve_cookie_value( $response, 'gechiui_test_cookie' );
		$this->assertSame( 'GC Cookie check', $value );

		$no_value = gc_remote_retrieve_cookie_value( $response, 'not_a_cookie' );
		$this->assertSame( '', $no_value );

		$no_cookie = gc_remote_retrieve_cookie( $response, 'not_a_cookie' );
		$this->assertSame( '', $no_cookie );
	}

	/**
	 * @ticket 37437
	 *
	 * @covers ::gc_remote_get
	 * @covers ::gc_remote_retrieve_cookies
	 * @covers ::gc_remote_retrieve_cookie
	 */
	public function test_get_response_cookies_with_gc_http_cookie_object() {
		$url = 'http://example.org';

		$response = gc_remote_get(
			$url,
			array(
				'cookies' => array(
					new GC_Http_Cookie(
						array(
							'name'  => 'test',
							'value' => 'foo',
						)
					),
				),
			)
		);

		$this->skipTestOnTimeout( $response );

		$cookies = gc_remote_retrieve_cookies( $response );

		$this->assertNotEmpty( $cookies );

		$cookie = gc_remote_retrieve_cookie( $response, 'test' );
		$this->assertInstanceOf( 'GC_Http_Cookie', $cookie );
		$this->assertSame( 'test', $cookie->name );
		$this->assertSame( 'foo', $cookie->value );
	}

	/**
	 * @ticket 37437
	 *
	 * @covers ::gc_remote_get
	 * @covers ::gc_remote_retrieve_cookies
	 * @covers ::gc_remote_retrieve_cookie
	 */
	public function test_get_response_cookies_with_name_value_array() {
		$url = 'http://example.org';

		$response = gc_remote_get(
			$url,
			array(
				'cookies' => array(
					'test' => 'foo',
				),
			)
		);

		$this->skipTestOnTimeout( $response );

		$cookies = gc_remote_retrieve_cookies( $response );

		$this->assertNotEmpty( $cookies );

		$cookie = gc_remote_retrieve_cookie( $response, 'test' );
		$this->assertInstanceOf( 'GC_Http_Cookie', $cookie );
		$this->assertSame( 'test', $cookie->name );
		$this->assertSame( 'foo', $cookie->value );
	}

	/**
	 * @ticket 43231
	 *
	 * @covers GC_HTTP_Requests_Response::__construct
	 * @covers ::gc_remote_retrieve_cookies
	 * @covers ::gc_remote_retrieve_cookie
	 * @covers GC_Http
	 */
	public function test_get_cookie_host_only() {
		// Emulate GC_Http::request() internals.
		$requests_response = new Requests_Response();

		$requests_response->cookies['test'] = Requests_Cookie::parse( 'test=foo; domain=.gechiui.com' );

		$requests_response->cookies['test']->flags['host-only'] = false; // https://github.com/GeChiUI/Requests/issues/306

		$http_response = new GC_HTTP_Requests_Response( $requests_response );

		$response = $http_response->to_array();

		// Check the host_only flag in the resulting GC_Http_Cookie.
		$cookie = gc_remote_retrieve_cookie( $response, 'test' );
		$this->assertSame( $cookie->domain, 'www.gechiui.com' );
		$this->assertFalse( $cookie->host_only, 'host-only flag not set' );

		// Regurgitate (Requests_Cookie -> GC_Http_Cookie -> Requests_Cookie).
		$cookies = GC_Http::normalize_cookies( gc_remote_retrieve_cookies( $response ) );
		$this->assertFalse( $cookies['test']->flags['host-only'], 'host-only flag data lost' );
	}
}
