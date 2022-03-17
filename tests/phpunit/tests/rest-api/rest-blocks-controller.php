<?php
/**
 * GC_REST_Blocks_Controller tests
 *
 * @package GeChiUI
 * @subpackage REST_API
 * @since 5.0.0
 */

/**
 * Tests for GC_REST_Blocks_Controller.
 *
 * @since 5.0.0
 *
 * @see GC_Test_REST_Controller_Testcase
 *
 * @group restapi-blocks
 * @group restapi
 */
class REST_Blocks_Controller_Test extends GC_UnitTestCase {

	/**
	 * Our fake block's post ID.
	 *
	 * @since 5.0.0
	 *
	 * @var int
	 */
	protected static $post_id;

	/**
	 * Our fake user IDs, keyed by their role.
	 *
	 * @since 5.0.0
	 *
	 * @var array
	 */
	protected static $user_ids;

	/**
	 * Create fake data before our tests run.
	 *
	 * @since 5.0.0
	 *
	 * @param GC_UnitTest_Factory $factory Helper that lets us create fake data.
	 */
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$post_id = gc_insert_post(
			array(
				'post_type'    => 'gc_block',
				'post_status'  => 'publish',
				'post_title'   => 'My cool block',
				'post_content' => '<!-- gc:paragraph --><p>Hello!</p><!-- /gc:paragraph -->',
			)
		);

		self::$user_ids = array(
			'editor'      => $factory->user->create( array( 'role' => 'editor' ) ),
			'author'      => $factory->user->create( array( 'role' => 'author' ) ),
			'contributor' => $factory->user->create( array( 'role' => 'contributor' ) ),
		);
	}

	/**
	 * Delete our fake data after our tests run.
	 *
	 * @since 5.0.0
	 */
	public static function gcTearDownAfterClass() {
		gc_delete_post( self::$post_id );

		foreach ( self::$user_ids as $user_id ) {
			self::delete_user( $user_id );
		}
	}

	/**
	 * Test cases for test_capabilities().
	 *
	 * @since 5.0.0
	 */
	public function data_capabilities() {
		return array(
			array( 'create', 'editor', 201 ),
			array( 'create', 'author', 201 ),
			array( 'create', 'contributor', 403 ),
			array( 'create', null, 401 ),

			array( 'read', 'editor', 200 ),
			array( 'read', 'author', 200 ),
			array( 'read', 'contributor', 200 ),
			array( 'read', null, 401 ),

			array( 'update_delete_own', 'editor', 200 ),
			array( 'update_delete_own', 'author', 200 ),
			array( 'update_delete_own', 'contributor', 403 ),

			array( 'update_delete_others', 'editor', 200 ),
			array( 'update_delete_others', 'author', 403 ),
			array( 'update_delete_others', 'contributor', 403 ),
			array( 'update_delete_others', null, 401 ),
		);
	}

	/**
	 * Exhaustively check that each role either can or cannot create, edit,
	 * update, and delete reusable blocks.
	 *
	 * @ticket 45098
	 *
	 * @dataProvider data_capabilities
	 *
	 * @param string $action          Action to perform in the test.
	 * @param string $role            User role to test.
	 * @param int    $expected_status Expected HTTP response status.
	 */
	public function test_capabilities( $action, $role, $expected_status ) {
		if ( $role ) {
			$user_id = self::$user_ids[ $role ];
			gc_set_current_user( $user_id );
		} else {
			gc_set_current_user( 0 );
		}

		switch ( $action ) {
			case 'create':
				$request = new GC_REST_Request( 'POST', '/gc/v2/blocks' );
				$request->set_body_params(
					array(
						'title'   => 'Test',
						'content' => '<!-- gc:paragraph --><p>Test</p><!-- /gc:paragraph -->',
					)
				);

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				break;

			case 'read':
				$request = new GC_REST_Request( 'GET', '/gc/v2/blocks/' . self::$post_id );

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				break;

			case 'update_delete_own':
				$post_id = gc_insert_post(
					array(
						'post_type'    => 'gc_block',
						'post_status'  => 'publish',
						'post_title'   => 'My cool block',
						'post_content' => '<!-- gc:paragraph --><p>Hello!</p><!-- /gc:paragraph -->',
						'post_author'  => $user_id,
					)
				);

				$request = new GC_REST_Request( 'PUT', '/gc/v2/blocks/' . $post_id );
				$request->set_body_params(
					array(
						'title'   => 'Test',
						'content' => '<!-- gc:paragraph --><p>Test</p><!-- /gc:paragraph -->',
					)
				);

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				$request = new GC_REST_Request( 'DELETE', '/gc/v2/blocks/' . $post_id );

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				gc_delete_post( $post_id );

				break;

			case 'update_delete_others':
				$request = new GC_REST_Request( 'PUT', '/gc/v2/blocks/' . self::$post_id );
				$request->set_body_params(
					array(
						'title'   => 'Test',
						'content' => '<!-- gc:paragraph --><p>Test</p><!-- /gc:paragraph -->',
					)
				);

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				$request = new GC_REST_Request( 'DELETE', '/gc/v2/blocks/' . self::$post_id );

				$response = rest_get_server()->dispatch( $request );
				$this->assertSame( $expected_status, $response->get_status() );

				break;

			default:
				$this->fail( "'$action' is not a valid action." );
		}
	}

	/**
	 * Check that the raw title and content of a block can be accessed when there
	 * is no set schema, and that the rendered content of a block is not included
	 * in the response.
	 */
	public function test_content() {
		gc_set_current_user( self::$user_ids['author'] );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/blocks/' . self::$post_id );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame(
			array(
				'raw' => 'My cool block',
			),
			$data['title']
		);
		$this->assertSame(
			array(
				'raw'       => '<!-- gc:paragraph --><p>Hello!</p><!-- /gc:paragraph -->',
				'protected' => false,
			),
			$data['content']
		);
	}
}
