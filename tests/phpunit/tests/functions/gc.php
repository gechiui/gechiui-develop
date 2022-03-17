<?php

/**
 * @group functions.php
 * @group query
 * @covers ::gc
 */
class Tests_Functions_GC extends GC_UnitTestCase {

	public function test_gc_sets_global_vars() {
		global $gc, $gc_query, $gc_the_query;

		gc();

		$this->assertInstanceOf( 'GC', $gc );
		$this->assertInstanceOf( 'GC_Query', $gc_query );
		$this->assertInstanceOf( 'GC_Query', $gc_the_query );
	}

}
