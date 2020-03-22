=== Video Sales for Woocommerce with Vimeo ===
Contributors: vincentastolfi
Donate link: https://paypal.com/astolfivincent
Tags: vimeo, woocommerce, video, sell
Requires at least: 4.6
Tested up to: 5.3.2
Stable tag: 0.0.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to connect your Vimeo account to your WooCommerce site, and sell access to your private, password protected videos at Vimeo to your customers.

== Description ==

This plugin & its author are in no way affiliated with Vimeo or WooCommerce; this is an open source project to link the two.

With Video Sales for Woocommerce with Vimeo, selling access to your Vimeo videos via WooCommerce has never been easier. Vimeo allows you to password protect your videos, and we can utilize this feature to allow us to sell premium access to those password protected videos!

You can easily connect your Vimeo account to your WooCommerce site using Vimeo's free API. Once you've configured the plugin, you will be able to select videos from your Vimeo library when creating your products. 

Once your products have been associated with their respective videos, you can edit your WooCommerce email notifications & thank you messages in your theme and include the meta data of your video in payment confirmations to your users (examples of meta data would be
the video's Name, Link, Password for access, etc).

== Installation ==

0. This plugin requires WooCommerce to work. Ensure you have WooCommerce installed.
1. Upload the plugin files to the `/wp-content/plugins/woocommerce-vimeo` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to WooCommerce->Settings->Vimeo screen to configure the plugin


== Frequently Asked Questions ==
= How do I get Vimeo API credentials for the plugin configuration page? =

* Go to [https://developer.vimeo.com/apps/new](https://developer.vimeo.com/apps/new) (Log in or create in account if you don't have one already)
* Select a name and description for your app; these are arbitrary, but you can set something like "Vimeo Woocommerce" if you'd like
* For the "Will people besides you be able to access your app?" option, select "No"
* If you agree to the terms of service, check the box and click "Create App"
* You will be taken to your Vimeo App's config page
* The Client Identifier and Client Secret are already generated, but we need to generate an Access Token
* Scroll to the "Authentication" section
* Under "Generate an access token" select the radio button that says "Authenticted (You)"
* Some checkboxes should appear to select "Scopes". Check only the "Public" & "Private" Scopes
* Click the gray "Generate" button just below the checkboxes
* Immediately copy that token you generated into your configuration options; it will not be accessible once you reload this page
* Also copy your Client Secret and Client Identifier into the Video Sales for Woocommerce with Vimeo plugin options at [https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo](https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo) (see screenshot 1)
* Save -- you're good to go!

= What meta data is available on Products that have Vimeo Videos selected? =

Products with Vimeo videos selected have a lot of extra meta data associated with the Vimeo video stored on them. You can access this data in your email templates, as it is stored as custom fields on the Product. The names of the custom fields are:

* `wc_vimeo_id`: The ID of the Vimeo video; the ID in the Vimeo URL https://vimeo.com/**18293829**
* `wc_vimeo_password`: The password needed to access the video (will only be set if video is password protected at Vimeo)
* `wc_vimeo_link`: The link to the Vimeo video (this is a full link [https://vimeo.com/18293829](https://vimeo.com/18293829))
* `wc_vimeo_name`: The name, or title of the Vimeo video
* `wc_vimeo_uri`: The uri of the Vimeo video (this is the link without the leading domain, e.g. /18293829)
* `wc_vimeo_description`: The description of the video, if set
* `wc_vimeo_duration`: The duration of the video, in seconds
* `wc_vimeo_status`: The status of the video, if it is available to be viewed

= How can I use this meta data / make it available to my customers once they've purchased my Products? =

This plugin works by setting meta data from the Vimeo video on the Product when you select a video. There are many ways to deliver this meta data to your users on purchase of a product.

One way is to create an email template in your theme. See the sample gist here: [https://gist.github.com/astolfivincent/42cc9359d6b616675d70659e6de6f7a4](https://gist.github.com/astolfivincent/42cc9359d6b616675d70659e6de6f7a4)

Copy the content of the gist. In your theme (or child theme's) folder create a file at `/woocommerce/emails/customer-completed-order.php` and copy the contents of the gist into that file. So the full directory would be `/wp-content/themes/{your theme or child theme}/woocommerce/emails/customer-completed-order.php`

This gist gives some examples of how to access the Video data in your confirmation email, but you could do something similar in the processing email or other confirmation emails. You can edit this file and reformat it to suit your styling tastes. 

For more information about custom WooCommerce emails,  see the section called "Creating Custom Templates with Code" at [https://woocommerce.com/posts/how-to-customize-emails-in-woocommerce/](https://woocommerce.com/posts/how-to-customize-emails-in-woocommerce/)

= I just uploaded a video to Vimeo, but it's not showing up in the list of Vimeo videos when I create a product =

This is likely due to caching. Video Sales for Woocommerce with Vimeo uses WordPress transients, a way of caching data that is resource intensive to generate or access.

This greatly increases performance, as your site doesn't need to make a direct request to Vimeo every time it needs to check video data, and can instead use data from a recent request that already completed. This cache is set to clear every 15 minutes by default.

Note that depending on how many videos you have at Vimeo, making requests to get video data can be resource intensive. If you have many videos, you might consider increasing the cache clearing interval.

You can change the cache clear interval or clear the cache manually at [https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo](https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo) (see screenshot 2)

== Screenshots ==

1. Configurations page at https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo & config for Vimeo API
2. Cache clearing option at https://your-site.com/wp-admin/admin.php?page=wc-settings&tab=vimeo
3. Upload a video to Vimeo
4. Set Privacy Options for video & password protect it
5. Set Password for your video
6. Edit video settings after saving
7. Manage Privacy settings/password of a video after it's been uploaded
8. Select Vimeo video when adding/editing a WooCommerce Product

== Changelog ==

= 0.0.1 =
* Original release

= 0.0.2 =
* vimeo/vimeo-api update

== Upgrade Notice ==

= 0.0.1 =
Plugin inception, original release

= 0.0.2 =
Vimeo made a major update to their PHP API, so we need to update or communication with the Vimeo API will be broken.