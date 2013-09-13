<?php
/**
 * Spread.us functions file
 */


/**
 * Register WordPress admin hooks
 */
function spreadus_init()
{
	add_action('profile_update', 'spreadus_profile_update');
	add_action('show_user_profile', 'spreadus_user_profile');
	add_action('edit_user_profile', 'spreadus_user_profile');
	add_action('admin_notices', 'spreadus_admin_notices');
}


/**
 * Save extended profile attributes
 *
 * @param int $userid ID of user
 */
function spreadus_profile_update($userid)
{
	if(isset($_POST['spreader_email']) && $_POST['spreader_email'] == htmlspecialchars($_POST['spreader_email']))
	{
		update_usermeta($userid, 'spreader_email', $_POST['spreader_email']);
	}
	else
	{
		update_usermeta($userid, 'spreader_email','');
	}
	
	if(isset($_POST['twitter_username']) && $_POST['twitter_username'] == htmlspecialchars($_POST['twitter_username']))
	{
		update_usermeta($userid, 'twitter_username', $_POST['twitter_username']);
	}
	else
	{
		update_usermeta($userid, 'twitter_username','');
	}
}


/**
 * Show extended profile attributes
 */
function spreadus_user_profile()
{
	global $profileuser;
	echo '
		<h3>'.__('Spread.us settings', 'spreadus').'</h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="twitter_username">'.__('Twitter username:', 'spreadus').'</label>
				</th>
				<td>
					<input type="text" name="twitter_username" id="twitter_username" value="' . $profileuser->twitter_username . '" class="regular-text">
					<span class="description">'.__('Without the \'@\' sign.', 'spreadus').'</span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="spreader_email">'.__('Spreader email:', 'spreadus').'</label>
				</th>
				<td>
					<input type="text" name="spreader_email" id="spreader_email" value="' . $profileuser->spreader_email . '" class="regular-text">
					<span class="description">'.__('Your Spread.us spreader email address.', 'spreadus').'</span>
				</td>
			</tr>
		</table>
	';
}


/**
 * Render queued notices
 */
function spreadus_admin_notices()
{
	// Get notice queue
	$notices = get_site_option('spreadus.notices');
	
	// Was at least one notice set?
	if(is_array($notices))
	{
		// Loop trough notices
		foreach($notices as $notice)
		{
			// Echo notice
			echo $notice;
		}
	}
	
	// Clean notice queue
	update_site_option('spreadus.notices', false);
}


/**
 * Register Spread.us settings page & add it to the admin menu
 */
function spreadus_admin_menu_link()
{
	add_options_page('Spread.Us', 'Spread.us', 8, SPREADUS_CONFIG_URL, 'spreadus_configpage');
}


/**
 * Build settings page for Spread.us
 */
function spreadus_configpage()
{
	// Load settings class
	require_once('settings.php');
	
	// Initialize settings page
	$spreadus_settings->init();
}


/**
 * Add settings link to the plugin description row
 */
function spreadus_plugins_overview_link($links, $file)
{
	// Static so we don't call plugin_basename on every plugin row
	static $this_plugin;
	if(!$this_plugin)
	{
		// Set the plugin name
		$this_plugin = plugin_basename(__FILE__);
	}
	
	// Is this the right plugin?
	if(dirname($file) == dirname($this_plugin))
	{
		// Add link to settings panel
		$links[] = '<a href="options-general.php?page=' . SPREADUS_CONFIG_URL . '">' . __('Settings', 'spreadus') . '</a>';
	}
	
	// Return links
	return $links;
}


/**
 * Tweet about a new blog post on publish
 */
function spreadus_post($post_id = null)
{
	// Action called, write a log
	error_log('Spread action called for published post:' . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
	
	// Require simple_html_dom
	require_once('simple_html_dom.php');
	
	// Require debug
	require_once('debug.php');
	
	// Import Wordpress Core
	global $wpdb;
	global $blog_id;
	
	// Get the post
	$post = get_post($post_id);
	
	// Only spread once
	$spread_already = get_post_meta($post_id, 'spreadus_spread', true);
	if( !empty($spread_already) )
	{
		error_log(' - Post was spread already, exiting.' . PHP_EOL . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
		return;
	}
	error_log(' - Unspread post, check the date.' . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
	
	// Make sure the post is not older than 4 hours
	if(strtotime($post->post_date) < strtotime("-4 hours", current_time('timestamp')))
	{
		update_post_meta($post_id, 'spreadus_spread', '1');
		update_post_meta($post_id, 'spreadus_spread_date', 'too_old');
		error_log(' - Post ' . $post_id . ' too old, marked as already spread & exiting.' . PHP_EOL . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
		return;
	}
	
	error_log(' - New post, passed time checks, allowed to make an API call.' . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
	
	// Get Spread.us site options
	$spreadus = get_site_option('spreadus.settings');
	$spreadus['account_name'] = get_site_option('spreadus.account_name');
	$spreadus['secret'] = get_site_option('spreadus.secret');
	$spreadus['authenticated'] = get_site_option('spreadus.authenticated');
	
	// Get blog post title
	$title = $post->post_title;
	
	$body_html = str_get_html($post->post_content);
	$first_image = "";
	foreach($body_html->find('img') as $e){
		$first_image = $e->src;
		add_post_meta($post_id, 'spreadus_images', $first_image, true);
		break;
	}
	
	$plain_text = $body_html->plaintext;
	
	// Get permalink
	$post_url = get_permalink($post_id);
	
	// Check if we should overwrite the default URL
	if(isset($spreadus['custom_url_field']) && !empty($spreadus['custom_url_field']))
	{
		$new_url = get_post_meta($post_id, $spreadus['custom_url_field'], true);
		if(strlen($new_url) > 3)
		{
			$post_url = $new_url;
		}
	}
	
	// Get categories
	$post_categories = array();
	foreach((get_the_category($post_id)) as $category)
	{
	    if($category->cat_name != 'Uncategorized')
	    {
	    	$post_categories[] = $category->cat_name;
	    }
	}
	error_log(' - Added categories: ' . implode(', ', $post_categories) . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
	
	// Get tags when needed
	if($spreadus['spread_tags'])
	{
		$tags = get_the_tags($post_id);
		if($tags)
		{
			$post_tags = array();
			foreach($tags as $tag)
			{
				$post_tags[] = $tag->name;
			}
			$post_categories = array_merge($post_categories, $post_tags);
			error_log(' - Added tags: ' . implode(', ', $post_tags) . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
		}
		else
		{
			error_log(' - No tags to add.' . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
		}
	}
	
	// Get author
	$author = get_userdata($post->post_author);
	
	// Debugging
	//add_post_meta($post_id, 'spreadus_debug', json_encode($spreadus), true);
	
	// Only try to spread when authenticated
	if($spreadus['authenticated'])
	{
	
		// Use default or user spreadus email adddress for publish information
		$spreader_email = !empty($author->spreader_email) ? $author->spreader_email : isset($spreadus['default_publish_address']) ? $spreadus['default_publish_address'] : '';
		
		// Get caption
		$caption = is_null($spreadus['message_caption']) ? $spreadus['message_caption'] : '';
		
		// Get author twitter account credentials
		$author_twitter_username = isset($author->twitter_username) ? $author->twitter_username : '';
		
		// Get sub-blog twitter account credentials
		//$twitter_publisher_options = get_option('twitter_publisher_options');
		//$blog_twitter_username = isset($twitter_publisher_options['twipub_screen_name']) ? $twitter_publisher_options['twipub_screen_name'] : '';
		$blog_twitter_username = '';
		
		$author_name = isset($author->display_name) ? $author->display_name : isset($author->first_name) && isset($author->last_name) ? $author->first_name . ' ' . $author->last_name : '';

		$hash = sha1(trim($spreadus['account_name']) . '_' . trim($spreader_email) . '_' . trim($spreadus['secret']) . '_' . trim($title) . '_' . trim($author_twitter_username) . '_' . trim($blog_twitter_username));
		
		$request_args = array(
			'hash' => $hash,
			'account_name' => $spreadus['account_name'],
			'email' => $spreader_email,
			'title' => $title,
			'text' => $plain_text,
			'url' => $post_url,
			'img_url' => $first_image,
			'caption' => $caption,
			'categories' => implode(',', $post_categories),
			'author_twitter_username' => $author_twitter_username,
			'blog_twitter_username' => $blog_twitter_username,
			'author' => $author_name,
			'api_version' => SPREADUS_API_VERSION,
			'plugin_version' => SPREADUS_PLUGIN_VERSION,
			'wordpress_version' => get_bloginfo('version')
		);
		
		$data = json_encode($request_args);
		
		// Spread.us testing overwrite
		if(strpos($_SERVER['HTTP_HOST'], 'spread.us') !== false)
		{
			$spreadus_url = 'http://guidobeta.spread.us';
		}
		else
		{
			$spreadus_url = 'http://spread.us';
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $spreadus_url . '/actions/spread_post.json' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_POST, 1 ); 
		curl_setopt( $ch, CURLOPT_POSTFIELDS, array( 'data' => $data ) );		
		$response = curl_exec( $ch );
		$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		
		error_log(' - Data: ' . PHP_EOL . ' - ' . print_r($data, true) . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
		error_log(' - Response:' . PHP_EOL . ' - ' . print_r($response, true) . PHP_EOL . PHP_EOL, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );

		// Add a flag to this blog post, so we only Tweet once about it
		add_post_meta($post_id, 'spreadus_spread', date('Y-m-d H:i:s'), true);
	}
	
	// cleanly return
	return;
}


/**
 * Create widget
 */
function spreadus_button_widget($args)
{
	extract($args);
	$widget_settings = get_option('spreadus.widget_settings');
	
	echo $before_widget;
	echo $before_title . 'Spread.us widget' . $after_title;
	
	if(!$widget_settings)
	{
		echo 'Please configure your widget from the Spread.us settings page.';
	}
	else
	{
		?>
		<a href="http://spread.us/intro/thenextweb" class="spreadus_button spreadus_<?php echo $widget_settings['button_size']; ?> <?php echo $widget_settings['button_color']; ?>">Spread.us</a>
		<script>
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//spread.us/popup/init.js';fjs.parentNode.insertBefore(js,fjs);}else{window.spreadus.init();}}(document,'script','spreadus_script');
		</script>
		<?php
	}
	
	echo $after_widget;
}