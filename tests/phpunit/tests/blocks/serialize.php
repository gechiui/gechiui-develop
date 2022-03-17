<?php
/**
 * Block serialization tests
 *
 * @package GeChiUI
 * @subpackage Blocks
 * @since 5.3.3
 */

/**
 * Tests for block serialization functions.
 *
 * @since 5.3.3
 *
 * @group blocks
 */
class Tests_Blocks_Serialize extends GC_UnitTestCase {

	/**
	 * @dataProvider data_serialize_identity_from_parsed
	 */
	public function test_serialize_identity_from_parsed( $original ) {
		$blocks = parse_blocks( $original );

		$actual   = serialize_blocks( $blocks );
		$expected = $original;

		$this->assertSame( $expected, $actual );
	}

	public function data_serialize_identity_from_parsed() {
		return array(
			// Void block.
			array( '<!-- gc:void /-->' ),

			// Freeform content ($block_name = null).
			array( 'Example.' ),

			// Block with content.
			array( '<!-- gc:content -->Example.<!-- /gc:content -->' ),

			// Block with attributes.
			array( '<!-- gc:attributes {"key":"value"} /-->' ),

			// Block with inner blocks.
			array( "<!-- gc:outer --><!-- gc:inner {\"key\":\"value\"} -->Example.<!-- /gc:inner -->\n\nExample.\n\n<!-- gc:void /--><!-- /gc:outer -->" ),

			// Block with attribute values that may conflict with HTML comment.
			array( '<!-- gc:attributes {"key":"\\u002d\\u002d\\u003c\\u003e\\u0026\\u0022"} /-->' ),

			// Block with attribute values that should not be escaped.
			array( '<!-- gc:attributes {"key":"€1.00 / 3 for €2.00"} /-->' ),
		);
	}

	public function test_serialized_block_name() {
		$this->assertNull( strip_core_block_namespace( null ) );
		$this->assertSame( 'example', strip_core_block_namespace( 'example' ) );
		$this->assertSame( 'example', strip_core_block_namespace( 'core/example' ) );
		$this->assertSame( 'plugin/example', strip_core_block_namespace( 'plugin/example' ) );
	}

}
