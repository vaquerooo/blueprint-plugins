<?php

class ExtraBurger extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Burger Trigger'); 
    }
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }
    
    function init() {
        
        add_filter("oxy_allowed_empty_options_list", array( $this, "allowedEmptyOptions") );
        add_filter("oxygen_vsb_element_presets_defaults", array( $this, "presetsDefaults") );

    }
    
    
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function presetsDefaults($defaults) {

        $default_burger_trigger_presets = array();
        
        include("burger-trigger/burger-trigger-default-presets.php");

        $defaults = array_merge($defaults, $default_burger_trigger_presets);

        return $defaults;
    }
    
    
    function extras_button_place() {
        return 'interactive';
    }
    
    
    function render($options, $defaults, $content) {
        
        $animation  = isset( $options['animation'] ) ? esc_attr($options['animation']) : '';
        $text = $options['text'];
        $aria_label = isset( $options['aria_label'] ) ? esc_attr($options['aria_label']) : '';
        //$maybe_animation = isset( $options['maybe_animation'] ) ? esc_attr($options['maybe_animation']) : '';
        
        $isactive = (isset( $options['start'] ) && $options['start'] === 'open') ? 'is-active' : '';
            
        $output = '<button ';
        
        if ($options['aria_label_display'] === 'enable') {
            
            $output .= 'aria-label='. $aria_label .' ';
            
        }
        
        if (esc_attr($options['maybe_animation']) === 'disable') {
            
            $output .= 'data-animation="disable" ';
            
        }
        
        $output .= ' class="hamburger hamburger--'.$animation.' '.$isactive.'" type="button"><span class="hamburger-box"><span class="hamburger-inner"></span></span>'.$text.'</button>';
        
        echo $output;
        
        // Allow users with Oxygen 3.4+ to preview burger animation in builder.
        if( method_exists('OxygenElement', 'builderInlineJS') ) {
        
            $this->El->builderInlineJS(
                "   jQuery('#%%ELEMENT_ID%%').off( 'click' );
                    jQuery('#%%ELEMENT_ID%%').on( 'click' , function(e) { 
                    
                        e.stopPropagation();    
                        
                        if (jQuery(this).children('.hamburger').hasClass('is-active')) {
                            jQuery(this).children('.hamburger').removeClass('is-active');
                        } else {
                            jQuery(this).children('.hamburger').addClass('is-active');
                        }
                        
                        
                    });
                
                ");
            
        }
        
        // add JavaScript code only once and if shortcode presented
        if ($this->js_added !== true) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            $this->js_added = true;
        }
        
    }

    function class_names() {
        return array();
    }

    function controls() {
        
        /**
         * Selectors
         */
        $hamburger_lines_selector = ".hamburger-inner, .hamburger-inner:after, .hamburger-inner:before";
        $hamburger_lines_hover_selector = ".hamburger:hover .hamburger-inner, .hamburger:hover .hamburger-inner:after, .hamburger:hover .hamburger-inner:before";
        $hamburger_lines_active_selector = ".hamburger.is-active .hamburger-inner, .hamburger.is-active .hamburger-inner:after, .hamburger.is-active .hamburger-inner:before";
        $hamburger_line = ".hamburger-inner";
        $hamburger_box = '.hamburger-box';
        $hamburger = ".hamburger";
        
        
        
        $this->addStyleControl(
            array(
                "name" => __('Burger Scale'),
                "selector" => $hamburger_box,
                "property" => '--burger-size',
                "control_type" => 'slider-measurebox',
                "default" => '1',
                )
            )
            //->setUnits('px','px')
            ->setRange('0','2', '.1');
        
        $this->addStyleControl(
            array(
                "name" => __('Burger Line Height'),
                "selector" => $hamburger_lines_selector,
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "default" => '4',
                )
            )
            ->setUnits('px','px')
            ->setRange('1','6', '1');
        
        /**
         * Burger Colors
         */
        
        $burger_color_section = $this->addControlSection("burger_color_section", __("Colors"), "assets/icon.png", $this);
    
        $burger_color_section->addStyleControls(
             array( 
                array(
                    "name" => 'Line Color',
                    "selector" => $hamburger_lines_selector,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Line Color',
                    "selector" => $hamburger_lines_hover_selector,
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Active Line Color',
                    "selector" => $hamburger_lines_active_selector,
                    "property" => 'background-color',
                )
                 
            )
        );
        
        
        
        
        /**
         * Hamburger Animation Settgins
         */
        $animations = $this->addControlSection("animations", __("Animation Settings"), "assets/icon.png", $this);
        
        
        $animations->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Burger Animations',
                'slug' => 'maybe_animation'
            )
            
        )->setValue(array( "enable" => "Enable", "disable" => "Disable" ))
         ->setDefaultValue('enable');
        
        
       $animations->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Animation",
                "slug" => "animation",
                "default" => 'slider',
                "condition" => 'maybe_animation!=disable'
            )
        )->setValue(
           array( 
                "3dx" => "3dx", 
                "3dy" => "3dy",
               "arrow" => "arrow",
               "arrowalt" => "arrowalt",
               "arrowturn" => "arrowturn",
               "boring" => "boring",
               "collapse" => "collapse",
               "elastic" => "elastic",
               "emphatic" => "emphatic",
               "minus" => "minus",
               "slider" => "slider",
               "spring" => "spring",
               "stand" => "stand",
               "squeeze" => "squeeze",
               "vortex" => "vortex",
               
           )
       )->rebuildElementOnChange();
        
        $transition = $animations->addStyleControl(
            array(
                "name" => __('Transition Duration'),
                "property" => 'transition-duration',
                "selector" => $hamburger_lines_selector,
                "control_type" => 'slider-measurebox',
                "default" => '400',
                "condition" => 'maybe_animation!=disable'
            )
        );

        $transition->setUnits('ms','ms');
        $transition->setRange(10, 800, 5);
        
        
        
    
        
        
        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'State on Page Load',
                'slug' => 'start')
            
        )->setValue(array( "closed" => "Closed", "open" => "Open" ))
         ->setDefaultValue('closed')->rebuildElementOnChange();
        
        
        /**
         * Hamburger Button Controls
         */
        $button = $this->addControlSection("button", __("Button Styles"), "assets/icon.png", $this);
        
        $button->addPreset(
            "padding",
            "button_padding",
            __("Padding"),
            $hamburger
        )->whiteList();
        
        $button->addStyleControls(
             array( 
                array(
                    "name" => 'Background Color',
                    "selector" => $hamburger,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Background  Color',
                    "selector" => $hamburger.":hover",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Active Background  Color',
                    "selector" => $hamburger.".is-active",
                    "property" => 'background-color',
                ) 
                 
            )
        );
        
        
        $button->borderSection('Borders', $hamburger,$this);
        $button->boxShadowSection('Shadows', $hamburger,$this);
        
        $button->boxShadowSection('Hover Shadows', $hamburger.":hover",$this);
        $button->borderSection('Hover Borders', $hamburger.":hover",$this);
        
        $button->boxShadowSection('Active Shadows', $hamburger.".is-active",$this);
        $button->borderSection('Active Borders', $hamburger.".is-active",$this);
        
        
        
        
        /**
         * Hamburger Button Text
         */
        
        $text_section = $this->addControlSection("text_section", __("Button Text"), "assets/icon.png", $this);
        
        $text_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Optional Text'),
                "slug" => 'text',
                "default" => '',
            )
        )->rebuildElementOnChange();
        
        $text_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Aria Label',
                'slug' => 'aria_label_display'
            )
            
        )->setValue(array( "enable" => "Enable", "disable" => "Disable" ))
         ->setDefaultValue('disable');
        
        $text_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Aria Label'),
                "slug" => 'aria_label',
                "default" => '',
                "condition" => 'aria_label_display=enable',
            )
        );
        
        $text_section->addStyleControl(
                 array(
                    "name" => 'Burger Box Margin-Right',
                    "selector" => $hamburger_box,
                    "property" => 'margin-right',
                )
        );
        
        $text_section->addStyleControls(
             array( 
                array(
                    "name" => 'Text Color',
                    "selector" => $hamburger,
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Hover Text Color',
                    "selector" => $hamburger.":hover",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Active Text Color',
                    "selector" => $hamburger.".is-active",
                    "property" => 'color',
                )
                 
            )
        );
        
        
    }
    
    
    function allowedEmptyOptions($options) {

        $options_to_add = array(
            "oxy-burger-trigger_text"
        );

        $options = array_merge($options, $options_to_add);

        return $options;
    }
    
    function customCSS($options, $selector) {
        
        $css = file_get_contents( plugin_dir_path(__FILE__) . 'assets/hamburgers.css' );
        
        $css .= ".oxy-burger-trigger {
                    display: inline-block;
                }
        
                .oxy-burger-trigger .hamburger {
                    display: flex;
                    padding: 0;
                    align-items: center;
                }
                
                .oxy-burger-trigger .hamburger-box {
                    --burger-size: 1;
                    transform: scale(var(--burger-size));
                    -webkit-transform: scale(var(--burger-size));
                }
                
                .oxy-burger-trigger .hamburger:focus {
                    outline: none;
                }
                
                .oxy-burger-trigger .hamburger-inner, 
                .oxy-burger-trigger .hamburger-inner:after, 
                .oxy-burger-trigger .hamburger-inner:before {
                    transition-duration: 400ms;
                }";
        
        return $css;
        
    }
    
    function output_js() { ?>
            
            <script type="text/javascript">
            jQuery(document).ready(oxygen_init_burger);
            function oxygen_init_burger($) {
                
                // check if supports touch, otherwise it's click:
                let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click'; 
                
                // Close hamburger when element clicked 
                $('.oxy-burger-trigger').on( touchEvent, function(e) {    
                    e.stopPropagation();
                    
                    console.log('clicked burger');
                    
                    // Check user wants animations
                    if ($(this).children( '.hamburger' ).data('animation') !== 'disable') {
                        $(this).children( '.hamburger' ).toggleClass('is-active');
                    }
                } );
                
                
                // For listening for modals closing to close the hamburger
                var className = 'live';
                var target = document.querySelectorAll(".oxy-modal-backdrop[data-trigger='user_clicks_element']");
                for (var i = 0; i < target.length; i++) {

                    // create an observer instance
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            
                            // When the style changes on modal backdrop
                            if (mutation.attributeName === 'style') {

                                // If the modal is live and is closing  
                                if(!mutation.target.classList.contains(className)){

                                    // Close the toggle
                                    closeToggle(mutation.target);

                                }
                            }  
                        });
                    });

                    // configuration of the observer
                    var config = { 
                        attributes: true,
                        attributeFilter: ['style'],
                        subtree: false
                    };

                    // pass in the target node, as well as the observer options
                    observer.observe(target[i], config);
                }
                
                
                
               // Helper function to close hamburger if modal closed.
                function closeToggle(elem) {
                    
                    var triggerSelector = $($(elem).data('trigger-selector'));
                    
                    // Abort if burger not being used as the trigger or animations not turned on
                    if ((!triggerSelector.hasClass('oxy-burger-trigger')) || (triggerSelector.children( '.hamburger' ).data('animation') === 'disable') ) {
                        return;
                    }
                    // Close that particular burger
                    triggerSelector.children('.hamburger').removeClass('is-active');
                    
                }
                
                
            } </script>

    <?php }

    function afterInit() {
        $this->removeApplyParamsButton();
    }

}

new ExtraBurger();