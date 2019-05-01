function jsUcfirstCustom(string) 
			{
				return string.charAt(0).toUpperCase() + string.slice(1);
			}
$('#permission-btn').on('click',function(){
     
      if($('#select2-id-label-single').val() == ''){
        $('#select2-id-label-single').addClass('error');
        $('#select_validate').html('Please select any staff member');         
      } else {
        $("#permission-form").attr('onsubmit', 'return true');
      }  
    });
$("#category-form #Val_Type, #category-form #Val_Level").change(function(){
	
	var level = $("#category-form #Val_Level").val();		
	var url = admin_url + category_based_on_type_level_url;
	var type = $("#category-form #Val_Type").val();		
	
	if(type == '0' || level == '0')
	{
				$("#category-form #ParentInput").hide();			
	}
	else{
		if(level == '1')
			{
				$("#category-form #ParentInput").hide();			

			}
		else if(level == '2' || level == '3')
			{
			$("#category-form #ParentInput").show();			
			val_type 	= $("#category-form #Val_Type").val();	
			
			
			
			if(level == '2')
				val_level 	= '1';	
			else if(level == '3')	
				val_level 	= '2';	
			$.ajax({    
				type: "POST",
				url: url,
				data: { 'Val_Type':val_type,'Val_Level':val_level},
				success: function(response){ 
				
					var obj = $.parseJSON(response);    
					$.notify({
						title: '<strong>'+ jsUcfirstCustom(obj.Class) +'! '+ ' </strong>',
						message: obj.Msg
					},{
						type: obj.Class,
						offset: {
							x: 20,
							y: 70
						}
					});	
					//ToastMessageshow(obj.Class, obj.Msg);  
					
					// The .each() method is unnecessary here:
					 $('#Val_Parent').empty(); //remove all child nodes
					
					if($.isEmptyObject(obj.Data)){
							var NoOption = $('<option value="0">No Parent</option>');										
					
							$('#Val_Parent').append(NoOption);
						
						}
					else{
							var DefaultOption = $('<option value="0">Select Any Parent</option>');										
							$('#Val_Parent').append(DefaultOption);
							
							$( obj.Data ).each(function( index, element ) {
								var newOption = $('<option value="'+element.CategoryID+'">'+element.C_Name+'</option>');										
								$('#Val_Parent').append(newOption);
							});
				
						
						}	
				}    
			});

		}
	}
});	

$("#product-form #Val_Pattributes").change(function(){
	
	var attribute = $("#product-form #Val_Pattributes").val();		
	var url = admin_url + attribvalues_based_on_attribute_url;
	
	
	if(attribute == '0' || attribute == '')
	{
				$("#product-form #AttribValuesInput").hide();			
	}
	else{
			$("#product-form #AttribValuesInput").show();			
			
			$.ajax({    
				type: "POST",
				url: url,
				data: { 'Val_Attribute':attribute},
				success: function(response){ 
				
					var obj = $.parseJSON(response);    
					$.notify({
						title: '<strong>'+ jsUcfirstCustom(obj.Class) +'! '+ ' </strong>',
						message: obj.Msg
					},{
						type: obj.Class,
						offset: {
							x: 20,
							y: 70
						}
					});	
					//ToastMessageshow(obj.Class, obj.Msg);  
					
					// The .each() method is unnecessary here:
					 $('#Val_Pattributevalues').empty(); //remove all child nodes
					
					if($.isEmptyObject(obj.Data)){
							var NoOption = $('<option value="0">No Value</option>');										
					
							$('#Val_Pattributevalues').append(NoOption);
						
						}
					else{
							var DefaultOption = $('<option value="0">Select Any Value</option>');										
							// $('#Val_Pattributevalues').append(DefaultOption);
							
							$( obj.Data ).each(function( index, element ) {
								var newOption = $('<option value="'+element.PAValueID+'">'+element.V_Title+' - '+element.V_Value+'</option>');										
								$('#Val_Pattributevalues').append(newOption);
							});
				
						
						}	
				}    
			});

	}
});	/*
$("#restaurant-form #Val_Category").change(function(){
	
	var category = $("#restaurant-form #Val_Category").val();		
	var url = admin_url + category_based_on_category_url;
	
	
	if(category == '0' || category == '')
	{
				$("#restaurant-form #FoodCategoriesInput").hide();			
	}
	else{
			$("#restaurant-form #FoodCategoriesInput").show();			
			
			$.ajax({    
				type: "POST",
				url: url,
				data: { 'Val_Category':category},
				success: function(response){ 
				
					var obj = $.parseJSON(response);    
					$.notify({
						title: '<strong>'+ jsUcfirstCustom(obj.Class) +'! '+ ' </strong>',
						message: obj.Msg
					},{
						type: obj.Class,
						offset: {
							x: 20,
							y: 70
						}
					});	
					//ToastMessageshow(obj.Class, obj.Msg);  
					
					// The .each() method is unnecessary here:
					 $('#Val_Pattributevalues').empty(); //remove all child nodes
					
					if($.isEmptyObject(obj.Data)){
							var NoOption = $('<option value="0">No Value</option>');										
					
							$('#Val_Pattributevalues').append(NoOption);
						
						}
					else{
							var DefaultOption = $('<option value="0">Select Any Value</option>');										
							$('#Val_Pattributevalues').append(DefaultOption);
							
							$( obj.Data ).each(function( index, element ) {
								var newOption = $('<option value="'+element.PAValueID+'">'+element.V_Title+'</option>');										
								$('#Val_Pattributevalues').append(newOption);
							});
				
						
						}	
				}    
			});

	}});	
*/