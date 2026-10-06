<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function skroutz_feed_builder_output() {

	if ( 'yes' === get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
		wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task' );
		wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task_second' );
		wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task' );
		update_option( 'wpslash_smart_cart_feed_xml_is_running', false );
		update_option( 'wpslash_smart_cart_feed_xml_processing_now', false );
		return;
	}

	$current_page =  get_option('wpslash_smart_cart_feed_xml_current_page', 0);
	$total_pages =  get_option('wpslash_smart_cart_feed_xml_total_pages', 0);
	$upload_dir = wp_upload_dir();



	$feed_generation_is_running =  get_option('wpslash_smart_cart_feed_xml_is_running', false);
	$feed_processing_now =  get_option('wpslash_smart_cart_feed_xml_processing_now', false);


/* $current_page =  0;
$total_pages =  0;
$feed_generation_is_running = false;

$feed_processing_now =  false;*/


$split_color_variations  = get_option('wc_settings_tab_wpslash_smart_cart_split_color_variations', 'no');  
$all_availabilties = skroutz_smart_cart_get_availabilities();

$alternate_skroutz_image  = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image', 'no');  
$alternate_create_field  = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image_create_field', 'yes');  

$alternate_skroutz_image_field = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field', '');  
$alternate_skroutz_image_field_variations = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations', '');  

$attributes_on_title = get_option('wc_settings_tab_wpslash_smart_cart_attributes_on_title', 'no');  
$overwrite_availability = get_option('wc_settings_tab_wpslash_smart_cart_feed_overwrite_availability', '');  

$shipping_up_to_2kg = get_option('wc_settings_tab_wpslash_smart_cart_feed_shipping_up_to_2kg', '');  
$free_shipping_over = get_option('wc_settings_tab_wpslash_smart_cart_feed_free_shipping_over', '');  
$shipping_additional_kg_cost = get_option('wc_settings_tab_wpslash_smart_cart_feed_shipping_additional_kg_cost', '');  


	if (!$feed_processing_now) {

		update_option('wpslash_smart_cart_feed_xml_processing_now', true);

		$write_or_append = 'a';
		if (!$feed_generation_is_running) {
			$write_or_append = 'w';
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$handle = fopen($upload_dir['basedir'] . '/tempskroutz.xml', $write_or_append);



		$unique_id_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_unique_id', 'id');
		$unique_id_custom_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_unique_id_custom_field', '');


		$ean_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_ean_field', '');
		$sku_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_sku_field', 'sku');
		$sku_custom_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field', '_sku');
		$sku_attribute_field = get_option('wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field', '_sku');

		$ean_required_cats = get_option('wc_settings_tab_wpslash_smart_cart_feed_ean_required_cats', array());
		$size_fields = get_option('wc_settings_tab_wpslash_smart_cart_feed_size_field', array());
		$color_fields = get_option('wc_settings_tab_wpslash_smart_cart_feed_color_field', array());
		$default_instock_availability = get_option('wc_settings_tab_wpslash_smart_cart_feed_default_availability_in_stock', '');
		$default_backorder_availability = get_option('wc_settings_tab_wpslash_smart_cart_feed_default_availability_backorder', '');

		$brand_taxonomy = get_option('wc_settings_tab_wpslash_smart_cart_feed_brand_tax', '');

		$included_cats = get_option('wc_settings_tab_wpslash_smart_cart_feed_included_cats', array());
		$included_brands = get_option('wc_settings_tab_wpslash_smart_cart_feed_included_brands', array());
		$included_tags = get_option('wc_settings_tab_wpslash_smart_cart_feed_included_tags', array());

		$excluded_cats = get_option('wc_settings_tab_wpslash_smart_cart_feed_exluded_cats', array());
		$excluded_brands = get_option('wc_settings_tab_wpslash_smart_cart_feed_excluded_brands', array());
		$excluded_tags = get_option('wc_settings_tab_wpslash_smart_cart_feed_excluded_tags', array());
		$excluded_backorder_items  = get_option('wc_settings_tab_wpslash_smart_cart_feed_excluded_backorder_items', 'no');  



		$excluded_cats_int = array();
		$excluded_brands_int = array();
		$excluded_tags_int = array();


		$included_cats_int = array();
		$included_brands_int = array();
		$included_tags_int = array();

		if (!empty($excluded_cats)) {
			foreach ($excluded_cats as $cat) {
				$excluded_cats_int[] = intval($cat);

			}
		}

		if (!empty($excluded_brands)) {
			foreach ($excluded_brands as $cat) {
				$excluded_brands_int[] = intval($cat);

			}
		}

		if (!empty($excluded_tags)) {
			foreach ($excluded_tags as $cat) {
				$excluded_tags_int[] = intval($cat);

			}
		}



		if (!empty($included_cats)) {
			foreach ($included_cats as $cat) {
				$included_cats_int[] = intval($cat);

			}
		}

		if (!empty($included_tags)) {
			foreach ($included_tags as $cat) {
				$included_tags_int[] = intval($cat);

			}
		}

		if (!empty($included_brands)) {
			foreach ($included_brands as $cat) {
				$included_brands_int[] = intval($cat);

			}
		}




		if (!$feed_generation_is_running) {

			$feed_generation_is_running = true;

			if ($feed_generation_is_running && ( !wp_next_scheduled('skroutz_smart_cart_feed_scheduled_task_second') )) {
				if ( 'yes' !== get_option( 'wc_settings_tab_wpslash_smart_cart_disable_xml_update', 'no' ) ) {
					wp_schedule_event(time(), 'every_five_seconds', 'skroutz_smart_cart_feed_scheduled_task_second');
				}
			}



			update_option('wpslash_smart_cart_feed_xml_is_running', true);

	// Echo out all the details


			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fputs
			fputs ($handle, '<?xml version="1.0" encoding="UTF-8"?>
			<mywebstore>
			<products>');


		}

		$current_page++;
		update_option('wpslash_smart_cart_feed_xml_current_page', $current_page);


		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		$args = array( 'post_type' => 'product', 'posts_per_page' => 100, 'post_status'=>'publish', 'paged'=>$current_page, 'tax_query'=>array() );



		if (!empty($included_cats_int)) {
			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] = 
			array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $included_cats_int,
	//'operator' => 'IN',
			);

		}

		if (!empty($included_tags_int)) {
			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] = 
			array(
			'taxonomy' => 'product_tag',
			'field'    => 'term_id',
			'terms'    => $included_tags_int,
	//'operator' => 'IN',
			);

		}

		if (!empty($included_brands_int)) {
			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] = 
			array(
			'taxonomy' => $brand_taxonomy,
			'field'    => 'term_id',
			'terms'    => $included_brands_int,
	//'operator' => 'IN',
			);

		}

		if (!empty($excluded_cats_int)) {
			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] = 
			array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $excluded_cats_int,
			'operator' => 'NOT IN',
			);

		}




		if (!empty($excluded_brands_int)) {


			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] =
			array(
			'taxonomy' => $brand_taxonomy,
			'field'    => 'term_id',
			'terms'    => $excluded_brands_int,
			'operator' => 'NOT IN',
			);

		}


		if (!empty($excluded_tags_int)) {


			if (!is_array($args['tax_query'])) {
				$args['tax_query'] = array();   
			}

			$args['tax_query'][] =
			array(
			'taxonomy' => 'product_tag',
			'field'    => 'term_id',
			'terms'    => $excluded_tags_int,
			'operator' => 'NOT IN',
			);

		}
		// phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query




		$loop = new WP_Query( $args );

		$total_pages = $loop->max_num_pages;
		update_option( 'wpslash_smart_cart_feed_xml_total_pages' , $total_pages );


		if ( $loop->have_posts() ) :

			while ( $loop->have_posts() ) :
				$loop->the_post(); 
				global $product; 
				global $woocommerce;
				$unique_id = '';
				$brand      ='';
				$id_clean =$product->get_id();
				$mpn=$product->get_sku();
				$include_in_feed = true;
				$size = '';
				$shipping = null;
				$weight = $product->get_weight();
				if (!empty($weight) && !empty($shipping_up_to_2kg)  && !empty($shipping_additional_kg_cost) ) {

					if ($weight < 2) {
						$shipping = $shipping_up_to_2kg;
						if (floatval($free_shipping_over) <= $product->get_price() ) {
							$shipping = 0;

						}

					}

					if ($weight > 2) {
						$additional_weight = $weight -2;
						$shipping_additional = floatval($shipping_additional_kg_cost) * ceil($additional_weight);
						$shipping = floatval($shipping_up_to_2kg) + $shipping_additional;

						if (floatval($free_shipping_over) <= $product->get_price() ) {
							$shipping = $shipping - floatval($shipping_up_to_2kg) ;

						}


					}
				}



				$product_require_ean  = false;

				if ('id' == $unique_id_field) {
					$unique_id = $id_clean;

				}
				if ('mpn'  == $unique_id_field) {
					$unique_id = $mpn;

				}
				if ( 'custom_field'  == $unique_id_field) {
					$unique_id =  get_post_meta($product->get_id(), $unique_id_custom_field, true);


				}
				$description = $product->get_description();

				$sku = $mpn;
				if ('custom_field'== $sku_field) {
					if (''!=$sku_custom_field) {

						$sku = get_post_meta($product->get_id(), $sku_custom_field, true);
					}


				}
				if ('attribute'== $sku_field) {
					if (''!=$sku_attribute_field) {
						$sku = $product->get_attribute($sku_attribute_field);
					}


				}
				$initial_title = get_the_title();
				if ('all_attributes' == $attributes_on_title) {
	//$product_attributes = $product->get_attributes();


					foreach ( $product->get_attributes() as $attr_name => $attr ) {


						foreach ( $attr->get_terms() as $term ) {

							$initial_title  .=' ' . $term->name;

						}
					}



				}

				if ('size_color' == $attributes_on_title) {
	//$product_attributes = $product->get_attributes();

					$size_attribute = $product->get_attribute($size_field);
					$color_attribute = $product->get_attribute($color_field);

					$initial_title  .=' ' . $size_attribute . ' ' . $color_attribute;

	/*foreach( $product->get_attributes() as $attr_name => $attr ){


	foreach( $attr->get_terms() as $term ){

	$initial_title  .=" ".$term->name;

	}
	}*/



				}


	$title      ='<![CDATA[ ' . $initial_title . ']]>';
	$link       =get_permalink();
	$splitted_product = array();






	$cat_terms = get_the_terms( $product->ID, 'product_cat' );
	$category ='';
	$category_ids = array();
	$category_id = 0;
				if (!empty($cat_terms)) {
							$current_cat = $cat_terms[0];
							$category_id = $cat_terms[0]->term_id;
							$categories = array();
							$categories[] = $current_cat->name;
							$category_ids[] = $current_cat->term_id;

						//  foreach ($cat_terms as $cat_term) {
					while ($current_cat->parent > 0) {
			$current_cat =  get_term( $current_cat->parent, 'product_cat' );

			$categories[] = $current_cat->name;
			$category_ids[] = $current_cat->term_id;



					}



				$category = implode(' > ', array_reverse($categories));
				}
	/*echo $category_id;
	print_r($category_ids);
	exit();
	die();*/



	$ean = '';
	$condition  ='New';
	$price      = number_format($product->get_price(), 2, '.', '');
	$variations_export = '';
	$quantity = 0;
	$ean_text ='';

				if (!empty(array_intersect($category_ids, $ean_required_cats)) ) {
						$product_require_ean = true;
				}

				if (!empty($ean_field)) {
						$ean = get_post_meta($product->get_id(), $ean_field, true);

					if (!empty($ean) ) {
						$ean_text ='<ean>' . $ean . '</ean>';

					} elseif ($product_require_ean) {

							$include_in_feed = false;
					}

				}
	$all_sizes  = array();

	$color_field = '';
				foreach ($color_fields as $field) {
					if (!empty($product->get_attribute($field))) {
						$color_field  = $field;
		
					}

				}
$size_field = '';
				foreach ($size_fields as $field) {
					if (!empty($product->get_attribute($field))) {
						$size_field  = $field;
		
					}

				}


	// $all_sizes  = wc_get_product_terms( $product->get_id(), 'pa_size', array( 'fields' => 'names' ) );
	$color = '';
				if ('yes' == $split_color_variations) {
					$color = wc_get_product_terms( $product->get_id(), $color_field, array( 'fields' => 'names' ) );
					if (is_array($color)) {
						$color = array_shift($color);


					}
				} else {
					$color_terms = wc_get_product_terms( $product->get_id(), $color_field, array( 'fields' => 'names' ) );
					if (is_array($color_terms)) {
						$color = array_shift($color_terms);


					}

				}


	//$brand =  array_shift(wc_get_product_terms( $product->id, 'pa_brand', array( 'fields' => 'names' ) ));
	//$all_sizes = array();
	$quantity = $product->get_stock_quantity();

				if ($product->is_type( 'variable' )) {

					$available_variations = $product->get_available_variations();

					$variations_count = count($available_variations);
					$loop_count = 0;

					foreach ( $available_variations as $variation  ) {
						$current_variation = wc_get_product( $variation['variation_id'] );
						$price = $current_variation->get_price();
						$stock_qty = $current_variation->get_stock_quantity();
						$variation_manage_stock  = $current_variation->get_manage_stock();

						$size_variation = '';
						$color_variation = '';
						$variation_sku = $current_variation->get_sku();
						if (!empty($ean_field)) {
						   $variation_ean = get_post_meta($variation['variation_id'], $ean_field, true);

						}

						$variation_availability = '';
						if ($current_variation->is_on_backorder()) {
							$variation_availability = $all_availabilties[$default_backorder_availability];

						}
						if ($current_variation->is_in_stock()) {
							$variation_availability = $all_availabilties[$default_instock_availability];

						}
						if ($overwrite_availability) {  
							if ($current_variation->get_attribute($overwrite_availability)) {
								$variation_availability = $current_variation->get_attribute($overwrite_availability);

							}
						}
						if (!$variation_ean) {
							$variation_ean = $ean;
						}

						if (!empty($variation_sku)) {
							$mpn = $variation_sku;
						}
						if ('custom_field' == $sku_field) {
							if (''!=$sku_custom_field) {
								$pre_field = get_post_meta( $variation['variation_id'], $sku_custom_field, true);
								if ($pre_field) {
									$mpn = $pre_field;
								}
							}



						}
						if ('attribute'== $sku_field) {
							if (''!=$sku_attribute_field) {
								$variation_attribute_sku = $current_variation->get_attribute($sku_attribute_field);
								if (!empty($variation_attribute_sku)) {
									$mpn = $variation_attribute_sku;
								} else {
								   $mpn = $product->get_attribute($sku_attribute_field);

								}
							}


						}





	





						if (   ( ( $variation_manage_stock ) && ( $stock_qty>=1 ) ) || ( !$variation_manage_stock )    ) {


							$quantity +=$stock_qty;


									$size_key      = sanitize_title('attribute_' . $size_field);

							if ( empty( $variation['attributes'][ $size_key ] ) ) {
								if ( true==true ) {
										$atrrValues =  wc_get_product_terms( $variation['variation_id'], $size_field, array( 'fields' => 'names' ) );
									if (is_array($attrValues)) {
									$all_sizes      = array_merge( $all_sizes, $attrValues );

									$size_variation = implode(',', $atrrValues);


									}
									//break;
								}
							} else {
						$term = get_terms(array( 'taxonomy'=> $size_field, 'fields' => 'names', 'slug' => $variation['attributes'][ $size_key ] ) );


								if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
									$all_sizes[] = $term[0];
									$size_variation = $term[0];

								}
							}

		
							









							if ( ( 'yes' ==  $split_color_variations )) {

										$color_key      = sanitize_title('attribute_' . $color_field);


								if ( empty( $variation['attributes'][ $color_key ] ) ) {
									if ( true==true ) {
											$atrrValues =  wc_get_product_terms( $variation['variation_id'], $color_field, array( 'fields' => 'names' ) );
										if (is_array($attrValues)) {

										$color_variation = implode(',', $atrrValues);


										}
									}
								} else {
$term = get_terms( array( 'taxonomy'=> $color_field, 'fields' => 'names', 'slug' => $variation['attributes'][ $color_key ] ) );



									if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
												$color_variation = $term[0];

									}
								}



								if (!empty($color_variation)) {


						$export_variation_array = array(
							'link'=>$link . '?attribute_' . $color_field . '=' . $color_variation . '&attribute_' . $size_field . '=' . $size_variation,
							'availability'=>$variation_availability,
							'quantity'=>$stock_qty,
							'size' =>$size_variation,
							'price' =>$current_variation->get_price(),
							'ean' =>$variation_ean,
							'manufacturersku'=>$mpn,
							'variationid'=>$unique_id . '.' . $color_variation . '.' . $size_variation,
							'unique_id'=>$unique_id . '.' . $color_variation,
							'image'=>$variation['image']['url'],


						);

									if ($variation_manage_stock) {
										$export_variation_array['quantity'] = $stock_qty;
									}



						$splitted_product[$color_variation]['variations'][] =$export_variation_array;


						$variation_image = $variation['image']['url'];


									if ($alternate_skroutz_image) {
										if ('yes' == $alternate_create_field) {

											$custom_img_id = intval(get_post_meta($variation['variation_id'], 'wpslash_skroutz_custom_image_variation', true));
											if ($custom_img_id > 0) {
												$variation_image = array_shift(wp_get_attachment_image_src( $custom_img_id , 'full'));
											}

										}
										if ('no' == $alternate_create_field) {
											$custom_img_id = intval(get_post_meta($variation['variation_id'], $alternate_skroutz_image_field_variations, true));
											if ($custom_img_id > 0) {
												$variation_image = array_shift(wp_get_attachment_image_src( $custom_img_id , 'full'));

											}

										}


									}



						$splitted_product[$color_variation]['basic'] = array(
						'link'=>$link . '?attribute_' . $color_field . '=' . $color_variation,
						'availability'=>$variation_availability,
						'price' =>$price_with_vat,
						'ean' =>$variation_ean,
						'mpn'=>$mpn,
						'unique_id'=>$unique_id . '.' . $color_variation,
						'image'=>$variation_image,


						);

								} elseif (!empty($size_variation)) {
					$variations_export .= ' <variation>
				<variationid>' . $unique_id . '.' . $size_variation . '</variationid>
				<link><![CDATA[' . $link . '?attribute_' . $size_field . '=' . $size_variation . ']]></link>
				<availability>' . $variation_availability . '</availability>
				<manufacturersku><![CDATA[' . $mpn . ']]></manufacturersku>
				<ean>' . $variation_ean . '</ean>
				<price_with_vat>' . $current_variation->get_price() . '</price_with_vat>
				<size>' . $size_variation . '</size>
				<quantity>' . $stock_qty . '</quantity>
				</variation>';
								}   

							//  }



							} elseif (!empty($size_variation)) {

									$variations_export .= ' <variation>
				<variationid>' . $unique_id . '.' . $size_variation . '</variationid>
				<link><![CDATA[' . $link . '?attribute_' . $size_field . '=' . $size_variation . ']]></link>
				<availability>' . $variation_availability . '</availability>
				<manufacturersku><![CDATA[' . $mpn . ']]></manufacturersku>
				<ean>' . $variation_ean . '</ean>
				<price_with_vat>' . $current_variation->get_price() . '</price_with_vat>
				<size>' . $size_variation . '</size>
				<quantity>' . $stock_qty . '</quantity>
				</variation>';

							}








						}



					}

				} else {
					//foreach ($size_fields as $size_field) {
						$current_size = $product->get_attribute($size_field);
					if ($current_size) {
						  $size =  $current_size;


					}

				//  }

				}
				if (!empty($all_sizes)) {
					$all_sizes = array_unique( $all_sizes );

					$size = implode(',', $all_sizes);


				}
	$size_xml = '';
				if (!empty($size)) {
					$size_xml = '<size><![CDATA[' . $size . ']]></size>';
				}




				if ($product->is_in_stock()) {

					$in_stock='Y';

				} else {
					$in_stock='N';


				}
	$availability = '';


				if ($product->is_on_backorder()) {
					$availability = $all_availabilties[$default_backorder_availability];

					if ('yes' == $excluded_backorder_items) {
						$include_in_feed = false;
					}

				}
				if ($product->is_in_stock()) {
					$availability = $all_availabilties[$default_instock_availability];

				}
				if ($overwrite_availability) {  
					if ($product->get_attribute($overwrite_availability)) {
						$availability = $product->get_attribute($overwrite_availability);

					}
				}
	$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full');
				if (is_array($image_array)) {

					$image      = array_shift($image_array);

				}



				if ($alternate_skroutz_image) {
					if ('yes' == $alternate_create_field) {

						$custom_img_id = intval(get_post_meta($product->get_id(), 'wpslash_skroutz_custom_image', true));
						if ($custom_img_id > 0) {
							$image_array = wp_get_attachment_image_src( $custom_img_id , 'full');
							if (is_array($image_array)) {
								$image = array_shift($image_array);
							}

						}

					}
					if ('no' == $alternate_create_field) {
						$custom_img_id = intval(get_post_meta($product->get_id(), $alternate_skroutz_image_field, true));
						if ($custom_img_id > 0) {
							$image_array = wp_get_attachment_image_src( $custom_img_id , 'full');

							if (is_array($image_array)) {
								$image = array_shift($image_array);
							}

						}

					}


				}

	$images_ids = $product->get_gallery_image_ids();
	$additional_images ='';
				foreach ($images_ids as $image_id) { 

					$image_link = wp_get_attachment_url( $image_id );

					$additional_images .= '<additional_imageurl>' . $image_link . '</additional_imageurl>';

				}





	$brands_terms = get_the_terms( $product->get_id(), $brand_taxonomy );

				if (!empty($brands_terms)) {
					foreach ($brands_terms as $brand_term) {
						$brand = $brand_term->name;
						break;
					}
					$brand = '<![CDATA[ ' . $brand . ']]>';
				}
	$color_xml = '';
				if (!empty($color)) {
					$color_xml = '<color><![CDATA[' . $color . ']]></color>';
				}
				if (!empty($variations_export)) {
					$variations_export =  '<variations>' . $variations_export . '</variations>';
				}

				if ($product->is_visible() && ( $product->is_in_stock() || $product->is_on_backorder() ) && $include_in_feed) {

					if ($splitted_product) {

						foreach ($splitted_product as $color=> $data) {
							$color_xml = '<color><![CDATA[' . $color . ']]></color>';

							$basic = $data['basic'];
							$variation_data =  $data['variations'];
							$mpn  = '<![CDATA[' . $basic['mpn'] . ']]>';
							$price_with_vat  = '<![CDATA[' . $basic['price'] . ']]>';
							$ean  = '<![CDATA[' . $basic['ean'] . ']]>';
							$link  = '<![CDATA[' . $basic['link'] . ']]>';
							$variations_export = '';
							$quantity = 0;
							foreach ($variation_data as $variation_size) {
								if (!empty($variation_size['size'])) {
										$variations_export .=  ' <variation>
										<variationid>' . $variation_size['variationid'] . '</variationid>
										<link><![CDATA[' . $variation_size['link'] . ']]></link>
										<availability>' . $variation_size['availability'] . '</availability>
										<manufacturersku><![CDATA[' . $variation_size['mpn'] . ']]></manufacturersku>
										<ean>' . $variation_size['ean'] . '</ean>
										<price_with_vat>' . $variation_size['price'] . '</price_with_vat>
										<size><![CDATA[' . $variation_size['size'] . ']]></size>
										<quantity>' . $variation_size['quantity'] . '</quantity>
										</variation>';
								}

		
								$quantity +=$variation_size['quantity'];
							}
							if (!empty($variations_export)) {
								$variations_export = '<variations>' . $variations_export . '</variations>';
							}
							$shipping_text = '';
							if (is_numeric($shipping)) {
								$shipping_text = '<shipping><![CDATA[' . $shipping . ']]></shipping>';


							}


							// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fputs
							fputs ($handle, '
				<product> 
				<id><![CDATA[' . $basic['unique_id'] . ']]></id>
				<mpn><![CDATA[' . $sku . ']]></mpn>
				' . $ean_text . ' 
				<name>' . $title . '</name>
				<link>' . $link . '</link>
				<image>' . $basic['image'] . '</image>
				' . $size_xml . '
				' . $color_xml . '
				<description><![CDATA[' . $description . ']]></description>

				<quantity><![CDATA[' . $quantity . ']]></quantity>
				<weight><![CDATA[' . $weight . 'kg]]></weight>

				' . $additional_images . '
				' . $variations_export . '
				<category><![CDATA[' . $category . ']]></category>
				<price_with_vat>' . $price . '</price_with_vat>
				<instock>' . $in_stock . '</instock>
				<availability>' . $availability . '</availability>
				' . $shipping_text . '
				<manufacturer>' . $brand . '</manufacturer>
				</product>');




						}


					} else {
						// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fputs
						fputs ($handle, '
			<product> 
			<id><![CDATA[' . $unique_id . ']]></id>
			<mpn><![CDATA[' . $sku . ']]></mpn>
			' . $ean_text . ' 
			<name>' . $title . '</name>
			<link>' . $link . '</link>
			<image>' . $image . '</image>
			' . $size_xml . '
			' . $color_xml . '
			<description><![CDATA[' . $description . ']]></description>

			<quantity><![CDATA[' . $quantity . ']]></quantity>
		    <weight><![CDATA[' . $weight . 'kg]]></weight>


			' . $additional_images . '
			' . $variations_export . '
			<category><![CDATA[' . $category . ']]></category>
			<price_with_vat>' . $price . '</price_with_vat>
			<instock>' . $in_stock . '</instock>
			<availability>' . $availability . '</availability>
			<manufacturer>' . $brand . '</manufacturer>
			</product>');

					}




				}
	endwhile;

	endif;

		if ( ( $current_page == $total_pages ) || ( 0 == $total_pages ) ) {

			wp_clear_scheduled_hook( 'skroutz_smart_cart_feed_scheduled_task_second' );
			wp_clear_scheduled_hook( 'wpslash_smart_cart_feed_scheduled_task_second' );
			update_option('wpslash_smart_cart_feed_xml_is_running', false);
			update_option('wpslash_smart_cart_feed_xml_total_pages', 0);
			update_option('wpslash_smart_cart_feed_xml_current_page', 0);


			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fputs
			fputs ($handle, '</products>
		</mywebstore>');

			$upload_dir = wp_upload_dir();

			copy($upload_dir['basedir'] . '/tempskroutz.xml', $upload_dir['basedir'] . '/wpslash_skroutz_xml_feed.xml');

		}
		if ($handle) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			fclose ($handle);

		}
	}

update_option('wpslash_smart_cart_feed_xml_processing_now', false);
}
