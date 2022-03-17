<?php

/**
 * @group sitemaps
 */
class Tests_Sitemaps_gcSitemapsRegistry extends GC_UnitTestCase {

	public function test_add_provider() {
		$provider = new GC_Sitemaps_Test_Provider();
		$registry = new GC_Sitemaps_Registry();

		$actual    = $registry->add_provider( 'foo', $provider );
		$providers = $registry->get_providers();

		$this->assertTrue( $actual );
		$this->assertCount( 1, $providers );
		$this->assertSame( $providers['foo'], $provider, 'Can not confirm sitemap registration is working.' );
	}

	public function test_add_provider_prevent_duplicates() {
		$provider1 = new GC_Sitemaps_Test_Provider();
		$provider2 = new GC_Sitemaps_Test_Provider();
		$registry  = new GC_Sitemaps_Registry();

		$actual1   = $registry->add_provider( 'foo', $provider1 );
		$actual2   = $registry->add_provider( 'foo', $provider2 );
		$providers = $registry->get_providers();

		$this->assertTrue( $actual1 );
		$this->assertFalse( $actual2 );
		$this->assertCount( 1, $providers );
		$this->assertSame( $providers['foo'], $provider1, 'Can not confirm sitemap registration is working.' );
	}
}
