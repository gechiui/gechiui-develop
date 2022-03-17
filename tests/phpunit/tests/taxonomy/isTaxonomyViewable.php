<?php

/**
 * @group taxonomy
 */
class Tests_Taxonomy_IsTaxonomyViewable extends GC_UnitTestCase {
	public function set_up() {
		parent::set_up();

		register_post_type( 'gctests_pt' );
		register_taxonomy( 'gctests_tax_viewable', 'gctests_pt', array( 'publicly_queryable' => true ) );
		register_taxonomy( 'gctests_tax_non_viewable', 'gctests_pt', array( 'publicly_queryable' => false ) );
	}

	/**
	 * @ticket 44466
	 */
	public function test_is_taxonomy_viewable_for_querable_taxonomy() {
		$this->assertTrue( is_taxonomy_viewable( 'gctests_tax_viewable' ) );
	}

	/**
	 * @ticket 44466
	 */
	public function test_is_taxonomy_viewable_for_non_querable_taxonomy() {
		$this->assertFalse( is_taxonomy_viewable( 'gctests_tax_non_viewable' ) );
	}

	/**
	 * @ticket 44466
	 */
	public function test_is_taxonomy_viewable_for_non_existing_taxonomy() {
		$this->assertFalse( is_taxonomy_viewable( 'gctests_tax_non_existing' ) );
	}

	/**
	 * @ticket 44466
	 */
	public function test_is_taxonomy_viewable_with_object_given() {
		$taxonomy = get_taxonomy( 'gctests_tax_viewable' );

		$this->assertTrue( is_taxonomy_viewable( $taxonomy ) );
	}
}
