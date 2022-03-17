<?php

if ( ! class_exists( '_GC_Editors', false ) ) {
	require_once ABSPATH . GCINC . '/class-gc-editor.php';
}

/**
 * @group editor
 *
 * @coversDefaultClass _GC_Editors
 */
class Tests_Editor_gcEditors extends GC_UnitTestCase {

	/**
	 * @covers ::gc_link_query
	 */
	public function test_gc_link_query_returns_false_when_nothing_found() {
		$actual = _GC_Editors::gc_link_query( array( 's' => 'foobarbaz' ) );

		$this->assertFalse( $actual );
	}

	/**
	 * @covers ::gc_link_query
	 */
	public function test_gc_link_query_returns_search_results() {
		$post   = self::factory()->post->create_and_get( array( 'post_status' => 'publish' ) );
		$actual = _GC_Editors::gc_link_query( array( 's' => $post->post_title ) );

		$this->assertSameSets(
			array(
				array(
					'ID'        => $post->ID,
					'title'     => $post->post_title,
					'permalink' => get_permalink( $post->ID ),
					'info'      => mysql2date( __( 'Y/m/d' ), $post->post_date ),
				),
			),
			$actual
		);
	}

	/**
	 * @ticket 41825
	 *
	 * @covers ::gc_link_query
	 */
	public function test_gc_link_query_returns_filtered_result_when_nothing_found() {
		add_filter( 'gc_link_query', array( $this, 'gc_link_query_callback' ) );
		$actual = _GC_Editors::gc_link_query( array( 's' => 'foobarbaz' ) );
		remove_filter( 'gc_link_query', array( $this, 'gc_link_query_callback' ) );

		$this->assertSameSets(
			array(
				array(
					'ID'        => 123,
					'title'     => 'foo',
					'permalink' => 'bar',
					'info'      => 'baz',
				),
			),
			$actual
		);
	}

	/**
	 * @covers ::gc_link_query
	 */
	public function test_gc_link_query_returns_filtered_search_results() {
		$post = self::factory()->post->create_and_get( array( 'post_status' => 'publish' ) );

		add_filter( 'gc_link_query', array( $this, 'gc_link_query_callback' ) );
		$actual = _GC_Editors::gc_link_query( array( 's' => $post->post_title ) );
		remove_filter( 'gc_link_query', array( $this, 'gc_link_query_callback' ) );

		$this->assertSameSets(
			array(
				array(
					'ID'        => $post->ID,
					'title'     => $post->post_title,
					'permalink' => get_permalink( $post->ID ),
					'info'      => mysql2date( __( 'Y/m/d' ), $post->post_date ),
				),
				array(
					'ID'        => 123,
					'title'     => 'foo',
					'permalink' => 'bar',
					'info'      => 'baz',
				),
			),
			$actual
		);
	}

	public function gc_link_query_callback( $results ) {
		return array_merge(
			$results,
			array(
				array(
					'ID'        => 123,
					'title'     => 'foo',
					'permalink' => 'bar',
					'info'      => 'baz',
				),
			)
		);
	}
}
