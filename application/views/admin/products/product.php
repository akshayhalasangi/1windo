 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
					<?php
						if(!empty($product)){
							echo ("Edit: ".$product->P_Name." - ");
						}
						echo _l('txt_products');
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
                                    <?php if(!empty($product)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_product'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_product'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="product-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <div class="form-group hide" id="ParentInput">
													<input type="hidden" value="<?= $çategoryId ?>" name="Val_Category" id="Val_Category">
												</div>
												
												
												<?php $attrs = (isset($product) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($product) ? $product->P_Name : '');?>  
                                                 
                                                <?= render_input( 'Val_Pname', 'add_edit_name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($product) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_description')
                                                    );
                                               
                                                $value=( !empty($product) ? $product->P_Description : '');?>  
                                                 
                                                <?= render_input( 'Val_Pdescription', 'add_edit_description',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												<?php $attrs = (isset($product) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_price')
                                                    );
                                               
                                                $value=( !empty($product) ? $product->P_Name : '');?>  
                                                 
                                                <?= render_input( 'Val_Pprice', 'add_edit_price',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<div class="form-group">
													<label for="Val_Pattributes" class="form-control-label"><?= _l('add_edit_attribute'); ?></label>
													<div class="">
														<select name="Val_Pattributes"  id="Val_Pattributes" class="form-control" required>
															<option value="0"><?= _l('select_attributes');?></option>
															<?php 
																foreach($attributes as $AttributeData)
																{
															?>
																	<option value="<?= $AttributeData['PAttributeID'];?>" <?php if(!empty($product)) { echo ($product->P_Attributes == $AttributeData['PAttributeID']) ? 'selected' : '';  } ?> >
																		<?= $AttributeData['A_Title'];?>
																	</option>
															<?php				
																}
															?>
														</select>
													</div>
												</div>
												
												<?php 
													if(!empty($product) && !empty($product->P_AttributeValues) && $product->P_Attributes != 0)
														{
															$Style = 'display:block;';
														}
													else
														{
															$Style = 'display:none;';
														}	
												?>
                                                
												<div class="form-group hide" id="AttribValuesInput" style="<?= $Style; ?>">
													<label for="Val_Pattributevalues" class="form-control-label"><?= _l('add_edit_attribute_values')." "._l('txt_select_any'); ?></label>
													<div class="">
														<select name="Val_Pattributevalues[]"  id="Val_Pattributevalues" class="form-control" multiple="multiple">
															<!-- <option value="0"><?= _l('select_attribute_values');?></option> -->
                                                            <?php 
																if(!empty($attribvalues) && !empty($product->P_AttributeValues))
																	{
                                                                        $AttributeValues = json_decode($product->P_AttributeValues);

																		foreach($attribvalues as $ValueData)
																		{
															?>
																			<option value="<?= $ValueData['PAValueID'];?>" <?php if(!empty($product)) { echo (in_array($ValueData['PAValueID'], $AttributeValues)) ? 'selected' : '';  } ?> >
																				<?= $ValueData['V_Title']. ' - '.$ValueData['V_Value'] ;?>
																			</option>
															<?php				
																		}
																}
															?>
															
														</select>
													</div>
												</div>
                                                
                                                <div class="form-group">
                                                    <label><?= _l('featured_image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('featured_image'); ?></span>
                                                        <input type="file" name="Val_Pfeaturedimage"  id="Val_Pfeaturedimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $FeaturedImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($product->P_FeaturedImage)){
                                                          $FeaturedImage = UPLOAD_PRODUCTS_BASE_URL.$product->ProductID.'/'.$product->P_FeaturedImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $FeaturedImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                                </div> 
                                                <input type="hidden" name="Val_Stype" value='1'>

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Products/ProductList/'.$çategoryId); ?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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