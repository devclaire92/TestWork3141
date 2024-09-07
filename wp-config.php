<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4oZRz_]_|1;E|u^|P_3c&Qtj$OXY[+ahM P.4)?wxywN%:SRfr0Q3Luw(;ZD&Ixh' );
define( 'SECURE_AUTH_KEY',  '3Bd~K>wsFpbZaw2a,P*1OFZ~FcBhbDC6YRs#7~4W|ldg !O/+8rI]=(qO1cNKN *' );
define( 'LOGGED_IN_KEY',    '>Se}sl8/W3C6H74LLp.)87tX:wVzOt?Ln2n!h9!iZ~,x?)keJf9R3|,9*D H$qX(' );
define( 'NONCE_KEY',        'GZN~;)|NdSx7jHKDsGny=nf#ZR?zKyqoa,Nq9!.aS%P%[NO+qoNCPef:~|VCoF@w' );
define( 'AUTH_SALT',        'wlAXH.O_e*W#=pyht&^XTICcVszq@wm5T^KZQ>iFNU;sdsPbIVr:Af>5|N`:(nV3' );
define( 'SECURE_AUTH_SALT', ',2m<1Ij{)a{J(5|WS26]qRpyh#mb_Z>G{Ps,).xAIwk)en1Yk$E+mA[qFxAIkZ!b' );
define( 'LOGGED_IN_SALT',   'uw%7SaX!b+;fG]t-V+mU&b2P!&6cBPh)e9Xo}{F177J#`y*Ip, fKRcG&dS%N^Yx' );
define( 'NONCE_SALT',       'E0p(c<y+C6MUzV}fboZSN,}snu%Q-_YZ@*3Wv*gwh!MBk[Zi<7(=XWtRYY~!SA*u' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
