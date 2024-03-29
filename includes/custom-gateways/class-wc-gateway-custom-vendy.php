<?php

/**
 * Class Tbz_WC_Vendy_Custom_Gateway.
 */
class WC_Gateway_Custom_Vendy extends WC_Gateway_Vendy_Subscriptions {

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
 
		$this->form_fields = array(
			'enabled'                          => array(
				'title'       => __( 'Enable/Disable', 'woo-vendy' ),
				/* translators: payment method title */
				'label'       => sprintf( __( 'Enable Vendy - %s', 'woo-vendy' ), $this->title ),
				'type'        => 'checkbox',
				'description' => __( 'Enable this gateway as a payment option on the checkout page.', 'woo-vendy' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'title'                            => array(
				'title'       => __( 'Title', 'woo-vendy' ),
				'type'        => 'text',
				'description' => __( 'This controls the payment method title which the user sees during checkout.', 'woo-vendy' ),
				'desc_tip'    => true,
				'default'     => __( 'Vendy', 'woo-vendy' ),
			),
			'description'                      => array(
				'title'       => __( 'Description', 'woo-vendy' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the payment method description which the user sees during checkout.', 'woo-vendy' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'payment_page'                     => array(
				'title'       => __( 'Payment Option', 'woo-vendy' ),
				'type'        => 'select',
				'description' => __( 'Popup shows the payment popup on the page while Redirect will redirect the customer to Vendy to make payment.', 'woo-vendy' ),
				'default'     => 'inline',
				'desc_tip'    => false,
				'options'     => array(
					''         => __( 'Select One', 'woo-vendy' ),
					'inline'   => __( 'Popup', 'woo-vendy' ),
//					'redirect' => __( 'Redirect', 'woo-vendy' ),
				),
			),
			'autocomplete_order'               => array(
				'title'       => __( 'Autocomplete Order After Payment', 'woo-vendy' ),
				'label'       => __( 'Autocomplete Order', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-autocomplete-order',
				'description' => __( 'If enabled, the order will be marked as complete after successful payment', 'woo-vendy' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'remove_cancel_order_button'       => array(
				'title'       => __( 'Remove Cancel Order & Restore Cart Button', 'woo-vendy' ),
				'label'       => __( 'Remove the cancel order & restore cart button on the pay for order page', 'woo-vendy' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
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
//				'class'       => __( 'woocommerce_vendy_subaccount_code', 'woo-vendy' ),
//				'default'     => '',
//			),
//			'split_payment_transaction_charge' => array(
//				'title'             => __( 'Split Payment Transaction Charge', 'woo-vendy' ),
//				'type'              => 'number',
//				'description'       => __( 'A flat fee to charge the subaccount for this transaction, in Naira (&#8358;). This overrides the split percentage set when the subaccount was created. Ideally, you will need to use this if you are splitting in flat rates (since subaccount creation only allows for percentage split). e.g. 100 for a &#8358;100 flat fee.', 'woo-vendy' ),
//				'class'             => 'woocommerce_vendy_split_payment_transaction_charge',
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
			'payment_channels'                 => array(
				'title'             => __( 'Payment Channels', 'woo-vendy' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-vendy-payment-channels',
				'description'       => __( 'The payment channels enabled for this gateway', 'woo-vendy' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->channels(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select payment channels', 'woo-vendy' ),
				),
			),
			'cards_allowed'                    => array(
				'title'             => __( 'Allowed Card Brands', 'woo-vendy' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-vendy-cards-allowed',
				'description'       => __( 'The card brands allowed for this gateway. This filter only works with the card payment channel.', 'woo-vendy' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->card_types(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select card brands', 'woo-vendy' ),
				),
			),
			'banks_allowed'                    => array(
				'title'             => __( 'Allowed Banks Card', 'woo-vendy' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-vendy-banks-allowed',
				'description'       => __( 'The banks whose card should be allowed for this gateway. This filter only works with the card payment channel.', 'woo-vendy' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->banks(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select banks', 'woo-vendy' ),
				),
			),
//			'payment_icons'                    => array(
//				'title'             => __( 'Payment Icons', 'woo-vendy' ),
//				'type'              => 'multiselect',
//				'class'             => 'wc-enhanced-select wc-vendy-payment-icons',
//				'description'       => __( 'The payment icons to be displayed on the checkout page.', 'woo-vendy' ),
//				'default'           => '',
//				'desc_tip'          => true,
//				'select_buttons'    => true,
//				'options'           => $this->payment_icons(),
//				'custom_attributes' => array(
//					'data-placeholder' => __( 'Select payment icons', 'woo-vendy' ),
//				),
//			),
			'custom_metadata'                  => array(
				'title'       => __( 'Custom Metadata', 'woo-vendy' ),
				'label'       => __( 'Enable Custom Metadata', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-metadata',
				'description' => __( 'If enabled, you will be able to send more information about the order to Vendy.', 'woo-vendy' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'meta_order_id'                    => array(
				'title'       => __( 'Order ID', 'woo-vendy' ),
				'label'       => __( 'Send Order ID', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-order-id',
				'description' => __( 'If checked, the Order ID will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'meta_name'                        => array(
				'title'       => __( 'Customer Name', 'woo-vendy' ),
				'label'       => __( 'Send Customer Name', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-name',
				'description' => __( 'If checked, the customer full name will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'meta_email'                       => array(
				'title'       => __( 'Customer Email', 'woo-vendy' ),
				'label'       => __( 'Send Customer Email', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-email',
				'description' => __( 'If checked, the customer email address will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'meta_phone'                       => array(
				'title'       => __( 'Customer Phone', 'woo-vendy' ),
				'label'       => __( 'Send Customer Phone', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-phone',
				'description' => __( 'If checked, the customer phone will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'meta_billing_address'             => array(
				'title'       => __( 'Order Billing Address', 'woo-vendy' ),
				'label'       => __( 'Send Order Billing Address', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-billing-address',
				'description' => __( 'If checked, the order billing address will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_shipping_address'            => array(
				'title'       => __( 'Order Shipping Address', 'woo-vendy' ),
				'label'       => __( 'Send Order Shipping Address', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-shipping-address',
				'description' => __( 'If checked, the order shipping address will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_products'                    => array(
				'title'       => __( 'Product(s) Purchased', 'woo-vendy' ),
				'label'       => __( 'Send Product(s) Purchased', 'woo-vendy' ),
				'type'        => 'checkbox',
				'class'       => 'wc-vendy-meta-products',
				'description' => __( 'If checked, the product(s) purchased will be sent to Vendy', 'woo-vendy' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
		);

	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {

		$vendy_settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=vendy' );
		$checkout_settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout' );
		?>

		<h2>
			<?php
			/* translators: payment method title */
			printf( __( 'Vendy - %s', 'woo-vendy' ), esc_attr( $this->title ) );
			?>
			<?php
			if ( function_exists( 'wc_back_link' ) ) {
				wc_back_link( __( 'Return to payments', 'woo-vendy' ), $checkout_settings_url );
			}
			?>
		</h2>

		<h4>
			<?php
			/* translators: link to Vendy developers settings page */
			printf( __( 'Important: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="%s" target="_blank" rel="noopener noreferrer">here</a> to the URL below', 'woo-vendy' ), 'https://dashboard.myvendy.com/#/settings/developer' );
			?>
		</h4>

		<p style="color: red">
			<code><?php echo esc_url( WC()->api_request_url( 'Tbz_WC_Vendy_Webhook' ) ); ?></code>
		</p>

		<p>
			<?php
			/* translators: link to Vendy general settings page */
			printf( __( 'To configure your Vendy API keys and enable/disable test mode, do that <a href="%s">here</a>', 'woo-vendy' ), esc_url( $vendy_settings_url ) );
			?>
		</p>

		<?php

		if ( $this->is_valid_for_use() ) {

			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';

		} else {

			/* translators: disabled message */
			echo '<div class="inline error"><p><strong>' . sprintf( __( 'Vendy Payment Gateway Disabled: %s', 'woo-vendy' ), esc_attr( $this->msg ) ) . '</strong></p></div>';

		}

	}

	/**
	 * Payment Channels.
	 */
	public function channels() {

		return array(
			'card'          => __( 'Cards', 'woo-vendy' ),
//			'bank'          => __( 'Pay with Bank', 'woo-vendy' ),
//			'ussd'          => __( 'USSD', 'woo-vendy' ),
//			'qr'            => __( 'QR', 'woo-vendy' ),
			'bank_transfer' => __( 'Bank Transfer', 'woo-vendy' ),
		);

	}

	/**
	 * Card Types.
	 */
	public function card_types() {

		return array(
			'visa'       => __( 'Visa', 'woo-vendy' ),
			'verve'      => __( 'Verve', 'woo-vendy' ),
			'mastercard' => __( 'Mastercard', 'woo-vendy' ),
		);

	}

	/**
	 * Banks.
	 */
	public function banks() {

		return array(
			'044'  => __( 'Access Bank', 'woo-vendy' ),
			'035A' => __( 'ALAT by WEMA', 'woo-vendy' ),
			'401'  => __( 'ASO Savings and Loans', 'woo-vendy' ),
			'023'  => __( 'Citibank Nigeria', 'woo-vendy' ),
			'063'  => __( 'Access Bank (Diamond)', 'woo-vendy' ),
			'050'  => __( 'Ecobank Nigeria', 'woo-vendy' ),
			'562'  => __( 'Ekondo Microfinance Bank', 'woo-vendy' ),
			'084'  => __( 'Enterprise Bank', 'woo-vendy' ),
			'070'  => __( 'Fidelity Bank', 'woo-vendy' ),
			'011'  => __( 'First Bank of Nigeria', 'woo-vendy' ),
			'214'  => __( 'First City Monument Bank', 'woo-vendy' ),
			'058'  => __( 'Guaranty Trust Bank', 'woo-vendy' ),
			'030'  => __( 'Heritage Bank', 'woo-vendy' ),
			'301'  => __( 'Jaiz Bank', 'woo-vendy' ),
			'082'  => __( 'Keystone Bank', 'woo-vendy' ),
			'014'  => __( 'MainStreet Bank', 'woo-vendy' ),
			'526'  => __( 'Parallex Bank', 'woo-vendy' ),
			'076'  => __( 'Polaris Bank Limited', 'woo-vendy' ),
			'101'  => __( 'Providus Bank', 'woo-vendy' ),
			'221'  => __( 'Stanbic IBTC Bank', 'woo-vendy' ),
			'068'  => __( 'Standard Chartered Bank', 'woo-vendy' ),
			'232'  => __( 'Sterling Bank', 'woo-vendy' ),
			'100'  => __( 'Suntrust Bank', 'woo-vendy' ),
			'032'  => __( 'Union Bank of Nigeria', 'woo-vendy' ),
			'033'  => __( 'United Bank For Africa', 'woo-vendy' ),
			'215'  => __( 'Unity Bank', 'woo-vendy' ),
			'035'  => __( 'Wema Bank', 'woo-vendy' ),
			'057'  => __( 'Zenith Bank', 'woo-vendy' ),
		);

	}

	/**
	 * Payment Icons.
	 */
	public function payment_icons() {

		return array(
			'verve'         => __( 'Verve', 'woo-vendy' ),
			'visa'          => __( 'Visa', 'woo-vendy' ),
			'mastercard'    => __( 'Mastercard', 'woo-vendy' ),
			'vendywhite' => __( 'Secured by Vendy White', 'woo-vendy' ),
			'vendyblue'  => __( 'Secured by Vendy Blue', 'woo-vendy' ),
			'vendy-wc'   => __( 'Vendy Nigeria', 'woo-vendy' ),
			'vendy-gh'   => __( 'Vendy Ghana', 'woo-vendy' ),
			'access'        => __( 'Access Bank', 'woo-vendy' ),
			'alat'          => __( 'ALAT by WEMA', 'woo-vendy' ),
			'aso'           => __( 'ASO Savings and Loans', 'woo-vendy' ),
			'citibank'      => __( 'Citibank Nigeria', 'woo-vendy' ),
			'diamond'       => __( 'Access Bank (Diamond)', 'woo-vendy' ),
			'ecobank'       => __( 'Ecobank Nigeria', 'woo-vendy' ),
			'ekondo'        => __( 'Ekondo Microfinance Bank', 'woo-vendy' ),
			'enterprise'    => __( 'Enterprise Bank', 'woo-vendy' ),
			'fidelity'      => __( 'Fidelity Bank', 'woo-vendy' ),
			'firstbank'     => __( 'First Bank of Nigeria', 'woo-vendy' ),
			'fcmb'          => __( 'First City Monument Bank', 'woo-vendy' ),
			'gtbank'        => __( 'Guaranty Trust Bank', 'woo-vendy' ),
			'heritage'      => __( 'Heritage Bank', 'woo-vendy' ),
			'jaiz'          => __( 'Jaiz Bank', 'woo-vendy' ),
			'keystone'      => __( 'Keystone Bank', 'woo-vendy' ),
			'mainstreet'    => __( 'MainStreet Bank', 'woo-vendy' ),
			'parallex'      => __( 'Parallex Bank', 'woo-vendy' ),
			'polaris'       => __( 'Polaris Bank Limited', 'woo-vendy' ),
			'providus'      => __( 'Providus Bank', 'woo-vendy' ),
			'stanbic'       => __( 'Stanbic IBTC Bank', 'woo-vendy' ),
			'standard'      => __( 'Standard Chartered Bank', 'woo-vendy' ),
			'sterling'      => __( 'Sterling Bank', 'woo-vendy' ),
			'suntrust'      => __( 'Suntrust Bank', 'woo-vendy' ),
			'union'         => __( 'Union Bank of Nigeria', 'woo-vendy' ),
			'uba'           => __( 'United Bank For Africa', 'woo-vendy' ),
			'unity'         => __( 'Unity Bank', 'woo-vendy' ),
			'wema'          => __( 'Wema Bank', 'woo-vendy' ),
			'zenith'        => __( 'Zenith Bank', 'woo-vendy' ),
		);

	}

	/**
	 * Display the selected payment icon.
	 */
	public function get_icon() {
		$icon_html = '<img src="' . WC_HTTPS::force_https_url( WC_VENDY_URL . '/assets/images/vendy.png' ) . '" alt="vendy" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
		$icon      = $this->payment_icons;

		if ( is_array( $icon ) ) {

			$additional_icon = '';

			foreach ( $icon as $i ) {
				$additional_icon .= '<img src="' . WC_HTTPS::force_https_url( WC_VENDY_URL . '/assets/images/' . $i . '.png' ) . '" alt="' . $i . '" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
			}

			$icon_html .= $additional_icon;
		}

		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
	}

	/**
	 * Outputs scripts used for vendy payment.
	 */
	public function payment_scripts() {

		if ( isset( $_GET['pay_for_order'] ) || ! is_checkout_pay_page() ) {
			return;
		}

		if ( $this->enabled === 'no' ) {
			return;
		}

		$order_key = urldecode( $_GET['key'] );
		$order_id  = absint( get_query_var( 'order-pay' ) );

		$order = wc_get_order( $order_id );

		if ( $this->id !== $order->get_payment_method() ) {
			return;
		}

//		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$suffix = '';

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'vendy', 'https://collections.myvendy.com/v1/inline.min.js', array( 'jquery' ), WC_VENDY_VERSION, false );

		wp_enqueue_script( 'wc_vendy', plugins_url( 'assets/js/vendy' . $suffix . '.js', WC_VENDY_MAIN_FILE ), array( 'jquery', 'vendy' ), WC_VENDY_VERSION, false );

		$vendy_params = array(
			'key' => $this->public_key,
			'testMode' => $this->testmode
		);

		if ( is_checkout_pay_page() && get_query_var( 'order-pay' ) ) {

			$email = $order->get_billing_email();

			$amount = $order->get_total();

			$txnref = $order_id . '_' . time();

			$the_order_id  = $order->get_id();
			$the_order_key = $order->get_order_key();
			$currency      = $order->get_currency();

			if ( $the_order_id == $order_id && $the_order_key == $order_key ) {

				$vendy_params['email']    = $email;
				$vendy_params['amount']   = absint( $amount );
				$vendy_params['txnref']   = $txnref;
				$vendy_params['currency'] = $currency;

			}

//            if ( in_array( 'card', $this->payment_channels ) ) {
//				$vendy_params['card_channel'] = 'true';
//			}
//
//			if ( in_array( 'bank_transfer', $this->payment_channels ) ) {
//				$vendy_params['bank_transfer_channel'] = 'true';
//			}

			if ( $this->banks ) {

				$vendy_params['banks_allowed'] = $this->banks;

			}

			if ( $this->cards ) {

				$vendy_params['cards_allowed'] = $this->cards;

			}

			if ( $this->custom_metadata ) {

				if ( $this->meta_order_id ) {

					$vendy_params['meta_order_id'] = $order_id;

				}

				if ( $this->meta_name ) {

					$vendy_params['meta_name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

				}

				if ( $this->meta_email ) {

					$vendy_params['meta_email'] = $email;

				}

				if ( $this->meta_phone ) {

					$vendy_params['meta_phone'] = $order->get_billing_phone();

				}

				if ( $this->meta_products ) {

					$line_items = $order->get_items();

					$products = '';

					foreach ( $line_items as $item_id => $item ) {
						$name      = $item['name'];
						$quantity  = $item['qty'];
						$products .= $name . ' (Qty: ' . $quantity . ')';
						$products .= ' | ';
					}

					$products = rtrim( $products, ' | ' );

					$vendy_params['meta_products'] = $products;

				}

				if ( $this->meta_billing_address ) {

					$billing_address = $order->get_formatted_billing_address();
					$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

					$vendy_params['meta_billing_address'] = $billing_address;

				}

				if ( $this->meta_shipping_address ) {

					$shipping_address = $order->get_formatted_shipping_address();
					$shipping_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $shipping_address ) );

					if ( empty( $shipping_address ) ) {

						$billing_address = $order->get_formatted_billing_address();
						$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

						$shipping_address = $billing_address;

					}

					$vendy_params['meta_shipping_address'] = $shipping_address;

				}
			}

			$order->update_meta_data( '_vendy_txn_ref', $txnref );
			$order->save();
		}

		wp_localize_script( 'wc_vendy', 'wc_vendy_params', $vendy_params );

	}

	/**
	 * Add custom gateways to the checkout page.
	 *
	 * @param $available_gateways
	 *
	 * @return mixed
	 */
	public function add_gateway_to_checkout( $available_gateways ) {

		if ( $this->enabled == 'no' ) {
			unset( $available_gateways[ $this->id ] );
		}

		return $available_gateways;

	}

	/**
	 * Check if the custom Vendy gateway is enabled.
	 *
	 * @return bool
	 */
	public function is_available() {

		if ( 'yes' == $this->enabled ) {

			if ( ! ( $this->public_key && $this->secret_key ) ) {

				return false;

			}

			return true;

		}

		return false;
	}
}
