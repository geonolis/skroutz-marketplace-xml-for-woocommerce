jQuery(document).ready(function()


	{




        jQuery(document).on('click', '.wpslash-skroutz-toggle-accept-order', function()
        {
                    jQuery(this).parent().find( ".wpslash_skroutz_accept_window" ).toggle('slow');

        });

        jQuery(document).on('click', '.wpslash-skroutz-toggle-reject-order', function()
        {
                    jQuery(this).parent().find( ".wpslash_skroutz_reject_window" ).toggle('slow');

        });

            jQuery(document).on('click', '.wpslash-skroutz-toggle-invoice-order', function()
        {
                    jQuery(this).parent().find( ".wpslash_smart_cart_invoice_wrapper" ).toggle('slow');

        });









       jQuery(document).on('click', '.wpslash-skroutz-accept-order', function()
        {
            console.log("click triggered");
         //jQuery(this).prop("disabled", true);

                

              var order_id    = jQuery(this).attr('order-id');
                       jQuery('.wp-list-table #post-'+order_id+' td.column-wpslash_skroutz_smart_cart').addClass('loading');

            var ajax_data ={};
            var vthis = jQuery(this);
        ajax_data['action'] = 'wpslash_skroutz_smart_cart_accept_order';
        ajax_data['order_id'] = order_id;
        ajax_data['pickup_location'] = jQuery(this).parent().find(".wpslash-skroutz-pickup-location").find('option:selected').val();
        ajax_data['pickup_window'] = jQuery(this).parent().find(".wpslash-skroutz-pickup-window").find('option:selected').val();
        ajax_data['parcels'] = 1;   

        ajax_data['security'] = wpslash_skroutz_smart_cart_order_obj.security;


    jQuery.ajax({
    url: wpslash_skroutz_smart_cart_order_obj.ajaxurl,
    type: 'POST',
    data: ajax_data,
    success: function(response){
                 console.log(response);

         response = jQuery.parseJSON(response);

             jQuery('.wp-list-table #post-'+order_id+' td.column-wpslash_skroutz_smart_cart').removeClass('loading');

         console.log(response);
         if(response.success)
         {

              vthis.parent().find( ".wpslash_skroutz_accept_window" ).toggle('slow');
              wpslash_skroutz_smart_cart_refesh_shoporder(order_id);

         }
            if(response.errors)
         {

                if(response.errors[0].code == "pickup_window")
                {
                    alert("Επιλέξτε άλλη ώρα παραλαβής");
                }

         }


        // jQuery(this).prop("disabled", false);

        

       
    },
  });



        });







              jQuery(document).on('click', '.wpslash-skroutz-reject-order', function()
        {

                

              var order_id    = jQuery(this).attr('order-id');
                       jQuery('.wp-list-table #post-'+order_id+' td.column-wpslash_skroutz_smart_cart').addClass('loading');

            var ajax_data ={};
            var vthis = jQuery(this);
        ajax_data['action'] = 'wpslash_skroutz_smart_cart_reject_order';
        ajax_data['order_id'] = order_id;
        ajax_data['rejection_reason'] = jQuery(this).parent().find(".wpslash-skroutz-rejection-reason").find('option:selected').val();
        ajax_data['quantity'] = jQuery(this).parent().find(".wpslash-skroutz-quantity").val();
        ajax_data['line_items'] = jQuery(this).parent().find(".wpslash-skroutz-item-id").val();

        ajax_data['security'] = wpslash_skroutz_smart_cart_order_obj.security;
        console.log(ajax_data);

    jQuery.ajax({
    url: wpslash_skroutz_smart_cart_order_obj.ajaxurl,
    type: 'POST',
    data: ajax_data,
    success: function(response){
                 console.log(response);

         response = jQuery.parseJSON(response);
         console.log(response);
         if(response.success)
         {

              vthis.parent().find( ".wpslash_skroutz_reject_window" ).toggle('slow');
              wpslash_skroutz_smart_cart_refesh_shoporder(order_id);

         }
            if(response.errors)
         {

                if(response.errors[0].code == "pickup_window")
                {
                    alert("Επιλέξτε άλλη ώρα παραλαβής");
                }

                   if(response.errors[0].code == "pickup_location")
                {
                    alert("Επιλέξτε άλλo κατάστημα παραλαβής");
                }

                


         }


        // jQuery(this).prop("disabled", false);
    jQuery('.wp-list-table #post-'+order_id+' td.column-wpslash_skroutz_smart_cart').removeClass('loading');

        

       
    },
  });



        });



              function wpslash_skroutz_smart_cart_refesh_shoporder(order_id)
        {
                jQuery('.wp-list-table #post-'+order_id+' td.column-wpslash_skroutz_smart_cart').addClass('loading');


            var ajax_data ={};
            var vthis = jQuery(this);
        ajax_data['action'] = 'wpslash_skroutz_smart_cart_refresh_shoporder';
        ajax_data['order_id'] = order_id;
   
        ajax_data['security'] = wpslash_skroutz_smart_cart_order_obj.security;


    jQuery.ajax({
    url: wpslash_skroutz_smart_cart_order_obj.ajaxurl,
    type: 'POST',
    data: ajax_data,
    success: function(response){
                 console.log(response);

         response = jQuery.parseJSON(response);
         console.log(response);
         if(response.success)
         {
             // vthis.parent().find( ".wpslash_skroutz_accept_window" ).toggle('slow');

              setTimeout(
                      function() 
                      {
                       jQuery('#post-'+order_id+' .column-wpslash_skroutz_smart_cart').html(response.html);
                       jQuery('.wp-list-table tr#post-'+order_id+' td.column-wpslash_skroutz_smart_cart').removeClass('loading');

                      }, 10000);
              

         }



        

       
    },
  });
        }

	});






