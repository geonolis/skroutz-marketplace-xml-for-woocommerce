<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('manage_woocommerce_page_wc-orders_custom_column', 'wpslash_skroutz_smart_cart_list_column_buttons', 99, 2);
add_action('manage_shop_order_posts_custom_column', 'wpslash_skroutz_smart_cart_list_column_buttons', 99, 2);

function wpslash_skroutz_smart_cart_list_column_buttons( $column, $order_id ) {
	global $post, $woocommerce;
	$the_order = ( $order_id instanceof \WP_Post ) ? wc_get_order( $order_id->id ) : $order_id;
	if ( ! is_object( $the_order ) && is_numeric( $the_order ) ) {
		$the_order = wc_get_order( absint( $the_order ) );
	}
	$order_id = $the_order->get_id();
	$skroutz_order = $the_order->get_meta('wpslash_skroutz_smart_cart_order', true);

	switch ($column) {
		case 'wpslash_skroutz_smart_cart':
			if ($skroutz_order) {
				wpslash_skroutz_smart_cart_shoporder($order_id);
			}
			break;
	}
}

add_action('init', 'wpslash_smart_cart_header_check_for_skroutz_webhook');
function wpslash_smart_cart_header_check_for_skroutz_webhook() {
	$security = get_option('wpslash_skroutz_smart_cart_security', false );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if (isset($_GET['wpslash_skroutz_smart_cart']) && ( sanitize_text_field( wp_unslash( $_GET['wpslash_skroutz_smart_cart'] ) ) == $security )) {
		// Read the raw webhook stream
		$raw_posted_data = file_get_contents( 'php://input' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$data = json_decode($raw_posted_data, true);

		if ( is_array( $data ) && isset( $data['event_type'] ) ) {
			if ('new_order' == $data['event_type']) {
				$order_data  = wpslash_smart_cart_order_process_order($data);
				wpslash_smart_cart_create_order($order_data);
			}

			if ('order_updated' == $data['event_type']) {
				$order_data  = wpslash_smart_cart_order_process_order($data);
				wpslash_smart_cart_update_order($order_data);
			}
		}

		die();
	}
}

add_filter('manage_woocommerce_page_wc-orders_columns', 'wpslash_skroutz_smart_cart_shop_order_column', 12);
add_filter('manage_edit-shop_order_columns', 'wpslash_skroutz_smart_cart_shop_order_column', 12);

function wpslash_skroutz_smart_cart_shop_order_column( $columns ) {
	$columns['wpslash_skroutz_smart_cart'] = esc_html__('Skroutz', 'skroutz-marketplace-xml-for-woocommerce');
	return $columns;
}

function wpslash_skroutz_smart_cart_handle_courier_query_var( $query, $query_vars ) {
	if ( ! empty( $query_vars['wpslash_skroutz_smart_cart_order_code'] ) ) {
		$query['meta_query'][] = array(
			'key'   => 'wpslash_skroutz_smart_cart_order_code',
			'value' => esc_attr( $query_vars['wpslash_skroutz_smart_cart_order_code'] ),
		);
	}

	if ( ! empty( $query_vars['wpslash_skroutz_smart_cart_order_courier_tracking_codes'] ) ) {
		$query['meta_query'][] = array(
			'key'   => 'wpslash_skroutz_smart_cart_order_courier_tracking_codes',
			'value' => esc_attr( $query_vars['wpslash_skroutz_smart_cart_order_courier_tracking_codes'] ),
		);
	}
	if ( ! empty( $query_vars['wpslash_skroutz_smart_cart_order_courier_tracking_codes_imploded'] ) ) {
		$query['meta_query'][] = array(
			'key'   => 'wpslash_skroutz_smart_cart_order_courier_tracking_codes_imploded',
			'value' => esc_attr( $query_vars['wpslash_skroutz_smart_cart_order_courier_tracking_codes_imploded'] ),
		);
	}

	if ( ! empty( $query_vars['wpslash_skroutz_smart_cart_order_courier'] ) ) {
		$query['meta_query'][] = array(
			'key'   => 'wpslash_skroutz_smart_cart_order_courier',
			'value' => esc_attr( $query_vars['wpslash_skroutz_smart_cart_order_courier'] ),
		);
	}

	return $query;
}
add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'wpslash_skroutz_smart_cart_handle_courier_query_var', 10, 2 );

function wpslash_skroutz_smart_cart_additional_search_fields( $search_fields ) {
	$search_fields[] = 'wpslash_skroutz_smart_cart_order_courier';
	$search_fields[] = 'wpslash_skroutz_smart_cart_order_courier_tracking_codes_imploded';
	$search_fields[] = 'wpslash_skroutz_smart_cart_order_code';

	return $search_fields;
}
add_filter( 'woocommerce_shop_order_search_fields', 'wpslash_skroutz_smart_cart_additional_search_fields' );

/**
 * Filter WooCommerce Order Attribution origin label for Skroutz orders.
 *
 * @param string $label            The label format (e.g. "Source: %s").
 * @param string $source_type      The source type.
 * @param string $source           The raw source.
 * @param string $formatted_source The formatted source.
 * @return string
 */
function wpslash_skroutz_order_attribution_origin_label( $label, $source_type, $source, $formatted_source ) {
	if ( 0 === strcasecmp( (string) $source, 'skroutz' ) || 0 === strcasecmp( (string) $formatted_source, 'skroutz' ) || 0 === strcasecmp( (string) $source_type, 'skroutz' ) ) {
		return '%s';
	}
	return $label;
}
add_filter( 'wc_order_attribution_origin_label', 'wpslash_skroutz_order_attribution_origin_label', 10, 4 );

/**
 * Filter WooCommerce Order Attribution formatted source for Skroutz orders.
 *
 * @param string $formatted_source The formatted source.
 * @param string $raw_source       The raw source.
 * @return string
 */
function wpslash_skroutz_order_attribution_formatted_source( $formatted_source, $raw_source ) {
	if ( 0 === strcasecmp( (string) $raw_source, 'skroutz' ) ) {
		return 'Skroutz';
	}
	return $formatted_source;
}
add_filter( 'wc_order_attribution_origin_formatted_source', 'wpslash_skroutz_order_attribution_formatted_source', 10, 2 );

/**
 * Buffer admin orders table filters to inject "Skroutz" into #filter-by-created-via.
 *
 * @param string $order_type The order type.
 * @param string $which      The table nav position ('top' or 'bottom').
 */
function wpslash_skroutz_start_created_via_buffer( $order_type = '', $which = '' ) {
	ob_start();
}
add_action( 'woocommerce_order_list_table_restrict_manage_orders', 'wpslash_skroutz_start_created_via_buffer', 5, 2 );
add_action( 'restrict_manage_posts', 'wpslash_skroutz_start_created_via_buffer', 5, 2 );

/**
 * Inject "Skroutz" option into #filter-by-created-via dropdown in order list table.
 *
 * @param string $order_type The order type.
 * @param string $which      The table nav position ('top' or 'bottom').
 */
function wpslash_skroutz_end_created_via_buffer( $order_type = '', $which = '' ) {
	$html = ob_get_clean();
	if ( false === $html || '' === $html ) {
		return;
	}

	if ( false !== strpos( $html, 'filter-by-created-via' ) && false === strpos( $html, 'value="Skroutz"' ) ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$current_created_via = isset( $_GET['_created_via'] ) ? sanitize_text_field( wp_unslash( $_GET['_created_via'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$selected = selected( 'Skroutz', $current_created_via, false );
		$option   = "\n\t\t\t<option value=\"Skroutz\"" . $selected . '>' . esc_html__( 'Skroutz', 'skroutz-marketplace-xml-for-woocommerce' ) . '</option>';

		$html = preg_replace(
			'/(<select[^>]*id=[\x27"]filter-by-created-via[\x27"][^>]*>.*?)(\s*<\/select>)/s',
			'$1' . $option . '$2',
			$html
		);
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'woocommerce_order_list_table_restrict_manage_orders', 'wpslash_skroutz_end_created_via_buffer', 25, 2 );
add_action( 'restrict_manage_posts', 'wpslash_skroutz_end_created_via_buffer', 25, 2 );

