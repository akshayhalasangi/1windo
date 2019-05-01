<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_products_attributes')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Products') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_products_attributes')?></span>
                                        <a href="<?= admin_url('Products/Attribute/'); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> New Options</a>
                                    </h5>
                                    <?php if(!empty($attributesList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_attribute'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($attributesList as $key => $attribute) : ?>
                                                <tr id="tr-<?= $attribute['PAttributeID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $attribute['A_Title'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($attribute['A_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $attribute['PAttributeID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Attribute" data-id="<?= $attribute['PAttributeID']; ?>">
                                                        <?php }  else if($attribute['A_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $attribute['PAttributeID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Attribute" data-id="<?= $attribute['PAttributeID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $attribute['PAttributeID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $attribute['PAttributeID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Products/Attribute/'.$attribute['PAttributeID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Attribute" data-id="<?= $attribute['PAttributeID']; ?>"><?= _l('txt_delete' ); ?></a>
																
																<a class="dropdown-item" href="<?= admin_url('Products/AttribValues/'.$attribute['PAttributeID']); ?>"><?= _l('txt_view_attributes' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Services/Profile/'.$attribute['PAttributeID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_attributes_not_found')?></h5></div>
                                    <?php endif; ?>
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