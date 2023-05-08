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
				<p><strong><?php esc_html_e('Use the shortcode [linkedin-register] to display the \'Register via LinkedIn\' button and [linkedin-login] to display the \'Login via LinkedIn\' button.', 'linkedin-login'); ?></strong></p>
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
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}
