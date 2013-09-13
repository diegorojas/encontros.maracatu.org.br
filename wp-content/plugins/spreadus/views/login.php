<div class="login_form">
	<p>
		<?php echo __('Login with your account name & secret.', 'spreadus'); ?>
	</p>
	
	<form method="POST">
		<input type="hidden" name="form" value="setup" />
		<table class="form-table">
			<tr>
            	<th>
            		<label for="account_name">
            			<?php echo __('Account name:', 'spreadus'); ?>
            		</label>
            	</th>
            	<td>
					<input type="text" name="account_name" value="<?php echo $_POST['account_name']; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
            	<th>
            		<label for="secret">
            			<?php echo __('Spread.us API key:', 'spreadus'); ?>
            		</label>
            	</th>
            	<td>
					<input type="text" name="secret" value="<?php echo $_POST['secret']; ?>" class="regular-text" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php echo __('Login', 'spreadus'); ?>" />
			<?php echo __('or', 'spreadus'); ?> <a href="<?php echo $this->spreadus_url; ?>/accounts/signup" onclick="return popitup(this.href, 500);"><?php echo __('create an account', 'spreadus'); ?></a>
		</p>
	</form>
</div>