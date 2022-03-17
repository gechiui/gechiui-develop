<?php

/**
 * @group date
 * @group datetime
 * @covers ::gc_date
 */
class Tests_Date_gcDate extends GC_UnitTestCase {

	/** @var GC_Locale */
	private $gc_locale_original;

	public function set_up() {
		global $gc_locale;

		parent::set_up();

		$this->gc_locale_original = clone $gc_locale;
	}

	public function tear_down() {
		global $gc_locale;

		$gc_locale = $this->gc_locale_original;

		parent::tear_down();
	}

	/**
	 * @ticket 28636
	 */
	public function test_should_return_false_on_invalid_timestamp() {
		$this->assertFalse( gc_date( DATE_RFC3339, 'invalid' ) );
	}

	/**
	 * @ticket 48319
	 */
	public function test_should_not_escape_localized_numbers() {
		global $gc_locale;

		$gc_locale->month = array( 10 => '10月' );

		$utc      = new DateTimeZone( 'UTC' );
		$datetime = new DateTimeImmutable( '2019-10-17', $utc );

		$this->assertSame( '10月', gc_date( 'F', $datetime->getTimestamp(), $utc ) );
	}

	/**
	 * @ticket 48319
	 */
	public function test_should_keep_localized_slashes() {
		global $gc_locale;

		$string           = 'A \ B';
		$gc_locale->month = array( 10 => $string );

		$utc      = new DateTimeZone( 'UTC' );
		$datetime = new DateTimeImmutable( '2019-10-17', $utc );

		$this->assertSame( $string, gc_date( 'F', $datetime->getTimestamp(), $utc ) );
	}
}
