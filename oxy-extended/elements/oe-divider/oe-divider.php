<?php
namespace Oxygen\OxyExtended;

/**
 * Divider Element
 */
class OEDivider extends \OxyExtendedEl {

	public $css_added = false;
	public $countdown_js_code = false;

	/**
	 * Retrieve divider element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Divider', 'oxy-extended' );
	}

	/**
	 * Retrieve divider element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_divider';
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
	 * Retrieve divider element icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element icon.
	 */
	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/' . basename( __FILE__, '.php' ) . '.svg';
	}

	/**
	 * Element HTML tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return tag
	 */
	public function tag() {
		return 'div';
	}

	private static function get_additional_styles() {
		static $additional_styles = null;

		if ( null !== $additional_styles ) {
			return $additional_styles;
		}
		$additional_styles = [];
		/**
		 * Additional Styles.
		 *
		 * Filters the styles used by Oxy extended to add additional divider styles.
		 *
		 * @since 1.0.0
		 *
		 * @param array $additional_styles Additional Oxy Extended divider styles.
		 */
		$additional_styles = apply_filters( 'oxyextend_divider_additional_styles', $additional_styles );
		return $additional_styles;
	}

	public function get_separator_styles() {
		return array_merge(
			self::get_additional_styles(),
			[
				'curly'   => [
					'label' => _x( 'Curly', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M0,21c3.3,0,8.3-0.9,15.7-7.1c6.6-5.4,4.4-9.3,2.4-10.3c-3.4-1.8-7.7,1.3-7.3,8.8C11.2,20,17.1,21,24,21"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'curved'   => [
					'label' => _x( 'Curved', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M0,6c6,0,6,13,12,13S18,6,24,6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'multiple'   => [
					'label' => _x( 'Multiple', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M24,8v12H0V8H24z M24,4v1H0V4H24z"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => false,
					'round' => false,
					'group' => 'pattern',
				],
				'slashes' => [
					'label' => _x( 'Slashes', 'shapes', 'oxy-extended' ),
					'shape' => '<g transform="translate(-12.000000, 0)"><path d="M28,0L10,18"/><path d="M18,0L0,18"/><path d="M48,0L30,18"/><path d="M38,0L20,18"/></g>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'view_box' => '0 0 20 16',
					'group' => 'line',
				],
				'squared' => [
					'label' => _x( 'Squared', 'shapes', 'oxy-extended' ),
					'shape' => '<polyline points="0,6 6,6 6,18 18,18 18,6 24,6 	"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'wavy'   => [
					'label' => _x( 'Wavy', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M0,6c6,0,0.9,11.1,6.9,11.1S18,6,24,6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'zigzag'  => [
					'label' => _x( 'Line - Zigzag', 'shapes', 'oxy-extended' ),
					'shape' => '<polyline points="0,18 12,6 24,18 "/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'arrows'   => [
					'label' => _x( 'Arrows', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M14.2,4c0.3,0,0.5,0.1,0.7,0.3l7.9,7.2c0.2,0.2,0.3,0.4,0.3,0.7s-0.1,0.5-0.3,0.7l-7.9,7.2c-0.2,0.2-0.4,0.3-0.7,0.3s-0.5-0.1-0.7-0.3s-0.3-0.4-0.3-0.7l0-2.9l-11.5,0c-0.4,0-0.7-0.3-0.7-0.7V9.4C1,9,1.3,8.7,1.7,8.7l11.5,0l0-3.6c0-0.3,0.1-0.5,0.3-0.7S13.9,4,14.2,4z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => true,
					'round' => true,
					'group' => 'pattern',
				],
				'pluses'   => [
					'label' => _x( 'Pluses', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M21.4,9.6h-7.1V2.6c0-0.9-0.7-1.6-1.6-1.6h-1.6c-0.9,0-1.6,0.7-1.6,1.6v7.1H2.6C1.7,9.6,1,10.3,1,11.2v1.6c0,0.9,0.7,1.6,1.6,1.6h7.1v7.1c0,0.9,0.7,1.6,1.6,1.6h1.6c0.9,0,1.6-0.7,1.6-1.6v-7.1h7.1c0.9,0,1.6-0.7,1.6-1.6v-1.6C23,10.3,22.3,9.6,21.4,9.6z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'rhombus'   => [
					'label' => _x( 'Rhombus', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M12.7,2.3c-0.4-0.4-1.1-0.4-1.5,0l-8,9.1c-0.3,0.4-0.3,0.9,0,1.2l8,9.1c0.4,0.4,1.1,0.4,1.5,0l8-9.1c0.3-0.4,0.3-0.9,0-1.2L12.7,2.3z"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'parallelogram'   => [
					'label' => _x( 'Parallelogram', 'shapes', 'oxy-extended' ),
					'shape' => '<polygon points="9.4,2 24,2 14.6,21.6 0,21.6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'rectangles'   => [
					'label' => _x( 'Rectangles', 'shapes', 'oxy-extended' ),
					'shape' => '<rect x="15" y="0" width="30" height="30"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => true,
					'group' => 'pattern',
					'view_box' => '0 0 60 30',
				],
				'dots_tribal'   => [
					'label' => _x( 'Dots', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M3,10.2c2.6,0,2.6,2,2.6,3.2S4.4,16.5,3,16.5s-3-1.4-3-3.2S0.4,10.2,3,10.2z M18.8,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.2-1.4-3.2-3.2S17,10.2,18.8,10.2z M34.6,10.2c1.5,0,2.6,1.4,2.6,3.2s-0.5,3.2-1.9,3.2c-1.5,0-3.4-1.4-3.4-3.2S33.1,10.2,34.6,10.2z M50.5,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.3-0.9-3.3-2.6S48.7,10.2,50.5,10.2z M66.2,10.2c1.5,0,3.4,1.4,3.4,3.2s-1.9,3.2-3.4,3.2c-1.5,0-2.6-0.4-2.6-2.1S64.8,10.2,66.2,10.2z M82.2,10.2c1.7,0.8,2.6,1.4,2.6,3.2s-0.1,3.2-1.6,3.2c-1.5,0-3.7-1.4-3.7-3.2S80.5,9.4,82.2,10.2zM98.6,10.2c1.5,0,2.6,0.4,2.6,2.1s-1.2,4.2-2.6,4.2c-1.5,0-3.7-0.4-3.7-2.1S97.1,10.2,98.6,10.2z M113.4,10.2c1.2,0,2.2,0.9,2.2,3.2s-0.1,3.2-1.3,3.2s-3.1-1.4-3.1-3.2S112.2,10.2,113.4,10.2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'trees_2_tribal'   => [
					'label' => _x( 'Fir Tree', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M111.9,18.3v3.4H109v-3.4H111.9z M90.8,18.3v3.4H88v-3.4H90.8z M69.8,18.3v3.4h-2.9v-3.4H69.8z M48.8,18.3v3.4h-2.9v-3.4H48.8z M27.7,18.3v3.4h-2.9v-3.4H27.7z M6.7,18.3v3.4H3.8v-3.4H6.7z M46.4,4l4.3,4.8l-1.8,0l3.5,4.4l-2.2-0.1l3,3.3l-11,0.4l3.6-3.8l-2.9-0.1l3.1-4.2l-1.9,0L46.4,4z M111.4,4l2.4,4.8l-1.8,0l3.5,4.4l-2.5-0.1l3.3,3.3h-11l3.1-3.4l-2.5-0.1l3.1-4.2l-1.9,0L111.4,4z M89.9,4l2.9,4.8l-1.9,0l3.2,4.2l-2.5,0l3.5,3.5l-11-0.4l3-3.1l-2.4,0L88,8.8l-1.9,0L89.9,4z M68.6,4l3,4.4l-1.9,0.1l3.4,4.1l-2.7,0.1l3.8,3.7H63.8l2.9-3.6l-2.9,0.1L67,8.7l-2,0.1L68.6,4z M26.5,4l3,4.4l-1.9,0.1l3.7,4.7l-2.5-0.1l3.3,3.3H21l3.1-3.4l-2.5-0.1l3.2-4.3l-2,0.1L26.5,4z M4.9,4l3.7,4.8l-1.5,0l3.1,4.2L7.6,13l3.4,3.4H0l3-3.3l-2.3,0.1l3.5-4.4l-2.3,0L4.9,4z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'rounds_tribal'   => [
					'label' => _x( 'Half Rounds', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M11.9,15.9L11.9,15.9L0,16c-0.2-3.7,1.5-5.7,4.9-6C10,9.6,12.4,14.2,11.9,15.9zM26.9,15.9L26.9,15.9L15,16c0.5-3.7,2.5-5.7,5.9-6C26,9.6,27.4,14.2,26.9,15.9z M37.1,10c3.4,0.3,5.1,2.3,4.9,6H30.1C29.5,14.4,31.9,9.6,37.1,10z M57,15.9L57,15.9L45,16c0-3.4,1.6-5.4,4.9-5.9C54.8,9.3,57.4,14.2,57,15.9z M71.9,15.9L71.9,15.9L60,16c-0.2-3.7,1.5-5.7,4.9-6C70,9.6,72.4,14.2,71.9,15.9z M82.2,10c3.4,0.3,5,2.3,4.8,6H75.3C74,13,77.1,9.6,82.2,10zM101.9,15.9L101.9,15.9L90,16c-0.2-3.7,1.5-5.7,4.9-6C100,9.6,102.4,14.2,101.9,15.9z M112.1,10.1c2.7,0.5,4.3,2.5,4.9,5.9h-11.9l0,0C104.5,14.4,108,9.3,112.1,10.1z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
				'leaves_tribal'   => [
					'label' => _x( 'Leaves', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M3,1.5C5,4.9,6,8.8,6,13s-1.7,8.1-5,11.5C0.3,21.1,0,17.2,0,13S1,4.9,3,1.5z M16,1.5c2,3.4,3,7.3,3,11.5s-1,8.1-3,11.5c-2-4.1-3-8.3-3-12.5S14,4.3,16,1.5z M29,1.5c2,4.8,3,9.3,3,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S27,4.9,29,1.5z M41.1,1.5C43.7,4.9,45,8.8,45,13s-1,8.1-3,11.5c-2-3.4-3-7.3-3-11.5S39.7,4.9,41.1,1.5zM55,1.5c2,2.8,3,6.3,3,10.5s-1.3,8.4-4,12.5c-1.3-3.4-2-7.3-2-11.5S53,4.9,55,1.5z M68,1.5c2,3.4,3,7.3,3,11.5s-0.7,8.1-2,11.5c-2.7-4.8-4-9.3-4-13.5S66,3.6,68,1.5z M82,1.5c1.3,4.8,2,9.3,2,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S79.3,4.9,82,1.5z M94,1.5c2,3.4,3,7.3,3,11.5s-1.3,8.1-4,11.5c-1.3-1.4-2-4.3-2-8.5S92,6.9,94,1.5z M107,1.5c2,2.1,3,5.3,3,9.5s-0.7,8.7-2,13.5c-2.7-3.4-4-7.3-4-11.5S105,4.9,107,1.5z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 117 26',
				],
				'stripes_tribal'   => [
					'label' => _x( 'Stripes', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M54,1.6V26h-9V2.5L54,1.6z M69,1.6v23.3L60,26V1.6H69z M24,1.6v23.5l-9-0.6V1.6H24z M30,0l9,0.7v24.5h-9V0z M9,2.5v22H0V3.7L9,2.5z M75,1.6l9,0.9v22h-9V1.6z M99,2.7v21.7h-9V3.8L99,2.7z M114,3.8v20.7l-9-0.5V3.8L114,3.8z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
				'squares_tribal'   => [
					'label' => _x( 'Squares', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M46.8,7.8v11.5L36,18.6V7.8H46.8z M82.4,7.8L84,18.6l-12,0.7L70.4,7.8H82.4z M0,7.8l12,0.9v9.9H1.3L0,7.8z M30,7.8v10.8H19L18,7.8H30z M63.7,7.8L66,18.6H54V9.5L63.7,7.8z M89.8,7L102,7.8v10.8H91.2L89.8,7zM108,7.8l12,0.9v8.9l-12,1V7.8z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'trees_tribal'   => [
					'label' => _x( 'Trees', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M6.4,2l4.2,5.7H7.7v2.7l3.8,5.2l-3.8,0v7.8H4.8v-7.8H0l4.8-5.2V7.7H1.1L6.4,2z M25.6,2L31,7.7h-3.7v2.7l4.8,5.2h-4.8v7.8h-2.8v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L25.6,2z M47.5,2l4.2,5.7h-3.3v2.7l3.8,5.2l-3.8,0l0.4,7.8h-2.8v-7.8H41l4.8-5.2V7.7h-3.7L47.5,2z M66.2,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H65v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L66.2,2zM87.4,2l4.8,5.7h-2.9v3.1l3.8,4.8l-3.8,0v7.8h-2.8v-7.8h-4.8l4.8-4.8V7.7h-3.7L87.4,2z M107.3,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H106v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L107.3,2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 123 26',
				],
				'planes_tribal'   => [
					'label' => _x( 'Tribal', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M29.6,10.3l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8v-2.9h5.8L29.6,10.3z M70.9,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L70.9,9.6z M111.5,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L111.5,9.6z M50.2,2.7l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2L45,10.7h-5.8V7.9H45L50.2,2.7z M11,2l2.1,1.7L9.6,7.2h7V10h-7l3.6,3.3L11,15.5L5.8,10H0V7.2h5.8L11,2z M91.5,2l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8V7.5h5.8L91.5,2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 121 26',
				],
				'x_tribal'   => [
					'label' => _x( 'X', 'shapes', 'oxy-extended' ),
					'shape' => '<path d="M10.7,6l2.5,2.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L0.7,17L4,13.1L0,8.6l2.3-1.3l3.9,3.9L10.7,6z M23.9,6.6l4.2,4.5L32,7.2l2.3,1.3l-4,4.5l3.2,3.9L32,19.1l-3.9-3.3l-4.5,4.3l-2.5-1.9l4.4-5.1l-4.2-3.9L23.9,6.6zM73.5,6L76,8.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L63.5,17l4.1-4.7L63.5,8l2.3-1.3l4.1,3.6L73.5,6z M94,6l2.5,2.6l-4,4.3l4,5.4L94,20.1l-3.9-5l-3.9,4.2L84,17l3.2-3.9L84,8.6l2.3-1.3l3.2,3.9L94,6z M106.9,6l4.5,5.1l3.9-3.9l2.3,1.3l-4,4.5l3.2,3.9l-1.6,2.1l-3.9-4.2l-4.5,5.2l-2.5-1.9l4-5.4l-4-4.3L106.9,6z M53.1,6l2.5,2.6l-4,4.3l4,4.6l-2.5,1.9l-4.5-4.5l-3.5,4.5L43.1,17l3.2-3.9l-4-4.5l2.3-1.3l3.9,3.9L53.1,6z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'zigzag_tribal'   => [
					'label' => _x( 'Tribal - Zigzag', 'shapes', 'oxy-extended' ),
					'shape' => '<polygon points="0,14.4 0,21 11.5,12.4 21.3,20 30.4,11.1 40.3,20 51,12.4 60.6,20 69.6,11.1 79.3,20 90.1,12.4 99.6,20 109.7,11.1 120,21 120,14.4 109.7,5 99.6,13 90.1,5 79.3,14.5 71,5.7 60.6,12.4 51,5 40.3,14.5 31.1,5 21.3,13 11.5,5 	"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
			]
		);
	}

	private function filter_styles_by( $array, $key, $value ) {
		return array_filter( $array, function( $style ) use ( $key, $value ) {
			return $value === $style[ $key ];
		} );
	}

	private function get_options_by_groups( $styles, $group = false ) {
		$groups_new = array();
		$options = array();
		$groups = [
			'line' => [
				'label' => __( 'Line', 'oxy-extended' ),
				'options' => [
					'solid' => __( 'Solid', 'oxy-extended' ),
					'double' => __( 'Double', 'oxy-extended' ),
					'dotted' => __( 'Dotted', 'oxy-extended' ),
					'dashed' => __( 'Dashed', 'oxy-extended' ),
				],
			],
		];
		foreach ( $styles as $key => $style ) {
			if ( ! isset( $groups[ $style['group'] ] ) ) {
				$groups[ $style['group'] ] = [
					'label' => ucwords( str_replace( '_', '', $style['group'] ) ),
					'options' => [],
				];
			}
			$groups[ $style['group'] ]['options'][ $key ] = $style['label'];
		}

		//$groups_new = $groups;

		/* if ( $group && isset( $groups[ $group ] ) ) {
			$groups_new = $groups[ $group ];
		} */

		foreach ( $groups as $group_key => $group_style ) {
			$options = array_merge( $options, $group_style['options'] );
		}

		return $options;
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
		$this->number_controls();
	}

	/**
	 * Controls for front section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function number_controls() {
		$styles = $this->get_separator_styles();

		$divider_style = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Divider Style', 'oxy-extended' ),
				'slug'    => 'oe_divider_style',
				'value'     => $this->get_options_by_groups( $styles ),
				'default' => 'solid',
				'css'     => false,
			)
		);
		$divider_style->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$divider_style->rebuildElementOnChange();

		$this->addStyleControl([
			'selector'     => '.oe-divider-separator',
			'property'     => 'width',
			'name'         => __( 'Width', 'oxy-extended' ),
			'control_type' => 'slider-measurebox',
		])->setUnits( '%', 'px,%' )->setValue( '100' );

		$this->addOptionControl(
			array(
				'type'         => 'buttons-list',
				'name'         => __( 'Alignment', 'oxy-extended' ),
				'slug'         => 'oe_divider_alignment',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
				),
				'default'      => 'center',
			)
		)->setValueCSS( array(
			'left'   => '.oe-divider { text-align: left; } .oe-divider-separator{ margin: 0 auto; margin-left: 0; }',
			'center' => '.oe-divider { text-align: center; } .oe-divider-separator{ margin: 0 auto; }',
			'right'  => '.oe-divider { text-align: right; } .oe-divider-separator{ margin: 0 auto; margin-right: 0; }',
		));

		$this->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Add Element', 'oxy-extended' ),
				'slug'          => 'oe_look',
				'value'         => array(
					'line'      => __( 'None', 'oxy-extended' ),
					'line_text' => __( 'Text', 'oxy-extended' ),
					'line_icon' => __( 'Icon', 'oxy-extended' ),
				),
				'default'       => 'line',
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Text', 'oxy-extended' ),
				'slug'      => 'oe_text',
				'value'     => 'Divider',
				'condition' => 'oe_look=line_text',
			)
		);

		$this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_html_tag',
				'value'   => array(
					'h1'   => __( 'H1', 'oxy-extended' ),
					'h2'   => __( 'H2', 'oxy-extended' ),
					'h3'   => __( 'H3', 'oxy-extended' ),
					'h4'   => __( 'H4', 'oxy-extended' ),
					'h5'   => __( 'H5', 'oxy-extended' ),
					'h6'   => __( 'H6', 'oxy-extended' ),
					'div'  => __( 'div', 'oxy-extended' ),
					'span' => __( 'span', 'oxy-extended' ),
					'p'    => __( 'p', 'oxy-extended' ),
				),
				'default' => 'span',
				'condition' => 'oe_look=line_text',
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'          => 'icon_finder',
				'name'          => __( 'Icon', 'oxy-extended' ),
				'slug'          => 'oe_icon',
				'value'         => 'FontAwesomeicon-check',
				'default'       => 'FontAwesomeicon-check',
				'css'           => false,
				'condition'     => 'oe_look=line_icon',
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'         => 'buttons-list',
				'name'         => __( 'Position', 'oxy-extended' ),
				'slug'         => 'oe_element_align',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
				),
				'default'      => 'center',
				'condition'     => 'oe_look!=line',
			)
		)->rebuildElementOnChange();

		$divider_style_section = $this->addControlSection( 'divider_style_section', __( 'Divider Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$divider_style_section->addStyleControls(
			[
				[
					'selector'     => '.oe-divider-container',
					'name'         => __( 'Color', 'oxy-extended' ),
					'property'     => '--divider-color',
					'control_type' => 'colorpicker',
				],
			]
		);

		$divider_weight = $divider_style_section->addStyleControl([
			'name'         => __( 'Weight', 'oxy-extended' ),
			'selector'     => '.oe-divider-container',
			'property'     => '--divider-border-width',
			'slug'         => 'divider_weight',
			'control_type' => 'slider-measurebox',
			'condition'    => 'oe_divider_style=solid||oe_divider_style=double||oe_divider_style=dotted||oe_divider_style=dashed||oe_divider_style=curly||oe_divider_style=curved||oe_divider_style=slashes||oe_divider_style=squared||oe_divider_style=wavy||oe_divider_style=zigzag',
		])->setUnits( 'px', 'px' )->setRange( '1', '10', '0.1' )->setValue( '1' );

		$divider_pattern_height = $divider_style_section->addStyleControl([
			'name'         => __( 'Size', 'oxy-extended' ),
			'selector'     => '.oe-divider-container',
			'property'     => '--divider-pattern-height',
			'slug'         => 'divider_pattern_height',
			'control_type' => 'slider-measurebox',
			'condition'    => 'oe_divider_style!=solid&&oe_divider_style!=double&&oe_divider_style!=dotted&&oe_divider_style!=dashed',
		])->setUnits( 'px', 'px' )->setRange( '1', '100', '0.1' )->setValue( '20' );

		$divider_pattern_size = $divider_style_section->addStyleControl([
			'name'         => __( 'Amount', 'oxy-extended' ),
			'selector'     => '.oe-divider-container',
			'property'     => '--divider-pattern-size',
			'slug'         => 'divider_pattern_size',
			'control_type' => 'slider-measurebox',
			'condition'    => 'oe_divider_style=curly||oe_divider_style=curved||oe_divider_style=slashes||oe_divider_style=squared||oe_divider_style=wavy||oe_divider_style=zigzag||oe_divider_style=arrows||oe_divider_style=pluses||oe_divider_style=rhombus||oe_divider_style=parallelogram||oe_divider_style=rectangles',
		])->setUnits( 'px', 'px' )->setRange( '1', '100', '0.1' )->setValue( '20' );

		$text_style_section = $this->addControlSection( 'text_style_section', __( 'Text', 'oxy-extended' ), 'assets/icon.png', $this );

		$text_style_section->addStyleControls(
			array(
				array(
					'selector' => '.oe-divider__text',
					'property' => 'color',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'font-family',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'font-weight',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'font-size',
					'value'    => 28,
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'line-height',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'letter-spacing',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'text-decoration',
				),
				array(
					'selector' => '.oe-divider__text',
					'property' => 'text-transform',
				),
			)
		);

		$icon_style_section = $this->addControlSection( 'icon_style_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$icon_style_section->addStyleControls(
			array(
				array(
					'selector'     => '.oe-divider__icon',
					'name'         => __( 'Color', 'oxy-extended' ),
					'property'     => 'fill',
					'control_type' => 'colorpicker',
					'slug'         => 'oe_divider_icon_color',
				),
			)
		);

		$icon_style_section->addStyleControl([
			'name'         => __( 'Size', 'oxy-extended' ),
			'selector'     => '.oe-divider-icon-svg',
			'property'     => 'width|height',
			'slug'         => 'icon_size',
			'control_type' => 'slider-measurebox',
		])->setUnits( 'px', 'px' )->setRange( '1', '100', '1' )->setValue( '15' );
	}

	/**
	 * Build SVG
	 *
	 * Build SVG element markup based on the widgets settings.
	 *
	 * @return string - An SVG element.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function build_svg( $options ) {
		if ( ! isset( $options['oe_divider_style'] ) ) {
			return '';
		}

		if ( 'solid' === $options['oe_divider_style'] || 'double' === $options['oe_divider_style'] || 'dotted' === $options['oe_divider_style'] || 'dashed' === $options['oe_divider_style'] || empty( $options['oe_divider_style'] ) ) {
			return '';
		}

		$svg_shapes = $this->get_separator_styles();
		$divider_style = $options['oe_divider_style'];

		$selected_pattern = $svg_shapes[ $divider_style ];
		$preserve_aspect_ratio = $selected_pattern['preserve_aspect_ratio'] ? 'xMidYMid meet' : 'none';
		$view_box = isset( $selected_pattern['view_box'] ) ? $selected_pattern['view_box'] : '0 0 24 24';

		$attr = [
			'preserveAspectRatio' => $preserve_aspect_ratio,
			'overflow' => 'visible',
			'height' => '100%',
			'viewBox' => $view_box,
		];

		if ( 'line' !== $selected_pattern['group'] ) {
			$attr['fill'] = 'black';
			$attr['stroke'] = 'none';
		} else {
			$attr['fill'] = 'none';
			$attr['stroke'] = 'black';
			$attr['stroke-width'] = '';
			$attr['stroke-linecap'] = 'square';
			$attr['stroke-miterlimit'] = '10';
		}

		$this->add_render_attribute( 'svg', $attr );

		$pattern_attribute_string = $this->get_render_attribute_string( 'svg' );
		$shape = $selected_pattern['shape'];

		return '<svg xmlns="http://www.w3.org/2000/svg" ' . $pattern_attribute_string . '>' . $shape . '</svg>';
	}

	public function svg_to_data_uri( $options, $svg ) {
		return str_replace(
			[ '<', '>', '"', '#' ],
			[ '%3C', '%3E', "'", '%23' ],
			$svg
		);
	}

	public function check_pattern_style( $options ) {
		if ( isset( $options['oe_divider_style'] ) ) {
			if ( '' === $options['oe_divider_style'] || 'solid' === $options['oe_divider_style'] || 'double' === $options['oe_divider_style'] || 'dotted' === $options['oe_divider_style'] || 'dashed' === $options['oe_divider_style'] ) {
				return false;
			} else {
				return true;
			}

			return true;
		}
	}

	public function check_pattern_size( $options ) {
		$styles = $this->get_separator_styles();
		$divider_style = $options['oe_divider_style'];

		$value = array_merge( array_keys( $this->filter_styles_by( $styles, 'supports_amount', false ) ), [
			'',
			'solid',
			'double',
			'dotted',
			'dashed',
		] );

		if ( in_array( $divider_style, $value ) ) {
			return false;
		}

		return true;
	}

	public function render_icon( $options ) {
		if ( 'line_icon' === $options['oe_look'] && isset( $options['oe_icon'] ) ) { ?>
			<span class="oe-divider__element oe-divider__icon oe-icon">
				<?php
					global $oxygen_svg_icons_to_load;

					$oxygen_svg_icons_to_load[] = $options['oe_icon'];

					echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-divider-icon-svg"><use xlink:href="#' . $options['oe_icon'] . '"></use></svg>';
				?>
			</span>
			<?php
		}
	}

	/**
	 * Render counter element output on the frontend.
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
		$uid = str_replace( '-', '', $options['selector'] ) . get_the_ID();
		$styles = $this->get_separator_styles();
		$svg_code = $this->build_svg( $options );
		$has_icon = 'line_icon' === ( $options['oe_look'] ) && isset( $options['oe_icon'] );
		$has_text = 'line_text' === ( $options['oe_look'] ) && isset( $options['oe_text'] );
		$divider_style = $options['oe_divider_style'];
		$pattern_check = $this->check_pattern_style( $options );
		$pattern_size = $this->check_pattern_size( $options );

		$keys = array_keys( $this->filter_styles_by( $styles, 'supports_amount', false ) );

		$container_key = 'container' . $uid;
		$wrapper_key = 'wrapper' . $uid;

		$this->add_render_attribute( $container_key, 'class', 'oe-divider-container' );

		if ( $pattern_check ) {
			$this->add_render_attribute( $container_key, 'class', 'oe-widget-divider--separator-type-pattern' );
		}

		if ( $pattern_size ) {
			$this->add_render_attribute( $container_key, 'class', 'oe-widget-divider--pattern-size' );
		}

		$this->add_render_attribute( $container_key, 'class', 'oe-widget-divider--view-' . $options['oe_look'] );
		$this->add_render_attribute( $container_key, 'class', 'oe-widget-divider--element-align-' . $options['oe_element_align'] );
		$this->add_render_attribute( $container_key, 'style', '--divider-border-style:' . $divider_style );

		if ( in_array( $divider_style, $keys ) ) {
			$this->add_render_attribute( $container_key, 'class', 'oe-widget-divider--no-spacing' );
		}

		$this->add_render_attribute( $wrapper_key, 'class', 'oe-divider' );

		if ( ! empty( $svg_code ) ) {
			$this->add_render_attribute( $wrapper_key, 'style', '--divider-pattern-url: url("data:image/svg+xml,' . $this->svg_to_data_uri( $content, $svg_code ) . '");' );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( $container_key ); ?>>
			<div <?php echo $this->get_render_attribute_string( $wrapper_key ); ?>>
				<span class="oe-divider-separator">
					<?php
					if ( $has_icon ) {
						$this->render_icon( $options );
					} elseif ( $has_text ) { ?>
					<span class="oe-divider__text oe-divider__element">
						<?php echo $options['oe_text']; ?>
					</span>
					<?php } ?>
				</span>
			</div>
		</div>
		<?php
	}

	public function customCSS( $original, $selector ) {
		$css = '';

		if ( ! $this->css_added ) {
			$this->css_added = true;
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
		}

		$prefix = $this->El->get_tag();

		if ( isset( $original[$prefix . '_oe_divider_style'] ) ) {
			$css .= $selector . ' .oe-divider-container { --divider-border-style: ' . $original[$prefix . '_oe_divider_style'] . ';}';
		}

		return $css;
	}
}

$oedivider = new OEDivider();
