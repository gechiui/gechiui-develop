<?php

/**
 * Test dbDelta()
 *
 * @group upgrade
 * @group dbdelta
 */
class Tests_dbDelta extends GC_UnitTestCase {

	/**
	 * The maximum size of an index with utf8mb4 collation and charset with a standard
	 * byte limit of 767. floor(767/4) = 191 characters.
	 */
	protected $max_index_length = 191;

	/**
	 * Database engine used for creating tables.
	 *
	 * Prior to MySQL 5.7, InnoDB did not support FULLTEXT indexes, so MyISAM is used instead.
	 */
	protected $db_engine = '';

	/**
	 * Display width for BIGINT data type.
	 *
	 * Prior to MySQL 8.0.17, default width of 20 digits was used: BIGINT(20).
	 * Since MySQL 8.0.17, display width for integer data types is no longer supported.
	 */
	protected $bigint_display_width = '';

	/**
	 * Make sure the upgrade code is loaded before the tests are run.
	 */
	public static function set_up_before_class() {

		parent::set_up_before_class();

		require_once ABSPATH . 'gc-admin/includes/upgrade.php';
	}

	/**
	 * Create a custom table to be used in each test.
	 */
	public function set_up() {

		global $gcdb;

		$db_version = $gcdb->db_version();

		if ( version_compare( $db_version, '5.7', '<' ) ) {
			// Prior to MySQL 5.7, InnoDB did not support FULLTEXT indexes, so MyISAM is used instead.
			$this->db_engine = 'ENGINE=MyISAM';
		}

		if ( version_compare( $db_version, '8.0.17', '<' ) ) {
			// Prior to MySQL 8.0.17, default width of 20 digits was used: BIGINT(20).
			$this->bigint_display_width = '(20)';
		}

		$gcdb->query(
			$gcdb->prepare(
				"
				CREATE TABLE {$gcdb->prefix}dbdelta_test (" .
					// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
					"id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
					column_1 varchar(255) NOT NULL,
					column_2 text,
					column_3 blob,
					PRIMARY KEY  (id),
					KEY key_1 (column_1(%d)),
					KEY compound_key (id,column_1(%d)),
					FULLTEXT KEY fulltext_key (column_1)" .
					// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
				") {$this->db_engine}
				",
				$this->max_index_length,
				$this->max_index_length
			)
		);

		// This has to be called after the `CREATE TABLE` above as the `_create_temporary_tables` filter
		// causes it to create a temporary table, and a temporary table cannot use a FULLTEXT index.
		parent::set_up();
	}

	/**
	 * Delete the custom table on teardown.
	 */
	public function tear_down() {

		global $gcdb;

		parent::tear_down();

		// This has to be called after the parent `tearDown()` method.
		$gcdb->query( "DROP TABLE IF EXISTS {$gcdb->prefix}dbdelta_test" );
	}

	/**
	 * Test table creation.
	 */
	public function test_creating_a_table() {

		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

		global $gcdb;

		$updates = dbDelta(
			"CREATE TABLE {$gcdb->prefix}dbdelta_create_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				PRIMARY KEY  (id)
			);"
		);

		$expected = array(
			"{$gcdb->prefix}dbdelta_create_test" => "Created table {$gcdb->prefix}dbdelta_create_test",
		);

		$this->assertSame( $expected, $updates );

		$this->assertSame(
			"{$gcdb->prefix}dbdelta_create_test",
			$gcdb->get_var(
				$gcdb->prepare(
					'SHOW TABLES LIKE %s',
					$gcdb->esc_like( "{$gcdb->prefix}dbdelta_create_test" )
				)
			)
		);

		$gcdb->query( "DROP TABLE {$gcdb->prefix}dbdelta_create_test" );
	}

	/**
	 * Test that it does nothing for an existing table.
	 */
	public function test_existing_table() {

		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length))
			)
			"
		);

		$this->assertSame( array(), $updates );
	}

	/**
	 * Test the column type is updated.
	 */
	public function test_column_type_change() {

		global $gcdb;

		// id: bigint => int(11)
		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id int(11) NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length))
			)
			"
		);

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.id"
					=> "Changed type of {$gcdb->prefix}dbdelta_test.id from bigint{$this->bigint_display_width} to int(11)",
			),
			$updates
		);
	}

	/**
	 * Test new column added.
	 */
	public function test_column_added() {

		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				extra_col longtext,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length))
			)
			"
		);

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.extra_col"
					=> "Added column {$gcdb->prefix}dbdelta_test.extra_col",
			),
			$updates
		);

		$this->assertTableHasColumn( 'column_1', $gcdb->prefix . 'dbdelta_test' );
		$this->assertTableHasPrimaryKey( 'id', $gcdb->prefix . 'dbdelta_test' );
	}

	/**
	 * Test that it does nothing when a column is removed.
	 *
	 * @ticket 26801
	 */
	public function test_columns_arent_removed() {

		global $gcdb;

		// No column column_1.
		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length))
			)
			"
		);

		$this->assertSame( array(), $updates );

		$this->assertTableHasColumn( 'column_1', $gcdb->prefix . 'dbdelta_test' );
	}

	/**
	 * Test that nothing happens with $execute is false.
	 */
	public function test_no_execution() {

		global $gcdb;

		// Added column extra_col.
		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				extra_col longtext,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length))
			)
			",
			false // Don't execute.
		);

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.extra_col"
					=> "Added column {$gcdb->prefix}dbdelta_test.extra_col",
			),
			$updates
		);

		$this->assertTableHasNotColumn( 'extra_col', $gcdb->prefix . 'dbdelta_test' );
	}

	/**
	 * Test inserting into the database
	 */
	public function test_insert_into_table() {
		global $gcdb;

		$insert = dbDelta(
			"INSERT INTO {$gcdb->prefix}dbdelta_test (column_1) VALUES ('wcphilly2015')"
		);

		$this->assertSame(
			array(),
			$insert
		);

		$this->assertTableRowHasValue( 'column_1', 'wcphilly2015', $gcdb->prefix . 'dbdelta_test' );

	}

	/**
	 * Test that FULLTEXT indexes are detected.
	 *
	 * @ticket 14445
	 */
	public function test_fulltext_index() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			)
			",
			false
		);

		$this->assertEmpty( $updates );
	}

	//
	// Assertions.
	//

	/**
	 * Assert that a table has a row with a value in a field.
	 *
	 * @param string $column The field name.
	 * @param string $value  The field value.
	 * @param string $table  The database table name.
	 */
	protected function assertTableRowHasValue( $column, $value, $table ) {
		global $gcdb;

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$table_row = $gcdb->get_row( "select $column from {$table} where $column = '$value'" );

		$expected = (object) array(
			$column => $value,
		);

		$this->assertEquals( $expected, $table_row );
	}

	/**
	 * Assert that a table has a column.
	 *
	 * @param string $column The field name.
	 * @param string $table  The database table name.
	 */
	protected function assertTableHasColumn( $column, $table ) {
		global $gcdb;

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$table_fields = $gcdb->get_results( "DESCRIBE $table" );

		$this->assertCount( 1, gc_list_filter( $table_fields, array( 'Field' => $column ) ) );
	}

	/**
	 * Assert that a table has a primary key.
	 *
	 * Checks for single-column primary keys. May not work for multi-column primary keys.
	 *
	 * @param string $column The column for the primary key.
	 * @param string $table  The database table name.
	 */
	protected function assertTableHasPrimaryKey( $column, $table ) {
		global $gcdb;

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$table_indices = $gcdb->get_results( "SHOW INDEX FROM $table" );

		$this->assertCount(
			1,
			gc_list_filter(
				$table_indices,
				array(
					'Key_name'    => 'PRIMARY',
					'Column_name' => $column,
				),
				'AND'
			)
		);
	}

	/**
	 * Assert that a table doesn't have a column.
	 *
	 * @param string $column The field name.
	 * @param string $table  The database table name.
	 */
	protected function assertTableHasNotColumn( $column, $table ) {

		global $gcdb;

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$table_fields = $gcdb->get_results( "DESCRIBE $table" );

		$this->assertCount( 0, gc_list_filter( $table_fields, array( 'Field' => $column ) ) );
	}

	/**
	 * @ticket 31869
	 */
	public function test_truncated_index() {
		global $gcdb;

		if ( ! $gcdb->has_cap( 'utf8mb4' ) ) {
			$this->markTestSkipped( 'This test requires utf8mb4 support in MySQL.' );
		}

		// This table needs to be actually created.
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

		$table_name = "{$gcdb->prefix}test_truncated_index";

		$create = "
			CREATE TABLE $table_name (
				a varchar(255) COLLATE utf8mb4_unicode_ci,
				KEY a_key (a)
			) ENGINE=InnoDB ROW_FORMAT=DYNAMIC";

		// phpcs:ignore GeChiUI.DB.PreparedSQL.NotPrepared
		$gcdb->query( $create );

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$index = $gcdb->get_row( "SHOW INDEXES FROM $table_name WHERE Key_name='a_key';" );

		$actual = dbDelta( $create, false );

		// phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
		$gcdb->query( "DROP TABLE IF EXISTS $table_name;" );

		if ( 191 !== $index->Sub_part ) {
			$this->markTestSkipped( 'This test requires the index to be truncated.' );
		}

		$this->assertSame( array(), $actual );
	}

	/**
	 * @ticket 36748
	 */
	public function test_dont_downsize_text_fields() {
		global $gcdb;

		$result = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 tinytext,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertSame( array(), $result );
	}

	/**
	 * @ticket 36748
	 */
	public function test_dont_downsize_blob_fields() {
		global $gcdb;

		$result = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 tinyblob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertSame( array(), $result );
	}

	/**
	 * @ticket 36748
	 */
	public function test_upsize_text_fields() {
		global $gcdb;

		$result = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 bigtext,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.column_2"
					=> "Changed type of {$gcdb->prefix}dbdelta_test.column_2 from text to bigtext",
			),
			$result
		);
	}

	/**
	 * @ticket 36748
	 */
	public function test_upsize_blob_fields() {
		global $gcdb;

		$result = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 mediumblob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.column_3"
					=> "Changed type of {$gcdb->prefix}dbdelta_test.column_3 from blob to mediumblob",
			),
			$result
		);
	}

	/**
	 * @ticket 20263
	 */
	public function test_query_with_backticks_does_not_throw_an_undefined_index_warning() {
		global $gcdb;

		$schema = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test2 (
				`id` bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				`column_1` varchar(255) NOT NULL,
				PRIMARY KEY  (id),
				KEY compound_key (id,column_1($this->max_index_length))
			)
		";

		// phpcs:ignore GeChiUI.DB.PreparedSQL.NotPrepared
		$gcdb->query( $schema );

		$updates = dbDelta( $schema, false );

		$gcdb->query( "DROP TABLE IF EXISTS {$gcdb->prefix}dbdelta_test2" );

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 36948
	 */
	public function test_spatial_indices() {
		global $gcdb;

		$db_version = $gcdb->db_version();

		if ( version_compare( $db_version, '5.4', '<' ) ) {
			$this->markTestSkipped( 'Spatial indices require MySQL 5.4 and above.' );
		}

		$geomcollection_name = 'geomcollection';

		if ( version_compare( $db_version, '8.0.11', '<' ) ) {
			// Prior to MySQL 8.0.11, GeometryCollection data type name was used.
			$geomcollection_name = 'geometrycollection';
		}

		$schema =
			"
			CREATE TABLE {$gcdb->prefix}spatial_index_test (
				non_spatial bigint{$this->bigint_display_width} unsigned NOT NULL,
				spatial_value {$geomcollection_name} NOT NULL,
				KEY non_spatial (non_spatial),
				SPATIAL KEY spatial_key (spatial_value)
			) {$this->db_engine};
			";

		// phpcs:ignore GeChiUI.DB.PreparedSQL.NotPrepared
		$gcdb->query( $schema );

		$updates = dbDelta( $schema, false );

		$this->assertEmpty( $updates );

		$schema =
			"
			CREATE TABLE {$gcdb->prefix}spatial_index_test (
				non_spatial bigint{$this->bigint_display_width} unsigned NOT NULL,
				spatial_value {$geomcollection_name} NOT NULL,
				spatial_value2 {$geomcollection_name} NOT NULL,
				KEY non_spatial (non_spatial),
				SPATIAL KEY spatial_key (spatial_value)
				SPATIAL KEY spatial_key2 (spatial_value2)
			) {$this->db_engine};
			";

		$updates = dbDelta( $schema, false );

		$this->assertSame(
			array(
				"{$gcdb->prefix}spatial_index_test.spatial_value2" => "Added column {$gcdb->prefix}spatial_index_test.spatial_value2",
				"Added index {$gcdb->prefix}spatial_index_test SPATIAL KEY `spatial_key2` (`spatial_value2`)",
			),
			$updates
		);

		$gcdb->query( "DROP TABLE IF EXISTS {$gcdb->prefix}spatial_index_test" );
	}

	/**
	 * @ticket 20263
	 */
	public function test_query_with_backticks_does_not_cause_a_query_to_alter_all_columns_and_indices_to_run_even_if_none_have_changed() {
		global $gcdb;

		$schema = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test2 (
				`id` bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				`references` varchar(255) NOT NULL,
				PRIMARY KEY  (`id`),
				KEY `compound_key` (`id`,`references`($this->max_index_length))
			)
		";

		// phpcs:ignore GeChiUI.DB.PreparedSQL.NotPrepared
		$gcdb->query( $schema );

		$updates = dbDelta( $schema );

		$table_indices      = $gcdb->get_results( "SHOW INDEX FROM {$gcdb->prefix}dbdelta_test2" );
		$compound_key_index = gc_list_filter( $table_indices, array( 'Key_name' => 'compound_key' ) );

		$gcdb->query( "DROP TABLE IF EXISTS {$gcdb->prefix}dbdelta_test2" );

		$this->assertCount( 2, $compound_key_index );
		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 20263
	 */
	public function test_index_with_a_reserved_keyword_can_be_created() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				`references` varchar(255) NOT NULL,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id , column_1($this->max_index_length)),
				KEY compound_key2 (id,`references`($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$table_indices = $gcdb->get_results( "SHOW INDEX FROM {$gcdb->prefix}dbdelta_test" );

		$this->assertCount( 2, gc_list_filter( $table_indices, array( 'Key_name' => 'compound_key2' ), 'AND' ) );

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test.references" => "Added column {$gcdb->prefix}dbdelta_test.references",
				0                                        => "Added index {$gcdb->prefix}dbdelta_test KEY `compound_key2` (`id`,`references`($this->max_index_length))",
			),
			$updates
		);
	}

	/**
	 * @ticket 20263
	 */
	public function test_gc_get_db_schema_does_no_alter_queries_on_existing_install() {
		$updates = dbDelta( gc_get_db_schema() );

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 20263
	 */
	public function test_key_and_index_and_fulltext_key_and_fulltext_index_and_unique_key_and_unique_index_indicies() {
		global $gcdb;

		$schema = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1),
				INDEX key_2 (column_1($this->max_index_length)),
				UNIQUE KEY key_3 (column_1($this->max_index_length)),
				UNIQUE INDEX key_4 (column_1($this->max_index_length)),
				FULLTEXT INDEX key_5 (column_1),
			) {$this->db_engine}
		";

		$creates = dbDelta( $schema );
		$this->assertSame(
			array(
				0 => "Added index {$gcdb->prefix}dbdelta_test KEY `key_2` (`column_1`($this->max_index_length))",
				1 => "Added index {$gcdb->prefix}dbdelta_test UNIQUE KEY `key_3` (`column_1`($this->max_index_length))",
				2 => "Added index {$gcdb->prefix}dbdelta_test UNIQUE KEY `key_4` (`column_1`($this->max_index_length))",
				3 => "Added index {$gcdb->prefix}dbdelta_test FULLTEXT KEY `key_5` (`column_1`)",
			),
			$creates
		);

		$updates = dbDelta( $schema );
		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 20263
	 */
	public function test_index_and_key_are_synonyms_and_do_not_recreate_indices() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				INDEX key_1 (column_1($this->max_index_length)),
				INDEX compound_key (id,column_1($this->max_index_length)),
				FULLTEXT INDEX fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 20263
	 */
	public function test_indices_with_prefix_limits_are_created_and_do_not_recreate_indices() {
		global $gcdb;

		$schema = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1),
				KEY key_2 (column_1(10)),
				KEY key_3 (column_2(100),column_1(10)),
			) {$this->db_engine}
		";

		$creates = dbDelta( $schema );
		$this->assertSame(
			array(
				0 => "Added index {$gcdb->prefix}dbdelta_test KEY `key_2` (`column_1`(10))",
				1 => "Added index {$gcdb->prefix}dbdelta_test KEY `key_3` (`column_2`(100),`column_1`(10))",
			),
			$creates
		);

		$updates = dbDelta( $schema );
		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34959
	 */
	public function test_index_col_names_with_order_do_not_recreate_indices() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length) DESC),
				KEY compound_key (id,column_1($this->max_index_length) ASC),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34873
	 */
	public function test_primary_key_with_single_space_does_not_recreate_index() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34869
	 */
	public function test_index_definitions_with_spaces_do_not_recreate_indices() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1        (         column_1($this->max_index_length)),
				KEY compound_key (id,      column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34871
	 */
	public function test_index_types_are_not_case_sensitive_and_do_not_recreate_indices() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				key key_1 (column_1($this->max_index_length)),
				key compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34874
	 */
	public function test_key_names_are_not_case_sensitive_and_do_not_recreate_indices() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY KEY_1 (column_1($this->max_index_length)),
				KEY compOUND_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY FULLtext_kEY (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34870
	 */
	public function test_unchanged_key_lengths_do_not_recreate_index() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1({$this->max_index_length})),
				KEY compound_key (id,column_1($this->max_index_length)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			",
			false
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 34870
	 */
	public function test_changed_key_lengths_do_not_recreate_index() {
		global $gcdb;

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				KEY changing_key_length (column_1(20)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertSame(
			array(
				"Added index {$gcdb->prefix}dbdelta_test KEY `changing_key_length` (`column_1`(20))",
			),
			$updates
		);

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				KEY changing_key_length (column_1(50)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1($this->max_index_length)),
				KEY compound_key (id,column_1($this->max_index_length)),
				KEY changing_key_length (column_1(1)),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );

		$updates = dbDelta(
			"
			CREATE TABLE {$gcdb->prefix}dbdelta_test (
				id bigint{$this->bigint_display_width} NOT NULL AUTO_INCREMENT,
				column_1 varchar(255) NOT NULL,
				column_2 text,
				column_3 blob,
				PRIMARY KEY  (id),
				KEY key_1 (column_1),
				KEY compound_key (id,column_1),
				KEY changing_key_length (column_1),
				FULLTEXT KEY fulltext_key (column_1)
			) {$this->db_engine}
			"
		);

		$this->assertEmpty( $updates );
	}

	/**
	 * @ticket 31679
	 */
	public function test_column_type_change_with_hyphens_in_name() {
		global $gcdb;

		$schema = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test2 (
				`foo-bar` varchar(255) DEFAULT NULL
			)
		";

		// phpcs:ignore GeChiUI.DB.PreparedSQL.NotPrepared
		$gcdb->query( $schema );

		$schema_update = "
			CREATE TABLE {$gcdb->prefix}dbdelta_test2 (
				`foo-bar` text DEFAULT NULL
			)
		";

		$updates = dbDelta( $schema_update );

		$gcdb->query( "DROP TABLE IF EXISTS {$gcdb->prefix}dbdelta_test2" );

		$this->assertSame(
			array(
				"{$gcdb->prefix}dbdelta_test2.foo-bar" => "Changed type of {$gcdb->prefix}dbdelta_test2.foo-bar from varchar(255) to text",
			),
			$updates
		);
	}
}
