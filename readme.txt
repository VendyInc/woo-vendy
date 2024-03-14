=== Vendy Woo Gateway ===
Contributors: Ayomide Fagbohungbe
Tags: vendy, woocommerce, payment gateway, verve, nigeria, naira, mastercard, visa
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.0.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Vendy for WooCommerce allows your store in Nigeria to accept secure payments from multiple local and global payment channels.

== Description ==

Vendy makes it easy for businesses in Nigeria tobum accept secure payments from multiple local and global payment channels. Integrate Vendy with your store today, and let your customers pay you with their choice of methods.

With Vendy for WooCommerce, you can accept payments via:

* Credit/Debit Cards — Visa, Mastercard, Verve
* Bank transfer (Nigeria)
* Many more coming soon

= Why Vendy? =

* Start receiving payments instantly—go from sign-up to your first real transaction in as little as 15 minutes
* Simple, transparent pricing—no hidden charges or fees
* Modern, seamless payment experience via the Vendy Checkout — [Try the demo!](https://myvendy.com/demo/checkout)
* Understand your customers better through a simple and elegant dashboard
* Access to attentive, empathetic customer support 24/7
* Free updates as we launch new features and payment options
* Clearly documented APIs to build your custom payment experiences

Over 60,000 businesses of all sizes in Nigeria rely on Vendy's suite of products to receive payments and make payouts seamlessly. Sign up on [dashboard.vendy.money/signup](https://dashboard.vendy.money/signup) to get started.

= Note =

This plugin is meant to be used by merchants in Nigeria.

= Plugin Features =

*   __Accept payment__ via Mastercard, Visa, Verve & Bank Transfer.
*   __Seamless integration__ into the WooCommerce checkout page. Accept payment directly on your site
*   __Refunds__ from the WooCommerce order details page. Refund an order directly from the order details page
*   __Recurring payment__ using [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin

= WooCommerce Subscriptions Integration =

*	The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with __WooCommerce v2.6 and above__ and __WooCommerce Subscriptions v2.0 and above__.

*	No subscription plans is created on Vendy. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin handles all the subscription functionality.

*	If a customer pays for a subscription using a Mastercard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

*	For customers paying with a Verve card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

*	If a subscription has a free trial and no signup-fee, automatic renewal is not possible for the first payment because the initial order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a Mastercard or Visa card is used to renew the subscription subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.


== Installation ==

*   Go to __WordPress Admin__
*   Go to __Plugins__ > __Add New__ from the left-hand menu
*   In the search box type __Vendy Woo Payment Gateway__
*   Click on Install now when you see __Vendy Woo Payment Gateway__ to install the plugin
*   After installation, __activate__ the plugin.


= Vendy Setup and Configuration =
*   Go to __WooCommerce > Settings__ and click on the __Payments__ tab
*   You'll see Vendy listed along with your other payment methods. Click __Set Up__
*   On the next screen, configure the plugin. There is a selection of options on the screen. Read what each one does below.

1. __Enable/Disable__ - Check this checkbox to Enable Vendy on your store's checkout
2. __Title__ - This will represent Vendy on your list of Payment options during checkout. It guides users to know which option to select to pay with Vendy. __Title__ is set to "Debit/Credit Cards" by default, but you can change it to suit your needs.
3. __Description__ - This controls the message that appears under the payment fields on the checkout page. Use this space to give more details to customers about what Vendy is and what payment methods they can use with it.
4. __Test Mode__ - Check this to enable test mode. When selected, the fields in step six will say "Test" instead of "Live." Test mode enables you to test payments before going live. The orders process with test payment methods, no money is involved so there is no risk. You can uncheck this when your store is ready to accept real payments.
5. __Payment Option__ - Select how Vendy Checkout displays to your customers. A popup displays Vendy Checkout on the same page.
6. __API Keys__ - The next two text boxes are for your Vendy API keys, which you can get from your Vendy Dashboard. If you enabled Test Mode in step four, then you'll need to use your test API keys here. Otherwise, you can enter your live keys.
7. __Additional Settings__ - While not necessary for the plugin to function, there are some extra configuration options you have here. You can do things like add custom metadata to your transactions (the data will show up on your Vendy dashboard). The tooltips next to the options provide more information on what they do.
8. Click on __Save Changes__ to update the settings.

To account for poor network connections, which can sometimes affect order status updates after a transaction, we __strongly__ recommend that you set a Webhook URL on your Vendy dashboard. This way, whenever a transaction is complete on your store, we'll send a notification to the Webhook URL, which will update the order and mark it as paid. You can set this up by using the URL in red at the top of the Settings page. Just copy the URL and save it as your webhook URL on your Vendy dashboard under __Settings > API Keys & Webhooks__ tab.

If you do not find Vendy on the Payment method options, please go through the settings again and ensure that:

*   You've checked the __"Enable/Disable"__ checkbox
*   You've entered your __API Keys__ in the appropriate field
*   You've clicked on __Save Changes__ during setup

== Frequently Asked Questions ==

= What Do I Need To Use The Plugin =

*   A Vendy merchant account—use an existing account or [create an account here](https://dashboard.vendy.money/signup)
*   An active [WooCommerce installation](https://docs.woocommerce.com/document/installing-uninstalling-woocommerce/)
*   A valid [SSL Certificate](https://docs.woocommerce.com/document/ssl-and-https/)

= WooCommerce Subscriptions Integration =

*	The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with WooCommerce v2.6 and above and WooCommerce Subscriptions v2.0 and above.

*	No subscription plans is created on Vendy. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) handles all the subscription functionality.

*	If a customer pays for a subscription using a MasterCard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

*	For customers paying with a Verve card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

*	If a subscription has a free trial and no signup-fee, automatic renewal is not possible because the order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a MasterCard or Visa card is used to renew subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.


== Changelog ==

= 1.0.1 - March 3, 2024

- Fix issue where test mode doesn't actually switch to prod

= 1.0.0 - February 28, 2024 =

*   First release

== Screenshots ==

1. Vendy displayed as a payment method on the WooCommerce payment methods page


2. Vendy Woo payment gateway settings page

3. Vendy on WooCommerce Checkout