 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
                    <?php
                        if(!empty($feature)){
                            echo ("Edit: ");
                        }
                        echo $serviceName." - "._l('txt_features');
                    ?>
                </h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if(!empty($feature)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_service_feature'); ?></h4>
                                    <?php } else { ?>                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_service_feature'); ?></h4> 
                                    <?php } ?>
                                    <br/>
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                            <?php 
                                                $path = admin_url('Users/User'); 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="feature-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                                                            
                                                <?php $attrs = (isset($feature) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_title')
                                                    );
                                               
                                                $value=( !empty($feature) ? $feature->F_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Ftitle', 'add_edit_title',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
                                                <input name="Val_Service" id="Val_Service" type="hidden" value="<?= $ServiceID ?>">
                                                <?php $attrs = (isset($feature) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_description')
                                                    );
                                               
                                                $value=( !empty($feature) ? $feature->F_Description : '');?>  
                                                 
                                                <?= render_input( 'Val_Fdescription', 'add_edit_description',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                                                 
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Services/Features/').$ServiceID;?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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