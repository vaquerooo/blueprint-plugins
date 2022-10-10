<?php

class ExtraCopyrightText extends OxygenExtraElements {
        

	function name() {
        return 'Copyright Year';
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return "dynamic";
    }
    
    function tag() {
        return array('default' => 'span');
    }
    
    function init() {
        
        // Allow textfields to be empty and not be replaced by defaults
        add_filter("oxy_allowed_empty_options_list", array( $this, "allowedEmptyOptions") );
    
    }

    function render($options, $defaults, $content) {
        
        // get options 
        $copyright  = isset( $options['copyright'] ) ? esc_attr($options['copyright']) : '';
        $text_before = isset( $options['text_before'] ) ? esc_attr($options['text_before']) : '';
        $text_after = isset( $options['text_after'] ) ? esc_attr($options['text_after']) : '';
        $text_first = isset( $options['text_first'] ) ? esc_attr($options['text_first']) : '';
        
        $output = $text_before .' '. $copyright . '&nbsp;';

        if ( '' !== $text_first && date( 'Y' ) !== $text_first ) {
            $output .= $text_first . ' &#x02013; ';
        }

        $output .= date( 'Y' ) .' '. $text_after;
        
        echo $output;
        
    }

    function class_names() {
        return array();
    }

    function controls() {
        
       $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('First Year (YYYY)'),
                "slug" => 'text_first',
                "default" => '',
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text Before'),
                "slug" => 'text_before',
                "default" => 'Copyright',
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text After'),
                "slug" => 'text_after',
                "default" => 'All Rights Reserved',
                
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Copyright Symbol'),
                "slug" => 'copyright',
                "default" => '©',
            )
        )->rebuildElementOnChange();
        
        $this->typographySection('Typography', '',$this);

    }
    
    function allowedEmptyOptions($options) {

        $options_to_add = array(
            "oxy-copyright-year_text_before",
            "oxy-copyright-year_text_after",
            "oxy-copyright-year_copyright",
        );

        $options = array_merge($options, $options_to_add);

        return $options;
    } 
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }

}

new ExtraCopyrightText();