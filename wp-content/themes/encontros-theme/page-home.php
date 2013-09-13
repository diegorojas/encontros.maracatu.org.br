<?php
/*
 * Template Name: Home
 */

get_header('home'); ?>
			<div id="content" class="home">				 <div id="ph-box">						<?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>				</div> 						</div>						<!-- <div id="content" class="one-column">				<?php //show_post('home');  // Shows the content of "Home" page. ?>						</div> --> <!-- #content -->
<?php get_sidebar('footer'); ?>	
<?php get_footer(); ?>