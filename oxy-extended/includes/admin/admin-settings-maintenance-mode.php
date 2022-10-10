<?php
use \OxyExtended\Classes\OE_Admin_Settings;

$settings = OE_Admin_Settings::get_settings();
?>
<h3><?php _e( 'Maintenance Mode / Coming Soon', 'oxy-extended' ); ?></h3>

<table class="form-table">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_enable"><?php esc_html_e( 'Enable', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<select id="oe_maintenance_mode_enable" name="oe_maintenance_mode_enable" style="min-width: 200px;">
				<?php $selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_enable', true ); ?>
				<option value="no" <?php selected( $selected, 'no' ); ?>><?php _e( 'No', 'oxy-extended' ); ?></option>
				<option value="yes" <?php selected( $selected, 'yes' ); ?>><?php _e( 'Yes', 'oxy-extended' ); ?></option>
			</select>
		</td>
	</tr>
</table>
<table class="form-table maintenance-mode-config">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_type"><?php esc_html_e( 'Type', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<select id="oe_maintenance_mode_type" name="oe_maintenance_mode_type" style="min-width: 200px;">
				<?php $selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_type', true ); ?>
				<option value="coming_soon" <?php selected( $selected, 'coming_soon' ); ?>><?php _e( 'Coming Soon', 'oxy-extended' ); ?></option>
				<option value="maintenance" <?php selected( $selected, 'maintenance' ); ?>><?php _e( 'Maintenance Mode', 'oxy-extended' ); ?></option>
			</select>
			<p class="description">
				<span class="desc--coming_soon" style="display: none;"><?php _e( 'Coming Soon returns HTTP 200 code, meaning the site is ready to be indexed.', 'oxy-extended' ); ?></span>
				<span class="desc--maintenance" style="display: none;"><?php _e( 'Maintenance Mode returns HTTP 503 code, so search engines know to come back a short time later. It is not recommended to use this mode for more than a couple of days.', 'oxy-extended' ); ?></span>
			</p>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_template"><?php esc_html_e( 'Page', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<?php $selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_template', true ); ?>
			<select id="oe_maintenance_mode_template" name="oe_maintenance_mode_template" style="min-width: 200px;">
				<?php echo \OxyExtended\Classes\OE_Maintenance_Mode::get_templates( $selected ); ?>
			</select>
			<p class="description">
				<span class="desc--template-select" style="color: red;"><?php _e( 'To enable maintenance mode you have to set a template for the maintenance mode page.', 'oxy-extended' ); ?></span>
				<span class="desc--template-edit"><a href="" class="edit-template" target="_blank"><?php _e( 'Edit Page', 'oxy-extended' ); ?></a></span>
			</p>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_access"><?php esc_html_e( 'Who Can Access', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<select id="oe_maintenance_mode_access" name="oe_maintenance_mode_access" style="min-width: 200px;">
				<?php $selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_access', true ); ?>
				<option value="logged_in" <?php selected( $selected, 'logged_in' ); ?>><?php _e( 'Logged In Users', 'oxy-extended' ); ?></option>
				<option value="custom" <?php selected( $selected, 'custom' ); ?>><?php _e( 'Custom', 'oxy-extended' ); ?></option>
			</select>
			<p class="description">
				<span class="desc--logged_in" style="display: none;"><?php _e( 'Website will be accessible for logged in users.', 'oxy-extended' ); ?></span>
				<span class="desc--custom" style="display: none;"><?php _e( 'Website will be accessible for the selected roles below.', 'oxy-extended' ); ?></span>
			</p>
		</td>
	</tr>
	<tr align="top" class="field-maintenance_mode_access_roles" style="display: none;">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_access_roles"><?php esc_html_e( 'Roles', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<?php
			$selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_access_roles', true );
			$access_roles = OE_Admin_Settings::get_user_roles();
			foreach ( $access_roles as $key => $access_role ) {
				?>
				<label>
					<input type="checkbox" name="oe_maintenance_mode_access_roles[]" value="<?php echo $key; ?>"<?php echo is_array( $selected ) && in_array( $key, $selected ) ? ' checked="checked"' : ''; ?> /><?php echo $access_role; ?>
				</label>
				<br />
				<?php
			}
			?>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_access_type"><?php esc_html_e( 'Include/Exclude URLs', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<select id="oe_maintenance_mode_access_type" name="oe_maintenance_mode_access_type" style="min-width: 200px;">
				<?php $selected = OE_Admin_Settings::get_option( 'oe_maintenance_mode_access_type', true ); ?>
				<option value="entire_site" <?php selected( $selected, 'entire_site' ); ?>><?php _e( 'Show on the Entire Site', 'oxy-extended' ); ?></option>
				<option value="include_urls" <?php selected( $selected, 'include_urls' ); ?>><?php _e( 'Include URLs', 'oxy-extended' ); ?></option>
				<option value="exclude_urls" <?php selected( $selected, 'exclude_urls' ); ?>><?php _e( 'Exclude URLs', 'oxy-extended' ); ?></option>
			</select>
			<p class="description">
				<span class="desc--entire_site" style="display: none;"><?php _e( 'By default the Maintenance Mode/Coming Soon is shown on the entire site.', 'oxy-extended' ); ?></span>
				<span class="desc--include_urls" style="display: none;"><?php _e( 'Maintenance Mode/Coming Soon will be shown on these URLs only.', 'oxy-extended' ); ?></span>
				<span class="desc--exclude_urls" style="display: none;"><?php _e( 'Maintenance Mode/Coming Soon will not be shown on these URLs.', 'oxy-extended' ); ?></span>
			</p>
		</td>
	</tr>
	<tr align="top" class="field-maintenance_mode_access_urls" style="display: none;">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_access_urls"></label>
		</th>
		<td>
			<?php $access_urls = OE_Admin_Settings::get_option( 'oe_maintenance_mode_access_urls', true ); ?>
			<textarea
				id="oe_maintenance_mode_access_urls"
				name="oe_maintenance_mode_access_urls"
				style="min-width: 380px;"
			><?php echo $access_urls; ?></textarea>
			<p class="description">
				<?php echo __( 'Enter one URL fragment per line. Use * character as a wildcard. Example: category/peace/* to target all posts in category peace.', 'oxy-extended' ); ?>
			</p>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="oe_maintenance_mode_ip_whitelist"><?php esc_html_e( 'IP Whitelist', 'oxy-extended' ); ?></label>
		</th>
		<td>
			<?php $ips = OE_Admin_Settings::get_option( 'oe_maintenance_mode_ip_whitelist', true ); ?>
			<textarea
				id="oe_maintenance_mode_ip_whitelist"
				name="oe_maintenance_mode_ip_whitelist"
				style="min-width: 380px;"
			><?php echo $ips; ?></textarea>
			<p class="description">
				<?php echo __( 'Enter one IP address per line.', 'oxy-extended' ); ?>
			</p>
		</td>
	</tr>
</table>

<?php //submit_button(); ?>

<script type="text/javascript">
(function($) {

	$('#oe_maintenance_mode_enable').on('change', function() {
		if ( $(this).val() === 'no' ) {
			$('.maintenance-mode-config').fadeOut(100);
		}
		if ( $(this).val() === 'yes' ) {
			$('.maintenance-mode-config').fadeIn(100);
		}
	}).trigger('change');

	$('#oe_maintenance_mode_access').on('change', function() {
		if ( $(this).val() === 'custom' ) {
			$('.field-maintenance_mode_access_roles').show();
		} else {
			$('.field-maintenance_mode_access_roles').hide();
		}
		$(this).parent().find('.description span').hide();
		$(this).parent().find('.desc--' + $(this).val()).show();
	}).trigger('change');

	$('#oe_maintenance_mode_access_type').on('change', function() {
		$(this).parent().find('.description span').hide();
		$(this).parent().find('.desc--' + $(this).val()).show();
		$('.field-maintenance_mode_access_urls').hide();
		if ( $(this).val() !== 'entire_site' ) {
			$('.field-maintenance_mode_access_urls').show();
		}
	}).trigger('change');

	$('#oe_maintenance_mode_type').on('change', function() {
		$(this).parent().find('.description span').hide();
		$(this).parent().find('.desc--' + $(this).val()).show();
	}).trigger('change');

	$('#oe_maintenance_mode_template').on('change', function() {
		$(this).parent().find('.description span').hide();
		if ( $(this).val() === '' ) {
			$(this).parent().find('.desc--template-select').show();
		} else {
			$(this).parent().find('.desc--template-edit')
				.show()
				.find('a.edit-template').attr('href', '<?php echo home_url(); ?>?p=' + $(this).val() + '&ct_builder=true');
		}
	}).trigger('change');

})(jQuery);
</script>