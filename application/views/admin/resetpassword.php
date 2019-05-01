 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_reser_password')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                 <?php if($this->uri->segment(2) == 'MyProfile' || $this->uri->segment(2) == 'ResetPassword'){ ?>
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('MyProfile'); ?>"><?= _l('txt_profile'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('ResetPassword'); ?>"><?= _l('txt_change_password'); ?></a>
                        </li>                         
                    </ul>
                </div>
            
                <?php } else  if(empty($member)){ ?>
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Staffs'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Staffs/Staff'); ?>"><?= _l('txt_add_staff'); ?></a>
                        </li>                         
                    </ul>
                </div>
           <?php } ?>
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(empty($member)){ ?>
                                    <h4 id="form-validation-basic-demo"><?= _l('txt_reset_password'); ?></h4>  
                                    <?php } else if($this->uri->segment(2) == 'MyProfile'){ ?>
                                    <h4 id="form-validation-basic-demo"><?= _l('txt_account'); ?></h4>  
                                    <?php } else { ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_staff'); ?></h4>     
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                            <?php if(!empty($member)){
                                                $path = admin_url('MyProfile');
                                                $checked = true;
                                            } else {
                                                $path = admin_url('Staffs/Staff');
                                                $checked = true;
                                            }

                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" onsubmit="return true"  enctype="multipart/form-data"> 
                                                  
                                                <?php
                                                if(empty($member)){

                                                $attrs = (isset($member) ? array('required'=>false) : array('required'=>false)); 
                                                $data_atts = array(
                                                    'data-validation'=>'password',                                                    
                                                    'data-validation-error-msg'=> _l('please_enter_password'),
                                                    'data-validation'=> 'strength',
                                                    'data-validation-strength'=> '2'
                                                    );
                                                ?>   
                                                <?= render_input( 'Password', 'addd_edit_password','','password',$attrs,array(),'','','','admin_placeholder_password','','',true,'','','',$data_atts); } ?>


                                                <?php
                                                if(empty($member)){

                                                $attrs = (isset($member) ? array('required'=>'true') : array('required'=>'true')); 
                                                $data_atts = array(
                                                   'data-validation'=>'password',                                                    
                                                    'data-validation-error-msg'=> _l('please_enter_password'),
                                                    'data-validation'=> 'strength',
                                                    'data-validation-strength'=> '2'                                                     
                                                    );
                                                ?>   
                                              


                                                <?= render_input( 'CPassword', 'addd_edit_confirm_password','','password',$attrs,array(),'','','','admin_placeholder_confirm_password','','',true,'','','',$data_atts); } ?>
 
                                                       <input type="hidden" name="Action" value="Reset">                  
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup"><?= _l('btn_reset_password'); ?></button>
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
