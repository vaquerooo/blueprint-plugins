<?php

class ExtraSearchForm extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Header Search'); 
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    
    function init() {

        // include only for builder
        if (isset( $_GET['oxygen_iframe'] )) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            
        }
        
    }
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }
    
    
    function extras_button_place() {
        return "interactive";
    }
    
    
    function render($options, $defaults, $content) {
        
         $search_icon = isset( $options['search_icon'] ) ? esc_attr($options['search_icon']) : "FontAwesomeicon-search";
         $close_icon = isset( $options['close_icon'] ) ? esc_attr($options['close_icon']) : "FontAwesomeicon-close";
        
        global $oxygen_svg_icons_to_load;
        $oxygen_svg_icons_to_load[] = $search_icon;
        $oxygen_svg_icons_to_load[] = $close_icon;


        // Get Options
        $action = home_url( '/' );
        $label = _x( 'Search for:', 'label' );
        $placeholder = isset( $options['placeholder_text'] ) ? esc_attr($options['placeholder_text']) : "Search ...";
        $icon_close_display = esc_attr($options['icon_close_display']);
        $value = get_search_query();
        $title = esc_attr_x( 'Search for:', 'label' );
        $submit_value = esc_attr_x( 'Search', 'submit button' );
        $search_post_type_filter = esc_attr($options['search_post_type_filter']);
        $post_type_slug = isset( $options['post_type_slug'] ) ? esc_attr($options['post_type_slug']) : "";
        


        $output = '<button aria-label="open search" class="oxy-header-search_toggle"><svg class="oxy-header-search_open-icon" id="open'. esc_attr($options['selector']) .'-icon"><use xlink:href="#' . $search_icon . '"></use></svg></button>';

        $output .= '<form role="search" method="get" class="oxy-header-search_form" action="'.$action.'">
                    <div class="oxy-header-container">
                    <label>
                        <span class="screen-reader-text">'.$label.'</span>
                        <input type="search" class="oxy-header-search_search-field" placeholder="'.$placeholder.'" value="'.$value.'" name="s" title="'.$title.'" />
                    </label>';
        
        // Maybe display Close icon
        if ($icon_close_display != 'hide') {
            $output .= '<button aria-label="close search" type=button class="oxy-header-search_toggle"><svg class="oxy-header-search_close-icon" id="close'. esc_attr($options['selector']) .'-icon"><use xlink:href="#' . $close_icon . '"></use></svg></button>';
        }
        
        if ( ( $search_post_type_filter === 'true') && isset( $options['post_type_slug'] ) ) {
            $output .= '<input type="hidden" name="post_type" value="'. $post_type_slug .'" />';
        }
        
        $output .= '<input type="submit" class="search-submit" value="'.$submit_value.'" /></div></form>';

        echo $output;
        
        
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
         * Type of Search
         */
        $this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => 'Header Search Type',
                'slug' => 'search_type',
                'default' => 'overlay'
            )
        )->setValue(array( 
            "overlay" => "Header Overlay", 
            "slideunder" => "Under Header",
            "fullscreen" => "Full Screen" 
        ))->setValueCSS( array(
            "overlay"  => " .oxy-header-search_form, .oxy-header-search_form {
                        height: 100%;
                    }
                    
               ",
            "slideunder"  => " .oxy-header-search_form {
                        top: 100%;
                        bottom: -100%;
                    }",
             "fullscreen"  => " .oxy-header-search_form {
                        position: fixed;
                        height: 100%;
                    }"
        ) );
        
        $form_selector = '.oxy-header-search_form';
        
        $this->addStyleControl( 
            array(
                "name" => 'Height',
                "type" => 'measurebox',
                "selector" => $form_selector,
                "units" => 'px',
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "condition" => 'search_type=slideunder'
            )
        )
        ->setRange('0','300','1');
        
        
       $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Form Visibility in Builder',
                'slug' => 'search_in_builder'
            )
        )->setValue(array( 
            "display" => "Display", 
            "hidden" => "Remain Hidden"
        ))->setDefaultValue('display')->rebuildElementOnChange();
        
        
        
        /**
         * Search Settings
        
        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Focus outline',
                'slug' => 'input_focus_outline'
            )
        )->setValue(array( 
            "show" => "Show", 
            "hide" => "Hide"
        ))->setDefaultValue('show')->setValueCSS( array(
            "hide"  => " .oxy-header-search_search-field:focus {
                            outline: 1px solid #ddd;
                        } ",
        ) );
         */
        
        /**
         * Search Settings
         */
        $search_section = $this->addControlSection("search_section", __("Search Results"), "assets/icon.png", $this);
        
        
        // Custom Post Type
        $search_section->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => __('Search One Post Type Only'),
                "slug" => 'search_post_type_filter',
                "value" => 'false'
            )
        );
        
         // Custom Post Type
        $search_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Post Type Slug'),
                "slug" => 'post_type_slug',
                "condition" => 'search_post_type_filter=true',
                "default" => '',
            )
        );
       
        
        /**
         * Icon Search
         */
        
        $icon_search = $this->addControlSection("icon_search", __("Search Icon"), "assets/icon.png", $this);
        $icon_search_selector = '.oxy-header-search_open-icon';
        
        $icon_search->addStyleControls(
            array(
                array(
                    "name" => __('Icon Color'),
                    "selector" => $icon_search_selector,
                    "property" => 'color',
                  
                ),
                array(
                    "name" => __('Hover Icon Color'),
                    "selector" => $icon_search_selector .":hover",
                    "property" => 'color',
                  
                ),
                array(
                    "name" => __('Icon Size'),
                    "selector" => $icon_search_selector,
                    "property" => 'font-size',
                   
                ),
            )
        );
        
        
        $icon_search_change = $icon_search->addControlSection("icon_search_change", __("Change Icon"), "assets/icon.png", $this);
       
        $icon_search_change->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Search Icon'),
                "slug" => 'search_icon',
                "default" => 'Lineariconsicon-magnifier'
            )
        )->rebuildElementOnChange();
        
        $icon_search_spacing = $icon_search->addControlSection("icon_search_spacing", __("Spacing"), "assets/icon.png", $this);
        
        $icon_search_spacing->addPreset(
            "margin",
            "icon_search_margin",
            __("Margin"),
            $icon_search_selector
        )->whiteList();
        
        
         /**
         * Icon Close
         */
        $icon_close = $this->addControlSection("icon_close", __("Close Icon"), "assets/icon.png", $this);
        $icon_close_selector = '.oxy-header-search_close-icon';
        
        $icon_close->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Icon Display',
                'slug' => 'icon_close_display'
            )
        )->setValue(array( 
            "display" => "Display", 
            "hide" => "Hide"
        ))->setDefaultValue('display')->rebuildElementOnChange();
        
        $icon_close->addStyleControls(
            array(
                array(
                    "name" => __('Icon Color'),
                    "selector" => $icon_close_selector,
                    "property" => 'color',
                    "condition" => 'icon_close_display=display'
                  
                ),
                array(
                    "name" => __('Hover Icon Color'),
                    "selector" => $icon_close_selector .":hover",
                    "property" => 'color',
                    "condition" => 'icon_close_display=display'
                  
                ),
                array(
                    "name" => __('Icon Size'),
                    "selector" => $icon_close_selector,
                    "property" => 'font-size',
                    "condition" => 'icon_close_display=display'
                   
                ),
            )
        );
        
        $icon_close_change = $icon_close->addControlSection("icon_close_change", __("Change Icon"), "assets/icon.png", $this);
        
        $icon_close_change->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Close Icon'),
                "slug" => 'close_icon',
                "default" => 'Lineariconsicon-cross',
                "condition" => 'icon_close_display=display'
            )
        )->rebuildElementOnChange();
        
        $icon_close_spacing = $icon_close->addControlSection("icon_close_spacing", __("Spacing"), "assets/icon.png", $this);
        
        $icon_close_spacing->addPreset(
            "margin",
            "icon_close_margin",
            __("Margin"),
            $icon_close_selector
        )->whiteList();
        
        
        
        
        $this->addStyleControls(
             array( 
                array(
                    "name" => 'Form Background Color',
                    "selector" => $form_selector,
                    "property" => 'background-color',
                    "default" => '#f3f3f3',
                )
                 
            )
        );
        
        
        
        $transition = $this->addStyleControl(
            array(
                "name" => __('Transition Duration'),
                "property" => 'transition-duration',
                "selector" => $form_selector,
                "control_type" => 'slider-measurebox',
                "default" => '.3',
            )
        );

        $transition->setUnits('s','s');
        $transition->setRange(.1, 1, .05);
        
        
        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Search Container width",
                "slug" => "container_width",
                "default" => 'header',
            )
        )->setValue(
           array( 
                "header" => "Header row width", 
                "full" => "Full width",
               //"custom" => "Custom",
               
           )
       )->setValueCSS( array(
            "header"  => " {
                        
                        }",
            "full"  => " .oxy-header-container {
                            max-width: 100%;
                        }"
        ) );
        
        
        
        /**
         * Form Input
         */
        $form_input_section = $this->addControlSection("form_input_section", __("Search Input"), "assets/icon.png", $this);
        $form_input_selector = '.oxy-header-search_search-field';
        
        // Placeholder Text
        $form_input_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Placeholder Text'),
                "slug" => 'placeholder_text',
                "default" => 'Search...',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $form_input_section->addPreset(
            "padding",
            "form_input_padding",
            __("Padding"),
            $form_input_selector
        )->whiteList();
        
        
        $form_input_section->borderSection('Borders', $form_input_selector,$this);
        
        $form_input_colors_section = $form_input_section->addControlSection("form_input_colors_section", __("Colors"), "assets/icon.png", $this);
        
        $form_input_colors_section->addStyleControls(
             array( 
                 array(
                    "name" => 'Background Color',
                    "selector" => $form_input_selector,
                    "property" => 'background-color',
                     "default" => 'rgba(255,255,255,0)',
                ),
                 array(
                    "name" => 'Color',
                    "selector" => $form_input_selector,
                    "property" => 'color',
                ),
                array(
                    "name" => 'Hover Color',
                    "selector" => $form_input_selector.":hover",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Focus Color',
                    "selector" => $form_input_selector.":focus",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Placeholder Color',
                    "selector" => $form_input_selector."::placeholder",
                    "property" => 'color',
                     "default" => 'inherit',
                )
                 
            )
        );
        
        
        $form_input_section->boxShadowSection('Shadows', $form_input_selector,$this);
        $form_input_section->typographySection('Typography', $form_input_selector,$this);
        
        
        
        if( method_exists('OxygenElement', 'builderInlineJS') ) {
        
            $this->El->builderInlineJS("jQuery('.oxygen-builder-body #%%ELEMENT_ID%%').find('.oxy-header-search_form').addClass('visible');");
            
        } else {
            // Uusers on pre Oxygen 3.4 will still get inline JS on front
            $this->El->inlineJS("jQuery('.oxygen-builder-body #%%ELEMENT_ID%%').find('.oxy-header-search_form').addClass('visible');");
            
        }
        
    }
    
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-header-search svg {
                    width: 1em;
                    height: 1em;
                    fill: currentColor;
                }
                
                .oxy-header-search_search-field:focus {
                    outline: none;
                }
                
                .oxy-header-search label {
                    width: 100%;
                }
                
                .oxy-header-search .screen-reader-text {
                    border: 0;
                    clip: rect(0, 0, 0, 0);
                    height: 1px;
                    overflow: hidden;
                    position: absolute !important;
                    width: 1px;
                    word-wrap: normal !important;
                }
                
                .oxy-header-search_toggle {
                    cursor: pointer;
                    background: none;
                    border: none;
                }
                
                .oxy-header-search input[type=submit] {
                    display: none;
                }
                
                .oxy-header-search_form {
                    background: #f3f3f3;
                    position: absolute;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    top: 0;
                    opacity: 0;
                    overflow: hidden;
                    visibility: hidden;
                    z-index: 99;
                    transition: all .3s ease;
                }
                
                .oxy-header-search_search-field {
                    background: rgba(255,255,255,0);
                    font-family: inherit;
                    border: none;
                    width: 100%;
                }
                
                .oxy-header-search_form.visible {
                    opacity: 1;
                    visibility: visible;
                }
                
                .oxy-header-search .oxy-header-container {
                    display: flex;
                    align-items: center;
                }
                ";
        
        if ( (isset( $options['oxy-header-search_search_in_builder']) ) && ($options['oxy-header-search_search_in_builder'] === 'hidden') ) {
            
            $css .= ".oxygen-builder-body .oxy-header-search_form {
                        visibility: hidden;
                    }"; 
        }

        return $css;
        
    }
    
    /**
     * Output js inline in footer once.
     */
    function output_js() { ?>
            
            <script type="text/javascript">
            jQuery(document).ready(oxygen_init_search);
            function oxygen_init_search($) {
                  
                $('body').on( 'click', '.oxy-header-search_toggle', function(e) {           
                        e.preventDefault();
                        let $toggle = $(this);
                        let $form = $toggle.closest('.oxy-header-search').find('.oxy-header-search_form');
                        
                        
                        if (!$form.hasClass('visible')) {
                            showSearch($toggle);
                            
                        } else {
                            hideSearch($toggle);
                        }
                    }
                );
                
                // Tabbing out will close search
                $('.oxy-header-search_toggle').next('.oxy-header-search_form').find('input[type=search]').on('keydown', function (event) {
                    
                    let togglebutton = $('.oxy-header-search_toggle');

                    if (event.keyCode === 9) {
                      hideSearch(togglebutton);
                    }

                });
                
                // Pressing ESC will close search
                $('.oxy-header-search_toggle').next('.oxy-header-search_form').find('input[type=search]').keyup(function(e){
                    
                    let togglebutton = $('.oxy-header-search_toggle');
                    if(e.keyCode === 27) {
                      hideSearch(togglebutton);
                    } 
                  });
                
                
                // Helper function to show the search form.
                function showSearch(toggle) {
                    
                    toggle.closest('.oxy-header-search').find('.oxy-header-search_form').addClass('visible');

                    setTimeout(
                    function() {
                        toggle.closest('.oxy-header-search').find('input[type=search]').focus();
                    }, 300);

                }

                // Helper function to hide the search form.
                function hideSearch(toggle) {

                    toggle.closest('.oxy-header-search').find('.oxy-header-search_form').removeClass('visible');

                }

             };
            
        </script>

    <?php }
    
    

}

new ExtraSearchForm();