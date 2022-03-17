<?php

/**
 * @group formatting
 */
class Tests_Formatting_gcHtmlExcerpt extends GC_UnitTestCase {
	public function test_simple() {
		$this->assertSame( 'Baba', gc_html_excerpt( 'Baba told me not to come', 4 ) );
	}
	public function test_html() {
		$this->assertSame( 'Baba', gc_html_excerpt( "<a href='http://baba.net/'>Baba</a> told me not to come", 4 ) );
	}
	public function test_entities() {
		$this->assertSame( 'Baba', gc_html_excerpt( 'Baba &amp; Dyado', 8 ) );
		$this->assertSame( 'Baba', gc_html_excerpt( 'Baba &#038; Dyado', 8 ) );
		$this->assertSame( 'Baba &amp; D', gc_html_excerpt( 'Baba &amp; Dyado', 12 ) );
		$this->assertSame( 'Baba &amp; Dyado', gc_html_excerpt( 'Baba &amp; Dyado', 100 ) );
	}
}
