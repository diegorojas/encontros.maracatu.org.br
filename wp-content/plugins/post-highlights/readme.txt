=== post highlights ===
Contributors: LeoGermani, PedroGermani
Donate link: http://post-highlights.hacklab.com.br
Plugin URI: http://post-highlights.hacklab.com.br
Tags: post, highlight, home
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 2.3.4

Add a nice looking animated highlights box to you theme, and lets you highlight your posts

== Description ==

Add a nice looking animated highlights box to you theme, and lets you highlight your posts

Features:

* Beautiful Jquery Box with fade between each picture
* Localization ready
* Easy to build your own theme
* Permission manager lets you choose who can highlight posts in your site

Refer to the <a href="http://post-highlights.hacklab.com.br">plugin website</a> for a live demo and documentation on how to build your theme

IMPORTANT: Since version 2.3.1 this plugin requires WordPress 3.0 or higher. For older versions of WordPress, please use version 3.1.

Localizations:

English <BR>
Brazilian Portuguese <BR>
Belorussian - by <a href="http://www.fatcow.com">FatCow</a> <BR>
Dutch - by <a href="http://www.bodrumturkeytravel.com">Rene</a> <BR>
Romanian - by <a href="http://webhostinggeeks.com/">Web Geek Science</a>

== Installation ==

. Download the package
. Extract it to the "plugins" folder of your wordpress
. In the Admin Panes go to "Plugins" and activate it

IMPORTANT: If you are upgrading from a previous version, deactivate and reactivate the plugin

== Usage ==

There are two ways to inser Post Highlights to your theme:

1. Using a Widget

Simply drag and drop the post highlights widget to a sidebar (note that only one post highlights widget instance per sidebar is allowed).

If you are using the widget, go to Post Highlights settings and check the option saying you want post highlights to automatically generate thumbnails for you

2. Adding to your theme code

Place the following code where you want the highlights to appear on your theme:

<?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>

To highlight a post go to Manage > Posts and check the checkbox under the Highlight column

Go to Post Highlights > Settings to change some options, such as delay time, button color and size.

== Changelog ==

2.3.4 - Abr 16 2012
* Add Romanian translations - Thanks to Alexander Ovsov (Web Geek Science)

2.3.3 - Feb 24 2012
* Fixes problem with left arrow
* Fixes problem with sticky posts that would allways appear

2.3.2 - Nov 17 2011
* Fixes problem with Internet Explorer

2.3.1 - Sep 20 2011
* Fixes problem with new jQuery version, Google Chrome and setTimeout() when the window was ou of focus causing the repeated animations

2.3 - Sep 06 2011
* Fixes possible vulnerability
* Small fix on numbered navigation
* Adds widget

2.2 - Jul 08 2010
* Add option to choose in which order the posts should be loaded

2.1.1 - May 18 2010
* Fix theme Default 2 CSS bug for chrome in Windows (Thanks to Lucas Daniel)

2.1 - Feb 11 2010
* Possibility to highlight pages - Thanks to Pablo Faria
* Post Highlights can create and use its own thumbnail

2.0.1
* Layout fix for default theme on i.E
* Fix on JS prevents from JS error when no posts are highlighted

2.0 New version
