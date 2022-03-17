<?php

if ( is_multisite() ) :

	/**
	 * A set of unit tests for GeChiUI Multisite
	 *
	 * @group multisite
	 */
	class Tests_Multisite extends GC_UnitTestCase {

		public function test_gcmu_log_new_registrations() {
			global $gcdb;

			$user = new GC_User( 1 );
			$ip   = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );

			gcmu_log_new_registrations( 1, 1 );

			// Currently there is no wrapper function for the registration_log.
			$reg_blog = $gcdb->get_col( $gcdb->prepare( "SELECT email FROM {$gcdb->registration_log} WHERE {$gcdb->registration_log}.blog_id = 1 AND IP LIKE %s", $ip ) );
			$this->assertSame( $user->user_email, $reg_blog[ count( $reg_blog ) - 1 ] );
		}

		/**
		 * @ticket 37392
		 */
		public function test_gc_count_sites() {
			// Create a random number of sites with each status.
			$site_ids = array(
				'public'   => self::factory()->blog->create_many(
					random_int( 0, 5 ),
					array(
						'public' => 1,
					)
				),
				'archived' => self::factory()->blog->create_many(
					random_int( 0, 5 ),
					array(
						'public'   => 0,
						'archived' => 1,
					)
				),
				'mature'   => self::factory()->blog->create_many(
					random_int( 0, 5 ),
					array(
						'public' => 0,
						'mature' => 1,
					)
				),
				'spam'     => self::factory()->blog->create_many(
					random_int( 0, 5 ),
					array(
						'public' => 0,
						'spam'   => 1,
					)
				),
				'deleted'  => self::factory()->blog->create_many(
					random_int( 0, 5 ),
					array(
						'public'  => 0,
						'deleted' => 1,
					)
				),
			);

			$counts = gc_count_sites();

			$counts_by_status = array_map( 'count', $site_ids );
			$expected         = array_merge(
				array( 'all' => array_sum( $counts_by_status ) ),
				$counts_by_status
			);
			// Add 1 to all & public for the main site.
			$expected['all']    += 1;
			$expected['public'] += 1;

			$this->assertSame( $expected, $counts );
		}
	}

endif;
