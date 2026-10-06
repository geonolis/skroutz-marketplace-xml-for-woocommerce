<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	add_action( 'woocommerce_settings_tabs', 'skroutz_smart_cart_add_settings_tab' );
// phpcs:disable WordPress.Security.NonceVerification.Recommended
function skroutz_smart_cart_add_settings_tab() {

		$current_tab =  '';
	if (isset($_GET['tab'])) {
		$current_tab = ( isset($_GET['tab']) == 'wpslash_skroutz_smart_cart' ) ? 'nav-tab-active' : '';

	}   
	//echo '<a href="admin.php?page=wc-settings&amp;tab=wpslash_skroutz_smart_cart" class="nav-tab ' . esc_html($current_tab) . '">' . esc_html__( 'Skroutz Smart Cart', 'skroutz-marketplace-xml-for-woocommerce' ) . '</a>';
}

add_filter( 'woocommerce_settings_tabs_array', 'skroutz_smart_cart_woocommerce_settings_tabs_array_filter' );


function skroutz_smart_cart_woocommerce_settings_tabs_array_filter( $array ) {

	$array['wpslash_skroutz_smart_cart'] = esc_html__( 'Skroutz Smart Cart', 'skroutz-marketplace-xml-for-woocommerce' );
	return $array;
}


	add_action( 'woocommerce_settings_wpslash_skroutz_smart_cart', 'skroutz_smart_cart_tab_content' );
function skroutz_smart_cart_tab_content() { 

	?>
		<ul class="subsubsub">
			<?php	
			if (!isset($_GET['section'])) {
				$_GET['section'] = 'smart_cart';

			} 

			?>
			<li><a href="admin.php?page=wc-settings&amp;tab=wpslash_skroutz_smart_cart&amp;section=smart_cart" class="
			<?php 
			if ('smart_cart' == $_GET['section']) {
echo 'current'; } 
			?>
			"><?php echo esc_html_e('Smart Cart Settings', 'skroutz-marketplace-xml-for-woocommerce'); ?></a> | </li>
			<li><a href="admin.php?page=wc-settings&amp;tab=wpslash_skroutz_smart_cart&amp;section=xml_feed" class="
			<?php 
			if ('xml_feed'== $_GET['section']) {
echo 'current'; } 
			?>
			"><?php echo esc_html_e('XML Feed', 'skroutz-marketplace-xml-for-woocommerce'); ?></a></li>

		</ul>
				<br class="clear">

		<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

		woocommerce_admin_fields( skroutz_smart_cart_tab_content_get_settings() );
		woocommerce_admin_fields( skroutz_smart_cart_xml_content_get_settings() );
}



function skroutz_smart_cart_tab_content_get_settings() {
	if ( ( isset($_GET['section']) && 'smart_cart' === $_GET['section'] ) || !isset($_GET['section']) ) {
		$settings = array(
		'section_title' => array(
		'name'     => __( 'Skroutz Smart Cart Settings', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => '',
		'id'       => 'wc_settings_tab_wpslash_smart_cart_title',
		),

		'webhookurl' => array(
		'name' => __( 'Webhook URL ', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'Copy and paste the above url to your Webhook URL inside your Skroutz Merchant Account', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_webhookurl',
		'custom_attributes' => array( 'readonly' => 'readonly' ),
		'value' =>get_site_url() . '/?wpslash_skroutz_smart_cart=' . get_option('wpslash_skroutz_smart_cart_security', '' ),

		),

		'api_token' => array(
		'name' => __( 'API Token ', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'The API token you will find under your Skroutz Merchant Account', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_api_token',
		),

		

		'unique_id' => array(
		'name' => esc_html__('Unique ID', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Field used as Unique ID on Skroutz. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_unique_id',
		'options' => array(
			'id' => esc_html__('Product ID (Default)', 'skroutz-marketplace-xml-for-woocommerce'),
			'mpn' => esc_html__('SKU', 'skroutz-marketplace-xml-for-woocommerce'),
			'custom_field' => esc_html__('Custom Field', 'skroutz-marketplace-xml-for-woocommerce'),

		),
		),

		'unique_id_custom_field' => array(
		'name' => esc_html__('Select Custom Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select the Custom Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_unique_id_custom_field',
		'options' => skroutz_smart_cart_product_meta_helper(),
		),


		'not_accepted_status' => array(
		'name' => esc_html__('Default Status for New Orders (Not Accepted)', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('The default Order status for not accepted orders.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_not_accepted_status',
		'options' => wc_get_order_statuses(),
		'default'=> 'wc-on-hold',

		),



		'accepted_status' => array(
		'name' => esc_html__('Default Status for Accepted Orders', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Status for Orders after acceptance', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_accepted_status',
		'options' => wc_get_order_statuses(),
		'default'=> 'wc-processing',
		),


		'auto_accept' => array(
		'name' => __( 'Automatic Order Acceptance', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__('Check this option if you want to automatically accept all orders from Skroutz Marketplace. The first available time will be selected as pickup time ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_auto_accept',
		'default'  => 'no',
		),
			
		'section_end' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_end',
		),
		);
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		return apply_filters( 'wc_settings_tab_skroutz_smart_cart_settings', apply_filters( 'wc_settings_tab_wpslash_smart_cart_settings', $settings ) );
	}
}


function skroutz_smart_cart_xml_content_get_settings() {
	if ( ( isset($_GET['section']) && 'xml_feed' === $_GET['section'] ) || !isset($_GET['section']) ) {



		$settings = array(
		'section_title' => array(
		'name'     => __( 'XML Feed Settings', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => '',
		'id'       => 'wc_settings_tab_wpslash_smart_cart_title',
		),

		'webhookurl' => array(
		'name' => __( 'Feed URL ', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'Copy and paste the above url to your Webhook URL inside your Skroutz Merchant Account', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_feedurl',
		'custom_attributes' => array( 'readonly' => 'readonly' ),
		'value' =>get_site_url() . '/?wpslash_skroutz_xml_feed=' . get_option('wpslash_skroutz_smart_cart_security', '' ),

		),

		'unique_id' => array(
		'name' => esc_html__('Unique ID', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Here you will have to select the field you are sending to Skroutz using the XML Feed as ID. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_unique_id',
		'options' => array(
			'id' => esc_html__('Product ID (Default)', 'skroutz-marketplace-xml-for-woocommerce'),
			'mpn' => esc_html__('SKU', 'skroutz-marketplace-xml-for-woocommerce'),
			'custom_field' => esc_html__('Custom Field', 'skroutz-marketplace-xml-for-woocommerce'),

		),
		),
	

		'unique_id_custom_field' => array(
		'name' => esc_html__('Custom Field for Unique ID', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select the Custom Field you want to use as Unique ID ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_unique_id_custom_field',
		'options' => skroutz_smart_cart_product_meta_helper(),
		),

					'brand_attribute' => array(
		'name' => esc_html__('Select Default Brand Taxonomy', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select the Taxonomy you are using for Brand.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_brand_tax',
		'options' => skroutz_smart_cart_brand_helper(),
		),

		'sku_field' => array(
		'name' => esc_html__('MPN Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select your SKU Field ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_sku_field',
		'options' => array(
			'mpn' => esc_html__('WooCommerce SKU', 'skroutz-marketplace-xml-for-woocommerce'),
			'attribute' => esc_html__('Attribute', 'skroutz-marketplace-xml-for-woocommerce'),

			'custom_field' => esc_html__('Custom Field', 'skroutz-marketplace-xml-for-woocommerce'),

		),
		),

			'sku_field_attribute_field' => array(
		'name' => esc_html__('Select Attibute', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select the Attribute you will use as SKU', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field',
		'options' => skroutz_smart_cart_attributes_helper(),
		),
			


		'sku_field_custom_field' => array(
		'name' => esc_html__('Custom Field for MPN Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select Custom field you will use as MPN', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field',
		'options' => skroutz_smart_cart_product_meta_helper(),
		'default'=> '',

		),


		'ean_field' => array(
		'name' => esc_html__('EAN Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select EAN field meta key. Its required for Electronic Stores. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_ean_field',
		'options' => skroutz_smart_cart_product_meta_helper(),
		'default'=> '',

		),

		'ean_categories' => array(
		'name' => esc_html__('Product Categories require EAN', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Product Categories that require EAN Code. Leave it empty if EAN is not required. Products without EAN on the Selected Categories will be not included in XML Feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_ean_required_cats',
		'options' => skroutz_smart_cart_categories_helper('product_cat'),

		),


		'size_atribute' => array(
		'name' => esc_html__('Size Attribute', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Size Attribute. Required for fashion/clothing stores. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_size_field',
		'options' => skroutz_smart_cart_attributes_helper(),
		'default'=> '',

		),

		'color_atribute' => array(
		'name' => esc_html__('Color Attribute', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Color Attribute. Required for fashion/clothing stores. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_color_field',
		'options' => skroutz_smart_cart_attributes_helper(),
		'default'=> '',

		),


		'default_availability' => array(
		'name' => esc_html__('Default Availability for in Stock Products', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('The default Product Availability for inStock Products', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_default_availability_in_stock',
		'options' => skroutz_smart_cart_get_availabilities(),
		'default'=> 'instock',

		),

		'backorder_availability' => array(
		'name' => esc_html__('Default Availability for BackOrder Products', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('The default Product Availability for BackOrder Products', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_default_availability_backorder',
		'options' => skroutz_smart_cart_get_availabilities(),
		'default'=> 'backorder',

		),
		'overwrite_availability' => array(
		'name' => esc_html__('Overwrite Availability ', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select an attribute you are using as Availability Status for Skroutz XML Feed. This will overwrite the default availability you have set for Skroutz.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_overwrite_availability',
		'options' => skroutz_smart_cart_attributes_helper(),
		'default'=> '',

		),



		'section_end_initial' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_end_first_part',
		),

				'section_start_shipping' => array(
		'title'     => __( 'Shipping', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => esc_html__('Shipping Information. Use this section only if all of your products the weight field completed. Othwerise the default shipping rules you will set om Skroutz Merchant Account will take place', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'       => 'wc_settings_tab_wpslash_smart_cart_feed_shipping_title',
		),

				'shipping_cost_up_to_2kg' => array(
		'name' => __( 'Shipping Cost (Up to 2KG) ', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'Shipping Cost for products up to 2kg', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_feed_shipping_up_to_2kg',
	),
						'free_shipping_for_orders_over' => array(
		'name' => __( 'Free Shipping for Orders Over', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'Free Shipping for Products over the specified amount', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_feed_free_shipping_over',
	),
		'shipping_cost_additional_kg' => array(
		'name' => __( 'Shipping Cost (Additional KG) ', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'text',
		'desc' => __( 'Shipping Cost for every additional KG', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_feed_shipping_additional_kg_cost',
	),

				'section_end_shipping' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_shipping_title_end',
		),

		'section_inclusions_title' => array(
		'title'     => __( 'Inclusions', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => esc_html__('Select  the Categories, Brands, Tags where  products will be included. To include them all, leave all options empty.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'       => 'wc_settings_tab_wpslash_smart_cart_inclusions_title',
		),

			'include_categories' => array(
		'name' => esc_html__('Include specific categories from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Product Categories from  products you want to be included in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_included_cats',
		'options' => skroutz_smart_cart_categories_helper('product_cat'),
		//'default'=> 'instock'

		),


			'include_tags' => array(
		'name' => esc_html__('Include products with specific tag from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Tags from products you want to be included in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_included_tags',
		'options' => skroutz_smart_cart_categories_helper('product_tag'),
		//'default'=> 'instock'

		),


	


		'include_brands' => array(
		'name' => esc_html__('Include products with a Specific Brand from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Brands from products you want to be included in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_included_brands',
		'options' => skroutz_smart_cart_categories_helper(get_option('wc_settings_tab_wpslash_smart_cart_feed_brand_tax', 'notselectedbrand')),
		),

		'section_end_inclusions' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_end_inclusions',
		),


		'section_exclusions_title' => array(
		'title'     => __( 'Exclusions', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => esc_html__("Exclude Products based on the following options. Leave the below fields if you don't want to apply any exclusions", 'skroutz-marketplace-xml-for-woocommerce'),
		'id'       => 'wc_settings_tab_wpslash_smart_cart_exlusions_title',
		),

			'exclude_categories' => array(
		'name' => esc_html__('Exclude specific categories from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Product Categories you want to not appear in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_exluded_cats',
		'options' => skroutz_smart_cart_categories_helper('product_cat'),

		),


			'exclude_tags' => array(
		'name' => esc_html__('Exclude products with specific tag from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Tags you want to not appear in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_exluded_tags',
		'options' => skroutz_smart_cart_categories_helper('product_tag'),

		),


	


		'excluded_brands' => array(
		'name' => esc_html__('Exclude products with a Specific Brand from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'multiselect',
		'desc' => esc_html__('Select Brands from products you want to not be included in feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_excluded_brands',
		'options' => skroutz_smart_cart_categories_helper(get_option('wc_settings_tab_wpslash_smart_cart_feed_brand_tax', 'notselectedbrand')),
		),

		'excluded_backorder' => array(
		'name' => __( 'Exclude Backorder Items', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__('This option will exclude any products on Backorder from feed', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_feed_excluded_backorder_items',
		'default'  => 'no',
		),

			'section_end_exclusions' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_end_exclusions',
		),
			'section_generation_title' => array(
		'title'     => __( 'Feed Generation', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => esc_html__('Feed Generation Settings', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'       => 'wc_settings_tab_wpslash_smart_cart_general_title',
		),

		'disable_xml_update' => array(
		'name' => __( 'Turn off XML Update', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__( 'Check this option to turn off XML feed generation and automatic updates.', 'skroutz-marketplace-xml-for-woocommerce' ),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_disable_xml_update',
		'default'  => 'no',
		),

			'feed_interval' => array(
		'name' => esc_html__('Generate XML Feed Every', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('How often you want the XML Feed to be regenerated', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_generation_interval',
		'options' => array(
			'60'=>esc_html__('1 Hour', 'skroutz-marketplace-xml-for-woocommerce'),
			'120'=>esc_html__('2 Hours', 'skroutz-marketplace-xml-for-woocommerce'),
			'360'=>esc_html__('6 Hours', 'skroutz-marketplace-xml-for-woocommerce'),
			'720'=>esc_html__('12 Hours', 'skroutz-marketplace-xml-for-woocommerce'),
			'1440'=>esc_html__('24 Hours', 'skroutz-marketplace-xml-for-woocommerce'),

			),
			'default'=> '60',
		),

			'feed_batch_size' => array(
		'name' => esc_html__('Batch Size', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('This is have to do with the feed generation speed and your website server resources. We are suggesting not increasing it.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_feed_batch_size',
		'options' => array(
			'50'=>esc_html__('50 products/batch', 'skroutz-marketplace-xml-for-woocommerce'),
			'100'=>esc_html__('100 products/batch', 'skroutz-marketplace-xml-for-woocommerce'),
			'150'=>esc_html__('150 products/batch', 'skroutz-marketplace-xml-for-woocommerce'),
			'200'=>esc_html__('200 products/batch', 'skroutz-marketplace-xml-for-woocommerce'),
			'300'=>esc_html__('300 products/batch', 'skroutz-marketplace-xml-for-woocommerce'),

			),
			'default'=> '100',
		),



/*
		'auto_accept' => array(
		'name' => __( 'Automatic Order Acceptance', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__('Check this option if you want to automatically accept all orders from Skroutz Marketplace. The first available time will be selected as pickup time ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_auto_accept',
		'default'  => 'no'
		),*/
			
		'section_end' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_end',
		),


		'section_tweaks_title' => array(
		'title'     => __( 'Tweaks', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type'     => 'title',
		'desc'     => esc_html__('Advanced Tweaks for Specific Circumstances. Please do not make changes on these settings if is not necessary.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'       => 'wc_settings_tab_wpslash_smart_cart_tweaks_title',
		),

		'alltributes_on_title' => array(
		'name' => esc_html__('Add Attributes on Title', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('This option will populate automatatically the selected attributes on the end of XML Product title ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_attributes_on_title',
		'options' => array(
			'no'=>esc_html__('No', 'skroutz-marketplace-xml-for-woocommerce'),
			'size_color'=>esc_html__('Size and Color Attributes', 'skroutz-marketplace-xml-for-woocommerce'),
			'all_attributes'=>esc_html__('All Attributes', 'skroutz-marketplace-xml-for-woocommerce'),

			),
			'default'=> 'yes',
		),


			'split_color_variations' => array(
		'name' => __( 'Split Colour Variations', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__('Skroutz normally requires each product to have a signle colour. In case you are using colours in variations , enabling this feature, will automatically split the product to the number of available colours and include them in XML feed as separated products. Each link will point directly to the colour variation so, be sure that you are also using different images per colour variation', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_split_color_variations',
		'default'  => 'no',
		),



		'alternate_image' => array(
		'name' => __( 'Alternate Skroutz Image', 'skroutz-marketplace-xml-for-woocommerce' ),
		'type' => 'checkbox',
		'desc' => esc_html__('Skroutz requires all images το have a white/transparent background. Check this option if you want to use a different image for XML Feed. ', 'skroutz-marketplace-xml-for-woocommerce'),
		'id'   => 'wc_settings_tab_wpslash_smart_cart_alternate_image',
		'default'  => 'no',
		),


		



			
		'alternate_image_create_field' => array(
		'name' => esc_html__('Alternate Image Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select if you want to add a new image field where you can assign the transparent/white background product image or if you already using an other custom field.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_alternate_image_create_field',
		'options' => array(
			'yes'=>esc_html__('Create Field', 'skroutz-marketplace-xml-for-woocommerce'),
			'no'=>esc_html__('Already using an other field', 'skroutz-marketplace-xml-for-woocommerce'),

			),
			'default'=> 'yes',
		),



		'alternate_image_custom_field' => array(
		'name' => esc_html__('Custom Image Field', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select if you want to add a new image field where you can assign the transparent/white background product image or if you already using an other custom field.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field',
		'options' => skroutz_smart_cart_product_meta_helper(),
		'default'=> '',
		),

			'alternate_image_custom_field_variations' => array(
		'name' => esc_html__('Custom Image Field for Variations', 'skroutz-marketplace-xml-for-woocommerce'),
		'type' => 'select',
		'desc' => esc_html__('Select if you want to add a new image field where you can assign the transparent/white background product image or if you already using an other custom field.', 'skroutz-marketplace-xml-for-woocommerce'),
		'id' => 'wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations',
		'options' => skroutz_smart_cart_product_variations_meta_helper(),
		'default'=> '',
		),
			'section_tweaks_end' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_skroutz_smart_cart_section_tweaks_end',
		),
		);
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		return apply_filters( 'wc_settings_tab_skroutz_smart_cart_settings', apply_filters( 'wc_settings_tab_wpslash_smart_cart_settings', $settings ) );
	}
}


	add_action('woocommerce_settings_save_wpslash_skroutz_smart_cart', 'skroutz_save_smart_cart_settings');

function skroutz_save_smart_cart_settings() {

	if ( ( isset($_GET['section']) && 'smart_cart' === $_GET['section'] ) || !isset($_GET['section']) ) {
		woocommerce_update_options( skroutz_smart_cart_tab_content_get_settings() );
	}
	if ( ( isset($_GET['section']) && 'xml_feed' === $_GET['section'] ) || !isset($_GET['section']) ) {
		woocommerce_update_options( skroutz_smart_cart_xml_content_get_settings() );
	}

	$disable_xml_update = get_option('wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no');

	if ( 'yes' === $disable_xml_update ) {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task' );
		wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task_second' );
		wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task' );
		update_option('wpslash_smart_cart_feed_xml_is_running', false);
		update_option('wpslash_smart_cart_feed_xml_processing_now', false);
		update_option('wpslash_smart_cart_feed_xml_total_pages', 0);
		update_option('wpslash_smart_cart_feed_xml_current_page', 0);
	} else {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
		wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task_second' );
		update_option('wpslash_smart_cart_feed_xml_is_running', false);
		update_option('wpslash_smart_cart_feed_xml_total_pages', 0);
		update_option('wpslash_smart_cart_feed_xml_current_page', 0);
		wp_schedule_event(time(), 'every_five_seconds', 'skroutz_smart_cart_feed_scheduled_task_second');
		$upload_dir = wp_upload_dir();
		if ( file_exists( $upload_dir['basedir'] . '/tempskroutz.xml' ) ) {
			wp_delete_file($upload_dir['basedir'] . '/tempskroutz.xml');
		}
	}
}
// phpcs:enable WordPress.Security.NonceVerification.Recommended

?>
