<?php 
/* Redefine the header image width and height ********************************************/
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 900 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 380 ) );

// post thumbnail support
	if ( function_exists( 'add_image_size' ) ) add_theme_support( 'post-thumbnails' );
	if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'thumb-grupos', 260, 100, true );
}

// Muda o limite do the_excerpt no child theme do TwentyTen
function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return nl2br($excerpt);
    }

	//Adiciona os arquivos de CustomPostType para Grupos
require_once (get_stylesheet_directory() . '/grupos/custom-grupos.php');
  

// Inserir Filtragem de Taxonomias

function get_terms_dropdown($taxonomies, $args){
    $myterms = get_terms($taxonomies, $args);
    $output ="";
    foreach($myterms as $term){
        $root_url = get_bloginfo('url');
        $term_taxonomy=$term->taxonomy;
        $term_slug=$term->slug;
        $term_name =$term->name;
        $link = $root_url.'/'.$term_taxonomy.'/'.$term_slug;
        $output .="<option value='".$link."'>".$term_name."</option>";
    }
    $output .="";
return $output;
}

 // Inserindo Menu para o Rodapé

	add_action( 'init', 'register_my_menus' );
		 
		function register_my_menus() {
		register_nav_menus(
		array(
		'menu-footer' => __( 'Menu do Rodape' )		
		)
		);
		}

// Remove a barra de atualização do Admin
 add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
 
 
 
// Personalizando o wp-login.php
/* function meu_logo_login()
{
    echo '<style  type="text/css"> h1 a {  background-image:url('.get_bloginfo('stylesheet_directory').'/imagens/logo_header_peq.png)  !important;  height: 170px !important;
    margin-top: -50px !important;  margin-left: 15px !important; } </style>';
}
add_action('login_head',  'meu_logo_login');
*/

// Para mudar o rodapé do wp-admin
function alt_admin_footer ()
{
    echo '<span id="footer-thankyou">Desenvolvido pela equipe do <a href="http://www.maracatu.org.br" target="_blank">Maracatu.org.br</a> com o maravilhoso <a href="http://br.wordpress.org" target="_blank">WordPress</a></span>';
}
add_filter('admin_footer_text', 'alt_admin_footer');

// Remove seções desnecessárias do Painel
function del_secoes_painel(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'del_secoes_painel');

  // Adiciona uma função para filtrar conteudo dos loops

function show_post($path) {
  $post = get_page_by_path($path);
  $content = apply_filters('the_content', $post->post_content);
  echo $content;
}
 
?>