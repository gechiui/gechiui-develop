<?php

/**
 * @group admin
 */
class Tests_Admin_IncludesSchema extends GC_UnitTestCase {

	private static $options;
	private static $blogmeta;
	private static $sitemeta;

	/**
	 * Make sure the schema code is loaded before the tests are run.
	 */
	public static function gcSetUpBeforeClass( GC_UnitTest_Factory $factory ) {
		global $gcdb;

		self::$options  = 'testprefix_options';
		self::$blogmeta = 'testprefix_blogmeta';
		self::$sitemeta = 'testprefix_sitemeta';

		$options  = self::$options;
		$blogmeta = self::$blogmeta;
		$sitemeta = self::$sitemeta;

		require_once ABSPATH . 'gc-admin/includes/schema.php';

		$charset_collate  = $gcdb->get_charset_collate();
		$max_index_length = 191;

		// phpcs:disable GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$gcdb->query(
			"
			CREATE TABLE {$options} (
				option_id bigint(20) unsigned NOT NULL auto_increment,
				option_name varchar(191) NOT NULL default '',
				option_value longtext NOT NULL,
				autoload varchar(20) NOT NULL default 'yes',
				PRIMARY KEY  (option_id),
				UNIQUE KEY option_name (option_name)
			) {$charset_collate}
			"
		);
		$gcdb->query(
			"
			CREATE TABLE {$blogmeta} (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				blog_id bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (meta_id),
				KEY meta_key (meta_key({$max_index_length})),
				KEY blog_id (blog_id)
			) {$charset_collate}
			"
		);
		$gcdb->query(
			"
			CREATE TABLE {$sitemeta} (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				site_id bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (meta_id),
				KEY meta_key (meta_key({$max_index_length})),
				KEY site_id (site_id)
			) {$charset_collate}
			"
		);
		// phpcs:enable
	}

	/**
	 * Drop tables that were created before running the tests.
	 */
	public static function gcTearDownAfterClass() {
		global $gcdb;

		$options  = self::$options;
		$blogmeta = self::$blogmeta;
		$sitemeta = self::$sitemeta;

		// phpcs:disable GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$gcdb->query( "DROP TABLE IF EXISTS {$options}" );
		$gcdb->query( "DROP TABLE IF EXISTS {$blogmeta}" );
		$gcdb->query( "DROP TABLE IF EXISTS {$sitemeta}" );
		// phpcs:enable
	}

	/**
	 * @ticket 44893
	 * @dataProvider data_populate_options
	 */
	public function test_populate_options( $options, $expected ) {
		global $gcdb;

		$orig_options  = $gcdb->options;
		$gcdb->options = self::$options;

		populate_options( $options );

		gc_cache_delete( 'alloptions', 'options' );

		$results = array();
		foreach ( $expected as $option => $value ) {
			$results[ $option ] = get_option( $option );
		}

		$gcdb->query( "TRUNCATE TABLE {$gcdb->options}" );

		$gcdb->options = $orig_options;

		$this->assertSame( $expected, $results );
	}

	public function data_populate_options() {
		return array(
			array(
				array(),
				array(
					// Random options to check.
					'posts_per_rss'    => '10',
					'rss_use_excerpt'  => '0',
					'mailserver_url'   => 'mail.example.com',
					'mailserver_login' => 'login@example.com',
					'mailserver_pass'  => 'password',
				),
			),
			array(
				array(
					'posts_per_rss'   => '7',
					'rss_use_excerpt' => '1',
				),
				array(
					// Random options to check.
					'posts_per_rss'    => '7',
					'rss_use_excerpt'  => '1',
					'mailserver_url'   => 'mail.example.com',
					'mailserver_login' => 'login@example.com',
					'mailserver_pass'  => 'password',
				),
			),
			array(
				array(
					'custom_option' => '1',
				),
				array(
					// Random options to check.
					'custom_option'    => '1',
					'posts_per_rss'    => '10',
					'rss_use_excerpt'  => '0',
					'mailserver_url'   => 'mail.example.com',
					'mailserver_login' => 'login@example.com',
					'mailserver_pass'  => 'password',
				),
			),
			array(
				array(
					'use_quicktags' => '1',
				),
				array(
					// This option is disallowed and should never exist.
					'use_quicktags' => false,
				),
			),
			array(
				array(
					'rss_0123456789abcdef0123456789abcdef' => '1',
					'rss_0123456789abcdef0123456789abcdef_ts' => '1',
				),
				array(
					// These options would be obsolete magpie cache data and should never exist.
					'rss_0123456789abcdef0123456789abcdef' => false,
					'rss_0123456789abcdef0123456789abcdef_ts' => false,
				),
			),
		);
	}

	/**
	 * @ticket 44896
	 * @group multisite
	 * @group ms-required
	 * @dataProvider data_populate_site_meta
	 */
	public function test_populate_site_meta( $meta, $expected ) {
		global $gcdb;

		$orig_blogmeta  = $gcdb->blogmeta;
		$gcdb->blogmeta = self::$blogmeta;

		populate_site_meta( 42, $meta );

		$results = array();
		foreach ( $expected as $meta_key => $value ) {
			$results[ $meta_key ] = get_site_meta( 42, $meta_key, true );
		}

		$gcdb->query( "TRUNCATE TABLE {$gcdb->blogmeta}" );

		$gcdb->blogmeta = $orig_blogmeta;

		$this->assertSame( $expected, $results );
	}

	public function data_populate_site_meta() {
		return array(
			array(
				array(),
				array(
					'unknown_value' => '',
				),
			),
			array(
				array(
					'custom_meta' => '1',
				),
				array(
					'custom_meta' => '1',
				),
			),
		);
	}

	/**
	 * @ticket 44895
	 * @group multisite
	 * @dataProvider data_populate_network_meta
	 */
	public function test_populate_network_meta( $meta, $expected ) {
		global $gcdb;

		$orig_sitemeta  = $gcdb->sitemeta;
		$gcdb->sitemeta = self::$sitemeta;

		populate_network_meta( 42, $meta );

		$results = array();
		foreach ( $expected as $meta_key => $value ) {
			if ( is_multisite() ) {
				$results[ $meta_key ] = get_network_option( 42, $meta_key );
			} else {
				$results[ $meta_key ] = $gcdb->get_var( $gcdb->prepare( "SELECT meta_value FROM {$gcdb->sitemeta} WHERE meta_key = %s AND site_id = %d", $meta_key, 42 ) );
			}
		}

		$gcdb->query( "TRUNCATE TABLE {$gcdb->sitemeta}" );

		$gcdb->sitemeta = $orig_sitemeta;

		$this->assertSame( $expected, $results );
	}

	public function data_populate_network_meta() {
		return array(
			array(
				array(),
				array(
					// Random meta to check.
					'registration'      => 'none',
					'blog_upload_space' => '100',
					'fileupload_maxk'   => '1500',
				),
			),
			array(
				array(
					'site_name' => 'My Great Network',
					'GCLANG'    => 'fr_FR',
				),
				array(
					// Random meta to check.
					'site_name'         => 'My Great Network',
					'registration'      => 'none',
					'blog_upload_space' => '100',
					'fileupload_maxk'   => '1500',
					'GCLANG'            => 'fr_FR',
				),
			),
			array(
				array(
					'custom_meta' => '1',
				),
				array(
					// Random meta to check.
					'custom_meta'       => '1',
					'registration'      => 'none',
					'blog_upload_space' => '100',
					'fileupload_maxk'   => '1500',
				),
			),
		);
	}
}