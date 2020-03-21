<?php
// Load Styles & Scripts in admin for Vimeo for Woocommerce
function woocommerce_vimeo_register_admin_styles($hook){
    global $pagenow;
    //apply styles/scripts to plugin settings page and also edit and post pages (order overview and detail pages)
    if('admin.php' == $pagenow && $_GET['page'] == 'wc-settings' || 'post.php' == $pagenow || 'edit.php' == $pagenow){
        //scripts
        wp_enqueue_script( 'admin-script-vimeo', plugins_url( 'js/scripts.js', __FILE__ ), array( 'jquery' ));
        //styles
        wp_enqueue_style( 'admin-style-vimeo', plugins_url( 'css/styles.css', __FILE__ ), array());
    }
}
add_action( 'admin_enqueue_scripts', 'woocommerce_vimeo_register_admin_styles' );

?>