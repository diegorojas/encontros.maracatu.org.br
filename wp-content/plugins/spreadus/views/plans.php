<link rel="stylesheet" href="<?php echo plugins_url('/css/plans.css', dirname(__FILE__)); ?>" type="text/css" />
<div class="payment_form">
	<p>
		<?php echo __('You don\'t seem to have a payment plan yet. Please pick one.', 'spreadus'); ?>
	</p>
	
	<div class="plans">
		<a href="<?php echo $this->spreadus_url; ?>/plans/signup/<?php echo $this->account_name; ?>/basic"
			onclick="return popitup(this.href, 500);">
			<div class="plan">
				<h3>Basic</h3>
				<p class="price">
					<span class="currency_sign">
						$
					</span>
					<span class="amount">
						100
					</span>
					<span class="description">
						/ month
					</span> 
				</p>
				
				<ul>
					<li>
						Up to 100 spreaders.
					</li>
					<li>
						Free basic support.
					</li>
					<li>
						Plugin for Wordpress readily available, others are coming.
					</li>
				</ul>
				
				<div class="bottom">
					<p class="description target">
						For small websites with up to one million unique visitors per month.
					</p>
				</div>
			</div>
		</a>
		
		<a href="<?php echo $this->spreadus_url; ?>/plans/signup/<?php echo $this->account_name; ?>/advanced"
			onclick="return popitup(this.href, 500);">
			<div class="plan accent">
				<h3>Advanced</h3>
				<p class="price">
					<span class="currency_sign">
						$
					</span>
					<span class="amount">
						750
					</span>
					<span class="description">
						/ month
					</span> 
				</p>
				
				<ul>
					<li>
						Up to 1,000 spreaders.
					</li>
					<li>
						Free advanced support.
					</li>
					<li>
						Plugin for Wordpress readily available, others on demand.
					</li>
				</ul>
				
				<div class="bottom">
					<p class="description target">
						For medium sized websites with up to ten million unique visitors per month.
					</p>
				</div>
			</div>
		</a>
		
		<a href="<?php echo $this->spreadus_url; ?>/plans/signup/<?php echo $this->account_name; ?>/custom"
			onclick="return popitup(this.href, 500);">
			<div class="plan custom">
				<h3>Custom</h3>
				<p class="price">
					<span class="currency_sign">
						$
					</span>
					<span class="amount">
						XXXX
					</span>
				</p>
				
				<ul>
					<li>
						Up to 1,000,000 spreaders.<br>
						<span class="description">Or maybe even more?</span>
					</li>
					<li>
						Intensive support.
					</li>
					<li>
						Custom integration in your website.
					</li>
				</ul>
				
				<div class="bottom">
					<p class="description target">
						For big websites with ten million or more unique visitors per month.
					</p>
				</div>
			</div>
		</a>
	</div>
	
	<p class="description">
		Is this all a bit too quick? Click
		<a href="<?php echo $this->spreadus_url; ?>/pages/about" onclick="return popitup(this.href, 500);">
			here
		</a>
		to read more about the Spread.us service. Or <a href="?page=<?php echo $_GET['page']; ?>&action=logout"><?php echo __('log out', 'spreadus'); ?></a>.
	</p>
</div>