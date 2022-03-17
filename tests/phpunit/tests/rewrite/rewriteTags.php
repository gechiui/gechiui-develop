<?php

/**
 * @group rewrite
 */
class Tests_Rewrite_Tags extends GC_UnitTestCase {
	protected $rewritecode;
	protected $rewritereplace;
	protected $queryreplace;

	public function set_up() {
		global $gc_rewrite;
		parent::set_up();

		$gc_rewrite = new GC_Rewrite();
		$gc_rewrite->init();

		$this->rewritecode    = $gc_rewrite->rewritecode;
		$this->rewritereplace = $gc_rewrite->rewritereplace;
		$this->queryreplace   = $gc_rewrite->queryreplace;
	}

	public function _invalid_rewrite_tags() {
		return array(
			array( 'foo', 'bar' ),
			array( '%', 'bar' ),
			array( '%a', 'bar' ),
			array( 'a%', 'bar' ),
			array( '%%', 'bar' ),
			array( '', 'bar' ),
		);
	}

	/**
	 * @dataProvider _invalid_rewrite_tags
	 *
	 * @param string $tag   Rewrite tag.
	 * @param string $regex Regex.
	 */
	public function test_add_rewrite_tag_invalid( $tag, $regex ) {
		global $gc_rewrite;

		add_rewrite_tag( $tag, $regex );
		$this->assertSameSets( $this->rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( $this->rewritereplace, $gc_rewrite->rewritereplace );
		$this->assertSameSets( $this->queryreplace, $gc_rewrite->queryreplace );
	}

	public function test_add_rewrite_tag_empty_query() {
		global $gc_rewrite;

		$rewritecode   = $gc_rewrite->rewritecode;
		$rewritecode[] = '%foo%';
		add_rewrite_tag( '%foo%', 'bar' );

		$this->assertSameSets( $rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( array_merge( $this->rewritereplace, array( 'bar' ) ), $gc_rewrite->rewritereplace );
		$this->assertSameSets( array_merge( $this->queryreplace, array( 'foo=' ) ), $gc_rewrite->queryreplace );
	}

	public function test_add_rewrite_tag_custom_query() {
		global $gc_rewrite;

		$rewritecode   = $gc_rewrite->rewritecode;
		$rewritecode[] = '%foo%';
		add_rewrite_tag( '%foo%', 'bar', 'baz=' );

		$this->assertSameSets( $rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( array_merge( $this->rewritereplace, array( 'bar' ) ), $gc_rewrite->rewritereplace );
		$this->assertSameSets( array_merge( $this->queryreplace, array( 'baz=' ) ), $gc_rewrite->queryreplace );
	}

	public function test_add_rewrite_tag_updates_existing() {
		global $gc_rewrite;

		add_rewrite_tag( '%pagename%', 'foo', 'bar=' );
		$this->assertContains( '%pagename%', $gc_rewrite->rewritecode );
		$this->assertContains( 'foo', $gc_rewrite->rewritereplace );
		$this->assertNotContains( '([^/]+?)', $gc_rewrite->rewritereplace );
		$this->assertContains( 'bar=', $gc_rewrite->queryreplace );
		$this->assertNotContains( 'pagename=', $gc_rewrite->queryreplace );
	}

	public function test_remove_rewrite_tag() {
		global $gc_rewrite;

		$rewritecode   = $gc_rewrite->rewritecode;
		$rewritecode[] = '%foo%';
		add_rewrite_tag( '%foo%', 'bar', 'baz=' );
		$this->assertSameSets( $rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( array_merge( $this->rewritereplace, array( 'bar' ) ), $gc_rewrite->rewritereplace );
		$this->assertSameSets( array_merge( $this->queryreplace, array( 'baz=' ) ), $gc_rewrite->queryreplace );

		remove_rewrite_tag( '%foo%' );
		$this->assertSameSets( $this->rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( $this->rewritereplace, $gc_rewrite->rewritereplace );
		$this->assertSameSets( $this->queryreplace, $gc_rewrite->queryreplace );
	}

	public function test_remove_rewrite_tag_internal_tag() {
		global $gc_rewrite;

		$this->assertContains( '%post_id%', $gc_rewrite->rewritecode );
		$this->assertContains( '([0-9]+)', $gc_rewrite->rewritereplace );
		$this->assertContains( 'p=', $gc_rewrite->queryreplace );

		remove_rewrite_tag( '%post_id%' );

		$this->assertNotContains( '%post_id%', $gc_rewrite->rewritecode );
		$this->assertNotContains( '([0-9]+)', $gc_rewrite->rewritereplace );
		$this->assertNotContains( 'p=', $gc_rewrite->queryreplace );
	}

	public function test_remove_rewrite_tag_only_removes_one_array_value() {
		global $gc_rewrite;

		$rewritecode      = $gc_rewrite->rewritecode;
		$rewritecode[]    = '%foo%';
		$rewritereplace   = $gc_rewrite->rewritereplace;
		$rewritereplace[] = '([0-9]{1,2})';
		add_rewrite_tag( '%foo%', '([0-9]{1,2})', 'post_type=foo&name=' );
		$this->assertSameSets( $rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( $rewritereplace, $gc_rewrite->rewritereplace );
		$this->assertSameSets( array_merge( $this->queryreplace, array( 'post_type=foo&name=' ) ), $gc_rewrite->queryreplace );

		remove_rewrite_tag( '%foo%' );
		$this->assertSameSets( $this->rewritecode, $gc_rewrite->rewritecode );
		$this->assertSameSets( $this->rewritereplace, $gc_rewrite->rewritereplace );
		$this->assertSameSets( $this->queryreplace, $gc_rewrite->queryreplace );

		$this->assertNotContains( '%foo%', $gc_rewrite->rewritecode );
		$this->assertContains( '([0-9]{1,2})', $gc_rewrite->rewritereplace );
		$this->assertNotContains( 'post_type=foo&name=', $gc_rewrite->queryreplace );
	}
}
