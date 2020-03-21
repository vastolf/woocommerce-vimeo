jQuery(document).ready(function ($) {
    // Sends signal to wc_vimeo_cache_clear in settings.php to clear caches
    $('#woocommerce-vimeo-cache-clear-1').bind('click', function(e) { 
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: { 
                action: 'wc_vimeo_cache_clear', 
                clear: 'true' 
            }
          }).done(function(msg) {
            alert(msg.response)
         });
    });
});   