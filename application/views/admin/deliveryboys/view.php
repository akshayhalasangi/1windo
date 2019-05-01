 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('Delivery Boy')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('DeliveryBoys') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div id="completeInfo" class="col-lg-12">

                                    <h3><?= _l('txt_deliveryboy_general_info')?></h3>
                                    <?php
                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                        if(!empty($deliveryboy->DB_ProfileImage)){
                                            $DisplayImage = UPLOAD_DELIVERYBOY_BASE_URL.$deliveryboy->deliveryboyID.'/'.$deliveryboy->DB_ProfileImage;
                                        }
                                    ?>
                                    <img id="profileImg" src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">

                                    <table id="deliveryboyGeneralInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_deliveryboy_name") ?></th>
                                                <td><?= (!empty($deliveryboy) ? $deliveryboy->DB_FirstName : '')." ".(!empty($deliveryboy) ? $deliveryboy->DB_LastName : '') ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_mobile_number") ?></th>
                                                <td><?= $deliveryboy->DB_CountryCode.$deliveryboy->DB_Mobile ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_email") ?></th>
                                                <td><?= $deliveryboy->DB_Email ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_locality") ?></th>
                                                <td><?= $deliveryboy->DB_Location ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_area") ?></th>
                                                <td><?= $deliveryboy->DB_Location ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class='divider'><?= _l('txt_deliveryboy_account_info')?></h3>
                                    <table id="deliveryboyAccountInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_deliveryboy_account_holder_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_AccountName) > 0 ? $Account->A_AccountName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_deliveryboy_account_type") ?></th>
                                                <td>
                                                    <?php 
                                                        if($Account->A_AccountType == '1'){
                                                            echo _l("txt_saving_account");
                                                        }else if($Account->A_AccountType == '2'){
                                                            echo _l("txt_current_account");
                                                        }else{
                                                            echo _l("txt_not_specified");
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_deliveryboy_account_number") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_AccountNumber) > 0 ? $Account->A_AccountNumber: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_deliveryboy_ifsc_code") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_IFSCNumber) > 0 ? $Account->A_IFSCNumber: '---') : '---' ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
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