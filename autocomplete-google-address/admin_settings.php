<?php
// Including meta box
include('metabox/init.php');

add_action('cmb2_admin_init', 'myprefix_register_theme_options_metabox');

// Enqueue custom styles for CMB2 fields
add_action('admin_enqueue_scripts', 'myprefix_custom_cmb2_styles');
function myprefix_custom_cmb2_styles() {
    $custom_css = "
        /* Style the metabox container */
        #myprefix_option_metabox .cmb2-wrap {
		width: 97%;
            background: #f7f7f7;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Style the title */
        #myprefix_option_metabox h2.hndle {
            font-size: 1.5em;
            color: #333;
            padding: 0 0 10px;
            margin: 0;
        }

        /* Style each field label */
        #myprefix_option_metabox .cmb2-metabox .cmb-th label {
            font-weight: bold;
            color: #555;
        }

        /* Style input fields */
        #myprefix_option_metabox .cmb2-metabox input[type='text'] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Style description text */
        #myprefix_option_metabox .cmb2-metabox .cmb2-metabox-description {
            font-style: italic;
            color: #888;
            margin-top: 5px;
        }
    ";

    wp_add_inline_style('wp-admin', $custom_css);
}

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function myprefix_register_theme_options_metabox() {

    $cmb_options = new_cmb2_box(array(
        'id'           => 'myprefix_option_metabox',
        'title'        => esc_html__('Autocomplete Settings', 'myprefix'),
        'object_types' => array('options-page'),
        'option_key'   => 'autocomplete',
        'icon_url'     => 'dashicons-location-alt',
        'capability'   => 'manage_options',
        'cmb_styles'   => false,
    ));

    $cmb_options->add_field(array(
        'name'    => __('Google Place API Key', 'myprefix'),
        'desc'    => __('Enter your Google Place API key here.', 'myprefix'),
        'id'      => 'google_place_api',
        'type'    => 'text',
        'default' => '',
    ));

    $cmb_options->add_field(array(
        'name'    => __('Form ID', 'myprefix'),
        'desc'    => __('Enter Form ID(s) without "#". Use commas for multiple IDs.', 'myprefix'),
        'id'      => 'form_id',
        'type'    => 'text',
        'default' => '',
    ));

    $cmb_options->add_field(array(
        'name' => __('Autocomplete Pro Features', 'myprefix'),
        'desc' => __(
            '<div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: #f9f9f9;">
                <ul style="margin: 0; padding: 0 20px;">
                    <li>Full and Smart Address types</li>
                    <li>Unlimited form use</li>
                    <li>Works on any form</li>
                    <li>Country restriction support</li>
                    <li>WooCommerce location map picker</li>
                </ul>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="https://checkout.freemius.com/mode/dialog/plugin/6886/plan/11211/" 
                        style="display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: orange; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">
                        Go For Pro
                    </a>
                    <a href="https://devsupport.vercel.app/" 
                        target="_blank" 
                        style="display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: green; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">
                        Buy Pro From Developer
                    </a>
                </div>
            </div>', 
            'myprefix'
        ),
        'id'   => 'ext',
        'type' => 'title',
    ));
}

/**
 * Wrapper function around cmb2_get_option
 * @since 0.1.0
 * @param string $key     Options array key
 * @param mixed  $default Optional default value
 * @return mixed           Option value
 */
function myprefix_get_option($key = '', $default = false) {
    if (function_exists('cmb2_get_option')) {
        return cmb2_get_option('autocomplete', $key, $default);
    }
    $opts = get_option('autocomplete', $default);
    $val = $default;
    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }
    return $val;
}
