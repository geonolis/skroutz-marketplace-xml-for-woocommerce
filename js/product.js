jQuery(document).ready(function($){

	'use strict';

	// Instantiates the variable that holds the media library frame.
	var metaImageFrame;
	var metaImageFrames = [];

$(document).on('click', '#skroutz_remove_thumbnail', function(e)
	{
		$( '#wpslash_skroutz_custom_image' ).val("");
		$( '#skroutz_image_select_link').html('<a class="skroutz_image_select_link">'+skroutz_smart_cart_product_obj.title+'</a>');
		$( '#skroutz_remove_thumbnail').remove();

	});

$(document).on('click', '[id^="skroutz_remove_thumbnail_variation_"]', function(e)
	{
				var loop_id  = parseInt(jQuery(this).attr("id").replace('skroutz_remove_thumbnail_variation_', ''));

		$( '#wpslash_skroutz_custom_image_variation_'+loop_id ).val("");
		$( '#skroutz_image_select_link_variation_'+loop_id).html('<a class="skroutz_image_select_link_variation_'+loop_id+'">'+skroutz_smart_cart_product_obj.title+'</a>');
		$( '#skroutz_remove_thumbnail_variation_'+loop_id).remove();

	});
	


	// Runs when the media button is clicked.
	$(document).on('click', '#skroutz_image_select_link', function(e)
	{


		e.preventDefault();

		// Sets up the media library frame
		metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
			title: skroutz_smart_cart_product_obj.title,
			button: { text:  skroutz_smart_cart_product_obj.button },
		});

		// Runs when an image is selected.
		metaImageFrame.on('select', function() {

		

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = metaImageFrame.state().get('selection').first().toJSON();
			console.log(media_attachment);

			// Sends the attachment URL to our custom image input field.
			$( '#wpslash_skroutz_custom_image' ).val(media_attachment.id);
			
			if($( '#skroutz_smart_cart_img' ).length)
			{
				jQuery('#skroutz_smart_cart_img').attr('src', media_attachment.sizes.thumbnail.url);
				jQuery('#skroutz_smart_cart_img').attr('width', media_attachment.sizes.thumbnail.width);
				jQuery('#skroutz_smart_cart_img').attr('height', media_attachment.sizes.thumbnail.height);
	
			}
			else
			{
			 jQuery('#skroutz_image_select_link').html('<img width="'+media_attachment.sizes.thumbnail.width+'" height="'+media_attachment.sizes.thumbnail.width+'" src="'+media_attachment.sizes.thumbnail.url+'" class="attachment-post-thumbnail size-post-thumbnail skroutz_smart_cart_img" alt="" loading="lazy">')

			}
			if(!jQuery('#skroutz_remove_thumbnail').length)
				{
											jQuery('#skroutz_image_select_link').next().append('<a  id="skroutz_remove_thumbnail">'+skroutz_smart_cart_product_obj.remove+'</a>');

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















	$(document).on('click', '[id^="skroutz_image_select_link_variation_"]', function(e)
	{

		var loop_id  = parseInt(jQuery(this).attr("id").replace('skroutz_image_select_link_variation_', ''));

		e.preventDefault();

		// Sets up the media library frame
		metaImageFrames[loop_id] = wp.media.frames.metaImageFrame = wp.media({
			title: skroutz_smart_cart_product_obj.title,
			button: { text:  skroutz_smart_cart_product_obj.button },
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
			
			if($( '#skroutz_smart_cart_img_variation_'+loop_id ).length)
			{
				jQuery('#skroutz_smart_cart_img_variation_'+loop_id).attr('src', media_attachment.sizes.thumbnail.url);
				jQuery('#skroutz_smart_cart_img_variation_'+loop_id).attr('width', media_attachment.sizes.thumbnail.width);
				jQuery('#skroutz_smart_cart_img_variation_'+loop_id).attr('height', media_attachment.sizes.thumbnail.height);
	
			}
			else
			{
			 jQuery('#skroutz_image_select_link_variation_'+loop_id).html('<img width="'+media_attachment.sizes.thumbnail.width+'" height="'+media_attachment.sizes.thumbnail.width+'" src="'+media_attachment.sizes.thumbnail.url+'" class="attachment-post-thumbnail size-post-thumbnail skroutz_smart_cart_img" alt="" loading="lazy">')

			}
			if(jQuery('#skroutz_remove_thumbnail_variation_'+loop_id+'').length == 0)
				{
				jQuery('#skroutz_image_select_link_variation_'+loop_id).next().html('<a  id="skroutz_remove_thumbnail_variation_'+loop_id+'">'+skroutz_smart_cart_product_obj.remove+'</a>');

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