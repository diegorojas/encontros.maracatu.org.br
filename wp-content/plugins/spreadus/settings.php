<?php
/**
 * This is the file that manages the whole settings panel
 */

/**
 * Don't redeclare the class
 */
if(!class_exists('SpreadusSettings'))
{
	/**
	 * Spread.us settings class
	 */
	class SpreadusSettings
	{
		/**
		 * Enable or disable debugging
		 */
		public $debug = false;
		
		/**
		 * Default Spread.us url for API calls & other stuff
		 */
		public $spreadus_url = 'http://spread.us';
		
		/**
		 * API version number
		 */
		public $api_version = SPREADUS_API_VERSION;
		
		/**
		 * Plugin version number
		 */
		public $plugin_version = SPREADUS_PLUGIN_VERSION;
		
		/**
		 * Counter for API calls made this pageload
		 */
		public $api_calls = 0;
		
		/**
		 * Spread.us account name
		 */
		public $account_name;
		
		/**
		 * Spread.us secret
		 */
		public $secret;
		
		/**
		 * Status of Spread.us authentication
		 */
		public $authenticated;
		
		/**
		 * Initialize the Spread.us settings panel
		 */
		public function init()
		{
			// Spread.us testing overwrite
			if(
				strpos($_SERVER['HTTP_HOST'], 'spread.us') !== false ||
				strpos($_SERVER['HTTP_HOST'], 'dev-pablo.thenextweb.com') !== false
			)
			{
				$this->spreadus_url = 'http://guidobeta.spread.us';
			}
			
			// Check if the user wants to logout
			if(isset($_GET['action']) && $_GET['action'] == 'logout')
			{
				$this->logout();
				return;
			}
			
			// Render the header
			require_once('views/header.php');
			
			// Set account_name, secret & authentication state
			$this->account_name 	= get_option('spreadus.account_name');
			$this->secret 			= get_option('spreadus.secret');
			$this->authenticated 	= get_option('spreadus.authenticated');
			
			// Check if we can process a form
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$this->process_form();
			}
			
			// Setup finished?
			if(!$this->account_name || !$this->secret)
			{
				$this->setup();
				return;
			}
			
			// Correct account_name & secret?
			$response = $this->call_api('check_account', array(
				'account_name' => $this->account_name,
				'hash' => $this->hash($this->account_name)
			));
			if(isset($response->error) && $response->code != 'down')
			{
				$this->logout();
				return;
			}
			
			// Do we have a plan?
			$response = $this->call_api('check_plan', array(
				'account_name' => $this->account_name,
				'hash' => $this->hash($this->account_name)
			));
			if(isset($response->code) && $response->code == 'no_plan')
			{
				$this->plan();
				return;
			}
			else
			{
				$this->account_status = isset($response->success) ? $response->success : $response->error;
			}
			
			// All good, render the settings page
			$this->settings();
			return;
		}
		
		/**
		 * Uninstall current Spread.us settings
		 */
		public function logout()
		{
			update_option('spreadus.account_name', false);
			update_option('spreadus.secret', false);
			//update_option('spreadus.account', false);
			update_option('spreadus.authenticated', false);
			update_option('spreadus.settings', false);
			update_option('spreadus.categories', false);
			
			$this->set_notice('Your Spread.us settings have been removed.');
			$this->refresh(false);
		}
		
		/**
		 * Render setup form
		 */
		private function setup()
		{
			$domain_parts = explode('.',$_SERVER['SERVER_NAME']);
			$domain = $domain_parts[count($domain_parts)-2] . '.' . $domain_parts[count($domain_parts)-1];
			
			switch($_GET['action'])
			{
				/*case 'signup':
					$view = 'signup';
					break;*/
				case 'set_account':
					$callback = base64_decode($_GET['callback']);
					$callback = explode('|', $callback);
					
					$this->sanitize_post_data($callback);
					
					update_option('spreadus.account_name', $callback[0]);
					update_option('spreadus.secret', $callback[1]);
					$view = 'set_account';
					break;
				default:
					$view = 'login';
					break;
			}
			require_once('views/' . $view . '.php');
		}
		
		/**
		 * Render plan panel
		 */
		private function plan()
		{
			require_once('views/plans.php');
		}
		
		/**
		 * Render settings panel
		 */
		private function settings()
		{
			$settings = get_option('spreadus.settings');
			$widget_settings = get_option('spreadus.widget_settings');
			$categories = get_option('spreadus.categories');
			
			switch($_GET['action'])
			{
				case 'sync_categories':
					// Render sync categories panel
					require_once('views/sync_categories.php');
					break;
				
				default:
					// Render settings top part
					require_once('views/settings_top.php');
					
					/*
					// Get account settings
					$response = $this->call_api('get_account', array(
						'account_name' => $this->account_name,
						'hash' => $this->hash($this->account_name)
					));
					
					// Can we get account settings?
					if(!isset($response->error))
					{
						$account = get_object_vars($response->account);
						require_once('views/settings_main.php');
					}
					else
					{
						require_once('views/down.php');
					}
					*/
					
					// Render settings bottom part
					require_once('views/settings_bottom.php');
			}
		}
		
		/**
		 * Process incoming forms
		 */
		private function process_form()
		{
			switch($_POST['form'])
			{
				case 'setup':
					$this->process_setup();
					break;
				case 'sync_categories':
					$this->process_sync_categories();
					break;
				case 'settings':
					$this->process_settings();
					break;
				default:
					$this->catch_form();
					break;
			}
		}
		
		/**
		 * Process the setup form
		 */
		private function process_setup()
		{
			switch($_GET['action'])
			{
				/*case 'signup':
					$response = $this->call_api('create_account', array(
						'account_name' => $_POST['account_name']
					));
					
					$this->account_name = $response->account_name;
					$this->secret = $response->secret;
					break;*/
				default:
					$this->account_name = $_POST['account_name'];
					$this->secret = $_POST['secret'];
					
					$response = $this->call_api('check_account', array(
						'account_name' => $this->account_name,
						'hash' => $this->hash($this->account_name)
					));
					break;
			}
							
			if(!isset($response->error))
			{
				update_option('spreadus.account_name', $this->account_name);
				update_option('spreadus.secret', $this->secret);
				update_option('spreadus.authenticated', '1');
				$this->set_notice($response->success);
				$this->refresh(false);
			}
			else
			{
				$this->set_notice($response->error, 'error');
				$this->refresh();
			}
		}
		
		/**
		 * Process the sync_categories form
		 */
		private function process_sync_categories()
		{
			$category_settings = get_option('spreadus.categories');
			$category_settings['last_synced'] = date('d-m-Y H:i:s');
			$category_settings['last_synced_categories'] = $this->get_categories();
			
			$categories = array();
			foreach($category_settings['last_synced_categories'] as $key => $category)
			{
				if(!isset($category->children))
				{
					$categories[] = $category->name;
				}
				else
				{
					$children = array();
					foreach($category->children as $child)
					{
						$children[] = $child->name;
					}
					
					$categories[$category->name] = $children;
				}
			}
			
			$response = $this->call_api('sync_categories', array(
				'account_name' => $this->account_name,
				'hash' => $this->hash($this->account_name, $categories),
				'categories' => $categories
			));
			
			$category_settings['sync_status'] = isset($response->error) ? $response->error : $response->success;
			
			if(!isset($response->error))
			{
				update_option('spreadus.categories', $category_settings);
			}
			
			$this->refresh();
		}
		
		/**
		 * Process the settings form
		 */
		private function process_settings()
		{
			$allowed_spreading_settings = array(
				'message_caption',
				'default_publish_address',
				'spread_tags',
				'no_automated_spreading',
				'custom_url_field'
			);
			
			$allowed_widget_settings = array(
				'button_size',
				'button_color'
			);
			
			$new_settings = array();
			$new_widget_settings = array();
			foreach($_POST as $key => $value)
			{
				if(in_array($key, $allowed_spreading_settings))
				{
					//update_option('spreadus.spreading.' . $key, htmlentities($value));
					$new_settings[$key] = $value;
				}
				if(in_array($key, $allowed_widget_settings))
				{
					//update_option('spreadus.spreading.' . $key, htmlentities($value));
					$new_widget_settings[$key] = $value;
				}
			}
			
			if(count($new_settings) > 0)
			{
				$this->sanitize_post_data($new_settings);
				//$old_settings = get_option('spreadus.settings');
				//$settings = array_merge($old_settings, $new_settings);
				//update_option('spreadus.settings', $settings);
				update_option('spreadus.settings', $new_settings);
			}
			
			if(count($new_widget_settings) > 0)
			{
				$this->sanitize_post_data($new_widget_settings);
				update_option('spreadus.widget_settings', $new_widget_settings);
			}
			
			$this->refresh();
		}
		
		/**
		 * Catch unrecognized form submits
		 */
		private function catch_form()
		{
			$this->set_notice('I could not process that form action...', 'error');
			$this->refresh();
		}
		
		/**
		 * Sanitize post data, make sure all data is safe.
		 */
		private function sanitize_post_data(&$value)
		{
			if(is_array($value))
			{
				array_walk_recursive($value, array($this, 'sanitize_post_data'));
			}
			else
			{
				$value = htmlentities($value);
			}
		}
		
		/**
		 * Add a notice to the notices queue
		 */
		private function set_notice($message, $type = 'updated')
		{
			$notice = '
				<div class="notice ' . $type . '">
					<p>
						<a href="#" class="close" style="float:right;font-variant:small-caps;">
							&times;
						</a>
						' . $message . '
					</p>
				</div>
			';
			
			$notices = get_option('spreadus.notices');
			if(is_array($notices))
			{
				$notices[] = $notice;
			}
			else
			{
				$notices = array($notice);
			}
			update_option('spreadus.notices', $notices);
		}
		
		/**
		 * Call a spread.us api method
		 */
		private function call_api( $action, $fields = Array() )
		{
			// Up the API call counter
			$this->api_calls++;
			
			// Add versions to the call
			$fields['api_version'] = $this->api_version;
			$fields['plugin_version'] = $this->plugin_version;
			$fields['wordpress_version'] = get_bloginfo('version');
			
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $this->spreadus_url . '/actions/' . $action . '.json');
			curl_setopt($c, CURLOPT_TIMEOUT, 4);
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS,  array('data' => json_encode($fields)));
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($c);
			curl_close($c);
			
			if(!$response || ($return = json_decode($response)) != true)
			{
				$return = new stdClass();
				$return->error = 'The Spread.us API appears to be down.';
				$return->code = 'down';
				$return->body = $response;
			}
			
			if($this->debug)
			{
				echo '<p>API call no. ' . $this->api_calls . ':</p>';
				var_dump($return);
				
				if($return->code == 'down')
				{
					echo '<p>Return body of fatal API call:</p>';
					echo '<div>' .$return->body. '</div>';
				}
				
				if($fields['account'])
				{
					var_dump($fields['account']);
					//die();
				}
			}
			
			return $return;
		}
		
		/**
		 * Create a secure hash
		 */
		private function hash()
		{
			$fields = func_get_args();
			return sha1($this->secret . '_' . $this->implode_r('_', $fields));
		}
		
		/**
		 * Get current page url
		 */
		private function get_current_url()
		{
			$page_url = 'http';
			if ($_SERVER['HTTPS'] == 'on')
			{
				$page_url .= 's';
			}
			$page_url .= '://' . $_SERVER['SERVER_NAME'];
			
			if ($_SERVER['SERVER_PORT'] != '80')
			{
				$page_url .=  ':' . $_SERVER['SERVER_PORT'];
			}
			
			$page_url .= $_SERVER['REQUEST_URI'];
			
			return $page_url;
		}
		
		/**
		 * Refresh page, with the option to strip the current action
		 */
		private function refresh($action = true)
		{
			$new_action = ($action === true && isset($_GET['action']) && !empty($_GET['action'])) ? '&action=' . $_GET['action'] : '';
			echo '
				<script>
					window.location = "?page='.$_GET['page'] . $new_action.'";
				</script>
			';
			exit();
		}
		
		/**
		 * Recursively impode an array or object to a string
		 */
		private function implode_r($glue, $pieces)
		{
			// Not array or object?
			if(!is_array($pieces) && !is_object($pieces))
			{
				return (string)$pieces;
			}
			
			// Do we have at least one item?
			if(count((array)$pieces)) 
			{
				// Start new return buffer
				$return = '';
				
				// Loop through the array or object
				foreach($pieces as $sub)
				{
					// Add new data to the return buffer
					$return .= $this->implode_r($glue, $sub) . $glue;
				}
				
				// Trim last glue piece
				$return = substr($return, 0, strlen($return) -strlen($glue));
			}
			
			return $return;
		}
		
		/**
		 * Get category list
		 */
		private function get_categories()
		{
			$wp_categories = get_categories(array(
				'orderby' => 'count',
				'order' => 'DESC',
				'parent' => 0,
				'number' => 20,
				'pad_counts' => 1
			));
			
			$categories = array();
			foreach($wp_categories as $category)
			{
				if($category->slug == 'uncategorized')
				{
					continue;
				}
				
				$wp_children = get_categories(array(
					'orderby' => 'count',
					'order' => 'DESC',
					'child_of' => $category->cat_ID,
					'number' => 40
				));
				
				if(count($wp_children) > 0)
				{
					$category->children = array();
					foreach($wp_children as $child)
					{
						$category->children[] = $child;
					}
				}
				
				$categories[] = $category;
			}
			
			return $categories;
		}
		
		/**
		 * Create a human readable date string
		 * 
		 * @param int|string $timestamp Timestamp or strtotime() compatible date string to convert
		 * @return string A human readable date string.(ex: '5 seconds ago' or 'Tomorrow at 5:28pm')
		 */
		public function format_date($timestamp)
		{
			// Convert strings to timestamps
			if(!ctype_alpha($timestamp))
			{
				$timestamp = strtotime($timestamp);
			}
			
			// Get time difference and setup arrays
			$difference = time() - $timestamp;
			$periods = array('second', 	'minute', 	'hour', 	'day', 	'week', 	'month', 'years');
			$lengths = array('60', 		'60', 		'24', 		'7', 	'4.35', 	'12');
			
			// Past or present
			if ($difference >= 0)
			{
				$ending = 'ago';
			}
			else
			{
				$difference = -$difference;
				$ending = 'to go';
			}
			
			// Figure out difference by looping while less than array length
			// and difference is larger than lengths.
			$arr_len = count($lengths);
			for($j = 0; $j < $arr_len && $difference >= $lengths[$j]; $j++)
			{
				$difference /= $lengths[$j];
			}
			
			// Round up
			$difference = round($difference);
			
			// Make plural if needed
			if($difference != 1)
			{
				$periods[$j] .= 's';
			}
			
			// Default format
			$text = $difference . ' ' . $periods[$j] . ' ' . $ending;
			
			// over 24 hours
			if($j > 2)
			{
				// future date over a day formate with year
				if($ending == 'to go')
				{
					if($j == 3 && $difference == 1)
					{
						$text = 'Tomorrow at ' . date('g:i a', $timestamp);
					}
					else
					{
						$text = date('F j, Y \a\\t g:i a', $timestamp);
					}
					return $text;
				}
				
				if($j == 3 && $difference == 1) // Yesterday
				{
					$text = 'Yesterday at ' . date('g:i a', $timestamp);
				}
				else if($j == 3) // Less than a week display -- Monday at 5:28pm
				{
					$text = date('l \a\\t g:i a', $timestamp);
				}
				else if($j < 6 && !($j == 5 && $difference == 12)) // Less than a year display -- June 25 at 5:23am
				{
					$text = date('F j \a\\t g:i a', $timestamp);
				}
				else // if over a year or the same month one year ago -- June 30, 2010 at 5:34pm
				{
					$text = date('F j, Y \a\\t g:i a', $timestamp);
				}
			}
			
			return $text;
		}
	}
}

/**
 * Initialize settings class
 */
$spreadus_settings = new SpreadusSettings;