<?php

/**
 * @group comment
 */
class Tests_Term_GcComment extends GC_UnitTestCase {
	protected static $comment_id;

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		global $gcdb;

		// Ensure that there is a comment with ID 1.
		$comment_1 = GC_Comment::get_instance( 1 );
		if ( ! $comment_1 ) {
			$gcdb->insert(
				$gcdb->comments,
				array(
					'comment_ID' => 1,
				)
			);

			clean_comment_cache( 1 );
		}

		self::$comment_id = $factory->comment->create();
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_work_for_numeric_string() {
		$found = GC_Comment::get_instance( (string) self::$comment_id );

		$this->assertEquals( self::$comment_id, $found->comment_ID );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_fail_for_negative_number() {
		$found = GC_Comment::get_instance( -self::$comment_id );

		$this->assertFalse( $found );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_fail_for_non_numeric_string() {
		$found = GC_Comment::get_instance( 'abc' );

		$this->assertFalse( $found );
	}

	/**
	 * @ticket 37738
	 */
	public function test_get_instance_should_succeed_for_float_that_is_equal_to_post_id() {
		$found = GC_Comment::get_instance( 1.0 );

		$this->assertEquals( 1, $found->comment_ID );
	}
}
