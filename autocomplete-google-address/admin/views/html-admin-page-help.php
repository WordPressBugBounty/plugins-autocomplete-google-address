<?php
/**
 * Provides the admin area view for the help page.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/admin/views
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h2 class="hndle"><span><?php esc_html_e( 'How to Use', 'autocomplete-google-address' ); ?></span></h2>
                        <div class="inside">
                            <h3><?php esc_html_e( '1. Configure your Settings', 'autocomplete-google-address' ); ?></h3>
                            <p><?php echo wp_kses_post( __( 'Go to <strong>Google Address &raquo; Settings</strong> and enter your Google Maps API key. This is required for the plugin to work.', 'autocomplete-google-address' ) ); ?></p>
                            
                            <h3><?php esc_html_e( '2. Create a New Form Configuration', 'autocomplete-google-address' ); ?></h3>
                            <p><?php echo wp_kses_post( __( 'Go to <strong>Google Address &raquo; Add New</strong>. Give your configuration a name and choose a mode (Single Line or Smart Mapping).', 'autocomplete-google-address' ) ); ?></p>
                            
                            <h3><?php esc_html_e( '3. Find Your Field Selectors', 'autocomplete-google-address' ); ?></h3>
                            <p><?php esc_html_e( 'Go to the page on your site with the form you want to add autocomplete to. Right-click on the form field and choose "Inspect" from the browser menu. Find the `id` or `class` of the input field.', 'autocomplete-google-address' ); ?></p>
                            <p><?php esc_html_e( 'If it has an ID (e.g., `id="billing_address_1"`), your selector is `#billing_address_1`.', 'autocomplete-google-address' ); ?></p>
                            <p><?php esc_html_e( 'If it has a class (e.g., `class="address-field"`), your selector is `.address-field`.', 'autocomplete-google-address' ); ?></p>

                            <h3><?php esc_html_e( '4. Configure the Mapping', 'autocomplete-google-address' ); ?></h3>
                            <p><?php esc_html_e( 'Copy the selector and paste it into the "Main Autocomplete Field Selector" field in your configuration. If you are using Smart Mapping, do the same for all the other address fields.', 'autocomplete-google-address' ); ?></p>

                            <h3><?php esc_html_e( '5. Add the Shortcode to Your Page', 'autocomplete-google-address' ); ?></h3>
                            <p><?php esc_html_e( 'After saving your configuration, a shortcode will be generated (e.g., `[aga_form id="123"]`). Copy this shortcode and paste it into the content of the page where your form is located. The shortcode itself does not display anything; it just loads the necessary scripts.', 'autocomplete-google-address' ); ?></p>
                            
                            <h3><?php esc_html_e( 'For Developers', 'autocomplete-google-address' ); ?></h3>
                            <p><?php esc_html_e( 'You can also use the PHP function `aga_render_form_config( $id )` in your theme templates to load a configuration programmatically.', 'autocomplete-google-address' ); ?></p>

                        </div>
                    </div>
                </div>
            </div>

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h2 class="hndle"><span><?php esc_html_e( 'Plugin Resources', 'autocomplete-google-address' ); ?></span></h2>
                        <div class="inside">
                            <ul>
                                <li><a href="#" target="_blank"><?php esc_html_e( 'Support Forum', 'autocomplete-google-address' ); ?></a></li>
                                <li><a href="#" target="_blank"><?php esc_html_e( 'Rate this Plugin', 'autocomplete-google-address' ); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <br class="clear">
    </div>
</div>
