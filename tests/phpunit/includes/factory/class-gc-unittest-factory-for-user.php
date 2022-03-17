<?php

/**
 * Unit test factory for users.
 *
 * Note: The below @method notations are defined solely for the benefit of IDEs,
 * as a way to indicate expected return values from the given factory methods.
 *
 * @method int create( $args = array(), $generation_definitions = null )
 * @method GC_User create_and_get( $args = array(), $generation_definitions = null )
 * @method int[] create_many( $count, $args = array(), $generation_definitions = null )
 */
class GC_UnitTest_Factory_For_User extends GC_UnitTest_Factory_For_Thing {

	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'user_login' => new GC_UnitTest_Generator_Sequence( 'User %s' ),
			'user_pass'  => 'password',
			'user_email' => new GC_UnitTest_Generator_Sequence( 'user_%s@example.org' ),
		);
	}

	/**
	 * Inserts an user.
	 *
	 * @param array $args The user data to insert.
	 *
	 * @return int|GC_Error The user ID on success, GC_Error object on failure.
	 */
	public function create_object( $args ) {
		return gc_insert_user( $args );
	}

	/**
	 * Updates the user data.
	 *
	 * @param int   $user_id ID of the user to update.
	 * @param array $fields  The user data to update.
	 *
	 * @return int|GC_Error The user ID on success, GC_Error object on failure.
	 */
	public function update_object( $user_id, $fields ) {
		$fields['ID'] = $user_id;
		return gc_update_user( $fields );
	}

	/**
	 * Retrieves the user for a given ID.
	 *
	 * @param int $user_id ID of the user ID to retrieve.
	 *
	 * @return GC_User The user object.
	 */
	public function get_object_by_id( $user_id ) {
		return new GC_User( $user_id );
	}
}
