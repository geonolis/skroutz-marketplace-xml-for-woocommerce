<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_activation_hook( WPSSSC_FILE, 'wpslash_skroutz_smart_cart_activation' );
 
/**
 * Runs only when the plugin is activated.
 *
 * @since 0.1.0
 */
function wpslash_skroutz_smart_cart_activation() {
	set_transient( 'wpslash-skroutz-smart-cart-notice', true);

	if (!get_option('wpslash_skroutz_smart_cart_security', false )) {
		update_option( 'wpslash_skroutz_smart_cart_security', wpslash_skroutz_smart_cart_generateRandomString( 7 ));
	}
}

function wpslash_skroutz_smart_cart_activation_notice() {
	if ( get_transient( 'wpslash-skroutz-smart-cart-notice' ) ) {
		?>
		<div class="updated notice is-dismissible">
			<p><?php esc_html_e('Thanks for installing Skroutz Smart Cart for WooCommerce', 'skroutz-marketplace-xml-for-woocommerce'); ?>. <strong><a href="admin.php?page=wc-settings&amp;tab=wpslash_skroutz_smart_cart"><?php esc_html_e('Click Here', 'skroutz-marketplace-xml-for-woocommerce'); ?></a></strong> <?php esc_html_e('to get started with the configuration', 'skroutz-marketplace-xml-for-woocommerce'); ?>.</p>
		</div>
		<?php
		delete_transient( 'wpslash-skroutz-smart-cart-notice' );
	}
}

add_action( 'admin_notices', 'wpslash_skroutz_smart_cart_activation_notice' );

function wpslash_skroutz_smart_cart_generateRandomString( $length = 10 ) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[wp_rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

add_action( 'plugins_loaded', 'wpslash_skroutz_secuirty_code_generate' );
 
function wpslash_skroutz_secuirty_code_generate() {
	if (!get_option('wpslash_skroutz_smart_cart_security', false )) {
		update_option( 'wpslash_skroutz_smart_cart_security', wpslash_skroutz_smart_cart_generateRandomString( 7 ));
	}
}

?>
