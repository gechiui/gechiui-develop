<?php

/**
 * @group taxonomy
 */
class Tests_Term_Cache extends GC_UnitTestCase {
	public function set_up() {
		parent::set_up();

		gc_cache_delete( 'last_changed', 'terms' );
	}

	/**
	 * @ticket 25711
	 */
	public function test_category_children_cache() {
		// Test with only one Parent => Child.
		$term_id1       = self::factory()->category->create();
		$term_id1_child = self::factory()->category->create( array( 'parent' => $term_id1 ) );
		$hierarchy      = _get_term_hierarchy( 'category' );

		$this->assertSame( array( $term_id1 => array( $term_id1_child ) ), $hierarchy );

		// Add another Parent => Child.
		$term_id2       = self::factory()->category->create();
		$term_id2_child = self::factory()->category->create( array( 'parent' => $term_id2 ) );
		$hierarchy      = _get_term_hierarchy( 'category' );

		$this->assertSame(
			array(
				$term_id1 => array( $term_id1_child ),
				$term_id2 => array( $term_id2_child ),
			),
			$hierarchy
		);
	}

	/**
	 * @ticket 22526
	 */
	public function test_category_name_change() {
		$term    = self::factory()->category->create_and_get( array( 'name' => 'Foo' ) );
		$post_id = self::factory()->post->create();
		gc_set_post_categories( $post_id, $term->term_id );

		$post  = get_post( $post_id );
		$cats1 = get_the_category( $post->ID );
		$this->assertSame( $term->name, reset( $cats1 )->name );

		gc_update_term( $term->term_id, 'category', array( 'name' => 'Bar' ) );
		$cats2 = get_the_category( $post->ID );
		$this->assertNotEquals( $term->name, reset( $cats2 )->name );
	}

	/**
	 * @ticket 14485
	 */
	public function test_hierachy_invalidation() {
		$tax = 'burrito';
		register_taxonomy( $tax, 'post', array( 'hierarchical' => true ) );
		$this->assertTrue( get_taxonomy( $tax )->hierarchical );

		$step      = 1;
		$parent_id = 0;
		$children  = 0;

		foreach ( range( 1, 9 ) as $i ) {
			switch ( $step ) {
				case 1:
					$parent    = gc_insert_term( 'Parent' . $i, $tax );
					$parent_id = $parent['term_id'];
					break;
				case 2:
					$parent    = gc_insert_term( 'Child' . $i, $tax, array( 'parent' => $parent_id ) );
					$parent_id = $parent['term_id'];
					$children++;
					break;
				case 3:
					gc_insert_term( 'Grandchild' . $i, $tax, array( 'parent' => $parent_id ) );
					$parent_id = 0;
					$children++;
					break;
			}

			$terms = get_terms( $tax, array( 'hide_empty' => false ) );
			$this->assertCount( $i, $terms );
			if ( $i > 1 ) {
				$hierarchy = _get_term_hierarchy( $tax );
				$this->assertNotEmpty( $hierarchy );
				$this->assertSame( $children, count( $hierarchy, COUNT_RECURSIVE ) - count( $hierarchy ) );
			}

			if ( 0 === ( $i % 3 ) ) {
				$step = 1;
			} else {
				$step++;
			}
		}

		_unregister_taxonomy( $tax );
	}

	public function test_get_term_should_update_term_cache_when_passed_an_object() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		$term = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$term_object = get_term( $term, 'gctests_tax' );
		gc_cache_delete( $term, 'terms' );

		// Affirm that the cache is empty.
		$this->assertEmpty( gc_cache_get( $term, 'terms' ) );

		$num_queries = $gcdb->num_queries;

		// get_term() will only be update the cache if the 'filter' prop is unset.
		unset( $term_object->filter );

		$term_object_2 = get_term( $term_object, 'gctests_tax' );

		// No new queries should have fired.
		$this->assertSame( $num_queries, $gcdb->num_queries );
		$this->assertSame( $term_object, $term_object_2 );
	}

	public function test_get_term_should_update_term_cache_when_passed_a_valid_term_identifier() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		$term = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		gc_cache_delete( $term, 'terms' );

		// Affirm that the cache is empty.
		$this->assertEmpty( gc_cache_get( $term, 'terms' ) );

		$num_queries = $gcdb->num_queries;

		// Prime cache.
		$term_object = get_term( $term, 'gctests_tax' );
		$this->assertNotEmpty( gc_cache_get( $term, 'terms' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$term_object_2 = get_term( $term, 'gctests_tax' );

		// No new queries should have fired.
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );
		$this->assertEquals( $term_object, $term_object_2 );
	}

	public function test_get_term_by_should_update_term_cache_when_passed_a_valid_term_identifier() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		$term = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		gc_cache_delete( $term, 'terms' );

		// Affirm that the cache is empty.
		$this->assertEmpty( gc_cache_get( $term, 'terms' ) );

		$num_queries = $gcdb->num_queries;

		// Prime cache.
		$term_object = get_term_by( 'id', $term, 'gctests_tax' );
		$this->assertNotEmpty( gc_cache_get( $term, 'terms' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$term_object_2 = get_term( $term, 'gctests_tax' );

		// No new queries should have fired.
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );
		$this->assertEquals( $term_object, $term_object_2 );
	}

	/**
	 * @ticket 30749
	 */
	public function test_get_terms_should_update_cache_for_located_terms() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );

		$terms = self::factory()->term->create_many(
			5,
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$term_objects = get_terms(
			'gctests_tax',
			array(
				'hide_empty' => false,
			)
		);

		$num_queries = $gcdb->num_queries;

		foreach ( $terms as $term_id ) {
			get_term( $term_id, 'gctests_tax' );
		}

		$this->assertSame( $num_queries, $gcdb->num_queries );

		_unregister_taxonomy( 'gctests_tax' );
	}

	/**
	 * @ticket 35462
	 */
	public function test_term_objects_should_not_be_modified_by_update_term_cache() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );
		$p = self::factory()->post->create();

		gc_set_object_terms( $p, $t, 'gctests_tax' );

		$terms = gc_get_object_terms( $p, 'gctests_tax', array( 'fields' => 'all_with_object_id' ) );

		update_term_cache( $terms );

		foreach ( $terms as $term ) {
			$this->assertSame( $p, $term->object_id );
		}
	}

	/**
	 * @ticket 21760
	 */
	public function test_get_term_by_slug_cache() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'slug'     => 'burrito',
				'name'     => 'Taco',
				'taxonomy' => 'post_tag',
			)
		);

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries = $gcdb->num_queries;

		$term = get_term_by( 'slug', 'burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( 'Taco', $term->name );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// This should now hit cache.
		$term = get_term_by( 'slug', 'burrito', 'post_tag' );
		$this->assertSame( 'Taco', $term->name );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		$this->assertEquals( get_term( $term_id, 'post_tag' ), $term );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 21760
	 */
	public function test_get_term_by_slug_cache_update() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'slug'     => 'burrito',
				'name'     => 'Taco',
				'taxonomy' => 'post_tag',
			)
		);

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries = $gcdb->num_queries;

		$term = get_term_by( 'slug', 'burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( 'Taco', $term->name );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// This should now hit cache.
		$term = get_term_by( 'slug', 'burrito', 'post_tag' );
		$this->assertSame( 'Taco', $term->name );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Update the tag which invalidates the cache.
		gc_update_term( $term_id, 'post_tag', array( 'name' => 'No Taco' ) );
		$num_queries = $gcdb->num_queries;

		// This should not hit cache.
		$term = get_term_by( 'slug', 'burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( 'No Taco', $term->name );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 21760
	 */
	public function test_get_term_by_name_cache() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'name'     => 'Burrito',
				'slug'     => 'noburrito',
				'taxonomy' => 'post_tag',
			)
		);

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries = $gcdb->num_queries;

		get_term_by( 'name', 'Burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// This should now hit cache.
		$term = get_term_by( 'name', 'Burrito', 'post_tag' );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		$this->assertEquals( get_term( $term_id, 'post_tag' ), $term );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 21760
	 */
	public function test_get_term_by_name_cache_update() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'name'     => 'Burrito',
				'slug'     => 'noburrito',
				'taxonomy' => 'post_tag',
			)
		);

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries = $gcdb->num_queries;

		get_term_by( 'name', 'Burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// This should now hit cache.
		get_term_by( 'name', 'Burrito', 'post_tag' );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Update the tag which invalidates the cache.
		gc_update_term( $term_id, 'post_tag', array( 'slug' => 'taco' ) );
		$num_queries = $gcdb->num_queries;

		// This should not hit cache.
		get_term_by( 'name', 'burrito', 'post_tag' );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 21760
	 */
	public function test_invalidating_term_caches_should_fail_when_invalidation_is_suspended() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'name'     => 'Burrito',
				'taxonomy' => 'post_tag',
			)
		);

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries  = $gcdb->num_queries;
		$last_changed = gc_cache_get( 'last_changed', 'terms' );

		$term1 = get_term_by( 'name', 'Burrito', 'post_tag' );
		$num_queries++;

		// Verify the term is cached.
		$term2 = get_term_by( 'name', 'Burrito', 'post_tag' );
		$this->assertSame( $num_queries, $gcdb->num_queries );
		$this->assertEquals( $term1, $term2 );

		$suspend = gc_suspend_cache_invalidation();

		// Update the tag.
		gc_update_term( $term_id, 'post_tag', array( 'name' => 'Taco' ) );
		$num_queries = $gcdb->num_queries;

		// Verify that the cached term still matches the initial cached term.
		$term3 = get_term_by( 'name', 'Burrito', 'post_tag' );
		$this->assertSame( $num_queries, $gcdb->num_queries );
		$this->assertEquals( $term1, $term3 );

		// Verify that last changed has not been updated as part of an invalidation routine.
		$this->assertSame( $last_changed, gc_cache_get( 'last_changed', 'terms' ) );

		// Clean up.
		gc_suspend_cache_invalidation( $suspend );
	}

	/**
	 * @ticket 21760
	 */
	public function test_get_term_by_does_not_prime_term_meta_cache() {
		global $gcdb;

		$term_id = $this->factory->term->create(
			array(
				'name'     => 'Burrito',
				'taxonomy' => 'post_tag',
			)
		);
		add_term_meta( $term_id, 'foo', 'bar' );

		clean_term_cache( $term_id, 'post_tag' );
		$num_queries = $gcdb->num_queries;

		$term = get_term_by( 'name', 'Burrito', 'post_tag' );
		$num_queries++;
		$this->assertInstanceOf( 'GC_Term', $term );
		$this->assertSame( $term_id, $term->term_id );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		$term_meta = get_term_meta( $term_id, 'foo', true );
		$num_queries++;
		$this->assertSame( $term_meta, 'bar' );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 37291
	 */
	public function test_get_object_term_cache_should_return_error_if_any_term_is_an_error() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );
		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t, 'gctests_tax' );

		// Prime cache.
		$terms = get_the_terms( $p, 'gctests_tax' );
		$this->assertSameSets( array( $t ), gc_list_pluck( $terms, 'term_id' ) );

		/*
		 * Modify cached array to insert an empty term ID,
		 * which will trigger an error in get_term().
		 */
		$cached_ids   = gc_cache_get( $p, 'gctests_tax_relationships' );
		$cached_ids[] = 0;
		gc_cache_set( $p, $cached_ids, 'gctests_tax_relationships' );

		$terms = get_the_terms( $p, 'gctests_tax' );
		$this->assertWPError( $terms );
	}
}
