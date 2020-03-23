<?php

// Update a product using ids id ($pid)
// Check if any updates from Vimeo, if so, update product meta
// Return updates that were made to product, if any
function wc_vimeo_product_video_data($pid = null) {
    if ($pid) {
        $videoMetaTags = ['id', 'link', 'uri', 'name', 'description', 'duration', 'status', 'password'];
        $videoMeta = [];

        foreach ($videoMetaTags as $tag) {
            $tagMeta = get_post_meta($pid, 'wc_vimeo_'.$tag, true);
            if ($tagMeta) {
                $videoMeta['wc_vimeo_'.$tag] = $tagMeta;
            }
        }
        if ($videoMeta['wc_vimeo_id']) {
            $videoTransient = wc_vimeo_get_set_video_item_transient($videoMeta['wc_vimeo_id']);

            if ($videoTransient) {
                $updated = [];
                foreach($videoMetaTags as $tag) {
                    if (isset($videoTransient->{$tag}) && strlen($videoTransient->{$tag}) > 0) {
                        if (!isset($videoMeta['wc_vimeo_'.$tag]) || $videoMeta['wc_vimeo_'.$tag] != $videoTransient->{$tag}) {
                            update_post_meta($pid, 'wc_vimeo_'.$tag, $videoTransient->{$tag});
                            $updated['wc_vimeo_'.$tag] = [
                                'update' => $videoTransient->{$tag}
                            ];
                        } 
                    } else {
                        delete_post_meta($pid, 'wc_vimeo_'.$tag);
                        $updated['wc_vimeo_'.$tag] = [
                            'update' => 'EMPTY - DELETED'
                        ];
                    }
                }
                return $updated;
            }
        }
    }
    return false;
}

// Gets all Products with a wc_vimeo_id set
// Updates each product's vimeo meta data using wc_vimeo_product_video_data
// Returns all updates (if any) that were made to the products
function wc_vimeo_update_product_video_meta() {
    $query = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'product',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key'     => 'wc_vimeo_id',
                'value'   => 0,
                'compare' => '>',
            ),
        ),
    ));
    
    if ($query && $query->posts && is_array($query->posts) && !empty($query->posts)) {
        $updates = [];
        foreach ($query->posts as $item) {
            if (isset($item->ID)) {
                $videoData = wc_vimeo_product_video_data($item->ID);
                if ($videoData !== false && is_array($videoData) && !empty($videoData)) {
                    $updates[$item->ID] = $videoData;
                }
            }
        }
        if (!empty($updates)) {
            return $updates;
        }
    }
    return false;
}

// Gets Cron Interval, enforced minimum of 1 minute
function wc_vimeo_get_cron_interval() {
    $cronInterval = get_option('wc_settings_vimeo_cron_interval');
    if ($cronInterval) {
        $cronInterval = intval($cronInterval);
        if ($cronInterval < 1) { // Enforcing minute minimum for cron interval
            return 60;
        } else {
            return $cronInterval*60;
        }
    } else {
        return 15*60;
    }
}

// Custom Interval for Vimeo data Cron task
function wc_vimeo_add_cron_interval($schedules) {
    $interval = wc_vimeo_get_cron_interval(); // Cron interval from Config page WooCommerce > Vimeo
    $schedules['wp_vimeo_schedule'] = array(
        'interval' => $interval,
        'display'  => esc_html__('Video Sales for Woocommerce with Vimeo - Cron Schedule'),
    );
    return $schedules;
}
add_filter('cron_schedules', 'wc_vimeo_add_cron_interval');

// Execute Cron Task hook
function wp_vimeo_cron_exec() {
    $updateMeta = wc_vimeo_update_product_video_meta();
    if ($updateMeta !== false) {
        if (is_array($updateMeta) && !empty($updateMeta)) {
            // Do Something (alert)
        }
    }
}
add_action('wp_vimeo_cron_hook', 'wp_vimeo_cron_exec');

// Schedule Cron Task if not scheduled already
if (!wp_next_scheduled('wp_vimeo_cron_hook')) {
    wp_schedule_event(time(), 'wp_vimeo_schedule', 'wp_vimeo_cron_hook');
}

// Handles the AJAX request triggered by the manual "Run Vimeo Cron Task" button on the settings page
function wc_vimeo_cron_run_ajax_action_function() {
    $reponse = array();
    if(!empty($_POST['run']) && $_POST['run'] == 'true') {
        wc_vimeo_clear_all_transients(); // Runs transient cache clear
        $timeStamp = wp_next_scheduled('wp_vimeo_cron_hook');
        if ($timeStamp) {
            $unschedule = wp_unschedule_event($timeStamp, 'wp_vimeo_schedule', 'wp_vimeo_cron_hook'); // Un-Schedule Cron
            if ($unschedule) {
                wp_schedule_event(time(), 'wp_vimeo_schedule', 'wp_vimeo_cron_hook'); // Re-Schedule Cron
            }
        }
        $response['response'] = wc_vimeo_update_product_video_meta();
    }
    header("Content-Type: application/json");
    //write_log($response);
    echo json_encode($response);
    exit(); // do not remove
}

add_action('wp_ajax_wc_vimeo_cron_run', 'wc_vimeo_cron_run_ajax_action_function');

?>