<?= init_head(true);?>
    <!----- START SECTION ----->
    <div class="ks-page">
     
    <div class="ks-page-content">
        <div class="ks-logo"><?= _l('title_1windo'); ?></div>

        <div class="card panel panel-default ks-light ks-panel ks-login">
            <div class="card-block">                
                <form class="form-container" id="login_form" action="<?= base_url('Authentication/ResetPassword');?>" method="POST">
                    <h4 class="ks-header"><?= _l('title_reset_password'); ?></h4>
                       
                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');  ?>      
                    
                    <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('autofocus'=>true,'required'=>'true')); ?>
                    <?php $data_atts = array(
                                'data-validation'=>'password',                                                    
                                'data-validation-error-msg'=> _l('please_enter_password'),
                                'data-validation'=> 'strength',
                                'data-validation-strength'=> '2'
                                );
                            ?>  


                    <?= render_input( 'Password', '','','password',$attrs,array(),'','border-top-none','','admin_auth_set_password_placeholder','','6',true,'','','',$data_atts,'input-icon icon-left icon-lg icon-color-primary'); ?>
                    
                    
                    
                    <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('autofocus'=>true,'required'=>'true')); ?>
                    <?php $data_atts = array(
                                'data-validation'=>'password',                                                    
                                'data-validation-error-msg'=> _l('please_enter_password'),
                                'data-validation'=> 'strength',
                                'data-validation-strength'=> '2'
                                );
                            ?>  

                    <?= render_input( 'NewPassword', '','','password',$attrs,array(),'','','','admin_auth_set_password_repeat_placeholder','','6',true,'','','',$data_atts,'input-icon icon-left icon-lg icon-color-primary'); ?>
                    
                    <input type="hidden" name='Email' value="<?php echo $this->input->get('email');?>">
 
                     
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><?= _l('btn_reset_password'); ?></button>
                    </div>
                     
                    <div class="ks-text-center">
                        <a href="<?= base_url('Authentication'); ?>"><?= _l('btn_login'); ?></a>
                    </div>                
                </form>
            </div>
        </div>
    </div>
</div>
<?= init_tail(true);?>    