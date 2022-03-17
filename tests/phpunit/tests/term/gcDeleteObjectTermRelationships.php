<?php

/**
 * @group taxonomy
 * @covers ::gc_delete_object_term_relationships
 */
class Tests_Term_GcDeleteObjectTermRelationships extends GC_UnitTestCase {
	public function test_single_taxonomy() {
		register_taxonomy( 'gctests_tax1', 'post' );
		register_taxonomy( 'gctests_tax2', 'post' );

		$t1 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax1' ) );
		$t2 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax2' ) );

		$object_id = 567;

		gc_set_object_terms( $object_id, array( $t1 ), 'gctests_tax1' );
		gc_set_object_terms( $object_id, array( $t2 ), 'gctests_tax2' );

		// Confirm the setup.
		$terms = gc_get_object_terms( $object_id, array( 'gctests_tax1', 'gctests_tax2' ), array( 'fields' => 'ids' ) );
		$this->assertSameSets( array( $t1, $t2 ), $terms );

		// gc_delete_object_term_relationships() doesn't have a return value.
		gc_delete_object_term_relationships( $object_id, 'gctests_tax2' );
		$terms = gc_get_object_terms( $object_id, array( 'gctests_tax1', 'gctests_tax2' ), array( 'fields' => 'ids' ) );

		$this->assertSameSets( array( $t1 ), $terms );
	}

	public function test_array_of_taxonomies() {
		register_taxonomy( 'gctests_tax1', 'post' );
		register_taxonomy( 'gctests_tax2', 'post' );
		register_taxonomy( 'gctests_tax3', 'post' );

		$t1 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax1' ) );
		$t2 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax2' ) );
		$t3 = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax3' ) );

		$object_id = 567;

		gc_set_object_terms( $object_id, array( $t1 ), 'gctests_tax1' );
		gc_set_object_terms( $object_id, array( $t2 ), 'gctests_tax2' );
		gc_set_object_terms( $object_id, array( $t3 ), 'gctests_tax3' );

		// Confirm the setup.
		$terms = gc_get_object_terms( $object_id, array( 'gctests_tax1', 'gctests_tax2', 'gctests_tax3' ), array( 'fields' => 'ids' ) );
		$this->assertSameSets( array( $t1, $t2, $t3 ), $terms );

		// gc_delete_object_term_relationships() doesn't have a return value.
		gc_delete_object_term_relationships( $object_id, array( 'gctests_tax1', 'gctests_tax3' ) );
		$terms = gc_get_object_terms( $object_id, array( 'gctests_tax1', 'gctests_tax2', 'gctests_tax3' ), array( 'fields' => 'ids' ) );

		$this->assertSameSets( array( $t2 ), $terms );
	}
}
