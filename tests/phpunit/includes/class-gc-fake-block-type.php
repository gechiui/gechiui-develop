<?php
/**
 * GC_Fake_Block_Type for testing
 *
 * @package GeChiUI
 * @subpackage Blocks
 * @since 5.0.0
 */

/**
 * Test class extending GC_Block_Type
 *
 * @since 5.0.0
 */
class GC_Fake_Block_Type extends GC_Block_Type {

	/**
	 * Render the fake block.
	 *
	 * @param array  $attributes Optional. Block attributes. Default empty array.
	 * @param string $content    Optional. Block content. Default empty string.
	 * @return string Rendered block HTML.
	 */
	public function render( $attributes = array(), $content = '' ) {
		return '<div>' . $content . '</div>';
	}
}
