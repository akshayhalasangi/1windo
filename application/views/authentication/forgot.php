<?= init_head(true);?> 
    <!----- START SECTION ----->
    <div class="ks-page">
     
    <div class="ks-page-content">
        <div class="ks-logo"><?= _l('title_1windo'); ?></div>

        <div class="card panel panel-default ks-light ks-panel ks-login">
            <div class="card-block">                
                <form class="form-container" action="<?= base_url('Authentication/ForgotPassword');?>" method="POST">
                    <h4 class="ks-header"></h4>
                     <h4 class="ks-header">
                        <?= _l('title_forgot_password'); ?>                        
                    </h4>
                    <?php //if ($flag == 3 || $flag == 4 || $flag == 5) { ?>
                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); //} ?> 

                    <?php
                    $data_atts = array(
                        'data-validation'=>'email',                                                    
                        'data-validation-error-msg'=> _l('please_enter_email')
                        );
                    ?>
                    
                    <?php $attrs = (isset($member) ? array('required'=>'true') : array('required'=>'true')); ?>   
                    <?= render_input( 'Email', '','','email',$attrs,array(),'','','','admin_placeholder_email','la la-at','',true,'','','',$data_atts,'input-icon icon-left icon-lg icon-color-primary'); ?>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><?= _l('title_submit'); ?></button>
                    </div>
                    <div class="ks-text-center">
                        <span class="text-muted"><?= _l('txt_remember_it'); ?></span> <a href="<?= base_url('Authentication');?>"><?= _l('txt_login')?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= init_tail(true);?>  
