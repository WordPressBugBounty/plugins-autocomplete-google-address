<?php
/**
 * Handles building the JS configuration object for the frontend.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/includes
 * @author     Md Nishath Khandakar <https://profiles.wordpress.org/nishatbd31/>
 */

defined( 'ABSPATH' ) || exit;

class AGA_Autocomplete {

    /**
     * Generates the configuration object for a specific form ID.
     * This object will be localized and used by the frontend JavaScript.
     *
     * @param int $form_id The ID of the aga_form post.
     * @return array The configuration array.
     */
    public static function get_js_config( $form_id ) {
        $form_id = absint( $form_id );
        if ( ! $form_id || 'aga_form' !== get_post_type( $form_id ) ) {
            return array();
        }

        $mode = get_post_meta( $form_id, 'Nish_aga_mode', true );
        $main_selector = get_post_meta( $form_id, 'Nish_aga_main_selector', true );

        $config = array(
            'form_id'       => $form_id,
            'mode'          => $mode,
            'main_selector' => $main_selector,
            'language'      => get_post_meta( $form_id, 'Nish_aga_language_override', true ),
            'selectors'     => array(),
        );

        if ( 'single_line' === $mode ) {
            $config['selectors']['lat'] = get_post_meta( $form_id, 'Nish_aga_lat_selector', true );
            $config['selectors']['lng'] = get_post_meta( $form_id, 'Nish_aga_lng_selector', true );
            $config['selectors']['place_id'] = get_post_meta( $form_id, 'Nish_aga_place_id_selector', true );
        } elseif ( 'smart_mapping' === $mode ) {
            $config['selectors']['street'] = get_post_meta( $form_id, 'Nish_aga_street_selector', true );
            $config['selectors']['city'] = get_post_meta( $form_id, 'Nish_aga_city_selector', true );
            $config['selectors']['state'] = get_post_meta( $form_id, 'Nish_aga_state_selector', true );
            $config['selectors']['zip'] = get_post_meta( $form_id, 'Nish_aga_zip_selector', true );
            $config['selectors']['country'] = get_post_meta( $form_id, 'Nish_aga_country_selector', true );
            $config['selectors']['lat'] = get_post_meta( $form_id, 'Nish_aga_map_lat_selector', true );
            $config['selectors']['lng'] = get_post_meta( $form_id, 'Nish_aga_map_lng_selector', true );
            $config['selectors']['place_id'] = get_post_meta( $form_id, 'Nish_aga_smart_place_id_selector', true );
            
            $config['formats'] = array(
                'state'   => get_post_meta( $form_id, 'Nish_aga_state_format', true ) ?: 'long',
                'country' => get_post_meta( $form_id, 'Nish_aga_country_format', true ) ?: 'long',
            );
        }

        // Add per-config settings for autocomplete restrictions
        $config['component_restrictions'] = array();
        $country_restriction = get_post_meta( $form_id, 'Nish_aga_country_restriction', true );
        if ( ! empty( $country_restriction ) ) {
            $config['component_restrictions']['country'] = $country_restriction;
        }

        // Clean up empty selectors
        $config['selectors'] = array_filter( $config['selectors'] );

        return $config;
    }
}
