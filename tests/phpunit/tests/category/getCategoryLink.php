<?php

/**
 * @group taxonomy
 * @covers ::get_category_link
 */
class Tests_Category_GetCategoryLink extends GC_UnitTestCase {
	public function test_success() {
		$c = self::factory()->category->create();

		$found    = get_category_link( $c );
		$expected = home_url( '?cat=' . $c );

		$this->assertSame( $expected, $found );
	}

	/**
	 * @ticket 42771
	 */
	public function test_should_return_link_for_term_from_another_taxonomy_on_primed_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'test-term',
			)
		);

		$term = get_term( $t );

		$found    = get_category_link( $t );
		$expected = home_url( '?gctests_tax=test-term' );

		$this->assertSame( $expected, $found );
	}

	/**
	 * @ticket 42771
	 */
	public function test_should_return_link_for_term_from_another_taxonomy_on_empty_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'slug'     => 'test-term',
			)
		);

		clean_term_cache( $t );

		$found    = get_category_link( $t );
		$expected = home_url( '?gctests_tax=test-term' );

		$this->assertSame( $expected, $found );
	}
}
