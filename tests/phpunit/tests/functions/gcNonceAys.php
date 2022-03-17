<?php

/**
 * Tests for the gc_nonce_ays() function.
 *
 * @since 5.9.0
 *
 * @group functions.php
 * @covers ::gc_nonce_ays
 */
class Tests_Functions_gcNonceAys extends GC_UnitTestCase {

	/**
	 * @ticket 53882
	 */
	public function test_gc_nonce_ays() {
		$this->expectException( 'GCDieException' );
		$this->expectExceptionMessage( 'The link you followed has expired.' );
		$this->expectExceptionCode( 403 );

		gc_nonce_ays( 'random_string' );
	}

	/**
	 * @ticket 53882
	 */
	public function test_gc_nonce_ays_log_out() {
		$this->expectException( 'GCDieException' );
		$this->expectExceptionMessageMatches( '#You are attempting to log out of Test Blog</p><p>Do you really want to <a href="http://example\.org/gc-login\.php\?action=logout&amp;_gcnonce=.{10}">log out</a>\?#m' );
		$this->expectExceptionCode( 403 );

		gc_nonce_ays( 'log-out' );
	}
}
