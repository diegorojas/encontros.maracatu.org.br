<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="primary" class="widget-area" role="complementary">
			<ul class="xoxo">

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

		
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
		

<?php endif; ?>

				</ul>
		</div><!-- #primary .widget-area -->