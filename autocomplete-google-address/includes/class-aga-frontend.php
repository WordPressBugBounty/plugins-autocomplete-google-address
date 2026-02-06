<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/nishatbd31/
 * @since      1.0.0
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/public
 * @author     Md Nishath Khandakar <https://profiles.wordpress.org/nishatbd31/>
 */
class AGA_Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * A list of form config IDs to be loaded on the current page.
     *
     * @since    1.0.0
     * @access   private
     * @var      array
     */
    private $forms_to_load = array();

    /**
     * A flag to ensure Google Maps API is enqueued only once.
     *
     * @since    1.0.0
     * @access   private
     * @var      boolean
     */
    private static $gmaps_enqueued = false;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( empty( $this->forms_to_load ) ) {
            return;
        }

		wp_enqueue_style( $this->plugin_name, AGA_PLUGIN_URL . 'public/css/frontend.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( empty( $this->forms_to_load ) ) {
            return;
        }

        $this->enqueue_google_maps_api();

		wp_enqueue_script( $this->plugin_name, AGA_PLUGIN_URL . 'public/js/frontend.js', array( 'jquery' ), $this->version, true );
        
        $this->localize_script_data();
	}
    
    /**
     * Handles the [aga_form] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Empty string. The shortcode's purpose is to enqueue scripts.
     */
    public function render_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'id' => 0,
            ),
            $atts,
            'aga_form'
        );

        $id = absint( $atts['id'] );
        if ( $id ) {
            $this->prepare_scripts_for_form( $id );
        }

        return ''; // The shortcode itself outputs nothing.
    }
    
    /**
     * Adds a form ID to the list of forms to be loaded on the page.
     *
     * @param int $form_id The ID of the form config post.
     */
    public function prepare_scripts_for_form( $form_id ) {
        if ( ! in_array( $form_id, $this->forms_to_load, true ) ) {
            $this->forms_to_load[] = $form_id;
        }
    }

    /**
     * Adds the Google Maps bootstrap loader script to the footer.
     * This is the recommended way to load the API asynchronously.
     */
    private function enqueue_google_maps_api() {
        if ( self::$gmaps_enqueued || aga_get_setting( 'do_not_load_gmaps_api' ) ) {
            return;
        }

        $api_key = aga_get_setting( 'api_key' );
        if ( empty( $api_key ) ) {
            return;
        }
        
        $language = '';
        if ( ! empty( $this->forms_to_load ) ) {
            foreach ( $this->forms_to_load as $form_id ) {
                $lang_override = get_post_meta( $form_id, 'Nish_aga_language_override', true );
                if ( ! empty( $lang_override ) ) {
                    $language = $lang_override;
                    break;
                }
            }
        }
        
        if ( empty( $language ) ) {
            $language = get_locale();
        }

        $api_url = add_query_arg(
            array(
                'key'       => $api_key,
                'libraries' => 'places',
                'language'  => $language,
            ),
            'https://maps.googleapis.com/maps/api/js'
        );

        wp_enqueue_script( 'aga-google-maps', esc_url( $api_url ), array(), null, true );
        
        self::$gmaps_enqueued = true;
    }
    
    /**
     * Localizes the form configuration data for the frontend script.
     */
    private function localize_script_data() {
        $configs = array();
        
        // Remove duplicates and ensure we have valid IDs.
        $this->forms_to_load = array_unique( array_map( 'absint', $this->forms_to_load ) );
        
        foreach ( $this->forms_to_load as $form_id ) {
            $form_post = get_post( $form_id );
            if ( $form_post && 'aga_form' === $form_post->post_type ) {
                $configs[] = AGA_Autocomplete::get_js_config( $form_id );
            }
        }

        wp_localize_script( $this->plugin_name, 'aga_form_configs', $configs );
    }

    /**
     * Finds and prepares any globally or page-specifically activated forms.
     * This runs on every page load on the frontend to check for configs that should be active.
     *
     * @since 1.1.0
     */
    public function load_automatic_forms() {
        $forms_to_load = array();

        // 1. Get globally active forms
        $global_args = array(
            'post_type'      => 'aga_form',
            'posts_per_page' => -1,
            'meta_key'       => 'Nish_aga_activate_globally',
            'meta_value'     => '1',
            'fields'         => 'ids',
        );
        $global_forms = get_posts( $global_args );
        if ( ! empty( $global_forms ) ) {
            $forms_to_load = array_merge( $forms_to_load, $global_forms );
        }

        // 2. Get forms active for the current page
        $current_page_id = get_queried_object_id();
        if ( $current_page_id ) {
            $page_args = array(
                'post_type'      => 'aga_form',
                'posts_per_page' => -1,
                'meta_query'     => array(
                    array(
                        'key'     => 'Nish_aga_load_on_pages',
                        'compare' => 'EXISTS',
                    ),
                ),
                'fields'         => 'ids',
            );
            $page_specific_forms = get_posts( $page_args );
            if ( ! empty( $page_specific_forms ) ) {
                foreach ( $page_specific_forms as $form_id ) {
                    $selected_pages = get_post_meta( $form_id, 'Nish_aga_load_on_pages', true );
                    if ( is_array( $selected_pages ) && in_array( $current_page_id, $selected_pages, true ) ) {
                        $forms_to_load[] = $form_id;
                    }
                }
            }
        }

        if ( ! empty( $forms_to_load ) ) {
            foreach ( array_unique( $forms_to_load ) as $form_id ) {
                $this->prepare_scripts_for_form( $form_id );
            }
        }
    }
    public function add_async_attribute( $tag, $handle ) {
        if ( 'aga-google-maps' !== $handle ) {
            return $tag;
        }
        return str_replace( ' src', ' async src', $tag );
    }

}
