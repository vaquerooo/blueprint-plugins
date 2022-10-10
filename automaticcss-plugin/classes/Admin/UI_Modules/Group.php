<?php
/**
 * Automatic.css Group UI file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Admin\UI_Modules;

use Automatic_CSS\Admin\UI_Elements\Base;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Model\Config\Variables;

/**
 * Group UI class.
 */
class Group {

	/**
	 * Render this input.
	 *
	 * @param string $group_id The group ID.
	 * @param array  $group_options The group options.
	 * @param array  $values The current variable values.
	 * @return void
	 */
	public static function render( $group_id, $group_options, $values ) {
		$has_changed_class = '';
		if ( ! empty( $group_options['content'] ) ) {
			foreach ( $group_options['content'] as $var_id => $var_options ) {
				if ( ! array_key_exists( 'type', $var_options ) || 'variable' !== $var_options['type'] ) {
					continue;
				}
				$var_options = ( Variables::get_instance() )->get_variable_options( $var_id );
				$default_value = is_array( $var_options ) && array_key_exists( 'default', $var_options ) && array_key_exists( $var_id, $values ) ?
					apply_filters( 'automaticcss_render_options_default', $var_options['default'], $var_id, $var_options, $values ) :
					null;
				if ( null !== $default_value && $default_value != $values[ $var_id ] ) {
					$has_changed_class = ' acss-group--changed';
					break;
				}
			}
		}
		?>
		<div class="acss-group<?php echo esc_attr( $has_changed_class ); ?>" id="acss-group-<?php echo esc_attr( $group_id ); ?>">
			<header class="acss-var__header">

				<div class="acss-var__info">
					<?php if ( ! empty( $group_options['title'] ) ) : ?>
						<h4 class="acss-group__title"><?php echo esc_html( $group_options['title'] ); ?></h4>
					<?php endif; ?>
					<?php if ( ! empty( $group_options['tooltip'] ) ) : ?>
						<svg class="acss-var__info__icon" width="9" height="10" viewBox="0 0 9 10" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.5 0.375H1.25C0.546875 0.375 0 0.941406 0 1.625V7.875C0 8.57812 0.546875 9.125 1.25 9.125H7.5C8.18359 9.125 8.75 8.57812 8.75 7.875V1.625C8.75 0.941406 8.18359 0.375 7.5 0.375ZM4.375 2.25C4.70703 2.25 5 2.54297 5 2.875C5 3.22656 4.70703 3.5 4.375 3.5C4.02344 3.5 3.75 3.22656 3.75 2.875C3.75 2.54297 4.02344 2.25 4.375 2.25ZM5.15625 7.25H3.59375C3.32031 7.25 3.125 7.05469 3.125 6.78125C3.125 6.52734 3.32031 6.3125 3.59375 6.3125H3.90625V5.0625H3.75C3.47656 5.0625 3.28125 4.86719 3.28125 4.59375C3.28125 4.33984 3.47656 4.125 3.75 4.125H4.375C4.62891 4.125 4.84375 4.33984 4.84375 4.59375V6.3125H5.15625C5.41016 6.3125 5.625 6.52734 5.625 6.78125C5.625 7.05469 5.41016 7.25 5.15625 7.25Z" fill="#707070" />
						</svg>
						<p class="acss-var__info__tooltip">
							<?php echo esc_html( $group_options['tooltip'] ); ?></p>
					<?php endif; ?>

				</div>

			</header>
			<?php if ( ! empty( $group_options['content'] ) ) : ?>
				<?php foreach ( $group_options['content'] as $content_id => $content_options ) : ?>
					<?php
					if ( ! isset( $content_options['type'] ) ) {
						continue; // can't do anything if I don't know the type.
					}
					if ( 'variable' === $content_options['type'] ) {
						// Content is a variable here. Naming like these for clarity.
						$var_id = $content_id;
						$var_options = $content_options;
						Base::render_variable( $var_id, $var_options, $values, false );
					}
					?>
				<?php endforeach; ?>
			<?php endif; ?>
				<footer>
					<p class="acss-group__error_message"></p>
				</footer>
		</div> <!-- .acss-group -->
		<?php
	}
}
