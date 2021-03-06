<?php

if ( is_multisite() ) :

	/**
	 * @group multisite
	 */
	class Tests_Multisite_gcmuValidateUserSignup extends GC_UnitTestCase {
		/**
		 * @dataProvider data_user_name
		 */
		public function test_user_name( $user_name, $error_message ) {
			$v = gcmu_validate_user_signup( $user_name, 'foo@example.com' );
			$this->assertContains( 'user_name', $v['errors']->get_error_codes(), $error_message );
		}

		public function data_user_name() {
			return array(
				array( 'contains spaces', 'User names with spaces are not allowed.' ),
				array( 'ContainsCaps', 'User names with capital letters are not allowed.' ),
				array( 'contains_underscores', 'User names with underscores are not allowed.' ),
				array( 'contains%^*()junk', 'User names with non-alphanumeric characters are not allowed.' ),
				array( '', 'Empty user names are not allowed.' ),
				array( 'foo', 'User names of 3 characters are not allowed.' ),
				array( 'fo', 'User names of 2 characters are not allowed.' ),
				array( 'f', 'User names of 1 characters are not allowed.' ),
				array( 'f', 'User names of 1 characters are not allowed.' ),
				array( '12345', 'User names consisting only of numbers are not allowed.' ),
				array( 'thisusernamecontainsenoughcharacterstobelongerthan60characters', 'User names longer than 60 characters are not allowed.' ),
			);
		}

		public function test_should_fail_for_illegal_names() {
			$illegal = array( 'foo123', 'bar123' );
			update_site_option( 'illegal_names', $illegal );

			foreach ( $illegal as $i ) {
				$v = gcmu_validate_user_signup( $i, 'foo@example.com' );
				$this->assertContains( 'user_name', $v['errors']->get_error_codes() );
			}
		}

		public function test_should_fail_for_unsafe_email_address() {
			add_filter( 'is_email_address_unsafe', '__return_true' );
			$v = gcmu_validate_user_signup( 'foo123', 'foo@example.com' );
			$this->assertContains( 'user_email', $v['errors']->get_error_codes() );
			remove_filter( 'is_email_address_unsafe', '__return_true' );
		}

		public function test_should_fail_for_invalid_email_address() {
			add_filter( 'is_email', '__return_false' );
			$v = gcmu_validate_user_signup( 'foo123', 'foo@example.com' );
			$this->assertContains( 'user_email', $v['errors']->get_error_codes() );
			remove_filter( 'is_email', '__return_false' );
		}

		public function test_should_fail_for_emails_from_disallowed_domains() {
			$domains = array( 'foo.com', 'bar.org' );
			update_site_option( 'limited_email_domains', $domains );

			$v = gcmu_validate_user_signup( 'foo123', 'foo@example.com' );
			$this->assertContains( 'user_email', $v['errors']->get_error_codes() );
		}

		public function test_should_not_fail_for_emails_from_allowed_domains_with_mixed_case() {
			$domains = array( 'foo.com', 'bar.org' );
			update_site_option( 'limited_email_domains', $domains );

			$v = gcmu_validate_user_signup( 'foo123', 'foo@BAR.org' );
			$this->assertNotContains( 'user_email', $v['errors']->get_error_codes() );
		}

		public function test_should_fail_for_existing_user_name() {
			$u = self::factory()->user->create( array( 'user_login' => 'foo123' ) );
			$v = gcmu_validate_user_signup( 'foo123', 'foo@example.com' );
			$this->assertContains( 'user_name', $v['errors']->get_error_codes() );
		}

		public function test_should_fail_for_existing_user_email() {
			$u = self::factory()->user->create( array( 'user_email' => 'foo@example.com' ) );
			$v = gcmu_validate_user_signup( 'foo123', 'foo@example.com' );
			$this->assertContains( 'user_email', $v['errors']->get_error_codes() );
		}

		public function test_should_fail_for_existing_signup_with_same_username() {
			// Don't send notifications.
			add_filter( 'gcmu_signup_user_notification', '__return_false' );
			gcmu_signup_user( 'foo123', 'foo@example.com' );
			remove_filter( 'gcmu_signup_user_notification', '__return_false' );

			$v = gcmu_validate_user_signup( 'foo123', 'foo2@example.com' );
			$this->assertContains( 'user_name', $v['errors']->get_error_codes() );
		}

		public function test_should_not_fail_for_existing_signup_with_same_username_if_signup_is_old() {
			// Don't send notifications.
			add_filter( 'gcmu_signup_user_notification', '__return_false' );
			gcmu_signup_user( 'foo123', 'foo@example.com' );
			remove_filter( 'gcmu_signup_user_notification', '__return_false' );

			global $gcdb;
			$date = gmdate( 'Y-m-d H:i:s', time() - ( 2 * DAY_IN_SECONDS ) - 60 );
			$gcdb->update( $gcdb->signups, array( 'registered' => $date ), array( 'user_login' => 'foo123' ) );

			$v = gcmu_validate_user_signup( 'foo123', 'foo2@example.com' );
			$this->assertNotContains( 'user_name', $v['errors']->get_error_codes() );
		}

		public function test_should_fail_for_existing_signup_with_same_email() {
			// Don't send notifications.
			add_filter( 'gcmu_signup_user_notification', '__return_false' );
			gcmu_signup_user( 'foo123', 'foo@example.com' );
			remove_filter( 'gcmu_signup_user_notification', '__return_false' );

			$v = gcmu_validate_user_signup( 'foo2', 'foo@example.com' );
			$this->assertContains( 'user_email', $v['errors']->get_error_codes() );
		}

		public function test_should_not_fail_for_existing_signup_with_same_email_if_signup_is_old() {
			// Don't send notifications.
			add_filter( 'gcmu_signup_user_notification', '__return_false' );
			gcmu_signup_user( 'foo123', 'foo@example.com' );
			remove_filter( 'gcmu_signup_user_notification', '__return_false' );

			global $gcdb;
			$date = gmdate( 'Y-m-d H:i:s', time() - ( 2 * DAY_IN_SECONDS ) - 60 );
			$gcdb->update( $gcdb->signups, array( 'registered' => $date ), array( 'user_login' => 'foo123' ) );

			$v = gcmu_validate_user_signup( 'foo2', 'foo2@example.com' );
			$this->assertNotContains( 'user_email', $v['errors']->get_error_codes() );
		}

		/**
		 * @ticket 43232
		 */
		public function test_should_not_fail_for_data_used_by_a_deleted_user() {
			global $gcdb;

			// Don't send notifications.
			add_filter( 'gcmu_signup_user_notification', '__return_false' );
			add_filter( 'gcmu_welcome_user_notification', '__return_false' );

			// Signup, activate and delete new user.
			gcmu_signup_user( 'foo123', 'foo@example.com' );
			$key  = $gcdb->get_var( "SELECT activation_key FROM $gcdb->signups WHERE user_login = 'foo123'" );
			$user = gcmu_activate_signup( $key );
			gcmu_delete_user( $user['user_id'] );

			$valid = gcmu_validate_user_signup( 'foo123', 'foo2@example.com' );

			remove_filter( 'gcmu_signup_user_notification', '__return_false' );
			remove_filter( 'gcmu_signup_user_notification', '__return_false' );

			$this->assertNotContains( 'user_name', $valid['errors']->get_error_codes() );
			$this->assertNotContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		public function test_invalid_email_address_with_no_banned_domains_results_in_error() {
			$valid = gcmu_validate_user_signup( 'validusername', 'invalid-email' );

			$this->assertContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		public function test_invalid_email_address_with_banned_domains_results_in_error() {
			update_site_option( 'banned_email_domains', 'bar.com' );
			$valid = gcmu_validate_user_signup( 'validusername', 'invalid-email' );
			delete_site_option( 'banned_email_domains' );

			$this->assertContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		public function test_incomplete_email_address_with_no_banned_domains_results_in_error() {
			$valid = gcmu_validate_user_signup( 'validusername', 'incomplete@email' );

			$this->assertContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		public function test_valid_email_address_matching_banned_domain_results_in_error() {
			update_site_option( 'banned_email_domains', 'bar.com' );
			$valid = gcmu_validate_user_signup( 'validusername', 'email@bar.com' );
			delete_site_option( 'banned_email_domains' );

			$this->assertContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		public function test_valid_email_address_not_matching_banned_domain_returns_in_success() {
			update_site_option( 'banned_email_domains', 'bar.com' );
			$valid = gcmu_validate_user_signup( 'validusername', 'email@example.com' );
			delete_site_option( 'banned_email_domains' );

			$this->assertNotContains( 'user_email', $valid['errors']->get_error_codes() );
		}

		/**
		 * @ticket 43667
		 */
		public function test_signup_nonce_check() {
			$original_php_self       = $_SERVER['PHP_SELF'];
			$_SERVER['PHP_SELF']     = '/gc-signup.php';
			$_POST['signup_form_id'] = 'user-signup-form';
			$_POST['_signup_form']   = gc_create_nonce( 'signup_form_' . $_POST['signup_form_id'] );

			$valid               = gcmu_validate_user_signup( 'validusername', 'email@example.com' );
			$_SERVER['PHP_SELF'] = $original_php_self;

			$this->assertNotContains( 'invalid_nonce', $valid['errors']->get_error_codes() );
		}

		/**
		 * @ticket 43667
		 */
		public function test_signup_nonce_check_invalid() {
			$original_php_self       = $_SERVER['PHP_SELF'];
			$_SERVER['PHP_SELF']     = '/gc-signup.php';
			$_POST['signup_form_id'] = 'user-signup-form';
			$_POST['_signup_form']   = gc_create_nonce( 'invalid' );

			$valid               = gcmu_validate_user_signup( 'validusername', 'email@example.com' );
			$_SERVER['PHP_SELF'] = $original_php_self;

			$this->assertContains( 'invalid_nonce', $valid['errors']->get_error_codes() );
		}
	}

endif;
