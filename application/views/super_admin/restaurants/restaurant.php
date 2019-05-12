 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
                    <?php
                        if(!empty($restaurant)){
                            echo ("Edit: ".$restaurant->R_Name." - ");
                        }
                        echo _l('txt_restaurants');
                    ?>
                </h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($restaurant)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_restaurant'); ?></h4>
                                    <?php } else { ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_restaurant'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                            <?php
                                                $path = admin_url('Users/User');                                               
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="restaurant-form" onsubmit="return true"  enctype="multipart/form-data"> 

                                                <?php $attrs = (isset($restaurant) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); $data_atts = array('data-validation'=>'required', 'data-validation-error-msg'=> _l('please_enter_name')); $value=( !empty($restaurant) ? $restaurant->R_Name : '');?>
                                                <?= render_input( 'Val_Rname', 'add_edit_restaurant_name', $value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>

												<div class="form-group " >
													<label for="Val_FoodCategory" class="form-control-label"><?= _l('add_edit_restaurant_food_category'); ?></label>
													<div class="">
														<select name="Val_FoodCategory[]"  id="Val_FoodCategory" class="form-control" required multiple="multiple">
															<?php 
																if(!empty($categories))
																	{
																		$FoodCategories = json_decode($restaurant->R_FoodCategoryID);	
																		foreach($categories as $categoryData)
																			{
															?>
																				<option value="<?= $categoryData['CategoryID'];?>" <?php if(!empty($restaurant)) { echo (in_array($categoryData['CategoryID'],$FoodCategories) ? 'selected' : '');  } ?> >
																					<?= $categoryData['C_Name'];?>
																				</option>
															<?php				
																			}
																}
															?>

														</select>
													</div>
												</div>

                                                <h5><?= _l('Choose_restaurant_location'); ?></h5>
                                                <div id="map"></div>

                                                <?php $attrs = (isset($restaurant) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_restaurant_address')
                                                );

                                                $value=( !empty($restaurant) ? $restaurant->R_Address : '');?>  

                                                <?= render_input( 'Val_RAddress', '',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,'',true); ?>

                                                <input type="hidden" name="Val_RLatitude" id="Val_RLatitude"/>
                                                <input type="hidden" name="Val_RLongitude" id="Val_RLongitude"/>

                                                <input type="hidden" name="Val_RCountry" id="Val_RCountry"/>
                                                <input type="hidden" name="Val_RNeighborhood" id="Val_RNeighborhood"/>
                                                <input type="hidden" name="Val_RCity" id="Val_RCity"/>
                                                <input type="hidden" name="Val_RArea" id="Val_RArea"/>
                                                <!-- <input type="hidden" name="Val_RSearch_Area" id="Val_RSearch_Area"/> -->

												<?php $attrs = (isset($restaurant) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_description')
                                                );

                                                $value=( !empty($restaurant) ? $restaurant->R_Description : '');?>  

                                                <?= render_input( 'Val_Rdescription', 'add_edit_description',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>

												<?php $attrs = (isset($restaurant) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_deliverytime')
                                                );

                                                $value=( !empty($restaurant) ? $restaurant->R_DeliveryTime: '');?>  

                                                <?= render_input( 'Val_Rdeliverytime', 'add_edit_deliverytime',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												<?php $attrs = (isset($restaurant) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_pricefortwo')
                                                    );
                                               
                                                $value=( !empty($restaurant) ? $restaurant->R_PriceforTwo : '');?>  
                                                 
                                                <?= render_input( 'Val_Rpricefortwo', 'add_edit_pricefortwo',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                
                                                <div class="form-group">
                                                    <label><?= _l('featured_image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('featured_image'); ?></span>
                                                        <input type="file" name="Val_Rfeaturedimage"  id="Val_Rfeaturedimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $FeaturedImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($restaurant->R_FeaturedImage)){
                                                          $FeaturedImage = UPLOAD_RESTAURANTS_BASE_URL.$restaurant->RestaurantID.'/'.$restaurant->R_FeaturedImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $FeaturedImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 
                                                <input type="hidden" name="Val_Stype" value='1'>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Restaurants');?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqx1VKa2mZW6cmCg1O3j_M_YG0kQuxwQw"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript"> 

    $(document).ready(function(){

        <?php if(empty($restaurant)){ ?>
            var browsLatitude = '16.770087';
            var browsLongitude = '74.557063';

            if ("geolocation" in navigator){
                navigator.geolocation.getCurrentPosition(function(position){console.log("Hello");
                    browsLatitude = position.coords.latitude;
                    browsLongitude = position.coords.longitude;
                    initiateMap(browsLatitude, browsLongitude);
                },function(e){
                    // console.log("Ërror: ",e);
                    initiateMap(browsLatitude, browsLongitude);
                });
            }else{
                // console.log("Browser doesn't support geolocation!");
                initiateMap(browsLatitude, browsLongitude);
            }
        <?php }else{

            if(strlen($restaurant->R_Latitude) > 0 && strlen($restaurant->R_Longitude) > 0){ ?>
                var browsLatitude = <?php echo $restaurant->R_Latitude ?>;
                var browsLongitude = <?php echo $restaurant->R_Longitude ?>;
            <?php }else{ ?>
                var browsLatitude = '16.770087';
                var browsLongitude = '74.557063';
            <?php } ?>
            
            initiateMap(browsLatitude, browsLongitude);
        <?php } ?>
    });

    function initiateMap(browsLatitude, browsLongitude){

        console.log("Lat: "+browsLatitude+", Long: "+browsLongitude);

        var map;
        var marker;
        var myLatlng = new google.maps.LatLng(browsLatitude, browsLongitude);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();

        function initialize(){
            var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map"), mapOptions);

        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true 
        }); 

        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {

                console.log("result: ",results);

                if (results[0]) {

                    $('#Val_RLatitude,#Val_RLongitude').show();
                    $('#Val_RAddress').val(results[0].formatted_address);
                    $('#Val_RLatitude').val(marker.getPosition().lat());
                    $('#Val_RLongitude').val(marker.getPosition().lng());

                    var address = [];

                    results[0].address_components.forEach( function(el) {
                        address[el.types[0]] = el.short_name;
                    });

                    console.log("Location Result: ",address);

                    $('#Val_RCountry').val(address['country']);
                    $('#Val_RCity').val(address['administrative_area_level_2']);
                    $('#Val_RNeighborhood').val(address['neighborhood']);
                    if(!address['political']){
                        $('#Val_RArea').val(address['locality']);
                    }else{
                        $('#Val_RArea').val(address['political']);
                    }

                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    console.log("result: ",results);
                    if (results[0]) {

                            $('#Val_RAddress').val(results[0].formatted_address);
                            $('#Val_RLatitude').val(marker.getPosition().lat());
                            $('#Val_RLongitude').val(marker.getPosition().lng());

                            var address = [];

                            results[0].address_components.forEach( function(el) {
                                address[el.types[0]] = el.short_name;
                            });

                            console.log("Location Result: ",address);

                            $('#Val_RCountry').val(address['country']);
                            $('#Val_RCity').val(address['administrative_area_level_2']);
                            $('#Val_RNeighborhood').val(address['neighborhood']);
                            if(!address['political']){
                                $('#Val_RArea').val(address['locality']);
                            }else{
                                $('#Val_RArea').val(address['political']);
                            }

                            console.log("Country: "+$('#Val_RCountry').val()+", City: "+$('#Val_RCity').val()+", Area: "+$('#Val_RArea').val());

                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    }

</script>

<?= init_tail();?>