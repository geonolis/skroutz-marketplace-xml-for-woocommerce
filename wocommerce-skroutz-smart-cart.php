<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @since             1.1.1
* @package           Skroutz_Marketplace_XML_For_WooCommerce
*
* @wordpress-plugin
* Plugin Name:       Skroutz Marketplace & XML for WooCommerce 
* Description:       Connect Skroutz Smart Cart with WooCommerce and WooShop POS & ERP, adds payment method skroutz and Generate XML Feed
* Version:           1.1.4
* Author:            GeoNolis
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       skroutz-marketplace-xml-for-woocommerce
* Domain Path:       /languages
* WC requires at least: 3.4
* WC tested up to: 8.9.1
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
define('WPSSSC_DIR', plugin_dir_path( __FILE__ ) );
define('WPSSSC_FILE', __DIR__ );
define('WPSSSC_DIR_URL', plugin_dir_url(__FILE__) );
if (!function_exists('is_plugin_active_for_network')) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php' ;

}
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php') ) {

	 require_once __DIR__ . '/inc/functions.php';
	 require_once __DIR__ . '/inc/additional.php';
	 require_once __DIR__ . '/inc/hooks.php';
	 require_once __DIR__ . '/inc/settings.php';
	 require_once __DIR__ . '/inc/ajax.hooks.php';
	 require_once __DIR__ . '/inc/feed.hooks.php';
	 require_once __DIR__ . '/inc/feed.builder.php';
	 require_once __DIR__ . '/inc/custom.fields.php';
	 require_once __DIR__ . '/inc/activation.hook.php';

		add_action( 'before_woocommerce_init', function () {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );



} else {
			   deactivate_plugins(WPSSSC_DIR . '/wocommerce-skroutz-smart-cart.php');
			   add_action( 'admin_notices', 'skroutz_smart_cart_requirememts_admin_notice' );
}

function skroutz_smart_cart_requirememts_admin_notice() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}


	?>
	<div class="notice is-dismissible notice-error"> <!-- can use 'notice-error' or 'notice-success' as well -->
		<p><?php esc_html_e( 'Skroutz Smart Cart for WooCommerce requires WooCommerce plugin to be activated .Please activate WooCommerce plugin first.', 'skroutz-marketplace-xml-for-woocommerce' ); ?></p>
	</div>
	<?php 
}


