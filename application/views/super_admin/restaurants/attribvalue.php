 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_products_attributes_values')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Product'); ?>"><?= _l('txt_add_product'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Attributes'); ?>"><?= _l('txt_listing_attributes'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Attribute'); ?>"><?= _l('txt_add_attribute'); ?></a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/AttribValues/').$AttributeID; ?>"><?= _l('txt_listing_attributes_values'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/AttribValue/').$AttributeID; ?>"><?= _l('txt_add_attribute_value'); ?></a>
                        </li>
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($attribute)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_product_attribute_value'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_product_attribute_value'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="attribute-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                
												<div class="form-group " >
													<label for="Val_Attribute" class="form-control-label"><?= _l('add_edit_attribute'); ?></label>
													<div class="">
														<select name="Val_Attribute"  id="Val_Attribute" class="form-control" required>
															<option value="0"><?= _l('select_attribute');?></option>
															<?php 
																
																		foreach($Attributes as $AttributeData)
																			{
															?>
																				<option value="<?= $AttributeData['PAttributeID'];?>" <?php if(!empty($attribvalue)) { echo ($attribvalue->V_AttributeID == $AttributeData['PAttributeID']) ? 'selected' : '';  } ?>>
																					<?= $AttributeData['A_Title'];?>
																				</option>
															<?php				
																			}
																
															?>
															
														</select>
													</div>
												</div>                                            
                                                <?php $attrs = (isset($attribvalue) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($attribvalue) ? $attribvalue->V_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Vtitle', 'add_edit_title',$value,'text',$attrs,array(),'','','','admin_placeholder_title','','',true,'','','',$data_atts,''); ?>
												
                                                <?php $attrs = (isset($attribvalue) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_value')
                                                    );
                                               
                                                $value=( !empty($attribvalue) ? $attribvalue->V_Value : '');?>  
                                                 
                                                <?= render_input( 'Val_Vvalue', 'add_edit_value',$value,'text',$attrs,array(),'','','','admin_placeholder_value','','',true,'','','',$data_atts,''); ?>
                                                                              
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Products/AttribValues/'.$AttributeID);?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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