 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendor About')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Vendors'); ?>"><?= _l('Vendors Listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Vendors/Vendor'); ?>"><?= _l('Add Vendor'); ?></a>
                        </li>                         
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($vendor)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_vendor'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_vendor'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Vendors/Vendor');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="vendor-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($vendor) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_business_name')
                                                    );
                                               
                                                $value=( !empty($About) ? $About->A_BusinessName : '');?>  
                                                 
                                                <?= render_input( 'Val_Abusinessname', 'Business Name',$value,'text',$attrs,array(),'','','','admin_placeholder_business_name','','',true,'','','',$data_atts,''); ?>

												 <div class="form-group">
													<label for="Val_Level" class="form-control-label"><?= _l('Business Presence'); ?> *</label>
													<div class="">
														<select name="Val_Abusinesspresence"  id="Val_Level" class="form-control" required>
															<option value="0"><?= _l('Select Business Presence');?></option>
															<option value="1" <?= $value =( !empty($About) && $About->A_BusinessPresence == '1' ? 'Selected' : ''); ?>>Website</option>
															<option value="2" <?= $value =( !empty($About) && $About->A_BusinessPresence == '2' ? 'Selected' : ''); ?> >Certificate</option>
															<option value="3" <?= $value =( !empty($About) && $About->A_BusinessPresence == '3' ? 'Selected' : ''); ?> >Online</option>
															<option value="4" <?= $value =( !empty($About) && $About->A_BusinessPresence == '4' ? 'Selected' : ''); ?> >None</option>
														</select>
													</div>
												</div>
												<?php $attrs = (isset($About) ? array('autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                   // 'data-validation'=>'required',
                                                   // 'data-validation-error-msg'=> _l('please_enter_profile_link')
                                                    );
                                               
                                                $value=( !empty($About) ? $About->A_ProfileLink : '');?>  
                                                 
                                                <?= render_input( 'Val_Aprofilelink', 'Profile Link',$value,'url',$attrs,array(),'','','','Profile Link','','',false,'','','',$data_atts,''); ?>
												<?php $attrs = (isset($About) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                 //   'data-validation'=>'required',
                                                  //  'data-validation-error-msg'=> _l('please_enter_profile_link')
                                                    );
                                               
                                                $value=( !empty($About) ? $About->A_FacebookLink : '');?>  
                                                 
                                                <?= render_input( 'Val_Afacebooklink', 'Facebook Link',$value,'url',$attrs,array(),'','','','Facebook Link','','',false,'','','',$data_atts,''); ?>
                                                <div class="form-group" id="">
													<label for="Val_Afacebooklink">Work Links</label>
													<?php
													
													$value=( !empty($About) ? json_decode($About->A_WorkLinks) : '');
													$value1=( !empty($value[0]) ? $value[0] : '');
													$value2=( !empty($value[1]) ? $value[1] : '');
													$value3=( !empty($value[2]) ? $value[2] : '');
													$value4=( !empty($value[3]) ? $value[3] : '');
													$value5=( !empty($value[4]) ? $value[4] : '');
													
													 ?>
													
													<input type="url" id="Val_Aworklinks1" name="Val_Aworklinks[]" class="form-control" value="<?= $value1; ?>" >
													<input type="url" id="Val_Aworklinks2" name="Val_Aworklinks[]" class="form-control" value="<?= $value2; ?>" >
													<input type="url" id="Val_Aworklinks3" name="Val_Aworklinks[]" class="form-control" value="<?= $value3; ?>" >
													<input type="url" id="Val_Aworklinks4" name="Val_Aworklinks[]" class="form-control" value="<?= $value4; ?>" >
													<input type="url" id="Val_Aworklinks5" name="Val_Aworklinks[]" class="form-control" value="<?= $value5; ?>" >
													</div>
												
												<?php $data_atts = array(
												   // 'data-validation'=>'required',
													//'data-validation-error-msg'=> _l('please_enter_mobile_no')
													);?>
												<?php $attrs = (!empty($About) ? array() :array()); ?>		
												<?php $value=( !empty($About) ? $About->A_PhoneNumber : '');?> 
												<?= render_input( 'Val_Aphonenumber', 'Phone Number',$value,'number',$attrs,array(),'','','','Phone Number','','',false,'','','',$data_atts); ?>

                                                <div class="form-group">
													<label for="Val_Atype" class="form-control-label"><?= _l('About Type'); ?></label>
													<div class="">
														<select name="Val_Atype"  id="Val_Atype" class="form-control" >
															<option value="0"><?= _l('Select Business Type');?></option>
															<option value="1" <?= $value =( !empty($About) && $About->A_Type == '1' ? 'Selected' : ''); ?>>Company</option>
															<option value="2" <?= $value =( !empty($About) && $About->A_Type == '2' ? 'Selected' : ''); ?> >Freelancer</option>
														</select>
													</div>
												</div>
												<?php $data_atts = array(
												   // 'data-validation'=>'required',
													//'data-validation-error-msg'=> _l('please_enter_mobile_no')
													);?>
												<?php $attrs = (!empty($About) ? array() : array()); ?>		
												<?php $value=( !empty($About) ? $About->A_ExperienceYear : '');?> 
												<?= render_input( 'Val_Aexperienceyear', 'Experience Year',$value,'number',$attrs,array(),'','','','Experience Year','','',false,'','','',$data_atts); ?>
												<?php $data_atts = array(
												   // 'data-validation'=>'required',
													//'data-validation-error-msg'=> _l('please_enter_mobile_no')
													);?>
												<?php $attrs = (!empty($About) ? array() : array()); ?>		
												<?php $value=( !empty($About) ? $About->A_ExperienceMonth : '');?> 
												<?= render_input( 'Val_Aexperiencemonth', 'Experience Month',$value,'number',$attrs,array(),'','','','Experience Month','','',false,'','','',$data_atts); ?>
												<?php $data_atts = array(
												   // 'data-validation'=>'required',
													//'data-validation-error-msg'=> _l('please_enter_mobile_no')
													);?>
												<?php $attrs = (!empty($About) ? array() : array()); ?>		
												<?php $value=( !empty($About) ? $About->A_Introduction : '');?> 
												<?= render_input( 'Val_Aintroduction', 'Introduction',$value,'text',$attrs,array(),'','','','Introduction','','',false,'','','',$data_atts); ?>
												<?php $data_atts = array(
												   // 'data-validation'=>'required',
													//'data-validation-error-msg'=> _l('please_enter_mobile_no')
													);?>
												<?php $attrs = (!empty($About) ? array() : array()); ?>		
												<?php $value=( !empty($About) ? $About->A_StartingPrice : '');?> 
												<?= render_input( 'Val_Astartingprice', 'Starting Price',$value,'number',$attrs,array(),'','','','Starting Price','','',false,'','','',$data_atts); ?>

												<div class="form-group" id="">
													<label for="Val_Afacebooklink">Specializations</label>
													<?php
													
													$value=( !empty($About) ? json_decode($About->A_Specialization) : '');
													$value1=( !empty($value[0]) ? $value[0] : '');
													$value2=( !empty($value[1]) ? $value[1] : '');
													$value3=( !empty($value[2]) ? $value[2] : '');
													$value4=( !empty($value[3]) ? $value[3] : '');
													$value5=( !empty($value[4]) ? $value[4] : '');
													
													 ?>
													
													<input type="text" id="Val_Aspecialization1" name="Val_Aspecialization[]" class="form-control" value="<?= $value1; ?>" >
													<input type="text" id="Val_Aspecialization2" name="Val_Aspecialization[]" class="form-control" value="<?= $value2; ?>" >
													<input type="text" id="Val_Aspecialization3" name="Val_Aspecialization[]" class="form-control" value="<?= $value3; ?>" >
													<input type="text" id="Val_Aspecialization4" name="Val_Aspecialization[]" class="form-control" value="<?= $value4; ?>" >
													<input type="text" id="Val_Aspecialization5" name="Val_Aspecialization[]" class="form-control" value="<?= $value5; ?>" >
													</div>
                                                <input type="hidden" name="vendor" value='1'>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Vendors');?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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