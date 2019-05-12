 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_users')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Users'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Users/User'); ?>"><?= _l('txt_add_user'); ?></a>
                        </li> 
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($user)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_user'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_user'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="user-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($user) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_full_name')
                                                    );
                                               
                                                $value=( !empty($user) ? $user->U_FullName : '');?>  
                                                 
                                                <?= render_input( 'Val_FullName', 'addd_edit_full_name',$value,'text',$attrs,array(),'','','','admin_placeholder_full_name','','',true,'','','',$data_atts,''); ?>

                                                
                                                
                                                <div class="form-group mob-form-group">
                                                    <label ><?= _l('addd_edit_mobile_no'); ?></label>
                                                    <div class="form-inline mob-form-input">
                                                         <div class="form-group form-group-country-code">
                                                            <label for="select2-id-label-single">                                                                     
                                                                <select id="select2-id-label-single" name="CountryCode" id="CountryCode" class="form-control select-country-code ks-select">
                                                                     
                                                                        <option value=""><?= _l('select_country');?></option>
                                                                        <?php $Countries = getCountryCode(); 
                                                                        if(!empty($Countries)) { 
                                                                            foreach($Countries as $country) { 
                                                                                $select = '';
                                                                                if(!empty($user)){
                                                                                    if('+'.$country['CountryPhoneCode'] == $user->U_CountryCode)   {
                                                                                    $select = 'selected';
                                                                                   }       
                                                                                }
                                                                               
                                                                        ?>
                                                                        <option value="<?php echo $country['CountryPhoneCode']; ?>" <?= $select; ?>><?= $country['CountryNiceName']; ?></option>
                                                                        <?php }  }?>
                                                                     
                                                                    
                                                                </select>
                                                            </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <?php $data_atts = array(
                                                                'data-validation'=>'required',
                                                                'data-validation-error-msg'=> _l('please_enter_mobile_no')
                                                                );?>
                                                        <?php $value=( !empty($user) ? $user->U_Mobile : '');?> 
                                                        <?= render_input( 'Val_Mobile', '',$value,'text',$attrs,array(),'','','','admin_placeholder_mobile_no','','',true,'','','',$data_atts); ?>

                                                        </div>


                                                    </div>
                                    
                                                </div>
                                             
                                                

                                                <?php $attrs = (!empty($user) ? array('required'=>'true','readonly' => true) :array('required'=>'true')); 
                                                $value=( !empty($user) ? $user->U_Email : '');
                                                $data_atts = array(
                                                    'data-validation'=>'email',                                                    
                                                    'data-validation-error-msg'=> _l('please_enter_email')
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_Email', 'addd_edit_email',$value,'email',$attrs,array(),'','','','admin_placeholder_email','','',true,'','','',$data_atts); ?>


                                                <?php
                                                if(empty($user)){

                                                $attrs = (isset($user) ? array('required'=>'true') : array('required'=>'true')); 
                                                $data_atts = array(
                                                    'data-validation'=>'password',                                                    
                                                    'data-validation-error-msg'=> _l('please_enter_password'),
                                                    'data-validation'=> 'strength',
                                                    'data-validation-strength'=> '2'
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_Password', 'addd_edit_password','','password',$attrs,array(),'','','','admin_placeholder_password','','',true,'','','',$data_atts); 
                                                } ?>
                                                
                                                <input type="hidden" name="user" value='1'>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="reset" class="btn btn-outline-primary ks-light"><?= _l('btn_reset'); ?></button>
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