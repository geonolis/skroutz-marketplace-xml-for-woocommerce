jQuery(document).ready(function()
	{


        jQuery('#wc_settings_tab_wpslash_smart_cart_feed_ean_field').select2();
        jQuery('#wc_settings_tab_wpslash_smart_cart_feed_ean_required_cats').select2();
                jQuery('#wc_settings_tab_wpslash_smart_cart_feed_size_field').select2();
		        jQuery('#wc_settings_tab_wpslash_smart_cart_feed_color_field').select2();

       jQuery('#wc_settings_tab_wpslash_smart_cart_feed_excluded_brands').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_feed_exluded_cats').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_feed_exluded_tags').select2();
		  jQuery('#wc_settings_tab_wpslash_smart_cart_feed_included_brands').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_feed_included_cats').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_feed_included_tags').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field').select2();
		jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variation').select2();


		var skroutz_alternate_field =  jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image :checked').val();
		if(!skroutz_alternate_field)
		{
			jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field').parent().parent().css("display", "none");
		    jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field').parent().parent().css("display", "none");
		    jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations').parent().parent().css("display", "none");



		}

		var skroutz_unique_id_field =  jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id :checked').val();

	if(skroutz_unique_id_field =="custom_field")
		{
		 
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "table-row");


		}
		else if(skroutz_unique_id_field =="attribute")
		{
				jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_attribute_field').parent().parent().css("display", "table-row");

		}
		else
		{
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "none");
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_attribute_field').parent().parent().css("display", "none");


		}



		var skroutz_unique_id_field_cart =  jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id :checked').val();

	if(skroutz_unique_id_field_cart !="custom_field")
		{
			jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "none");
		 


		}
		else
		{
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "table-row");

		}


var skroutz_feed_sku_field =  jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_field :checked').val();

	if(skroutz_feed_sku_field =="custom_field")
		{

			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "table-row");
		 			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "none");



		}
		else if(skroutz_feed_sku_field =="attribute")
		{
			
			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "table-row");
		 			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "none");		 


		}
		else
		{
		 			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "none");		 
		 			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "none");		 

		}



	jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_feed_sku_field', function()
		{

			if(jQuery(this).val() == "custom_field")
			{
						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "table-row");


			}
			else
			{
			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "none");


			}

		});

		jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_feed_sku_field', function()
		{

			if(jQuery(this).val() == "custom_field")
			{
						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "table-row");
						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "none");


			}
			else if(jQuery(this).val() == "attribute")
			{
						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "none");
						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "table-row");


			}
			else
			{
			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_custom_field').parent().parent().css("display", "none");
			jQuery('#wc_settings_tab_wpslash_smart_cart_feed_sku_attribute_field').parent().parent().css("display", "none");


			}

		});


	jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_unique_id', function()
		{

			if(jQuery(this).val() == "custom_field")
			{
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "table-row");
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_attribute_field').parent().parent().css("display", "table-row");


			}
			else if(jQuery(this).val() == "attribute")
			{	
				jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "none");
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_attribute_field').parent().parent().css("display", "table-row");

			}
			else
			{
jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_custom_field').parent().parent().css("display", "none");
						jQuery('#wc_settings_tab_wpslash_smart_cart_unique_id_attribute_field').parent().parent().css("display", "none");


			}

		});





	jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_feed_unique_id', function()
		{

			if(jQuery(this).val() == "custom_field")
			{
									jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_attribute_field').parent().parent().css("display", "none");

			  						jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_custom_field').parent().parent().css("display", "table-row");


			}
			else if(jQuery(this).val() == "attribute")
			{
											jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_custom_field').parent().parent().css("display", "none");

							  			 jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_attribute_field').parent().parent().css("display", "table-row");


			}
			else
			{
							jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_custom_field').parent().parent().css("display", "none");
							jQuery('#wc_settings_tab_wpslash_smart_cart_feed_unique_id_attribute_field').parent().parent().css("display", "none");


			}

		});










		jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_alternate_image', function()
		{

			if(jQuery(this).is(':checked'))
			{
			   jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field').parent().parent().css("display", "table-row");
			   jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field').trigger('change');

			}
			else
			{
				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field').parent().parent().css("display", "none");


				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field').trigger('change');

			}

		});


			jQuery(document).on('change', '#wc_settings_tab_wpslash_smart_cart_alternate_image_create_field', function()
		{
			if( jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image').is(':checked'))
			{

		

			if(jQuery(this).val() == "yes")
			{
			   jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field').parent().parent().css("display", "none");
				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations').parent().parent().css("display", "none");

			}
			else
			{
				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field').parent().parent().css("display", "table-row");
				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations').parent().parent().css("display", "table-row");

			}
		    }
		    else
		    {
		    	jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field').parent().parent().css("display", "none");
				jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image_custom_field_variations').parent().parent().css("display", "none");

		    }

		});


			   jQuery('#wc_settings_tab_wpslash_smart_cart_alternate_image').trigger('change');


	});