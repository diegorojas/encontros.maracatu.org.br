<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_directory') ?>/css/estilo.css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Dosis:600' rel='stylesheet' type='text/css'>
 <!-- Google Fonts END -->
 <!-- FONT CUFON -->
<script type="text/javascript" src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/xilosa_400.font.js"></script>
<script type="text/javascript">// <![CDATA[
	Cufon.replace('.xilosa, h1.entry-title');
// ]]></script>
   <!-- Google Analytics -->
 <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33607439-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="cabecalho">
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'div' : 'div'; ?>
					<<?php echo $heading_tag; ?> id="logo-header">
						<span>
							<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</span>
					</<?php echo $heading_tag; ?>>
			
			<div id="cabecalho-direita">
			
			<div id="midias-sociais">
			</div>
			
			<div id="busca">
			<?php get_search_form(); ?>
			</div>
			
			<div id="access" role="navigation">
			 <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
			<div id="cabecalho-sup-dir">
			<div id="site-description" class="xilosa"><?php bloginfo( 'description' ); ?></div>
				<div id="infos-evento">
				<div id="data">
				2, 3 E 4<br />
				Novembro<br />
				2012
				</div>
				<div id="local">
				S&atilde;o Paulo<br />
				Serra Negra
				
				</div>
				</div>
			</div><!-- #cabecalho-sup-dir -->
			</div><!-- #cabecalho-direita -->
		</div>


		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main" class="home">
