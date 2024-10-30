<?php
/**
 * Plugin Name: Coupon Zen
 * Description: Create Custom Coupons codes and benefit from a variety of user-friendly features.
 * Plugin URI:  https://hasthemes.com/plugins/
 * Author:      HasThemes
 * Author URI:  https://hasthemes.com/
 * Version:     1.1.0
 * License:     GPL2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: couponzen
 * Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

define( 'COUPONZEN_VERSION', '1.1.0' );
define( 'COUPONZEN_PL_ROOT', __FILE__ );
define( 'COUPONZEN_PL_URL', plugins_url( '/', COUPONZEN_PL_ROOT ) );
define( 'COUPONZEN_PL_PATH', plugin_dir_path( COUPONZEN_PL_ROOT ) );
define( 'COUPONZEN_PL_INCLUDE', COUPONZEN_PL_PATH .'include/' );
define( 'COUPONZEN_PL_FRONTEND', COUPONZEN_PL_PATH .'frontend/' );

// Plugin Init
include( COUPONZEN_PL_INCLUDE.'/class.couponzen.php' );
// Admin Pages Init
include( COUPONZEN_PL_PATH.'/admin/class.admin-init.php' );

// Initialize Main Class
HTCoupon_Zen::instance();