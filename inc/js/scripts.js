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

    // Sends signal to wc_vimeo_cron_run in settings.php to clear caches
    $('#woocommerce-vimeo-cron-run-1').bind('click', function(e) { 
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            async: false,
            data: { 
                action: 'wc_vimeo_cron_run', 
                run: 'true' 
            }
          }).done(function(msg) {
            if (msg.response) {
                alert('Following updates made: '+msg.response);
            } else {
                alert('There were no updates from Vimeo for your Products -- everything already up to date.');
            }
         });
    });
});   