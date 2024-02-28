<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Gateway_Vendy extends WC_Payment_Gateway_CC
{

    /**
     * Is test mode active?
     *
     * @var bool
     */
    public $testmode;

    /**
     * Should orders be marked as complete after payment?
     *
     * @var bool
     */
    public $autocomplete_order;

    /**
     * Vendy payment page type.
     *
     * @var string
     */
    public $payment_page;

    /**
     * Vendy test public key.
     *
     * @var string
     */
    public $test_public_key;

    /**
     * Vendy test secret key.
     *
     * @var string
     */
    public $test_secret_key;

    /**
     * Vendy live public key.
     *
     * @var string
     */
    public $live_public_key;

    /**
     * Vendy live secret key.
     *
     * @var string
     */
    public $live_secret_key;

    /**
     * Should we save customer cards?
     *
     * @var bool
     */
    public $saved_cards = false;

    /**
     * Should Vendy split payment be enabled.
     *
     * @var bool
     */
    public $split_payment = false;

    /**
     * Should the cancel & remove order button be removed on the pay for order page.
     *
     * @var bool
     */
    public $remove_cancel_order_button;

    /**
     * Vendy sub account code.
     *
     * @var string
     */
    public $subaccount_code;

    /**
     * Who bears Vendy charges?
     *
     * @var string
     */
    public $charges_account;

    /**
     * A flat fee to charge the sub account for each transaction.
     *
     * @var string
     */
    public $transaction_charges;

    /**
     * Should custom metadata be enabled?
     *
     * @var bool
     */
    public $custom_metadata;

    /**
     * Should the order id be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_order_id;

    /**
     * Should the customer name be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_name;

    /**
     * Should the billing email be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_email;

    /**
     * Should the billing phone be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_phone;

    /**
     * Should the billing address be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_billing_address;

    /**
     * Should the shipping address be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_shipping_address;

    /**
     * Should the order items be sent as a custom metadata to Vendy?
     *
     * @var bool
     */
    public $meta_products;

    /**
     * API public key
     *
     * @var string
     */
    public $public_key;

    /**
     * API secret key
     *
     * @var string
     */
    public $secret_key;

    /**
     * Gateway disabled message
     *
     * @var string
     */
    public $msg;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = 'vendy';
        $this->method_title = __('Vendy', 'woo-vendy');
        $this->method_description = sprintf(__('Vendy provide merchants with the tools and services needed to accept online payments from local and international customers using Mastercard, Visa, Verve Cards and Bank Accounts. <a href="%1$s" target="_blank">Sign up</a> for a Vendy account, and <a href="%2$s" target="_blank">get your API keys</a>.', 'woo-vendy'), 'https://myvendy.com', 'https://dashboard.myvendy.com');
        $this->has_fields = true;

        $this->payment_page = $this->get_option('payment_page');

        $this->supports = array(
            'products',
            'refunds',
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

        // Load the form fields
        $this->init_form_fields();

        // Load the settings
        $this->init_settings();

        // Get setting values

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->enabled = $this->get_option('enabled');
        $this->testmode = $this->get_option('testmode') === 'yes' ? true : false;
        $this->autocomplete_order = $this->get_option('autocomplete_order') === 'yes' ? true : false;

        $this->test_public_key = $this->get_option('test_public_key');
        $this->test_secret_key = $this->get_option('test_secret_key');

        $this->live_public_key = $this->get_option('live_public_key');
        $this->live_secret_key = $this->get_option('live_secret_key');

//		$this->saved_cards = $this->get_option( 'saved_cards' ) === 'yes' ? true : false;

//		$this->split_payment              = $this->get_option( 'split_payment' ) === 'yes' ? true : false;
        $this->remove_cancel_order_button = $this->get_option('remove_cancel_order_button') === 'yes' ? true : false;
//		$this->subaccount_code            = $this->get_option( 'subaccount_code' );
//		$this->charges_account            = $this->get_option( 'split_payment_charge_account' );
//		$this->transaction_charges        = $this->get_option( 'split_payment_transaction_charge' );

        $this->custom_metadata = $this->get_option('custom_metadata') === 'yes' ? true : false;

        $this->meta_order_id = $this->get_option('meta_order_id') === 'yes' ? true : false;
        $this->meta_name = $this->get_option('meta_name') === 'yes' ? true : false;
        $this->meta_email = $this->get_option('meta_email') === 'yes' ? true : false;
        $this->meta_phone = $this->get_option('meta_phone') === 'yes' ? true : false;
        $this->meta_billing_address = $this->get_option('meta_billing_address') === 'yes' ? true : false;
        $this->meta_shipping_address = $this->get_option('meta_shipping_address') === 'yes' ? true : false;
        $this->meta_products = $this->get_option('meta_products') === 'yes' ? true : false;

        $this->public_key = $this->testmode ? $this->test_public_key : $this->live_public_key;
        $this->secret_key = $this->testmode ? $this->test_secret_key : $this->live_secret_key;

        // Hooks
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        add_action('admin_notices', array($this, 'admin_notices'));
        add_action(
            'woocommerce_update_options_payment_gateways_' . $this->id,
            array(
                $this,
                'process_admin_options',
            )
        );

        add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

        // Payment listener/API hook.
        add_action('woocommerce_api_wc_gateway_vendy', array($this, 'verify_vendy_transaction'));

        // Webhook listener/API hook.
        add_action('woocommerce_api_tbz_wc_vendy_webhook', array($this, 'process_webhooks'));

        // Check if the gateway can be used.
        if (!$this->is_valid_for_use()) {
            $this->enabled = false;
        }

    }

    /**
     * Check if this gateway is enabled and available in the user's country.
     */
    public function is_valid_for_use()
    {

        if (!in_array(get_woocommerce_currency(), apply_filters('woocommerce_vendy_supported_currencies', array('NGN', 'USD', 'ZAR', 'GHS', 'KES', 'XOF', 'EGP', 'RWF')))) {

            $this->msg = sprintf(__('Vendy does not support your store currency. Kindly set it to either NGN (&#8358), GHS (&#x20b5;), USD (&#36;), KES (KSh), RWF (R₣), ZAR (R), XOF (CFA), or EGP (E£) <a href="%s">here</a>', 'woo-vendy'), admin_url('admin.php?page=wc-settings&tab=general'));

            return false;

        }

        return true;

    }

    /**
     * Display vendy payment icon.
     */
    public function get_icon()
    {

        $base_location = wc_get_base_location();

        if ('GH' === $base_location['country']) {
            $icon = '<img src="' . WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-gh.png', WC_VENDY_MAIN_FILE)) . '" alt="Vendy Payment Options" />';
        } elseif ('ZA' === $base_location['country']) {
            $icon = '<img src="' . WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-za.png', WC_VENDY_MAIN_FILE)) . '" alt="Vendy Payment Options" />';
        } elseif ('KE' === $base_location['country']) {
            $icon = '<img src="' . WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-ke.png', WC_VENDY_MAIN_FILE)) . '" alt="Vendy Payment Options" />';
        } elseif ('CI' === $base_location['country']) {
            $icon = '<img src="' . WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-civ.png', WC_VENDY_MAIN_FILE)) . '" alt="Vendy Payment Options" />';
        } else {
            $icon = '<img src="' . WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-wc.png', WC_VENDY_MAIN_FILE)) . '" alt="Vendy Payment Options" />';
        }

        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);

    }

    /**
     * Check if Vendy merchant details is filled.
     */
    public function admin_notices()
    {

        if ($this->enabled == 'no') {
            return;
        }

        // Check required fields.
        if (!($this->public_key && $this->secret_key)) {
            echo '<div class="error"><p>' . sprintf(__('Please enter your Vendy merchant details <a href="%s">here</a> to be able to use the Vendy WooCommerce plugin.', 'woo-vendy'), admin_url('admin.php?page=wc-settings&tab=checkout&section=vendy')) . '</p></div>';
            return;
        }

    }

    /**
     * Check if Vendy gateway is enabled.
     *
     * @return bool
     */
    public function is_available()
    {

        if ('yes' == $this->enabled) {

            if (!($this->public_key && $this->secret_key)) {

                return false;

            }

            return true;

        }

        return false;

    }

    /**
     * Admin Panel Options.
     */
    public function admin_options()
    {

        ?>

        <h2><?php _e('Vendy', 'woo-vendy'); ?>
            <?php
            if (function_exists('wc_back_link')) {
                wc_back_link(__('Return to payments', 'woo-vendy'), admin_url('admin.php?page=wc-settings&tab=checkout'));
            }
            ?>
        </h2>

        <h4>
            <strong><?php printf(__('Optional: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="%1$s" target="_blank" rel="noopener noreferrer">here</a> to the URL below<span style="color: red"><pre><code>%2$s</code></pre></span>', 'woo-vendy'), 'https://dashboard.myvendy.com', WC()->api_request_url('Tbz_WC_Vendy_Webhook')); ?></strong>
        </h4>

        <?php

        if ($this->is_valid_for_use()) {

            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';

        } else {
            ?>
            <div class="inline error"><p>
                    <strong><?php _e('Vendy Payment Gateway Disabled', 'woo-vendy'); ?></strong>: <?php echo $this->msg; ?>
                </p></div>

            <?php
        }

    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields()
    {

        $form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'woo-vendy'),
                'label' => __('Enable Vendy', 'woo-vendy'),
                'type' => 'checkbox',
                'description' => __('Enable Vendy as a payment option on the checkout page.', 'woo-vendy'),
                'default' => 'no',
                'desc_tip' => true,
            ),
            'title' => array(
                'title' => __('Title', 'woo-vendy'),
                'type' => 'text',
                'description' => __('This controls the payment method title which the user sees during checkout.', 'woo-vendy'),
                'default' => __('Pay with Vendy(Debit/Credit Cards/Pay with Bank Transfer)', 'woo-vendy'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'woo-vendy'),
                'type' => 'textarea',
                'description' => __('This controls the payment method description which the user sees during checkout.', 'woo-vendy'),
                'default' => __('Make payment using your debit and credit cards', 'woo-vendy'),
                'desc_tip' => true,
            ),
            'testmode' => array(
                'title' => __('Test mode', 'woo-vendy'),
                'label' => __('Enable Test Mode', 'woo-vendy'),
                'type' => 'checkbox',
                'description' => __('Test mode enables you to test payments before going live. <br />Once the LIVE MODE is enabled on your Vendy account uncheck this.', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'payment_page' => array(
                'title' => __('Payment Option', 'woo-vendy'),
                'type' => 'select',
                'description' => __('Popup shows the payment popup on the page while Redirect will redirect the customer to Vendy to make payment.', 'woo-vendy'),
                'default' => 'inline',
                'desc_tip' => false,
                'options' => array(
                    '' => __('Select One', 'woo-vendy'),
                    'inline' => __('Popup', 'woo-vendy'),
//					'redirect'  => __( 'Redirect', 'woo-vendy' ),
                ),
            ),
            'test_secret_key' => array(
                'title' => __('Test Secret Key', 'woo-vendy'),
                'type' => 'password',
                'description' => __('Enter your Test Secret Key here', 'woo-vendy'),
                'default' => '',
            ),
            'test_public_key' => array(
                'title' => __('Test Public Key', 'woo-vendy'),
                'type' => 'text',
                'description' => __('Enter your Test Public Key here.', 'woo-vendy'),
                'default' => '',
            ),
            'live_secret_key' => array(
                'title' => __('Live Secret Key', 'woo-vendy'),
                'type' => 'password',
                'description' => __('Enter your Live Secret Key here.', 'woo-vendy'),
                'default' => '',
            ),
            'live_public_key' => array(
                'title' => __('Live Public Key', 'woo-vendy'),
                'type' => 'text',
                'description' => __('Enter your Live Public Key here.', 'woo-vendy'),
                'default' => '',
            ),
            'autocomplete_order' => array(
                'title' => __('Autocomplete Order After Payment', 'woo-vendy'),
                'label' => __('Autocomplete Order', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-autocomplete-order',
                'description' => __('If enabled, the order will be marked as complete after successful payment', 'woo-vendy'),
                'default' => 'no',
                'desc_tip' => true,
            ),
            'remove_cancel_order_button' => array(
                'title' => __('Remove Cancel Order & Restore Cart Button', 'woo-vendy'),
                'label' => __('Remove the cancel order & restore cart button on the pay for order page', 'woo-vendy'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no',
            ),
//			'split_payment'                    => array(
//				'title'       => __( 'Split Payment', 'woo-vendy' ),
//				'label'       => __( 'Enable Split Payment', 'woo-vendy' ),
//				'type'        => 'checkbox',
//				'description' => '',
//				'class'       => 'woocommerce_vendy_split_payment',
//				'default'     => 'no',
//				'desc_tip'    => true,
//			),
//			'subaccount_code'                  => array(
//				'title'       => __( 'Subaccount Code', 'woo-vendy' ),
//				'type'        => 'text',
//				'description' => __( 'Enter the subaccount code here.', 'woo-vendy' ),
//				'class'       => 'woocommerce_vendy_subaccount_code',
//				'default'     => '',
//			),
//			'split_payment_transaction_charge' => array(
//				'title'             => __( 'Split Payment Transaction Charge', 'woo-vendy' ),
//				'type'              => 'number',
//				'description'       => __( 'A flat fee to charge the subaccount for this transaction, in Naira (&#8358;). This overrides the split percentage set when the subaccount was created. Ideally, you will need to use this if you are splitting in flat rates (since subaccount creation only allows for percentage split). e.g. 100 for a &#8358;100 flat fee.', 'woo-vendy' ),
//				'class'             => __( 'woocommerce_vendy_split_payment_transaction_charge', 'woo-vendy' ),
//				'default'           => '',
//				'custom_attributes' => array(
//					'min'  => 1,
//					'step' => 0.1,
//				),
//				'desc_tip'          => false,
//			),
//			'split_payment_charge_account'     => array(
//				'title'       => __( 'Vendy Charges Bearer', 'woo-vendy' ),
//				'type'        => 'select',
//				'description' => __( 'Who bears Vendy charges?', 'woo-vendy' ),
//				'class'       => 'woocommerce_vendy_split_payment_charge_account',
//				'default'     => '',
//				'desc_tip'    => false,
//				'options'     => array(
//					''           => __( 'Select One', 'woo-vendy' ),
//					'account'    => __( 'Account', 'woo-vendy' ),
//					'subaccount' => __( 'Subaccount', 'woo-vendy' ),
//				),
//			),
            'custom_gateways' => array(
                'title' => __('Additional Vendy Gateways', 'woo-vendy'),
                'type' => 'select',
                'description' => __('Create additional custom Vendy based gateways. This allows you to create additional Vendy gateways using custom filters. You can create a gateway that accepts only verve cards, a gateway that accepts only bank payment, a gateway that accepts a specific bank issued cards.', 'woo-vendy'),
                'default' => '',
                'desc_tip' => true,
                'options' => array(
                    '' => __('Select One', 'woo-vendy'),
                    '1' => __('1 gateway', 'woo-vendy'),
                    '2' => __('2 gateways', 'woo-vendy'),
//					'3' => __( '3 gateways', 'woo-vendy' ),
//					'4' => __( '4 gateways', 'woo-vendy' ),
//					'5' => __( '5 gateways', 'woo-vendy' ),
                ),
            ),
//			'saved_cards'                      => array(
//				'title'       => __( 'Saved Cards', 'woo-vendy' ),
//				'label'       => __( 'Enable Payment via Saved Cards', 'woo-vendy' ),
//				'type'        => 'checkbox',
//				'description' => __( 'If enabled, users will be able to pay with a saved card during checkout. Card details are saved on Vendy servers, not on your store.<br>Note that you need to have a valid SSL certificate installed.', 'woo-vendy' ),
//				'default'     => 'no',
//				'desc_tip'    => true,
//			),
            'custom_metadata' => array(
                'title' => __('Custom Metadata', 'woo-vendy'),
                'label' => __('Enable Custom Metadata', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-metadata',
                'description' => __('If enabled, you will be able to send more information about the order to Vendy.', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_order_id' => array(
                'title' => __('Order ID', 'woo-vendy'),
                'label' => __('Send Order ID', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-order-id',
                'description' => __('If checked, the Order ID will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_name' => array(
                'title' => __('Customer Name', 'woo-vendy'),
                'label' => __('Send Customer Name', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-name',
                'description' => __('If checked, the customer full name will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_email' => array(
                'title' => __('Customer Email', 'woo-vendy'),
                'label' => __('Send Customer Email', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-email',
                'description' => __('If checked, the customer email address will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_phone' => array(
                'title' => __('Customer Phone', 'woo-vendy'),
                'label' => __('Send Customer Phone', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-phone',
                'description' => __('If checked, the customer phone will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_billing_address' => array(
                'title' => __('Order Billing Address', 'woo-vendy'),
                'label' => __('Send Order Billing Address', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-billing-address',
                'description' => __('If checked, the order billing address will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_shipping_address' => array(
                'title' => __('Order Shipping Address', 'woo-vendy'),
                'label' => __('Send Order Shipping Address', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-shipping-address',
                'description' => __('If checked, the order shipping address will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'meta_products' => array(
                'title' => __('Product(s) Purchased', 'woo-vendy'),
                'label' => __('Send Product(s) Purchased', 'woo-vendy'),
                'type' => 'checkbox',
                'class' => 'wc-vendy-meta-products',
                'description' => __('If checked, the product(s) purchased will be sent to Vendy', 'woo-vendy'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
        );

        if ('NGN' !== get_woocommerce_currency()) {
            unset($form_fields['custom_gateways']);
        }

        $this->form_fields = $form_fields;

    }

    /**
     * Payment form on checkout page
     */
    public function payment_fields()
    {

        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }

        if (!is_ssl()) {
            return;
        }

        if ($this->supports('tokenization') && is_checkout() && $this->saved_cards && is_user_logged_in()) {
            $this->tokenization_script();
            $this->saved_payment_methods();
            $this->save_payment_method_checkbox();
        }

    }

    /**
     * Outputs scripts used for vendy payment.
     */
    public function payment_scripts()
    {

        if (isset($_GET['pay_for_order']) || !is_checkout_pay_page()) {
            return;
        }

        if ($this->enabled === 'no') {
            return;
        }

        $order_key = urldecode($_GET['key']);
        $order_id = absint(get_query_var('order-pay'));

        $order = wc_get_order($order_id);

        if ($this->id !== $order->get_payment_method()) {
            return;
        }

        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        wp_enqueue_script('jquery');

        wp_enqueue_script('vendy', 'https://collections.myvendy.com/v1/inline.js', array('jquery'), WC_VENDY_VERSION, false);

        wp_enqueue_script('wc_vendy', plugins_url('assets/js/vendy' . $suffix . '.js', WC_VENDY_MAIN_FILE), array('jquery', 'vendy'), WC_VENDY_VERSION, false);

        $vendy_params = array(
            'key' => $this->public_key,
        );

        if (is_checkout_pay_page() && get_query_var('order-pay')) {

            $email = $order->get_billing_email();
            $amount = $order->get_total();
            $txnref = $order_id . '_' . time();
            $the_order_id = $order->get_id();
            $the_order_key = $order->get_order_key();
            $currency = $order->get_currency();

            if ($the_order_id == $order_id && $the_order_key == $order_key) {

                $vendy_params['email'] = $email;
                $vendy_params['amount'] = absint($amount);
                $vendy_params['txnref'] = $txnref;
                $vendy_params['currency'] = $currency;

            }

            if ($this->split_payment) {

                $vendy_params['subaccount_code'] = $this->subaccount_code;
                $vendy_params['charges_account'] = $this->charges_account;

                if (empty($this->transaction_charges)) {
                    $vendy_params['transaction_charges'] = '';
                } else {
                    $vendy_params['transaction_charges'] = $this->transaction_charges * 100;
                }
            }

            if ($this->custom_metadata) {

                if ($this->meta_order_id) {

                    $vendy_params['meta_order_id'] = $order_id;

                }

                if ($this->meta_name) {

                    $vendy_params['meta_name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

                }

                if ($this->meta_email) {

                    $vendy_params['meta_email'] = $email;

                }

                if ($this->meta_phone) {

                    $vendy_params['meta_phone'] = $order->get_billing_phone();

                }

                if ($this->meta_products) {

                    $line_items = $order->get_items();

                    $products = '';

                    foreach ($line_items as $item_id => $item) {
                        $name = $item['name'];
                        $quantity = $item['qty'];
                        $products .= $name . ' (Qty: ' . $quantity . ')';
                        $products .= ' | ';
                    }

                    $products = rtrim($products, ' | ');

                    $vendy_params['meta_products'] = $products;

                }

                if ($this->meta_billing_address) {

                    $billing_address = $order->get_formatted_billing_address();
                    $billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

                    $vendy_params['meta_billing_address'] = $billing_address;

                }

                if ($this->meta_shipping_address) {

                    $shipping_address = $order->get_formatted_shipping_address();
                    $shipping_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $shipping_address));

                    if (empty($shipping_address)) {

                        $billing_address = $order->get_formatted_billing_address();
                        $billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

                        $shipping_address = $billing_address;

                    }

                    $vendy_params['meta_shipping_address'] = $shipping_address;

                }
            }

            $order->update_meta_data('_vendy_txn_ref', $txnref);
            $order->save();
        }

        wp_localize_script('wc_vendy', 'wc_vendy_params', $vendy_params);
    }

    /**
     * Load admin scripts.
     */
    public function admin_scripts()
    {

        if ('woocommerce_page_wc-settings' !== get_current_screen()->id) {
            return;
        }

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        $vendy_admin_params = array(
            'plugin_url' => WC_VENDY_URL,
        );

        wp_enqueue_script('wc_vendy_admin', plugins_url('assets/js/vendy-admin' . $suffix . '.js', WC_VENDY_MAIN_FILE), array(), WC_VENDY_VERSION, true);

        wp_localize_script('wc_vendy_admin', 'wc_vendy_admin_params', $vendy_admin_params);

    }

    /**
     * Process the payment.
     *
     * @param int $order_id
     *
     * @return array|void
     */
    public function process_payment($order_id)
    {

        if ('redirect' === $this->payment_page) {

            return $this->process_redirect_payment_option($order_id);

        } elseif (isset($_POST['wc-' . $this->id . '-payment-token']) && 'new' !== $_POST['wc-' . $this->id . '-payment-token']) {

            $token_id = wc_clean($_POST['wc-' . $this->id . '-payment-token']);
            $token = \WC_Payment_Tokens::get($token_id);

            if ($token->get_user_id() !== get_current_user_id()) {

                wc_add_notice('Invalid token ID', 'error');

                return;

            } else {

                $status = $this->process_token_payment($token->get_token(), $order_id);

                if ($status) {

                    $order = wc_get_order($order_id);

                    return array(
                        'result' => 'success',
                        'redirect' => $this->get_return_url($order),
                    );

                }
            }
        } else {

            $order = wc_get_order($order_id);

            if (is_user_logged_in() && isset($_POST['wc-' . $this->id . '-new-payment-method']) && true === (bool)$_POST['wc-' . $this->id . '-new-payment-method'] && $this->saved_cards) {

                $order->update_meta_data('_wc_vendy_save_card', true);

                $order->save();

            }

            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true),
            );

        }

    }

    /**
     * Process a redirect payment option payment.
     *
     * @param int $order_id
     * @return array|void
     * @since 5.7
     */
    public function process_redirect_payment_option($order_id)
    {
        //todo: We don't currently support this

        $order = wc_get_order($order_id);
        $amount = $order->get_total() * 100;
        $txnref = $order_id . '_' . time();
        $callback_url = WC()->api_request_url('WC_Gateway_Vendy');

        $payment_channels = $this->get_gateway_payment_channels($order);

        $vendy_params = array(
            'amount' => absint($amount),
            'email' => $order->get_billing_email(),
            'currency' => $order->get_currency(),
            'reference' => $txnref,
            'callback_url' => $callback_url,
        );

        if (!empty($payment_channels)) {
            $vendy_params['channels'] = $payment_channels;
        }

        if ($this->split_payment) {

            $vendy_params['subaccount'] = $this->subaccount_code;
            $vendy_params['bearer'] = $this->charges_account;

            if (empty($this->transaction_charges)) {
                $vendy_params['transaction_charge'] = '';
            } else {
                $vendy_params['transaction_charge'] = $this->transaction_charges * 100;
            }
        }

        $vendy_params['metadata']['custom_fields'] = $this->get_custom_fields($order_id);
        $vendy_params['metadata']['cancel_action'] = wc_get_cart_url();

        $order->update_meta_data('_vendy_txn_ref', $txnref);
        $order->save();

        $vendy_url = $this->get_vendy_domain().'/transactions/initiate';

        $token = $this->get_vendy_auth_token();
        if ($token instanceof WP_Error) {
            return $token;
        }

        $headers = array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        );

        $args = array(
            'headers' => $headers,
            'timeout' => 60,
            'body' => json_encode($vendy_params),
        );

        $request = wp_remote_post($vendy_url, $args);

        if (!is_wp_error($request) && 200 === wp_remote_retrieve_response_code($request)) {

            $vendy_response = json_decode(wp_remote_retrieve_body($request));

            return array(
                'result' => 'success',
                'redirect' => $vendy_response->data->authorization_url,
            );

        } else {
            wc_add_notice(__('Unable to process payment try again', 'woo-vendy'), 'error');

            return;
        }

    }

    /**
     * Process a token payment.
     *
     * @param $token
     * @param $order_id
     *
     * @return bool
     */
    public function process_token_payment($token, $order_id)
    {

        if ($token && $order_id) {

            $order = wc_get_order($order_id);

            $order_amount = $order->get_total() * 100;
            $txnref = $order_id . '_' . time();

            $order->update_meta_data('_vendy_txn_ref', $txnref);
            $order->save();

            $auth_token = $this->get_vendy_auth_token();
            if ($token instanceof WP_Error) {
                return false;
            }

            $vendy_url = $this->get_vendy_domain().'/transaction/charge_authorization';

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $auth_token,
            );

            $metadata['custom_fields'] = $this->get_custom_fields($order_id);

            if (strpos($token, '###') !== false) {
                $payment_token = explode('###', $token);
                $auth_code = $payment_token[0];
                $customer_email = $payment_token[1];
            } else {
                $auth_code = $token;
                $customer_email = $order->get_billing_email();
            }

            $body = array(
                'email' => $customer_email,
                'amount' => absint($order_amount),
                'metadata' => $metadata,
                'authorization_code' => $auth_code,
                'reference' => $txnref,
                'currency' => $order->get_currency(),
            );

            $args = array(
                'body' => json_encode($body),
                'headers' => $headers,
                'timeout' => 60,
            );

            $request = wp_remote_post($vendy_url, $args);

            $response_code = wp_remote_retrieve_response_code($request);

            if (!is_wp_error($request) && in_array($response_code, array(200, 400), true)) {

                $vendy_response = json_decode(wp_remote_retrieve_body($request));

                if ((200 === $response_code) && ('success' === strtolower($vendy_response->data->status))) {

                    $order = wc_get_order($order_id);

                    if (in_array($order->get_status(), array('processing', 'completed', 'on-hold'))) {

                        wp_redirect($this->get_return_url($order));

                        exit;

                    }

                    $order_total = $order->get_total();
                    $order_currency = $order->get_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($order_currency);
                    $amount_paid = $vendy_response->data->amount / 100;
                    $vendy_ref = $vendy_response->data->reference;
                    $payment_currency = $vendy_response->data->currency;
                    $gateway_symbol = get_woocommerce_currency_symbol($payment_currency);

                    // check if the amount paid is equal to the order amount.
                    if ($amount_paid < absint($order_total)) {

                        $order->update_status('on-hold', '');

                        $order->add_meta_data('_transaction_id', $vendy_ref, true);

                        $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
                        $notice_type = 'notice';

                        // Add Customer Order Note
                        $order->add_order_note($notice, 1);

                        // Add Admin Order Note
                        $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $vendy_ref);
                        $order->add_order_note($admin_order_note);

                        wc_add_notice($notice, $notice_type);

                    } else {

                        if ($payment_currency !== $order_currency) {

                            $order->update_status('on-hold', '');

                            $order->update_meta_data('_transaction_id', $vendy_ref);

                            $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
                            $notice_type = 'notice';

                            // Add Customer Order Note
                            $order->add_order_note($notice, 1);

                            // Add Admin Order Note
                            $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $vendy_ref);
                            $order->add_order_note($admin_order_note);

                            function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

                            wc_add_notice($notice, $notice_type);

                        } else {

                            $order->payment_complete($vendy_ref);

                            $order->add_order_note(sprintf('Payment via Vendy successful (Transaction Reference: %s)', $vendy_ref));

                            if ($this->is_autocomplete_order_enabled($order)) {
                                $order->update_status('completed');
                            }
                        }
                    }

                    $order->save();

                    $this->save_subscription_payment_token($order_id, $vendy_response);

                    WC()->cart->empty_cart();

                    return true;

                } else {

                    $order_notice = __('Payment was declined by Vendy.', 'woo-vendy');
                    $failed_notice = __('Payment failed using the saved card. Kindly use another payment option.', 'woo-vendy');

                    if (!empty($vendy_response->msg)) {

                        $order_notice = sprintf(__('Payment was declined by Vendy. Reason: %s.', 'woo-vendy'), $vendy_response->msg);
                        $failed_notice = sprintf(__('Payment failed using the saved card. Reason: %s. Kindly use another payment option.', 'woo-vendy'), $vendy_response->msg);

                    }

                    $order->update_status('failed', $order_notice);

                    wc_add_notice($failed_notice, 'error');

                    do_action('wc_gateway_vendy_process_payment_error', $failed_notice, $order);

                    return false;
                }
            }
        } else {

            wc_add_notice(__('Payment Failed.', 'woo-vendy'), 'error');

        }

    }

    /**
     * Show new card can only be added when placing an order notice.
     */
    public function add_payment_method()
    {

        wc_add_notice(__('You can only add a new card when placing an order.', 'woo-vendy'), 'error');

        return;

    }

    /**
     * Displays the payment page.
     *
     * @param $order_id
     */
    public function receipt_page($order_id)
    {

        $order = wc_get_order($order_id);

        echo '<div id="wc-vendy-form">';

        echo '<p>' . __('Thank you for your order, please click the button below to pay with Vendy.', 'woo-vendy') . '</p>';

        echo '<div id="vendy_form"><form id="order_review" method="post" action="' . WC()->api_request_url('WC_Gateway_Vendy') . '"></form><button class="button" id="vendy-payment-button">' . __('Pay Now', 'woo-vendy') . '</button>';

        if (!$this->remove_cancel_order_button) {
            echo '  <a class="button cancel" id="vendy-cancel-payment-button" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woo-vendy') . '</a></div>';
        }

        echo '</div>';

    }

    /**
     * Verify Vendy payment.
     */
    public function verify_vendy_transaction()
    {

        if (isset($_REQUEST['vendy_txnref'])) {
            $vendy_txn_ref = sanitize_text_field($_REQUEST['vendy_txnref']);
        } elseif (isset($_REQUEST['reference'])) {
            $vendy_txn_ref = sanitize_text_field($_REQUEST['reference']);
        } else {
            $vendy_txn_ref = false;
        }

        @ob_clean();

        if ($vendy_txn_ref) {

            $vendy_response = $this->get_vendy_transaction($vendy_txn_ref);

            if (false !== $vendy_response) {
                if (0 == $vendy_response->data->failed && 1 == $vendy_response->data->debited) {

                    $order_details = explode('_', $vendy_response->data->meta->reference);
                    $order_id = (int)$order_details[0];
                    $order = wc_get_order($order_id);
                    if (in_array($order->get_status(), array('processing', 'completed', 'on-hold'))) {
                        wp_redirect($this->get_return_url($order));
                        exit;

                    }

                    $order_total = $order->get_total();
                    $order_currency = $order->get_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($order_currency);
                    $amount_paid = $vendy_response->data->amount;
                    $vendy_ref = $vendy_response->data->refid;
                    $payment_currency = strtoupper($vendy_response->data->currency);
                    $gateway_symbol = get_woocommerce_currency_symbol($payment_currency);

                    // check if the amount paid is equal to the order amount.
                    if ($amount_paid < absint($order_total)) {

                        $order->update_status('on-hold', '');

                        $order->add_meta_data('_transaction_id', $vendy_ref, true);

                        $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
                        $notice_type = 'notice';

                        // Add Customer Order Note
                        $order->add_order_note($notice, 1);

                        // Add Admin Order Note
                        $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $vendy_ref);
                        $order->add_order_note($admin_order_note);

                        function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

                        wc_add_notice($notice, $notice_type);

                    } else {

                        if ($payment_currency !== $order_currency) {

                            $order->update_status('on-hold', '');

                            $order->update_meta_data('_transaction_id', $vendy_ref);

                            $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
                            $notice_type = 'notice';

                            // Add Customer Order Note
                            $order->add_order_note($notice, 1);

                            // Add Admin Order Note
                            $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $vendy_ref);
                            $order->add_order_note($admin_order_note);

                            function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

                            wc_add_notice($notice, $notice_type);

                        } else {

                            $order->payment_complete($vendy_ref);
                            $order->add_order_note(sprintf(__('Payment via Vendy successful (Transaction Reference: %s)', 'woo-vendy'), $vendy_ref));

                            if ($this->is_autocomplete_order_enabled($order)) {
                                $order->update_status('completed');
                            }
                        }
                    }

                    $order->save();

                    $this->save_card_details($vendy_response, $order->get_user_id(), $order_id);

                    WC()->cart->empty_cart();
                } else if (-1 == $vendy_response->data->failed) {
	                $order_details = explode('_', $vendy_response->data->meta->reference);
	                $order_id = (int)$order_details[0];
	                $order = wc_get_order($order_id);
                    $order->update_status('pending', __('Payment is still pending on Vendy.', 'woo-vendy'));
                } else {
	                $order_details = explode('_', $vendy_response->data->meta->reference);
	                $order_id = (int)$order_details[0];
	                $order = wc_get_order($order_id);
                    $order->update_status('failed', __('Payment was declined by Vendy.', 'woo-vendy'));
                }

	            wp_redirect($this->get_return_url($order));
                exit;
            }

	        wp_redirect(wc_get_page_permalink('cart'));
            exit;
        }
        wp_redirect(wc_get_page_permalink('cart'));

        exit;

    }

    /**
     * Process Webhook.
     */
    public function process_webhooks()
    {

        if (!array_key_exists('HTTP_X_VENDY_SIGNATURE', $_SERVER) || (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')) {
            exit;
        }

        $json = file_get_contents('php://input');

        // validate event do all at once to avoid timing attack.
        if ($_SERVER['HTTP_X_SIGNATURE'] !== hash_hmac('sha256', $json, $this->secret_key)) {
            exit;
        }

        $event = json_decode($json);
        $event_type = $event["event_type"];

        if ('transaction_success' !== strtolower($event_type)) {
            return;
        }

        sleep(10);

        $vendy_response = $this->get_vendy_transaction($event->data->meta->reference);

        if (false === $vendy_response) {
            return;
        }

        $order_details = explode('_', $vendy_response->data->meta->reference);

        $order_id = (int)$order_details[0];

        $order = wc_get_order($order_id);

        if (!$order) {
            return;
        }

        $vendy_txn_ref = $order->get_meta('_vendy_txn_ref');

        if ($vendy_response->data->refid != $vendy_txn_ref) {
            exit;
        }

        http_response_code(200);

        if (in_array(strtolower($order->get_status()), array('processing', 'completed', 'on-hold'), true)) {
            exit;
        }

        $order_currency = $order->get_currency();

        $currency_symbol = get_woocommerce_currency_symbol($order_currency);

        $order_total = $order->get_total();

        $amount_paid = $vendy_response->data->amount;

        $vendy_ref = $vendy_response->data->refid;

        $payment_currency = strtoupper($vendy_response->data->currency);

        $gateway_symbol = get_woocommerce_currency_symbol($payment_currency);

        // check if the amount paid is equal to the order amount.
        if ($amount_paid < absint($order_total)) {

            $order->update_status('on-hold', '');

            $order->add_meta_data('_transaction_id', $vendy_ref, true);

            $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
            $notice_type = 'notice';

            // Add Customer Order Note.
            $order->add_order_note($notice, 1);

            // Add Admin Order Note.
            $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $vendy_ref);
            $order->add_order_note($admin_order_note);

            function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

            wc_add_notice($notice, $notice_type);

            WC()->cart->empty_cart();

        } else {

            if ($payment_currency !== $order_currency) {

                $order->update_status('on-hold', '');

                $order->update_meta_data('_transaction_id', $vendy_ref);

                $notice = sprintf(__('Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-vendy'), '<br />', '<br />', '<br />');
                $notice_type = 'notice';

                // Add Customer Order Note.
                $order->add_order_note($notice, 1);

                // Add Admin Order Note.
                $admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Vendy Transaction Reference:</strong> %9$s', 'woo-vendy'), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $vendy_ref);
                $order->add_order_note($admin_order_note);

                function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

                wc_add_notice($notice, $notice_type);

            } else {

                $order->payment_complete($vendy_ref);

                $order->add_order_note(sprintf(__('Payment via Vendy successful (Transaction Reference: %s)', 'woo-vendy'), $vendy_ref));

                WC()->cart->empty_cart();

                if ($this->is_autocomplete_order_enabled($order)) {
                    $order->update_status('completed');
                }
            }
        }

        $order->save();

        $this->save_card_details($vendy_response, $order->get_user_id(), $order_id);

        exit;
    }

    /**
     * Save Customer Card Details.
     *
     * @param $vendy_response
     * @param $user_id
     * @param $order_id
     */
    public function save_card_details($vendy_response, $user_id, $order_id)
    {

        $this->save_subscription_payment_token($order_id, $vendy_response);

        $order = wc_get_order($order_id);

        $save_card = $order->get_meta('_wc_vendy_save_card');

        if ($user_id && $this->saved_cards && $save_card && $vendy_response->data->authorization->reusable && 'card' == $vendy_response->data->authorization->channel) {

            $gateway_id = $order->get_payment_method();

            $last4 = $vendy_response->data->authorization->last4;
            $exp_year = $vendy_response->data->authorization->exp_year;
            $brand = $vendy_response->data->authorization->card_type;
            $exp_month = $vendy_response->data->authorization->exp_month;
            $auth_code = $vendy_response->data->authorization->authorization_code;
            $customer_email = $vendy_response->data->customer->email;

            $payment_token = "$auth_code###$customer_email";

            $token = new WC_Payment_Token_CC();
            $token->set_token($payment_token);
            $token->set_gateway_id($gateway_id);
            $token->set_card_type(strtolower($brand));
            $token->set_last4($last4);
            $token->set_expiry_month($exp_month);
            $token->set_expiry_year($exp_year);
            $token->set_user_id($user_id);
            $token->save();

            $order->delete_meta_data('_wc_vendy_save_card');
            $order->save();
        }
    }

    /**
     * Save payment token to the order for automatic renewal for further subscription payment.
     *
     * @param $order_id
     * @param $vendy_response
     */
    public function save_subscription_payment_token($order_id, $vendy_response)
    {

        if (!function_exists('wcs_order_contains_subscription')) {
            return;
        }

        if ($this->order_contains_subscription($order_id) && $vendy_response->data->authorization->reusable && 'card' == $vendy_response->data->authorization->channel) {

            $auth_code = $vendy_response->data->authorization->authorization_code;
            $customer_email = $vendy_response->data->customer->email;

            $payment_token = "$auth_code###$customer_email";

            // Also store it on the subscriptions being purchased or paid for in the order
            if (function_exists('wcs_order_contains_subscription') && wcs_order_contains_subscription($order_id)) {

                $subscriptions = wcs_get_subscriptions_for_order($order_id);

            } elseif (function_exists('wcs_order_contains_renewal') && wcs_order_contains_renewal($order_id)) {

                $subscriptions = wcs_get_subscriptions_for_renewal_order($order_id);

            } else {

                $subscriptions = array();

            }

            if (empty($subscriptions)) {
                return;
            }

            foreach ($subscriptions as $subscription) {
                $subscription->update_meta_data('_vendy_token', $payment_token);
                $subscription->save();
            }
        }

    }

    /**
     * Get custom fields to pass to Vendy.
     *
     * @param int $order_id WC Order ID
     *
     * @return array
     */
    public function get_custom_fields($order_id)
    {

        $order = wc_get_order($order_id);

        $custom_fields = array();

        $custom_fields[] = array(
            'display_name' => 'Plugin',
            'variable_name' => 'plugin',
            'value' => 'woo-vendy',
        );

        if ($this->custom_metadata) {

            if ($this->meta_order_id) {

                $custom_fields[] = array(
                    'display_name' => 'Order ID',
                    'variable_name' => 'order_id',
                    'value' => $order_id,
                );

            }

            if ($this->meta_name) {

                $custom_fields[] = array(
                    'display_name' => 'Customer Name',
                    'variable_name' => 'customer_name',
                    'value' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                );

            }

            if ($this->meta_email) {

                $custom_fields[] = array(
                    'display_name' => 'Customer Email',
                    'variable_name' => 'customer_email',
                    'value' => $order->get_billing_email(),
                );

            }

            if ($this->meta_phone) {

                $custom_fields[] = array(
                    'display_name' => 'Customer Phone',
                    'variable_name' => 'customer_phone',
                    'value' => $order->get_billing_phone(),
                );

            }

            if ($this->meta_products) {

                $line_items = $order->get_items();

                $products = '';

                foreach ($line_items as $item_id => $item) {
                    $name = $item['name'];
                    $quantity = $item['qty'];
                    $products .= $name . ' (Qty: ' . $quantity . ')';
                    $products .= ' | ';
                }

                $products = rtrim($products, ' | ');

                $custom_fields[] = array(
                    'display_name' => 'Products',
                    'variable_name' => 'products',
                    'value' => $products,
                );

            }

            if ($this->meta_billing_address) {

                $billing_address = $order->get_formatted_billing_address();
                $billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

                $vendy_params['meta_billing_address'] = $billing_address;

                $custom_fields[] = array(
                    'display_name' => 'Billing Address',
                    'variable_name' => 'billing_address',
                    'value' => $billing_address,
                );

            }

            if ($this->meta_shipping_address) {

                $shipping_address = $order->get_formatted_shipping_address();
                $shipping_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $shipping_address));

                if (empty($shipping_address)) {

                    $billing_address = $order->get_formatted_billing_address();
                    $billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

                    $shipping_address = $billing_address;

                }
                $custom_fields[] = array(
                    'display_name' => 'Shipping Address',
                    'variable_name' => 'shipping_address',
                    'value' => $shipping_address,
                );

            }

        }

        return $custom_fields;
    }

    /**
     * Process a refund request from the Order details screen.
     *
     * @param int $order_id WC Order ID.
     * @param float|null $amount Refund Amount.
     * @param string $reason Refund Reason
     *
     * @return bool|WP_Error
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {

        if (!($this->public_key && $this->secret_key)) {
            return false;
        }

        $order = wc_get_order($order_id);

        if (!$order) {
            return false;
        }

        $order_currency = $order->get_currency();
        $transaction_id = $order->get_transaction_id();

        $vendy_response = $this->get_vendy_transaction($transaction_id);

        if (false !== $vendy_response) {

            if (0 == $vendy_response->data->failed && 1 == $vendy_response->data->debited) {

                $merchant_note = sprintf(__('Refund for Order ID: #%1$s on %2$s', 'woo-vendy'), $order_id, get_site_url());



                $token = $this->get_vendy_auth_token();
                if ($token instanceof WP_Error) {
                    return false;
                }
                $body = array(
                    'tranRef' => $transaction_id,
                    'customer_note' => $reason,
                    'merchant_note' => $merchant_note,
                );

                $headers = array(
                    'Authorization' => 'Bearer ' . $token,
                );

                $args = array(
                    'headers' => $headers,
                    'timeout' => 60,
                    'body' => $body,
                );

                $refund_url = $this->get_vendy_domain().'/reversals/create';

                $refund_request = wp_remote_post($refund_url, $args);

                if (!is_wp_error($refund_request) && 200 === wp_remote_retrieve_response_code($refund_request)) {

                    $refund_response = json_decode(wp_remote_retrieve_body($refund_request));

                    if ($refund_response->data->status) {
                        $amount = wc_price($amount, array('currency' => $order_currency));
                        $refund_id = $refund_response->data->refid;
                        $refund_message = sprintf(__('Refunded %1$s. Refund ID: %2$s. Reason: %3$s', 'woo-vendy'), $amount, $refund_id, $reason);
                        $order->add_order_note($refund_message);

                        return true;
                    }
                } else {

                    $refund_response = json_decode(wp_remote_retrieve_body($refund_request));

                    if (isset($refund_response->msg)) {
                        return new WP_Error('error', $refund_response->msg);
                    } else {
                        return new WP_Error('error', __('Can&#39;t process refund at the moment. Try again later.', 'woo-vendy'));
                    }
                }
            }
        }
    }

    /**
     * Checks if WC version is less than passed in version.
     *
     * @param string $version Version to check against.
     *
     * @return bool
     */
    public function is_wc_lt($version)
    {
        return version_compare(WC_VERSION, $version, '<');
    }

    /**
     * Checks if autocomplete order is enabled for the payment method.
     *
     * @param WC_Order $order Order object.
     * @return bool
     * @since 5.7
     */
    protected function is_autocomplete_order_enabled($order)
    {
        $autocomplete_order = false;

        $payment_method = $order->get_payment_method();

        $vendy_settings = get_option('woocommerce_' . $payment_method . '_settings');

        if (isset($vendy_settings['autocomplete_order']) && 'yes' === $vendy_settings['autocomplete_order']) {
            $autocomplete_order = true;
        }

        return $autocomplete_order;
    }

    /**
     * Retrieve the payment channels configured for the gateway
     *
     * @param WC_Order $order Order object.
     * @return array
     * @since 5.7
     */
    protected function get_gateway_payment_channels($order)
    {

        $payment_method = $order->get_payment_method();

        if ('vendy' === $payment_method) {
            return array();
        }

        $payment_channels = $this->payment_channels;

        if (empty($payment_channels)) {
            $payment_channels = array('card');
        }

        return $payment_channels;
    }

    protected function get_vendy_domain()
    {
        return $this->testmode ? "https://api.staging.vendy.money" : "https://api.vendy.money";
    }

    protected function get_vendy_auth_token()
    {

        $vendy_url = $this->get_vendy_domain() . '/auth';
        $headers = array(
            'AccessKey' => $this->public_key,
            'SecretKey' => $this->secret_key,
        );

        $args = array(
            'headers' => $headers,
            'timeout' => 60,
        );

        $request = wp_remote_post($vendy_url, $args);

        if (!is_wp_error($request) && 200 === wp_remote_retrieve_response_code($request)) {
            $json =  json_decode(wp_remote_retrieve_body($request));

            return $json->token;
        } else {
            return new WP_Error('error', 'AccessKey or SecretKey might be incorrect');
        }
    }

    /**
     * Retrieve a transaction from Vendy.
     *
     * @param $vendy_txn_ref
     * @return false|mixed
     * @since 5.7.5
     */
    private function get_vendy_transaction($vendy_txn_ref)
    {

        $token = $this->get_vendy_auth_token();

        if ($token instanceof WP_Error) {
            return false;
        }

        $vendy_url = $this->get_vendy_domain() . '/transactions/' . $vendy_txn_ref;

        $headers = array(
            'Authorization' => 'Bearer ' . $token,
        );

        $args = array(
            'headers' => $headers,
            'timeout' => 60,
        );

        $request = wp_remote_get($vendy_url, $args);

        if (!is_wp_error($request) && 200 === wp_remote_retrieve_response_code($request)) {
            return json_decode(wp_remote_retrieve_body($request));
        }

        return false;
    }

    /**
     * Get Vendy payment icon URL.
     */
    public function get_logo_url()
    {

        $base_location = wc_get_base_location();

        if ('GH' === $base_location['country']) {
            $url = WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-gh.png', WC_VENDY_MAIN_FILE));
        } elseif ('ZA' === $base_location['country']) {
            $url = WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-za.png', WC_VENDY_MAIN_FILE));
        } elseif ('KE' === $base_location['country']) {
            $url = WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-ke.png', WC_VENDY_MAIN_FILE));
        } elseif ('CI' === $base_location['country']) {
            $url = WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-civ.png', WC_VENDY_MAIN_FILE));
        } else {
            $url = WC_HTTPS::force_https_url(plugins_url('assets/images/vendy-wc.png', WC_VENDY_MAIN_FILE));
        }

        return apply_filters('wc_vendy_gateway_icon_url', $url, $this->id);
    }

    /**
     * Check if an order contains a subscription.
     *
     * @param int $order_id WC Order ID.
     *
     * @return bool
     */
    public function order_contains_subscription($order_id)
    {

        return function_exists('wcs_order_contains_subscription') && (wcs_order_contains_subscription($order_id) || wcs_order_contains_renewal($order_id));

    }
}
