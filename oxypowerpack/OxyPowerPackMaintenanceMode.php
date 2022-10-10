<?php

defined('ABSPATH') or die();
class OxyPowerPackMaintenanceMode
{
    function __construct()
    {
        add_action( 'pre_get_posts', array($this, 'pre_get_posts') );
        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'template_redirect', array($this, 'template_redirect') );
	    add_action( 'wp', array( $this, 'send_headers') );

        if( !empty( $_GET['access_pass'] ) && trim($_GET['access_pass']) == get_option('oxy_maintenance_bypass') )
        {
            setcookie("oxymaintenancebypass","true",time()+60*60*24);
            $_COOKIE["oxymaintenancebypass"] = 'true'; // To get is working for the current request
        }

        add_action('oxypowerpack_settings_page', array( $this, 'settings_page' ));


        add_action( 'admin_print_footer_scripts', function() {
            global $post;
            $screen = get_current_screen();
            if ( $screen->id == 'page' ) {
                $roles = get_editable_roles();
                $page_is_maintenance = false;
                $default_page = intval(get_option('oxy_maintenance_page_default', -1));
                if( $post && $default_page == $post->ID )
                {
                    $page_is_maintenance = true;
                } else {
                    foreach( $roles as $role)
                    {
                        $role_page = intval(get_option('oxy_maintenance_page_'.str_replace(' ','',$role['name']), -1));
                        if( $role_page == $post->ID ) $page_is_maintenance = true;
                    }
                }

                if( !$page_is_maintenance) return;
                ?>
                <script type="text/javascript">
                    (function($){
                        $('#ct_parent_template').before("(OxyPowerPack notice: It's a good idea to not use a template on maintenance/coming soon pages)");
                    })(jQuery);
                </script>
                <?php
            }
        } );

        if( get_option('oxy_maintenance_enabled') == 'true' && is_user_logged_in() && current_user_can('manage_options') ){
            add_action('admin_bar_menu', function($admin_bar){
                $admin_bar->add_menu( array(
                    'id'    => 'oxy-maintenance-mode',
                    'title' => 'Maintenance Mode Active',
                    'href'  => admin_url( 'admin.php?page=oxypowerpack' ),
                    'meta'  => array(
                        'title' => 'Maintenance Mode Active',
                    ),
                ));
            }, 100);
            add_action( 'admin_head', array($this, 'custom_admin_bar_item_style') );
            add_action( 'wp_head', array($this, 'custom_admin_bar_item_style') );
        }
    }

    function custom_admin_bar_item_style() {

        if ( ! is_admin_bar_showing() ) {
            return;
        }

        ?>
        <style>
            #wp-admin-bar-oxy-maintenance-mode {
                background-color:red !important;
                color:white !important;
            }
        </style>
        <?php
    }

    function get_maintenance_page_id()
    {
        $maintenance_page_id = intval(get_option('oxy_maintenance_page_default'));
        if( is_user_logged_in() )
        {
            if( current_user_can('manage_options') ) return -1;
            $user = wp_get_current_user();
            $roles = ( array ) $user->roles;
            $role_page = -1;
            foreach ($roles as $role)
            {
                $role_page = intval(get_option('oxy_maintenance_page_'.str_replace(' ','',$role), '-1'));
            }
            if( $role_page == 0 ) $role_page = $maintenance_page_id;
            $maintenance_page_id = $role_page;
        }
        if( isset($_COOKIE["oxymaintenancebypass"]) && $_COOKIE["oxymaintenancebypass"] == 'true' ) $maintenance_page_id = -1;
        return $maintenance_page_id;
    }

    function template_redirect()
    {
        if( ! is_admin() && OxyPowerPack::is_oxygen_detected() && get_option('oxy_maintenance_enabled') == 'true' && get_option('oxy_maintenance_mode') == 'redirect' )
        {
            global $post;
            $maintenance_page = $this->get_maintenance_page_id();
            if( $maintenance_page == -1 ) return;
            $maintenance_page_url = get_post_permalink(get_post( $maintenance_page ));
            if( !isset($post) || $post->ID != $maintenance_page ) {
                wp_redirect( $maintenance_page_url, 302,  'OxyPowerPack' );
                exit();
            }
        }
    }

    function send_headers(){
	    if(is_admin() || !OxyPowerPack::is_oxygen_detected() || get_option('oxy_maintenance_enabled') != 'true' || get_option('oxy_maintenance_mode') == 'redirect') return;
	    $maintenance_page = $this->get_maintenance_page_id();
	    if( $maintenance_page == -1 ) return;
	    if(get_option('oxy_maintenance_503_status') == 'true')
        {
            http_response_code(503);
        }
    }

    function pre_get_posts($query)
    {
        if(is_admin() || !OxyPowerPack::is_oxygen_detected() || get_option('oxy_maintenance_enabled') != 'true' || get_option('oxy_maintenance_mode') == 'redirect') return;

        if ( !$query->is_main_query() )
            return;

        $maintenance_page = $this->get_maintenance_page_id();
        if( $maintenance_page == -1 ) return;

        $query->set('page_id' ,$maintenance_page);
        $query->is_singular = true;
        $query->is_page = true;
        $query->is_category = false;
        $query->is_archive = false;


    }

    function settings_page($angular=false)
    {
        if(!$angular):
        ?>
        <script type="text/javascript">
            function copy_bypass(){
                var copyText = document.getElementById("oxy_maintenance_bypass_dummy");
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                document.execCommand("copy");
                jQuery('#oxy_maintenance_bypass_dummy').hide().fadeIn('slow');
            }
            function bypass_code_changed(){
                setTimeout(function(){
                    var currDummy = jQuery("#oxy_maintenance_bypass_dummy").val();
                    currDummy = currDummy.split('=')[0] + '=' + jQuery("#oxy_maintenance_bypass").val();
                    jQuery("#oxy_maintenance_bypass_dummy").val( currDummy );
                },50);
            }
        </script>
        <?php
        endif;
        ?>
        <hr ng-if="false">


                <form method="post" action="<?php echo get_admin_url();?>options.php" class="row <?php if($angular) echo 'm-0 mr-2 pt-2';?>" ng-show="!oxyPowerPack.maintenanceModeFormBusy">

                    <?php
                    settings_fields( 'oxy_maintenance_mode' );
                    $args = array(
                        'numberposts' => -1,
                        'post_type' => 'page'
                    );
                    $posts = get_posts($args);

                    $roles = get_editable_roles();

                    $default_page = get_option('oxy_maintenance_page_default');
                    $mode = get_option('oxy_maintenance_mode');
                    ?>

                    <div class="col-md-6">
                        <h2 ng-if="false" class="text-secondary">Maintenance Mode</h2>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            Maintenance Activated
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxy_maintenance_enabled">
                                <input type="checkbox" id="oxy_maintenance_enabled" name="oxy_maintenance_enabled" value="true" <?php checked(get_option('oxy_maintenance_enabled'), "true"); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="oxy_maintenance_mode" class="col-sm-7">
                            Operating Mode
                        </label>

                        <select name="oxy_maintenance_mode" class="form-control form-control-sm col-sm-5" id="oxy_maintenance_mode" class="form-control form-control-sm">
                            <option value="replace" <?php echo $mode == 'replace' ? 'selected':'' ?>>Replace the page content</option>
                            <option value="redirect" <?php echo $mode == 'redirect' ? 'selected':'' ?>>Redirect to maintenance page</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="oxy_maintenance_bypass" class="col-sm-7">
                            Bypass Code
                        </label>
                        <div class="col-sm-5 p-0">
                            <input type="text" id="oxy_maintenance_bypass" class="form-control form-control-sm m-0" name="oxy_maintenance_bypass" value="<?php echo get_option('oxy_maintenance_bypass');?>" onkeypress="<?php if(!$angular) echo 'bypass_code_changed();'; ?>" ng-keypress="oxyPowerPack.maintenanceModeBypassChanged()">
                            <input type="text" id="oxy_maintenance_bypass_dummy" class="form-control form-control-sm m-0" value="<?php echo site_url('?access_pass=') . get_option('oxy_maintenance_bypass');?>" readonly><button type="button" onclick="<?php if(!$angular) echo 'copy_bypass();return false;'; ?>" ng-click="oxyPowerPack.maintenanceModeBypassCopy()" class="btn btn-secondary btn-sm">Copy bypass URL</button>
                        </div>
                    </div>

                    <!--div class="form-group row">
                        <label for="oxy_maintenance_503_status" class="form-check-label col-sm-5">
                            Set 503 Status Code on Maintenance Page
                        </label>
                        <div class="col-sm-7">
                            <input type="checkbox" id="oxy_maintenance_503_status" name="oxy_maintenance_503_status" value="true" <?php checked(get_option('oxy_maintenance_503_status'), "true"); ?>>
                        </div>

                    </div-->

                    </div>
                    <div class="col-md-6">

                    <h2 ng-if="false" class="text-secondary"><small>Maintenance Pages</small></h2>
                    <div class="form-group row">
                        <label for="oxy_maintenance_page_default" class="col-sm-5">
                            Logged-Out Users
                        </label>
                        <select name="oxy_maintenance_page_default" id="oxy_maintenance_page_default" class="form-control form-control-sm col-sm-7">
                            <option value="-1" <?php echo intval($default_page) == -1 ? 'selected':'' ?>></option>
                            <?php
                            foreach( $posts as $post ): ?>
                                <option value="<?php echo $post->ID; ?>" <?php echo intval($default_page) == $post->ID ? 'selected':'' ?>><?php echo $post->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php foreach( $roles as $role):
                        if( isset( $role['capabilities']['manage_options']) && $role['capabilities']['manage_options'] == true ) continue;
                        $role_page = get_option('oxy_maintenance_page_'.str_replace(' ','',$role['name']), -1);
                        ?>
                        <div class="form-group row">
                            <label for="oxy_maintenance_page_<?php echo str_replace(' ','',$role['name']);?>" class="col-sm-5">
                                <?php echo $role['name'];?>
                            </label>

                            <select name="oxy_maintenance_page_<?php echo str_replace(' ','',$role['name']);?>" class="form-control form-control-sm col-sm-7" id="oxy_maintenance_page_<?php echo str_replace(' ','',$role['name']);?>">
                                <option value="-1" <?php echo intval($role_page) == -1 ? 'selected':'' ?>>No maintenance mode for <?php echo $role['name'];?></option>
                                <option value="0" <?php echo intval($role_page) == 0 ? 'selected':'' ?>>Default (Inherit from Logged-Out Users)</option>
                                <?php
                                foreach( $posts as $post ): ?>
                                    <option value="<?php echo $post->ID; ?>" <?php echo intval($role_page) == $post->ID ? 'selected':'' ?>><?php echo $post->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php
                    endforeach; ?>
                    <?php submit_button('Save Settings', 'btn btn-primary btn-sm'); ?>

                    </div>

                </form>

        <?php
    }

    function admin_init()
    {
        add_option('oxy_maintenance_enabled', 'false');
        register_setting('oxy_maintenance_mode', 'oxy_maintenance_enabled');

        add_option('oxy_maintenance_page_default', -1);
        register_setting('oxy_maintenance_mode', 'oxy_maintenance_page_default');

        add_option('oxy_maintenance_mode', 'replace');
        register_setting('oxy_maintenance_mode', 'oxy_maintenance_mode');

        add_option('oxy_maintenance_bypass', substr(md5(rand()),0,5));
        register_setting('oxy_maintenance_mode', 'oxy_maintenance_bypass');

	    add_option('oxy_maintenance_503_status', 'false');
	    register_setting('oxy_maintenance_mode', 'oxy_maintenance_503_status');

        $roles = get_editable_roles();

        foreach ($roles as $role){
            if (isset($role['capabilities']['manage_options']) && $role['capabilities']['manage_options'] == true) continue;
            add_option('oxy_maintenance_page_' . str_replace(' ','',$role['name']), -1);
            register_setting('oxy_maintenance_mode', 'oxy_maintenance_page_' . str_replace(' ','',$role['name']));
        }

        // Disable maintenance mode if no default maintenance page is set
        $default_page = intval(get_option('oxy_maintenance_page_default'));
        $plugin_status = get_option('oxy_maintenance_enabled');
        if( $plugin_status == 'true' && $default_page == -1){
            $plugin_status = null;
            update_option( 'oxy_maintenance_enabled', 'false' );
            add_action( 'admin_notices', function() {
                ?>
                <div class="notice notice-warning">
                    <p><?php _e( 'Maintenance Mode disabled - A default page for logged-out users must be set.', 'oxypowerpack' ); ?></p>
                </div>
                <?php
            } );
        }

        add_filter( 'display_post_states', function( $post_states, $post ) {
            $maintenance_states = OxyPowerPackMaintenanceMode::label_for_page($post);
            if( !empty( $maintenance_states )) $post_states[] = $maintenance_states;
            return $post_states;
        }, 10, 2 );

        if( isset($_GET['page']) && $_GET['page']=='oxypowerpack' && isset($_GET['oxypowerpack_render_maintenance_form'])) {
            $this->settings_page(true);
            exit();
        }

    }

    static function label_for_page($post)
    {
        $roles = get_editable_roles();
        $valid_roles = array();
        $default_page = intval(get_option('oxy_maintenance_page_default', -1));
        if( $default_page == $post->ID )
        {
            $valid_roles[] = 'Logged-Out Users';
        }
        foreach( $roles as $role)
        {
            $role_page = intval(get_option('oxy_maintenance_page_'.str_replace(' ','',$role['name']), -1));
            if( $role_page == 0 ) $role_page = $default_page;
            if( $role_page == $post->ID ) $valid_roles[] = $role['name'];
        }

        if( count($valid_roles) == 0) return '';

        return 'Maintenance Mode ('. join(', ',$valid_roles) .')';
    }
}