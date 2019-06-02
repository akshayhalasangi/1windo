 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_staffs')?></h3>
            </section>
        </div>
        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <?php  if($this->uri->segment(2) == 'MyProfile' ||  $this->uri->segment(2) == 'ResetPassword'){ ?>
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('MyProfile'); ?>"><?= _l('txt_profile'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('ResetPassword'); ?>"><?= _l('txt_change_password'); ?></a>
                        </li>

                        <?php  $admin = getAdminData(get_staff_user_id());
                        if(!empty($admin) && $admin->S_IsAdmin == 0){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Permissions'); ?>"><?= _l('txt_permission'); ?></a>
                        </li>    
                        <?php } ?>                    
                    </ul>
                </div>
                <?php } else { ?>
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
                                    <?php if(!empty($admin)){ ?>
                                    
                                    <h4 id="form-validation-basic-demo"><?= _l('txt_account'); ?></h4>
                                    <?php } else if(!empty($member)){ ?>
                                    <h4 id="form-validation-basic-demo"><?= _l('txt_edit_staff'); ?></h4>    
                                    <?php } else { ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_staff'); ?></h4>     
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                            <?php if(!empty($admin)){
                                                $path = admin_url('MyProfile');
                                                $member = $admin;
                                                $checked = true;
                                            } else if(!empty($member)){
                                                $path = admin_url('Staffs/Staff/'.$member->Staff_ID);
                                                $checked = true;
                                            } else {
                                                $path = admin_url('Staffs/Staff');
                                                $checked = true;
                                            }

                                            ?>
                                            <form method="POST" action="<?= $path; ?>" class="has-validation-callback" onsubmit="return true"  enctype="multipart/form-data"> 
                                                <?php if(empty($member)){  ?>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input id="Val_IsAdmin" name="Val_IsAdmin" <?= $checked; ?> type="checkbox" class="custom-control-input">
                                                        <label class="custom-control-label" for="Val_IsAdmin"><?= _l('txt_is_admin')?></label>
                                                    </div>
                                                </div> 
                                            <?php } ?>                                               
                                                <?php $attrs = (isset($member) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_first_name')
                                                    );
                                                
                                                $value=( isset($member) ? $member->S_FirstName : '');?>  
                                                 
                                                <?= render_input( 'Val_FirstName', 'addd_edit_first_name',$value,'text',$attrs,array(),'','','','admin_placeholder_first_name','','',true,'','','',$data_atts,''); ?>

                                                <?php $attrs = (isset($member) ? array('required'=>'true') : array('required'=>'true')); 
                                                $value=( isset($member) ? $member->S_LastName : '');
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_last_name')
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_LastName', 'addd_edit_last_name',$value,'text',$attrs,array(),'','','','admin_placeholder_last_name','','',true,'','','',$data_atts); ?>

                                                <?php $attrs = (isset($member) ? array('required'=>'true','readonly' => true) :array('required'=>'true')); 
                                                $value=( isset($member) ? $member->S_Email : '');
                                                $data_atts = array(
                                                    'data-validation'=>'email',                                                    
                                                    'data-validation-error-msg'=> _l('please_enter_email')
                                                    );
                                                ?>   
                                                <?= render_input( 'Val_Email', 'addd_edit_email',$value,'email',$attrs,array(),'','','','admin_placeholder_email','','',true,'','','',$data_atts); ?>


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
                                                <?= render_input( 'Val_Password', 'addd_edit_password','','password',$attrs,array(),'','','','admin_placeholder_password','','',true,'','','',$data_atts); } ?>
                                                <?php if( "MyProfile" !==$this->uri->segment(2)) :?>
                                                <label><?= _l('Select Area'); ?></label><br/>
                                                <select id="select2-id-label-single"  name="Area" id="Staff" class="form-control ks-select" style="margin-bottom: .4em">

                                                    <option value=""><?= _l('Select Area');?></option>
                                                    <?php
                                                    if(!empty($areas)) {
                                                        foreach($areas as $area) {
                                                            if($area['id'] == $member->area) {
                                                            ?>
                                                            <option value="<?php echo $area['id']; ?>" selected="selected"><?= $area['name']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $area['id']; ?>"><?= $area['name']; ?></option>
                                                        <?php }
                                                        }  
                                                    }?>
                                                </select>
                                                <?php endif;?>
                                                <div class="form-group" style="margin-top: .8em">
                                                    <label><?= _l('profile_image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('profile_image'); ?></span>
                                                        <input type="file" name="Val_Staff_Profile_Image"  id="Val_Staff_Profile_Image" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($member->S_ProfileImage)){
                                                          $ProfileImage = UPLOAD_STAFF_BASE_URL.$member->Staff_ID.'/'.$member->S_ProfileImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $ProfileImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                             
                                                </div> 

                                               
                                                                        
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup"><?= _l('btn_submit'); ?></button>
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