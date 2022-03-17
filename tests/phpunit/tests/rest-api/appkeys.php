<?php
/**
 * Unit tests covering GC_AppKeys functionality.
 *
 * @package    GeChiUI
 * @subpackage REST API
 */

/**
 * @group  restapi
 * @group  app_password
 */
class Test_GC_AppKeys extends GC_UnitTestCase {

	/**
	 * Administrator user id.
	 *
	 * @var int
	 */
	private static $user_id;

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$user_id = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);

		if ( is_multisite() ) {
			grant_super_admin( self::$user_id );
		}
	}


	/**
	 * @covers       GC_AppKeys::create_new_appkey
	 * @ticket       51941
	 * @dataProvider data_create_new_appkey_validation
	 */
	public function test_create_new_appkey_validation( $expected, array $args = array(), array $names = array() ) {
		// Create the existing passwords.
		foreach ( $names as $name ) {
			GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => $name ) );
		}

		$actual = GC_AppKeys::create_new_appkey( self::$user_id, $args );

		$this->assertInstanceOf( GC_Error::class, $actual );
		$this->assertSame( $expected['error_code'], $actual->get_error_code() );
		$this->assertSame( $expected['error_message'], $actual->get_error_message( $expected['error_code'] ) );
	}

	public function data_create_new_appkey_validation() {
		return array(
			'appkey_empty_name when no args' => array(
				'expected' => array(
					'error_code'    => 'appkey_empty_name',
					'error_message' => 'An application name is required to create an appkey.',
				),
			),
			'appkey_empty_name when no name' => array(
				'expected' => array(
					'error_code'    => 'appkey_empty_name',
					'error_message' => 'An application name is required to create an appkey.',
				),
				'args'     => array( 'app_id' => 1 ),
			),
			'appkey_empty_name when empty name' => array(
				'expected' => array(
					'error_code'    => 'appkey_empty_name',
					'error_message' => 'An application name is required to create an appkey.',
				),
				'args'     => array( 'name' => '   ' ),
			),
			'appkey_empty_name when <script>' => array(
				'expected' => array(
					'error_code'    => 'appkey_empty_name',
					'error_message' => 'An application name is required to create an appkey.',
				),
				'args'     => array( 'name' => '<script>console.log("Hello")</script>' ),
			),
			'appkey_duplicate_name when name exists' => array(
				'expected' => array(
					'error_code'    => 'appkey_duplicate_name',
					'error_message' => 'Each application name should be unique.',
				),
				'args'     => array( 'name' => 'test2' ),
				'names'    => array( 'test1', 'test2' ),
			),
		);
	}

	/**
	 * @covers       GC_AppKeys::create_new_appkey
	 * @ticket       51941
	 * @dataProvider data_create_new_appkey
	 */
	public function test_create_new_appkey( array $args, array $names = array() ) {
		// Create the existing passwords.
		foreach ( $names as $name ) {
			GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => $name ) );
		}

		list( $new_password, $new_item ) = GC_AppKeys::create_new_appkey( self::$user_id, $args );

		$this->assertNotEmpty( $new_password );
		$this->assertSame(
			array( 'uuid', 'app_id', 'name', 'password', 'created', 'last_used', 'last_ip' ),
			array_keys( $new_item )
		);
		$this->assertSame( $args['name'], $new_item['name'] );
	}

	public function data_create_new_appkey() {
		return array(
			'should create new password when no passwords exists' => array(
				'args' => array( 'name' => 'test3' ),
			),
			'should create new password when name is unique'      => array(
				'args'  => array( 'name' => 'test3' ),
				'names' => array( 'test1', 'test2' ),
			),
		);
	}

	/**
	 * @covers       GC_AppKeys::application_name_exists_for_user
	 * @ticket       51941
	 * @dataProvider data_application_name_exists_for_user
	 */
	public function test_application_name_exists_for_user( $expected, $name ) {
		if ( $expected ) {
			GC_AppKeys::create_new_appkey( self::$user_id, array( 'name' => $name ) );
		}

		$this->assertSame( $expected, GC_AppKeys::application_name_exists_for_user( self::$user_id, $name ) );
	}

	public function data_application_name_exists_for_user() {
		return array(
			array( false, 'test1' ),
			array( false, 'baz' ),
			array( false, 'bar' ),
			array( true, 'App 1' ),
			array( true, 'Some Test' ),
			array( true, 'Baz' ),
		);
	}

	/**
	 * @covers       GC_AppKeys::update_appkey
	 * @ticket       51941
	 * @dataProvider data_update_appkey
	 */
	public function test_update_appkey( array $update, array $existing ) {
		// Create the original item.
		list( , $original_item ) = GC_AppKeys::create_new_appkey( self::$user_id, $existing );
		$uuid                    = $original_item['uuid'];

		$actual = GC_AppKeys::update_appkey( self::$user_id, $uuid, $update );

		$this->assertTrue( $actual );

		// Check updated only given values.
		$updated_item = GC_AppKeys::get_user_appkey( self::$user_id, $uuid );
		foreach ( $updated_item as $key => $update_value ) {
			$expected_value = isset( $update[ $key ] ) ? $update[ $key ] : $original_item[ $key ];
			$this->assertSame( $expected_value, $update_value );
		}
	}

	/**
	 * @covers       GC_AppKeys::update_appkey
	 * @ticket       51941
	 * @dataProvider data_update_appkey
	 */
	public function test_update_appkey_when_no_password_found( array $update ) {
		$actual = GC_AppKeys::update_appkey( self::$user_id, '', $update );

		$this->assertInstanceOf( GC_Error::class, $actual );
		$this->assertSame( 'appkey_not_found', $actual->get_error_code() );
		$this->assertSame( 'Could not find an appkey with that id.', $actual->get_error_message( 'appkey_not_found' ) );
	}

	public function data_update_appkey() {
		return array(
			'should not update when no values given to update' => array(
				'update'   => array(),
				'existing' => array( 'name' => 'Test' ),
			),
			'should not update when given same name' => array(
				'update'   => array( 'name' => 'Test' ),
				'existing' => array( 'name' => 'Test' ),
			),
			'should update name'                     => array(
				'update'   => array( 'name' => 'Test Updated' ),
				'existing' => array( 'name' => 'Test' ),
			),
		);
	}
}
