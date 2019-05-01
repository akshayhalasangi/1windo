 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_services_options')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Options/').$ServiceID; ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Option/').$ServiceID.'/'.$PackageID; ?>"><?= _l('txt_add_service_option'); ?></a>
                        </li> 
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($option)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_service_option'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_service_option'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="option-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($option) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($option) ? $option->O_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Otitle', 'add_edit_title',$value,'text',$attrs,array(),'','','','admin_placeholder_title','','',true,'','','',$data_atts,''); ?>
												<div class="form-group hide" id="ParentInput">
													<label for="Val_Pervice" class="form-control-label"><?= _l('add_edit_category'); ?></label>
													<div class="">
														<select name="Val_Package"  id="Val_Package" class="form-control" required>
															<option value="0"><?= _l('select_package');?></option>
															<?php 
																
																		foreach($Packages as $PackageData)
																			{
															?>
																				<option value="<?= $PackageData['SPackageID'];?>" <?php if(!empty($option)) { echo ($option->O_PackageID == $PackageData['SPackageID']) ? 'selected' : '';  } ?>>
																					<?= $PackageData['P_Title'];?>
																				</option>
															<?php				
																			}
																
															?>
															
														</select>
													</div>
												</div>
                                                <?php $attrs = (isset($option) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_description')
                                                    );
                                               
                                                $value=( !empty($option) ? $option->O_Price : '');?>  
                                                 
                                                <?= render_input( 'Val_Oprice', 'add_edit_price',$value,'text',$attrs,array(),'','','','admin_placeholder_price','','',true,'','','',$data_atts,''); ?>
                                                  
												<input type="hidden" value="<?= $ServiceID;?>" name="Val_Service" />
												                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Services/Options/').$ServiceID.'/'.$PackageID;?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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