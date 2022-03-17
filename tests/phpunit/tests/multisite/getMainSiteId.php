<?php

if ( is_multisite() ) :

	/**
	 * Tests for the get_main_site_id() function.
	 *
	 * @group ms-site
	 * @group multisite
	 */
	class Tests_Multisite_GetMainSiteId extends GC_UnitTestCase {
		protected static $network_ids;
		protected static $site_ids;

		public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
			self::$network_ids = array(
				'www.gechiui.com/' => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/',
				),
				'gc.org/'        => array(
					'domain' => 'gc.org',
					'path'   => '/',
				), // A network with no sites.
			);

			foreach ( self::$network_ids as &$id ) {
				$id = $factory->network->create( $id );
			}
			unset( $id );

			self::$site_ids = array(
				'www.w.org/'         => array(
					'domain' => 'www.w.org',
					'path'   => '/',
				),
				'www.gechiui.com/'     => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'www.gechiui.com/foo/' => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/foo/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
			);

			foreach ( self::$site_ids as &$id ) {
				$id = $factory->blog->create( $id );
			}
			unset( $id );
		}

		public static function gcTearDownAfterClass() {
			foreach ( self::$site_ids as $id ) {
				gc_delete_site( $id );
			}

			global $gcdb;

			foreach ( self::$network_ids as $id ) {
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->sitemeta} WHERE site_id = %d", $id ) );
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->site} WHERE id= %d", $id ) );
			}

			gc_update_network_site_counts();
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_on_main_site_returns_self() {
			$this->assertSame( get_current_blog_id(), get_main_site_id() );
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_returns_main_site_in_switched_context() {
			$main_site_id  = get_current_blog_id();
			$other_site_id = self::$site_ids['www.w.org/'];

			switch_to_blog( $other_site_id );
			$result = get_main_site_id();
			restore_current_blog();

			$this->assertSame( $main_site_id, $result );
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_with_different_network_returns_correct_id() {
			$this->assertSame( self::$site_ids['www.gechiui.com/'], get_main_site_id( self::$network_ids['www.gechiui.com/'] ) );
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_on_network_without_site_returns_0() {
			$this->assertSame( 0, get_main_site_id( self::$network_ids['gc.org/'] ) );
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_on_invalid_network_returns_0() {
			$this->assertSame( 0, get_main_site_id( 333 ) );
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_filtered() {
			add_filter( 'pre_get_main_site_id', array( $this, 'filter_get_main_site_id' ) );
			$result = get_main_site_id();

			$this->assertSame( 333, $result );
		}

		public function filter_get_main_site_id() {
			return 333;
		}

		/**
		 * @ticket 29684
		 */
		public function test_get_main_site_id_filtered_depending_on_network() {
			add_filter( 'pre_get_main_site_id', array( $this, 'filter_get_main_site_id_depending_on_network' ), 10, 2 );
			$result = get_main_site_id( self::$network_ids['www.gechiui.com/'] );

			$this->assertSame( 333, $result );
		}

		public function filter_get_main_site_id_depending_on_network( $main_site_id, $network ) {
			// Override main site ID for a specific network for the test.
			if ( $network->id === (int) self::$network_ids['www.gechiui.com/'] ) {
				return 333;
			}

			return $main_site_id;
		}

		/**
		 * @ticket 41936
		 */
		public function test_get_main_site_id_with_property_value() {
			global $current_site;

			$original_main_site_id = $current_site->blog_id;
			$current_site->blog_id = '123';

			$result = get_main_site_id();

			$current_site->blog_id = $original_main_site_id;

			$this->assertSame( 123, $result );
		}

		/**
		 * @ticket 41936
		 */
		public function test_get_main_site_id_filtered_with_property_value() {
			global $current_site;

			$original_main_site_id = $current_site->blog_id;
			$current_site->blog_id = '123';

			add_filter( 'pre_get_main_site_id', array( $this, 'filter_get_main_site_id' ) );
			$result = get_main_site_id();

			$current_site->blog_id = $original_main_site_id;

			$this->assertSame( 333, $result );
		}
	}

endif;
