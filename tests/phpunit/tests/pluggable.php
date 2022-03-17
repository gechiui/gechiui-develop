<?php

/**
 * @group pluggable
 */
class Tests_Pluggable extends GC_UnitTestCase {

	/**
	 * Tests that the signatures of all functions in pluggable.php match their expected signature.
	 *
	 * @ticket 33654
	 * @ticket 33867
	 *
	 * @dataProvider get_defined_pluggable_functions
	 */
	public function test_pluggable_function_signatures_match( $function ) {

		$signatures = $this->get_pluggable_function_signatures();

		$this->assertTrue( function_exists( $function ) );
		$this->assertArrayHasKey( $function, $signatures );

		$function_ref = new ReflectionFunction( $function );
		$param_refs   = $function_ref->getParameters();

		$this->assertSame( count( $signatures[ $function ] ), count( $param_refs ) );

		$i = 0;

		foreach ( $signatures[ $function ] as $name => $value ) {

			$param_ref = $param_refs[ $i ];
			$msg       = 'Parameter: ' . $param_ref->getName();

			if ( is_numeric( $name ) ) {
				$name = $value;
				$this->assertFalse( $param_ref->isOptional(), $msg );
			} else {
				$this->assertTrue( $param_ref->isOptional(), $msg );
				$this->assertSame( $value, $param_ref->getDefaultValue(), $msg );
			}

			$this->assertSame( $name, $param_ref->getName(), $msg );

			$i++;

		}

	}

	/**
	 * Test the tests. Makes sure all the expected pluggable functions exist and that they live in pluggable.php.
	 *
	 * @ticket 33654
	 * @ticket 33867
	 */
	public function test_all_pluggable_functions_exist() {

		$defined  = gc_list_pluck( $this->get_defined_pluggable_functions(), 0 );
		$expected = $this->get_pluggable_function_signatures();

		foreach ( $expected as $function => $sig ) {
			$msg = 'Function: ' . $function . '()';
			$this->assertTrue( function_exists( $function ), $msg );
			$this->assertContains( $function, $defined, $msg );
		}

	}

	/**
	 * Data provider for our pluggable function signature tests.
	 *
	 * @return array Data provider array of pluggable function names.
	 */
	public function get_defined_pluggable_functions() {

		require_once ABSPATH . '/gc-admin/includes/upgrade.php';

		$test_functions = array(
			'install_network',
			'gc_install',
			'gc_install_defaults',
			'gc_new_blog_notification',
			'gc_upgrade',
			'install_global_terms',
		);
		$test_files     = array(
			'gc-includes/pluggable.php',
		);

		// Pluggable function signatures are not tested when an external object cache is in use. See #31491.
		if ( ! gc_using_ext_object_cache() ) {
			$test_files[] = 'gc-includes/cache.php';
		}

		$data = array();

		foreach ( $test_functions as $function ) {
			$data[] = array(
				$function,
			);
		}

		foreach ( $test_files as $file ) {
			preg_match_all( '#^\t?function (\w+)#m', file_get_contents( ABSPATH . '/' . $file ), $functions );

			foreach ( $functions[1] as $function ) {
				$data[] = array(
					$function,
				);
			}
		}

		return $data;

	}

	/**
	 * Expected pluggable function signatures.
	 *
	 * @return array Array of signatures keyed by their function name.
	 */
	public function get_pluggable_function_signatures() {

		$signatures = array(

			// gc-includes/pluggable.php:
			'gc_set_current_user'             => array(
				'id',
				'name' => '',
			),
			'gc_get_current_user'             => array(),
			'get_userdata'                    => array( 'user_id' ),
			'get_user_by'                     => array( 'field', 'value' ),
			'cache_users'                     => array( 'user_ids' ),
			'gc_mail'                         => array(
				'to',
				'subject',
				'message',
				'headers'     => '',
				'attachments' => array(),
			),
			'gc_authenticate'                 => array( 'username', 'password' ),
			'gc_logout'                       => array(),
			'gc_validate_auth_cookie'         => array(
				'cookie' => '',
				'scheme' => '',
			),
			'gc_generate_auth_cookie'         => array(
				'user_id',
				'expiration',
				'scheme' => 'auth',
				'token'  => '',
			),
			'gc_parse_auth_cookie'            => array(
				'cookie' => '',
				'scheme' => '',
			),
			'gc_set_auth_cookie'              => array(
				'user_id',
				'remember' => false,
				'secure'   => '',
				'token'    => '',
			),
			'gc_clear_auth_cookie'            => array(),
			'is_user_logged_in'               => array(),
			'auth_redirect'                   => array(),
			'check_admin_referer'             => array(
				'action'    => -1,
				'query_arg' => '_gcnonce',
			),
			'check_ajax_referer'              => array(
				'action'    => -1,
				'query_arg' => false,
				'die'       => true,
			),
			'gc_redirect'                     => array(
				'location',
				'status'        => 302,
				'x_redirect_by' => 'GeChiUI',
			),
			'gc_sanitize_redirect'            => array( 'location' ),
			'_gc_sanitize_utf8_in_redirect'   => array( 'matches' ),
			'gc_safe_redirect'                => array(
				'location',
				'status'        => 302,
				'x_redirect_by' => 'GeChiUI',
			),
			'gc_validate_redirect'            => array(
				'location',
				'default' => '',
			),
			'gc_notify_postauthor'            => array(
				'comment_id',
				'deprecated' => null,
			),
			'gc_notify_moderator'             => array( 'comment_id' ),
			'gc_password_change_notification' => array( 'user' ),
			'gc_new_user_notification'        => array(
				'user_id',
				'deprecated' => null,
				'notify'     => '',
			),
			'gc_nonce_tick'                   => array(),
			'gc_verify_nonce'                 => array(
				'nonce',
				'action' => -1,
			),
			'gc_create_nonce'                 => array( 'action' => -1 ),
			'gc_salt'                         => array( 'scheme' => 'auth' ),
			'gc_hash'                         => array(
				'data',
				'scheme' => 'auth',
			),
			'gc_hash_password'                => array( 'password' ),
			'gc_check_password'               => array(
				'password',
				'hash',
				'user_id' => '',
			),
			'gc_generate_password'            => array(
				'length'              => 12,
				'special_chars'       => true,
				'extra_special_chars' => false,
			),
			'gc_rand'                         => array(
				'min' => 0,
				'max' => 0,
			),
			'gc_set_password'                 => array( 'password', 'user_id' ),
			'get_avatar'                      => array(
				'id_or_email',
				'size'    => 96,
				'default' => '',
				'alt'     => '',
				'args'    => null,
			),
			'gc_text_diff'                    => array(
				'left_string',
				'right_string',
				'args' => null,
			),

			// gc-admin/includes/schema.php:
			'install_network'                 => array(),

			// gc-admin/includes/upgrade.php:
			'gc_install'                      => array(
				'blog_title',
				'user_name',
				'user_email',
				'public',
				'deprecated'    => '',
				'user_password' => '',
				'language'      => '',
			),
			'gc_install_defaults'             => array( 'user_id' ),
			'gc_new_blog_notification'        => array( 'blog_title', 'blog_url', 'user_id', 'password' ),
			'gc_upgrade'                      => array(),
			'install_global_terms'            => array(),
		);

		// Pluggable function signatures are not tested when an external object cache is in use. See #31491.
		if ( ! gc_using_ext_object_cache() ) {
			$signatures = array_merge(
				$signatures,
				array(

					// gc-includes/cache.php:
					'gc_cache_add'                       => array(
						'key',
						'data',
						'group'  => '',
						'expire' => 0,
					),
					'gc_cache_close'                     => array(),
					'gc_cache_decr'                      => array(
						'key',
						'offset' => 1,
						'group'  => '',
					),
					'gc_cache_delete'                    => array(
						'key',
						'group' => '',
					),
					'gc_cache_flush'                     => array(),
					'gc_cache_get'                       => array(
						'key',
						'group' => '',
						'force' => false,
						'found' => null,
					),
					'gc_cache_get_multiple'              => array(
						'keys',
						'group' => '',
						'force' => false,
					),
					'gc_cache_incr'                      => array(
						'key',
						'offset' => 1,
						'group'  => '',
					),
					'gc_cache_init'                      => array(),
					'gc_cache_replace'                   => array(
						'key',
						'data',
						'group'  => '',
						'expire' => 0,
					),
					'gc_cache_set'                       => array(
						'key',
						'data',
						'group'  => '',
						'expire' => 0,
					),
					'gc_cache_switch_to_blog'            => array( 'blog_id' ),
					'gc_cache_add_global_groups'         => array( 'groups' ),
					'gc_cache_add_non_persistent_groups' => array( 'groups' ),
					'gc_cache_reset'                     => array(),
				)
			);
		}

		return $signatures;
	}

	/**
	 * @ticket 28020
	 */
	public function test_get_user_by_should_return_same_instance_as_gc_get_current_user() {
		// Create a test user.
		$new_user = self::factory()->user->create( array( 'role' => 'subscriber' ) );

		// Set the test user as the current user.
		$current_user = gc_set_current_user( $new_user );

		// Get the test user using get_user_by().
		$from_get_user_by = get_user_by( 'id', $new_user );

		$this->assertSame( $current_user, $from_get_user_by );
	}
}
