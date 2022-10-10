<?php

class ExtraslideMenu extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Slide Menu'); 
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }
    
    function init() {

        // include only for builder
        if (isset( $_GET['oxygen_iframe'] )) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            
        }
        
    }
    
    
    function extras_button_place() {
        return "interactive";
    }
    
    
    function render($options, $defaults, $content) {
        
        if (isset( $options['schema_markup'] ) && esc_attr($options['schema_markup']) === 'true') {
        
            add_filter('nav_menu_link_attributes', array( $this, 'oxy_slide_menu_schema_link_attributes' ), 3, 10);
        
        }
        
        $icon = isset( $options['icon'] ) ? esc_attr($options['icon']) : "";
        
        global $oxygen_svg_icons_to_load;
        $oxygen_svg_icons_to_load[] = $icon;
        
        $trigger = isset( $options['trigger'] ) ? esc_attr($options['trigger']) : "";
        $start = esc_attr($options['start']);
        $duration = isset( $options['duration'] ) ? esc_attr($options['duration']) : "";
        $schema_markup = isset( $options['schema_markup'] ) && esc_attr($options['schema_markup']) === 'true' ? 'itemscope itemtype="https://schema.org/SiteNavigationElement"' : '';
        $link_before = isset( $options['schema_markup'] ) && esc_attr($options['schema_markup']) === 'true' ? '<span itemprop="name">' : '';
        $link_after = isset( $options['schema_markup'] ) && esc_attr($options['schema_markup']) === 'true' ? '</span>' : '';

      
        ob_start();
        
        ?><nav class="oxy-slide-menu_inner" <?php echo $schema_markup; ?> data-duration="<?php echo $duration; ?>" data-start="<?php echo $start; ?>" data-trigger-selector="<?php echo $trigger; ?>">  <?php
		
        // get options
        $menu_name  = isset( $options['extras_menu_name'] ) ? esc_attr($options['extras_menu_name']) : '';

		wp_nav_menu( array(
			'menu'           => $menu_name,
			'menu_class'     => 'oxy-slide-menu_list',
			'container'		 => '',
			'link_before'	 => $link_before,
			'link_after'	 => $link_after,
		) );

        $nav_menu_output = ob_get_clean();

        echo $nav_menu_output;
        
        echo '</nav>';
        
        if (isset( $options['schema_markup'] ) && esc_attr($options['schema_markup']) === 'true') {
        
            remove_filter('nav_menu_link_attributes', array( $this, 'oxy_slide_menu_schema_link_attributes' ), 3, 10);
            
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
    
    function oxy_slide_menu_schema_link_attributes( $atts, $item, $args ) {
        $atts['itemprop'] = 'url';
        return $atts;
    }

    function controls() {
        
        /**
         * Menu Dropdown
         */ 
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 

        $menus_list = array(); 
        foreach ( $menus as $key => $menu ) {
            $menus_list[$menu->term_id] = $menu->name;
        } 

        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Menu",
                "slug" => "extras_menu_name"
            )
        )->setValue($menus_list)->rebuildElementOnChange();
        
        
        /**
         * Menu Dropdown
         */ 
        $start_control = $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'State on Page Load',
                'slug' => 'start'
            )
            
        );
        
        $start_control->setValue(array( "hidden" => "Hidden", "open" => "Open" ));
        $start_control->setDefaultValue('open');
        $start_control->setValueCSS( array(
            "hidden" => "
                {
                 display: none;
                }
            ",
        ) );
        //$start_control->whiteList();
        
        
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Trigger to reveal menu'),
                "slug" => 'trigger',
                "condition" => 'start=hidden',
                "default" => '.oxy-burger-trigger',
            )
        )->whiteList();
        
        $item_selector = ".oxy-slide-menu_list .menu-item a";
        
        
        
        $align_control = $this->addOptionControl(
            array(
                'type' => 'dropdown',
                "name" => __('Menu align'),
                "slug" => "menu_align",
            )
        );
        
        $align_control->setValue(array( 
            "text left" => "Left",
            "center" => "Center all",
            "center_text"  => "Center text",
            "right" => "Right",
        ));
        $align_control->setDefaultValue('left');
        $align_control->setValueCSS( array(
            "center_text" => " .menu-item a {
                            justify-content: center;
                            position: relative;
                        }

                        .oxy-slide-menu_dropdown-icon-click-area {
                            position: absolute;
                            right: 0;
                        }
            ",
            
             "center" => " .menu-item a {
                            justify-content: center;
                        }
            ",
            "right" => " .menu-item a {
                            justify-content: flex-end;
                        }
            ",
        ) );
        
        
         $this->addStyleControl(
           array(
                    "property" => 'font-size',
                    //"selector" => $item_selector,
                )
        );
        
        
        $this->addOptionControl(
           array(
                "type" => 'slider-measurebox',
                "name" => __('Sub menu slide duration'),
                "slug" 	    => "duration",
                "default" => "300",
            )
        )
        ->setUnits('ms','ms')
        ->setRange(0, 1000, 1)
        ->rebuildElementOnChange()
        ->whiteList();
        
        
        
         /**
         * Icons
         */
        
        $icon_section = $this->addControlSection("icon_section", __("Icons"), "assets/icon.png", $this);
        
        $icon_choose = $icon_section->addControlSection("icon_choose", __("Change Icons"), "assets/icon.png", $this);
        
        $icon_selector = ".oxy-slide-menu_dropdown-icon-click-area > svg";
        
        $icon_click_area = '.oxy-slide-menu_dropdown-icon-click-area';
        
        $icon_choose->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'icon',
                "default" => 'Lineariconsicon-chevron-down'
            )
        )->rebuildElementOnChange();
        
        /**
         * Add SVG icon
         */ 
        $this->El->inlineJS(
            "jQuery('#%%ELEMENT_ID%% .oxy-slide-menu_list .menu-item-has-children > a', 'body').each(function(){
                jQuery(this).append('<button aria-expanded=\"false\" aria-pressed=\"false\" class=\"oxy-slide-menu_dropdown-icon-click-area\"><svg class=\"oxy-slide-menu_dropdown-icon\"><use xlink:href=\"#%%icon%%\"></use></svg><span class=\"screen-reader-text\">Submenu</span></button>');
            });
            "
        );
        
        $icon_section->addStyleControls(
            array(
                array(
                    "name" => __('Icon Size'),
                    "selector" => $icon_selector,
                    "property" => 'font-size',
                   
                ),
            )
        );
        
        
        /**
         * Icon Colors
         */ 
        $icon_colors = $icon_section->addControlSection("icon_colors", __("Colors"), "assets/icon.png", $this);
        
        $icon_colors->addStyleControls(
            array(
                array(
                    "name" => __('Color'),
                    "selector" => $icon_click_area,
                    "property" => 'color',
                  
                ),
                 array(
                    "name" => __('Hover Color'),
                    "selector" => $icon_click_area.":hover",
                    "property" => 'color',
                  
                ),
                 array(
                    "name" => __('Focus Color'),
                    "selector" => $icon_click_area.":focus",
                    "property" => 'color',
                  
                ),
                array(
                    "name" => __('Background Color'),
                    "selector" => $icon_click_area,
                    "property" => 'background-color',
                  
                ),
                array(
                    "name" => __('Hover Background Color'),
                    "selector" => $icon_click_area.":hover",
                    "property" => 'background-color',
                  
                ),
                array(
                    "name" => __('Focus Background Color'),
                    "selector" => $icon_click_area.":focus",
                    "property" => 'background-color',
                  
                ),
            )
        );
        
        $icon_colors->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => __('Focus Outline'),
                "slug" => 'icon_focus',
                "value" => 'true'
            )
        )->setValueCSS( array(
            "false" => " .oxy-slide-menu_dropdown-icon-click-area:focus {
                outline: none;
            }",
        ) );
        
        
        /**
         * Icon Spacing
         */ 
        $icon_spacing = $icon_section->addControlSection("icon_spacing", __("Icon Spacing"), "assets/icon.png", $this);
        
        $icon_section->borderSection('Borders', $icon_click_area,$this);

        $icon_spacing->addPreset( 
            "margin",
            "dropdown_icon_item_margin",
            __("Icon Margin"),
            $icon_click_area
        )->whiteList();
        
        $icon_spacing->addPreset(
            "padding",
            "dropdown_icon_item_padding",
            __("Icon Padding"),
            $icon_click_area
        )->whiteList();
        
        $icon_section->addStyleControl(
            array(
                "name" => __('Rotation'),
                "selector" => $icon_selector,
                "property" => 'transform:rotate',
                "control_type" => 'slider-measurebox',
            )
        )
        ->setUnits('deg','deg')
        ->setRange('-180','180');

        $icon_section->addStyleControl(
            array(
                "name" => __('Rotation When Open'),
                "selector" => ".oxy-slide-menu_dropdown-icon-click-area.oxy-slide-menu_open > svg",
                "property" => 'transform:rotate',
                "control_type" => 'slider-measurebox',
                "default" => "180"
                //"condition" => 'show_dropdown_icon=true',
            )
        )
        ->setUnits('deg','deg')
        ->setRange('-180','180');
        
        $icon_section->addStyleControl(
            array(
                "type" => 'measurebox',
                "selector" => $icon_selector,
                "name" => __('Rotate Duration'),
                //"default" => "0",
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
            )
        )
        ->setUnits('s','s')
        ->setRange('0','1','0.1');
        
        
        
        
        /**
         * Menu Items
         */
        
        $styles_section = $this->addControlSection("extra_slide_menu_styles_section", __("Menu Items"), "assets/icon.png", $this);
        
        
        
        
        $styles_section->addStyleControls(
            array(
                array(
                    "name" => __('Color'),
                    "property" => 'color',
                    "selector" => $item_selector,
                    "default" => 'inherit'
                ),
                array(
                    "name" => __('Hover Color'),
                    "property" => 'color',
                    "selector" => $item_selector.":hover",
                ),
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "selector" => $item_selector,
                    "default" => 'inherit'
                ),
                array(
                    "name" => __('Background Hover Color'),
                    "property" => 'background-color',
                    "selector" => $item_selector.":hover",
                )
            )
        );
        
        $styles_section->addStyleControl(
            array(
                "type" => 'measurebox',
                "selector" => $item_selector,
                "name" => __('Hover Transition Duration'),
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
            )
        )
        ->setUnits('s','s')
        ->setRange('0','1','0.1');
        
        
        
        $styles_section->borderSection('Borders', $item_selector,$this);
        $styles_section->typographySection('Typography', '',$this);
        
        
        
        /**
         * Menu Item Spacing
         */
        
        $item_spacing = $styles_section->addControlSection("extra_slide_menu_item_spacing", __("Spacing"), "assets/icon.png", $this);
        
        $item_spacing->addPreset(
            "padding",
            "extra_menu_link_padding",
            __("Padding"),
            $item_selector
        )->whiteList();
        
        $item_spacing->addPreset(
            "margin",
            "extra_menu_link_margin",
            __("Margin"),
            $item_selector
        )->whiteList();
        
        
       /**
         * SubMenus 
         */
        
        $submenu_section = $this->addControlSection("extra_slide_menu_submenu_section", __("Sub Menus"), "assets/icon.png", $this);
        
        $submenu_selector = ".sub-menu";
        
        $submenu_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "selector" => $submenu_selector
                )
            )
        );
        
        
        $submenu_section->typographySection('Typography', $submenu_selector,$this);
        
        $submenu_spacing = $submenu_section->addControlSection("extra_slide_menu_submenu_spacing", __("Spacing"), "assets/icon.png", $this);
        
        $submenu_spacing->addPreset(
            "padding",
            "extra_slide_menu_submenu_padding",
            __("Padding"),
            $submenu_selector
        )->whiteList();
        
        $submenu_spacing->addPreset(
            "margin",
            "extra_slide_menu_submenu_margin",
            __("Margin"),
            $submenu_selector
        )->whiteList();
        
        /**
         * Schema Markup 
         */
        $this->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => __('Add Schema markup to menu','oxygen'),
                "slug" => 'schema_markup'
            )
        );
        
        
    }
    
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-slide-menu {
                    width: 100%;
                }
        
                .oxy-slide-menu .menu-item a {
                    color: inherit;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                
                .oxy-slide-menu .menu-item {
                    list-style-type: none;
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                }
                
                .oxy-slide-menu_dropdown-icon-click-area {
                    background: none;
                    cursor: pointer;
                    color: inherit;
                    border: none;
                    padding: 0;
                }
                
                .oxy-slide-menu_dropdown-icon-click-area.oxy-slide-menu_open > svg {
                    transform: rotate(180deg);
                }
                
                .oxy-slide-menu_list {
                    padding: 0;
                }

                .oxy-slide-menu .sub-menu {
                    display: none;
                    flex-direction: column;
                    padding: 0;
                }
        
                .oxy-slide-menu_dropdown-icon {
                    height: 1em;
                    fill: currentColor;
                    width: 1em;
                }
                
                .oxy-slide-menu_dropdown-icon-click-area:first-of-type:nth-last-of-type(2) {
                    display: none;
                }
                
                .oxy-slide-menu .screen-reader-text {
                    clip: rect(1px,1px,1px,1px);
                    height: 1px;
                    overflow: hidden;
                    position: absolute!important;
                    width: 1px;
                    word-wrap: normal!important;
                }";

            return $css;
        
    }
    
    /**
     * Output js inline in footer once.
     */
    function output_js() { ?>
            
            <script type="text/javascript">   
                
            jQuery(document).ready(oxygen_init_slide_menu);
            function oxygen_init_slide_menu($) {
                
                // check if supports touch, otherwise it's click:
                let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click';  
                  
                    $('.oxy-slide-menu').each(function(){
                        
                          let slide_menu = $(this);
                          let slide_start = slide_menu.children( '.oxy-slide-menu_inner' ).data( 'start' );
                          let slide_duration = slide_menu.children( '.oxy-slide-menu_inner' ).data( 'duration' );
                          let slideClickArea = '.menu-item-has-children > a > .oxy-slide-menu_dropdown-icon-click-area';
                         
                         // If being hidden as starting position, for use as mobile menu
                          if ( slide_start == 'hidden' ) {

                              let slide_trigger_selector = $( slide_menu.children( '.oxy-slide-menu_inner' ).data( 'trigger-selector' ) );

                              //slide_trigger_selector.click( function( event ) {
                              slide_trigger_selector.on( touchEvent, function(e) {      
                                 slide_menu.slideToggle(slide_duration);
                              } );

                          }

                    });    
                
                
                    // Sub menu icon being clicked
                     $('body').on( touchEvent, '.menu-item-has-children > a > .oxy-slide-menu_dropdown-icon-click-area',  function(e) {           
                            e.preventDefault();
                                oxy_slide_menu_toggle(this);
                            }

                        );

                    function oxy_slide_menu_toggle(trigger) {
                                    
                                var durationData = $(trigger).closest('.oxy-slide-menu_inner').data( 'duration' );
                                var othermenus = $(trigger).closest( '.menu-item-has-children' ).siblings('.menu-item-has-children');
                                                 othermenus.find( '.sub-menu' ).slideUp( durationData );
                                                 othermenus.find( '.oxy-slide-menu_open' ).removeClass( 'oxy-slide-menu_open' );
                                                 othermenus.find( '.oxy-slide-menu_open' ).attr('aria-expanded', function (i, attr) {
                                                        return attr == 'true' ? 'false' : 'true'
                                                    });
                                                othermenus.find( '.oxy-slide-menu_open' ).attr('aria-pressed', function (i, attr) {
                                                    return attr == 'true' ? 'false' : 'true'
                                                });

                                $(trigger).closest('.menu-item-has-children').children('.sub-menu').slideToggle( durationData );

                                $(trigger).attr('aria-expanded', function (i, attr) {
                                    return attr == 'true' ? 'false' : 'true'
                                });
                                
                                $(trigger).attr('aria-pressed', function (i, attr) {
                                    return attr == 'true' ? 'false' : 'true'
                                });
                                
                                $(trigger).toggleClass('oxy-slide-menu_open');

                            }        
                
                
                
                    let selector = '.oxy-slide-menu .menu-item a[href*="#"]';
                    $('body').on(touchEvent, selector, function(event){
                        if ($(event.target).closest('.oxy-slide-menu_dropdown-icon-click-area').length > 0) {
                            // toggle icon clicked, no need to trigger it 
                            return;
                        }
                        else if ($(this).attr("href") === "#" && $(this).parent().hasClass('menu-item-has-children')) {
                            // prevent browser folllowing link
                            event.preventDefault();
                            // empty href don't lead anywhere, use it as toggle icon click area
                            var hasklinkIcon = $(this).find('.oxy-slide-menu_dropdown-icon-click-area');
                            oxy_slide_menu_toggle(hasklinkIcon);
                            
                        }
                    });

             };
            
        </script>

    <?php }
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }

}

new ExtraslideMenu();