<?php

class ExtraContentSwitcher extends OxygenExtraElements {
        
    var $js_added = false;

	function name() {
        return 'Content Switcher';
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return "interactive";
    }
    
    function init() {
        
        $this->enableNesting();
    
    }

    function render($options, $defaults, $content) {
        
        $output = '<div class=oxy-inner-content>';
        
         if ($content) {
            
                $output .= do_shortcode($content); 
            
            } 
        
        $output .= '</div>';
        
        echo $output;
        
    }

    function class_names() {
        return array('');
    }

    function controls() {
        
        //$this->addCustomControl('<div class="oxygen-control-label">The first two elements added inside the content switcher will be the elements that are switched</div>', 'desc');
        
        $content_selector = '.oxy-inner-content > *:nth-child(1), .oxy-inner-content > *:nth-child(2)'; 
        
       
        
        $this->addStyleControl(
            array(
                "name" => 'Fade Duration',
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
                "default" => '0',
                "selector" => $content_selector,
            )
        )
        ->setUnits('s','s')
        ->setRange('0','1','.01');
        
        
       
        
        /**
         * Styles controls
         */
        $this->addStyleControl( 
            array(
                "default" => "",
                "units" => 'px',
                "property" => 'width',
                "control_type" => 'slider-measurebox',
                "selector" => '',
                
            )
        )
        ->setRange('0','1000','1');
        
        
         /**
         * Visibility
        */ 
        $this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => __('Visibility in Builder'),
                'slug' => 'builder_visibility',
                'default' => 'default',
            )
            
        )->setValue(
            array( 
                "default" => "Default (first element visible)", 
                "both" => 'Keep both elements visible',
            )
        );
        
        
         // Switch the positioning
        $this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => __('Switch the Positioning'),
                'slug' => 'set_positioning'
            )
            
        )->setValue(
            array( 
                "both" => "Enable", 
                "first" => "Disable" 
            )
        )
        ->setDefaultValue('both')
        ->setValueCSS( array(
            "first"  => " .oxy-inner-content > *:nth-child(1) {
                            position: relative;
                        }
                        
                         .oxy-inner-content > *:nth-child(2) {
                            position: absolute;
                        }",
        ) )->setParam("description", __("Disable if content switching causing layout issues"));  
        
        
        

    }
    
   
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-content-switcher {
                    position: relative;
                }
                
                .oxy-content-switcher .oxy-inner-content > *:nth-child(1) {
                    transition-property: visibility, opacity, transform;
                    position: relative;
                    opacity: 1;
                    visibility: visible;
                    width: 100%;
                    transform: none;
                }
                
                .oxy-content-switcher .oxy-inner-content > *:nth-child(2)  {
                    transition-property: visibility,opacity, transform;
                    position: absolute;
                    top: 0;
                    left: 0;
                    opacity: 0;
                    visibility: hidden;
                    width: 100%;
                }
                
                .oxy-content-switcher_toggled .oxy-inner-content > *:nth-child(1) {
                    position: absolute;
                    top: 0;
                    left: 0;
                    opacity: 0;
                    visibility: hidden;
                    width: 100%;
                    
                }
                
                .oxy-content-switcher_toggled .oxy-inner-content > *:nth-child(2) {
                    position: relative;
                    opacity: 1;
                    visibility: visible;
                    width: 100%;
                    transform: none;
                }
                
                .oxygen-builder-body .oxy-content-switcher > .oxy-inner-content:empty {
                    min-height: 80px;
                    min-width: 300px;
                }
                
                .oxygen-builder-body $selector .oxy-inner-content > *:nth-child(1).ct-active,
                .oxygen-builder-body $selector .oxy-inner-content > *:nth-child(2).ct-active,
                .oxygen-builder-body $selector .oxy-inner-content > *:nth-child(1).ct-active-parent,
                .oxygen-builder-body $selector .oxy-inner-content > *:nth-child(2).ct-active-parent {
                    position: relative;
                    opacity: 1;
                    visibility: visible;
                    width: 100%;
                }";
        
        
        /**
         * Builder Visibility
         */
        if ((isset($options["oxy-content-switcher_builder_visibility"]) && $options["oxy-content-switcher_builder_visibility"] === "both")) {
            
            $css .= ".oxygen-builder-body $selector .oxy-inner-content > *:nth-child(1),
                    .oxygen-builder-body $selector .oxy-inner-content > *:nth-child(2) {
                        position: relative;
                        opacity: 1;
                        visibility: visible;
                        width: 100%;
                    }";
            
        }
        
        return $css;
        
    }
    

}

new ExtraContentSwitcher();