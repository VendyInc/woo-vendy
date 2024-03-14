<?php
/**
 * Plugin Name: Vendy Woo Payment Gateway
 * Plugin URI: https://myvendy.com
 * Description: WooCommerce payment gateway for Vendy
 * Version: 1.0.1
 * Author: Vendy Dev
 * Author URI: https://vendy.readme.io
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 7.0
 * WC tested up to: 8.3
 * Text Domain: woo-vendy
 * Domain Path: /languages
 */

use Automattic\WooCommerce\Admin\Notes\Note;
use Automattic\WooCommerce\Admin\Notes\Notes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_VENDY_MAIN_FILE', __FILE__ );
define( 'WC_VENDY_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

define( 'WC_VENDY_VERSION', '1.0.1' );

/**
 * Initialize Vendy Woo Payment gateway.
 */
function tbz_wc_vendy_init() {

	load_plugin_textdomain( 'woo-vendy', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		add_action( 'admin_notices', 'tbz_wc_vendy_wc_missing_notice' );
		return;
	}

	add_action( 'admin_init', 'tbz_wc_vendy_testmode_notice' );

	require_once __DIR__ . '/includes/class-wc-gateway-vendy.php';

	require_once __DIR__ . '/includes/class-wc-gateway-vendy-subscriptions.php';

	require_once __DIR__ . '/includes/custom-gateways/class-wc-gateway-custom-vendy.php';

	require_once __DIR__ . '/includes/custom-gateways/gateway-one/class-wc-gateway-vendy-one.php';
	require_once __DIR__ . '/includes/custom-gateways/gateway-two/class-wc-gateway-vendy-two.php';
	require_once __DIR__ . '/includes/custom-gateways/gateway-three/class-wc-gateway-vendy-three.php';
	require_once __DIR__ . '/includes/custom-gateways/gateway-four/class-wc-gateway-vendy-four.php';

	add_filter( 'woocommerce_payment_gateways', 'tbz_wc_add_vendy_gateway', 99 );

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tbz_woo_vendy_plugin_action_links' );

}
add_action( 'plugins_loaded', 'tbz_wc_vendy_init', 99 );

/**
 * Add Settings link to the plugin entry in the plugins menu.
 *
 * @param array $links Plugin action links.
 *
 * @return array
 **/
function tbz_woo_vendy_plugin_action_links( $links ) {

	$settings_link = array(
		'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=vendy' ) . '" title="' . __( 'View Vendy Woo Settings', 'woo-vendy' ) . '">' . __( 'Settings', 'woo-vendy' ) . '</a>',
	);

	return array_merge( $settings_link, $links );

}

/**
 * Add Vendy Gateway to Woo.
 *
 * @param array $methods Woo payment gateways methods.
 *
 * @return array
 */
function tbz_wc_add_vendy_gateway( $methods ) {

	if ( class_exists( 'WC_Subscriptions_Order' ) && class_exists( 'WC_Payment_Gateway_CC' ) ) {
		$methods[] = 'WC_Gateway_Vendy_Subscriptions';
	} else {
		$methods[] = 'WC_Gateway_Vendy';
	}

	if ( 'NGN' === get_woocommerce_currency() ) {

		$settings        = get_option( 'woocommerce_vendy_settings', '' );
		$custom_gateways = isset( $settings['custom_gateways'] ) ? $settings['custom_gateways'] : '';

		switch ( $custom_gateways ) {
            case '4':
				$methods[] = 'WC_Gateway_Vendy_One';
				$methods[] = 'WC_Gateway_Vendy_Two';
				$methods[] = 'WC_Gateway_Vendy_Three';
				$methods[] = 'WC_Gateway_Vendy_Four';
				break;

			case '3':
				$methods[] = 'WC_Gateway_Vendy_One';
				$methods[] = 'WC_Gateway_Vendy_Two';
				$methods[] = 'WC_Gateway_Vendy_Three';
				break;

			case '2':
				$methods[] = 'WC_Gateway_Vendy_One';
				$methods[] = 'WC_Gateway_Vendy_Two';
				break;

			case '1':
				$methods[] = 'WC_Gateway_Vendy_One';
				break;

			default:
				break;
		}
	}

	return $methods;

}

/**
 * Display a notice if WooCommerce is not installed
 */
function tbz_wc_vendy_wc_missing_notice() {
	echo '<div class="error"><p><strong>' . sprintf( __( 'Vendy requires WooCommerce to be installed and active. Click %s to install WooCommerce.', 'woo-vendy' ), '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=772&height=539' ) . '" class="thickbox open-plugin-details-modal">here</a>' ) . '</strong></p></div>';
}

/**
 * Display the test mode notice.
 **/
function tbz_wc_vendy_testmode_notice() {

	if ( ! class_exists( Notes::class ) ) {
		return;
	}

	if ( ! class_exists( WC_Data_Store::class ) ) {
		return;
	}

	if ( ! method_exists( Notes::class, 'get_note_by_name' ) ) {
		return;
	}

	$test_mode_note = Notes::get_note_by_name( 'vendy-test-mode' );

	if ( false !== $test_mode_note ) {
		return;
	}

	$vendy_settings = get_option( 'woocommerce_vendy_settings' );
	$test_mode         = $vendy_settings['testmode'] ?? '';

	if ( 'yes' !== $test_mode ) {
		Notes::delete_notes_with_name( 'vendy-test-mode' );

		return;
	}

	$note = new Note();
	$note->set_title( __( 'Vendy test mode enabled', 'woo-vendy' ) );
	$note->set_content( __( 'Vendy test mode is currently enabled. Remember to disable it when you want to start accepting live payment on your site.', 'woo-vendy' ) );
	$note->set_type( Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
	$note->set_layout( 'plain' );
	$note->set_is_snoozable( false );
	$note->set_name( 'vendy-test-mode' );
	$note->set_source( 'woo-vendy' );
	$note->add_action( 'disable-vendy-test-mode', __( 'Disable Vendy test mode', 'woo-vendy' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=vendy' ) );
	$note->save();
}

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

/**
 * Registers WooCommerce Blocks integration.
 */
function tbz_wc_gateway_vendy_woocommerce_block_support() {
	if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		require_once __DIR__ . '/includes/class-wc-gateway-vendy-blocks-support.php';
		require_once __DIR__ . '/includes/custom-gateways/class-wc-gateway-custom-vendy-blocks-support.php';
		require_once __DIR__ . '/includes/custom-gateways/gateway-one/class-wc-gateway-vendy-one-blocks-support.php';
		require_once __DIR__ . '/includes/custom-gateways/gateway-two/class-wc-gateway-vendy-two-blocks-support.php';
		require_once __DIR__ . '/includes/custom-gateways/gateway-three/class-wc-gateway-vendy-three-blocks-support.php';
		require_once __DIR__ . '/includes/custom-gateways/gateway-four/class-wc-gateway-vendy-four-blocks-support.php';
		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			static function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				$payment_method_registry->register( new WC_Gateway_Vendy_Blocks_Support() );
				$payment_method_registry->register( new WC_Gateway_Vendy_One_Blocks_Support() );
				$payment_method_registry->register( new WC_Gateway_Vendy_Two_Blocks_Support() );
				$payment_method_registry->register( new WC_Gateway_Vendy_Three_Blocks_Support() );
				$payment_method_registry->register( new WC_Gateway_Vendy_Four_Blocks_Support() );
			}
		);
	}
}
add_action( 'woocommerce_blocks_loaded', 'tbz_wc_gateway_vendy_woocommerce_block_support' );
