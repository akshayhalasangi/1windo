 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
            <?php if(!empty($category)){ ?>
                <h3><?= _l('update')." ".$category->C_Name." Category" ?></h3>
            <?php } else { ?>                                          
                <h3><?= _l('txt_add_category') ?></h3>
            <?php }  ?>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($category)){ ?>
                                     <!-- <h4 id="form-validation-basic-demo"><?= _l('txt_edit_category'); ?></h4> 
                                     <br/> -->
                                    <?php } else { ?>                                          
                                        <!-- <h4 id="form-validation-basic-demo"><?= _l('txt_add_category'); ?></h4> 
                                        <br/>  -->
                                    <?php }  ?>
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="category-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($category) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($category) ? $category->C_Name : '');?>  
                                                 
                                                <?= render_input( 'Val_Categoryname', 'add_edit_name',$value,'text',$attrs,array(),'','','','admin_placeholder_name','','',true,'','','',$data_atts,''); ?>

                                                <input type="hidden" name="Val_Level" id="Val_Level" value="<?= $level; ?>">
                                                <input type="hidden" name="Val_Type" id="Val_Type" value="<?= $type; ?>">
                                                <input type="hidden" name="Val_Parent" id="Val_Parent" value="<?= $parentId; ?>">
                                                <input type="hidden" name="Val_OuterParent" id="Val_OuterParent" value="<?= $outerParentId; ?>">
                                                
												<?php 
													if(!empty($category) && !empty($category->C_Parent))
														{
															$Style = 'display:block;';
														}
													else
														{
															$Style = 'display:none;';
														}	
												?>
                                                
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label><?= _l('display_image'); ?></label><br/>
                                                            <button class="btn btn-primary ks-btn-file">
                                                                <span class="la la-cloud-upload ks-icon"></span>
                                                                <span class="ks-text"><?= _l('profile_image'); ?></span>
                                                                <input type="file" name="Val_Displayimage"  id="Val_Displayimage" accept='image/*'>
                                                            </button>
                                                            <br/> <br/> 
                                                            <?php   
                                                                $DisplayImage = UPLOAD_NO_IMAGE;
                                                                if(!empty($category->C_DisplayImage)){
                                                                    $DisplayImage = UPLOAD_CATEGORIES_BASE_URL.$category->CategoryID.'/'.$category->C_DisplayImage;
                                                                }
                                                            ?>       
                                                            <div class="ks-info">                             
                                                                <img src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">
                                                            </div>
                                                    
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label><?= _l('display_icon'); ?></label><br/>
                                                            <button class="btn btn-primary ks-btn-file">
                                                                <span class="la la-cloud-upload ks-icon"></span>
                                                                <span class="ks-text"><?= _l('display_icon'); ?></span>
                                                                <input type="file" name="Val_Displayicon"  id="Val_Displayicon" accept='.png, .jpg, .jpeg'>
                                                            </button>
                                                            <br/> <br/> 
                                                            <?php   
                                                                $DisplayIcon = UPLOAD_NO_IMAGE;
                                                                if(!empty($category->C_DisplayIcon)){
                                                                    $DisplayIcon = UPLOAD_CATEGORIES_BASE_URL.$category->CategoryID.'/'.$category->C_DisplayIcon;
                                                                }
                                                            ?>       
                                                            <div class="ks-info">                             
                                                                <img src="<?= $DisplayIcon; ?>" class="img-avatar" width="167" height="167">
                                                            </div>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="category" value='1'>
                                                <!-- <input type="hidden" name="parentURL" id="parentURL" value="<?= $_SERVER['HTTP_REFERER'] ?>"> -->
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.history.back()" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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