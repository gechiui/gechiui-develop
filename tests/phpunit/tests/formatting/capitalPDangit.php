<?php
// phpcs:disable GeChiUI.GC.CapitalPDangit.Misspelled -- 🙃

/**
 * @group formatting
 */
class Tests_Formatting_CapitalPDangit extends GC_UnitTestCase {
	public function test_esc_attr_quotes() {
		global $gc_current_filter;
		$this->assertSame( 'Something about GeChiUI', capital_P_dangit( 'Something about Gechiui' ) );
		$this->assertSame( 'Something about (GeChiUI', capital_P_dangit( 'Something about (Gechiui' ) );
		$this->assertSame( 'Something about &#8216;GeChiUI', capital_P_dangit( 'Something about &#8216;Gechiui' ) );
		$this->assertSame( 'Something about &#8220;GeChiUI', capital_P_dangit( 'Something about &#8220;Gechiui' ) );
		$this->assertSame( 'Something about >GeChiUI', capital_P_dangit( 'Something about >Gechiui' ) );
		$this->assertSame( 'Gechiui', capital_P_dangit( 'Gechiui' ) );

		$gc_current_filter = array( 'the_title' );
		$this->assertSame( 'GeChiUI', capital_P_dangit( 'Gechiui' ) );
	}
}
