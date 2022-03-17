<?php
/**
 * Test gc_strip_all_tags()
 *
 * @group formatting
 */
class Tests_Formatting_gcStripAllTags extends GC_UnitTestCase {

	public function test_gc_strip_all_tags() {

		$text = 'lorem<br />ipsum';
		$this->assertSame( 'loremipsum', gc_strip_all_tags( $text ) );

		$text = "lorem<br />\nipsum";
		$this->assertSame( "lorem\nipsum", gc_strip_all_tags( $text ) );

		// Test removing breaks is working.
		$text = 'lorem<br />ipsum';
		$this->assertSame( 'loremipsum', gc_strip_all_tags( $text, true ) );

		// Test script / style tag's contents is removed.
		$text = 'lorem<script>alert(document.cookie)</script>ipsum';
		$this->assertSame( 'loremipsum', gc_strip_all_tags( $text ) );

		$text = "lorem<style>* { display: 'none' }</style>ipsum";
		$this->assertSame( 'loremipsum', gc_strip_all_tags( $text ) );

		// Test "marlformed" markup of contents.
		$text = "lorem<style>* { display: 'none' }<script>alert( document.cookie )</script></style>ipsum";
		$this->assertSame( 'loremipsum', gc_strip_all_tags( $text ) );
	}
}

