<?php
/**
 * Installs GeChiUI for the purpose of the unit-tests
 *
 * @todo Reuse the init/load code in init.php
 */
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

$config_file_path = $argv[1];
$multisite        = in_array( 'run_ms_tests', $argv, true );

if ( ! defined( 'GC_RUN_CORE_TESTS' ) && in_array( 'run_core_tests', $argv, true ) ) {
	define( 'GC_RUN_CORE_TESTS', true );
}

define( 'GC_INSTALLING', true );

/*
 * Cron tries to make an HTTP request to the site, which always fails,
 * because tests are run in CLI mode only.
 */
define( 'DISABLE_GC_CRON', true );

require_once $config_file_path;
require_once __DIR__ . '/functions.php';

// Set the theme to our special empty theme, to avoid interference from the current Twenty* theme.
if ( ! defined( 'GC_DEFAULT_THEME' ) ) {
	define( 'GC_DEFAULT_THEME', 'default' );
}

tests_reset__SERVER();

$PHP_SELF            = '/index.php';
$GLOBALS['PHP_SELF'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

tests_add_filter( 'gc_die_handler', '_gc_die_handler_filter_exit' );

require_once ABSPATH . '/gc-settings.php';

require_once ABSPATH . '/gc-admin/includes/upgrade.php';
require_once ABSPATH . '/gc-includes/gc-db.php';

// Override the PHPMailer.
global $phpmailer;
require_once __DIR__ . '/mock-mailer.php';
$phpmailer = new MockPHPMailer();

register_theme_directory( __DIR__ . '/../data/themedir1' );

/*
 * default_storage_engine and storage_engine are the same option, but storage_engine
 * was deprecated in MySQL (and MariaDB) 5.5.3, and removed in 5.7.
 */
if ( version_compare( $gcdb->db_version(), '5.5.3', '>=' ) ) {
	$gcdb->query( 'SET default_storage_engine = InnoDB' );
} else {
	$gcdb->query( 'SET storage_engine = InnoDB' );
}
$gcdb->select( DB_NAME, $gcdb->dbh );

echo 'Installing...' . PHP_EOL;

$gcdb->query( 'SET foreign_key_checks = 0' );
foreach ( $gcdb->tables() as $table => $prefixed_table ) {
	//phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
	$gcdb->query( "DROP TABLE IF EXISTS $prefixed_table" );
}

foreach ( $gcdb->tables( 'ms_global' ) as $table => $prefixed_table ) {
	//phpcs:ignore GeChiUI.DB.PreparedSQL.InterpolatedNotPrepared
	$gcdb->query( "DROP TABLE IF EXISTS $prefixed_table" );

	// We need to create references to ms global tables.
	if ( $multisite ) {
		$gcdb->$table = $prefixed_table;
	}
}
$gcdb->query( 'SET foreign_key_checks = 1' );

// Prefill a permalink structure so that GC doesn't try to determine one itself.
add_action( 'populate_options', '_set_default_permalink_structure_for_tests' );

gc_install( GC_TESTS_TITLE, 'admin', GC_TESTS_EMAIL, true, null, 'password' );

// Delete dummy permalink structure, as prefilled above.
if ( ! is_multisite() ) {
	delete_option( 'permalink_structure' );
}
remove_action( 'populate_options', '_set_default_permalink_structure_for_tests' );

if ( $multisite ) {
	echo 'Installing network...' . PHP_EOL;

	define( 'GC_INSTALLING_NETWORK', true );

	$title             = GC_TESTS_TITLE . ' Network';
	$subdomain_install = false;

	install_network();
	$error = populate_network( 1, GC_TESTS_DOMAIN, GC_TESTS_EMAIL, $title, '/', $subdomain_install );

	if ( is_gc_error( $error ) ) {
		gc_die( $error );
	}

	$gc_rewrite->set_permalink_structure( '' );
}
