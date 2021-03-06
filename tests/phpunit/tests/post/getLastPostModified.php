<?php

/**
 * @group post
 */
class Tests_Post_GetLastPostModified extends GC_UnitTestCase {

	/**
	 * @ticket 47777
	 */
	public function test_get_lastpostmodified() {
		global $gcdb;

		$post_post_date_first     = '2020-01-30 16:09:28';
		$post_post_modified_first = '2020-02-28 17:10:29';
		$post_post_date_last      = '2020-03-30 18:11:30';
		$post_post_modified_last  = '2020-04-30 19:12:31';

		$book_post_date_first     = '2019-05-30 20:09:28';
		$book_post_modified_first = '2019-06-30 21:10:29';
		$book_post_date_last      = '2019-07-30 22:11:30';
		$book_post_modified_last  = '2019-08-30 23:12:31';

		// Register book post type.
		register_post_type( 'book', array( 'has_archive' => true ) );

		// Create a simple post.
		$simple_post_id_first = self::factory()->post->create(
			array(
				'post_title' => 'Simple Post First',
				'post_type'  => 'post',
				'post_date'  => $post_post_date_first,
			)
		);

		$simple_post_id_last = self::factory()->post->create(
			array(
				'post_title' => 'Simple Post Last',
				'post_type'  => 'post',
				'post_date'  => $post_post_date_last,
			)
		);

		// Create custom type post.
		$book_cpt_id_first = self::factory()->post->create(
			array(
				'post_title' => 'Book CPT First',
				'post_type'  => 'book',
				'post_date'  => $book_post_date_first,
			)
		);

		$book_cpt_id_last = self::factory()->post->create(
			array(
				'post_title' => 'Book CPT Last',
				'post_type'  => 'book',
				'post_date'  => $book_post_date_last,
			)
		);

		// Update `post_modified` and `post_modified_gmt`.
		$gcdb->update(
			$gcdb->posts,
			array(
				'post_modified'     => $post_post_modified_first,
				'post_modified_gmt' => $post_post_modified_first,
			),
			array(
				'ID' => $simple_post_id_first,
			)
		);

		$gcdb->update(
			$gcdb->posts,
			array(
				'post_modified'     => $post_post_modified_last,
				'post_modified_gmt' => $post_post_modified_last,
			),
			array(
				'ID' => $simple_post_id_last,
			)
		);

		$gcdb->update(
			$gcdb->posts,
			array(
				'post_modified'     => $book_post_modified_first,
				'post_modified_gmt' => $book_post_modified_first,
			),
			array(
				'ID' => $book_cpt_id_first,
			)
		);

		$gcdb->update(
			$gcdb->posts,
			array(
				'post_modified'     => $book_post_modified_last,
				'post_modified_gmt' => $book_post_modified_last,
			),
			array(
				'ID' => $book_cpt_id_last,
			)
		);

		$this->assertSame( $post_post_modified_last, get_lastpostmodified( 'blog', 'post' ) );
		$this->assertSame( $book_post_modified_last, get_lastpostmodified( 'blog', 'book' ) );
	}
}
