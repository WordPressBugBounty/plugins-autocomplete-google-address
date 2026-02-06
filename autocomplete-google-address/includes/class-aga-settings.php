<?php
/**
 * Handles the plugin's settings page.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/includes
 * @author     Md Nishath Khandakar <https://profiles.wordpress.org/nishatbd31/>
 */

defined( 'ABSPATH' ) || exit;

class AGA_Settings {

    /**
     * The single instance of the class.
     *
     * @var AGA_Settings
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main AGA_Settings Instance.
     *
     * Ensures only one instance of AGA_Settings is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return AGA_Settings - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Register settings, sections, and fields.
     */
    public function register_settings() {
        register_setting(
            'Nish_aga_settings_group', // Option group
            'Nish_aga_settings',       // Option name
            array( $this, 'sanitize' ) // Sanitize callback
        );




    }

    /**
     * Sanitize each setting field as needed.
     *
     * @param array $input Contains all settings fields as array keys
     * @return array
     */
    public function sanitize( $input ) {
        $new_input = array();
        $options = get_option( 'Nish_aga_settings', array() );
        if ( isset( $input['api_key'] ) ) {
            $new_api_key = sanitize_text_field( $input['api_key'] );
            if ( ! empty( $new_api_key ) ) {
                $new_input['api_key'] = $new_api_key;
            } else if ( ! empty( $options['api_key'] ) ) {
                $new_input['api_key'] = $options['api_key'];
            }
        }

        if ( isset( $input['do_not_load_gmaps_api'] ) ) {
            $new_input['do_not_load_gmaps_api'] = absint( $input['do_not_load_gmaps_api'] );
        } else {
            $new_input['do_not_load_gmaps_api'] = 0;
        }
        

        


        return $new_input;
    }



}
