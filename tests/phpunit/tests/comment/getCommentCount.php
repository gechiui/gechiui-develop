<?php

class Tests_Get_Comment_Count extends GC_UnitTestCase {

	public function test_get_comment_count() {
		$count = get_comment_count();

		$this->assertSame( 0, $count['approved'] );
		$this->assertSame( 0, $count['awaiting_moderation'] );
		$this->assertSame( 0, $count['spam'] );
		$this->assertSame( 0, $count['trash'] );
		$this->assertSame( 0, $count['post-trashed'] );
		$this->assertSame( 0, $count['total_comments'] );
	}

	public function test_get_comment_count_approved() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 1,
			)
		);

		$count = get_comment_count();

		$this->assertSame( 1, $count['approved'] );
		$this->assertSame( 0, $count['awaiting_moderation'] );
		$this->assertSame( 0, $count['spam'] );
		$this->assertSame( 0, $count['trash'] );
		$this->assertSame( 0, $count['post-trashed'] );
		$this->assertSame( 1, $count['total_comments'] );
	}

	public function test_get_comment_count_awaiting() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 0,
			)
		);

		$count = get_comment_count();

		$this->assertSame( 0, $count['approved'] );
		$this->assertSame( 1, $count['awaiting_moderation'] );
		$this->assertSame( 0, $count['spam'] );
		$this->assertSame( 0, $count['trash'] );
		$this->assertSame( 0, $count['post-trashed'] );
		$this->assertSame( 1, $count['total_comments'] );
	}

	public function test_get_comment_count_spam() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'spam',
			)
		);

		$count = get_comment_count();

		$this->assertSame( 0, $count['approved'] );
		$this->assertSame( 0, $count['awaiting_moderation'] );
		$this->assertSame( 1, $count['spam'] );
		$this->assertSame( 0, $count['trash'] );
		$this->assertSame( 0, $count['post-trashed'] );
		$this->assertSame( 1, $count['total_comments'] );
	}

	public function test_get_comment_count_trash() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'trash',
			)
		);

		$count = get_comment_count();

		$this->assertSame( 0, $count['approved'] );
		$this->assertSame( 0, $count['awaiting_moderation'] );
		$this->assertSame( 0, $count['spam'] );
		$this->assertSame( 1, $count['trash'] );
		$this->assertSame( 0, $count['post-trashed'] );
		$this->assertSame( 0, $count['total_comments'] );
	}

	public function test_get_comment_count_post_trashed() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'post-trashed',
			)
		);

		$count = get_comment_count();

		$this->assertSame( 0, $count['approved'] );
		$this->assertSame( 0, $count['awaiting_moderation'] );
		$this->assertSame( 0, $count['spam'] );
		$this->assertSame( 0, $count['trash'] );
		$this->assertSame( 1, $count['post-trashed'] );
		$this->assertSame( 0, $count['total_comments'] );
	}
}
