<?php

/**
 * Adicionamos uma acção no inicio do carregamento do WordPress
 * através da função add_action( 'init' )
 */
add_action( 'init', 'type_post_grupos' );

	function type_post_grupos() {

    /**
     * Labels customizados para o tipo de post
     */
    $labels = array(
	    'name' => _x('Grupos Participantes', 'post type general name'),
	    'singular_name' => _x('Grupo', 'post type singular name'),
	    'add_new' => _x('Novo Grupo', 'projeto'),
	    'add_new_item' => __('Novo Grupo'),
	    'edit_item' => __('Editar Grupo'),
	    'new_item' => __('Novo Grupo'),
	    'all_items' => __('Todos Grupos'),
	    'view_item' => __('Ver Gtupo'),
	    'search_items' => __('Procurar Grupos'),
	    'not_found' =>  __('Nenhum Grupo encontrado'),
	    'not_found_in_trash' => __('Nenhum Grupo encontrado no lixo'),
	    'parent_item_colon' => '',
	    'menu_name' => 'Grupos'
    );
    
        $args = array(
			'labels' => $labels,
            'public' => true,
            'public_queryable' => true,
            'show_ui' => true, 
			'show_in_menu' => true,	
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => array('title','editor','thumbnail')
			);
			register_post_type( 'grupos' , $args );
			
					/**
		* Registamos a categoria de  para o tipo de post Grupos
		*/
			register_taxonomy( 'grupos_category', array( 'grupos' ), array(
				'hierarchical' => true,
				'label' => __( 'Grupos' ),
				'labels' => array( // Labels customizadas
				'name' => _x( 'Categorias de Grupo', 'taxonomy general name' ),
				'singular_name' => _x( 'Grupo', 'taxonomy singular name' ),
				'search_items' =>  __( 'Pesquisar Grupos' ),
				'all_items' => __( 'Todos Grupos' ),
				'parent_item' => __( 'Parent Category' ),
				'parent_item_colon' => __( 'Parent Category:' ),
				'edit_item' => __( 'Editar Grupo' ),
				'update_item' => __( 'Update Category' ),
				'add_new_item' => __( 'Adicionar Nova Categoria' ),
				'new_item_name' => __( 'Novo Nome da Categoria' ),
				'menu_name' => __( 'Categorias de Grupos' ),
			),
				'show_ui' => true,
				'show_in_tag_cloud' => true,
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'grupos_category',
					'with_front' => false,
				),
				)
			);
	
			flush_rewrite_rules();

}   add_action('save_post', 'save_agenda_post');
    
    function save_agenda_post(){
        global $post;        
            update_post_meta($post->ID, 'data_meta', $_POST['data_meta']);
    }

define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace('\\','/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));

add_action('admin_init','my_meta_init');

function my_meta_init()
{
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style

	wp_enqueue_style('my_meta_css', MY_THEME_PATH . '/css/meta.css');

	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/add_meta_box

	foreach (array('grupos') as $type) 
	{
		add_meta_box('my_all_meta', 'Informações do Grupo', 'my_meta_setup', $type, 'normal', 'high');
	}
	
	add_action('save_post','my_meta_save');
}

function my_meta_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta = get_post_meta($post->ID,'_my_meta',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MY_THEME_FOLDER . '/metaboxes_grupos.php');
	
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
 
function my_meta_save($post_id) 
{
	// authentication checks

	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;

	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	// authentication passed, save data

	// var types
	// single: _my_meta[var]
	// array: _my_meta[var][]
	// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]

	$current_data = get_post_meta($post_id, '_my_meta', TRUE);	
 
	$new_data = $_POST['_my_meta'];

	my_meta_clean($new_data);
	
	if ($current_data) 
	{
		if (is_null($new_data)) delete_post_meta($post_id,'_my_meta');
		else update_post_meta($post_id,'_my_meta',$new_data);
	}
	elseif (!is_null($new_data))
	{
		add_post_meta($post_id,'_my_meta',$new_data,TRUE);
	}

	return $post_id;
}

function my_meta_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}

		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}

  ?>
