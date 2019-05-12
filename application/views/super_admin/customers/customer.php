 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_customers')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <!-- <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Customers'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link current active" href="<?= admin_url('Customers/Customer'); ?>"><?= _l('txt_add_customer'); ?></a>
                        </li> 
                    </ul>
                </div> -->
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($customer)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_customer'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_customer'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="customer-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($customer) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_first_name')
                                                    );
                                               
                                                $value=( !empty($customer) ? $customer->C_FirstName : '');?>  
                                                 
                                                <?= render_input( 'Val_Cfirstname', 'addd_edit_first_name',$value,'text',$attrs,array(),'','','','admin_placeholder_first_name','','',true,'','','',$data_atts,''); ?>

												 <?php $attrs = (isset($customer) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_last_name')
                                                    );
                                               
                                                $value=( !empty($customer) ? $customer->C_LastName : '');?>  
                                                 
                                                <?= render_input( 'Val_Clastname', 'addd_edit_last_name',$value,'text',$attrs,array(),'','','','admin_placeholder_last_name','','',true,'','','',$data_atts,''); ?>
                                                
                                                
                                                <div class="form-group mob-form-group">
                                                    <label ><?= _l('addd_edit_mobile_no'); ?></label>
                                                    <div class="form-inline mob-form-input">
                                                         <div class="form-group form-group-country-code">
                                                            <label for="select2-id-label-single">     
															<?php if( empty($customer) ) { ?>		                                                                
                                                                <select id="select2-id-label-single" name="Val_Ccountrycode"  id="CountryCode" class="form-control select-country-code ks-select">
                                                                     
                                                                        <option value=""><?= _l('select_country');?></option>
                                                                        <?php $Countries = getCountryCode(); 
                                                                        if(!empty($Countries)) { 
                                                                            foreach($Countries as $country) { 
                                                                                $select = '';
                                                                                if(!empty($customer)){
                                                                                    if('+'.$country['CountryPhoneCode'] == $customer->C_CountryCode)   {
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
														<?php $attrs = (!empty($customer) ? array('required'=>'true','readonly' => true,'disabled'=>true) :array('required'=>'true')); ?>		
                                                        <?php $value=( !empty($customer) ? $customer->C_CountryCode.$customer->C_Mobile : '');?> 
                                                        <?= render_input( 'Val_Cmobilenumber', '',$value,'text',$attrs,array(),'','','','admin_placeholder_mobile_no','','',true,'','','',$data_atts); ?>

                                                        </div>


                                                    </div>
                                    
                                                </div>
                                             
                                                

                                                <?php $attrs = (!empty($customer) ? array('required' => true) :array('required'=>'true')); 
                                                $value=( !empty($customer) ? $customer->C_Email : '');
                                                $data_atts = array(
                                                   // 'data-validation'=>'email',                                                    
                                                   // 'data-validation-error-msg'=> _l('please_enter_email')
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_Cemailaddress', 'addd_edit_email',$value,'email',$attrs,array(),'','','','admin_placeholder_email','','',true,'','','',$data_atts); ?>


                                                <input type="hidden" name="customer" value='1'>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Customers');?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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