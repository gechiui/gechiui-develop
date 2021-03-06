<?php
/**
 * Unit tests covering GC_REST_Pattern_Directory_Controller functionality.
 *
 * @package GeChiUI
 * @subpackage REST API
 */

/**
 * @group restapi
 * @group pattern-directory
 */
class GC_REST_Pattern_Directory_Controller_Test extends GC_Test_REST_Controller_Testcase {

	/**
	 * Contributor user id.
	 *
	 * @since 5.8.0
	 *
	 * @var int
	 */
	protected static $contributor_id;

	/**
	 * Set up class test fixtures.
	 *
	 * @since 5.8.0
	 *
	 * @param GC_UnitTest_Factory $factory GeChiUI unit test factory.
	 */
	public static function gcSetUpBeforeClass( $factory ) {
		self::$contributor_id = $factory->user->create(
			array(
				'role' => 'contributor',
			)
		);
	}

	/**
	 * Asserts that the pattern matches the expected response schema.
	 *
	 * @param GC_REST_Response[] $pattern An individual pattern from the REST API response.
	 */
	public function assertPatternMatchesSchema( $pattern ) {
		$schema     = ( new GC_REST_Pattern_Directory_Controller() )->get_item_schema();
		$pattern_id = isset( $pattern->id ) ? $pattern->id : '{pattern ID is missing}';

		$this->assertTrue(
			rest_validate_value_from_schema( $pattern, $schema ),
			"Pattern ID `$pattern_id` doesn't match the response schema."
		);

		$this->assertSame(
			array_keys( $schema['properties'] ),
			array_keys( $pattern ),
			"Pattern ID `$pattern_id` doesn't contain all of the fields expected from the schema."
		);
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::register_routes
	 *
	 * @since 5.8.0
	 */
	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();

		$this->assertArrayHasKey( '/gc/v2/pattern-directory/patterns', $routes );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_context_param
	 *
	 * @since 5.8.0
	 */
	public function test_context_param() {
		$request  = new GC_REST_Request( 'OPTIONS', '/gc/v2/pattern-directory/patterns' );
		$response = rest_get_server()->dispatch( $request );
		$patterns = $response->get_data();

		$this->assertSame( 'view', $patterns['endpoints'][0]['args']['context']['default'] );
		$this->assertSame( array( 'view', 'embed' ), $patterns['endpoints'][0]['args']['context']['enum'] );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'browse-all', true );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertNotWPError( $response->as_error() );
		$this->assertSame( 200, $response->status );
		$this->assertGreaterThan( 0, count( $patterns ) );

		array_walk( $patterns, array( $this, 'assertPatternMatchesSchema' ) );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_by_category() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'browse-category', true );

		$request = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'category' => 2 ) );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertNotWPError( $response->as_error() );
		$this->assertSame( 200, $response->status );
		$this->assertGreaterThan( 0, count( $patterns ) );

		array_walk( $patterns, array( $this, 'assertPatternMatchesSchema' ) );

		foreach ( $patterns as $pattern ) {
			$this->assertContains( 'buttons', $pattern['categories'] );
		}
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_by_keyword() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'browse-keyword', true );

		$request = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'keyword' => 11 ) );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertNotWPError( $response->as_error() );
		$this->assertSame( 200, $response->status );
		$this->assertGreaterThan( 0, count( $patterns ) );

		array_walk( $patterns, array( $this, 'assertPatternMatchesSchema' ) );

		foreach ( $patterns as $pattern ) {
			$this->assertContains( 'core', $pattern['keywords'] );
		}
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_search() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'search', true );

		$search_term = 'button';
		$request     = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'search' => $search_term ) );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertNotWPError( $response->as_error() );
		$this->assertSame( 200, $response->status );
		$this->assertGreaterThan( 0, count( $patterns ) );

		array_walk( $patterns, array( $this, 'assertPatternMatchesSchema' ) );

		foreach ( $patterns as $pattern ) {
			$search_field_values = $pattern['title'] . ' ' . $pattern['description'];

			$this->assertStringContainsStringIgnoringCase( $search_term, $search_field_values );
		}
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_wdotorg_unavailable() {
		gc_set_current_user( self::$contributor_id );
		self::prevent_requests_to_host( 'api.gechiui.com' );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$response = rest_do_request( $request );

		$this->assertErrorResponse( 'patterns_api_failed', $response, 500 );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_logged_out() {
		$request = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'search' => 'button' ) );
		$response = rest_do_request( $request );

		$this->assertErrorResponse( 'rest_pattern_directory_cannot_view', $response );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_no_results() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'browse-all', false );

		$request = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'category' => PHP_INT_MAX ) );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertSame( 200, $response->status );
		$this->assertSame( array(), $patterns );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_search_no_results() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'search', false );

		$request = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$request->set_query_params( array( 'search' => '0c4549ee68f24eaaed46a49dc983ecde' ) );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertSame( 200, $response->status );
		$this->assertSame( array(), $patterns );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_invalid_response_data() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'invalid-data', true );

		$request  = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$response = rest_do_request( $request );

		$this->assertSame( 500, $response->status );
		$this->assertWPError( $response->as_error() );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_items
	 *
	 * @since 5.8.0
	 */
	public function test_get_items_prepare_filter() {
		gc_set_current_user( self::$contributor_id );
		self::mock_successful_response( 'browse-all', true );

		// Test that filter changes uncached values.
		add_filter(
			'rest_prepare_block_pattern',
			static function( $response ) {
				return 'initial value';
			}
		);

		$request  = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertSame( 'initial value', $patterns[0] );

		// Test that filter changes cached values (the previous request primed the cache).
		add_filter(
			'rest_prepare_block_pattern',
			static function( $response ) {
				return 'modified the cache';
			},
			11
		);

		// Test that the filter works against cached values.
		$request  = new GC_REST_Request( 'GET', '/gc/v2/pattern-directory/patterns' );
		$response = rest_do_request( $request );
		$patterns = $response->get_data();

		$this->assertSame( 'modified the cache', $patterns[0] );
	}

	public function test_get_item() {
		$this->markTestSkipped( 'Controller does not have get_item route.' );
	}

	public function test_create_item() {
		$this->markTestSkipped( 'Controller does not have create_item route.' );
	}

	public function test_update_item() {
		$this->markTestSkipped( 'Controller does not have update_item route.' );
	}

	public function test_delete_item() {
		$this->markTestSkipped( 'Controller does not have delete_item route.' );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::prepare_item_for_response
	 *
	 * @since 5.8.0
	 */
	public function test_prepare_item() {
		$controller                   = new GC_REST_Pattern_Directory_Controller();
		$raw_patterns                 = json_decode( self::get_raw_response( 'browse-all' ) );
		$raw_patterns[0]->extra_field = 'this should be removed';

		$prepared_pattern = $controller->prepare_response_for_collection(
			$controller->prepare_item_for_response( $raw_patterns[0], new GC_REST_Request() )
		);

		$this->assertPatternMatchesSchema( $prepared_pattern );
		$this->assertArrayNotHasKey( 'extra_field', $prepared_pattern );
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::prepare_item_for_response
	 *
	 * @since 5.8.0
	 */
	public function test_prepare_item_search() {
		$controller                   = new GC_REST_Pattern_Directory_Controller();
		$raw_patterns                 = json_decode( self::get_raw_response( 'search' ) );
		$raw_patterns[0]->extra_field = 'this should be removed';

		$prepared_pattern = $controller->prepare_response_for_collection(
			$controller->prepare_item_for_response( $raw_patterns[0], new GC_REST_Request() )
		);

		$this->assertPatternMatchesSchema( $prepared_pattern );
		$this->assertArrayNotHasKey( 'extra_field', $prepared_pattern );
	}

	/**
	 * Get a mocked raw response from api.gechiui.com.
	 *
	 * @return string
	 */
	private static function get_raw_response( $action ) {
		$fixtures_dir = DIR_TESTDATA . '/blocks/pattern-directory';

		switch ( $action ) {
			default:
			case 'browse-all':
				// Response from https://api.gechiui.com/patterns/1.0/.
				$response = file_get_contents( $fixtures_dir . '/browse-all.json' );
				break;

			case 'browse-category':
				// Response from https://api.gechiui.com/patterns/1.0/?pattern-categories=2.
				$response = file_get_contents( $fixtures_dir . '/browse-category-2.json' );
				break;

			case 'browse-keyword':
				// Response from https://api.gechiui.com/patterns/1.0/?pattern-keywords=11.
				$response = file_get_contents( $fixtures_dir . '/browse-keyword-11.json' );
				break;

			case 'search':
				// Response from https://api.gechiui.com/patterns/1.0/?search=button.
				$response = file_get_contents( $fixtures_dir . '/search-button.json' );
				break;

			case 'invalid-data':
				$response = ''; // Any HTTP 200 response from w.org should be in JSON, even if it contains an error message.
				break;
		}

		return $response;
	}

	/**
	 * @covers GC_REST_Pattern_Directory_Controller::get_item_schema
	 *
	 * @since 5.8.0
	 */
	public function test_get_item_schema() {
		$this->markTestSkipped( "The controller's schema is hardcoded, so tests would not be meaningful." );
	}

	/**
	 * Simulate a successful outbound HTTP requests, to keep tests pure and performant.
	 *
	 * @param string $action          Pass a case from `get_raw_response()` to determine returned data.
	 * @param bool   $expects_results Pass `true` to get results, or `false` to get 0 results.
	 *
	 * @since 5.8.0
	 */
	private static function mock_successful_response( $action, $expects_results ) {
		add_filter(
			'pre_http_request',
			static function ( $preempt, $args, $url ) use ( $action, $expects_results ) {

				if ( 'api.gechiui.com' !== gc_parse_url( $url, PHP_URL_HOST ) ) {
					return $preempt;
				}

				$response = array(
					'headers'  => array(),
					'response' => array(
						'code'    => 200,
						'message' => 'OK',
					),
					'body'     => $expects_results ? self::get_raw_response( $action ) : '[]',
					'cookies'  => array(),
					'filename' => null,
				);

				return $response;
			},
			10,
			3
		);
	}

	/**
	 * Simulate a network failure on outbound http requests to a given hostname.
	 *
	 * @since 5.8.0
	 *
	 * @param string $blocked_host The host to block connections to.
	 */
	private static function prevent_requests_to_host( $blocked_host = 'api.gechiui.com' ) {
		add_filter(
			'pre_http_request',
			static function ( $return, $args, $url ) use ( $blocked_host ) {

				if ( gc_parse_url( $url, PHP_URL_HOST ) === $blocked_host ) {
					return new GC_Error(
						'patterns_api_failed',
						"An expected error occurred connecting to $blocked_host because of a unit test.",
						"cURL error 7: Failed to connect to $blocked_host port 80: Connection refused"
					);

				}

				return $return;
			},
			10,
			3
		);
	}
}
