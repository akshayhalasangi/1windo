 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendor Business Location')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Vendors'); ?>"><?= _l('Vendors Listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Vendors/Vendor'); ?>"><?= _l('Add Vendor'); ?></a>
                        </li> 
                    </ul>
                </div>
             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($Profile)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('Edit Vendor Location'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_vendor'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Vendors/Vendor');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="vendor-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <div class="form-group">
                                                    <label><?= _l('Work Images'); ?></label><br/>
                                                    <button class="btn btn-primary ks-btn-file">
                                                        <span class="la la-cloud-upload ks-icon"></span>
                                                        <span class="ks-text"><?= _l('Work Images'); ?></span>
                                                        <input type="file" name="Val_Wworksgallery[]"  id="Val_Wworksgallery" multiple>
                                                    </button>
                                                    <br/> <br/> 
                                                    <?php   
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
														$WorkImages = json_decode($Work->W_WorksGallery);
                                                        if(!empty($WorkImages)){
														
															foreach($WorkImages as $WorkImage)
																{
																	$DisplayImage = UPLOAD_VENDOR_BASE_URL.$Work->W_VendorID.'/'.$WorkImage;
														?>
																	
																	<div class="ks-info">                             
																		<img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
																	</div>

													<?php			
																}
																
														
                                                        }
                                                    ?>       
                                             
                                                </div> 
												
												
                                                <input type="hidden" name="vendor" value='1'>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Vendors');?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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