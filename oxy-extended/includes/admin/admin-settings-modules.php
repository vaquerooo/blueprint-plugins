<?php
$modules         = oe_get_modules();
$enabled_modules = oe_get_enabled_modules();
?>
<div class="oe-settings-section">
	<div class="oe-settings-section-header">
		<h3 class="oe-settings-section-title"><?php _e( 'Widgets', 'oxy-extended' ); ?></h3>
	</div>
	<div class="oe-settings-section-content">
		<button type="button" class="button toggle-all-widgets"><?php _e( 'Toggle All', 'oxy-extended' ); ?></button>
		<table class="form-table oe-settings-elements-grid">
			<?php
			foreach ( $modules as $module_name => $module_title ) :
				if ( ! is_array( $enabled_modules ) && 'disabled' != $enabled_modules ) {
					$module_enabled = true;
				} elseif ( ! is_array( $enabled_modules ) && 'disabled' === $enabled_modules ) {
					$module_enabled = false;
				} else {
					$module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[ $module_name ] );
				}
			?>
			<tr valign="top">
				<th>
					<label for="<?php echo $module_name; ?>">
						<?php echo $module_title; ?>
					</label>
				</th>
				<td>
					<label class="oe-admin-field-toggle">
						<input
							id="<?php echo $module_name; ?>"
							name="oe_enabled_modules[]"
							type="checkbox"
							value="<?php echo $module_name; ?>"
							<?php echo $module_enabled ? ' checked="checked"' : '' ?>
						/>
						<span class="oe-admin-field-toggle-slider" aria-hidden="true"></span>
					</label>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>

<?php wp_nonce_field('oe-modules-settings', 'oe-modules-settings-nonce'); ?>

<script>
(function($) {
	if ( $('input[name="oe_enabled_modules[]"]:checked').length > 0 ) {
		$('.toggle-all-widgets').addClass('checked');
	}
	$('.toggle-all-widgets').on('click', function() {
		if ( $(this).hasClass('checked') ) {
			$('input[name="oe_enabled_modules[]"]').prop('checked', false);
			$(this).removeClass('checked');
		} else {
			$('input[name="oe_enabled_modules[]"]').prop('checked', true);
			$(this).addClass('checked');
		}
	});
})(jQuery);
</script>