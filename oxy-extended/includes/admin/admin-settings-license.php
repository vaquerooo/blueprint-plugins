<?php
use \OxyExtended\Classes\OE_Admin_Settings;

$settings = OE_Admin_Settings::get_settings();
?>
<div class="oe-settings-section">
	<div class="oe-settings-section-header">
		<h3 class="oe-settings-section-title"><?php _e( 'License', 'oxy-extended' ); ?></h3>
	</div>
	<div class="oe-settings-section-content">
		<table class="form-table">
			<tbody>
				<?php if ( ! defined( 'OE_LICENSE_KEY' ) ) {
					$license = get_option( 'oe_license_key' );
					$status  = get_option( 'oe_license_status' );
					?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php esc_html_e( 'License Key', 'oxy-extended' ); ?>
						</th>
						<td>
							<input id="oe_license_key" name="oe_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license, 'oxy-extended' ); ?>" />
							<p class="description"><?php echo sprintf( __( 'Enter your <a href="%s" target="_blank">license key</a> to enable remote updates and support.', 'oxy-extended' ), 'https://oxyextended.com/my-account/' ); ?>
						</td>
					</tr>
					<?php if ( false !== $license && ! empty( $license ) ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php esc_html_e( 'License Status', 'oxy-extended' ); ?>
							</th>
							<td>
								<?php if ( $status == 'valid' ) { ?>
									<span style="color: #267329; background: #caf1cb; padding: 5px 10px; text-shadow: none; border-radius: 3px; display: inline-block; text-transform: uppercase;"><?php esc_html_e( 'active', 'oxy-extended' ); ?></span>
									<?php wp_nonce_field( 'oe_license_deactivate_nonce', 'oe_license_deactivate_nonce' ); ?>
									<input type="submit" class="button-secondary" name="oe_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'oxy-extended' ); ?>" />
								<?php } else { ?>
									<?php if ( $status == '' ) { $status = 'inactive'; } ?>
									<span style="<?php echo $status == 'inactive' ? 'color: #fff; background: #b1b1b1;' : 'color: red; background: #ffcdcd;'; ?> padding: 5px 10px; text-shadow: none; border-radius: 3px; display: inline-block; text-transform: uppercase;"><?php echo $status; ?></span>
									<?php
									wp_nonce_field( 'oe_license_activate_nonce', 'oe_license_activate_nonce' ); ?>
									<input type="submit" class="button-secondary" name="oe_license_activate" value="<?php esc_html_e( 'Activate License', 'oxy-extended' ); ?>"/>
									<p class="description"><?php esc_html_e( 'Please click the Activate License button to activate your license.', 'oxy-extended' ); ?>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
