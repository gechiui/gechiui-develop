<?php

abstract class GC_Test_REST_Controller_Testcase extends GC_Test_REST_TestCase {

	protected $server;

	public function set_up() {
		parent::set_up();
		add_filter( 'rest_url', array( $this, 'filter_rest_url_for_leading_slash' ), 10, 2 );
		/** @var GC_REST_Server $gc_rest_server */
		global $gc_rest_server;
		$gc_rest_server = new Spy_REST_Server;
		do_action( 'rest_api_init', $gc_rest_server );
	}

	public function tear_down() {
		remove_filter( 'rest_url', array( $this, 'test_rest_url_for_leading_slash' ), 10, 2 );
		/** @var GC_REST_Server $gc_rest_server */
		global $gc_rest_server;
		$gc_rest_server = null;
		parent::tear_down();
	}

	abstract public function test_register_routes();

	abstract public function test_context_param();

	abstract public function test_get_items();

	abstract public function test_get_item();

	abstract public function test_create_item();

	abstract public function test_update_item();

	abstract public function test_delete_item();

	abstract public function test_prepare_item();

	abstract public function test_get_item_schema();

	public function filter_rest_url_for_leading_slash( $url, $path ) {
		if ( is_multisite() || get_option( 'permalink_structure' ) ) {
			return $url;
		}

		// Make sure path for rest_url has a leading slash for proper resolution.
		if ( 0 !== strpos( $path, '/' ) ) {
			$this->fail(
				sprintf(
					'REST API URL "%s" should have a leading slash.',
					$path
				)
			);
		}

		return $url;
	}
}
