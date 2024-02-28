<?php

class WC_Gateway_Vendy_One extends WC_Gateway_Custom_Vendy {

	/**
	 * Payment channels.
	 *
	 * @var array
	 */
	public $payment_channels;

	/**
	 * Allowed bank cards.
	 *
	 * @var array
	 */
	public $banks;

	/**
	 * Allowed card brands.
	 *
	 * @var array
	 */
	public $cards;

	/**
	 * Payment icons.
	 *
	 * @var array
	 */
	public $payment_icons;

	/**
	 * Vendy settings.
	 *
	 * @var array
	 */
	public $vendy_settings;

	/**
	 * WC_Gateway_Vendy_One constructor.
	 */
	public function __construct() {

		$this->id = 'vendy-one';

		$gateway_title = $this->get_option( 'title' );

		if ( empty( $gateway_title ) ) {
			$gateway_title = __( 'One', 'woo-vendy' );
		}

		$this->method_title       = sprintf( __( 'Vendy - %s', 'woo-vendy' ), $gateway_title );
		$this->method_description = sprintf( __( 'Vendy provide merchants with the tools and services needed to accept online payments from local and international customers using Mastercard, Visa, Verve Cards and Bank Accounts. <a href="%1$s" target="_blank">Sign up</a> for a Vendy account, and <a href="%2$s" target="_blank">get your API keys</a>.', 'woo-vendy' ), 'https://myvendy.com', 'https://dashboard.myvendy.comm/#/settings/developer' );

		$this->payment_page = $this->get_option( 'payment_page' );

		$this->has_fields = true;

		$this->supports = array(
			'products',
			'tokenization',
			'subscriptions',
			'multiple_subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
		);

		$this->vendy_settings = get_option( 'woocommerce_vendy_settings', '' );

		// Get setting values.
		$this->title       = $gateway_title;
		$this->description = $this->get_option( 'description' );
		$this->enabled     = $this->get_option( 'enabled' );

		$this->testmode = $this->vendy_settings['testmode'] === 'yes' ? true : false;

		$this->payment_channels = $this->get_option( 'payment_channels' );

		$this->cards = $this->get_option( 'cards_allowed' );
		$this->banks = $this->get_option( 'banks_allowed' );

		$this->test_public_key = $this->vendy_settings['test_public_key'];
		$this->test_secret_key = $this->vendy_settings['test_secret_key'];

		$this->live_public_key = $this->vendy_settings['live_public_key'];
		$this->live_secret_key = $this->vendy_settings['live_secret_key'];

		$this->payment_icons = $this->get_option( 'payment_icons' );

		$this->custom_metadata = $this->get_option( 'custom_metadata' ) === 'yes' ? true : false;

		$this->meta_order_id         = $this->get_option( 'meta_order_id' ) === 'yes' ? true : false;
		$this->meta_name             = $this->get_option( 'meta_name' ) === 'yes' ? true : false;
		$this->meta_email            = $this->get_option( 'meta_email' ) === 'yes' ? true : false;
		$this->meta_phone            = $this->get_option( 'meta_phone' ) === 'yes' ? true : false;
		$this->meta_billing_address  = $this->get_option( 'meta_billing_address' ) === 'yes' ? true : false;
		$this->meta_shipping_address = $this->get_option( 'meta_shipping_address' ) === 'yes' ? true : false;
		$this->meta_products         = $this->get_option( 'meta_products' ) === 'yes' ? true : false;

		$this->public_key = $this->testmode ? $this->test_public_key : $this->live_public_key;
		$this->secret_key = $this->testmode ? $this->test_secret_key : $this->live_secret_key;

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );

		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'add_gateway_to_checkout' ) );

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {

			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );

		}

	}

}
