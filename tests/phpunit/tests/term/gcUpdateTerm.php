<?php

/**
 * @group taxonomy
 */
class Tests_Term_GcUpdateTerm extends GC_UnitTestCase {
	public function test_gc_update_term_taxonomy_does_not_exist() {
		$found = gc_update_term( 1, 'bar' );

		$this->assertWPError( $found );
		$this->assertSame( 'invalid_taxonomy', $found->get_error_code() );
	}

	public function test_gc_update_term_term_empty_string_should_return_gc_error() {
		$found = gc_update_term( '', 'post_tag' );

		$this->assertWPError( $found );
		$this->assertSame( 'invalid_term', $found->get_error_code() );
	}

	public function test_gc_update_term_unslash_name() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'name' => 'Let\\\'s all say \\"Hooray\\" for GeChiUI taxonomy',
			)
		);

		$term = get_term( $found['term_id'], 'gctests_tax' );
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertSame( 'Let\'s all say "Hooray" for GeChiUI taxonomy', $term->name );
	}

	public function test_gc_update_term_unslash_description() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'description' => 'Let\\\'s all say \\"Hooray\\" for GeChiUI taxonomy',
			)
		);

		$term = get_term( $found['term_id'], 'gctests_tax' );
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertSame( 'Let\'s all say "Hooray" for GeChiUI taxonomy', $term->description );
	}

	public function test_gc_update_term_name_empty_string() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'name' => '',
			)
		);

		$this->assertWPError( $found );
		$this->assertSame( 'empty_term_name', $found->get_error_code() );
		_unregister_taxonomy( 'gctests_tax' );
	}

	/**
	 * @ticket 29614
	 */
	public function test_gc_update_term_parent_does_not_exist() {
		register_taxonomy(
			'gctests_tax',
			array(
				'hierarchical' => true,
			)
		);
		$fake_term_id = 787878;

		$this->assertNull( term_exists( $fake_term_id, 'gctests_tax' ) );

		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'parent' => $fake_term_id,
			)
		);

		$this->assertWPError( $found );
		$this->assertSame( 'missing_parent', $found->get_error_code() );

		$term = get_term( $t, 'gctests_tax' );
		$this->assertSame( 0, $term->parent );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_slug_empty_string_while_not_updating_name() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Foo Bar',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'slug' => '',
			)
		);

		$term = get_term( $t, 'gctests_tax' );
		$this->assertSame( 'foo-bar', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_slug_empty_string_while_updating_name() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'name' => 'Foo Bar',
				'slug' => '',
			)
		);

		$term = get_term( $t, 'gctests_tax' );
		$this->assertSame( 'foo-bar', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_slug_set_slug() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'slug' => 'foo-bar',
			)
		);

		$term = get_term( $t, 'gctests_tax' );
		$this->assertSame( 'foo-bar', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	/**
	 * @ticket 5809
	 */
	public function test_gc_update_term_should_not_create_duplicate_slugs_within_the_same_taxonomy() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'name'     => 'Foo',
				'slug'     => 'foo',
				'taxonomy' => 'gctests_tax',
			)
		);

		$t2 = self::factory()->term->create(
			array(
				'name'     => 'Bar',
				'slug'     => 'bar',
				'taxonomy' => 'gctests_tax',
			)
		);

		$updated = gc_update_term(
			$t2,
			'gctests_tax',
			array(
				'slug' => 'foo',
			)
		);

		$this->assertWPError( $updated );
		$this->assertSame( 'duplicate_term_slug', $updated->get_error_code() );
	}

	/**
	 * @ticket 5809
	 */
	public function test_gc_update_term_should_allow_duplicate_slugs_in_different_taxonomy() {
		register_taxonomy( 'gctests_tax', 'post' );
		register_taxonomy( 'gctests_tax_2', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'name'     => 'Foo',
				'slug'     => 'foo',
				'taxonomy' => 'gctests_tax',
			)
		);

		$t2 = self::factory()->term->create(
			array(
				'name'     => 'Foo',
				'slug'     => 'bar',
				'taxonomy' => 'gctests_tax_2',
			)
		);

		$updated = gc_update_term(
			$t2,
			'gctests_tax_2',
			array(
				'slug' => 'foo',
			)
		);

		$this->assertNotWPError( $updated );

		$t1_term = get_term( $t1, 'gctests_tax' );
		$t2_term = get_term( $t2, 'gctests_tax_2' );
		$this->assertSame( $t1_term->slug, $t2_term->slug );
	}

	/**
	 * @ticket 30780
	 */
	public function test_gc_update_term_should_allow_duplicate_names_in_different_taxonomies() {
		register_taxonomy( 'gctests_tax', 'post' );
		register_taxonomy( 'gctests_tax_2', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'name'     => 'Foo',
				'slug'     => 'foo',
				'taxonomy' => 'gctests_tax',
			)
		);

		$t2 = self::factory()->term->create(
			array(
				'name'     => 'Bar',
				'slug'     => 'bar',
				'taxonomy' => 'gctests_tax_2',
			)
		);

		$updated = gc_update_term(
			$t2,
			'gctests_tax_2',
			array(
				'name' => 'Foo',
			)
		);

		$this->assertNotWPError( $updated );

		$t2_term = get_term( $t2, 'gctests_tax_2' );
		$this->assertSame( 'Foo', $t2_term->name );
	}

	/**
	 * @ticket 30780
	 */
	public function test_gc_update_term_should_allow_duplicate_names_at_different_levels_of_the_same_taxonomy() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'hierarchical' => true,
			)
		);

		$t1 = self::factory()->term->create(
			array(
				'name'     => 'Foo',
				'slug'     => 'foo',
				'taxonomy' => 'gctests_tax',
			)
		);

		$t2 = self::factory()->term->create(
			array(
				'name'     => 'Bar',
				'slug'     => 'bar',
				'taxonomy' => 'gctests_tax',
				'parent'   => $t1,
			)
		);

		$t3 = self::factory()->term->create(
			array(
				'name'     => 'Bar Child',
				'slug'     => 'bar-child',
				'taxonomy' => 'gctests_tax',
				'parent'   => $t2,
			)
		);

		$updated = gc_update_term(
			$t3,
			'gctests_tax',
			array(
				'name' => 'Bar',
			)
		);

		$this->assertNotWPError( $updated );

		$t3_term = get_term( $t3, 'gctests_tax' );
		$this->assertSame( 'Bar', $t3_term->name );
	}

	/**
	 * @ticket 5809
	 */
	public function test_gc_update_term_should_split_shared_term() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		register_taxonomy( 'gctests_tax_2', 'post' );

		$t1 = gc_insert_term( 'Foo', 'gctests_tax' );
		$t2 = gc_insert_term( 'Foo', 'gctests_tax_2' );

		// Manually modify because shared terms shouldn't naturally occur.
		$gcdb->update(
			$gcdb->term_taxonomy,
			array( 'term_id' => $t1['term_id'] ),
			array( 'term_taxonomy_id' => $t2['term_taxonomy_id'] ),
			array( '%d' ),
			array( '%d' )
		);

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( 'Foo' ), 'gctests_tax' );
		gc_set_object_terms( $posts[1], array( 'Foo' ), 'gctests_tax_2' );

		// Verify that the terms are shared.
		$t1_terms = gc_get_object_terms( $posts[0], 'gctests_tax' );
		$t2_terms = gc_get_object_terms( $posts[1], 'gctests_tax_2' );
		$this->assertSame( $t1_terms[0]->term_id, $t2_terms[0]->term_id );

		gc_update_term(
			$t2_terms[0]->term_id,
			'gctests_tax_2',
			array(
				'name' => 'New Foo',
			)
		);

		$t1_terms = gc_get_object_terms( $posts[0], 'gctests_tax' );
		$t2_terms = gc_get_object_terms( $posts[1], 'gctests_tax_2' );
		$this->assertNotEquals( $t1_terms[0]->term_id, $t2_terms[0]->term_id );
	}

	public function test_gc_update_term_alias_of_no_term_group() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t1     = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$term_1 = get_term( $t1, 'gctests_tax' );

		$created_term_ids = gc_insert_term( 'Foo', 'gctests_tax' );
		gc_update_term(
			$created_term_ids['term_id'],
			'gctests_tax',
			array(
				'alias_of' => $term_1->slug,
			)
		);
		$created_term = get_term( $created_term_ids['term_id'], 'gctests_tax' );

		$updated_term_1 = get_term( $t1, 'gctests_tax' );
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertSame( 0, $term_1->term_group );
		$this->assertNotEmpty( $created_term->term_group );
		$this->assertSame( $created_term->term_group, $updated_term_1->term_group );
	}

	public function test_gc_update_term_alias_of_existing_term_group() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t1     = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$term_1 = get_term( $t1, 'gctests_tax' );

		$t2     = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'alias_of' => $term_1->slug,
			)
		);
		$term_2 = get_term( $t2, 'gctests_tax' );

		$created_term_ids = gc_insert_term( 'Foo', 'gctests_tax' );
		gc_update_term(
			$created_term_ids['term_id'],
			'gctests_tax',
			array(
				'alias_of' => $term_2->slug,
			)
		);
		$created_term = get_term( $created_term_ids['term_id'], 'gctests_tax' );
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertNotEmpty( $created_term->term_group );
		$this->assertSame( $created_term->term_group, $term_2->term_group );
	}

	public function test_gc_update_term_alias_of_nonexistent_term() {
		register_taxonomy( 'gctests_tax', 'post' );
		$created_term_ids = gc_insert_term( 'Foo', 'gctests_tax' );
		gc_update_term(
			$created_term_ids['term_id'],
			'gctests_tax',
			array(
				'alias_of' => 'bar',
			)
		);
		$created_term = get_term( $created_term_ids['term_id'], 'gctests_tax' );
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertSame( 0, $created_term->term_group );
	}

	public function test_gc_update_term_slug_same_as_old_slug() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo',
			)
		);

		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'slug' => 'foo',
			)
		);

		$term = get_term( $t, 'gctests_tax' );

		$this->assertSame( $t, $found['term_id'] );
		$this->assertSame( 'foo', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_duplicate_slug_generated_due_to_empty_slug_param() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo-bar',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'not foo bar',
			)
		);

		$found = gc_update_term(
			$t2,
			'gctests_tax',
			array(
				'slug' => '',
				'name' => 'Foo? Bar!', // Will sanitize to 'foo-bar'.
			)
		);

		$term = get_term( $t2, 'gctests_tax' );

		$this->assertSame( $t2, $found['term_id'] );
		$this->assertSame( 'foo-bar-2', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_duplicate_slug_with_changed_parent() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'hierarchical' => true,
			)
		);
		$p  = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo-bar',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$found = gc_update_term(
			$t2,
			'gctests_tax',
			array(
				'parent' => $p,
				'slug'   => 'foo-bar',
			)
		);

		$term        = get_term( $t2, 'gctests_tax' );
		$parent_term = get_term( $p, 'gctests_tax' );

		$this->assertSame( $t2, $found['term_id'] );
		$this->assertSame( 'foo-bar-' . $parent_term->slug, $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_duplicate_slug_failure() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo-bar',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'my-old-slug',
			)
		);

		$found = gc_update_term(
			$t2,
			'gctests_tax',
			array(
				'slug' => 'foo-bar',
			)
		);

		$term = get_term( $t2, 'gctests_tax' );

		$this->assertWPError( $found );
		$this->assertSame( 'duplicate_term_slug', $found->get_error_code() );
		$this->assertSame( 'my-old-slug', $term->slug );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_gc_update_term_should_return_term_id_and_term_taxonomy_id() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t     = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'slug' => 'foo',
			)
		);

		$term_by_id   = get_term( $found['term_id'], 'gctests_tax' );
		$term_by_slug = get_term_by( 'slug', 'foo', 'gctests_tax' );
		$term_by_ttid = get_term_by( 'term_taxonomy_id', $found['term_taxonomy_id'], 'gctests_tax' );

		_unregister_taxonomy( 'gctests_tax' );

		$this->assertIsArray( $found );
		$this->assertNotEmpty( $found['term_id'] );
		$this->assertNotEmpty( $found['term_taxonomy_id'] );
		$this->assertNotEmpty( $term_by_id );
		$this->assertEquals( $term_by_id, $term_by_slug );
		$this->assertEquals( $term_by_id, $term_by_ttid );
	}

	/**
	 * @ticket 32876
	 */
	public function test_gc_update_term_should_return_int_values_for_term_id_and_term_taxonomy_id() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t     = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$found = gc_update_term(
			$t,
			'gctests_tax',
			array(
				'slug' => 'foo',
			)
		);

		$this->assertIsInt( $found['term_id'] );
		$this->assertIsInt( $found['term_taxonomy_id'] );
	}

	public function test_gc_update_term_should_clean_term_cache() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'hierarchical' => true,
			)
		);

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		/*
		 * It doesn't appear that GeChiUI itself ever sets these
		 * caches, but we should ensure that they're being cleared for
		 * compatibility with third-party addons. Prime the caches
		 * manually.
		 */
		gc_cache_set( 'all_ids', array( 1, 2, 3 ), 'gctests_tax' );
		gc_cache_set( 'get', array( 1, 2, 3 ), 'gctests_tax' );

		$found = gc_update_term(
			$t1,
			'gctests_tax',
			array(
				'parent' => $t2,
			)
		);
		_unregister_taxonomy( 'gctests_tax' );

		$this->assertFalse( gc_cache_get( 'all_ids', 'gctests_tax' ) );
		$this->assertFalse( gc_cache_get( 'get', 'gctests_tax' ) );

		$cached_children = get_option( 'gctests_tax_children' );
		$this->assertNotEmpty( $cached_children[ $t2 ] );
		$this->assertContains( $found['term_id'], $cached_children[ $t2 ] );
	}

	/**
	 * @ticket 30780
	 */
	public function test_gc_update_term_should_assign_new_slug_when_reassigning_parent_as_long_as_there_is_no_other_term_with_the_same_slug() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'hierarchical' => true,
			)
		);
		register_taxonomy(
			'gctests_tax_2',
			'post',
			array(
				'hierarchical' => true,
			)
		);

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'parent-term',
			)
		);

		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo',
			)
		);

		gc_update_term(
			$t2,
			'gctests_tax',
			array(
				'parent' => $t1,
			)
		);

		$t2_term = get_term( $t2, 'gctests_tax' );

		$this->assertSame( 'foo', $t2_term->slug );

		_unregister_taxonomy( 'gctests_tax' );
	}

	/**
	 * @ticket 30780
	 */
	public function test_gc_update_term_should_not_assign_new_slug_when_reassigning_parent_as_long_as_there_is_no_other_slug_conflict_within_the_taxonomy() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'hierarchical' => true,
			)
		);
		register_taxonomy(
			'gctests_tax_2',
			'post',
			array(
				'hierarchical' => true,
			)
		);

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'parent-term',
			)
		);

		// Same slug but in a different tax.
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax_2',
				'slug'     => 'foo',
			)
		);

		$t3 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo',
			)
		);

		gc_update_term(
			$t3,
			'gctests_tax',
			array(
				'parent' => $t1,
			)
		);

		$t3_term = get_term( $t3, 'gctests_tax' );

		$this->assertSame( 'foo', $t3_term->slug );

		_unregister_taxonomy( 'gctests_tax' );
	}

	/**
	 * @ticket 31954
	 */
	public function test_gc_update_term_with_null_get_term() {
		$t     = self::factory()->term->create( array( 'taxonomy' => 'category' ) );
		$found = gc_update_term( $t, 'post_tag', array( 'slug' => 'foo' ) );

		$this->assertWPError( $found );
		$this->assertSame( 'invalid_term', $found->get_error_code() );
	}

}
