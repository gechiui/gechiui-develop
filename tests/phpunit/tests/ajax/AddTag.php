<?php

/**
 * Admin ajax functions to be tested.
 */
require_once ABSPATH . 'gc-admin/includes/ajax-actions.php';

/**
 * Class for testing ajax add tag functionality.
 *
 * @group ajax
 */
class Tests_Ajax_AddTag extends GC_Ajax_UnitTestCase {

	/**
	 * @dataProvider data_add_tag
	 *
	 * @ticket 42937
	 *
	 * @param array                 $post_data Data to populate $_POST.
	 * @param string                $expected  Expected response.
	 * @param array|string|callable $callback  Optional. Callback to register to 'term_updated_messages'
	 *                                         filter. Default empty string (no callback).
	 */
	public function test_add_tag( array $post_data, $expected, $callback = '' ) {
		$this->_setRole( 'administrator' );

		$_POST                     = $post_data;
		$_POST['_gcnonce_add-tag'] = gc_create_nonce( 'add-tag' );

		if ( ! empty( $callback ) ) {
			add_filter( 'term_updated_messages', $callback );
		}

		try {
			$this->_handleAjax( 'add-tag' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		$this->assertSame( $expected, (string) $this->get_xml_response_taxonomy()->response_data );
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function data_add_tag() {
		return array(
			'add a category'                        => array(
				'post_data' => array(
					'taxonomy'  => 'category',
					'post_type' => 'post',
					'screen'    => 'edit-category',
					'action'    => 'add-tag',
					'tag-name'  => 'blues',
				),
				'expected'  => 'Category added.',
			),
			'add a category with message filtering' => array(
				'post_data' => array(
					'taxonomy'  => 'category',
					'post_type' => 'post',
					'screen'    => 'edit-category',
					'action'    => 'add-tag',
					'tag-name'  => 'techno',
				),
				'expected'  => 'A new category added.',
				'callback'  => static function( array $messages ) {
					$messages['category'][1] = 'A new category added.';
					return $messages;
				},
			),
			'add a post_tag'                        => array(
				'post_data' => array(
					'taxonomy'  => 'post_tag',
					'post_type' => 'post',
					'screen'    => 'edit-post_tag',
					'action'    => 'add-tag',
					'tag-name'  => 'Louis Armstrong',
				),
				'expected'  => 'Tag added.',
			),
		);
	}

	/**
	 * @ticket 42937
	 */
	public function test_adding_category_without_capability_should_error() {
		$this->_setRole( 'subscriber' );

		$_POST['taxonomy']         = 'category';
		$_POST['post_type']        = 'post';
		$_POST['screen']           = 'edit-category';
		$_POST['action']           = 'add-tag';
		$_POST['tag - name']       = 'disco';
		$_POST['_gcnonce_add-tag'] = gc_create_nonce( 'add-tag' );

		$this->expectException( 'GCAjaxDieStopException' );
		$this->expectExceptionMessage( '-1' );
		$this->_handleAjax( 'add-tag' );
	}

	/**
	 * @ticket 42937
	 */
	public function test_adding_existing_category_should_error() {
		$this->_setRole( 'administrator' );

		gc_insert_term( 'testcat', 'category' );

		$_POST = array(
			'taxonomy'         => 'category',
			'post_type'        => 'post',
			'screen'           => 'edit-category',
			'action'           => 'add-tag',
			'tag-name'         => 'testcat',
			'_gcnonce_add-tag' => gc_create_nonce( 'add-tag' ),
		);

		try {
			$this->_handleAjax( 'add-tag' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		$expected = 'A term with the name provided already exists with this parent.';
		$this->assertSame( $expected, (string) $this->get_xml_response_taxonomy()->gc_error );
	}

	/**
	 * Helper method to get the taxonomy's response or error.
	 *
	 * @since 5.9.0
	 *
	 * @return SimpleXMLElement Response or error object.
	 */
	private function get_xml_response_taxonomy() {
		$xml = simplexml_load_string( $this->_last_response, 'SimpleXMLElement', LIBXML_NOCDATA );

		return $xml->response->taxonomy;
	}
}
