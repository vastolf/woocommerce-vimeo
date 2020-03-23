<?php
use Vimeo\Vimeo;

// Returns transient duration setting from WooCommerce Vimeo options page
// If nothing is set, it uses 15 minutes as the default.
// If set to 0, it will default to 1 minute (60 seconds)
function wc_vimeo_get_transient_duration() {
    $transientDuration = get_option('wc_settings_vimeo_transient_duration');
    if ($transientDuration) {
        $transientDuration = intval($transientDuration);
        if ($transientDuration < 1) { // Enforcing minute minimum for transients
            return 60;
        } else {
            return $transientDuration*60;
        }
    } else {
        return 15*60;
    }
}

// Returns current set values for the Vimeo API configuration
function wc_vimeo_get_config_options() {
    $options = [];
    $clientID = get_option('wc_settings_vimeo_client_id');
    $clientSecret = get_option('wc_settings_vimeo_client_secret');
    $accessToken = get_option('wc_settings_vimeo_access_token');
    if ($clientID && $clientSecret && $accessToken) {
        $options['client_id'] = $clientID;
        $options['client_secret'] = $clientSecret;
        $options['access_token'] = $accessToken;
        return $options;
    }
    return false;
}

// Directly requests video data on the current user,
// Using the configuration options for Vimeo API
// And returns the data in the body of the response
function wc_vimeo_my_videos_request() {
    $configOptions = wc_vimeo_get_config_options();
    if ($configOptions) {
        $client = new Vimeo($configOptions['client_id'], $configOptions['client_secret'], $configOptions['access_token']);
        $response = $client->request('/me/videos', array(), 'GET');
        if (is_array($response['body']) && is_array($response['body']['data'])) {
            if (!empty($response['body']['data'])) {
                return $response['body']['data'];
            }
        }
        return false;
    }
    return false;
}

// Recieves a $video array, and returns it with the extraneous data removed
function wc_vimeo_build_parsed_video($video) {
    if (is_array($video) && !empty($video)) {
        $videoParsed = [];
        $videoParsed['id'] = preg_replace('/[^0-9]/', '', $video['uri']);
        $videoParsed['link'] = $video['link'];
        $videoParsed['uri'] = $video['uri'];
        $videoParsed['name'] = $video['name'];
        $videoParsed['description'] = $video['description'];
        $videoParsed['privacy'] = $video['privacy'];
        $videoParsed['pictures'] = $video['pictures'];
        $videoParsed['status'] = $video['status'];
        $videoParsed['duration'] = $video['duration'];
        $videoParsed['password'] = (isset($video['password']) ? $video['password'] : '');
        return $videoParsed;
    }
    return false;
}

// Get video data from wc_vimeo_my_videos_request
// Parse the data using wc_vimeo_build_parsed_video
// Create a new array of parsed videos, with their key set to their video ID
function wc_vimeo_parse_video_data($encode = false) {
    $data = wc_vimeo_my_videos_request();
    if ($data && !empty($data)) {
        $videoData = [];
        foreach($data as $video) {
            $videoParsed = wc_vimeo_build_parsed_video($video);
            if ($videoParsed && !empty($videoParsed)) {
                $videoID = $videoParsed['id'];
                $videoData[$videoID] = $videoParsed;
            }
        }
        if (!empty($videoData)) {
            if (!$encode) {
                return $videoData;
            } else {
                return json_encode($videoData, JSON_UNESCAPED_SLASHES);
            }
            
        }
        return false;
    }
    return false;
}

// Sets and returns the main Vimeo transient, json_decoded (an object), which contains
// all videos & video data under the account. All other transients
// for individual videos are build from this transient
function wc_vimeo_get_set_video_transient() {
    $transientName = 'wc_vimeo_videos_main_transient';
    $videoTransient = get_transient($transientName);
    $duration = wc_vimeo_get_transient_duration();
    if ($videoTransient != false) {
        return json_decode($videoTransient); // Return JSON decoded transient
    } else {
        $videoData = wc_vimeo_parse_video_data(true);
        set_transient($transientName, $videoData, $duration);
        return json_decode($videoData); // Return video data unencoded
    }
    return false;
}

// Sets and returns a transient, json_decoded (an object), for an individual video
// Is built from the transient created in wc_vimeo_get_set_video_transient
function wc_vimeo_get_set_video_item_transient($vid = false) {
    $vimeoTransient = wc_vimeo_get_set_video_transient();
    $duration = wc_vimeo_get_transient_duration();
    if ($vimeoTransient && !empty($vimeoTransient)) {
        foreach($vimeoTransient as $key => $value) {
            if ($vid && $vid == $key) { // if the $vid provided is this item
                $transientName = 'wc_vimeo_video_'.$vid;
                $video = get_transient($transientName);

                if ($video != false) {
                    return json_decode($video); 
                } else {
                    $videoData = json_encode($value, JSON_UNESCAPED_SLASHES); // json encode video
                    set_transient($transientName, $videoData, $duration);
                    return $value;
                }
            }
        }
    }
    return false;
}

// Get video transient using wc_vimeo_get_set_video_transient
// Turn it into a usable array for WordPress
// When setting the video select dropdown's options on the Product edit page
function wc_vimeo_get_product_options() {
    $options = [];
    $videoData = wc_vimeo_get_set_video_transient();
    if ($videoData && !empty($videoData)) {
        foreach ($videoData as $key => $value) {
            $options[$key] = $value->name;
        }
    }
    if (!empty($options)) {
        return $options;
    }
    return false;
}


// Clears all active Transients set by this plugin
function wc_vimeo_clear_all_transients() {
    $options = wc_vimeo_get_product_options();
    $duration = wc_vimeo_get_transient_duration() / 60;
    $cleared = false;
    if (false !== get_transient('wc_vimeo_videos_main_transient')) {
        delete_transient('wc_vimeo_videos_main_transient');
        $cleared = true;
    }
    foreach($options as $key => $value) {
        $transientName = 'wc_vimeo_video_'.$key;
        if (false !== get_transient($transientName)) {
            delete_transient($transientName);
            $cleared = true;
        }
    }
    return $cleared;
}


// Updates or Deletes post meta based on if the $value intended
// to be set isset() and is at least 1 character long
function wc_vimeo_update_delete_post_meta($post_id, $tag, $value) {
    if (isset($value) && strlen($value) > 0) {
		update_post_meta($post_id, $tag, wc_clean($value));
	} else {
		delete_post_meta($post_id, $tag);
	}
}

?>