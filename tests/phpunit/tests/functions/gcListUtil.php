<?php

/**
 * Test GC_List_Util class.
 *
 * @group functions.php
 */
class Tests_Functions_gcListUtil extends GC_UnitTestCase {

	/**
	 * @covers GC_List_Util::get_input
	 */
	public function test_gc_list_util_get_input() {
		$input = array( 'foo', 'bar' );
		$util  = new GC_List_Util( $input );

		$this->assertSameSets( $input, $util->get_input() );
	}

	/**
	 * @covers GC_List_Util::get_output
	 */
	public function test_gc_list_util_get_output_immediately() {
		$input = array( 'foo', 'bar' );
		$util  = new GC_List_Util( $input );

		$this->assertSameSets( $input, $util->get_output() );
	}

	/**
	 * @covers GC_List_Util::get_output
	 */
	public function test_gc_list_util_get_output() {
		$expected = array(
			(object) array(
				'foo' => 'bar',
				'bar' => 'baz',
			),
		);

		$util   = new GC_List_Util(
			array(
				(object) array(
					'foo' => 'bar',
					'bar' => 'baz',
				),
				(object) array( 'bar' => 'baz' ),
			)
		);
		$actual = $util->filter( array( 'foo' => 'bar' ) );

		$this->assertEqualSets( $expected, $actual );
		$this->assertEqualSets( $expected, $util->get_output() );
	}
}
