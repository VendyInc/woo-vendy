jQuery( function( $ ) {
	'use strict';

	/**
	 * Object to handle Vendy admin functions.
	 */
	var wc_vendy_admin = {
		/**
		 * Initialize.
		 */
		init: function() {

			// Toggle api key settings.
			$( document.body ).on( 'change', '#woocommerce_vendy_testmode', function() {
				var test_secret_key = $( '#woocommerce_vendy_test_secret_key' ).parents( 'tr' ).eq( 0 ),
					test_public_key = $( '#woocommerce_vendy_test_public_key' ).parents( 'tr' ).eq( 0 ),
					live_secret_key = $( '#woocommerce_vendy_live_secret_key' ).parents( 'tr' ).eq( 0 ),
					live_public_key = $( '#woocommerce_vendy_live_public_key' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					test_secret_key.show();
					test_public_key.show();
					live_secret_key.hide();
					live_public_key.hide();
				} else {
					test_secret_key.hide();
					test_public_key.hide();
					live_secret_key.show();
					live_public_key.show();
				}
			} );

			$( '#woocommerce_vendy_testmode' ).change();

			$( document.body ).on( 'change', '.woocommerce_vendy_split_payment', function() {
				var subaccount_code = $( '.woocommerce_vendy_subaccount_code' ).parents( 'tr' ).eq( 0 ),
					subaccount_charge = $( '.woocommerce_vendy_split_payment_charge_account' ).parents( 'tr' ).eq( 0 ),
					transaction_charge = $( '.woocommerce_vendy_split_payment_transaction_charge' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					subaccount_code.show();
					subaccount_charge.show();
					transaction_charge.show();
				} else {
					subaccount_code.hide();
					subaccount_charge.hide();
					transaction_charge.hide();
				}
			} );

			$( '#woocommerce_vendy_split_payment' ).change();

			// Toggle Custom Metadata settings.
			$( '.wc-vendy-metadata' ).change( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( '.wc-vendy-meta-order-id, .wc-vendy-meta-name, .wc-vendy-meta-email, .wc-vendy-meta-phone, .wc-vendy-meta-billing-address, .wc-vendy-meta-shipping-address, .wc-vendy-meta-products' ).closest( 'tr' ).show();
				} else {
					$( '.wc-vendy-meta-order-id, .wc-vendy-meta-name, .wc-vendy-meta-email, .wc-vendy-meta-phone, .wc-vendy-meta-billing-address, .wc-vendy-meta-shipping-address, .wc-vendy-meta-products' ).closest( 'tr' ).hide();
				}
			} ).change();

			// Toggle Bank filters settings.
			$( '.wc-vendy-payment-channels' ).on( 'change', function() {

				var channels = $( ".wc-vendy-payment-channels" ).val();

				if ( $.inArray( 'card', channels ) != '-1' ) {
					$( '.wc-vendy-cards-allowed' ).closest( 'tr' ).show();
					$( '.wc-vendy-banks-allowed' ).closest( 'tr' ).show();
				}
				else {
					$( '.wc-vendy-cards-allowed' ).closest( 'tr' ).hide();
					$( '.wc-vendy-banks-allowed' ).closest( 'tr' ).hide();
				}

			} ).change();

			$( ".wc-vendy-payment-icons" ).select2( {
				templateResult: formatVendyPaymentIcons,
				templateSelection: formatVendyPaymentIconDisplay
			} );

			$( '#woocommerce_vendy_test_secret_key, #woocommerce_vendy_live_secret_key' ).after(
				'<button class="wc-vendy-toggle-secret" style="height: 30px; margin-left: 2px; cursor: pointer"><span class="dashicons dashicons-visibility"></span></button>'
			);

			$( '.wc-vendy-toggle-secret' ).on( 'click', function( event ) {
				event.preventDefault();

				let $dashicon = $( this ).closest( 'button' ).find( '.dashicons' );
				let $input = $( this ).closest( 'tr' ).find( '.input-text' );
				let inputType = $input.attr( 'type' );

				if ( 'text' == inputType ) {
					$input.attr( 'type', 'password' );
					$dashicon.removeClass( 'dashicons-hidden' );
					$dashicon.addClass( 'dashicons-visibility' );
				} else {
					$input.attr( 'type', 'text' );
					$dashicon.removeClass( 'dashicons-visibility' );
					$dashicon.addClass( 'dashicons-hidden' );
				}
			} );
		}
	};

	function formatVendyPaymentIcons( payment_method ) {
		if ( !payment_method.id ) {
			return payment_method.text;
		}

		var $payment_method = $(
			'<span><img src=" ' + wc_vendy_admin_params.plugin_url + '/assets/images/' + payment_method.element.value.toLowerCase() + '.png" class="img-flag" style="height: 15px; weight:18px;" /> ' + payment_method.text + '</span>'
		);

		return $payment_method;
	};

	function formatVendyPaymentIconDisplay( payment_method ) {
		return payment_method.text;
	};

	wc_vendy_admin.init();

} );
