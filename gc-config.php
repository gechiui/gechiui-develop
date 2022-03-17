<?php
/**
 * The base configuration for GeChiUI
 *
 * The gc-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "gc-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://www.gechiui.com/support/article/editing-gc-config-php/
 *
 * @package GeChiUI
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for GeChiUI */
define( 'DB_NAME', 'gechiui_develop' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'mysql' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.gechiui.com/secret-key/1.1/salt/ www.GeChiUI.com secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'rf<8k;kami,N,-$s^m>ASq*@?vKFOzlZQ1y)iUIv}zPYm~I!P?+_PE6ziybR3)O.' );
define( 'SECURE_AUTH_KEY',   'XD:osm&,aw|sX%Mj^{A,0KT;Yt.gH5jKp3A.,x:MTadziA>!hTbQ:V3UfD=o:T%)' );
define( 'LOGGED_IN_KEY',     'tfLSoaVRre%^?.njtQL5syo?&U-7&kGz/01D#y061V*4Hpx I|TW7.H7W|uW$xf?' );
define( 'NONCE_KEY',         ':N)V^-H|s~#}@:^nC dZ?R4,k[be_oSbMlCfY5Y@dozVSsX4U*)r_y;Fh]@X9_7!' );
define( 'AUTH_SALT',         '%D+?}&EAm:o 4p(MOu`U[UsOtWF=7HZs)6GUt#-+3f^C|},s#KH%;%+%C?si=Nyl' );
define( 'SECURE_AUTH_SALT',  'B]sv8:O.@hMu!g*yfE}h5#U2)I?D1^Ye*D@H`r_.$U7/wjGtb=PS9#t]v%  !7u6' );
define( 'LOGGED_IN_SALT',    '`(GAVz^WDW?c!cjLy3a$;qt.;2?{#kNnx#x)-icr4{1d.b@X}5-YU?&F`#LUU$F}' );
define( 'NONCE_SALT',        '1~w2@,j1^*tF65#$Kb0ORoprl:O1rT0M6x`,UH!-d?sRR-0uMq7dqe~K2a/,s4^c' );
define( 'GC_CACHE_KEY_SALT', 'i]hszVo8*,^V%dnv*-dI?qpM2koYYj[Z@}.HiDwIvYPo_AfWAutwcn+H&|yaFD7i' );


/**#@-*/

/**
 * GeChiUI database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'gc_';

/**
 * For developers: GeChiUI debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use GC_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://www.gechiui.com/support/article/debugging-in-gechiui/
 */
define( 'GC_DEBUG', true );


/* Add any custom values between this line and the "stop editing" line. */



define( 'GC_DEBUG_LOG', true );
define( 'GC_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
define( 'GC_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the GeChiUI directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up GeChiUI vars and included files. */
require_once ABSPATH . 'gc-settings.php';
