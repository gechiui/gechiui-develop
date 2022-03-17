<?php

/**
 * @group canonical
 * @group rewrite
 * @group query
 * @group sitemaps
 */
class Tests_Canonical_Sitemaps extends GC_Canonical_UnitTestCase {

	public function set_up() {
		parent::set_up();
		$gc_sitemaps = new GC_Sitemaps();
		$gc_sitemaps->init();
	}

	public function test_remove_trailing_slashes_for_sitemap_index_requests() {
		$this->set_permalink_structure( '/%postname%/' );
		$this->assertCanonical( '/gc-sitemap.xml', '/gc-sitemap.xml' );
		$this->assertCanonical( '/gc-sitemap.xml/', '/gc-sitemap.xml' );
	}

	public function test_remove_trailing_slashes_for_sitemap_index_stylesheet_requests() {
		$this->set_permalink_structure( '/%postname%/' );
		$this->assertCanonical( '/gc-sitemap-index.xsl', '/gc-sitemap-index.xsl' );
		$this->assertCanonical( '/gc-sitemap-index.xsl/', '/gc-sitemap-index.xsl' );
	}

	public function test_remove_trailing_slashes_for_sitemap_requests() {
		$this->set_permalink_structure( '/%postname%/' );
		$this->assertCanonical( '/gc-sitemap-posts-post-1.xml', '/gc-sitemap-posts-post-1.xml' );
		$this->assertCanonical( '/gc-sitemap-posts-post-1.xml/', '/gc-sitemap-posts-post-1.xml' );
		$this->assertCanonical( '/gc-sitemap-users-1.xml', '/gc-sitemap-users-1.xml' );
		$this->assertCanonical( '/gc-sitemap-users-1.xml/', '/gc-sitemap-users-1.xml' );
	}

	public function test_remove_trailing_slashes_for_sitemap_stylesheet_requests() {
		$this->set_permalink_structure( '/%postname%/' );
		$this->assertCanonical( '/gc-sitemap.xsl', '/gc-sitemap.xsl' );
		$this->assertCanonical( '/gc-sitemap.xsl/', '/gc-sitemap.xsl' );
	}

	/**
	 * Ensure sitemaps redirects work as expected with pretty permalinks.
	 *
	 * @dataProvider data_sitemaps_canonical_pretty_redirects
	 * @ticket 50910
	 */
	public function test_sitemaps_canonical_pretty_redirects( $test_url, $expected ) {
		$this->set_permalink_structure( '/%postname%/' );
		$this->assertCanonical( $test_url, $expected, 50910 );
	}

	/**
	 * Data provider for test_sitemaps_canonical_pretty_redirects.
	 *
	 * @return array[] {
	 *     Data to test with.
	 *
	 *     @type string $0 The test URL.
	 *     @type string $1 The expected canonical URL.
	 * }
	 */
	public function data_sitemaps_canonical_pretty_redirects() {
		return array(
			// Ugly/incorrect versions redirect correctly.
			array( '/?sitemap=index', '/gc-sitemap.xml' ),
			array( '/gc-sitemap.xml/', '/gc-sitemap.xml' ),
			array( '/?sitemap=posts&sitemap-subtype=post', '/gc-sitemap-posts-post-1.xml' ),
			array( '/?sitemap=posts&sitemap-subtype=post&paged=2', '/gc-sitemap-posts-post-2.xml' ),
			array( '/?sitemap=taxonomies&sitemap-subtype=category', '/gc-sitemap-taxonomies-category-1.xml' ),
			array( '/?sitemap=taxonomies&sitemap-subtype=category&paged=2', '/gc-sitemap-taxonomies-category-2.xml' ),

			// Pretty versions don't redirect incorrectly.
			array( '/gc-sitemap.xml', '/gc-sitemap.xml' ),
			array( '/gc-sitemap-posts-post-1.xml', '/gc-sitemap-posts-post-1.xml' ),
			array( '/gc-sitemap-posts-post-2.xml', '/gc-sitemap-posts-post-2.xml' ),
			array( '/gc-sitemap-taxonomies-category-1.xml', '/gc-sitemap-taxonomies-category-1.xml' ),
			array( '/gc-sitemap-taxonomies-category-2.xml', '/gc-sitemap-taxonomies-category-2.xml' ),
		);
	}

	/**
	 * Ensure sitemaps redirects work as expected with ugly permalinks.
	 *
	 * @dataProvider data_sitemaps_canonical_ugly_redirects
	 * @ticket 50910
	 */
	public function test_sitemaps_canonical_ugly_redirects( $test_url, $expected ) {
		$this->set_permalink_structure( '' );
		$this->assertCanonical( $test_url, $expected, 50910 );
	}

	/**
	 * Data provider for test_sitemaps_canonical_ugly_redirects.
	 *
	 * @return array[] {
	 *     Data to test with.
	 *
	 *     @type string $0 The test URL.
	 *     @type string $1 The expected canonical URL.
	 * }
	 */
	public function data_sitemaps_canonical_ugly_redirects() {
		return array(
			// Ugly permalinks remain ugly.
			array( '/?sitemap=index', '/?sitemap=index' ),
			array( '/?sitemap=posts&sitemap-subtype=post', '/?sitemap=posts&sitemap-subtype=post' ),
			array( '/?sitemap=posts&sitemap-subtype=post&paged=2', '/?sitemap=posts&sitemap-subtype=post&paged=2' ),
			array( '/?sitemap=taxonomies&sitemap-subtype=category', '/?sitemap=taxonomies&sitemap-subtype=category' ),
			array( '/?sitemap=taxonomies&sitemap-subtype=category&paged=2', '/?sitemap=taxonomies&sitemap-subtype=category&paged=2' ),
		);
	}
}
