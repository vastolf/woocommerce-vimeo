<?php
/*
*	Plugin Name: Video Sales for Woocommerce with Vimeo
*	Plugin URI: https://www.nueue.net/
*	Description: Allows you to connect your Vimeo account to your WooCommerce site, and sell access to your private, password protected videos at Vimeo to your customers.
*	Version: 0.0.2
*	Author: Vincent Astolfi
*   Author URI: https://nueue.net/
*	Text Domain: woocommerce-vimeo
*   WC requires at least: 3.5
*   WC tested up to: 4.0.0
*	Support: https://wordpress.org/support/plugin/wc-vimeo/
*/

/*
* // Uncomment to enable debug functionality
* require('inc/debug.php'); // introduces write_log(); function
*/

require('vendor/autoload.php'); // Composer (includes Vimeo API library vimeo/vimeo-api)
require('inc/functions.php'); // Main functionality of plugin lives here
require('inc/scripts.php'); // Minimal CSS & JS imports for Admin
require('inc/settings.php'); // Woocommerce Admin Settings
require('inc/cron.php'); // Cron Task / Scheduling / Functionality
require('inc/woocommerce-product-settings.php'); // Woocommerce Product Settings

?>