<?php
/*
Plugin Name: Recoda Workspace 
Author: Renato Corluka
Author URI: https://recoda.me
Description: Integrated development environment for Oxygen Builder
Version: 0.9.8 P1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: recoda_ws
*/

// General direct file access security
if ( ! defined( 'WPINC' ) ) die;



register_uninstall_hook( __FILE__, 'recoda_workspace_on_deactivate' );

//hook just for debug
register_deactivation_hook( __FILE__, 'recoda_workspace_on_deactivate' );
// Only load styles and scripts if 1) user is currently logged in, 2) Oxygen editor is loaded, and 3) builder is set to "true".
add_action( 'init', 'tbf_EnqueueStylesAndScripts' );
add_action('wp_ajax_recoda_workspace_action_save_user_preference', 'recoda_workspace_save_user_preference');


function recoda_workspace_save_user_preference() {
    $data = wp_json_encode( $_POST['user_pref_load'] );
	update_option( 'recoda_workspace_user_preference', $data );
    die(); // this is required to return a proper result
}


function recoda_workspace_on_deactivate() {
	delete_option( 'recoda_workspace_user_preference' );
}
function recoda_workspace_update_user_preference_callback() {
	delete_option( 'recoda_workspace_user_preference' );
}

function tbf_EnqueueStylesAndScripts() {
	// HOOK CONDITION 1: User MUST BE LOGGED IN!!
	if( ! is_user_logged_in() ) return;

	// HOOK CONDITION 2: Check builder is available and editable before loading styles and scripts
	if ( isset( $_GET['ct_builder'] ) && $_GET['ct_builder'] == true ) :
		
		// Enqueue styles
		add_action( 'wp_footer', 'tbf_EnqueueCSS' );
		function tbf_EnqueueCSS() {
			$ws_path =  plugin_dir_path(__FILE__);
			wp_enqueue_style( 'tbf_styles', plugin_dir_url(__FILE__) . 'css/recoda_ux.min.css', array(), filemtime($ws_path . 'css/recoda_ux.min.css'));
			wp_enqueue_style( 'recoda-oxy-39', plugin_dir_url(__FILE__) . 'css/recoda_oxy39.css', array(), filemtime($ws_path . 'css/recoda_oxy39.css'));
			wp_enqueue_style( 'recoda_codemirror_foldgutter', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/fold/foldgutter.css', array(), filemtime($ws_path . 'vendors/codemirror/addon/fold/foldgutter.css'));
			wp_enqueue_style( 'recoda_codemirror_show-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/show-hint.css', array(), filemtime($ws_path . 'vendors/codemirror/addon/hint/show-hint.css'));
		}

		
		/* ______Hook scripts ____________________________________________________________________________
							HOOK:	oxygen_enqueue_ui_scripts => when: run this scripts just inside Oxygen UI
							ARG:	tbf_EnqueueJS => what all you need to enque 
		______________________________________________________________________________________________________*/ 
		add_action( 'oxygen_enqueue_ui_scripts', 'tbf_EnqueueJS' );
		add_action( 'oxygen_enqueue_iframe_scripts', 'ws_enqueue_iframe_scripts');
		/* ______Enqueue scripts ____________________________________________________________________________
							ARG1: ENQUEUE SCRIPT AS
							ARG2: PATH TO FILE => "plugin_dir_url(__FILE__) . 'js/_Globals.js" means position me to plugin directory and string concatination for relative path to plugin DIR
							ARG3: DEPENDENCY HANDLES, if there are dependecies, set theme here so wordpress know which scripts are needed to load first
							ARG4: VERSION NUMBER => if it changes wordpress will reload cache auto, in production use time stamp to prevent cache so all time it will reload cache
							ARG5: LOAD IN FOOTER (bool) => true/false, decide where you wanna load your scripts
		______________________________________________________________________________________________________*/ 
		function tbf_EnqueueJS() {
			function add_async_attribute($tag, $handle) {
                
                // set script names which will have async attribute
                $async_array = ['tbf-global-scripts', 'tbf-addElements-scripts', 'tbf-reang-scripts','tbf-advantor-scripts','tbf-themer-scripts', 'tbf-viewporthandles-scripts', 'ws-shortcuts-scripts', 'tbf-shortcutterframe-scripts', 'tbf-shortcutterdom-scripts', 'tbf-altclick-scripts', 'recoda-legacy_38', 'recoda-isore'];
       
                // add async attribute to src tag
                if( in_array($handle, $async_array) ){
                    return str_replace( ' src', ' defer="defer" src', $tag );
                }else{
                    return $tag;
                }
                 
            } 
			$ws_path =  plugin_dir_path(__FILE__);
			wp_enqueue_script( 'tbf-global-scripts', plugin_dir_url(__FILE__) . 'js/_Globals.js', array(), filemtime($ws_path . 'js/_Globals.js'), true );
			wp_enqueue_script( 'tbf-themer-scripts', plugin_dir_url(__FILE__) . 'js/themer.js', '', filemtime($ws_path . 'js/themer.js'), true );
			wp_enqueue_script( 'tbf-addElements-scripts', plugin_dir_url(__FILE__) . 'js/addElements.js', array(), filemtime($ws_path . 'js/addElements.js'), true );
			
			wp_enqueue_script( 'tbf-reang-scripts', plugin_dir_url(__FILE__) . 'js/reang.js', '', filemtime($ws_path . 'js/reang.js'), true );
			
			wp_enqueue_script( 'tbf-advantor-scripts', plugin_dir_url(__FILE__) . 'js/advantor.js', '', filemtime($ws_path . 'js/advantor.js'), true );
			//wp_enqueue_script( 'tbf-themer-scripts', plugin_dir_url(__FILE__) . 'js/themer.js', '', filemtime($ws_path . 'js/themer.js'), true );
			wp_enqueue_script( 'tbf-viewporthandles-scripts', plugin_dir_url(__FILE__) . 'js/viewporthandles.js', '', filemtime($ws_path . 'js/viewporthandles.js'), true );
			wp_enqueue_script( 'ws-shortcuts-scripts', plugin_dir_url(__FILE__) . 'js/shortcuts.js', '', filemtime($ws_path . 'js/shortcuts.js'), true );
			wp_enqueue_script( 'tbf-shortcutterframe-scripts', plugin_dir_url(__FILE__) . 'js/shortcutterframe.js', '', filemtime($ws_path . 'js/shortcutterframe.js'), true );
			wp_enqueue_script( 'tbf-altclick-scripts', plugin_dir_url(__FILE__) . 'js/altclick.js', '', filemtime($ws_path . 'js/altclick.js'), true );
			wp_enqueue_script( 'recoda-isore', plugin_dir_url(__FILE__) . 'js/isore.js', '', filemtime($ws_path . 'js/isore.js'), true );
			
		
			

			wp_enqueue_script( 'recoda_codemirror_brace-fold', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/fold/brace-fold.js', '', filemtime($ws_path . 'vendors/codemirror/addon/fold/brace-fold.js'), true );
			wp_enqueue_script( 'recoda_codemirror_foldgutter', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/fold/foldgutter.js', '', filemtime($ws_path . 'vendors/codemirror/addon/fold/foldgutter.js'), true );
			wp_enqueue_script( 'recoda_codemirror_foldcode', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/fold/foldcode.js', '', filemtime($ws_path . 'vendors/codemirror/addon/fold/foldcode.js'), true );
			
			wp_enqueue_script( 'recoda_codemirror_show-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/show-hint.js', '', filemtime($ws_path . 'vendors/codemirror/addon/hint/show-hint.js'), true );
			wp_enqueue_script( 'recoda_codemirror_javascript-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/javascript-hint.js', '', filemtime($ws_path . 'vendors/codemirror/addon/hint/javascript-hint.js'), true );
			wp_enqueue_script( 'recoda_codemirror_css-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/css-hint.js', '', filemtime($ws_path . 'vendors/codemirror/addon/hint/css-hint.js'), true );
			wp_enqueue_script( 'recoda_codemirror_php-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/php-hint.js', '', filemtime($ws_path . 'vendors/codemirror/addon/hint/php-hint.js'), true );
			wp_enqueue_script( 'recoda_codemirror_anyword-hint', plugin_dir_url(__FILE__) . 'vendors/codemirror/addon/hint/anyword-hint.js', '', filemtime($ws_path . 'vendors/codemirror/addon/hint/anyword-hint.js'), true );
			
			add_filter('script_loader_tag', 'add_async_attribute', 10, 2);
			// Setup our data
			$is_template = ( isset( $_GET['ct_template'] ) && $_GET['ct_template'] ) ? 1 : 0;
			
			if( !function_exists('get_plugin_data') ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				
			}
			if( !function_exists('get_plugin_data') ){
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					$xy_plugin_data = "Err";
					$ws_plugin_data = "Err";
				}
			else{
				$xy_plugin_data = get_plugin_data( CT_PLUGIN_MAIN_FILE );
				$ws_plugin_data = get_plugin_data( __FILE__ );
				
			}
			$args = array( '_builtin' => false );
			
			
			
			$worskspace_user_preference = json_decode(get_option( 'recoda_workspace_user_preference' ));
		
			wp_localize_script( 'tbf-global-scripts', 'rewsLocalVars', array(  'siteURL' => get_site_url(), 'oxyVersion' => $xy_plugin_data, 'wsVersion' =>$ws_plugin_data, 'istemplate' => $is_template, 'adminURL' => admin_url(), 'userPreference' => $worskspace_user_preference, 'userId' => get_current_user_id() ));
			
			
		}

		function ws_enqueue_iframe_scripts() {
			$ws_path =  plugin_dir_path(__FILE__);
			
			wp_enqueue_script( 'recoda-iframe-scripts', plugin_dir_url(__FILE__) . 'js/iframescripts.js', '', filemtime($ws_path . 'js/iframescripts.js'), true );
			
			
		}
	
	endif;
	
	include_once 'includes/views/class-switcher.php';
	include_once 'includes/views/user-profiles-init.php';
}
