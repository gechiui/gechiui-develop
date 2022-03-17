<?php

/**
 * @group error-protection
 */
class Tests_Error_Protection_gcRecoveryModeCookieService extends GC_UnitTestCase {

	/**
	 * @ticket 46130
	 *
	 * @covers GC_Recovery_Mode_Cookie_Service::validate_cookie
	 */
	public function test_validate_cookie_returns_gc_error_if_invalid_format() {

		$service = new GC_Recovery_Mode_Cookie_Service();

		$error = $service->validate_cookie( 'gibbersih' );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_format', $error->get_error_code() );

		$error = $service->validate_cookie( base64_encode( 'test|data|format' ) );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_format', $error->get_error_code() );

		$error = $service->validate_cookie( base64_encode( 'test|data|format|to|long' ) );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_format', $error->get_error_code() );
	}

	/**
	 * @ticket 46130
	 *
	 * @covers GC_Recovery_Mode_Cookie_Service::validate_cookie
	 */
	public function test_validate_cookie_returns_gc_error_if_expired() {
		$service    = new GC_Recovery_Mode_Cookie_Service();
		$reflection = new ReflectionMethod( $service, 'recovery_mode_hash' );
		$reflection->setAccessible( true );

		$to_sign = sprintf( 'recovery_mode|%s|%s', time() - WEEK_IN_SECONDS - 30, gc_generate_password( 20, false ) );
		$signed  = $reflection->invoke( $service, $to_sign );
		$cookie  = base64_encode( sprintf( '%s|%s', $to_sign, $signed ) );

		$error = $service->validate_cookie( $cookie );
		$this->assertWPError( $error );
		$this->assertSame( 'expired', $error->get_error_code() );
	}

	/**
	 * @ticket 46130
	 *
	 * @covers GC_Recovery_Mode_Cookie_Service::validate_cookie
	 */
	public function test_validate_cookie_returns_gc_error_if_signature_mismatch() {
		$service    = new GC_Recovery_Mode_Cookie_Service();
		$reflection = new ReflectionMethod( $service, 'generate_cookie' );
		$reflection->setAccessible( true );

		$cookie  = $reflection->invoke( $service );
		$cookie .= 'gibbersih';

		$error = $service->validate_cookie( $cookie );
		$this->assertWPError( $error );
		$this->assertSame( 'signature_mismatch', $error->get_error_code() );
	}

	/**
	 * @ticket 46130
	 *
	 * @covers GC_Recovery_Mode_Cookie_Service::validate_cookie
	 */
	public function test_validate_cookie_returns_gc_error_if_created_at_is_invalid_format() {
		$service    = new GC_Recovery_Mode_Cookie_Service();
		$reflection = new ReflectionMethod( $service, 'recovery_mode_hash' );
		$reflection->setAccessible( true );

		$to_sign = sprintf( 'recovery_mode|%s|%s', 'month', gc_generate_password( 20, false ) );
		$signed  = $reflection->invoke( $service, $to_sign );
		$cookie  = base64_encode( sprintf( '%s|%s', $to_sign, $signed ) );

		$error = $service->validate_cookie( $cookie );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_created_at', $error->get_error_code() );
	}

	/**
	 * @ticket 46130
	 *
	 * @covers GC_Recovery_Mode_Cookie_Service::validate_cookie
	 */
	public function test_validate_cookie_returns_true_for_valid_cookie() {

		$service    = new GC_Recovery_Mode_Cookie_Service();
		$reflection = new ReflectionMethod( $service, 'generate_cookie' );
		$reflection->setAccessible( true );

		$this->assertTrue( $service->validate_cookie( $reflection->invoke( $service ) ) );
	}
}
