<?php

/**
 * @group comment
 * @covers ::_gc_batch_update_comment_type
 */
class Tests_Batch_Update_Comment_Type extends GC_UnitTestCase {

	/**
	 * @ticket 49236
	 */
	public function test__gc_batch_update_comment_type() {
		global $gcdb;

		$comment_ids     = self::factory()->comment->create_many( 3 );
		$comment_id_list = implode( ',', $comment_ids );

		$gcdb->query(
			"UPDATE {$gcdb->comments}
			SET comment_type = ''
			WHERE comment_type = 'comment'
			AND comment_ID in ({$comment_id_list})" // phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		);

		clean_comment_cache( $comment_ids );

		foreach ( $comment_ids as $comment_id ) {
			$comment = get_comment( $comment_id );
			$this->assertEmpty( $comment->comment_type );
		}

		add_filter( 'gc_update_comment_type_batch_size', array( $this, 'filter_comment_type_batch_size' ) );
		add_filter( 'schedule_event', '__return_null' );

		_gc_batch_update_comment_type();

		remove_filter( 'gc_update_comment_type_batch_size', array( $this, 'filter_comment_type_batch_size' ) );
		remove_filter( 'schedule_event', '__return_null' );

		foreach ( $comment_ids as $comment_id ) {
			$updated_comment = get_comment( $comment_id );
			$this->assertSame( 'comment', $updated_comment->comment_type );
		}
	}

	public function filter_comment_type_batch_size() {
		return 3;
	}
}
