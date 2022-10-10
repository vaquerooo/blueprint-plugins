<?php
/**
 * Automatic.css Base UI file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Admin\UI_Elements;

use Automatic_CSS\Exceptions\Invalid_Variable;
use Automatic_CSS\Plugin;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Model\Config\Variables;

/**
 * Base UI class.
 */
abstract class Base {

	/**
	 * Enable rendering the wrapper around the element.
	 *
	 * @var boolean
	 */
	private $enable_outside_wrapper_rendering = false;

	/**
	 * Render options.
	 *
	 * @var array
	 */
	protected $render_options = array();

	/**
	 * Constructor
	 *
	 * @param string $var_id Variable ID.
	 * @param array  $var_options Variable's options.
	 * @param string $var_value Variable's current value.
	 */
	public function __construct( $var_id, $var_options, $var_value = null ) {
		$this->render_options = $var_options;
		$this->render_options['base_name'] = 'automatic_css_settings';
		$this->render_options['id'] = $var_id;
		$this->render_options['value'] = $var_value;
		if ( null === $this->render_options['value'] ) {
			// no value was passed, let's set the default, if present.
			$this->render_options['value'] = array_key_exists( 'default', $var_options ) ? $var_options['default'] : '';
		}
		$this->render_options['default'] = array_key_exists( 'default', $var_options ) ? $var_options['default'] : '';
		$hidden = array_key_exists( 'hidden', $var_options ) ? (bool) $var_options['hidden'] : false;
		$this->render_options['var_classes'] = $hidden ? ' hidden' : '';
		$this->render_options['var_classes'] .= $var_value != $var_options['default'] ? ' acss-var--changed' : '';
		$required_condition = array_key_exists( 'required_condition', $var_options ) ? (bool) $var_options['required_condition'] : false;
		$this->render_options['required'] = ! empty( $var_options['default'] ) || ( $hidden && $required_condition ) ? 'required="required"' : '';
		$this->render_options['placeholder'] = array_key_exists( 'placeholder', $var_options ) ? $var_options['placeholder'] : '';
	}

	/**
	 * Enable the wrapper.
	 *
	 * @return Base
	 */
	public function with_wrapper() {
		$this->enable_outside_wrapper_rendering = true;
		return $this;
	}

	/**
	 * Render the wrapper opening.
	 *
	 * @return void
	 */
	public function render_wrapper_open() {
		?>
		<?php if ( $this->enable_outside_wrapper_rendering ) : ?>
			<div class="acss-var<?php echo esc_attr( $this->render_options['var_classes'] ); ?>" id="acss-var-<?php echo esc_attr( $this->render_options['id'] ); ?>">
				<header class="acss-var__header">

					<div class="acss-var__info">
						<?php if ( ! empty( $this->render_options['title'] ) ) : ?>
							<h4 class="acss-var__title"><?php echo esc_html( $this->render_options['title'] ); ?></h4>

						<?php endif; ?>
						<?php if ( ! empty( $this->render_options['tooltip'] ) ) : ?>
							<svg class="acss-var__info__icon" width="9" height="10" viewBox="0 0 9 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.5 0.375H1.25C0.546875 0.375 0 0.941406 0 1.625V7.875C0 8.57812 0.546875 9.125 1.25 9.125H7.5C8.18359 9.125 8.75 8.57812 8.75 7.875V1.625C8.75 0.941406 8.18359 0.375 7.5 0.375ZM4.375 2.25C4.70703 2.25 5 2.54297 5 2.875C5 3.22656 4.70703 3.5 4.375 3.5C4.02344 3.5 3.75 3.22656 3.75 2.875C3.75 2.54297 4.02344 2.25 4.375 2.25ZM5.15625 7.25H3.59375C3.32031 7.25 3.125 7.05469 3.125 6.78125C3.125 6.52734 3.32031 6.3125 3.59375 6.3125H3.90625V5.0625H3.75C3.47656 5.0625 3.28125 4.86719 3.28125 4.59375C3.28125 4.33984 3.47656 4.125 3.75 4.125H4.375C4.62891 4.125 4.84375 4.33984 4.84375 4.59375V6.3125H5.15625C5.41016 6.3125 5.625 6.52734 5.625 6.78125C5.625 7.05469 5.41016 7.25 5.15625 7.25Z" fill="#707070" />
							</svg>
							<p class="acss-var__info__tooltip"><?php echo wp_kses_post( $this->render_options['tooltip'] ); ?></p>
						<?php endif; ?>
					</div>

				</header> <!-- .acss-var__heading -->


				<p class="acss-var__error_message"></p>
				</header> <!-- .acss-var__heading -->
			<?php endif; ?>
			<div class="acss-value">
				<div class="acss-value__input-wrapper">
				<?php
	}

	/**
	 * Render the wrapper closing.
	 *
	 * @return void
	 */
	public function render_wrapper_close() {
		$can_reset = isset( $this->render_options['no_reset'] ) && true === (bool) $this->render_options['no_reset'] ? false : true;
		?>
		<?php if ( $can_reset ) : ?>
			<?php
			$disable_reset_button = $this->render_options['value'] == $this->render_options['default'] ? ' disabled="disabled"' : ''; // TODO: improve check by considering what type "value" is.
			?>
						<input type="image" src="<?php echo esc_url( ACSS_ASSETS_URL . '/img/reset.svg' ); ?>" class="acss-reset-button" <?php echo $disable_reset_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
						<div class="acss-tooltip">
							<p>Are you sure?</p>
							<button class="acss-tooltip__accept">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9 0.25C4.00781 0.25 0 4.29297 0 9.25C0 14.2422 4.00781 18.25 9 18.25C13.957 18.25 18 14.2422 18 9.25C18 4.29297 13.957 0.25 9 0.25ZM13.043 7.70312L8.54297 12.2031C8.36719 12.4141 8.12109 12.4844 7.875 12.4844C7.59375 12.4844 7.34766 12.4141 7.17188 12.2031L4.92188 9.95312C4.53516 9.56641 4.53516 8.96875 4.92188 8.58203C5.30859 8.19531 5.90625 8.19531 6.29297 8.58203L7.875 10.1289L11.6719 6.33203C12.0586 5.94531 12.6562 5.94531 13.043 6.33203C13.4297 6.71875 13.4297 7.31641 13.043 7.70312Z" fill="#80BE7E" />
								</svg>
							</button>
						</div>
		<?php endif; ?>
				</div> <!-- .acss-value__input-wrapper -->
			</div> <!-- .acss-value -->
		<?php if ( $this->enable_outside_wrapper_rendering ) : ?>
			</div> <!-- .acss-var -->
		<?php endif; ?>
		<?php
	}

	/**
	 * Render the unit.
	 */
	protected function render_unit() {
		if ( ! empty( $this->render_options['unit'] ) ) {
			printf(
				'<div class="acss-value__unit">%s</div>',
				esc_attr( $this->render_options['unit'] )
			);
		}
	}

	/**
	 * Check if the provided content type is a variable
	 *
	 * @param string $type Content type.
	 * @return boolean
	 */
	public static function is_variable( $type ) {
		return 'variable' === $type;
	}

	/**
	 * Check if the provided content type is a variable
	 *
	 * @param string $type Content type.
	 * @return boolean
	 */
	public static function is_allowed_variable_type( $type ) {
		$input_types = array( 'checkbox', 'color', 'hidden', 'number', 'plain_text', 'select', 'text', 'toggle' );
		return in_array( $type, $input_types, true );
	}

	/**
	 * Render a variable based on its type.
	 *
	 * @param string  $var_id Variable ID.
	 * @param mixed   $ui_options Variable's UI options.
	 * @param array   $values All variables current values.
	 * @param boolean $with_wrapper Whether to render the wrapper or not.
	 * @return void
	 */
	public static function render_variable( $var_id, $ui_options, $values, $with_wrapper = true ) {
		$var_value = isset( $values[ $var_id ] ) ? $values[ $var_id ] : null;
		$var_options = ( Variables::get_instance() )->get_variable_options( $var_id );
		if ( null === $var_options || ! is_array( $var_options ) || empty( $var_options['type'] ) ) {
			// TODO: error message?
			return;
		}
		$var_options = array_merge( $ui_options, $var_options );
		if ( ! empty( $ui_options['unit'] ) ) {
			// The unit set in ui.json overrides the one set in variables.json, because it's UI specific.
			$var_options['unit'] = $ui_options['unit'];
		}
		// The next line allows color saturation variables to hook in and change the default value based on their color' saturation.
		$default_value = array_key_exists( 'default', $var_options ) ? $var_options['default'] : '';
		$var_options['default'] = apply_filters( 'automaticcss_render_options_default', $default_value, $var_id, $var_options, $values );
		if ( ! empty( $var_options['condition'] ) && is_array( $var_options['condition'] ) ) {
			// Check if the variable is hidden based on its condition.
			$condition = $var_options['condition'];
			if ( ! empty( $condition['type'] ) && 'show_only_if' === $condition['type'] ) {
				$condition_field = ! empty( $condition['field'] ) ? $condition['field'] : '';
				$condition_value = isset( $condition['value'] ) ? $condition['value'] : null;
				$condition_required = ! empty( $condition['required'] ) ? (bool) $condition['required'] : true;
				if ( '' !== $condition_field && null !== $condition_value ) {
					$actual_value = isset( $values[ $condition_field ] ) ? $values[ $condition_field ] : null;
					if ( null !== $actual_value && null !== $var_value && $var_value !== $actual_value ) {
						// Condition is false, this field should be hidden.
						$var_options['hidden'] = true;
						$var_options['required_condition'] = $condition_required;
					}
				}
			}
		}
		$var_ui_element = null;
		switch ( $var_options['type'] ) {
			case 'text':
				$var_ui_element = new Text( $var_id, $var_options, $var_value );
				break;
			case 'number':
				$var_ui_element = new Number( $var_id, $var_options, $var_value );
				break;
			case 'color':
				$var_ui_element = new Color( $var_id, $var_options, $var_value );
				break;
			case 'select':
				$var_ui_element = new Select( $var_id, $var_options, $var_value );
				break;
			case 'checkbox':
				$var_ui_element = new Checkbox( $var_id, $var_options, $var_value );
				break;
			case 'toggle':
				$var_ui_element = new Toggle( $var_id, $var_options, $var_value );
				break;
			case 'plaintext':
				$var_ui_element = new Plain_Text( $var_id, $var_options, $var_value );
				break;
			case 'hidden':
				$var_ui_element = new Hidden( $var_id, $var_options, $var_value );
				break;
		}
		if ( null !== $var_ui_element ) {
			if ( $with_wrapper ) {
				$var_ui_element->with_wrapper()->render();
			} else {
				$var_ui_element->render();
			}
		}
	}

	/**
	 * Render the UI element.
	 *
	 * @return void
	 */
	abstract public function render();
}
