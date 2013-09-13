<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header('home'); ?>

			<div id="content" class="home">

				 <div id="ph-box">
						<?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>
				</div> 
			
			</div>
			
			<!-- <div id="content" class="one-column">
				<?php // show_post('home');  // Shows the content of "Home" page. ?>
			
			</div> --> <!-- #content -->

<?php get_sidebar('footer'); ?>	
<?php get_footer(); ?>
