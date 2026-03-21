<?php
/**
 * Provides the admin area view for the settings page.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/admin/views
 */

defined( 'ABSPATH' ) || exit;

$options       = get_option( 'Nish_aga_settings' );
$is_paying     = function_exists( 'google_autocomplete' ) && google_autocomplete()->is_paying();
$woo_active    = class_exists( 'WooCommerce' );
$checkout_url  = function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#';

// Masked API key display.
$current_api_key = $options['api_key'] ?? '';
$masked_api_key  = '';
if ( ! empty( $current_api_key ) ) {
	$length         = strlen( $current_api_key );
	$masked_api_key = str_repeat( "\xE2\x80\xA2", max( 0, $length - 4 ) ) . substr( $current_api_key, -4 );
}
?>
<div class="wrap" id="aga-settings-page">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'Nish_aga_settings_group' ); ?>

		<!-- Tab Navigation -->
		<nav class="aga-settings-nav">
			<button type="button" class="aga-settings-tab active" data-tab="general">
				<span class="dashicons dashicons-admin-generic"></span>
				<?php esc_html_e( 'General', 'autocomplete-google-address' ); ?>
			</button>
			<button type="button" class="aga-settings-tab" data-tab="woocommerce">
				<span class="dashicons dashicons-cart"></span>
				<?php esc_html_e( 'WooCommerce', 'autocomplete-google-address' ); ?>
				<span class="aga-tab-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
			</button>
			<button type="button" class="aga-settings-tab" data-tab="advanced">
				<span class="dashicons dashicons-admin-tools"></span>
				<?php esc_html_e( 'Advanced', 'autocomplete-google-address' ); ?>
			</button>
		</nav>

		<!-- Tab: General -->
		<div class="aga-settings-panel active" data-tab="general">
			<div class="aga-card">
				<div class="aga-card-header">
					<h2><?php esc_html_e( 'Google Maps API Settings', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-card-body">
					<div class="aga-field-group">
						<label for="api_key"><strong><?php esc_html_e( 'Google Maps API Key', 'autocomplete-google-address' ); ?></strong></label>
						<div class="aga-api-key-row">
							<input type="text" id="api_key" name="Nish_aga_settings[api_key]" value="<?php echo esc_attr( $masked_api_key ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your Google Maps API Key', 'autocomplete-google-address' ); ?>" />
							<button type="button" id="aga-validate-key-btn" class="button button-secondary">
								<span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-top: -2px;"></span>
								<?php esc_html_e( 'Validate Key', 'autocomplete-google-address' ); ?>
							</button>
							<span id="aga-key-status"></span>
						</div>
						<p class="description">
							<?php echo wp_kses_post( __( 'You must enable the <strong>Places API (New)</strong> and <strong>Maps JavaScript API</strong> in your Google Cloud console.', 'autocomplete-google-address' ) ); ?><br/>
							<?php esc_html_e( 'Leave this field blank to keep the existing API key. Enter a new key to update it.', 'autocomplete-google-address' ); ?>
						</p>
					</div>

					<hr />

					<div class="aga-field-group">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=aga-wizard' ) ); ?>" class="button button-secondary">
							<span class="dashicons dashicons-admin-settings" style="vertical-align: middle; margin-top: -2px;"></span>
							<?php esc_html_e( 'Run Setup Wizard', 'autocomplete-google-address' ); ?>
						</a>
						<p class="description" style="margin-top: 8px;">
							<?php esc_html_e( 'Launch the guided setup wizard to configure the plugin step by step.', 'autocomplete-google-address' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab: WooCommerce -->
		<div class="aga-settings-panel" data-tab="woocommerce">
			<div class="aga-card">
				<div class="aga-card-header">
					<h2><?php esc_html_e( 'WooCommerce Integration', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-card-body">
					<?php if ( ! $woo_active ) : ?>
						<div class="notice notice-warning inline" style="margin: 0 0 20px;">
							<p>
								<span class="dashicons dashicons-warning" style="color: #dba617; vertical-align: middle;"></span>
								<?php esc_html_e( 'WooCommerce is not installed or active.', 'autocomplete-google-address' ); ?>
							</p>
						</div>
					<?php endif; ?>

					<?php if ( ! $is_paying ) : ?>
						<!-- Pro upgrade banner -->
						<div class="aga-premium-gated-content">
							<div class="aga-premium-overlay">
								<div class="aga-premium-message">
									<span class="dashicons dashicons-lock" style="font-size: 28px; width: 28px; height: 28px; color: #2271b1;"></span>
									<h3 style="margin: 10px 0 5px;"><?php esc_html_e( 'Pro Feature', 'autocomplete-google-address' ); ?></h3>
									<p><?php esc_html_e( 'WooCommerce auto-integration is available in the Pro plan. Upgrade to automatically add address autocomplete to your checkout.', 'autocomplete-google-address' ); ?></p>
									<a href="<?php echo esc_url( $checkout_url ); ?>" class="button button-primary" target="_blank">
										<?php esc_html_e( 'Upgrade to Pro', 'autocomplete-google-address' ); ?>
									</a>
								</div>
							</div>

							<!-- Blurred / disabled preview of the toggles -->
							<div style="opacity: 0.4; pointer-events: none;">
								<div class="aga-field-group">
									<label style="display: block; margin-bottom: 10px;">
										<strong><?php esc_html_e( 'Enable WooCommerce Auto-Integration', 'autocomplete-google-address' ); ?></strong>
									</label>
									<label class="aga-switch">
										<input type="checkbox" disabled checked>
										<span class="aga-slider"></span>
									</label>
								</div>
								<div class="aga-field-group">
									<label style="display: block; margin-bottom: 10px;">
										<strong><?php esc_html_e( 'Enable Block Checkout Support', 'autocomplete-google-address' ); ?></strong>
									</label>
									<label class="aga-switch">
										<input type="checkbox" disabled checked>
										<span class="aga-slider"></span>
									</label>
								</div>
							</div>
						</div>
					<?php else : ?>
						<!-- WooCommerce Auto-Integration Toggle -->
						<div class="aga-field-group">
							<label for="woocommerce_enabled_toggle" style="display: block; margin-bottom: 10px;">
								<strong><?php esc_html_e( 'Enable WooCommerce Auto-Integration', 'autocomplete-google-address' ); ?></strong>
							</label>
							<label class="aga-switch">
								<input type="checkbox" id="woocommerce_enabled_toggle" name="Nish_aga_settings[woocommerce_enabled]" value="1" <?php checked( $options['woocommerce_enabled'] ?? 1, 1 ); ?>>
								<span class="aga-slider"></span>
							</label>
							<label for="woocommerce_enabled_toggle" style="display: inline-block; vertical-align: middle; margin-left: 10px;">
								<?php esc_html_e( 'Enable WooCommerce Auto-Integration', 'autocomplete-google-address' ); ?>
							</label>
							<p class="description" style="margin-top: 5px;">
								<?php esc_html_e( 'Automatically add address autocomplete to WooCommerce billing and shipping fields. No form configuration needed.', 'autocomplete-google-address' ); ?>
							</p>
						</div>

						<hr />

						<!-- Block Checkout Support Toggle -->
						<div class="aga-field-group">
							<label for="woocommerce_block_checkout_toggle" style="display: block; margin-bottom: 10px;">
								<strong><?php esc_html_e( 'Enable Block Checkout Support', 'autocomplete-google-address' ); ?></strong>
							</label>
							<label class="aga-switch">
								<input type="checkbox" id="woocommerce_block_checkout_toggle" name="Nish_aga_settings[woocommerce_block_checkout]" value="1" <?php checked( $options['woocommerce_block_checkout'] ?? 1, 1 ); ?>>
								<span class="aga-slider"></span>
							</label>
							<label for="woocommerce_block_checkout_toggle" style="display: inline-block; vertical-align: middle; margin-left: 10px;">
								<?php esc_html_e( 'Enable Block Checkout Support', 'autocomplete-google-address' ); ?>
							</label>
							<p class="description" style="margin-top: 5px;">
								<?php esc_html_e( 'Enable address autocomplete support for the WooCommerce block-based checkout.', 'autocomplete-google-address' ); ?>
							</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Tab: Advanced -->
		<div class="aga-settings-panel" data-tab="advanced">
			<div class="aga-card">
				<div class="aga-card-header">
					<h2><?php esc_html_e( 'Advanced Settings', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-card-body">
					<div class="aga-field-group">
						<label for="do_not_load_gmaps_api_toggle" style="display: block; margin-bottom: 10px;">
							<strong><?php esc_html_e( 'Conflict Handling', 'autocomplete-google-address' ); ?></strong>
						</label>
						<label class="aga-switch">
							<input type="checkbox" id="do_not_load_gmaps_api_toggle" name="Nish_aga_settings[do_not_load_gmaps_api]" value="1" <?php checked( $options['do_not_load_gmaps_api'] ?? 0, 1 ); ?>>
							<span class="aga-slider"></span>
						</label>
						<label for="do_not_load_gmaps_api_toggle" style="display: inline-block; vertical-align: middle; margin-left: 10px;">
							<?php esc_html_e( 'Do not load Google Maps JS API', 'autocomplete-google-address' ); ?>
						</label>
						<p class="description" style="margin-top: 5px;">
							<?php esc_html_e( 'Enable this if your theme or another plugin already loads the Google Maps API. This will prevent conflicts.', 'autocomplete-google-address' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
</div>

<style>
	/* Settings Tab Navigation */
	.aga-settings-nav {
		display: flex;
		gap: 0;
		border-bottom: 2px solid #c3c4c7;
		margin: 20px 0 0;
		padding: 0;
	}

	.aga-settings-tab {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 10px 18px;
		background: #f0f0f1;
		border: 1px solid #c3c4c7;
		border-bottom: none;
		border-radius: 4px 4px 0 0;
		margin-bottom: -2px;
		cursor: pointer;
		font-size: 14px;
		font-weight: 500;
		color: #50575e;
		transition: background 0.15s ease, color 0.15s ease;
		line-height: 1.4;
	}

	.aga-settings-tab:hover {
		background: #e5e5e6;
		color: #1d2327;
	}

	.aga-settings-tab.active {
		background: #fff;
		border-bottom: 2px solid #fff;
		color: #1d2327;
		font-weight: 600;
	}

	.aga-settings-tab .dashicons {
		font-size: 16px;
		width: 16px;
		height: 16px;
		line-height: 16px;
	}

	.aga-tab-badge {
		display: inline-block;
		background: #2271b1;
		color: #fff;
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		padding: 1px 6px;
		border-radius: 3px;
		line-height: 1.5;
		letter-spacing: 0.5px;
	}

	/* Settings Panels */
	.aga-settings-panel {
		display: none;
		padding-top: 20px;
	}

	.aga-settings-panel.active {
		display: block;
	}

	/* API Key Row */
	.aga-api-key-row {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	.aga-api-key-row .regular-text {
		flex: 0 1 400px;
	}

	#aga-key-status {
		font-weight: 600;
		font-size: 13px;
	}

	#aga-key-status.aga-key-valid {
		color: #00a32a;
	}

	#aga-key-status.aga-key-invalid {
		color: #d63638;
	}

	#aga-key-status.aga-key-checking {
		color: #dba617;
	}
</style>

