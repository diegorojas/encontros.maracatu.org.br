=== Spread.us ===
Contributors: tnwlabs, guidobouman, dennisvandervliet
Tags: twitter, facebook, tweet, retweet, share, like, automatically, spread, spread.us, publish
Requires at least: 2.9.1
Tested up to: 3.3.1
Stable tag: 1.1.2

Automatically share posts through Spread.us.

== Description ==

This plugin allows you to automatically spread your posts on Twitter and/or Facebook via the Spread.us API.

== Installation ==

1. Upload in `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in Wordpress
3. Login (or create an account), and manage your settings from the 'Spread.us settings' option in the 'Settings' submenu.

== Changelog ==

= 1.1.1 =
* Adds the option to overwrite the default URL with a URL from a custom field
* Adds Dennis as a new contributor to the plugin

= 1.1.1 =
* Adds a configurable sidebar widget
* Fixes a bug where a php warning would be generated if the categories had never been synced
* Fixes a bug where the plugin would say it synced categories while an error occurred
* Improves some textual feedback
* Fixes wrongful use of a Wordpress function
* Replaces all php shorttags (<?) with the full equivalent (<?php)
* Moves the spreadus function to it's own file
* Adds better code documentation
* Adds general code cleanup

= 1.1.0 =
* Adds the new category syncing feature to the plugin
* Fixes a bug where people would have login problems

= 1.0.6 =
* Fixes a bug where a valid login wouldn't be recognized by the plugin

= 1.0.5 =
* Removes all settings upon logout, preventing colliding data for different accounts
* Changes "Account secret" to "Spread.us API key", which is also used in the Spread.us admin panel
* Fixes a bug where some API calls wouldn't send the right plugin version number
* Fixes a bug where the plugin could send an illegal API call to Spread.us
* Fixes a bug where stylesheets & scripts would not be available in the plugin
* The plugin author is now officially TNW Labs

= 1.0.4 =
* Adds the option to include tags as categories for Spread.us
* Switches to a less paranoid Wordpress hook [publish_post]
* Improves logging for the sake of better support from our side
* Adds version numbers of the plugin, api & wordpress installation to api calls

= 1.0.3 =
* Ensures compatibility with Wordpress 3.3.1
* Adds some small changes to the readme.txt

= 1.0.2 =
* Adds support for scheduled posts
* Fixes a datetime checking issue where the server's time was used instead of the blog's time
* Improves logging to debug.log
* Adds support for automatic domain switching when on the Spread.us server
* Removes old code which was used by the first tester

= 1.0.1 =
* Small bugfix
* Removed setup page, was unnecessary
* Removed support for beta plan
* Fixed a bug where an old PHP version would give a fatal error when creating a hash

= 1.0 =
* Initial release

== Frequently Asked Questions ==

= What does this plugin do? =
This plugin provides Wordpress users a smooth integration with the Spread.us service.

= Can I use this plugin without a Spread.us account? =
No, but you can easily create an account from the plugin.
