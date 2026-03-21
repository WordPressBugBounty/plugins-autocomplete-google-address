<?php
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce auto-integration.
 *
 * Adds Google Places autocomplete to WooCommerce checkout address fields
 * automatically when WooCommerce is active and the integration is enabled.
 */
class AGA_WooCommerce {

	/**
	 * Initialize hooks.
	 */
	public function __construct() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if ( ! function_exists( 'google_autocomplete' ) || ! google_autocomplete()->is_paying() ) {
			return;
		}

		if ( ! aga_get_setting( 'woocommerce_enabled' ) ) {
			return;
		}

		add_filter( 'aga_form_configs', array( $this, 'add_checkout_configs' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue' ) );
	}

	/**
	 * Only enqueue on the WooCommerce checkout page.
	 */
	public function maybe_enqueue() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}

		add_filter( 'aga_should_load_frontend', '__return_true' );
	}

	/**
	 * Add WooCommerce checkout configs to the frontend config array.
	 *
	 * @param array $configs Existing form configs.
	 * @return array
	 */
	public function add_checkout_configs( $configs ) {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return $configs;
		}

		$checkout_configs = $this->get_checkout_configs();

		return array_merge( $configs, $checkout_configs );
	}

	/**
	 * Build the checkout autocomplete configs.
	 *
	 * @return array
	 */
	public function get_checkout_configs() {
		$is_block_checkout = aga_get_setting( 'woocommerce_block_checkout' );

		if ( $is_block_checkout ) {
			return $this->get_block_checkout_configs();
		}

		return $this->get_classic_checkout_configs();
	}

	/**
	 * Configs for the classic WooCommerce checkout.
	 *
	 * @return array
	 */
	private function get_classic_checkout_configs() {
		return array(
			array(
				'form_id'                => 'woo_billing',
				'mode'                   => 'smart_mapping',
				'main_selector'          => '#billing_address_1',
				'selectors'              => array(
					'street'  => '#billing_address_1',
					'city'    => '#billing_city',
					'state'   => '#billing_state',
					'zip'     => '#billing_postcode',
					'country' => '#billing_country',
				),
				'formats'                => array(
					'state'   => 'short',
					'country' => 'short',
				),
				'component_restrictions' => array(),
			),
			array(
				'form_id'                => 'woo_shipping',
				'mode'                   => 'smart_mapping',
				'main_selector'          => '#shipping_address_1',
				'selectors'              => array(
					'street'  => '#shipping_address_1',
					'city'    => '#shipping_city',
					'state'   => '#shipping_state',
					'zip'     => '#shipping_postcode',
					'country' => '#shipping_country',
				),
				'formats'                => array(
					'state'   => 'short',
					'country' => 'short',
				),
				'component_restrictions' => array(),
			),
		);
	}

	/**
	 * Configs for the WooCommerce block-based checkout.
	 *
	 * @return array
	 */
	private function get_block_checkout_configs() {
		return array(
			array(
				'form_id'                => 'woo_billing',
				'mode'                   => 'smart_mapping',
				'main_selector'          => '#billing-address_1',
				'selectors'              => array(
					'street'  => '#billing-address_1',
					'city'    => '#billing-city',
					'state'   => '#billing-state',
					'zip'     => '#billing-postcode',
					'country' => '#billing-country',
				),
				'formats'                => array(
					'state'   => 'short',
					'country' => 'short',
				),
				'component_restrictions' => array(),
			),
			array(
				'form_id'                => 'woo_shipping',
				'mode'                   => 'smart_mapping',
				'main_selector'          => '#shipping-address_1',
				'selectors'              => array(
					'street'  => '#shipping-address_1',
					'city'    => '#shipping-city',
					'state'   => '#shipping-state',
					'zip'     => '#shipping-postcode',
					'country' => '#shipping-country',
				),
				'formats'                => array(
					'state'   => 'short',
					'country' => 'short',
				),
				'component_restrictions' => array(),
			),
		);
	}
}
