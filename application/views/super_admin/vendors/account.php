 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendor Account')?></h3>
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
                                     <h4 id="form-validation-basic-demo"><?= _l('Edit Vendor Account'); ?></h4>
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
                                                                                            
                                                
												<?php $attrs = (isset($Account) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter GST Name')
                                                    );
                                               
                                                $value=( !empty($Account) ? $Account->A_GSTName : '');?>  
                                                 
                                                <?= render_input( 'Val_Agstname', 'GST Name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												<?php $attrs = (isset($Account) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter GST Number')
                                                    );
                                               
                                                $value=( !empty($Account) ? $Account->A_GSTNumber : '');?>  
                                                 
                                                <?= render_input( 'Val_Agstnumber', 'GST Number',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Account) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Bank Account Name')
                                                    );
                                               
                                                $value=( !empty($Account) ? $Account->A_AccountName : '');?>  
                                                 
                                                <?= render_input( 'Val_Aaccountname', 'Account Name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												<div class="form-group">
													<label for="Val_Aaccounttype" class="form-control-label"><?= _l('Account Type'); ?> *</label>
													<div class="">
														<select name="Val_Aaccounttype"  id="Val_Aaccounttype" class="form-control" required>
															<option value=""><?= _l('Select Account Type');?></option>
															<option value="1" <?= $value =( !empty($Account) && $Account->A_AccountType == '1' ? 'Selected' : ''); ?>>Saving</option>
															<option value="2" <?= $value =( !empty($Account) && $Account->A_AccountType == '2' ? 'Selected' : ''); ?> >Current</option>
														</select>
													</div>
												</div>
												<?php $attrs = (isset($Account) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Bank Account Number')
                                                    );
                                               
                                                $value=( !empty($Account) ? $Account->A_AccountNumber : '');?>  
                                                 
                                                <?= render_input( 'Val_Aaccountnumber', 'Account Number',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Account) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Bank IFSC Name')
                                                    );
                                               
                                                $value=( !empty($Account) ? $Account->A_IFSCNumber : '');?>  
                                                 
                                                <?= render_input( 'Val_Aifscnumber', 'IFSC Name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												
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