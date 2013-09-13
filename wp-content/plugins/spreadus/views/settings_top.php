<h3><?php echo __('Account credentials', 'spreadus'); ?></h3>
<table class="form-table">
	<tr>
    	<th>
    		<?php echo __('Account status:', 'spreadus'); ?>
    	</th>
    	<td>
			<?php echo __($this->account_status, 'spreadus'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo __('Logged in as:', 'spreadus'); ?>
		</th>
		<td>
			<?php echo $this->account_name; ?>
			(<a href="?page=<?php echo $_GET['page']; ?>&action=logout"><?php echo __('log out', 'spreadus'); ?></a>)
		</td>
	</tr>
	<tr>
		<th>
			<?php echo __('Spread.us API key:', 'spreadus'); ?>
		</th>
		<td>
			<?php echo get_option('spreadus.secret'); ?>
		</td>
	</tr>
</table>

<h3><?php echo __('Spread.us categories', 'spreadus'); ?></h3>
<table class="form-table">
	<tr>
    	<th>
    		<?php echo __('Last synced:', 'spreadus'); ?>
    	</th>
    	<td>
			<?php echo __($categories['last_synced'] ? $this->format_date($categories['last_synced']) . '.' : 'Not yet synced.', 'spreadus'); ?>
			(<a href="?page=<?php echo $_GET['page']; ?>&action=sync_categories"><?php echo __('sync now', 'spreadus'); ?></a>)
		</td>
	</tr>
	<tr>
    	<th>
    		<?php echo __('Sync status:', 'spreadus'); ?>
    	</th>
    	<td>
			<?php echo __($categories['sync_status'] ? $categories['sync_status'] : 'Waiting for first sync.', 'spreadus'); ?>
		</td>
	</tr>
</table>

<form method="post">
	<input type="hidden" name="form" value="settings" />
	
	<h3><?php echo __('Spreading behavior', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="message_caption">
					<?php echo __('Message caption:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="message_caption" id="message_caption" class="regular-text"
					value="<?php echo stripslashes($settings['message_caption']); ?>"
					title="<?php echo __('Default caption for your messages on Facebook.', 'spreadus'); ?>" />
			</td>
		</tr>
		<?php /*
		<tr>
			<th>
				<label for="default_publish_address">
					<?php echo __('Default publisher email address:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="default_publish_address" id="default_publish_address" class="regular-text"
					value="<?php echo stripslashes($settings['default_publish_address']); ?>"
					title="<?php echo __('Default email address that is used to publish when author did not set his own.', 'spreadus'); ?>" />
			</td>
		</tr>
		*/ ?>
		<tr>
			<th>
				<label for="custom_url_field">
					<?php echo __('Custom url field:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="custom_url_field" id="custom_url_field" class="regular-text"
					value="<?php echo stripslashes($settings['custom_url_field']); ?>"
					title="<?php echo __('The name of the custom field that contains the right URL to the post.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
        	<th>
        		<?php echo __('Spread tags as categories:', 'spreadus'); ?>
        	</th>
        	<td>
        		<input type="hidden" name="spread_tags" value="0" />
            	<input type="checkbox" name="spread_tags" id="spread_tags"
            		value="1" <?php checked($settings['spread_tags'], '1'); ?> />
				<label for="spread_tags">
					<?php echo __('Add used tags to the categories list that is sent to Spread.us', 'spreadus'); ?>
				</label>
			</td>
		</tr>
		<tr>
        	<th>
        		<?php echo __('No automated spreading:', 'spreadus'); ?>
        	</th>
        	<td>
        		<input type="hidden" name="no_automated_spreading" value="0" />
            	<input type="checkbox" name="no_automated_spreading" id="no_automated_spreading"
            		value="1" <?php checked($settings['no_automated_spreading'], '1'); ?> />
				<label for="no_automated_spreading">
					<?php echo __('Do not automatically spread on publishing.', 'spreadus'); ?>
				</label>
			</td>
		</tr>
	</table>
	
	<h3><?php echo __('Button widget', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="button_size">
					<?php echo __('Button size:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<select name="button_size" id="button_size"
					title="<?php echo __('The size of the button in your sidebar.', 'spreadus'); ?>">
					<option value="small" <?php selected($widget_settings['button_size'], 'small'); ?>>Small</option>
					<option value="big" <?php selected($widget_settings['button_size'], 'big'); ?>>Large</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				<label for="button_color">
					<?php echo __('Button color:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<select name="button_color" id="button_color"
					title="<?php echo __('The color of the button in your sidebar.', 'spreadus'); ?>">
					<option value="light" <?php selected($widget_settings['button_color'], 'light'); ?>>Light</option>
					<option value="dark" <?php selected($widget_settings['button_color'], 'dark'); ?>>Dark</option>
				</select>
			</td>
		</tr>
	</table>