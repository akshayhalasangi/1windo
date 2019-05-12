 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
                    <?php
                        if(!empty($Attributes)){
                            echo ("Edit: ".$attribvalue->V_Title." - ");
                        }
                        echo $attributeName." - "._l('txt_products_attributes_values');
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
                                    <?php if(!empty($Attributes)){ ?>
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_edit_product_attribute_value'); ?></h4>
                                    <?php } else { ?>
                                          
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_add_product_attribute_value'); ?></h4> 
                                    <?php }  ?>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                             <?php 
                                                $path = admin_url('Users/User');                                               
                                             
 
                                            ?>
                                            <form method="POST" action="" class="has-validation-callback" id="attribute-form" onsubmit="return true"  enctype="multipart/form-data"> 
                                                
												<div class="form-group " >
                                                    <input type="hidden" value="<?= $AttributeID ?>" name="Val_Attribute"  id="Val_Attribute">
												</div>                                            
                                                <?php $attrs = (isset($attribvalue) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($attribvalue) ? $attribvalue->V_Title : '');?>  
                                                 
                                                <?= render_input( 'Val_Vtitle', 'add_edit_title',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
                                                <?php $attrs = (isset($attribvalue) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_value')
                                                    );
                                               
                                                $value=( !empty($attribvalue) ? $attribvalue->V_Value : '');?>  
                                                 
                                                <?= render_input( 'Val_Vvalue', 'add_edit_value',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                                              
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
                                                    <button type="button" onclick="window.location.href='<?= admin_url('Products/AttribValues/'.$AttributeID);?>'" class="btn btn-outline-primary ks-light"><?= _l('btn_back'); ?></button>
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