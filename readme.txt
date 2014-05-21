=== Gravity Forms / WooCommerce Recently Viewed Products ===
Contributors: Mike_Cowobo
Donate link: http://trenvo.com/
Tags: woocommerce, click stream, recent products, gravity forms, forms
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds recently viewed products from WooCommerce to all Gravity Form submissions

== Description ==

This plugin automatically adds Recently Viewed Products from WooCommerce to your form submissions, giving you insight into what products your visitors were interested in before sending their enquiry.

Simply install the plugin and activate it. If both WooCommerce and Gravity Forms are set up on your website, you will start seeing the recently viewed products of your visitors in your form entry screen. To also add the data to your emails, use the `{recently_viewed_products}` merge tag.

**Please note:** This plugin does *not* add any extra tracking to your website. If you have WooCommerce installed, it will automatically track recently viewed products for each visitor in a cookie which this plugin uses to add to each form submission. When you use this plugin, make sure your privacy policy is clear on how you are using your visitors' click stream information.

=== For developers ===

This plugin is very extensible and makes it easy to implement different ecommerce backends or alternative tracking mechanisms altogether. To modify the tracking, either make a child class of `GF_Recently_Viewed_Products` and override the `_get_current_recently_viewed_products` method, or use the following filters:

**For use with WooCommerce:**

*   `wc_gf_recently_viewed_product_ids`    (array) List of post ids
*   `wc_gf_recently_viewed_products_query` (array) Query for get_posts (use to change the post type)
*   `wc_gf_recently_viewed_products`       (array) List of posts

**Without WooCommerce: (make sure to init the `GF_Recently_Viewed_Products` class yourself)**

*   `gform_recently_viewed_product_ids`    (array) List of post ids
*   `gform_recently_viewed_products_query` (array) Query for get_posts (use to change the post type)
*   `gform_recently_viewed_products`       (array) List of posts

If you extend this plugin to

== Installation ==

Install the plugin the normal way and activate it. If both WooCommerce and Gravity Forms are set up on your website, you will start seeing the recently viewed products of your visitors in your form entry screen. To also add the data to your emails, use the `{recently_viewed_products}` merge tag.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. Recently viewed products in Gravity Forms entry
2. Gravity Forms email with Recently Viewed Products using merge tag

== Changelog ==

= 1.1 =
* Fix bug in merge tag when no data is available

= 1.0 =
* Release to WP.org

= 0.9 =
* Pre-release on GitHub

== Upgrade Notice ==

