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
                                                                                            
                                                
												<?php $attrs = (isset($Location) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Location')
                                                    );
                                               
                                                $value=( !empty($Location) ? $Location->L_Location : '');?>  
                                                 
                                                <?= render_input( 'Val_Llocation', 'Location',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												<?php $attrs = (isset($Location) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Latitude')
                                                    );
                                               
                                                $value=( !empty($Location) ? $Location->L_Latitude : '');?>  
                                                 
                                                <?= render_input( 'Val_Llatitude', 'Latitude',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												<?php $attrs = (isset($Location) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('Please enter Latitude')
                                                    );
                                               
                                                $value=( !empty($Location) ? $Location->L_Longitude : '');?>  
                                                 
                                                <?= render_input( 'Val_Llongitude', 'Longitude',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												
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