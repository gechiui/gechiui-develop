<?php
/**
 * gc-mail-real-test.php
 *
 * Test script for gc_mail with real addresses.
 */

// Parse options.
$options = 'v:r:d';
if ( is_callable( 'getopt' ) ) {
	$opts = getopt( $options );
} else {
	require __DIR__ . '/gc-testlib/getopt.php';
	$opts = getoptParser::getopt( $options );
}

define( 'DIR_TESTROOT', realpath( __DIR__ ) );

define( 'TEST_GC', true );
define( 'GC_DEBUG', array_key_exists( 'd', $opts ) );

if ( ! empty( $opts['r'] ) ) {
	define( 'DIR_GC', realpath( $opts['r'] ) );
} elseif ( ! empty( $opts['v'] ) ) {
		define( 'DIR_GC', DIR_TESTROOT . '/gechiui-' . $opts['v'] );
} else {
	define( 'DIR_GC', DIR_TESTROOT . '/gechiui' );
}

// Make sure all useful errors are displayed during setup.
error_reporting( E_ALL & ~E_DEPRECATED );
ini_set( 'display_errors', true );

require_once DIR_TESTROOT . '/gc-testlib/utils.php';

// Configure GC.
require_once DIR_TESTROOT . '/gc-config.php';
define( 'ABSPATH', realpath( DIR_GC ) . '/' );

// Install GC.
define( 'GC_BLOG_TITLE', rand_str() );
define( 'GC_USER_NAME', rand_str() );
define( 'GC_USER_EMAIL', rand_str() . '@example.com' );

// Initialize GC.
define( 'GC_INSTALLING', 1 );
$_SERVER['PATH_INFO'] = $_SERVER['SCRIPT_NAME']; // Prevent a warning from some sloppy code in gc-settings.php.
require_once ABSPATH . 'gc-settings.php';

drop_tables();

require_once ABSPATH . 'gc-admin/includes/upgrade.php';
gc_install( GC_BLOG_TITLE, GC_USER_NAME, GC_USER_EMAIL, true );

// Make sure we're installed.
assert( true === is_blog_installed() );

// phpcs:ignore Generic.NamingConventions.UpperCaseConstantName.ConstantNotUpperCase
define( 'PHPUnit_MAIN_METHOD', false );
$original_gcdb = $GLOBALS['gcdb'];

// Hide warnings during testing, since that's the normal GC behaviour.
if ( ! GC_DEBUG ) {
	error_reporting( E_ALL ^ E_NOTICE );
}

$to        = 'To <gc.mail.testing@gmail.com>';
$from      = 'From <gc.mail.testing+from@gmail.com>';
$cc        = 'CC <gc.mail.testing+cc@gmail.com>';
$bcc       = 'BCC <gc.mail.testing+bcc@gmail.com>';
$subject   = 'RFC2822 Testing';
$message   = 'My RFC822 Test Message';
$headers[] = "From: {$from}";
$headers[] = "CC: {$cc}";

gc_mail( $to, $subject, $message, $headers );

$headers   = array();
$subject   = 'RFC2822 Testing 2';
$message   = 'My RFC822 Test Message 2';
$to        = 'To <gc.mail.testing+to@gmail.com>';
$headers[] = "BCC: {$bcc}";
gc_mail( '', $subject, $message, $headers );
echo "Test emails sent!\n";

