<?php

/**
 * @group user
 */
class Tests_User_GcAuthenticateSpamCheck extends GC_UnitTestCase {

	/**
	 * @group ms-excluded
	 */
	public function test_gc_authenticate_spam_check_returns_user_when_single_site() {
		$user_id     = self::factory()->user->create( array( 'role' => 'contributor' ) );
		$user        = new GC_User( $user_id );
		$actual_user = gc_authenticate_spam_check( $user );
		gc_delete_user( $user_id );

		$this->assertSame( $user->user_login, $actual_user->user_login );
	}

	/**
	 * @group ms-required
	 */
	public function test_gc_authenticate_spam_check_returns_user_when_not_flagged() {
		$user_id     = self::factory()->user->create( array( 'role' => 'contributor' ) );
		$user        = new GC_User( $user_id );
		$actual_user = gc_authenticate_spam_check( $user );
		gcmu_delete_user( $user_id );

		$this->assertSame( $user->user_login, $actual_user->user_login );
	}

	/**
	 * @group ms-required
	 */
	public function test_gc_authenticate_spam_check_returns_gc_error_when_flagged() {
		$user_id = self::factory()->user->create( array( 'role' => 'contributor' ) );
		gc_update_user(
			array(
				'ID'   => $user_id,
				'spam' => '1',
			)
		);

		$user        = new GC_User( $user_id );
		$actual_user = gc_authenticate_spam_check( $user );
		gcmu_delete_user( $user_id );

		$this->assertInstanceOf( 'GC_Error', $actual_user );
	}
}
