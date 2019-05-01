<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_customers')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <!-- <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link current active" href="<?= admin_url('Customers'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Customers/Customer'); ?>"><?= _l('txt_add_customer'); ?></a>
                        </li>                         
                    </ul>
                </div> -->
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_customers')?></span>
                                    </h5>
                                    <?php if(!empty($customersList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_customer'); ?></th>                                                     
                                                    <th><?= _l('txt_mobileno'); ?></th>
                                                    <th><?= _l('txt_mobileno'); ?></th>
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($customersList as $key => $customer) : ?>
                                                <tr id="tr-<?= $customer['CustomerID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    
                                                    <td><?php 
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($customer['C_ProfileImage'])){
                                                          $ProfileImage = UPLOAD_CUSTOMER_BASE_URL.$customer['CustomerID'].'/'.$customer['C_ProfileImage'];
                                                        } ?>
                                                        <div class="ks-customer">
                                                            <img class="ks-avatar" src="<?= $ProfileImage; ?>" width="24" height="24">
                                                            <?php if($customer['C_Status'] != 4) { ?>
                                                            <span class="ks-name"><?= $customer['C_FirstName'];?></span>
                                                            <?php } else { ?>
                                                            <span class="ks-name ks-color-danger"><?= $customer['C_FirstName'];?></span>    
                                                            <span class="badge ks-circle badge-danger"></span>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                    <td><span class="ks-name"><?= $customer['C_CountryCode'].$customer['C_Mobile'];?></span></td>
                                                    <td><span class="ks-name"><?= $customer['C_Email'];?></span></td> 
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($customer['C_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $customer['CustomerID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Customer" data-id="<?= $customer['CustomerID']; ?>">
                                                        <?php }  else if($customer['C_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $customer['CustomerID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Customer" data-id="<?= $customer['CustomerID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <!-- <a class="btn btn-link" id="dropdownMenu<?= $customer['CustomerID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-trash-o"></span>
                                                            </a> -->

                                                            <a class="btn btn-link tbl-delete sweet-5" href="javascript:;"  data-act="Customer" data-id="<?= $customer['CustomerID']; ?>">
                                                                <span class="la la-trash-o"></span>
                                                            </a>
                                                            
                                                            <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $customer['CustomerID']; ?>">
                                                                <a class="dropdown-item" href="<?= 'Customers/Customer/'.$customer['CustomerID']; ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Customer" data-id="<?= $customer['CustomerID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                a class="dropdown-item" href="<?= 'Customers/Profile/'.$customer['CustomerID']; ?>"><?= _l('txt_view' ); ?></a>
                                                            </div> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_customers_not_found')?></h5></div>
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