<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('WC_Settings_PCNOrderSync')) {

    function pcnordersync_add_homerunner_settings() {

        class WC_Settings_PCNOrderSync extends WC_Settings_Page
        {
            public function __construct() {
                $this->id = 'pcnordersync';
                $this->label = __('PCN OrderSync - Settings', 'homerunner-pcn-ordersync');

                add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
                add_action('woocommerce_settings_' . $this->id, array($this, 'output'));

                add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
                add_action('woocommerce_get_settings_for_' . $this->id, array($this, 'get_option'));
                add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
            }

            // Get settings array - Returns all input fields
            public function get_settings($current_section = '') {
                $menu = array(
                    array(
                        'name' => __('PCN OrderSync - Settings', 'homerunner-pcn-ordersync'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'pcnordersync_setting',
                    ),
                    array(
                        'name'    => __( 'Status for new order', 'homerunner-pcn-ordersync' ),
                        'desc'    => __( 'Add the status new orders in your webshop gets', 'homerunner-pcn-ordersync' ),
                        'id'      => 'pcn_settings_neworderstatus',
                        'css'     => 'min-width:150px;',
                        'std'     => 'left', // WooCommerce < 2.0
                        'default' => 'left', // WooCommerce >= 2.0
                        'type'    => 'select',
                        'options' => array(
                            'pending'        => __( 'Pending payment', 'homerunner-pcn-ordersync' ),
                            'processing'       => __( 'Processing', 'homerunner-pcn-ordersync' ),
                            'on-hold'  => __( 'On hold', 'homerunner-pcn-ordersync' ),
                            'completed' => __( 'Completed', 'homerunner-pcn-ordersync' ),
                            'cancelled' => __( 'Cancelled', 'homerunner-pcn-ordersync' ),
                            'refunded' => __( 'Refunded', 'homerunner-pcn-ordersync' ),
                            'failed' => __( 'Failed', 'homerunner-pcn-ordersync' ),
                        ),
                        'desc_tip' =>  true,
                    ),
                    array(
                        'name'    => __( 'Wanted status when order is packed and ready to charge?', 'homerunner-pcn-ordersync' ),
                        'desc'    => __( 'What status do you want the plugin to set the order when order is packed.', 'homerunner-pcn-ordersync' ),
                        'id'      => 'pcn_settings_wantedorderstatus',
                        'css'     => 'min-width:150px;',
                        'std'     => 'left', // WooCommerce < 2.0
                        'default' => 'left', // WooCommerce >= 2.0
                        'type'    => 'select',
                        'options' => array(
                            'pending'        => __( 'Pending payment', 'homerunner-pcn-ordersync' ),
                            'processing'       => __( 'Processing', 'homerunner-pcn-ordersync' ),
                            'on-hold'  => __( 'On hold', 'homerunner-pcn-ordersync' ),
                            'completed' => __( 'Completed', 'homerunner-pcn-ordersync' ),
                            'cancelled' => __( 'Cancelled', 'homerunner-pcn-ordersync' ),
                            'refunded' => __( 'Refunded', 'homerunner-pcn-ordersync' ),
                            'failed' => __( 'Failed', 'homerunner-pcn-ordersync' ),
                        ),
                        'desc_tip' =>  true,
                    ),
                    array(
                        'name' => __('Debug', 'homerunner-pcn-ordersync'),
                        'type' => 'checkbox',
                        'id' => 'pcn_settings_debug',
                        'desc_tip' => false,
                        'desc' => __('Debug', 'homerunner-pcn-stocksync'),
                    )
                );

                $settings = apply_filters('pcnordersync_settings', $menu);
                return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
            }

            // Save settings
            public function save()
            {
                parent::save();
            }
        }

        return new WC_Settings_PCNOrderSync();
    }

    add_filter('woocommerce_get_settings_pages', 'pcnordersync_add_homerunner_settings', 17);

}

