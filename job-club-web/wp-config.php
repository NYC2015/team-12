<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'test');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', '');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm:jSKCua#`z4H+AhW8V1?l=wIubskyZRQ+3.-Y,fHeY;%LGdGjKVS` -o,EiGI!*');
define('SECURE_AUTH_KEY',  '@#!g`(3OJOq.IB%6qcL8!|e hh,fsF.ayrx`!jMIgdEcsiDC;dA(&DKfMnLM=:~U');
define('LOGGED_IN_KEY',    'vtLX-]Zv.Gzk9c6G~?.0_A{D9Xo4OA>nR+3YGG%YJhaHKdB,2r1GcGmUe@yDDa(*');
define('NONCE_KEY',        '.pS`Vk%oEB~Ua?<*M;6.5NaNJ[)!#8`%OCFaYAj1-Y?b5<yp{%B]%WFg-%e%mxoP');
define('AUTH_SALT',        '~`:UwSHmCp^)p(~u9 QPS(Je2Q<hG mYS@l4X;d70B4tPdH`+?Q2{WcFj-1vmvK{');
define('SECURE_AUTH_SALT', 'NdV#FGojNT]rWLA}bIqr?E_s;]YD7SzA^.e%E:H9uLR^e2tD1Z<65ML2QOtem$ue');
define('LOGGED_IN_SALT',   'ft2((Wg)G+!i!|fb ;,EqY=j.tlIdOg9&dR0BSv_SXdbLJ:`$zNH#;xx!fBJqt%:');
define('NONCE_SALT',       '/>g_?esenA<wc:=HV@$&4d+|[ET>:{Ok%&T(28#i#`dzO?F8wno,iWl?VAoE5bln');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ctcf_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
