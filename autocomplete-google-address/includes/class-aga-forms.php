<?php
defined( 'ABSPATH' ) || exit;

class AGA_Forms {

    private $post_type = 'aga_form';

public function __construct() {
    add_action( 'init', array( $this, 'register_post_type' ), 20 );
    // add_action( 'admin_menu', array( $this, 'remove_duplicate_add_new_menu' ), 999 );
    

    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
    add_action( 'save_post_' . $this->post_type, array( $this, 'save_meta_box_data' ) );
    add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'set_custom_edit_columns' ) );
    add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'custom_column_content' ), 10, 2 );
}


    /**
     * Register Custom Post Type
     */
    public function register_post_type() {

        $labels = array(
            'name'               => 'Address Forms',
            'singular_name'      => 'Address Form',
            'menu_name'          => 'Google Address',
            'name_admin_bar'     => 'Address Form',
            'add_new'            => 'Add New Form Config',
            'add_new_item'       => 'Add New',
            'new_item'           => 'New Form Config',
            'edit_item'          => 'Edit Form Config',
            'view_item'          => 'View Form Config',
            'all_items'          => 'All Configs',
            'search_items'       => 'Search Form Configs',
            'not_found'          => 'No form configs found.',
            'not_found_in_trash' => 'No form configs found in Trash.',
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-location-alt',
            'menu_position'      => 13,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => array( 'title' ),
            'show_in_rest'       => true,
        );

        register_post_type( $this->post_type, $args );
    }

    public function remove_duplicate_add_new_menu() {
    remove_submenu_page(
        'edit.php?post_type=' . $this->post_type,
        'post-new.php?post_type=' . $this->post_type
    );
}

public function add_custom_add_new_menu() {

    add_submenu_page(
        'edit.php?post_type=' . $this->post_type,
        'Add New Form Config',
        'Add New Form Config',
        'edit_posts',
        'post-new.php?post_type=' . $this->post_type
    );
}



    /**
     * Meta Box
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aga_form_config_metabox',
            'Form Configuration',
            array( $this, 'render_meta_box' ),
            $this->post_type,
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'aga_save_meta_box_data', 'aga_meta_box_nonce' );

        require_once AGA_PLUGIN_DIR . 'admin/views/html-admin-page-form-edit.php';
    }

    /**
     * Save Meta
     */
    public function save_meta_box_data( $post_id ) {

        if (
            ! isset( $_POST['aga_meta_box_nonce'] ) ||
            ! wp_verify_nonce( $_POST['aga_meta_box_nonce'], 'aga_save_meta_box_data' )
        ) {
            return;
        }

        if (
            defined( 'DOING_AUTOSAVE' ) ||
            wp_is_post_revision( $post_id ) ||
            get_post_type( $post_id ) !== $this->post_type ||
            ! current_user_can( 'edit_post', $post_id )
        ) {
            return;
        }

        $fields = array(
            'Nish_aga_mode',
            'Nish_aga_main_selector',
            'Nish_aga_language_override',
            'Nish_aga_country_restriction',
            'Nish_aga_lat_selector',
            'Nish_aga_lng_selector',
            'Nish_aga_place_id_selector',
            'Nish_aga_street_selector',
            'Nish_aga_city_selector',
            'Nish_aga_state_selector',
            'Nish_aga_zip_selector',
            'Nish_aga_country_selector',
            'Nish_aga_map_lat_selector',
            'Nish_aga_map_lng_selector',
            'Nish_aga_smart_place_id_selector',
            'Nish_aga_state_format',
            'Nish_aga_country_format',
        );

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta(
                    $post_id,
                    $field,
                    sanitize_text_field( wp_unslash( $_POST[ $field ] ) )
                );
            } else {
                delete_post_meta( $post_id, $field );
            }
        }

        update_post_meta(
            $post_id,
            'Nish_aga_activate_globally',
            isset( $_POST['Nish_aga_activate_globally'] ) ? '1' : ''
        );

        if ( isset( $_POST['Nish_aga_load_on_pages'] ) && is_array( $_POST['Nish_aga_load_on_pages'] ) ) {
            update_post_meta(
                $post_id,
                'Nish_aga_load_on_pages',
                array_map( 'absint', $_POST['Nish_aga_load_on_pages'] )
            );
        } else {
            delete_post_meta( $post_id, 'Nish_aga_load_on_pages' );
        }
    }

    /**
     * Admin Columns
     */
    public function set_custom_edit_columns( $columns ) {
        return array(
            'cb'         => $columns['cb'],
            'title'      => $columns['title'],
            'aga_mode'   => 'Mode',
            'aga_global' => 'Global',
            'date'       => $columns['date'],
        );
    }

    public function custom_column_content( $column, $post_id ) {

        if ( 'aga_mode' === $column ) {
            $mode = get_post_meta( $post_id, 'Nish_aga_mode', true );
            echo esc_html(
                $mode === 'single_line'
                    ? 'Single Line'
                    : ( $mode === 'smart_mapping' ? 'Smart Mapping' : 'None' )
            );
        }

        if ( 'aga_global' === $column ) {
            echo get_post_meta( $post_id, 'Nish_aga_activate_globally', true )
                ? '<span style="color:green;">✓</span>'
                : '<span style="color:red;">✗</span>';
        }
    }
}

new AGA_Forms();
