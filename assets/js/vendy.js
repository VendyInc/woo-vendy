jQuery(function ($) {

    let vendy_submit = false;

    $('#wc-vendy-form').hide();

    wcVendyFormHandler();

    jQuery('#vendy-payment-button').click(function (e) {
        return wcVendyFormHandler();
    });

    jQuery('#vendy_form form#order_review').submit(function (e) {
        return wcVendyFormHandler();
    });

    function wcVendyCustomFields() {

        let custom_fields = [
            {
                "display_name": "Plugin",
                "variable_name": "plugin",
                "value": "woo-vendy"
            }
        ];

        if (wc_vendy_params.meta_order_id) {

            custom_fields.push({
                display_name: "Order ID",
                variable_name: "order_id",
                value: wc_vendy_params.meta_order_id
            });

        }

        if (wc_vendy_params.meta_name) {

            custom_fields.push({
                display_name: "Customer Name",
                variable_name: "customer_name",
                value: wc_vendy_params.meta_name
            });
        }

        if (wc_vendy_params.meta_email) {

            custom_fields.push({
                display_name: "Customer Email",
                variable_name: "customer_email",
                value: wc_vendy_params.meta_email
            });
        }

        if (wc_vendy_params.meta_phone) {

            custom_fields.push({
                display_name: "Customer Phone",
                variable_name: "customer_phone",
                value: wc_vendy_params.meta_phone
            });
        }

        if (wc_vendy_params.meta_billing_address) {

            custom_fields.push({
                display_name: "Billing Address",
                variable_name: "billing_address",
                value: wc_vendy_params.meta_billing_address
            });
        }

        if (wc_vendy_params.meta_shipping_address) {

            custom_fields.push({
                display_name: "Shipping Address",
                variable_name: "shipping_address",
                value: wc_vendy_params.meta_shipping_address
            });
        }

        if (wc_vendy_params.meta_products) {

            custom_fields.push({
                display_name: "Products",
                variable_name: "products",
                value: wc_vendy_params.meta_products
            });
        }

        return custom_fields;
    }

    function wcVendyCustomFilters() {

        let custom_filters = {};

        if (wc_vendy_params.card_channel) {

            if (wc_vendy_params.banks_allowed) {

                custom_filters['banks'] = wc_vendy_params.banks_allowed;

            }

            if (wc_vendy_params.cards_allowed) {

                custom_filters['card_brands'] = wc_vendy_params.cards_allowed;
            }

        }

        return custom_filters;
    }

    function wcPaymentChannels() {

        let payment_channels = [];

        // if ( wc_vendy_params.bank_channel ) {
        // 	payment_channels.push( 'bank' );
        // }

        if (wc_vendy_params.card_channel) {
            payment_channels.push('card');
        }
        //
        // if ( wc_vendy_params.ussd_channel ) {
        // 	payment_channels.push( 'ussd' );
        // }
        //
        // if ( wc_vendy_params.qr_channel ) {
        // 	payment_channels.push( 'qr' );
        // }

        if (wc_vendy_params.bank_transfer_channel) {
            payment_channels.push('bank_transfer');
        }

        return payment_channels;
    }

    function wcVendyFormHandler() {

        $('#wc-vendy-form').hide();

        if (vendy_submit) {
            vendy_submit = false;
            return true;
        }

        let $form = $('form#payment-form, form#order_review'),
            vendy_txnref = $form.find('input.vendy_txnref'),
            subaccount_code = '',
            charges_account = '',
            transaction_charges = '';

        vendy_txnref.val('');
        let amount = Number(wc_vendy_params.amount);

        let vendy_callback = function (transaction) {
            $form.append('<input type="hidden" class="vendy_txnref" name="vendy_txnref" value="' + transaction.refid + '"/>');
            vendy_submit = true;

            $form.submit();

            $('body').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                },
                css: {
                    cursor: "wait"
                }
            });
        };

        let meta_data = wcVendyCustomFields();

        if (Array.isArray(wcPaymentChannels()) && wcPaymentChannels().length) {
            meta_data['methods'] = wcPaymentChannels();
            if (!$.isEmptyObject(wcVendyCustomFilters())) {
                meta_data['custom_filters'] = wcVendyCustomFilters();
            }
        }

        const vendyPay = new VendyPay();

        vendyPay.initPayPopup({
            key: wc_vendy_params.key,
            amount: amount,
            currency: wc_vendy_params.currency,
            phoneNumber: wc_vendy_params.meta_phone,
            chargeCustomer: true,
            isTest: true,
            meta: {
                reference: wc_vendy_params.txnref,
                custom_fields: meta_data,
            },
            onSuccess: vendy_callback,
            onFailure: (transaction) => {
                $('#wc-vendy-form').show();
                $(this.el).unblock();
            },
            onCancel: (_) => {
                $('#wc-vendy-form').show();
                $(this.el).unblock();
            },
        });
    }
});