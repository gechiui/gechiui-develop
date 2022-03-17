<?php

/**
 * @group post
 */
class Tests_Post_gcGetPostParentId extends GC_UnitTestCase {
	/**
	 * Parent post ID.
	 *
	 * @var int
	 */
	public static $parent_post_id;

	/**
	 * Post ID.
	 *
	 * @var int
	 */
	public static $post_id;

	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		self::$parent_post_id = $factory->post->create();
		self::$post_id        = $factory->post->create( array( 'post_parent' => self::$parent_post_id ) );
	}

	public function test_gc_get_post_parent_id_with_post_object() {
		$post = get_post( self::$post_id );
		$this->assertInstanceOf( 'GC_Post', $post );
		$this->assertSame( self::$parent_post_id, gc_get_post_parent_id( $post ) );
	}

	public function test_gc_get_post_parent_id_with_post_id() {
		$this->assertSame( self::$parent_post_id, gc_get_post_parent_id( self::$post_id ) );
	}

	/**
	 * @ticket 48358
	 * @covers ::gc_get_post_parent_id
	 */
	public function test_gc_get_post_parent_id_with_no_post_argument_default_to_global_post_id() {
		$GLOBALS['post'] = get_post( self::$post_id );
		$this->assertSame( self::$parent_post_id, gc_get_post_parent_id() );
	}

	public function test_gc_get_post_parent_id_with_non_existing_id_default_to_global_post_id() {
		$GLOBALS['post'] = get_post( self::$post_id );
		$this->assertSame( self::$parent_post_id, gc_get_post_parent_id( 0 ) );
	}

	public function test_gc_get_post_parent_id_with_boolean_default_to_global_post_id() {
		$GLOBALS['post'] = get_post( self::$post_id );
		$this->assertSame( self::$parent_post_id, gc_get_post_parent_id( false ) );
	}

	public function test_gc_get_post_parent_id_with_string_default_to_false() {
		$GLOBALS['post'] = get_post( self::$post_id );
		$this->assertFalse( gc_get_post_parent_id( 'string' ) );
	}
}
