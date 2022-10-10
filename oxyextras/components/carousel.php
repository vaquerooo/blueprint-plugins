<?php

class ExtraCarousel extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Carousel Builder'); 
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
        
        $this->El->useAJAXControls();
        $this->enableNesting();
        
    }
    
    
    function extras_button_place() {
        return "interactive";
    }
    
    
    function render($options, $defaults, $content) { 
        
        // get options
        $nav_type = isset( $options['nav_type'] ) ? esc_attr($options['nav_type']) : "";
        $prev_icon  = isset( $options['prev_icon'] ) ? esc_attr($options['prev_icon']) : "";
        $next_icon  = isset( $options['next_icon'] ) ? esc_attr($options['next_icon']) : "";
        
        $contain = isset( $options['contain'] ) ? esc_attr($options['contain']) : "";
        $percentage_position = isset( $options['percentage_position'] ) ? esc_attr($options['percentage_position']) : "";
        $free_scroll = isset( $options['free_scroll'] ) ? esc_attr($options['free_scroll']) : "";
        $draggable = isset( $options['draggable'] ) ? esc_attr($options['draggable']) : "";
        $wrap_around = isset( $options['wrap_around'] ) ? esc_attr($options['wrap_around']) : "";
        //$maybe_group_cells = isset( $options['maybe_group_cells'] ) ? esc_attr($options['maybe_group_cells']) : "";
        $group_cells = isset( $options['group_cells'] ) ? esc_attr($options['group_cells']) : "1";
        //$maybe_autoplay = isset( $options['maybe_autoplay'] ) ? esc_attr($options['maybe_autoplay']) : "";
        $autoplay = isset( $options['autoplay'] ) ? esc_attr($options['autoplay']) : "";
        $initial_index = isset( $options['initial_index'] ) ? esc_attr($options['initial_index']) : "";
        $maybe_accessibility = isset( $options['maybe_accessibility'] ) ? esc_attr($options['maybe_accessibility']) : "";
        $cell_align = isset( $options['cell_align'] ) ? esc_attr($options['cell_align']) : "";
        $right_to_left = isset( $options['right_to_left'] ) ? esc_attr($options['right_to_left']) : "";
        $page_dots = isset( $options['page_dots'] ) ? esc_attr($options['page_dots']) : "";
        
        $as_nav_for = isset( $options['as_nav_for'] ) ? esc_attr($options['as_nav_for']) : "";
        $click_to_select = isset( $options['click_to_select'] ) ? esc_attr($options['click_to_select']) : "false";
        
        $parallax_bg = isset( $options['parallax_bg'] ) ? esc_attr($options['parallax_bg']) : "false";
        $parallax_bg_control = isset( $options['parallax_bg_control'] ) ? esc_attr($options['parallax_bg_control']) : "5";
        
        
        $drag_threshold = isset( $options['drag_threshold'] ) ? esc_attr($options['drag_threshold']) : "";
        $selected_attraction = isset( $options['selected_attraction'] ) ? esc_attr($options['selected_attraction']) : "";
        $friction = isset( $options['friction'] ) ? esc_attr($options['friction']) : "";
        $free_scroll_friction = isset( $options['free_scroll_friction'] ) ? esc_attr($options['free_scroll_friction']) : "";
        
        
        if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'repeater' ) {
            $carousel_selector = '.oxy-dynamic-list';
        } 
        else if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'woo' ) {
            $carousel_selector = 'ul.products';
        }
        else if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'easy_posts' ) {
            $carousel_selector = '.oxy-posts';
        }
        else {                
            //$carousel_selector = isset( $options['carousel_selector'] ) ? esc_attr($options['carousel_selector']) : "";
            $carousel_selector = '.oxy-inner-content';
        }
        
        
        if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'woo' ) {
            $cell_selector = '.product';
        } 
        else if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'easy_posts' ) {
            $cell_selector = '.oxy-post';
        }
        else if ( isset( $options['carousel_type'] ) && esc_attr($options['carousel_type'])  === 'custom' ) {
            $cell_selector = '.cell';
        }
        else {
             $cell_selector = isset( $options['cell_selector'] ) ? esc_attr($options['cell_selector']) : "";
        }
        
        
        
        
        if ( isset( $options['nav_type'] ) && esc_attr($options['nav_type'])  === 'custom' ) {
            $previous_selector = isset( $options['previous_selector'] ) ? esc_attr($options['previous_selector']) : "";
            $next_selector = isset( $options['next_selector'] ) ? esc_attr($options['next_selector']) : "";
        } else {
            $previous_selector = '#'. esc_attr($options['selector']) . ' .oxy-carousel-builder_prev';
            $next_selector = '#'. esc_attr($options['selector']) . ' .oxy-carousel-builder_next';  
        }
        
        
        global $oxygen_svg_icons_to_load;
        $oxygen_svg_icons_to_load[] = $next_icon;
        $oxygen_svg_icons_to_load[] = $prev_icon;
        
        $output = '';
        
                
        $output .= '<div class="oxy-inner-content" ';
        
        $output .= 'data-prev="' . $previous_selector . '" ';
        $output .= 'data-next="' . $next_selector . '" ';
        $output .= 'data-contain="' . $contain . '" ';
        $output .= 'data-percent="' . $percentage_position . '" ';
        $output .= 'data-freescroll="' . $free_scroll . '" ';
        $output .= 'data-draggable="' . $draggable . '" ';
        $output .= 'data-wraparound="' . $wrap_around . '" ';
        $output .= 'data-carousel="' . $carousel_selector . '" ';
        $output .= 'data-cell="' . $cell_selector . '" ';
        
        $output .= 'data-dragthreshold="' . $drag_threshold . '" ';
        $output .= 'data-selectedattraction="' . $selected_attraction . '" ';
        $output .= 'data-friction="' . $friction . '" ';
        $output .= 'data-freescrollfriction="' . $free_scroll_friction . '" ';
        
        
            
        if (isset( $options['parallax_bg'] ) && esc_attr($options['parallax_bg']) === 'false') {    
            // No grouping if we have parallax on
            $output .= 'data-groupcells="' . $group_cells . '" ';
            
        }
        
        $output .= 'data-autoplay="' . $autoplay . '" ';
        
        $output .= 'data-initial="' . $initial_index . '" ';
        $output .= 'data-accessibility="' . $maybe_accessibility . '" ';
        $output .= 'data-cellalign="' . $cell_align . '" ';
        $output .= 'data-righttoleft="' . $right_to_left . '" ';
        $output .= 'data-pagedots="' . $page_dots . '" ';
        
        $output .= 'data-clickselect="' . $click_to_select . '" ';   
        
        
        if (isset( $options['wrap_around'] ) && esc_attr($options['wrap_around']) === 'false') {
            // No parallax if wrap around is on.
            $output .= 'data-parallaxbg="' . $parallax_bg . '" ';
            $output .= 'data-bgspeed="' . $parallax_bg_control . '" ';
        
        }
        
        
            
        if (isset( $options['editor_mode'] ) && esc_attr($options['editor_mode']) === 'preview') {
            
            $output .= 'data-preview="true" ';
            
        }
        
        if ( isset( $options['maybe_asnavfor'] ) && esc_attr($options['maybe_asnavfor'])  === 'true' ) {
            
            $output .= 'data-asnavfor="' . $as_nav_for . '" ';
            
        }
        
        if ( isset( $options['adaptive_height'] ) && esc_attr($options['adaptive_height'])  === 'true' ) {
            
            $output .= 'data-adaptheight="true" ';
            
        }
        
        
        
        
        $output .= '>';
        
        if ($content) {
            
            $output .= do_shortcode($content); 
            
        } 
           
        $output .= '</div>';
        
        if ('icon' === $nav_type) {
            
            $output .= '<div class="oxy-carousel-builder_icon oxy-carousel-builder_prev"><svg id="prev' . esc_attr($options['selector']) . '"><use xlink:href="#' . $prev_icon .'"></use></svg></span></div>';
            $output .= '<div class="oxy-carousel-builder_icon oxy-carousel-builder_next"><svg id="next' . esc_attr($options['selector']) . '"><use xlink:href="#' . $next_icon .'"></use></svg></span></div>';
            
        }
        
        echo $output;
        
        
        $inline = file_get_contents( plugin_dir_path(__FILE__) . 'assets/flickity/flickity.js' );
                                                        
        if( method_exists('OxygenElement', 'builderInlineJS') ) {
            
            $this->El->builderInlineJS($inline); 
            
        }
        
        
        if ($this->js_added !== true) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            $this->js_added = true;
        }  
        
    }
    

    function class_names() {
        return array('');
    }

    function controls() {
        
        /**
         * Selectors
         */ 
        $repeater_div = '.oxy-dynamic-list > .ct-div-block, .oxy-dynamic-list .flickity-slider > .ct-div-block';
        $product_list_div = 'ul.products .product, ul.products .flickity-slider > .product';
        $easy_posts_div = '.oxy-posts .oxy-post';
        $cell_div = '.cell';
        $dots_selector = '.flickity-page-dots';
        $dot_selector = '.flickity-page-dots .dot';
        $dot_selected_selector = '.flickity-page-dots .dot.is-selected';
        $cell_selected = '.is-selected';
        $cell_previous = '.is-previous';
        $cell_next = '.is-next';
        
      
        
        
        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'In-Editor Mode',
                'slug' => 'editor_mode',
            )
            
        )->setValue(array( 
            "edit" => "Edit",
            "preview" => "Preview",
            )
        )->setDefaultValue('edit')
        ->setParam("description", __("Always click 'Apply Params' button to apply"));
        
        
        $this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => 'Carousel Type',
                'slug' => 'carousel_type',
            )
            
        )->setValue(array( 
            "repeater" => "Repeater", 
            "easy_posts" => "Easy posts",
            "woo" => "Woo components",
            //"ACF Gallery",
            "custom" => "Custom elements (.cell)",
            )
        )->setValueCSS( array(
            "woo"  => "",
            "custom" => "
                        .oxy-inner-content {
                            display: flex;
                            flex-direction: row;
                            flex-wrap: nowrap;
                        }
                        .cell {
                            flex-shrink: 0;
                        }
            ",
                
        ) );
        
        
        /*
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Carousel Selector'),
                "slug" => 'carousel_selector',
                "default" => '.oxy-carousel-builder',
                "condition" => 'carousel_type=custom'
            )
        ); */
        
        
        
        /**
         * Cells
         */ 
        $cells_section = $this->addControlSection("cells_section", __("Cells"), "assets/icon.png", $this);
        
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell width',
                "type" => 'measurebox',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $repeater_div,
                "condition" => 'editor_mode!=preview&&carousel_type=repeater'
                
            )
        )
        ->setUnits('%')
        ->setRange('0','100','.1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell width',
                "type" => 'measurebox',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $product_list_div,
                "condition" => 'editor_mode!=preview&&carousel_type=woo'
                
            )
        )
        ->setUnits('%')
        ->setRange('0','100','.1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell width',
                "type" => 'measurebox',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $cell_div,
                "condition" => 'editor_mode!=preview&&carousel_type=custom'
                
            )
        )
        ->setUnits('%')
        ->setRange('0','100','.1');
        
        
        
            
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell width',
                "type" => 'measurebox',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $easy_posts_div,
                "condition" => 'editor_mode!=preview&&carousel_type=easy_posts'
                
            )
        )
        ->setUnits('%')
        ->setRange('0','100','.1');    
        
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell height',
                "type" => 'measurebox',
                "default" => "300",
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => '.oxy-dynamic-list > .ct-div-block, .oxy-dynamic-list .flickity-slider > .ct-div-block, .oxy-inner-content .oxy-dynamic-list',
                "condition" => 'editor_mode!=preview&&carousel_type=repeater'
            )
        )
        ->setUnits('px')    
        ->setRange('0','1000','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell height',
                "type" => 'measurebox',
                "default" => "300",
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => '.products > .product, .products .flickity-slider > .product, .oxy-inner-content .products',
                "condition" => 'editor_mode!=preview&&carousel_type=woo'
            )
        )
        ->setUnits('px')    
        ->setRange('0','1000','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell height',
                "type" => 'measurebox',
                "default" => "300",
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => '.oxy-posts > .oxy-post, .oxy-posts .flickity-slider > .oxy-post, .oxy-inner-content .oxy-posts',
                "condition" => 'editor_mode!=preview&&carousel_type=easy_posts'
            )
        )
        ->setUnits('px')    
        ->setRange('0','1000','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Cell height',
                "type" => 'measurebox',
                "default" => "300",
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => '.cell, .oxy-inner-content .flickity-slider > .cell, .oxy-inner-content',
                "condition" => 'editor_mode!=preview&&carousel_type=custom'
            )
        )
        ->setUnits('px')    
        ->setRange('0','1000','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Space between cells',
                "type" => 'measurebox',
                "default" => "0",
                "units" => 'px',
                "property" => 'margin-right',
                "control_type" => 'slider-measurebox',
                "selector" => $repeater_div,
                "condition" => 'editor_mode!=preview&&carousel_type=repeater'
            )
        )
        ->setUnits('px')
        ->setRange('0','100','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Space between cells',
                "type" => 'measurebox',
                "default" => "0",
                "units" => 'px',
                "property" => 'margin-right',
                "control_type" => 'slider-measurebox',
                "selector" => $product_list_div,
                "condition" => 'editor_mode!=preview&&carousel_type=woo'
            )
        )
        ->setUnits('px')
        ->setRange('0','100','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Space between cells',
                "type" => 'measurebox',
                "default" => "0",
                "units" => 'px',
                "property" => 'margin-right',
                "control_type" => 'slider-measurebox',
                "selector" => $easy_posts_div,
                "condition" => 'editor_mode!=preview&&carousel_type=easy_posts'
            )
        )
        ->setUnits('px')
        ->setRange('0','100','1');
        
        $cells_section->addStyleControl( 
            array(
                "name" => 'Space between cells',
                "type" => 'measurebox',
                "default" => "0",
                "units" => 'px',
                "property" => 'margin-right',
                "control_type" => 'slider-measurebox',
                "selector" => $cell_div,
                "condition" => 'editor_mode!=preview&&carousel_type=custom'
            )
        )
        ->setUnits('px')
        ->setRange('0','100','1');
        
    
        $cells_section->addStyleControl(
            array(
                "name" => __('Cell Transition'),
                "property" => 'transition-duration',
                "selector" => $repeater_div,
                "control_type" => 'slider-measurebox',
                "default" => '400',
                "condition" => 'editor_mode!=preview&&carousel_type=repeater'
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $cells_section->addStyleControl(
            array(
                "name" => __('Cell Transition'),
                "property" => 'transition-duration',
                "selector" => $product_list_div,
                "control_type" => 'slider-measurebox',
                "default" => '400',
                "condition" => 'editor_mode!=preview&&carousel_type=woo'
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $cells_section->addStyleControl(
            array(
                "name" => __('Cell Transition'),
                "property" => 'transition-duration',
                "selector" => $easy_posts_div,
                "control_type" => 'slider-measurebox',
                "default" => '400',
                "condition" => 'editor_mode!=preview&&carousel_type=easy_posts'
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $cells_section->addStyleControl(
            array(
                "name" => __('Cell Transition'),
                "property" => 'transition-duration',
                "selector" => $cell_div,
                "control_type" => 'slider-measurebox',
                "default" => '400',
                "condition" => 'editor_mode!=preview&&carousel_type=custom'
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $cells_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Cell Selector'),
                "slug" => 'cell_selector',
                "default" => '.ct-div-block',
                "condition" => 'carousel_type!=woo&&carousel_type!=custom&&carousel_type!=easy_posts'
            )
        );
        
        /**
         * Selected Styles
         */ 
        $cell_selected_section = $cells_section->addControlSection("cell_selected_section", __("Selected Cells"), "assets/icon.png", $this);
        
        $cell_selected_section->addCustomControl(
            '<div style="color: #fff; line-height: 1.3; font-size: 13px;">These changes will only be seen inside Oxygen in preview mode</div>','description');
        
        
        $cell_selected_section->addStyleControls(
            array(
                array(
                    "name" => __('Selected Opacity'),
                    "property" => 'opacity',
                    "selector" => $cell_selected,
                ),
                array(
                    "name" => __('Selected Background Color'),
                    "property" => 'background-color',
                    "selector" => $cell_selected,
                ),
                array(
                    "name" => __('Selected Text Color'),
                    "property" => 'color',
                    "selector" => $cell_selected,
                ),
            )
        );
        
        
         $cell_selected_section->addStyleControl(
            array(
                "name" => __('Selected Scale'),
                "selector" => $cell_selected,
                "property" => '--cell-selected-scale',
                "control_type" => 'slider-measurebox',
                "default" => '1',
                )
            )
            ->setRange('0','2', '.02');
        
        
        $cell_selected_section->addStyleControl(
            array(
                "name" => __('Selected Rotate'),
                "selector" => $cell_selected,
                "property" => '--cell-selected-rotate',
                "control_type" => 'slider-measurebox',
                "default" => '0',
                )
            )
            ->setUnits('deg','deg')
            ->setRange('-180','180');
        
        /**
         * Selected Styles
         */ 
        $cell_prev_next_section = $cells_section->addControlSection("cell_prev_next_section", __("Prev/Next Cells"), "assets/icon.png", $this);
        
        $cell_prev_next_section->addCustomControl(
            '<div style="color: #fff; line-height: 1.3; font-size: 13px;">These changes will only be seen inside Oxygen in preview mode</div>','description');
        
        
        $cell_prev_next_section->addStyleControl(
            array(
                "name" => __('Prev Scale'),
                "selector" => $cell_previous,
                "property" => '--cell-prev-scale',
                "control_type" => 'slider-measurebox',
                "default" => '1',
                )
            )
            ->setRange('0','2', '.02');
        
        $cell_prev_next_section->addStyleControl(
            array(
                "name" => __('Prev Rotate'),
                "selector" => $cell_previous,
                "property" => '--cell-prev-rotate',
                "control_type" => 'slider-measurebox',
                "default" => '0',
                )
            )
            ->setUnits('deg','deg')
            ->setRange('-180','180');
        
        $cell_prev_next_section->addStyleControls(
            array(
                array(
                    "name" => __('Previous Opacity'),
                    "property" => 'opacity',
                    "selector" => $cell_previous,
                ),
            )
        );
        
        $cell_prev_next_section->addStyleControl(
            array(
                "name" => __('Next Scale'),
                "selector" => $cell_next,
                "property" => '--cell-next-scale',
                "control_type" => 'slider-measurebox',
                "default" => '1',
                )
            )
            ->setRange('0','2', '.02');
        
        
        
        $cell_prev_next_section->addStyleControl(
            array(
                "name" => __('Next Rotate'),
                "selector" => $cell_next,
                "property" => '--cell-next-rotate',
                "control_type" => 'slider-measurebox',
                "default" => '0',
                )
            )
            ->setUnits('deg','deg')
            ->setRange('-180','180');
        
        
        
        $cell_prev_next_section->addStyleControls(
            array(
                array(
                    "name" => __('Next Opacity'),
                    "property" => 'opacity',
                    "selector" => $cell_next,
                ),
            )
        );
            
            
        
        /**
         * Config
         */ 
        $config_section = $this->addControlSection("config_section", __("Carousel"), "assets/icon.png", $this);
        
            
        $config_section->addCustomControl(
            '<div style="color: #fff; line-height: 1.3; font-size: 13px;">These changes will only be seen inside Oxygen in preview mode and by clicking Apply Params</div>','description');
        
        
        $config_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Group cells'),
                "slug" => 'group_cells',
                "default" => '1',
            )
        )->setRange('1','10','1');
        
        
        
        $config_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Initial cell selected'),
                "slug" => 'initial_index',
                "default" => '1',
            )
        )->setRange('1','10','1');
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Cell align',
                'slug' => 'cell_align'
            )
            
        )->setValue(array( 
            "left" => "Left", 
            "center" => "Center",
            "right" => "Right" 
            )           
        )->setDefaultValue('center');
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Overflow',
                'slug' => 'viewport_overflow'
            )
            
        )->setValue(array( 
            "visible" => "Visible", 
            "hidden" => "Hidden" 
            )
        )
         ->setDefaultValue('hidden')
         ->setValueCSS( array(
            "visible"  => " .flickity-viewport {
                                overflow: unset;
                            }",
             "hidden"  => " .oxy_dynamic_list:not(.flickity-enabled) {
                                overflow-x: hidden;
                            }
                            
                            .oxy_dynamic_list.flickity-enabled .flickity-viewport {
                                overflow-x: hidden;
                            }
                            
                            ul.products:not(.flickity-enabled) {
                                overflow-x: hidden;
                            }
                            
                            ul.products.flickity-enabled .flickity-viewport {
                                overflow-x: hidden;
                            }
                            
                            ul.products:not(.flickity-enabled) {
                                overflow-x: hidden;
                            }
                            
                            ul.products.flickity-enabled .flickity-viewport {
                                overflow-x: hidden;
                            }
                            
                            .oxy-posts:not(.flickity-enabled) {
                                overflow-x: hidden;
                            }
                            
                            .oxy-posts.flickity-enabled .flickity-viewport {
                                overflow-x: hidden;
                            }",
                
        ) );
        
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Adaptive height'),
                'slug' => 'adaptive_height'
            )
            
        )->setDefaultValue('false')
        ->setValue(array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        );  
        
        $config_section->addStyleControl(
            array(
                "name" => __('Height transition duration'),
                "property" => 'transition-duration',
                "selector" => '.flickity-viewport',
                "control_type" => 'slider-measurebox',
                "condition" => 'adaptive_height=true',
                "default" => '0',
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Wrap around (infinite cells)'),
                'slug' => 'wrap_around'
            )
            
        )->setDefaultValue('false')
        ->setValue(array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        );  
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Contain (no spaces left at start/end)',
                'slug' => 'contain',
                'condition' => 'wrap_around=false'
            )
            
        )->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable" 
            )
        );
        
        
        /**
         * Config / Behaviour
         */ 
        $config_behaviour_section = $config_section->addControlSection("config_behaviour_section", __("Behaviour"), "assets/icon.png", $this);
        
            
        
        $config_behaviour_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Click cell to select'),
                'slug' => 'click_to_select'
            )
            
        )->setDefaultValue('false')
        ->setValue(array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        );  
        
        
        
        
        $config_behaviour_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Autoplay timing (0 = off)'),
                "slug" => 'autoplay',
                "value" => '0',
            )
        )->setRange('0','4000','10')
        ->setUnits('ms','ms');    
        
        
        $config_behaviour_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Carousel is draggable',
                'slug' => 'draggable'
            )
            
        )->setDefaultValue('true')
            ->setValue(array( 
             "true" => "Enable", 
             "false" => "Disable"
            )
        );   
        
        
        $config_behaviour_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Free scrolling (no snapping to each cell)',
                'slug' => 'free_scroll'
            )
            
        )
        ->setDefaultValue('false')    
        ->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable" 
            )
        );
        
        
        
        
        $config_behaviour_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Use as navigation for another carousel',
                'slug' => 'maybe_asnavfor'
            )
            
        )
        ->setDefaultValue('false')    
        ->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable" 
            )
        )->setValueCSS( array(
            "true"  => " .flickity-slider > .ct-div-block {
                            cursor: pointer;
                        }
                        ",
        ) );
        
        $config_behaviour_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Other carousel builder selector'),
                "slug" => 'as_nav_for',
                "default" => '#main-carousel-builder',
                "condition" => 'maybe_asnavfor=true'
            )
        );
            
          
        
        
        
        $config_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Enable carousel at this break point (& below)',
                'slug' => 'watch_css'
            )
            
        )->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable"
            )
        )
        ->setDefaultValue('true')
        ->setValueCSS( array(
            "false"  => " .oxy-dynamic-list::after {
                                content: none;
                            }
                            
                            ul.products::after {
                                content: none;
                            }
                            
                            .oxy-posts::after {
                                content: none;
                            }
                            
                            .oxy-inner-content::after {
                                content: none;
                            }
                            
                            ul.products {
                                flex-wrap: wrap;
                            }
                            
                            .flickity-page-dots,
                            .oxy-carousel-builder_icon {
                                display: none;
                            }",
            
            "true"  => " .oxy-dynamic-list::after {
                                content: 'flickity';
                            }
                            
                            ul.products::after {
                                content: 'flickity';
                            }
                            
                            .oxy-posts::after {
                                content: 'flickity';
                            }
                            
                            .oxy-inner-content::after {
                                content: 'flickity';
                            }
                            
                            ul.products {
                                flex-wrap: nowrap;
                            }
                            
                            .flickity-page-dots,
                            .oxy-carousel-builder_icon{
                                display: inline-flex;
                            }",
        ) )->whiteList();
        
        
        
        
        
        
        
        /**
         * Advanced
         */ 
        $config_other_section = $config_section->addControlSection("config_other_section", __("Other"), "assets/icon.png", $this);
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Percentage Position',
                'slug' => 'percentage_position'
            )
            
        )->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable" 
            )           
        )->setDefaultValue('true')->setParam("description", __("Disable only if not using % for cell widths"));
        
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Keyboard navigation (accessibility)',
                'slug' => 'maybe_accessibility'
            )
            
        )
        ->setDefaultValue('true')    
        ->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable" 
            )
        );  
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Right to left'),
                'slug' => 'right_to_left'
            )
            
        )->setDefaultValue('false')
        ->setValue(array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        );  
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Woo results count'),
                'slug' => 'woo_results_count',
                'condition' => 'carousel_type=woo'
            )
            
        )->setDefaultValue('disable')
        ->setValue(array( 
             "enable" => "Show", 
            "disable" => "Hide"
            )
        )->setValueCSS( array(
            "enable"  => " .woocommerce-result-count {
                            display: block;
                        }
                        ",
        ) );
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Woo sorting dropdown'),
                'slug' => 'woo_sorting',
                'condition' => 'carousel_type=woo'
            )
            
        )->setDefaultValue('disable')
        ->setValue(array( 
             "enable" => "Show", 
            "disable" => "Hide"
            )
        )->setValueCSS( array(
            "enable"  => " .woocommerce-ordering {
                            display: block;
                        }
                        ",
        ) );  
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Woo cart buttons'),
                'slug' => 'woo_cart_buttons',
                'condition' => 'carousel_type=woo'
            )
            
        )->setDefaultValue('display')
        ->setValue(array(  
            "display" => "Show",
            "hide" => "Hide"
            )
        )->setValueCSS( array(
            "hide"  => ".product .add_to_cart_button {
                            display: none;
                        }
                        
                        .product .added_to_cart {
                            display: none;
                        }
                        
                        .product .button {
                            display: none;
                        }",
        ) );  
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Woo prices'),
                'slug' => 'woo_price',
                'condition' => 'carousel_type=woo'
            )
            
        )->setDefaultValue('display')
        ->setValue(array( 
            "display" => "Show",
            "hide" => "Hide"
            )
        )->setValueCSS( array(
            "hide"  => ".product .price {
                            display: none;
                        }
                        ",
        ) );  
        
        
        $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Woo product title'),
                'slug' => 'woo_title',
                'condition' => 'carousel_type=woo'
            )
            
        )->setDefaultValue('display')
        ->setValue(array( 
            "display" => "Show",
            "hide" => "Hide"
            )
        )->setValueCSS( array(
            "hide"  => ".product .woocommerce-loop-product__title {
                            display: none;
                        }
                        ",
        ) );  
        
        
         $config_other_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Parallax Elements',
                'slug' => 'parallax_bg',
                'condition' => 'wrap_around=false&&carousel_type!=woo&&carousel_type!=custom'
            )
            
        )->setDefaultValue('false')
        ->setValue(array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        );  
        
       /* $config_other_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Default speed (lower = subtle)'),
                "slug" => 'parallax_bg_control',
                "value" => '5',
                "condition" => 'parallax_bg=true&&wrap_around=false'
            )
        )->setRange('1','10','.1'); */
        
       
        
        
        /**
         * Navigation
         */ 
        $navigation_section = $this->addControlSection("navigation_section", __("Navigation Arrows"), "assets/icon.png", $this);
        
        $navigation_section->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => __('Navigation Arrows'),
                'slug' => 'nav_type'
            )
            
        )->setValue(array( 
            "icon" => "Built-in icons (use style settings below)", 
            "custom" => "Using custom elements" ,
            "none" => 'None',
         )
        )
         ->setDefaultValue('icon')
         ->setValueCSS( array(
            "custom"  => " .oxy-carousel-builder_icon {
                            display: none;
                        }
                        ",
             "none"  => " .oxy-carousel-builder_icon {
                            display: none;
                        }
                        "
        ) );
        
        
        /* $navigation_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Navigation Visibility'),
                'slug' => 'nav_visibility'
            )
            
        )->setValue(array( "hover" => "On Hover", "always" => "Always Visible" ))
         ->setDefaultValue('always')
         ->setValueCSS( array(
            "hover"  => 
                " .oxy-carousel-builder_icon {
                    opacity: 0;
                    visibility: hidden;
                    }

                    .oxy-inner-content:hover .oxy-carousel-builder_icon {
                        opacity: 1;
                        visibility: visible;
                    }
                "
        ) ); */
        
        
        
        
        
        /**
         * Icons
         */ 
        $prev_icon_section = $navigation_section->addControlSection("prev_icon_section", __("Previous Icon"), "assets/icon.png", $this);
        
        $navigation_icon_selector = '.oxy-carousel-builder_icon';
        
        $navigation_section->addStyleControl(
                array(
                    "name" => __('Icon Size'),
                    "slug" => "icon_size",
                    "selector" => $navigation_icon_selector,
                    "control_type" => 'slider-measurebox',
                    "value" => '14',
                    "property" => 'font-size',
                    "condition" => 'nav_type=icon',
                )
        )->setRange(1, 72, 1);
        
        $prev_icon_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Prev Icon'),
                "slug" => 'prev_icon',
                "value" => 'FontAwesomeicon-chevron-left', 
                "condition" => 'nav_type=icon',
            )
        );
        
        
        $next_icon_section = $navigation_section->addControlSection("next_icon_section", __("Next Icon"), "assets/icon.png", $this);
        
        $next_icon_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Next Icon'),
                "slug" => 'next_icon',
                "value" => 'FontAwesomeicon-chevron-right', 
                "condition" => 'nav_type=icon',
            )
        );
        
        
        $navigation_spacing_section = $navigation_section->addControlSection("navigation_spacing_section", __("Position / Spacing"), "assets/icon.png", $this);
        
        $navigation_spacing_section->addPreset(
            "padding",
            "nav_icon_padding",
            __("Padding"),
            $navigation_icon_selector
        )->whiteList();
        
        $prev_selector = '.oxy-carousel-builder_prev';
        $next_selector = '.oxy-carousel-builder_next';
        
        
        $navigation_spacing_section->addCustomControl(
            '<div style="color: #fff; font-size: 13px;">Previous Navigation</div>','description');
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $prev_selector,
                    "control_type" => 'measurebox',
                    "value" => '50',
                    "property" => 'top',
                )
        )->setUnits('%', 'px,%,em,auto,vw,vh')
         ->setParam('hide_wrapper_end', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $prev_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'bottom',
                )
        )->setParam('hide_wrapper_start', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                   "selector" => $prev_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'left',
                )
        )->setParam('hide_wrapper_end', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $prev_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'right',
                )
        )->setParam('hide_wrapper_start', true);
        
        
        $navigation_spacing_section->addCustomControl(
            '<div style="color: #fff; font-size: 13px;">Next Navigation</div>','description');
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $next_selector,
                    "control_type" => 'measurebox',
                    "value" => '50',
                    "property" => 'top',
                )
        )->setUnits('%', 'px,%,em,auto,vw,vh')
         ->setParam('hide_wrapper_end', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $next_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'bottom',
                )
        )->setParam('hide_wrapper_start', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $next_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'left',
                )
        )->setParam('hide_wrapper_end', true);
        
        $navigation_spacing_section->addStyleControl(
                array(
                    "selector" => $next_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'right',
                )
        )->setParam('hide_wrapper_start', true);
        
        
       
        
        
        
        $navigation_colors_section = $navigation_section->addControlSection("navigation_colors_section", __("Colors"), "assets/icon.png", $this);
        
        
        $navigation_colors_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "default" => '#222',
                    "selector" => $navigation_icon_selector,
                ),
                array(
                    "name" => __('Hover Background Color'),
                    "property" => 'background-color',
                    "selector" => $navigation_icon_selector.":hover",
                ),
                array(
                    "name" => __('Icon Color'),
                    "property" => 'color',
                    "default" => '#fff',
                    "selector" => $navigation_icon_selector,
                ),
                array(
                    "name" => __('Hover Icon Color'),
                    "property" => 'color',
                    "selector" => $navigation_icon_selector.":hover",
                )
            )
        );
        
        $navigation_colors_section->addStyleControl(
            array(
                "name" => __('Hover Transition Duration'),
                "property" => 'transition-duration',
                "selector" => $navigation_icon_selector,
                "control_type" => 'slider-measurebox',
                "default" => '400',
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        $navigation_section->borderSection('Borders', $navigation_icon_selector,$this);
        $navigation_section->boxShadowSection('Shadows', $navigation_icon_selector,$this);
        

        
        $navigation_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Previous Selector'),
                "slug" => 'previous_selector',
                "default" => '.prev-btn',
                "condition" => 'nav_type=custom'
            )
        );
        
        $navigation_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Next Selector'),
                "slug" => 'next_selector',
                "default" => '.next-btn',
                 "condition" => 'nav_type=custom'
            )
        );
        
        
        
        
        /**
         * Page Dots
         */ 
        $dots_section = $this->addControlSection("dots_section", __("Page Dots"), "assets/icon.png", $this);
        
        
        $dots_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Dots Display',
                'slug' => 'page_dots',
                'default' => 'true'
            )
            
        )->setValue(array( 
            "true" => "Enable", 
            "false" => "Disable"
            )
        )->setValueCSS( array(
            "false"  => " .flickity-page-dots {
                                display: none;
                            }",
        ) );
        
        
        $dots_section->addOptionControl(
            array(
                "name" => __('Hide Dots Below'),
                "slug" => 'hide_dots_below',
                "type" => 'medialist',
                "default" => 'never',
                "condition" => 'page_dots=true'
            )
        );
        
        /**
         * Dot Styles
         */ 
        $dots_styles_section = $dots_section->addControlSection("dots_styles_sectionf", __("Dot Styles"), "assets/icon.png", $this);
        
        $dots_styles_section->addStyleControls(
             array( 
                 array( 
                    "selector" => $dot_selector,
                    "property" => 'background-color',
                      "condition" => 'page_dots=true'
                ),
                array(
                    "selector" => $dot_selector,
                    "property" => 'opacity',
                    "default" => '0.25',
                     "condition" => 'page_dots=true'
                ),
                array(
                    "default" => "20",
                    "selector" => $dot_selector,
                    "property" => 'border-radius',
                      "condition" => 'page_dots=true'
                ), 
            )
        );
        
        $dots_styles_section->addStyleControl( 
            array(
                "default" => "10",
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => $dot_selector,
                "condition" => 'page_dots=true'
            )
        )
        ->setUnits('px','px')
        ->setRange('1','20','1');
        
        $dots_styles_section->addStyleControl( 
            array(
                "default" => "10",
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $dot_selector,
                "condition" => 'page_dots=true'
            )
        )
        ->setUnits('px','px')
        ->setRange('1','30','1');
        
       
        
        $dots_styles_section->addStyleControl( 
            array(
                "name" => 'Margin Between',
                "default" => "8",
                "property" => 'margin-left|margin-right',
                "control_type" => 'slider-measurebox',
                "selector" => $dot_selector,
                "condition" => 'page_dots=true'
            )
        )
        ->setUnits('px','px')
        ->setRange('0','10','1');
        
        
        
        
        
        
        
        
        
        /**
         * Selected Page Dots
         */ 
        $dots_selected_section = $dots_section->addControlSection("dots_selected_section", __("Selected Dot"), "assets/icon.png", $this);
        
        
        $dots_selected_section->addStyleControls(
             array( 
                  array(
                    "name" => 'Selected background color',  
                    "selector" => $dot_selected_selector,
                    "property" => 'background-color',
                      "condition" => 'page_dots=true'
                ),
                array(
                    "name" => 'Selected opacity',
                    "selector" => $dot_selected_selector,
                    "property" => 'opacity',
                    "default" => '1',
                     "condition" => 'page_dots=true'
                ),
                 
            )
        );
        
        
        $dots_selected_section->addStyleControl(
            array(
                "name" => __('Scale'),
                "selector" => $dot_selected_selector,
                "property" => '--selected-dot-scale',
                "control_type" => 'slider-measurebox',
                "default" => '1',
                )
            )
            ->setRange('0','2', '.02');
        
        $dots_selected_section->addStyleControl(
            array(
                "name" => __('Transition duration'),
                "property" => 'transition-duration',
                "selector" => $dot_selector,
                "control_type" => 'slider-measurebox',
                "default" => '0',
            )
        )->setUnits('ms','ms')
         ->setRange(10, 800, 5);
        
        
        
        $dots_position_section = $dots_section->addControlSection("dot_position_section", __("Positioning"), "assets/icon.png", $this);
        
        
        
        
        $dots_position_section->addStyleControl(
                array(
                    "selector" => $dots_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'top',
                    "condition" => 'page_dots=true'
                )
        )->setParam('hide_wrapper_end', true);
        
        $dots_position_section->addStyleControl(
                array(
                    "selector" => $dots_selector,
                    "control_type" => 'measurebox',
                    "value" => '-25',
                    "property" => 'bottom',
                    "condition" => 'page_dots=true'
                )
        )->setUnits('px', 'px,%,em,auto,vw,vh')
         ->setParam('hide_wrapper_start', true);
        
        $dots_position_section->addStyleControl(
                array(
                   "selector" => $dots_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'left',
                    "condition" => 'page_dots=true'
                )
        )->setParam('hide_wrapper_end', true);
        
        $dots_position_section->addStyleControl(
                array(
                    "selector" => $dots_selector,
                    "control_type" => 'measurebox',
                    "value" => '0',
                    "property" => 'right',
                    "condition" => 'page_dots=true'
                )
        )->setParam('hide_wrapper_start', true);
        
        
        
         /**
         * Friction / Drag
         */ 
        $friction_section = $this->addControlSection("friction_section", __("Friction / Drag"), "assets/icon.png", $this);
        
        $friction_section->addCustomControl(
            '<div style="color: #fff; line-height: 1.3; font-size: 13px;">These changes will not be visible while inside Oxygen</div>','description');
        
        $friction_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Drag Threshold'),
                "slug" => 'drag_threshold',
                "value" => '3',
            )
        )->setRange('0','40','1')
         ->setParam("description", __("Number of px moved until dragging starts"));    
        
        $friction_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Selected Attraction'),
                "slug" => 'selected_attraction',
                "value" => '0.025',
            )
        )->setRange('0','1','.001')
         ->setParam("description", __("Higher attraction makes the slider move faster"));    
        
        
        
        $friction_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Friction'),
                "slug" => 'friction',
                "value" => '0.28',
            )
        )->setRange('0','1','.02') 
         ->setParam("description", __("Higher friction makes the slider feel stickier and less bouncy")); 
        
        
        $friction_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Free Scroll Friction'),
                "slug" => 'free_scroll_friction',
                "value" => '0.075',
                "condition" => 'free_scroll=true'
            )
        )->setRange('0','1','.05') 
         ->setParam("description", __("Higher friction makes the slider feel stickier")); 
        
       
       
    }
    
    
    
    function defaultCSS() {
        
        $css = ".oxygenberg-element.oxy-dynamic-list:empty:after {
                    display: block;
                    content: attr(gutenberg-placeholder);
                }";
        
        return $css;
        
    }
   
    function customCSS($options, $selector) {
        
        $css = '';
        
        $css .= file_get_contents( plugin_dir_path(__FILE__) . 'assets/flickity/flickity.css' );
        
        $css .= "
                .oxy-carousel-builder {
                    display: flex;
                    flex-direction: column;
                    position: relative;
                    width: 100%;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list.flick:not(.ct-section) {
                    display: block;
                }
                
                .oxy-carousel-builder .flickity-prev-next-button,
                .oxy-carousel-builder .flickity-page-dots {
                    z-index: 2147483643;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list > div.flickity-viewport:not(.oxy_repeater_original):first-child {
                    display: block;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list {
                    display: flex;
                    flex-direction: row;
                }
                
                .oxy-carousel-builder .oxy-woo-element ul.products {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: nowrap;
                    margin: 0;
                }
                
                $selector .oxy-posts {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: nowrap;
                }
                
                .oxy-carousel-builder ul.products::before {
                    content: none;
                }
                
                .oxy-carousel-builder .oxy-woo-element ul.products .product {
                    float: none;
                    padding: 0;
                    flex-shrink: 0;
                }           
                
                .oxy-carousel-builder .oxy-post {
                    float: none;
                    flex-shrink: 0;
                }
                
                .oxy-carousel-builder .cell {
                    float: none;
                    flex-shrink: 0;
                }
                            
                .oxy-carousel-builder .flickity-viewport {
                    transition-property: height;
                }
                
                .oxy-carousel-builder .flickity-page-dots {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list::after {
                    content: 'flickity';
                    display: none;
                }
                
                .oxy-carousel-builder ul.products::after {
                    content: 'flickity';
                    display: none;
                }
                
                .oxy-carousel-builder .oxy-posts::after {
                    content: 'flickity';
                    display: none;
                }
                
                .oxy-carousel-builder .oxy-inner-content::after {
                    content: 'flickity';
                    display: none;
                }
                
                .oxy-carousel-builder .woocommerce-result-count,
                .oxy-carousel-builder .woocommerce-ordering {
                    display: none;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list > .ct-div-block,
                .oxy-carousel-builder .oxy-dynamic-list .flickity-slider > .ct-div-block {
                    transition: transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                    -webkit-transition: -webkit-transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                }
                
                .oxy-carousel-builder ul.products .product,
                .oxy-carousel-builder ul.products .flickity-slider > .product {
                    transition: transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                    -webkit-transition: -webkit-transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                }
                
                .oxy-carousel-builder .cell,
                .oxy-carousel-builder .flickity-slider > .cell {
                    transition: transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                    -webkit-transition: -webkit-transform 0.4s ease, background-color 0.4s ease, color 0.4s ease, opacity 0.4s ease;
                }
                
                .oxy-carousel-builder .oxy-dynamic-list > .ct-div-block,
                .oxy-carousel-builder .oxy-dynamic-list .flickity-slider > .ct-div-block {
                    flex-shrink: 0;
                    overflow: hidden;
                }
                
                .oxy-carousel-builder_icon {
                    background-color: #222;
                    color: #fff;
                    display: inline-flex;
                    font-size: 14px;
                    padding: .75em;
                    cursor: pointer;
                    transition-duration: 400ms;
                    transition-property: color, background-color;
                }
                
                
                .oxy-carousel-builder_icon {
                    top: 50%;
                    position: absolute;
                    transform: translateY(-50%);
                    -webkit-transform: translateY(-50%);
                }
                
                .oxy-carousel-builder_prev {
                    left: 0;
                }
                
                .oxy-carousel-builder_next {
                    right: 0;
                }
                
                .oxy-carousel-builder_icon svg {
                    height: 1em;
                    width: 1em;
                    fill: currentColor;
                }
                
                .oxy-carousel-builder .flickity-page-dots .dot {
                    --selected-dot-scale: 1;
                    flex-shrink: 0;
                }
                
                .oxy-carousel-builder .oxy-repeater-pages-wrap {
                    display: none;
                }
                
                .oxy-carousel-builder .is-next {
                    --cell-next-scale: 1;
                    --cell-next-rotate: 0deg;
                }
                
                .oxy-carousel-builder .is-selected {
                    --cell-selected-scale: 1;
                    --cell-selected-rotate: 0deg;
                }
                
                .oxy-carousel-builder .is-previous {
                    --cell-prev-scale: 1;
                    --cell-prev-rotate: 0deg;
                }
               
               $selector .is-next {
                  
                    transform: scale(var(--cell-next-scale)) rotate(var(--cell-next-rotate));
                    -webkit-transform: scale(var(--cell-next-scale)) rotate(var(--cell-next-rotate));
               }
               
               $selector .is-selected:not(.dot) {
                  
                    transform: scale(var(--cell-selected-scale)) rotate(var(--cell-selected-rotate));
                    -webkit-transform: scale(var(--cell-selected-scale)) rotate(var(--cell-selected-rotate));
               }
               
               $selector .is-previous {
                  
                    transform: scale(var(--cell-prev-scale)) rotate(var(--cell-prev-rotate));
                    -webkit-transform: scale(var(--cell-prev-scale)) rotate(var(--cell-prev-rotate));
               }
               
               $selector .dot.is-selected {
                    transform: scale(var(--selected-dot-scale));
                    -webkit-transform: scale(var(--selected-dot-scale));
               }
               
               .oxy-carousel-builder .oxy-inner-content [data-speed] {
                    transition: transform 0s;
                    -webkit-transition: transform 0s;
                }
               
            
                // In builder styles
                .oxygen-builder-body .oxy-carousel-builder .oxy-dynamic-list .flickity-slider > .ct-div-block:not(:first-child) {
                    opacity: .4;
                    pointer-events: none;
                }
                
                .oxy-carousel-builder .oxy-inner-content:empty {
                    min-height: 80px;
                }
                
                
                $selector .flickity-enabled {
                    display: block;
                }

                .oxy-carousel-builder .oxy-inner-content:empty + .flickity-page-dots .dot:not(:first-child) {
                    display: none;
                }
                
                .oxygen-builder-body .oxy-carousel-builder .oxy-dynamic-list.flickity-enabled {
                    pointer-events: none;
                }
                
                .oxygen-builder-body .oxy-carousel-builder .oxy-dynamic-list.flickity-enabled .oxy_repeater_original {
                     /* display: none!important; */
                }
                
                .oxygen-builder-body .oxy-flickity-buttons {
                    position: absolute;
                    display: block;
                    align-items: center;
                    color: #fff;
                    background-color: rgb(100, 0, 255);
                    z-index: 2147483641;
                    cursor: default;
                }
                
                .oxygen-builder-body .oxy-flickity-buttons .hide {
                    display: none;
                }
                
                
                ";
        
        $css .= "
                
                $selector .oxy-dynamic-list.flickity-enabled {
                    display: block;
                }
                
                
                ";
        
        if (!isset($options["oxy-carousel-builder_editor_mode"]) || $options["oxy-carousel-builder_editor_mode"] === "preview") {
            
            $css .= "
                
                .oxygen-builder-body $selector .oxy-inner-content {
                    cursor: pointer;
                }    
                
                .oxygen-builder-body $selector .oxy-inner-content + .flickity-page-dots {
                    display: none;
                }
            
            ";
            
        }
        
        if (!isset($options["oxy-carousel-builder_editor_mode"]) || $options["oxy-carousel-builder_editor_mode"] === "edit") {
            
            $css .= "
            
                .oxygen-builder-body $selector .oxy-dynamic-list:after {
                        content: '';
                    }
                      
                
                .oxygen-builder-body $selector .flickity-viewport + .flickity-page-dots {
                    display: none;
                }
                
                .oxygen-builder-body $selector .oxy-dynamic-list {
                     overflow-x: unset; 
                }
            
            ";
            
        }
        
        if ((isset($options["oxy-carousel-builder_hide_dots_below"]) && $options["oxy-carousel-builder_hide_dots_below"]!="never")) {
                $max_width = oxygen_vsb_get_media_query_size($options["oxy-carousel-builder_hide_dots_below"]);
                $css .= "@media (max-width: {$max_width}px) {
                
                            $selector .flickity-page-dots {
                                display: none;
                            }
                            
                        }";
            }
        
        
        
                
        
        return $css;
    }
    
    function output_js() { 
        
        wp_enqueue_script( 'flickity', plugin_dir_url( __FILE__ ) . 'assets/flickity/flickity.pkgd.min.js', '', '2.2.1' );
        wp_enqueue_script( 'flickity-init', plugin_dir_url( __FILE__ ) . 'assets/flickity/flickity-init.js', '', '2.2.1' );
        
    }


}

new ExtraCarousel();