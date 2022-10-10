<?php
use \OxyExtended\Classes\OE_Admin_Settings;

$current_tab  = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'general';
$settings     = OE_Admin_Settings::get_settings();
?>
<style>
#wpcontent {
	padding: 0;
}
#footer-left {
	display: none;
}
.oe-settings-wrap {
	margin: 0;
}
.oe-settings-wrap * {
	box-sizing: border-box;
}
.oe-notices-target {
	margin: 0;
}
.oe-settings-header {
	display: flex;
	align-items: center;
	padding: 0 20px;
	background: #fff;
	box-shadow: 0 1px 8px 0 rgba(0,0,0,0.05);
}
.oe-settings-header h3 {
	margin: 0;
	font-weight: 500;
}
.oe-settings-header h3 .dashicons {
	color: #a2a2a2;
	vertical-align: text-bottom;
}
.oe-settings-tabs {
	margin-left: 30px;
}
.oe-settings-tabs a,
.oe-settings-tabs a:hover,
.oe-settings-tabs a.nav-tab-active {
	background: none;
	border: none;
	box-shadow: none;
}
.oe-settings-tabs a {
	font-weight: 500;
	padding: 0 10px;
	color: #5f5f5f;
}
.oe-settings-tabs a.nav-tab-active {
	color: #333;
}
.oe-settings-tabs a > span {
	display: block;
	padding: 10px 0;
	border-bottom: 3px solid transparent;
}
.oe-settings-tabs a.nav-tab-active > span {
	border-bottom: 3px solid #0073aa;
}
.oe-settings-content {
	padding: 20px;
}
.oe-settings-content > form {
	background: #fff;
	padding: 10px 30px;
	box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
}
.oe-settings-content > form .form-table th {
	font-weight: 500;
}
.oe-settings-section {
	margin-bottom: 20px;
}
.oe-settings-section .oe-settings-section-title {
	font-weight: 300;
	font-size: 22px;
	border-bottom: 1px solid #eee;
	padding-bottom: 15px;
}
.oe-settings-section .oe-settings-elements-grid > tbody {
	display: flex;
	align-items: center;
	flex-direction: row;
	flex-wrap: wrap;
}
.oe-settings-section .oe-settings-elements-grid > tbody tr {
	background: #f3f5f6;
	margin-right: 10px;
	margin-bottom: 10px;
	padding: 12px;
	border-radius: 5px;
}
.oe-settings-section .oe-settings-elements-grid > tbody tr th,
.oe-settings-section .oe-settings-elements-grid > tbody tr td {
	padding: 0;
}
.oe-settings-section .oe-settings-elements-grid th > label {
	user-select: none;
}
.oe-settings-section .toggle-all-widgets {
	margin-bottom: 10px;
}
.oe-settings-section .oe-admin-field-toggle {
	position: relative;
	display: inline-block;
	width: 35px;
	height: 16px;
}
.oe-settings-section .oe-admin-field-toggle input {
	opacity: 0;
	width: 0;
	height: 0;
}
.oe-settings-section .oe-admin-field-toggle .oe-admin-field-toggle-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #fff;
	border: 1px solid #7e8993;
	border-radius: 34px;
	-webkit-transition: .4s;
	transition: .4s;
}
.oe-settings-section .oe-admin-field-toggle .oe-admin-field-toggle-slider:before {
	border-radius: 50%;
	position: absolute;
	content: "";
	height: 10px;
	width: 10px;
	left: 2px;
	bottom: 2px;
	background-color: #7e8993;
	-webkit-transition: .4s;
	transition: .4s;
}
.oe-settings-section .oe-admin-field-toggle input[type="checkbox"]:checked + .oe-admin-field-toggle-slider:before {
	background-color: #0071a1;
	-webkit-transform: translateX(19px);
	-ms-transform: translateX(19px);
	transform: translateX(19px);
}

.oe-settings-section .oe-admin-field-toggle input:focus + .oe-admin-field-toggle-slider {
	border-color: #0071a1;
	box-shadow: 0 0 2px 1px #0071a1;
	transition: 0s;
}
</style>

<div class="wrap oe-settings-wrap">

	<div class="oe-settings-header">
		<h3>
			<span class="dashicons dashicons-admin-settings"></span>
			<span>
			<?php
				$admin_label = $settings['admin_label'];
				$admin_label = trim( $admin_label ) !== '' ? trim( $admin_label ) : 'Oxy Extended';
				echo sprintf( esc_html__( '%s Settings', 'oxy-extended' ), $admin_label );
			?>
			</span>
		</h3>
		<div class="oe-settings-tabs wp-clearfix">
			<?php self::render_tabs( $current_tab ); ?>
		</div>
	</div>

	<div class="oe-settings-content">
		<h2 class="oe-notices-target"></h2>
		<?php OE_Admin_Settings::render_update_message(); ?>
		<form method="post" id="oe-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">
			<?php self::render_setting_page(); ?>
			<?php
			if ( 'white-label' !== $current_tab ) {
				submit_button();
			} else {
				if ( 'off' === $settings['hide_wl_settings'] ) {
					submit_button();
				}
			}
			?>
		</form>

		<?php if ( 'on' != $settings['hide_support'] ) { ?>
			<br />
			<h2><?php esc_html_e( 'Support', 'oxy-extended' ); ?></h2>
			<p>
				<?php
					$support_link = $settings['support_link'];
					$support_link = !empty( $support_link ) ? $support_link : 'https://oxyextended.com/contact/';
					esc_html_e( 'For submitting any support queries, feedback, bug reports or feature requests, please visit', 'oxy-extended' ); ?> <a href="<?php echo $support_link; ?>" target="_blank"><?php esc_html_e('this link', 'oxy-extended'); ?></a>
			</p>
		<?php } ?>
	</div>
</div>
