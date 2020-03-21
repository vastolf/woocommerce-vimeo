<?php

// WooCommerce Vimeo settings tab
class WC_Settings_WooCommerce_Vimeo {
    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_vimeo', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_vimeo', __CLASS__ . '::update_settings' );
    }
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['vimeo'] = __( 'Vimeo', 'wc-vimeo' );
        return $settings_tabs;
    }
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
        

        $settings = array(

            array(
                'name' => __( 'Vimeo API Configuration', 'woocommerce-to-vimeo' ),
                'type' => 'title',
                'desc' => __('<p>These options allow you to connect your site to your Vimeo account. Once you have done so, you can create products associated with your private, password protected Vimeo videos.</p>', 'woocommerce'),
                'id'   => 'wc_vimeo_api_configuration'
            ),

            array(
                'name' => __( 'Client ID', 'woocommerce-to-vimeo' ),
                'type' => 'text',
                'desc_tip' => __( 'You\'ll need to get this from your Vimeo App settings https://developer.vimeo.com/apps/', 'woocommerce-to-vimeo' ),
                'desc' => '<br />',
                'css'  => 'min-width:320px;',
                'id'   => 'wc_settings_vimeo_client_id'
            ),

            array(
                'name' => __( 'Client Secret', 'woocommerce-to-vimeo' ),
                'type' => 'text',
                'desc_tip' => __( 'You\'ll need to get this from your Vimeo App settings https://developer.vimeo.com/apps/', 'woocommerce-to-vimeo' ),
                'desc' => '<br />',
                'css'  => 'min-width:320px;',
                'id'   => 'wc_settings_vimeo_client_secret'
            ),

            array(
                'name' => __( 'Access Token', 'woocommerce-to-vimeo' ),
                'type' => 'text',
                'desc_tip' => __( 'You\'ll need to generate this from your Vimeo App settings https://developer.vimeo.com/apps/', 'woocommerce-to-vimeo' ),
                'desc' => '<br />',
                'css'  => 'min-width:320px;',
                'id'   => 'wc_settings_vimeo_access_token'
            ),

            //section end
            array( 'type' => 'sectionend', 'id' => 'wc_vimeo_api_configuration' ),

            array(
                'name' => __( 'Woocommerce Vimeo Cache Options', 'woocommerce-to-vimeo' ),
                'type' => 'title',
                'desc' => __('<p>The options below control caching of data requested from Vimeo. You can control how often your site re-requests data from Vimeo (a resource intensive task) and caches it, as well as clear the current cache.</p>
                              <div class="wc-vimeo__cache-clear-wrapper"><button id="woocommerce-vimeo-cache-clear-1" class="wc-vimeo__cache-clear button secondary">Clear Vimeo Cache</button><span>Warning: Clearing your Vimeo Data Cache will force your site to re-request all of your video data from Vimeo. You may need to do this because you have new or updated content at Vimeo that isn\'t yet showing up on your site/when adding Products. However, depending on how many videos you have, this can be a resourse intensive process.</span></div>', 'woocommerce'),
                'id'   => 'wc_vimeo_cache_configuration'
            ),

            array(
                'name' => __( 'Vimeo cache duration (in minutes)', 'woocommerce-to-vimeo' ),
                'type' => 'number',
                'default' => '15',
                'desc' => 'To increase performance, this plugin caches your Vimeo videos\' data for a set number of minutes, after which it is cleared, and will need to be re-requested from Vimeo. Set the number of minutes you\'d like between Vimeo data cache clears (default: 15).<br />',
                'css'  => 'min-width:50px;',
                'id'   => 'wc_settings_vimeo_transient_duration'
            ),

            array( 'type' => 'sectionend', 'id' => 'wc_vimeo_cache_configuration' ),
        );
      
        return apply_filters( 'wc_settings_vimeo', $settings );
    }
}

WC_Settings_WooCommerce_Vimeo::init();

// Add a settings link on the plugin page
function woocommerce_vimeo_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wc-settings&tab=vimeo">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'woocommerce_vimeo_add_settings_link' );

// Handles the AJAX request triggered by the "Clear Vimeo Cache" button on the settings page
// Deletes all transients related to woocommerce-vimeo
function woocommerce_vimeo_ajax_action_function() {
    $reponse = array();
    if(!empty($_POST['clear']) && $_POST['clear'] == 'true'){
        $options = woocommerce_vimeo_get_product_options();
        $duration = woocommerce_vimeo_get_transient_duration() / 60;
        if (false !== get_transient('woocommerce_vimeo_videos_main_transient')) {
            delete_transient('woocommerce_vimeo_videos_main_transient');
        }
        foreach($options as $key => $value) {
            $transientName = 'woocommerce_vimeo_video_'.$key;
            if (false !== get_transient($transientName)) {
                delete_transient($transientName);
            }
        }
        $response['response'] = 'The Vimeo Data Cache has been cleared for your site. It will clear again automatically in '.$duration.' minutes.';
    }
    header( "Content-Type: application/json" );
    echo json_encode($response);
    exit(); // do not remove
}

add_action('wp_ajax_wc_vimeo_cache_clear', 'woocommerce_vimeo_ajax_action_function');

?>