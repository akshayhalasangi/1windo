 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_services_works')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Feature/').$ServiceID; ?>"><?= _l('txt_features'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Step/').$ServiceID; ?>"><?= _l('txt_steps'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Work/').$ServiceID; ?>"><?= _l('txt_works'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= admin_url('Services/Review/').$ServiceID; ?>"><?= _l('txt_reviews'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Package/').$ServiceID; ?>"><?= _l('txt_packages'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Timeslab/').$ServiceID; ?>"><?= _l('txt_timeslabs'); ?></a>
                        </li>
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($work)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_service_work'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_service_work'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="work-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($work) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($work) ? $work->W_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Wtitle', 'add_edit_title',$value,'text',$attrs,array(),'','','','admin_placeholder_title','','',true,'','','',$data_atts,''); ?>
												<div class="form-group hide" id="ParentInput">
													<label for="Val_Service" class="form-control-label"><?= _l('add_edit_category'); ?></label>
													<div class="">
														<select name="Val_Service"  id="Val_Service" class="form-control" required>
															<option value="0"><?= _l('select_service');?></option>
															<?php 
																
																		foreach($Services as $ServiceData)
																			{
															?>
																				<option value="<?= $ServiceData['ServiceID'];?>" <?php if(!empty($work)) { echo ($work->W_ServiceID == $ServiceData['ServiceID']) ? 'selected' : '';  } ?>>
																					<?= $ServiceData['S_Name'];?>
																				</option>
															<?php				
																			}
																
															?>
															
														</select>
													</div>
												</div>
                                                <div class="form-group">
                                                    <label><?= _l('display_image'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('profile_image'); ?></span>
                                                        <input type="file" name="Val_Wdisplayimage"  id="Val_Wdisplayimage" >
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($work->W_DisplayImage)){
                                                          $DisplayImage = UPLOAD_SERVICES_WORKS_BASE_URL.$work->SWorkID.'/'.$work->W_DisplayImage;
                                                        }
                                                    ?>       
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                    </div>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Services/Works/').$ServiceID;?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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