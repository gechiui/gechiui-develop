<?php

if ( is_multisite() ) :

	/**
	 * Test site query functionality in multisite.
	 *
	 * @group ms-site
	 * @group multisite
	 */
	class Tests_Multisite_gcSiteQuery extends GC_UnitTestCase {
		protected static $network_ids;
		protected static $site_ids;

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
				'www.w.org/make/'         => array(
					'domain'  => 'www.w.org',
					'path'    => '/make/',
					'public'  => 1,
					'lang_id' => 1,
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

		public function test_gc_site_query_by_ID() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'ID'     => self::$site_ids['www.w.org/'],
				)
			);

			$this->assertSameSets( array( self::$site_ids['www.w.org/'] ), $found );
		}

		public function test_gc_site_query_by_number() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'number' => 3,
				)
			);

			$this->assertCount( 3, $found );
		}

		public function test_gc_site_query_by_site__in_with_single_id() {
			$expected = array( self::$site_ids['www.gechiui.com/foo/'] );

			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'   => 'ids',
					'site__in' => $expected,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_site__in_with_multiple_ids() {
			$expected = array( self::$site_ids['www.gechiui.com/'], self::$site_ids['www.gechiui.com/foo/'] );

			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'   => 'ids',
					'site__in' => $expected,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		/**
		 * Test the `count` query var
		 */
		public function test_gc_site_query_by_site__in_and_count_with_multiple_ids() {
			$expected = array( self::$site_ids['www.gechiui.com/'], self::$site_ids['www.gechiui.com/foo/'] );

			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'   => 'ids',
					'count'    => true,
					'site__in' => $expected,
				)
			);

			$this->assertSame( 2, $found );
		}

		public function test_gc_site_query_by_site__not_in_with_single_id() {
			$excluded = array( self::$site_ids['www.gechiui.com/foo/'] );
			$expected = array_diff( self::$site_ids, $excluded );

			// Exclude main site since we don't have control over it here.
			$excluded[] = 1;

			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					'site__not_in' => $excluded,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_site__not_in_with_multiple_ids() {
			$excluded = array( self::$site_ids['www.gechiui.com/'], self::$site_ids['www.gechiui.com/foo/'] );
			$expected = array_diff( self::$site_ids, $excluded );

			// Exclude main site since we don't have control over it here.
			$excluded[] = 1;

			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					'site__not_in' => $excluded,
				)
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_network_id_with_order() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
				)
			);

			$expected = array(
				self::$site_ids['www.gechiui.com/'],
				self::$site_ids['www.gechiui.com/foo/'],
				self::$site_ids['www.gechiui.com/foo/bar/'],
			);

			$this->assertSame( $expected, $found );

			$found = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'DESC',
				)
			);

			$this->assertSame( array_reverse( $expected ), $found );
		}

		public function test_gc_site_query_by_network_id_with_existing_sites() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['make.gechiui.com/'],
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_network_id_with_no_existing_sites() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.net/'],
				)
			);

			$this->assertEmpty( $found );
		}

		public function test_gc_site_query_by_domain() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'domain' => 'www.w.org',
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/'],
				self::$site_ids['www.w.org/foo/'],
				self::$site_ids['www.w.org/foo/bar/'],
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain_and_offset() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'domain' => 'www.w.org',
					'offset' => 1,
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/foo/'],
				self::$site_ids['www.w.org/foo/bar/'],
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain_and_number_and_offset() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'domain' => 'www.w.org',
					'number' => 2,
					'offset' => 1,
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/foo/'],
				self::$site_ids['www.w.org/foo/bar/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain__in_with_single_domain() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'domain__in' => array( 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain__in_with_multiple_domains() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'     => 'ids',
					'domain__in' => array( 'www.gechiui.com', 'make.gechiui.com' ),
				)
			);

			$expected = array(
				self::$site_ids['www.gechiui.com/'],
				self::$site_ids['www.gechiui.com/foo/'],
				self::$site_ids['www.gechiui.com/foo/bar/'],
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain__not_in_with_single_domain() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'domain__not_in' => array( 'www.w.org' ),
				)
			);

			$expected = array(
				get_current_blog_id(), // Account for the initial site added by the test suite.
				self::$site_ids['www.gechiui.com/'],
				self::$site_ids['www.gechiui.com/foo/'],
				self::$site_ids['www.gechiui.com/foo/bar/'],
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_domain__not_in_with_multiple_domains() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'domain__not_in' => array( 'www.gechiui.com', 'www.w.org' ),
				)
			);

			$expected = array(
				get_current_blog_id(), // Account for the initial site added by the test suite.
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_path_with_expected_results() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'path'   => '/foo/bar/',
				)
			);

			$expected = array(
				self::$site_ids['www.gechiui.com/foo/bar/'],
				self::$site_ids['www.w.org/foo/bar/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_path_with_no_expected_results() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'path'   => '/foo/bar/foo/',
				)
			);

			$this->assertEmpty( $found );
		}

		// archived, mature, spam, deleted, public.

		public function test_gc_site_query_by_archived() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'archived'     => '0',
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_mature() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'mature'       => '0',
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_spam() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'spam'         => '0',
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_deleted() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'deleted'      => '0',
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_deleted_with_no_results() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'  => 'ids',
					'deleted' => '1',
				)
			);

			$this->assertEmpty( $found );
		}

		public function test_gc_site_query_by_public() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'public'       => '1',
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_lang_id_with_zero() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'lang_id'      => 0,
				)
			);

			$this->assertSameSets( array_diff( array_values( self::$site_ids ), array( self::$site_ids['www.w.org/make/'] ) ), $found );
		}

		public function test_gc_site_query_by_lang_id() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'  => 'ids',
					'lang_id' => 1,
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_lang_id_with_no_results() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'  => 'ids',
					'lang_id' => 2,
				)
			);

			$this->assertEmpty( $found );
		}

		public function test_gc_site_query_by_lang__in() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'   => 'ids',
					'lang__in' => array( 1 ),
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_lang__in_with_multiple_ids() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					// Exclude main site since we don't have control over it here.
					'site__not_in' => array( 1 ),
					'lang__in'     => array( 0, 1 ),
				)
			);

			$this->assertSameSets( array_values( self::$site_ids ), $found );
		}

		public function test_gc_site_query_by_lang__not_in() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					'lang__not_in' => array( 0 ),
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_lang__not_in_with_multiple_ids() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'       => 'ids',
					'lang__not_in' => array( 0, 1 ),
				)
			);

			$this->assertEmpty( $found );
		}

		public function test_gc_site_query_by_search_with_text_in_domain() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'ke.wordp',
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_text_in_path() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'foo',
				)
			);

			$expected = array(
				self::$site_ids['www.gechiui.com/foo/'],
				self::$site_ids['www.gechiui.com/foo/bar/'],
				self::$site_ids['make.gechiui.com/foo/'],
				self::$site_ids['www.w.org/foo/'],
				self::$site_ids['www.w.org/foo/bar/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_text_in_path_and_domain() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'make',
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_text_in_path_and_domain_order_by_domain_desc() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'  => 'ids',
					'search'  => 'make',
					'order'   => 'DESC',
					'orderby' => 'domain',
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSame( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_text_in_path_exclude_domain_from_search() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'search'         => 'make',
					'search_columns' => array( 'path' ),
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSame( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_text_in_domain_exclude_path_from_search() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'search'         => 'make',
					'search_columns' => array( 'domain' ),
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSame( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_wildcard_in_text() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields' => 'ids',
					'search' => 'm*ke',
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_wildcard_in_text_exclude_path_from_search() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'search'         => 'm*ke',
					'search_columns' => array( 'domain' ),
				)
			);

			$expected = array(
				self::$site_ids['make.gechiui.com/'],
				self::$site_ids['make.gechiui.com/foo/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		public function test_gc_site_query_by_search_with_wildcard_in_text_exclude_domain_from_search() {
			$q     = new GC_Site_Query();
			$found = $q->query(
				array(
					'fields'         => 'ids',
					'search'         => 'm*ke',
					'search_columns' => array( 'path' ),
				)
			);

			$expected = array(
				self::$site_ids['www.w.org/make/'],
			);

			$this->assertSameSets( $expected, $found );
		}

		/**
		 * @ticket 41197
		 */
		public function test_gc_site_query_cache_with_different_fields_no_count() {
			global $gcdb;
			$q                 = new GC_Site_Query();
			$query_1           = $q->query(
				array(
					'fields'     => 'all',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
				)
			);
			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
				)
			);

			$this->assertSame( $number_of_queries, $gcdb->num_queries );
		}

		/**
		 * @ticket 41197
		 */
		public function test_gc_site_query_cache_with_different_fields_active_count() {
			global $gcdb;
			$q = new GC_Site_Query();

			$query_1           = $q->query(
				array(
					'fields'     => 'all',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
					'count'      => true,
				)
			);
			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
					'count'      => true,
				)
			);
			$this->assertSame( $number_of_queries, $gcdb->num_queries );
		}

		/**
		 * @ticket 41197
		 */
		public function test_gc_site_query_cache_with_same_fields_different_count() {
			global $gcdb;
			$q = new GC_Site_Query();

			$query_1 = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
				)
			);

			$number_of_queries = $gcdb->num_queries;

			$query_2 = $q->query(
				array(
					'fields'     => 'ids',
					'network_id' => self::$network_ids['www.gechiui.com/'],
					'number'     => 3,
					'order'      => 'ASC',
					'count'      => true,
				)
			);
			$this->assertSame( $number_of_queries + 1, $gcdb->num_queries );
		}

		/**
		 * @ticket 40229
		 * @dataProvider data_gc_site_query_meta_query
		 */
		public function test_gc_site_query_meta_query( $query, $expected, $strict ) {
			if ( ! is_site_meta_supported() ) {
				$this->markTestSkipped( 'Test only runs with the blogmeta database table installed.' );
			}

			add_site_meta( self::$site_ids['www.gechiui.com/'], 'foo', 'foo' );
			add_site_meta( self::$site_ids['www.gechiui.com/foo/'], 'foo', 'bar' );
			add_site_meta( self::$site_ids['www.gechiui.com/foo/bar/'], 'foo', 'baz' );
			add_site_meta( self::$site_ids['make.gechiui.com/'], 'bar', 'baz' );
			add_site_meta( self::$site_ids['www.gechiui.com/'], 'numberfoo', 1 );
			add_site_meta( self::$site_ids['www.gechiui.com/foo/'], 'numberfoo', 2 );

			$query['fields'] = 'ids';

			$q     = new GC_Site_Query();
			$found = $q->query( $query );

			foreach ( $expected as $index => $domain_path ) {
				$expected[ $index ] = self::$site_ids[ $domain_path ];
			}

			if ( $strict ) {
				$this->assertSame( $expected, $found );
			} else {
				$this->assertSameSets( $expected, $found );
			}
		}

		public function data_gc_site_query_meta_query() {
			return array(
				array(
					array(
						'meta_key' => 'foo',
					),
					array(
						'www.gechiui.com/',
						'www.gechiui.com/foo/',
						'www.gechiui.com/foo/bar/',
					),
					false,
				),
				array(
					array(
						'meta_key'   => 'foo',
						'meta_value' => 'bar',
					),
					array(
						'www.gechiui.com/foo/',
					),
					false,
				),
				array(
					array(
						'meta_key'     => 'foo',
						'meta_value'   => array( 'bar', 'baz' ),
						'meta_compare' => 'IN',
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/foo/bar/',
					),
					false,
				),
				array(
					array(
						'meta_query' => array(
							array(
								'key'   => 'foo',
								'value' => 'bar',
							),
							array(
								'key'   => 'numberfoo',
								'value' => 2,
								'type'  => 'NUMERIC',
							),
						),
					),
					array(
						'www.gechiui.com/foo/',
					),
					false,
				),
				array(
					array(
						'meta_key' => 'foo',
						'orderby'  => 'meta_value',
						'order'    => 'ASC',
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/foo/bar/',
						'www.gechiui.com/',
					),
					true,
				),
				array(
					array(
						'meta_key' => 'foo',
						'orderby'  => 'foo',
						'order'    => 'ASC',
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/foo/bar/',
						'www.gechiui.com/',
					),
					true,
				),
				array(
					array(
						'meta_key' => 'numberfoo',
						'orderby'  => 'meta_value_num',
						'order'    => 'DESC',
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/',
					),
					true,
				),
				array(
					array(
						'meta_query' => array(
							array(
								'key'     => 'foo',
								'value'   => array( 'foo', 'bar' ),
								'compare' => 'IN',
							),
							array(
								'key' => 'numberfoo',
							),
						),
						'orderby'    => array( 'meta_value' => 'ASC' ),
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/',
					),
					true,
				),
				array(
					array(
						'meta_query' => array(
							array(
								'key'     => 'foo',
								'value'   => array( 'foo', 'bar' ),
								'compare' => 'IN',
							),
							array(
								'key' => 'numberfoo',
							),
						),
						'orderby'    => array( 'foo' => 'ASC' ),
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/',
					),
					true,
				),
				array(
					array(
						'meta_query' => array(
							array(
								'key'     => 'foo',
								'value'   => array( 'foo', 'bar' ),
								'compare' => 'IN',
							),
							'my_subquery' => array(
								'key' => 'numberfoo',
							),
						),
						'orderby'    => array( 'my_subquery' => 'DESC' ),
					),
					array(
						'www.gechiui.com/foo/',
						'www.gechiui.com/',
					),
					true,
				),
			);
		}

		/**
		 * @ticket 45749
		 * @ticket 47599
		 */
		public function test_sites_pre_query_filter_should_bypass_database_query() {
			global $gcdb;

			add_filter( 'sites_pre_query', array( __CLASS__, 'filter_sites_pre_query' ), 10, 2 );

			$num_queries = $gcdb->num_queries;

			$q       = new GC_Site_Query();
			$results = $q->query( array() );

			remove_filter( 'sites_pre_query', array( __CLASS__, 'filter_sites_pre_query' ), 10, 2 );

			// Make sure no queries were executed.
			$this->assertSame( $num_queries, $gcdb->num_queries );

			// We manually inserted a non-existing site and overrode the results with it.
			$this->assertSame( array( 555 ), $results );

			// Make sure manually setting found_sites doesn't get overwritten.
			$this->assertSame( 1, $q->found_sites );
		}

		public static function filter_sites_pre_query( $sites, $query ) {
			$query->found_sites = 1;

			return array( 555 );
		}

		/**
		 * @ticket 51333
		 */
		public function test_sites_pre_query_filter_should_set_sites_property() {
			add_filter( 'sites_pre_query', array( __CLASS__, 'filter_sites_pre_query_and_set_sites' ), 10, 2 );

			$q       = new GC_Site_Query();
			$results = $q->query( array() );

			remove_filter( 'sites_pre_query', array( __CLASS__, 'filter_sites_pre_query_and_set_sites' ), 10 );

			// Make sure the sites property is the same as the results.
			$this->assertSame( $results, $q->sites );

			// Make sure the site domain is `www.gechiui.com`.
			$this->assertSame( 'www.gechiui.com', $q->sites[0]->domain );
		}

		public static function filter_sites_pre_query_and_set_sites( $sites, $query ) {
			return array( get_site( self::$site_ids['www.gechiui.com/'] ) );
		}
	}

endif;
