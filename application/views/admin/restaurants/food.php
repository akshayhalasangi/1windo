 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
                    <?php echo _l('txt_restaurant').": ".$restaurantName; ?>
                </h3>
                <!-- <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Restaurants/Foods/'.$RestaurantID) ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button> -->
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($food)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_restaurant_food'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_restaurant_food'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="food-form" onsubmit="return true"  enctype="multipart/form-data"> 
												
                                                <input type="hidden" id="Val_Restaurant" name="Val_Restaurant" value="<?= $RestaurantID ?>">
												<div class="form-group" >
													<label for="Val_Category" class="form-control-label"><?= _l('add_edit_food_category'); ?></label>
													<div class="">
														<select name="Val_Category"  id="Val_Category" class="form-control" required>
															<option value="0"><?= _l('select_category');?></option>
															<?php 
																
																		foreach($SubCategories as $SubCategoryData)
																			{
															?>
																				<option value="<?= $SubCategoryData['CategoryID'];?>" <?php if(!empty($food)) { echo ($food->F_CategoryID == $SubCategoryData['CategoryID']) ? 'selected' : '';  } ?> >
																					<?= $SubCategoryData['C_Name'];?>
																				</option>
															<?php				
								
																			}
																
															?>
															
														</select>
													</div>
												</div>											 
                                                <?php $attrs = (isset($food) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($food) ? $food->F_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Ftitle', 'add_edit_food_name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
                                                <?php $attrs = (isset($food) ? array('autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>null,
                                                    'data-validation-error-msg'=> _l('please_enter_description')
                                                    );
                                               
                                                $value=( !empty($food) ? $food->F_Description : '');?>  
                                                 
                                                <?= render_input( 'Val_Fdescription', 'add_edit_description',$value,'text',$attrs,array(),'','','','','','',false,'','','',$data_atts,''); ?>
												
											<?php $attrs = (isset($food) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_price')
                                                    );
                                               
                                                $value=( !empty($food) ? $food->F_Price : '');?>	
												 <?= render_input( 'Val_Fprice', 'add_edit_price',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                               <div class="form-group">
													<label for="Val_Ftype" class="form-control-label"><?= _l('add_edit_type'); ?></label>
													<div class="">
														<select name="Val_Ftype"  id="Val_Ftype" class="form-control" required>
															<option value="0"><?= _l('select_type');?></option>
															<option value="1" <?= $value =( !empty($food) && $food->F_Type == '1' ? 'Selected' : ''); ?>>Veg.</option>
															<option value="2" <?= $value =( !empty($food) && $food->F_Type == '2' ? 'Selected' : ''); ?> >Non Veg.</option>
														</select>
													</div>
												</div>
												<div class="form-group">
                                                    <label><?= _l('display_image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('display_image'); ?></span>
                                                        <input type="file" name="Val_Fdisplayimage"  id="Val_Fdisplayimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($food->F_DisplayImage)){
                                                          $DisplayImage = UPLOAD_RESTAURANTS_FOODS_BASE_URL.$food->RFoodID.'/'.$food->F_DisplayImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 
												                                  
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Restaurants/Foods/'.$RestaurantID);?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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
<?= init_tail();?>