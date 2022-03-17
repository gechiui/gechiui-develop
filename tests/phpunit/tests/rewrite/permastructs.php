<?php

/**
 * @group rewrite
 */
class Tests_Rewrite_Permastructs extends GC_UnitTestCase {

	public function set_up() {
		parent::set_up();

		$this->set_permalink_structure( '/%postname%/' );
	}

	public function test_add_permastruct() {
		global $gc_rewrite;

		add_permastruct( 'foo', 'bar/%foo%' );
		$this->assertSameSetsWithIndex(
			array(
				'with_front'  => true,
				'ep_mask'     => EP_NONE,
				'paged'       => true,
				'feed'        => true,
				'walk_dirs'   => true,
				'endpoints'   => true,
				'forcomments' => false,
				'struct'      => '/bar/%foo%',
			),
			$gc_rewrite->extra_permastructs['foo']
		);
	}

	public function test_remove_permastruct() {
		global $gc_rewrite;

		add_permastruct( 'foo', 'bar/%foo%' );
		$this->assertIsArray( $gc_rewrite->extra_permastructs['foo'] );
		$this->assertSame( '/bar/%foo%', $gc_rewrite->extra_permastructs['foo']['struct'] );

		remove_permastruct( 'foo' );
		$this->assertArrayNotHasKey( 'foo', $gc_rewrite->extra_permastructs );
	}
}
