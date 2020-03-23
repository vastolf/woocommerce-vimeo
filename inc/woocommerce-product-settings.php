<?php

// New Vimeo Tab on WooCommerce settings page
add_filter( 'woocommerce_product_data_tabs', 'wc_vimeo_add_product_tab' );
function wc_vimeo_add_product_tab( $product_data_tabs ) {
	$product_data_tabs['vimeo'] = array(
		'label' => __('Vimeo', 'wc-vimeo'),
		'target' => 'wc_vimeo_id',
        'class'  => array('show_if_virtual'),
	);
	return $product_data_tabs;
}

// New Vimeo Tab content on WooCommerce settings page
add_action( 'woocommerce_product_data_panels', 'wc_vimeo_tab_content' );
function wc_vimeo_tab_content() {
	?>
	<div id="wc_vimeo_id" class="panel woocommerce_options_panel">
		<?php
		$options = [];
		$options[''] = __( 'Select a Vimeo video', 'wc-vimeo'); // default value
		$productOptions = wc_vimeo_get_product_options();
		if (is_array($productOptions) && !empty($productOptions)) {
			foreach ($productOptions as $key => $value) {
				$options[$key] = $value;
			}
		}
		woocommerce_wp_select(array(
			'id'            => 'wc_vimeo_id',
			'wrapper_class' => 'show_if_simple',
			'label'         => __('Select a video', 'wc-vimeo'),
            'description'   => __('<p>This will be the Vimeo video associated with this product for purchase. The video should be configured to only be <a target="_blank" href="https://vimeo.com/blog/post/eyes-privacy-settings-share-your-videos-securely/">accessible with a password at Vimeo.</a><p>', 'wc-vimeo'),
			'options' => $options
		));
		?>
	</div>
	<?php
}

// Save tab settings
add_action('woocommerce_process_product_meta', 'wc_vimeo_save_tab_settings');
function wc_vimeo_save_tab_settings($post_id) {
	$tagPrefix = 'wc_vimeo_';
    if (isset($_POST['wc_vimeo_id'])) {
		$videoMetaTags = ['link', 'uri', 'name', 'description', 'duration', 'status', 'password'];
		$videoData = wc_vimeo_get_set_video_item_transient($_POST['wc_vimeo_id']);
		wc_vimeo_update_delete_post_meta($post_id, $tagPrefix.'id', $_POST['wc_vimeo_id']);
		foreach ($videoMetaTags as $tag) {
			wc_vimeo_update_delete_post_meta($post_id, $tagPrefix.$tag, $videoData->$tag);
		}
	}
}

?>
