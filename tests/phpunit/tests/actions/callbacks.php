<?php

/**
 * @group hooks
 */
class Tests_Actions_Callbacks extends GC_UnitTestCase {

	/**
	 * @ticket 23265
	 *
	 * @covers ::add_action
	 */
	public function test_callback_representations() {
		$tag = __FUNCTION__;

		$this->assertFalse( has_action( $tag ) );

		add_action( $tag, array( 'Class', 'method' ) );

		$this->assertSame( 10, has_action( $tag, array( 'Class', 'method' ) ) );

		$this->assertSame( 10, has_action( $tag, 'Class::method' ) );
	}
}
