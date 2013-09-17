<?php
/*
 * Template Name: Home
 */

get_header('home'); ?>
				<div id="content" class="home">

				 <div id="ph-box">
						<?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>
				</div> 
			
			</div>
<?php get_sidebar('footer'); ?>	
<?php get_footer(); ?>