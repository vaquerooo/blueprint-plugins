<?php

class ExtraAlert extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Alert Box'); 
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    
    function tag() {
        return array('default' => 'div' );
    }
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }
    
    function init() {
        $this->enableNesting();
    }
    
    
    function extras_button_place() {
        return "interactive";
    }
    
    
    function render($options, $defaults, $content) {
        
        
        // icons
        $icon  = isset( $options['icon'] ) ? esc_attr($options['icon']) : "";

        global $oxygen_svg_icons_to_load;
        $oxygen_svg_icons_to_load[] = $icon;
        
        
          if ($content) {

            echo do_shortcode($content);

          } 

          echo '<span class="alert-box_icon" ';    

                if(isset($options['show_again'])) {
                    echo 'data-open-again="' . esc_attr( $options['show_again'] ) . '"';
                }

                if(isset($options['show_days'])) {
                    echo 'data-open-again-after-days="' . esc_attr( $options['show_days'] ) . '"';
                }
        
                if (isset( $options['alert_closing'] ) && $options["alert_closing"] === "slide" ) {
                    echo 'data-close="' . esc_attr( $options['alert_closing'] ) . '"';  
                }

        echo '>';

          if (isset( $options['display_icon'] ) && $options["display_icon"] === "show" ) { ?>  
            <svg class="oxy-close-alert" id="<?php echo esc_attr($options['selector']); ?>-open-icon"><use xlink:href="#<?php echo $icon; ?>"></use></svg>
          <?php } 

      echo '</span>';
        
        
      // add JavaScript code only once and if shortcode presented
        if ($this->js_added !== true) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            $this->js_added = true;
        }    
        
        
        
        if( method_exists('OxygenElement', 'builderInlineJS') ) {
            // This is inline so when user adds alert box in header it will be full width for them without having to reload.
            $this->El->builderInlineJS("jQuery('#%%ELEMENT_ID%%').parent('.oxy-header-center').parent('.oxy-header-container').addClass('oxy-alert-box_inside');"); 
            
        } else {
            // Users on pre Oxygen v3.4 will have this on front end also.
            $this->El->inlineJS("jQuery('#%%ELEMENT_ID%%').parent('.oxy-header-center').parent('.oxy-header-container').addClass('oxy-alert-box_inside');");     
        }
        
    }

    function class_names() {
        return array();
    }
    
    function description() {
        ob_start(); ?>
        
            <div class=oxygen-control-label><?php echo __( "If the default close icon is removed, any element inside the alert box with 'oxy-close-alert' class can act as a close button." ) ?></div>

        <?php 

        return ob_get_clean();
    }

    function controls() {
        
        /**
         * Alert Type
         */
        /*$this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => 'Alert Type',
                'slug' => 'alert_type'
            )
            
        )->setValue(
            array( 
            "default" => "Default", 
            //"fixed" => "Fixed" ,
            "header" => "Header Notification",    
            )
        )
         ->setDefaultValue('default')
         ->setValueCSS( array(
            "header"  => "",
        ) );    */
        
        /**
         * Show Again
         */
        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Once the user has closed the alert",
                "slug" => "show_again",
                "default" => 'always_show',
            )
        )->setValue(
           array( 
                "always_show" => "Show again on every page load",
                "never_show_again" => "Never show again",
                "show_again_after" => "Show again after:",
           )
       );
        
       $this->addOptionControl(
           array(
                "type" => 'measurebox',
                "name" => __('Show Again After:'),
                "slug" 	    => "show_days",
                "default" => "3",
                "control_type" => 'slider-measurebox',
                "condition"		=> "show_again=show_again_after",
            )
        )
        ->setUnits('days','days');
        
        
        $this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => 'Alert Closing',
                'slug' => 'alert_closing'
            )
            
        )->setValue(
            array( 
            "fade" => "Fade Out", 
            "slide" => "Slide Up",    
            )
        )
         ->setDefaultValue('fade');
        
        
        
        /**
         * Alert Style controls
         */
        $this->addStyleControl(
            array(
                "property" => 'background-color',
            )
        );
        
        $this->addStyleControl(
            array(
                "property" => 'width',
            )
        );
        
        
        /**
         * Gutenberg support
         */
        if( class_exists( 'Oxygen_Gutenberg' ) ) {
        
            $this->addOptionControl(
                array(
                    'type' => 'buttons-list',
                    'name' => 'Visibility in Gutenberg',
                    'slug' => 'gutenberg_display')

            )->setValue(array( "hidden" => "Hidden", "visible" => "Visible" ))
             ->setDefaultValue('hidden');
            
        }
        
        
        /**
         * Icon
         */
        
        $icon = $this->addControlSection("icon", __("Close Icon"), "assets/icon.png", $this); 
        
         $icon_solid_selector = '.alert-box_icon';
        
        
        $icon_size = $icon->addStyleControl(
                array(
                    "name" => __('Icon Size'),
                    "slug" => "icon_size",
                    "selector" => $icon_solid_selector,
                    "control_type" => 'slider-measurebox',
                    "value" => '24',
                    "property" => 'font-size',
                    "condition" => 'display_icon=show',
                )
        );
        $icon_size->setRange(4, 72, 1);
        
        
        $icon->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Icon Display (Need to Apply Params)',
                'slug' => 'display_icon'
            )
        )->setValue(array( "show" => "Show Icon", "hide" => "Use Custom" ))
         ->setDefaultValue('show');
        
        $html = $this->description('desc',__("Description","oxygen"));
        $icon->addCustomControl($html, 'desc');
        
        
        $icon_choose = $icon->addControlSection("icon_choose", __("Change Icon"), "assets/icon.png", $this);
        
        $icon_choose->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'icon',
                "value" => 'Lineariconsicon-cross',
                "condition" => 'display_icon=show',
            )
        );
        
        $icon_color_section = $icon->addControlSection("icon_color_section", __("Colors"), "assets/icon.png", $this);
        
        $icon_color_section->addStyleControl(
            array(
                "property" => 'background-color',
                //"default" => '',
                "selector" => $icon_solid_selector,
                "condition" => 'display_icon=show',
            )
        );
        
        $icon_color_section->addStyleControl(
            array(
                "property" => 'color',
                "default" => '',
                "selector" => $icon_solid_selector,
                "condition" => 'display_icon=show',
            )
        );
        
        $icon_spacing_section = $icon->addControlSection("icon_spacing_section", __("Layout / Spacing"), "assets/icon.png", $this);
        
        $icon_spacing_section->addPreset(
            "padding",
            "icon_padding",
            __("Padding"),
            $icon_solid_selector
        )->whiteList();
        
        $icon_spacing_section->addPreset(
            "padding",
            "icon_margin",
            __("Margin"),
            $icon_solid_selector
        )->whiteList();
        
        $icon_spacing_section->addStyleControl(
            array(
                "property" => 'top',
                "default" => '10',
                "selector" => $icon_solid_selector,
            )
        );
        
        $icon_spacing_section->addStyleControl(
            array(
                "property" => 'left',
                "selector" => $icon_solid_selector,
            )
        );
        
        $icon_spacing_section->addStyleControl(
            array(
                "property" => 'right',
                "default" => '10',
                "selector" => $icon_solid_selector,
            )
        );
        
        $icon_spacing_section->addStyleControl(
            array(
                "property" => 'bottom',
                "selector" => $icon_solid_selector,
            )
        );
        
        $icon->borderSection('Borders', $icon_solid_selector,$this);
        $icon->boxShadowSection('Shadows', $icon_solid_selector,$this); 
       
        
        
        /**
         * Inner
         */
        
        $inner_selector = '';

        $spacing_section = $this->addControlSection("spacing_section", __("Layout / Spacing"), "assets/icon.png", $this);
        
        $spacing_section->flex('', $this);
        
        $spacing_section->addStyleControls(
            array(
                array(
                    "name" => 'Padding Left',
                    "property" => 'padding-left',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30'
                ),
                array(
                    "name" => 'Padding Right',
                    "property" => 'padding-right',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30'
                ),
                array(
                    "name" => 'Padding Top',
                    "property" => 'padding-top',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30'
                ),
                array(
                    "name" => 'Padding Bottom',
                    "property" => 'padding-bottom',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30'
                )
            )
        );
        
    }
    
    function defaultCSS() {
        
        $css = ".oxy-alert-box {
                    padding: 30px;
                }";
        
        return $css;
    }
    
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-alert-box {
                    display: none;
                    position: relative;
                    max-width: 100%;
                    margin: 0;
                }
                
                .show-alert {
                    display: inline-flex;
                }
                
                .oxy-alert-box_inside .oxy-alert-box {
                    width: 100%;
                }
                
                .oxygen-builder-body .oxy-alert-box {
                    display: inline-flex;
                }
                
                .alert-box-inner {
                    display: flex;
                }
                
                .alert-box_icon {
                   display: inline-flex;
                    position: absolute;
                    top: 10px;
                    right: 10px;
                }
                
                .alert-box_icon svg {
                    fill: currentColor;
                    width: 1em;
                    height: 1em;
                    cursor: pointer;
                }
                
                .oxy-alert-box_inside.oxy-header-container {
                    padding-left: 0;
                    padding-right: 0;
                }

                .oxygen-builder-body .oxy-alert-box_inside.oxy-header-container > div:empty {
                    min-width: 0;
                }";
        
            // Maybe visible in gutenberg
            if ((isset($options["oxy-alert-box_gutenberg_display"]) && $options["oxy-alert-box_gutenberg_display"] === "visible")) {
             
                $css .= ".oxy-alert-box.oxygenberg-element {
                            display: inline-flex;
                        }"; 
            }
        
        
           // Maybe change header row
            if ((isset($options["oxy-alert-box_alert_type"]) && $options["oxy-alert-box_alert_type"] === "header")) {
             
                $css .= ".header-row-alert .oxy-header-container {
                            padding: 0;
                        }

                        .oxygen-builder-body .header-row-alert .oxy-header-container > div:empty {
                            min-width: 0;
                        }"; 
            }
                
        
        return $css;
    }
    
    function output_js() { ?>
            
            <script type="text/javascript">
            jQuery(document).ready(oxygen_init_alert);
            function oxygen_init_alert($) {
                
                // Helper function to maybe show alert
                function showAlert( alert ) {
                    var $alert = jQuery( alert );
                    var alertData = $alert.children('.alert-box_icon');
                    var alertId = $alert[0].id;

                    // Current and last time in milliseconds
                    var currentTime = new Date().getTime();
                    var lastShownTime = localStorage && localStorage['oxy-' + alertId + '-last-shown-time'] ? JSON.parse( localStorage['oxy-' + alertId + '-last-shown-time'] ) : false;
                   
                        switch( alertData.data( 'open-again' ) ) {
                            case 'never_show_again':
                                // if it was shown at least once, don't show it again
                                if( lastShownTime !== false ) return;
                                break;
                            case 'show_again_after':
                                var settingDays = parseInt( alertData.data( 'open-again-after-days' ) );
                                var actualDays = ( currentTime - lastShownTime ) / ( 60*60*24*1000 );
                                if( actualDays < settingDays ) return;
                                break;
                            default:
                                //always show
                                break;
                        }
                   
                    // save current time as last shown time
                    if( localStorage ) localStorage['oxy-' + alertId + '-last-shown-time'] = JSON.stringify( currentTime );
                    
                    // Class added to display alert
                    $alert.addClass('show-alert');
                   
                }
                
                // Loop through all found alerts on page
                $( ".oxy-alert-box" ).each(function( index ) {
                    var alert = this;

                    (function( alert ){
                        var $alert = $( alert );
                        // Maybe show each alert
						showAlert( alert );

                    })( alert );

                });
                
                // Close button function
                $( '.oxy-close-alert, .alert-box_icon' ).click( function() {
                     event.preventDefault();
                     if ( $(this).closest('.oxy-alert-box').find('.alert-box_icon').data( 'close' ) === 'slide') {
                        $(this).closest('.oxy-alert-box').slideUp();
                     } else {
                         $(this).closest('.oxy-alert-box').fadeOut();
                     }
                } );
                
                // Give header new class if it contains an alert box
                $('.oxy-header-center:has(.oxy-alert-box)').parent('.oxy-header-container').addClass('oxy-alert-box_inside');
                
            }
                
        </script>

    <?php }

}

new ExtraAlert();