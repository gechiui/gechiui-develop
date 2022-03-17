<?php

/**
 * @group taxonomy
 */
class Tests_Term_GcRemoveObjectTerms extends GC_UnitTestCase {
	/**
	 * @ticket 34338
	 */
	public function test_removal_should_delete_object_relationship_cache() {
		register_taxonomy( 'gctests_tax', 'post' );
		$p = self::factory()->post->create();
		$t = self::factory()->term->create( array( 'taxonomy' => 'gctests_tax' ) );

		gc_set_object_terms( $p, $t, 'gctests_tax' );

		// Pollute the cache.
		get_the_terms( $p, 'gctests_tax' );

		gc_remove_object_terms( $p, $t, 'gctests_tax' );

		$this->assertFalse( get_the_terms( $p, 'gctests_tax' ) );
	}
}
