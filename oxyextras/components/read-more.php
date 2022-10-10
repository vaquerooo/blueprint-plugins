<?php

class ExtraReadMore extends OxygenExtraElements {
    
    var $js_added = false;

	function name() {
        return __('Read More / Less'); 
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return "interactive"; 
    }
    
    function init() {
        
        $this->enableNesting();
        
        // include JS only for builder
        if (isset( $_GET['oxygen_iframe'] )) {
            //add_action( 'wp_head', array( $this, 'output_js' ) );
            add_action( 'wp_footer', array( $this, 'output_init_js' ), 25 );
        }
        
    }
    
    
    function render($options, $defaults, $content) {
        
        $open_text  = isset( $options['open_text'] ) ? esc_attr($options['open_text']) : 'Read More';
        $close_text = isset( $options['close_text'] ) ? esc_attr($options['close_text']) : 'Close';
        $speed = isset( $options['speed'] ) ? esc_attr($options['speed']) : '700';
        $height_margin = isset( $options['height_margin'] ) ? esc_attr($options['height_margin']) : '16';
        
        $output = '';
        
        $output .= '<div id="'. esc_attr($options['selector']) .'-inner" class="oxy-read-more-inner oxy-inner-content" data-margin="' . $height_margin . '" data-speed="' . $speed . '" data-open="' . $open_text . '" data-close="' . $close_text . '">';
        
        if ($content) {
            
            $output .= do_shortcode($content); 
            
        } 
        
        $output .= '</div>';
        
        //For Styling in builder only, is removed on frontend
        $output .= '<a class="oxy-read-more-link">'.$open_text.'</a>';
        
        echo $output;
       
        
         // add JavaScript code only once and if shortcode presented
        if ($this->js_added !== true) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            add_action( 'wp_footer', array( $this, 'output_init_js' ), 25 );
            $this->js_added = true;
        }
    }

    function class_names() {
        return array();
    }

    function controls() {
        
        
        $this->addStyleControl( 
            array(
                 "name" => __('Collapsed Height'),
                "type" => 'measurebox',
                "selector" => '.oxy-read-more-inner',
                "property" => 'max-height',
                "default" => '200',
                "control_type" => 'slider-measurebox',
            )
        )
        ->setRange('0','1000','1')->setUnits('px','px')->whiteList();
        
        
        $this->addOptionControl(
            array(
                
                "name" => __('Animation Speed'),
                "slug" => 'speed',
                "default" => '700',
                
                "type" => 'slider-measurebox',
            )
        )->setRange('0','2000','1')->setUnits('ms','ms');
        
        
        $this->addOptionControl(
            array(
                
                "name" => __('Height Margin'),
                "slug" => 'height_margin',
                "default" => '16',
                "type" => 'measurebox',
            )
        )->setUnits('px','px');
        
        
        
        
        /**
         * Link
         */
        $link_section = $this->addControlSection("link_section", __("Read More Link"), "assets/icon.png", $this);
        $link_selector = '.oxy-read-more-link';
        
        
        $link_text_section = $link_section->addControlSection("link_text_section", __("Link Text"), "assets/icon.png", $this);
        
        $link_text_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Read More Text'),
                "slug" => 'open_text',
                "default" => 'Read More',
            )
        );
            
        $link_text_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Close Text'),
                "slug" => 'close_text',
                "default" => 'Close',
            )
        );
        
        
        $link_color_section = $link_section->addControlSection("link_color_section", __("Colors"), "assets/icon.png", $this);
        
        $link_color_section->addStyleControl(
            array(
                "name"     => 'Link Color',
                "property" => 'color',
                "selector" => $link_selector
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name" => 'Link Hover Color',
                "property" => 'color',
                "selector" => $link_selector .":hover",
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name"     => 'Background Color',
                "property" => 'background-color',
                "selector" => $link_selector
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name"     => 'Background Hover Color',
                "property" => 'background-color',
                "selector" => $link_selector .":hover",
            )
        );
        
        $link_section->typographySection('Typography', $link_selector,$this);
        
        $link_spacing_section = $link_section->addControlSection("link_spacing_section", __("Spacing"), "assets/icon.png", $this);
        
        $link_spacing_section->addPreset(
            "padding",
            "link_padding",
            __("Padding"),
            $link_selector
        )->whiteList();
        
        $link_spacing_section->addPreset(
            "margin",
            "link_margin",
            __("Margin"),
            $link_selector
        )->whiteList();
        
        
        
    }
    
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-read-more-less {
                    display: flex;
                    flex-direction: column; 
                    width: 100%;
                }
        
                .oxy-read-more-inner {
                   display: block;
                   max-height: 200px;
                   overflow: hidden;
                   width: 100%;
                }
                
                .oxy-read-more-inner:empty {
                    min-height: 80px;
                }
               
                .oxy-read-more-link {
                    margin-left: auto;
                    cursor: pointer;
                }
                
                .oxy-read-more-link {
                    position: relative;
                    width: 100%;
                }

                .oxy-read-more-link span {
                    position: absolute;
                    right: 0;
                    width: 100%;
                }
                ";
        
        return $css;
    }
    
    function output_js() { 

       wp_enqueue_script( 'readmore-js', plugin_dir_url( __FILE__ ) . 'assets/readmore.min.js', '', '3.0.0' );
        
    }
    
    function output_init_js() { ?>
            
            <script type="text/javascript">
            jQuery(document).ready(oxygen_init_readmore);
            function oxygen_init_readmore($) {
                
                $('.oxy-read-more-inner').each(function(){
               
                    let readMore = $(this),
                        readMoreID = $(this).attr('ID'),
                        openText = readMore.data( 'open' ),
                        closeText = readMore.data( 'close' ),
                        speed = readMore.data( 'speed' ),
                        heightMargin = readMore.data( 'margin' );
                    
                    new Readmore('#' + readMoreID, {
                          speed: speed,
                          moreLink: '<a href=# class=oxy-read-more-link>' + openText + '</a>',
                          lessLink: '<a href=# class=oxy-read-more-link>' + closeText + '</a>',
                          embedCSS: false,
                          heightMargin: heightMargin,
                        });
                    
                });
                 
                 $('.oxy-read-more-link').next('.oxy-read-more-link').remove();
                
            }</script>
        
    <?php }

}

new ExtraReadMore();