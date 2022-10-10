<?php

class ExtraOffCanvasWrapper extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Off Canvas'); 
    }
    
    function init() {

        $this->enableNesting();

    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return 'interactive';
    }
    
    
    function render($options, $defaults, $content) {
        
        $trigger = isset( $options['trigger'] ) ? $options['trigger'] : 'ct-inner-wrap';
        $click_outside = $options['click_outside'] === 'true' ? 'true' : 'false';
        $start = $options['start'] === 'open' ? 'true' : 'false';
        $pressing_esc = $options['pressing_esc'] === 'true' ? 'true' : 'false';
        $focus_selector = $options['maybe_focus'] === 'selector' ? $options['focus_selector'] : '.offcanvas-inner';
        $maybe_focus = $options['maybe_focus'];
        $stagger_animation_delay = isset( $options['stagger_animation_delay'] ) ? esc_attr($options['stagger_animation_delay']) : '50';
        $stagger_first_delay = isset( $options['stagger_first_delay'] ) ? esc_attr($options['stagger_first_delay']) : '200';
        $animation_reset = isset( $options['animation_reset'] ) ? esc_attr($options['animation_reset']) : '400';
        
        
        $output = '<div class="oxy-offcanvas_backdrop"></div>';
        
        $output .= '<div class="offcanvas-inner oxy-inner-content" data-start="'. $start .'" data-click-outside="'. $click_outside .'" data-trigger-selector="'. $trigger .'" data-esc="'. $pressing_esc .'" tabindex="0" ';
        
        if ( 'selector' === $maybe_focus ) {
            $output .= 'data-focus-selector="'. $focus_selector .'" ';
        } elseif ( 'inner' === $maybe_focus ) {
            $output .= 'data-focus-selector=".offcanvas-inner" ';
        }
        
        $output .= 'data-reset="'. $animation_reset .'" ';
        
        if ( 'true' === $options['stagger_animations'] ) {
            $output .= 'data-stagger="'. $stagger_animation_delay .'" ';
            $output .= 'data-first-delay="'. $stagger_first_delay .'" ';
        }
        
        $output .= '>';
        
            if ($content) {
            
                $output .= do_shortcode($content); 
            
            } 
        
        $output .= '</div>';
            
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
        
        $offcanvas_inner_selector = '.offcanvas-inner';
        
        $this->addStyleControls(
            array(
            
                array(
                    //"name" => 'Canvas Backgro'
                    "property" => 'background-color',
                    "default" => '#fff',
                    "selector" => $offcanvas_inner_selector,
                )
            )
        );
        
        /**
         * Choose trigger selector
         */
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Open / Close Trigger selector'),
                "slug" => 'trigger',
                "default" => '.oxy-burger-trigger',
            )
        );
        
        /**
         * Slide Options
         */
         $side_options = $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Slide in from',
                'slug' => 'side'
            )
            
        )->setValue(array( "left" => "Left", "right" => "Right", "top" => "Top", "bottom" => "Bottom" ));
         $side_options->setDefaultValue('left');
        $side_options->setValueCSS( array(
            "left"  => " .offcanvas-inner {
                            height: 100vh;
                            min-height: -webkit-fill-available;
                        }
                        ",
            "right"  => " .offcanvas-inner {
                            left: auto;
                            right: 0;
                            height: 100vh;
                            min-height: -webkit-fill-available;
                        }",
            "top"  => " .offcanvas-inner {
                            left: 0;
                            right: 0;
                            top: 0;
                            bottom: auto;
                            width: 100%;
                        }",
            "bottom"  => " .offcanvas-inner {
                            left: 0;
                            right: 0;
                            top: auto;
                            bottom: 0;
                            width: 100%;
                    }"
        ) );
        
        
        /**
         * Styles controls
         */
        $this->addStyleControl( 
            array(
                "type" => 'measurebox',
                "default" => "280",
                "units" => 'px',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => $offcanvas_inner_selector,
                "condition" => 'side!=top&&side!=bottom'
                
            )
        )
        ->setRange('0','1000','1');
        
        $this->addStyleControl( 
            array(
                "type" => 'measurebox',
                "default" => "300",
                "units" => 'px',
                "property" => 'height',
                "control_type" => 'slider-measurebox',
                "selector" => $offcanvas_inner_selector,
                "condition" => 'side!=left&&side!=right'
            )
        )
        ->setRange('0','1000','1');
        
        $this->addStyleControl(
            array(
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
                "default" => '.5',
                "selector" => $offcanvas_inner_selector,
            )
        )
        ->setUnits('s','s')
        ->setRange('0','1','.1');
        
        
        
      
        /**
         * Visibility in Builder
         */
        $visibility_oxygen = $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Visibility in Builder',
                'slug' => 'builder_visibility'
            )
        )->setValue(array( "hidden" => "Hidden", "visible" => "Visible" ));
        $visibility_oxygen->setDefaultValue('visible');
        $visibility_oxygen->setValueCSS( array(
            "hidden"  => " {
                        display: none;
                    }
                    
               ",
        ) );
        
        /**
         * Config
         */
        $offcanvas_section = $this->addControlSection("offcanvas_section", __("Config"), "assets/icon.png", $this);
        
        
        $offcanvas_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'State on page load',
                'slug' => 'start'
            )
            
        )->setValue(array( "closed" => "Closed", "open" => "Open" ))
         ->setDefaultValue('closed');
        
        $offcanvas_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Site scrolling when open',
                'slug' => 'overflow',
                'condition' => 'start=closed'
            )
            
        )->setValue(array( "true" => "Enabled", "false" => "Disabled" ))
         ->setDefaultValue('true');
        
        $offcanvas_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Clicking outside offcanvas closes it',
                'slug' => 'click_outside',
                'condition' => 'start=closed&&backdrop_display=display'
            )
            
        )->setValue(array( "true" => "Enabled", "false" => "Disabled" ))
         ->setDefaultValue('true');
        
        $offcanvas_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'ESC key when focus in offcanvas closes it',
                'slug' => 'pressing_esc',
                'condition' => 'start=closed'
            )
            
        )->setValue(array( "true" => "Enabled", "false" => "Disabled" ))
         ->setDefaultValue('false');
        
        
        $offcanvas_section->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => 'Move focus when opened',
                'slug' => 'maybe_focus',
            )
            
        )->setValue(
            array(  
                "disable" => "disable",
                "selector" => "selector inside offcanvas",
                "inner" => "offcanvas inner"
            )
        )
         ->setDefaultValue('disable');
        
        
        $offcanvas_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Selector to receive focus (first found in offcanvas)'),
                "slug" => 'focus_selector',
                "default" => '.ff-el-input--content input',
                "condition" => 'maybe_focus=selector',
            )
        );
        
        
        
        
        /**
         * Inner Layout / Spacing
         */
        $offcanvas_inner_section = $this->addControlSection("Spacing", __("Layout / Spacing"), "assets/icon.png", $this);
        $offcanvas_inner_section->flex($offcanvas_inner_selector, $this);
        
        $this->boxShadowSection('Box Shadow', $offcanvas_inner_selector,$this);
        
        
        $offcanvas_inner_section->addStyleControls(
            array(
                array(
                    "name" => 'Padding Left',
                    "property" => 'padding-left',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30',
                    "selector" => $offcanvas_inner_selector
                ),
                array(
                    "name" => 'Padding Right',
                    "property" => 'padding-right',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30',
                    "selector" => $offcanvas_inner_selector
                ),
                array(
                    "name" => 'Padding Top',
                    "property" => 'padding-top',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30',
                    "selector" => $offcanvas_inner_selector
                ),
                array(
                    "name" => 'Padding Bottom',
                    "property" => 'padding-bottom',
                    "control_type" => "measurebox",
                    "unit" => "px",
                    "value" => '30',
                    "selector" => $offcanvas_inner_selector
                ),
                array(
                    "property" => 'z-index',
                    "default" => '1000',
                    "selector" => $offcanvas_inner_selector
                )
            )
        );
        
        
        /**
         * Backdrop
         */
        $offcanvas_backdrop_section = $this->addControlSection("offcanvas_backdrop_section", __("Backdrop"), "assets/icon.png", $this);
        $offcanvas_backdrop_selector = '.oxy-offcanvas_backdrop';
        
        
        $backdrop_display = $offcanvas_backdrop_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Backdrop Display',
                'slug' => 'backdrop_display'
            )
        )->setValue(array( "hide" => "Disable", "display" => "Enable" ));
        $backdrop_display->setDefaultValue('display');
        $backdrop_display->setValueCSS( array(
            "hide"  => " .oxy-offcanvas_backdrop {
                            opacity: 0;
                            visibility: hidden;
                        }
                    
               "
        ) );
        
        $offcanvas_backdrop_section->addStyleControls(
            array(
                array(
                    "property" => 'background-color',
                    "selector" => $offcanvas_backdrop_selector,
                    "condition" => 'backdrop_display=display',
                    "default" => 'rgba(0,0,0,0.5)'
                ),
                array(
                    "property" => 'z-index',
                    "selector" => $offcanvas_backdrop_selector,
                    "condition" => 'backdrop_display=display'
                )
            )
        );
        
        $offcanvas_backdrop_section->addStyleControl(
            array(
                "name" => 'Fade Duration',
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
                "default" => '.5',
                "selector" => $offcanvas_inner_selector,
                 "condition" => 'backdrop_display=display'
            )
        )
        ->setUnits('s','s')
        ->setRange('0','1','.1');
        
        
        /**
         * Config
         */
        $inner_animations_section = $this->addControlSection("inner_animations_section", __("Inner Animations"), "assets/icon.png", $this);
        
        /**
         * Slide Options
         */
         $stagger_control = $inner_animations_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __('Stagger scroll animations'),
                'slug' => 'stagger_animations'
            )
            
        )->setValue(array( 
             "true" => "Enable", 
             "false" => "Disable"
         ));
         $stagger_control->setDefaultValue('false');
        
        
        $inner_animations_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('First element delay'),
                "slug" => 'stagger_first_delay',
                "value" => '200',
                "condition" => 'stagger_animations=true'
            )
        )->setUnits('ms','ms')
         ->setRange('0','1000','50');
        
        $inner_animations_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Delay each animation by..'),
                "slug" => 'stagger_animation_delay',
                "value" => '50',
                "condition" => 'stagger_animations=true'
            )
        )->setUnits('ms','ms')
         ->setRange('50','400','50');
        
        $inner_animations_section->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Animation reset timing'),
                "slug" => 'animation_reset',
                "value" => '400',
                "condition" => 'stagger_animations=true'
            )
        )->setUnits('ms','ms')
         ->setRange('100','1000','5')
         ->setParam("description", __("How long before animations reset after closing offcanvas"));        
        
        
        
        
    }
    
    
    function customCSS($options, $selector) {
        
    
        $css = "body:not(.oxygen-builder-body) $selector {
                    display: block;
                }
                
                body:not(.oxygen-builder-body) .editor-styles-wrapper $selector {
                    visibility: hidden;
                }";
        
        $css .= ".oxy-off-canvas {
                    visibility: visible;
                    pointer-events: none;
                }
        
                .offcanvas-inner {
                    background: #fff;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                        -ms-flex-direction: column;
                            flex-direction: column;
                    position: fixed;
                    height: 100vh;
                    max-width: 100%;
                    width: 280px;
                    overflow-x: hidden;
                    top: 0;
                    left: 0;
                    padding: 30px;
                    z-index: 1000;
                    -webkit-transition: -webkit-transform .5s cubic-bezier(0.77, 0, 0.175, 1);
                    transition: -webkit-transform .5s cubic-bezier(0.77, 0, 0.175, 1);
                    -o-transition: transform .5s cubic-bezier(0.77, 0, 0.175, 1);
                    transition: transform .5s cubic-bezier(0.77, 0, 0.175, 1);
                    transition: transform .5s cubic-bezier(0.77, 0, 0.175, 1), 
                    -webkit-transform .5s cubic-bezier(0.77, 0, 0.175, 1);
                    pointer-events: auto;
                }
                
                .offcanvas-inner:focus {
                    outline: none;
                }
                
                .oxy-offcanvas_backdrop {
                    background: rgba(0,0,0,.5);
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    opacity: 0;
                    visibility: hidden;
                    -webkit-transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
                    -o-transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
                    transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
                    pointer-events: auto;
                }
                
                .oxy-off-canvas-toggled .oxy-offcanvas_backdrop {
                    opacity: 1;
                     visibility: visible;
                }
                
                body.oxygen-builder-body $selector .offcanvas-inner {
                    -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                    z-index: 2147483640;
                }
                
                body.oxygen-builder-body .oxy-slide-menu-dropdown-icon-click-area {
                    position: relative;
                    z-index: 2147483641;
                }
                
                body.oxygen-builder-body .oxy-offcanvas_backdrop {
                    opacity: 1;
                    visibility: visible;
                }";
        
        if ((isset($options["oxy-off-canvas_overflow"]) && $options["oxy-off-canvas_overflow"] === "false")  && ($options["oxy-off-canvas_start"] != "true") ) {
            
            $css .= "body.off-canvas-toggled {
                        overflow: hidden;
                    }";
            
        }
        
        /**
         * Offcanvas Right
         */
        if ((isset($options["oxy-off-canvas_side"]) && $options["oxy-off-canvas_side"] === "right")) {
            
            if ((!isset($options["oxy-off-canvas_start"]) || $options["oxy-off-canvas_start"] === "closed")) {
                
                $css .= "$selector .offcanvas-inner {
                        -webkit-transform: translate(100%,0);
                            -ms-transform: translate(100%,0);
                                transform: translate(100%,0);
                    }
                
                    $selector.oxy-off-canvas-toggled .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                    }";
                
            } else {
                
                 $css .= "$selector.oxy-off-canvas-toggled .offcanvas-inner  {
                        -webkit-transform: translate(100%,0);
                            -ms-transform: translate(100%,0);
                                transform: translate(100%,0);
                    }
            
                    body.oxygen-builder-body $selector .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                            left: auto;
                    }";
                
            }
            
           
            
        }
        
        
        /**
         * Offcanvas Left (default)
         */
        if ((!isset($options["oxy-off-canvas_start"]) || $options["oxy-off-canvas_start"] === "closed")) {
            
            $css .= ".oxy-off-canvas .offcanvas-inner {
                        -webkit-transform: translate(-100%,0);
                            -ms-transform: translate(-100%,0);
                                transform: translate(-100%,0);
                    }

                    .oxy-off-canvas-toggled.oxy-off-canvas .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                    }";
            
        }
        
        if ((isset($options["oxy-off-canvas_start"]) && $options["oxy-off-canvas_start"] === "open")) {
            
            $css .= ".oxy-off-canvas-toggled.oxy-off-canvas .offcanvas-inner {
                        -webkit-transform: translate(-100%,0);
                            -ms-transform: translate(-100%,0);
                                transform: translate(-100%,0);
                    }
                    
                    .oxy-offcanvas_backdrop {
                       visibility: visible;
                        opacity: 1;
                    }
                    
                    .oxy-off-canvas-toggled.oxy-off-canvas .oxy-offcanvas_backdrop {
                        opacity: 0;
                        visibility: hidden;
                    }";
            
        }
        
        
        /**
         * Offcanvas Top
         */
        if ((isset($options["oxy-off-canvas_side"]) && $options["oxy-off-canvas_side"] === "top")) {
            
            if ((!isset($options["oxy-off-canvas_start"]) || $options["oxy-off-canvas_start"] === "closed")) {
                
                $css .= "$selector .offcanvas-inner {
                        -webkit-transform: translate(0,-100%);
                            -ms-transform: translate(0,-100%);
                                transform: translate(0,-100%);
                    }
                
                    $selector.oxy-off-canvas-toggled .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                    }";
                
            } else {
                
                 $css .= "$selector.oxy-off-canvas-toggled .offcanvas-inner  {
                        -webkit-transform: translate(0,-100%);
                            -ms-transform: translate(0,-100%);
                                transform: translate(0,-100%);
                    }
            
                    body.oxygen-builder-body $selector .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                        left: auto;
                    }";
                
            }
            
           
            
        }
        
        
        
        /**
         * Offcanvas Bottom
         */
        if ((isset($options["oxy-off-canvas_side"]) && $options["oxy-off-canvas_side"] === "bottom")) {
            
            if ((!isset($options["oxy-off-canvas_start"]) || $options["oxy-off-canvas_start"] === "closed")) {
                
                $css .= "$selector .offcanvas-inner {
                        -webkit-transform: translate(0,100%);
                            -ms-transform: translate(0,100%);
                                transform: translate(0,100%);
                    }
                
                    $selector.oxy-off-canvas-toggled .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                    }";
                
            } else {
                
                 $css .= "$selector.oxy-off-canvas-toggled .offcanvas-inner  {
                        -webkit-transform: translate(0,100%);
                            -ms-transform: translate(0,100%);
                                transform: translate(0,100%);
                    }
            
                    body.oxygen-builder-body $selector .offcanvas-inner {
                        -webkit-transform: none;
                        -ms-transform: none;
                            transform: none;
                        left: auto;
                    }";
                
            }
            
           
            
        }
        
        
        
        return $css;
    }
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }
    
    function output_js() { ?>
            
            <script type="text/javascript">
            jQuery(document).ready(oxygen_init_offcanvas);
            function oxygen_init_offcanvas($) {
                
                // check if supports touch, otherwise it's click:
                let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click'; 

                $('.oxy-off-canvas .offcanvas-inner').each(function() {

                    var offCanvas = $(this),
                        triggerSelector = offCanvas.data('trigger-selector'),
                        offCanvasOutside = offCanvas.data('click-outside'),
                        offCanvasEsc = offCanvas.data('esc'),
                        offCanvasStart = offCanvas.data('start'),
                        offCanvasBackdrop = offCanvas.data('backdrop'),
                        offCanvasFocusSelector = offCanvas.data('focus-selector'),
                        backdrop = offCanvas.prev('.oxy-offcanvas_backdrop'),
                        menuHashLink = offCanvas.find('a[href*=\\#]').not(".menu-item-has-children > a"),
                        stagger = offCanvas.data('stagger'),
                        reset = offCanvas.data('reset');
                            
                    
                    $(window).load(function() {
                        // Make sure AOS animations are reset for elements inside after everything has loaded
                        setTimeout(function(){
                            offCanvas.find(".aos-animate").addClass("aos-animate-disabled").removeClass("aos-animate");
                        }, 400); // wait
                        
                        if (stagger != null) {
                        
                            var delay = offCanvas.data('first-delay');
                            offCanvas.find('.aos-animate').each(function() {
                                delay = delay + stagger;
                                $(this).attr('data-aos-delay', delay);
                            });

                        }
                        
                        
                    });    

                    // Trigger Clicked
                    $(triggerSelector).on(touchEvent, function(e) {
                        
                        e.stopPropagation();
                        e.preventDefault();
                        
                        console.log('clicked trigger');
                        
                        if ($(this).hasClass('oxy-close-modal')) {
                            oxyCloseModal();
                        } 
                        
                        if (!offCanvas.parent().hasClass('oxy-off-canvas-toggled')) {
                            openOffCanvas();
                        } else {
                            closeOffCanvas();
                        }
                    });


                    // Backdrop Clicked
                    $('.oxy-offcanvas_backdrop').on(touchEvent, function(e) {
                        e.stopPropagation();
                        if (offCanvasOutside === true) {
                            closeBurger();
                            closeOffCanvas();
                        }

                    });

                    // Pressing ESC from inside the offcanvas will close it
                    offCanvas.keyup(function(e) {
                        if (offCanvasEsc === true) {
                            if (e.keyCode === 27) {
                                closeBurger();
                                closeOffCanvas();
                            }
                        }
                    });

                    // Make sure hashlinks inside any menus in the offcanvas will close the modal.
                    menuHashLink.on(touchEvent, function(e) {
                        e.stopPropagation();
                        if (this.pathname === window.location.pathname) {
                            closeBurger();
                            closeOffCanvas();
                        }
                    });

                    function openOffCanvas() {
                        offCanvas.parent().addClass('oxy-off-canvas-toggled');
                        $('body').addClass('off-canvas-toggled');
                        offCanvas.find(".aos-animate-disabled").removeClass("aos-animate-disabled").addClass("aos-animate");
                        
                        
                        if (offCanvasFocusSelector) {
                            offCanvas.parent().find(offCanvasFocusSelector).eq(0).focus();
                        }
                    }

                    function closeOffCanvas() {

                        $('body').removeClass('off-canvas-toggled');
                        offCanvas.parent().removeClass('oxy-off-canvas-toggled');
                        
                        setTimeout(function(){
                            offCanvas.find(".aos-animate").removeClass("aos-animate").addClass("aos-animate-disabled");
                        }, reset); // wait before removing the animate class 
                    }
                    
                    function closeBurger() {
                       
                            if ( ( $(triggerSelector).children('.hamburger').length > 0) && ($(this).children('.hamburger').data('animation') !== 'disable')) {
                                $(triggerSelector).children('.hamburger').removeClass('is-active');
                            }
                    }

                });
            }
        </script>

    <?php }
    
}

new ExtraOffCanvasWrapper();