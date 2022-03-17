<?php

/**
 * @group  comment
 * @covers ::gc_update_comment_count_now
 */
class Tests_Update_Comment_Count_Now extends GC_UnitTestCase {
	public function _return_100() {
		return 100;
	}

	public function test_invalid_post_bails_early() {
		$this->assertFalse( gc_update_comment_count_now( 100 ) );
		$this->assertFalse( gc_update_comment_count_now( null ) );
		$this->assertFalse( gc_update_comment_count_now( 0 ) );
	}

	public function test_regular_post_updates_comment_count() {
		global $gcdb;

		$post_id = self::factory()->post->create();

		self::factory()->comment->create_post_comments( $post_id, 1 );
		$this->assertSame( '1', get_comments_number( $post_id ) );

		$num_queries = $gcdb->num_queries;
		$this->assertTrue( gc_update_comment_count_now( $post_id ) );
		$this->assertSame( $num_queries + 2, $gcdb->num_queries );

		$this->assertSame( '1', get_comments_number( $post_id ) );
	}

	public function test_using_filter_adjusts_comment_count_without_an_additional_database_query() {
		global $gcdb;

		add_filter( 'pre_gc_update_comment_count_now', array( $this, '_return_100' ) );

		$post_id = self::factory()->post->create();

		self::factory()->comment->create_post_comments( $post_id, 1 );
		$this->assertSame( '100', get_comments_number( $post_id ) );

		$num_queries = $gcdb->num_queries;
		$this->assertTrue( gc_update_comment_count_now( $post_id ) );
		// Only one query is made instead of two.
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$this->assertSame( '100', get_comments_number( $post_id ) );

		remove_filter( 'pre_gc_update_comment_count_now', array( $this, '_return_100' ) );
	}
}
