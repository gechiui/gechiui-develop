<?php

/**
 * @group taxonomy
 */
class Tests_IsObjectInTerm extends GC_UnitTestCase {
	public function test_terms_are_ints() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );
		$t2 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( $t1 ), 'gctests_tax' );

		$this->assertTrue( is_object_in_term( $posts[0], 'gctests_tax', array( $t1, $t2 ) ) );
		$this->assertFalse( is_object_in_term( $posts[1], 'gctests_tax', array( $t1, $t2 ) ) );

		_unregister_taxonomy( 'gctests_tax', 'post' );
	}

	public function test_terms_are_strings_and_match_term_id() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );
		$t2 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( $t1 ), 'gctests_tax' );

		$t1_str = (string) $t1;
		$t2_str = (string) $t2;

		$this->assertTrue( is_object_in_term( $posts[0], 'gctests_tax', array( $t1_str, $t2_str ) ) );
		$this->assertFalse( is_object_in_term( $posts[1], 'gctests_tax', array( $t1_str, $t2_str ) ) );

		_unregister_taxonomy( 'gctests_tax', 'post' );
	}

	public function test_terms_are_strings_and_match_term_name() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Foo',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Bar',
			)
		);

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( $t1 ), 'gctests_tax' );

		$this->assertTrue( is_object_in_term( $posts[0], 'gctests_tax', array( 'Foo', 'Bar' ) ) );
		$this->assertFalse( is_object_in_term( $posts[1], 'gctests_tax', array( 'Foo', 'Bar' ) ) );

		_unregister_taxonomy( 'gctests_tax', 'post' );
	}

	public function test_terms_are_strings_and_match_term_slug() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'bar',
			)
		);

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( $t1 ), 'gctests_tax' );

		$this->assertTrue( is_object_in_term( $posts[0], 'gctests_tax', array( 'foo', 'bar' ) ) );
		$this->assertFalse( is_object_in_term( $posts[1], 'gctests_tax', array( 'foo', 'bar' ) ) );

		_unregister_taxonomy( 'gctests_tax', 'post' );
	}

	public function test_terms_contain_strings_and_ints_and_match_term_id_as_int() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'foo',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'bar',
			)
		);

		$posts = self::factory()->post->create_many( 2 );
		gc_set_object_terms( $posts[0], array( $t1 ), 'gctests_tax' );

		$this->assertTrue( is_object_in_term( $posts[0], 'gctests_tax', array( $t1, 'bar' ) ) );
		$this->assertFalse( is_object_in_term( $posts[1], 'gctests_tax', array( $t1, 'bar' ) ) );

		_unregister_taxonomy( 'gctests_tax', 'post' );
	}

	/**
	 * @ticket 29467
	 */
	public function test_should_not_return_true_if_term_name_begins_with_existing_term_id() {
		register_taxonomy( 'gctests_tax', 'post' );
		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );

		$post_ID = self::factory()->post->create();
		gc_set_object_terms( $post_ID, $t, 'gctests_tax' );

		$int_tax_name = $t . '_term_name';

		$this->assertFalse( is_object_in_term( $post_ID, 'gctests_tax', $int_tax_name ) );

		// Verify it works properly when the post is actually in the term.
		gc_set_object_terms( $post_ID, array( $int_tax_name ), 'gctests_tax' );
		$this->assertTrue( is_object_in_term( $post_ID, 'gctests_tax', $int_tax_name ) );
	}

	/**
	 * @ticket 32044
	 */
	public function test_should_populate_and_hit_relationships_cache() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		$terms = self::factory()->term->create_many( 2, array( 'taxonomy' => 'gctests_tax' ) );

		$o = 12345;
		gc_set_object_terms( $o, $terms[0], 'gctests_tax' );

		$num_queries = $gcdb->num_queries;
		$this->assertTrue( is_object_in_term( $o, 'gctests_tax', $terms[0] ) );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );

		$this->assertFalse( is_object_in_term( $o, 'gctests_tax', $terms[1] ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 32044
	 */
	public function test_should_not_be_fooled_by_a_stale_relationship_cache() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		$terms = self::factory()->term->create_many( 2, array( 'taxonomy' => 'gctests_tax' ) );

		$o = 12345;
		gc_set_object_terms( $o, $terms[0], 'gctests_tax' );

		$num_queries = $gcdb->num_queries;
		$this->assertTrue( is_object_in_term( $o, 'gctests_tax', $terms[0] ) );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );

		gc_set_object_terms( $o, $terms[1], 'gctests_tax' );

		$num_queries = $gcdb->num_queries;
		$this->assertTrue( is_object_in_term( $o, 'gctests_tax', $terms[1] ) );
		$num_queries++;
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}

	/**
	 * @ticket 37721
	 */
	public function test_invalid_taxonomy_should_return_gc_error_object() {
		$this->assertWPError( is_object_in_term( 12345, 'foo', 'bar' ) );
	}
}
