<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	use Automattic\WooCommerce\Utilities\OrderUtil;

//add skroutz markatplace payment way
add_filter('woocommerce_payment_gateways', 'skroutz_add_gateway_class');
function skroutz_add_gateway_class($gateways) {
    $gateways[] = 'WC_Gateway_Skroutz_Marketplace';
    return $gateways;
}

add_action('plugins_loaded', 'skroutz_init_gateway_class');
function skroutz_init_gateway_class() {

    class WC_Gateway_Skroutz_Marketplace extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'dsdcskroutz';
            $this->has_fields = false;
            $this->method_title = __('Skroutz Marketplace', 'skroutz-marketplace-xml-for-woocommerce');
            $this->method_description = __('Πληρωμή μέσω Skroutz Marketplace', 'skroutz-marketplace-xml-for-woocommerce');

            // No settings, so disable the admin form
            $this->enabled = 'yes';
            $this->title = __('Skroutz Marketplace', 'skroutz-marketplace-xml-for-woocommerce');
        }

        public function is_available() {
            // Αυτός ο τρόπος πληρωμής χρησιμοποιείται μόνο μέσω custom κώδικα
            return false;
        }
    }
}

//αρχικό πλαγίν

function skroutz_smart_cart_order_process_order( $skroutz_order_data ) {
$unique_id = get_option( 'wc_settings_tab_wpslash_smart_cart_unique_id', 'id' );
$unique_id_custom_field = get_option( 'wc_settings_tab_wpslash_smart_cart_unique_id_custom_field', '' );

$order = array();
//$order["address"]["first_name"] = "Skroutz";
//$order["address"]["last_name"] = "Order";
$default_order_status = str_replace('wc-', '', get_option( 'wc_settings_tab_wpslash_smart_cart_not_accepted_status', 'wc-on-hold' ));



$order['address']['first_name'] = $skroutz_order_data['order']['customer']['first_name'];
$order['address']['last_name'] = $skroutz_order_data['order']['customer']['last_name'];
$order['address']['address_1'] = $skroutz_order_data['order']['customer']['address']['street_name'] . ' ' . $skroutz_order_data['order']['customer']['address']['street_number'];
$order['address']['city'] = $skroutz_order_data['order']['customer']['address']['city'];
$order['address']['postcode'] = $skroutz_order_data['order']['customer']['address']['zip'];
$order['skroutz_data']['code'] = $skroutz_order_data['order']['code'];
$order['order_status'] = $default_order_status;
$order['skroutz_data']['accept_options'] = $skroutz_order_data['order']['accept_options'];
$order['skroutz_data']['reject_options'] = $skroutz_order_data['order']['reject_options'];
$order['skroutz_data']['skroutz_line_items'] = $skroutz_order_data['order']['line_items'];

$order['skroutz_data']['fulfilled_by_skroutz'] = $skroutz_order_data['order']['fulfilled_by_skroutz'];
$order['skroutz_data']['pickup_window'] = $skroutz_order_data['order']['pickup_window'];
$order['skroutz_data']['pickup_window'] = $skroutz_order_data['order']['pickup_window'];
$order['skroutz_data']['expires_at'] = $skroutz_order_data['order']['expires_at'];
$order['skroutz_data']['courier'] = $skroutz_order_data['order']['courier'];
$order['skroutz_data']['state'] = $skroutz_order_data['order']['state'];

$order['skroutz_data']['courier_voucher'] = $skroutz_order_data['order']['courier_voucher'];
$order['skroutz_data']['courier_tracking_codes'] = $skroutz_order_data['order']['courier_tracking_codes'];
$order['skroutz_data']['courier_tracking_codes_imploded'] = implode(',', $skroutz_order_data['order']['courier_tracking_codes']);
$order['invoice'] = $skroutz_order_data['order']['invoice'];
$order['gift_wrap'] = $skroutz_order_data['order']['gift_wrap'];

$order['street_name'] = $skroutz_order_data['order']['customer']['address']['street_name'];
$order['street_number'] = $skroutz_order_data['order']['customer']['address']['street_number'];


	if (true ==$order['invoice']) {
	

		$order['invoice_company'] = $skroutz_order_data['order']['invoice_details']['company'];
		$order['invoice_profession'] = $skroutz_order_data['order']['invoice_details']['profession'];
		$order['invoice_vat'] = $skroutz_order_data['order']['invoice_details']['vat_number'];
		$order['invoice_doy'] = $skroutz_order_data['order']['invoice_details']['doy'];
		$order['invoice_address'] = $skroutz_order_data['order']['invoice_details']['address']['street_name'] . ' ' . $skroutz_order_data['order']['invoice_details']['address']['street_number'];
		
		$order['street_name']=$skroutz_order_data['order']['invoice_details']['address']['street_name'];
		$order['street_number']=$skroutz_order_data['order']['invoice_details']['address']['street_number'];
			
		$order['invoice_zip'] = $skroutz_order_data['order']['invoice_details']['address']['zip'];
		$order['invoice_city'] = $skroutz_order_data['order']['invoice_details']['address']['city'];
		$order['invoice_region'] = $skroutz_order_data['order']['invoice_details']['address']['region'];
		$order['invoice_vat_exclusion_requested'] = $skroutz_order_data['order']['invoice_details']['invoice_vat_exclusion_requested'];
		if (true == $order['invoice_vat_exclusion_requested']) {
			$order['invoice_vat_exclusion_id_type'] = $skroutz_order_data['order']['invoice_details']['vat_exclusion_representative']['id_type'];
			$order['invoice_vat_exclusion_id_number'] = $skroutz_order_data['order']['invoice_details']['vat_exclusion_representative']['id_number'];
			$order['invoice_vat_exclusion_otp'] = $skroutz_order_data['order']['invoice_details']['vat_exclusion_representative']['otp'];

		}
	

	}

$order['changes'] = $skroutz_order_data['changes'];


	foreach ($skroutz_order_data['order']['line_items'] as $item) {
		if ('id' == $unique_id) {

	
		 $product_id = $item['shop_uid'];

			if ('yes' == get_option('we_skroutz_xml_color_id_parent_id', null)) {
				if (strpos($product_id, '-') !== false) {
						  $product_id_exploded =  explode('-', $product_id);
						  $product_id =intval($product_id_exploded[0]);
						  $attribute_term_id = intval($product_id_exploded[1]);
						  $attribute_term  = get_term($attribute_term_id);
						  $item['size']['shop_value'] = $attribute_term->name;
				}
			}
		$product = wc_get_product($product_id);


			if ($product->is_type('variable')) {
				  $size_found = false;

				if (array_key_exists('size', $item)) {
					 $selected_size_attribute = $item['size']['shop_value'];

					$variations = $product->get_available_variations();

					//fix 1 variation variable issue
					if ( count($variations) === 1 ) {
						$variation_id = $variations[0]['variation_id'];
						$product_id = $variation_id;
					} else {


						foreach ($product->get_available_variations() as $child ) {
							foreach ($child['attributes'] as $key => $value ) {
								$taxonomy  = str_replace('attribute_', '', $key);
								$attr_name = get_taxonomy( $taxonomy )->labels->singular_name; // Attribute name
								$term_name = get_term_by( 'slug', $value, $taxonomy )->name; // Value name

								//fix first variation issue
								if (strtolower($term_name) === strtolower($selected_size_attribute) ) {

									$size_found = true;
									break;
								}

							}
							if ($size_found ) {
								$variation_id = $child['variation_id']; // Get and set the varition ID
								$product_id = $variation_id;
								break; // we stop the loop
							}
						}
			   
					}

				}
			}


		 $item_processed = array( 'id'=>$product_id, 'quantity'=> ( $item['quantity'] ), 'price'=>$item['unit_price'] );
		$order['items'][] = $item_processed ;


		}
		if ('mpn' ==  $unique_id) {
		 $product_id = wc_get_product_id_by_sku($item['shop_uid']);
		 $product = wc_get_product($product_id);
		 $args = array();
			if ($product->is_type('variable')) {
				$size_found = false;

				if (array_key_exists('size', $item)) {
					 $selected_size_attribute = $item['size']['shop_value'];

	   


					foreach ($product->get_available_variations() as $child ) {
						foreach ($child['attributes'] as $key => $value ) {
							$taxonomy  = str_replace('attribute_', '', $key);
							$attr_name = get_taxonomy( $taxonomy )->labels->singular_name; // Attribute name
							$term_name = get_term_by( 'slug', $value, $taxonomy )->name; // Value name


							if ( $term_name == $selected_size_attribute ) {

								$size_found = true;
								break;
							}

						}
						if ($size_found ) {
							$variation_id = $child['variation_id']; // Get and set the varition ID
							$product_id = $variation_id;
							break; // we stop the loop
						}
					}
			   


				}
			}


		$item_processed = array( 'id'=>$product_id, 'quantity'=> ( $item['quantity'] ), 'args'=> $args, 'price'=>$item['unit_price'] );
		$order['items'][] = $item_processed ;


		}


		if ('custom_field' ==  $unique_id) {
		 $product_id = skroutz_get_product_id_by_custom_field($item['shop_uid']);
		 $product = wc_get_product($product_id);
			if ($product) {
			
		
			$args = array();
				if ($product->is_type('variable')) {
					$size_found = false;

					if (array_key_exists('size', $item)) {
						$selected_size_attribute = $item['size']['shop_value'];

	   


						foreach ($product->get_available_variations() as $child ) {
							foreach ($child['attributes'] as $key => $value ) {
								  $taxonomy  = str_replace('attribute_', '', $key);
								  $attr_name = get_taxonomy( $taxonomy )->labels->singular_name; // Attribute name
								  $term_name = get_term_by( 'slug', $value, $taxonomy )->name; // Value name


								if ( $value == $selected_size_attribute ) {

								 $size_found = true;
								 break;
								}

							}
							if ($size_found ) {
								 $variation_id = $child['variation_id']; // Get and set the varition ID
								 $product_id = $variation_id;
								 break; // we stop the loop
							}
						}
			   


					}
				}


		   $item_processed = array( 'id'=>$product_id, 'quantity'=> ( $item['quantity'] ), 'args'=> $args );
		   $order['items'][] = $item_processed ;
			}

		}


	}
return $order;
}


function skroutz_smart_cart_create_order( $data ) {


$orders = array();
	if (class_exists('\Automattic\WooCommerce\Utilities\OrderUtil') &&   OrderUtil::custom_orders_table_usage_is_enabled()) {



		$orders = wc_get_orders(
		array(
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		'meta_query' => array(
			array(
								'key' => 'wpslash_skroutz_smart_cart_order_code',
								 'value' => $data['skroutz_data']['code'],

			),

		),
		'return'        => 'ids',
		)
	);


	} else {
		$orders = wc_get_orders( array( 'wpslash_skroutz_smart_cart_order_code' => $data['skroutz_data']['code'], 'return'        => 'ids' ) );
	}


	if (empty($orders)) {

   
	$order    = new WC_Order();

	$gateways = WC()->payment_gateways->get_available_payment_gateways();

	// Set Billing and Shipping adresses
		foreach ( array( 'billing_', 'shipping_' ) as $type ) {
			foreach ( $data['address'] as $key => $value ) {
				if ( 'shipping_' === $type && in_array( $key, array( 'email', 'phone' ) ) ) {
				continue;
				}

				$type_key = $type . $key;

				if ( is_callable( array( $order, "set_{$type_key}" ) ) ) {
					$order->{"set_{$type_key}"}( $value );
				}
			}
		}

	// Set created via and all order origin attributions to "Skroutz".
	$order->set_created_via( 'Skroutz' );
	$order->update_meta_data( '_created_via', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_origin', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_source_type', 'utm' );
	$order->update_meta_data( '_wc_order_attribution_utm_source', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_utm_medium', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_utm_campaign', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_utm_source_platform', 'Skroutz' );
	$order->update_meta_data( '_wc_order_attribution_referrer', 'https://www.skroutz.gr/' );
	$order->set_customer_id( $data['user_id'] );
	$order->set_currency( get_woocommerce_currency() );
	$order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
	$order->set_customer_note( isset( $data['order_comments'] ) ? $data['order_comments'] : '' );
	//here we set the payment method for skroutz marcetplace.

	$order->set_payment_method('dsdcskroutz' );
	$order->set_payment_method_title(__('Skroutz Marketplace', 'skroutz-marketplace-xml-for-woocommerce'));

	$calculate_taxes_for = array(
		'country'  => $data['address']['country'],
		'state'    => $data['address']['state'],
		'postcode' => $data['address']['postcode'],
		'city'     => $data['address']['city'],
	);

	// Line items
		foreach ( $data['items'] as $line_item ) {
	  
			$product = wc_get_product($line_item['id']);
			$product->set_price( $line_item['price']);

			$item_id = $order->add_product( $product, $line_item['quantity']);

			$item    = $order->get_item( $item_id, false );

			$item->calculate_taxes($calculate_taxes_for);
			$item->save();
		}



	// Set calculated totals
	$order->calculate_totals();
	

	$accepted =  'no';
		if ('accepted' == $data['skroutz_data']['state']) {
			$accepted = 'yes';


		}
		if ('dispatched' == $data['skroutz_data']['state']) {
			$accepted = 'yes';

		}


	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order', 'yes' );
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_accepted', $accepted  );
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier', $data['skroutz_data']['courier'] );
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_state', $data['skroutz_data']['state'] );

		if ($data['skroutz_data']['courier_tracking_codes']) {
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier_tracking_codes', $data['skroutz_data']['courier_tracking_codes']  );
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier_tracking_codes_imploded', implode(',', $data['skroutz_data']['courier_tracking_codes'])  );

		}


		if ($data['skroutz_data']['courier_voucher']) {
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier_voucher', $data['skroutz_data']['courier_voucher']  );

		}
		if ($data['gift_wrap']) {

			  $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_gift_wrap', 'yes');

		}

		if ($data['invoice']) {
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice', 'yes' );
			 
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_company', $data['invoice_company'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_profession', $data['invoice_profession'] );

			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_vat', $data['invoice_vat'] );

			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_doy', $data['invoice_doy'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_address', $data['invoice_address'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_zip', $data['invoice_zip'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_city', $data['invoice_city'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_region', $data['invoice_region'] );

			 $order->update_meta_data( '_billing_timologio', 'Y' );
			 
			
			 $order->update_meta_data( '_vatname', $data['invoice_company'] );
			 $order->update_meta_data( '_billing_store', $data['invoice_profession'] );

			 $order->update_meta_data( '_billing_vat', $data['invoice_vat'] );

			 $order->update_meta_data( '_billing_irs', $data['invoice_doy'] );
			 $order->update_meta_data( '_vataddress', $data['invoice_address']." ".$data['invoice_city']." ".$data['invoice_zip'] );

			 $order->update_meta_data( '_vat_zip_code', $data['invoice_zip'] );
			 $order->update_meta_data( '_vat_city', $data['invoice_city'] );

			 $order->update_meta_data( '_vat_street', $data['street_name'] );
			 $order->update_meta_data( '_vat_street_number', $data['street_number'] );


			if ($data['invoice_vat_exclusion_requested']) {



			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_requested', 'yes');
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_id_type', $data['invoice_vat_exclusion_id_type'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_id_number', $data['invoice_vat_exclusion_id_number'] );
			 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_otp', $data['invoice_vat_exclusion_otp'] );


				
			}

		}



	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_code', $data['skroutz_data']['code'] );
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_accept_options', json_encode($data['skroutz_data']['accept_options']) );    
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_reject_options', json_encode($data['skroutz_data']['reject_options']) );    
	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_line_items', json_encode($data['skroutz_data']['skorutz_line_items']) );    

	 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_expires_at', $data['skroutz_data']['expires_at'] );    


		if ( isset($data['order_status']) ) {
			$order->update_status($data['order_status']);
		}
	$order_id = $order->save();

	$skroutz_auto_accept = get_option('wc_settings_tab_wpslash_smart_cart_auto_accept', false);
		if ('yes' == $skroutz_auto_accept) {
	

		$skroutz_order = get_post_meta($order_id, 'wpslash_skroutz_smart_cart_order_code', true);
		$api_token  = get_option( 'wc_settings_tab_wpslash_smart_cart_api_token', false );

		$post_data = array(
			'number_of_parcels' => 1,
			'pickup_location'   => $data['skroutz_data']['accept_options']['pickup_location'][0]['id'],
			'pickup_window'     => intval( $data['skroutz_data']['accept_options']['pickup_window'][0]['id'] ),
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
		$after_accepted_status = str_replace('wc-', '', get_option( 'wc_settings_tab_wpslash_smart_cart_accepted_status', 'wc-processing' ));

		$order->update_status($after_accepted_status);
		$order->save();


		
		}



	} else {
		skroutz_smart_cart_update_order( $data );
	}
	
	// Returns the order ID
	return $order_id;
}


function skroutz_smart_cart_update_order( $data ) {


		/*  $orders = wc_get_orders( array( 'wpslash_skroutz_smart_cart_order_code' => $data['skroutz_data']['code'],     'return'        => 'ids' ) );
			$order_id = $orders[0];
			$order = wc_get_order($order_id);
			if ( $order ) {
				if ( empty( $order->get_created_via() ) || 'skroutz_smart_cart' === $order->get_created_via() ) {
					$order->set_created_via( 'Skroutz' );
					$order->update_meta_data( '_created_via', 'Skroutz' );
				}
				if ( 'Skroutz' !== $order->get_meta( '_wc_order_attribution_origin' ) ) {
					$order->update_meta_data( '_wc_order_attribution_origin', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_source_type', 'utm' );
					$order->update_meta_data( '_wc_order_attribution_utm_source', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_medium', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_campaign', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_source_platform', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_referrer', 'https://www.skroutz.gr/' );
				}
			}

			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_line_items', json_encode($data['skroutz_data']['skroutz_line_items']) );
			$order_id = $order->save();*/

	$data['changes']['state'] = array( 'new'=> $data['skroutz_data']['state'] );


	if (!empty($data['changes'])) {
		$orders = array();
		if (class_exists('\Automattic\WooCommerce\Utilities\OrderUtil') &&   OrderUtil::custom_orders_table_usage_is_enabled()) {



			$orders = wc_get_orders(
				array(
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query' => array(
						array(
							'key' => 'wpslash_skroutz_smart_cart_order_code',
							'value' => $data['skroutz_data']['code'],



						),

					),
					'return'        => 'ids',
				)
			);


		} else {
			$orders = wc_get_orders( array( 'wpslash_skroutz_smart_cart_order_code' => $data['skroutz_data']['code'], 'return'        => 'ids' ) );
		}

			$order_id = $orders[0];
			$order = wc_get_order($order_id);

			if ( $order ) {
				if ( empty( $order->get_created_via() ) || 'skroutz_smart_cart' === $order->get_created_via() ) {
					$order->set_created_via( 'Skroutz' );
					$order->update_meta_data( '_created_via', 'Skroutz' );
				}
				if ( 'Skroutz' !== $order->get_meta( '_wc_order_attribution_origin' ) ) {
					$order->update_meta_data( '_wc_order_attribution_origin', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_source_type', 'utm' );
					$order->update_meta_data( '_wc_order_attribution_utm_source', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_medium', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_campaign', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_utm_source_platform', 'Skroutz' );
					$order->update_meta_data( '_wc_order_attribution_referrer', 'https://www.skroutz.gr/' );
				}
			}

			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_line_items', json_encode($data['skroutz_data']['skroutz_line_items']) );

		foreach ($data['changes'] as $changed_key => $values) {

				$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_' . $changed_key, $values['new'] );

			if ('courier_tracking_codes' == $changed_key) {
				$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_' . $changed_key . '_imploded', implode(',', $values['new']) );
 
			}
			if ('state' == $changed_key) {
				if ('accepted' == $values['new']) {
					 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_accepted', 'yes' );

				}
				if ('dispatched' == $values['new']) {
					 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_accepted', 'yes' );
					 $order->update_status( 'completed' );

				}
				if ('cancelled' == $values['new']) {
						$order->update_status( 'cancelled' );


				}
				if ('rejected' == $values['new']) {
						$order->update_status( 'cancelled', esc_html_e('Order has been Rejected from Skroutz', 'skroutz-marketplace-xml-for-woocommerce') );


				}

					 $order->update_meta_data( 'wpslash_skroutz_smart_cart_order_state', $values['new'] );

 
			}
			if (is_array($values['new'])) {
				$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_' . $changed_key . '_imploded', implode(',', $values['new']) );
	
			}

		}

	   /*     if(!empty($data["skroutz_data"]["courier"]))
			{
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier', $data["skroutz_data"]["courier"] );

			}
			if(!empty($data["skroutz_data"]["courier_tracking_codes"]))
			{
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier_tracking_codes', $data["skroutz_data"]["courier_tracking_codes"] );

			}
			if(!empty($data["skroutz_data"]["courier_voucher"]))
			{
			$order->update_meta_data( 'wpslash_skroutz_smart_cart_order_courier_voucher', $data["skroutz_data"]["courier_voucher"] );

			}*/
			$order_id = $order->save();


	}
}



function skroutz_smart_cart_shoporder( $order_id ) {
		$order = wc_get_order($order_id);
		$order_status = $order->get_status();
		$skroutz_order = $order->get_meta( 'wpslash_skroutz_smart_cart_order', true);
		$skroutz_order_accepted = $order->get_meta( 'wpslash_skroutz_smart_cart_order_accepted', true);
		$skroutz_order_code = $order->get_meta( 'wpslash_skroutz_smart_cart_order_code', true);
		$skroutz_accept_options = json_decode($order->get_meta( 'wpslash_skroutz_smart_cart_order_accept_options', true), true);
		$skroutz_reject_options = json_decode($order->get_meta( 'wpslash_skroutz_smart_cart_order_reject_options', true), true);
		$skroutz_voucher = $order->get_meta( 'wpslash_skroutz_smart_cart_order_courier_voucher', true);
		$skroutz_courier= $order->get_meta( 'wpslash_skroutz_smart_cart_order_courier', true);
		$skroutz_courier_tracking_codes= $order->get_meta( 'wpslash_skroutz_smart_cart_order_courier_tracking_codes', true);
		$skroutz_invoice= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice', true);
		$skroutz_gift_wrap= $order->get_meta( 'wpslash_skroutz_smart_cart_order_gift_wrap', true);

		$order_state = $order->get_meta( 'wpslash_skroutz_smart_cart_order_state', true);

		$skroutz_invoice_company= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_company', true);
		$skroutz_invoice_profession= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_profession', true);
		$skroutz_invoice_vat= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_vat', true);
		$skroutz_invoice_doy= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_doy', true);
		$skroutz_invoice_address= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_address', true);
		$skroutz_invoice_zip= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_zip', true);
		$skroutz_invoice_city= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_city', true);
		$skroutz_invoice_region= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_region', true);

		$skroutz_invoice_vat_exclusion_requested= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_requested', true);
		$skroutz_invoice_vat_exclusion_id_type= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_id_type', true);
		$skroutz_invoice_vat_exclusion_id_number= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_id_number', true);
		$skroutz_invoice_vat_exclusion_otp= $order->get_meta( 'wpslash_skroutz_smart_cart_order_invoice_vat_exclusion_otp', true);
		$skroutz_line_items  = json_decode($order->get_meta( 'wpslash_skroutz_smart_cart_order_line_items', true), true);


	?>	
	<div class="row"><!-- START 1st ROW -->
		<div class="column">
			<span class="img-wrapper">
			  <img  style="height: 20px;width: 20px;" src="<?php echo esc_html(WPSSSC_DIR_URL . 'img/skroutz.png'); ?>" />
			   <a href="https://merchants.skroutz.gr/merchants/orders/<?php echo esc_html($skroutz_order_code); ?>" target="_blank"><?php echo esc_html($skroutz_order_code); ?></a>
			</span>
		</div>

	<?php if ( ( 'yes' == $skroutz_order_accepted ) || ( 'cancelled' == $order_status ) ) : ?>
			<div class="column">
				<span class="skroutz-status <?php echo esc_html($order_state); ?>">
					<?php echo esc_html(skroutz_smart_cart_states($order_state)); ?>
				</span>
			</div>
	</div><!-- END 1st ROW -->
	<?php endif; ?>

		<?php if ( ( 'yes' == $skroutz_order_accepted ) || ( 'cancelled' == $order_status ) ) : ?>
			<div class="row"><!-- START 2nd ROW -->
				<div class="column">
					<?php if (!empty($skroutz_courier)) : ?>
						<span class="skroutz-courier"><?php echo esc_html($skroutz_courier); ?></span>
					<?php endif; ?>
				</div>
				<div class="column">
					<?php if (!empty($skroutz_courier_tracking_codes)) : ?>
						<?php foreach ($skroutz_courier_tracking_codes as $tracking_code) : ?>
							<span class="skroutz-tracking-code"><?php echo esc_html($tracking_code); ?></span>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div><!-- END 2nd ROW -->
	
			<?php if (!empty($skroutz_voucher) && ( 'cancelled' != $order_status ) ) : ?>
				<div class="row"><!-- START 3rd ROW -->
					<div class="column half">
						<a class="button skroutz-print-voucher" target="_blank" href="<?php echo esc_html($skroutz_voucher); ?>"><?php echo esc_html__('Print Voucher', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>
					</div>
				</div><!-- END 3rd ROW -->
			<?php else : ?>
				<?php if ('cancelled' != $order_status ) : ?>
					<div class="row"><!-- START 3rd ROW -->
						<div class="column half">
							<span class="skroutz-waiting-voucher"><?php esc_html_e('Voucher is not ready yet', 'skroutz-marketplace-xml-for-woocommerce'); ?></span>
						</div>
					</div><!-- END 3rd ROW -->
				<?php endif; ?>
			<?php endif; ?>	

		<?php endif; ?>

		<?php if ('yes' == $skroutz_invoice) : ?>
			<div class="row"><!-- START 4th ROW -->
				<div class="column">
					<a class="button skroutz-toggle-invoice-order"><?php echo esc_html__('Invoice Details', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>

					<div class="skroutz_smart_cart_invoice_wrapper">
					
						<div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('Company Name', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_company); ?></span>
						</div>


						<div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('Profession', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_profession); ?></span>
						</div>

						 <div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('VAT', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_vat); ?></span>
						</div>


						 <div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('DOY', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_doy); ?></span>
						</div>

						   <div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('Address', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_address); ?></span>
						</div>

						<div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('Zip', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_zip); ?></span>
						</div>


						 <div class="skroutz_smart_cart_field_wrapper">
						<label><?php esc_html_e('City', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
						<span><?php echo esc_html($skroutz_invoice_city); ?></span>
						</div>

					</div>
				</div>
			</div><!-- END 4th ROW -->
		<?php endif; ?>
			
		<?php if ('yes' == $skroutz_gift_wrap) : ?>
			<div class="row"><!-- START 5th ROW -->
				<div class="column">
					<span class="skroutz-smart-cart-gift-wrap"><?php esc_html_e('Gift Wrap', 'skroutz-marketplace-xml-for-woocommerce'); ?></span>
				</div>
			</div><!-- END 5th ROW -->
		<?php endif; ?>	
		
		<?php if ('no' == $skroutz_order_accepted && ( 'cancelled' != $order_status )) : ?>
			<div class="row"><!-- START 6th ROW -->
				<div class="column">
			
					<a class="button skroutz-toggle-accept-order"><?php echo esc_html__('Accept Order', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>

						<div class="skroutz_accept_window">
							<label><?php esc_html_e('Pickup Location from', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
							<select class="skroutz-pickup-location">
					<?php foreach ($skroutz_accept_options['pickup_location'] as $location) : ?>
									<option value="<?php echo esc_html($location['id']); ?>"><?php echo esc_html($location['label']); ?></option>
								<?php endforeach; ?>
							</select>


								<label><?php esc_html_e('Pickup Time', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
							<select class="skroutz-pickup-window">
					<?php foreach ($skroutz_accept_options['pickup_window'] as $window) : ?>
										<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
										$exploded_array_time  = explode(' ', $window['label']);
										$time  = trim($exploded_array_time[0]);
										$exploded_array_date  = explode(',', $window['label']);
										
										$exploded_array_date_final = explode(' ', trim($exploded_array_date[1]));

										$date = trim($exploded_array_date_final[1]);
										$skroutz_format  = date_parse_from_format('d/m/y H:i', $date . ' ' . $time);
										$skroutz_time = date_i18n('Y-m-d H:i', strtotime($skroutz_format['year'] . '-' . $skroutz_format['month'] . '-' . $skroutz_format['day'] . ' ' . $skroutz_format['hour'] . ':' . $skroutz_format['minute']));

										$current_time = current_time('Y-m-d H:i');
										if (strtotime($skroutz_time) > strtotime($current_time) ) {
											?>
									<option value="<?php echo esc_html($window['id']); ?>"><?php echo esc_html($window['label']); ?></option>
								<?php 
										}
									endforeach; 
					?>
							</select>

							<a class="button skroutz-accept-order" order-id="<?php echo esc_html($order_id); ?>"><?php echo esc_html__('Accept', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>
	
						</div>

				</div>
				<div class="column">
					<a class="button skroutz-toggle-reject-order"><?php echo esc_html__('Reject Order', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>

						<div class="skroutz_reject_window">

							<?php foreach ($skroutz_line_items as $skroutz_item) : ?>

							<label><?php esc_html_e('Reject Reason', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
							<select class="skroutz-rejection-reason">
								<?php foreach ($skroutz_reject_options['line_item_rejection_reasons'] as $reject_option) : ?>
									<option value="<?php echo esc_html($reject_option['id']); ?>" req-q="<?php echo esc_html($reject_option['requires_available_quantity']); ?>"><?php echo esc_html($reject_option['label']); ?></option>
								<?php endforeach; ?>
							</select>


								<label><?php esc_html_e('Quantity', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
								<input type="number" class="skroutz-quantity" />
								<input type="hidden" class="skroutz-item-id" value="<?php echo esc_html($skroutz_item['id']); ?>" />

								<?php endforeach; ?>

							<a class="button skroutz-reject-order" order-id="<?php echo esc_html($order_id); ?>"><?php echo esc_html__('Reject', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>
	
						</div>
				</div>
			</div><!-- END 6th ROW -->
		<?php endif; ?>
		
<?php 
}

function skroutz_smart_cart_states( $state = '' ) {
		$all_states = array(
		'open' => esc_html__('Waiting Accept', 'skroutz-marketplace-xml-for-woocommerce'),
		'accepted' => esc_html__('Accepted', 'skroutz-marketplace-xml-for-woocommerce'),
		'rejected' => esc_html__('Rejected', 'skroutz-marketplace-xml-for-woocommerce'),
		'cancelled' => esc_html__('Cancelled', 'skroutz-marketplace-xml-for-woocommerce'),
		'expired' => esc_html__('Expired', 'skroutz-marketplace-xml-for-woocommerce'),
		'dispatched' => esc_html__('Dispatched', 'skroutz-marketplace-xml-for-woocommerce'),
		'delivered' => esc_html__('Delivered', 'skroutz-marketplace-xml-for-woocommerce'),
		'partially_returned' => esc_html__('Partially Returned', 'skroutz-marketplace-xml-for-woocommerce'),
		'returned' => esc_html__('Returned', 'skroutz-marketplace-xml-for-woocommerce'),
		'for_return' => esc_html__('For Return', 'skroutz-marketplace-xml-for-woocommerce'),

	);
		if (empty($state)) {
			  return $all_states;
  
		} else {
			return $all_states[$state];
		}
}

function skroutz_get_product_id_by_custom_field( $field ) {
	$unique_id_custom_field = get_option( 'wc_settings_tab_wpslash_smart_cart_unique_id_custom_field', '_sku' );


	$args = array(
	'post_type' => 'product',
	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	'meta_key' => $unique_id_custom_field,
	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
	'meta_value' => $field, //'meta_value' => array('yes'),
	'meta_compare' => '==', //'meta_compare' => 'NOT IN'
);
$products = wc_get_products($args);
	if (!empty($products)) {
		return $products[0]->get_id();
	}
}
return 0;
?>
