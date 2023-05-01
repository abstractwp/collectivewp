<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add a new submenu page under Settings.
add_action( 'admin_menu', 'linkedin_settings_page' );
function linkedin_settings_page() {
	add_submenu_page(
		'options-general.php',
		'LinkedIn Settings',
		'LinkedIn Settings',
		'manage_options',
		'linkedin-settings',
		'linkedin_settings_callback'
	);
}

// Register plugin settings.
add_action( 'admin_init', 'linkedin_register_settings' );
function linkedin_register_settings() {
	register_setting( 'linkedin_settings', 'linkedin_client_id' );
	register_setting( 'linkedin_settings', 'linkedin_client_secret' );
	register_setting( 'linkedin_settings', 'linkedin_redirect_uri' );
	register_setting( 'linkedin_settings', 'linkedin_form_id' );
	register_setting( 'linkedin_settings', 'linkedin_field_first_name' );
	register_setting( 'linkedin_settings', 'linkedin_field_last_name' );
	register_setting( 'linkedin_settings', 'linkedin_field_email_address' );
	register_setting( 'linkedin_settings', 'linkedin_field_username' );
}

// Display the plugin settings page.
function linkedin_settings_callback() {
?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'linkedin_settings' ); ?>
			<?php do_settings_sections( 'linkedin_settings' ); ?>
			<table class="form-table">
				<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Client ID', 'linkedin-settings' ); ?></th>
						<td><input type="text" name="linkedin_client_id" value="<?php echo esc_attr( get_option( 'linkedin_client_id' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Client Secret', 'linkedin-settings' ); ?></th>
						<td><input type="text" name="linkedin_client_secret" value="<?php echo esc_attr( get_option( 'linkedin_client_secret' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Redirect URI', 'linkedin-settings' ); ?></th>
						<td><input type="text" name="linkedin_redirect_uri" value="<?php echo esc_attr( get_option( 'linkedin_redirect_uri' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Gravity Form ID', 'linkedin-settings' ); ?></th>
						<td><input type="text" name="linkedin_form_id" value="<?php echo esc_attr( get_option( 'linkedin_form_id' ) ); ?>" /></td>
				</tr>
			</table>
			<h2><?php esc_html_e('Form Mapping Fields', 'linkedin-login'); ?></h2>
			<p><?php esc_html_e('Gravity Form fields that is used for registration.', 'linkedin-login'); ?></p>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('First Name', 'linkedin-login'); ?></th>
					<td>
							<input type="text" name="linkedin_field_first_name" value="<?php echo esc_attr(get_option('linkedin_field_first_name')); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Last Name', 'linkedin-login'); ?></th>
					<td>
							<input type="text" name="linkedin_field_last_name" value="<?php echo esc_attr(get_option('linkedin_field_last_name')); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Email Address', 'linkedin-login'); ?></th>
					<td>
							<input type="text" name="linkedin_field_email_address" value="<?php echo esc_attr(get_option('linkedin_field_email_address')); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Username', 'linkedin-login'); ?></th>
					<td>
							<input type="text" name="linkedin_field_username" value="<?php echo esc_attr(get_option('linkedin_field_username')); ?>" class="regular-text">
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}
