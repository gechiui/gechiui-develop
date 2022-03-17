<?php

/**
 * @group formatting
 */
class Tests_Formatting_gcReplaceInHtmlTags extends GC_UnitTestCase {
	/**
	 * Check for expected behavior of new function gc_replace_in_html_tags().
	 *
	 * @dataProvider data_gc_replace_in_html_tags
	 */
	public function test_gc_replace_in_html_tags( $input, $output ) {
		return $this->assertSame( $output, gc_replace_in_html_tags( $input, array( "\n" => ' ' ) ) );
	}

	public function data_gc_replace_in_html_tags() {
		return array(
			array(
				"Hello \n World",
				"Hello \n World",
			),
			array(
				"<Hello \n World>",
				'<Hello   World>',
			),
			array(
				"<!-- Hello \n World -->",
				'<!-- Hello   World -->',
			),
			array(
				"<!-- Hello <\n> World -->",
				'<!-- Hello < > World -->',
			),
		);
	}
}

