<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

class OEHotspot extends \OxyExtendedEl {

	public $css_added = false;

	/**
	 * Retrieve hotspots element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Image Hotspots', 'oxy-extended' );
	}

	/**
	 * Retrieve hotspots element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_hotspots';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

	/**
	 * Retrieve hotspots element icon.
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
		$this->El->useAJAXControls();
		$this->enableNesting();
		add_action( 'ct_toolbar_component_settings', function() {
			?>
			<label class="oxygen-control-label oxy-oe_hotspots-elements-label"
				ng-if="isActiveName('oxy-oe_hotspots')&&!hasOpenTabs('oxy-oe_hotspots')" style="text-align: center; margin-top: 15px;">
				<?php _e( 'Available Elements', 'oxy-extended' ); ?>
			</label>
			<div class="oxygen-control-row oxy-oe_hotspots-elements"
				ng-if="isActiveName('oxy-oe_hotspots')&&!hasOpenTabs('oxy-oe_hotspots')">
				<?php do_action( 'oxygen_add_plus_oehsmarker_comp' ); ?>
			</div>
		<?php }, 30 );
	}

	public function controls() {
		$image = $this->addCustomControl(
			"<div class=\"oxygen-control\">			
				<div class=\"oxygen-file-input\">
					<input type=\"text\" spellcheck=\"false\" ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'ct_image','oxy-oe_hotspots_oe_hs_image'); iframeScope.parseImageShortcode()\" ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oxy-oe_hotspots_oe_hs_image')}\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_hotspots_oe_hs_image']\" ng-model-options=\"{ debounce: 10 }\" class=\"ng-pristine ng-valid oxygen-option-default ng-touched\">
					<div class=\"oxygen-file-input-browse\" data-mediatitle=\"Select Image\" data-mediabutton=\"Select Image\" data-mediaproperty=\"oxy-oe_hotspots_oe_hs_image\" data-mediatype=\"mediaUrl\" data-returnvalue=\"url\">" . __( 'browse', 'oxy-extended' ) . "</div>
				</div>
			</div>",
			'oe_hs_image'
		);
		$image->setParam( 'heading', __( 'Upload Image', 'oxy-extended' ) );
		$image->setParam( 'description', __( 'Click on Apply Params button to show image in the editor.', 'oxy-extended' ) );
		$image->rebuildElementOnChange();

		$image_size = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Image Size', 'oxy-extended' ),
				'slug'    => 'image_size',
				'value'   => OE_Helper::get_image_sizes(),
				'default' => 'full',
				'css'     => false,
			)
		);
		$image_size->rebuildElementOnChange();

		$this->addStyleControl([
			'selector'     => 'img.oe-hs-image',
			'property'     => 'width',
			'slug'         => 'oe_hs_image_width',
			'control_type' => 'measurebox',
		])->setUnits( 'px', 'px' )->setParam( 'hide_wrapper_end', true );

		$this->addStyleControl([
			'selector'     => 'img.oe-hs-image',
			'property'     => 'height',
			'slug'         => 'oe_hs_image_height',
			'control_type' => 'measurebox',
		])->setUnits( 'px', 'px' )->setParam( 'hide_wrapper_start', true );

		$img_alt = $this->addOptionControl([
			'type' => 'textfield',
			'name' => __( 'Alt Text', 'oxy-extended' ),
			'slug' => 'oe_hs_image_alt',
		]);
		$img_alt->setParam( 'dynamicdatacode', '<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesContentMode" callback="iframeScope.oeDynamicHPImageAlt">data</div>' );
	}

	public function render( $options, $defaults, $content ) {
		$img_alt = '';

		if ( isset( $options['oe_hs_image_alt'] ) ) {
			$img_alt = $options['oe_hs_image_alt'];
			if ( strstr( $img_alt, 'oedata_' ) ) {
				$img_alt = base64_decode( str_replace( 'oedata_', '', $img_alt ) );
				$shortcode = oxyextend_gsss( $this->El, $img_alt );
				$img_alt = do_shortcode( $shortcode );
			}
		}

		if ( isset( $options['oe_hs_image'] ) ) {
			$image = $options['oe_hs_image'];
			$image_id = attachment_url_to_postid( $image );
			$image_size = $options['image_size'];
			$image_src = wp_get_attachment_image_src ($image_id, $image_size );
			if ( strstr( $image, 'oedata_' ) ) {
				$image = base64_decode( str_replace( 'oedata_', '', $image ) );
				$shortcode = oxyextend_gsss( $this->El, $image );
				$image = do_shortcode( $shortcode );
			}

			$img_width = isset( $options['oe_hs_image_width'] ) ? ' width="' . intval( $options['oe_hs_image_width'] ) . '"' : '';
			$img_height = isset( $options['oe_hs_image_height'] ) ? ' height="' . intval( $options['oe_hs_image_height'] ) . '"' : '';

			echo '<img src="' . esc_url( $image_src[0] ) . '" class="oe-hs-image" alt="' . esc_attr( $img_alt ) . '"' . $img_width . $img_height . '/>';
		}

		echo '<div class="oe-hotspots-wrap oxy-inner-content">' . do_shortcode( $content ) . '</div>';
	}

	public function customCSS( $original, $selector ) {
		$css = '';
		if ( ! $this->css_added ) {
			$css = '.oxy-oe-hotspots { display: inline-block; width: 100%; min-height: 40px; position: relative; margin: 20px auto; }
				.oxy-oe-hotspots img, .oe-hotspots-wrap img { max-width: 100%; height: auto; }';

			$this->css_added = true;
		}

		return $css;
	}
}

new OEHotspot();
