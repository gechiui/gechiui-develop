<?php

/**
 * Unit tests for `gc_debug_mode()`.
 *
 * @package GeChiUI
 * @subpackage UnitTests
 * @since 5.9.0
 */

/**
 * Class Test_GC_Debug_Mode.
 *
 * @group load.php
 * @group gc-debug-mode
 * @covers ::gc_debug_mode
 *
 * @since 5.9.0
 */
class Test_GC_Debug_Mode extends GC_UnitTestCase {
	/**
	 * Test: `gc_debug_mode()` should log, but not display, errors for `ms-files.php`.
	 *
	 * @ticket 53493
	 *
	 * @since 5.9.0
	 */
	public function test_ms_files_logs_but_doesnt_display_errors() {
		/*
		 * Global constants can't be mocked in PHPUnit, so this can only run with the expected
		 * values already set in `gc-tests-config.php`. Unfortunately, that means it won't run in
		 * automated workflows, but it's still useful when testing locally.
		 *
		 * It may be possible to enable automated workflows by mocking `define()`, or by setting up
		 * addition automated flows that initialize the tests with different values for the constants.
		 * At the moment, though, neither of those seem to provide enough benefit to justify the time
		 * investment.
		 *
		 * @link https://theaveragedev.com/mocking-constants-in-tests/
		 */
		if ( true !== GC_DEBUG || true !== GC_DEBUG_DISPLAY || true !== GC_DEBUG_LOG ) {
			$this->markTestSkipped( 'Test requires setting `GC_DEBUG_*` constants in `gc-tests-config.php` to expected values.' );
		}

		// `display_errors` should be _on_ because of `GC_DEBUG_DISPLAY`.
		gc_debug_mode();

		$this->assertSame( E_ALL, (int) ini_get( 'error_reporting' ) );
		$this->assertSame( '1', ini_get( 'display_errors' ) );
		$this->assertSame( '1', ini_get( 'log_errors' ) );
		$this->assertStringContainsString( 'debug.log', ini_get( 'error_log' ) );

		// `display_errors` should be _off_ now, because of `MS_FILES_REQUEST`.
		define( 'MS_FILES_REQUEST', true );
		gc_debug_mode();

		$this->assertSame( E_ALL, (int) ini_get( 'error_reporting' ) );
		$this->assertSame( '0', ini_get( 'display_errors' ) );
		$this->assertSame( '1', ini_get( 'log_errors' ) );
		$this->assertStringContainsString( 'debug.log', ini_get( 'error_log' ) );
	}
}
