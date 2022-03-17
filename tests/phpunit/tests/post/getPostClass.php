<?php

/**
 * @group post
 * @covers ::get_post_class
 */
class Tests_Post_GetPostClass extends GC_UnitTestCase {
	protected $post_id;

	public function set_up() {
		parent::set_up();
		$this->post_id = self::factory()->post->create();
	}

	public function test_with_tags() {
		gc_set_post_terms( $this->post_id, array( 'foo', 'bar' ), 'post_tag' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( 'tag-foo', $found );
		$this->assertContains( 'tag-bar', $found );
	}

	public function test_with_categories() {
		$cats = self::factory()->category->create_many( 2 );
		gc_set_post_terms( $this->post_id, $cats, 'category' );

		$cat0 = get_term( $cats[0], 'category' );
		$cat1 = get_term( $cats[1], 'category' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( 'category-' . $cat0->slug, $found );
		$this->assertContains( 'category-' . $cat1->slug, $found );
	}

	public function test_with_custom_taxonomy() {
		register_taxonomy( 'gctests_tax', 'post' );
		gc_set_post_terms( $this->post_id, array( 'foo', 'bar' ), 'gctests_tax' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( 'gctests_tax-foo', $found );
		$this->assertContains( 'gctests_tax-bar', $found );
	}

	/**
	 * @ticket 22271
	 */
	public function test_with_custom_classes_and_no_post() {
		$this->assertSame( array(), get_post_class( '', null ) );
		$this->assertSame( array( 'foo' ), get_post_class( 'foo', null ) );
		$this->assertSame( array( 'foo', 'bar' ), get_post_class( array( 'foo', 'bar' ), null ) );
	}

	/**
	 * @ticket 30883
	 */
	public function test_with_utf8_category_slugs() {
		$cat_id1 = self::factory()->category->create( array( 'name' => 'Первая рубрика' ) );
		$cat_id2 = self::factory()->category->create( array( 'name' => 'Вторая рубрика' ) );
		$cat_id3 = self::factory()->category->create( array( 'name' => '25кадр' ) );
		gc_set_post_terms( $this->post_id, array( $cat_id1, $cat_id2, $cat_id3 ), 'category' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( "category-$cat_id1", $found );
		$this->assertContains( "category-$cat_id2", $found );
		$this->assertContains( "category-$cat_id3", $found );
	}

	/**
	 * @ticket 30883
	 */
	public function test_with_utf8_tag_slugs() {
		$tag_id1 = self::factory()->tag->create( array( 'name' => 'Первая метка' ) );
		$tag_id2 = self::factory()->tag->create( array( 'name' => 'Вторая метка' ) );
		$tag_id3 = self::factory()->tag->create( array( 'name' => '25кадр' ) );
		gc_set_post_terms( $this->post_id, array( $tag_id1, $tag_id2, $tag_id3 ), 'post_tag' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( "tag-$tag_id1", $found );
		$this->assertContains( "tag-$tag_id2", $found );
		$this->assertContains( "tag-$tag_id3", $found );
	}

	/**
	 * @ticket 30883
	 */
	public function test_with_utf8_term_slugs() {
		register_taxonomy( 'gctests_tax', 'post' );
		$term_id1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Первая метка',
			)
		);
		$term_id2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Вторая метка',
			)
		);
		$term_id3 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => '25кадр',
			)
		);
		gc_set_post_terms( $this->post_id, array( $term_id1, $term_id2, $term_id3 ), 'gctests_tax' );

		$found = get_post_class( '', $this->post_id );

		$this->assertContains( "gctests_tax-$term_id1", $found );
		$this->assertContains( "gctests_tax-$term_id2", $found );
		$this->assertContains( "gctests_tax-$term_id3", $found );
	}

	/**
	 * @group cache
	 */
	public function test_taxonomy_classes_hit_cache() {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );
		gc_set_post_terms( $this->post_id, array( 'foo', 'bar' ), 'gctests_tax' );
		gc_set_post_terms( $this->post_id, array( 'footag', 'bartag' ), 'post_tag' );

		// Prime cache, including meta cache, which is used by get_post_class().
		update_object_term_cache( $this->post_id, 'post' );
		update_meta_cache( 'post', $this->post_id );

		$num_queries = $gcdb->num_queries;

		$found = get_post_class( '', $this->post_id );

		$this->assertSame( $num_queries, $gcdb->num_queries );
	}
}
