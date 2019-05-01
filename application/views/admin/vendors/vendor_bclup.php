 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendor')?></h3>
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
                                     <h4 id="form-validation-basic-demo"><?= _l('Vendor Edit'); ?></h4>
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
                                                    'data-validation-error-msg'=> _l('please_enter_first_name')
                                                    );
                                               
                                                $value=( !empty($vendor) ? $vendor->V_FirstName : '');?>  
                                                 
                                                <?= render_input( 'Val_Vfirstname', 'addd_edit_first_name',$value,'text',$attrs,array(),'','','','admin_placeholder_first_name','','',true,'','','',$data_atts,''); ?>

												 <?php $attrs = (isset($vendor) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_last_name')
                                                    );
                                               
                                                $value=( !empty($vendor) ? $vendor->V_LastName : '');?>  
                                                 
                                                <?= render_input( 'Val_Vlastname', 'addd_edit_last_name',$value,'text',$attrs,array(),'','','','admin_placeholder_last_name','','',true,'','','',$data_atts,''); ?>
                                                
                                                <div class="form-group">
                                                    <label ><?= _l('Business Category'); ?></label>
                                                                <select  name="Val_Category"  id="Val_Category" class="form-control select-country-code ks-select">
                                                                     
                                                                        <option value=""><?= _l('Select Category');?></option>
                                                                        <?php 
                                                                        if(!empty($Categories)) { 
                                                                            foreach($Categories as $Category) { 
                                                                                $select = '';
                                                                                if(!empty($vendor)){
                                                                                    if($Category['CategoryID'] == $vendor->V_CategoryID)   {
                                                                                    $select = 'selected';
                                                                                   }       
                                                                                }
                                                                               
                                                                        ?>
                                                                        <option value="<?php echo $Category['CategoryID']; ?>" <?= $select; ?>><?= $Category['C_Name']; ?></option>
                                                                        <?php }  }?>
                                                                     
                                                                    
                                                                </select> 
                                    
                                                </div>
                                                <div class="form-group mob-form-group">
                                                    <label ><?= _l('addd_edit_mobile_no'); ?></label>
                                                    <div class="form-inline mob-form-input">
                                                         <div class="form-group form-group-country-code">
                                                            <label for="select2-id-label-single">     
															<?php if( empty($vendor) ) { ?>		                                                                
                                                                <select id="select2-id-label-single" name="Val_Vcountrycode"  id="CountryCode" class="form-control select-country-code ks-select">
                                                                     
                                                                        <option value=""><?= _l('select_country');?></option>
                                                                        <?php $Countries = getCountryCode(); 
                                                                        if(!empty($Countries)) { 
                                                                            foreach($Countries as $country) { 
                                                                                $select = '';
                                                                                if(!empty($vendor)){
                                                                                    if('+'.$country['CountryPhoneCode'] == $vendor->V_CountryCode)   {
                                                                                    $select = 'selected';
                                                                                   }       
                                                                                }
                                                                               
                                                                        ?>
                                                                        <option value="+<?php echo $country['CountryPhoneCode']; ?>" <?= $select; ?>><?= $country['CountryNiceName']; ?></option>
                                                                        <?php }  }?>
                                                                     
                                                                    
                                                                </select> 
															<?php } ?>	
                                                            </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <?php $data_atts = array(
                                                                'data-validation'=>'required',
                                                                'data-validation-error-msg'=> _l('please_enter_mobile_no')
                                                                );?>
														<?php $attrs = (!empty($vendor) ? array('required'=>'true','readonly' => true,'disabled'=>true) :array('required'=>'true')); ?>		
                                                        <?php $value=( !empty($vendor) ? $vendor->V_CountryCode.$vendor->V_Mobile : '');?> 
                                                        <?= render_input( 'Val_Vmobilenumber', '',$value,'text',$attrs,array(),'','','','admin_placeholder_mobile_no','','',true,'','','',$data_atts); ?>

                                                        </div>


                                                    </div>
                                    
                                                </div>
                                             
                                                

                                                <?php $attrs = (!empty($vendor) ? array('readonly' => true) :array('required'=>'true')); 
                                                $value=( !empty($vendor) ? $vendor->V_Email : '');
                                                $data_atts = array(
                                                   // 'data-validation'=>'email',                                                    
                                                   // 'data-validation-error-msg'=> _l('please_enter_email')
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_Vemailaddress', 'addd_edit_email',$value,'email',$attrs,array(),'','','','admin_placeholder_email','','',true,'','','',$data_atts); ?>
												
												 <div class="form-group">
                                                    <label><?= _l('Profile Image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('Profile Image'); ?></span>
                                                        <input type="file" name="Val_ProfileImage"  id="Val_ProfileImage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($vendor->V_ProfileImage)){
                                                          $DisplayImage = UPLOAD_VENDOR_BASE_URL.$vendor->VendorID.'/'.$vendor->V_ProfileImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 
												<?php 
												
												if(!empty($vendor)){
												if($vendor->V_ProfileStatus == '2')
													{
														if($vendor->V_VerificationStatus == '2')
															{
													
												?>
												<div class="form-group">
													<label for="Val_Status" class="form-control-label"><?= _l('Status'); ?> *</label>
													<div class="">
														<select name="Val_Status"  id="Val_Status" class="form-control" required>
															<option value=""><?= _l('Select Status');?></option>
															<option value="3" <?= $value =( !empty($vendor) && $vendor->V_VerificationStatus == '3' ? 'Selected' : ''); ?>>Approve</option>
															<option value="4" <?= $value =( !empty($vendor) && $vendor->V_VerificationStatus == '4' ? 'Selected' : ''); ?> >Reject</option>
															
														</select>
													</div>
												</div>
												<?php 
															}
													}	
												}
												?>
												

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