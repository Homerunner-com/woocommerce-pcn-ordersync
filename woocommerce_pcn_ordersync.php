<?php
/**
 * Plugin Name: HomeRunner: PCN OrderSync
 * Plugin URI: http://homerunner.om
 * Description: This plugins keeps orders status updated with PakkecenterNord
 * Version: 1.0
 * Author: HomeRunner
 * Author URI: http://homerunner.com
 * Developer: Kevin Hansen / HomeRunner
 * Developer URI: http://homerunner.dk
 * Text Domain: homerunner-pcn-ordersync
 * Domain Path: /languages
 *
 * Developed with: Wordpress 5.3.2
 * Developed with: WooCommerce 3.9.0
 *
 * Copyright: © 2023- HomeRunner.com
 * License: MIT
 */

// Check if absolute path of wordpress directory else exit
if (!defined('ABSPATH')) {
    exit;
}

// Define version of plugin
define('PCN_WOOCOMMERCE_ORDER', '1.0');
define('PLUGIN_FILE_URL', __FILE__);

add_action('plugins_loaded', 'pcn_ordersync_load_textdomain');
function pcn_ordersync_load_textdomain() {
    load_plugin_textdomain('homerunner-pcn-ordersync', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Check if woocommerce is active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (!is_plugin_active('woocommerce/woocommerce.php')) {
    // If WooCommerce isn't active then give admin a warning
    add_action('admin_notices', function () {
        ?>
        <div class="notice notice-warning">
            <p><?php echo __('PCN OrderSync requires that WooCommerce is installed.', 'homerunner-pcn-ordersync'); ?></p>
            <p><?php echo __('You can download WooCommerce here: ', 'homerunner-pcn-ordersync') . sprintf('<a href="%s/wp-admin/plugin-install.php?s=WooCommerce&tab=search&type=term">Download</a>', get_site_url()) ?></p>
        </div>
        <?php
    });
    return;

} elseif (!is_plugin_active('homerunner-pcn-stocksync/woocommerce_pcn_stocksync.php')) {
    // If HomeRunner: PCN StockSync isn't active then give admin a warning
    add_action('admin_notices', function () {
        ?>
        <div class="notice notice-warning">
            <p><?php echo __('PCN OrderSync requires that PCN StockSync is installed.', 'homerunner-pcn-ordersync'); ?></p>
            <p><?php echo __('You can download PCN StockSync here: ', 'homerunner-pcn-ordersync') . sprintf('<a href="https://github.com/Homerunner-com/woocommerce-pcn-stocksync">Download</a>', get_site_url()) ?></p>
        </div>
        <?php
    });
    return;

} else {
    // Define plugin path
    if (!defined('PCN_ORDERSYNC_DIR')) {
        define('PCN_ORDERSYNC_DIR', plugin_dir_path(__FILE__));
    }

    // Add settings link to plugin in overview of plugins
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pcnordersync_action_links');
    function pcnordersync_action_links($links) {
        $links[] = '<a href="' . admin_url('admin.php?page=wc-settings&tab=pcnordersync') . '">Indstillinger</a>';
        return $links;
    }

    include(PCN_ORDERSYNC_DIR . 'includes/curl.php');
    include(PCN_ORDERSYNC_DIR . 'includes/functions.php');
    include(PCN_ORDERSYNC_DIR . 'includes/admin/class-pcnordersync-settings.php');
}
