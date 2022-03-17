<?php

/**
 * @group taxonomy
 */
class Tests_Term_GcTerm extends GC_UnitTestCase {
	protected static $term_id;

	public function set_up() {
		parent::set_up();
		register_taxonomy( 'gctests_tax', 'post' );
	}

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		global $gcdb;

		register_taxonomy( 'gctests_tax', 'post' );

		// Ensure that there is a term with ID 1.
		if ( ! get_term( 1 ) ) {
			$gcdb->insert(
				$gcdb->terms,
				array(
					'term_id' => 1,
				)
			);

			$gcdb->insert(
				$gcdb->term_taxonomy,
				array(
					'term_id'  => 1,
					'taxonomy' => 'gctests_tax',
				)
			);

			clean_term_cache( 1, 'gctests_tax' );
		}

		self::$term_id = $factory->term->create( array( 'taxonomy' => 'gctests_tax' ) );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_work_for_numeric_string() {
		$found = GC_Term::get_instance( (string) self::$term_id );

		$this->assertSame( self::$term_id, $found->term_id );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_fail_for_negative_number() {
		$found = GC_Term::get_instance( -self::$term_id );

		$this->assertFalse( $found );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_fail_for_non_numeric_string() {
		$found = GC_Term::get_instance( 'abc' );

		$this->assertFalse( $found );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_succeed_for_float_that_is_equal_to_post_id() {
		$found = GC_Term::get_instance( 1.0 );

		$this->assertSame( 1, $found->term_id );
	}

	/**
	 * @ticket 40671
	 */
	public function test_get_instance_should_respect_taxonomy_when_term_id_is_found_in_cache() {
		global $gcdb;

		register_taxonomy( 'gctests_tax2', 'post' );

		// Ensure that cache is primed.
		GC_Term::get_instance( self::$term_id, 'gctests_tax' );

		$found = GC_Term::get_instance( self::$term_id, 'gctests_tax2' );
		$this->assertFalse( $found );
	}
}
