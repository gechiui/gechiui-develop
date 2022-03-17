<?php

/**
 * Tests for the _gc_to_kebab_case() function
 *
 * @since 5.8.0
 *
 * @group functions.php
 * @covers ::_gc_to_kebab_case
 */
class Tests_Functions_gcToKebabCase extends GC_UnitTestCase {

	public function test_gc_to_kebab_case() {
		$this->assertSame( 'white', _gc_to_kebab_case( 'white' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white+black' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white:black' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white*black' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white.black' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white black' ) );
		$this->assertSame( 'white-black', _gc_to_kebab_case( 'white	black' ) );
		$this->assertSame( 'white-to-black', _gc_to_kebab_case( 'white-to-black' ) );
		$this->assertSame( 'white-2-white', _gc_to_kebab_case( 'white2white' ) );
		$this->assertSame( 'white-2nd', _gc_to_kebab_case( 'white2nd' ) );
		$this->assertSame( 'white-2-ndcolor', _gc_to_kebab_case( 'white2ndcolor' ) );
		$this->assertSame( 'white-2nd-color', _gc_to_kebab_case( 'white2ndColor' ) );
		$this->assertSame( 'white-2nd-color', _gc_to_kebab_case( 'white2nd_color' ) );
		$this->assertSame( 'white-23-color', _gc_to_kebab_case( 'white23color' ) );
		$this->assertSame( 'white-23', _gc_to_kebab_case( 'white23' ) );
		$this->assertSame( '23-color', _gc_to_kebab_case( '23color' ) );
		$this->assertSame( 'white-4th', _gc_to_kebab_case( 'white4th' ) );
		$this->assertSame( 'font-2-xl', _gc_to_kebab_case( 'font2xl' ) );
		$this->assertSame( 'white-to-white', _gc_to_kebab_case( 'whiteToWhite' ) );
		$this->assertSame( 'white-t-owhite', _gc_to_kebab_case( 'whiteTOwhite' ) );
		$this->assertSame( 'whit-eto-white', _gc_to_kebab_case( 'WHITEtoWHITE' ) );
		$this->assertSame( '42', _gc_to_kebab_case( 42 ) );
		$this->assertSame( 'ive-done', _gc_to_kebab_case( "i've done" ) );
		$this->assertSame( 'ffffff', _gc_to_kebab_case( '#ffffff' ) );
		$this->assertSame( 'ffffff', _gc_to_kebab_case( '$ffffff' ) );
	}
}
