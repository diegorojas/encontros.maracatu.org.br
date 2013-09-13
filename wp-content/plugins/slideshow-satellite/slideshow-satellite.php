<?php
/*
Plugin Name: Slideshow Satellite
Plugin URI: http://c-pr.es/projects/satellite
Author: C- Pres
Author URI: http://c-pr.es/membership-options
Description: Display photography and content in highly configurable ways with this slideshow. Pretty pretty pretty.
Version: 1.3.3
*/
define('DS', '/');
define( 'SATL_VERSION', '1.3.3');
$uploads = wp_upload_dir();
if ( ! defined( 'SATL_PLUGIN_BASENAME' ) )
	define( 'SATL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'SATL_PLUGIN_NAME' ) )
	define( 'SATL_PLUGIN_NAME', trim( dirname( SATL_PLUGIN_BASENAME ), '/' ) );
if ( ! defined( 'SATL_PLUGIN_DIR' ) )
	define( 'SATL_PLUGIN_DIR', WP_PLUGIN_DIR . DS . SATL_PLUGIN_NAME );
if ( ! defined( 'SATL_PLUGIN_URL' ) )
	define( 'SATL_PLUGIN_URL', WP_PLUGIN_URL . DS . SATL_PLUGIN_NAME );
if ( ! defined( 'SATL_UPLOAD_DIR' ) )
	define( 'SATL_UPLOAD_DIR', $uploads['basedir']. DS . SATL_PLUGIN_NAME );
if ( ! defined( 'SATL_UPLOAD_URL' ) )
	define( 'SATL_UPLOAD_URL', $uploads['baseurl']. DS . SATL_PLUGIN_NAME );
if ( ! defined( 'SATL_UPLOADPRO_DIR' ) )
	define( 'SATL_UPLOADPRO_DIR', SATL_UPLOAD_DIR . '/pro/' );
if ( ! defined( 'SATL_PLUGINPRO_DIR' ) )
	define( 'SATL_PLUGINPRO_DIR', SATL_PLUGIN_DIR . '/pro/' );
if ( ! file_exists( SATL_PLUGINPRO_DIR ) )
	define( 'SATL_PRO', false );
else
	define( 'SATL_PRO', true );
	
require_once SATL_PLUGIN_DIR . '/slideshow-satellite-plugin.php';
require_once SATL_PLUGIN_DIR . '/slideshow-satellite-premium.php';
	
class Satellite extends SatellitePlugin {
	function __construct() {
		$url = explode("&", $_SERVER['REQUEST_URI']);
		$this -> url = $url[0];
		
		$this -> register_plugin('slideshow-satellite', __FILE__);
		
		//WordPress action hooks
		$this -> add_action('admin_menu');
		$this -> add_action('admin_head');
		$this -> add_action('admin_notices');
		
		//WordPress filter hooks
              if ( $this -> get_option('satwiz') != "N") {
		$this -> add_filter('mce_buttons');
		$this -> add_filter('mce_external_plugins');
              }
		$this -> add_filter('plugin_action_links', 'add_satl_settings_link', 10, 2 );			
		
		add_shortcode('satellite', array($this, 'embed'));
		add_shortcode('gpslideshow', array($this, 'embed'));
                if ($this->get_option('embedss') == "Y") {
        		add_shortcode('slideshow', array($this, 'embed'));
                }
                if ( class_exists( 'SatellitePremium' ) ) {
                  $satlp = new SatellitePremium;
                  register_activation_hook( __FILE__, array( &$satlp, 'prem_activate_plugin' ));
                }
		
	}  

        function my_action_javascript() {
            ?>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                function showSatellite(id) {
                    var data = {
                            action: 'my_action',
                            slideshow: id
                    };
                    $.post(ajaxurl, data, function(response) {
                            alert('Got this from the server: ' + response);
                    });        
                }
            }
            </script>
            <?php 
        }        

	function admin_menu() {
		add_menu_page(__('Satellite', SATL_PLUGIN_NAME), __('Satellite', SATL_PLUGIN_NAME), $this -> get_option('manager'), "satellite", array($this, 'admin_settings'), SATL_PLUGIN_URL . '/images/icon.png');
		$this -> menus['satellite'] = add_submenu_page("satellite", __('Configuration', SATL_PLUGIN_NAME), __('Configuration', SATL_PLUGIN_NAME), $this -> get_option('manager'), "satellite", array($this, 'admin_settings'));
		$this -> menus['satellite-galleries'] = add_submenu_page("satellite", __('Manage Galleries', SATL_PLUGIN_NAME), __('Manage Galleries', SATL_PLUGIN_NAME), $this -> get_option('manager'), "satellite-galleries", array($this, 'admin_galleries'));		
		$this -> menus['satellite-slides'] = add_submenu_page("satellite", __('Manage Slides', SATL_PLUGIN_NAME), __('Manage Slides', SATL_PLUGIN_NAME), $this -> get_option('manager'), "satellite-slides", array($this, 'admin_slides'));		
		
		add_action('admin_head-' . $this -> menus['satellite'], array($this, 'admin_head_gallery_settings'));
	}
	
	function admin_head() {
		$this -> render('head', false, true, 'admin');
	}
	
	function admin_head_gallery_settings() {		
		add_meta_box('submitdiv', __('Save Settings', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_submit"), $this -> menus['satellite'], 'side', 'core');
		add_meta_box('generaldiv', __('General Settings', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_general"), $this -> menus['satellite'], 'normal', 'core');
		add_meta_box('linksimagesdiv', __('Links &amp; Images Overlay', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_linksimages"), $this -> menus['satellite'], 'normal', 'core');
		add_meta_box('stylesdiv', __('Appearance &amp; Styles', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_styles"), $this -> menus['satellite'], 'normal', 'core');
		add_meta_box('thumbsdiv', __('Thumbnail Settings', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_thumbs"), $this -> menus['satellite'], 'normal', 'core');
		add_meta_box('advanceddiv', __('Advanced Settings', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_advanced"), $this -> menus['satellite'], 'normal', 'core');
                if ( SATL_PRO ) {
                    add_meta_box('prodiv', __('Premium Edition Only', SATL_PLUGIN_NAME), array($this -> Metabox, "settings_pro"), $this -> menus['satellite'], 'normal', 'core');
                }
		
		do_action('do_meta_boxes', $this -> menus['satellite'], 'normal');
		do_action('do_meta_boxes', $this -> menus['satellite'], 'side');
		
	}
	
	function admin_notices() {
		$this -> check_uploaddir();
                $this -> check_sgprodir();
	
		if (!empty($_GET[$this -> pre . 'message'])) {		
			$msg_type = (!empty($_GET[$this -> pre . 'updated'])) ? 'msg' : 'err';
			call_user_method('render_' . $msg_type, $this, $_GET[$this -> pre . 'message']);
		}
	}
	
	function mce_buttons($buttons) {
		array_push($buttons, "separator", "gallery");
		return $buttons;
	}
	
	function mce_external_plugins($plugins) {
		$plugins['gallery'] = SATL_PLUGIN_URL . '/js/tinymce/editor_plugin.js';
		return $plugins;
	}
	
	function slideshow($output = true, $post_id = null, $exclude = null, $include = null, $custom = null, $gallery = null,$width = null, $height = null) {
                if (SATL_PRO) {
                    require SATL_PLUGIN_DIR . '/pro/newinit.php';
                }
		
		$this->resetTemp();
		$args = func_get_args();
		global $wpdb;
		$post_id_orig = $post -> ID;
		if ( ((! empty($width)) || (! empty($height))) && SATL_PRO ) {
			require SATL_PLUGIN_DIR . '/pro/custom_sizing.php';
		}
		
		if ( ! empty($post_id) && $post = get_post($post_id)) {
			if ($attachments = get_children("post_parent=" . $post -> ID . "&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC")) {
				$content = $this -> exclude_ids($attachments, $exclude, $include);
			}
		}
		elseif ( (! empty( $custom ) || (! empty( $gallery ) ) ) ) {
                    $gallery = ($custom) ? $custom : $gallery;
                    $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($gallery)), null, array('order', "ASC"));
                    $this->slidenum = count($slides);

                    if ( $this -> get_option('transition_temp') == "OM") {
                            $content = $this -> render('multislider', array('slides' => $slides, 'frompost' => false), false, 'pro');
                    } else {
                            $content = $this -> render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    }			
		}
		else {
			$slides = $this -> Slide -> find_all(null, null, array('slide_order', "ASC"));
                        $this->slidenum = count($slides);

			if ( $this -> get_option('transition_temp') == "OM") {
				$content = $this -> render('multislider', array('slides' => $slides, 'frompost' => false), false, 'pro');
			} else {
				$content = $this -> render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
			}
		}
		$post -> ID = $post_id_orig;
		if ($output) { echo $content; } else { return $content; }
	}
	function embed($atts = array(), $content = null) {
		//global variables
		global $wpdb;
                if (SATL_PRO) {
                    require SATL_PLUGIN_DIR . '/pro/newinit.php';
                }

		$defaults = array('post_id' => null, 'exclude' => null, 'include' => null, 'custom' => null, 'gallery' => null, 'caption' => null, 'auto' => null, 'w' => null, 'h' => null, 'nolink' => null, 'slug' => null, 'thumbs' => null, 'align' => null, 'nav' => null, 'transition' => null, 'display' => null, 'random' => null, 'splash' => null );
		extract( shortcode_atts( $defaults, $atts ) );
		
		$this->resetTemp();
		$align = stripslashes($align);
                
                if (!empty ($display)) {
                    if ($display == "off") {
                        return null;
                    }
                }
		if ( !empty( $caption ) ) { 
			if ( ($this -> get_option('information')=='Y') && ( $caption == 'off' ) ) {
				$this -> update_option('information_temp', 'N');
                                
			} elseif ( ($this -> get_option('information')=='N') && ( $caption == 'on' ) ) {
				$this -> update_option('information_temp', 'Y');
			}
			if ( ($this -> get_option('orbitinfo')=='Y') && ( $caption == 'off' ) ) {
				$this -> update_option('orbitinfo_temp', 'N');
                                
			} elseif ( ($this -> get_option('orbitinfo')=='N') && ( $caption == 'on' ) ) {
				$this -> update_option('orbitinfo_temp', 'Y');
			}
                    }
		if ( !empty( $thumbs ) ) { 
			if (($this -> get_option( 'thumbnails')=='Y' ) && ( $thumbs == 'off' )) {
				$this -> update_option('thumbnails_temp', 'N');	
			} elseif (($this -> get_option( 'thumbnails')=='N' ) && ( $thumbs == 'on' )) {
				$this -> update_option( 'thumbnails_temp', 'Y' );
			} elseif ($thumbs == "fullright") {
                            	$this -> update_option( 'thumbnails_temp', 'FR' );
			} elseif ($thumbs == "fullleft") {
                            	$this -> update_option( 'thumbnails_temp', 'FL' );
			}
		}
                
                if( !empty( $random ) ){   // update random in db options
			if(($this -> get_option('random') == 'off' || $this -> get_option('random') == null)  && ($random == 'on') ){
				$this -> update_option('random', 'on' );	
			} elseif(($this -> get_option('random') == 'on' )  && ($random == 'off')){
				$this -> update_option('random', 'off' );
			}
		}

		if ( !empty( $transition ) ) {
			if (($this -> get_option( 'transition' )!='FE' ) && ( $transition == 'fade-empty' )) {
				$this -> update_option('transition_temp', 'FE');	
			} elseif (($this -> get_option( 'transition' )!='FB' ) && ( $transition == 'fade-blend' ) ) {
				$this -> update_option( 'transition_temp', 'FB' );
			} elseif (($this -> get_option( 'transition' )!='OHS' ) && ( $transition == 'vertical-slide' ) ) {
				$this -> update_option( 'transition_temp', 'OVS' );
			} elseif (($this -> get_option( 'transition' )!='OHS' ) && ( $transition == 'horizontal-slide' )) {
				$this -> update_option( 'transition_temp', 'OHS' );
			} elseif (($this -> get_option( 'transition' )!='OHP' ) && ( $transition == 'horizontal-push' )) {
				$this -> update_option( 'transition_temp', 'OHP' );
			} elseif (($this -> get_option( 'transition' )!='OM' ) && ( $transition == 'orbit-multi' )) {
				$this -> update_option( 'transition_temp', 'OM' );
			}
		}
		if ( !empty( $auto ) ) { 
			if (($this -> get_option('autoslide')=='Y' ) && ( $auto == 'off' ) ) {
				$this -> update_option('autoslide_temp', 'N' );	
			} elseif ( ( $this -> get_option('autoslide')=='N' ) && ($auto == 'on' ) ) {
				$this -> update_option( 'autoslide_temp', 'Y' );
			}
		} elseif ( $this -> get_option( 'autoslide') == 'Y' ) {
			$this -> update_option( 'autoslide_temp', 'Y' ); 
		}
                if ( !empty( $splash )) {
                        if ($splash == 'on')
                           $this -> update_option('splash', true);
                }
               

                /******** PRO ONLY **************/
		if ( SATL_PRO ) {

			require SATL_PLUGIN_DIR . '/pro/custom_sizing.php';
                       
		}
		//$this -> add_action(array($this, 'pro_custom_wh'));
		/******** END PRO ONLY **************/
		if ( !empty($nocaption) ) { 
                    $this -> update_option('information', 'N' ); 
                    $this -> update_option('orbitinfo', 'N' ); 
                    }
		if ( !empty($nolink) ) { $this -> update_option( 'nolinker', 'Y' ); }
			else { $this -> update_option( 'nolinker', 'N' ); }
		if ( (!empty($custom)) || (!empty($gallery)) ) { // custom is deprecated as of version 1.2
                    $gallery = ($custom) ? $custom : $gallery;
                    $multigallery = preg_match("[\,]",$gallery);
                    if ( $multigallery ) {
                        $gallery_array = explode(',',$gallery);
                        $first_gallery = $gallery_array[0];
                        $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($first_gallery)), null, array('slide_order', "ASC"));
                    } else {
                        $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($gallery)), null, array('slide_order', "ASC"));
                    }
                                            
                    if( $this -> get_option('random') == "on"){
                        shuffle($slides);
                    }
                    $this->slidenum = count($slides);
                    if ( SATL_PRO && $multigallery )
                        $content = $this -> render('galleries', array('slides' => $slides, 'frompost' => false, 'galleries' => $gallery_array), false, 'premium');
                    elseif (SATL_PRO && $this -> get_option('splash'))
                        $content = $this -> render('splash', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    elseif ( $this -> get_option( 'thumbnails_temp') == "FR" || $this -> get_option( 'thumbnails_temp') == "FL" )
                        $content = $this -> render('fullthumb', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    else
                        $content = $this -> render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                } else { // from post
			global $post;
			$post_id_orig = $post -> ID;
			if ( empty( $slug )) {
				$pid = (empty($post_id)) ? $post -> ID : $post_id;
			} else {
				$page = get_page_by_path('$slug');
				if ($page) {
					$pid = $page->ID;
				} else {
					$page = get_page_by_path($slug, '', 'post');
					if ($page) {
						$pid = $page->ID;
					} else {
						$pid = (empty($post_id)) ? $post -> ID : $post_id;
					}
				}
			}
			if (!empty( $pid ) && $post = get_post($pid)) {
				if ($attachments = get_children("post_parent=" . $post -> ID . "&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC")) {
					if( $this -> get_option('random') == "on"){
						shuffle($attachments);
					}
					$content = $this->exclude_ids($attachments, $exclude, $include);
				}
			}
			$post -> ID = $post_id_orig;
		}
		
		return $content;
	}
	function resetTemp() {
		// This section allows for using _temp variable only (esp in gallery.php)
		if ($this -> get_option('information')=='Y') { $this -> update_option('information_temp', 'Y'); }
		elseif ($this -> get_option('information')=='N') { $this -> update_option('information_temp', 'N'); }
		if ($this -> get_option('orbitinfo')=='Y') { $this -> update_option('orbitinfo_temp', 'Y'); }
		elseif ($this -> get_option('orbitinfo')=='N') { $this -> update_option('orbitinfo_temp', 'N'); }
		if ($this -> get_option('thumbnails')=='Y') { 
                    $this -> update_option('thumbnails_temp', 'Y'); 
                    if ($this -> get_option('thumbposition')=='FR') { $this -> update_option('thumbnails_temp', 'FR'); }
                    elseif ($this -> get_option('thumbposition')=='FL') { $this -> update_option('thumbnails_temp', 'FL'); }
                }
		elseif ($this -> get_option('thumbnails')=='N') { $this -> update_option('thumbnails_temp', 'N'); }
		if ($this -> get_option('autoslide')=='Y') { $this -> update_option('autoslide_temp', 'Y'); }
		elseif ($this -> get_option('autoslide')=='N') { $this -> update_option('autoslide_temp', 'N'); }
		if ($this -> get_option('transition')=='FE') { $this -> update_option('transition_temp', 'FE'); }
		elseif ($this -> get_option('transition')=='FB') { $this -> update_option('transition_temp', 'FB'); }
		elseif ($this -> get_option('transition')=='OVS') { $this -> update_option('transition_temp', 'OVS'); }
		elseif ($this -> get_option('transition')=='OHS') { $this -> update_option('transition_temp', 'OHS'); }
		elseif ($this -> get_option('transition')=='OHP') { $this -> update_option('transition_temp', 'OHP'); }
		elseif ($this -> get_option('transition')=='OM') { $this -> update_option('transition_temp', 'OM'); }
                if ($this -> get_option('random') != null) { $this -> update_option('random', null); }
                
                // RESET FOR PREMIUM EDITION SINGLE INSTANCE
                /** Align, Width, Height, all save on top of itself, and don't need cleared out **/
                
                //if ($this -> get_option('align_temp') != null) { $this -> update_option('align_temp', null); }
                //if ($this -> get_option('nav_temp') != null) { $this -> update_option('nav_temp', null); }
                //if ($this -> get_option('width_temp') != null) { $this -> update_option('width_temp', null); }
                //if ($this -> get_option('height_temp') != null) { $this -> update_option('height_temp', null); }
                
		$style = array();
		$style = $this -> get_option('styles');
		$style['align'] = "none";
		$this -> update_option('styles', $style);
                
                // RESET non configurable options
                $this -> update_option('splash', false);
                $this -> update_option('align', false);
	}
	function exclude_ids( $attachments, $exclude, $include ) {
		if ( ! empty( $exclude )) {
			$exclude = array_map('trim', explode(',', $exclude));
/*			echo("<script type='text/javascript'>alert('exclude! ".$exclude[0]."');</script>");*/
			foreach ( $attachments as $id => $attachment ) {
				if (in_array( $id, $exclude )) {
					unset( $attachments[$id] );
				}
			}
		}
		elseif (!empty($include)) {
			$include = array_map('trim', explode(',', $include));
/*			echo("<script type='text/javascript'>alert('include!".$include[0]."');</script>");*/
			foreach ($attachments as $id => $attachment) {
				if (in_array($id, $include)) {}
				else { unset($attachments[$id]); }
			}
		}
		if ( $this -> get_option('transition_temp') == "OM") {
			$content = $this -> render('multislider', array('slides' => $attachments, 'frompost' => true), false, 'pro');
		} elseif (  $this -> get_option( 'thumbnails_temp') == "FR" || 
                            $this -> get_option( 'thumbnails_temp') == "FL" ) {
			$content = $this -> render('fullthumb', array('slides' => $attachments, 'frompost' => true), false, 'orbit');
		} else {
			$content = $this -> render('default', array('slides' => $attachments, 'frompost' => true), false, 'orbit');
                }
		return $content;
	}	
	
	function admin_slides() {	
		switch ($_GET['method']) {
			case 'delete'			:
				if (!empty($_GET['id'])) {
					if ($this -> Slide -> delete($_GET['id'])) {
						$msg_type = 'message';
						$message = __('Slide has been removed', SATL_PLUGIN_NAME);
					} else {
						$msg_type = 'error';
						$message = __('Slide cannot be removed', SATL_PLUGIN_NAME);	
					}
				} else {
					$msg_type = 'error';
					$message = __('No slide was specified', SATL_PLUGIN_NAME);
				}
				
				$this -> redirect($this -> url, $msg_type, $message);
				break;
			case 'single'			:
				if (!empty($_POST['section'])) {
                                        $msg_type = 'message';
                                        $single = $_POST['section'];
                                        $message = __('You have successfully updated your view to '.$single, SATL_PLUGIN_NAME);
                                        if ( $single != "All") {
                                            $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($single)), null, array('slide_order', "ASC"));
                                            $this -> url = $this -> url . "&single={$single}";
                                        } else {
                                            $this -> url = $this -> url;
                                        }
				} else {
					$msg_type = 'error';
					$message = __('No section was specified', SATL_PLUGIN_NAME);
				}
				$this -> redirect($this -> url, $msg_type, $message);
				break;
			case 'save'				:
				if (!empty($_POST)) {
					if ($this -> Slide -> save($_POST, true)) {
						$message = __('Slide has been saved', SATL_PLUGIN_NAME);
						$this -> redirect($this -> url, "message", $message);
					} else {
						$this -> render('slides' . DS . 'save', false, true, 'admin');
					}
				} else {
					$this -> Db -> model = $this -> Slide -> model;
					$this -> Slide -> find(array('id' => $_GET['id']));
					$this -> render('slides' . DS . 'save', false, true, 'admin');
				}
				break;
			case 'mass'				:
				if (!empty($_POST['action'])) {
					if (!empty($_POST['Slide']['checklist'])) {						
						switch ($_POST['action']) {
							case 'delete'				:							
								foreach ($_POST['Slide']['checklist'] as $slide_id) {
									$this -> Slide -> delete($slide_id);
								}
								
								$message = __('Selected slides have been removed', SATL_PLUGIN_NAME);
								$this -> redirect($this -> url, 'message', $message);
								break;
						}
					} else {
						$message = __('No slides were selected', SATL_PLUGIN_NAME);
						$this -> redirect($this -> url, "error", $message);
					}
				} else {
					$message = __('No action was specified', SATL_PLUGIN_NAME);
					$this -> redirect($this -> url, "error", $message);
				}
				break;
			case 'order'			:
				$slides = $this -> Slide -> find_all(null, null, array('slide_order', "ASC"));
				$this -> render('slides' . DS . 'order', array('slides' => $slides), true, 'admin');
				break;
                        case 'copysgpro'                :
                                $sgprodir = SATL_UPLOAD_DIR.'/../slideshow-gallery-pro/';
                                SatelliteSlide::full_copy($sgprodir, SATL_UPLOAD_DIR);
                                if ($this -> is_empty_folder(SATL_UPLOAD_DIR)) {
                                    $message = __('Sorry! Your files weren\'t able to be copied over.', SATL_PLUGIN_NAME);
                                    $this -> redirect($this -> url, "error", $message);
                                } else {
                                    $message = __('Your files have been successfully copied!', SATL_PLUGIN_NAME);
                                    $this -> redirect($this -> url, "message", $message);
                                }
                                break;
			default					:
				$data = $this -> paginate('Slide');				
				$this -> render('slides' . DS . 'index', array('slides' => $data[$this -> Slide -> model], 'paginate' => $data['Paginate']), true, 'admin');
				break;
		}
	}
        
	function admin_galleries() {	
                $galleries = $this->Gallery->find_all(null, null, array('id', "ASC"));

		switch ($_GET['method']) {
			case 'delete'			:
				if (!empty($_GET['id'])) {
					if ($this -> Gallery -> delete($_GET['id'])) {
						$msg_type = 'message';
						$message = __('Gallery has been removed', SATL_PLUGIN_NAME);
					} else {
						$msg_type = 'error';
						$message = __('Gallery cannot be removed', SATL_PLUGIN_NAME);	
					}
				} else {
					$msg_type = 'error';
					$message = __('No gallery was specified', SATL_PLUGIN_NAME);
				}
				
				$this -> redirect($this -> url, $msg_type, $message);
				break;
			case 'save'				:
				if (!empty($_POST)) {
					if ($this -> Gallery -> save($_POST, true)) {
                                            if (!empty($_POST['images'])) {
                                                if ($this -> Slide -> processImages($_POST['images'], $_POST['Gallery']['id'])) {
                                                    $message = __('Gallery and images have been saved', SATL_PLUGIN_NAME);
                                                } else {
                                                    $message = __('Gallery has saved but image upload failed', SATL_PLUGIN_NAME);
                                                }
                                            } else {
                                                $message = __('Gallery info has been saved', SATL_PLUGIN_NAME);
                                            }
                                            $this -> redirect($this -> url, "message", $message);
					} else {
						$this -> render('galleries' . DS . 'save', false, true, 'admin');
					}
				} else {
					$this -> Db -> model = $this -> Gallery -> model;
					$this -> Gallery -> find(array('id' => $_GET['id']));
					$this -> render('galleries' . DS . 'save', false, true, 'admin');
				}
				break;                                
			case 'mass'				:
				if (!empty($_POST['action'])) {
					if (!empty($_POST['Gallery']['checklist'])) {						
						switch ($_POST['action']) {
							case 'delete'				:							
								foreach ($_POST['Gallery']['checklist'] as $gallery_id) {
									$this -> Gallery -> delete($gallery_id);
								}
								
								$message = __('Selected galleries have been removed', SATL_PLUGIN_NAME);
								$this -> redirect($this -> url, 'message', $message);
								break;
						}
					} else {
						$message = __('No galleries were selected', SATL_PLUGIN_NAME);
						$this -> redirect($this -> url, "error", $message);
					}
				} else {
					$message = __('No action was specified', SATL_PLUGIN_NAME);
					$this -> redirect($this -> url, "error", $message);
				}
				break;
			default					:
				$data = $this -> paginate('Gallery');				
				$this -> render('galleries' . DS . 'index', array('galleries' => $data[$this -> Gallery -> model], 'paginate' => $data['Paginate']), true, 'admin');
				break;
		}
	}
        
        function admin_newgallery() {
                if (!empty($_POST)) {
                    if ($this -> Gallery -> save($_POST, true)) {
                        if (!empty($_POST['images'])) {
                            $this -> Slide -> processImages($_POST['images']);
                            $message = __('Gallery and images have been saved', SATL_PLUGIN_NAME);
                        } else {
                            $message = __('Gallery with no images has been saved', SATL_PLUGIN_NAME);
                        }
                        $this -> redirect($this -> url, "message", $message);
                    } else {
                        $this -> render('galleries' . DS . 'save', false, true, 'admin');
                    }
                } else {
                    $this -> Db -> model = $this -> Gallery -> model;
                    $this -> Gallery -> find(array('id' => $_GET['id']));
                    $this -> render('galleries' . DS . 'save', false, true, 'admin');
                }

        }
	
	function admin_settings() {
            if ( ! isset( $_GET['method'] ) ) { $_GET['method'] = "undefined"; }
            switch ($_GET['method']) {
                case 'reset'			:
                    global $wpdb;
                    $query = "DELETE FROM `" . $wpdb -> prefix . "options` WHERE `option_name` LIKE '" . $this -> pre . "%';";

                    if ($wpdb -> query($query)) {
                            $message = __('All configuration settings have been reset to their defaults', SATL_PLUGIN_NAME);
                            $msg_type = 'message';
                            $this -> render_msg($message);	
                    } else {
                            $message = __('Configuration settings could not be reset', SATL_PLUGIN_NAME);
                            $msg_type = 'error';
                            $this -> render_err($message);
                    }

                    $this -> redirect($this -> url, $msg_type, $message);
                    break;
                default					:
                    if (!empty($_POST)) {
                        foreach ($_POST as $pkey => $pval) {		
                                $this -> update_option($pkey, $pval);
                        }

                        $message = __('Configuration has been saved', SATL_PLUGIN_NAME);
                        $this -> render_msg($message);
                    }	
                    break;
            }

            $this -> render('settings', false, true, 'admin');
	}
	
}
//initialize a Satellite object
$Satellite = new Satellite();
?>
