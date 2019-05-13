 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
					<?php
						if(!empty($country)){
							echo ("Edit: ".$country->CountryName." - ");
						}
						echo _l('Country');
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

                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">

                                            <form method="POST" action="" class="has-validation-callback" id="country-form" onsubmit="return true"  enctype="multipart/form-data">
                                                                                            

												
												
												<?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true)); 
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_name')
                                                    );
                                               
                                                $value=( !empty($country) ? $country->name : '');?>
                                                 
                                                <?= render_input( 'Val_name', 'Country name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>

												<?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_CountryISO')
                                                    );

                                                $value=( !empty($country) ? $country->currency : '');?>

                                                <?= render_input( 'Val_currency', 'Country currency',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                <?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_currency')
                                                );
                                                $value=( !empty($country) ? $country->iso2 : '');?>
                                                 
                                                <?= render_input( 'Val_iso2', 'Country ISO2',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
												
												
												<?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_CountryISO2')
                                                );
                                                $value=( !empty($country) ? $country-> iso3 : '');?>

                                                <?= render_input( 'Val_iso3', 'Country ISO3',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                <?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_CountryISO3')
                                                );


                                                $value=( !empty($country) ? $country->phone_code : '');?>

                                                <?= render_input( 'Val_phone_code', 'Country Num Code',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                <?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_Country Phone Code')
                                                );

                                                $value=( !empty($country) ? $country->capital : '');?>


                                                <?= render_input( 'Val_capital', 'Country Capital',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>
                                                <?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                                $data_atts = array(
                                                    'data-validation'=>'required',
                                                    'data-validation-error-msg'=> _l('please_enter_Capital')
                                                );

                                               ?>

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary model-popup" ><?= _l('btn_submit'); ?></button>
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