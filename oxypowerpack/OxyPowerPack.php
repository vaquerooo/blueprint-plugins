<?php
/*
Plugin Name: OxyPowerPack
Author: Emmanuel Laborin
Author URI: https://oxypowerpack.com
Description: Power Features and Elements for Oxygen Builder
Version: 2.0.3
*/

defined('ABSPATH') or die();
define( 'OXYPOWERPACK_VERSION', '2.0.3' );
define( 'OXYPOWERPACK_PREVIEW_LABEL', '' );
define( 'OXYPOWERPACK_CURRENT_DESIGNSET', 'aHR0cHM6Ly9kZXNpZ25zZXQub3h5cG93ZXJwYWNrLmNvbQpPeHlQb3dlclBhY2sgRGVzaWducwpGUFV1dUtEb1lwMUQ=' );

include_once('OxyPowerPackUpdater.php');
//raz0r
update_option('OxyPowerPack_license_key','license_key');
update_option('OxyPowerPack_license_status','valid');
$oxyPowerPackComponents = array();

class OxyPowerPack
{
    static $OxyPowerPackRunning;
    static $components;
	static $component_tags;
    private $drawer;
    private $maintenance_mode;

    function __construct()
    {
        OxyPowerPack::$OxyPowerPackRunning = false;
        add_action('plugins_loaded', function(){

            if( !OxyPowerPack::is_oxygen_detected() || version_compare(CT_VERSION, '3.0', '<') || version_compare(get_bloginfo('version'), '4.7', '<') )
            {
                add_action( 'admin_notices', function()
                {
                    ?>
                    <div class="notice notice-warning">
                        <p><?php _e( '<strong>Requirements not met.</strong> OxyPowerPack needs Oxygen Builder 3.0+ and WordPress 4.7+ to run.', 'oxypowerpack' ); ?></p>
                    </div>
                    <?php
                } );
                return;
            }

			include_once 'api/oxypowerpack.element.class.php';
            include_once 'api/OxyPowerPackEl.php';

            foreach( glob(plugin_dir_path(__FILE__) . "components/*.component.php" ) as $filename)
            {
                include $filename;
            }

			OxyPowerPack::$OxyPowerPackRunning = true;
			include_once 'OxyPowerPackDrawer.php';
			include_once 'OxyPowerPackMaintenanceMode.php';
			$this->drawer = new OxyPowerPackDrawer();
			$this->maintenance_mode = new OxyPowerPackMaintenanceMode();

            add_action( 'init', array($this, 'init'), 0 );

        });
    }

    function init()
    {
		global $oxyPowerPackComponents;
		OxyPowerPack::$components = array();
		OxyPowerPack::$component_tags = array();
		foreach($oxyPowerPackComponents as $key => $component)
		{
			$new_comp = array("name" => $component->name(), "description"=> $component->description(), "slug"=>$component->name2slug($component->name()));
			OxyPowerPack::$components[] = $new_comp;
			OxyPowerPack::$component_tags[] = $key;
		}

		add_action('wp_enqueue_scripts', function () {
			// Enqueue any helper scripts
			// TODO: move this to a more appropriate place, in the component class
			if ( defined("SHOW_CT_BUILDER" ) && !defined( "OXYGEN_IFRAME" ) ) {
				//wp_enqueue_style('oxypowerpackdatetimepickerstyle', plugin_dir_url(__FILE__) . "assets/vendor/datetimepicker/jquery.datetimepicker.min.css");
				wp_enqueue_script("oxypowerpackdatetimepicker", plugin_dir_url(__FILE__) . "assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js", array('jquery'), OXYPOWERPACK_VERSION);
			}

			// Dequeue all components scripts and styles in frontend, because the API doesn't care if the shortcode was actually used or not. We'll enqueue them later only if used
			if ( !defined("SHOW_CT_BUILDER" ) ) {
				foreach (OxyPowerPack::$components as $component) {
                    wp_dequeue_script('oxy-' . $component['slug']);
                    wp_dequeue_style('oxy-' . $component['slug']);
					for( $i = 0; $i <=7; $i++) {
						wp_dequeue_script('oxy-' . $component['slug'] . '-' . $i);
						wp_dequeue_style('oxy-' . $component['slug'] . '-' . $i);
					}
				}
			}

		}, 11); // Priority 11 to ensure all components scrips and styles are already enqueued

		// Enqueue all component styles and scripts in frontend, only if the shortcode was actually used
		add_filter( 'do_shortcode_tag', function ($output, $tag, $attr){
			global $oxyPowerPackComponents;
			if( in_array( $tag, OxyPowerPack::$component_tags)){ //make sure it is the right shortcode
				// The shortcode was used, so, enqueue its assets
				$oxyPowerPackComponents[$tag]->El->enqueue_scripts();
				$oxyPowerPackComponents[$tag]->El->enqueue_styles();
				return $output;
			}

			if( $tag == 'ct_image' && strpos($output, 'lazyload') !== false && !is_admin() ) {
				$output = str_replace('lazyload', '', $output);
				$output = str_replace('src=', 'lazyload-src=', $output);
				$output = str_replace('srcset=', 'lazyload-srcset=', $output);
            }
			return $output;
		},10,3);

        add_action('admin_menu', function()
        {
            add_menu_page( 'OxyPowerPack','OxyPowerPack', 'manage_options', 'oxypowerpack', array( $this, 'settings_page' ),
            plugin_dir_url(__FILE__).'/assets/img/logo.png" style="height:18px;');
            add_submenu_page('oxypowerpack','Power Form Submissions', 'Power Form Submissions', 'manage_options','edit.php?post_type=opp_form_submissions');
        });

        add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
        function load_admin_styles() {
            if(isset($_GET['page']) && in_array( $_GET['page'], array(
                    'oxypowerpack'
            ) ))
            {
                wp_enqueue_style( 'oxypowerpack_admin', plugin_dir_url(__FILE__) . '/assets/dist/oxypowerpack.css', false, OXYPOWERPACK_VERSION );
            }
        }

        add_action('oxygen_vsb_component_attr', function($options, $tag){
            $events = [ 'page-load','scroll','countdown-finished','cf7-submit','click','mouseover','mouseout','enterviewport','exitviewport','startoverlapping','finishoverlapping','powerform-submitted' ];
            foreach ($events as $event) {
                $option = str_replace('-','_','oxyPowerPackEvent-' . $event);
                $option_settings = $option . '_settings';
                if(isset($options[$option]) && get_option('oxypowerpack_events_enabled') == 'true'){
                    $event_data = $options[$option];
                    $event_string = "";
                    foreach ($event_data as $action){
                        $event_string .= $action['slug'];
                        foreach ($action['attributes'] as $attribute => $value) {
                            $event_string .= '&' . $attribute . '=' . OxyPowerPack::replace_insecure_tokens( $value );
                        }
                        $event_string .= '|';
                    }
                    $event_string = trim( $event_string,'|');
                    if( $event_string != '' && !defined("SHOW_CT_BUILDER" ) && !is_admin()) {
                        wp_enqueue_script("oxypowerpackEvents", plugin_dir_url(__FILE__) . "assets/dist/oxypowerpackEvents.min.js", array('jquery'), OXYPOWERPACK_VERSION, true);
                        echo 'data-oxyPowerPack-' . $event . '="' . $event_string . '" ';
                    }
                }

                if(isset($options[$option_settings]) && get_option('oxypowerpack_events_enabled') == 'true' ){
                    foreach ($options[$option_settings] as $key => $value) {
                        if(!empty($value)) echo  'data-oxyPowerPack-' . $event .'-' . $key . '="' . OxyPowerPack::replace_insecure_tokens( $value ) . '" ';
                    }
                }
            }

            if(isset($options['oxyPowerPackAttributes'])  && get_option('oxypowerpack_attributes_enabled') == 'true'){
                foreach($options['oxyPowerPackAttributes'] as $attribute ){
                    if( substr( $attribute['value'], 0,  20) == 'oxy_base64_encoded::' ) $attribute['value'] = base64_decode(substr($attribute['value'], 20));
                    $attribute['value'] = str_replace('&', '&amp;',$attribute['value']);
                    if(!empty($attribute['value'])) echo $attribute['name'].'="' . $attribute['value'] . '" ';
                }
            }

            if(isset($options['oxyPowerPackParallax']) && $options['oxyPowerPackParallax'] != '0' && get_option('oxypowerpack_parallax_enabled') == 'true' && ( !wp_is_mobile() || get_option('oxypowerpack_parallax_enabled_mobile') == 'true' )){
                if ( !defined("SHOW_CT_BUILDER" ) && !is_admin() ) {
                    wp_enqueue_script("oxypowerpackParallax", plugin_dir_url(__FILE__) . "assets/vendor/rellax/rellax.min.js", array('jquery'), OXYPOWERPACK_VERSION, true);
                }
                echo 'data-rellax-speed="' . $options['oxyPowerPackParallax'] . '" ';
            }

	        if(isset($options['oxyPowerPackTextRotator']) && count($options['oxyPowerPackTextRotator']) > 0 && get_option('oxypowerpack_textrotator_enabled') == 'true'){
		        if ( !defined("SHOW_CT_BUILDER" ) && !is_admin() ) {
			        wp_enqueue_script("oxypowerpackRotator", plugin_dir_url(__FILE__) . "assets/dist/oxypowerpackRotator.min.js", array('jquery'), OXYPOWERPACK_VERSION, true);
		        }
		        $texts = [];
		        foreach ($options['oxyPowerPackTextRotator'] as $text) {
		            $texts[] = $text['text'];
                }
		        echo 'data-oxypowerpack-rotator="' . join('|',$texts ) . '" ';
	        }

	        if(isset($options['oxyPowerPackLazyLoad']) && $options['oxyPowerPackLazyLoad'] == 'true' && get_option('oxypowerpack_lazyload') == 'true'){
		        if ( !defined("SHOW_CT_BUILDER" ) && !is_admin() ) {
			        wp_enqueue_script("oxypowerpackLazyLoad", plugin_dir_url(__FILE__) . "assets/dist/oxypowerpackLazyLoad.min.js", array('jquery'), OXYPOWERPACK_VERSION, true);
		        }
		        //output just a flag, so we can later replace src and srcset with something else if found
		        echo 'lazyload ';
	        }

            if(isset($options['oxyPowerPackTooltip']) && get_option('oxypowerpack_tooltips_enabled') == 'true'){
                if ( !defined("SHOW_CT_BUILDER" ) && !is_admin() ) {
                    wp_enqueue_script("oxypowerpackPopper", plugin_dir_url(__FILE__) . "assets/vendor/popper/popper.min.js", array('jquery'), OXYPOWERPACK_VERSION, true);
                    wp_enqueue_script("oxypowerpackTippy", plugin_dir_url(__FILE__) . "assets/vendor/tippy/tippy-bundle.iife.min.js", array('oxypowerpackPopper'), OXYPOWERPACK_VERSION, true);
                    wp_enqueue_script("oxypowerpackTooltips", plugin_dir_url(__FILE__) . "assets/dist/oxypowerpackTooltips.min.js", array('oxypowerpackTippy'), OXYPOWERPACK_VERSION, true);
                    if( !empty( $options['oxyPowerPackTooltip']['theme'] ) ) wp_enqueue_style( 'oxypowerpackTippyTheme' . $options['oxyPowerPackTooltip']['theme'], plugin_dir_url(__FILE__) . "assets/vendor/tippy/themes/" . $options['oxyPowerPackTooltip']['theme'] . ".css" );
                    if( $options['oxyPowerPackTooltip']['animation'] != 'fade' ) wp_enqueue_style( 'oxypowerpackTippyAnimation' . $options['oxyPowerPackTooltip']['animation'], plugin_dir_url(__FILE__) . "assets/vendor/tippy/animations/" . $options['oxyPowerPackTooltip']['animation'] . ".css" );
                }

                $tippyAtts = [];

                $tippyAtts[] = 'data-tippy-animation="' . $options['oxyPowerPackTooltip']['animation']  . '"';
                $tippyAtts[] = 'data-tippy-arrow="' . ($options['oxyPowerPackTooltip']['arrow'] == 'true' ? 'true' : 'false')  . '"';
                $tippyAtts[] = 'data-tippy-oppcontent="' . $options['oxyPowerPackTooltip']['content']  . '"';
                $tippyAtts[] = 'data-tippy-opptype="' . $options['oxyPowerPackTooltip']['type']  . '"';
                $tippyAtts[] = 'data-tippy-oppcontentcopy="' . ($options['oxyPowerPackTooltip']['contentCopy'] == true ? 'true' : 'false')  . '"';
                $tippyAtts[] = 'data-tippy-max-width="' . $options['oxyPowerPackTooltip']['maxWidth']  . '"';
                $tippyAtts[] = 'data-tippy-placement="' . $options['oxyPowerPackTooltip']['placement']  . '"';
                $tippyAtts[] = 'data-tippy-trigger="' . $options['oxyPowerPackTooltip']['trigger']  . '"';
                $tippyAtts[] = 'data-tippy-theme="' . $options['oxyPowerPackTooltip']['theme']  . '"';

                echo join(' ',$tippyAtts ) . ' ';
            }

        }, 10,2);

	    add_action('ct_toolbar_component_settings', function(){
		    ?>
		    <label class="oxygen-checkbox" id="oxypowerpack-lazyload-checkbox" ng-show="iframeScope.component.active.name=='ct_image' && oxyPowerPack.BEData.lazyload_enabled=='true'" style="display: flex;flex-direction: row;align-items: baseline;">
			    <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 20px;margin-right: 15px;" title="OxyPowerPack" alt="OxyPowerPack" />
			    <input type="checkbox" ng-true-value="'true'" ng-false-value="'false'" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['oxyPowerPackLazyLoad']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'oxyPowerPackLazyLoad');" class="ng-pristine ng-untouched ng-valid">
			    <div class="oxygen-checkbox-checkbox" ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.getOption('oxyPowerPackLazyLoad')=='true'}">
				    Lazy-Load This Image
			    </div>
		    </label>
		    <?php
	    });

        //Register general settings
        add_option('oxypowerpack_drawer_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_drawer_enabled');

        add_option('oxypowerpack_events_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_events_enabled');

        add_option('oxypowerpack_attributes_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_attributes_enabled');

        add_option('oxypowerpack_parallax_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_parallax_enabled');

	    add_option('oxypowerpack_textrotator_enabled', 'true');
	    register_setting('oxypowerpack', 'oxypowerpack_textrotator_enabled');

		add_option('oxypowerpack_parallax_enabled_mobile', 'true');
		register_setting('oxypowerpack', 'oxypowerpack_parallax_enabled_mobile');

	    add_option('oxypowerpack_lazyload', 'true');
	    register_setting('oxypowerpack', 'oxypowerpack_lazyload');

        add_option('oxypowerpack_tooltips_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_tooltips_enabled');

        add_option('oxypowerpack_login_enabled', 'true');
        register_setting('oxypowerpack', 'oxypowerpack_login_enabled');

        add_option('oxypowerpack_sass_secret', substr(md5(rand()),0,5));
        register_setting('oxypowerpack', 'oxypowerpack_sass_secret');

        add_option('oxypowerpack_sass_enabled', 'false');
        register_setting('oxypowerpack', 'oxypowerpack_sass_enabled');

        add_option('oxypowerpack_mapbox_key', '');
        register_setting('oxypowerpack', 'oxypowerpack_mapbox_key');

        // register form submissions CPT
        $labels = array(
            'name'                  => _x( 'Form Submissions', 'Post Type General Name', 'oxygen' ),
            'singular_name'         => _x( 'Form Submission', 'Post Type Singular Name', 'oxygen' ),
            'menu_name'             => __( 'Form Submissions', 'oxygen' ),
            'name_admin_bar'        => __( 'Form Submissions', 'oxygen' ),
            'archives'              => __( 'Form Submission Archives', 'oxygen' ),
        );
        $args = array(
            'label'                 => __( 'Form Submission', 'oxygen' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => false,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type( 'opp_form_submissions', $args );

        //Update the design set
        $source_key = OXYPOWERPACK_CURRENT_DESIGNSET;
        if( !empty($source_key) ){
            $oxygen_vsb_source_sites = get_option('oxygen_vsb_source_sites');
            if( !is_array($oxygen_vsb_source_sites) ) $oxygen_vsb_source_sites = [];
            $source_key = base64_decode($source_key);
            $exploded = explode("\n", $source_key);
            $source_site_label = isset($exploded[1])?$exploded[1]:false;
            $source_site_url = isset($exploded[0])?$exploded[0]:false;
            $source_site_access = isset($exploded[2])?$exploded[2]:false;
            if( get_option( 'OxyPowerPack_license_status' ) == 'valid' ){
	            $oxygen_vsb_source_sites[sanitize_title($source_site_label)] = array('label' => sanitize_text_field($source_site_label), 'url' => esc_url_raw($source_site_url), 'accesskey' => ($source_site_access === false ? '' : sanitize_text_field($source_site_access)));
            } else if( isset( $oxygen_vsb_source_sites[sanitize_title($source_site_label)] ) ) {
                unset( $oxygen_vsb_source_sites[sanitize_title($source_site_label)] );
            }
            update_option('oxygen_vsb_source_sites', $oxygen_vsb_source_sites);
        }

        if( isset( $_GET['oppPowerFormSubmission']) ) {
	        header('Content-Type: application/json');
	        $result = array(
                'message' => '',
                'error' => false
            );

            $formid = $_POST['oppForm'];
            $form_options = get_option('oxyPowerPackForms', []);
            if(!isset($form_options[$formid])) {
                $result['message'] = 'Invalid Form ID';
                $result['error'] = true;
                echo json_encode( $result );
                die();
            }
            $options = json_decode($form_options[$formid], true);

	        if(!wp_verify_nonce( $_POST['_wpnonce'], 'oppForm_'.$formid )){
		        $result['message'] = 'Invalid Nonce';
		        $result['error'] = true;
		        echo json_encode( $result );
		        die();
	        }

	        $invalid_fields = ['oppForm', '_wpnonce', '_wp_http_referer', 'simple_captcha', 'simple_captcha_number_1', 'simple_captcha_number_2'];

	        $all_data = "";
	        foreach ( $_POST as $field_name => $value ) {
		        if( in_array($field_name, $invalid_fields) ) continue;
		        $all_data .= '<b>' . $field_name . ':</b> ' . wp_strip_all_tags($value) .'<br/>';
	        }

	        if( isset($_POST['simple_captcha'])){
	            $num1 = intval($_POST['simple_captcha_number_1']);
		        $num2 = intval($_POST['simple_captcha_number_2']);
		        $result2 = $num1 + $num2;
		        if(intval($_POST['simple_captcha']) != $result2){
			        $result['message'] = 'Invalid Captcha';
			        $result['error'] = true;
			        echo json_encode( $result );
			        die();
		        }
	        }

	        if(isset($options['save_to_database']) && $options['save_to_database'] == 'true' ){


		        wp_insert_post( array(
			        'post_title'    => wp_strip_all_tags( $options['form_name'] . ' (' . $options['selector'] . ')' ),
			        'post_content'  => $all_data,
			        'post_status'   => 'publish',
			        'post_type' => 'opp_form_submissions'
		        ) );
	        }

	        if(isset($options['send_email_admins']) && $options['send_email_admins'] == 'true' ){
	            $emails = explode(',', $options['admin_email_address']);
	            $emails_good = [];
		        foreach ( $emails as $email ) {
                    $emailtmp = sanitize_email($email);
                    if(!empty($emailtmp)) $emails_good[] = $emailtmp;
	            }
		        $emails = implode(',', $emails_good);
		        $email_body = $options['admin_email_body'];
		        foreach ( $_POST as $field_name => $value ) {
			        if( in_array($field_name, $invalid_fields) ) continue;
			        $email_body = str_replace('['.$field_name.']', $value, $email_body);
		        }
		        $email_body = str_replace('[full_data]', $all_data, $email_body);
                wp_mail($emails,$options['admin_email_subject'], $email_body,array('Content-Type: text/html; charset=UTF-8'));
	        }

	        if(isset($options['send_email_user']) && $options['send_email_user'] == 'true' ){
		        $email = $options['user_email_field'];
		        if(!empty($email) && isset( $_POST[$email]) && !empty(sanitize_email($_POST[$email])) ) {
                    $email = sanitize_email($_POST[$email]);
			        $email_body = $options['user_email_body'];
			        foreach ( $_POST as $field_name => $value ) {
				        if ( in_array( $field_name, $invalid_fields ) ) {
					        continue;
				        }
				        $email_body = str_replace( '[' . $field_name . ']', $value, $email_body );
			        }
			        $email_body = str_replace('[full_data]', $all_data, $email_body);
			        wp_mail( $email, $options['user_email_subject'], $email_body, array( 'Content-Type: text/html; charset=UTF-8' ) );
		        }
	        }

	        if(isset($options['trigger_webhook']) && $options['trigger_webhook'] == 'true' ){
		        $url = $options['webhook_url'];
		        if(!empty($url) ) {
		            if( $options['webhook_payload'] == 'json' ){
			            wp_remote_post($url, array(
				            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
				            'body'        => json_encode($_POST),
				            'method'      => 'POST',
				            'blocking' => false,
				            'data_format' => 'body',
			            ));
		            } else {
			            wp_remote_post( $url, array(
					            'method' => 'POST',
					            'blocking' => false,
					            'body' => $_POST
				            )
			            );
		            }
		        }
	        }

	        if(isset($options['show_alert_message']) && $options['show_alert_message'] == 'true' ){
		        $result['message'] = $options['alert_content'];
	        }
	        $result = json_encode( $result );
	        echo $result;
	        exit();
        }

        if( isset( $_GET['oxypowerpack-upload-styles'] ) && isset( $_POST['style'] ) && isset( $_POST['secret'] ) ){

            if( get_option('oxypowerpack_sass_enabled') != 'true' ) {
                echo "External SASS Workflow is not enabled\n";
                die();
            }

            if( get_option('oxypowerpack_sass_secret') != $_POST['secret'] ) {
                echo "Invalid SASS Workflow secret code\n";
                die();
            }

            $opciones_actuales = get_option("ct_style_sheets", [] );
            $new_css = base64_encode( $_POST['style'] );

            $highest_id = 1;
            $reason_not_updated = 'unknown error';
            foreach ($opciones_actuales as $key => $stylesheet) {
                if($opciones_actuales[$key]['id'] > $highest_id) $highest_id = $stylesheet['id'];
                if($opciones_actuales[$key]['name'] == 'OxyPowerPack-Styles'){
                    if( $opciones_actuales[$key]['css'] != $new_css ){
                        $opciones_actuales[$key]['css'] = $new_css;
                    } else {
                        $reason_not_updated = "css is the same";
                    }
                    $highest_id = null;
                    break;
                }
            }

            if( ! is_null( $highest_id ) ) {
                $highest_id++;
                $opciones_actuales[] = array(
                    'id' => $highest_id,
                    'name' => 'OxyPowerPack-Styles',
                    'parent' => 0,
                    'css' => $new_css
                );
            }
            if( !update_option("ct_style_sheets", $opciones_actuales )){
                echo "Stylesheets not updated (". $reason_not_updated .")\n";
            }else{
                oxygen_vsb_cache_universal_css();
                echo "Stylesheets updated\n";
            }
            
            die();
        }

        if( isset( $_GET['oxypowerpack-get-styles'] ) ){
            $opciones_actuales = get_option("ct_style_sheets", [] );
            $out = "";
            foreach ($opciones_actuales as $key => $stylesheet) {
                if($opciones_actuales[$key]['name'] == 'OxyPowerPack-Styles'){
                    $out = $opciones_actuales[$key]['css'];
                    break;
                }                
            }
            header('Content-Type: application/json');
            echo json_encode( array( 'style' => $out ) );
            die();
        }

        if( isset( $_GET['oxypowerpack-heartbeat'] )) {
            $result = is_user_logged_in() ? wp_create_nonce( 'oxygen-nonce-' . $_GET['oxypowerpack-heartbeat'] ) : 'expired';
            header('Content-Type: application/json');
            echo json_encode( array( 'session_status' => $result ) );
            die();
        }

        add_action('oxygen_add_plus_sections', function(){
           ?>
            <div class='oxygen-add-section-accordion'
                 ng-click="switchTab('components', 'oxypowerpack');"
                 ng-hide="iframeScope.hasOpenFolders()">
                <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 18px; width: 18px; margin-left: 0px;margin-right: 10px;" title="OxyPowerPack" />
                <?php _e("OxyPowerPack", "oxypowerpack") ?>
                <img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/add-icons/dropdown-arrow.svg'/>
            </div>
            <div class='oxygen-add-section-accordion-contents oxygen-add-section-accordion-contents-toppad'
                 ng-if="isShowTab('components','oxypowerpack')">
                <h2><?php _e("Power", "oxypowerpack");?></h2>
                <div class="oxygen-add-section-element" ng-click="oxyPowerPack.comingSoonFeature('Power Video');" style="opacity:0.3;">
                    <img src="<?php echo plugin_dir_url(__FILE__)?>assets/img/icon-powervideo.svg">
                    <img src="<?php echo plugin_dir_url(__FILE__)?>assets/img/icon-powervideo-active.svg">
                    Power Video (soon)
                </div>
                <?php do_action("oxygen_add_plus_oxypowerpack_power"); ?>
                <div class="oxygen-add-section-element" ng-click="oxyPowerPack.premadeForms.premadeFormsModal=true;">
                    <img src="<?php echo plugin_dir_url(__FILE__)?>assets/img/icon-powerform.svg">
                    <img src="<?php echo plugin_dir_url(__FILE__)?>assets/img/icon-powerform-active.svg">
                    Power Form
                </div>
                <h2><?php _e("Basics", "oxypowerpack");?></h2>
                <?php do_action("oxygen_add_plus_oxypowerpack_basic"); ?>
                <!--h2><?php _e("Bootstrap", "oxypowerpack");?></h2-->
                <?php do_action("oxygen_add_plus_oxypowerpack_bootstrap"); ?>
            </div>
            <?php
        });
    }

    static function replace_insecure_tokens( $string ) {
        $invalid = ['"', '|', '&'];
        $valid = ['__QUOTATION_MARK__', '__VERTICAL_LINE__', '__AMPERSAND__'];
        return str_replace( $invalid, $valid, $string );
    }

    static function is_oxygen_detected()
    {
        return defined('CT_VERSION');
    }

    function settings_page(){
?>
<div class="oxypowerpack settings">
    <div class="container my-3 pt-3 bg-light text-primary">

        <div class="jumbotron py-4">
            <div class="container" style="display: flex; flex-direction: row; align-items: flex-end;">
                <img src="<?php echo plugin_dir_url(__FILE__)?>assets/img/logo.png" style="width:80px;" alt="OxyPowerPack">
                <h1 class="text-secondary ml-3">OxyPowerPack Settings</h1>
            </div>
        </div>

        <?php include('assets/templates/general-settings.php'); ?>

        <?php do_action('oxypowerpack_settings_page');?>
        <?php do_action('OxyPowerPack_license_form');?>
    </div>
</div>
<?php
    }
}

new OxyPowerPack();
