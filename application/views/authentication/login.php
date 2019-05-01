<?= init_head(true);?>
    <!----- START SECTION ----->
    <div class="ks-page">
     
    <div class="ks-page-content">
        <div class="ks-logo"><img src="http://1windo.com/App/uploads/1windo.png" alt="1Windo Logo" /> <!--<?= _l('title_1windo'); ?>--></div>
        <div class="card panel panel-default ks-light ks-panel ks-login">
            <div class="card-block">                
                <form class="form-container" id="login_form" action="<?= base_url('Authentication/Admin');?>" method="POST">
                    <h4 class="ks-header"><?= _l('title_login'); ?></h4>
                     
                    <?php  if($flag == 9 || $flag == 10 || $flag == 11){?>  
                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); } ?>      
                    <?php $attrs = (isset($member) ? array('required'=>'true') : array('required'=>'true')); ?>  

                    <?php
                    $data_atts = array(
                        'data-validation'=>'email',                                                    
                        'data-validation-error-msg'=> _l('please_enter_email')
                        );
                    ?>

                    <?= render_input( 'Email', '','','email',$attrs,array(),'','','','admin_placeholder_email','la la-at','',true,'','','',$data_atts,'input-icon icon-left icon-lg icon-color-primary'); ?>
                    
                    
                    <?php $data_atts = array(
                        'data-validation'=>'required',                                                    
                        'data-validation-error-msg'=> _l('please_enter_password_single'),
                       // 'data-validation'=> 'strength',
                        //'data-validation-strength'=> '2'
                        );
                    ?>  

                    <?php $attrs = (isset($member) ? array('required'=>'true') : array('required'=>'true')); ?>   
                    <?= render_input( 'Password', '','','password',$attrs,array(),'','','','admin_placeholder_password','la la-key','',true,'','','',$data_atts,'input-icon icon-left icon-lg icon-color-primary'); ?>
 
                     <div class="input-icon icon-right icon icon-lg icon-color-primary">                            
                             <label> <input type="checkbox" id="Remember" name="Remember"><span class="cr" style="margin-left:  6px;"><i class="cr-icon fa fa-check"></i></span><?= _l('txt_remeber')?></label>
                        </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><?= _l('title_login'); ?></button>
                    </div>
                     
                    <div class="ks-text-center">
                        <a href="<?= base_url('Authentication/ForgotPassword'); ?>"><?= _l('txt_forgot_your_password'); ?></a>
                    </div>                
                </form>
            </div>
        </div>
    </div>
</div>
   
<?= init_tail(true);?>    
 
