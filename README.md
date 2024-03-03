# Vendy WooCommerce Gateway

**Contributors:** Ayomide Fagbohungbe

**Tags:** vendy, woocommerce, payment gateway, verve, nigeria, mastercard, visa

**Requires at least:** 4.7

**Tested up to:** 5.4

**Stable tag:** 1.0.1

**License:** GPLv2 or later

**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Vendy WooCommerce Payment Gateway allows you to accept online payments from local and international customers

## Description

This is a Vendy payment gateway for WooCommerce.

Vendy is on a mission to deliver a safe and convenient payment experience for customers and merchants. Vendy provide Nigerian with the tools and services needed to accept online payments from local and international customers using Mastercard, Visa, Verve, Bank Accounts, GTB 737 & Visa QR.

To signup for a Vendy Merchant account visit their website by clicking [here](https://myvendy.com)

Vendy WooCommerce Payment Gateway allows you to accept payment on your WooCommerce store using Mastercard, Visa, Verve, Bank Transfer.

With this Vendy WooCommerce Payment Gateway plugin, you will be able to accept the following payment methods in your shop:

- **Mastercard**
- **Visa**
- **Verve**
- **Bank Transfer**

### Note

This plugin is meant to be used by merchants in Nigeria.

### Plugin Features

- **Accept payment** via Mastercard, Visa, Verve & Bank Transfer
- **Seamless integration** into the WooCommerce checkout page. Accept payment directly on your site
- **Refunds** from the WooCommerce order details page. Refund an order directly from the order details page

### WooCommerce Subscriptions Integration

- The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with **WooCommerce v2.6 and above** and **WooCommerce Subscriptions v2.0 and above**.

- No subscription plans is created on Vendy. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin handles all the subscription functionality.

- If a customer pays for a subscription using a Mastercard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

- For customers paying with a Verve card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

- If a subscription has a free trial and no signup-fee, automatic renewal is not possible for the first payment because the initial order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a Mastercard or Visa card is used to renew the subscription subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.

## Installation

### Automatic Installation

-     Login to your WordPress Admin area
-     Go to "Plugins > Add New" from the left hand menu
-     In the search box type __Vendy WooCommerce Payment Gateway__
- From the search result you will see **Vendy WooCommerce Payment Gateway** click on **Install Now** to install the plugin
- A popup window will ask you to confirm your wish to install the Plugin.
- After installation, activate the plugin.
-     Open the settings page for WooCommerce and click the "Checkout" tab.
-     Click on the __Vendy__ link from the available Checkout Options
- Configure your **Vendy Payment Gateway** settings. See below for details.

### Manual Installation

1.      Download the plugin zip file
2.      Login to your WordPress Admin. Click on "Plugins > Add New" from the left hand menu.
3.  Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
4.  Activate the plugin.
5.      Open the settings page for WooCommerce and click the "Checkout" tab.
6.      Click on the __Vendy__ link from the available Checkout Options
7.  Configure your **Vendy Payment Gateway** settings. See below for details.

### Configure the plugin

To configure the plugin, go to **WooCommerce > Settings** from the left hand menu, then click **Checkout** from the top tab. You will see **Vendy** as part of the available Checkout Options. Click on it to configure the payment gateway.

- **Enable/Disable** - check the box to enable Vendy Payment Gateway.
- **Title** - allows you to determine what your customers will see this payment option as on the checkout page.
- **Description** - controls the message that appears under the payment fields on the checkout page. Here you can list the types of cards you accept.
- **Test Mode** - Check to enable test mode. Test mode enables you to test payments before going live. If you ready to start receiving real payment on your site, kindly uncheck this.
- **Test Secret Key** - Enter your Test Secret Key here. Get your API keys from your Vendy account under Settings > Developer/API
- **Test Public Key** - Enter your Test Public Key here. Get your API keys from your Vendy account under Settings > Developer/API
- **Live Secret Key** - Enter your Live Secret Key here. Get your API keys from your Vendy account under Settings > Developer/API
- **Live Public Key** - Enter your Live Public Key here. Get your API keys from your Vendy account under Settings > Developer/API
- Click on **Save Changes** for the changes you made to be effected.

## Frequently Asked Questions

### What Do I Need To Use The Plugin

1. You need to have WooCommerce plugin installed and activated on your WordPress site.
2. You need to open a Vendy merchant account on [Vendy](https://dashboard.vendy.money)

### WooCommerce Subscriptions Integration

- The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with WooCommerce v2.6 and above and WooCommerce Subscriptions v2.0 and above.

- No subscription plans is created on Vendy. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) handles all the subscription functionality.

- If a customer pays for a subscription using a MasterCard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

- For customers paying with a Verve Card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

- If a subscription has a free trial and no signup-fee, automatically renewal is not possible because the order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a MasterCard or Visa card is used to renew subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.

## Changelog

### 1.0.1 - March 3, 2024

- Fix issue where test mode doesn't actually switch to prod

### 1.0.0 - February 28, 2024

- First release

## Upgrade Notice

### 1.0.0

- WooCommerce 4.0 compatibility.

## Screenshots

### 1. Vendy WooCommerce Payment Gateway Setting Page

###

![Screenshot 1](https://github.com/VendyInc/woo-vendy/blob/master/assets/images/screenshot-1.png)

### 2. Vendy WooCommerce Payment Gateway on the checkout page

###

![Screenshot 2](https://github.com/VendyInc/woo-vendy/blob/master/assets/images/screenshot-2.png)

### 3. Vendy popup payment page

###

![Screenshot 3](https://github.com/VendyInc/woo-vendy/blob/master/assets/images/screenshot-3.png)

