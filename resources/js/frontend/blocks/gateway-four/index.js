/**
 * External dependencies
 */
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { getSetting } from '@woocommerce/settings';
import {Content, ariaLabel, Label} from '../base';
import { PAYMENT_METHOD_NAME } from './constants';

const settings = getSetting( 'vendy-four_data', {} );
const label = ariaLabel({ title: settings.title });

/**
 * Vendy payment method config object.
 */
const Vendy_Gateway = {
    name: PAYMENT_METHOD_NAME,
    label: <Label logoUrls={ settings.logo_urls } title={ settings.title } />,
    content: <Content description={ settings.description } />,
    edit: <Content description={ settings.description } />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        showSavedCards: settings.allow_saved_cards,
        showSaveOption: settings.allow_saved_cards,
        features: settings.supports,
    },
};

registerPaymentMethod( Vendy_Gateway );
