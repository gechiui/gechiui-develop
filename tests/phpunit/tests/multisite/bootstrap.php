<?php

if ( is_multisite() ) :

	/**
	 * Tests specific to the bootstrap process of Multisite.
	 *
	 * @group ms-bootstrap
	 * @group multisite
	 */
	class Tests_Multisite_Bootstrap extends GC_UnitTestCase {
		protected static $network_ids;
		protected static $site_ids;

		public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
			self::$network_ids = array(
				'www.gechiui.com/'         => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/',
				),
				'make.gechiui.com/'    => array(
					'domain' => 'make.gechiui.com',
					'path'   => '/',
				),
				'www.gechiui.com/one/'     => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/one/',
				),
				'www.gechiui.com/one/b/'   => array(
					'domain' => 'www.gechiui.com',
					'path'   => '/one/b/',
				),
				'gechiui.net/'         => array(
					'domain' => 'gechiui.net',
					'path'   => '/',
				),
				'www.gechiui.net/'     => array(
					'domain' => 'www.gechiui.net',
					'path'   => '/',
				),
				'www.gechiui.net/two/' => array(
					'domain' => 'www.gechiui.net',
					'path'   => '/two/',
				),
				'gechiui.net/three/'   => array(
					'domain' => 'gechiui.net',
					'path'   => '/three/',
				),
			);

			foreach ( self::$network_ids as &$id ) {
				$id = $factory->network->create( $id );
			}
			unset( $id );

			self::$site_ids = array(
				'www.gechiui.com/'          => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'www.gechiui.com/foo/'      => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/foo/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'www.gechiui.com/foo/bar/'  => array(
					'domain'     => 'www.gechiui.com',
					'path'       => '/foo/bar/',
					'network_id' => self::$network_ids['www.gechiui.com/'],
				),
				'make.gechiui.com/'     => array(
					'domain'     => 'make.gechiui.com',
					'path'       => '/',
					'network_id' => self::$network_ids['make.gechiui.com/'],
				),
				'make.gechiui.com/foo/' => array(
					'domain'     => 'make.gechiui.com',
					'path'       => '/foo/',
					'network_id' => self::$network_ids['make.gechiui.com/'],
				),
				'www.w.org/'              => array(
					'domain' => 'www.w.org',
					'path'   => '/',
				),
				'www.w.org/foo/'          => array(
					'domain' => 'www.w.org',
					'path'   => '/foo/',
				),
				'www.w.org/foo/bar/'      => array(
					'domain' => 'www.w.org',
					'path'   => '/foo/bar/',
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
		 * @ticket 27003
		 * @dataProvider data_get_network_by_path
		 *
		 * @param string $expected_key The array key associated with expected data for the test.
		 * @param string $domain       The requested domain.
		 * @param string $path         The requested path.
		 * @param string $message      The message to pass for failed tests.
		 */
		public function test_get_network_by_path( $expected_key, $domain, $path, $message ) {
			$network = get_network_by_path( $domain, $path );
			$this->assertSame( self::$network_ids[ $expected_key ], $network->id, $message );
		}

		public function data_get_network_by_path() {
			return array(
				array( 'www.gechiui.com/', 'www.gechiui.com', '/', 'A standard domain and path request should work.' ),
				array( 'gechiui.net/', 'gechiui.net', '/notapath/', 'A missing path on a top level domain should find the correct network.' ),
				array( 'www.gechiui.net/', 'www.gechiui.net', '/notapath/', 'A missing path should find the correct network.' ),
				array( 'www.gechiui.com/one/', 'www.gechiui.com', '/one/', 'Should find the path despite the www.' ),
				array( 'www.gechiui.com/one/', 'www.gechiui.com', '/one/page/', 'A request with two path segments should find the correct network.' ),
				array( 'www.gechiui.com/one/b/', 'www.gechiui.com', '/one/b/', 'A request with two valid path segments should find the correct network.' ),
				array( 'www.gechiui.com/', 'site1.gechiui.com', '/one/', 'Should not find path because domains do not match.' ),
				array( 'gechiui.net/three/', 'gechiui.net', '/three/', 'A network can have a path.' ),
				array( 'www.gechiui.net/two/', 'www.gechiui.net', '/two/', 'A www network with a path can coexist with a non-www network.' ),
				array( 'gechiui.net/', 'site1.gechiui.net', '/notapath/', 'An invalid subdomain should find the top level network domain.' ),
				array( 'gechiui.net/', 'site1.gechiui.net', '/three/', 'An invalid subdomain and path should find the top level network domain.' ),
				array( 'gechiui.net/', 'x.y.gechiui.net', '/', 'An invalid two level subdomain should find the top level network domain.' ),
			);
		}

		/**
		 * @ticket 37217
		 * @dataProvider data_get_network_by_path_with_zero_path_segments
		 *
		 * @param string $expected_key The array key associated with expected data for the test.
		 * @param string $domain       The requested domain.
		 * @param string $path         The requested path.
		 * @param string $message      The message to pass for failed tests.
		 */
		public function test_get_network_by_path_with_zero_path_segments( $expected_key, $domain, $path, $message ) {
			add_filter( 'network_by_path_segments_count', '__return_zero' );

			$network = get_network_by_path( $domain, $path );

			remove_filter( 'network_by_path_segments_count', '__return_zero' );

			$this->assertSame( self::$network_ids[ $expected_key ], $network->id, $message );
		}

		public function data_get_network_by_path_with_zero_path_segments() {
			return array(
				array( 'www.gechiui.com/', 'www.gechiui.com', '/', 'A standard domain and path request should work.' ),
				array( 'gechiui.net/', 'gechiui.net', '/notapath/', 'A network matching a top level domain should be found regardless of path.' ),
				array( 'www.gechiui.net/', 'www.gechiui.net', '/notapath/', 'A network matching a domain should be found regardless of path.' ),
				array( 'www.gechiui.com/', 'www.gechiui.com', '/one/', 'Should find the network despite the www and regardless of path.' ),
				array( 'www.gechiui.com/', 'site1.gechiui.com', '/one/', 'Should find the network with the corresponding top level domain regardless of path.' ),
				array( 'www.gechiui.net/', 'www.gechiui.net', '/two/', 'A www network can coexist with a non-www network.' ),
				array( 'make.gechiui.com/', 'make.gechiui.com', '/notapath/', 'A subdomain network should be found regardless of path.' ),
				array( 'gechiui.net/', 'x.y.gechiui.net', '/', 'An invalid two level subdomain should find the top level network domain.' ),
			);
		}

		/**
		 * Even if a matching network is available, it should not match if the the filtered
		 * value for network path segments is fewer than the number of paths passed.
		 */
		public function test_get_network_by_path_with_forced_single_path_segment_returns_single_path_network() {
			add_filter( 'network_by_path_segments_count', array( $this, 'filter_network_path_segments' ) );
			$network = get_network_by_path( 'www.gechiui.com', '/one/b/' );
			remove_filter( 'network_by_path_segments_count', array( $this, 'filter_network_path_segments' ) );

			$this->assertSame( self::$network_ids['www.gechiui.com/one/'], $network->id );
		}

		public function filter_network_path_segments() {
			return 1;
		}

		/**
		 * @ticket 27003
		 * @ticket 27927
		 * @dataProvider data_get_site_by_path
		 *
		 * @param string $expected_key The array key associated with expected data for the test.
		 * @param string $domain       The requested domain.
		 * @param string $path         The requested path.
		 * @param int    $segments     Optional. Number of segments to use in `get_site_by_path()`.
		 */
		public function test_get_site_by_path( $expected_key, $domain, $path, $segments = null ) {
			$site = get_site_by_path( $domain, $path, $segments );

			if ( $expected_key ) {
				$this->assertEquals( self::$site_ids[ $expected_key ], $site->blog_id );
			} else {
				$this->assertFalse( $site );
			}
		}

		public function data_get_site_by_path() {
			return array(
				array( 'www.gechiui.com/', 'www.gechiui.com', '/notapath/' ),
				array( 'www.gechiui.com/', 'www.gechiui.com', '/notapath/' ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/' ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/' ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/', 3 ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/', 3 ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/', 2 ),
				array( 'www.gechiui.com/foo/bar/', 'www.gechiui.com', '/foo/bar/baz/', 2 ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com', '/foo/bar/baz/', 1 ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com', '/foo/bar/baz/', 1 ),
				array( 'www.gechiui.com/', 'www.gechiui.com', '/', 0 ),
				array( 'www.gechiui.com/', 'www.gechiui.com', '/', 0 ),
				array( 'make.gechiui.com/foo/', 'make.gechiui.com', '/foo/bar/baz/quz/', 4 ),
				array( 'make.gechiui.com/foo/', 'www.make.gechiui.com', '/foo/bar/baz/quz/', 4 ),
				array( 'www.w.org/', 'www.w.org', '/', 0 ),
				array( 'www.w.org/', 'www.w.org', '/notapath' ),
				array( 'www.w.org/foo/bar/', 'www.w.org', '/foo/bar/baz/' ),
				array( 'www.w.org/foo/', 'www.w.org', '/foo/bar/baz/', 1 ),

				// A site installed with www will not be found by the root domain.
				array( false, 'w.org', '/' ),
				array( false, 'w.org', '/notapath/' ),
				array( false, 'w.org', '/foo/bar/baz/' ),
				array( false, 'w.org', '/foo/bar/baz/', 1 ),

				// A site will not be found by its root domain when an invalid subdomain is requested.
				array( false, 'invalid.gechiui.com', '/' ),
				array( false, 'invalid.gechiui.com', '/foo/bar/' ),
			);
		}

		/**
		 * @ticket 27884
		 * @dataProvider data_multisite_bootstrap
		 *
		 * @param string $site_key    The array key associated with the expected site for the test.
		 * @param string $network_key The array key associated with the expected network for the test.
		 * @param string $domain      The requested domain.
		 * @param string $path        The requested path.
		 */
		public function test_multisite_bootstrap( $site_key, $network_key, $domain, $path ) {
			global $current_blog;

			$expected = array(
				'network_id' => self::$network_ids[ $network_key ],
				'site_id'    => self::$site_ids[ $site_key ],
			);

			ms_load_current_site_and_network( $domain, $path );
			$actual = array(
				'network_id' => $current_blog->site_id,
				'site_id'    => $current_blog->blog_id,
			);
			ms_load_current_site_and_network( GC_TESTS_DOMAIN, '/' );

			$this->assertEqualSetsWithIndex( $expected, $actual );
		}

		public function data_multisite_bootstrap() {
			return array(
				array( 'www.gechiui.com/', 'www.gechiui.com/', 'www.gechiui.com', '/' ),
				array( 'www.gechiui.com/', 'www.gechiui.com/', 'www.gechiui.com', '/2014/04/23/hello-world/' ),
				array( 'www.gechiui.com/', 'www.gechiui.com/', 'www.gechiui.com', '/sample-page/' ),
				array( 'www.gechiui.com/', 'www.gechiui.com/', 'www.gechiui.com', '/?p=1' ),
				array( 'www.gechiui.com/', 'www.gechiui.com/', 'www.gechiui.com', '/gc-admin/' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/foo/' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/FOO/' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/foo/2014/04/23/hello-world/' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/foo/sample-page/' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/foo/?p=1' ),
				array( 'www.gechiui.com/foo/', 'www.gechiui.com/', 'www.gechiui.com', '/foo/gc-admin/' ),
				array( 'make.gechiui.com/', 'make.gechiui.com/', 'make.gechiui.com', '/' ),
				array( 'make.gechiui.com/foo/', 'make.gechiui.com/', 'make.gechiui.com', '/foo/' ),
			);
		}

		/**
		 * @ticket 27884
		 */
		public function test_multisite_bootstrap_additional_path_segments() {
			global $current_blog;

			$expected = array(
				'network_id' => self::$network_ids['www.gechiui.com/'],
				'site_id'    => self::$site_ids['www.gechiui.com/foo/bar/'],
			);
			add_filter( 'site_by_path_segments_count', array( $this, 'filter_path_segments_to_two' ) );
			ms_load_current_site_and_network( 'www.gechiui.com', '/foo/bar/' );
			$actual = array(
				'network_id' => $current_blog->site_id,
				'site_id'    => $current_blog->blog_id,
			);
			remove_filter( 'site_by_path_segments_count', array( $this, 'filter_path_segments_to_two' ) );
			ms_load_current_site_and_network( GC_TESTS_DOMAIN, '/' );

			$this->assertEqualSetsWithIndex( $expected, $actual );
		}

		/**
		 * @ticket 37053
		 */
		public function test_get_site_by_path_returns_gc_site() {
			add_filter( 'pre_get_site_by_path', array( $this, 'filter_pre_get_site_by_path' ), 10, 3 );

			$site = get_site_by_path( 'example.com', '/foo/' );

			remove_filter( 'pre_get_site_by_path', array( $this, 'filter_pre_get_site_by_path' ), 10 );

			$this->assertInstanceOf( 'GC_Site', $site );
		}

		public function filter_path_segments_to_two() {
			return 2;
		}

		public function filter_pre_get_site_by_path( $site, $domain, $path ) {
			$site          = new stdClass();
			$site->blog_id = 100;
			$site->domain  = $domain;
			$site->path    = $path;
			$site->site_id = 1;

			return $site;
		}
	}

endif;
