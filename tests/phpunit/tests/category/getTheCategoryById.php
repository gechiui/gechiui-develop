<?php

/**
 * @group taxonomy
 * @covers ::get_the_category_by_ID
 */
class Tests_Category_GetTheCategoryById extends GC_UnitTestCase {
	public function test_success() {
		$c = self::factory()->category->create(
			array(
				'name' => 'Foo',
			)
		);

		$found = get_the_category_by_ID( $c );

		$this->assertSame( 'Foo', $found );
	}

	/**
	 * @ticket 42771
	 */
	public function test_should_return_link_for_term_from_another_taxonomy_on_primed_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Foo',
			)
		);

		$term = get_term( $t );

		$found = get_the_category_by_ID( $t );

		$this->assertSame( 'Foo', $found );
	}

	/**
	 * @ticket 42771
	 */
	public function test_should_return_link_for_term_from_another_taxonomy_on_empty_cache() {
		register_taxonomy( 'gctests_tax', 'post' );

		$t = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax',
				'name'     => 'Foo',
			)
		);

		clean_term_cache( $t );

		$found = get_the_category_by_ID( $t );

		$this->assertSame( 'Foo', $found );
	}
}
