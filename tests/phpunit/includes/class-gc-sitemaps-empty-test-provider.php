<?php

/**
 * Class GC_Sitemaps_Empty_Test_Provider.
 *
 * Provides test data for additional registered providers.
 */
class GC_Sitemaps_Empty_Test_Provider extends GC_Sitemaps_Provider {
	/**
	 * GC_Sitemaps_Empty_Test_Provider constructor.
	 *
	 * @param string $object_type Optional. Object type name to use. Default 'test'.
	 */
	public function __construct( $object_type = 'test' ) {
		$this->object_type = $object_type;
	}

	/**
	 * Gets a URL list for a sitemap.
	 *
	 * @param int    $page_num       Page of results.
	 * @param string $object_subtype Optional. Object subtype name. Default empty.
	 * @return array[] Array of URL information for a sitemap.
	 */
	public function get_url_list( $page_num, $object_subtype = '' ) {
		return array();
	}

	/**
	 * Query for determining the number of pages.
	 *
	 * @param string $object_subtype Optional. Object subtype. Default empty.
	 * @return int Total number of pages.
	 */
	public function get_max_num_pages( $object_subtype = '' ) {
		return 0;
	}
}
