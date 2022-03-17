<?php

/**
 * @group site-health
 */
class Tests_Site_Health extends GC_UnitTestCase {
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		// Include the `GC_Site_Health` file.
		require_once ABSPATH . 'gc-admin/includes/class-gc-site-health.php';
	}

	/**
	 * Ensure Site Health reports correctly cron job reports.
	 *
	 * @ticket 47223
	 */
	public function test_cron_health_checks_critical() {
		$gc_site_health = new GC_Site_Health();

		// Clear the cron array.
		_set_cron_array( array() );

		$cron_health = $gc_site_health->get_test_scheduled_events();

		$this->assertSame( 'critical', $cron_health['status'] );
		$this->assertSame( __( 'It was not possible to check your scheduled events' ), $cron_health['label'] );
		$this->assertWPError( $gc_site_health->has_late_cron() );
		$this->assertWPError( $gc_site_health->has_missed_cron() );
	}

	/**
	 * Ensure Site Health reports correctly cron job reports.
	 *
	 * @dataProvider data_cron_health_checks
	 * @ticket 47223
	 */
	public function test_cron_health_checks( $times, $expected_status, $expected_label, $expected_late, $expected_missed ) {
		$gc_site_health = new GC_Site_Health();

		/*
		 * Clear the cron array.
		 *
		 * The core jobs may register as late/missed in the test suite as they
		 * are not run. Clearing the array ensures the site health tests are only
		 * reported based on the jobs set in the test.
		 */
		_set_cron_array( array() );
		$times = (array) $times;
		foreach ( $times as $job => $time ) {
			$timestamp = strtotime( $time );
			gc_schedule_event( $timestamp, 'daily', __FUNCTION__ . "_{$job}" );
		}

		$cron_health = $gc_site_health->get_test_scheduled_events();

		$this->assertSame( $expected_status, $cron_health['status'] );
		$this->assertSame( $expected_label, $cron_health['label'] );
		$this->assertSame( $expected_late, $gc_site_health->has_late_cron() );
		$this->assertSame( $expected_missed, $gc_site_health->has_missed_cron() );
	}

	/**
	 * Data provider for Site Health cron reports.
	 *
	 * The test suite runs with `DISABLE_GC_CRON === true` so the
	 * missed and late tests need to account for the extended periods
	 * allowed for with this flag enabled.
	 *
	 * 1. string|array Times to schedule (run through strtotime())
	 * 2. string       Expected status
	 * 3. string       Expected label
	 * 4. bool         Expected outcome has_late_cron()
	 * 5. bool         Expected outcome has_missed_cron()
	 */
	public function data_cron_health_checks() {
		return array(
			array(
				'+5 minutes',
				'good',
				__( 'Scheduled events are running' ),
				false,
				false,
			),
			array(
				'-50 minutes',
				'recommended',
				__( 'A scheduled event is late' ),
				true,
				false,
			),
			array(
				'-500 minutes',
				'recommended',
				__( 'A scheduled event has failed' ),
				false,
				true,
			),
			array(
				array(
					'-50 minutes',
					'-500 minutes',
				),
				'recommended',
				__( 'A scheduled event has failed' ),
				true,
				true,
			),
		);
	}
}
