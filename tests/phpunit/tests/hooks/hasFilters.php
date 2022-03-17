<?php

/**
 * Test the has_filters method of GC_Hook
 *
 * @group hooks
 * @covers GC_Hook::has_filters
 */
class Tests_Hooks_HasFilters extends GC_UnitTestCase {

	public function test_has_filters_with_callback() {
		$callback      = '__return_null';
		$hook          = new GC_Hook();
		$tag           = __FUNCTION__;
		$priority      = 1;
		$accepted_args = 2;

		$hook->add_filter( $tag, $callback, $priority, $accepted_args );

		$this->assertTrue( $hook->has_filters() );
	}

	public function test_has_filters_without_callback() {
		$hook = new GC_Hook();
		$this->assertFalse( $hook->has_filters() );
	}

	public function test_not_has_filters_with_removed_callback() {
		$callback      = '__return_null';
		$hook          = new GC_Hook();
		$tag           = __FUNCTION__;
		$priority      = 1;
		$accepted_args = 2;

		$hook->add_filter( $tag, $callback, $priority, $accepted_args );
		$hook->remove_filter( $tag, $callback, $priority );
		$this->assertFalse( $hook->has_filters() );
	}

	public function test_not_has_filter_with_directly_removed_callback() {
		$callback      = '__return_null';
		$hook          = new GC_Hook();
		$tag           = __FUNCTION__;
		$priority      = 1;
		$accepted_args = 2;

		$hook->add_filter( $tag, $callback, $priority, $accepted_args );
		$function_key = _gc_filter_build_unique_id( $tag, $callback, $priority );
		unset( $hook->callbacks[ $priority ][ $function_key ] );

		$this->assertFalse( $hook->has_filters() );
	}
}
