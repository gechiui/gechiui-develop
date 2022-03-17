<?php

/**
 * @group taxonomy
 */
class Tests_Taxonomy extends GC_UnitTestCase {
	public function test_get_post_taxonomies() {
		$this->assertSame( array( 'category', 'post_tag', 'post_format' ), get_object_taxonomies( 'post' ) );
	}

	public function test_get_link_taxonomies() {
		$this->assertSame( array( 'link_category' ), get_object_taxonomies( 'link' ) );
	}

	/**
	 * @ticket 5417
	 */
	public function test_get_unknown_taxonomies() {
		// Taxonomies for an unknown object type.
		$this->assertSame( array(), get_object_taxonomies( 'unknown' ) );
		$this->assertSame( array(), get_object_taxonomies( '' ) );
		$this->assertSame( array(), get_object_taxonomies( 0 ) );
		$this->assertSame( array(), get_object_taxonomies( null ) );
	}

	public function test_get_post_taxonomy() {
		foreach ( get_object_taxonomies( 'post' ) as $taxonomy ) {
			$tax = get_taxonomy( $taxonomy );
			// Should return an object with the correct taxonomy object type.
			$this->assertIsObject( $tax );
			$this->assertIsArray( $tax->object_type );
			$this->assertSame( array( 'post' ), $tax->object_type );
		}
	}

	public function test_get_the_taxonomies() {
		$post_id = self::factory()->post->create();

		$taxes = get_the_taxonomies( $post_id );
		$this->assertNotEmpty( $taxes );
		$this->assertSame( array( 'category' ), array_keys( $taxes ) );

		$id = self::factory()->tag->create();
		gc_set_post_tags( $post_id, array( $id ) );

		$taxes = get_the_taxonomies( $post_id );
		$this->assertNotEmpty( $taxes );
		$this->assertCount( 2, $taxes );
		$this->assertSame( array( 'category', 'post_tag' ), array_keys( $taxes ) );
	}

	/**
	 * @ticket 27238
	 */
	public function test_get_the_taxonomies_term_template() {
		$post_id = self::factory()->post->create();

		$taxes = get_the_taxonomies( $post_id, array( 'term_template' => '%2$s' ) );
		$this->assertSame( 'Categories: Uncategorized.', $taxes['category'] );

		$taxes = get_the_taxonomies( $post_id, array( 'term_template' => '<span class="foo"><a href="%1$s">%2$s</a></span>' ) );
		$link  = get_category_link( 1 );
		$this->assertSame( 'Categories: <span class="foo"><a href="' . $link . '">Uncategorized</a></span>.', $taxes['category'] );
	}

	public function test_the_taxonomies() {
		$post_id = self::factory()->post->create();

		$this->expectOutputString(
			sprintf(
				'Categories: <a href="%s">Uncategorized</a>.',
				get_category_link( 1 )
			)
		);
		the_taxonomies( array( 'post' => $post_id ) );
	}

	/**
	 * @ticket 27238
	 */
	public function test_the_taxonomies_term_template() {
		$post_id = self::factory()->post->create();

		$output = get_echo(
			'the_taxonomies',
			array(
				array(
					'post'          => $post_id,
					'term_template' => '%2$s',
				),
			)
		);
		$this->assertSame( 'Categories: Uncategorized.', $output );

		$output = get_echo(
			'the_taxonomies',
			array(
				array(
					'post'          => $post_id,
					'term_template' => '<span class="foo"><a href="%1$s">%2$s</a></span>',
				),
			)
		);
		$link   = get_category_link( 1 );
		$this->assertSame( 'Categories: <span class="foo"><a href="' . $link . '">Uncategorized</a></span>.', $output );
	}

	public function test_get_link_taxonomy() {
		foreach ( get_object_taxonomies( 'link' ) as $taxonomy ) {
			$tax = get_taxonomy( $taxonomy );
			// Should return an object with the correct taxonomy object type.
			$this->assertIsObject( $tax );
			$this->assertIsArray( $tax->object_type );
			$this->assertSame( array( 'link' ), $tax->object_type );
		}
	}

	public function test_taxonomy_exists_known() {
		$this->assertTrue( taxonomy_exists( 'category' ) );
		$this->assertTrue( taxonomy_exists( 'post_tag' ) );
		$this->assertTrue( taxonomy_exists( 'link_category' ) );
	}

	public function test_taxonomy_exists_unknown() {
		$this->assertFalse( taxonomy_exists( rand_str() ) );
		$this->assertFalse( taxonomy_exists( '' ) );
		$this->assertFalse( taxonomy_exists( 0 ) );
		$this->assertFalse( taxonomy_exists( null ) );
	}

	public function test_is_taxonomy_hierarchical() {
		$this->assertTrue( is_taxonomy_hierarchical( 'category' ) );
		$this->assertFalse( is_taxonomy_hierarchical( 'post_tag' ) );
		$this->assertFalse( is_taxonomy_hierarchical( 'link_category' ) );
	}

	public function test_is_taxonomy_hierarchical_unknown() {
		$this->assertFalse( is_taxonomy_hierarchical( rand_str() ) );
		$this->assertFalse( is_taxonomy_hierarchical( '' ) );
		$this->assertFalse( is_taxonomy_hierarchical( 0 ) );
		$this->assertFalse( is_taxonomy_hierarchical( null ) );
	}

	public function test_register_taxonomy() {

		// Make up a new taxonomy name, and ensure it's unused.
		$tax = 'tax_new';
		$this->assertFalse( taxonomy_exists( $tax ) );

		register_taxonomy( $tax, 'post' );
		$this->assertTrue( taxonomy_exists( $tax ) );
		$this->assertFalse( is_taxonomy_hierarchical( $tax ) );

		// Clean up.
		unset( $GLOBALS['gc_taxonomies'][ $tax ] );
	}

	public function test_register_hierarchical_taxonomy() {

		// Make up a new taxonomy name, and ensure it's unused.
		$tax = 'tax_new';
		$this->assertFalse( taxonomy_exists( $tax ) );

		register_taxonomy( $tax, 'post', array( 'hierarchical' => true ) );
		$this->assertTrue( taxonomy_exists( $tax ) );
		$this->assertTrue( is_taxonomy_hierarchical( $tax ) );

		// Clean up.
		unset( $GLOBALS['gc_taxonomies'][ $tax ] );
	}

	/**
	 * @ticket 48558
	 */
	public function test_register_taxonomy_return_value() {
		$this->assertInstanceOf( 'GC_Taxonomy', register_taxonomy( 'foo', 'post' ) );
	}

	/**
	 * @ticket 21593
	 *
	 * @expectedIncorrectUsage register_taxonomy
	 */
	public function test_register_taxonomy_with_too_long_name() {
		$this->assertInstanceOf( 'GC_Error', register_taxonomy( 'abcdefghijklmnopqrstuvwxyz0123456789', 'post', array() ) );
	}

	/**
	 * @ticket 31135
	 *
	 * @expectedIncorrectUsage register_taxonomy
	 */
	public function test_register_taxonomy_with_empty_name() {
		$this->assertInstanceOf( 'GC_Error', register_taxonomy( '', 'post', array() ) );
	}

	/**
	 * @ticket 26948
	 */
	public function test_register_taxonomy_show_in_quick_edit_should_default_to_value_of_show_ui() {
		register_taxonomy(
			'gctests_tax_1',
			'post',
			array(
				'show_ui' => true,
			)
		);

		register_taxonomy(
			'gctests_tax_2',
			'post',
			array(
				'show_ui' => false,
			)
		);

		$tax_1 = get_taxonomy( 'gctests_tax_1' );
		$this->assertTrue( $tax_1->show_in_quick_edit );

		$tax_2 = get_taxonomy( 'gctests_tax_2' );
		$this->assertFalse( $tax_2->show_in_quick_edit );
	}

	/**
	 * @ticket 11058
	 */
	public function test_registering_taxonomies_to_object_types() {
		// Create a taxonomy to test with.
		$tax = 'test_tax';
		$this->assertFalse( taxonomy_exists( $tax ) );
		register_taxonomy( $tax, 'post', array( 'hierarchical' => true ) );

		// Create a post type to test with.
		$post_type = 'test_cpt';
		$this->assertFalse( get_post_type( $post_type ) );
		$this->assertObjectHasAttribute( 'name', register_post_type( $post_type ) );

		// Core taxonomy, core post type.
		$this->assertTrue( unregister_taxonomy_for_object_type( 'category', 'post' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'category', 'post' ) );
		$this->assertTrue( register_taxonomy_for_object_type( 'category', 'post' ) );

		// Core taxonomy, non-core post type.
		$this->assertTrue( register_taxonomy_for_object_type( 'category', $post_type ) );
		$this->assertTrue( unregister_taxonomy_for_object_type( 'category', $post_type ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'category', $post_type ) );
		$this->assertTrue( register_taxonomy_for_object_type( 'category', $post_type ) );

		// Core taxonomies, non-post object types.
		$this->assertFalse( register_taxonomy_for_object_type( 'category', 'user' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'category', 'user' ) );

		// Non-core taxonomy, core post type.
		$this->assertTrue( unregister_taxonomy_for_object_type( $tax, 'post' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( $tax, 'post' ) );
		$this->assertTrue( register_taxonomy_for_object_type( $tax, 'post' ) );

		// Non-core taxonomy, non-core post type.
		$this->assertTrue( register_taxonomy_for_object_type( $tax, $post_type ) );
		$this->assertTrue( unregister_taxonomy_for_object_type( $tax, $post_type ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( $tax, $post_type ) );
		$this->assertTrue( register_taxonomy_for_object_type( $tax, $post_type ) );

		// Non-core taxonomies, non-post object types.
		$this->assertFalse( register_taxonomy_for_object_type( $tax, 'user' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( $tax, 'user' ) );

		unset( $GLOBALS['gc_taxonomies'][ $tax ] );
		_unregister_post_type( $post_type );

	}

	/**
	 * @ticket 32590
	 */
	public function test_register_taxonomy_for_post_type_for_taxonomy_with_no_object_type_should_filter_out_empty_object_types() {
		register_taxonomy( 'gctests_tax', '' );
		register_taxonomy_for_object_type( 'gctests_tax', 'post' );
		$tax = get_taxonomy( 'gctests_tax' );

		$expected = array( 'post' );
		$this->assertSameSets( $expected, $tax->object_type );
	}

	public function test_get_objects_in_term_should_return_invalid_taxonomy_error() {
		$terms = get_objects_in_term( 1, 'invalid_taxonomy' );
		$this->assertInstanceOf( 'GC_Error', $terms );
		$this->assertSame( 'invalid_taxonomy', $terms->get_error_code() );
	}

	public function test_get_objects_in_term_should_return_empty_array() {
		$this->assertSame( array(), get_objects_in_term( 1, 'post_tag' ) );
	}

	public function test_get_objects_in_term_should_return_objects_ids() {
		$tag_id              = self::factory()->tag->create();
		$cat_id              = self::factory()->category->create();
		$posts_with_tag      = array();
		$posts_with_category = array();

		for ( $i = 0; $i < 3; $i++ ) {
			$post_id = self::factory()->post->create();
			gc_set_post_tags( $post_id, array( $tag_id ) );
			$posts_with_tag[] = $post_id;
		}

		for ( $i = 0; $i < 3; $i++ ) {
			$post_id = self::factory()->post->create();
			gc_set_post_categories( $post_id, array( $cat_id ) );
			$posts_with_category[] = $post_id;
		}

		for ( $i = 0; $i < 3; $i++ ) {
			self::factory()->post->create();
		}

		$posts_with_terms = array_merge( $posts_with_tag, $posts_with_category );

		$this->assertEquals( $posts_with_tag, get_objects_in_term( $tag_id, 'post_tag' ) );
		$this->assertEquals( $posts_with_category, get_objects_in_term( $cat_id, 'category' ) );
		$this->assertEquals( $posts_with_terms, get_objects_in_term( array( $tag_id, $cat_id ), array( 'post_tag', 'category' ) ) );
		$this->assertEquals( array_reverse( $posts_with_tag ), get_objects_in_term( $tag_id, 'post_tag', array( 'order' => 'desc' ) ) );
	}

	/**
	 * @ticket 37094
	 */
	public function test_term_assignment_should_invalidate_get_objects_in_term_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$posts   = self::factory()->post->create_many( 2 );
		$term_id = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		gc_set_object_terms( $posts[1], $term_id, 'gctests_tax' );

		// Prime cache.
		$before = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertEqualSets( array( $posts[1] ), $before );

		gc_set_object_terms( $posts[1], array(), 'gctests_tax' );

		$after = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertSame( array(), $after );
	}

	/**
	 * @ticket 37094
	 */
	public function test_term_deletion_should_invalidate_get_objects_in_term_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$posts   = self::factory()->post->create_many( 2 );
		$term_id = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		gc_set_object_terms( $posts[1], $term_id, 'gctests_tax' );

		// Prime cache.
		$before = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertEqualSets( array( $posts[1] ), $before );

		gc_delete_term( $term_id, 'gctests_tax' );

		$after = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertSame( array(), $after );
	}

	/**
	 * @ticket 37094
	 */
	public function test_post_deletion_should_invalidate_get_objects_in_term_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$posts   = self::factory()->post->create_many( 2 );
		$term_id = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		gc_set_object_terms( $posts[1], $term_id, 'gctests_tax' );

		// Prime cache.
		$before = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertEqualSets( array( $posts[1] ), $before );

		gc_delete_post( $posts[1], true );

		$after = get_objects_in_term( $term_id, 'gctests_tax' );
		$this->assertSame( array(), $after );
	}

	/**
	 * @ticket 25706
	 */
	public function test_in_category() {
		$post = self::factory()->post->create_and_get();

		// in_category() returns false when first parameter is empty().
		$this->assertFalse( in_category( '', $post ) );
		$this->assertFalse( in_category( false, $post ) );
		$this->assertFalse( in_category( null, $post ) );

		// Test expected behavior of in_category().
		$term = gc_insert_term( 'Test', 'category' );
		gc_set_object_terms( $post->ID, $term['term_id'], 'category' );
		$this->assertTrue( in_category( $term['term_id'], $post ) );
	}

	public function test_insert_category_create() {
		$cat = array(
			'cat_ID'   => 0,
			'taxonomy' => 'category',
			'cat_name' => 'test1',
		);
		$this->assertIsNumeric( gc_insert_category( $cat, true ) );
	}

	public function test_insert_category_update() {
		$cat = array(
			'cat_ID'   => 1,
			'taxonomy' => 'category',
			'cat_name' => 'Updated Name',
		);
		$this->assertSame( 1, gc_insert_category( $cat ) );
	}

	public function test_insert_category_force_error_handle() {
		$cat = array(
			'cat_ID'   => 0,
			'taxonomy' => 'force_error',
			'cat_name' => 'Error',
		);
		$this->assertInstanceOf( 'GC_Error', gc_insert_category( $cat, true ) );
	}

	public function test_insert_category_force_error_no_handle() {
		$cat = array(
			'cat_ID'   => 0,
			'taxonomy' => 'force_error',
			'cat_name' => 'Error',
		);
		$this->assertSame( 0, gc_insert_category( $cat, false ) );
	}

	public function test_get_ancestors_taxonomy_non_hierarchical() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$this->assertSame( array(), get_ancestors( $t, 'gctests_tax' ) );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_get_ancestors_taxonomy() {
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
				'parent'   => $t1,
			)
		);
		$t3 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'parent'   => $t2,
			)
		);
		$t4 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'parent'   => $t1,
			)
		);

		$this->assertSameSets( array( $t2, $t1 ), get_ancestors( $t3, 'gctests_tax' ) );
		_unregister_taxonomy( 'gctests_tax' );
	}

	public function test_get_ancestors_post_type_non_hierarchical() {
		register_post_type( 'gctests_pt' );
		$p = self::factory()->post->create(
			array(
				'taxonomy' => 'gctests_pt',
			)
		);

		$this->assertSameSets( array(), get_ancestors( $p, 'gctests_tax' ) );
	}

	public function test_get_ancestors_post_type() {
		register_post_type(
			'gctests_pt',
			array(
				'hierarchical' => true,
			)
		);
		$p1 = self::factory()->post->create(
			array(
				'post_type' => 'gctests_pt',
			)
		);
		$p2 = self::factory()->post->create(
			array(
				'post_type'   => 'gctests_pt',
				'post_parent' => $p1,
			)
		);
		$p3 = self::factory()->post->create(
			array(
				'post_type'   => 'gctests_pt',
				'post_parent' => $p2,
			)
		);
		$p4 = self::factory()->post->create(
			array(
				'post_type'   => 'gctests_pt',
				'post_parent' => $p1,
			)
		);

		$this->assertSameSets( array( $p2, $p1 ), get_ancestors( $p3, 'gctests_pt' ) );
		_unregister_post_type( 'gctests_pt' );
	}

	/**
	 * @ticket 15029
	 */
	public function test_get_ancestors_taxonomy_post_type_conflict_resource_type_taxonomy() {
		register_post_type(
			'gctests_conflict',
			array(
				'hierarchical' => true,
			)
		);
		$p1 = self::factory()->post->create(
			array(
				'post_type' => 'gctests_conflict',
			)
		);
		$p2 = self::factory()->post->create(
			array(
				'post_type'   => 'gctests_conflict',
				'post_parent' => $p1,
			)
		);

		register_taxonomy(
			'gctests_conflict',
			'post',
			array(
				'hierarchical' => true,
			)
		);
		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_conflict',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_conflict',
				'parent'   => $t1,
			)
		);

		$this->assertSameSets( array( $p1 ), get_ancestors( $p2, 'gctests_conflict', 'post_type' ) );
		$this->assertSameSets( array( $t1 ), get_ancestors( $t2, 'gctests_conflict', 'taxonomy' ) );
		$this->assertSameSets( array( $t1 ), get_ancestors( $t2, 'gctests_conflict' ) );
		_unregister_post_type( 'gctests_pt' );
	}

	/**
	 * @ticket 21949
	 */
	public function test_nonpublicly_queryable_taxonomy_should_not_be_queryable_using_taxname_query_var() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'publicly_queryable' => false,
			)
		);

		$t = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		$this->go_to( '/?gctests_tax=' . $t->slug );

		$this->assertFalse( is_tax( 'gctests_tax' ) );
	}

	/**
	 * @ticket 21949
	 */
	public function test_it_should_be_possible_to_register_a_query_var_that_matches_the_name_of_a_nonpublicly_queryable_taxonomy() {
		global $gc;

		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'publicly_queryable' => false,
			)
		);
		$t = $this->factory->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = $this->factory->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		add_filter( 'do_parse_request', array( $this, 'register_query_var' ) );
		$this->go_to( '/?gctests_tax=foo' );
		remove_filter( 'do_parse_request', array( $this, 'register_query_var' ) );

		// Not a taxonomy...
		$this->assertFalse( is_tax( 'gctests_tax' ) );

		// ...but query var works.
		$this->assertSame( 'foo', $gc->query_vars['gctests_tax'] );
	}

	public static function register_query_var( $r ) {
		global $gc;

		$gc->add_query_var( 'gctests_tax' );

		return $r;
	}

	/**
	 * @ticket 21949
	 */
	public function test_nonpublicly_queryable_taxonomy_should_not_be_queryable_using_taxonomy_and_term_vars() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'publicly_queryable' => false,
			)
		);

		$t = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		$this->go_to( '/?taxonomy=gctests_tax&term=' . $t->slug );

		$this->assertFalse( is_tax( 'gctests_tax' ) );
	}

	/**
	 * @ticket 34491
	 */
	public function test_public_taxonomy_should_be_publicly_queryable() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'public' => true,
			)
		);

		$this->assertContains( 'gctests_tax', get_taxonomies( array( 'publicly_queryable' => true ) ) );

		$t = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		$this->go_to( '/?gctests_tax=' . $t->slug );

		$this->assertTrue( is_tax( 'gctests_tax' ) );
	}

	/**
	 * @ticket 34491
	 */
	public function test_private_taxonomy_should_not_be_publicly_queryable() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'public' => false,
			)
		);

		$this->assertContains( 'gctests_tax', get_taxonomies( array( 'publicly_queryable' => false ) ) );

		$t = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		$this->go_to( '/?gctests_tax=' . $t->slug );

		$this->assertFalse( is_tax( 'gctests_tax' ) );
	}

	/**
	 * @ticket 34491
	 */
	public function test_private_taxonomy_should_be_overridden_by_publicly_queryable() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'public'             => false,
				'publicly_queryable' => true,
			)
		);

		$this->assertContains( 'gctests_tax', get_taxonomies( array( 'publicly_queryable' => true ) ) );

		$t = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$p = self::factory()->post->create();
		gc_set_object_terms( $p, $t->slug, 'gctests_tax' );

		$this->go_to( '/?gctests_tax=' . $t->slug );

		$this->assertTrue( is_tax( 'gctests_tax' ) );
	}

	/**
	 * @ticket 35089
	 */
	public function test_query_var_should_be_forced_to_false_for_non_public_taxonomy() {
		register_taxonomy(
			'gctests_tax',
			'post',
			array(
				'public'    => false,
				'query_var' => true,
			)
		);

		$tax = get_taxonomy( 'gctests_tax' );
		$this->assertFalse( $tax->query_var );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_unknown_taxonomy() {
		$this->assertWPError( unregister_taxonomy( 'foo' ) );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_twice() {
		register_taxonomy( 'foo', 'post' );
		$this->assertTrue( unregister_taxonomy( 'foo' ) );
		$this->assertWPError( unregister_taxonomy( 'foo' ) );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_disallow_builtin_taxonomy() {
		$this->assertWPError( unregister_taxonomy( 'post_tag' ) );
		$this->assertWPError( unregister_taxonomy( 'category' ) );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_removes_query_vars() {
		global $gc;

		register_taxonomy( 'foo', 'post', array( 'query_var' => 'bar' ) );

		$this->assertIsInt( array_search( 'bar', $gc->public_query_vars, true ) );
		$this->assertTrue( unregister_taxonomy( 'foo' ) );
		$this->assertFalse( array_search( 'bar', $gc->public_query_vars, true ) );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_removes_permastruct() {
		$this->set_permalink_structure( '/%postname%' );

		global $gc_rewrite;

		register_taxonomy(
			'foo',
			'post',
			array(
				'query_var' => 'bar',
				'rewrite'   => true,
			)
		);

		$this->assertIsArray( $gc_rewrite->extra_permastructs['foo'] );
		$this->assertTrue( unregister_taxonomy( 'foo' ) );
		$this->assertArrayNotHasKey( 'foo', $gc_rewrite->extra_permastructs );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_removes_rewrite_rules() {
		$this->set_permalink_structure( '/%postname%' );

		global $gc_rewrite;

		register_taxonomy( 'foo', 'post', array( 'query_var' => 'bar' ) );

		$count_before = count( $gc_rewrite->rewritereplace );

		$this->assertContains( '%foo%', $gc_rewrite->rewritecode );
		$this->assertContains( 'bar=', $gc_rewrite->queryreplace );
		$this->assertTrue( unregister_taxonomy( 'foo' ) );
		$this->assertNotContains( '%foo%', $gc_rewrite->rewritecode );
		$this->assertNotContains( 'bar=', $gc_rewrite->queryreplace );
		$this->assertCount( --$count_before, $gc_rewrite->rewritereplace ); // Array was reduced by one value.
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_removes_taxonomy_from_global() {
		global $gc_taxonomies;

		register_taxonomy( 'foo', 'post' );

		$this->assertIsObject( $gc_taxonomies['foo'] );
		$this->assertIsObject( get_taxonomy( 'foo' ) );

		$this->assertTrue( unregister_taxonomy( 'foo' ) );

		$this->assertArrayNotHasKey( 'foo', $gc_taxonomies );
		$this->assertFalse( get_taxonomy( 'foo' ) );
	}

	/**
	 * @ticket 35227
	 */
	public function test_unregister_taxonomy_removes_meta_box_callback() {
		global $gc_filter;

		register_taxonomy( 'foo', 'post' );

		$this->assertArrayHasKey( 'gc_ajax_add-foo', $gc_filter );
		$this->assertCount( 1, $gc_filter['gc_ajax_add-foo']->callbacks );
		$this->assertTrue( unregister_taxonomy( 'foo' ) );
		$this->assertArrayNotHasKey( 'gc_ajax_add-foo', $gc_filter );
	}

	/**
	 * @ticket 35227
	 */
	public function test_taxonomy_does_not_exist_after_unregister_taxonomy() {
		register_taxonomy( 'foo', 'post' );
		$this->assertTrue( taxonomy_exists( 'foo' ) );
		unregister_taxonomy( 'foo' );
		$this->assertFalse( taxonomy_exists( 'foo' ) );
	}

	/**
	 * @ticket 39308
	 */
	public function test_taxonomy_name_property_should_not_get_overridden_by_passed_args() {
		register_taxonomy( 'foo', 'post', array( 'name' => 'bar' ) );

		$taxonomy = get_taxonomy( 'foo' );
		unregister_taxonomy( 'foo' );

		$this->assertSame( 'foo', $taxonomy->name );
	}

	/**
	 * @ticket 36514
	 */
	public function test_edit_post_hierarchical_taxonomy() {

		$taxonomy_name = 'foo';
		$term_name     = 'bar';

		register_taxonomy(
			$taxonomy_name,
			array( 'post' ),
			array(
				'hierarchical' => false,
				'meta_box_cb'  => 'post_categories_meta_box',
			)
		);
		$post = self::factory()->post->create_and_get(
			array(
				'post_type' => 'post',
			)
		);

		$term_id = self::factory()->term->create_object(
			array(
				'name'     => $term_name,
				'taxonomy' => $taxonomy_name,
			)
		);

		gc_set_current_user( self::factory()->user->create( array( 'role' => 'editor' ) ) );
		$updated_post_id = edit_post(
			array(
				'post_ID'   => $post->ID,
				'post_type' => 'post',
				'tax_input' => array(
					$taxonomy_name => array(
						(string) $term_id, // Cast term_id as string to match what's sent in GC Admin.
					),
				),
			)
		);

		$terms_obj        = get_the_terms( $updated_post_id, $taxonomy_name );
		$problematic_term = current( gc_list_pluck( $terms_obj, 'name' ) );

		$this->assertSame( $problematic_term, $term_name );
	}

	/**
	 * Test default term for custom taxonomy.
	 *
	 * @ticket 43517
	 */
	public function test_default_term_for_custom_taxonomy() {

		gc_set_current_user( self::factory()->user->create( array( 'role' => 'editor' ) ) );

		$tax = 'custom-tax';

		// Create custom taxonomy to test with.
		register_taxonomy(
			$tax,
			'post',
			array(
				'hierarchical' => true,
				'public'       => true,
				'default_term' => array(
					'name' => 'Default category',
					'slug' => 'default-category',
				),
			)
		);

		// Add post.
		$post_id = self::factory()->post->create(
			array(
				'post_title' => 'Foo',
				'post_type'  => 'post',
			)
		);

		// Test default term.
		$term = gc_get_post_terms( $post_id, $tax );
		$this->assertSame( get_option( 'default_term_' . $tax ), $term[0]->term_id );

		// Test default term deletion.
		$this->assertSame( gc_delete_term( $term[0]->term_id, $tax ), 0 );

		// Add custom post type.
		register_post_type(
			'post-custom-tax',
			array(
				'taxonomies' => array( $tax ),
			)
		);
		$post_id = self::factory()->post->create(
			array(
				'post_title' => 'Foo',
				'post_type'  => 'post-custom-tax',
			)
		);

		// Test default term.
		$term = gc_get_post_terms( $post_id, $tax );
		$this->assertSame( get_option( 'default_term_' . $tax ), $term[0]->term_id );

		// gc_set_object_terms() should not assign default term.
		gc_set_object_terms( $post_id, array(), $tax );
		$term = gc_get_post_terms( $post_id, $tax );
		$this->assertSame( array(), $term );

		unregister_taxonomy( $tax );
		$this->assertSame( get_option( 'default_term_' . $tax ), false );
	}

	/**
	 * @ticket 51320
	 */
	public function test_default_term_for_post_in_multiple_taxonomies() {
		$post_type = 'test_post_type';
		$tax1      = 'test_tax1';
		$tax2      = 'test_tax2';

		register_post_type( $post_type, array( 'taxonomies' => array( $tax1, $tax2 ) ) );
		register_taxonomy( $tax1, $post_type, array( 'default_term' => 'term_1' ) );
		register_taxonomy( $tax2, $post_type, array( 'default_term' => 'term_2' ) );

		$post_id = self::factory()->post->create( array( 'post_type' => $post_type ) );

		$taxonomies = get_post_taxonomies( $post_id );

		$this->assertContains( $tax1, $taxonomies );
		$this->assertContains( $tax2, $taxonomies );
	}
}
