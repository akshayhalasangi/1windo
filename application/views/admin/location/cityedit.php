<?= init_head();?>
<div class="ks-column ks-page">
    <div class="ks-page-header">
        <section class="ks-title">
            <h3>
                <?php
                if(!empty($country)){
                    echo ("Edit: ".$country->name." - ");
                }
                echo _l('City');
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


                                            <div class="form-group">
                                                <label for="Val_CountryID" class="form-control-label"><?= _l('Country'); ?></label>
                                                <div class="">
                                                    <select name="Val_country_id"  id="Val_country_id" class="form-control" required>
                                                        <option value="0"><?= _l('select Country');?></option>
                                                        <?php
                                                        foreach($countryList as $AttributeData)
                                                        {
                                                            ?>
                                                            <option value="<?= $AttributeData['id'];?>" <?php if(!empty($country->country_id)) { echo ($country->country_id == $AttributeData['id']) ? 'selected' : '';  } ?> >
                                                                <?= $AttributeData['name'];?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Val_CountryID" class="form-control-label"><?= _l('Country'); ?></label>
                                                <div class="">
                                                    <select name="Val_state_id"  id="Val_state_id" class="form-control" required>
                                                        <option value="0"><?= _l('select State');?></option>
                                                        <?php
                                                        foreach($stateList as $AttributeData)
                                                        {
                                                            ?>
                                                            <option value="<?= $AttributeData['id'];?>" <?php if(!empty($country->state_id)) { echo ($country->state_id == $AttributeData['id']) ? 'selected' : '';  } ?> >
                                                                <?= $AttributeData['name'];?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php $attrs = (isset($country) ? array('required'=>'true','autofocus'=>true) : array('autofocus'=>true));
                                            $data_atts = array(
                                                'data-validation'=>'required',
                                                'data-validation-error-msg'=> _l('please_enter_name')
                                            );

                                            $value=( !empty($country) ? $country->name : '');?>

                                            <?= render_input( 'Val_name', 'City name',$value,'text',$attrs,array(),'','','','','','',true,'','','',$data_atts,''); ?>


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