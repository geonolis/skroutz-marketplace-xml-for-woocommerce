<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpslash_skroutz_smart_cart_register_meta_boxes() {
	$alternate_skroutz_image_enabled = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image', 'no');
	$create_field = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image_create_field', 'no');
	if (( 'yes'== $alternate_skroutz_image_enabled ) && ( 'yes'== $create_field )) {
			add_meta_box( 'wpslash-skroutz-alternate-image', __( 'Skroutz Alternate Image', 'skroutz-marketplace-xml-for-woocommerce' ), 'wpslash_skroutz_smart_cart_altenate_image_metabox', 'product', 'side', 'high' );

	}
}
add_action( 'add_meta_boxes', 'wpslash_skroutz_smart_cart_register_meta_boxes' );

function wpslash_skroutz_smart_cart_altenate_image_metabox() {

		global $post;
		$saved_image_id = get_post_meta( $post->ID, 'wpslash_skroutz_custom_image', true );
		$saved_url = wp_get_attachment_image_src( $saved_image_id, 'thumbnail' );
		wp_nonce_field( 'wpslash_skroutz_smart_cart_metabox_nonce', 'wpslash_skroutz_smart_cart_nonce' );
	?>
		<a id="wpslash_skroutz_image_select_link">
		<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		if ($saved_url) : 
			?>


			 <img width="<?php echo esc_html($saved_url[1]) ; ?>" height="<?php echo esc_html($saved_url[2]) ; ?>" src="<?php echo esc_html($saved_url[0]) ; ?>" class="attachment-post-thumbnail size-post-thumbnail wpslash_skroutz_smart_cart_img" alt="" loading="lazy" id="wpslash_skroutz_smart_cart_img">
			<?php else : ?>

				<?php esc_html_e('Choose or Upload Media', 'skroutz-marketplace-xml-for-woocommerce'); ?>

		<?php 
		endif;  

			?>
		</a>
		<?php if ($saved_url) : ?>
		<a  id="wpslash_remove_skroutz_thumbnail"><?php esc_html_e('Remove Image', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>
	<?php endif; ?>

				<div>
			
					<input type="hidden" name="wpslash_skroutz_custom_image" id="wpslash_skroutz_custom_image" value="<?php echo esc_attr( $saved_image_id ); ?>"><br>

					
				</div>


		<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

		// Security field
		wp_nonce_field( 'myplugin_form_metabox_nonce', 'myplugin_form_metabox_process' );
}

function wpslash_skroutz_smart_cart_save_meta( $post_id ) {

	if ( !isset( $_POST['wpslash_skroutz_smart_cart_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpslash_skroutz_smart_cart_nonce'] ) ), 'wpslash_skroutz_smart_cart_metabox_nonce') ) { 
	  return;
	}

	if ( !current_user_can( 'edit_post', $post_id )) {
	  return;
	}

	if ( isset($_POST['wpslash_skroutz_custom_image']) ) {        
	  update_post_meta($post_id, 'wpslash_skroutz_custom_image', intval( $_POST['wpslash_skroutz_custom_image']));      
	}  
}
add_action('save_post', 'wpslash_skroutz_smart_cart_save_meta');





add_action( 'woocommerce_product_after_variable_attributes', 'wpslash_skroutz_variation_image_field', 10, 3 );

function wpslash_skroutz_variation_image_field( $loop, $variation_data, $variation ) {
	$alternate_skroutz_image_enabled = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image', 'no');
	$create_field = get_option('wc_settings_tab_wpslash_smart_cart_alternate_image_create_field', 'no');
	if (( 'yes'== $alternate_skroutz_image_enabled ) && ( 'yes'== $create_field )) {
		?>
	<div class="wpslash_skroutz_variation_alternate_image_wrapper">
		<label><?php esc_html_e('Alternate Skroutz Image', 'skroutz-marketplace-xml-for-woocommerce'); ?></label>
		<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


		$saved_image_id = get_post_meta( $variation->ID, 'wpslash_skroutz_custom_image_variation', true );
		$saved_url = wp_get_attachment_image_src( $saved_image_id, 'thumbnail' );
		wp_nonce_field( 'wpslash_skroutz_smart_cart_metabox_nonce', 'wpslash_skroutz_smart_cart_nonce' );
		?>
		<a id="wpslash_skroutz_image_select_link_variation_<?php echo esc_html($loop); ?>">
		<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		if ($saved_url) : 
			?>


			 <img width="<?php echo esc_html($saved_url[1]) ; ?>" height="<?php echo esc_html($saved_url[2]) ; ?>" src="<?php echo esc_html($saved_url[0]) ; ?>" class="attachment-post-thumbnail size-post-thumbnail" id="wpslash_skroutz_smart_cart_img_variation_<?php echo esc_html($loop); ?>" alt="" loading="lazy">
			<?php else : ?>

				<?php esc_html_e('Choose or Upload Media', 'skroutz-marketplace-xml-for-woocommerce'); ?>

		<?php 
		endif;  

			?>
		</a>
		<?php if ($saved_url) : ?>
		<a  id="wpslash_remove_skroutz_thumbnail_variation_<?php echo esc_html($loop); ?>"><?php esc_html_e('Remove Image', 'skroutz-marketplace-xml-for-woocommerce'); ?></a>
	<?php endif; ?>

				<div>
			
					<input type="hidden" name="wpslash_skroutz_custom_image_variation[<?php echo esc_html($loop); ?>]" id="wpslash_skroutz_custom_image_variation_<?php echo esc_html($loop); ?>" value="<?php echo esc_attr( $saved_image_id ); ?>"><br>

					
				</div>
			</div>
				<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	}
}

add_action( 'woocommerce_save_product_variation', 'wpslash_skroutz_save_variation_settings_fields', 10, 2 );

function wpslash_skroutz_save_variation_settings_fields( $variation_id, $loop ) {
	if ( !isset( $_POST['wpslash_skroutz_smart_cart_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpslash_skroutz_smart_cart_nonce'] ) ), 'wpslash_skroutz_smart_cart_metabox_nonce') ) { 
	  return;
	}
	if ( isset($_POST['wpslash_skroutz_custom_image_variation']) ) {   

		if ( ! empty( $_POST['wpslash_skroutz_custom_image_variation']) ) {
			update_post_meta( $variation_id, 'wpslash_skroutz_custom_image_variation', intval( $_POST['wpslash_skroutz_custom_image_variation'] ));
		}

	}     
}



?>
