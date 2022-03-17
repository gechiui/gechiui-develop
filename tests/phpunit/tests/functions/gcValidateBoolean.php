<?php

/**
 * Tests the gc_validate_boolean function.
 *
 * @group functions.php
 * @covers ::gc_validate_boolean
 */
class Tests_Functions_gcValidateBoolean extends GC_UnitTestCase {
	/**
	 * Provides test scenarios for all possible scenarios in gc_validate_boolean().
	 *
	 * @return array
	 */
	public function data_provider() {
			$std = new \stdClass();

			return array(
				array( null, false ),
				array( true, true ),
				array( false, false ),
				array( 'true', true ),
				array( 'false', false ),
				array( 'FalSE', false ), // @ticket 30238
				array( 'FALSE', false ), // @ticket 30238
				array( 'TRUE', true ),
				array( ' FALSE ', true ),
				array( 'yes', true ),
				array( 'no', true ),
				array( 'string', true ),
				array( '', false ),
				array( array(), false ),
				array( 1, true ),
				array( 0, false ),
				array( -1, true ),
				array( 99, true ),
				array( 0.1, true ),
				array( 0.0, false ),
				array( '1', true ),
				array( '0', false ),
				array( $std, true ),
			);
	}

	/**
	 * Test gc_validate_boolean().
	 *
	 * @dataProvider data_provider
	 *
	 * @param mixed $test_value
	 * @param bool $expected
	 *
	 * @ticket 30238
	 * @ticket 39868
	 */
	public function test_gc_validate_boolean( $test_value, $expected ) {
		$this->assertSame( gc_validate_boolean( $test_value ), $expected );
	}
}
