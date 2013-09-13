	<h3><?php echo __('Basic information', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="display_name">
					<?php echo __('Display name:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="display_name" id="display_name" style="width: 25em" class="regular-text"
					value="<?php echo stripslashes($account['display_name']); ?>"
					title="<?php echo __('The name of your service.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="description">
					<?php echo __('Description:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<textarea name="description" id="description" style="font-size: 12px; width: 25em; height: 10em"
					title="<?php echo __('This description is visible on the overview page 
					of your account over at http://spread.us/settings.', 'spreadus'); ?>"
				><?php echo stripslashes($account['description']); ?></textarea>
			</td>
		</tr>
		<tr>
			<th>
				<label for="url">
					<?php echo __('Website url:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="url" id="url" class="regular-text"
					value="<?php echo stripslashes($account['url']); ?>"
					title="<?php echo __('The url of your website. (http://example.com/)', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="support_email">
					<?php echo __('Support e-mail:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="support_email" id="support_email" class="regular-text"
					value="<?php echo stripslashes($account['support_email']); ?>"
					title="<?php echo __('The e-mail address you will request our support with.', 'spreadus'); ?>" />
			</td>
		</tr>
	</table>
	
	<h3><?php echo __('URL shortener service', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="shortener">
					<?php echo __('URL shortener service:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<?php
					$shortener = $account['shortener'];
				?>
				<select name="shortener" id="shortener" class="regular-text"
					title="<?php echo __('Your URL shortener service.', 'spreadus'); ?>" />
					<option value="awesm" <?php echo $shortener == 'awesm' ? 'selected' : ''; ?>>
						Awe.sm
					</option>
					<option value="bitly" <?php echo $shortener == 'bitly' ? 'selected' : ''; ?>>
						Bit.ly
					</option>
				</select>
			</td>
		</tr>
		<tr class="shortener awesm <?php echo $shortener == 'awesm' ? 'active' : 'hidden'; ?>">
			<th>
				<label for="awesm_key">
					<?php echo __('Awe.sm api key:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="awesm_key" id="awesm_key" class="regular-text"
					value="<?php echo stripslashes($account['awesm_key']); ?>"
					title="<?php echo __('Your Awe.sm API key.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr class="shortener bitly <?php echo $shortener == 'bitly' ? 'active' : 'hidden'; ?>">
			<th>
				<label for="bitly_username">
					<?php echo __('Bit.ly username:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="bitly_username" id="bitly_username" class="regular-text"
					value="<?php echo stripslashes($account['bitly_username']); ?>"
					title="<?php echo __('Your Bit.ly username.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr class="shortener bitly <?php echo $shortener == 'bitly' ? 'active' : 'hidden'; ?>">
			<th>
				<label for="bitly_key">
					<?php echo __('Bit.ly api key:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="bitly_key" id="bitly_key" class="regular-text"
					value="<?php echo stripslashes($account['bitly_key']); ?>"
					title="<?php echo __('Your Bit.ly API key.', 'spreadus'); ?>" />
			</td>
		</tr>
	</table>
	
	<h3><?php echo __('Application settings', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="twitter_consumer_key">
					<?php echo __('Twitter consumer key:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="twitter_consumer_key" id="twitter_consumer_key" class="regular-text"
					value="<?php echo stripslashes($account['twitter_consumer_key']); ?>"
					title="<?php echo __('Your Twitter application consumer key.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="twitter_consumer_secret">
					<?php echo __('Twitter consumer secret:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="twitter_consumer_secret" id="twitter_consumer_secret" class="regular-text"
					value="<?php echo stripslashes($account['twitter_consumer_secret']); ?>"
					title="<?php echo __('Your Twitter application consumer secret.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="facebook_app_id">
					<?php echo __('Facebook application ID:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="facebook_app_id" id="facebook_app_id" class="regular-text"
					value="<?php echo stripslashes($account['facebook_app_id']); ?>"
					title="<?php echo __('Your Facebook application ID.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="facebook_consumer_secret">
					<?php echo __('Facebok consumer secret:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="facebook_consumer_secret" id="facebook_consumer_secret" class="regular-text"
					value="<?php echo stripslashes($account['facebook_consumer_secret']); ?>"
					title="<?php echo __('Your Facebook application consumer secret.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Not sure about these values?
			</th>
			<td>
				<small>
					Click <a href="<?php echo $this->spreadus_url; ?>/pages/applications" onclick="return popitup(this.href, 500);">here</a> for a guide on how to create your applications.
				</small>
			</td>
		</tr>
	</table>
	
	<h3><?php echo __('Outgoing mail server', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="smtp_host">
					<?php echo __('SMTP host & port:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="smtp_host" id="smtp_host" style="width: 239px"
					value="<?php echo stripslashes($account['smtp_host']); ?>"
					title="<?php echo __('The host of your outgoing mail server. (ex: smtp.example.com)', 'spreadus'); ?>" />
				:
				<input type="text" name="smtp_port" id="smtp_port" class="small-text"
					value="<?php echo stripslashes($account['smtp_port']); ?>"
					title="<?php echo __('The port of your outgoing mail server. (ex: 25)', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="smtp_username">
					<?php echo __('SMTP username:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="smtp_username" id="smtp_username" class="regular-text"
					value="<?php echo stripslashes($account['smtp_username']); ?>"
					title="<?php echo __('The username for your mail server.', 'spreadus'); ?>" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="smtp_password">
					<?php echo __('SMTP password:', 'spreadus'); ?>
				</label>
			</th>
			<td>
				<input type="text" name="smtp_password" id="smtp_password" class="regular-text"
					value="<?php echo stripslashes($account['smtp_password']); ?>"
					title="<?php echo __('The password for your mail server.', 'spreadus'); ?>" />
			</td>
		</tr>
	</table>