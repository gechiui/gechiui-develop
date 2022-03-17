<?php

/* Path to the GeChiUI codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', dirname( __FILE__ ) . '/src/' );

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the GeChiUI installation defined above.
 */
define( 'GC_DEFAULT_THEME', 'default' );

/*
 * Test with multisite enabled.
 * Alternatively, use the tests/phpunit/multisite.xml configuration file.
 */
// define( 'GC_TESTS_MULTISITE', true );

/*
 * Force known bugs to be run.
 * Tests with an associated Trac ticket that is still open are normally skipped.
 */
// define( 'GC_TESTS_FORCE_KNOWN_BUGS', true );

// Test with GeChiUI debug mode (default).
define( 'GC_DEBUG', true );

// ** Database settings ** //

/*
 * This configuration file will be used by the copy of GeChiUI being tested.
 * gechiui/gc-config.php will be ignored.
 *
 * WARNING WARNING WARNING!
 * These tests will DROP ALL TABLES in the database with the prefix named below.
 * DO NOT use a production database or one that is shared with something else.
 */

define( 'DB_NAME', 'youremptytestdbnamehere' );
define( 'DB_USER', 'yourusernamehere' );
define( 'DB_PASSWORD', 'yourpasswordhere' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.gechiui.com/secret-key/1.1/salt/ www.GeChiUI.com secret-key service}
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'gctests_';   // Only numbers, letters, and underscores please!

define( 'GC_TESTS_DOMAIN', 'example.org' );
define( 'GC_TESTS_EMAIL', 'admin@example.org' );
define( 'GC_TESTS_TITLE', 'Test Blog' );

define( 'GC_PHP_BINARY', 'php' );

define( 'GCLANG', '' );
