<?php

/**
 * @group post
 */
class Tests_Post_gcPublishPost extends GC_UnitTestCase {

	/**
	 * Auto-draft post ID.
	 *
	 * @var int
	 */
	public static $auto_draft_id;

	/**
	 * Create shared fixtures.
	 *
	 * @param GC_UnitTest_Factory $factory Test suite factory.
	 */
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$auto_draft_id = $factory->post->create( array( 'post_status' => 'auto-draft' ) );
	}

	/**
	 * Ensure gc_publish_post does not add default category in error.
	 *
	 * @ticket 51292
	 */
	public function test_gc_publish_post_respects_current_categories() {
		$post_id     = self::$auto_draft_id;
		$category_id = $this->factory->term->create( array( 'taxonomy' => 'category' ) );
		gc_set_post_categories( $post_id, $category_id );
		gc_publish_post( $post_id );

		$post_categories = get_the_category( $post_id );
		$this->assertCount( 1, $post_categories );
		$this->assertSame(
			$category_id,
			$post_categories[0]->term_id,
			'gc_publish_post replaced set category.'
		);
	}

	/**
	 * Ensure gc_publish_post adds default category.
	 *
	 * @covers ::gc_publish_post
	 * @ticket 51292
	 */
	public function test_gc_publish_post_adds_default_category() {
		$post_id = self::$auto_draft_id;

		gc_publish_post( $post_id );

		$post_categories = get_the_category( $post_id );
		$this->assertCount( 1, $post_categories );
		$this->assertSame(
			(int) get_option( 'default_category' ),
			$post_categories[0]->term_id,
			'gc_publish_post failed to add default category.'
		);
	}

	/**
	 * Ensure gc_publish_post adds default category when tagged.
	 *
	 * @covers ::gc_publish_post
	 * @ticket 51292
	 */
	public function test_gc_publish_post_adds_default_category_when_tagged() {
		$post_id = self::$auto_draft_id;
		$tag_id  = $this->factory->term->create( array( 'taxonomy' => 'post_tag' ) );
		gc_set_post_tags( $post_id, array( $tag_id ) );
		gc_publish_post( $post_id );

		$post_categories = get_the_category( $post_id );
		$this->assertCount( 1, $post_categories );
		$this->assertSame(
			(int) get_option( 'default_category' ),
			$post_categories[0]->term_id,
			'gc_publish_post failed to add default category.'
		);
	}

	/**
	 * Ensure gc_publish_post does not add default term in error.
	 *
	 * @covers ::gc_publish_post
	 * @ticket 51292
	 */
	public function test_gc_publish_post_respects_current_terms() {
		// Create custom taxonomy to test with.
		register_taxonomy(
			'tax_51292',
			'post',
			array(
				'hierarchical' => true,
				'public'       => true,
				'default_term' => array(
					'name' => 'Default 51292',
					'slug' => 'default-51292',
				),
			)
		);

		$post_id = self::$auto_draft_id;
		$term_id = $this->factory->term->create( array( 'taxonomy' => 'tax_51292' ) );
		gc_set_object_terms( $post_id, array( $term_id ), 'tax_51292' );
		gc_publish_post( $post_id );

		$post_terms = get_the_terms( $post_id, 'tax_51292' );
		$this->assertCount( 1, $post_terms );
		$this->assertSame(
			$term_id,
			$post_terms[0]->term_id,
			'gc_publish_post replaced set term for custom taxonomy.'
		);
	}

	/**
	 * Ensure gc_publish_post adds default term.
	 *
	 * @covers ::gc_publish_post
	 * @ticket 51292
	 */
	public function test_gc_publish_post_adds_default_term() {
		// Create custom taxonomy to test with.
		register_taxonomy(
			'tax_51292',
			'post',
			array(
				'hierarchical' => true,
				'public'       => true,
				'default_term' => array(
					'name' => 'Default 51292',
					'slug' => 'default-51292',
				),
			)
		);

		$post_id = self::$auto_draft_id;

		gc_publish_post( $post_id );

		$post_terms = get_the_terms( $post_id, 'tax_51292' );
		$this->assertCount( 1, $post_terms );
		$this->assertSame(
			get_term_by( 'slug', 'default-51292', 'tax_51292' )->term_id,
			$post_terms[0]->term_id,
			'gc_publish_post failed to add default term for custom taxonomy.'
		);
	}
}
