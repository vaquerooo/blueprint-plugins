<?php
/**
 * Automatic.css Panel UI file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Admin\UI_Modules;

use Automatic_CSS\Admin\UI_Elements\Base;
use Automatic_CSS\Helpers\Logger;

/**
 * Panel UI class.
 */
class Panel {

	/**
	 * Render this input.
	 *
	 * @param string $panel_id The panel ID.
	 * @param array  $panel_options The panel options.
	 * @param array  $values The current variable values.
	 * @return void
	 */
	public static function render( $panel_id, $panel_options, $values ) {
		$variable_class = ! empty( $panel_options['variable'] ) ? ' acss-panel-inner--has-variable' : '';
		?>
		<div class="acss-panel" id="acss-panel-<?php echo esc_attr( $panel_id ); ?>">
			<div class="acss-panel-inner<?php echo esc_attr( $variable_class ); ?>">
				<header class="acss-panel__header acss-var__header">

					<div class="acss-var__info">
						<?php if ( ! empty( $panel_options['title'] ) ) : ?>
							<h3 class="acss-panel__title"><?php echo esc_html( $panel_options['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $panel_options['tooltip'] ) ) : ?>
							<svg width="9" height="10" viewBox="0 0 9 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.5 0.375H1.25C0.546875 0.375 0 0.941406 0 1.625V7.875C0 8.57812 0.546875 9.125 1.25 9.125H7.5C8.18359 9.125 8.75 8.57812 8.75 7.875V1.625C8.75 0.941406 8.18359 0.375 7.5 0.375ZM4.375 2.25C4.70703 2.25 5 2.54297 5 2.875C5 3.22656 4.70703 3.5 4.375 3.5C4.02344 3.5 3.75 3.22656 3.75 2.875C3.75 2.54297 4.02344 2.25 4.375 2.25ZM5.15625 7.25H3.59375C3.32031 7.25 3.125 7.05469 3.125 6.78125C3.125 6.52734 3.32031 6.3125 3.59375 6.3125H3.90625V5.0625H3.75C3.47656 5.0625 3.28125 4.86719 3.28125 4.59375C3.28125 4.33984 3.47656 4.125 3.75 4.125H4.375C4.62891 4.125 4.84375 4.33984 4.84375 4.59375V6.3125H5.15625C5.41016 6.3125 5.625 6.52734 5.625 6.78125C5.625 7.05469 5.41016 7.25 5.15625 7.25Z" fill="#707070" />
							</svg>
							<?php // 20220628 - MG - removed the .acss-var__info__description from the following paragraph while cleaning up. ?>
							<p class="acss-var__info__tooltip">
								<?php echo wp_kses_post( $panel_options['tooltip'] ); ?></p>
						<?php endif; ?>
					</div>

				</header> <!-- .acss-panel__header -->
				<?php if ( ! empty( $panel_options['description'] ) ) : ?>
					<p class="acss-var__info__description"><?php echo wp_kses_post( $panel_options['description'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $panel_options['variable'] ) ) : ?>
					<?php
					// Content is a variable here. Naming like these for clarity.
					$var_id = $panel_options['variable'];
					Base::render_variable( $var_id, array(), $values );
					?>
				<?php endif; ?>
			</div> <!-- .acss-panel-inner -->
			<?php if ( ! empty( $panel_options['content'] ) ) : ?>
				<?php foreach ( $panel_options['content'] as $content_id => $content_options ) : ?>
					<?php
					if ( ! isset( $content_options['type'] ) ) {
						continue; // can't do anything if I don't know the type.
					}
					// Panels can only fit accordions, dividers, groups and variables.
					if ( 'accordion' === $content_options['type'] ) {
						Accordion::render( $content_id, $content_options, $values );
					} else if ( 'divider' === $content_options['type'] ) {
						Divider::render( $content_id, $content_options );
					} else if ( 'group' === $content_options['type'] ) {
						Group::render( $content_id, $content_options, $values );
					} else if ( 'variable' === $content_options['type'] ) {
						// Content is a variable here. Naming like these for clarity.
						$var_id = $content_id;
						$var_options = $content_options;
						Base::render_variable( $var_id, $var_options, $values );
					}
					?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div> <!-- .acss-panel -->
		<?php
	}
}
