<?php
namespace Oxygen\OxyExtended;

/**
 * Read More.
 */
class OEReadMore extends \OxyExtendedEl {

    public $js_added = false;

    /**
	 * Retrieve Read More / Less Feed element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Read More / Less', 'oxy-extended' );
	}

    /**
	 * Retrieve Read More Feed element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_read_more_feed';
	}

    /**
	 * Element Subsection.
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

    /**
	 * Retrieve Read More Feed element icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element icon.
	 */
	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/' . basename( __FILE__, '.php' ) . '.svg';
	}

    public function init() {

        $this->enableNesting();

        // include JS only for builder
        if (isset( $_GET['oxygen_iframe'] )) {
            //add_action( 'wp_head', array( $this, 'output_js' ) );
            add_action( 'wp_footer', array( $this, 'output_init_js' ), 25 );
        }

    }

    /**
	 * Element Controls
	 *
	 * Adds different controls to allow the user to change and customize the element settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function controls() {
        $this->addStyleControl( 
            array(
                "name"         => __( 'Collapsed Height', 'oxy-extended' ),
                "type"         => 'measurebox',
                "selector"     => '.oxy-read-more-inner',
                "property"     => 'max-height',
                "default"      => '200',
                "control_type" => 'slider-measurebox',
            )
        )
        ->setRange( '0', '1000', '1' )->setUnits( 'px', 'px' )->whiteList();

        $this->addOptionControl(
            array(
                "name"    => __( 'Animation Speed', 'oxy-extended' ),
                "slug"    => 'speed',
                "default" => '700',
                "type"    => 'slider-measurebox',
            )
        )->setRange( '0', '2000', '1' )->setUnits( 'ms', 'ms' );

        $this->addOptionControl(
            array(
                
                "name"    => __( 'Height Margin', 'oxy-extended' ),
                "slug"    => 'height_margin',
                "default" => '16',
                "type"    => 'measurebox',
            )
        )->setUnits( 'px', 'px' );

        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => __( 'Fade effect', 'oxy-extended' ),
                'slug' => 'maybe_fade'
            )
            
        )->setDefaultValue( 'false' )
        ->setValue( array( 
             "true" => "Enable", 
            "false" => "Disable"
            )
        )->setValueCSS( array(
            "true" => ".oxy-read-more-inner {
                          position: relative;
                        }
                        
                        .oxy-read-more-inner::after {
                          position:absolute;
                          content: '';
                          top: 0;
                          bottom: 0;
                          left: 0;
                          right: 0;
                          background: linear-gradient(0deg, var(--fade-color) 0%, rgba(255,255,255,0) 100%);
                          visibility: visibility;
                          opacity: 1;
                          transition: all .5s ease;
                        }

                        .oxy-read-more-less_expanded::after {
                          visibility: hidden;
                          opacity: 0;
                        }",
                
        ) );

        $this->addStyleControl( 
            array(
                 "name"         => __( 'Fade color', 'oxy-extended' ),
                 "property"     => '--fade-color',
                 "selector"     => '.oxy-read-more-inner',
                 "control_type" => 'colorpicker',
                 "condition"    => 'maybe_fade=true'
            )
         );

        /**
         * Link.
         */
        $link_section = $this->addControlSection( 'link_section', __( 'Read More Link', 'oxy-extended' ), "assets/icon.png", $this );
        $link_selector = '.oxy-read-more-link';

        $link_text_section = $link_section->addControlSection( 'link_text_section', __( 'Link Text', 'oxy-extended' ), "assets/icon.png", $this );

        $link_text_section->addOptionControl(
            array(
                "type"    => 'textfield',
                "name"    => __( 'Read More Text', 'oxy-extended' ),
                "slug"    => 'open_text',
                "default" => 'Read More',
            )
        );
            
        $link_text_section->addOptionControl(
            array(
                "type"    => 'textfield',
                "name"    => __( 'Close Text', 'oxy-extended' ),
                "slug"    => 'close_text',
                "default" => 'Close',
            )
        );

        $link_color_section = $link_section->addControlSection( "link_color_section", __( "Colors", 'oxy-extended' ), "assets/icon.png", $this );
        
        $link_color_section->addStyleControl(
            array(
                "name"     => __( 'Link Color', 'oxy-extended' ),
                "property" => 'color',
                "selector" => $link_selector
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name" => __( 'Link Hover Color', 'oxy-extended' ),
                "property" => 'color',
                "selector" => $link_selector .":hover",
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name"     => __( 'Background Color', 'oxy-extended' ),
                "property" => 'background-color',
                "selector" => $link_selector
            )
        );
        
        $link_color_section->addStyleControl(
            array(
                "name"     => __( 'Background Hover Color', 'oxy-extended' ),
                "property" => 'background-color',
                "selector" => $link_selector .":hover",
            )
        );

        $link_section->typographySection( __( 'Typography', 'oxy-extended' ), $link_selector, $this );
        
        $link_spacing_section = $link_section->addControlSection( "link_spacing_section", __( "Spacing", 'oxy-extended' ), "assets/icon.png", $this );
        
        $link_spacing_section->addPreset(
            "padding",
            "link_padding",
            __( "Padding", 'oxy-extended' ),
            $link_selector
        )->whiteList();
        
        $link_spacing_section->addPreset(
            "margin",
            "link_margin",
            __( "Margin", 'oxy-extended' ),
            $link_selector
        )->whiteList();

    }

    public function output_js() {
        wp_enqueue_script( 'readmore-js', OXY_EXTENDED_URL . 'assets/js/readmore.min.js', filemtime( OXY_EXTENDED_DIR . 'assets/js/readmore.min.js' ), true );
    }

    public function output_init_js() { ?>

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
                      beforeToggle: function(trigger, element, expanded) {
                        if(!expanded) { // The "Close" link was clicked
                          readMore.addClass('oxy-read-more-less_expanded');
                        } else {
                          readMore.removeClass('oxy-read-more-less_expanded');
                        }
                      }
                });
            });

             $( '.oxy-read-more-link' ).next( '.oxy-read-more-link' ).remove();
        }</script>

    <?php }

    public function customCSS( $options, $selector ) {

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
               --fade-color: #fff;
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

    /**
	 * Render Read More Feed element output on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $options  Element options.
	 * @param  mixed $defaults Element defaults.
	 * @param  mixed $content  Element content.
	 * @return void
	 */
	public function render( $options, $defaults, $content ) {

        $open_text     = isset( $options['open_text'] ) ? esc_attr( $options['open_text'] ) : __( 'Read More', 'oxy-extended' );
        $close_text    = isset( $options['close_text'] ) ? esc_attr( $options['close_text'] ) : __( 'Close', 'oxy-extended' );
        $speed         = isset( $options['speed'] ) ? esc_attr( $options['speed'] ) : '700';
        $height_margin = isset( $options['height_margin'] ) ? esc_attr( $options['height_margin'] ) : '16';

        $output  = '';
        $output .= '<div id="'. esc_attr( $options['selector'] ) .'-inner" class="oxy-read-more-inner oxy-inner-content" data-margin="' . $height_margin . '" data-speed="' . $speed . '" data-open="' . $open_text . '" data-close="' . $close_text . '">';

        if ( ! empty( $content ) ) {
            ob_start();
            echo do_shortcode( $content );
            $output .= ob_get_clean();
        }
        $output .= '</div>';

        //For Styling in builder only, is removed on frontend
        $output .= '<a class="oxy-read-more-link">'.$open_text.'</a>';

        echo $output;

        // add JavaScript code only once and if shortcode presented.
        if ( $this->js_added !== true ) {
            add_action( 'wp_footer', array( $this, 'output_js' ) );
            add_action( 'wp_footer', array( $this, 'output_init_js' ), 25 );
            $this->js_added = true;
        }
    }
}

new OEReadMore();
