<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Vendors')?></h3>
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
                                        <span class="ks-text"><?= _l('Vendors')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($vendorsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('Vendor Name'); ?></th>                                                     
                                                    <th><?= _l('txt_mobileno'); ?></th>
                                                    <th><?= _l('lbl_business_category'); ?></th>
                                                    <!-- <th><?= _l('txt_status'); ?></th> -->
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($vendorsList as $key => $vendor) : ?>
                                                <tr id="tr-<?= $vendor['VendorID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    
                                                    <td><?php 
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($vendor['V_ProfileImage'])){
                                                          $ProfileImage = UPLOAD_VENDOR_BASE_URL.$vendor['VendorID'].'/'.$vendor['V_ProfileImage'];
                                                        } ?>
                                                        <div class="ks-vendor">
                                                            <img class="ks-avatar" src="<?= $ProfileImage; ?>" width="24" height="24">
                                                            <?php if($vendor['V_Status'] != 4) { ?>
                                                            <span class="ks-name"><?= $vendor['V_FirstName'];?></span>
                                                            <?php } else { ?>
                                                            <span class="ks-name ks-color-danger"><?= $vendor['V_FirstName'];?></span>    
                                                            <span class="badge ks-circle badge-danger"></span>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                    <td><span class="ks-name ks-color-danger"><?= $vendor['V_CountryCode'].$vendor['V_Mobile'];?></span>    
                                                    </td> 
                                                        <!-- <td>                                                         
                                                            <?php if($vendor['V_ProfileStatus'] == 1) { ?>
                                                                <span class="badge badge-default">No Details</span>
                                                            <?php } else if($vendor['V_ProfileStatus'] == 2) { ?>
                                                                    <?php if($vendor['V_VerificationStatus'] == 1) { ?>
                                                                    <span class="badge badge-default">Pending</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 2) { ?>
                                                                    <span class="badge badge-primary">Submitted</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 3) { ?>
                                                                    <span class="badge badge-info">Approved</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 4) { ?>
                                                                    <span class="badge badge-pink">Rejected</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 4) { ?>
                                                                    <span class="badge badge-success	">Delivered</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 5) { ?>
                                                                    <span class="badge badge-danger">Cancelled</span>
                                                                    <?php }  else if($vendor['V_VerificationStatus'] == 6) { ?>
                                                                    <span class="badge badge-warning">Assigned</span>
                                                                    <?php }?>
                                                            <?php }?>
                                                        </td> -->
                                                    <td>
                                                        <?php
                                                            if(!empty($Categories)) {
                                                                foreach($Categories as $Category) { 
                                                                    $select = '';
                                                                    if(!empty($vendor)){
                                                                        if($Category['CategoryID'] == $vendor['V_CategoryID'])   {
                                                                            echo $Category['C_Name'];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if($vendor['V_VerificationStatus'] >= 2 || $vendor['V_VerificationStatus'] >= 3){ ?>
                                                            <label class="ks-checkbox-slider ks-primary">
                                                                <?php if($vendor['V_Status'] == 1 || $vendor['V_Status'] == 3) { ?>
                                                                <input type="checkbox" id="Switch<?= $vendor['VendorID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Vstatus"  data-type="Vendor" data-id="<?= $vendor['VendorID']; ?>">
                                                                    <?php }  else if($vendor['V_Status'] == 2) { ?>
                                                                <input type="checkbox" id="Switch<?= $vendor['VendorID']; ?>" value="3" data-status="3" class="tbl-status" name="Val_Vstatus" checked data-type="Vendor" data-id="<?= $vendor['VendorID']; ?>">       
                                                                    <?php }?>
                                                                <span class="ks-indicator" ></span>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $vendor['VendorID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $vendor['VendorID']; ?>">
                                                                <a class="dropdown-item" href="<?= 'Vendors/Vendor/'.$vendor['VendorID']; ?>"><?= _l('txt_view_details' ); ?></a>
                                                                <a class="dropdown-item" href="<?= 'Vendors/VendorProducts/'.$vendor['VendorID']; ?>"><?= _l('Add Products' ); ?></a>
                                                                <a class="dropdown-item" href="<?= 'Vendors/ListVendorProducts/'.$vendor['VendorID']; ?>"><?= _l('View Products' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Vendor" data-id="<?= $vendor['VendorID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('Vendors Not Found.')?></h5></div>
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