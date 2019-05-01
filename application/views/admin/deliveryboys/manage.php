<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Delivery Boys')?></h3>
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
                                        <span class="ks-text"><?= _l('Delivery Boys')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($deliveryboysList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('lbl_deliveryboy_name'); ?></th>
                                                    <th><?= _l('lbl_area'); ?></th>
                                                    <th><?= _l('txt_mobileno'); ?></th>
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($deliveryboysList as $key => $deliveryboy) : ?>
                                                <tr id="tr-<?= $deliveryboy['DeliveryBoyID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    
                                                    <td><?php 
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($deliveryboy['V_ProfileImage'])){
                                                          $ProfileImage = UPLOAD_DELIVERYBOY_BASE_URL.$deliveryboy['DeliveryBoyID'].'/'.$deliveryboy['DB_ProfileImage'];
                                                        } ?>
                                                        <div class="ks-vendor">
                                                            <img class="ks-avatar" src="<?= $ProfileImage; ?>" width="24" height="24">
                                                            <?php if($deliveryboy['DB_Status'] != 4) { ?>
                                                            <span class="ks-name"><?= $deliveryboy['DB_FirstName'];?></span>
                                                            <?php } else { ?>
                                                            <span class="ks-name ks-color-danger"><?= $deliveryboy['DB_FirstName'];?></span>    
                                                            <span class="badge ks-circle badge-danger"></span>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?= $deliveryboy['DB_Location'];?>
                                                    </td>
                                                    <td><span class="ks-name ks-color-danger"><?= $deliveryboy['DB_CountryCode'].$deliveryboy['DB_Mobile'];?></span>    
                                                    </td> 
                                                    <td>
                                                        <?php if($deliveryboy['DB_ProfileStatus'] == 2 || $deliveryboy['DB_ProfileStatus'] == 3){ ?>
                                                            <label class="ks-checkbox-slider ks-primary">
                                                                <?php if($deliveryboy['DB_Status'] == 1 || $deliveryboy['DB_Status'] == 3) { ?>
                                                                <input type="checkbox" id="Switch<?= $deliveryboy['DeliveryBoyID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_DBstatus"  data-type="DeliveryBoy" data-id="<?= $deliveryboy['DeliveryBoyID']; ?>">
                                                                    <?php }  else if($deliveryboy['DB_Status'] == 2) { ?>
                                                                <input type="checkbox" id="Switch<?= $deliveryboy['DeliveryBoyID']; ?>" value="3" data-status="3" class="tbl-status" name="Val_DBstatus" checked data-type="DeliveryBoy" data-id="<?= $deliveryboy['DeliveryBoyID']; ?>">       
                                                                    <?php }?>
                                                                <span class="ks-indicator" ></span>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $deliveryboy['DeliveryBoyID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $deliveryboy['DeliveryBoyID']; ?>">
                                                                <a class="dropdown-item" href="<?= 'DeliveryBoys/DeliveryBoy/'.$deliveryboy['DeliveryBoyID']; ?>"><?= _l('txt_view_details' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="DeliveryBoy" data-id="<?= $deliveryboy['DeliveryBoyID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('Delivery Boys Not Found.')?></h5></div>
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