<?php

class Tests_GC_Count_Comments extends GC_UnitTestCase {

	public function test_gc_count_comments() {
		$count = gc_count_comments();

		$this->assertSame( 0, $count->approved );
		$this->assertSame( 0, $count->moderated );
		$this->assertSame( 0, $count->spam );
		$this->assertSame( 0, $count->trash );
		$this->assertSame( 0, $count->{'post-trashed'} );
		$this->assertSame( 0, $count->total_comments );
		$this->assertSame( 0, $count->all );
	}

	public function test_gc_count_comments_approved() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 1,
			)
		);

		$count = gc_count_comments();

		$this->assertSame( 1, $count->approved );
		$this->assertSame( 0, $count->moderated );
		$this->assertSame( 0, $count->spam );
		$this->assertSame( 0, $count->trash );
		$this->assertSame( 0, $count->{'post-trashed'} );
		$this->assertSame( 1, $count->total_comments );
		$this->assertSame( 1, $count->all );
	}

	public function test_gc_count_comments_awaiting() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 0,
			)
		);

		$count = gc_count_comments();

		$this->assertSame( 0, $count->approved );
		$this->assertSame( 1, $count->moderated );
		$this->assertSame( 0, $count->spam );
		$this->assertSame( 0, $count->trash );
		$this->assertSame( 0, $count->{'post-trashed'} );
		$this->assertSame( 1, $count->total_comments );
		$this->assertSame( 1, $count->all );
	}

	public function test_gc_count_comments_spam() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'spam',
			)
		);

		$count = gc_count_comments();

		$this->assertSame( 0, $count->approved );
		$this->assertSame( 0, $count->moderated );
		$this->assertSame( 1, $count->spam );
		$this->assertSame( 0, $count->trash );
		$this->assertSame( 0, $count->{'post-trashed'} );
		$this->assertSame( 1, $count->total_comments );
		$this->assertSame( 0, $count->all );
	}

	public function test_gc_count_comments_trash() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'trash',
			)
		);

		$count = gc_count_comments();

		$this->assertSame( 0, $count->approved );
		$this->assertSame( 0, $count->moderated );
		$this->assertSame( 0, $count->spam );
		$this->assertSame( 1, $count->trash );
		$this->assertSame( 0, $count->{'post-trashed'} );
		$this->assertSame( 0, $count->total_comments );
		$this->assertSame( 0, $count->all );
	}

	public function test_gc_count_comments_post_trashed() {
		self::factory()->comment->create(
			array(
				'comment_approved' => 'post-trashed',
			)
		);

		$count = gc_count_comments();

		$this->assertSame( 0, $count->approved );
		$this->assertSame( 0, $count->moderated );
		$this->assertSame( 0, $count->spam );
		$this->assertSame( 0, $count->trash );
		$this->assertSame( 1, $count->{'post-trashed'} );
		$this->assertSame( 0, $count->total_comments );
		$this->assertSame( 0, $count->all );
	}

	public function test_gc_count_comments_cache() {
		$post_id    = self::factory()->post->create(
			array(
				'post_status' => 'publish',
			)
		);
		$comment_id = self::factory()->comment->create(
			array(
				'comment_approved' => '1',
				'comment_post_ID'  => $post_id,
			)
		);

		$count1 = gc_count_comments( $post_id );

		$this->assertSame( 1, $count1->approved );
		$this->assertSame( 0, $count1->moderated );
		$this->assertSame( 0, $count1->spam );
		$this->assertSame( 0, $count1->trash );
		$this->assertSame( 0, $count1->{'post-trashed'} );
		$this->assertSame( 1, $count1->total_comments );
		$this->assertSame( 1, $count1->all );

		$all_count1 = gc_count_comments();

		$this->assertSame( 1, $all_count1->approved );
		$this->assertSame( 0, $all_count1->moderated );
		$this->assertSame( 0, $all_count1->spam );
		$this->assertSame( 0, $all_count1->trash );
		$this->assertSame( 0, $all_count1->{'post-trashed'} );
		$this->assertSame( 1, $all_count1->total_comments );
		$this->assertSame( 1, $all_count1->all );

		gc_spam_comment( $comment_id );

		$count2 = gc_count_comments( $post_id );

		$this->assertSame( 0, $count2->approved );
		$this->assertSame( 0, $count2->moderated );
		$this->assertSame( 1, $count2->spam );
		$this->assertSame( 0, $count2->trash );
		$this->assertSame( 0, $count2->{'post-trashed'} );
		$this->assertSame( 1, $count2->total_comments );
		$this->assertSame( 0, $count2->all );

		$all_count2 = gc_count_comments();

		$this->assertSame( 0, $all_count2->approved );
		$this->assertSame( 0, $all_count2->moderated );
		$this->assertSame( 1, $all_count2->spam );
		$this->assertSame( 0, $all_count2->trash );
		$this->assertSame( 0, $all_count2->{'post-trashed'} );
		$this->assertSame( 1, $all_count2->total_comments );
		$this->assertSame( 0, $all_count2->all );

		gc_trash_comment( $comment_id );

		$count3 = gc_count_comments( $post_id );

		$this->assertSame( 0, $count3->approved );
		$this->assertSame( 0, $count3->moderated );
		$this->assertSame( 0, $count3->spam );
		$this->assertSame( 1, $count3->trash );
		$this->assertSame( 0, $count3->{'post-trashed'} );
		$this->assertSame( 0, $count3->total_comments );
		$this->assertSame( 0, $count3->all );

		$all_count3 = gc_count_comments();

		$this->assertSame( 0, $all_count3->approved );
		$this->assertSame( 0, $all_count3->moderated );
		$this->assertSame( 0, $all_count3->spam );
		$this->assertSame( 1, $all_count3->trash );
		$this->assertSame( 0, $all_count3->{'post-trashed'} );
		$this->assertSame( 0, $all_count3->total_comments );
		$this->assertSame( 0, $all_count3->all );

		gc_untrash_comment( $comment_id );

		$count4 = gc_count_comments( $post_id );

		$this->assertSame( 0, $count4->approved );
		$this->assertSame( 0, $count4->moderated );
		$this->assertSame( 1, $count4->spam );
		$this->assertSame( 0, $count4->trash );
		$this->assertSame( 0, $count4->{'post-trashed'} );
		$this->assertSame( 1, $count4->total_comments );
		$this->assertSame( 0, $count4->all );

		$all_count4 = gc_count_comments();

		$this->assertSame( 0, $all_count4->approved );
		$this->assertSame( 0, $all_count4->moderated );
		$this->assertSame( 1, $all_count4->spam );
		$this->assertSame( 0, $all_count4->trash );
		$this->assertSame( 0, $all_count4->{'post-trashed'} );
		$this->assertSame( 1, $all_count4->total_comments );
		$this->assertSame( 0, $all_count4->all );
	}
}