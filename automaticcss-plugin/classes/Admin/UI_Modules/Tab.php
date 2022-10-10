<?php
/**
 * Automatic.css Tab UI file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Admin\UI_Modules;

use Automatic_CSS\Admin\UI_Elements\Checkbox;
use Automatic_CSS\Admin\UI_Elements\Color;
use Automatic_CSS\Admin\UI_Elements\Hidden;
use Automatic_CSS\Admin\UI_Elements\Number;
use Automatic_CSS\Admin\UI_Elements\Plain_Text;
use Automatic_CSS\Admin\UI_Elements\Select;
use Automatic_CSS\Admin\UI_Elements\Text;
use Automatic_CSS\Admin\UI_Elements\Toggle;
use Automatic_CSS\Plugin;
use Automatic_CSS\Helpers\Logger;

/**
 * Tab UI class.
 */
class Tab {

	/**
	 * Render this input.
	 *
	 * @param string $tab_id The tab ID.
	 * @param array  $tab_options The tab options.
	 * @param array  $values The current variable values.
	 * @return void
	 */
	public static function render( $tab_id, $tab_options, $values ) {
		?>
		<div class="acss-tab" id="acss-tab-<?php echo esc_attr( $tab_id ); ?>">
			<header class="acss-tab__header">
				<div class="acss-tab__title-wrapper">
					<?php if ( array_key_exists( 'title', $tab_options ) && '' !== $tab_options['title'] ) : ?>
						<h2 class="acss-tab__title"><?php echo esc_html( $tab_options['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( array_key_exists( 'description', $tab_options ) && '' !== $tab_options['description'] ) : ?>
						<p class="acss-tab__description"><?php echo wp_kses_post( $tab_options['description'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php foreach ( apply_filters( 'automaticcss_tab_' . $tab_id . '_warnings', array() ) as $warning_id => $warning_options ) : ?>
					<div class="acss-tab__warning">


						<div class="acss-var__info">
							<?php if ( ! empty( $warning_options['text'] ) ) : ?>
								<p class="acss-tab__warning__heading"><?php echo wp_kses_post( $warning_options['text'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $warning_options['tooltip'] ) ) : ?>
								<svg width="9" height="10" viewBox="0 0 9 10" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.5 0.375H1.25C0.546875 0.375 0 0.941406 0 1.625V7.875C0 8.57812 0.546875 9.125 1.25 9.125H7.5C8.18359 9.125 8.75 8.57812 8.75 7.875V1.625C8.75 0.941406 8.18359 0.375 7.5 0.375ZM4.375 2.25C4.70703 2.25 5 2.54297 5 2.875C5 3.22656 4.70703 3.5 4.375 3.5C4.02344 3.5 3.75 3.22656 3.75 2.875C3.75 2.54297 4.02344 2.25 4.375 2.25ZM5.15625 7.25H3.59375C3.32031 7.25 3.125 7.05469 3.125 6.78125C3.125 6.52734 3.32031 6.3125 3.59375 6.3125H3.90625V5.0625H3.75C3.47656 5.0625 3.28125 4.86719 3.28125 4.59375C3.28125 4.33984 3.47656 4.125 3.75 4.125H4.375C4.62891 4.125 4.84375 4.33984 4.84375 4.59375V6.3125H5.15625C5.41016 6.3125 5.625 6.52734 5.625 6.78125C5.625 7.05469 5.41016 7.25 5.15625 7.25Z" fill="#707070" />
								</svg>
								<p class="acss-tab__warning__description acss-var__info__tooltip"><?php echo wp_kses_post( $warning_options['tooltip'] ); ?></p>
							<?php endif; ?>
						</div>

					</div>
				<?php endforeach; ?>
			</header> <!-- .acss-panel__header -->
			<?php if ( ! empty( $tab_options['content'] ) ) : ?>
				<?php foreach ( $tab_options['content'] as $content_id => $content_options ) : ?>
					<?php
					if ( ! isset( $content_options['type'] ) || 'panel' !== $content_options['type'] ) {
						continue; // can't do anything if it's not a panel.
					}
					Panel::render( $content_id, $content_options, $values );
					?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div> <!-- .acss-tab -->
		<?php
	}

}
