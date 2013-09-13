=== Slideshow Satellite ===
Contributors: C- Pres
Donate link: http://c-pr.es/projects/satellite
Tags: slideshow pro, photographer, galleries, satellite, orbit, zurb orbit, slideshow gallery, slides, slideshow, image gallery, gallery, slideshow satellite, photography, slideshow orbit
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.3.3
License: GPLv2 or later

Ultra customizable and contemporary photo slideshow. Build photograph galleries or content displays with Slideshow Satellite. 

== Description ==
The goal of this slideshow is to create beautiful ways of displaying content in ways that suit the content you are providing.

Either use your current Wordpress Media Galleries or take advantage of the Satellite's own custom gallery manager.

To explore the manual or get the premium edition: http://c-pr.es/projects/satellite/

To embed into a post/page, simply insert <code>[satellite]</code> into its content with optional <code>post_id</code>, <code>thumbs</code>, <code>exclude</code>, <code>include</code>, <code>caption</code>, and <code>auto</code>  parameters.  Check out the Slideshow Satellite Manual on the plugin details page (linked above) for code examples.

== Installation ==
Installing the WordPress Slideshow Satellite plugin manually is very easy. Simply follow the steps below.

1. In your WP Dashboard goto Plugins and Add New. Then click Upload

1. Upload 'slideshow-satellite.zip', Install & Activate.

1. Configure the settings according to your needs through the 'Satellite' > 'Configuration' menu

1. Add and manage your slides in the 'Satellite' > 'Manage Slides' section (Or just use the built in wordpress gallery)

1. Put `[satellite post_id="X" exclude="" caption="on/off" thumbs="on/off"]` to embed a slideshow with the images of a post into your posts/pages or use `[satellite gallery=1]` to embed a slideshow with images in 'Manage Slides'

1. For the most up to date list of options available please goto: http://c-pr.es/projects/satellite and check out the manual

1. Premium Edition: You will download the premium edition from the website directly after paying and setting up your user account

1. Premium Edition: Just make sure the `/pro/` folder is in your /wp-content/plugins/slideshow-satellite/ directory and you're set!

== Frequently Asked Questions ==
= I am having some issues with my plugin, whats a good first look? =
Start with 'Reset to Defaults' on the top of your plugin configuration page.

= Still having some major issues, next? =
You may be dealing with a conflict with your theme or other plugins. To really test, if it works with Twenty-Eleven theme and no other plugins active it's a conflict.

= It's not that serious, just a little funky=
Oh, well have you checked out the Manual? http://bit.ly/stlmanual

= How can I display the slideshow in a sidebar as a widget? =
Install the plugin Advanced Text Widget and put the embed code in there. We would suggest using the Premium Edition as you can specify width and height in the embed.

= All the images show up on the page, this ain't no slideshow!!! Oh, and I'm running the slideshow through the template in PHP or through another plugin =
The slideshow isn't loading your JS or CSS most likely! That's because the plugin doesn't know it's being called. It's looking for the `[satellite` reference and not seeing it. `[satellite display=off]` in an area being called by the loop on that page or just going to Advanced Settings and turn 'Shortcode Requirement' to off.

= I'm seeing a non-stop loading icon on the page =
You're most likely dealing with some javascript weirdness. Check using Firebug and ask for help in the forums. Your theme or another plugin might be working improperly with Satellite

= Can I display/embed multiple instances of Slideshow Satellite? =
Yes you can, but you have to have the Premium Edition

= What if I only want captions on some of my pages
Set your default captions to off; for any slideshow you put on your page use `[satellite caption=on]` - Captions can also be set at the Gallery level

= How do I find the numbers to exclude (or include)? =
Not as easy as it used to be! Go into the Media Library. Choose an image you want to exclude and click on it and notice your address bar: "/wp-admin/media.php?action=edit&attachment_id=353". Therefore, `[satellite exclude=353]`

= What sizes can my thumbnails be? =
For Wordpress Image Gallery the max is 100, for the Custom Galleries the max is 150 pixels. The thumbnail must be at least 10 pixels.

= How do I show multiple galleries on a single page in a tabbed view? =
If you have the premium edition and multiple custom galleries setup do this `[satellite gallery=5,3,4,8]` and they will display in that order

= The slideshow loads a little funky, I fear it's the theme =
With premium edition, you can load the plugin after the theme loads by using the splash screen `[satellite gallery=2 splash=on]`

= 

Premium Questions

= My plugin breaks when I install the premium version =
Many times this has to do with how it was uploaded... Delete all the slideshow-satellite files from your plugins directory (this can be done by clicking 'Delete' from the Plugin Page menu) Then use the Plugin Page -> Add New -> Upload -> to upload the slideshow-satellite.zip file

You may also want to "Reset Configuration" from the Configuration page


== Screenshots ==
1. Slideshow Satellite with bottom thumbnails
2. Slideshow Satellite with "Full Right"

Two should be enough, we don't want to bog down your install with screenshots. Come to the site for more, and a video tutorial: http://c-pr.es/satellite

== Changelog ==
= 1.3.3 =
* Adding back in Shortcode Requirement
* Fixing Captions on the Right
* Adding Navigation Opacity
* Adding "Clean Start" for hover effects
* Adding always show play/pause button
* Kanji & Russian & Multilingual Support! UTF8 titles and descriptions

= 1.3.2 =
* Added More Gallery capability for call to action
* Navigation hidden to start
* Added more unique caption functionality to Galleries
* Added transparency capabilities
* Added an extra fade capability, "Fade Empty", and changed name of prior to "Fade Blend"
* Fixed captions to display through animation
* Added splash and load through AJAX for premium
* Added multiple gallery options when comma-delimited

= 1.2 =
* Added Galleries
* Added Bulk Image Upload
* Fixed Transparent Caption Backgrounds
* Added Text on the Right for Galleries
* Full Left & Full Right on General Config
* Slides no pause when image is clicked

= 1.1.4 = 
* Fixing for Wordpress 3.4
* Premium /pro/ directory copying feature for Automatic Updates
* Fixes for Full-Right Scrolling
* Fix all PHP errors

= 1.1 = 
* Added Caption Hover!
* Added Random capability
* Toggle Requirement of shortcode to load js and css
* Added multiple title sizing including 'Hidden'
* Minor bug fixes including Directory Separator
* Added Premium edition notifier of new versions

= 1.02 =
* Created easy out for tinyMCE bug
* Fixed some minor thumbnail issues
* Cleaned up Config Page
* Added more Caption options
* Update database to varchar textlocation
* "No link" fix
* Navigation Arrows push out
 
= 1.0 =
* Initial Plugin Release using a customized ZURB Orbit javascript slideshow
* Created easy one-click transition from Slideshow Gallery Pro
* Took best ideas in the world, created some more, and added some rad spice.

== Upgrade Notice ==

= 1.3.3 =
There may be some ordering of custom gallery slides you'll want to reconfirm. We had to rename the order column of the slides table for MySQL requirements.