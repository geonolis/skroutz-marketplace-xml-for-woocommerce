<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('init', 'skroutz_smart_cart_header_check_for_skroutz_feed');
function skroutz_smart_cart_header_check_for_skroutz_feed() {
	$security = get_option('wpslash_skroutz_smart_cart_security', false );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if (isset($_GET['wpslash_skroutz_xml_feed']) && ( sanitize_text_field( wp_unslash( $_GET['wpslash_skroutz_xml_feed'] ) ) == $security )) {
		$current_page = get_option('wpslash_smart_cart_feed_xml_current_page', 0);
		$total_pages = get_option('wpslash_smart_cart_feed_xml_total_pages', 0);
		$feed_generation_is_running = get_option('wpslash_smart_cart_feed_xml_is_running', false);
		if ($total_pages > 0) {
			$percentage = floor(( ( $current_page * 100 ) / $total_pages ));
		} else {
			$percentage = 100;
		}
		$upload_dir = wp_upload_dir();

		if (file_exists($upload_dir['basedir'] . '/wpslash_skroutz_xml_feed.xml')) {
			header('Content-Type: application/xml;');
			header('Content-Length: ' . filesize($upload_dir['basedir'] . '/wpslash_skroutz_xml_feed.xml'));
			header('Location: ' . $upload_dir['baseurl'] . '/wpslash_skroutz_xml_feed.xml');
			exit();
		} else {
			if ( 'yes' === get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
				esc_html_e('XML Feed generation is turned off in settings.', 'skroutz-marketplace-xml-for-woocommerce');
				die();
			}
			skroutz_feed_builder_output();
			esc_html_e('Please wait while the XML file is getting created for first time....', 'skroutz-marketplace-xml-for-woocommerce');
			echo esc_html__('Percentage Completed:', 'skroutz-marketplace-xml-for-woocommerce') . ' ' . esc_html($percentage) . '%';
		}

		die();
	}
}

if ( 'yes' !== get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
	add_action( 'skroutz_smart_cart_feed_scheduled_task', 'skroutz_smart_cart_feed_scheduled_task' );
	add_action( 'skroutz_smart_cart_feed_scheduled_task_second', 'skroutz_smart_cart_feed_scheduled_task_second' );
	add_action( 'wp', 'skroutz_smart_cart_feed_auto_cron' );
}

function skroutz_smart_cart_feed_scheduled_task() {
	if ( 'yes' === get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task' );
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
		return;
	}

	skroutz_feed_builder_output();
}

function skroutz_smart_cart_feed_scheduled_task_second() {
	if ( 'yes' === get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task' );
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
		return;
	}

	skroutz_feed_builder_output();
}

function skroutz_smart_cart_feed_auto_cron() {
	if ( 'yes' === get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task' );
		return;
	}

	if (!wp_next_scheduled('skroutz_smart_cart_feed_scheduled_task')) {
		wp_schedule_event(time(), 'hourly', 'skroutz_smart_cart_feed_scheduled_task');
	}
}

function skroutz_smart_cart_add_seconds( $schedules ) {
	$schedules['every_five_seconds'] = array(
		'interval' => 5,
		'display'  => __( 'Every  5 Second', 'skroutz-marketplace-xml-for-woocommerce' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'skroutz_smart_cart_add_seconds' );
