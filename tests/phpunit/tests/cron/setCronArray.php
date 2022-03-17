<?php

/**
 * Test the `_set_cron_array()` function.
 *
 * @group cron
 * @covers ::_set_cron_array
 */
class Tests_Cron_setCronArray extends GC_UnitTestCase {

	public function set_up() {
		parent::set_up();
		// Make sure the schedule is clear.
		_set_cron_array( array() );
	}

	public function tear_down() {
		// Make sure the schedule is clear.
		_set_cron_array( array() );
		parent::tear_down();
	}

	/**
	 * Tests the input validation for the `_set_cron_array()` function.
	 *
	 * Includes verifying that invalid input - typically `false` - does not result in a PHP
	 * deprecation warning on PHP 8.1 or higher.
	 *
	 * The warning that we should not see:
	 * `Deprecated: Automatic conversion of false to array is deprecated`.
	 *
	 * @ticket 53635
	 *
	 * @dataProvider data_set_cron_array_input_validation
	 *
	 * @param mixed $input    Cron "array".
	 * @param array $expected Expected array entry count of the cron option after update.
	 */
	public function test_set_cron_array_input_validation( $input, $expected ) {
		delete_option( 'cron' );
		$this->assertTrue( _set_cron_array( $input ) );

		$crons = get_option( 'cron' );
		$this->assertIsArray( $crons, 'Cron option is not an array.' );
		$this->assertArrayHasKey( 'version', $crons, 'Cron option does not have a "version" key.' );
		$this->assertCount( $expected, $crons, 'Cron option does not contain the expected nr of entries.' );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_set_cron_array_input_validation() {
		return array(
			'null'        => array(
				'input'    => null,
				'expected' => 1,
			),
			// Function _get_cron_array() may return `false`, so this is the PHP 8.1 "problem" test.
			'false'       => array(
				'input'    => false,
				'expected' => 1,
			),
			'empty array' => array(
				'input'    => array(),
				'expected' => 1,
			),
			'cron array'  => array(
				'input'    => array(
					'version' => 2,
					time()    => array(
						'hookname' => array(
							'event key' => array(
								'schedule' => 'schedule',
								'args'     => 'args',
								'interval' => 'interval',
							),
						),
					),
				),
				'expected' => 2,
			),
		);
	}

	/**
	 * Tests that `_set_cron_array()` returns `false` when the cron option was not updated.
	 *
	 * @dataProvider data_set_cron_array_returns_false_when_not_updated
	 *
	 * @param array $input    Cron array.
	 * @param mixed $gc_error Value to use for $gc_error.
	 */
	public function test_set_cron_array_returns_false_when_not_updated( $input, $gc_error ) {
		$this->assertFalse( _set_cron_array( $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_set_cron_array_returns_false_when_not_updated() {
		return array(
			'empty array' => array(
				'input'    => array(),
				'gc_error' => false,
			),
			'cron array'  => array(
				'input'    => array(
					'version' => 2,
				),
				'gc_error' => 0,
			),
		);
	}

	/**
	 * Tests that `_set_cron_array()` returns a GC_Error object when the cron option was not updated and `$gc_error` is truthy.
	 *
	 * @dataProvider data_set_cron_array_returns_GC_Error_when_not_updated
	 *
	 * @param array $input    Cron array.
	 * @param mixed $gc_error Value to use for $gc_error.
	 */
	public function test_set_cron_array_returns_GC_Error_when_not_updated( $input, $gc_error ) {
		$result = _set_cron_array( $input, $gc_error );
		$this->assertWPError( $result, 'Return value is not an instance of GC_Error.' );
		$this->assertSame( 'could_not_set', $result->get_error_code(), 'GC_Error error code does not match expected code.' );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_set_cron_array_returns_GC_Error_when_not_updated() {
		return array(
			'empty array' => array(
				'input'    => array(),
				'gc_error' => true,
			),
			'cron array'  => array(
				'input'    => array(
					'version' => 2,
				),
				'gc_error' => 1,
			),
		);
	}

	/**
	 * Tests that `_set_cron_array()` returns true when the cron option was updated and `$gc_error` is truthy.
	 */
	public function test_set_cron_array_does_not_return_GC_Error_when_updated() {
		$result = _set_cron_array(
			array(
				'version' => 2,
				time()    => array(
					'hookname' => array(
						'event key' => array(
							'schedule' => 'schedule',
							'args'     => 'args',
							'interval' => 'interval',
						),
					),
				),
			),
			true
		);

		$this->assertTrue( $result );
	}
}
