<?php

/**
 * A factory for making GeChiUI data with a cross-object type API.
 *
 * Tests should use this factory to generate test fixtures.
 */
class GC_UnitTest_Factory {

	/**
	 * Generates post fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Post
	 */
	public $post;

	/**
	 * Generates attachment fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Attachment
	 */
	public $attachment;

	/**
	 * Generates comment fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Comment
	 */
	public $comment;

	/**
	 * Generates user fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_User
	 */
	public $user;

	/**
	 * Generates taxonomy term fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Term
	 */
	public $term;

	/**
	 * Generates category fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Term
	 */
	public $category;

	/**
	 * Generates tag fixtures for use in tests.
	 *
	 * @var GC_UnitTest_Factory_For_Term
	 */
	public $tag;

	/**
	 * Generates bookmark (link) fixtures for use in tests.
	 *
	 * @since 4.6.0
	 * @var GC_UnitTest_Factory_For_Bookmark
	 */
	public $bookmark;

	/**
	 * Generates blog (site) fixtures for use in Multisite tests.
	 *
	 * @var GC_UnitTest_Factory_For_Blog
	 */
	public $blog;

	/**
	 * Generates network fixtures for use in Multisite tests.
	 *
	 * @var GC_UnitTest_Factory_For_Network
	 */
	public $network;

	public function __construct() {
		$this->post       = new GC_UnitTest_Factory_For_Post( $this );
		$this->attachment = new GC_UnitTest_Factory_For_Attachment( $this );
		$this->comment    = new GC_UnitTest_Factory_For_Comment( $this );
		$this->user       = new GC_UnitTest_Factory_For_User( $this );
		$this->term       = new GC_UnitTest_Factory_For_Term( $this );
		$this->category   = new GC_UnitTest_Factory_For_Term( $this, 'category' );
		$this->tag        = new GC_UnitTest_Factory_For_Term( $this, 'post_tag' );
		$this->bookmark   = new GC_UnitTest_Factory_For_Bookmark( $this );
		if ( is_multisite() ) {
			$this->blog    = new GC_UnitTest_Factory_For_Blog( $this );
			$this->network = new GC_UnitTest_Factory_For_Network( $this );
		}
	}
}
