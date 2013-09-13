<div class="sync_categories_form">
	<h3><?php echo __('Spread.us categories', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
	    	<th>
	    		<?php echo __('Last synced:', 'spreadus'); ?>
	    	</th>
	    	<td>
				<?php echo __($categories['last_synced'] ? $this->format_date($categories['last_synced']) . '.' : 'Not yet synced.', 'spreadus'); ?>
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
	
	<?php if($settings['categories_last_synced']): ?>
	
	<?php endif; ?>
	
	<form method="POST">
		<input type="hidden" name="form" value="sync_categories" />
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php echo __('Sync now', 'spreadus'); ?>" />
			<?php echo __('or', 'spreadus'); ?> <a href="?page=<?php echo $_GET['page']; ?>"><?php echo __('go back', 'spreadus'); ?></a>
		</p>
	</form>
	
	<h3><?php echo __('Blog categories', 'spreadus'); ?></h3>
	<table class="form-table">
		<tr>
	    	<th>
	    		<?php echo __('Current categories:', 'spreadus'); ?>
	    	</th>
	    	<td>
	    		<ul class="category_list">
					<?php $wp_categories = $this->get_categories(); ?>
					<?php if(count($wp_categories) > 0): ?>
					<?php foreach($wp_categories as $category): ?>
					<?php if($category->slug == 'uncategorized') continue; ?>
					<li>
						<span class="category_name">
							<?php echo $category->name; ?>
						</span>
						<span class="post_count">
							(<?php echo $category->count; ?> posts)
						</span>
					</li>
					<?php if(count($category->children) > 0) foreach($category->children as $child): ?>
					<li>
						<span class="category_name">
							- <?php echo $child->name; ?>
						</span>
						<span class="post_count">
							(<?php echo $child->count; ?> posts)
						</span>
					</li>
					<?php endforeach; ?>
					<?php endforeach; ?>
					<?php else: ?>
					Nothing synced yet
					<?php endif; ?>
				</ul>
			</td>
		</tr>
		<tr>
	    	<th>
	    		<?php echo __('Last synced Categories:', 'spreadus'); ?>
	    	</th>
	    	<td>
	    		<ul class="category_list">
					<?php $old_categories = $categories['last_synced_categories']; ?>
					<?php if(is_array($old_categories) && count($old_categories) > 0): ?>
					<?php foreach($old_categories as $key => $category): ?>
					<li>
						<span class="category_name">
							<?php echo $category->name; ?>
						</span>
						<span class="post_count">
							(<?php echo $category->count; ?> posts)
						</span>
					</li>
					<?php if(count($category->children) > 0) foreach($category->children as $child): ?>
					<li>
						<span class="category_name">
							- <?php echo $child->name; ?>
						</span>
						<span class="post_count">
							(<?php echo $child->count; ?> posts)
						</span>
					</li>
					<?php endforeach; ?>
					<?php endforeach; ?>
					<?php else: ?>
					Waiting for first sync.
					<?php endif; ?>
				</ul>
			</td>
		</tr>
	</table>
</div>