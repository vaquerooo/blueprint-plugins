<?php
defined('ABSPATH') or die();

class OxyPowerPackDrawer
{

    function __construct()
    {

        if(get_option('oxypowerpack_drawer_enabled') != 'true') return;

        add_action( 'admin_init', array($this, 'output_admin_menu_json'), 0 );

        add_action('wp_enqueue_scripts', function () {

            if ( defined("SHOW_CT_BUILDER" ) && !defined( "OXYGEN_IFRAME" ) ) {
                wp_enqueue_style('oxypowerpackcss', plugin_dir_url(__FILE__) . "assets/dist/oxypowerpack.min.css");
                wp_enqueue_style('oxypowerpackmapboxcss', plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl.css");
                wp_register_script('oxypowerpackmapbox',plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl.js", array(), OXYPOWERPACK_VERSION);
                wp_enqueue_style('oxypowerpackmapboxdrawcss', plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl-draw/mapbox-gl-draw.css");
                wp_register_script('oxypowerpackmapboxdraw',plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl-draw/mapbox-gl-draw.js", array('oxypowerpackmapbox'), OXYPOWERPACK_VERSION);
                wp_enqueue_style('oxypowerpackmapboxgeocodercss', plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl-geocoder/mapbox-gl-geocoder.css");
                wp_register_script('oxypowerpackmapboxgeocoder',plugin_dir_url(__FILE__) . "assets/vendor/mapbox/mapbox-gl-geocoder/mapbox-gl-geocoder.min.js", array('oxypowerpackmapbox'), OXYPOWERPACK_VERSION);
                $minified = true;
                if(isset($_GET['debugger'])) $minified = false;
                wp_register_script("oxypowerpackjs", plugin_dir_url(__FILE__) . "assets/" . ($minified ? 'dist/oxypowerpack.min' : 'sources/js/oxypowerpack') . ".js", array('oxypowerpackmapbox','oxypowerpackmapboxdraw','oxypowerpackmapboxgeocoder','ct-angular-ui', 'wp-api', 'jquery-form','oxypowerpackdatetimepicker'), OXYPOWERPACK_VERSION);

                wp_localize_script( 'oxypowerpackjs', 'OxyPowerPackBEData', array(
                        'admin_url' => admin_url(),
                        'library_enabled' => post_type_exists( 'oxy_user_library' ),
                        'components' => OxyPowerPack::$components,
                        'events_enabled' => get_option('oxypowerpack_events_enabled', 'false'),
                        'attributes_enabled' => get_option('oxypowerpack_attributes_enabled', 'false'),
                        'tooltips_enabled' => get_option('oxypowerpack_tooltips_enabled', 'false'),
                        'parallax_enabled' => get_option('oxypowerpack_parallax_enabled', 'false'),
                        'textrotator_enabled' => get_option('oxypowerpack_textrotator_enabled', 'false'),
                        'lazyload_enabled' => get_option('oxypowerpack_lazyload', 'false'),
                        'license_status' => get_option( 'OxyPowerPack_license_status' ),
                        'loginIframeSrc' => add_query_arg( array('interim-login' => '1' ), wp_login_url() ),
                        'relogin_enabled' => get_option('oxypowerpack_login_enabled', 'false'),
                        'sass_enabled' => get_option('oxypowerpack_sass_enabled', 'false'),
                        'mapbox_key' => trim(get_option('oxypowerpack_mapbox_key', ''))
                ));
                wp_enqueue_script('oxypowerpackjs');

                // Inject cacheSchema = false to avoid errors when users activate "make this wordpress install a design set" and WP API schema is cached without endpoint for oxy_user_library CPT
                global $wp_scripts;
                $apiData = $wp_scripts->get_data('wp-api-request', 'data');
                if ( ! empty( $apiData ) ) {
                    $script = 'wpApiSettings.cacheSchema = false;';
                    $apiData = "$apiData\n$script";
                    $wp_scripts->add_data( 'wp-api-request', 'data', $apiData );
                }

            }
        });
        add_action("ct_before_builder", array( $this, "inject_user_interface") );

        // Expose Oxygen templates, and library/gutenberg blocks to REST API
        add_filter( 'register_post_type_args', function ( $args, $post_type ) {
            if ( $post_type == "ct_template" || $post_type == "oxy_user_library" ) {
                $args['show_in_rest'] = true;
                $args['supports'][] = 'custom-fields';
            }
            return $args;
        }, 10, 2 );

        // Expose various ct_* to REST API
        $meta_args = array(
            'type'      => 'string',
            'description'    => '',
            'single'        => true,
            'show_in_rest'    => true,
        );
        $meta_args_parent_template = array(
            'type'      => 'integer',
            'description'    => '',
            'single'        => true,
            'show_in_rest'    => true,
        );
        register_meta( 'page', 'ct_builder_shortcodes', $meta_args );
        register_meta( 'post', 'ct_builder_shortcodes', $meta_args );
        register_meta( 'post', 'ct_template_type', $meta_args );
        register_meta( 'post', 'ct_parent_template', $meta_args_parent_template );

        add_action( 'template_redirect', array($this, 'redirect_to'),0 );

        add_action('oxygen_vsb_effects_tabs_after', function () {
            ?>
            <div class='oxygen-sidebar-advanced-subtab' ng-class="{'oxy-styles-present': iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackParallax'] && iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackParallax'] != '0' }"
                 ng-click="oxyPowerPack.parallaxModal=true"
                 ng-hide="hasOpenTabs('effects') || oxyPowerPack.BEData.parallax_enabled!='true'">
                <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 25px;margin-left: 0px;margin-right: 28px;" title="OxyPowerPack" />
                <span>Parallax Speed</span>
            </div>
            <?php
        });

	    add_action("ct_toolbar_advanced_settings", 	function(){
	        ?>
            <div class='oxygen-sidebar-advanced-subtab' ng-class="{'oxy-styles-present': iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackAttributes'].length }"
                 ng-show="showAllStyles && oxyPowerPack.BEData.attributes_enabled=='true'"
                 ng-click="oxyPowerPack.attributes.attributesModal=true">
                <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 25px;margin-left: 0px;margin-right: 28px;" title="OxyPowerPack" alt="OxyPowerPack" />
                <span>Custom HTML Attributes</span>
            </div>
            <div class='oxygen-sidebar-advanced-subtab' ng-class="{'oxy-styles-present': !!iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackTooltip'] }"
                 ng-show="showAllStyles && oxyPowerPack.BEData.tooltips_enabled=='true'"
                 ng-click="oxyPowerPack.tooltips.showTooltipsModal()">
                <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 25px;margin-left: 0px;margin-right: 28px;" title="OxyPowerPack" alt="OxyPowerPack" />
                <span ng-show="!!iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackTooltip']">Edit Popover</span>
                <span ng-show="!!!iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackTooltip']">Create Popover</span>
            </div>
            <?php
        }, 11 );
	    add_action("ct_toolbar_component_settings", 	function(){
	        ?>
            <div class='oxygen-sidebar-advanced-subtab' ng-class="{'oxy-styles-present': iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackTextRotator'].length }"
                 ng-show="isActiveName('ct_span')&&!hasOpenTabs('ct_span')&& oxyPowerPack.BEData.textrotator_enabled=='true'"
                 ng-click="oxyPowerPack.textRotator.textRotatorModal=true">
                <img src='<?=plugin_dir_url(__FILE__)?>assets/img/logo.png' style="height: 25px;margin-left: 0px;margin-right: 28px;" title="OxyPowerPack" />
                <span>Text Rotator</span>
            </div>
		    <?php
        },11);

    }

    function inject_user_interface()
    {
        if ( defined("SHOW_CT_BUILDER" ) && !defined( "OXYGEN_IFRAME" ) ):
            ?>
            <?php require_once("assets/templates/drawer.template.php"); ?>
        <?php
        endif;

    }

    function redirect_to()
    {
        global $post;
        global $wpdb;
        if(!$post) return;
        if( isset( $_GET[ 'oxypowerpack_redirect_to' ] ) ){
            $url = '';
            switch(trim($_GET['oxypowerpack_redirect_to'] ) )
            {
                case 'oxygen':
                    if($post->post_type != 'ct_template') // Not templates
                    {
                        // We have to generate the correct URL, so posts rendered with a template that does not have an
                        // inner content are redirected to the dashboard edit screen instead

                        if ( get_option( 'page_for_posts' ) == $post->ID || get_option( 'page_on_front' ) == $post->ID ) {
                            $generic_view = ct_get_archives_template( $post->ID );
                            if(!$generic_view) $generic_view = ct_get_posts_template( $post->ID );
                        }
                        else $generic_view = ct_get_posts_template( $post->ID );

                        $ct_other_template = get_post_meta( $post->ID, 'ct_other_template', true );
                        // check if the other template contains ct_inner_content
                        $shortcodes = false;
                        if($ct_other_template && $ct_other_template > 0) {
                            $shortcodes = get_post_meta($ct_other_template, 'ct_builder_shortcodes', true);
                        } elseif ( $generic_view && $ct_other_template != -1) {
                            $shortcodes = get_post_meta($generic_view->ID, 'ct_builder_shortcodes', true);
                        }

                        $templates = $wpdb->get_results(
                            "SELECT id, post_title
                                FROM $wpdb->posts as post
                                WHERE post_type = 'ct_template'
                                AND post.post_status IN ('publish')"
                        );

                        $show_edit_button = false;
                        $option_is_selected = false;
                        $editing_block = false;

                        if ($post->post_type =='oxy_user_library'){
                            $editing_block = true;
                            $templates = [];
                        }

                        if( !$editing_block && $generic_view )
                        {
                            $shortcodes = get_post_meta($generic_view->ID, 'ct_builder_shortcodes', true);
                            $contains_inner_content = (strpos($shortcodes, '[ct_inner_content') !== false);
                            if(empty($ct_other_template)) {
                                $option_is_selected = true;
                                if($contains_inner_content) {
                                    $show_edit_button = true;
                                }
                            }
                        }

                        foreach($templates as $template) {
                            // do not count re-usables
                            $ct_template_type = get_post_meta($template->id, 'ct_template_type', true);
                            if($ct_template_type && $ct_template_type =='reusable_part') {
                                continue;
                            }

                            // do not count type = inner_content
                            $template_inner_content = get_post_meta($template->id, 'ct_template_inner_content', true);
                            if($template_inner_content) {
                                continue;
                            }

                            $is_selected_template = intval($ct_other_template) == $template->id;

                            $codes = get_post_meta($template->id, 'ct_builder_shortcodes', true);

                            $contains_inner_content = (strpos($codes, '[ct_inner_content') !== false);

                            if($is_selected_template) {
                                $shortcodes = get_post_meta($template->id, 'ct_builder_shortcodes', true);

                                $option_is_selected = true;
                                if($contains_inner_content) {
                                    $show_edit_button = true;
                                }
                            }
                        }

                        if(!$show_edit_button && $option_is_selected){
                            //Redirect to dashboard instead
                            $url = get_edit_post_link( $post->ID, '' );
                        } else {
                            // Redirect to builder
                            $url = ct_get_post_builder_link( $post->ID ) . ((($shortcodes && strpos($shortcodes, '[ct_inner_content') !== false) && intval($ct_other_template) !== -1)?'&ct_inner=true':'');
                        }
                    } else {
                        // Templates logic is easier
                        $ct_parent_template = get_post_meta( $post->ID, 'ct_parent_template', true );
                        $shortcodes = '';
                        if($ct_parent_template && $ct_parent_template > 0) {
                            $shortcodes = get_post_meta($ct_parent_template, 'ct_builder_shortcodes', true);
                        }
                        $url = ct_get_post_builder_link( $post->ID ) . (($shortcodes && strpos($shortcodes, '[ct_inner_content') !== false)?'&ct_inner=true':'');
                    }
                    break;
                case 'dashboard':
                    $url = get_edit_post_link( $post->ID, '' );
                    break;
            }

            if( !empty($url) )
            {
                wp_redirect( $url );
                exit();
            }
        }
    }

    function output_admin_menu_json()
    {
        global $menu;
        global $submenu;
        if (isset($_GET['oxypowerpack_menu_json']))
        {
            header('Content-Type: application/json');
            echo json_encode(array('menu'=> $menu, 'submenu' => $submenu));
            exit();
        }
    }

}
