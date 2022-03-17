<?php

/**
 * @group taxonomy
 */
class Tests_Term_GetTheTerms extends GC_UnitTestCase {
	protected $taxonomy        = 'category';
	protected static $post_ids = array();

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$post_ids = $factory->post->create_many( 5 );
	}

	/**
	 * @ticket 22560
	 */
	public function test_object_term_cache() {
		$post_id = self::$post_ids[0];

		$terms_1 = array( 'foo', 'bar', 'baz' );
		$terms_2 = array( 'bar', 'bing' );

		// Cache should be empty after a set.
		$tt_1 = gc_set_object_terms( $post_id, $terms_1, $this->taxonomy );
		$this->assertCount( 3, $tt_1 );
		$this->assertFalse( gc_cache_get( $post_id, $this->taxonomy . '_relationships' ) );

		// gc_get_object_terms() does not prime the cache.
		gc_get_object_terms(
			$post_id,
			$this->taxonomy,
			array(
				'fields'  => 'names',
				'orderby' => 't.term_id',
			)
		);
		$this->assertFalse( gc_cache_get( $post_id, $this->taxonomy . '_relationships' ) );

		// get_the_terms() does prime the cache.
		$terms = get_the_terms( $post_id, $this->taxonomy );
		$cache = gc_cache_get( $post_id, $this->taxonomy . '_relationships' );
		$this->assertIsArray( $cache );

		// Cache should be empty after a set.
		$tt_2 = gc_set_object_terms( $post_id, $terms_2, $this->taxonomy );
		$this->assertCount( 2, $tt_2 );
		$this->assertFalse( gc_cache_get( $post_id, $this->taxonomy . '_relationships' ) );
	}

	/**
	 * @ticket 24189
	 */
	public function test_object_term_cache_when_term_changes() {
		$post_id = self::$post_ids[0];
		$tag_id  = self::factory()->tag->create(
			array(
				'name'        => 'Amaze Tag',
				'description' => 'My Amazing Tag',
			)
		);

		$tt_1 = gc_set_object_terms( $post_id, $tag_id, 'post_tag' );

		$terms = get_the_terms( $post_id, 'post_tag' );
		$this->assertSame( $tag_id, $terms[0]->term_id );
		$this->assertSame( 'My Amazing Tag', $terms[0]->description );

		$_updated = gc_update_term(
			$tag_id,
			'post_tag',
			array(
				'description' => 'This description is even more amazing!',
			)
		);

		$_new_term = get_term( $tag_id, 'post_tag' );
		$this->assertSame( $tag_id, $_new_term->term_id );
		$this->assertSame( 'This description is even more amazing!', $_new_term->description );

		$terms = get_the_terms( $post_id, 'post_tag' );
		$this->assertSame( $tag_id, $terms[0]->term_id );
		$this->assertSame( 'This description is even more amazing!', $terms[0]->description );
	}

	/**
	 * @ticket 34262
	 */
	public function test_get_the_terms_should_return_gc_term_objects_from_cache() {
		$p = self::$post_ids[0];
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );
		gc_set_object_terms( $p, $t, 'gctests_tax' );

		// Prime the cache.
		get_the_terms( $p, 'gctests_tax' );

		$cached = get_the_terms( $p, 'gctests_tax' );

		$this->assertNotEmpty( $cached );
		$this->assertSame( $t, (int) $cached[0]->term_id );
		$this->assertInstanceOf( 'GC_Term', $cached[0] );
	}

	/**
	 * @ticket 31086
	 */
	public function test_get_the_terms_should_return_zero_indexed_array_when_cache_is_empty() {
		register_taxonomy( 'gctests_tax', 'post' );
		$p = self::$post_ids[0];
		gc_set_object_terms( $p, array( 'foo', 'bar' ), 'gctests_tax' );

		$found = get_the_terms( $p, 'gctests_tax' );

		$this->assertSameSets( array( 0, 1 ), array_keys( $found ) );
	}

	/**
	 * @ticket 31086
	 */
	public function test_get_the_terms_should_return_zero_indexed_array_when_cache_is_primed() {
		register_taxonomy( 'gctests_tax', 'post' );
		$p = self::$post_ids[0];
		gc_set_object_terms( $p, array( 'foo', 'bar' ), 'gctests_tax' );

		// Prime cache.
		update_object_term_cache( array( $p ), array( 'post' ) );

		$found = get_the_terms( $p, 'gctests_tax' );

		$this->assertSameSets( array( 0, 1 ), array_keys( $found ) );
	}

	/**
	 * @ticket 35180
	 * @ticket 28922
	 */
	public function test_get_the_terms_should_return_results_ordered_by_name_when_pulling_from_cache() {
		register_taxonomy( 'gctests_tax', 'post' );
		$p = self::$post_ids[0];

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'fff',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'aaa',
			)
		);
		$t3 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'zzz',
			)
		);

		gc_set_object_terms( $p, array( $t1, $t2, $t3 ), 'gctests_tax' );
		update_object_term_cache( $p, 'post' );

		$found = get_the_terms( $p, 'gctests_tax' );

		$this->assertSame( array( $t2, $t1, $t3 ), gc_list_pluck( $found, 'term_id' ) );
	}

	/**
	 * @ticket 34723
	 */
	public function test_get_the_terms_should_return_gc_error_when_taxonomy_is_unregistered() {
		$p     = self::$post_ids[0];
		$terms = get_the_terms( $p, 'this-taxonomy-does-not-exist' );
		$this->assertWPError( $terms );
	}

	/**
	 * @ticket 36814
	 */
	public function test_count_should_not_be_improperly_cached() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );

		gc_set_object_terms( self::$post_ids[0], $t, 'gctests_tax' );

		$terms = get_the_terms( self::$post_ids[0], 'gctests_tax' );
		$this->assertSame( 1, $terms[0]->count );

		gc_set_object_terms( self::$post_ids[1], $t, 'gctests_tax' );

		$terms = get_the_terms( self::$post_ids[0], 'gctests_tax' );
		$this->assertSame( 2, $terms[0]->count );
	}

	/**
	 * @ticket 36814
	 */
	public function test_uncached_terms_should_be_primed_with_a_single_query() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );

		$terms = self::factory()->term->create_many( 3, array( 'taxonomy' => 'gctests_tax' ) );

		gc_set_object_terms( self::$post_ids[0], $terms, 'gctests_tax' );

		get_the_terms( self::$post_ids[0], 'gctests_tax' );

		// Clean cache for two of the terms.
		clean_term_cache( array( $terms[0], $terms[1] ), 'gctests_tax', false );

		$num_queries = $gcdb->num_queries;
		$found       = get_the_terms( self::$post_ids[0], 'gctests_tax' );

		$this->assertSameSets( $terms, gc_list_pluck( $found, 'term_id' ) );

		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );

	}

	/**
	 * @ticket 40306
	 */
	public function test_term_cache_should_be_invalidated_on_set_object_terms() {
		register_taxonomy( 'gctests_tax', 'post' );

		// Temporarily disable term counting, which performs its own cache invalidation.
		gc_defer_term_counting( true );

		// Create Test Category.
		$term_id = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$post_id = self::factory()->post->create();

		// Prime cache.
		get_the_terms( $post_id, 'gctests_tax' );

		gc_set_object_terms( $post_id, $term_id, 'gctests_tax' );

		$terms = get_the_terms( $post_id, 'gctests_tax' );

		// Re-activate term counting so this doesn't affect other tests.
		gc_defer_term_counting( false );

		$this->assertIsArray( $terms );
		$this->assertSame( array( $term_id ), gc_list_pluck( $terms, 'term_id' ) );
	}

	/**
	 * @ticket 40306
	 */
	public function test_term_cache_should_be_invalidated_on_remove_object_terms() {
		register_taxonomy( 'gctests_tax', 'post' );

		// Create Test Category.
		$term_ids = self::factory()->term->create_many(
			2,
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$post_id = self::factory()->post->create();

		gc_set_object_terms( $post_id, $term_ids, 'gctests_tax' );

		// Prime cache.
		get_the_terms( $post_id, 'gctests_tax' );

		// Temporarily disable term counting, which performs its own cache invalidation.
		gc_defer_term_counting( true );

		gc_remove_object_terms( $post_id, $term_ids[0], 'gctests_tax' );

		$terms = get_the_terms( $post_id, 'gctests_tax' );

		// Re-activate term counting so this doesn't affect other tests.
		gc_defer_term_counting( false );

		$this->assertIsArray( $terms );
		$this->assertSame( array( $term_ids[1] ), gc_list_pluck( $terms, 'term_id' ) );
	}
}
