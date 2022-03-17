<?php

/**
 * Unit test factory for networks.
 *
 * Note: The below @method notations are defined solely for the benefit of IDEs,
 * as a way to indicate expected return values from the given factory methods.
 *
 * @method int create( $args = array(), $generation_definitions = null )
 * @method GC_Network create_and_get( $args = array(), $generation_definitions = null )
 * @method int[] create_many( $count, $args = array(), $generation_definitions = null )
 */
class GC_UnitTest_Factory_For_Network extends GC_UnitTest_Factory_For_Thing {

	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'domain'            => GC_TESTS_DOMAIN,
			'title'             => new GC_UnitTest_Generator_Sequence( 'Network %s' ),
			'path'              => new GC_UnitTest_Generator_Sequence( '/testpath%s/' ),
			'network_id'        => new GC_UnitTest_Generator_Sequence( '%s', 2 ),
			'subdomain_install' => false,
		);
	}

	public function create_object( $args ) {
		require_once ABSPATH . 'gc-admin/includes/upgrade.php';

		if ( ! isset( $args['user'] ) ) {
			$email = GC_TESTS_EMAIL;
		} else {
			$email = get_userdata( $args['user'] )->user_email;
		}

		populate_network( $args['network_id'], $args['domain'], $email, $args['title'], $args['path'], $args['subdomain_install'] );
		return (int) $args['network_id'];
	}

	public function update_object( $network_id, $fields ) {}

	public function get_object_by_id( $network_id ) {
		return get_network( $network_id );
	}
}
