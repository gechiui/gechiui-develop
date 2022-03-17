<?php

/**
 * @group xmlrpc
 */
class Tests_XMLRPC_gc_restoreRevision extends GC_XMLRPC_UnitTestCase {
	public $post_id;
	public $revision_id;

	public function set_up() {
		parent::set_up();

		$this->post_id = self::factory()->post->create( array( 'post_content' => 'edit1' ) ); // Not saved as a revision.
		// First saved revision on update, see https://core.trac.gechiui.com/changeset/24650
		gc_insert_post(
			array(
				'ID'           => $this->post_id,
				'post_content' => 'edit2',
			)
		);

		$revisions = gc_get_post_revisions( $this->post_id );
		// First revision is empty, see https://core.trac.gechiui.com/changeset/23842
		// $revision = array_shift( $revisions );
		// First revision is NOT empty, see https://core.trac.gechiui.com/changeset/24650
		$revision          = array_shift( $revisions );
		$this->revision_id = $revision->ID;
	}

	public function test_invalid_username_password() {
		$result = $this->myxmlrpcserver->gc_restoreRevision( array( 1, 'username', 'password', $this->revision_id ) );
		$this->assertIXRError( $result );
		$this->assertSame( 403, $result->code );
	}

	public function test_incapable_user() {
		$this->make_user_by_role( 'subscriber' );

		$result = $this->myxmlrpcserver->gc_restoreRevision( array( 1, 'subscriber', 'subscriber', $this->revision_id ) );
		$this->assertIXRError( $result );
		$this->assertSame( 401, $result->code );
	}

	public function test_capable_user() {
		$this->make_user_by_role( 'editor' );

		$result = $this->myxmlrpcserver->gc_restoreRevision( array( 1, 'editor', 'editor', $this->revision_id ) );
		$this->assertNotIXRError( $result );
	}

	public function test_revision_restored() {
		$this->make_user_by_role( 'editor' );

		$result = $this->myxmlrpcserver->gc_restoreRevision( array( 1, 'editor', 'editor', $this->revision_id ) );
		$this->assertTrue( $result );
		$this->assertSame( 'edit2', get_post( $this->post_id )->post_content );
	}
}
