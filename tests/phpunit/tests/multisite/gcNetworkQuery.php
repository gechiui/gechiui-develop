<?php

if ( is_multisite() ) :

	/**
	 * Test network query functionality in multisite.
	 *
	 * @group ms-network
	 * @group ms-network-query
	 * @group multisite
	 */
	class Tests_Multisite_gcNetworkQuery extends GC_UnitTestCase {
		protected static $network_ids;

		public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
			self::$network_ids = array(
				'www.gechiui.com/'      => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/',
				),
				'make.gechiui.com/' => array(
					'domain' => 'make.gechiui.com',
					'path'   => '/',
				),
				'www.gechiui.net/'  => array(
					'domain' => 'www.gechiui.net',
					'path'   => '/',
				),
				'www.w.org/foo/'      => array(
					'domain' => 'www.w.org',
					'path'   => '/foo/',
				),
			);

			foreach ( self::$network_ids as &$id ) {
				$id = $factory->network->create( $id );
			}
			unset( $id );
		}

		public static function gcTearDownAfterClass() {
			global $gcdb;

			foreach ( self::$network_ids as $id ) {
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->sitemeta} WHERE site_id = %d", $id ) );
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->site} WHERE id= %d", $id ) );
			}
		}

		public function test_gc_network_query_by_number() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
				)
			);

			$this->assertCount( 3, $found );
		}

		public function test_gc_network_query_by_network__in_with_order() {
			$expected = array( self::$network_ids['www.gechiui.com/'], self::$network_ids['make.gechiui.com/'] );

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'      => 'ids',
					'network__in' => $expected,
					'order'       => 'ASC',
				)
			);

			$this->assertSame( $expected, $found );

			$found = $q->query(
				array(
					'fields'      => 'ids',
					'network__in' => $expected,
					'order'       => 'DESC',
				)
			);

			$this->assertSame( array_reverse( $expected ), $found );
		}

		public function test_gc_network_query_by_network__in_with_single_id() {
			$expected = array( self::$network_ids['www.gechiui.com/'] );

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'      => 'ids',
					'network__in' => $expected,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_network__in_with_multiple_ids() {
			$expected = array( self::$network_ids['www.gechiui.com/'], self::$network_ids['www.gechiui.net/'] );

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'      => 'ids',
					'network__in' => $expected,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_network__in_and_count_with_multiple_ids() {
			$expected = array( self::$network_ids['www.gechiui.com/'], self::$network_ids['make.gechiui.com/'] );

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'      => 'ids',
					'count'       => true,
					'network__in' => $expected,
				)
			);

			$this->assertSame( 2, $found );
		}

		public function test_gc_network_query_by_network__not_in_with_single_id() {
			$excluded = array( self::$network_ids['www.gechiui.com/'] );
			$expected = array_diff( self::$network_ids, $excluded );

			// Exclude main network since we don't have control over it here.
			$excluded[] = 1;

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'          => 'ids',
					'network__not_in' => $excluded,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_network__not_in_with_multiple_ids() {
			$excluded = array( self::$network_ids['www.gechiui.com/'], self::$network_ids['www.w.org/foo/'] );
			$expected = array_diff( self::$network_ids, $excluded );

			// Exclude main network since we don't have control over it here.
			$excluded[] = 1;

			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'          => 'ids',
					'network__not_in' => $excluded,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'domain' => 'www.w.org',
				)
			);

			$expected = array(
				self::$network_ids['www.w.org/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__in_with_single_domain() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'domain__in' => array( 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$network_ids['make.gechiui.com/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__in_with_multiple_domains() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'domain__in' => array( 'www.gechiui.com', 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.com/'],
				self::$network_ids['make.gechiui.com/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__in_with_multiple_domains_and_number() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'number'     => 1,
					'domain__in' => array( 'www.gechiui.com', 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.com/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__in_with_multiple_domains_and_number_and_offset() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'number'     => 1,
					'offset'     => 1,
					'domain__in' => array( 'www.gechiui.com', 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$network_ids['make.gechiui.com/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__not_in_with_single_domain() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'domain__not_in' => array( 'www.w.org' ),
				)
			);

			$expected = array(
				get_current_site()->id, // Account for the initial network added by the test suite.
				self::$network_ids['www.gechiui.com/'],
				self::$network_ids['make.gechiui.com/'],
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__not_in_with_multiple_domains() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'domain__not_in' => array( 'www.gechiui.com', 'www.w.org' ),
				)
			);

			$expected = array(
				get_current_site()->id, // Account for the initial network added by the test suite.
				self::$network_ids['make.gechiui.com/'],
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__not_in_with_multiple_domains_and_number() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'number'         => 2,
					'domain__not_in' => array( 'www.gechiui.com', 'www.w.org' ),
				)
			);

			$expected = array(
				get_current_site()->id, // Account for the initial network added by the test suite.
				self::$network_ids['make.gechiui.com/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_domain__not_in_with_multiple_domains_and_number_and_offset() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'number'         => 2,
					'offset'         => 1,
					'domain__not_in' => array( 'www.gechiui.com', 'www.w.org' ),
				)
			);

			$expected = array(
				self::$network_ids['make.gechiui.com/'],
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_path_with_expected_results() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'          => 'ids',
					'path'            => '/',
					'network__not_in' => get_current_site()->id, // Exclude the initial network added by the test suite.
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.com/'],
				self::$network_ids['make.gechiui.com/'],
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_path_and_number_and_offset_with_expected_results() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'          => 'ids',
					'number'          => 1,
					'offset'          => 2,
					'path'            => '/',
					'network__not_in' => get_current_site()->id, // Exclude the initial network added by the test suite.
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_path_with_no_expected_results() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'path'   => '/bar/',
				)
			);

			$this->assertEmpty( $found );
		}

		public function test_gc_network_query_by_search_with_text_in_domain() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'ww.word',
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.net/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_search_with_text_in_path() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'foo',
				)
			);

			$expected = array(
				self::$network_ids['www.w.org/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_network_query_by_path_order_by_domain_desc() {
			$q     = new GC_Network_Query();
			$found = $q->query(
				array(
					'fields'          => 'ids',
					'path'            => '/',
					'network__not_in' => get_current_site()->id, // Exclude the initial network added by the test suite.
					'order'           => 'DESC',
					'orderby'         => 'domain',
				)
			);

			$expected = array(
				self::$network_ids['www.gechiui.net/'],
				self::$network_ids['www.gechiui.com/'],
				self::$network_ids['make.gechiui.com/'],
			);

			$this->assertSame( $expected, $found );
		}

		/**
		 * @ticket 41347
		 */
		public function test_gc_network_query_cache_with_different_fields_no_count() {
			global $gcdb;

			$q                 = new GC_Network_Query();
			$query_1           = $q->query(
				array(
					'fields' => 'all',
					'number' => 3,
					'order'  => 'ASC',
				)
			);
			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
					'order'  => 'ASC',
				)
			);

			$this->assertSame( $number_of_queries, $gcdb->num_queries );
		}

		/**
		 * @ticket 41347
		 */
		public function test_gc_network_query_cache_with_different_fields_active_count() {
			global $gcdb;

			$q = new GC_Network_Query();

			$query_1           = $q->query(
				array(
					'fields' => 'all',
					'number' => 3,
					'order'  => 'ASC',
					'count'  => true,
				)
			);
			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
					'order'  => 'ASC',
					'count'  => true,
				)
			);
			$this->assertSame( $number_of_queries, $gcdb->num_queries );
		}

		/**
		 * @ticket 41347
		 */
		public function test_gc_network_query_cache_with_same_fields_different_count() {
			global $gcdb;

			$q = new GC_Network_Query();

			$query_1 = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
					'order'  => 'ASC',
				)
			);

			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
					'order'  => 'ASC',
					'count'  => true,
				)
			);
			$this->assertSame( $number_of_queries + 1, $gcdb->num_queries );
		}

		/**
		 * @ticket 45749
		 * @ticket 47599
		 */
		public function test_networks_pre_query_filter_should_bypass_database_query() {
			global $gcdb;

			add_filter( 'networks_pre_query', array( __CLASS__, 'filter_networks_pre_query' ), 10, 2 );

			$num_queries = $gcdb->num_queries;

			$q       = new GC_Network_Query();
			$results = $q->query( array() );

			remove_filter( 'networks_pre_query', array( __CLASS__, 'filter_networks_pre_query' ), 10, 2 );

			// Make sure no queries were executed.
			$this->assertSame( $num_queries, $gcdb->num_queries );

			// We manually inserted a non-existing site and overrode the results with it.
			$this->assertSame( array( 555 ), $results );

			// Make sure manually setting found_networks doesn't get overwritten.
			$this->assertSame( 1, $q->found_networks );
		}

		public static function filter_networks_pre_query( $networks, $query ) {
			$query->found_networks = 1;

			return array( 555 );
		}

		/**
		 * @ticket 51333
		 */
		public function test_networks_pre_query_filter_should_set_networks_property() {
			add_filter( 'networks_pre_query', array( __CLASS__, 'filter_networks_pre_query_and_set_networks' ), 10, 2 );

			$q       = new GC_Network_Query();
			$results = $q->query( array() );

			remove_filter( 'networks_pre_query', array( __CLASS__, 'filter_networks_pre_query_and_set_networks' ), 10 );

			// Make sure the networks property is the same as the results.
			$this->assertSame( $results, $q->networks );

			// Make sure the network domain is `www.gechiui.com`.
			$this->assertSame( 'www.gechiui.com', $q->networks[0]->domain );
		}

		public static function filter_networks_pre_query_and_set_networks( $networks, $query ) {
			return array( get_network( self::$network_ids['www.gechiui.com/'] ) );
		}
	}

endif;
