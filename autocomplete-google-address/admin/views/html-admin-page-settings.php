<?php
/**
 * Provides the admin area view for the settings page.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/admin/views
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap" id="aga-settings-page">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'Nish_aga_settings_group' ); ?>
        <?php $options = get_option( 'Nish_aga_settings' ); ?>

        <div class="aga-card">
            <div class="aga-card-header">
                <h2><?php esc_html_e( 'Google Maps API Settings', 'autocomplete-google-address' ); ?></h2>
            </div>
            <div class="aga-card-body">
                <!-- API Key -->
                <div class="aga-field-group">
                    <label for="api_key"><strong><?php esc_html_e( 'Google Maps API Key', 'autocomplete-google-address' ); ?></strong></label>
                    <?php
                    $current_api_key = $options['api_key'] ?? '';
                    $masked_api_key = '';
                    if ( ! empty( $current_api_key ) ) {
                        $length = strlen( $current_api_key );
                        $masked_api_key = str_repeat( '•', max( 0, $length - 4 ) ) . substr( $current_api_key, -4 );
                    }
                    ?>
                    <input type="text" id="api_key" name="Nish_aga_settings[api_key]" value="<?php echo esc_attr( $masked_api_key ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your Google Maps API Key', 'autocomplete-google-address' ); ?>" />
                    <p class="description">
                        <?php echo wp_kses_post( __( 'You must enable the <strong>Places API</strong> and <strong>Maps JavaScript API</strong> in your Google Cloud Platform console.', 'autocomplete-google-address' ) ); ?><br/>
                        <?php esc_html_e( 'Leave this field blank to keep the existing API key. Enter a new key to update it.', 'autocomplete-google-address' ); ?>
                    </p>
                </div>


            </div>
        </div>

        <div class="aga-card">
            <div class="aga-card-header">
                <h2><?php esc_html_e( 'Advanced Settings', 'autocomplete-google-address' ); ?></h2>
            </div>
            <div class="aga-card-body">
                <!-- Conflict Handling -->
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
                    <p class="description" style="margin-top: 5px;"><?php esc_html_e( 'Enable this if your theme or another plugin already loads the Google Maps API. This will prevent conflicts.', 'autocomplete-google-address' ); ?></p>
                </div>
            </div>
        </div>

        <?php submit_button(); ?>
    </form>
</div>
