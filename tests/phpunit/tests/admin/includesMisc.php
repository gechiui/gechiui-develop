<?php

/**
 * @group admin
 */
class Tests_Admin_IncludesMisc extends GC_UnitTestCase {

	/**
	 * @covers ::url_shorten
	 */
	public function test_shorten_url() {
		$tests = array(
			'gechiui\.org/about/philosophy'
				=> 'gechiui\.org/about/philosophy',     // No longer strips slashes.
			'www.gechiui.com/about/philosophy'
				=> 'www.gechiui.com/about/philosophy',
			'http://www.gechiui.com/about/philosophy/'
				=> 'www.gechiui.com/about/philosophy',      // Remove http, trailing slash.
			'http://www.gechiui.com/about/philosophy/'
				=> 'www.gechiui.com/about/philosophy',      // Remove http, www.
			'http://www.gechiui.com/about/philosophy/#box'
				=> 'www.gechiui.com/about/philosophy/#box',      // Don't shorten 35 characters.
			'http://www.gechiui.com/about/philosophy/#decisions'
				=> 'www.gechiui.com/about/philosophy/#&hellip;', // Shorten to 32 if > 35 after cleaning.
		);
		foreach ( $tests as $k => $v ) {
			$this->assertSame( $v, url_shorten( $k ) );
		}
	}
}
