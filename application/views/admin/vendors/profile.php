 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendor Profile')?></h3>
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
                                    <?php if(!empty($Profile)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('Edit Vendor Profile'); ?></h4>
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
                                                                                            
                                                <div class="form-group">
													<label for="Val_Pidcardtype" class="form-control-label"><?= _l('ID Card Type'); ?> *</label>
													<div class="">
														<select name="Val_Pidcardtype"  id="Val_Pidcardtype" class="form-control" required>
															<option value=""><?= _l('Select ID Card Type');?></option>
															<option value="1" <?= $value =( !empty($Profile) && $Profile->P_IDCardType == '1' ? 'Selected' : ''); ?>>Voters Card</option>
															<option value="2" <?= $value =( !empty($Profile) && $Profile->P_IDCardType == '2' ? 'Selected' : ''); ?> >Driving Card</option>
															<option value="3" <?= $value =( !empty($Profile) && $Profile->P_IDCardType == '3' ? 'Selected' : ''); ?> >Pan Card</option>
															
														</select>
													</div>
												</div>
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Card name')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_IDCardName : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcardname', 'ID Card Name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Card number')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_IDCardNumber : '');?>  
                                                 
                                                <?= render_input( 'Val_Pidcardnumber', 'ID Card No.',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<div class="form-group">
                                                    <label><?= _l('Front Image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('Front Image'); ?></span>
                                                        <input type="file" name="Val_Pidcardfrontimage"  id="Val_Pidcardfrontimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($Profile->P_IDCardFrontImage)){
                                                          $DisplayImage = UPLOAD_VENDORS_BASE_URL.$Profile->P_VendorID.'/'.$Profile->P_IDCardFrontImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 
												<div class="form-group">
                                                    <label><?= _l('Back Image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('Back Image'); ?></span>
                                                        <input type="file" name="Val_Pidcardbackimage"  id="Val_Pidcardbackimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($Profile->P_IDCardBackImage)){
                                                          $DisplayImage = UPLOAD_VENDORS_BASE_URL.$Profile->P_VendorID.'/'.$Profile->P_IDCardBackImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Guardian name')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_GuardianName : '');?>  
                                                 
                                                <?= render_input( 'Val_Pguardianname', 'Guardian Name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												 <div class="form-group">
													<label for="Val_Pgender" class="form-control-label"><?= _l('Gender'); ?> *</label>
													<div class="">
														<select name="Val_Pgender"  id="Val_Pgender" class="form-control" required>
															<option value="0"><?= _l('Select Gender');?></option>
															<option value="1" <?= $value =( !empty($Profile) && $Profile->P_Gender == '1' ? 'Selected' : ''); ?>>Male</option>
															<option value="2" <?= $value =( !empty($Profile) && $Profile->P_Gender == '2' ? 'Selected' : ''); ?> >Female</option>
															<option value="3" <?= $value =( !empty($Profile) && $Profile->P_Gender == '3' ? 'Selected' : ''); ?> >Other</option>
														</select>
													</div>
												</div>
												 <div class="form-group" id="">
													<label for="Val_Afacebooklink">BirthDate *</label>
													<?php
													
													$value=( !empty($Profile) ? json_decode($Profile->P_BirthDate) : '');
													
													
													 ?>
													
													<input type="date" id="Val_Pbirthdate" name="Val_Pbirthdate" class="form-control" value="<?= $value; ?>" required>
													
												</div>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter permanent building')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_PermanentBuilding : '');?>  
                                                 
                                                <?= render_input( 'Val_Ppermanentbuilding', 'Permanent Building',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter permanent locality')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_PermanentLocality : '');?>  
                                                 
                                                <?= render_input( 'Val_Ppermanentlocality', 'Permanent Locality',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter permanent city')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_PermanentCity : '');?>  
                                                 
                                                <?= render_input( 'Val_Ppermanentcity', 'Permanent City',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter permanent state')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_PermanentState : '');?>  
                                                 
                                                <?= render_input( 'Val_Ppermanentstate', 'Permanent State',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter permanent pincode')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_PermanentPincode : '');?>  
                                                 
                                                <?= render_input( 'Val_Ppermanentpincode', 'Permanent Pincode',$value,'number',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter current building')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_CurrentBuilding : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcurrentbuilding', 'Current Building',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter current locality')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_CurrentLocality : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcurrentlocality', 'Current Locality',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter current city')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_CurrentCity : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcurrentcity', 'Current City',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter current state')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_CurrentState : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcurrentstate', 'Current State',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Profile) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter current pincode')
                                                    );
                                               
                                                $value=( !empty($Profile) ? $Profile->P_CurrentPincode : '');?>  
                                                 
                                                <?= render_input( 'Val_Pcurrentpincode', 'Current Pincode',$value,'number',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
		
												
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