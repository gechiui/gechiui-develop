<?php

/**
 * @group formatting
 */
class Tests_Formatting_gcSlash extends GC_UnitTestCase {

	/**
	 * @ticket 42195
	 *
	 * @dataProvider data_gc_slash
	 *
	 * @param string $value
	 * @param string $expected
	 */
	public function test_gc_slash( $value, $expected ) {
		$this->assertSame( $expected, gc_slash( $value ) );
	}

	/**
	 * Data provider for test_gc_slash().
	 *
	 * @return array {
	 *     @type array {
	 *         @type mixed  $value    The value passed to gc_slash().
	 *         @type string $expected The expected output of gc_slash().
	 *     }
	 * }
	 */
	public function data_gc_slash() {
		return array(
			array( 123, 123 ),
			array( 123.4, 123.4 ),
			array( true, true ),
			array( false, false ),
			array(
				array(
					'hello',
					null,
					'"string"',
					125.41,
				),
				array(
					'hello',
					null,
					'\"string\"',
					125.41,
				),
			),
			array( "first level 'string'", "first level \'string\'" ),
		);
	}

	/**
	 * @ticket 24106
	 */
	public function test_adds_slashes() {
		$old = "I can't see, isn't that it?";
		$new = "I can\'t see, isn\'t that it?";
		$this->assertSame( $new, gc_slash( $old ) );
		$this->assertSame( "I can\\\\\'t see, isn\\\\\'t that it?", gc_slash( $new ) );
		$this->assertSame( array( 'a' => $new ), gc_slash( array( 'a' => $old ) ) ); // Keyed array.
		$this->assertSame( array( $new ), gc_slash( array( $old ) ) ); // Non-keyed.
	}

	/**
	 * @ticket 24106
	 */
	public function test_preserves_original_datatype() {

		$this->assertTrue( gc_slash( true ) );
		$this->assertFalse( gc_slash( false ) );
		$this->assertSame( 4, gc_slash( 4 ) );
		$this->assertSame( 'foo', gc_slash( 'foo' ) );
		$arr      = array(
			'a' => true,
			'b' => false,
			'c' => 4,
			'd' => 'foo',
		);
		$arr['e'] = $arr; // Add a sub-array.
		$this->assertSame( $arr, gc_slash( $arr ) ); // Keyed array.
		$this->assertSame( array_values( $arr ), gc_slash( array_values( $arr ) ) ); // Non-keyed.

		$obj = new stdClass;
		foreach ( $arr as $k => $v ) {
			$obj->$k = $v;
		}
		$this->assertSame( $obj, gc_slash( $obj ) );
	}

	/**
	 * @ticket 24106
	 */
	public function test_add_even_more_slashes() {
		$old = 'single\\slash double\\\\slash triple\\\\\\slash';
		$new = 'single\\\\slash double\\\\\\\\slash triple\\\\\\\\\\\\slash';
		$this->assertSame( $new, gc_slash( $old ) );
		$this->assertSame( array( 'a' => $new ), gc_slash( array( 'a' => $old ) ) ); // Keyed array.
		$this->assertSame( array( $new ), gc_slash( array( $old ) ) ); // Non-keyed.
	}

}
