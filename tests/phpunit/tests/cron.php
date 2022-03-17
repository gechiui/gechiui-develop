<?php

/**
 * Test the cron scheduling functions
 *
 * @group cron
 */
class Tests_Cron extends GC_UnitTestCase {
	/**
	 * @var array Cron array for testing preflight filters.
	 */
	private $preflight_cron_array;

	/**
	 * @var int Timestamp of now() + 30 minutes;
	 */
	private $plus_thirty_minutes;

	public function set_up() {
		parent::set_up();
		// Make sure the schedule is clear.
		_set_cron_array( array() );
		$this->preflight_cron_array = array();
		$this->plus_thirty_minutes  = strtotime( '+30 minutes' );
	}

	public function tear_down() {
		// Make sure the schedule is clear.
		_set_cron_array( array() );
		parent::tear_down();
	}

	public function test_gc_get_schedule_empty() {
		// Nothing scheduled.
		$hook = __FUNCTION__;
		$this->assertFalse( gc_get_schedule( $hook ) );
	}

	public function test_schedule_event_single() {
		// Schedule an event and make sure it's returned by gc_next_scheduled().
		$hook      = __FUNCTION__;
		$timestamp = strtotime( '+1 hour' );

		$scheduled = gc_schedule_single_event( $timestamp, $hook );
		$this->assertTrue( $scheduled );
		$this->assertSame( $timestamp, gc_next_scheduled( $hook ) );

		// It's a non-recurring event.
		$this->assertFalse( gc_get_schedule( $hook ) );

	}

	public function test_schedule_event_single_args() {
		// Schedule an event with arguments and make sure it's returned by gc_next_scheduled().
		$hook      = 'event';
		$timestamp = strtotime( '+1 hour' );
		$args      = array( 'foo' );

		$scheduled = gc_schedule_single_event( $timestamp, $hook, $args );
		$this->assertTrue( $scheduled );
		// This returns the timestamp only if we provide matching args.
		$this->assertSame( $timestamp, gc_next_scheduled( $hook, $args ) );
		// These don't match so return nothing.
		$this->assertFalse( gc_next_scheduled( $hook ) );
		$this->assertFalse( gc_next_scheduled( $hook, array( 'bar' ) ) );

		// It's a non-recurring event.
		$this->assertFalse( gc_get_schedule( $hook, $args ) );
	}

	public function test_schedule_event() {
		// Schedule an event and make sure it's returned by gc_next_scheduled().
		$hook      = __FUNCTION__;
		$recur     = 'hourly';
		$timestamp = strtotime( '+1 hour' );

		$scheduled = gc_schedule_event( $timestamp, $recur, $hook );
		$this->assertTrue( $scheduled );
		// It's scheduled for the right time.
		$this->assertSame( $timestamp, gc_next_scheduled( $hook ) );
		// It's a recurring event.
		$this->assertSame( $recur, gc_get_schedule( $hook ) );
	}

	public function test_schedule_event_args() {
		// Schedule an event and make sure it's returned by gc_next_scheduled().
		$hook      = 'event';
		$timestamp = strtotime( '+1 hour' );
		$recur     = 'hourly';
		$args      = array( 'foo' );

		$scheduled = gc_schedule_event( $timestamp, 'hourly', $hook, $args );
		$this->assertTrue( $scheduled );
		// This returns the timestamp only if we provide matching args.
		$this->assertSame( $timestamp, gc_next_scheduled( $hook, $args ) );
		// These don't match so return nothing.
		$this->assertFalse( gc_next_scheduled( $hook ) );
		$this->assertFalse( gc_next_scheduled( $hook, array( 'bar' ) ) );

		$this->assertSame( $recur, gc_get_schedule( $hook, $args ) );
	}

	/**
	 * Tests that a call to gc_schedule_event() on a site without any scheduled events
	 * does not result in a PHP deprecation warning on PHP 8.1 or higher.
	 *
	 * The warning that we should not see:
	 * `Deprecated: Automatic conversion of false to array is deprecated`.
	 *
	 * @ticket 53635
	 *
	 * @covers ::gc_schedule_event
	 */
	public function test_gc_schedule_event_without_cron_option_does_not_throw_warning() {
		delete_option( 'cron' );

		// Verify that the cause of the error is in place.
		$this->assertFalse( _get_cron_array(), '_get_cron_array() does not return false' );

		$hook      = __FUNCTION__;
		$timestamp = strtotime( '+10 minutes' );

		// Add an event.
		$this->assertTrue( gc_schedule_event( $timestamp, 'daily', $hook ) );
	}

	/**
	 * Tests that a call to gc_schedule_single_event() on a site without any scheduled events
	 * does not result in the value "false" being added into the cron array.
	 *
	 * @ticket 53950
	 *
	 * @covers ::gc_schedule_single_event
	 */
	public function test_gc_schedule_single_event_without_cron_option() {
		delete_option( 'cron' );

		// Verify that the cause of the error is in place.
		$this->assertFalse( _get_cron_array(), '_get_cron_array() does not return false' );

		$hook      = __FUNCTION__;
		$timestamp = strtotime( '+10 minutes' );

		// Add an event.
		$this->assertTrue( gc_schedule_single_event( $timestamp, $hook ), 'Scheduling single event failed' );

		// Verify that "false" is not a value in the final cron array.
		$this->assertNotContains( false, get_option( 'cron' ), 'Resulting cron array contains the value "false"' );
	}

	public function test_unschedule_event() {
		// Schedule an event and make sure it's returned by gc_next_scheduled().
		$hook      = __FUNCTION__;
		$timestamp = strtotime( '+1 hour' );

		gc_schedule_single_event( $timestamp, $hook );
		$this->assertSame( $timestamp, gc_next_scheduled( $hook ) );

		// Now unschedule it and make sure it's gone.
		$unscheduled = gc_unschedule_event( $timestamp, $hook );
		$this->assertTrue( $unscheduled );
		$this->assertFalse( gc_next_scheduled( $hook ) );
	}

	public function test_clear_schedule() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );

		// Schedule several events with and without arguments.
		gc_schedule_single_event( strtotime( '+1 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+2 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+3 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+4 hour' ), $hook, $args );

		// Make sure they're returned by gc_next_scheduled().
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook ) );
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the no args events and make sure it's gone.
		$hook_unscheduled = gc_clear_scheduled_hook( $hook );
		$this->assertSame( 2, $hook_unscheduled );
		$this->assertFalse( gc_next_scheduled( $hook ) );
		// The args events should still be there.
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the args events and make sure they're gone too.
		// Note: gc_clear_scheduled_hook() expects args passed directly, rather than as an array.
		gc_clear_scheduled_hook( $hook, $args );
		$this->assertFalse( gc_next_scheduled( $hook, $args ) );
	}

	public function test_clear_undefined_schedule() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );

		gc_schedule_single_event( strtotime( '+1 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+2 hour' ), $hook, $args );

		// Clear the schedule for no args events and ensure no events are cleared.
		$hook_unscheduled = gc_clear_scheduled_hook( $hook );
		$this->assertSame( 0, $hook_unscheduled );
	}

	public function test_clear_schedule_multiple_args() {
		$hook = __FUNCTION__;
		$args = array( 'arg1', 'arg2' );

		// Schedule several events with and without arguments.
		gc_schedule_single_event( strtotime( '+1 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+2 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+3 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+4 hour' ), $hook, $args );

		// Make sure they're returned by gc_next_scheduled().
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook ) );
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the no args events and make sure it's gone.
		gc_clear_scheduled_hook( $hook );
		$this->assertFalse( gc_next_scheduled( $hook ) );
		// The args events should still be there.
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the args events and make sure they're gone too.
		// Note: gc_clear_scheduled_hook() used to expect args passed directly, rather than as an array pre GC 3.0.
		gc_clear_scheduled_hook( $hook, $args );
		$this->assertFalse( gc_next_scheduled( $hook, $args ) );
	}

	/**
	 * @ticket 10468
	 */
	public function test_clear_schedule_new_args() {
		$hook       = __FUNCTION__;
		$args       = array( 'arg1' );
		$multi_hook = __FUNCTION__ . '_multi';
		$multi_args = array( 'arg2', 'arg3' );

		// Schedule several events with and without arguments.
		gc_schedule_single_event( strtotime( '+1 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+2 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+3 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+4 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+5 hour' ), $multi_hook, $multi_args );
		gc_schedule_single_event( strtotime( '+6 hour' ), $multi_hook, $multi_args );

		// Make sure they're returned by gc_next_scheduled().
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook ) );
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the no args events and make sure it's gone.
		gc_clear_scheduled_hook( $hook );
		$this->assertFalse( gc_next_scheduled( $hook ) );
		// The args events should still be there.
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the args events and make sure they're gone too.
		// gc_clear_scheduled_hook() should take args as an array like the other functions.
		gc_clear_scheduled_hook( $hook, $args );
		$this->assertFalse( gc_next_scheduled( $hook, $args ) );

		// Clear the schedule for the args events and make sure they're gone too.
		// gc_clear_scheduled_hook() should take args as an array like the other functions and does from GC 3.0.
		gc_clear_scheduled_hook( $multi_hook, $multi_args );
		$this->assertFalse( gc_next_scheduled( $multi_hook, $multi_args ) );
	}

	/**
	 * @ticket 18997
	 */
	public function test_unschedule_hook() {
		$hook = __FUNCTION__;
		$args = array( 'foo' );

		// Schedule several events with and without arguments.
		gc_schedule_single_event( strtotime( '+1 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+2 hour' ), $hook );
		gc_schedule_single_event( strtotime( '+3 hour' ), $hook, $args );
		gc_schedule_single_event( strtotime( '+4 hour' ), $hook, $args );

		// Make sure they're returned by gc_next_scheduled().
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook ) );
		$this->assertGreaterThan( 0, gc_next_scheduled( $hook, $args ) );

		// Clear the schedule and make sure it's gone.
		$unschedule_hook = gc_unschedule_hook( $hook );
		$this->assertSame( 4, $unschedule_hook );
		$this->assertFalse( gc_next_scheduled( $hook ) );
	}

	public function test_unschedule_undefined_hook() {
		$hook           = __FUNCTION__;
		$unrelated_hook = __FUNCTION__ . '_two';

		// Attempt to clear schedule on non-existent hook.
		$unschedule_hook = gc_unschedule_hook( $hook );
		$this->assertSame( 0, $unschedule_hook );
		$this->assertFalse( gc_next_scheduled( $hook ) );

		// Repeat tests with populated cron array.
		gc_schedule_single_event( strtotime( '+1 hour' ), $unrelated_hook );
		gc_schedule_single_event( strtotime( '+2 hour' ), $unrelated_hook );

		$unschedule_hook = gc_unschedule_hook( $hook );
		$this->assertSame( 0, $unschedule_hook );
		$this->assertFalse( gc_next_scheduled( $hook ) );
	}

	/**
	 * @ticket 6966
	 */
	public function test_duplicate_event() {
		// Duplicate events close together should be skipped.
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+5 minutes' );
		$ts2  = strtotime( '+3 minutes' );

		// First one works.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );

		// Subsequent ones are ignored.
		$this->assertFalse( gc_schedule_single_event( $ts2, $hook, $args ) );
		$subsequent = gc_schedule_single_event( $ts2, $hook, $args, true );
		$this->assertWPError( $subsequent );
		$this->assertSame( 'duplicate_event', $subsequent->get_error_code() );

		// The next event should be at +5 minutes, not +3.
		$this->assertSame( $ts1, gc_next_scheduled( $hook, $args ) );
	}

	/**
	 * @ticket 6966
	 */
	public function test_not_duplicate_event() {
		// Duplicate events far apart should work normally.
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+30 minutes' );
		$ts2  = strtotime( '+3 minutes' );

		// First one works.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );
		// Second works too.
		$this->assertTrue( gc_schedule_single_event( $ts2, $hook, $args ) );

		// The next event should be at +3 minutes, even though that one was scheduled second.
		$this->assertSame( $ts2, gc_next_scheduled( $hook, $args ) );
		gc_unschedule_event( $ts2, $hook, $args );
		// Following event at +30 minutes should be there too.
		$this->assertSame( $ts1, gc_next_scheduled( $hook, $args ) );
	}

	public function test_not_duplicate_event_reversed() {
		// Duplicate events far apart should work normally regardless of order.
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+3 minutes' );
		$ts2  = strtotime( '+30 minutes' );

		// First one works.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );
		// Second works too.
		$this->assertTrue( gc_schedule_single_event( $ts2, $hook, $args ) );

		// The next event should be at +3 minutes.
		$this->assertSame( $ts1, gc_next_scheduled( $hook, $args ) );
		gc_unschedule_event( $ts1, $hook, $args );
		// Following event should be there too.
		$this->assertSame( $ts2, gc_next_scheduled( $hook, $args ) );
	}

	/**
	 * Ensure the pre_scheduled_event filter prevents
	 * modification of the cron_array_option.
	 *
	 * @ticket 32656
	 */
	public function test_pre_schedule_event_filter() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+30 minutes' );
		$ts2  = strtotime( '+3 minutes' );

		$expected = _get_cron_array();

		add_filter( 'pre_schedule_event', array( $this, 'filter_pre_schedule_event_filter' ), 10, 2 );

		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );
		$this->assertTrue( gc_schedule_event( $ts2, 'hourly', $hook ) );

		// Check cron option is unchanged.
		$this->assertSame( $expected, _get_cron_array() );

		$expected_preflight[ $ts2 ][ $hook ][ md5( serialize( array() ) ) ] = array(
			'schedule' => 'hourly',
			'interval' => HOUR_IN_SECONDS,
			'args'     => array(),
		);

		$expected_preflight[ $ts1 ][ $hook ][ md5( serialize( $args ) ) ] = array(
			'schedule' => false,
			'interval' => 0,
			'args'     => $args,
		);

		$this->assertSame( $expected_preflight, $this->preflight_cron_array );
	}

	/**
	 * Filter the scheduling of events to use the preflight array.
	 */
	public function filter_pre_schedule_event_filter( $null, $event ) {
		$key = md5( serialize( $event->args ) );

		$this->preflight_cron_array[ $event->timestamp ][ $event->hook ][ $key ] = array(
			'schedule' => $event->schedule,
			'interval' => isset( $event->interval ) ? $event->interval : 0,
			'args'     => $event->args,
		);
		uksort( $this->preflight_cron_array, 'strnatcasecmp' );
		return true;
	}

	/**
	 * Ensure the pre_reschedule_event filter prevents
	 * modification of the cron_array_option.
	 *
	 * @ticket 32656
	 */
	public function test_pre_reschedule_event_filter() {
		$hook = __FUNCTION__;
		$ts1  = strtotime( '+30 minutes' );

		// Add an event.
		$this->assertTrue( gc_schedule_event( $ts1, 'hourly', $hook ) );
		$expected = _get_cron_array();

		// Add preflight filter.
		add_filter( 'pre_reschedule_event', '__return_true' );

		// Reschedule event with preflight filter in place.
		gc_reschedule_event( $ts1, 'daily', $hook );

		// Check cron option is unchanged.
		$this->assertSame( $expected, _get_cron_array() );
	}

	/**
	 * Ensure the pre_unschedule_event filter prevents
	 * modification of the cron_array_option.
	 *
	 * @ticket 32656
	 */
	public function test_pre_unschedule_event_filter() {
		$hook = __FUNCTION__;
		$ts1  = strtotime( '+30 minutes' );

		// Add an event.
		$this->assertTrue( gc_schedule_event( $ts1, 'hourly', $hook ) );
		$expected = _get_cron_array();

		// Add preflight filter.
		add_filter( 'pre_unschedule_event', '__return_true' );

		// Unschedule event with preflight filter in place.
		gc_unschedule_event( $ts1, $hook );

		// Check cron option is unchanged.
		$this->assertSame( $expected, _get_cron_array() );
	}

	/**
	 * Ensure the clearing scheduled hooks filter prevents
	 * modification of the cron_array_option.
	 *
	 * @ticket 32656
	 */
	public function test_pre_clear_scheduled_hook_filters() {
		$hook = __FUNCTION__;
		$ts1  = strtotime( '+30 minutes' );

		// Add an event.
		$this->assertTrue( gc_schedule_event( $ts1, 'hourly', $hook ) );
		$expected = _get_cron_array();

		// Add preflight filters.
		add_filter( 'pre_clear_scheduled_hook', '__return_true' );
		add_filter( 'pre_unschedule_hook', '__return_zero' );

		// Unschedule event with preflight filter in place.
		gc_clear_scheduled_hook( $hook );

		// Check cron option is unchanged.
		$this->assertSame( $expected, _get_cron_array() );

		// Unschedule all events with preflight filter in place.
		gc_unschedule_hook( $hook );

		// Check cron option is unchanged.
		$this->assertSame( $expected, _get_cron_array() );
	}

	/**
	 * Ensure the preflight hooks for scheduled events
	 * return a filtered value as expected.
	 *
	 * @ticket 32656
	 */
	public function test_pre_scheduled_event_hooks() {
		add_filter( 'pre_get_scheduled_event', array( $this, 'filter_pre_scheduled_event_hooks' ) );

		$actual  = gc_get_scheduled_event( 'preflight_event', array(), $this->plus_thirty_minutes );
		$actual2 = gc_next_scheduled( 'preflight_event', array() );

		$expected = (object) array(
			'hook'      => 'preflight_event',
			'timestamp' => $this->plus_thirty_minutes,
			'schedule'  => false,
			'args'      => array(),
		);

		$this->assertEquals( $expected, $actual );
		$this->assertSame( $expected->timestamp, $actual2 );
	}

	public function filter_pre_scheduled_event_hooks() {
		return (object) array(
			'hook'      => 'preflight_event',
			'timestamp' => $this->plus_thirty_minutes,
			'schedule'  => false,
			'args'      => array(),
		);
	}

	/**
	 * Ensure gc_get_scheduled_event() returns the expected one off events.
	 *
	 * When no timestamp is specified, the next event should be returned.
	 * When a timestamp is specified, a particular event should be returned.
	 *
	 * @ticket 45976.
	 */
	public function test_get_scheduled_event_singles() {
		$hook    = __FUNCTION__;
		$args    = array( 'arg1' );
		$ts_late = strtotime( '+30 minutes' );
		$ts_next = strtotime( '+3 minutes' );

		$expected1 = (object) array(
			'hook'      => $hook,
			'timestamp' => $ts_late,
			'schedule'  => false,
			'args'      => $args,
		);

		$expected2 = (object) array(
			'hook'      => $hook,
			'timestamp' => $ts_next,
			'schedule'  => false,
			'args'      => $args,
		);

		// Schedule late running event.
		gc_schedule_single_event( $ts_late, $hook, $args );
		// Schedule next running event.
		gc_schedule_single_event( $ts_next, $hook, $args );

		// Late running, timestamp specified.
		$this->assertEquals( $expected1, gc_get_scheduled_event( $hook, $args, $ts_late ) );

		// Next running, timestamp specified.
		$this->assertEquals( $expected2, gc_get_scheduled_event( $hook, $args, $ts_next ) );

		// Next running, no timestamp specified.
		$this->assertEquals( $expected2, gc_get_scheduled_event( $hook, $args ) );
	}

	/**
	 * Ensure gc_get_scheduled_event() returns the expected recurring events.
	 *
	 * When no timestamp is specified, the next event should be returned.
	 * When a timestamp is specified, a particular event should be returned.
	 *
	 * @ticket 45976.
	 */
	public function test_get_scheduled_event_recurring() {
		$hook     = __FUNCTION__;
		$args     = array( 'arg1' );
		$ts_late  = strtotime( '+30 minutes' );
		$ts_next  = strtotime( '+3 minutes' );
		$schedule = 'hourly';
		$interval = HOUR_IN_SECONDS;

		$expected1 = (object) array(
			'hook'      => $hook,
			'timestamp' => $ts_late,
			'schedule'  => $schedule,
			'args'      => $args,
			'interval'  => $interval,
		);

		$expected2 = (object) array(
			'hook'      => $hook,
			'timestamp' => $ts_next,
			'schedule'  => $schedule,
			'args'      => $args,
			'interval'  => $interval,
		);

		// Schedule late running event.
		gc_schedule_event( $ts_late, $schedule, $hook, $args );
		// Schedule next running event.
		gc_schedule_event( $ts_next, $schedule, $hook, $args );

		// Late running, timestamp specified.
		$this->assertEquals( $expected1, gc_get_scheduled_event( $hook, $args, $ts_late ) );

		// Next running, timestamp specified.
		$this->assertEquals( $expected2, gc_get_scheduled_event( $hook, $args, $ts_next ) );

		// Next running, no timestamp specified.
		$this->assertEquals( $expected2, gc_get_scheduled_event( $hook, $args ) );
	}

	/**
	 * Ensure gc_get_scheduled_event() returns false when expected.
	 *
	 * @ticket 45976.
	 */
	public function test_get_scheduled_event_false() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts   = strtotime( '+3 minutes' );

		// No scheduled events.
		// - With timestamp.
		$this->assertFalse( gc_get_scheduled_event( $hook, $args, $ts ) );
		// - Get next, none scheduled.
		$this->assertFalse( gc_get_scheduled_event( $hook, $args ) );

		// Schedule an event.
		gc_schedule_event( $ts, $hook, $args );
		// - Unregistered timestamp.
		$this->assertFalse( gc_get_scheduled_event( $hook, $args, strtotime( '+30 minutes' ) ) );
		// - Invalid timestamp.
		$this->assertFalse( gc_get_scheduled_event( $hook, $args, 'Words Fail!' ) );

	}

	/**
	 * Ensure any past event counts as a duplicate.
	 *
	 * @ticket 44818
	 */
	public function test_duplicate_past_event() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '-14 minutes' );
		$ts2  = strtotime( '+5 minutes' );
		$ts3  = strtotime( '-2 minutes' );

		// First event scheduled successfully.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );

		// Second event fails.
		$this->assertFalse( gc_schedule_single_event( $ts2, $hook, $args ) );

		// Third event fails.
		$this->assertFalse( gc_schedule_single_event( $ts3, $hook, $args ) );

		// Fourth event fails.
		$subsequent = gc_schedule_single_event( $ts3, $hook, $args, true );
		$this->assertWPError( $subsequent );
		$this->assertSame( 'duplicate_event', $subsequent->get_error_code() );
	}

	/**
	 * Ensure any near future event counts as a duplicate.
	 *
	 * @ticket 44818
	 */
	public function test_duplicate_near_future_event() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+4 minutes' );
		$ts2  = strtotime( '-15 minutes' );
		$ts3  = strtotime( '+12 minutes' );

		// First event scheduled successfully.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );

		// Second event fails.
		$this->assertFalse( gc_schedule_single_event( $ts2, $hook, $args ) );

		// Third event fails.
		$this->assertFalse( gc_schedule_single_event( $ts3, $hook, $args ) );

		// Fourth event fails.
		$subsequent = gc_schedule_single_event( $ts3, $hook, $args, true );
		$this->assertWPError( $subsequent );
		$this->assertSame( 'duplicate_event', $subsequent->get_error_code() );

	}

	/**
	 * Duplicate future events are disallowed.
	 *
	 * @ticket 44818
	 */
	public function test_duplicate_future_event() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+15 minutes' );
		$ts2  = strtotime( '-600 seconds', $ts1 );
		$ts3  = strtotime( '+600 seconds', $ts1 );

		// First event scheduled successfully.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );

		// Events within ten minutes should fail.
		$this->assertFalse( gc_schedule_single_event( $ts2, $hook, $args ) );
		$this->assertFalse( gc_schedule_single_event( $ts3, $hook, $args ) );

		$subsequent = gc_schedule_single_event( $ts3, $hook, $args, true );
		$this->assertWPError( $subsequent );
		$this->assertSame( 'duplicate_event', $subsequent->get_error_code() );
	}

	/**
	 * Future events are allowed.
	 *
	 * @ticket 44818
	 */
	public function test_not_duplicate_future_event() {
		$hook = __FUNCTION__;
		$args = array( 'arg1' );
		$ts1  = strtotime( '+15 minutes' );
		$ts2  = strtotime( '-601 seconds', $ts1 );
		$ts3  = strtotime( '+601 seconds', $ts1 );

		// First event scheduled successfully.
		$this->assertTrue( gc_schedule_single_event( $ts1, $hook, $args ) );

		// Events over ten minutes should work.
		$this->assertTrue( gc_schedule_single_event( $ts2, $hook, $args ) );
		$this->assertTrue( gc_schedule_single_event( $ts3, $hook, $args ) );
	}

	/**
	 * @ticket 49961
	 */
	public function test_invalid_timestamp_for_event_returns_error() {
		$single_event      = gc_schedule_single_event( -50, 'hook', array(), true );
		$event             = gc_schedule_event( -50, 'daily', 'hook', array(), true );
		$rescheduled_event = gc_reschedule_event( -50, 'daily', 'hook', array(), true );
		$unscheduled_event = gc_unschedule_event( -50, 'hook', array(), true );

		$this->assertWPError( $single_event );
		$this->assertSame( 'invalid_timestamp', $single_event->get_error_code() );

		$this->assertWPError( $event );
		$this->assertSame( 'invalid_timestamp', $event->get_error_code() );

		$this->assertWPError( $rescheduled_event );
		$this->assertSame( 'invalid_timestamp', $rescheduled_event->get_error_code() );

		$this->assertWPError( $unscheduled_event );
		$this->assertSame( 'invalid_timestamp', $unscheduled_event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_invalid_recurrence_for_event_returns_error() {
		$event             = gc_schedule_event( time(), 'invalid', 'hook', array(), true );
		$rescheduled_event = gc_reschedule_event( time(), 'invalid', 'hook', array(), true );

		$this->assertWPError( $event );
		$this->assertSame( 'invalid_schedule', $event->get_error_code() );

		$this->assertWPError( $rescheduled_event );
		$this->assertSame( 'invalid_schedule', $rescheduled_event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_disallowed_event_returns_false_when_gc_error_is_set_to_false() {
		add_filter( 'schedule_event', '__return_false' );

		$single_event      = gc_schedule_single_event( time(), 'hook', array() );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array() );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array() );

		$this->assertFalse( $single_event );
		$this->assertFalse( $event );
		$this->assertFalse( $rescheduled_event );
	}

	/**
	 * @ticket 49961
	 */
	public function test_disallowed_event_returns_error_when_gc_error_is_set_to_true() {
		add_filter( 'schedule_event', '__return_false' );

		$single_event      = gc_schedule_single_event( time(), 'hook', array(), true );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array(), true );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array(), true );

		$this->assertWPError( $single_event );
		$this->assertSame( 'schedule_event_false', $single_event->get_error_code() );

		$this->assertWPError( $event );
		$this->assertSame( 'schedule_event_false', $event->get_error_code() );

		$this->assertWPError( $rescheduled_event );
		$this->assertSame( 'schedule_event_false', $rescheduled_event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_schedule_short_circuit_with_error_returns_false_when_gc_error_is_set_to_false() {
		$return_error = function( $pre, $event, $gc_error ) {
			$this->assertFalse( $gc_error );

			return new GC_Error(
				'my_error',
				'An error ocurred'
			);
		};

		// Add filters which return a GC_Error:
		add_filter( 'pre_schedule_event', $return_error, 10, 3 );
		add_filter( 'pre_reschedule_event', $return_error, 10, 3 );

		// Schedule events without the `$gc_error` parameter:
		$single_event      = gc_schedule_single_event( time(), 'hook', array() );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array() );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array() );

		// Ensure boolean false is returned:
		$this->assertFalse( $single_event );
		$this->assertFalse( $event );
		$this->assertFalse( $rescheduled_event );
	}

	/**
	 * @ticket 49961
	 */
	public function test_schedule_short_circuit_with_error_returns_error_when_gc_error_is_set_to_true() {
		$return_error = function( $pre, $event, $gc_error ) {
			$this->assertTrue( $gc_error );

			return new GC_Error(
				'my_error',
				'An error ocurred'
			);
		};

		// Add filters which return a GC_Error:
		add_filter( 'pre_schedule_event', $return_error, 10, 3 );
		add_filter( 'pre_reschedule_event', $return_error, 10, 3 );

		// Schedule events with the `$gc_error` parameter:
		$single_event      = gc_schedule_single_event( time(), 'hook', array(), true );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array(), true );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array(), true );

		// Ensure the error object is returned:
		$this->assertWPError( $single_event );
		$this->assertSame( 'my_error', $single_event->get_error_code() );

		$this->assertWPError( $event );
		$this->assertSame( 'my_error', $event->get_error_code() );

		$this->assertWPError( $rescheduled_event );
		$this->assertSame( 'my_error', $rescheduled_event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_schedule_short_circuit_with_false_returns_false_when_gc_error_is_set_to_false() {
		// Add filters which return false:
		add_filter( 'pre_schedule_event', '__return_false' );
		add_filter( 'pre_reschedule_event', '__return_false' );

		// Schedule events without the `$gc_error` parameter:
		$single_event      = gc_schedule_single_event( time(), 'hook', array() );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array() );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array() );

		// Ensure false is returned:
		$this->assertFalse( $single_event );
		$this->assertFalse( $event );
		$this->assertFalse( $rescheduled_event );
	}

	/**
	 * @ticket 49961
	 */
	public function test_schedule_short_circuit_with_false_returns_error_when_gc_error_is_set_to_true() {
		// Add filters which return false:
		add_filter( 'pre_schedule_event', '__return_false' );
		add_filter( 'pre_reschedule_event', '__return_false' );

		// Schedule events with the `$gc_error` parameter:
		$single_event      = gc_schedule_single_event( time(), 'hook', array(), true );
		$event             = gc_schedule_event( time(), 'daily', 'hook', array(), true );
		$rescheduled_event = gc_reschedule_event( time(), 'daily', 'hook', array(), true );

		// Ensure an error object is returned:
		$this->assertWPError( $single_event );
		$this->assertSame( 'pre_schedule_event_false', $single_event->get_error_code() );

		$this->assertWPError( $event );
		$this->assertSame( 'pre_schedule_event_false', $event->get_error_code() );

		$this->assertWPError( $rescheduled_event );
		$this->assertSame( 'pre_reschedule_event_false', $rescheduled_event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 * @expectedDeprecated gc_clear_scheduled_hook
	 */
	public function test_deprecated_argument_usage_of_gc_clear_scheduled_hook() {
		$return_pre = function( $pre, $hook, $args, $gc_error ) {
			$this->assertSame( array( 1, 2, 3 ), $args );
			$this->assertFalse( $gc_error );

			return $pre;
		};

		add_filter( 'pre_clear_scheduled_hook', $return_pre, 10, 4 );

		$cleared = gc_clear_scheduled_hook( 'hook', 1, 2, 3 );

		$this->assertSame( 0, $cleared );
	}

	/**
	 * @ticket 49961
	 */
	public function test_clear_scheduled_hook_returns_default_pre_filter_error_when_gc_error_is_set_to_true() {
		add_filter( 'pre_unschedule_event', '__return_false' );

		gc_schedule_single_event( strtotime( '+1 hour' ), 'test_hook' );
		gc_schedule_single_event( strtotime( '+2 hours' ), 'test_hook' );

		$cleared = gc_clear_scheduled_hook( 'test_hook', array(), true );

		$this->assertWPError( $cleared );
		$this->assertSame(
			array(
				'pre_unschedule_event_false',
			),
			$cleared->get_error_codes()
		);
		$this->assertCount( 2, $cleared->get_error_messages() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_clear_scheduled_hook_returns_custom_pre_filter_error_when_gc_error_is_set_to_true() {
		$return_error = function( $pre, $timestamp, $hook, $args, $gc_error ) {
			$this->assertTrue( $gc_error );

			return new GC_Error( 'error_code', 'error message' );
		};

		add_filter( 'pre_unschedule_event', $return_error, 10, 5 );

		gc_schedule_single_event( strtotime( '+1 hour' ), 'test_hook' );
		gc_schedule_single_event( strtotime( '+2 hours' ), 'test_hook' );

		$cleared = gc_clear_scheduled_hook( 'test_hook', array(), true );

		$this->assertWPError( $cleared );
		$this->assertSame(
			array(
				'error_code',
			),
			$cleared->get_error_codes()
		);
		$this->assertSame(
			array(
				'error message',
				'error message',
			),
			$cleared->get_error_messages()
		);
	}

	/**
	 * @ticket 49961
	 */
	public function test_unschedule_short_circuit_with_error_returns_false_when_gc_error_is_set_to_false() {
		$return_error = function( $pre, $hook, $gc_error ) {
			$this->assertFalse( $gc_error );

			return new GC_Error(
				'my_error',
				'An error ocurred'
			);
		};

		// Add a filter which returns a GC_Error:
		add_filter( 'pre_unschedule_hook', $return_error, 10, 3 );

		// Unschedule a hook without the `$gc_error` parameter:
		$result = gc_unschedule_hook( 'hook' );

		// Ensure boolean false is returned:
		$this->assertFalse( $result );
	}

	/**
	 * @ticket 49961
	 */
	public function test_unschedule_short_circuit_with_error_returns_error_when_gc_error_is_set_to_true() {
		$return_error = function( $pre, $hook, $gc_error ) {
			$this->assertTrue( $gc_error );

			return new GC_Error(
				'my_error',
				'An error ocurred'
			);
		};

		// Add a filter which returns a GC_Error:
		add_filter( 'pre_unschedule_hook', $return_error, 10, 3 );

		// Unschedule a hook with the `$gc_error` parameter:
		$result = gc_unschedule_hook( 'hook', true );

		// Ensure the error object is returned:
		$this->assertWPError( $result );
		$this->assertSame( 'my_error', $result->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_unschedule_short_circuit_with_false_returns_false_when_gc_error_is_set_to_false() {
		// Add a filter which returns false:
		add_filter( 'pre_unschedule_hook', '__return_false' );

		// Unschedule a hook without the `$gc_error` parameter:
		$result = gc_unschedule_hook( 'hook' );

		// Ensure false is returned:
		$this->assertFalse( $result );
	}

	/**
	 * @ticket 49961
	 */
	public function test_unschedule_short_circuit_with_false_returns_error_when_gc_error_is_set_to_true() {
		// Add a filter which returns false:
		add_filter( 'pre_unschedule_hook', '__return_false' );

		// Unchedule a hook with the `$gc_error` parameter:
		$result = gc_unschedule_hook( 'hook', true );

		// Ensure an error object is returned:
		$this->assertWPError( $result );
		$this->assertSame( 'pre_unschedule_hook_false', $result->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_cron_array_error_is_returned_when_scheduling_single_event() {
		// Force update_option() to fail by setting the new value to match the existing:
		add_filter(
			'pre_update_option_cron',
			static function() {
				return get_option( 'cron' );
			}
		);

		// Attempt to schedule a valid event:
		$event = gc_schedule_single_event( time(), 'hook', array(), true );

		// Ensure an error object is returned:
		$this->assertWPError( $event );
		$this->assertSame( 'could_not_set', $event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_cron_array_error_is_returned_when_scheduling_event() {
		// Force update_option() to fail by setting the new value to match the existing:
		add_filter(
			'pre_update_option_cron',
			static function() {
				return get_option( 'cron' );
			}
		);

		// Attempt to schedule a valid event:
		$event = gc_schedule_event( time(), 'daily', 'hook', array(), true );

		// Ensure an error object is returned:
		$this->assertWPError( $event );
		$this->assertSame( 'could_not_set', $event->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_cron_array_error_is_returned_when_unscheduling_hook() {
		// Schedule a valid event:
		$event = gc_schedule_event( strtotime( '+1 hour' ), 'daily', 'hook', array(), true );

		// Force update_option() to fail by setting the new value to match the existing:
		add_filter(
			'pre_update_option_cron',
			static function() {
				return get_option( 'cron' );
			}
		);

		// Attempt to unschedule the hook:
		$unscheduled = gc_unschedule_hook( 'hook', true );

		// Ensure an error object is returned:
		$this->assertTrue( $event );
		$this->assertWPError( $unscheduled );
		$this->assertSame( 'could_not_set', $unscheduled->get_error_code() );
	}

	/**
	 * @ticket 49961
	 */
	public function test_cron_array_error_is_returned_when_unscheduling_event() {
		// Schedule a valid event:
		$event = gc_schedule_event( strtotime( '+1 hour' ), 'daily', 'hook', array(), true );

		// Force update_option() to fail by setting the new value to match the existing:
		add_filter(
			'pre_update_option_cron',
			static function() {
				return get_option( 'cron' );
			}
		);

		// Attempt to unschedule the event:
		$unscheduled = gc_unschedule_event( gc_next_scheduled( 'hook' ), 'hook', array(), true );

		// Ensure an error object is returned:
		$this->assertTrue( $event );
		$this->assertWPError( $unscheduled );
		$this->assertSame( 'could_not_set', $unscheduled->get_error_code() );
	}

}
