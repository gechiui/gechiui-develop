<?php

/**
 * @group gc
 */
class Tests_GC extends GC_UnitTestCase {
	/**
	 * @var GC
	 */
	protected $gc;

	public function set_up() {
		parent::set_up();
		$this->gc = new GC();
	}

	public function test_add_query_var() {
		$public_qv_count = count( $this->gc->public_query_vars );

		$this->gc->add_query_var( 'test' );
		$this->gc->add_query_var( 'test2' );
		$this->gc->add_query_var( 'test' );

		$this->assertCount( $public_qv_count + 2, $this->gc->public_query_vars );
		$this->assertContains( 'test', $this->gc->public_query_vars );
		$this->assertContains( 'test2', $this->gc->public_query_vars );
	}

	public function test_remove_query_var() {
		$public_qv_count = count( $this->gc->public_query_vars );

		$this->gc->add_query_var( 'test' );
		$this->assertContains( 'test', $this->gc->public_query_vars );
		$this->gc->remove_query_var( 'test' );

		$this->assertCount( $public_qv_count, $this->gc->public_query_vars );
	}
}
