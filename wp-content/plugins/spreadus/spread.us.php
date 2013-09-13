<?php
/**
 * Plugin Name: Spread.us
 * Plugin URI: http://spread.us/
 * Description: Push your posts to Spread.us
 * Version: 1.1.2
 * Author: TNW Labs
 * Author URI: http://spread.us/
 */
 
/**
 * Define plugin version number
 */
define('SPREADUS_PLUGIN_VERSION', '1.1.2');
 
/**
 * Define API version number
 */
define('SPREADUS_API_VERSION', '1');
 
/**
 * Define URL parameter for the Spread.us config page
 */
define('SPREADUS_CONFIG_URL', 'spreadus');

/**
 * Include spreadus functions
 */
require_once('spreadus_functions.php');

/**
 * Add spreadus_init to admin_init
 */
add_action('admin_init', 'spreadus_init');

/**
 * Add spreadus settings page to admin_menu
 */
add_action('admin_menu', 'spreadus_admin_menu_link');

/**
 * Add extra link to the Spread.us plugin in the plugin list
 */
add_filter('plugin_action_links', 'spreadus_plugins_overview_link', 10, 2);

/**
 * Add hook to publish action of posts
 */
add_action('publish_post', 'spreadus_post', 9);

/**
 * Add Spread.us widget
 */
wp_register_sidebar_widget('spreadus_button_widget', 'Spread.us button', 'spreadus_button_widget');