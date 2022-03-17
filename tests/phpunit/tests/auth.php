<?php

/**
 * @group pluggable
 * @group auth
 */
class Tests_Auth extends GC_UnitTestCase {
	protected $user;

	/**
	 * @var GC_User
	 */
	protected static $_user;
	protected static $user_id;
	protected static $gc_hasher;

	/**
	 * Action hook.
	 */
	protected $nonce_failure_hook = 'gc_verify_nonce_failed';

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$_user = $factory->user->create_and_get(
			array(
				'user_login' => 'password-tests',
			)
		);

		self::$user_id = self::$_user->ID;

		require_once ABSPATH . GCINC . '/class-phpass.php';
		self::$gc_hasher = new PasswordHash( 8, true );
	}

	public function set_up() {
		parent::set_up();

		$this->user = clone self::$_user;
		gc_set_current_user( self::$user_id );
		update_site_option( 'using_appkeys', 1 );

		unset( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $GLOBALS['gc_rest_appkey_status'], $GLOBALS['gc_rest_appkey_uuid'] );
	}

	public function tear_down() {
		// Cleanup all the global state.
		unset( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $GLOBALS['gc_rest_appkey_status'], $GLOBALS['gc_rest_appkey_uuid'] );

		parent::tear_down();
	}

	public function test_auth_cookie_valid() {
		$cookie = gc_generate_auth_cookie( self::$user_id, time() + 3600, 'auth' );
		$this->assertSame( self::$user_id, gc_validate_auth_cookie( $cookie, 'auth' ) );
	}

	public function test_auth_cookie_invalid() {
		// 3600 or less and +3600 may occur in gc_validate_auth_cookie(),
		// as an ajax test may have defined DOING_AJAX, failing the test.

		$cookie = gc_generate_auth_cookie( self::$user_id, time() - 7200, 'auth' );
		$this->assertFalse( gc_validate_auth_cookie( $cookie, 'auth' ), 'expired cookie' );

		$cookie = gc_generate_auth_cookie( self::$user_id, time() + 3600, 'auth' );
		$this->assertFalse( gc_validate_auth_cookie( $cookie, 'logged_in' ), 'wrong auth scheme' );

		$cookie          = gc_generate_auth_cookie( self::$user_id, time() + 3600, 'auth' );
		list($a, $b, $c) = explode( '|', $cookie );
		$cookie          = $a . '|' . ( $b + 1 ) . '|' . $c;
		$this->assertFalse( gc_validate_auth_cookie( self::$user_id, 'auth' ), 'altered cookie' );
	}

	public function test_auth_cookie_scheme() {
		// Arbitrary scheme name.
		$cookie = gc_generate_auth_cookie( self::$user_id, time() + 3600, 'foo' );
		$this->assertSame( self::$user_id, gc_validate_auth_cookie( $cookie, 'foo' ) );

		// Wrong scheme name - should fail.
		$cookie = gc_generate_auth_cookie( self::$user_id, time() + 3600, 'foo' );
		$this->assertFalse( gc_validate_auth_cookie( $cookie, 'bar' ) );
	}

	/**
	 * @ticket 23494
	 */
	public function test_password_trimming() {
		$passwords_to_test = array(
			'a password with no trailing or leading spaces',
			'a password with trailing spaces ',
			' a password with leading spaces',
			' a password with trailing and leading spaces ',
		);

		foreach ( $passwords_to_test as $password_to_test ) {
			gc_set_password( $password_to_test, $this->user->ID );
			$authed_user = gc_authenticate( $this->user->user_login, $password_to_test );

			$this->assertInstanceOf( 'GC_User', $authed_user );
			$this->assertSame( $this->user->ID, $authed_user->ID );
		}
	}

	/**
	 * Test gc_hash_password trims whitespace
	 *
	 * This is similar to test_password_trimming but tests the "lower level"
	 * gc_hash_password function
	 *
	 * @ticket 24973
	 */
	public function test_gc_hash_password_trimming() {

		$password = ' pass with leading whitespace';
		$this->assertTrue( gc_check_password( 'pass with leading whitespace', gc_hash_password( $password ) ) );

		$password = 'pass with trailing whitespace ';
		$this->assertTrue( gc_check_password( 'pass with trailing whitespace', gc_hash_password( $password ) ) );

		$password = ' pass with whitespace ';
		$this->assertTrue( gc_check_password( 'pass with whitespace', gc_hash_password( $password ) ) );

		$password = "pass with new line \n";
		$this->assertTrue( gc_check_password( 'pass with new line', gc_hash_password( $password ) ) );

		$password = "pass with vertial tab o_O\x0B";
		$this->assertTrue( gc_check_password( 'pass with vertial tab o_O', gc_hash_password( $password ) ) );
	}

	/**
	 * @ticket 29217
	 */
	public function test_gc_verify_nonce_with_empty_arg() {
		$this->assertFalse( gc_verify_nonce( '' ) );
		$this->assertFalse( gc_verify_nonce( null ) );
	}

	/**
	 * @ticket 29542
	 */
	public function test_gc_verify_nonce_with_integer_arg() {
		$this->assertFalse( gc_verify_nonce( 1 ) );
	}

	/**
	 * @ticket 24030
	 */
	public function test_gc_nonce_verify_failed() {
		$nonce = substr( md5( uniqid() ), 0, 10 );
		$count = did_action( $this->nonce_failure_hook );

		gc_verify_nonce( $nonce, 'nonce_test_action' );

		$this->assertSame( ( $count + 1 ), did_action( $this->nonce_failure_hook ) );
	}

	/**
	 * @ticket 24030
	 */
	public function test_gc_nonce_verify_success() {
		$nonce = gc_create_nonce( 'nonce_test_action' );
		$count = did_action( $this->nonce_failure_hook );

		gc_verify_nonce( $nonce, 'nonce_test_action' );

		$this->assertSame( $count, did_action( $this->nonce_failure_hook ) );
	}

	/**
	 * @ticket 36361
	 */
	public function test_check_admin_referer_with_no_action_triggers_doing_it_wrong() {
		$this->setExpectedIncorrectUsage( 'check_admin_referer' );

		// A valid nonce needs to be set so the check doesn't die().
		$_REQUEST['_gcnonce'] = gc_create_nonce( -1 );
		$result               = check_admin_referer();
		$this->assertSame( 1, $result );

		unset( $_REQUEST['_gcnonce'] );
	}

	public function test_check_admin_referer_with_default_action_as_string_not_doing_it_wrong() {
		// A valid nonce needs to be set so the check doesn't die().
		$_REQUEST['_gcnonce'] = gc_create_nonce( '-1' );
		$result               = check_admin_referer( '-1' );
		$this->assertSame( 1, $result );

		unset( $_REQUEST['_gcnonce'] );
	}

	/**
	 * @ticket 36361
	 */
	public function test_check_ajax_referer_with_no_action_triggers_doing_it_wrong() {
		$this->setExpectedIncorrectUsage( 'check_ajax_referer' );

		// A valid nonce needs to be set so the check doesn't die().
		$_REQUEST['_gcnonce'] = gc_create_nonce( -1 );
		$result               = check_ajax_referer();
		$this->assertSame( 1, $result );

		unset( $_REQUEST['_gcnonce'] );
	}

	public function test_password_length_limit() {
		$limit = str_repeat( 'a', 4096 );

		gc_set_password( $limit, self::$user_id );
		// phpass hashed password.
		$this->assertStringStartsWith( '$P$', $this->user->data->user_pass );

		$user = gc_authenticate( $this->user->user_login, 'aaaaaaaa' );
		// Wrong password.
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, $limit );
		$this->assertInstanceOf( 'GC_User', $user );
		$this->assertSame( self::$user_id, $user->ID );

		// One char too many.
		$user = gc_authenticate( $this->user->user_login, $limit . 'a' );
		// Wrong password.
		$this->assertInstanceOf( 'GC_Error', $user );

		gc_set_password( $limit . 'a', self::$user_id );
		$user = get_user_by( 'id', self::$user_id );
		// Password broken by setting it to be too long.
		$this->assertSame( '*', $user->data->user_pass );

		$user = gc_authenticate( $this->user->user_login, '*' );
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, '*0' );
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, '*1' );
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, 'aaaaaaaa' );
		// Wrong password.
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, $limit );
		// Wrong password.
		$this->assertInstanceOf( 'GC_Error', $user );

		$user = gc_authenticate( $this->user->user_login, $limit . 'a' );
		// Password broken by setting it to be too long.
		$this->assertInstanceOf( 'GC_Error', $user );
	}

	/**
	 * @ticket 45746
	 */
	public function test_user_activation_key_is_saved() {
		$user = get_userdata( $this->user->ID );
		$key  = get_password_reset_key( $user );

		// A correctly saved key should be accepted.
		$check = check_password_reset_key( $key, $this->user->user_login );
		$this->assertNotWPError( $check );
		$this->assertInstanceOf( 'GC_User', $check );
		$this->assertSame( $this->user->ID, $check->ID );
	}

	/**
	 * @ticket 32429
	 */
	public function test_user_activation_key_is_checked() {
		global $gcdb;

		$key = gc_generate_password( 20, false );
		$gcdb->update(
			$gcdb->users,
			array(
				'user_activation_key' => strtotime( '-1 hour' ) . ':' . self::$gc_hasher->HashPassword( $key ),
			),
			array(
				'ID' => $this->user->ID,
			)
		);
		clean_user_cache( $this->user );

		// A valid key should be accepted.
		$check = check_password_reset_key( $key, $this->user->user_login );
		$this->assertNotWPError( $check );
		$this->assertInstanceOf( 'GC_User', $check );
		$this->assertSame( $this->user->ID, $check->ID );

		// An invalid key should be rejected.
		$check = check_password_reset_key( 'key', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );

		// An empty key should be rejected.
		$check = check_password_reset_key( '', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );

		// A truncated key should be rejected.
		$partial = substr( $key, 0, 10 );
		$check   = check_password_reset_key( $partial, $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );
	}

	/**
	 * @ticket 32429
	 */
	public function test_expired_user_activation_key_is_rejected() {
		global $gcdb;

		$key = gc_generate_password( 20, false );
		$gcdb->update(
			$gcdb->users,
			array(
				'user_activation_key' => strtotime( '-48 hours' ) . ':' . self::$gc_hasher->HashPassword( $key ),
			),
			array(
				'ID' => $this->user->ID,
			)
		);
		clean_user_cache( $this->user );

		// An expired but otherwise valid key should be rejected.
		$check = check_password_reset_key( $key, $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );
	}

	/**
	 * @ticket 32429
	 */
	public function test_empty_user_activation_key_fails_key_check() {
		// An empty user_activation_key should not allow any key to be accepted.
		$check = check_password_reset_key( 'key', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );

		// An empty user_activation_key should not allow an empty key to be accepted.
		$check = check_password_reset_key( '', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );
	}

	/**
	 * @ticket 32429
	 */
	public function test_legacy_user_activation_key_is_rejected() {
		global $gcdb;

		// A legacy user_activation_key is one without the `time()` prefix introduced in GeChiUI 4.3.

		$key = gc_generate_password( 20, false );
		$gcdb->update(
			$gcdb->users,
			array(
				'user_activation_key' => self::$gc_hasher->HashPassword( $key ),
			),
			array(
				'ID' => $this->user->ID,
			)
		);
		clean_user_cache( $this->user );

		// A legacy user_activation_key should not be accepted.
		$check = check_password_reset_key( $key, $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );

		// An empty key with a legacy user_activation_key should be rejected.
		$check = check_password_reset_key( '', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );
	}

	/**
	 * @ticket 32429
	 * @ticket 24783
	 */
	public function test_plaintext_user_activation_key_is_rejected() {
		global $gcdb;

		// A plaintext user_activation_key is one stored before hashing was introduced in GeChiUI 3.7.

		$key = gc_generate_password( 20, false );
		$gcdb->update(
			$gcdb->users,
			array(
				'user_activation_key' => $key,
			),
			array(
				'ID' => $this->user->ID,
			)
		);
		clean_user_cache( $this->user );

		// A plaintext user_activation_key should not allow an otherwise valid key to be accepted.
		$check = check_password_reset_key( $key, $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );

		// A plaintext user_activation_key should not allow an empty key to be accepted.
		$check = check_password_reset_key( '', $this->user->user_login );
		$this->assertInstanceOf( 'GC_Error', $check );
	}

	/**
	 * Ensure users can log in using both their username and their email address.
	 *
	 * @ticket 9568
	 */
	public function test_log_in_using_email() {
		$user_args = array(
			'user_login' => 'johndoe',
			'user_email' => 'mail@example.com',
			'user_pass'  => 'password',
		);
		$this->factory->user->create( $user_args );

		$this->assertInstanceOf( 'GC_User', gc_authenticate( $user_args['user_email'], $user_args['user_pass'] ) );
		$this->assertInstanceOf( 'GC_User', gc_authenticate( $user_args['user_login'], $user_args['user_pass'] ) );
	}

	/**
	 * @ticket 38744
	 */
	public function test_gc_signon_using_email_with_an_apostrophe() {
		$user_args = array(
			'user_email' => "mail\'@example.com",
			'user_pass'  => 'password',
		);
		$this->factory()->user->create( $user_args );

		$_POST['log'] = $user_args['user_email'];
		$_POST['pwd'] = $user_args['user_pass'];
		$this->assertInstanceOf( 'GC_User', gc_signon() );
	}

	/**
	 * HTTP Auth headers are used to determine the current user.
	 *
	 * @ticket 42790
	 *
	 * @covers ::gc_validate_appkey
	 */
	public function test_appkey_authentication() {
		$user_id = $this->factory()->user->create(
			array(
				'user_login' => 'http_auth_login',
				'user_pass'  => 'http_auth_pass', // Shouldn't be allowed for API login.
			)
		);

		// Create a new app-only password.
		list( $user_app_password, $item ) = GC_AppKeys::create_new_appkey( $user_id, array( 'name' => 'phpunit' ) );

		// Fake a REST API request.
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		// Fake an HTTP Auth request with the regular account password first.
		$_SERVER['PHP_AUTH_USER'] = 'http_auth_login';
		$_SERVER['PHP_AUTH_PW']   = 'http_auth_pass';

		$this->assertNull(
			gc_validate_appkey( null ),
			'Regular user account password should not be allowed for API authentication'
		);
		$this->assertNull( rest_get_authenticated_app_password() );

		// Not try with an App password instead.
		$_SERVER['PHP_AUTH_PW'] = $user_app_password;

		$this->assertSame(
			$user_id,
			gc_validate_appkey( null ),
			'Appkeys should be allowed for API authentication'
		);
		$this->assertSame( $item['uuid'], rest_get_authenticated_app_password() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_respects_existing_user() {
		$this->assertSame( self::$_user, gc_authenticate_appkey( self::$_user, self::$_user->user_login, 'password' ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_is_rejected_if_not_api_request() {
		add_filter( 'appkey_is_api_request', '__return_false' );

		$this->assertNull( gc_authenticate_appkey( null, self::$_user->user_login, 'password' ) );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_invalid_username() {
		add_filter( 'appkey_is_api_request', '__return_true' );

		$error = gc_authenticate_appkey( null, 'idonotexist', 'password' );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_username', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_invalid_email() {
		add_filter( 'appkey_is_api_request', '__return_true' );

		$error = gc_authenticate_appkey( null, 'idonotexist@example.org', 'password' );
		$this->assertWPError( $error );
		$this->assertSame( 'invalid_email', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_not_allowed() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_false' );

		$error = gc_authenticate_appkey( null, self::$_user->user_login, 'password' );
		$this->assertWPError( $error );
		$this->assertSame( 'appkeys_disabled', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_not_allowed_for_user() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );
		add_filter( 'gc_is_appkeys_available_for_user', '__return_false' );

		$error = gc_authenticate_appkey( null, self::$_user->user_login, 'password' );
		$this->assertWPError( $error );
		$this->assertSame( 'appkeys_disabled_for_user', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_incorrect_password() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		$error = gc_authenticate_appkey( null, self::$_user->user_login, 'password' );
		$this->assertWPError( $error );
		$this->assertSame( 'incorrect_password', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_custom_errors() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		add_action(
			'gc_authenticate_appkey_errors',
			static function ( GC_Error $error ) {
				$error->add( 'my_code', 'My Error' );
			}
		);

		list( $password ) = GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => 'phpunit' ) );

		$error = gc_authenticate_appkey( null, self::$_user->user_login, $password );
		$this->assertWPError( $error );
		$this->assertSame( 'my_code', $error->get_error_code() );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_by_username() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		list( $password ) = GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => 'phpunit' ) );

		$user = gc_authenticate_appkey( null, self::$_user->user_login, $password );
		$this->assertInstanceOf( GC_User::class, $user );
		$this->assertSame( self::$user_id, $user->ID );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_by_email() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		list( $password ) = GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => 'phpunit' ) );

		$user = gc_authenticate_appkey( null, self::$_user->user_email, $password );
		$this->assertInstanceOf( GC_User::class, $user );
		$this->assertSame( self::$user_id, $user->ID );
	}

	/**
	 * @ticket 42790
	 */
	public function test_authenticate_appkey_chunked() {
		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		list( $password ) = GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => 'phpunit' ) );

		$user = gc_authenticate_appkey( null, self::$_user->user_email, GC_AppKeys::chunk_password( $password ) );
		$this->assertInstanceOf( GC_User::class, $user );
		$this->assertSame( self::$user_id, $user->ID );
	}

	/**
	 * @ticket 51939
	 */
	public function test_authenticate_appkey_returns_null_if_not_in_use() {
		delete_site_option( 'using_appkeys' );

		$authenticated = gc_authenticate_appkey( null, 'idonotexist', 'password' );
		$this->assertNull( $authenticated );
	}

	/**
	 * @ticket 52003
	 *
	 * @covers ::gc_validate_appkey
	 */
	public function test_appkeys_does_not_attempt_auth_if_missing_password() {
		GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => 'phpunit' ) );

		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );

		$_SERVER['PHP_AUTH_USER'] = self::$_user->user_login;
		unset( $_SERVER['PHP_AUTH_PW'] );

		$this->assertNull( gc_validate_appkey( null ) );
	}

	/**
	 * @ticket 53386
	 * @dataProvider data_appkeys_can_use_capability_checks_to_determine_feature_availability
	 */
	public function test_appkeys_can_use_capability_checks_to_determine_feature_availability( $role, $authenticated ) {
		$user = self::factory()->user->create_and_get( array( 'role' => $role ) );

		list( $password ) = GC_AppKeys::create_new_appkey( $user->ID, array( 'name' => 'phpunit' ) );

		add_filter( 'appkey_is_api_request', '__return_true' );
		add_filter( 'gc_is_appkeys_available', '__return_true' );
		add_filter(
			'gc_is_appkeys_available_for_user',
			static function ( $available, GC_User $user ) {
				return user_can( $user, 'edit_posts' );
			},
			10,
			2
		);

		$_SERVER['PHP_AUTH_USER'] = $user->user_login;
		$_SERVER['PHP_AUTH_PW']   = $password;

		unset( $GLOBALS['current_user'] );
		$current = get_current_user_id();

		if ( $authenticated ) {
			$this->assertSame( $user->ID, $current );
		} else {
			$this->assertSame( 0, $current );
		}
	}

	public function data_appkeys_can_use_capability_checks_to_determine_feature_availability() {
		return array(
			'allowed'     => array( 'editor', true ),
			'not allowed' => array( 'subscriber', false ),
		);
	}
}
