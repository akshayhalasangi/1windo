 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_complete_vendor_info')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Vendors') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div id="completeInfo" class="col-lg-12">

                                    <h3><?= _l('txt_vendor_general_info')?></h3>
                                    <?php   
                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                        if(!empty($vendor->V_ProfileImage)){
                                            $DisplayImage = UPLOAD_VENDOR_BASE_URL.$vendor->VendorID.'/'.$vendor->V_ProfileImage;
                                        }
                                    ?>
                                    <img id="profileImg" src="<?= $DisplayImage; ?>" class="img-avatar" width="167" height="167">

                                    <table id="vendorGeneralInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_name") ?></th>
                                                <td><?= (!empty($vendor) ? $vendor->V_FirstName : '')." ".(!empty($vendor) ? $vendor->V_LastName : '') ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_category") ?></th>
                                                <td>
                                                    <?php
                                                        if(!empty($Categories)) { 
                                                            foreach($Categories as $Category) { 
                                                                $select = '';
                                                                if(!empty($vendor)){
                                                                    if($Category['CategoryID'] == $vendor->V_CategoryID)   {
                                                                        echo $Category['C_Name'];
                                                                   }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_mobile_number") ?></th>
                                                <td><?= $vendor->V_CountryCode.$vendor->V_Mobile ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class="divider"><?= _l('txt_vendor_business_info')?></h3>

                                    <table id="vendorBusinessInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_BusinessName) > 0 ? $About->A_BusinessName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_Introduction") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_Introduction) > 0 ? $About->A_Introduction: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_presence") ?></th>
                                                <td>
                                                    <?php
                                                        if(!empty($About->A_BusinessPresence)) {
                                                            switch($About->A_BusinessPresence){
                                                                case '1': echo "Website";
                                                                        break;
                                                                case '2': echo "Certificate";
                                                                        break;
                                                                case '3': echo "Online";
                                                                        break;
                                                                default: echo "---";
                                                                        break;
                                                            }
                                                        }else{
                                                            echo "---";
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_facebook_link") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_FacebookLink) > 0 ? $About->A_FacebookLink: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_phone_no") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_PhoneNumber) > 0 ? $About->A_PhoneNumber: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_type") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_Type) > 0 ? $About->A_Type: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_experience") ?></th>
                                                <td><?= (!empty($About) ? (strlen($About->A_ExperienceYear) > 0 ? $About->A_ExperienceYear." (Y)": '--- (Y)') : '---')." ".(!empty($About) ? (strlen($About->A_ExperienceMonth) > 0 ? $About->A_ExperienceMonth." (M)": '--- (M)') : '---') ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_starting_price") ?></th>
                                                <td><?= !empty($About) ? (strlen($About->A_StartingPrice) > 0 ? $About->A_StartingPrice: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_business_specialization") ?></th>
                                                <td>
                                                    <?php
                                                        $value=( !empty($About) ? json_decode($About->A_Specialization) : '');
                                                        echo ( !empty($value[0]) ? $value[0] : '').( !empty($value[1]) ? ", ".$value[1] : '').( !empty($value[2]) ? ", ".$value[2] : '').( !empty($value[3]) ? ", ".$value[3] : '').( !empty($value[4]) ? ", ".$value[4] : '');
                                                    ?>
                                            </tr>
                                            
                                        </tbody>
                                    </table>

                                    <h3 class="divider"><?= _l('txt_vendor_id_address_proof')?></h3>

                                    <table id="vendorProofInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_id_proof_name") ?></th>
                                                <td>
                                                    <?php
                                                        switch($Profile->P_IDCardType){
                                                            case "1": echo "Voter ID";
                                                                        break;
                                                            case '2': echo "Driving Licence";
                                                                        break;
                                                            case '3': echo "PAN Card";
                                                                        break;
                                                            default: echo "---";
                                                                        break;
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_id_holder_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($Profile->P_IDCardName) > 0 ? $Profile->P_IDCardName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_id_number") ?></th>
                                                <td><?= !empty($About) ? (strlen($Profile->P_IDCardNumber) > 0 ? $Profile->P_IDCardNumber: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_id_image") ?></th>
                                                <td>
                                                    <?php
                                                        $DisplayImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($Profile->P_IDCardFrontImage)){
                                                            $DisplayImageFront = UPLOAD_VENDORS_BASE_URL.$Profile->P_VendorID.'/'.$Profile->P_IDCardFrontImage;
                                                        }
                                                        if(!empty($Profile->P_IDCardFrontImage)){
                                                            $DisplayImageBack = UPLOAD_VENDORS_BASE_URL.$Profile->P_VendorID.'/'.$Profile->P_IDCardBackImage;
                                                        }
                                                    ?>
                                                    <div class="ks-info">                             
                                                        <img src="<?= $DisplayImageFront; ?>" class="img-avatar" width="250" height="200">
                                                        <img src="<?= $DisplayImageBack; ?>" class="img-avatar" width="250" height="200">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_guardian_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($Profile->P_GuardianName) > 0 ? $Profile->P_GuardianName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_gender") ?></th>
                                                <td>
                                                    <?php 
                                                        switch($Profile->P_Gender){
                                                            case "1": echo _l("txt_male");
                                                                      break;
                                                            case "2": echo _l("txt_female");
                                                                      break;
                                                            case "3": echo _l("txt_others");
                                                                      break;
                                                            default: echo "---";
                                                                      break;
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_birthdate") ?></th>
                                                <td><?= !empty($About) ? (strlen($Profile->P_BirthDate) > 0 ? $Profile->P_BirthDate: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_permanent_address") ?></th>
                                                <td>
                                                    <?php
                                                        echo (!empty($About) ? (strlen($Profile->P_PermanentBuilding) > 0 ? "# ".$Profile->P_PermanentBuilding.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_PermanentLocality) > 0 ? $Profile->P_PermanentLocality.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_PermanentCity) > 0 ? $Profile->P_PermanentCity.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_PermanentState) > 0 ? $Profile->P_PermanentState.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_PermanentPincode) > 0 ? $Profile->P_PermanentPincode.",<br>": '') : '');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_current_address") ?></th>
                                                <td>
                                                    <?php
                                                        echo (!empty($About) ? (strlen($Profile->P_CurrentBuilding) > 0 ? "# ".$Profile->P_CurrentBuilding.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_CurrentLocality) > 0 ? $Profile->P_CurrentLocality.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_CurrentCity) > 0 ? $Profile->P_CurrentCity.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_CurrentState) > 0 ? $Profile->P_CurrentState.",<br>": '') : '');
                                                        echo (!empty($About) ? (strlen($Profile->P_CurrentPincode) > 0 ? $Profile->P_CurrentPincode.",<br>": '') : '');
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class="divider"><?= _l('txt_vendor_exact_location')?></h3>

                                    <table id="vendorLocationInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_location") ?></th>
                                                <td><?= !empty($About) ? (strlen($Location->L_Location) > 0 ? $Location->L_Location: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_latitude") ?></th>
                                                <td><?= !empty($About) ? (strlen($Location->L_Latitude) > 0 ? $Location->L_Latitude: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_longitude") ?></th>
                                                <td><?= !empty($About) ? (strlen($Location->L_Longitude) > 0 ? $Location->L_Longitude: '---') : '---' ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class="divider"><?= _l('txt_vendor_work_images')?></h3>

                                    <table id="vendorLocationInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <?php   
                                                    $DisplayImage = UPLOAD_NO_IMAGE;
                                                    $WorkImages = json_decode($Work->W_WorksGallery);
                                                    if(!empty($WorkImages)){
                                                    
                                                        foreach($WorkImages as $WorkImage)
                                                        {
                                                            $DisplayImage = UPLOAD_VENDOR_BASE_URL.$Work->W_VendorID.'/'.$WorkImage;
                                                    ?>
                                                            <div class="ks-info">                             
                                                                <img src="<?= $DisplayImage; ?>" class="img-avatar" width="250" height="250">
                                                            </div>
                                                <?php			
                                                        }
                                                            
                                                    
                                                    }else{
                                                        echo _l("txt_no_img_found");;
                                                    }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class="divider"><?= _l('txt_vendor_account_info')?></h3>

                                    <table id="vendorAccountInfo" class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_gst_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_GSTName) > 0 ? $Account->A_GSTName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_gst_number") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_GSTNumber) > 0 ? $Account->A_GSTNumber: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_account_holder_name") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_AccountName) > 0 ? $Account->A_AccountName: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_account_type") ?></th>
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
                                                <th scope="row"><?= _l("lbl_vendor_account_number") ?></th>
                                                <td><?= !empty($About) ? (strlen($Account->A_AccountNumber) > 0 ? $Account->A_AccountNumber: '---') : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= _l("lbl_vendor_ifsc_code") ?></th>
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