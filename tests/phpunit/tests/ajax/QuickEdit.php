<?php

/**
 * Admin Ajax functions to be tested.
 */
require_once ABSPATH . 'gc-admin/includes/ajax-actions.php';

/**
 * Testing Quick Edit AJAX functionality.
 *
 * @group ajax
 */
class Tests_Ajax_QuickEdit extends GC_Ajax_UnitTestCase {

	/**
	 * @ticket 26948
	 */
	public function test_dont_process_terms_if_taxonomy_does_not_allow_show_on_quick_edit() {
		register_taxonomy(
			'gctests_tax_1',
			'post',
			array(
				'show_in_quick_edit' => false,
				'hierarchical'       => true,
			)
		);
		register_taxonomy(
			'gctests_tax_2',
			'post',
			array(
				'show_in_quick_edit' => true,
				'hierarchical'       => true,
			)
		);

		$t1 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax_1',
			)
		);
		$t2 = self::factory()->term->create(
			array(
				'taxonomy' => 'gctests_tax_2',
			)
		);

		// Become an administrator.
		$this->_setRole( 'administrator' );

		$post = self::factory()->post->create_and_get(
			array(
				'post_author' => get_current_user_id(),
			)
		);

		// Set up a request.
		$_POST['_inline_edit'] = gc_create_nonce( 'inlineeditnonce' );
		$_POST['post_ID']      = $post->ID;
		$_POST['post_type']    = $post->post_type;
		$_POST['content']      = $post->post_content;
		$_POST['excerpt']      = $post->post_excerpt;
		$_POST['_status']      = $post->post_status;
		$_POST['post_status']  = $post->post_status;
		$_POST['screen']       = 'post';
		$_POST['post_view']    = 'excerpt';
		$_POST['tax_input']    = array(
			'gctests_tax_1' => array( $t1 ),
			'gctests_tax_2' => array( $t2 ),
		);

		// Make the request.
		try {
			$this->_handleAjax( 'inline-save' );
		} catch ( GCAjaxDieContinueException $e ) {
			unset( $e );
		}

		// 'gctests_tax_1' terms should have been refused.
		$post_terms_1 = gc_get_object_terms( $post->ID, 'gctests_tax_1' );
		$this->assertEmpty( $post_terms_1 );

		// 'gctests_tax_2' terms should have been added successfully.
		$post_terms_2 = gc_get_object_terms( $post->ID, 'gctests_tax_2' );
		$this->assertSameSets( array( $t2 ), gc_list_pluck( $post_terms_2, 'term_id' ) );
	}
}
