<?php

/**
 * @group taxonomy
 */
class Tests_Term_GcDeleteTerm extends GC_UnitTestCase {
	protected $deleted_term;
	protected $object_ids;

	/**
	 * @ticket 33485
	 * @ticket 35213
	 */
	public function test_count_property_passed_to_filters_should_reflect_pre_deleted_term() {
		register_taxonomy( 'gctests_tax', 'post' );

		$terms = self::factory()->term->create_many(
			2,
			array(
				'taxonomy' => 'gctests_tax',
			)
		);

		$post_id = self::factory()->post->create();

		gc_set_object_terms( $post_id, array( $terms[0] ), 'gctests_tax' );

		add_action( 'delete_term', array( $this, 'catch_deleted_term' ), 10, 5 );

		gc_delete_term( $terms[0], 'gctests_tax' );
		$this->assertSame( 1, $this->deleted_term->count );
		$this->assertSame( $this->object_ids, array( (string) $post_id ) );

		gc_delete_term( $terms[1], 'gctests_tax' );
		$this->assertSame( 0, $this->deleted_term->count );
		$this->assertSame( $this->object_ids, array() );
	}

	public function catch_deleted_term( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
		$this->deleted_term = $deleted_term;
		$this->object_ids   = $object_ids;
	}
}
