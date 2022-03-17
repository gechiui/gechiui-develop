<?php

if ( is_multisite() ) :
	/**
	 * Test get_site() wrapper of GC_Site in multisite.
	 *
	 * @group ms-site
	 * @group multisite
	 */
	class Tests_Multisite_GetSite extends GC_UnitTestCase {
		protected static $site_ids;

		public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
			self::$site_ids = array(
				'www.gechiui.com/'         => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/',
				),
				'www.gechiui.com/foo/'     => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/foo/',
				),
				'www.gechiui.com/foo/bar/' => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/foo/bar/',
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

			gc_update_network_site_counts();
		}

		public function test_get_site_in_switched_state_returns_switched_site() {
			switch_to_blog( self::$site_ids['www.gechiui.com/foo/'] );
			$site = get_site();
			restore_current_blog();

			$this->assertSame( self::$site_ids['www.gechiui.com/foo/'], $site->id );
		}

	}

endif;
