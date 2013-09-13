<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">
			<div id="site-info">
				<p>Uma realiza&ccedil;&atilde;o dos brincantes do Maracatu de Baque Virado</p>
			</div><!-- #site-info -->
			<div id="footer-menu">
			<div id="site-generator">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyten' ) ); ?>" title="Criado com Wordpress" rel="generator">WordPress</a>
			</div><!-- #site-generator -->
			<?php wp_nav_menu( array( 'theme_location' => 'menu-footer') ); ?>
			</div>

		</div><!-- #colophon -->
		<div id="linha-footer"></div>
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
