<?php
/**
 * Testing Ajax customize manager functionality.
 *
 * @package    GeChiUI
 * @subpackage UnitTests
 * @since      4.3.0
 * @group      ajax
 */
class Tests_Ajax_CustomizeManager extends GC_Ajax_UnitTestCase {

	/**
	 * Instance of GC_Customize_Manager which is reset for each test.
	 *
	 * @var GC_Customize_Manager
	 */
	public $gc_customize;

	/**
	 * Admin user ID.
	 *
	 * @var int
	 */
	protected static $admin_user_id;

	/**
	 * Subscriber user ID.
	 *
	 * @var int
	 */
	protected static $subscriber_user_id;

	/**
	 * Last response parsed.
	 *
	 * @var array|null
	 */
	protected $_last_response_parsed;

	/**
	 * Set up before class.
	 *
	 * @param GC_UnitTest_Factory $factory Factory.
	 */
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$subscriber_user_id = $factory->user->create( array( 'role' => 'subscriber' ) );
		self::$admin_user_id      = $factory->user->create( array( 'role' => 'administrator' ) );
	}

	/**
	 * Set up the test fixture.
	 */
	public function set_up() {
		parent::set_up();
		require_once ABSPATH . GCINC . '/class-gc-customize-manager.php';
	}

	/**
	 * Tear down.
	 */
	public function tear_down() {
		$_REQUEST = array();
		parent::tear_down();
	}

	/**
	 * Helper to keep it DRY
	 *
	 * @param string $action Action.
	 */
	protected function make_ajax_call( $action ) {
		$this->_last_response_parsed = null;
		$this->_last_response        = '';
		try {
			$this->_handleAjax( $action );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}
		if ( $this->_last_response ) {
			$this->_last_response_parsed = json_decode( $this->_last_response, true );
		}
	}

	/**
	 * Overridden caps for user_has_cap.
	 *
	 * @var array
	 */
	protected $overridden_caps = array();

	/**
	 * Dynamically filter a user's capabilities.
	 *
	 * @param array $allcaps An array of all the user's capabilities.
	 * @return array All caps.
	 */
	public function filter_user_has_cap( $allcaps ) {
		$allcaps = array_merge( $allcaps, $this->overridden_caps );
		return $allcaps;
	}

	/**
	 * Test GC_Customize_Manager::save().
	 *
	 * @ticket 30937
	 * @covers GC_Customize_Manager::save
	 */
	public function test_save_failures() {
		global $gc_customize;
		$gc_customize = new GC_Customize_Manager();
		$gc_customize->register_controls();
		add_filter( 'user_has_cap', array( $this, 'filter_user_has_cap' ) );

		// Unauthenticated.
		gc_set_current_user( 0 );
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'unauthenticated', $this->_last_response_parsed['data'] );

		// Unauthorized.
		gc_set_current_user( self::$subscriber_user_id );
		$nonce             = gc_create_nonce( 'save-customize_' . $gc_customize->get_stylesheet() );
		$_POST['nonce']    = $nonce;
		$_GET['nonce']     = $nonce;
		$_REQUEST['nonce'] = $nonce;
		$exception         = null;
		try {
			ob_start();
			$gc_customize->setup_theme();
		} catch ( GCAjaxDieContinueException $e ) {
			$exception = $e;
		}
		$this->assertNotEmpty( $e );
		$this->assertEquals( -1, $e->getMessage() );

		// Not called setup_theme.
		gc_set_current_user( self::$admin_user_id );
		$nonce             = gc_create_nonce( 'save-customize_' . $gc_customize->get_stylesheet() );
		$_POST['nonce']    = $nonce;
		$_GET['nonce']     = $nonce;
		$_REQUEST['nonce'] = $nonce;
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'not_preview', $this->_last_response_parsed['data'] );

		// Bad nonce.
		$_POST['nonce']    = 'bad';
		$_GET['nonce']     = 'bad';
		$_REQUEST['nonce'] = 'bad';
		$gc_customize->setup_theme();
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'invalid_nonce', $this->_last_response_parsed['data'] );

		// User cannot create.
		$nonce                            = gc_create_nonce( 'save-customize_' . $gc_customize->get_stylesheet() );
		$_POST['nonce']                   = $nonce;
		$_GET['nonce']                    = $nonce;
		$_REQUEST['nonce']                = $nonce;
		$post_type_obj                    = get_post_type_object( 'customize_changeset' );
		$post_type_obj->cap->create_posts = 'create_customize_changesets';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'cannot_create_changeset_post', $this->_last_response_parsed['data'] );
		$this->overridden_caps[ $post_type_obj->cap->create_posts ] = true;
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$post_type_obj->cap->create_posts = 'customize'; // Restore.

		// Changeset already published.
		$gc_customize->set_post_value( 'blogname', 'Hello' );
		$gc_customize->save_changeset_post( array( 'status' => 'publish' ) );
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_already_published', $this->_last_response_parsed['data']['code'] );
		gc_update_post(
			array(
				'ID'          => $gc_customize->changeset_post_id(),
				'post_status' => 'auto-draft',
			)
		);

		// User cannot edit.
		$post_type_obj                 = get_post_type_object( 'customize_changeset' );
		$post_type_obj->cap->edit_post = 'edit_customize_changesets';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'cannot_edit_changeset_post', $this->_last_response_parsed['data'] );
		$this->overridden_caps[ $post_type_obj->cap->edit_post ] = true;
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$post_type_obj->cap->edit_post = 'customize'; // Restore.

		// Bad customize_changeset_data.
		$_POST['customize_changeset_data'] = '[MALFORMED]';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'invalid_customize_changeset_data', $this->_last_response_parsed['data'] );

		// Bad customize_changeset_status.
		$_POST['customize_changeset_data']   = '{}';
		$_POST['customize_changeset_status'] = 'unrecognized';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'bad_customize_changeset_status', $this->_last_response_parsed['data'] );

		// Disallowed publish posts if not allowed.
		$post_type_obj                       = get_post_type_object( 'customize_changeset' );
		$post_type_obj->cap->publish_posts   = 'publish_customize_changesets';
		$_POST['customize_changeset_status'] = 'publish';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_publish_unauthorized', $this->_last_response_parsed['data'] );
		$_POST['customize_changeset_status'] = 'future';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_publish_unauthorized', $this->_last_response_parsed['data'] );
		$post_type_obj->cap->publish_posts = 'customize'; // Restore.

		// Validate date.
		$_POST['customize_changeset_status'] = 'draft';
		$_POST['customize_changeset_date']   = 'BAD DATE';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'bad_customize_changeset_date', $this->_last_response_parsed['data'] );
		$_POST['customize_changeset_date'] = '2010-01-01 00:00:00';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'not_future_date', $this->_last_response_parsed['data']['code'] );
		$_POST['customize_changeset_date'] = ( gmdate( 'Y' ) + 1 ) . '-01-01 00:00:00';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$_POST['customize_changeset_status'] = 'future';
		$_POST['customize_changeset_date']   = '+10 minutes';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'future', get_post_status( $gc_customize->changeset_post_id() ) );
		gc_update_post(
			array(
				'ID'          => $gc_customize->changeset_post_id(),
				'post_status' => 'auto-draft',
			)
		);
	}

	/**
	 * Set up valid user state.
	 *
	 * @param string $uuid Changeset UUID.
	 * @return GC_Customize_Manager
	 */
	protected function set_up_valid_state( $uuid = null ) {
		global $gc_customize;
		gc_set_current_user( self::$admin_user_id );
		$gc_customize = new GC_Customize_Manager(
			array(
				'changeset_uuid' => $uuid,
			)
		);
		$gc_customize->register_controls();
		$nonce             = gc_create_nonce( 'save-customize_' . $gc_customize->get_stylesheet() );
		$_POST['nonce']    = $nonce;
		$_GET['nonce']     = $nonce;
		$_REQUEST['nonce'] = $nonce;
		$gc_customize->setup_theme();
		return $gc_customize;
	}

	/**
	 * Test GC_Customize_Manager::save().
	 *
	 * @ticket 30937
	 * @covers GC_Customize_Manager::save
	 */
	public function test_save_success_publish_create() {
		$gc_customize = $this->set_up_valid_state();

		$_POST['customize_changeset_status'] = 'publish';
		$_POST['customize_changeset_title']  = 'Success Changeset';
		$_POST['customize_changeset_data']   = gc_json_encode(
			array(
				'blogname' => array(
					'value' => 'Successful Site Title',
				),
			)
		);
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertIsArray( $this->_last_response_parsed['data'] );

		$this->assertSame( 'publish', $this->_last_response_parsed['data']['changeset_status'] );
		$this->assertArrayHasKey( 'next_changeset_uuid', $this->_last_response_parsed['data'] );
		$this->assertTrue( gc_is_uuid( $this->_last_response_parsed['data']['next_changeset_uuid'], 4 ) );
		$this->assertSame( 'Success Changeset', get_post( $gc_customize->changeset_post_id() )->post_title );
		$this->assertSame( 'Successful Site Title', get_option( 'blogname' ) );
	}

	/**
	 * Test GC_Customize_Manager::save().
	 *
	 * @ticket 30937
	 * @covers GC_Customize_Manager::save
	 */
	public function test_save_success_publish_edit() {
		$uuid = gc_generate_uuid4();

		$post_id      = $this->factory()->post->create(
			array(
				'post_name'    => $uuid,
				'post_title'   => 'Original',
				'post_type'    => 'customize_changeset',
				'post_status'  => 'auto-draft',
				'post_content' => gc_json_encode(
					array(
						'blogname' => array(
							'value' => 'New Site Title',
						),
					)
				),
			)
		);
		$gc_customize = $this->set_up_valid_state( $uuid );

		$_POST['customize_changeset_status'] = 'publish';
		$_POST['customize_changeset_title']  = 'Published';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertIsArray( $this->_last_response_parsed['data'] );

		$this->assertSame( 'publish', $this->_last_response_parsed['data']['changeset_status'] );
		$this->assertArrayHasKey( 'next_changeset_uuid', $this->_last_response_parsed['data'] );
		$this->assertTrue( gc_is_uuid( $this->_last_response_parsed['data']['next_changeset_uuid'], 4 ) );
		$this->assertSame( 'New Site Title', get_option( 'blogname' ) );
		$this->assertSame( 'Published', get_post( $post_id )->post_title );
	}

	/**
	 * Test GC_Customize_Manager::save().
	 *
	 * @ticket 38943
	 * @covers GC_Customize_Manager::save
	 */
	public function test_success_save_post_date() {
		$uuid         = gc_generate_uuid4();
		$post_id      = $this->factory()->post->create(
			array(
				'post_name'    => $uuid,
				'post_title'   => 'Original',
				'post_type'    => 'customize_changeset',
				'post_status'  => 'auto-draft',
				'post_content' => gc_json_encode(
					array(
						'blogname' => array(
							'value' => 'New Site Title',
						),
					)
				),
			)
		);
		$gc_customize = $this->set_up_valid_state( $uuid );

		// Success future schedule date.
		$future_date                         = ( gmdate( 'Y' ) + 1 ) . '-01-01 00:00:00';
		$_POST['customize_changeset_status'] = 'future';
		$_POST['customize_changeset_title']  = 'Future date';
		$_POST['customize_changeset_date']   = $future_date;
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertArrayHasKey( 'changeset_date', $this->_last_response_parsed['data'] );
		$changeset_post_schedule = get_post( $post_id );
		$this->assertSame( $future_date, $changeset_post_schedule->post_date );

		// Success future changeset change to draft keeping existing date.
		unset( $_POST['customize_changeset_date'] );
		$_POST['customize_changeset_status'] = 'draft';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertArrayNotHasKey( 'changeset_date', $this->_last_response_parsed['data'] );
		$changeset_post_draft = get_post( $post_id );
		$this->assertSame( $future_date, $changeset_post_draft->post_date );

		// Success if date is not passed with schedule changeset and stored changeset have future date.
		$_POST['customize_changeset_status'] = 'future';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertArrayHasKey( 'changeset_date', $this->_last_response_parsed['data'] );
		$changeset_post_schedule = get_post( $post_id );
		$this->assertSame( $future_date, $changeset_post_schedule->post_date );
		// Success if draft with past date.
		$now = current_time( 'mysql' );
		gc_update_post(
			array(
				'ID'            => $post_id,
				'post_status'   => 'draft',
				'post_date'     => $now,
				'post_date_gmt' => get_gmt_from_date( $now ),
			)
		);

		// Fail if future request and existing date is past.
		$_POST['customize_changeset_status'] = 'future';
		unset( $_POST['customize_changeset_date'] );
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'not_future_date', $this->_last_response_parsed['data']['code'] );

		// Success publish changeset reset date to current.
		gc_update_post(
			array(
				'ID'            => $post_id,
				'post_status'   => 'future',
				'post_date'     => $future_date,
				'post_date_gmt' => get_gmt_from_date( $future_date ),
			)
		);
		unset( $_POST['customize_changeset_date'] );
		$_POST['customize_changeset_status'] = 'publish';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertArrayHasKey( 'next_changeset_uuid', $this->_last_response_parsed['data'] );
		$this->assertTrue( gc_is_uuid( $this->_last_response_parsed['data']['next_changeset_uuid'], 4 ) );
		$changeset_post_publish = get_post( $post_id );
		$this->assertNotEquals( $future_date, $changeset_post_publish->post_date );

		// Check response when trying to update an already-published post.
		$this->assertSame( 'trash', get_post_status( $post_id ) );
		$_POST['customize_changeset_status'] = 'publish';
		$this->make_ajax_call( 'customize_save' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_already_published', $this->_last_response_parsed['data']['code'] );
		$this->assertArrayHasKey( 'next_changeset_uuid', $this->_last_response_parsed['data'] );
		$this->assertTrue( gc_is_uuid( $this->_last_response_parsed['data']['next_changeset_uuid'], 4 ) );
	}

	/**
	 * Test GC_Customize_Manager::save().
	 *
	 * @ticket 39896
	 * @covers GC_Customize_Manager::save
	 */
	public function test_save_autosave() {
		$uuid = gc_generate_uuid4();

		$post_id = $this->factory()->post->create(
			array(
				'post_name'    => $uuid,
				'post_type'    => 'customize_changeset',
				'post_status'  => 'draft',
				'post_content' => gc_json_encode(
					array(
						'blogname' => array(
							'value' => 'New Site Title',
						),
					)
				),
			)
		);
		$this->set_up_valid_state( $uuid );

		$this->assertFalse( gc_get_post_autosave( $post_id ) );

		$_POST['customize_changeset_data'] = gc_json_encode(
			array(
				'blogname' => array(
					'value' => 'Autosaved Site Title',
				),
			)
		);

		$_POST['customize_changeset_autosave'] = 'on';
		$this->make_ajax_call( 'customize_save' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'draft', $this->_last_response_parsed['data']['changeset_status'] );
		$autosave_revision = gc_get_post_autosave( $post_id );
		$this->assertInstanceOf( 'GC_Post', $autosave_revision );

		$this->assertStringContainsString( 'New Site Title', get_post( $post_id )->post_content );
		$this->assertStringContainsString( 'Autosaved Site Title', $autosave_revision->post_content );
	}

	/**
	 * Test request for trashing a changeset.
	 *
	 * @ticket 39896
	 * @covers GC_Customize_Manager::handle_changeset_trash_request
	 */
	public function test_handle_changeset_trash_request() {
		$uuid         = gc_generate_uuid4();
		$gc_customize = $this->set_up_valid_state( $uuid );

		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'invalid_nonce', $this->_last_response_parsed['data']['code'] );

		$nonce             = gc_create_nonce( 'trash_customize_changeset' );
		$_POST['nonce']    = $nonce;
		$_GET['nonce']     = $nonce;
		$_REQUEST['nonce'] = $nonce;
		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'non_existent_changeset', $this->_last_response_parsed['data']['code'] );

		$gc_customize->register_controls(); // And settings too.
		$gc_customize->set_post_value( 'blogname', 'HELLO' );
		$gc_customize->save_changeset_post(
			array(
				'status' => 'save',
			)
		);

		add_filter( 'map_meta_cap', array( $this, 'return_do_not_allow' ) );
		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_trash_unauthorized', $this->_last_response_parsed['data']['code'] );
		remove_filter( 'map_meta_cap', array( $this, 'return_do_not_allow' ) );

		$lock_user_id  = static::factory()->user->create( array( 'role' => 'administrator' ) );
		$previous_user = get_current_user_id();
		gc_set_current_user( $lock_user_id );
		$gc_customize->set_changeset_lock( $gc_customize->changeset_post_id() );
		gc_set_current_user( $previous_user );
		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_locked', $this->_last_response_parsed['data']['code'] );
		delete_post_meta( $gc_customize->changeset_post_id(), '_edit_lock' );

		gc_update_post(
			array(
				'ID'          => $gc_customize->changeset_post_id(),
				'post_status' => 'trash',
			)
		);
		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_already_trashed', $this->_last_response_parsed['data']['code'] );

		gc_update_post(
			array(
				'ID'          => $gc_customize->changeset_post_id(),
				'post_status' => 'draft',
			)
		);

		$gc_trash_post_count = did_action( 'gc_trash_post' );
		add_filter( 'pre_trash_post', '__return_false' );
		$this->make_ajax_call( 'customize_trash' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_trash_failure', $this->_last_response_parsed['data']['code'] );
		remove_filter( 'pre_trash_post', '__return_false' );
		$this->assertSame( $gc_trash_post_count, did_action( 'gc_trash_post' ) );

		$gc_trash_post_count = did_action( 'gc_trash_post' );
		$this->assertSame( 'draft', get_post_status( $gc_customize->changeset_post_id() ) );
		$this->make_ajax_call( 'customize_trash' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'trash', get_post_status( $gc_customize->changeset_post_id() ) );
		$this->assertSame( $gc_trash_post_count + 1, did_action( 'gc_trash_post' ) );
	}

	/**
	 * Return caps array containing 'do_not_allow'.
	 *
	 * @return array Caps.
	 */
	public function return_do_not_allow() {
		return array( 'do_not_allow' );
	}

	/**
	 * Test request for dismissing autosave changesets.
	 *
	 * @ticket 39896
	 * @covers GC_Customize_Manager::handle_dismiss_autosave_or_lock_request
	 * @covers GC_Customize_Manager::dismiss_user_auto_draft_changesets
	 */
	public function test_handle_dismiss_autosave_or_lock_request() {
		$uuid          = gc_generate_uuid4();
		$gc_customize  = $this->set_up_valid_state( $uuid );
		$valid_user_id = get_current_user_id();

		// Temporarily remove user to test requirement that user is logged in. See #42450.
		gc_set_current_user( 0 );
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'unauthenticated', $this->_last_response_parsed['data'] );
		gc_set_current_user( $valid_user_id );

		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'invalid_nonce', $this->_last_response_parsed['data'] );

		$nonce             = gc_create_nonce( 'customize_dismiss_autosave_or_lock' );
		$_POST['nonce']    = $nonce;
		$_GET['nonce']     = $nonce;
		$_REQUEST['nonce'] = $nonce;

		$_POST['dismiss_lock']    = true;
		$_GET['dismiss_lock']     = true;
		$_REQUEST['dismiss_lock'] = true;
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'no_changeset_to_dismiss_lock', $this->_last_response_parsed['data'] );

		$_POST['dismiss_autosave']    = true;
		$_GET['dismiss_autosave']     = true;
		$_REQUEST['dismiss_autosave'] = true;
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'no_auto_draft_to_delete', $this->_last_response_parsed['data'] );

		$other_user_id = $this->factory()->user->create();

		// Create auto-drafts.
		$user_auto_draft_ids = array();
		for ( $i = 0; $i < 3; $i++ ) {
			$user_auto_draft_ids[] = $this->factory()->post->create(
				array(
					'post_name'    => gc_generate_uuid4(),
					'post_type'    => 'customize_changeset',
					'post_status'  => 'auto-draft',
					'post_author'  => self::$admin_user_id,
					'post_content' => gc_json_encode( array() ),
				)
			);
		}
		$other_user_auto_draft_ids = array();
		for ( $i = 0; $i < 3; $i++ ) {
			$other_user_auto_draft_ids[] = $this->factory()->post->create(
				array(
					'post_name'    => gc_generate_uuid4(),
					'post_type'    => 'customize_changeset',
					'post_status'  => 'auto-draft',
					'post_author'  => $other_user_id,
					'post_content' => gc_json_encode( array() ),
				)
			);
		}
		foreach ( array_merge( $user_auto_draft_ids, $other_user_auto_draft_ids ) as $post_id ) {
			$this->assertFalse( (bool) get_post_meta( $post_id, '_customize_restore_dismissed', true ) );
		}
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'auto_draft_dismissed', $this->_last_response_parsed['data'] );
		foreach ( $user_auto_draft_ids as $post_id ) {
			$this->assertSame( 'auto-draft', get_post_status( $post_id ) );
			$this->assertTrue( (bool) get_post_meta( $post_id, '_customize_restore_dismissed', true ) );
		}
		foreach ( $other_user_auto_draft_ids as $post_id ) {
			$this->assertSame( 'auto-draft', get_post_status( $post_id ) );
			$this->assertFalse( (bool) get_post_meta( $post_id, '_customize_restore_dismissed', true ) );
		}

		// Subsequent test results in none dismissed.
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'no_auto_draft_to_delete', $this->_last_response_parsed['data'] );

		// Save a changeset as a draft.
		$r = $gc_customize->save_changeset_post(
			array(
				'data'   => array(
					'blogname' => array(
						'value' => 'Foo',
					),
				),
				'status' => 'draft',
			)
		);

		$_POST['dismiss_autosave']    = false;
		$_GET['dismiss_autosave']     = false;
		$_REQUEST['dismiss_autosave'] = false;
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'changeset_lock_dismissed', $this->_last_response_parsed['data'] );

		$_POST['dismiss_autosave']    = true;
		$_GET['dismiss_autosave']     = true;
		$_REQUEST['dismiss_autosave'] = true;
		$this->assertNotWPError( $r );
		$this->assertFalse( gc_get_post_autosave( $gc_customize->changeset_post_id() ) );
		$this->assertStringContainsString( 'Foo', get_post( $gc_customize->changeset_post_id() )->post_content );

		// Since no autosave yet, confirm no action.
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'no_autosave_revision_to_delete', $this->_last_response_parsed['data'] );

		// Add the autosave revision.
		$r = $gc_customize->save_changeset_post(
			array(
				'data'     => array(
					'blogname' => array(
						'value' => 'Bar',
					),
				),
				'autosave' => true,
			)
		);
		$this->assertNotWPError( $r );
		$autosave_revision = gc_get_post_autosave( $gc_customize->changeset_post_id() );
		$this->assertInstanceOf( 'GC_Post', $autosave_revision );
		$this->assertStringContainsString( 'Foo', get_post( $gc_customize->changeset_post_id() )->post_content );
		$this->assertStringContainsString( 'Bar', $autosave_revision->post_content );

		// Confirm autosave gets deleted.
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertTrue( $this->_last_response_parsed['success'] );
		$this->assertSame( 'autosave_revision_deleted', $this->_last_response_parsed['data'] );
		$this->assertFalse( gc_get_post_autosave( $gc_customize->changeset_post_id() ) );

		// Since no autosave yet, confirm no action.
		$this->make_ajax_call( 'customize_dismiss_autosave_or_lock' );
		$this->assertFalse( $this->_last_response_parsed['success'] );
		$this->assertSame( 'no_autosave_revision_to_delete', $this->_last_response_parsed['data'] );
	}

	/**
	 * Test request for retrieving installed themes.
	 *
	 * @ticket 54549
	 * @covers GC_Customize_Manager::handle_load_themes_request
	 */
	public function test_gc_ajax_customize_load_themes_action() {
		$arguments = array(
			'changeset_uuid'     => false,
			'settings_previewed' => true,
			'branching'          => false,
		);
		new GC_Customize_Manager( $arguments );
		gc_set_current_user( self::$admin_user_id );
		$nonce                 = gc_create_nonce( 'switch_themes' );
		$_POST['nonce']        = $nonce;
		$_GET['nonce']         = $nonce;
		$_REQUEST['nonce']     = $nonce;
		$_POST['theme_action'] = 'installed';
		$this->make_ajax_call( 'customize_load_themes' );
		$response = $this->_last_response_parsed;
		$this->assertIsArray( $response, 'Response is not an array' );

		$this->assertArrayHasKey( 'success', $response, 'Response must have a "success" key' );
		$this->assertTrue( $response['success'], 'Response was not "success"' );

		$this->assertArrayHasKey( 'data', $response, 'Response must have a "data" key' );
		$this->assertIsArray( $response['data'], 'The response "data" is not an array' );
		$this->assertArrayHasKey( 'themes', $response['data'], 'The response data must have a "themes" key' );
		$this->assertIsArray( $response['data']['themes'], 'Themes data is not an array' );
		$this->assertNotEmpty( $response['data']['themes'], 'Themes data must not be empty' );

		foreach ( $response['data']['themes'] as $theme ) {
			$this->assertIsArray( $theme, 'Theme is not an array' );
			$this->assertNotEmpty( $theme, 'Theme data must not be empty' );
			$this->assertArrayHasKey( 'id', $theme, 'Theme data must have an "id" key' );
			$this->assertNotEmpty( $theme['id'], 'Theme id cannot be empty' );

			$this->assertArrayHasKey( 'name', $theme, 'Theme data must have a "name" key' );
			$this->assertNotEmpty( $theme['name'], 'Theme name cannot be empty' );

			$this->assertArrayHasKey( 'blockTheme', $theme, 'Themes data must include information about blocks support' );
		}
	}
}
