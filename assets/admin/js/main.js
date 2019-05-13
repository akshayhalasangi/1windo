  (function ($) {
    $(document).ready(function() {        
       $('.sweet-5').on('click', function(){

            var id = $(this).data('id');
            var act = $(this).data('act');

            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this and its child records!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm){
                        if(act == 'Customer')
                            url =  customer_delete_url;
                        else if(act == 'Vendor')
                            url =  vendor_delete_url;
                        else if(act == 'DeliveryBoy')
                            url =  deliveryboy_delete_url;
                        else if(act == 'Category')
                            url =  category_delete_url;
						else if(act == 'Service')     
							url = service_delete_url;
						else if(act == 'Feature')     
							url = feature_delete_url;
						else if(act == 'Step')     
							url = step_delete_url;							
						else if(act == 'Work')     
							url = work_delete_url;							
						else if(act == 'Package')     
							url = package_delete_url;							
						else if(act == 'Option')     
							url = option_delete_url;							
						else if(act == 'Timeslab')     
							url = timeslab_delete_url;							
						else if(act == 'Product')     
							url = product_delete_url;
						else if(act == 'Attribute')     
							url = attribute_delete_url;
						else if(act == 'AttribValue')     
							url = attribvalue_delete_url;
						else if(act == 'Restaurant')     
							url = restaurant_delete_url;	
						else if(act == 'Food')     
							url = food_delete_url;	
                        else if(act == 'Staff')
                            url =  staff_delete_url;
                        else if(act == 'Notification')
                            url =  notification_delete_url;
                        else if(act == 'VendorProduct'){
                            url =  notification_delete_url;
                        }
                        else if(act == 'Country')
                            url = site_url+'Admin/Location/DeleteCountry';
                        else if(act == 'State')
                            url =  site_url+'Admin/Location/DeleteState';
                        else if(act == 'city')
                            url =  site_url+'Admin/Location/DeleteCity';
                        else if(act == 'Area')
                            url =  site_url+'Admin/Location/DeleteArea';

                        /* Ajax request */
                        $.ajax({    
                            type: "POST",
                            url: url,                 
                            data: {id:id},          
                            success: function(response){ 
                                var obj = $.parseJSON(response);
                                console.log("OBJ: ",obj);
                               $.notify({
									title: '<strong>'+ jsUcfirst(obj.Class) +'! '+ ' </strong>',
									message: obj.Msg
								},{
									type: obj.Class,
									offset: {
										x: 20,
										y: 70
									}
								});
											  //  ToastMessageshow(obj.Class, obj.Msg);
                                if(act == 'Notification'){  
                                    $('#noti-'+id).remove();  
                                    $('#notibody-'+id).remove();
                                } else {
                                    $('#tr-'+id).remove();      
                                }
                                
                            }
                        });
                        swal("Deleted!", "Record has been deleted!", "success");
                    } else {
                        swal("Cancelled", "Record is safe :)", "error");
                    }
                });
        });
		
		function jsUcfirst(string) 
			{
				return string.charAt(0).toUpperCase() + string.slice(1);
			}

        /* Update status */
		$('.tbl-status').on('click',function(){       
			var type = $(this).data("type");    
			var status = $(this).val();

			if(type == 'Customer')     
				var url = admin_url + customer_update_status_url;
			else if(type == 'Vendor')     
				var url = admin_url + vendor_update_status_url;
			else if(type == 'DeliveryBoy')     
				var url = admin_url + deliveryboy_update_status_url;
			else if(type == 'Category')     
				var url = admin_url + category_update_status_url;
			else if(type == 'Service')     
				var url = admin_url + service_update_status_url;
			else if(type == 'Feature')     
				var url = admin_url + feature_update_status_url;
			else if(type == 'Step')     
				var url = admin_url + step_update_status_url;
			else if(type == 'Work')     
				var url = admin_url + work_update_status_url;
			else if(type == 'Review')     
				var url = admin_url + review_update_status_url;
			else if(type == 'Package')     
				var url = admin_url + package_update_status_url;
			else if(type == 'Option')     
				var url = admin_url + option_update_status_url;
			else if(type == 'Timeslab')     
				var url = admin_url + timeslab_update_status_url;
			else if(type == 'Product')     
				var url = admin_url + product_update_status_url;
			else if(type == 'Attribute')     
				var url = admin_url + attribute_update_status_url;
			else if(type == 'AttribValue')
				var url = admin_url + attribvalue_update_status_url;
			else if(type == 'PReview')     
				var url = admin_url + review_product_update_status_url;
			else if(type == 'Restaurant')     
				var url = admin_url + restaurant_update_status_url;				
			else if(type == 'RReview')
				var url = admin_url + review_restaurant_update_status_url;
			else if(type == 'Food')     
				var url = admin_url + food_update_status_url;				
			else if(type == 'Staff')     
				var url = admin_url + staff_update_status_url;
            
            var id = $(this).data("id");
            var elementId = $(this).attr("id");

			$.ajax({
            type: "POST",
            url: url,
            data: {'id':id,'type':type,'status':status},
            success: function(response){
				console.log("Response: ",response);
                var obj = $.parseJSON(response);
				$.notify({
					title: '<strong>'+ jsUcfirst(obj.Class) +'! '+ ' </strong>',
					message: obj.Msg
				},{
					type: obj.Class,
					offset: {
						x: 20,
						y: 70
					}
				});	
               // ToastMessageshow(obj.Class, obj.Msg);
                
               if(status == 1){
                   console.log("Ïn 1");
                    $("#"+elementId).val('2');
                    $("#"+elementId).attr('data-status', '2');
                }else{
                    console.log("Ïn 2");
                    $("#"+elementId).val('1');
                    $("#"+elementId).attr('data-status', '1');
                }

                if(type == 'User'){ 
                    if(status == 1){  
                        $('#label-enable-'+id).hide();
                        $('#label-disable-'+id).show();
                    } else if(status == 2){                 
                        $('#label-disable-'+id).hide();
                        $('#label-enable-'+id).show();                  
                    }
                } else {
                    if(status == 1){  
                        $('#label-enable-'+id).show();
                        $('#label-disable-'+id).hide();                 
                    } else if(status == 2){                 
                        $('#label-disable-'+id).show();
                        $('#label-enable-'+id).hide();                  
                    }    
                }
                
            }    
        });
              
    });
        $('.tbl-vendor-products').on('click',function(){
            var status = $(this).val();

            var id = $(this).data("id");
            var vendorid = $(this).data("vendorid");
            var elementId = $(this).attr("id");

            $.ajax({
                type: "POST",
                url: site_url + 'Admin/Vendors/AddProductForVendor',
                data: {'productid':id,'vendorid':vendorid, 'status':status},
                success: function(response){
                    console.log("Response: ",response);
                    var obj = $.parseJSON(response);
                    $.notify({
                        title: '<strong>'+ jsUcfirst(obj.Class) +'! '+ ' </strong>',
                        message: obj.Msg
                    },{
                        type: obj.Class,
                        offset: {
                            x: 20,
                            y: 70
                        }
                    });
                    // ToastMessageshow(obj.Class, obj.Msg);

                    if(status == 1){
                        console.log("Ïn 1");
                        $("#"+elementId).val('2');
                        $("#"+elementId).attr('data-status', '2');
                    }else{
                        console.log("Ïn 2");
                        $("#"+elementId).val('1');
                        $("#"+elementId).attr('data-status', '1');
                    }

                    if(type == 'User'){
                        if(status == 1){
                            $('#label-enable-'+id).hide();
                            $('#label-disable-'+id).show();
                        } else if(status == 2){
                            $('#label-disable-'+id).hide();
                            $('#label-enable-'+id).show();
                        }
                    } else {
                        if(status == 1){
                            $('#label-enable-'+id).show();
                            $('#label-disable-'+id).hide();
                        } else if(status == 2){
                            $('#label-disable-'+id).show();
                            $('#label-enable-'+id).hide();
                        }
                    }

                }
            });

        });
        //To navigate to the sub level on click of a row
    $(".tableRowActions tr.hierarchy td:not(:nth-last-child(2), :last-child)").click(function(){
        var url = $(this).parent().attr("data-url");
        window.location.href = url;
    });   		

    $('.card-header h5 button').click(function(){
        $(this).find('i').toggleClass('fa-plus fa-minus');
      });
		
   });
})(jQuery);

function ToastMessageshow(msgclass, message) {
    //
    var Message = '';
    if(msgclass == 'success')
        Message = toastr.success(message);              
    else if(msgclass == 'danger')
        Message = toastr.success(message);              
    else if(msgclass == 'warning')
        Message = toastr.success(message);              
    else if(msgclass == 'info')
        Message = toastr.success(message);  
    else{
        Message = toastr.success(message);
    }    
    
    return Message;
}   

