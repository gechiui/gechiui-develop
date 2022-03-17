<?php

if ( is_multisite() ) :
	/**
	 * Test get_id_from_blogname() in multisite.
	 *
	 * @group blogname
	 * @group ms-site
	 * @group multisite
	 */
	class Tests_Multisite_GetIdFromBlogname extends GC_UnitTestCase {
		protected static $network_ids;
		protected static $site_ids;

		public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
			self::$network_ids = array(
				'www.gechiui.com/'     => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/',
				),
				'www.gechiui.net/' => array(
					'domain' => 'www.gechiui.net',
					'path'   => '/',
				),
			);

			foreach ( self::$network_ids as &$id ) {
				$id = $factory->network->create( $id );
			}
			unset( $id );

			self::$site_ids = array(
				'www.gechiui.com/'         => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'foo.gechiui.com/'     => array(
					'domain'     => 'foo.gechiui.com',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'www.gechiui.com/foo/'     => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/foo/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'www.gechiui.net/'     => array(
					'domain'     => 'www.gechiui.net',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.net/'],
				),
				'foo.gechiui.net/'     => array(
					'domain'     => 'foo.gechiui.net',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.net/'],
				),
				'www.gechiui.net/foo/' => array(
					'domain'     => 'www.gechiui.net',
					'path'       => '/foo/',
					'network_id' => self::$network_ids['www.gechiui.net/'],
				),
			);

			foreach ( self::$site_ids as &$id ) {
				$id = $factory->blog->create( $id );
			}
			unset( $id );
		}

		public static function gcTearDownAfterClass() {
			global $gcdb;

			foreach ( self::$site_ids as $id ) {
				gc_delete_site( $id );
			}

			foreach ( self::$network_ids as $id ) {
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->sitemeta} WHERE site_id = %d", $id ) );
				$gcdb->query( $gcdb->prepare( "DELETE FROM {$gcdb->site} WHERE id= %d", $id ) );
			}

			gc_update_network_site_counts();
		}

		/**
		 * @ticket 34450
		 */
		public function test_get_id_from_blogname_no_www() {
			global $current_site;

			$original_network = $current_site;
			$current_site     = get_network( self::$network_ids['www.gechiui.com/'] );

			if ( is_subdomain_install() ) {
				$expected = self::$site_ids['foo.gechiui.com/'];
			} else {
				$expected = self::$site_ids['www.gechiui.com/foo/'];
			}

			$result       = get_id_from_blogname( 'foo' );
			$current_site = $original_network;

			$this->assertSame( $expected, $result );
		}

		/**
		 * @ticket 34450
		 */
		public function test_get_id_from_blogname_www() {
			global $current_site;

			$original_network = $current_site;
			$current_site     = get_network( self::$network_ids['www.gechiui.net/'] );

			if ( is_subdomain_install() ) {
				$expected = self::$site_ids['foo.gechiui.net/'];
			} else {
				$expected = self::$site_ids['www.gechiui.net/foo/'];
			}

			$result       = get_id_from_blogname( 'foo' );
			$current_site = $original_network;

			$this->assertSame( $expected, $result );
		}

		public function test_get_id_from_blogname_invalid_slug() {
			global $current_site;

			$original_network = $current_site;
			$current_site     = get_network( self::$network_ids['www.gechiui.com/'] );

			$result       = get_id_from_blogname( 'bar' );
			$current_site = $original_network;

			$this->assertNull( $result );
		}

	}

endif;
