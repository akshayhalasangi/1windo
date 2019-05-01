<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_products_attributes_values')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Product'); ?>"><?= _l('txt_add_product'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Attributes'); ?>"><?= _l('txt_listing_attributes'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/Attribute'); ?>"><?= _l('txt_add_attribute'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/AttribValues/').$AttributeID; ?>"><?= _l('txt_listing_attributes_values'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Products/AttribValue/').$AttributeID; ?>"><?= _l('txt_add_attribute_value'); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_products_attributes_values')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($attribvaluesList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_attribute_values'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($attribvaluesList as $key => $attribvalue) : ?>
                                                <tr id="tr-<?= $attribvalue['PAValueID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $attribvalue['V_Title'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($attribvalue['V_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $attribvalue['PAValueID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="AttribValue" data-id="<?= $attribvalue['PAValueID']; ?>">
                                                        <?php }  else if($attribvalue['V_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $attribvalue['PAValueID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="AttribValue" data-id="<?= $attribvalue['PAValueID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $attribvalue['PAValueID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $attribvalue['PAValueID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Products/AttribValue/'.$AttributeID.'/'.$attribvalue['PAValueID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="AttribValue" data-id="<?= $attribvalue['PAValueID']; ?>"><?= _l('txt_delete' ); ?></a>
																
                                                                <!--a class="dropdown-item" href="<?= 'Services/Profile/'.$attribute['PAValueID']; ?>"><?= _l('txt_view' ); ?></a-->
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