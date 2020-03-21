<?php

// New Vimeo Tab on WooCommerce settings page
add_filter( 'woocommerce_product_data_tabs', 'woocommerce_vimeo_add_product_tab' );
function woocommerce_vimeo_add_product_tab( $product_data_tabs ) {
	$product_data_tabs['vimeo'] = array(
		'label' => __('Vimeo', 'wc-vimeo'),
		'target' => 'wc_vimeo_id',
        'class'  => array('show_if_virtual'),
	);
	return $product_data_tabs;
}

// New Vimeo Tab content on WooCommerce settings page
add_action( 'woocommerce_product_data_panels', 'woocommerce_vimeo_tab_content' );
function woocommerce_vimeo_tab_content() {
	?>
	<div id="wc_vimeo_id" class="panel woocommerce_options_panel">
		<?php
		woocommerce_wp_select(array(
			'id'            => 'wc_vimeo_id',
			'wrapper_class' => 'show_if_simple',
			'label'         => __('Select a video', 'wc-vimeo'),
            'description'   => __('<p>This will be the Vimeo video associated with this product for purchase. The video should be configured to only be <a target="_blank" href="https://vimeo.com/blog/post/eyes-privacy-settings-share-your-videos-securely/">accessible with a password at Vimeo.</a><p>', 'wc-vimeo'),
			'options' => woocommerce_vimeo_get_product_options()
		));
		?>
	</div>
	<?php
}

// Save tab settings
add_action('woocommerce_process_product_meta', 'woocommerce_vimeo_save_tab_settings');
function woocommerce_vimeo_save_tab_settings($post_id) {
    if (isset($_POST['wc_vimeo_id'])) {
		$video = woocommerce_vimeo_get_set_video_item_transient($_POST['wc_vimeo_id']);
		if (isset($video->name)) {
			update_post_meta($post_id, 'wc_vimeo_name', wc_clean($video->name));
		}
		if (isset($video->link)) {
			update_post_meta($post_id, 'wc_vimeo_link', wc_clean($video->link));
		}
		if (isset($video->uri)) {
			update_post_meta($post_id, 'wc_vimeo_uri', wc_clean($video->uri));
		}
		if (isset($video->description)) {
			update_post_meta($post_id, 'wc_vimeo_description', wc_clean($video->description));
		}
		if (isset($video->status)) {
			update_post_meta($post_id, 'wc_vimeo_status', wc_clean($video->status));
		}
		if (isset($video->password)) {
			update_post_meta($post_id, 'wc_vimeo_password', wc_clean($video->password));
		}
		if (isset($video->duration)) {
			update_post_meta($post_id, 'wc_vimeo_duration', wc_clean($video->duration));
		}
		update_post_meta($post_id, 'wc_vimeo_id', wc_clean($_POST['wc_vimeo_id']));
	}
}

?>
