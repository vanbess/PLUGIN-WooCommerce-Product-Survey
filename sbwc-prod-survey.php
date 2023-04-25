<?php

/**
 * Plugin Name:       SBWC Product Survey
 * Description:       Displays product survey pop-up on WooCommerce order complete page
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            WC Bessinger
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sb-prod-surv
 */

defined('ABSPATH') || exit();

add_action('plugins_loaded', function () {

    // constants
    define('SBPS_PATH', plugin_dir_path(__FILE__));
    define('SBPS_URL', plugin_dir_url(__FILE__));

    // admin
    include SBPS_PATH . 'admin.php';

    // order thank you
    include SBPS_PATH . 'order-complete.php';
});
