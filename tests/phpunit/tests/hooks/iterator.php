<?php

/**
 * Test the Iterator implementation of GC_Hook
 *
 * @group hooks
 * @covers GC_Hook::add_filter
 */
class Tests_Hooks_Iterator extends GC_UnitTestCase {

	public function test_foreach() {
		$callback_one  = '__return_null';
		$callback_two  = '__return_false';
		$hook          = new GC_Hook();
		$tag           = __FUNCTION__;
		$priority      = 1;
		$accepted_args = 2;

		$hook->add_filter( $tag, $callback_one, $priority, $accepted_args );
		$hook->add_filter( $tag, $callback_two, $priority + 1, $accepted_args );

		$functions  = array();
		$priorities = array();
		foreach ( $hook as $key => $callbacks ) {
			$priorities[] = $key;
			foreach ( $callbacks as $function_index => $the_ ) {
				$functions[] = $the_['function'];
			}
		}
		$this->assertSameSets( array( $priority, $priority + 1 ), $priorities );
		$this->assertSameSets( array( $callback_one, $callback_two ), $functions );
	}
}
