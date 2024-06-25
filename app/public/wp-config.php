<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'Mvd.T561aX/*G].uoFSa[5:7mh$Qs{oetZjoSywKGA|Y8Z#t2.l6omUtyxf$ZTwh' );
define( 'SECURE_AUTH_KEY',   '<wn#3An_m!qbP0.R4-oqp(aNI>dfFRs/h<` >B|1]#g2y|`mqB^n@*b5S`wP_evs' );
define( 'LOGGED_IN_KEY',     '1g~Oe2giQnP}/(KqMy`z{;d&M4erN-uc^7lx#?,Tg/89J w{UuKPuQ$:&X(+Gb5H' );
define( 'NONCE_KEY',         'ImXBzAIF MFzkc{S3BW()Y]y%MAVwiyOChLzf*VcGRC9i%FJZKbqOdTfQas{+#Jm' );
define( 'AUTH_SALT',         'CdEvK.qC+k50Cp9{dvgkm2jR6NYl;>tZr88pA..6ll7UXSVi&>*U<NGb-LA+myyt' );
define( 'SECURE_AUTH_SALT',  '+Cz*l5rII~94$$@iA;0z}F8:sXE(Bsb^@nci^.Ml3:]+fEu?7?Ed^y74P,>&u|(G' );
define( 'LOGGED_IN_SALT',    'B$se}oa[OU4`x:esN`:4Yb`QHIFvg`tMI;fSbxYil.F|=PpmvX#f5]_RM;f5YceP' );
define( 'NONCE_SALT',        'G,n,^:Ah1M<nhqMRVqXgd~ajds4GX$*e,;K|f+8:%at,YC^sUqa-y;X[lG7Mn*Hn' );
define( 'WP_CACHE_KEY_SALT', 'pO=Qk0(@Vz1Y2HIkNYesR-;qUW5L:b*A;chd*H4=wNi9LX0M)QWHHPdlU=~Ru3*k' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
