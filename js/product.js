jQuery(document).ready(function($){

	'use strict';

	// Instantiates the variable that holds the media library frame.
	var metaImageFrame;
	var metaImageFrames = [];

$(document).on('click', '#wpslash_remove_skroutz_thumbnail', function(e)
	{
		$( '#wpslash_skroutz_custom_image' ).val("");
		$( '#wpslash_skroutz_image_select_link').html('<a class="wpslash_skroutz_image_select_link">'+wpslash_skroutz_smart_cart_product_obj.title+'</a>');
		$( '#wpslash_remove_skroutz_thumbnail').remove();

	});

$(document).on('click', '[id^="wpslash_remove_skroutz_thumbnail_variation_"]', function(e)
	{
				var loop_id  = parseInt(jQuery(this).attr("id").replace('wpslash_remove_skroutz_thumbnail_variation_', ''));

		$( '#wpslash_skroutz_custom_image_variation_'+loop_id ).val("");
		$( '#wpslash_skroutz_image_select_link_variation_'+loop_id).html('<a class="wpslash_skroutz_image_select_link_variation_'+loop_id+'">'+wpslash_skroutz_smart_cart_product_obj.title+'</a>');
		$( '#wpslash_remove_skroutz_thumbnail_variation_'+loop_id).remove();

	});
	


	// Runs when the media button is clicked.
	$(document).on('click', '#wpslash_skroutz_image_select_link', function(e)
	{


		e.preventDefault();

		// Sets up the media library frame
		metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
			title: wpslash_skroutz_smart_cart_product_obj.title,
			button: { text:  wpslash_skroutz_smart_cart_product_obj.button },
		});

		// Runs when an image is selected.
		metaImageFrame.on('select', function() {

		

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = metaImageFrame.state().get('selection').first().toJSON();
			console.log(media_attachment);

			// Sends the attachment URL to our custom image input field.
			$( '#wpslash_skroutz_custom_image' ).val(media_attachment.id);
			
			if($( '#wpslash_skroutz_smart_cart_img' ).length)
			{
				jQuery('#wpslash_skroutz_smart_cart_img').attr('src', media_attachment.sizes.thumbnail.url);
				jQuery('#wpslash_skroutz_smart_cart_img').attr('width', media_attachment.sizes.thumbnail.width);
				jQuery('#wpslash_skroutz_smart_cart_img').attr('height', media_attachment.sizes.thumbnail.height);
	
			}
			else
			{
			 jQuery('#wpslash_skroutz_image_select_link').html('<img width="'+media_attachment.sizes.thumbnail.width+'" height="'+media_attachment.sizes.thumbnail.width+'" src="'+media_attachment.sizes.thumbnail.url+'" class="attachment-post-thumbnail size-post-thumbnail wpslash_skroutz_smart_cart_img" alt="" loading="lazy">')

			}
			if(!jQuery('#wpslash_remove_skroutz_thumbnail').length)
				{
											jQuery('#wpslash_skroutz_image_select_link').next().append('<a  id="wpslash_remove_skroutz_thumbnail">'+wpslash_skroutz_smart_cart_product_obj.remove+'</a>');

				}



		});
metaImageFrame.on('open', function() {
    let selection = metaImageFrame.state().get('selection');
    if(selection)
    {
    	let id = parseInt(jQuery('#wpslash_skroutz_custom_image').val()); // array of IDs of previously selected files. You're gonna build it dynamically
    if(id >0)
    {
     let attachment = wp.media.attachment(id);
  	 selection.add([attachment]);
    }
    }
    
  	
  });



		// Opens the media library frame.
		metaImageFrame.open();

	});















	$(document).on('click', '[id^="wpslash_skroutz_image_select_link_variation_"]', function(e)
	{

		var loop_id  = parseInt(jQuery(this).attr("id").replace('wpslash_skroutz_image_select_link_variation_', ''));

		e.preventDefault();

		// Sets up the media library frame
		metaImageFrames[loop_id] = wp.media.frames.metaImageFrame = wp.media({
			title: wpslash_skroutz_smart_cart_product_obj.title,
			button: { text:  wpslash_skroutz_smart_cart_product_obj.button },
		});

		// Runs when an image is selected.
		metaImageFrames[loop_id].on('select', function() {

		

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = metaImageFrames[loop_id].state().get('selection').first().toJSON();
			console.log(media_attachment);

			// Sends the attachment URL to our custom image input field.
			$( '#wpslash_skroutz_custom_image_variation_'+loop_id ).val(media_attachment.id);
			$( '#wpslash_skroutz_custom_image_variation_'+loop_id ).trigger('change');

			jQuery('#wpslash_skroutz_custom_image_variation_1').trigger('change');
			
			if($( '#wpslash_skroutz_smart_cart_img_variation_'+loop_id ).length)
			{
				jQuery('#wpslash_skroutz_smart_cart_img_variation_'+loop_id).attr('src', media_attachment.sizes.thumbnail.url);
				jQuery('#wpslash_skroutz_smart_cart_img_variation_'+loop_id).attr('width', media_attachment.sizes.thumbnail.width);
				jQuery('#wpslash_skroutz_smart_cart_img_variation_'+loop_id).attr('height', media_attachment.sizes.thumbnail.height);
	
			}
			else
			{
			 jQuery('#wpslash_skroutz_image_select_link_variation_'+loop_id).html('<img width="'+media_attachment.sizes.thumbnail.width+'" height="'+media_attachment.sizes.thumbnail.width+'" src="'+media_attachment.sizes.thumbnail.url+'" class="attachment-post-thumbnail size-post-thumbnail wpslash_skroutz_smart_cart_img" alt="" loading="lazy">')

			}
			if(jQuery('#wpslash_remove_skroutz_thumbnail_variation_'+loop_id+'').length == 0)
				{
				jQuery('#wpslash_skroutz_image_select_link_variation_'+loop_id).next().html('<a  id="wpslash_remove_skroutz_thumbnail_variation_'+loop_id+'">'+wpslash_skroutz_smart_cart_product_obj.remove+'</a>');

				}



		});
metaImageFrames[loop_id].on('open', function() {
    let selection = metaImageFrames[loop_id].state().get('selection');
    if(selection)
    {
    	let id = parseInt(jQuery('#wpslash_skroutz_custom_image['+loop_id+']').val()); // array of IDs of previously selected files. You're gonna build it dynamically
    if(id >0)
    {
     let attachment = wp.media.attachment(id);
  	 selection.add([attachment]);
    }
    }
    
  	
  });



		// Opens the media library frame.
		metaImageFrames[loop_id].open();

	});







});