=== Random Content ===
Contributors: endocreative
Donate link: http://endocreative.com
Tags: content, widget, random, shortcode, text, images
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Randomly display any content anywhere on your site.

== Description ==

This plugin allows you to display random content anywhere on your site using a shortcode or widget. You can group your random content together, allowing you to display different content in multiple locations throughout your site.

The content is added via a custom post type, so you have full access to the tinyMCE editor. This allows you to easily add images, text, and links to your random content. 

 
== Installation ==

1. Upload `random-content.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create an entry in the Random Content post type. 
4. Categorize your content using Groups. You can create as many Groups as you like.

= Using a widget =
1. Navigate to Appearance->Widgets and add the Random Content widget to a sidebar.
2. Select a group from the dropdown. If you don't create a group, the widget will use all entries.

= Using a shortcode =
Place the shortcode `[random]` anywhere on a post or page. To choose entries from a specific group, add the `group_id` parameter. For example, `[random group_id="64"]`. 

**Only content within the content editor will be displayed.**

== Frequently Asked Questions ==

= Can I group certain entries together? =

Yes. Create a group, and assign specific entries to the group. It works just like categories. 

= How do I find the group id? =

Click on Random Content->Group. Click on the group name and look for the tag_ID parameter in the address bar's URL.

== Screenshots ==

1. Adding the widget to a sidebar.

== Changelog ==

= 0.4 =
* Update input text syntax in widget

= 0.3 =
* Add shortcode functionality.
* Reset post data after widget query.

= 0.1 =
* First released into the wild.