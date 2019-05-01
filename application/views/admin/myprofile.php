<?= init_head(); ?>
<div class="wrapper">   
        <!----- SIDEBAR ----->
        <?php $this->load->view('admin/includes/sidebar'); ?>       
         <div class="right_part">        
             <?php $this->load->view('admin/includes/topbar'); ?> 
            <!----- START SECTION ----->
            <section class="page-main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="content-box">                            
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form class="form bg-white repeater form-profile" id="signup-form" method="POST" onsubmit="return false;">    
                                        <div class="form-content">
                                                 
                                        <?php $value=( isset($adminuser) ? $adminuser->AFirstName : ''); ?>
                                        <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('autofocus'=>true,'required'=>'true')); ?><?= render_input( 'FirstName', 'admin_add_edit_firstname',$value,'text',$attrs,array(),'','','','admin_placeholder_firstname','','',true,'','','please_enter_first_name'); ?>

                                        <?php $value=( isset($adminuser) ? $adminuser->ALastName : ''); ?>
                                        <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('required'=>'true')); ?>
                                        <?= render_input( 'LastName', 'admin_add_edit_lastname',$value,'text',$attrs,array(),'','','','admin_placeholder_lastname','','',true,'','','please_enter_last_name'); ?>

                                        <?php $value=( isset($adminuser) ? $adminuser->AEmail : ''); ?>
                                        <?php $attrs = (isset($adminuser) ? array('required'=>'true','readonly' =>'true') : array('required'=>'true','readonly' =>'true')); ?>
                                        <?= render_input( 'Email', 'admin_add_edit_email',$value,'text',$attrs,array(),'','','','admin_placeholder_email','','',true,'','','please_enter_email'); ?>

                                        <div class="bottom">
                                            <div class="button-content">                                            
                                                <button class="button button-blue btn-toast" id="signup-btn"><?= _l('txt_save_changes');?></button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form class="form bg-white form-profile" id="reset-form" method="POST" onsubmit="return false;">  
                                    <div class="form-content">  
                                        <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('autofocus'=>true,'required'=>'true')); ?>
                                        <?= render_input( 'Password', 'admin_add_edit_password','','password',$attrs,array(),'','','','admin_auth_set_password_placeholder','','',true,'','','please_enter_password'); ?>
                                       
                                        <?php $attrs = (isset($adminuser) ? array('required'=>'true') : array('autofocus'=>true,'required'=>'true')); ?>
                                        <?= render_input( 'CPassword', 'admin_auth_set_password_repeat','','password',$attrs,array(),'','','','admin_auth_set_password_repeat_placeholder','','',true,'','','please_enter_retype_password'); ?>
                                        
                                        <?php $value=( isset($adminuser) ? $adminuser->AEmail : ''); ?>
                                        <input type="hidden" value="Reset" name="Action" />

                                        <div class="bottom">
                                            <div class="button-content">
                                                <button class="button button-blue button-bg-none" type="reset"><?= _l('btn_cancel');?></button>
                                                <button class="button button-blue btn-toast" id="reset-btn"><?= _l('btn_change_password'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            
    </div>
</div>
 
<?= init_tail(); ?>
 