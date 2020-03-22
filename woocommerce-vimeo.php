<?php
/*
*	Plugin Name: WooCommerce Vimeo
*	Plugin URI: https://www.nueue.net/
*	Description: WooCommerce Vimeo
*	Version: 0.0.2
*	Author: Vincent Astolfi
*   Author URI: https://nueue.net/
*	Text Domain: woocommerce-vimeo
*   WC requires at least: 3.5
*   WC tested up to: 4.0.0
*	Support: https://wordpress.org/support/plugin/wc-vimeo/
*/

require('vendor/autoload.php'); // Composer (includes Vimeo API library vimeo/vimeo-api)
require('inc/functions.php'); // Main functionality of plugin lives here
require('inc/scripts.php'); // Minimal CSS & JS imports for Admin
require('inc/settings.php'); // Woocommerce Admin Settings
require('inc/woocommerce-product-settings.php'); // Woocommerce Product Settings

?>