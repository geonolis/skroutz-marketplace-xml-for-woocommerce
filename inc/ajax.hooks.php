<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('wp_ajax_wpslash_skroutz_smart_cart_load_order', 'wpslash_skroutz_smart_cart_load_order');
function wpslash_skroutz_smart_cart_load_order() {
	check_ajax_referer('wpslash_skroutz_smart_cart_order_security', 'security');

	if (isset($_POST['order_id'])) {
		$order_id = intval( wp_unslash( $_POST['order_id'] ) );
		$skroutz_order = get_post_meta($order_id, 'wpslash_skroutz_smart_cart_order_code', true);
		$api_token  = get_option( 'wc_settings_tab_wpslash_smart_cart_api_token', false );

		$response = wp_remote_get( 'https://api.skroutz.gr/merchants/ecommerce/orders/' . $skroutz_order, array(
			'timeout' => 30,
			'headers' => array(
				'Accept'        => 'application/vnd.skroutz+json; version=3.0',
				'Authorization' => 'Bearer ' . $api_token,
			),
		) );

		if ( is_wp_error( $response ) ) {
			echo 'cURL Error #:' . esc_html( $response->get_error_message() );
		} else {
			$body = wp_remote_retrieve_body( $response );
			$decoded_response = json_decode( $body, true );

			echo wp_json_encode( $decoded_response );
		}
	}

	wp_die();
}

add_action('wp_ajax_wpslash_skroutz_smart_cart_reject_order', 'wpslash_skroutz_smart_cart_reject_order');
function wpslash_skroutz_smart_cart_reject_order() {
	check_ajax_referer('wpslash_skroutz_smart_cart_order_security', 'security');

	if (isset($_POST['order_id'])) {
		$order_id = intval( wp_unslash( $_POST['order_id'] ) );
		$skroutz_order = get_post_meta($order_id, 'wpslash_skroutz_smart_cart_order_code', true);
		$rejection_reason = '';
		$api_token  = get_option( 'wc_settings_tab_wpslash_smart_cart_api_token', false );
		$quantity = '';
		$line_items = '';

		if (isset($_POST['rejection_reason'])) {
			$rejection_reason = sanitize_text_field( wp_unslash( $_POST['rejection_reason'] ) );
		}
		if (isset($_POST['line_items'])) {
			$line_items = sanitize_text_field( wp_unslash( $_POST['line_items'] ) );
		}
		if (isset($_POST['quantity'])) {
			$quantity = sanitize_text_field( wp_unslash( $_POST['quantity'] ) );
		}

		$post_data = array(
			'line_items' => array(
				array(
					'id'        => $line_items,
					'reason_id' => intval( $rejection_reason ),
				),
			),
		);

		$response = wp_remote_post( 'https://api.skroutz.gr/merchants/ecommerce/orders/' . $skroutz_order . '/reject', array(
			'timeout' => 30,
			'headers' => array(
				'Accept'        => 'application/vnd.skroutz+json; version=3.0',
				'Authorization' => 'Bearer ' . $api_token,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode( $post_data ),
		) );

		if ( is_wp_error( $response ) ) {
			echo 'cURL Error #:' . esc_html( $response->get_error_message() );
		} else {
			$body = wp_remote_retrieve_body( $response );
			$decoded_response = json_decode( $body, true );
			echo wp_json_encode( $decoded_response );
		}
	}

	wp_die();
}

add_action('wp_ajax_wpslash_skroutz_smart_cart_accept_order', 'wpslash_skroutz_smart_cart_accept_order');
function wpslash_skroutz_smart_cart_accept_order() {
	check_ajax_referer('wpslash_skroutz_smart_cart_order_security', 'security');

	$api_token  = get_option( 'wc_settings_tab_wpslash_smart_cart_api_token', false );

	if (isset($_POST['order_id'])) {
		$order_id = intval( wp_unslash( $_POST['order_id'] ) );
		$order = wc_get_order($order_id);
		$skroutz_order = $order->get_meta('wpslash_skroutz_smart_cart_order_code');

		$pickup_location = '';
		$pickup_window = 0;
		$parcels = 1;
		if (isset($_POST['pickup_location'])) {
			$pickup_location = sanitize_text_field( wp_unslash( $_POST['pickup_location'] ) );
		}
		if (isset($_POST['pickup_window'])) {
			$pickup_window = intval( wp_unslash( $_POST['pickup_window'] ) );
		}
		if (isset($_POST['parcels'])) {
			$parcels = intval( wp_unslash( $_POST['parcels'] ) );
		}

		$post_data = array(
			'number_of_parcels' => $parcels,
			'pickup_location'   => $pickup_location,
			'pickup_window'     => $pickup_window,
		);

		$response = wp_remote_post( 'https://api.skroutz.gr/merchants/ecommerce/orders/' . $skroutz_order . '/accept', array(
			'timeout' => 30,
			'headers' => array(
				'Accept'        => 'application/vnd.skroutz+json; version=3.0',
				'Authorization' => 'Bearer ' . $api_token,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode( $post_data ),
		) );

		if ( is_wp_error( $response ) ) {
			echo 'cURL Error #:' . esc_html( $response->get_error_message() );
		} else {
			$body = wp_remote_retrieve_body( $response );
			$decoded_response = json_decode( $body, true );

			if ( is_array( $decoded_response ) && array_key_exists('success', $decoded_response) ) {
				if (true == $decoded_response['success']) {
					$status_after_acceptance = str_replace('wc-', '', get_option( 'wc_settings_tab_wpslash_smart_cart_accepted_status', 'wc-processing' ));
					$order->update_meta_data('wpslash_skroutz_smart_cart_order_accepted', 'yes' );
					$order->update_status($status_after_acceptance);
				}
			}
			echo wp_json_encode( $decoded_response );
		}
	}

	wp_die();
}

add_action('wp_ajax_wpslash_skroutz_smart_cart_refresh_shoporder', 'wpslash_skroutz_smart_cart_refresh_shoporder');
function wpslash_skroutz_smart_cart_refresh_shoporder() {
	check_ajax_referer('wpslash_skroutz_smart_cart_order_security', 'security');

	if (isset($_POST['order_id'])) {
		$order_id = intval( wp_unslash( $_POST['order_id'] ) );
		ob_start();
		wpslash_skroutz_smart_cart_shoporder($order_id);
		$html_data = ob_get_clean();

		$response = array( 'success'=>true, 'order_id'=>$order_id, 'html'=>$html_data );

		echo wp_json_encode( $response );
	}

	wp_die();
}
