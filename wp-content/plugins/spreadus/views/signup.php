<div class="signup_form">
	<p>
		<?php echo __('Just pick a name & click the button below to get started.', 'spreadus'); ?>
	</p>
	
	<form method="POST">
		<input type="hidden" name="form" value="setup" />
		<table class="form-table">
			<tr>
            	<th>
            		<label for="account_name">
            			<?php echo __('Desired account name:', 'spreadus'); ?>
            		</label>
            	</th>
            	<td>
					<input type="text" name="account_name" value="<?php echo $domain; ?>" class="regular-text"
						title="<?php echo __('Allowed characters:', 'spreadus'); ?> a-z, 0-9, '-', '_' <?php echo __('and', 'spreadus'); ?> '.'" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php echo __('Create my account', 'spreadus'); ?>" />
			<?php echo __('or', 'spreadus'); ?> <a href="?page=<?php echo $_GET['page']; ?>&action=login"><?php echo __('login', 'spreadus'); ?></a>
		</p>
	</form>
</div>