<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function skroutz_smart_cart_enqueue_styles( $hook ) {

	global $typenow;

	if ( ( 'shop_order' === $typenow ) || ( 'woocommerce_page_wc-orders' === $typenow ) ) {

		wp_enqueue_style('skroutz-smart-cart-css', WPSSSC_DIR_URL . '/css/main.css', array(), '1.1.0', 'all');
		wp_enqueue_script('skroutz-smart-cart-js', WPSSSC_DIR_URL . '/js/main.js', array( 'jquery' ), '0.1.0', true);
		wp_localize_script( 'skroutz-smart-cart-js', 'skroutz_smart_cart_order_obj',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce('skroutz_smart_cart_order_security'),
			)
		);
	}

	if ('product' === $typenow) {

		wp_enqueue_style('skroutz-smart-cart-product-css', WPSSSC_DIR_URL . '/css/product.css', array(), '0.1.0', 'all');
		wp_enqueue_script('skroutz-smart-cart-product-js', WPSSSC_DIR_URL . '/js/product.js', array( 'jquery' ), '0.1.0', true);
		wp_localize_script( 'skroutz-smart-cart-product-js', 'skroutz_smart_cart_product_obj',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce('skroutz_smart_cart_order_security'),
				'title' => __( 'Choose or Upload Media', 'skroutz-marketplace-xml-for-woocommerce' ),
				'button' => __( 'Use this media', 'skroutz-marketplace-xml-for-woocommerce' ),
				'remove'=> __( 'Remove Image', 'skroutz-marketplace-xml-for-woocommerce' ),
			)
		);
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ('woocommerce_page_wc-settings' === $hook && isset($_GET['tab']) && ( 'wpslash_skroutz_smart_cart' === $_GET['tab'] )) {
		wp_register_script( 'skroutz-smart-cart-wc-settings', WPSSSC_DIR_URL . 'js/settings.js', array( 'jquery', 'select2' ), '2.0.0', true );
		wp_enqueue_script( 'skroutz-smart-cart-wc-settings' );
	}
}
add_action( 'admin_enqueue_scripts', 'skroutz_smart_cart_enqueue_styles', 10, 1 );

function skroutz_smart_cart_categories_helper( $taxonomy ) {
	if ('notselectedbrand' == $taxonomy) {
		return array( '0'=>esc_html__('Select a Brand Taxonomy First', 'skroutz-marketplace-xml-for-woocommerce') );
	}
	$all_cats = array();
	$orderby      = 'name';  
	$show_count   = 0;      // 1 for yes, 0 for no
	$pad_counts   = 0;      // 1 for yes, 0 for no
	$hierarchical = 1;      // 1 for yes, 0 for no  
	$title        = '';  
	$empty        = 0;

	$args = array(
		'taxonomy'     => $taxonomy,
		'orderby'      => $orderby,
		'show_count'   => $show_count,
		'pad_counts'   => $pad_counts,
		'hierarchical' => $hierarchical,
		'title_li'     => $title,
		'hide_empty'   => $empty,
	);
	$all_categories = get_categories( $args );
	foreach ($all_categories as $cat) {
		if (0 == $cat->category_parent) {
			$category_id = $cat->term_id;       
			$all_cats[$category_id] = $cat->name;

			$args2 = array(
				'taxonomy'     => $taxonomy,
				'child_of'     => 0,
				'parent'       => $category_id,
				'orderby'      => $orderby,
				'show_count'   => $show_count,
				'pad_counts'   => $pad_counts,
				'hierarchical' => $hierarchical,
				'title_li'     => $title,
				'hide_empty'   => $empty,
			);
			$sub_cats = get_categories( $args2 );
			if ($sub_cats) {
				foreach ($sub_cats as $sub_category) {
					$category_id = $sub_category->term_id;       
					$all_cats[$category_id] = '->' . $sub_category->name;
				}   
			}
		}       
	}

	return $all_cats;
}

function skroutz_smart_cart_attributes_helper() {
	$all_cats = array();
	$attributes =  wc_get_attribute_taxonomies();
	$all_cats[''] = __( 'None', 'skroutz-marketplace-xml-for-woocommerce' );

	if ($attributes) {
		foreach ( $attributes as $attribute ) {
			$all_cats['pa_' . $attribute->attribute_name] = $attribute->attribute_label;
		}
	}         

	return $all_cats;
}

function skroutz_smart_cart_brand_helper() {
	$all_tax = array();
	$taxonomies = get_object_taxonomies( 'product', 'objects' );
	foreach ($taxonomies as $taxonomy) {
		$all_tax[$taxonomy->name] = $taxonomy->label . ' (' . $taxonomy->name . ')';
	}

	return $all_tax;
}

function skroutz_smart_cart_product_meta_helper() {
	$all_keys = array( ''=> __( 'None', 'skroutz-marketplace-xml-for-woocommerce' ) );
	$args = array( 'post_type' => 'product', 'posts_per_page' => 1, 'post_status'=>'publish' );
	$query = new WP_Query( $args );
	if (!empty($query->posts)) {
		$product_id = $query->posts[0]->ID;
		$post_meta = get_post_meta($product_id);

		$keys = array_keys( $post_meta );
		$keys = array_combine($keys, $keys);
		$keys = $all_keys + $keys;
		return $keys;
	}

	return $all_keys;
}

function skroutz_smart_cart_product_variations_meta_helper() {
	$all_keys = array( ''=> __( 'None', 'skroutz-marketplace-xml-for-woocommerce' ) );

	$args = array( 'post_type' => 'product_variation', 'posts_per_page' => 1, 'post_status'=>'publish' );
	$query = new WP_Query( $args );
	if (!empty($query->posts)) {
		$product_id = $query->posts[0]->ID;
		$post_meta = get_post_meta($product_id);

		$keys = array_keys( $post_meta );
		$keys = array_combine($keys, $keys);
		$keys = $all_keys + $keys;

		return $keys;
	}

	return $all_keys;
}

function skroutz_smart_cart_get_availabilities( $key = '' ) {
	$availabilties = array(
		'instock'   => __( 'Παράδοση 1 - 3 Εργάσιμες', 'skroutz-marketplace-xml-for-woocommerce' ),
		'backorder' => __( 'Παράδοση 4- 10 Εργάσιμες', 'skroutz-marketplace-xml-for-woocommerce' ),
		'ondemand'  => __( 'Παράδοση έως 30 Εργάσιμες', 'skroutz-marketplace-xml-for-woocommerce' ),
		'instant'   => __( 'Άμεσα διαθέσιμο', 'skroutz-marketplace-xml-for-woocommerce' ),
	);

	if (empty($key)) {
		return $availabilties;
	} else {
		return $availabilties[$key];
	}
}
