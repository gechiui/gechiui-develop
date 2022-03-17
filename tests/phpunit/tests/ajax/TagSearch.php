<?php

/**
 * Admin Ajax functions to be tested.
 */
require_once ABSPATH . 'gc-admin/includes/ajax-actions.php';

/**
 * Testing Ajax tag search functionality.
 *
 * @package    GeChiUI
 * @subpackage UnitTests
 * @since      3.4.0
 * @group      ajax
 */
class Tests_Ajax_TagSearch extends GC_Ajax_UnitTestCase {

	/**
	 * List of terms to insert on setup
	 *
	 * @var array
	 */
	private static $terms = array(
		'chattels',
		'depo',
		'energumen',
		'figuriste',
		'habergeon',
		'impropriation',
	);

	private static $term_ids = array();

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		foreach ( self::$terms as $t ) {
			self::$term_ids[] = gc_insert_term( $t, 'post_tag' );
		}
	}

	/**
	 * Test as an admin
	 */
	public function test_post_tag() {

		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_GET['tax'] = 'post_tag';
		$_GET['q']   = 'chat';

		// Make the request.
		try {
			$this->_handleAjax( 'ajax-tag-search' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Ensure we found the right match.
		$this->assertSame( $this->_last_response, 'chattels' );
	}

	/**
	 * Test with no results
	 */
	public function test_no_results() {

		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_GET['tax'] = 'post_tag';
		$_GET['q']   = md5( uniqid() );

		// Make the request.
		// No output, so we get a stop exception.
		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '' );
		$this->_handleAjax( 'ajax-tag-search' );
	}

	/**
	 * Test with commas
	 */
	public function test_with_comma() {

		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_GET['tax'] = 'post_tag';
		$_GET['q']   = 'some,nonsense, terms,chat'; // Only the last term in the list is searched.

		// Make the request.
		try {
			$this->_handleAjax( 'ajax-tag-search' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Ensure we found the right match.
		$this->assertSame( $this->_last_response, 'chattels' );
	}

	/**
	 * Test as a logged out user
	 */
	public function test_logged_out() {

		// Log out.
		gc_logout();

		// Set up a default request.
		$_GET['tax'] = 'post_tag';
		$_GET['q']   = 'chat';

		// Make the request.
		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '-1' );
		$this->_handleAjax( 'ajax-tag-search' );
	}

	/**
	 * Test with an invalid taxonomy type
	 */
	public function test_invalid_tax() {

		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_GET['tax'] = 'invalid-taxonomy';
		$_GET['q']   = 'chat';

		// Make the request.
		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '0' );
		$this->_handleAjax( 'ajax-tag-search' );
	}

	/**
	 * Test as an unprivileged user
	 */
	public function test_unprivileged_user() {

		// Become a subscriber.
		$this->_setRole( 'subscriber' );

		// Set up a default request.
		$_GET['tax'] = 'post_tag';
		$_GET['q']   = 'chat';

		// Make the request.
		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '-1' );
		$this->_handleAjax( 'ajax-tag-search' );
	}

}
