<?php

/**
 * @group comment
 */
class Tests_Comment extends GC_UnitTestCase {
	protected static $user_id;
	protected static $post_id;
	protected static $notify_message = '';

	public function set_up() {
		parent::set_up();
		reset_phpmailer_instance();
	}

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$user_id = $factory->user->create(
			array(
				'role'       => 'author',
				'user_login' => 'test_gc_user_get',
				'user_pass'  => 'password',
				'user_email' => 'test@test.com',
			)
		);

		self::$post_id = $factory->post->create(
			array(
				'post_author' => self::$user_id,
			)
		);
	}

	public function test_gc_update_comment() {
		$post  = self::factory()->post->create_and_get(
			array(
				'post_title' => 'some-post',
				'post_type'  => 'post',
			)
		);
		$post2 = self::factory()->post->create_and_get(
			array(
				'post_title' => 'some-post-2',
				'post_type'  => 'post',
			)
		);

		$comments = self::factory()->comment->create_post_comments( $post->ID, 5 );

		$result = gc_update_comment(
			array(
				'comment_ID'     => $comments[0],
				'comment_parent' => $comments[1],
			)
		);
		$this->assertSame( 1, $result );

		$comment = get_comment( $comments[0] );
		$this->assertEquals( $comments[1], $comment->comment_parent );

		$result = gc_update_comment(
			array(
				'comment_ID'     => $comments[0],
				'comment_parent' => $comments[1],
			)
		);
		$this->assertSame( 0, $result );

		$result = gc_update_comment(
			array(
				'comment_ID'      => $comments[0],
				'comment_post_ID' => $post2->ID,
			)
		);

		$comment = get_comment( $comments[0] );
		$this->assertEquals( $post2->ID, $comment->comment_post_ID );
	}

	/**
	 * @ticket 30627
	 */
	public function test_gc_update_comment_updates_comment_type() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		gc_update_comment(
			array(
				'comment_ID'   => $comment_id,
				'comment_type' => 'pingback',
			)
		);

		$comment = get_comment( $comment_id );
		$this->assertSame( 'pingback', $comment->comment_type );
	}

	/**
	 * @ticket 36784
	 */
	public function test_gc_update_comment_updates_comment_meta() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		gc_update_comment(
			array(
				'comment_ID'   => $comment_id,
				'comment_meta' => array(
					'food'  => 'taco',
					'sauce' => 'fire',
				),
			)
		);

		$this->assertSame( 'fire', get_comment_meta( $comment_id, 'sauce', true ) );
	}

	/**
	 * @ticket 30307
	 */
	public function test_gc_update_comment_updates_user_id() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		gc_update_comment(
			array(
				'comment_ID' => $comment_id,
				'user_id'    => 1,
			)
		);

		$comment = get_comment( $comment_id );
		$this->assertEquals( 1, $comment->user_id );
	}

	/**
	 * @ticket 34954
	 */
	public function test_gc_update_comment_with_no_post_id() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => 0 ) );

		$updated_comment_text = 'I should be able to update a comment with a Post ID of zero';

		$update = gc_update_comment(
			array(
				'comment_ID'      => $comment_id,
				'comment_content' => $updated_comment_text,
				'comment_post_ID' => 0,
			)
		);
		$this->assertSame( 1, $update );

		$comment = get_comment( $comment_id );
		$this->assertSame( $updated_comment_text, $comment->comment_content );
	}

	/**
	 * @ticket 39732
	 */
	public function test_gc_update_comment_returns_false_for_invalid_comment_or_post_id() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		$update = gc_update_comment(
			array(
				'comment_ID'      => -1,
				'comment_post_ID' => self::$post_id,
			)
		);
		$this->assertFalse( $update );

		$update = gc_update_comment(
			array(
				'comment_ID'      => $comment_id,
				'comment_post_ID' => -1,
			)
		);
		$this->assertFalse( $update );
	}

	/**
	 * @ticket 39732
	 */
	public function test_gc_update_comment_is_gc_error() {
		$comment_id = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		add_filter( 'gc_update_comment_data', array( $this, 'gc_update_comment_data_filter' ), 10, 3 );

		$result = gc_update_comment(
			array(
				'comment_ID'   => $comment_id,
				'comment_type' => 'pingback',
			),
			true
		);

		remove_filter( 'gc_update_comment_data', array( $this, 'gc_update_comment_data_filter' ), 10, 3 );

		$this->assertWPError( $result );
	}

	/**
	 * Blocks comments from being updated by returning GC_Error.
	 */
	public function gc_update_comment_data_filter( $data, $comment, $commentarr ) {
		return new GC_Error( 'comment_wrong', 'gc_update_comment_data filter fails for this comment.', 500 );
	}

	public function test_get_approved_comments() {
		$ca1 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
			)
		);
		$ca2 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
			)
		);
		$ca3 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '0',
			)
		);
		$c2  = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_type'     => 'pingback',
			)
		);
		$c3  = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_type'     => 'trackback',
			)
		);
		$c4  = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_type'     => 'mario',
			)
		);
		$c5  = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_type'     => 'luigi',
			)
		);

		$found = get_approved_comments( self::$post_id );

		// All comment types will be returned.
		$this->assertEquals( array( $ca1, $ca2, $c2, $c3, $c4, $c5 ), gc_list_pluck( $found, 'comment_ID' ) );
	}

	/**
	 * @ticket 30412
	 */
	public function test_get_approved_comments_with_post_id_0_should_return_empty_array() {
		$ca1 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
			)
		);

		$found = get_approved_comments( 0 );

		$this->assertSame( array(), $found );
	}

	/**
	 * @ticket 14279
	 */
	public function test_gc_new_comment_respects_dates() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
			'comment_date'         => '2011-01-01 10:00:00',
			'comment_date_gmt'     => '2011-01-01 10:00:00',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( $data['comment_date'], $comment->comment_date );
		$this->assertSame( $data['comment_date_gmt'], $comment->comment_date_gmt );
	}

	/**
	 * @ticket 14601
	 */
	public function test_gc_new_comment_respects_author_ip() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '192.168.1.1',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( $data['comment_author_IP'], $comment->comment_author_IP );
	}

	/**
	 * @ticket 14601
	 */
	public function test_gc_new_comment_respects_author_ip_empty_string() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( $data['comment_author_IP'], $comment->comment_author_IP );
	}

	/**
	 * @ticket 14601
	 */
	public function test_gc_new_comment_respects_comment_agent() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_agent'        => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( $data['comment_agent'], $comment->comment_agent );
	}

	/**
	 * @ticket 14601
	 */
	public function test_gc_new_comment_should_trim_provided_comment_agent_to_254_chars() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_agent'        => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16 Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en; rv:1.8.1.4pre) Gecko/20070511 Camino/1.6pre',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16 Mozilla/5.0 (Macintosh; U; PPC Mac OS ', $comment->comment_agent );
	}

	/**
	 * @ticket 14601
	 */
	public function test_gc_new_comment_respects_comment_agent_empty_string() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_agent'        => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( $data['comment_agent'], $comment->comment_agent );
	}


	public function test_comment_field_lengths() {
		$data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => str_repeat( 'A', 65536 ),
			'comment_date'         => '2011-01-01 10:00:00',
			'comment_date_gmt'     => '2011-01-01 10:00:00',
		);

		$id = gc_new_comment( $data );

		$comment = get_comment( $id );

		$this->assertSame( strlen( $comment->comment_content ), 65535 );
	}

	/**
	 * @ticket 32566
	 */
	public function test_gc_notify_moderator_should_not_throw_notice_when_post_author_is_0() {
		$p = self::factory()->post->create(
			array(
				'post_author' => 0,
			)
		);

		$c = self::factory()->comment->create(
			array(
				'comment_post_ID' => $p,
			)
		);

		$this->assertTrue( gc_notify_moderator( $c ) );
	}

	public function test_gc_new_comment_notify_postauthor_should_send_email_when_comment_is_approved() {
		$c = self::factory()->comment->create(
			array(
				'comment_post_ID' => self::$post_id,
			)
		);

		$sent = gc_new_comment_notify_postauthor( $c );
		$this->assertTrue( $sent );
	}

	public function test_gc_new_comment_notify_postauthor_should_not_send_email_when_comment_is_unapproved() {
		$c = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '0',
			)
		);

		$sent = gc_new_comment_notify_postauthor( $c );
		$this->assertFalse( $sent );
	}

	/**
	 * @ticket 33587
	 */
	public function test_gc_new_comment_notify_postauthor_should_not_send_email_when_comment_has_been_marked_as_spam() {
		$c = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => 'spam',
			)
		);

		$sent = gc_new_comment_notify_postauthor( $c );
		$this->assertFalse( $sent );
	}

	/**
	 * @ticket 35006
	 */
	public function test_gc_new_comment_notify_postauthor_should_not_send_email_when_comment_has_been_trashed() {
		$c = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => 'trash',
			)
		);

		$sent = gc_new_comment_notify_postauthor( $c );
		$this->assertFalse( $sent );
	}

	/**
	 * @ticket 43805
	 */
	public function test_gc_new_comment_notify_postauthor_content_should_include_link_to_parent() {
		$c1 = self::factory()->comment->create(
			array(
				'comment_post_ID' => self::$post_id,
			)
		);

		$c2 = self::factory()->comment->create(
			array(
				'comment_post_ID' => self::$post_id,
				'comment_parent'  => $c1,
			)
		);

		add_filter( 'comment_notification_text', array( $this, 'save_comment_notification_text' ) );
		gc_new_comment_notify_postauthor( $c2 );
		remove_filter( 'comment_notification_text', array( $this, 'save_comment_notification_text' ) );

		$this->assertStringContainsString( admin_url( "comment.php?action=editcomment&c={$c1}" ), self::$notify_message );
	}

	/**
	 * @ticket 43805
	 */
	public function test_gc_new_comment_notify_moderator_content_should_include_link_to_parent() {
		$c1 = self::factory()->comment->create(
			array(
				'comment_post_ID' => self::$post_id,
			)
		);

		$c2 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_parent'   => $c1,
				'comment_approved' => '0',
			)
		);

		add_filter( 'comment_moderation_text', array( $this, 'save_comment_notification_text' ) );
		gc_new_comment_notify_moderator( $c2 );
		remove_filter( 'comment_moderation_text', array( $this, 'save_comment_notification_text' ) );

		$this->assertStringContainsString( admin_url( "comment.php?action=editcomment&c={$c1}" ), self::$notify_message );
	}

	/**
	 * Callback for the `comment_notification_text` & `comment_moderation_text` filters.
	 *
	 * @param string $notify_message The comment notification or moderation email text.
	 * @return string
	 */
	public function save_comment_notification_text( $notify_message = '' ) {
		self::$notify_message = $notify_message;
		return $notify_message;
	}

	/**
	 * @ticket 12431
	 */
	public function test_gc_new_comment_with_meta() {
		$c = self::factory()->comment->create(
			array(
				'comment_approved' => '1',
				'comment_meta'     => array(
					'food'  => 'taco',
					'sauce' => 'fire',
				),
			)
		);

		$this->assertSame( 'fire', get_comment_meta( $c, 'sauce', true ) );
	}

	/**
	 * @ticket 8071
	 */
	public function test_gc_comment_get_children_should_fill_children() {
		$c1 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
			)
		);

		$c2 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_parent'   => $c1,
			)
		);

		$c3 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_parent'   => $c2,
			)
		);

		$c4 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_parent'   => $c1,
			)
		);

		$c5 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
			)
		);

		$c6 = self::factory()->comment->create(
			array(
				'comment_post_ID'  => self::$post_id,
				'comment_approved' => '1',
				'comment_parent'   => $c5,
			)
		);

		$comment  = get_comment( $c1 );
		$children = $comment->get_children();

		// Direct descendants of $c1.
		$this->assertEqualSets( array( $c2, $c4 ), array_values( gc_list_pluck( $children, 'comment_ID' ) ) );

		// Direct descendants of $c2.
		$this->assertEqualSets( array( $c3 ), array_values( gc_list_pluck( $children[ $c2 ]->get_children(), 'comment_ID' ) ) );
	}

	/**
	 * @ticket 27571
	 */
	public function test_post_properties_should_be_lazyloaded() {
		$c = self::factory()->comment->create( array( 'comment_post_ID' => self::$post_id ) );

		$post    = get_post( self::$post_id );
		$comment = get_comment( $c );

		$post_fields = array( 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'comment_status', 'ping_status', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order', 'post_type', 'post_mime_type', 'comment_count' );

		foreach ( $post_fields as $pf ) {
			$this->assertTrue( isset( $comment->$pf ), $pf );
			$this->assertSame( $post->$pf, $comment->$pf, $pf );
		}
	}


	/**
	 * Helper function to set up comment for 761 tests.
	 *
	 * @since 4.4.0
	 * @access public
	 */
	public function setup_notify_comment() {
		/**
		 * Prevent flood alert from firing.
		 */
		add_filter( 'comment_flood_filter', '__return_false' );

		/**
		 * Set up a comment for testing.
		 */
		$post = $this->factory->post->create(
			array(
				'post_author' => self::$user_id,
			)
		);

		$comment = $this->factory->comment->create(
			array(
				'comment_post_ID' => $post,
			)
		);

		return array(
			'post'    => $post,
			'comment' => $comment,
		);
	}

	/**
	 * @ticket 761
	 */
	public function test_gc_notify_moderator_filter_moderation_notify_option_true_filter_false() {
		$comment_data = $this->setup_notify_comment();

		/**
		 * Test with moderator notification setting on, filter set to off.
		 * Should not send a notification.
		 */
		update_option( 'moderation_notify', 1 );
		add_filter( 'notify_moderator', '__return_false' );

		$notification_sent = $this->try_sending_moderator_notification( $comment_data['comment'], $comment_data['post'] );

		$this->assertFalse( $notification_sent, 'Moderator notification setting on, filter set to off' );

		remove_filter( 'notify_moderator', '__return_false' );
		remove_filter( 'comment_flood_filter', '__return_false' );
	}

	/**
	 * @ticket 761
	 */
	public function test_gc_notify_moderator_filter_moderation_notify_option_false_filter_true() {
		$comment_data = $this->setup_notify_comment();

		/**
		 * Test with moderator notification setting off, filter set to on.
		 * Should send a notification.
		 */
		update_option( 'moderation_notify', 0 );
		add_filter( 'notify_moderator', '__return_true' );

		$notification_sent = $this->try_sending_moderator_notification( $comment_data['comment'], $comment_data['post'] );

		$this->assertTrue( $notification_sent, 'Moderator notification setting off, filter set to on' );

		remove_filter( 'notify_moderator', '__return_true' );
		remove_filter( 'comment_flood_filter', '__return_false' );
	}

	/**
	 * @ticket 761
	 */
	public function test_gc_notify_post_author_filter_comments_notify_option_true_filter_false() {

		$comment_data = $this->setup_notify_comment();

		/**
		 * Test with author notification setting on, filter set to off.
		 * Should not send a notification.
		 */
		update_option( 'comments_notify', 1 );
		add_filter( 'notify_post_author', '__return_false' );

		$notification_sent = $this->try_sending_author_notification( $comment_data['comment'], $comment_data['post'] );

		$this->assertFalse( $notification_sent, 'Test with author notification setting on, filter set to off' );

		remove_filter( 'notify_post_author', '__return_false' );
		remove_filter( 'comment_flood_filter', '__return_false' );
	}

	/**
	 * @ticket 761
	 */
	public function test_gc_notify_post_author_filter_comments_notify_option_false_filter_true() {
		$comment_data = $this->setup_notify_comment();

		/**
		 * Test with author notification setting off, filter set to on.
		 * Should send a notification.
		 */
		update_option( 'comments_notify', 0 );
		add_filter( 'notify_post_author', '__return_true' );

		$notification_sent = $this->try_sending_author_notification( $comment_data['comment'], $comment_data['post'] );

		$this->assertTrue( $notification_sent, 'Test with author notification setting off, filter set to on' );

		remove_filter( 'notify_post_author', '__return_true' );
		remove_filter( 'comment_flood_filter', '__return_false' );
	}

	/**
	 * Helper function to test moderator notifications.
	 *
	 * @since 4.4.0
	 * @access public
	 */
	public function try_sending_moderator_notification( $comment, $post ) {

		// Don't approve comments, triggering notifications.
		add_filter( 'pre_comment_approved', '__return_false' );

		// Moderators are notified when a new comment is added.
		$data = array(
			'comment_post_ID'      => $post,
			'comment_author'       => 'Comment Author',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);
		gc_new_comment( $data );

		// Check to see if a notification email was sent to the moderator `admin@example.org`.
		if ( isset( $GLOBALS['phpmailer']->mock_sent )
			&& ! empty( $GLOBALS['phpmailer']->mock_sent )
			&& GC_TESTS_EMAIL === $GLOBALS['phpmailer']->mock_sent[0]['to'][0][0]
		) {
			$email_sent_when_comment_added = true;
			reset_phpmailer_instance();
		} else {
			$email_sent_when_comment_added = false;
		}

		return $email_sent_when_comment_added;
	}

	/**
	 * Helper function to test sending author notifications.
	 *
	 * @since 4.4.0
	 * @access public
	 */
	public function try_sending_author_notification( $comment, $post ) {

		// Approve comments, triggering notifications.
		add_filter( 'pre_comment_approved', '__return_true' );

		// Post authors possibly notified when a comment is approved on their post.
		gc_set_comment_status( $comment, 'approve' );

		// Check to see if a notification email was sent to the post author `test@test.com`.
		if ( isset( $GLOBALS['phpmailer']->mock_sent )
			&& ! empty( $GLOBALS['phpmailer']->mock_sent )
			&& 'test@test.com' === $GLOBALS['phpmailer']->mock_sent[0]['to'][0][0]
		) {
			$email_sent_when_comment_approved = true;
		} else {
			$email_sent_when_comment_approved = false;
		}
		reset_phpmailer_instance();

		// Post authors are notified when a new comment is added to their post.
		$data = array(
			'comment_post_ID'      => $post,
			'comment_author'       => 'Comment Author',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);
		gc_new_comment( $data );

		// Check to see if a notification email was sent to the post author `test@test.com`.
		if ( isset( $GLOBALS['phpmailer']->mock_sent ) &&
			! empty( $GLOBALS['phpmailer']->mock_sent ) &&
			'test@test.com' === $GLOBALS['phpmailer']->mock_sent[0]['to'][0][0] ) {
				$email_sent_when_comment_added = true;
				reset_phpmailer_instance();
		} else {
			$email_sent_when_comment_added = false;
		}

		return $email_sent_when_comment_approved || $email_sent_when_comment_added;
	}

	public function test_close_comments_for_old_post() {
		update_option( 'close_comments_for_old_posts', true );
		// Close comments more than one day old.
		update_option( 'close_comments_days_old', 1 );

		$old_date    = date_create( '-25 hours' );
		$old_post_id = self::factory()->post->create( array( 'post_date' => date_format( $old_date, 'Y-m-d H:i:s' ) ) );

		$old_post_comment_status = _close_comments_for_old_post( true, $old_post_id );
		$this->assertFalse( $old_post_comment_status );

		$new_post_comment_status = _close_comments_for_old_post( true, self::$post_id );
		$this->assertTrue( $new_post_comment_status );
	}

	public function test_close_comments_for_old_post_undated_draft() {
		$draft_id             = self::factory()->post->create(
			array(
				'post_status' => 'draft',
				'post_type'   => 'post',
			)
		);
		$draft_comment_status = _close_comments_for_old_post( true, $draft_id );

		$this->assertTrue( $draft_comment_status );
	}

	/**
	 * @ticket 35276
	 */
	public function test_gc_update_comment_author_id_and_agent() {

		$default_data = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_IP'    => '192.168.0.1',
			'comment_agent'        => 'WRONG_AGENT',
			'comment_author_url'   => '',
			'comment_author_email' => '',
			'comment_type'         => '',
			'comment_content'      => 'Comment',
		);

		$comment_id = gc_new_comment( $default_data );

		// Confirm that the IP and Agent are correct on initial save.
		$save = get_comment( $comment_id );
		$this->assertSame( $default_data['comment_author_IP'], $save->comment_author_IP );
		$this->assertSame( $default_data['comment_agent'], $save->comment_agent );

		// Update the comment.
		gc_update_comment(
			array(
				'comment_ID'        => $comment_id,
				'comment_author_IP' => '111.111.1.1',
				'comment_agent'     => 'SHIELD_AGENT',
			)
		);

		// Retrieve and check the new values.
		$updated = get_comment( $comment_id );
		$this->assertSame( '111.111.1.1', $updated->comment_author_IP );
		$this->assertSame( 'SHIELD_AGENT', $updated->comment_agent );
	}

	public function test_gc_get_comment_fields_max_lengths() {
		$expected = array(
			'comment_author'       => 245,
			'comment_author_email' => 100,
			'comment_author_url'   => 200,
			'comment_content'      => 65525,
		);

		$lengths = gc_get_comment_fields_max_lengths();

		foreach ( $lengths as $field => $length ) {
			$this->assertSame( $expected[ $field ], $length );
		}
	}

	/**
	 * The `gc_comments_personal_data_eraser()` function should erase user's comments.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_comments_personal_data_eraser() {

		$post_id = self::factory()->post->create();
		$user_id = self::factory()->user->create();

		$args       = array(
			'user_id'              => $user_id,
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_agent'        => 'COMMENT_AGENT',
			'comment_content'      => 'Comment Content',
		);
		$comment_id = self::factory()->comment->create( $args );

		gc_comments_personal_data_eraser( $args['comment_author_email'] );

		$comment = get_comment( $comment_id );

		$actual = array(
			'comment_ID'           => $comment->comment_ID,
			'user_id'              => $comment->user_id,
			'comment_author'       => $comment->comment_author,
			'comment_author_email' => $comment->comment_author_email,
			'comment_author_url'   => $comment->comment_author_url,
			'comment_author_IP'    => $comment->comment_author_IP,
			'comment_date'         => $comment->comment_date,
			'comment_date_gmt'     => $comment->comment_date_gmt,
			'comment_agent'        => $comment->comment_agent,
			'comment_content'      => $comment->comment_content,
		);

		$expected = array(
			'comment_ID'           => (string) $comment_id,
			'user_id'              => '0', // Anonymized.
			'comment_author'       => 'Anonymous', // Anonymized.
			'comment_author_email' => '', // Anonymized.
			'comment_author_url'   => '', // Anonymized.
			'comment_author_IP'    => '192.168.0.0', // Anonymized.
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_date_gmt'     => '2018-04-14 17:20:00',
			'comment_agent'        => '', // Anonymized.
			'comment_content'      => 'Comment Content',
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_comments_personal_data_eraser()` function's output on an empty first page.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_comments_personal_data_eraser_empty_first_page_output() {

		$actual   = gc_comments_personal_data_eraser( 'nocommentsfound@local.host' );
		$expected = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_comments_personal_data_eraser()` function's output, for the non-empty first page.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_comments_personal_data_eraser_non_empty_first_page_output() {

		$post_id = self::factory()->post->create();
		$args    = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_agent'        => 'COMMENT_AGENT',
			'comment_content'      => 'Comment Content',
		);
		self::factory()->comment->create( $args );

		$actual   = gc_comments_personal_data_eraser( $args['comment_author_email'] );
		$expected = array(
			'items_removed'  => true,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_comments_personal_data_eraser()` function's output, for an empty second page.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_comments_personal_data_eraser_empty_second_page_output() {

		$post_id = self::factory()->post->create();
		$args    = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_agent'        => 'COMMENT_AGENT',
			'comment_content'      => 'Comment Content',
		);
		self::factory()->comment->create( $args );

		$actual   = gc_comments_personal_data_eraser( $args['comment_author_email'], 2 );
		$expected = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_anonymize_comment` filter, to prevent comment anonymization.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_anonymize_comment_filter_to_prevent_comment_anonymization() {

		$post_id    = self::factory()->post->create();
		$args       = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_agent'        => 'COMMENT_AGENT',
			'comment_content'      => 'Comment Content',
		);
		$comment_id = self::factory()->comment->create( $args );

		add_filter( 'gc_anonymize_comment', '__return_false' );
		$actual = gc_comments_personal_data_eraser( $args['comment_author_email'] );
		remove_filter( 'gc_anonymize_comment', '__return_false' );

		$message = sprintf( 'Comment %d contains personal data but could not be anonymized.', $comment_id );

		$expected = array(
			'items_removed'  => false,
			'items_retained' => true,
			'messages'       => array( $message ),
			'done'           => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_anonymize_comment` filter, to prevent comment anonymization, with a custom message.
	 *
	 * @group privacy
	 * @ticket 43442
	 */
	public function test_gc_anonymize_comment_filter_to_prevent_comment_anonymization_with_custom_message() {

		$post_id    = self::factory()->post->create();
		$args       = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-04-14 17:20:00',
			'comment_agent'        => 'COMMENT_AGENT',
			'comment_content'      => 'Comment Content',
		);
		$comment_id = self::factory()->comment->create( $args );

		add_filter( 'gc_anonymize_comment', array( $this, 'gc_anonymize_comment_custom_message' ), 10, 3 );
		$actual = gc_comments_personal_data_eraser( $args['comment_author_email'] );
		remove_filter( 'gc_anonymize_comment', array( $this, 'gc_anonymize_comment_custom_message' ) );

		$message = sprintf( 'Some custom message for comment %d.', $comment_id );

		$expected = array(
			'items_removed'  => false,
			'items_retained' => true,
			'messages'       => array( $message ),
			'done'           => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Callback for the `gc_anonymize_comment` filter.
	 *
	 * @param  bool|string $anonymize          Whether to apply the comment anonymization (bool).
	 *                                         Custom prevention message (string). Default true.
	 * @param  GC_Comment  $comment            GC_Comment object.
	 * @param  array       $anonymized_comment Anonymized comment data.
	 * @return string
	 */
	public function gc_anonymize_comment_custom_message( $anonymize, $comment, $anonymized_comment ) {
		return sprintf( 'Some custom message for comment %d.', $comment->comment_ID );
	}

	public function test_update_should_invalidate_comment_cache() {
		global $gcdb;

		$c = self::factory()->comment->create( array( 'comment_author' => 'Foo' ) );

		$comment = get_comment( $c );
		$this->assertSame( 'Foo', $comment->comment_author );

		gc_update_comment(
			array(
				'comment_ID'     => $c,
				'comment_author' => 'Bar',
			)
		);

		$comment = get_comment( $c );

		$this->assertSame( 'Bar', $comment->comment_author );
	}

	public function test_trash_should_invalidate_comment_cache() {
		global $gcdb;

		$c = self::factory()->comment->create();

		$comment = get_comment( $c );

		gc_trash_comment( $c );

		$comment = get_comment( $c );

		$this->assertSame( 'trash', $comment->comment_approved );
	}

	public function test_untrash_should_invalidate_comment_cache() {
		global $gcdb;

		$c = self::factory()->comment->create();
		gc_trash_comment( $c );

		$comment = get_comment( $c );
		$this->assertSame( 'trash', $comment->comment_approved );

		gc_untrash_comment( $c );

		$comment = get_comment( $c );

		$this->assertSame( '1', $comment->comment_approved );
	}

	public function test_spam_should_invalidate_comment_cache() {
		global $gcdb;

		$c = self::factory()->comment->create();

		$comment = get_comment( $c );

		gc_spam_comment( $c );

		$comment = get_comment( $c );

		$this->assertSame( 'spam', $comment->comment_approved );
	}

	public function test_unspam_should_invalidate_comment_cache() {
		global $gcdb;

		$c = self::factory()->comment->create();
		gc_spam_comment( $c );

		$comment = get_comment( $c );
		$this->assertSame( 'spam', $comment->comment_approved );

		gc_unspam_comment( $c );

		$comment = get_comment( $c );

		$this->assertSame( '1', $comment->comment_approved );
	}

	/**
	 * Testing the `gc_comments_personal_data_exporter()` function.
	 *
	 * @group privacy
	 * @ticket 43440
	 */
	public function test_gc_comments_personal_data_exporter() {
		$args = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_agent'        => 'SOME_AGENT',
			'comment_date'         => '2018-03-28 20:05:00',
			'comment_content'      => 'Comment',
		);

		$comment_id = self::factory()->comment->create( $args );

		$actual   = gc_comments_personal_data_exporter( $args['comment_author_email'] );
		$expected = $args;

		$this->assertTrue( $actual['done'] );

		// Number of exported comments.
		$this->assertCount( 1, $actual['data'] );

		// Number of exported comment properties.
		$this->assertCount( 8, $actual['data'][0]['data'] );

		// Exported group.
		$this->assertSame( 'comments', $actual['data'][0]['group_id'] );
		$this->assertSame( 'Comments', $actual['data'][0]['group_label'] );

		// Exported comment properties.
		$this->assertSame( $expected['comment_author'], $actual['data'][0]['data'][0]['value'] );
		$this->assertSame( $expected['comment_author_email'], $actual['data'][0]['data'][1]['value'] );
		$this->assertSame( $expected['comment_author_url'], $actual['data'][0]['data'][2]['value'] );
		$this->assertSame( $expected['comment_author_IP'], $actual['data'][0]['data'][3]['value'] );
		$this->assertSame( $expected['comment_agent'], $actual['data'][0]['data'][4]['value'] );
		$this->assertSame( $expected['comment_date'], $actual['data'][0]['data'][5]['value'] );
		$this->assertSame( $expected['comment_content'], $actual['data'][0]['data'][6]['value'] );
		$this->assertSame( esc_html( get_comment_link( $comment_id ) ), strip_tags( $actual['data'][0]['data'][7]['value'] ) );
	}

	/**
	 * Testing the `gc_comments_personal_data_exporter()` function for no comments found.
	 *
	 * @group privacy
	 * @ticket 43440
	 */
	public function test_gc_comments_personal_data_exporter_no_comments_found() {

		$actual = gc_comments_personal_data_exporter( 'nocommentsfound@local.host' );

		$expected = array(
			'data' => array(),
			'done' => true,
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Testing the `gc_comments_personal_data_exporter()` function for an empty comment property.
	 *
	 * @group privacy
	 * @ticket 43440
	 */
	public function test_gc_comments_personal_data_exporter_empty_comment_prop() {
		$args = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-03-28 20:05:00',
			'comment_agent'        => '',
			'comment_content'      => 'Comment',
		);

		$c = self::factory()->comment->create( $args );

		$actual = gc_comments_personal_data_exporter( $args['comment_author_email'] );

		$this->assertTrue( $actual['done'] );

		// Number of exported comments.
		$this->assertCount( 1, $actual['data'] );

		// Number of exported comment properties.
		$this->assertCount( 7, $actual['data'][0]['data'] );
	}

	/**
	 * Testing the `gc_comments_personal_data_exporter()` function with an empty second page.
	 *
	 * @group privacy
	 * @ticket 43440
	 */
	public function test_gc_comments_personal_data_exporter_empty_second_page() {
		$args = array(
			'comment_post_ID'      => self::$post_id,
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'personal@local.host',
			'comment_author_url'   => 'https://local.host/',
			'comment_author_IP'    => '192.168.0.1',
			'comment_date'         => '2018-03-28 20:05:00',
			'comment_agent'        => 'SOME_AGENT',
			'comment_content'      => 'Comment',
		);

		$c = self::factory()->comment->create( $args );

		$actual = gc_comments_personal_data_exporter( $args['comment_author_email'], 2 );

		$this->assertTrue( $actual['done'] );

		// Number of exported comments.
		$this->assertCount( 0, $actual['data'] );
	}
}
