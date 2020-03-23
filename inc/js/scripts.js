jQuery(document).ready(function ($) {
    // Sends signal to wc_vimeo_cache_clear in settings.php to clear caches
    $('#woocommerce-vimeo-cache-clear-1').bind('click', function(e) {
        $(e.target).attr('disabled', 'disabled');
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: { 
                action: 'wc_vimeo_cache_clear', 
                clear: 'true' 
            }
          }).done(function(msg) {
            alert(msg.response);
            $(e.target).removeAttr('disabled');
         });
    });

    // Sends signal to wc_vimeo_cron_run in settings.php to clear caches
    $('#woocommerce-vimeo-cron-run-1').bind('click', function(e) {
        $(e.target).attr('disabled', 'disabled');
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: { 
                action: 'wc_vimeo_cron_run', 
                run: 'true' 
            }
          }).done(function(msg) {
              if (msg.response != false) {
                  var alertText = 'The following updates were made:';
                  for (item in msg.response) {
                      for (tag in msg.response[item]) {
                          alertText += '\r\n';
                          alertText += 'Product '+item+' - '+tag+': '+msg.response[item][tag]['update'];
                      }
                  }
                  alert(alertText);
              } else {
                  alert('No updates to Product Vimeo video data needed -- already up to date');
              }
              $(e.target).removeAttr('disabled');
         });
    });
});   