<?php
/**
 * Admin Ajax functions to be tested.
 */
require_once ABSPATH . 'gc-admin/includes/ajax-actions.php';

/**
 * Testing Ajax handler for updating a plugin.
 *
 * @group ajax
 */
class Tests_Ajax_Update_Plugin extends GC_Ajax_UnitTestCase {

	public function test_missing_nonce() {
		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '-1' );
		$this->_handleAjax( 'update-plugin' );
	}

	public function test_missing_plugin() {
		$_POST['_ajax_nonce'] = gc_create_nonce( 'updates' );
		$_POST['slug']        = 'foo';

		// Make the request.
		try {
			$this->_handleAjax( 'update-plugin' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$expected = array(
			'success' => false,
			'data'    => array(
				'slug'         => '',
				'errorCode'    => 'no_plugin_specified',
				'errorMessage' => 'No plugin specified.',
			),
		);

		$this->assertSameSets( $expected, $response );
	}

	public function test_missing_slug() {
		$_POST['_ajax_nonce'] = gc_create_nonce( 'updates' );
		$_POST['plugin']      = 'foo/bar.php';

		// Make the request.
		try {
			$this->_handleAjax( 'update-plugin' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$expected = array(
			'success' => false,
			'data'    => array(
				'slug'         => '',
				'errorCode'    => 'no_plugin_specified',
				'errorMessage' => 'No plugin specified.',
			),
		);

		$this->assertSameSets( $expected, $response );
	}

	public function test_missing_capability() {
		$_POST['_ajax_nonce'] = gc_create_nonce( 'updates' );
		$_POST['plugin']      = 'foo/bar.php';
		$_POST['slug']        = 'foo';

		// Make the request.
		try {
			$this->_handleAjax( 'update-plugin' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$expected = array(
			'success' => false,
			'data'    => array(
				'update'       => 'plugin',
				'slug'         => 'foo',
				'oldVersion'   => '',
				'newVersion'   => '',
				'errorMessage' => 'Sorry, you are not allowed to update plugins for this site.',
			),
		);

		$this->assertSameSets( $expected, $response );
	}

	public function test_invalid_file() {
		$this->_setRole( 'administrator' );

		$_POST['_ajax_nonce'] = gc_create_nonce( 'updates' );
		$_POST['plugin']      = '../foo/bar.php';
		$_POST['slug']        = 'foo';

		// Make the request.
		try {
			$this->_handleAjax( 'update-plugin' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$expected = array(
			'success' => false,
			'data'    => array(
				'update'       => 'plugin',
				'slug'         => 'foo',
				'oldVersion'   => '',
				'newVersion'   => '',
				'errorMessage' => 'Sorry, you are not allowed to update plugins for this site.',
			),
		);

		$this->assertSameSets( $expected, $response );
	}

	/**
	 * @group ms-excluded
	 */
	public function test_update_plugin() {
		$this->_setRole( 'administrator' );

		$_POST['_ajax_nonce'] = gc_create_nonce( 'updates' );
		$_POST['plugin']      = 'hello.php';
		$_POST['slug']        = 'hello-dolly';

		// Make the request.
		try {
			// Prevent gc_update_plugins() from running.
			gc_installing( true );
			$this->_handleAjax( 'update-plugin' );
			gc_installing( false );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$expected = array(
			'success' => false,
			'data'    => array(
				'update'       => 'plugin',
				'slug'         => 'hello-dolly',
				'oldVersion'   => 'Version 1.7.2',
				'newVersion'   => '',
				'plugin'       => 'hello.php',
				'pluginName'   => 'Hello Dolly',
				'debug'        => array( 'The plugin is at the latest version.' ),
				'errorMessage' => 'The plugin is at the latest version.',
			),
		);

		$this->assertSameSets( $expected, $response );
	}
}
