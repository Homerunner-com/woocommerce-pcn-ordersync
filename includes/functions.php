<?php

// Create cron function
add_action('wp_scheduled_pcn_ordersync_checkorder', 'pcn_ordersync_checkorder');
function pcn_ordersync_checkorder() {
    $old_tz = date_default_timezone_get();

    // Handle dates
    // Gets orders packed the last 5 hours
    date_default_timezone_set('Europe/Copenhagen');
    $startDate = date("d-m-y H:i", (time()-(60*60)*5));
    $endDate = date("d-m-y H:i", time());
    date_default_timezone_set($old_tz);

    $ordersWithinPeriod = PCNOrderSync_Curl::getOrdersFromPeriod($startDate, $endDate);

    if(get_option('pcn_settings_debug') == 'yes') {
        error_log('Response from getOrdersFromPeriod: ' . print_r($ordersWithinPeriod, 1));
    }

    if(isset($ordersWithinPeriod->results) AND count($ordersWithinPeriod->results)) {
        foreach ($ordersWithinPeriod->results as $pcnOrder) {
            $wcOrder = wc_get_order($pcnOrder->shop_orderno);

            if (!empty($wcOrder)) {
                $shouldCheck = false;

                if ($wcOrder->get_status() == get_option('pcn_settings_neworderstatus')) {
                    $shouldCheck = true;
                }

                if ($pcnOrder->state == 100 AND $pcnOrder->state_txt == 'packed' AND $shouldCheck) {
                    $wcOrder->update_status(get_option('pcn_settings_wantedorderstatus'), '<b>PakkecenterNord (' . $pcnOrder->packed_date . '):</b> Pakken er blevet pakket og afsendes nu.');
                }
            }
        }
    }
}

// Register cron intervals
add_filter('cron_schedules', 'pcn_ordersync_cron_intervals');
function pcn_ordersync_cron_intervals($schedules) {
    $schedules['every5minute'] = array(
        'interval' => 300, // 300 seconds = 5 minutes
        'display' => __('Every 5 minutes', 'homerunner-pcn-ordersync')
    );

    return $schedules;
}

// Handle activation of plugin and create event
register_activation_hook(PLUGIN_FILE_URL, 'pcn_ordersync_activate');
function pcn_ordersync_activate() {
    wp_schedule_event(time(), 'every5minute', 'wp_scheduled_pcn_ordersync_checkorder');
}

// Handle deactivation of plugin and remove event
register_deactivation_hook(PLUGIN_FILE_URL, 'pcn_ordersync_deactivate');
function pcn_ordersync_deactivate() {
    wp_clear_scheduled_hook('wp_scheduled_pcn_ordersync_checkorder');
}

