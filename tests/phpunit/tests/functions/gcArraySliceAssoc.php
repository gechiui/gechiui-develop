<?php

/**
 * Tests gc_array_slice_assoc function
 *
 * @since 5.3.0
 *
 * @group functions.php
 * @covers ::gc_array_slice_assoc
 */
class Tests_Functions_gcArraySliceAssoc extends GC_UnitTestCase {

	/**
	 * Test gc_array_slice_assoc().
	 *
	 * @dataProvider data_gc_array_slice_assoc_arrays
	 *
	 * @ticket 46638
	 *
	 * @param array $target_array The original array.
	 * @param array $keys         The list of keys.
	 * @param array $expected     The expected result.
	 */
	public function test_gc_array_slice_assoc( $target_array, $keys, $expected ) {
		$this->assertSame( gc_array_slice_assoc( $target_array, $keys ), $expected );
	}

	/**
	 * Test data for gc_array_slice_assoc().
	 *
	 * @return array
	 */
	public function data_gc_array_slice_assoc_arrays() {
		return array(
			array(
				array( 1 => 1 ),
				array( 1 ),
				array( 1 => 1 ),
			),
			array(
				array( 1 => 1 ),
				array( 0 ),
				array(),
			),
			array(
				array( 1 => array( 1 => 1 ) ),
				array( 1 ),
				array( 1 => array( 1 => 1 ) ),
			),
			array(
				array(
					1 => 1,
					2 => 2,
				),
				array( 1 ),
				array( 1 => 1 ),
			),
			array(
				array(
					1 => 1,
					2 => 2,
				),
				array( 2 ),
				array( 2 => 2 ),
			),
			array(
				array(
					1 => 1,
					2 => 2,
				),
				array( 1, 1 ),
				array( 1 => 1 ),
			),
			array(
				array( 1 => array( 1 => array( 1 => 1 ) ) ),
				array( 1 ),
				array( 1 => array( 1 => array( 1 => 1 ) ) ),
			),
			array(
				array(
					1 => 1,
					2 => 2,
				),
				array( 1, 2 ),
				array(
					1 => 1,
					2 => 2,
				),
			),
			array(
				array(
					'1' => '1',
					'2' => '2',
				),
				array( '1' ),
				array( '1' => '1' ),
			),
			array(
				array(
					'1' => '1',
					'2' => '2',
				),
				array( '2' ),
				array( '2' => '2' ),
			),
			array(
				array(
					'1' => '1',
					'2' => '2',
				),
				array( 1 ),
				array( '1' => '1' ),
			),
			array(
				array(
					'1' => '1',
					'2' => '2',
				),
				array( 1 ),
				array( '1' => '1' ),
			),
			array(
				array( 1 => 1 ),
				array( '1' ),
				array( 1 => 1 ),
			),
		);
	}
}
