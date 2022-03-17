<?php

/**
 * @group taxonomy
 */
class Tests_GC_Taxonomy extends GC_UnitTestCase {
	public function test_instances() {
		global $gc_taxonomies;

		foreach ( $gc_taxonomies as $taxonomy ) {
			$this->assertInstanceOf( 'GC_Taxonomy', $taxonomy );
		}
	}

	public function test_does_not_add_query_var_if_not_public() {
		$this->set_permalink_structure( '/%postname%' );

		/* @var GC $gc */
		global $gc;

		$taxonomy        = 'taxonomy1';
		$taxonomy_object = new GC_Taxonomy( $taxonomy, 'post' );

		$taxonomy_object->add_rewrite_rules();
		$this->assertNotContains( 'foobar', $gc->public_query_vars );
	}

	public function test_adds_query_var_if_public() {
		$this->set_permalink_structure( '/%postname%' );

		/* @var GC $gc */
		global $gc;

		$taxonomy        = 'taxonomy2';
		$taxonomy_object = new GC_Taxonomy(
			$taxonomy,
			'post',
			array(
				'public'    => true,
				'rewrite'   => false,
				'query_var' => 'foobar',
			)
		);

		$taxonomy_object->add_rewrite_rules();
		$in_array = in_array( 'foobar', $gc->public_query_vars, true );

		$taxonomy_object->remove_rewrite_rules();
		$in_array_after = in_array( 'foobar', $gc->public_query_vars, true );

		$this->assertTrue( $in_array );
		$this->assertFalse( $in_array_after );
	}

	public function test_adds_rewrite_rules() {
		$this->set_permalink_structure( '/%postname%' );

		/* @var GC_Rewrite $gc_rewrite */
		global $gc_rewrite;

		$taxonomy        = 'taxonomy3';
		$taxonomy_object = new GC_Taxonomy(
			$taxonomy,
			'post',
			array(
				'public'  => true,
				'rewrite' => true,
			)
		);

		$taxonomy_object->add_rewrite_rules();
		$rewrite_tags = $gc_rewrite->rewritecode;

		$taxonomy_object->remove_rewrite_rules();
		$rewrite_tags_after = $gc_rewrite->rewritecode;

		$this->assertNotFalse( array_search( "%$taxonomy%", $rewrite_tags, true ) );
		$this->assertFalse( array_search( "%$taxonomy%", $rewrite_tags_after, true ) );
	}

	public function test_adds_ajax_callback() {
		$taxonomy        = 'taxonomy4';
		$taxonomy_object = new GC_Taxonomy(
			$taxonomy,
			'post',
			array(
				'public'  => true,
				'rewrite' => true,
			)
		);

		$taxonomy_object->add_hooks();
		$has_action = has_action( "gc_ajax_add-$taxonomy", '_gc_ajax_add_hierarchical_term' );

		$taxonomy_object->remove_hooks();
		$has_action_after = has_action( "gc_ajax_add-$taxonomy", '_gc_ajax_add_hierarchical_term' );

		$this->assertSame( 10, $has_action );
		$this->assertFalse( $has_action_after );

	}
}
