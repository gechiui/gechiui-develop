<?php
require_once ABSPATH . 'gc-admin/includes/admin.php';
require_once ABSPATH . GCINC . '/class-IXR.php';
require_once ABSPATH . GCINC . '/class-gc-xmlrpc-server.php';

abstract class GC_XMLRPC_UnitTestCase extends GC_UnitTestCase {
	/**
	 * @var gc_xmlrpc_server
	 */
	protected $myxmlrpcserver;

	public function set_up() {
		parent::set_up();

		add_filter( 'pre_option_enable_xmlrpc', '__return_true' );

		$this->myxmlrpcserver = new gc_xmlrpc_server();
	}

	public function tear_down() {
		remove_filter( 'pre_option_enable_xmlrpc', '__return_true' );

		$this->remove_added_uploads();

		parent::tear_down();
	}

	protected static function make_user_by_role( $role ) {
		return self::factory()->user->create(
			array(
				'user_login' => $role,
				'user_pass'  => $role,
				'role'       => $role,
			)
		);
	}

}
