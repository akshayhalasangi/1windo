<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendor extends W_Controller
{
    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');
        $this->Val_Start = 0;
        $this->Val_Limit = 999999;

    }

    public function index()
    {
        echo "Access Denied";
    }

    public function Fetch()
    {
        $data = $this->input->post();

        if (!empty($data['Action']) && $data['Action'] == 'GetAllVendors') {

            $VendorsArray = $this->Vendors_model->get();

            if ($VendorsArray) {

                foreach ($VendorsArray as $VendorArray) {

                    $VendorFullName = $VendorArray['C_FirstName'] . " " . $VendorArray['C_LastName'];
                    $VendorProfileImage = UPLOAD_VENDOR_BASE_URL . $VendorArray['VendorID'] . '/' . $VendorArray['C_ProfileImage'];
                    $Records[] = array(
                        'TechnicianID' => $VendorArray['VendorID'],
                        'FullName' => $VendorFullName,
                        'FirstName' => $VendorArray['C_FirstName'],
                        'LastName' => $VendorArray['C_LastName'],
                        'MobileNumber' => $VendorArray['C_Mobile'],
                        'EmailAddress' => $VendorArray['C_Email'],
                        'ProfileImage' => $VendorProfileImage,
                        'Status' => $VendorArray['C_Status']
                    );
                }
                $result = array(
                    'status' => 'success', 'flag' => '1', 'message' => 'Vendors Records Fetched', 'data' => $Records
                );
            } elseif ($TechsArray === false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => 'No entry found.');
            }
        } else {
            if (!empty($data['Action']) && $data['Action'] == 'SingleVendor') {

                if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                    $VendorData = $this->Vendors_model->get($data['Val_Vendor']);

                    if ($VendorData) {

                        $VendorJobs = $this->Jobs_model->getJoined(null,
                            array(TBL_JOBS . '.VendorID' => $data['Val_Vendor']), false);

                        if (!empty($VendorJobs)) {
                            $TotalJobs = (string)count($VendorJobs);
                        } else {
                            $TotalJobs = (string)0;
                        }

                        $VendorFullName = $VendorData->C_FirstName . " " . $VendorData->C_LastName;
                        $VendorProfileImage = (!empty($VendorData->C_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->C_ProfileImage : null);
                        $Records[] = array(
                            'VendorID' => $VendorData->VendorID,
                            'FullName' => $VendorFullName,
                            'FirstName' => $VendorData->C_FirstName,
                            'LastName' => $VendorData->C_LastName,
                            'Mobile' => $VendorData->C_Mobile,
                            'Email' => $VendorData->C_Email,
                            'Address' => $VendorData->C_Address,
                            'ProfileImage' => $VendorProfileImage,
                            'TotalJobs' => $TotalJobs
                        );


                        $result = array(
                            'status' => 'success', 'flag' => '1', 'message' => 'Vendor Record Fetched',
                            'data' => $Records
                        );
                    } elseif ($VendorArray === false) {
                        $result = array(
                            'status' => 'error', 'flag' => '2', 'message' => 'Vendor Record Not Fetched',
                            'data' => $data['Val_Vendor']
                        );
                    }
                } else {
                    $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.');
                }
            } else {
                if (!empty($data['Action']) && $data['Action'] == 'GetProfile') {

                    if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                        if ($VendorData) {

                            $ProfileData = $this->Vendors_model->getProfile(null,
                                array('P_VendorID' => $data['Val_Vendor']));
                            $AboutData = $this->Vendors_model->getAbout(null,
                                array('A_VendorID' => $data['Val_Vendor']));
                            $WorksData = $this->Vendors_model->getWorks(null,
                                array('W_VendorID' => $data['Val_Vendor']));
                            $LocationsData = $this->Vendors_model->getLocations(null,
                                array('L_VendorID' => $data['Val_Vendor']));

                            if (!empty($ProfileData)) {
                                $ProfileData = (object)$ProfileData[0];
                                $IdentityStatus = $ProfileData->P_IDCardStatus;
                                $PersonalStatus = $ProfileData->P_PersonalStatus;
                                $CurrentStatus = $ProfileData->P_CurrentStatus;
                                $TermsStatus = $ProfileData->P_TermsStatus;

                            } else {
                                $IdentityStatus = '';
                                $PersonalStatus = '';
                                $CurrentStatus = '';
                                $TermsStatus = '';
                            }

                            if ($IdentityStatus == '2' && $PersonalStatus == '2' && $CurrentStatus == '2' && $TermsStatus == '2') {
                                $IdentityVerificationStatus = '2';
                            } else {
                                $IdentityVerificationStatus = '1';
                            }


                            if (!empty($AboutData)) {
                                $AboutData = (object)$AboutData[0];
                                $AboutMeStatus = $AboutData->A_Status;

                            } else {
                                $AboutMeStatus = '';
                            }

                            if (!empty($WorksData)) {
                                $WorksData = (object)$WorksData[0];
                                $WorksStatus = $WorksData->W_Status;

                            } else {
                                $WorksStatus = '';
                            }

                            if (!empty($LocationsData)) {
                                $LocationsData = (object)$LocationsData[0];
                                $BusinessLocationStatus = $LocationsData->L_Status;

                            } else {
                                $BusinessLocationStatus = '';
                            }

                            $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                            $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : "");
                            $Record = array(
                                'VendorID' => getStringValue($VendorData->VendorID),
                                'FullName' => getStringValue($VendorFullName),
                                'FirstName' => getStringValue($VendorData->V_FirstName),
                                'LastName' => getStringValue($VendorData->V_LastName),
                                'CountryCode' => getStringValue($VendorData->V_CountryCode),
                                'MobileNumber' => getStringValue($VendorData->V_Mobile),
                                'EmailAddress' => getStringValue($VendorData->V_Email),
                                'Latitude' => getStringValue($VendorData->V_Latitude),
                                'Longitude' => getStringValue($VendorData->V_Longitude),
                                'Address' => getStringValue($VendorData->V_Address),
                                'Location' => getStringValue($VendorData->V_Location),
                                'City' => getStringValue($VendorData->V_City),
                                'Country' => getStringValue($VendorData->V_Country),
                                'ProfileImage' => $VendorProfileImage,
                                'ProfileStatus' => $VendorData->V_ProfileStatus,
                                'VerificationStatus' => $VendorData->V_VerificationStatus,
                                'VerificationMessage' => getStringValue($VendorData->V_VerificationMessage),
                                'IdentityVerificationStatus' => $IdentityVerificationStatus,
                                'AboutMeStatus' => $AboutMeStatus,
                                'WorksStatus' => $WorksStatus,
                                'BusinessLocationStatus' => $BusinessLocationStatus,
                            );

                            $result = array(
                                'status' => 'success', 'flag' => '1', 'message' => 'Vendor Record Fetched',
                                'data' => $Record
                            );
                        } elseif ($VendorArray === false) {
                            $result = array(
                                'status' => 'error', 'flag' => '2', 'message' => 'Vendor Record Not Fetched',
                                'data' => (object)array()
                            );
                        }
                    } else {
                        $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.');
                    }
                } else {
                    if (!empty($data['Action']) && $data['Action'] == 'GetIdentity') {

                        if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                            if ($VendorData) {

                                $ProfileData = $this->Vendors_model->getProfile(null,
                                    array('P_VendorID' => $data['Val_Vendor']));

                                if (!empty($ProfileData)) {
                                    $ProfileData = (object)$ProfileData[0];
                                    $IdentityStatus = $ProfileData->P_IDCardStatus;
                                    $PersonalStatus = $ProfileData->P_PersonalStatus;
                                    $CurrentStatus = $ProfileData->P_CurrentStatus;
                                    $TermsStatus = $ProfileData->P_TermsStatus;
                                    $Record = array(
                                        'VendorID' => getStringValue($data['Val_Vendor']),
                                        'ProfileID' => getStringValue($ProfileData->VProfileID),
                                        'IdentityStatus' => getStringValue($ProfileData->P_IDCardStatus),
                                        'PersonalDetailsStatus' => getStringValue($ProfileData->P_PersonalStatus),
                                        'CurrentAddressStatus' => getStringValue($ProfileData->P_CurrentStatus),
                                        'TermsStatus' => getStringValue($ProfileData->P_TermsStatus),
                                    );
                                    $result = array(
                                        'status' => 'success', 'flag' => '1', 'message' => 'Vendor Record Fetched',
                                        'data' => $Record
                                    );

                                } else {
                                    $IdentityStatus = '';
                                    $PersonalStatus = '';
                                    $CurrentStatus = '';
                                    $TermsStatus = '';
                                    $result = array(
                                        'status' => 'success', 'flag' => '1', 'message' => 'No Record Fetched',
                                        'data' => (object)array()
                                    );
                                }


                            } elseif ($VendorData === false) {
                                $result = array(
                                    'status' => 'error', 'flag' => '2', 'message' => 'Vendor Record Not Fetched',
                                    'data' => (object)array()
                                );
                            }
                        } else {
                            $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.');
                        }
                    } else {
                        if (!empty($data['Action']) && $data['Action'] == 'GetIdentityDetails') {

                            if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                if ($VendorData) {

                                    $ProfileData = $this->Vendors_model->getProfile(null,
                                        array('P_VendorID' => $data['Val_Vendor']));

                                    if (!empty($ProfileData)) {
                                        $ProfileData = (object)$ProfileData[0];
                                        $IdentityStatus = $ProfileData->P_IDCardStatus;
                                        $PersonalStatus = $ProfileData->P_PersonalStatus;
                                        $CurrentStatus = $ProfileData->P_CurrentStatus;
                                        $TermsStatus = $ProfileData->P_TermsStatus;

                                        $FrontImage = (!empty($ProfileData->P_IDCardFrontImage) ? UPLOAD_VENDOR_BASE_URL . $data['Val_Vendor'] . '/' . $ProfileData->P_IDCardFrontImage : '');
                                        $BackImage = (!empty($ProfileData->P_IDCardBackImage) ? UPLOAD_VENDOR_BASE_URL . $data['Val_Vendor'] . '/' . $ProfileData->P_IDCardBackImage : '');

                                        $Record = array(
                                            'VendorID' => getStringValue($data['Val_Vendor']),
                                            'ProfileID' => getStringValue($ProfileData->VProfileID),
                                            'IDCardType' => getStringValue($ProfileData->P_IDCardType),
                                            'IDCardName' => getStringValue($ProfileData->P_IDCardName),
                                            'IDCardNumber' => getStringValue($ProfileData->P_IDCardNumber),
                                            'IDCardFrontImage' => getStringValue($FrontImage),
                                            'IDCardBackImage' => getStringValue($BackImage),
                                            'GuardianName' => getStringValue($ProfileData->P_GuardianName),
                                            'Gender' => getStringValue($ProfileData->P_Gender),
                                            'BirthDate' => getStringValue($ProfileData->P_BirthDate),
                                            'PermanentBuilding' => getStringValue($ProfileData->P_PermanentBuilding),
                                            'PermanentLocality' => getStringValue($ProfileData->P_PermanentLocality),
                                            'PermanentCity' => getStringValue($ProfileData->P_PermanentCity),
                                            'PermanentState' => getStringValue($ProfileData->P_PermanentState),
                                            'PermanentPincode' => getStringValue($ProfileData->P_PermanentPincode),
                                            'CurrentBuilding' => getStringValue($ProfileData->P_CurrentBuilding),
                                            'CurrentLocality' => getStringValue($ProfileData->P_CurrentLocality),
                                            'CurrentCity' => getStringValue($ProfileData->P_CurrentCity),
                                            'CurrentState' => getStringValue($ProfileData->P_CurrentState),
                                            'CurrentPincode' => getStringValue($ProfileData->P_CurrentPincode),
                                            'IdentityStatus' => getStringValue($ProfileData->P_IDCardStatus),
                                            'PersonalDetailsStatus' => getStringValue($ProfileData->P_PersonalStatus),
                                            'CurrentAddressStatus' => getStringValue($ProfileData->P_CurrentStatus),
                                            'TermsStatus' => getStringValue($ProfileData->P_TermsStatus),
                                        );
                                        $result = array(
                                            'status' => 'success', 'flag' => '1',
                                            'message' => 'Vendor Profile Record Fetched', 'data' => $Record
                                        );

                                    } else {
                                        $IdentityStatus = '';
                                        $PersonalStatus = '';
                                        $CurrentStatus = '';
                                        $TermsStatus = '';
                                        $result = array(
                                            'status' => 'success', 'flag' => '1', 'message' => 'No Record Fetched',
                                            'data' => (object)array()
                                        );
                                    }


                                } elseif ($VendorData === false) {
                                    $result = array(
                                        'status' => 'error', 'flag' => '2', 'message' => 'Vendor Record Not Fetched',
                                        'data' => (object)array()
                                    );
                                }
                            } else {
                                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.');
                            }
                        } else {
                            if (!empty($data['Action']) && $data['Action'] == 'GetAboutDetails') {

                                if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                    if ($VendorData) {

                                        $AboutData = $this->Vendors_model->getAbout(null,
                                            array('A_VendorID' => $data['Val_Vendor']));

                                        if (!empty($AboutData)) {
                                            $AboutData = (object)$AboutData[0];
                                            $AStatus = $AboutData->A_Status;

                                            $WorkLinks = json_decode($AboutData->A_WorkLinks);
                                            if (empty($WorkLinks)) {
                                                $WorkLinks = array('', '', '', '', '');
                                            } else {
                                                $WorkLinksCount = count($WorkLinks);
                                                if ($WorkLinksCount != 5) {
                                                    for ($i = $WorkLinksCount; $i < 5; $i++) {
                                                        $WorkLinks[] = "";

                                                    }
                                                }
                                            }
                                            $Specialization = json_decode($AboutData->A_Specialization);
                                            if (empty($Specialization)) {
                                                $Specialization = array('', '', '', '', '');
                                            } else {
                                                $SpecializationCount = count($Specialization);
                                                if ($SpecializationCount != 5) {
                                                    for ($i = $SpecializationCount; $i < 5; $i++) {
                                                        $Specialization[] = "";

                                                    }
                                                }
                                            }

                                            $Record = array(
                                                'VendorID' => getStringValue($data['Val_Vendor']),
                                                'AboutID' => getStringValue($AboutData->VAboutID),
                                                'BusinessName' => getStringValue($AboutData->A_BusinessName),
                                                'BusinessPresence' => getStringValue($AboutData->A_BusinessPresence),
                                                'ProfileLink' => getStringValue($AboutData->A_ProfileLink),
                                                'FacebookLink' => getStringValue($AboutData->A_FacebookLink),
                                                'WorkLinks' => $WorkLinks,
                                                'PhoneNumber' => getStringValue($AboutData->A_PhoneNumber),
                                                'Type' => getStringValue($AboutData->A_Type),
                                                'ExperienceYear' => getStringValue($AboutData->A_ExperienceYear),
                                                'ExperienceMonth' => getStringValue($AboutData->A_ExperienceMonth),
                                                'Introduction' => getStringValue($AboutData->A_Introduction),
                                                'StartingPrice' => getStringValue($AboutData->A_StartingPrice),
                                                'Specialization' => $Specialization,
                                                'AboutStatus' => getStringValue($AStatus),
                                            );
                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Vendor About Record Fetched', 'data' => $Record
                                            );

                                        } else {

                                            $result = array(
                                                'status' => 'success', 'flag' => '1', 'message' => 'No Record Fetched',
                                                'data' => (object)array()
                                            );
                                        }


                                    } elseif ($VendorData === false) {
                                        $result = array(
                                            'status' => 'error', 'flag' => '2',
                                            'message' => 'Vendor Record Not Fetched', 'data' => (object)array()
                                        );
                                    }
                                } else {
                                    $result = array(
                                        'status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.'
                                    );
                                }
                            } else {
                                if (!empty($data['Action']) && $data['Action'] == 'GetWorksDetails') {

                                    if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                        if ($VendorData) {

                                            $WorksData = $this->Vendors_model->getWorks(null,
                                                array('W_VendorID' => $data['Val_Vendor']));

                                            if (!empty($WorksData)) {
                                                $WorksData = (object)$WorksData[0];
                                                $WStatus = $WorksData->W_Status;

                                                $WorksGallery = array();

                                                $WorksGallery = json_decode($WorksData->W_WorksGallery);


                                                if (!empty($WorksGallery)) {
                                                    foreach ($WorksGallery as &$value) {
                                                        $value = UPLOAD_VENDOR_BASE_URL . $data['Val_Vendor'] . '/' . trim($value);
                                                    }
                                                } else {
                                                    $WorksGallery = array();
                                                }
                                                $Record = array(
                                                    'VendorID' => getStringValue($data['Val_Vendor']),
                                                    'WorkID' => getStringValue($WorksData->VWorkID),
                                                    'WorksGalleryCount' => (string)count($WorksGallery),
                                                    'WorksGallery' => $WorksGallery,
                                                    'WorkStatus' => getStringValue($WStatus),
                                                );
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Vendor Work Record Fetched', 'data' => $Record
                                                );

                                            } else {

                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'No Record Fetched', 'data' => (object)array()
                                                );
                                            }


                                        } elseif ($VendorData === false) {
                                            $result = array(
                                                'status' => 'error', 'flag' => '2',
                                                'message' => 'Vendor Record Not Fetched', 'data' => (object)array()
                                            );
                                        }
                                    } else {
                                        $result = array(
                                            'status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.'
                                        );
                                    }
                                } else {
                                    if (!empty($data['Action']) && $data['Action'] == 'GetLocationDetails') {

                                        if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                            if ($VendorData) {

                                                $LocationData = $this->Vendors_model->getLocations(null,
                                                    array('L_VendorID' => $data['Val_Vendor']));

                                                if (!empty($LocationData)) {
                                                    $LocationData = (object)$LocationData[0];
                                                    $LStatus = $LocationData->L_Status;


                                                    $Record = array(
                                                        'VendorID' => getStringValue($data['Val_Vendor']),
                                                        'LocationID' => getStringValue($LocationData->VLocationID),
                                                        'Location' => getStringValue($LocationData->L_Location),
                                                        'Latitude' => getStringValue($LocationData->L_Latitude),
                                                        'Longitude' => getStringValue($LocationData->L_Longitude),
                                                        'Radius' => getStringValue($LocationData->L_Radius),
                                                        'LocationStatus' => getStringValue($LStatus),
                                                    );
                                                    $result = array(
                                                        'status' => 'success', 'flag' => '1',
                                                        'message' => 'Vendor Location Record Fetched', 'data' => $Record
                                                    );

                                                } else {

                                                    $result = array(
                                                        'status' => 'success', 'flag' => '1',
                                                        'message' => 'No Record Fetched', 'data' => (object)array()
                                                    );
                                                }


                                            } elseif ($VendorData === false) {
                                                $result = array(
                                                    'status' => 'error', 'flag' => '2',
                                                    'message' => 'Vendor Record Not Fetched', 'data' => (object)array()
                                                );
                                            }
                                        } else {
                                            $result = array(
                                                'status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.'
                                            );
                                        }
                                    } else {
                                        $result = array(
                                            'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->data = $result;
        echo json_encode($this->data);
    }

    // Edit Profile Of Vendor ANd Service Provider
    public function Profile()
    {
        $data = $this->input->post();
        $Record = array();

        if (!empty($data) && $data['Action'] == 'Update') {
            if (!empty($data['Val_Vendor'])) {
                $data['Val_Vprofilestatus'] = '2';
                $success = $this->Vendors_model->update($data, $data['Val_Vendor']);

                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                if ($VendorData) {
                    $data['Val_Relation'] = $data['Val_Vendor'];
//							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');
                    $PostData = array();

                    $ProfileData = $this->Vendors_model->getProfile(null, array('P_VendorID' => $data['Val_Vendor']));
                    if (empty($ProfileData)) {
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $PSuccess = $this->Vendors_model->addProfile($PostData);
                    }

                    $AboutData = $this->Vendors_model->getAbout(null, array('A_VendorID' => $data['Val_Vendor']));
                    if (empty($AboutData)) {
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $ASuccess = $this->Vendors_model->addAbout($PostData);
                    }

                    $WorkData = $this->Vendors_model->getWorks(null, array('W_VendorID' => $data['Val_Vendor']));
                    if (empty($WorkData)) {
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $WSuccess = $this->Vendors_model->addWorks($PostData);
                    }

                    $LocationData = $this->Vendors_model->getLocations(null,
                        array('L_VendorID' => $data['Val_Vendor']));
                    if (empty($LocationData)) {
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $LSuccess = $this->Vendors_model->addLocations($PostData);
                    }

                    $AccountData = $this->Vendors_model->getAccounts(null, array('A_VendorID' => $data['Val_Vendor']));
                    if (empty($AccountData)) {
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $ACSuccess = $this->Vendors_model->addAccounts($PostData);
                    }

                    $AddressArray = $this->Vendors_model->getAddresses(null,
                        array('A_RelationID' => $VendorData->VendorID, 'A_Type' => '2'));

                    $AddressRecords = array();
                    $AddressCount = (string)count($AddressRecords);
                    $AddressData = $AddressRecords;
                    if (!empty($AddressArray)) {

                        foreach ($AddressArray as $Adress) {
                            $AddressRecords[] = array(
                                'AddressID' => getStringValue($Adress['AddressID']),
                                'Name' => getStringValue($Adress['A_Name']),
                                'Address' => getStringValue($Adress['A_Address']),
                                'Location' => getStringValue($Adress['A_Location']),
                                'Latitude' => getStringValue($Adress['A_Latitude']),
                                'Longitude' => getStringValue($Adress['A_Longitude']),
                            );
                        }
                    }
                    $AddressCount = (string)count($AddressRecords);
                    $AddressData = $AddressRecords;

                    $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                    $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : '');

                    $Record = array(
                        'VendorID' => getStringValue($VendorData->VendorID),
                        'FullName' => getStringValue($VendorFullName),
                        'FirstName' => getStringValue($VendorData->V_FirstName),
                        'LastName' => getStringValue($VendorData->V_LastName),
                        'CountryCode' => getStringValue($VendorData->V_CountryCode),
                        'MobileNumber' => getStringValue($VendorData->V_Mobile),
                        'EmailAddress' => getStringValue($VendorData->V_Email),

                        'Latitude' => getStringValue($VendorData->V_Latitude),
                        'Longitude' => getStringValue($VendorData->V_Longitude),
                        'Address' => getStringValue($VendorData->V_Address),
                        'AddressCount' => $AddressCount,
                        'AddressData' => $AddressData,
                        'Location' => getStringValue($VendorData->V_Location),
                        'City' => getStringValue($VendorData->V_City),
                        'Country' => getStringValue($VendorData->V_Country),

                        //										'ProfileImage' => $CustomerProfileImage,
                        'ProfileStatus' => $VendorData->V_ProfileStatus,
                        'VerificationStatus' => $VendorData->V_VerificationStatus,
                        'Status' => $VendorData->V_Status,
                    );
                }

//					if ($success || $Asuccess) {}
                if ($success) {
                    $result = array(
                        'status' => 'success', 'flag' => '1', 'message' => 'Vendor Profile Updated Successfully',
                        'data' => $Record
                    );
                } else {
                    if ($success == false) {
                        $result = array(
                            'status' => 'error', 'flag' => '2', 'message' => 'Vendor Profile Not Updated',
                            'data' => $data['Val_Vendor']
                        );
                    } else {
                        $result = array(
                            'status' => 'warning', 'flag' => '3', 'message' => 'Something Important', 'data' => $success
                        );
                    }
                }
            } else {
                $result = array(
                    'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...', 'data' => $Record
                );
            }

        } else {
            if (!empty($data) && $data['Action'] == 'UpdateProfileImage') {
                if (!empty($data['Val_Vendor'])) {


                    $success = handle_vendor_profile_image($data['Val_Vendor']);

                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                    if ($VendorData) {
                        $data['Val_Relation'] = $data['Val_Vendor'];
                        $AddressArray = $this->Vendors_model->getAddresses(null,
                            array('A_RelationID' => $VendorData->VendorID, 'A_Type' => '2'));

                        $AddressRecords = array();
                        $AddressCount = (string)count($AddressRecords);
                        $AddressData = $AddressRecords;
                        if (!empty($AddressArray)) {

                            foreach ($AddressArray as $Adress) {
                                $AddressRecords[] = array(
                                    'AddressID' => getStringValue($Adress['AddressID']),
                                    'Name' => getStringValue($Adress['A_Name']),
                                    'Address' => getStringValue($Adress['A_Address']),
                                    'Location' => getStringValue($Adress['A_Location']),
                                    'Latitude' => getStringValue($Adress['A_Latitude']),
                                    'Longitude' => getStringValue($Adress['A_Longitude']),
                                );
                            }
                        }
                        $AddressCount = (string)count($AddressRecords);
                        $AddressData = $AddressRecords;

                        $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                        $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : '');

                        $Record = array(
                            'VendorID' => getStringValue($VendorData->VendorID),
                            'FullName' => getStringValue($VendorFullName),
                            'FirstName' => getStringValue($VendorData->V_FirstName),
                            'LastName' => getStringValue($VendorData->V_LastName),
                            'CountryCode' => getStringValue($VendorData->V_CountryCode),
                            'MobileNumber' => getStringValue($VendorData->V_Mobile),
                            'EmailAddress' => getStringValue($VendorData->V_Email),
                            'Latitude' => getStringValue($VendorData->V_Latitude),
                            'Longitude' => getStringValue($VendorData->V_Longitude),
                            'Address' => getStringValue($VendorData->V_Address),
                            'AddressCount' => $AddressCount,
                            'AddressData' => $AddressData,
                            'Location' => getStringValue($VendorData->V_Location),
                            'City' => getStringValue($VendorData->V_City),
                            'Country' => getStringValue($VendorData->V_Country),
                            'ProfileImage' => $VendorProfileImage,
                            'ProfileStatus' => $VendorData->V_ProfileStatus,
                            'VerificationStatus' => $VendorData->V_VerificationStatus,
                            'Status' => $VendorData->V_Status,
                        );
                    }
//					if ($success || $Asuccess) {}
                    if (!empty($success) && !is_array($success) && $success == true) {
                        $result = array(
                            'status' => 'success', 'flag' => '1',
                            'message' => 'Vendor Profile Image Updated Successfully', 'data' => $Record
                        );
                    } else {
                        if ((is_array($success) && $success['flag'] == '1') || empty($success)) {
                            $result = array(
                                'status' => 'error', 'flag' => '2', 'message' => 'Vendor Profile Image Not Updated',
                                'data' => $success
                            );
                        } else {
                            $result = array(
                                'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                'data' => $success
                            );
                        }
                    }
                } else {
                    $result = array(
                        'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...', 'data' => $Record
                    );
                }

            } else {
                if (!empty($data) && $data['Action'] == 'UpdateIdentity') {
                    if (!empty($data['Val_Vendor']) && !empty($data['Val_Type'])) {

                        $ProfileData = $this->Vendors_model->getProfile(null,
                            array('P_VendorID' => $data['Val_Vendor']));

                        if (!empty($ProfileData)) {
                            $ProfileData = (object)$ProfileData[0];
                            if ($data['Val_Type'] == '1') {
                                $data['Val_Pidcardstatus'] = '2';
                                $FrontImageStatus = handle_vendor_identity_front_image($data['Val_Vendor']);
                                $BackImageStatus = handle_vendor_identity_back_image($data['Val_Vendor']);
                            } else {
                                if ($data['Val_Type'] == '2') {
                                    $data['Val_Ppersonalstatus'] = '2';
                                } else {
                                    if ($data['Val_Type'] == '3') {
                                        $data['Val_Pcurrentstatus'] = '2';
                                    } else {
                                        if ($data['Val_Type'] == '4') {
                                            $data['Val_Ptermsstatus'] = '2';
                                        }
                                    }
                                }
                            }

                            $success = $this->Vendors_model->updateProfile($data, $ProfileData->VProfileID);

                            $ProfileData = $this->Vendors_model->getProfile($ProfileData->VProfileID);
                            $Record = array(
                                'VendorID' => getStringValue($ProfileData->P_VendorID),
                                'ProfileID' => getStringValue($ProfileData->VProfileID),
                                'IdentityStatus' => getStringValue($ProfileData->P_IDCardStatus),
                                'PersonalDetailsStatus' => getStringValue($ProfileData->P_PersonalStatus),
                                'CurrentAddressStatus' => getStringValue($ProfileData->P_CurrentStatus),
                                'TermsStatus' => getStringValue($ProfileData->P_TermsStatus),
                            );

                            if ($success) {
                                $result = array(
                                    'status' => 'success', 'flag' => '1',
                                    'message' => 'Vendor Profile Updated Successfully', 'data' => $Record
                                );
                            } else {
                                if ($success == false) {
                                    $result = array(
                                        'status' => 'error', 'flag' => '2', 'message' => 'Vendor Profile Not Updated',
                                        'data' => $Record
                                    );
                                } else {
                                    $result = array(
                                        'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                        'data' => (object)array()
                                    );
                                }
                            }

                        } else {
                            $result = array(
                                'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                'data' => (object)array()
                            );
                        }

                    } else {
                        $result = array(
                            'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...', 'data' => $Record
                        );
                    }

                } else {
                    if (!empty($data) && $data['Action'] == 'UpdateAbout') {
                        if (!empty($data['Val_Vendor'])) {

                            $AboutData = $this->Vendors_model->getAbout(null,
                                array('A_VendorID' => $data['Val_Vendor']));

                            if (!empty($AboutData)) {
                                $AboutData = (object)$AboutData[0];


                                if (!empty($data['Val_Abusinessname']) && !empty($data['Val_Abusinesspresence'])) {
                                    $data['Val_Astatus'] = '2';
                                }


                                $success = $this->Vendors_model->updateAbout($data, $AboutData->VAboutID);

                                $AboutData = $this->Vendors_model->getAbout($AboutData->VAboutID);
                                $Record = array(
                                    'VendorID' => getStringValue($AboutData->A_VendorID),
                                    'AboutID' => getStringValue($AboutData->VAboutID),
                                    'AboutStatus' => getStringValue($AboutData->A_Status),
                                );

                                if ($success) {
                                    $result = array(
                                        'status' => 'success', 'flag' => '1',
                                        'message' => 'Vendor About Updated Successfully', 'data' => $Record
                                    );
                                } else {
                                    if ($success == false) {
                                        $result = array(
                                            'status' => 'error', 'flag' => '2', 'message' => 'Vendor About Not Updated',
                                            'data' => $Record
                                        );
                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                            'data' => (object)array()
                                        );
                                    }
                                }

                            } else {
                                $result = array(
                                    'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                    'data' => (object)array()
                                );
                            }

                        } else {
                            $result = array(
                                'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...',
                                'data' => $Record
                            );
                        }

                    } else {
                        if (!empty($data) && $data['Action'] == 'UpdateWorks') {
                            if (!empty($data['Val_Vendor'])) {

                                $WorksData = $this->Vendors_model->getWorks(null,
                                    array('W_VendorID' => $data['Val_Vendor']));

                                if (!empty($WorksData)) {
                                    $WorksData = (object)$WorksData[0];


                                    $data['Val_Wstatus'] = '2';


                                    $Gallery = handle_vendor_works_images($data['Val_Vendor']);
                                    $success = $this->Vendors_model->updateWorks($data, $WorksData->VWorkID);

                                    $WorksData = $this->Vendors_model->getWorks($WorksData->VWorkID);
                                    $Record = array(
                                        'VendorID' => getStringValue($WorksData->W_VendorID),
                                        'WorkID' => getStringValue($WorksData->VWorkID),
                                        'WorkStatus' => getStringValue($WorksData->W_Status),
                                    );

                                    if ($success) {
                                        $result = array(
                                            'status' => 'success', 'flag' => '1',
                                            'message' => 'Vendor Works Updated Successfully', 'data' => $Record
                                        );
                                    } else {
                                        if ($success == false) {
                                            $result = array(
                                                'status' => 'error', 'flag' => '2',
                                                'message' => 'Vendor Works Not Updated', 'data' => $Record
                                            );
                                        } else {
                                            $result = array(
                                                'status' => 'warning', 'flag' => '3',
                                                'message' => 'Something Important', 'data' => (object)array()
                                            );
                                        }
                                    }

                                } else {
                                    $result = array(
                                        'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                        'data' => (object)array()
                                    );
                                }

                            } else {
                                $result = array(
                                    'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...',
                                    'data' => $Record
                                );
                            }

                        } else {
                            if (!empty($data) && $data['Action'] == 'UpdateLocation') {
                                if (!empty($data['Val_Vendor'])) {

                                    $LocationData = $this->Vendors_model->getLocations(null,
                                        array('L_VendorID' => $data['Val_Vendor']));

                                    if (!empty($LocationData)) {
                                        $LocationData = (object)$LocationData[0];


                                        if (!empty($data['Val_Llocation']) && !empty($data['Val_Llatitude']) && !empty($data['Val_Llongitude']) && !empty($data['Val_Lradius'])) {
                                            $data['Val_Lstatus'] = '2';
                                        }


                                        $success = $this->Vendors_model->updateLocations($data,
                                            $LocationData->VLocationID);

                                        $LocationData = $this->Vendors_model->getLocations($LocationData->VLocationID);
                                        $Record = array(
                                            'VendorID' => getStringValue($LocationData->L_VendorID),
                                            'LocationID' => getStringValue($LocationData->VLocationID),
                                            'LocationStatus' => getStringValue($LocationData->L_Status),
                                        );

                                        if ($success) {
                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Vendor Location Updated Successfully', 'data' => $Record
                                            );
                                        } else {
                                            if ($success == false) {
                                                $result = array(
                                                    'status' => 'error', 'flag' => '2',
                                                    'message' => 'Vendor Location Not Updated', 'data' => $Record
                                                );
                                            } else {
                                                $result = array(
                                                    'status' => 'warning', 'flag' => '3',
                                                    'message' => 'Something Important', 'data' => (object)array()
                                                );
                                            }
                                        }

                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3', 'message' => 'Something Important',
                                            'data' => (object)array()
                                        );
                                    }

                                } else {
                                    $result = array(
                                        'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...',
                                        'data' => $Record
                                    );
                                }

                            } else {
                                if (!empty($data) && $data['Action'] == 'DeleteWorkImage') {


                                    if (!empty($data['Val_Vendor'])) {

                                        $WorksData = $this->Vendors_model->getWorks(null,
                                            array('W_VendorID' => $data['Val_Vendor']));

                                        if (!empty($WorksData)) {
                                            $WorksData = (object)$WorksData[0];


                                            $WorksGallery = json_decode($WorksData->W_WorksGallery);

                                            if (!empty($WorksGallery) && is_array($WorksGallery)) {
                                                foreach ($WorksGallery as $WorkImage) {
                                                    if ($data['Val_Image'] != $WorkImage) {
                                                        $WorkImagesGallery[] = $WorkImage;
                                                    }

                                                }
                                                if (count($WorkImagesGallery) == '0') {
                                                    $data['Val_Wstatus'] = '1';
                                                }

                                                $data['Val_Wworksgallery'] = json_encode($WorkImagesGallery);

                                            } else {
                                            }


                                            $success = $this->Vendors_model->updateWorks($data, $WorksData->VWorkID);

                                            if ($success) {
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Vendor Works Image Delete Successfully',
                                                    'data' => (object)array()
                                                );
                                            } else {
                                                if ($success == false) {
                                                    $result = array(
                                                        'status' => 'error', 'flag' => '2',
                                                        'message' => 'Vendor Works Image Not Delete',
                                                        'data' => (object)array()
                                                    );
                                                } else {
                                                    $result = array(
                                                        'status' => 'warning', 'flag' => '3',
                                                        'message' => 'Something Important', 'data' => (object)array()
                                                    );
                                                }
                                            }

                                        } else {
                                            $result = array(
                                                'status' => 'warning', 'flag' => '3',
                                                'message' => 'Something Important', 'data' => (object)array()
                                            );
                                        }

                                    } else {
                                        $result = array(
                                            'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...',
                                            'data' => $Record
                                        );
                                    }


                                } else {
                                    if (!empty($data) && $data['Action'] == 'SubmitForApproval') {
                                        if (!empty($data['Val_Vendor'])) {
                                            $data['Val_Vverificationstatus'] = '2';
                                            $success = $this->Vendors_model->update($data, $data['Val_Vendor']);

                                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                            if ($VendorData) {

                                                $ProfileData = $this->Vendors_model->getProfile(null,
                                                    array('P_VendorID' => $data['Val_Vendor']));
                                                $AboutData = $this->Vendors_model->getAbout(null,
                                                    array('A_VendorID' => $data['Val_Vendor']));
                                                $WorksData = $this->Vendors_model->getWorks(null,
                                                    array('W_VendorID' => $data['Val_Vendor']));
                                                $LocationsData = $this->Vendors_model->getLocations(null,
                                                    array('L_VendorID' => $data['Val_Vendor']));

                                                if (!empty($ProfileData)) {
                                                    $ProfileData = (object)$ProfileData[0];
                                                    $IdentityStatus = $ProfileData->P_IDCardStatus;
                                                    $PersonalStatus = $ProfileData->P_PersonalStatus;
                                                    $CurrentStatus = $ProfileData->P_CurrentStatus;
                                                    $TermsStatus = $ProfileData->P_TermsStatus;

                                                } else {
                                                    $IdentityStatus = '';
                                                    $PersonalStatus = '';
                                                    $CurrentStatus = '';
                                                    $TermsStatus = '';
                                                }

                                                if ($IdentityStatus == '2' && $PersonalStatus == '2' && $CurrentStatus == '2' && $TermsStatus == '2') {
                                                    $IdentityVerificationStatus = '2';
                                                } else {
                                                    $IdentityVerificationStatus = '1';
                                                }


                                                if (!empty($AboutData)) {
                                                    $AboutData = (object)$AboutData[0];
                                                    $AboutMeStatus = $AboutData->A_Status;

                                                } else {
                                                    $AboutMeStatus = '';
                                                }

                                                if (!empty($WorksData)) {
                                                    $WorksData = (object)$WorksData[0];
                                                    $WorksStatus = $WorksData->W_Status;

                                                } else {
                                                    $WorksStatus = '';
                                                }

                                                if (!empty($LocationsData)) {
                                                    $LocationsData = (object)$LocationsData[0];
                                                    $BusinessLocationStatus = $LocationsData->L_Status;

                                                } else {
                                                    $BusinessLocationStatus = '';
                                                }

                                                $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                                                $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : "");
                                                $Record = array(
                                                    'VendorID' => getStringValue($VendorData->VendorID),
                                                    'FullName' => getStringValue($VendorFullName),
                                                    'FirstName' => getStringValue($VendorData->V_FirstName),
                                                    'LastName' => getStringValue($VendorData->V_LastName),
                                                    'CountryCode' => getStringValue($VendorData->V_CountryCode),
                                                    'MobileNumber' => getStringValue($VendorData->V_Mobile),
                                                    'EmailAddress' => getStringValue($VendorData->V_Email),
                                                    'Latitude' => getStringValue($VendorData->V_Latitude),
                                                    'Longitude' => getStringValue($VendorData->V_Longitude),
                                                    'Address' => getStringValue($VendorData->V_Address),
                                                    'Location' => getStringValue($VendorData->V_Location),
                                                    'City' => getStringValue($VendorData->V_City),
                                                    'Country' => getStringValue($VendorData->V_Country),
                                                    'ProfileImage' => $VendorProfileImage,
                                                    'ProfileStatus' => $VendorData->V_ProfileStatus,
                                                    'VerificationStatus' => $VendorData->V_VerificationStatus,
                                                    'VerificationMessage' => getStringValue($VendorData->V_VerificationMessage),
                                                    'IdentityVerificationStatus' => $IdentityVerificationStatus,
                                                    'AboutMeStatus' => $AboutMeStatus,
                                                    'WorksStatus' => $WorksStatus,
                                                    'BusinessLocationStatus' => $BusinessLocationStatus,
                                                );

                                            }

                                            if ($success) {
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Vendor Profile Submitted Successfully',
                                                    'data' => $Record
                                                );
                                            } else {
                                                if ($success == false) {
                                                    $result = array(
                                                        'status' => 'error', 'flag' => '2',
                                                        'message' => 'Vendor Profile Not Submitted ',
                                                        'data' => (object)array()
                                                    );
                                                } else {
                                                    $result = array(
                                                        'status' => 'warning', 'flag' => '3',
                                                        'message' => 'Something Important', 'data' => (object)array()
                                                    );
                                                }
                                            }
                                        } else {
                                            $result = array(
                                                'status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...',
                                                'data' => (object)array()
                                            );
                                        }

                                    } else {
                                        if (!empty($data) && $data['Action'] == 'VerificationStatus') {
                                            if (!empty($data['Val_Vendor'])) {
                                                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                                if ($VendorData) {

                                                    $ProfileData = $this->Vendors_model->getProfile(null,
                                                        array('P_VendorID' => $data['Val_Vendor']));
                                                    $AboutData = $this->Vendors_model->getAbout(null,
                                                        array('A_VendorID' => $data['Val_Vendor']));
                                                    $WorksData = $this->Vendors_model->getWorks(null,
                                                        array('W_VendorID' => $data['Val_Vendor']));
                                                    $LocationsData = $this->Vendors_model->getLocations(null,
                                                        array('L_VendorID' => $data['Val_Vendor']));

                                                    if (!empty($ProfileData)) {
                                                        $ProfileData = (object)$ProfileData[0];
                                                        $IdentityStatus = $ProfileData->P_IDCardStatus;
                                                        $PersonalStatus = $ProfileData->P_PersonalStatus;
                                                        $CurrentStatus = $ProfileData->P_CurrentStatus;
                                                        $TermsStatus = $ProfileData->P_TermsStatus;

                                                    } else {
                                                        $IdentityStatus = '';
                                                        $PersonalStatus = '';
                                                        $CurrentStatus = '';
                                                        $TermsStatus = '';
                                                    }

                                                    if ($IdentityStatus == '2' && $PersonalStatus == '2' && $CurrentStatus == '2' && $TermsStatus == '2') {
                                                        $IdentityVerificationStatus = '2';
                                                    } else {
                                                        $IdentityVerificationStatus = '1';
                                                    }


                                                    if (!empty($AboutData)) {
                                                        $AboutData = (object)$AboutData[0];
                                                        $AboutMeStatus = $AboutData->A_Status;

                                                    } else {
                                                        $AboutMeStatus = '';
                                                    }

                                                    if (!empty($WorksData)) {
                                                        $WorksData = (object)$WorksData[0];
                                                        $WorksStatus = $WorksData->W_Status;

                                                    } else {
                                                        $WorksStatus = '';
                                                    }

                                                    if (!empty($LocationsData)) {
                                                        $LocationsData = (object)$LocationsData[0];
                                                        $BusinessLocationStatus = $LocationsData->L_Status;

                                                    } else {
                                                        $BusinessLocationStatus = '';
                                                    }

                                                    $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                                                    $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : "");
                                                    $Record = array(
                                                        'VendorID' => getStringValue($VendorData->VendorID),
                                                        'FullName' => getStringValue($VendorFullName),
                                                        'FirstName' => getStringValue($VendorData->V_FirstName),
                                                        'LastName' => getStringValue($VendorData->V_LastName),
                                                        'CountryCode' => getStringValue($VendorData->V_CountryCode),
                                                        'MobileNumber' => getStringValue($VendorData->V_Mobile),
                                                        'EmailAddress' => getStringValue($VendorData->V_Email),
                                                        'Latitude' => getStringValue($VendorData->V_Latitude),
                                                        'Longitude' => getStringValue($VendorData->V_Longitude),
                                                        'Address' => getStringValue($VendorData->V_Address),
                                                        'Location' => getStringValue($VendorData->V_Location),
                                                        'City' => getStringValue($VendorData->V_City),
                                                        'Country' => getStringValue($VendorData->V_Country),
                                                        'ProfileImage' => $VendorProfileImage,
                                                        'ProfileStatus' => $VendorData->V_ProfileStatus,
                                                        'VerificationStatus' => $VendorData->V_VerificationStatus,
                                                        'VerificationMessage' => getStringValue($VendorData->V_VerificationMessage),
                                                        'IdentityVerificationStatus' => $IdentityVerificationStatus,
                                                        'AboutMeStatus' => $AboutMeStatus,
                                                        'WorksStatus' => $WorksStatus,
                                                        'BusinessLocationStatus' => $BusinessLocationStatus,
                                                    );


                                                    $result = array(
                                                        'status' => 'success', 'flag' => '1',
                                                        'message' => 'Vendor Profile Submitted Successfully',
                                                        'data' => $Record
                                                    );
                                                } else {
                                                    $result = array(
                                                        'status' => 'error', 'flag' => '2',
                                                        'message' => 'Vendor Profile Not Submitted ',
                                                        'data' => (object)array()
                                                    );
                                                }
                                            } else {
                                                $result = array(
                                                    'status' => 'info', 'flag' => '4',
                                                    'message' => 'Parameter Missing...', 'data' => (object)array()
                                                );
                                            }
                                        } else {
                                            if (!empty($data) && $data['Action'] == 'UpdateAccount') {
                                                if (!empty($data['Val_Vendor'])) {

                                                    $AccountData = $this->Vendors_model->getAccounts(null,
                                                        array('A_VendorID' => $data['Val_Vendor']));

                                                    if (!empty($AccountData)) {
                                                        $AccountData = (object)$AccountData[0];


                                                        if (!empty($data['Val_Aaccountname']) && !empty($data['Val_Aaccounttype']) && !empty($data['Val_Aaccountnumber']) && !empty($data['Val_Aifscnumber'])) {
                                                            $data['Val_Astatus'] = '2';
                                                        }


                                                        $success = $this->Vendors_model->updateAccounts($data,
                                                            $AccountData->VAccountID);

                                                        $AccountData = $this->Vendors_model->getAccounts($AccountData->VAccountID);
                                                        $Record = array(
                                                            'VendorID' => getStringValue($AccountData->A_VendorID),
                                                            'AccountID' => getStringValue($AccountData->VAccountID),
                                                            'AccountStatus' => getStringValue($AccountData->A_Status),
                                                        );

                                                        if ($success) {
                                                            $result = array(
                                                                'status' => 'success', 'flag' => '1',
                                                                'message' => 'Vendor Account Updated Successfully',
                                                                'data' => $Record
                                                            );
                                                        } else {
                                                            if ($success == false) {
                                                                $result = array(
                                                                    'status' => 'error', 'flag' => '2',
                                                                    'message' => 'Vendor Account Not Updated',
                                                                    'data' => $Record
                                                                );
                                                            } else {
                                                                $result = array(
                                                                    'status' => 'warning', 'flag' => '3',
                                                                    'message' => 'Something Important',
                                                                    'data' => (object)array()
                                                                );
                                                            }
                                                        }

                                                    } else {
                                                        $result = array(
                                                            'status' => 'warning', 'flag' => '3',
                                                            'message' => 'Something Important',
                                                            'data' => (object)array()
                                                        );
                                                    }

                                                } else {
                                                    $result = array(
                                                        'status' => 'info', 'flag' => '4',
                                                        'message' => 'Parameter Missing...', 'data' => $Record
                                                    );
                                                }

                                            } else {
                                                if (!empty($data) && $data['Action'] == 'UpdateProfile') {
                                                    if (!empty($data['Val_Vendor'])) {
                                                        $data['Val_Vprofilestatus'] = '2';
                                                        $success = $this->Vendors_model->update($data,
                                                            $data['Val_Vendor']);

                                                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                                        if ($VendorData) {
                                                            $data['Val_Relation'] = $data['Val_Vendor'];
//							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');


                                                            $AddressArray = $this->Vendors_model->getAddresses(null,
                                                                array(
                                                                    'A_RelationID' => $VendorData->VendorID,
                                                                    'A_Type' => '2'
                                                                ));

                                                            $AddressRecords = array();
                                                            $AddressCount = (string)count($AddressRecords);
                                                            $AddressData = $AddressRecords;
                                                            if (!empty($AddressArray)) {

                                                                foreach ($AddressArray as $Adress) {
                                                                    $AddressRecords[] = array(
                                                                        'AddressID' => getStringValue($Adress['AddressID']),
                                                                        'Name' => getStringValue($Adress['A_Name']),
                                                                        'Address' => getStringValue($Adress['A_Address']),
                                                                        'Location' => getStringValue($Adress['A_Location']),
                                                                        'Latitude' => getStringValue($Adress['A_Latitude']),
                                                                        'Longitude' => getStringValue($Adress['A_Longitude']),
                                                                    );
                                                                }
                                                            }
                                                            $AddressCount = (string)count($AddressRecords);
                                                            $AddressData = $AddressRecords;

                                                            $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                                                            $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : '');

                                                            $Record = array(
                                                                'VendorID' => getStringValue($VendorData->VendorID),
                                                                'FullName' => getStringValue($VendorFullName),
                                                                'FirstName' => getStringValue($VendorData->V_FirstName),
                                                                'LastName' => getStringValue($VendorData->V_LastName),
                                                                'CountryCode' => getStringValue($VendorData->V_CountryCode),
                                                                'MobileNumber' => getStringValue($VendorData->V_Mobile),
                                                                'EmailAddress' => getStringValue($VendorData->V_Email),

                                                                'Latitude' => getStringValue($VendorData->V_Latitude),
                                                                'Longitude' => getStringValue($VendorData->V_Longitude),
                                                                'Address' => getStringValue($VendorData->V_Address),
                                                                'AddressCount' => $AddressCount,
                                                                'AddressData' => $AddressData,
                                                                'Location' => getStringValue($VendorData->V_Location),
                                                                'City' => getStringValue($VendorData->V_City),
                                                                'Country' => getStringValue($VendorData->V_Country),

                                                                //										'ProfileImage' => $CustomerProfileImage,
                                                                'ProfileStatus' => $VendorData->V_ProfileStatus,
                                                                'VerificationStatus' => $VendorData->V_VerificationStatus,
                                                                'Status' => $VendorData->V_Status,
                                                            );
                                                        }

//					if ($success || $Asuccess) {}
                                                        if ($success) {
                                                            $result = array(
                                                                'status' => 'success', 'flag' => '1',
                                                                'message' => 'Vendor Profile Updated Successfully',
                                                                'data' => $Record
                                                            );
                                                        } else {
                                                            if ($success == false) {
                                                                $result = array(
                                                                    'status' => 'error', 'flag' => '2',
                                                                    'message' => 'Vendor Profile Not Updated',
                                                                    'data' => $data['Val_Vendor']
                                                                );
                                                            } else {
                                                                $result = array(
                                                                    'status' => 'warning', 'flag' => '3',
                                                                    'message' => 'Something Important',
                                                                    'data' => $success
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        $result = array(
                                                            'status' => 'info', 'flag' => '4',
                                                            'message' => 'Parameter Missing...', 'data' => $Record
                                                        );
                                                    }

                                                } else {
                                                    if (!empty($data) && $data['Action'] == 'UpdateDetails') {

                                                        $success = $this->Vendors_model->update($data,
                                                            $data['Val_Vendor']);
                                                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                                        if ($VendorData) {
                                                            $data['Val_Relation'] = $data['Val_Vendor'];

                                                            $VendorFullName = $VendorData->C_FirstName . " " . $VendorData->C_LastName;
                                                            //$VendorProfileImage = (!empty($VendorData->C_ProfileImage) ? UPLOAD_VENDOR_BASE_URL.$VendorData->VendorID.'/'.$VendorData->C_ProfileImage : NULL);
                                                            $Record = array(
                                                                'VendorID' => getStringValue($VendorData->VendorID),
                                                                'FullName' => getStringValue($VendorFullName),
                                                                'FirstName' => getStringValue($VendorData->C_FirstName),
                                                                'LastName' => getStringValue($VendorData->C_LastName),
                                                                'CountryCode' => getStringValue($VendorData->C_CountryCode),
                                                                'MobileNumber' => getStringValue($VendorData->C_Mobile),
                                                                'EmailAddress' => getStringValue($VendorData->C_Email),
                                                                'Latitude' => getStringValue($VendorData->C_Latitude),
                                                                'Longitude' => getStringValue($VendorData->C_Longitude),
                                                                'Address' => getStringValue($VendorData->C_Address),
                                                                'Location' => getStringValue($VendorData->C_Location),
                                                                //		'ProfileImage'=> $VendorProfileImage,
                                                            );
                                                        }

                                                        if ($success) {
                                                            $result = array(
                                                                'status' => 'success', 'flag' => '1',
                                                                'message' => _l('msg_location_updated_success',
                                                                    _l('user_vendor')), 'data' => $Record
                                                            );
                                                        } else {
                                                            if ($success == false) {
                                                                $result = array(
                                                                    'status' => 'error', 'flag' => '2',
                                                                    'message' => _l('msg_location_updated_fail',
                                                                        _l('user_vendor')),
                                                                    'data' => $data['Val_Vendor']
                                                                );
                                                            } else {
                                                                $result = array(
                                                                    'status' => 'warning', 'flag' => '3',
                                                                    'message' => _l('msg_something_went_wrong'),
                                                                    'data' => $success
                                                                );
                                                            }
                                                        }

                                                    } /*		else if( !empty($data) && $data['Action'] == 'UpdateLocation' ) {

					$success = $this->Vendors_model->update($data,$data['Val_Vendor']);
					$VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

					if ($VendorData) {
							$data['Val_Relation'] = $data['Val_Vendor'];

							$AddressArray = $this->Vendors_model->getAddresses(NULL,array('A_VendorID'=>$VendorData->VendorID));

							$AddressRecords = array();
							$AddressCount 	= (string)count($AddressRecords);
							$AddressData 	= $AddressRecords;
							if(!empty($AddressArray)){
								foreach($AddressArray as $Adress)
									{
										$AddressRecords[] = array(
													'AddressID' 	=> getStringValue($Adress['AddressID']),
													'Name' 			=> getStringValue($Adress['A_Name']),
													'Address' 		=> getStringValue($Adress['A_Address']),
													'Location' 		=> getStringValue($Adress['A_Location']),
													'Latitude' 		=> getStringValue($Adress['A_Latitude']),
													'Longitude' 	=> getStringValue($Adress['A_Longitude']),
													);
									}
							}
							$AddressCount 	= (string)count($AddressRecords);
							$AddressData	= $AddressRecords;


							$VendorFullName = $VendorData->C_FirstName." ".$VendorData->C_LastName;
							$OTPResponse = "";
							//$VendorProfileImage = (!empty($VendorData->C_ProfileImage) ? UPLOAD_VENDOR_BASE_URL.$VendorData->VendorID.'/'.$VendorData->C_ProfileImage : NULL);
							$Record = array(
										'VendorID' 		=> getStringValue($VendorData->VendorID),
										'FullName' 			=> getStringValue($VendorFullName),
										'FirstName'			=> getStringValue($VendorData->C_FirstName),
										'LastName'			=> getStringValue($VendorData->C_LastName),
										'CountryCode'		=> getStringValue($VendorData->C_CountryCode),
										'MobileNumber'		=> getStringValue($VendorData->C_Mobile),
										'EmailAddress'		=> getStringValue($VendorData->C_Email),
										'Latitude'			=> getStringValue($VendorData->C_Latitude),
										'Longitude'			=> getStringValue($VendorData->C_Longitude),
										'Address'			=> getStringValue($VendorData->C_Address),
										'AddressCount'		=> $AddressCount,
										'AddressData'		=> $AddressData,
										'Location'			=> getStringValue($VendorData->C_Location),
										'OTPCode'			=> getStringValue($OTPResponse),
								//		'ProfileImage'=> $VendorProfileImage,
									);
					}

					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_location_updated_success',_l('user_vendor')),'data'=>$Record);
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_location_updated_fail',_l('user_vendor')),'data'=>$data['Val_Vendor']);
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>_l('msg_something_went_wrong'),'data'=>$success);
					}

		} */
                                                    else {
                                                        if (!empty($data) && $data['Action'] == 'AddAddress') {

                                                            if (!empty($data['Val_Vendor']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])) {

                                                                $success = $this->Vendors_model->addAddress($data);

                                                                $address_id = $success;
                                                                $Record = array();
                                                                if ($success) {
                                                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                    $AddressArray = $this->Vendors_model->getAddresses(null,
                                                                        array('A_VendorID' => $data['Val_Vendor']));

                                                                    $AddressRecords = array();
                                                                    $AddressCount = (string)count($AddressRecords);
                                                                    $AddressData = $AddressRecords;
                                                                    if (!empty($AddressArray)) {

                                                                        foreach ($AddressArray as $Adress) {
                                                                            $AddressRecords[] = array(
                                                                                'AddressID' => getStringValue($Adress['AddressID']),
                                                                                'Name' => getStringValue($Adress['A_Name']),
                                                                                'Address' => getStringValue($Adress['A_Address']),
                                                                                'Location' => getStringValue($Adress['A_Location']),
                                                                                'Latitude' => getStringValue($Adress['A_Latitude']),
                                                                                'Longitude' => getStringValue($Adress['A_Longitude']),
                                                                            );
                                                                        }
                                                                    }
                                                                    $AddressCount = (string)count($AddressRecords);
                                                                    $AddressData = $AddressRecords;
                                                                    $OTPResponse = "";

                                                                    $VendorFullName = getStringValue($VendorData->C_FirstName) . " " . getStringValue($VendorData->C_LastName);
                                                                    $Record = array(
                                                                        'VendorID' => getStringValue($VendorData->VendorID),
                                                                        'FullName' => getStringValue($VendorFullName),
                                                                        'FirstName' => getStringValue($VendorData->C_FirstName),
                                                                        'LastName' => getStringValue($VendorData->C_LastName),
                                                                        'CountryCode' => getStringValue($VendorData->C_CountryCode),
                                                                        'MobileNumber' => getStringValue($VendorData->C_Mobile),
                                                                        'EmailAddress' => getStringValue($VendorData->C_Email),
                                                                        'Latitude' => getStringValue($VendorData->C_Latitude),
                                                                        'Longitude' => getStringValue($VendorData->C_Longitude),
                                                                        'Address' => getStringValue($VendorData->C_Address),
                                                                        'AddressCount' => $AddressCount,
                                                                        'AddressData' => $AddressData,
                                                                        'Location' => getStringValue($VendorData->C_Location),
                                                                        'OTPCode' => getStringValue($OTPResponse),
                                                                        //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                                        //		'Status'=> getStatus($VendorData->C_Status),
                                                                    );

                                                                    $result = array(
                                                                        'status' => 'success', 'flag' => '1',
                                                                        'message' => 'Address Added Successfully',
                                                                        'data' => $Record
                                                                    );
                                                                } else {
                                                                    if ($success == false) {
                                                                        $result = array(
                                                                            'status' => 'error', 'flag' => '2',
                                                                            'message' => 'We couldn\'t add address. Please try again later.(404)',
                                                                            'data' => $Record
                                                                        );
                                                                    } else {
                                                                        $result = array(
                                                                            'status' => 'warning', 'flag' => '3',
                                                                            'message' => 'Something Important Happened !! ',
                                                                            'data' => $Record
                                                                        );
                                                                    }
                                                                }

                                                            } else {
                                                                $result = array(
                                                                    'status' => 'info', 'flag' => '4',
                                                                    'message' => 'Parameter Missing'
                                                                );
                                                            }

                                                        } else {
                                                            if (!empty($data) && $data['Action'] == 'UpdateAddress') {

                                                                if (!empty($data['Val_Vendor']) && !empty($data['Val_Address']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])) {

                                                                    $data['Val_Type'] = '1';
                                                                    $data['Val_Relation'] = $data['Val_Vendor'];
                                                                    unset($data['Val_Vendor']);
                                                                    $success = $this->Vendors_model->updateAddress($data,
                                                                        $data['Val_Address']);
                                                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Relation']);
                                                                    $AddressData = $this->Vendors_model->getAddresses($data['Val_Address']);
                                                                    $Record = array();
                                                                    if (!empty($VendorData) && !empty($AddressData)) {

                                                                        $AddressArray = $this->Vendors_model->getAddresses(null,
                                                                            array('A_RelationID' => $VendorData->VendorID));

                                                                        $AddressRecords = array();
                                                                        $AddressCount = (string)count($AddressRecords);
                                                                        $AddressData = $AddressRecords;
                                                                        if (!empty($AddressArray)) {
                                                                            foreach ($AddressArray as $Adress) {
                                                                                $AddressRecords[] = array(
                                                                                    'AddressID' => getStringValue($Adress['AddressID']),
                                                                                    'Name' => getStringValue($Adress['A_Name']),
                                                                                    'Address' => getStringValue($Adress['A_Address']),
                                                                                    'Location' => getStringValue($Adress['A_Location']),
                                                                                    'Latitude' => getStringValue($Adress['A_Latitude']),
                                                                                    'Longitude' => getStringValue($Adress['A_Longitude']),
                                                                                );
                                                                            }
                                                                        }
                                                                        $AddressCount = (string)count($AddressRecords);
                                                                        $AddressData = $AddressRecords;

                                                                        $OTPResponse = "";

                                                                        $VendorFullName = $VendorData->C_FirstName . " " . $VendorData->C_LastName;
                                                                        //$VendorProfileImage = (!empty($VendorData->C_ProfileImage) ? UPLOAD_VENDOR_BASE_URL.$VendorData->VendorID.'/'.$VendorData->C_ProfileImage : NULL);
                                                                        $Record = array(
                                                                            'VendorID' => getStringValue($VendorData->VendorID),
                                                                            'FullName' => getStringValue($VendorFullName),
                                                                            'FirstName' => getStringValue($VendorData->C_FirstName),
                                                                            'LastName' => getStringValue($VendorData->C_LastName),
                                                                            'CountryCode' => getStringValue($VendorData->C_CountryCode),
                                                                            'MobileNumber' => getStringValue($VendorData->C_Mobile),
                                                                            'EmailAddress' => getStringValue($VendorData->C_Email),
                                                                            'Latitude' => getStringValue($VendorData->C_Latitude),
                                                                            'Longitude' => getStringValue($VendorData->C_Longitude),
                                                                            'Address' => getStringValue($VendorData->C_Address),
                                                                            'AddressCount' => $AddressCount,
                                                                            'AddressData' => $AddressData,
                                                                            'Location' => getStringValue($VendorData->C_Location),
                                                                            'OTPCode' => getStringValue($OTPResponse),
                                                                            //		'ProfileImage'=> $VendorProfileImage,
                                                                        );
                                                                    }

                                                                    if ($success) {
                                                                        $result = array(
                                                                            'status' => 'success', 'flag' => '1',
                                                                            'message' => _l('msg_location_updated_success',
                                                                                _l('user_vendor')), 'data' => $Record
                                                                        );
                                                                    } else {
                                                                        if ($success == false) {
                                                                            $result = array(
                                                                                'status' => 'error', 'flag' => '2',
                                                                                'message' => _l('msg_location_updated_fail',
                                                                                    _l('user_vendor')),
                                                                                'data' => $data['Val_Vendor']
                                                                            );
                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'warning', 'flag' => '3',
                                                                                'message' => _l('msg_something_went_wrong'),
                                                                                'data' => $success
                                                                            );
                                                                        }
                                                                    }
                                                                } else {
                                                                    $result = array(
                                                                        'status' => 'info', 'flag' => '4',
                                                                        'message' => 'Parameter Missing'
                                                                    );
                                                                }
                                                            } else {
                                                                if (!empty($data) && $data['Action'] == 'DeleteAddress') {

                                                                    $success = $this->Vendors_model->deleteAddress($data['Val_Address']);

                                                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                    $AddressArray = $this->Vendors_model->getAddresses(null,
                                                                        array('A_VendorID' => $data['Val_Vendor']));

                                                                    $AddressRecords = array();
                                                                    $AddressCount = (string)count($AddressRecords);
                                                                    $AddressData = $AddressRecords;
                                                                    if (!empty($AddressArray)) {

                                                                        foreach ($AddressArray as $Adress) {
                                                                            $AddressRecords[] = array(
                                                                                'AddressID' => getStringValue($Adress['AddressID']),
                                                                                'Name' => getStringValue($Adress['A_Name']),
                                                                                'Address' => getStringValue($Adress['A_Address']),
                                                                                'Location' => getStringValue($Adress['A_Location']),
                                                                                'Latitude' => getStringValue($Adress['A_Latitude']),
                                                                                'Longitude' => getStringValue($Adress['A_Longitude']),
                                                                            );
                                                                        }
                                                                    }
                                                                    $AddressCount = (string)count($AddressRecords);
                                                                    $AddressData = $AddressRecords;
                                                                    $OTPResponse = "";

                                                                    $VendorFullName = getStringValue($VendorData->C_FirstName) . " " . getStringValue($VendorData->C_LastName);
                                                                    $Record = array(
                                                                        'VendorID' => getStringValue($VendorData->VendorID),
                                                                        'FullName' => getStringValue($VendorFullName),
                                                                        'FirstName' => getStringValue($VendorData->C_FirstName),
                                                                        'LastName' => getStringValue($VendorData->C_LastName),
                                                                        'CountryCode' => getStringValue($VendorData->C_CountryCode),
                                                                        'MobileNumber' => getStringValue($VendorData->C_Mobile),
                                                                        'EmailAddress' => getStringValue($VendorData->C_Email),
                                                                        'Latitude' => getStringValue($VendorData->C_Latitude),
                                                                        'Longitude' => getStringValue($VendorData->C_Longitude),
                                                                        'Address' => getStringValue($VendorData->C_Address),
                                                                        'AddressCount' => $AddressCount,
                                                                        'AddressData' => $AddressData,
                                                                        'Location' => getStringValue($VendorData->C_Location),
                                                                        'OTPCode' => getStringValue($OTPResponse),
                                                                        //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                                        //		'Status'=> getStatus($VendorData->C_Status),
                                                                    );
                                                                    if ($success) {
                                                                        $result = array(
                                                                            'status' => 'success', 'flag' => '1',
                                                                            'message' => 'Vendor Address Deleted Successfully',
                                                                            'data' => $Record
                                                                        );
                                                                    } else {
                                                                        if ($success == false) {
                                                                            $result = array(
                                                                                'status' => 'error', 'flag' => '2',
                                                                                'message' => 'Vendor Address Not Deleted',
                                                                                'data' => $Record
                                                                            );
                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'warning', 'flag' => '3',
                                                                                'message' => 'Something Important',
                                                                                'data' => $success
                                                                            );
                                                                        }
                                                                    }

                                                                } else {
                                                                    if (!empty($data) && $data['Action'] == 'Delete') {

                                                                        $success = $this->Vendors_model->delete($data['Val_Vendor']);

                                                                        if ($success) {
                                                                            $Adata['Val_Relation'] = $data['Val_Vendor'];
                                                                            $Adata['Val_Status'] = '1';
                                                                            $Asuccess = $this->Authentication_model->AppUpdate($Adata,
                                                                                $Adata['Val_Relation'], '1');

                                                                        }

                                                                        if ($success || $Asuccess) {
                                                                            $result = array(
                                                                                'status' => 'success', 'flag' => '1',
                                                                                'message' => 'Vendor Profile Deleted Successfully',
                                                                                'data' => 'Confidential'
                                                                            );
                                                                        } else {
                                                                            if ($success == false && $Asuccess == false) {
                                                                                $result = array(
                                                                                    'status' => 'error', 'flag' => '2',
                                                                                    'message' => 'Vendor Profile Not Deleted',
                                                                                    'data' => $data['Val_Vendor']
                                                                                );
                                                                            } else {
                                                                                $result = array(
                                                                                    'status' => 'warning',
                                                                                    'flag' => '3',
                                                                                    'message' => 'Something Important',
                                                                                    'data' => $success
                                                                                );
                                                                            }
                                                                        }

                                                                    } else {
                                                                        $result = array(
                                                                            'status' => 'info', 'flag' => '4',
                                                                            'message' => 'Parameter Missing'
                                                                        );
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->data = $result;
        echo json_encode($this->data);
    }

    // Edit Profile Of Vendor ANd Service Provider
    public function Dashboard()
    {
        $Records = array();
        $Record = array();
        $data = $this->input->post();

        if (!empty($data['Action']) && $data['Action'] == 'GetData') {

            if (!empty($data['Val_Vendor'])) {
                $OrderRecords = array();
                $OrderRecordsCount = (string)count($OrderRecords);
                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                if (!empty($VendorData)) {
                    $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                    if (!empty($CategoryData)) {
                        $BusinessType = $CategoryData->C_Type;
                        if ($BusinessType == '1') {
                            $OrdersArray = $this->Cart_model->getServicePastOrdersData($data['Val_Vendor']);
                        } else {
                            if ($BusinessType == '2') {
                                $OrdersArray = $this->Cart_model->getProductsPastOrdersData($data['Val_Vendor']);

                            } else {
                                if ($BusinessType == '3') {
                                    $OrdersArray = $this->Cart_model->getRestaurantsPastOrdersData($data['Val_Vendor']);

                                }
                            }
                        }

                        $TotalSales = 0;
                        if (!empty($OrdersArray)) {
                            foreach ($OrdersArray as $OrderArray) {
                                $OrderData = (object)$OrderArray;

                                if (!empty($OrderData->CartID)) {
                                    //28:03:2019 08:41PM
                                    $TotalSales = $TotalSales + $OrderData->C_Total;

                                    $OrderID = $OrderData->CartID;
                                    $OrderType = '1';
                                    $OrderName = getOrderName('1', $OrderID);
                                    $OrderDate = $OrderData->C_AssignedDttm;
                                    $OrderTimeAgo = time_ago($OrderData->C_AssignedDttm);
                                    $OrderTotal = $OrderData->C_Total;
                                } else {
                                    if (!empty($OrderData->PCartID)) {
                                        //28:03:2019 08:41PM
                                        $TotalSales = $TotalSales + $OrderData->PC_Total;

                                        $OrderID = $OrderData->PCartID;
                                        $OrderType = '2';
                                        $OrderName = getOrderName('2', $OrderID);
                                        $OrderDate = $OrderData->PC_AssignedDttm;
                                        $OrderTimeAgo = time_ago($OrderData->PC_AssignedDttm);
                                        $OrderTotal = $OrderData->PC_Total;
                                    } else {
                                        if (!empty($OrderData->RCartID)) {
                                            //28:03:2019 08:41PM
                                            $TotalSales = $TotalSales + $OrderData->RC_Total;

                                            $OrderID = $OrderData->RCartID;
                                            $OrderType = '3';
                                            $OrderName = getOrderName('3', $OrderID);
                                            $OrderDate = $OrderData->RC_AssignedDttm;
                                            $OrderTimeAgo = time_ago($OrderData->RC_AssignedDttm);
                                            $OrderTotal = $OrderData->RC_Total;
                                        }
                                    }
                                }

                                $OrderRecords[] = array(
                                    'OrderID' => $OrderID,
                                    'OrderType' => $OrderType,
                                    'OrderName' => $OrderName,
                                    'OrderDate' => $OrderDate,
                                    'OrderTimeAgo' => $OrderTimeAgo,
                                    'OrderTotal' => "Rs. " . getStringValue($OrderTotal),
                                );
                            }
                        }


                        $CurrentMonth = date('m');
                        $LastMonth = date("m", strtotime("-1 month"));
                        $LastMonthYear = date("Y", strtotime("-1 month"));
                        //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                        $TotalDays = cal_days_in_month(CAL_GREGORIAN, $LastMonth, $LastMonthYear);

                        $StringDate = $LastMonthYear . '-' . $LastMonth . '-01';
                        $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                        //5th
                        $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +4 day"));
                        $OrdersArray1 = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_1' AND  DATE(C_BookedDttm) >= '$LastMonthDate' ");

                        //10th
                        $month_line_2 = date('Y-m-d', strtotime($LastMonthDate . " +9 day"));
                        $OrdersArray2 = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_2' AND  DATE(C_BookedDttm) > '$month_line_1' ");

                        //15th
                        $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +14 day"));
                        $OrdersArray3 = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_3' AND  DATE(C_BookedDttm) > '$month_line_2' ");
                        //20th
                        $month_line_4 = date('Y-m-d', strtotime($LastMonthDate . " +19 day"));
                        $OrdersArray4 = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_4' AND  DATE(C_BookedDttm) > '$month_line_3' ");
                        //25th
                        $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +24 day"));
                        $OrdersArray5 = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_5' AND  DATE(C_BookedDttm) > '$month_line_4' ");
                        //last day of month
                        $month_line_last = date('Y-m-d', strtotime($LastMonthDate . " next month - 1 hour"));
                        $OrdersArraylast = $this->Cart_model->getCartTotal(null,
                            array('C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'),
                            " DATE(C_BookedDttm) <= '$month_line_last' AND  DATE(C_BookedDttm) > '$month_line_5' ");


                        if (!empty($OrdersArray1)) {
                            $Ordertotal1 = $OrdersArray1->C_Total;
                        } else {
                            $Ordertotal1 = number_format('0', 2, '.', '');
                        }

                        if (!empty($OrdersArray2)) {
                            $Ordertotal2 = $OrdersArray2->C_Total;
                        } else {
                            $Ordertotal2 = number_format('0', 2, '.', '');
                        }

                        if (!empty($OrdersArray3)) {
                            $Ordertotal3 = $OrdersArray3->C_Total;
                        } else {
                            $Ordertotal3 = number_format('0', 2, '.', '');
                        }

                        if (!empty($OrdersArray4)) {
                            $Ordertotal4 = $OrdersArray4->C_Total;
                        } else {
                            $Ordertotal4 = number_format('0', 2, '.', '');
                        }

                        if (!empty($OrdersArray5)) {
                            $Ordertotal5 = $OrdersArray5->C_Total;
                        } else {
                            $Ordertotal5 = number_format('0', 2, '.', '');
                        }

                        if (!empty($OrdersArraylast)) {
                            $Ordertotallast = $OrdersArraylast->C_Total;
                        } else {
                            $Ordertotallast = number_format('0', 2, '.', '');
                        }


                        $AxisValues = array(
                            $month_line_1, $month_line_2, $month_line_3, $month_line_4, $month_line_5, $month_line_last
                        );
                        $PointValues = array(
                            $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5, $Ordertotallast
                        );

                        $MiscRecords = array(
                            'NotificationCount' => "1",
                        );


                        $Records['OrderRecords'] = $OrderRecords;
                        $Records['GraphRecords']['AxisValues'] = $AxisValues;
                        $Records['GraphRecords']['PointValues'] = $PointValues;
                        $Records['GraphRecords']['TotalSales'] = 'Rs. ' . number_format($TotalSales, '2');
                        $Records['MiscData'] = $MiscRecords;

                        $result = array(
                            'status' => 'success', 'flag' => '1', 'message' => 'Vendor Dashboard Data Fetched',
                            'data' => $Records
                        );


                    } else {
                        $result = array(
                            'status' => 'warning', 'flag' => '3', 'message' => 'Something Went Wrong...',
                            'data' => (object)$Records
                        );
                    }
                } else {
                    $result = array(
                        'status' => 'warning', 'flag' => '3', 'message' => 'Something Went Wrong...',
                        'data' => (object)$Records
                    );
                }


            } else {
                $result = array(
                    'status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing...', 'data' => (object)$Records
                );
            }

        } else {
            if (!empty($data['Action']) && $data['Action'] == 'GetRevenueData') {

                if (!empty($data['Val_Vendor'])) {
                    $OrderRecords = array();
                    $OrderRecordsCount = (string)count($OrderRecords);
                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                    if (!empty($VendorData)) {
                        $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                        if (!empty($CategoryData)) {
                            $BusinessType = $CategoryData->C_Type;
                            $Type = $data['Val_Type'];
                            if ($BusinessType == '1') {
                                $OrdersArray = $this->Cart_model->get(null, array(
                                    'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1', 'C_Status' => '3'
                                ));
                                if ($Type == '1') {

                                    $CurrentDay = date('Y-m-d');

                                    //5th
                                    $month_line_1 = date('Y-m-d', strtotime('-1 days'));
                                    $OrdersArray1 = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_1'");

                                    //10th
                                    $month_line_2 = date('Y-m-d', strtotime('-2 days'));
                                    $OrdersArray2 = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_2'");

                                    //15th
                                    $month_line_3 = date('Y-m-d', strtotime('-3 days'));
                                    $OrdersArray3 = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_3'");
                                    //20th
                                    $month_line_4 = date('Y-m-d', strtotime('-4 days'));
                                    $OrdersArray4 = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_4'");
                                    //25th
                                    $month_line_5 = date('Y-m-d', strtotime('-5 days'));
                                    $OrdersArray5 = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_5'");
                                    //last day of month
                                    $month_line_last = date('Y-m-d', strtotime('-6 days'));
                                    $OrdersArraylast = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$month_line_last'");


                                    if (!empty($OrdersArray1)) {
                                        $Ordertotal1 = number_format($OrdersArray1->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotal1 = number_format('0', 2, '.', '');
                                    }

                                    if (!empty($OrdersArray2)) {
                                        $Ordertotal2 = number_format($OrdersArray2->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotal2 = number_format('0', 2, '.', '');
                                    }

                                    if (!empty($OrdersArray3)) {
                                        $Ordertotal3 = number_format($OrdersArray3->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotal3 = number_format('0', 2, '.', '');
                                    }

                                    if (!empty($OrdersArray4)) {
                                        $Ordertotal4 = number_format($OrdersArray4->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotal4 = number_format('0', 2, '.', '');
                                    }

                                    if (!empty($OrdersArray5)) {
                                        $Ordertotal5 = number_format($OrdersArray5->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotal5 = number_format('0', 2, '.', '');
                                    }

                                    if (!empty($OrdersArraylast)) {
                                        $Ordertotallast = number_format($OrdersArraylast->C_Total, 2, '.', '');
                                    } else {
                                        $Ordertotallast = number_format('0', 2, '.', '');
                                    }


                                    $AxisValues = array(
                                        $month_line_1, $month_line_2, $month_line_3, $month_line_4, $month_line_5,
                                        $month_line_last
                                    );
                                    $PointValues = array(
                                        $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                        $Ordertotallast
                                    );

                                    $CurrentDate = date('Y-m-d');

                                    $TodaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$CurrentDate'");
                                    $TodaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$CurrentDate'");

                                    if (!empty($TodaysTotalOrdersArray)) {
                                        $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                    } else {
                                        $TotalOrdersToday = '0';
                                    }


                                    if (!empty($TodaysOrdersArray)) {
                                        $OrdertotalToday = $TodaysOrdersArray->C_Total;
                                    } else {
                                        $OrdertotalToday = number_format('0', 2, '.', '');
                                    }

                                    $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                    $YesterdaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$YesterdayDate'");
                                    $YesterdaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                        'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1', 'C_Status' => '3'
                                    ), " DATE(C_BookedDttm) = '$YesterdayDate'");

                                    if (!empty($YesterdaysTotalOrdersArray)) {
                                        $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                    } else {
                                        $TotalOrdersYesterday = '0';
                                    }


                                    if (!empty($YesterdaysOrdersArray)) {
                                        $OrdertotalYesterday = $YesterdaysOrdersArray->C_Total;
                                    } else {
                                        $OrdertotalYesterday = number_format('0', 2, '.', '');
                                    }

                                    $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                    $Records['GraphRecords']['PointValues'] = $PointValues;
                                    $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                    $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                    $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                    $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                    $Records['GraphRecords']['TotalSales'] = '';


                                } else {
                                    if ($Type == '2') {

                                        $CurrentMonth = date('m');
                                        $LastMonth = date("m", strtotime("-1 month"));
                                        $LastMonthYear = date("Y", strtotime("-1 month"));
                                        //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                        $TotalDays = cal_days_in_month(CAL_GREGORIAN, $LastMonth, $LastMonthYear);

                                        $StringDate = $LastMonthYear . '-' . $LastMonth . '-01';
                                        $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                        //5th
                                        $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +4 day"));
                                        $OrdersArray1 = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");

                                        //10th
                                        $month_line_2 = date('Y-m-d', strtotime($LastMonthDate . " +9 day"));
                                        $OrdersArray2 = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");

                                        //15th
                                        $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +14 day"));
                                        $OrdersArray3 = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
                                        //20th
                                        $month_line_4 = date('Y-m-d', strtotime($LastMonthDate . " +19 day"));
                                        $OrdersArray4 = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
                                        //25th
                                        $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +24 day"));
                                        $OrdersArray5 = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
                                        //last day of month
                                        $month_line_last = date('Y-m-d',
                                            strtotime($LastMonthDate . " next month - 1 hour"));
                                        $OrdersArraylast = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ),
                                            " DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");


                                        if (!empty($OrdersArray1)) {
                                            $Ordertotal1 = $OrdersArray1->PC_Total;
                                        } else {
                                            $Ordertotal1 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray2)) {
                                            $Ordertotal2 = $OrdersArray2->PC_Total;
                                        } else {
                                            $Ordertotal2 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray3)) {
                                            $Ordertotal3 = $OrdersArray3->PC_Total;
                                        } else {
                                            $Ordertotal3 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray4)) {
                                            $Ordertotal4 = $OrdersArray4->PC_Total;
                                        } else {
                                            $Ordertotal4 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray5)) {
                                            $Ordertotal5 = $OrdersArray5->PC_Total;
                                        } else {
                                            $Ordertotal5 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArraylast)) {
                                            $Ordertotallast = $OrdersArraylast->PC_Total;
                                        } else {
                                            $Ordertotallast = number_format('0', 2, '.', '');
                                        }


                                        $AxisValues = array(
                                            $month_line_1, $month_line_2, $month_line_3, $month_line_4, $month_line_5,
                                            $month_line_last
                                        );
                                        $PointValues = array(
                                            $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                            $Ordertotallast
                                        );

                                        $CurrentDate = date('Y-m-d');

                                        $TodaysOrdersArray = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ), " DATE(PC_BookedDttm) = '$CurrentDate'");
                                        $TodaysTotalOrdersArray = $this->Cart_model->getProductsCart(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                            'PC_Status' => '3'
                                        ), " DATE(PC_BookedDttm) = '$CurrentDate'");

                                        if (!empty($TodaysTotalOrdersArray)) {
                                            $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                        } else {
                                            $TotalOrdersToday = '0';
                                        }


                                        if (!empty($TodaysOrdersArray)) {
                                            $OrdertotalToday = $TodaysOrdersArray->PC_Total;
                                        } else {
                                            $OrdertotalToday = number_format('0', 2, '.', '');
                                        }

                                        $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                        $YesterdaysOrdersArray = $this->Cart_model->getProductsCartTotal(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                            'PC_Status' => '3'
                                        ), " DATE(PC_BookedDttm) = '$YesterdayDate'");
                                        $YesterdaysTotalOrdersArray = $this->Cart_model->getProductsCart(null, array(
                                            'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                            'PC_Status' => '3'
                                        ), " DATE(PC_BookedDttm) = '$YesterdayDate'");

                                        if (!empty($YesterdaysTotalOrdersArray)) {
                                            $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                        } else {
                                            $TotalOrdersYesterday = '0';
                                        }


                                        if (!empty($YesterdaysOrdersArray)) {
                                            $OrdertotalYesterday = $YesterdaysOrdersArray->PC_Total;
                                        } else {
                                            $OrdertotalYesterday = number_format('0', 2, '.', '');
                                        }

                                        $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                        $Records['GraphRecords']['PointValues'] = $PointValues;
                                        $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                        $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                        $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                        $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                        $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';


                                    } else {
                                        if ($Type == '3') {

                                            $CurrentMonth = date('m');
                                            $Last3rdMonth = date("m", strtotime("-3 month"));
                                            $Last3rdMonthYear = date("Y", strtotime("-3 month"));
                                            //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                            $TotalDays = cal_days_in_month(CAL_GREGORIAN, $Last3rdMonth,
                                                $Last3rdMonthYear);

                                            $StringDate = $Last3rdMonthYear . '-' . $Last3rdMonth . '-01';
                                            $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                            //5th
                                            $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +15 day"));
                                            $OrdersArray1 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");

                                            //10th
                                            $month_line_2 = date('Y-m-d',
                                                strtotime($LastMonthDate . " next month - 1 hour"));
                                            $OrdersArray2 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");

                                            //15th
                                            $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +45 day"));
                                            $OrdersArray3 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
                                            //20th
                                            $month_line_4 = date('Y-m-d',
                                                strtotime($LastMonthDate . "  +2 month - 1 hour"));
                                            $OrdersArray4 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
                                            //25th
                                            $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +75 day"));
                                            $OrdersArray5 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
                                            //last day of month
                                            $month_line_last = date('Y-m-d',
                                                strtotime($LastMonthDate . " +3 month - 1 hour"));
                                            $OrdersArraylast = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ),
                                                " DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");


                                            if (!empty($OrdersArray1)) {
                                                $Ordertotal1 = $OrdersArray1->RC_Total;
                                            } else {
                                                $Ordertotal1 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray2)) {
                                                $Ordertotal2 = $OrdersArray2->RC_Total;
                                            } else {
                                                $Ordertotal2 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray3)) {
                                                $Ordertotal3 = $OrdersArray3->RC_Total;
                                            } else {
                                                $Ordertotal3 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray4)) {
                                                $Ordertotal4 = $OrdersArray4->RC_Total;
                                            } else {
                                                $Ordertotal4 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray5)) {
                                                $Ordertotal5 = $OrdersArray5->RC_Total;
                                            } else {
                                                $Ordertotal5 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArraylast)) {
                                                $Ordertotallast = $OrdersArraylast->RC_Total;
                                            } else {
                                                $Ordertotallast = number_format('0', 2, '.', '');
                                            }

                                            $AxisValues = array(
                                                $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                $month_line_5, $month_line_last
                                            );
                                            $PointValues = array(
                                                $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                                $Ordertotallast
                                            );


                                            $CurrentDate = date('Y-m-d');

                                            $TodaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                'RC_Status' => '3'
                                            ), " DATE(RC_BookedDttm) = '$CurrentDate'");
                                            $TodaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null, array(
                                                'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '1',
                                                'RC_Status' => '3'
                                            ), " DATE(RC_BookedDttm) = '$CurrentDate'");

                                            if (!empty($TodaysTotalOrdersArray)) {
                                                $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersToday = '0';
                                            }


                                            if (!empty($TodaysOrdersArray)) {
                                                $OrdertotalToday = $TodaysOrdersArray->RC_Total;
                                            } else {
                                                $OrdertotalToday = number_format('0', 2, '.', '');
                                            }

                                            $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                            $YesterdaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null,
                                                array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ), " DATE(RC_BookedDttm) = '$YesterdayDate'");
                                            $YesterdaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null,
                                                array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '1',
                                                    'RC_Status' => '3'
                                                ), " DATE(RC_BookedDttm) = '$YesterdayDate'");

                                            if (!empty($YesterdaysTotalOrdersArray)) {
                                                $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersYesterday = '0';
                                            }


                                            if (!empty($YesterdaysOrdersArray)) {
                                                $OrdertotalYesterday = $YesterdaysOrdersArray->RC_Total;
                                            } else {
                                                $OrdertotalYesterday = number_format('0', 2, '.', '');
                                            }

                                            $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                            $Records['GraphRecords']['PointValues'] = $PointValues;
                                            $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                            $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                            $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                            $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                            $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';

                                        }
                                    }
                                }

                            } else {
                                if ($BusinessType == '2') {
                                    $OrdersArray = $this->Cart_model->getProductsCart(null, array(
                                        'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                        'PC_Status' => '3'
                                    ));
                                    if ($Type == '1') {

                                        $CurrentDay = date('Y-m-d');

                                        //5th
                                        $month_line_1 = date('Y-m-d', strtotime('-1 days'));
                                        $OrdersArray1 = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_1'");

                                        //10th
                                        $month_line_2 = date('Y-m-d', strtotime('-2 days'));
                                        $OrdersArray2 = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_2'");

                                        //15th
                                        $month_line_3 = date('Y-m-d', strtotime('-3 days'));
                                        $OrdersArray3 = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_3'");
                                        //20th
                                        $month_line_4 = date('Y-m-d', strtotime('-4 days'));
                                        $OrdersArray4 = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_4'");
                                        //25th
                                        $month_line_5 = date('Y-m-d', strtotime('-5 days'));
                                        $OrdersArray5 = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_5'");
                                        //last day of month
                                        $month_line_last = date('Y-m-d', strtotime('-6 days'));
                                        $OrdersArraylast = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$month_line_last'");


                                        if (!empty($OrdersArray1)) {
                                            $Ordertotal1 = number_format($OrdersArray1->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotal1 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray2)) {
                                            $Ordertotal2 = number_format($OrdersArray2->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotal2 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray3)) {
                                            $Ordertotal3 = number_format($OrdersArray3->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotal3 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray4)) {
                                            $Ordertotal4 = number_format($OrdersArray4->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotal4 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArray5)) {
                                            $Ordertotal5 = number_format($OrdersArray5->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotal5 = number_format('0', 2, '.', '');
                                        }

                                        if (!empty($OrdersArraylast)) {
                                            $Ordertotallast = number_format($OrdersArraylast->C_Total, 2, '.', '');
                                        } else {
                                            $Ordertotallast = number_format('0', 2, '.', '');
                                        }
                                        $AxisValues = array(
                                            $month_line_1, $month_line_2, $month_line_3, $month_line_4, $month_line_5,
                                            $month_line_last
                                        );
                                        $PointValues = array(
                                            $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                            $Ordertotallast
                                        );

                                        $CurrentDate = date('Y-m-d');

                                        $TodaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$CurrentDate'");
                                        $TodaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$CurrentDate'");

                                        if (!empty($TodaysTotalOrdersArray)) {
                                            $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                        } else {
                                            $TotalOrdersToday = '0';
                                        }


                                        if (!empty($TodaysOrdersArray)) {
                                            $OrdertotalToday = $TodaysOrdersArray->C_Total;
                                        } else {
                                            $OrdertotalToday = number_format('0', 2, '.', '');
                                        }

                                        $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                        $YesterdaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$YesterdayDate'");
                                        $YesterdaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                            'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1',
                                            'C_Status' => '3'
                                        ), " DATE(C_BookedDttm) = '$YesterdayDate'");

                                        if (!empty($YesterdaysTotalOrdersArray)) {
                                            $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                        } else {
                                            $TotalOrdersYesterday = '0';
                                        }


                                        if (!empty($YesterdaysOrdersArray)) {
                                            $OrdertotalYesterday = $YesterdaysOrdersArray->C_Total;
                                        } else {
                                            $OrdertotalYesterday = number_format('0', 2, '.', '');
                                        }

                                        $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                        $Records['GraphRecords']['PointValues'] = $PointValues;
                                        $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                        $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                        $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                        $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                        $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';


                                    } else {
                                        if ($Type == '2') {

                                            $CurrentMonth = date('m');
                                            $LastMonth = date("m", strtotime("-1 month"));
                                            $LastMonthYear = date("Y", strtotime("-1 month"));
                                            //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                            $TotalDays = cal_days_in_month(CAL_GREGORIAN, $LastMonth, $LastMonthYear);

                                            $StringDate = $LastMonthYear . '-' . $LastMonth . '-01';
                                            $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                            //5th
                                            $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +4 day"));
                                            $OrdersArray1 = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");

                                            //10th
                                            $month_line_2 = date('Y-m-d', strtotime($LastMonthDate . " +9 day"));
                                            $OrdersArray2 = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");

                                            //15th
                                            $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +14 day"));
                                            $OrdersArray3 = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
                                            //20th
                                            $month_line_4 = date('Y-m-d', strtotime($LastMonthDate . " +19 day"));
                                            $OrdersArray4 = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
                                            //25th
                                            $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +24 day"));
                                            $OrdersArray5 = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
                                            //last day of month
                                            $month_line_last = date('Y-m-d',
                                                strtotime($LastMonthDate . " next month - 1 hour"));
                                            $OrdersArraylast = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ),
                                                " DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");


                                            if (!empty($OrdersArray1)) {
                                                $Ordertotal1 = $OrdersArray1->PC_Total;
                                            } else {
                                                $Ordertotal1 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray2)) {
                                                $Ordertotal2 = $OrdersArray2->PC_Total;
                                            } else {
                                                $Ordertotal2 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray3)) {
                                                $Ordertotal3 = $OrdersArray3->PC_Total;
                                            } else {
                                                $Ordertotal3 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray4)) {
                                                $Ordertotal4 = $OrdersArray4->PC_Total;
                                            } else {
                                                $Ordertotal4 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray5)) {
                                                $Ordertotal5 = $OrdersArray5->PC_Total;
                                            } else {
                                                $Ordertotal5 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArraylast)) {
                                                $Ordertotallast = $OrdersArraylast->PC_Total;
                                            } else {
                                                $Ordertotallast = number_format('0', 2, '.', '');
                                            }


                                            $AxisValues = array(
                                                $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                $month_line_5, $month_line_last
                                            );
                                            $PointValues = array(
                                                $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                                $Ordertotallast
                                            );

                                            $CurrentDate = date('Y-m-d');

                                            $TodaysOrdersArray = $this->Cart_model->getProductsCartTotal(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                'PC_Status' => '3'
                                            ), " DATE(PC_BookedDttm) = '$CurrentDate'");
                                            $TodaysTotalOrdersArray = $this->Cart_model->getProductsCart(null, array(
                                                'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                                'PC_Status' => '3'
                                            ), " DATE(PC_BookedDttm) = '$CurrentDate'");

                                            if (!empty($TodaysTotalOrdersArray)) {
                                                $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersToday = '0';
                                            }


                                            if (!empty($TodaysOrdersArray)) {
                                                $OrdertotalToday = $TodaysOrdersArray->PC_Total;
                                            } else {
                                                $OrdertotalToday = number_format('0', 2, '.', '');
                                            }

                                            $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                            $YesterdaysOrdersArray = $this->Cart_model->getProductsCartTotal(null,
                                                array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ), " DATE(PC_BookedDttm) = '$YesterdayDate'");
                                            $YesterdaysTotalOrdersArray = $this->Cart_model->getProductsCart(null,
                                                array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                                    'PC_Status' => '3'
                                                ), " DATE(PC_BookedDttm) = '$YesterdayDate'");

                                            if (!empty($YesterdaysTotalOrdersArray)) {
                                                $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersYesterday = '0';
                                            }


                                            if (!empty($YesterdaysOrdersArray)) {
                                                $OrdertotalYesterday = $YesterdaysOrdersArray->PC_Total;
                                            } else {
                                                $OrdertotalYesterday = number_format('0', 2, '.', '');
                                            }


                                            $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                            $Records['GraphRecords']['PointValues'] = $PointValues;
                                            $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                            $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                            $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                            $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                            $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';


                                        } else {
                                            if ($Type == '3') {

                                                $CurrentMonth = date('m');
                                                $Last3rdMonth = date("m", strtotime("-3 month"));
                                                $Last3rdMonthYear = date("Y", strtotime("-3 month"));
                                                //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                                $TotalDays = cal_days_in_month(CAL_GREGORIAN, $Last3rdMonth,
                                                    $Last3rdMonthYear);

                                                $StringDate = $Last3rdMonthYear . '-' . $Last3rdMonth . '-01';
                                                $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                                //5th
                                                $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +15 day"));
                                                $OrdersArray1 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");

                                                //10th
                                                $month_line_2 = date('Y-m-d',
                                                    strtotime($LastMonthDate . " next month - 1 hour"));
                                                $OrdersArray2 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");

                                                //15th
                                                $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +45 day"));
                                                $OrdersArray3 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
                                                //20th
                                                $month_line_4 = date('Y-m-d',
                                                    strtotime($LastMonthDate . "  +2 month - 1 hour"));
                                                $OrdersArray4 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
                                                //25th
                                                $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +75 day"));
                                                $OrdersArray5 = $this->Cart_model->getRestaurantsCartTotal(null, array(
                                                    'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                    'RC_Status' => '3'
                                                ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
                                                //last day of month
                                                $month_line_last = date('Y-m-d',
                                                    strtotime($LastMonthDate . " +3 month - 1 hour"));
                                                $OrdersArraylast = $this->Cart_model->getRestaurantsCartTotal(null,
                                                    array(
                                                        'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                        'RC_Status' => '3'
                                                    ),
                                                    " DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");


                                                if (!empty($OrdersArray1)) {
                                                    $Ordertotal1 = $OrdersArray1->RC_Total;
                                                } else {
                                                    $Ordertotal1 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray2)) {
                                                    $Ordertotal2 = $OrdersArray2->RC_Total;
                                                } else {
                                                    $Ordertotal2 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray3)) {
                                                    $Ordertotal3 = $OrdersArray3->RC_Total;
                                                } else {
                                                    $Ordertotal3 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray4)) {
                                                    $Ordertotal4 = $OrdersArray4->RC_Total;
                                                } else {
                                                    $Ordertotal4 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray5)) {
                                                    $Ordertotal5 = $OrdersArray5->RC_Total;
                                                } else {
                                                    $Ordertotal5 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArraylast)) {
                                                    $Ordertotallast = $OrdersArraylast->RC_Total;
                                                } else {
                                                    $Ordertotallast = number_format('0', 2, '.', '');
                                                }


                                                $AxisValues = array(
                                                    $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                    $month_line_5, $month_line_last
                                                );
                                                $PointValues = array(
                                                    $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4,
                                                    $Ordertotal5, $Ordertotallast
                                                );


                                                $CurrentDate = date('Y-m-d');

                                                $TodaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null,
                                                    array(
                                                        'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                        'RC_Status' => '3'
                                                    ), " DATE(RC_BookedDttm) = '$CurrentDate'");
                                                $TodaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null,
                                                    array(
                                                        'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '1',
                                                        'RC_Status' => '3'
                                                    ), " DATE(RC_BookedDttm) = '$CurrentDate'");

                                                if (!empty($TodaysTotalOrdersArray)) {
                                                    $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                                } else {
                                                    $TotalOrdersToday = '0';
                                                }


                                                if (!empty($TodaysOrdersArray)) {
                                                    $OrdertotalToday = $TodaysOrdersArray->RC_Total;
                                                } else {
                                                    $OrdertotalToday = number_format('0', 2, '.', '');
                                                }

                                                $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                                $YesterdaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null,
                                                    array(
                                                        'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '4',
                                                        'RC_Status' => '3'
                                                    ), " DATE(RC_BookedDttm) = '$YesterdayDate'");
                                                $YesterdaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null,
                                                    array(
                                                        'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '1',
                                                        'RC_Status' => '3'
                                                    ), " DATE(RC_BookedDttm) = '$YesterdayDate'");

                                                if (!empty($YesterdaysTotalOrdersArray)) {
                                                    $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                                } else {
                                                    $TotalOrdersYesterday = '0';
                                                }


                                                if (!empty($YesterdaysOrdersArray)) {
                                                    $OrdertotalYesterday = $YesterdaysOrdersArray->RC_Total;
                                                } else {
                                                    $OrdertotalYesterday = number_format('0', 2, '.', '');
                                                }


                                                $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                                $Records['GraphRecords']['PointValues'] = $PointValues;
                                                $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                                $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                                $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                                $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                                $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';

                                            }
                                        }
                                    }

                                } else {
                                    if ($BusinessType == '3') {
                                        $OrdersArray = $this->Cart_model->getRestaurantsCart(null, array(
                                            'RC_AssignedTo' => $data['Val_Vendor'], 'RC_OrderStatus' => '1',
                                            'RC_Status' => '3'
                                        ));
                                        if ($Type == '1') {

                                            $CurrentDay = date('Y-m-d');

                                            //5th
                                            $month_line_1 = date('Y-m-d', strtotime('-1 days'));
                                            $OrdersArray1 = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_1'");

                                            //10th
                                            $month_line_2 = date('Y-m-d', strtotime('-2 days'));
                                            $OrdersArray2 = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_2'");

                                            //15th
                                            $month_line_3 = date('Y-m-d', strtotime('-3 days'));
                                            $OrdersArray3 = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_3'");
                                            //20th
                                            $month_line_4 = date('Y-m-d', strtotime('-4 days'));
                                            $OrdersArray4 = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_4'");
                                            //25th
                                            $month_line_5 = date('Y-m-d', strtotime('-5 days'));
                                            $OrdersArray5 = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_5'");
                                            //last day of month
                                            $month_line_last = date('Y-m-d', strtotime('-6 days'));
                                            $OrdersArraylast = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$month_line_last'");


                                            if (!empty($OrdersArray1)) {
                                                $Ordertotal1 = number_format($OrdersArray1->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotal1 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray2)) {
                                                $Ordertotal2 = number_format($OrdersArray2->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotal2 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray3)) {
                                                $Ordertotal3 = number_format($OrdersArray3->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotal3 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray4)) {
                                                $Ordertotal4 = number_format($OrdersArray4->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotal4 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArray5)) {
                                                $Ordertotal5 = number_format($OrdersArray5->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotal5 = number_format('0', 2, '.', '');
                                            }

                                            if (!empty($OrdersArraylast)) {
                                                $Ordertotallast = number_format($OrdersArraylast->C_Total, 2, '.', '');
                                            } else {
                                                $Ordertotallast = number_format('0', 2, '.', '');
                                            }


                                            $AxisValues = array(
                                                $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                $month_line_5, $month_line_last
                                            );
                                            $PointValues = array(
                                                $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4, $Ordertotal5,
                                                $Ordertotallast
                                            );

                                            $CurrentDate = date('Y-m-d');

                                            $TodaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$CurrentDate'");
                                            $TodaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$CurrentDate'");

                                            if (!empty($TodaysTotalOrdersArray)) {
                                                $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersToday = '0';
                                            }


                                            if (!empty($TodaysOrdersArray)) {
                                                $OrdertotalToday = $TodaysOrdersArray->C_Total;
                                            } else {
                                                $OrdertotalToday = number_format('0', 2, '.', '');
                                            }

                                            $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                            $YesterdaysOrdersArray = $this->Cart_model->getCartTotal(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '4',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$YesterdayDate'");
                                            $YesterdaysTotalOrdersArray = $this->Cart_model->get(null, array(
                                                'C_AssignedTo' => $data['Val_Vendor'], 'C_OrderStatus' => '1',
                                                'C_Status' => '3'
                                            ), " DATE(C_BookedDttm) = '$YesterdayDate'");

                                            if (!empty($YesterdaysTotalOrdersArray)) {
                                                $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                            } else {
                                                $TotalOrdersYesterday = '0';
                                            }


                                            if (!empty($YesterdaysOrdersArray)) {
                                                $OrdertotalYesterday = $YesterdaysOrdersArray->C_Total;
                                            } else {
                                                $OrdertotalYesterday = number_format('0', 2, '.', '');
                                            }


                                            $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                            $Records['GraphRecords']['PointValues'] = $PointValues;
                                            $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                            $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                            $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                            $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                            $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';


                                        } else {
                                            if ($Type == '2') {

                                                $CurrentMonth = date('m');
                                                $LastMonth = date("m", strtotime("-1 month"));
                                                $LastMonthYear = date("Y", strtotime("-1 month"));
                                                //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                                $TotalDays = cal_days_in_month(CAL_GREGORIAN, $LastMonth,
                                                    $LastMonthYear);

                                                $StringDate = $LastMonthYear . '-' . $LastMonth . '-01';
                                                $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                                //5th
                                                $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +4 day"));
                                                $OrdersArray1 = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");

                                                //10th
                                                $month_line_2 = date('Y-m-d', strtotime($LastMonthDate . " +9 day"));
                                                $OrdersArray2 = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");

                                                //15th
                                                $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +14 day"));
                                                $OrdersArray3 = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
                                                //20th
                                                $month_line_4 = date('Y-m-d', strtotime($LastMonthDate . " +19 day"));
                                                $OrdersArray4 = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
                                                //25th
                                                $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +24 day"));
                                                $OrdersArray5 = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
                                                //last day of month
                                                $month_line_last = date('Y-m-d',
                                                    strtotime($LastMonthDate . " next month - 1 hour"));
                                                $OrdersArraylast = $this->Cart_model->getProductsCartTotal(null, array(
                                                    'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                    'PC_Status' => '3'
                                                ),
                                                    " DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");


                                                if (!empty($OrdersArray1)) {
                                                    $Ordertotal1 = $OrdersArray1->PC_Total;
                                                } else {
                                                    $Ordertotal1 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray2)) {
                                                    $Ordertotal2 = $OrdersArray2->PC_Total;
                                                } else {
                                                    $Ordertotal2 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray3)) {
                                                    $Ordertotal3 = $OrdersArray3->PC_Total;
                                                } else {
                                                    $Ordertotal3 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray4)) {
                                                    $Ordertotal4 = $OrdersArray4->PC_Total;
                                                } else {
                                                    $Ordertotal4 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArray5)) {
                                                    $Ordertotal5 = $OrdersArray5->PC_Total;
                                                } else {
                                                    $Ordertotal5 = number_format('0', 2, '.', '');
                                                }

                                                if (!empty($OrdersArraylast)) {
                                                    $Ordertotallast = $OrdersArraylast->PC_Total;
                                                } else {
                                                    $Ordertotallast = number_format('0', 2, '.', '');
                                                }


                                                $AxisValues = array(
                                                    $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                    $month_line_5, $month_line_last
                                                );
                                                $PointValues = array(
                                                    $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4,
                                                    $Ordertotal5, $Ordertotallast
                                                );

                                                $CurrentDate = date('Y-m-d');

                                                $TodaysOrdersArray = $this->Cart_model->getProductsCartTotal(null,
                                                    array(
                                                        'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                        'PC_Status' => '3'
                                                    ), " DATE(PC_BookedDttm) = '$CurrentDate'");
                                                $TodaysTotalOrdersArray = $this->Cart_model->getProductsCart(null,
                                                    array(
                                                        'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                                        'PC_Status' => '3'
                                                    ), " DATE(PC_BookedDttm) = '$CurrentDate'");

                                                if (!empty($TodaysTotalOrdersArray)) {
                                                    $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                                } else {
                                                    $TotalOrdersToday = '0';
                                                }


                                                if (!empty($TodaysOrdersArray)) {
                                                    $OrdertotalToday = $TodaysOrdersArray->PC_Total;
                                                } else {
                                                    $OrdertotalToday = number_format('0', 2, '.', '');
                                                }

                                                $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                                $YesterdaysOrdersArray = $this->Cart_model->getProductsCartTotal(null,
                                                    array(
                                                        'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '4',
                                                        'PC_Status' => '3'
                                                    ), " DATE(PC_BookedDttm) = '$YesterdayDate'");
                                                $YesterdaysTotalOrdersArray = $this->Cart_model->getProductsCart(null,
                                                    array(
                                                        'PC_AssignedTo' => $data['Val_Vendor'], 'PC_OrderStatus' => '1',
                                                        'PC_Status' => '3'
                                                    ), " DATE(PC_BookedDttm) = '$YesterdayDate'");

                                                if (!empty($YesterdaysTotalOrdersArray)) {
                                                    $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                                } else {
                                                    $TotalOrdersYesterday = '0';
                                                }


                                                if (!empty($YesterdaysOrdersArray)) {
                                                    $OrdertotalYesterday = $YesterdaysOrdersArray->PC_Total;
                                                } else {
                                                    $OrdertotalYesterday = number_format('0', 2, '.', '');
                                                }


                                                $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                                $Records['GraphRecords']['PointValues'] = $PointValues;
                                                $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                                $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                                $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                                $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                                $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';


                                            } else {
                                                if ($Type == '3') {

                                                    $CurrentMonth = date('m');
                                                    $Last3rdMonth = date("m", strtotime("-3 month"));
                                                    $Last3rdMonthYear = date("Y", strtotime("-3 month"));
                                                    //echo date("Y-m-d H:i:s",strtotime("-1 month"));
                                                    $TotalDays = cal_days_in_month(CAL_GREGORIAN, $Last3rdMonth,
                                                        $Last3rdMonthYear);

                                                    $StringDate = $Last3rdMonthYear . '-' . $Last3rdMonth . '-01';
                                                    $LastMonthDate = date('Y-m-d', strtotime($StringDate));
                                                    //5th
                                                    $month_line_1 = date('Y-m-d', strtotime($LastMonthDate . " +15 day"));
                                                    $OrdersArray1 = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");

                                                    //10th
                                                    $month_line_2 = date('Y-m-d',
                                                        strtotime($LastMonthDate . " next month - 1 hour"));
                                                    $OrdersArray2 = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");

                                                    //15th
                                                    $month_line_3 = date('Y-m-d', strtotime($LastMonthDate . " +45 day"));
                                                    $OrdersArray3 = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
                                                    //20th
                                                    $month_line_4 = date('Y-m-d',
                                                        strtotime($LastMonthDate . "  +2 month - 1 hour"));
                                                    $OrdersArray4 = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
                                                    //25th
                                                    $month_line_5 = date('Y-m-d', strtotime($LastMonthDate . " +75 day"));
                                                    $OrdersArray5 = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
                                                    //last day of month
                                                    $month_line_last = date('Y-m-d',
                                                        strtotime($LastMonthDate . " +3 month - 1 hour"));
                                                    $OrdersArraylast = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ),
                                                        " DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");


                                                    if (!empty($OrdersArray1)) {
                                                        $Ordertotal1 = $OrdersArray1->RC_Total;
                                                    } else {
                                                        $Ordertotal1 = number_format('0', 2, '.', '');
                                                    }

                                                    if (!empty($OrdersArray2)) {
                                                        $Ordertotal2 = $OrdersArray2->RC_Total;
                                                    } else {
                                                        $Ordertotal2 = number_format('0', 2, '.', '');
                                                    }

                                                    if (!empty($OrdersArray3)) {
                                                        $Ordertotal3 = $OrdersArray3->RC_Total;
                                                    } else {
                                                        $Ordertotal3 = number_format('0', 2, '.', '');
                                                    }

                                                    if (!empty($OrdersArray4)) {
                                                        $Ordertotal4 = $OrdersArray4->RC_Total;
                                                    } else {
                                                        $Ordertotal4 = number_format('0', 2, '.', '');
                                                    }

                                                    if (!empty($OrdersArray5)) {
                                                        $Ordertotal5 = $OrdersArray5->RC_Total;
                                                    } else {
                                                        $Ordertotal5 = number_format('0', 2, '.', '');
                                                    }

                                                    if (!empty($OrdersArraylast)) {
                                                        $Ordertotallast = $OrdersArraylast->RC_Total;
                                                    } else {
                                                        $Ordertotallast = number_format('0', 2, '.', '');
                                                    }


                                                    $AxisValues = array(
                                                        $month_line_1, $month_line_2, $month_line_3, $month_line_4,
                                                        $month_line_5, $month_line_last
                                                    );
                                                    $PointValues = array(
                                                        $Ordertotal1, $Ordertotal2, $Ordertotal3, $Ordertotal4,
                                                        $Ordertotal5, $Ordertotallast
                                                    );


                                                    $CurrentDate = date('Y-m-d');

                                                    $TodaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ), " DATE(RC_BookedDttm) = '$CurrentDate'");
                                                    $TodaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '1', 'RC_Status' => '3'
                                                        ), " DATE(RC_BookedDttm) = '$CurrentDate'");

                                                    if (!empty($TodaysTotalOrdersArray)) {
                                                        $TotalOrdersToday = (string)count($TodaysTotalOrdersArray);
                                                    } else {
                                                        $TotalOrdersToday = '0';
                                                    }


                                                    if (!empty($TodaysOrdersArray)) {
                                                        $OrdertotalToday = $TodaysOrdersArray->RC_Total;
                                                    } else {
                                                        $OrdertotalToday = number_format('0', 2, '.', '');
                                                    }

                                                    $YesterdayDate = date('Y-m-d', strtotime('-1 day'));
                                                    $YesterdaysOrdersArray = $this->Cart_model->getRestaurantsCartTotal(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '4', 'RC_Status' => '3'
                                                        ), " DATE(RC_BookedDttm) = '$YesterdayDate'");
                                                    $YesterdaysTotalOrdersArray = $this->Cart_model->getRestaurantsCart(null,
                                                        array(
                                                            'RC_AssignedTo' => $data['Val_Vendor'],
                                                            'RC_OrderStatus' => '1', 'RC_Status' => '3'
                                                        ), " DATE(RC_BookedDttm) = '$YesterdayDate'");

                                                    if (!empty($YesterdaysTotalOrdersArray)) {
                                                        $TotalOrdersYesterday = (string)count($YesterdaysTotalOrdersArray);
                                                    } else {
                                                        $TotalOrdersYesterday = '0';
                                                    }


                                                    if (!empty($YesterdaysOrdersArray)) {
                                                        $OrdertotalYesterday = $YesterdaysOrdersArray->RC_Total;
                                                    } else {
                                                        $OrdertotalYesterday = number_format('0', 2, '.', '');
                                                    }


                                                    $Records['GraphRecords']['AxisValues'] = $AxisValues;
                                                    $Records['GraphRecords']['PointValues'] = $PointValues;
                                                    $Records['GraphRecords']['TodaysRevenue'] = 'Rs. ' . $OrdertotalToday;
                                                    $Records['GraphRecords']['TodaysOrders'] = $TotalOrdersToday;
                                                    $Records['GraphRecords']['YesterdaysRevenue'] = 'Rs. ' . $OrdertotalYesterday;
                                                    $Records['GraphRecords']['YesterdaysOrders'] = $TotalOrdersYesterday;
                                                    $Records['GraphRecords']['TotalSales'] = 'Rs. 1234.00';

                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            /*										if($Type == '1'){

											$CurrentDay 	= date('Y-m-d');

											 //5th
											$month_line_1 = date('Y-m-d', strtotime('-1 days'));
											$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_1'");

											 //10th
											$month_line_2 = date('Y-m-d', strtotime('-2 days'));
											$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_2'");

											 //15th
											$month_line_3 = date('Y-m-d', strtotime('-3 days'));
											$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_3'");
											 //20th
											$month_line_4 = date('Y-m-d', strtotime('-4 days'));
											$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_4'");
											 //25th
											$month_line_5 = date('Y-m-d', strtotime('-5 days'));
											$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_5'");
											//last day of month
											$month_line_last = date('Y-m-d', strtotime('-6 days'));
											$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_last'");


											if(!empty($OrdersArray1))
												$Ordertotal1 	= number_format($OrdersArray1->C_Total,2,'.','');
											else
												$Ordertotal1 	= number_format('0',2,'.','');

											if(!empty($OrdersArray2))
												$Ordertotal2 	= number_format($OrdersArray2->C_Total,2,'.','');
											else
												$Ordertotal2 	= number_format('0',2,'.','');

											if(!empty($OrdersArray3))
												$Ordertotal3 	= number_format($OrdersArray3->C_Total,2,'.','');
											else
												$Ordertotal3 	= number_format('0',2,'.','');

											if(!empty($OrdersArray4))
												$Ordertotal4 	= number_format($OrdersArray4->C_Total,2,'.','');
											else
												$Ordertotal4 	= number_format('0',2,'.','');

											if(!empty($OrdersArray5))
												$Ordertotal5 	= number_format($OrdersArray5->C_Total,2,'.','');
											else
												$Ordertotal5 	= number_format('0',2,'.','');

											if(!empty($OrdersArraylast))
												$Ordertotallast 	= number_format($OrdersArraylast->C_Total,2,'.','');
											else
												$Ordertotallast 	= number_format('0',2,'.','');




											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');

											$TodaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");

											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';


											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->C_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');

											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");

											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';


											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->C_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');





											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';


										} else if($Type == '2'){

											$CurrentMonth 	= date('m');
											$LastMonth 	= date("m",strtotime("-1 month"));
											$LastMonthYear = date("Y",strtotime("-1 month"));
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );

											$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
											$OrdersArray1 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");

											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
											$OrdersArray2 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");

											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
											$OrdersArray3 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
											$OrdersArray4 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
											$OrdersArray5 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");


											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->PC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');

											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->PC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');

											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->PC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');

											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->PC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');

											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->PC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');

											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->PC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');




											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');

											$TodaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");

											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';


											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->PC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');

											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Vendor'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");

											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';


											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->PC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');





											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';


										} else if($Type == '3'){

											$CurrentMonth 	= date('m');
											$Last3rdMonth 	= date("m",strtotime("-3 month"));
											$Last3rdMonthYear = date("Y",strtotime("-3 month"));
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$Last3rdMonth,$Last3rdMonthYear );

											$StringDate = $Last3rdMonthYear.'-'.$Last3rdMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +15 day"));
											$OrdersArray1 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");

											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArray2 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");

											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +45 day"));
											$OrdersArray3 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate."  +2 month - 1 hour"));
											$OrdersArray4 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +75 day"));
											$OrdersArray5 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." +3 month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");


											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->RC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');

											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->RC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');

											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->RC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');

											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->RC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');

											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->RC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');

											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->RC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');




											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);


											$CurrentDate 	= date('Y-m-d');

											$TodaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");

											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';


											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->RC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');

											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Vendor'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");

											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';


											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->RC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');





											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';

										}
*/
                            $MiscRecords = array(
                                'NotificationCount' => "1",
                            );

                            $Records['MiscData'] = $MiscRecords;
                            $result = array(
                                'status' => 'success', 'flag' => '1', 'message' => 'Vendor Revenue Data Fetched',
                                'data' => $Records
                            );


                        } else {
                            $result = array(
                                'status' => 'warning', 'flag' => '3', 'message' => 'Something Went Wrong...',
                                'data' => (object)$Records
                            );
                        }
                    } else {
                        $result = array(
                            'status' => 'warning', 'flag' => '3', 'message' => 'Something Went Wrong...',
                            'data' => (object)$Records
                        );
                    }


                } else {
                    $result = array(
                        'status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing...',
                        'data' => (object)$Records
                    );
                }

            } else {
                if (!empty($data['Action']) && $data['Action'] == 'AddToCart') {

                    if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Vendor']) && !empty($data['Val_Option']) && !empty($data['Val_Address']) && !empty($data['Val_Date']) && !empty($data['Val_Timeslab'])) {


                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                        //print_r($VendorData);
                        $VendorFullName = getStringValue($VendorData->C_FirstName) . " " . getStringValue($VendorData->C_LastName);
                        $VendorAddress = array(
                            getStringValue($VendorData->C_Address), getStringValue($VendorData->C_Location)
                        );

                        $Options = $data['Val_Option'];
                        $OptionsArray = json_decode($Options);
                        $CTotal = 0;
                        foreach ($OptionsArray as $Option) {
                            $OptionsData = $this->Services_model->getOptions($Option);

                            $ServiceID = $OptionsData->O_ServiceID;
                            $ServiceData = $this->Services_model->get($ServiceID);
                            $ServiceName = $ServiceData->S_Name;

                            $PackageID[] = $OptionsData->O_PackageID;
                            $PackageData = $this->Services_model->getPackages($OptionsData->O_PackageID);
                            $PackageNames[] = $PackageData->P_Title;

                            $OptionID[] = $OptionsData->SPOptionID;
                            $OptionNames[] = $OptionsData->O_Title;
                            $OptionPrices[] = $OptionsData->O_Price;
                            $CTotal = $CTotal + $OptionsData->O_Price;
                        }

                        $TimeslabData = $this->Services_model->getTimeslabs($data['Val_Timeslab']);
                        $TimeslabTitle = $TimeslabData->T_Title;

                        $PostData['Val_Type'] = $data['Val_Type'];
                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                        $PostData['Val_Cvendorname'] = $VendorFullName;
                        $PostData['Val_Cvendoraddress'] = json_encode($VendorAddress);
                        $PostData['Val_Service'] = $ServiceID;
                        $PostData['Val_Cservicenames'] = $ServiceName;
                        $PostData['Val_Package'] = json_encode($PackageID);
                        $PostData['Val_Cpackagenames'] = json_encode($PackageNames);


                        $PostData['Val_Option'] = json_encode($OptionID);
                        $PostData['Val_Coptionnames'] = json_encode($OptionNames);
                        $PostData['Val_Coptionprices'] = json_encode($OptionPrices);
                        $PostData['Val_Cdate'] = $data['Val_Date'];
                        $PostData['Val_Ctimeslab'] = $data['Val_Timeslab'];
                        $PostData['Val_Ctimeslabtitle'] = $TimeslabTitle;
                        $PostData['Val_Ctotal'] = number_format($CTotal, 2, '.', '');

                        $success = $this->Cart_model->add($PostData);

                        if ($success) {
                            $CartID = $success;
                            $CartData = $this->Cart_model->get($CartID);


                            $OptionsCount = "0";
                            $OptionsData = array();
//							$OptionNames[] = $OptionsData->O_Title;
                            $Index = 0;
                            foreach ($OptionNames as $Option) {

                                $OptionsData[] = array(
                                    'Title' => $PackageNames[$Index],
                                    'Description' => $Option,
                                    'Currency' => "Rs. ",
                                    'Price' => $OptionPrices[$Index],
                                );
                                $Index++;
                            }
                            $OptionsCount = (string)count($OptionsData);
                            $Record = array(
                                'CartID' => getStringValue($CartData->CartID),
                                'OptionsCount' => $OptionsCount,
                                'OptionsData' => $OptionsData,
                                'Currency' => "Rs. ",
                                'CartTotal' => $CartData->C_Total,
                                //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                //		'Status'=> getStatus($VendorData->C_Status),
                            );

                            $result = array(
                                'status' => 'success', 'flag' => '1', 'message' => 'Cart Created Successfully',
                                'data' => $Record
                            );


                        } else {
                            if ($success == false) {
                                $data['Val_ProfileImage'] = '';
                                $result = array(
                                    'status' => 'error', 'flag' => '2',
                                    'message' => 'We couldn\'t register you. Please try again later.(404)',
                                    'data' => $Record
                                );
                            } else {
                                $result = array(
                                    'status' => 'warning', 'flag' => '3',
                                    'message' => 'Something Important Happened !! ', 'data' => $success
                                );
                            }
                        }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                    } else {
                        if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Vendor']) && !empty($data['Val_Product'])) {

                            $ExistingCartArray = $this->Cart_model->getProductsCart(null, array(
                                'PC_Status <>' => '3', 'PC_Status <>' => '4', 'PC_VendorID' => $data['Val_Vendor']
                            ));

                            if (!empty($ExistingCartArray) && count($ExistingCartArray) == '1') {
                                $ExistingCartData = (object)$ExistingCartArray[0];

                                //echo "Exist";
                                $ProductVal = $data['Val_Product'];
                                $Products = $ExistingCartData->PC_ProductID;

                                $ProductsArray = json_decode($Products);
                                $PricesArray = json_decode($ExistingCartData->PC_Prices);
                                $CTotal = 0;
                                $ProductsTotal = 0;
                                $DeliveryCharges = $ExistingCartData->PC_DeliveryCharge;


                                if (!empty($ProductsArray)) {


                                    foreach ($ProductsArray as $Key => $Product) {
                                        $ProductsData = $this->Products_model->get($Product);

                                        $ProductID[] = $ProductsData->ProductID;
                                        $ProductNames[] = $ProductsData->P_Name;

                                        $ProductPrices[] = $PricesArray[$Key];

                                        $ProductsTotal = $ProductsTotal + $PricesArray[$Key];

                                    }
                                } else {
                                    $ProductsTotal = $ExistingCartData->PC_ItemTotal;
                                }


                                if (!in_array($ProductVal, $ProductID, true)) {
                                    $ProductsData = $this->Products_model->get($ProductVal);

                                    $ProductID[] = $ProductsData->ProductID;
                                    $ProductNames[] = $ProductsData->P_Name;
                                    $ProductPrices[] = $ProductsData->P_Price;

                                    $ProductsTotal = $ProductsTotal + $ProductsData->P_Price;


                                    $CartTotal = $ProductsTotal + $DeliveryCharges;

                                    $PostData['Val_Product'] = json_encode($ProductID);
                                    $PostData['Val_PCproductnames'] = json_encode($ProductNames);
                                    $PostData['Val_PCprices'] = json_encode($ProductPrices);
                                    $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                    $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                    $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');
                                    $PostData['Val_PCstatus'] = '2';

                                    //						$PostData['Val_PCdetail']			=  json_encode(array());
                                    //						$PostData['Val_Address']			=  "";
                                    //					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
                                    //					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;

                                    $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,
                                        $ExistingCartData->PCartID);

                                    if ($CartAddProductStatus) {

                                        $ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
                                        $DetailID = $ProductsDetailsArray;
                                        $PostDetailData['Val_Cart'] = $ExistingCartData->PCartID;
                                        $PostDetailData['Val_Product'] = $ProductVal;
                                        $PostDetailData['Val_PDquantity'] = '1';
                                        $PostDetailData['Val_Attribute'] = getStringValue($data['Val_Attribute']);
                                        $PostDetailData['Val_Attribvalue'] = getStringValue($data['Val_Attribvalue']);
                                        $CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);
                                        $DetailID[] = (string)$CartDetailsSuccess;


                                        $UpdatePostData['Val_PCdetail'] = json_encode($DetailID);

                                        $CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,
                                            $ExistingCartData->PCartID);
                                    }
                                    $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);

                                    $ProductsCount = "0";
                                    $ProductsData = array();
                                    $ProductsDetailsArray = json_decode($CartData->PC_DetailID);
                                    $Index = 0;

                                    foreach ($ProductsDetailsArray as $ProductDetail) {

                                        $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                        $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                        if (!empty($ProductDetailData->PD_AttributeID)) {
                                            $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                            $AttributeTitle = $ProductAttributeData->A_Title;
                                        } else {
                                            $AttributeTitle = "";
                                        }
                                        if (!empty($ProductDetailData->PD_AttribValueID)) {
                                            //print_r($ProductDetailData->PD_AttributeID);
                                            $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);

                                            $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                        } else {
                                            $AttributeValueTitle = "";
                                        }


                                        $FeaturedImage = '';
                                        $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                        $ProductsData[] = array(
                                            'DetailID' => $ProductDetailData->CPDetailID,
                                            'ProductID' => $ProductDetailData->PD_ProductID,
                                            'Title' => $ProductData->P_Name,
                                            'Attribute' => $AttributeTitle,
                                            'AttributeValue' => $AttributeValueTitle,
                                            'Currency' => "Rs. ",
                                            'Price' => $ProductData->P_Price,
                                            'Quantity' => $ProductDetailData->PD_Quantity,
                                            'FeaturedImage' => $FeaturedImage,
                                        );
                                        $Index++;
                                    }
                                    $ProductsCount = (string)count($ProductsData);
                                    $Record = array(
                                        'CartID' => getStringValue($CartData->PCartID),
                                        'ProductsCount' => $ProductsCount,
                                        'ProductsData' => $ProductsData,
                                        'Currency' => "Rs. ",
                                        'ItemTotal' => $CartData->PC_ItemTotal,
                                        'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                                        'CartTotal' => $CartData->PC_CartTotal,
                                    );

                                    $result = array(
                                        'status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully',
                                        'data' => $Record
                                    );
                                } else {

                                    $CartID = $ExistingCartData->PCartID;
                                    $CartData = $this->Cart_model->getProductsCart($CartID);

                                    $ProductsCount = "0";
                                    $ProductsData = array();
                                    $ProductsDetailsArray = json_decode($CartData->PC_DetailID);
                                    $Index = 0;

                                    foreach ($ProductsDetailsArray as $ProductDetail) {

                                        $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                        $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                        if (!empty($ProductDetailData->PD_AttributeID)) {
                                            $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                            $AttributeTitle = $ProductAttributeData->A_Title;
                                        } else {
                                            $AttributeTitle = "";
                                        }
                                        if (!empty($ProductDetailData->PD_AttribValueID)) {
                                            $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                            $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                        } else {
                                            $AttributeValueTitle = "";
                                        }


                                        $FeaturedImage = '';
                                        $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                        $ProductsData[] = array(
                                            'DetailID' => $ProductDetailData->CPDetailID,
                                            'ProductID' => $ProductDetailData->PD_ProductID,
                                            'Title' => $ProductData->P_Name,
                                            'Attribute' => $AttributeTitle,
                                            'AttributeValue' => $AttributeValueTitle,
                                            'Currency' => "Rs. ",
                                            'Price' => $ProductData->P_Price,
                                            'Quantity' => $ProductDetailData->PD_Quantity,
                                            'FeaturedImage' => $FeaturedImage,
                                        );
                                        $Index++;
                                    }
                                    $ProductsCount = (string)count($ProductsData);
                                    $Record = array(
                                        'CartID' => getStringValue($CartData->PCartID),
                                        'ProductsCount' => $ProductsCount,
                                        'ProductsData' => $ProductsData,
                                        'Currency' => "Rs. ",
                                        'ItemTotal' => $CartData->PC_ItemTotal,
                                        'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                                        'CartTotal' => $CartData->PC_CartTotal,
                                        //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                        //		'Status'=> getStatus($VendorData->C_Status),
                                    );

                                    $result = array(
                                        'status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully',
                                        'data' => $Record
                                    );
                                }
                            } else {


                                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                //print_r($VendorData);
                                $VendorFullName = getStringValue($VendorData->C_FirstName) . " " . getStringValue($VendorData->C_LastName);
                                $VendorAddress = array(
                                    getStringValue($VendorData->C_Address), getStringValue($VendorData->C_Location)
                                );

                                $Product = $data['Val_Product'];
                                //$Products 			= $data['Val_Product'];
                                //$ProductsArray 		= json_decode($Products);
                                $CTotal = 0;
                                $ProductsTotal = 0;
                                $DeliveryCharges = 15.00;
                                //					if(!empty($ProductsArray)){
                                //						foreach($ProductsArray as $Product){
                                $ProductsData = $this->Products_model->get($Product);

                                $ProductID[] = $ProductsData->ProductID;
                                $ProductNames[] = $ProductsData->P_Name;
                                $ProductPrices[] = $ProductsData->P_Price;

                                $ProductsTotal = $ProductsTotal + $ProductsData->P_Price;

                                //						}

                                $CartTotal = $ProductsTotal + $DeliveryCharges;

                                $PostData['Val_Vendor'] = $data['Val_Vendor'];
                                $PostData['Val_PCvendorname'] = $VendorFullName;
                                $PostData['Val_PCvendoraddress'] = json_encode($VendorAddress);
                                $PostData['Val_Product'] = json_encode($ProductID);
                                $PostData['Val_PCproductnames'] = json_encode($ProductNames);

                                $PostData['Val_PCdate'] = date('Y-m-d');
                                //						$PostData['Val_PCdetail']			=  json_encode(array());
                                $PostData['Val_PCprices'] = json_encode($ProductPrices);
                                //						$PostData['Val_Address']			=  "";
                                $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                $PostData['Val_PCdeliverycharges'] = number_format($DeliveryCharges, 2, '.', '');
                                $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                //					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
                                //					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
                                $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');

                                $success = $this->Cart_model->addCartProducts($PostData);

                                if ($success) {
                                    $CartID = $success;
                                    //    private $product_cart_details_data = array('CPDetailID'=>'Val_Cartdetail', 'PD_CartID'=>'Val_Cart','PD_ProductID'=>'Val_Product','PD_Quantity'=>'Val_PDquantity', 'PD_AttributeID'=>'Val_Attribute', 'PD_AttribValueID'=>'Val_Attribvalue', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

                                    //	foreach($ProductsArray as $Product){
                                    $PostDetailData['Val_Cart'] = $CartID;
                                    $PostDetailData['Val_Product'] = $Product;
                                    $PostDetailData['Val_PDquantity'] = '1';
                                    $PostDetailData['Val_Attribute'] = $data['Val_Attribute'];
                                    $PostDetailData['Val_Attribvalue'] = $data['Val_Attribvalue'];
                                    $CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);
                                    $DetailID[] = (string)$CartDetailsSuccess;
                                    //}

                                    $UpdatePostData['Val_PCdetail'] = json_encode($DetailID);
                                    $CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData, $CartID);

                                    //							addCartProductsDetails
                                    $CartData = $this->Cart_model->getProductsCart($CartID);
                                    //print_r($CartData);

                                    $ProductsCount = "0";
                                    $ProductsData = array();
                                    $ProductsDetailsArray = json_decode($CartData->PC_DetailID);
                                    $Index = 0;

                                    foreach ($ProductsDetailsArray as $ProductDetail) {

                                        $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                        $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);

                                        if (!empty($ProductDetailData->PD_AttributeID)) {
                                            $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                            $AttributeTitle = $ProductAttributeData->A_Title;
                                        } else {
                                            $AttributeTitle = "";
                                        }
                                        if (!empty($ProductDetailData->PD_AttributeID)) {
                                            $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                            $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                        } else {
                                            $AttributeValueTitle = "";
                                        }

                                        $FeaturedImage = '';
                                        $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                        $ProductsData[] = array(
                                            'DetailID' => $ProductDetailData->CPDetailID,
                                            'ProductID' => $ProductDetailData->PD_ProductID,
                                            'Title' => $ProductData->P_Name,
                                            'Attribute' => $AttributeTitle,
                                            'AttributeValue' => $AttributeValueTitle,
                                            'Currency' => "Rs. ",
                                            'Price' => $ProductData->P_Price,
                                            'Quantity' => $ProductDetailData->PD_Quantity,
                                            'FeaturedImage' => $FeaturedImage,
                                        );
                                        $Index++;
                                    }
                                    $ProductsCount = (string)count($ProductsData);
                                    $Record = array(
                                        'CartID' => getStringValue($CartData->PCartID),
                                        'ProductsCount' => $ProductsCount,
                                        'ProductsData' => $ProductsData,
                                        'Currency' => "Rs. ",
                                        'ItemTotal' => $CartData->PC_ItemTotal,
                                        'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                                        'CartTotal' => $CartData->PC_CartTotal,
                                        //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                        //		'Status'=> getStatus($VendorData->C_Status),
                                    );

                                    $result = array(
                                        'status' => 'success', 'flag' => '1', 'message' => 'Cart Created Successfully',
                                        'data' => $Record
                                    );


                                } else {
                                    if ($success == false) {
                                        $data['Val_ProfileImage'] = '';
                                        $result = array(
                                            'status' => 'error', 'flag' => '2',
                                            'message' => 'We couldn\'t register you. Please try again later.(404)',
                                            'data' => $Record
                                        );
                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3',
                                            'message' => 'Something Important Happened !! ', 'data' => $success
                                        );
                                    }
                                }
                                //					} else {
//								$result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);
//						}


                                //echo "No Exist";
                            }

                        } else {
                            if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Vendor']) && !empty($data['Val_Restaurant']) && !empty($data['Val_Food'])) {

                                $ExistingCartArray = $this->Cart_model->getRestaurantsCart(null, array(
                                    'RC_Status <>' => '3', 'RC_Status <>' => '4', 'RC_VendorID' => $data['Val_Vendor']
                                ));

                                if (!empty($ExistingCartArray) && count($ExistingCartArray) == '1') {
                                    //echo "Exist";
                                    $ExistingCartData = (object)$ExistingCartArray[0];
                                    $Restaurant = $data['Val_Restaurant'];
                                    $RestaurantData = $this->Restaurants_model->get($Restaurant);
                                    if (!empty($RestaurantData)) {
                                        if ($Restaurant == $ExistingCartData->RC_RestaurantID) {
                                            //echo "Exist2";
                                            $CTotal = 0;
                                            $FoodsTotal = 0;
                                            $DeliveryCharges = $ExistingCartData->RC_DeliveryCharge;
                                            $FoodPricesArray = json_decode($ExistingCartData->RC_Prices);

                                            $FoodID = $data['Val_Food'];

                                            $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null,
                                                array(
                                                    'RD_CartID' => $ExistingCartData->RCartID, 'RD_FoodID' => $FoodID
                                                ));
                                            if (!empty($FoodPricesArray)) {
                                                foreach ($FoodPricesArray as $Key => $Price) {
                                                    $FoodsTotal = $FoodsTotal + $Price;
                                                }
                                            } else {
                                                $FoodsTotal = $ExistingCartData->PC_ItemTotal;
                                            }

                                            if (empty($ExistingCartDetailData)) //if(!in_array($ProductVal,$ProductID,true))
                                            {

                                                $FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);

                                                if (!empty($FoodData)) {
                                                    $FoodPricesArray[] = $FoodData->F_Price;
                                                    $FoodAmount = number_format($FoodData->F_Price, 2, '.', '');
                                                    $FoodsTotal = $FoodsTotal + $FoodAmount;
                                                } else {
                                                    $FoodPricesArray[] = "";
                                                    $FoodAmount = number_format(0, 2, '.', '');
                                                    $FoodsTotal = $FoodsTotal + 0;
                                                }

                                                $FoodPricesArray = array_filter($FoodPricesArray);

                                                $CartTotal = $FoodsTotal + $DeliveryCharges;

                                                $ItemCount = $ExistingCartData->RC_ItemCount + 1;

                                                $PostData['Val_RCprices'] = json_encode($FoodPricesArray);
                                                $PostData['Val_RCitemcount'] = getStringValue($ItemCount);
                                                $PostData['Val_RCitemtotal'] = number_format($FoodsTotal, 2, '.', '');
                                                $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                                $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');
                                                $PostData['Val_RCstatus'] = '2';

                                                $CartAddProductStatus = $this->Cart_model->updateCartRestaurants($PostData,
                                                    $ExistingCartData->RCartID);
                                                $RestaurantsDetailsArray = json_decode($ExistingCartData->RC_DetailID);
                                                $DetailID = $RestaurantsDetailsArray;
                                                if ($CartAddProductStatus) {


                                                    $PostDetailData['Val_Cart'] = $ExistingCartData->RCartID;
                                                    $PostDetailData['Val_Food'] = $FoodID;
                                                    $PostDetailData['Val_RDquantity'] = '1';
                                                    $PostDetailData['Val_RDprice'] = getStringValue($FoodAmount);
                                                    $CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);
                                                    $DetailID[] = (string)$CartDetailsSuccess;


                                                    $UpdatePostData['Val_RCdetail'] = json_encode($DetailID);
                                                    $CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData,
                                                        $ExistingCartData->RCartID);
                                                }

                                                $CartData = $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);
                                                $ItemCount = $CartData->RC_ItemCount;

                                                $Record = array(
                                                    'CartID' => getStringValue($CartData->RCartID),
                                                    'Currency' => "Rs. ",
                                                    'ItemCount' => getStringValue($ItemCount),
                                                    'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                    'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                                );

                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Product Added Successfully', 'data' => $Record
                                                );

                                                //echo 'Empty';
                                            } else {

                                                //Nothing Happens, Just Show Existing Data as it is
                                                //print_r($ExistingCartDetailData);

                                                $CartID = $ExistingCartData->RCartID;
                                                $CartData = $this->Cart_model->getRestaurantsCart($CartID);
                                                $ItemCount = $CartData->RC_ItemCount;


                                                $Record = array(
                                                    'CartID' => getStringValue($CartData->RCartID),
                                                    'Currency' => "Rs. ",
                                                    'ItemCount' => getStringValue($ItemCount),
                                                    'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                    'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                                );

                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Product Added Successfully', 'data' => $Record
                                                );

                                            }
                                        } else {
                                            //Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
                                            $CartID = $ExistingCartData->RCartID;
                                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);

                                            $ItemCount = $CartData->RC_ItemCount;

                                            $Record = array(
                                                'CartID' => getStringValue($CartData->RCartID),
                                                'Currency' => "Rs. ",
                                                'ItemCount' => getStringValue($ItemCount),
                                                'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                            );

                                            $result = array(
                                                'status' => 'warning', 'flag' => '3',
                                                'message' => 'You cannot add items from this restaurant as your cart contains items from another restaurant.',
                                                'data' => $Record
                                            );

                                        }
                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3',
                                            'message' => 'Something Important Happened !! ', 'data' => (object)array()
                                        );
                                    }
                                    //echo "exist";
                                } else {

                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                    $VendorFullName = getStringValue($VendorData->C_FirstName) . " " . getStringValue($VendorData->C_LastName);
                                    $VendorAddress = array(
                                        getStringValue($VendorData->C_Address), getStringValue($VendorData->C_Location)
                                    );


                                    $Restaurant = $data['Val_Restaurant'];
                                    //$Products 			= $data['Val_Product'];
                                    //$ProductsArray 		= json_decode($Products);
                                    $CTotal = 0;
                                    $ProductsTotal = 0;
                                    $DeliveryCharges = 15.00;
                                    $RestaurantData = $this->Restaurants_model->get($Restaurant);
                                    if (!empty($RestaurantData)) {
                                        $RestaurantID = $RestaurantData->RestaurantID;
                                        $RestaurantName = $RestaurantData->R_Name;

                                        $FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);

                                        if (!empty($FoodData)) {
                                            $ProductPrices[] = $FoodData->F_Price;
                                            $FoodAmount = number_format($FoodData->F_Price, 2, '.', '');
                                            $ProductsTotal = $ProductsTotal + $FoodAmount;
                                        } else {
                                            $ProductPrices = array();
                                            $FoodAmount = number_format(0, 2, '.', '');
                                            $ProductsTotal = $ProductsTotal + 0;
                                        }


                                        /*					if(!empty($ProductsArray)){}
				//						foreach($ProductsArray as $Product){
										$RestaurantData 	=	$this->Restaurants_model->get($Restaurant);

										$RestaurantID 	= $RestaurantData->RestaurantID;
										$RestaurantName	= $RestaurantData->R_Name;
										$ProductPrices[]= $RestaurantData->P_Price;

										$ProductsTotal 	= $ProductsTotal + $ProductsData->P_Price;

				//						}	*/

                                        $CartTotal = $ProductsTotal + $DeliveryCharges;

                                        $PostData['Val_Vendor'] = $data['Val_Vendor'];
                                        $PostData['Val_RCvendorname'] = $VendorFullName;
                                        $PostData['Val_RCvendoraddress'] = json_encode($VendorAddress);
                                        $PostData['Val_Restaurant'] = $RestaurantID;
                                        $PostData['Val_RCrestaurantname'] = $RestaurantName;

                                        $PostData['Val_RCdate'] = date('Y-m-d');
                                        //						$PostData['Val_RCdetail']			=  json_encode(array());
                                        $PostData['Val_RCprices'] = json_encode($ProductPrices);
                                        //						$PostData['Val_Address']			=  "";

                                        $PostData['Val_RCitemcount'] = 1;
                                        $PostData['Val_RCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                        $PostData['Val_RCdeliverycharges'] = number_format($DeliveryCharges, 2, '.',
                                            '');
                                        $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                        //					$PostData['Val_RCpaymentoption']	=  $TimeslabTitle;
                                        //					$PostData['Val_RCservicecharge']	=  $TimeslabTitle;
                                        $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');

                                        $success = $this->Cart_model->addCartRestaurants($PostData);

                                        if ($success) {
                                            $CartID = $success;
                                            //   private $restaurant_cart_details_data = array('CRDetailID'=>'Val_Cartdetail', 'RD_CartID'=>'','RD_FoodID'=>'','RD_Quantity'=>'', 'RD_Price'=>'', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

                                            $FoodAmount;
                                            //	foreach($ProductsArray as $Product){
                                            $PostDetailData['Val_Cart'] = $CartID;
                                            $PostDetailData['Val_Food'] = $data['Val_Food'];
                                            $PostDetailData['Val_RDquantity'] = '1';
                                            $PostDetailData['Val_RDprice'] = $FoodAmount;
                                            $CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);
                                            $DetailID[] = (string)$CartDetailsSuccess;
                                            //}

                                            $UpdatePostData['Val_RCdetail'] = json_encode($DetailID);
                                            $CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData,
                                                $CartID);

                                            //							addCartProductsDetails
                                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);
                                            //print_r($CartData);

                                            $RestaurantsCount = "0";
                                            $RestaurantsData = array();
                                            $RestaurantsDetailsArray = json_decode($CartData->RC_DetailID);
                                            $Index = 0;

                                            $Record = array(
                                                'CartID' => getStringValue($CartData->RCartID),
                                                'Currency' => "Rs. ",
                                                'ItemCount' => getStringValue($CartData->RC_ItemCount),
                                                'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                                //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                //		'Status'=> getStatus($VendorData->C_Status),
                                            );

                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Cart Created Successfully', 'data' => $Record
                                            );


                                        } else {
                                            if ($success == false) {
                                                $data['Val_ProfileImage'] = '';
                                                $result = array(
                                                    'status' => 'error', 'flag' => '2',
                                                    'message' => 'We couldn\'t register you. Please try again later.(404)',
                                                    'data' => (object)array()
                                                );
                                            } else {
                                                $result = array(
                                                    'status' => 'warning', 'flag' => '3',
                                                    'message' => 'Something Important Happened !! ',
                                                    'data' => (object)array()
                                                );
                                            }
                                        }


                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3',
                                            'message' => 'Something Important Happened !! ', 'data' => (object)array()
                                        );
                                    }


                                    //		{			} else {
//								$result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);
//						}


                                    //echo "No Exist";
                                }

                            } else {
                                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
                            }
                        }
                    }

                } else {
                    if (!empty($data['Action']) && $data['Action'] == 'UpdateCart') {


                        if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail']) && !empty($data['Val_Quantity']) && !empty($data['Val_Vendor']) && !empty($data['Val_Product'])) {
                            $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                            $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails($data['Val_Detail']);


//					exit;

                            if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {
                                //$ExistingCartData = (object)$ExistingCartArray[0];

                                $ProductVal = $data['Val_Product'];
                                $Products = $ExistingCartData->PC_ProductID;
                                $ProductsArray = json_decode($Products);
                                $CTotal = 0;
                                $ProductsTotal = 0;
                                $DeliveryCharges = $ExistingCartData->PC_DeliveryCharge;
                                if (!empty($ProductsArray)) {
                                    foreach ($ProductsArray as $Product) {
                                        $ProductsData = $this->Products_model->get($Product);

                                        if ($ProductVal == $Product) {
                                            $ProductPrice = ($ProductsData->P_Price * $data['Val_Quantity']);
                                        } else {
                                            $ProductPrice = $ProductsData->P_Price;
                                        }

                                        $ProductPrices[] = number_format($ProductPrice, 2, '.', '');//$ProductPrice;
                                        $ProductsTotal = $ProductsTotal + number_format($ProductPrice, 2, '.', '');

                                    }
                                } else {
                                    $ProductsTotal = $ExistingCartData->PC_ItemTotal;
                                }

                                $CartTotal = $ProductsTotal + $DeliveryCharges;
                                $PostData['Val_PCprices'] = json_encode($ProductPrices);
                                $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');
                                $PostData['Val_PCstatus'] = '2';

                                //						$PostData['Val_PCdetail']			=  json_encode(array());
                                //						$PostData['Val_Address']			=  "";
                                //					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
                                //					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;

                                $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,
                                    $ExistingCartData->PCartID);
                                if ($CartAddProductStatus) {

//											$ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
//											$DetailID = $ProductsDetailsArray;

                                    $PostDetailData['Val_PDquantity'] = $data['Val_Quantity'];
                                    $CartDetailsSuccess = $this->Cart_model->updateCartProductsDetails($PostDetailData,
                                        $data['Val_Detail']);
//											$DetailID[] = (string)$CartDetailsSuccess;


//											$UpdatePostData['Val_PCdetail']			= json_encode($DetailID);
//											$CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,$ExistingCartData->PCartID);
                                }

                                $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);

                                $ProductsCount = "0";
                                $ProductsData = array();
                                $ProductsDetailsArray = json_decode($CartData->PC_DetailID);
                                $Index = 0;

                                foreach ($ProductsDetailsArray as $ProductDetail) {

                                    $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                    $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);

                                    if (!empty($ProductDetailData->PD_AttributeID)) {
                                        $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                        $AttributeTitle = $ProductAttributeData->A_Title;
                                    } else {
                                        $AttributeTitle = "";
                                    }
                                    if (!empty($ProductDetailData->PD_AttributeID)) {
                                        $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                        $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                    } else {
                                        $AttributeValueTitle = "";
                                    }


                                    $FeaturedImage = '';
                                    $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                    $ProductsData[] = array(
                                        'DetailID' => $ProductDetailData->CPDetailID,
                                        'ProductID' => $ProductDetailData->PD_ProductID,
                                        'Title' => $ProductData->P_Name,
                                        'Attribute' => $AttributeTitle,
                                        'AttributeValue' => $AttributeValueTitle,
                                        'Currency' => "Rs. ",
                                        'Price' => $ProductData->P_Price,
                                        'Quantity' => $ProductDetailData->PD_Quantity,
                                        'FeaturedImage' => $FeaturedImage,
                                    );
                                    $Index++;
                                }
                                $ProductsCount = (string)count($ProductsData);
                                $Record = array(
                                    'CartID' => getStringValue($CartData->PCartID),
                                    'ProductsCount' => $ProductsCount,
                                    'ProductsData' => $ProductsData,
                                    'Currency' => "Rs. ",
                                    'ItemTotal' => $CartData->PC_ItemTotal,
                                    'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                                    'CartTotal' => $CartData->PC_CartTotal,
                                );

                                $result = array(
                                    'status' => 'success', 'flag' => '1', 'message' => 'Product Updated Successfully',
                                    'data' => $Record
                                );

                            } else {
                                $result = array(
                                    'status' => 'warning', 'flag' => '3',
                                    'message' => 'Something Important Happened !! ', 'data' => (object)array()
                                );
//							echo "No Exist";
                            }


//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                        } else {
                            if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Food']) && !empty($data['Val_Quantity']) && !empty($data['Val_Vendor']) && !empty($data['Val_Restaurant'])) {
                                $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                                //$ExistingCartDetailData	=  $this->Cart_model->getProductsCartDetails($data['Val_Detail']);
                                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null,
                                    array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID' => $data['Val_Food']));
                                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1') {
                                    $ExistingCartDetailData = (object)$ExistingCartDetailData[0];
                                    $RestaurantID = $data['Val_Restaurant'];
                                    $RestaurantData = $this->Restaurants_model->get($RestaurantID);

                                    if (!empty($RestaurantData)) {
                                        if ($RestaurantID == $ExistingCartData->RC_RestaurantID) {
                                            //echo "Exist2";
                                            $CTotal = 0;
                                            $FoodsTotal = 0;
                                            $DeliveryCharges = $ExistingCartData->RC_DeliveryCharge;
                                            $FoodPricesArray = json_decode($ExistingCartData->RC_Prices);

                                            $FoodID = $data['Val_Food'];

                                            if (!empty($FoodPricesArray)) {
                                                foreach ($FoodPricesArray as $Key => $Price) {
                                                    $FoodsTotal = $FoodsTotal + $Price;
                                                }
                                            } else {
                                                $FoodsTotal = $ExistingCartData->PC_ItemTotal;
                                            }

                                            $FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);

                                            if (!empty($FoodData)) {
                                                $FoodAmount = ($FoodData->F_Price * $data['Val_Quantity']);
                                                $FoodAmount = number_format($FoodAmount, 2, '.', '');
                                                //$FoodPricesArray[]	= $FoodAmount;
                                                //$FoodsTotal 		= $FoodsTotal + $FoodAmount;
                                            } else {

                                                $FoodAmount = number_format(0, 2, '.', '');
                                                $FoodPricesArray[] = "";
                                                $FoodsTotal = $FoodsTotal + 0;
                                            }


                                            $FoodsDetailsArray = json_decode($ExistingCartData->RC_DetailID);
                                            if (!empty($FoodsDetailsArray)) {
                                                foreach ($FoodsDetailsArray as $Key => $DetailID) {
                                                    if ($DetailID == $ExistingCartDetailData->CRDetailID) {
                                                        $FoodPricesArray[$Key] = $FoodAmount;
                                                        $FoodsTotal = $FoodsTotal - $ExistingCartDetailData->RD_Price;
                                                        $FoodsTotal = $FoodsTotal + $FoodAmount;
                                                    }
                                                }
                                            }


                                            $ExistingCartDetailsItemsArray = $this->Cart_model->getRestaurantsCartDetails(null,
                                                array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID <>' => $FoodID));
                                            $ItemCount = 0;
                                            if (!empty($ExistingCartDetailsItemsArray)) {
                                                foreach ($ExistingCartDetailsItemsArray as $DetailItemArray) {
                                                    $ItemCount = $ItemCount + $DetailItemArray['RD_Quantity'];
                                                }
                                            } else {
                                                $ItemCount = $ItemCount + 0;
                                            }

                                            $ItemCount = $ItemCount + $data['Val_Quantity'];
                                            $FoodPricesArray = array_filter($FoodPricesArray);

                                            $CartTotal = $FoodsTotal + $DeliveryCharges;
                                            $PostData['Val_RCprices'] = json_encode($FoodPricesArray);
                                            $PostData['Val_RCitemcount'] = getStringValue($ItemCount);
                                            $PostData['Val_RCitemtotal'] = number_format($FoodsTotal, 2, '.', '');
                                            $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                            $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');
                                            $PostData['Val_RCstatus'] = '2';

                                            $CartUpdateProductStatus = $this->Cart_model->updateCartRestaurants($PostData,
                                                $ExistingCartData->RCartID);
                                            if ($CartUpdateProductStatus) {
                                                $PostDetailData['Val_RDprice'] = $FoodAmount;
                                                $PostDetailData['Val_RDquantity'] = $data['Val_Quantity'];
                                                $CartDetailsSuccess = $this->Cart_model->updateCartRestaurantsDetails($PostDetailData,
                                                    $ExistingCartDetailData->CRDetailID);
                                            }
                                            $CartData = $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);

                                            $Record = array(
                                                'CartID' => getStringValue($CartData->RCartID),
                                                'Currency' => "Rs. ",
                                                'ItemCount' => getStringValue($ItemCount),
                                                'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                            );

                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Product Update Successfully', 'data' => $Record
                                            );

                                        } else {
                                            //Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
                                            $CartID = $ExistingCartData->RCartID;
                                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);

                                            $FoodDetailsArray = json_decode($CartData->RC_DetailID);

                                            if (!empty($FoodDetailsArray)) {
                                                $ItemCount = (string)count($FoodDetailsArray);
                                            } else {
                                                $ItemCount = "0";
                                            }

                                            $Record = array(
                                                'CartID' => getStringValue($CartData->RCartID),
                                                'Currency' => "Rs. ",
                                                'ItemCount' => $ItemCount,
                                                'ItemTotal' => $CartData->RC_ItemTotal,
                                                'CartTotal' => $CartData->RC_CartTotal,
                                            );

                                            $result = array(
                                                'status' => 'warning', 'flag' => '3',
                                                'message' => 'You cannot add items from this restaurant as your cart contains items from another restaurant.',
                                                'data' => $Record
                                            );

                                        }
                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3',
                                            'message' => 'Something Important Happened !! ', 'data' => (object)array()
                                        );
                                    }

                                } else {
                                    $result = array(
                                        'status' => 'warning', 'flag' => '3',
                                        'message' => 'Something Important Happened !! ', 'data' => (object)array()
                                    );
//							echo "No Exist";
                                }


//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                            } else {
                                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
                            }
                        }


                    } else {
                        if (!empty($data['Action']) && $data['Action'] == 'RemoveCart') {


                            if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail']) && !empty($data['Val_Vendor']) && !empty($data['Val_Product'])) {
                                $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                                $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails($data['Val_Detail']);

                                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {
                                    //$ExistingCartData = (object)$ExistingCartArray[0];

                                    $ProductVal = $data['Val_Product'];
                                    $Products = $ExistingCartData->PC_ProductID;
                                    $Prices = $ExistingCartData->PC_Prices;
                                    $ProductsArray = json_decode($Products);
                                    $PricesArray = json_decode($Prices);
                                    $CTotal = 0;
                                    $ProductsTotal = 0;

                                    $DeliveryCharges = $ExistingCartData->PC_DeliveryCharge;
                                    if (!empty($ProductsArray)) {
                                        $ProductPrice = 0;
                                        foreach ($ProductsArray as $Key => $Product) {
                                            $ProductsData = $this->Products_model->get($Product);

                                            if ($ProductVal == $Product) {
                                                $ProductPrice = 0;
                                            } else {
                                                $ProductID[] = $ProductsData->ProductID;

                                                $ProductNames[] = $ProductsData->P_Name;
                                                $ProductPrice = (float)$PricesArray[$Key];
                                                $ProductPrices[] = number_format($ProductPrice, 2, '.',
                                                    '');//$ProductPrice;
                                            }

                                            $ProductsTotal = $ProductsTotal + number_format($ProductPrice, 2, '.', '');

                                        }
                                    } else {
                                        $ProductsTotal = $ExistingCartData->PC_ItemTotal;
                                    }

                                    $DetailIdArray = json_decode($ExistingCartData->PC_DetailID);


                                    if (in_array($data['Val_Detail'], $DetailIdArray, true)) {
                                        $Key = array_search($data['Val_Detail'], $DetailIdArray, true);
                                        unset($DetailIdArray[$Key]);
                                        $DetailIdArray = array_values($DetailIdArray);
                                    }
                                    if (!empty($ProductID)) {
                                        $CartTotal = $ProductsTotal + $DeliveryCharges;
                                        $PostData['Val_Product'] = json_encode($ProductID);
                                        $PostData['Val_PCproductnames'] = json_encode($ProductNames);
                                        $PostData['Val_PCprices'] = json_encode($ProductPrices);
                                        $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                        $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                        $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');
                                        $PostData['Val_PCstatus'] = '2';
                                        $PostData['Val_PCdetail'] = json_encode($DetailIdArray);
                                        //						$PostData['Val_PCdetail']			=  json_encode(array());
                                        //						$PostData['Val_Address']			=  "";
                                        //					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
                                        //					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
                                        $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,
                                            $ExistingCartData->PCartID);
                                        if ($CartAddProductStatus) {
                                            $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);
                                        }
                                        $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);

                                        $ProductsCount = "0";
                                        $ProductsData = array();
                                        $ProductsDetailsArray = json_decode($CartData->PC_DetailID);
                                        $Index = 0;

                                        foreach ($ProductsDetailsArray as $ProductDetail) {

                                            $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                            $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                            if (!empty($ProductDetailData->PD_AttributeID)) {
                                                $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                                $AttributeTitle = $ProductAttributeData->A_Title;
                                            } else {
                                                $AttributeTitle = "";
                                            }
                                            if (!empty($ProductDetailData->PD_AttributeID)) {
                                                $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                                $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                            } else {
                                                $AttributeValueTitle = "";
                                            }
                                            $FeaturedImage = '';
                                            $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                            $ProductsData[] = array(
                                                'DetailID' => $ProductDetailData->CPDetailID,
                                                'ProductID' => $ProductDetailData->PD_ProductID,
                                                'Title' => $ProductData->P_Name,
                                                'Attribute' => $AttributeTitle,
                                                'AttributeValue' => $AttributeValueTitle,
                                                'Currency' => "Rs. ",
                                                'Price' => $ProductData->P_Price,
                                                'Quantity' => $ProductDetailData->PD_Quantity,
                                                'FeaturedImage' => $FeaturedImage,
                                            );
                                            $Index++;
                                        }
                                        $ProductsCount = (string)count($ProductsData);
                                        $Record = array(
                                            'CartID' => getStringValue($CartData->PCartID),
                                            'ProductsCount' => $ProductsCount,
                                            'ProductsData' => $ProductsData,
                                            'Currency' => "Rs. ",
                                            'ItemTotal' => $CartData->PC_ItemTotal,
                                            'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                                            'CartTotal' => $CartData->PC_CartTotal,
                                        );

                                        $result = array(
                                            'status' => 'success', 'flag' => '1',
                                            'message' => 'Product Updated Successfully', 'data' => $Record
                                        );

                                    } else {
                                        $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);
                                        $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);
                                        $result = array(
                                            'status' => 'success', 'flag' => '1',
                                            'message' => 'Cart Cleared Successfully', 'data' => (object)array()
                                        );
                                    }
//


                                } else {
                                    if (empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                                        $result = array(
                                            'status' => 'success', 'flag' => '1', 'message' => 'Cart Already Empty.',
                                            'data' => (object)array()
                                        );
                                    } else {
//							$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3',
                                            'message' => 'Product Already removed from cart.',
                                            'data' => (object)array()
                                        );
//							echo "No Exist";
                                    }
                                }


//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                            } else {
                                if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Restaurant']) && !empty($data['Val_Vendor']) && !empty($data['Val_Food'])) {
                                    $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                                    $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null,
                                        array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID' => $data['Val_Food']));
                                    if (!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1') {
                                        $ExistingCartDetailData = (object)$ExistingCartDetailData[0];

                                        $DetailIdArray = json_decode($ExistingCartData->RC_DetailID);
                                        $PricesJson = $ExistingCartData->RC_Prices;
                                        $PricesArray = json_decode($PricesJson);
                                        $FoodPricesArray = array();
                                        $CTotal = 0;
                                        $FoodsTotal = 0;
                                        $ItemsCount = 0;
                                        $DeliveryCharges = $ExistingCartData->RC_DeliveryCharge;
                                        $DetailIDs = array();

                                        if (!empty($DetailIdArray)) {
                                            foreach ($DetailIdArray as $Key => $DetailID) {


                                                $ExistingCartDetailsItemsArrayTemp = $this->Cart_model->getRestaurantsCartDetails($DetailID);

                                                if ($DetailID == $ExistingCartDetailData->CRDetailID) {
                                                    $FoodPrice = 0;
                                                } else {
                                                    $DetailIDs[] = $DetailID;
                                                    $ItemsCount = $ItemsCount + $ExistingCartDetailsItemsArrayTemp->RD_Quantity;

                                                    $FoodPrice = (float)$PricesArray[$Key];
                                                    $FoodPricesArray[] = number_format($FoodPrice, 2, '.',
                                                        '');//$ProductPrice;
                                                }
                                                $FoodsTotal = $FoodsTotal + number_format($FoodPrice, 2, '.', '');

                                            }
                                        } else {
                                            $FoodsTotal = $ExistingCartData->RC_ItemTotal;
                                        }

                                        if (!empty($DetailIDs)) {

                                            //$ItemsCount = $ItemCount + $data['Val_Quantity'];
                                            $FoodPricesArray = array_filter($FoodPricesArray);

                                            $CartTotal = $FoodsTotal + $DeliveryCharges;
                                            $PostData['Val_RCprices'] = json_encode($FoodPricesArray);
                                            $PostData['Val_RCitemcount'] = getStringValue($ItemsCount);
                                            $PostData['Val_RCitemtotal'] = number_format($FoodsTotal, 2, '.', '');
                                            $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                            $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');
                                            $PostData['Val_RCstatus'] = '2';
                                            $PostData['Val_RCdetail'] = json_encode($DetailIDs);

                                            $CartRemoveRestaurantStatus = $this->Cart_model->updateCartRestaurants($PostData,
                                                $ExistingCartData->RCartID);

                                            if ($CartRemoveRestaurantStatus) {
                                                $CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($ExistingCartDetailData->CRDetailID);
                                            }
                                            $CartData = $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);

                                            $Record = array(
                                                'CartID' => getStringValue($CartData->RCartID),
                                                'Currency' => "Rs. ",
                                                'ItemCount' => getStringValue($ItemsCount),
                                                'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                                'DeliveryCharges' => getStringValue($CartData->RC_DeliveryCharge),
                                                'CartTotal' => getStringValue($CartData->RC_CartTotal),
                                            );
                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Product Updated Successfully', 'data' => $Record
                                            );

                                        } else {

                                            $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                                            $CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($ExistingCartDetailData->CRDetailID);
                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Cart Cleared Successfully', 'data' => (object)array()
                                            );
                                        }

                                    } else {
                                        if (empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Cart Already Empty.', 'data' => (object)array()
                                            );
                                        } else {
//							$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
                                            $result = array(
                                                'status' => 'warning', 'flag' => '3',
                                                'message' => 'Product Already removed from cart.',
                                                'data' => (object)array()
                                            );
//							echo "No Exist";
                                        }
                                    }


//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                                } else {
                                    $result = array(
                                        'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                    );
                                }
                            }


                        } else {
                            if (!empty($data['Action']) && $data['Action'] == 'ClearCart') {


                                if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Cart'])) {
                                    $ExistingCartData = $this->Cart_model->get($data['Val_Cart']);

                                    if (!empty($ExistingCartData)) {
                                        $CartSuccess = $this->Cart_model->delete($data['Val_Cart']);
                                        $result = array(
                                            'status' => 'success', 'flag' => '1',
                                            'message' => 'Cart Cleared Successfully.', 'data' => (object)array()
                                        );
                                    } else {
                                        $result = array(
                                            'status' => 'warning', 'flag' => '3', 'message' => 'Cart Already Cleared.',
                                            'data' => (object)array()
                                        );
                                    }

                                } else {
                                    if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart'])) {
                                        $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                                        $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails(null,
                                            array('PD_CartID' => $data['Val_Cart']));

                                        if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {
                                            $DetailIdArray = json_decode($ExistingCartData->PC_DetailID);

                                            foreach ($DetailIdArray as $ProductDetail) {
                                                $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($ProductDetail);
                                            }
                                            $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);

                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Cart Cleared Successfully.', 'data' => $Record
                                            );

                                        } else {
                                            if (!empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                                                $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Cleared Successfully.',
                                                    'data' => (object)array()
                                                );
                                            } else {
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Already Cleared.', 'data' => (object)array()
                                                );
                                            }
                                        }

                                    } else {
                                        if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart'])) {
                                            $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                                            $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null,
                                                array('RD_CartID' => $data['Val_Cart']));
                                            if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {

                                                $DetailIdArray = json_decode($ExistingCartData->RC_DetailID);
                                                $DetailIDs = array();

                                                if (!empty($DetailIdArray)) {
                                                    foreach ($DetailIdArray as $Key => $DetailID) {
                                                        $CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($DetailID);
                                                    }
                                                }

                                                $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Cleared Successfully', 'data' => (object)array()
                                                );
                                            } else {
                                                if (!empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                                                    $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                                                    $result = array(
                                                        'status' => 'success', 'flag' => '1',
                                                        'message' => 'Cart Cleared Successfully.',
                                                        'data' => (object)array()
                                                    );
                                                } else {
                                                    $result = array(
                                                        'status' => 'warning', 'flag' => '3',
                                                        'message' => 'Cart Already Empty.', 'data' => (object)array()
                                                    );
                                                }
                                            }

                                        } else {
                                            $result = array(
                                                'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                            );
                                        }
                                    }
                                }


                            } else {
                                if (!empty($data['Action']) && $data['Action'] == 'CartCheckout') {


                                    if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Vendor']) && !empty($data['Val_Cart']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal'])) {

                                        $data['Val_Corderstatus'] = '1';
                                        $data['Val_Cstatus'] = '3';
                                        $data['Val_Cbookeddttm'] = date('Y-m-d H:i:s');
                                        $success = $this->Cart_model->update($data, $data['Val_Cart']);

                                        if ($success) {
                                            $CartData = $this->Cart_model->get($data['Val_Cart']);


                                            /*$OptionsCount 	= 0;
							$OptionsData  	= array();
							$OptionNames 	= json_decode($CartData->C_OptionNames);
							$OptionPrices 	= json_decode($CartData->C_OptionPrices);
							$PackageNames 	= json_decode($CartData->C_PackageNames);
							$Index = 0;
							foreach($OptionNames as $Option)
								{

									$OptionsData[] = array(
													'Title'			=>$PackageNames[$Index],
													'Description'	=>$Option,
													'Currency'		=>"Rs. ",
													'Price'			=>$OptionPrices[$Index],
													);
									$Index++;
									}
							*/
                                            $TimeslabData = $this->Services_model->getTimeslabs($CartData->C_TimeslabID);


                                            $StartTime = date('h:i A',
                                                strtotime($CartData->C_Date . " " . $TimeslabData->T_StartTime));
                                            $EndTime = date('h:i A',
                                                strtotime($CartData->C_Date . " " . $TimeslabData->T_EndTime));
                                            $Date = date('D, j M,Y', strtotime($CartData->C_Date));
                                            $CartTimeText = $StartTime . " - " . $EndTime . " on " . $Date;

                                            $Record = array(
                                                'OrderID' => getStringValue($CartData->CartID),
                                                //'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
                                                'TimeText' => $CartTimeText,
//										'Currency'		=>"Rs. ",
//										'CartTotal' 	=> $CartData->C_Total,

                                                //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                //		'Status'=> getStatus($VendorData->C_Status),
                                            );

                                            $result = array(
                                                'status' => 'success', 'flag' => '1',
                                                'message' => 'Cart Checkout Successfully', 'data' => $Record
                                            );


                                        } else {
                                            if ($success == false) {
                                                $CartData = $this->Cart_model->get($data['Val_Cart']);

                                                $TimeslabData = $this->Services_model->getTimeslabs($CartData->C_TimeslabID);


                                                $StartTime = date('h:i A',
                                                    strtotime($CartData->C_Date . " " . $TimeslabData->T_StartTime));
                                                $EndTime = date('h:i A',
                                                    strtotime($CartData->C_Date . " " . $TimeslabData->T_EndTime));
                                                $Date = date('D, j M,Y', strtotime($CartData->C_Date));
                                                $CartTimeText = $StartTime . " - " . $EndTime . " on " . $Date;

                                                $Record = array(
                                                    'OrderID' => getStringValue($CartData->CartID),
                                                    //'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
                                                    'TimeText' => $CartTimeText,
//										'Currency'		=>"Rs. ",
//										'CartTotal' 	=> $CartData->C_Total,

                                                    //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                    //		'Status'=> getStatus($VendorData->C_Status),
                                                );


                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart is already Updated', 'data' => $Record
                                                );
                                            } else {
                                                $result = array(
                                                    'status' => 'warning', 'flag' => '3',
                                                    'message' => 'Something Important Happened !! ', 'data' => $success
                                                );
                                            }
                                        }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                                    } else {
                                        if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Vendor']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal'])) {

                                            unset($data['Val_Type']);
                                            $data['Val_PCpaymentoption'] = $data['Val_Cpaymentoption'];
                                            $data['Val_PCservicecharge'] = $data['Val_Cservicecharge'];
                                            $data['Val_PCtotal'] = $data['Val_Ctotal'];
                                            $data['Val_PCorderstatus'] = '1';
                                            $data['Val_PCstatus'] = '3';
                                            $data['Val_PCbookeddttm'] = date('Y-m-d H:i:s');

                                            $success = $this->Cart_model->updateCartProducts($data, $data['Val_Cart']);

                                            if ($success) {
                                                $CartData = $this->Cart_model->getProductsCart($data['Val_Cart']);

                                                $Record = array(
                                                    'OrderID' => getStringValue($CartData->PCartID),
                                                    //'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
//										'TimeText'		=> $CartTimeText,
                                                    'Currency' => "Rs. ",
                                                    'CartTotal' => $CartData->PC_CartTotal,
                                                    'GrandTotal' => $CartData->PC_Total,

                                                    //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                    //		'Status'=> getStatus($VendorData->C_Status),
                                                );

                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Checkout Successfully', 'data' => $Record
                                                );


                                            } else {
                                                if ($success == false) {

                                                    $CartData = $this->Cart_model->getProductsCart($data['Val_Cart']);

                                                    $Record = array(
                                                        'OrderID' => getStringValue($CartData->PCartID),
                                                        //'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
//										'TimeText'		=> $CartTimeText,
                                                        'Currency' => "Rs. ",
                                                        'CartTotal' => $CartData->PC_CartTotal,
                                                        'GrandTotal' => $CartData->PC_Total,

                                                        //		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                                        //		'Status'=> getStatus($VendorData->C_Status),
                                                    );


                                                    $result = array(
                                                        'status' => 'success', 'flag' => '1',
                                                        'message' => 'Cart is already Updated', 'data' => $Record
                                                    );
                                                } else {
                                                    $result = array(
                                                        'status' => 'warning', 'flag' => '3',
                                                        'message' => 'Something Important Happened !! ',
                                                        'data' => $success
                                                    );
                                                }
                                            }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');


                                        } else {
                                            if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Vendor']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_RCpaymentoption']) && !empty($data['Val_RCservicecharge']) && !empty($data['Val_RCtotal'])) {
                                                unset($data['Val_Type']);
                                                $data['Val_RCorderstatus'] = '1';
                                                $data['Val_RCstatus'] = '3';
                                                $data['Val_RCbookeddttm'] = date('Y-m-d H:i:s');
                                                $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                                                if (!empty($CartData)) {
                                                    $success = $this->Cart_model->updateCartRestaurants($data,
                                                        $data['Val_Cart']);

                                                    if ($success) {
                                                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);

                                                        $Record = array(
                                                            'OrderID' => getStringValue($CartData->RCartID),
                                                            'Currency' => "Rs. ",
                                                            'OrderTotal' => $CartData->RC_CartTotal,
                                                        );

                                                        $result = array(
                                                            'status' => 'success', 'flag' => '1',
                                                            'message' => 'Cart Checkout Successfully', 'data' => $Record
                                                        );


                                                    } else {
                                                        if ($success == false) {

                                                            $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);

                                                            $Record = array(
                                                                'OrderID' => getStringValue($CartData->RCartID),
                                                                'Currency' => "Rs. ",
                                                                'OrderTotal' => $CartData->RC_CartTotal,
                                                            );


                                                            $result = array(
                                                                'status' => 'success', 'flag' => '1',
                                                                'message' => 'Cart is already Updated',
                                                                'data' => $Record
                                                            );
                                                        } else {
                                                            $result = array(
                                                                'status' => 'warning', 'flag' => '3',
                                                                'message' => 'Something Important Happened !! ',
                                                                'data' => $success
                                                            );
                                                        }
                                                    }
                                                } else {
                                                    $result = array(
                                                        'status' => 'warning', 'flag' => '3',
                                                        'message' => 'Something Important Happened !! ',
                                                        'data' => (object)array()
                                                    );
                                                }


                                            } else {
                                                $result = array(
                                                    'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                                );
                                            }
                                        }
                                    }


                                } else {
                                    if (!empty($data['Action']) && $data['Action'] == 'GetRestaurantCart') {


                                        if (!empty($data['Val_Vendor'])) {

                                            $ExistingRestaurantCartArray = $this->Cart_model->getRestaurantsCart(null,
                                                array(
                                                    'RC_OrderStatus' => '0', 'RC_Status <>' => '3',
                                                    'RC_Status <>' => '4', 'RC_VendorID' => $data['Val_Vendor']
                                                ));
                                            $DetailIDsArray = array();

                                            if (!empty($ExistingRestaurantCartArray)) {
                                                $ExistingCartData = (object)$ExistingRestaurantCartArray[0];


                                                $DetailIDsJson = $ExistingCartData->RC_DetailID;
                                                $DetailIDsArray = json_decode($DetailIDsJson);

                                                $CartItemsCount = 0;
                                                $FoodsRecords = array();
                                                $FoodsCount = 0;
                                                if (!empty($DetailIDsArray)) {
                                                    $FoodsRecords = array();
                                                    $FoodsCount = 0;
                                                    foreach ($DetailIDsArray as $DetailID) {
                                                        $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                                        $FoodID = '';
                                                        if (!empty($ExistingCartDetailData)) {
                                                            $CartItemsCount = $CartItemsCount + $ExistingCartDetailData->RD_Quantity;
                                                            $FoodID = $ExistingCartDetailData->RD_FoodID;

                                                            $FoodData = $this->Restaurants_model->getFoods($FoodID);

                                                            $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL . $FoodData->RFoodID . '/' . $FoodData->F_DisplayImage : '');
                                                            array_push($FoodsRecords, array(
                                                                    'FoodID' => getStringValue($FoodData->RFoodID),

                                                                    'Title' => getStringValue($FoodData->F_Title),
                                                                    'Description' => getStringValue($FoodData->F_Description),
                                                                    'Currency' => getStringValue("Rs. "),
                                                                    'Price' => getStringValue($FoodData->F_Price),
                                                                    'DisplayImage' => getStringValue($DisplayImage),
                                                                    'Type' => getStringValue($FoodData->F_Type),
                                                                    'CartQuantity' => $ExistingCartDetailData->RD_Quantity
                                                                )
                                                            );

                                                        } else {
                                                            $CartItemsCount = $CartItemsCount + 0;
                                                        }
                                                    }
                                                    $FoodsCount = (string)count($FoodsRecords);
                                                    if (!empty($ExistingCartData->RC_ItemCount)) {
                                                        $CartItemsCount = $ExistingCartData->RC_ItemCount;
                                                    } else {
                                                        $CartItemsCount = $CartItemsCount;
                                                    }

                                                    $CartItemsTotal = number_format($ExistingCartData->RC_ItemTotal, 2);

                                                } else {
                                                    //echo "Not Matching 3";
                                                    $CartItemsCount = $CartItemsCount + 0;
                                                }

                                                $Record = array(
                                                    'CartID' => getStringValue($ExistingCartData->RCartID),
                                                    'FoodsCount' => $FoodsCount,
                                                    'FoodsData' => $FoodsRecords,
                                                    'Currency' => "Rs. ",
                                                    'ItemsCount' => $CartItemsCount,
                                                    'ItemTotal' => $ExistingCartData->RC_ItemTotal,
                                                    'DeliveryCharges' => $ExistingCartData->RC_DeliveryCharge,
                                                    'CartTotal' => $ExistingCartData->RC_CartTotal,

                                                );
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Fetched Successfully.', 'data' => $Record
                                                );
                                            } else {
                                                $result = array(
                                                    'status' => 'error', 'flag' => '2', 'message' => 'Cart is empty',
                                                    'data' => (object)array()
                                                );
                                            }
                                        } else {
                                            $result = array(
                                                'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                            );
                                        }


                                    } else {
                                        if (!empty($data['Action']) && $data['Action'] == 'GetCartDetails') {


                                            if (!empty($data['Val_Vendor'])) {

                                                $ExistingServiceCartArray = $this->Cart_model->get(null, array(
                                                    'C_OrderStatus' => '0', 'C_Status <>' => '3', 'C_Status <>' => '4',
                                                    'C_VendorID' => $data['Val_Vendor']
                                                ));
                                                $ExistingProductCartArray = $this->Cart_model->getProductsCart(null,
                                                    array(
                                                        'PC_OrderStatus' => '0', 'PC_Status <>' => '3',
                                                        'PC_Status <>' => '4', 'PC_VendorID' => $data['Val_Vendor']
                                                    ));
                                                $ExistingRestaurantCartArray = $this->Cart_model->getRestaurantsCart(null,
                                                    array(
                                                        'RC_OrderStatus' => '0', 'RC_Status <>' => '3',
                                                        'RC_Status <>' => '4', 'RC_VendorID' => $data['Val_Vendor']
                                                    ));

                                                $ServicesCart = '1';
                                                $ServicesRecords = (object)array();
                                                if (!empty($ExistingServiceCartArray)) {
                                                    $ExistingServiceCartData = (object)$ExistingServiceCartArray[0];
                                                    $CartID = $ExistingServiceCartArray;

                                                    $OptionsCount = "0";
                                                    $OptionsData = array();
                                                    $OptionNames = json_decode($ExistingServiceCartData->C_OptionNames);
                                                    $PackageNames = json_decode($ExistingServiceCartData->C_PackageNames);
                                                    $OptionPrices = json_decode($ExistingServiceCartData->C_OptionPrices);

                                                    $Index = 0;
                                                    foreach ($OptionNames as $Option) {

                                                        $OptionsData[] = array(
                                                            'Title' => $PackageNames[$Index],
                                                            'Description' => $Option,
                                                            'Currency' => "Rs. ",
                                                            'Price' => $OptionPrices[$Index],
                                                        );
                                                        $Index++;
                                                    }
                                                    $OptionsCount = (string)count($OptionsData);
                                                    $ServicesRecords = array(
                                                        'CartID' => getStringValue($ExistingServiceCartData->CartID),
                                                        'OptionsCount' => $OptionsCount,
                                                        'OptionsData' => $OptionsData,
                                                        'Currency' => "Rs. ",
                                                        'CartTotal' => $ExistingServiceCartData->C_Total,
                                                    );
                                                    $ServicesCart = '2';
                                                }
                                                $ProductsCart = '1';
                                                $ProductsRecords = (object)array();
                                                $ProductsCartItemsCount = 0;
                                                if (!empty($ExistingProductCartArray)) {
                                                    $ExistingProductCartData = (object)$ExistingProductCartArray[0];
                                                    $CartID = $ExistingProductCartData->PCartID;

                                                    $ProductsCount = "0";
                                                    $ProductsData = array();
                                                    $ProductsDetailsArray = json_decode($ExistingProductCartData->PC_DetailID);
                                                    $Index = 0;
                                                    if (!empty($ProductsDetailsArray)) {
                                                        foreach ($ProductsDetailsArray as $ProductDetail) {

                                                            $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                                            $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                                            if (!empty($ProductDetailData->PD_AttributeID)) {
                                                                $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                                                $AttributeTitle = $ProductAttributeData->A_Title;
                                                            } else {
                                                                $AttributeTitle = "";
                                                            }
                                                            if (!empty($ProductDetailData->PD_AttribValueID)) {
                                                                $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                                                $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                                            } else {
                                                                $AttributeValueTitle = "";
                                                            }


                                                            $FeaturedImage = '';
                                                            $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                                            $ProductsData[] = array(
                                                                'DetailID' => $ProductDetailData->CPDetailID,
                                                                'ProductID' => $ProductDetailData->PD_ProductID,
                                                                'Title' => $ProductData->P_Name,
                                                                'Attribute' => $AttributeTitle,
                                                                'AttributeValue' => $AttributeValueTitle,
                                                                'Currency' => "Rs. ",
                                                                'Price' => $ProductData->P_Price,
                                                                'Quantity' => $ProductDetailData->PD_Quantity,
                                                                'FeaturedImage' => $FeaturedImage,
                                                            );
                                                            $ProductsCartItemsCount = $ProductsCartItemsCount + $ProductDetailData->PD_Quantity;

                                                            $Index++;
                                                        }
                                                    } else {
                                                        //echo "Not Matching 3";
                                                        $ProductsCartItemsCount = $ProductsCartItemsCount + 0;
                                                    }
                                                    $ProductsCount = (string)count($ProductsData);
                                                    $ProductsRecords = array(
                                                        'CartID' => getStringValue($CartID),
                                                        'ProductsCount' => $ProductsCount,
                                                        'ProductsData' => $ProductsData,
                                                        'Currency' => "Rs. ",
                                                        'ItemsCount' => $ProductsCartItemsCount,
                                                        'ItemTotal' => $ExistingProductCartData->PC_ItemTotal,
                                                        'DeliveryCharges' => $ExistingProductCartData->PC_DeliveryCharge,
                                                        'CartTotal' => $ExistingProductCartData->PC_CartTotal,

                                                    );
                                                    $ProductsCart = '2';
                                                }

                                                $RestaurantsCart = '1';
                                                $RestaurantsRecords = (object)array();
                                                $RestaurantsCartItemsCount = 0;
                                                if (!empty($ExistingRestaurantCartArray)) {
                                                    $ExistingCartData = (object)$ExistingRestaurantCartArray[0];

                                                    $DetailIDsJson = $ExistingCartData->RC_DetailID;
                                                    $DetailIDsArray = json_decode($DetailIDsJson);

                                                    $RestaurantsCartItemsCount = 0;
                                                    $FoodsRecords = array();
                                                    $FoodsCount = 0;
                                                    if (!empty($DetailIDsArray)) {
                                                        $FoodsRecords = array();
                                                        $FoodsCount = 0;
                                                        foreach ($DetailIDsArray as $DetailID) {
                                                            $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                                            $FoodID = '';
                                                            if (!empty($ExistingCartDetailData)) {
                                                                $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + $ExistingCartDetailData->RD_Quantity;
                                                                $FoodID = $ExistingCartDetailData->RD_FoodID;

                                                                $FoodData = $this->Restaurants_model->getFoods($FoodID);

                                                                $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL . $FoodData->RFoodID . '/' . $FoodData->F_DisplayImage : '');
                                                                array_push($FoodsRecords, array(
                                                                        'FoodID' => getStringValue($FoodData->RFoodID),

                                                                        'Title' => getStringValue($FoodData->F_Title),
                                                                        'Description' => getStringValue($FoodData->F_Description),
                                                                        'Currency' => getStringValue("Rs. "),
                                                                        'Price' => getStringValue($FoodData->F_Price),
                                                                        'DisplayImage' => getStringValue($DisplayImage),
                                                                        'Type' => getStringValue($FoodData->F_Type),
                                                                        'CartQuantity' => $ExistingCartDetailData->RD_Quantity
                                                                    )
                                                                );

                                                            } else {
                                                                $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                                                            }
                                                        }
                                                        $FoodsCount = (string)count($FoodsRecords);
                                                        if (!empty($ExistingCartData->RC_ItemCount)) {
                                                            $RestaurantsCartItemsCount = $ExistingCartData->RC_ItemCount;
                                                        } else {
                                                            $RestaurantsCartItemsCount = $RestaurantsCartItemsCount;
                                                        }

                                                        $RestaurantsCartItemsCount = (string)$RestaurantsCartItemsCount;

                                                    } else {
                                                        //echo "Not Matching 3";
                                                        $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                                                    }

                                                    $RestaurantsRecords = array(
                                                        'CartID' => getStringValue($ExistingCartData->RCartID),
                                                        'RestaurantID' => getStringValue($ExistingCartData->RC_RestaurantID),
                                                        'FoodsCount' => $FoodsCount,
                                                        'FoodsData' => $FoodsRecords,
                                                        'Currency' => "Rs. ",
                                                        'ItemsCount' => $RestaurantsCartItemsCount,
                                                        'ItemTotal' => $ExistingCartData->RC_ItemTotal,
                                                        'DeliveryCharges' => $ExistingCartData->RC_DeliveryCharge,
                                                        'CartTotal' => $ExistingCartData->RC_CartTotal,
                                                    );
                                                    $RestaurantsCart = '2';
                                                }

                                                $CartData['ServicesCart'] = $ServicesCart;
                                                $CartData['ServicesCartData'] = $ServicesRecords;
                                                $CartData['ProductsCart'] = $ProductsCart;
                                                $CartData['ProductsCartData'] = $ProductsRecords;
                                                $CartData['RestaurantsCart'] = $RestaurantsCart;
                                                $CartData['RestaurantsCartData'] = $RestaurantsRecords;
                                                $result = array(
                                                    'status' => 'success', 'flag' => '1',
                                                    'message' => 'Cart Fetched Successfully', 'data' => $CartData
                                                );


                                                //print_r($ExistingServiceCartArray);
                                                //print_r($ExistingProductCartArray);
                                                //print_r($ExistingRestaurantCartArray);
                                                //	exit;

                                            } else {
                                                $result = array(
                                                    'status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing'
                                                );
                                            }


                                        } else {
                                            if (!empty($data['Action']) && $data['Action'] == 'GetOrderHistory') {

                                                if (!empty($data['Val_Vendor'])) {
                                                    $OngoingOrderRecords = array();
                                                    $PastOrderRecords = array();
                                                    $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                    $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                    $this->db->where('vendor_id', $data['Val_Vendor']);
                                                    $vendorProducts = $this->db->get('1w_tbl_product_vendor')->result_array();
                                                    $vendorProductsIds = array_map(function ($product) {
                                                        return $product['product_id'];
                                                    }, $vendorProducts);
                                                    if (!empty($VendorData)) {
                                                        $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                        if (!empty($CategoryData)) {
                                                            $BusinessType = $CategoryData->C_Type;
                                                            if ($BusinessType == '1') {
                                                                // $OngoingOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_Status'=>'3'),"C_OrderStatus <> 0 OR C_OrderStatus <> 1 OR C_OrderStatus <> 4 OR C_OrderStatus <> 5"); /* Updation = Change "AND" into "OR" 28.03.2019 8:49pm */
                                                                // $PastOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Vendor'],'C_Status'=>'3'),'(C_OrderStatus = 4 OR C_OrderStatus = 5) ');
                                                                $OngoingOrdersArray = $this->Cart_model->getServiceOngoingOrders($data['Val_Vendor']); /* Updation = Change "AND" into "OR" 28.03.2019 8:49pm */
                                                                $PastOrdersArray = $this->Cart_model->getServicePastOrders($data['Val_Vendor']);

                                                            } else {
                                                                if ($BusinessType == '2') {
                                                                    $OngoingOrdersArray = $this->Cart_model->getProductsOngoingOrders($data['Val_Vendor']);
                                                                    $NewOrdersArray = $this->Cart_model->getProductsNewOngoingOrders($data['Val_Vendor']);
                                                                    if ($NewOrdersArray != false) {
                                                                        array_push($OngoingOrdersArray, $NewOrdersArray);
                                                                    }
                                                                    // $OngoingOrdersArrayForVendor = array_filter($OngoingOrdersArray,
                                                                    //     function ($order) use ($vendorProductsIds) {
                                                                    //         $intersectingArray = array_intersect(json_decode($order['PC_ProductID']),
                                                                    //             $vendorProductsIds);
                                                                    //         if (count($intersectingArray) > 0) {
                                                                    //             return $order;
                                                                    //         }
                                                                    //     });
//
                                                                    $PastOrdersArray = $this->Cart_model->getProductsPastOrders($data['Val_Vendor']);

                                                                } else {
                                                                    if ($BusinessType == '3') {
                                                                        $OngoingOrdersArray = $this->Cart_model->getRestaurantsOngoingOrders($data['Val_Vendor']); /* Updation = Change "AND" into "OR" 28.03.2019 8:49pm */
                                                                        $PastOrdersArray = $this->Cart_model->getRestaurantsPastOrders($data['Val_Vendor']);

                                                                    }
                                                                }
                                                            }


                                                            if (!empty($OngoingOrdersArray)) {
                                                                foreach ($OngoingOrdersArray as $OrderArray) {
                                                                    $OrderData = (object)$OrderArray;
                                                                    if (!empty($OrderData->CartID)) {

                                                                        $OrderID = $OrderData->CartID;
                                                                        $OrderType = '1';
                                                                        $OrderName = getOrderName('1', $OrderID);
                                                                        $OrderDate = $OrderData->C_AssignedDttm;
                                                                        $OrderTimeAgo = time_ago($OrderData->C_AssignedDttm);
                                                                        $OrderTotal = $OrderData->C_Total;
                                                                    } else {
                                                                        if (!empty($OrderData->PCartID)) {

                                                                            $OrderID = $OrderData->PCartID;
                                                                            $OrderType = '2';
                                                                            $OrderName = getOrderName('2', $OrderID);
                                                                            $OrderDate = $OrderData->PC_AssignedDttm;
                                                                            $OrderTimeAgo = time_ago($OrderData->PC_AssignedDttm);
                                                                            $OrderTotal = $OrderData->PC_Total;
                                                                        } else {
                                                                            if (!empty($OrderData->RCartID)) {

                                                                                $OrderID = $OrderData->RCartID;
                                                                                $OrderType = '3';
                                                                                $OrderName = getOrderName('3',
                                                                                    $OrderID);
                                                                                $OrderDate = $OrderData->RC_AssignedDttm;
                                                                                $OrderTimeAgo = time_ago($OrderData->RC_AssignedDttm);
                                                                                $OrderTotal = $OrderData->RC_Total;
                                                                            }
                                                                        }
                                                                    }

                                                                    $OngoingOrderRecords[] = array(
                                                                        'OrderID' => $OrderID,
                                                                        'OrderType' => $OrderType,
                                                                        'OrderName' => $OrderName,
                                                                        'OrderDate' => $OrderDate,
                                                                        'OrderTimeAgo' => $OrderTimeAgo,
                                                                        'Currency' => "Rs. ",
                                                                        'OrderTotal' => getStringValue($OrderTotal),
                                                                    );
                                                                }
                                                                $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                            }

                                                            if (!empty($PastOrdersArray)) {
                                                                foreach ($PastOrdersArray as $OrderArray) {
                                                                    $OrderData = (object)$OrderArray;

                                                                    if (!empty($OrderData->CartID)) {

                                                                        $OrderID = $OrderData->CartID;
                                                                        $OrderType = '1';
                                                                        $OrderName = getOrderName('1', $OrderID);
                                                                        $OrderDate = $OrderData->C_AssignedDttm;
                                                                        $OrderTimeAgo = time_ago($OrderData->C_AssignedDttm);
                                                                        $OrderTotal = $OrderData->C_Total;
                                                                    } else {
                                                                        if (!empty($OrderData->PCartID)) {

                                                                            $OrderID = $OrderData->PCartID;
                                                                            $OrderType = '2';
                                                                            $OrderName = getOrderName('2', $OrderID);
                                                                            $OrderDate = $OrderData->PC_AssignedDttm;
                                                                            $OrderTimeAgo = time_ago($OrderData->PC_AssignedDttm);
                                                                            $OrderTotal = $OrderData->PC_Total;
                                                                        } else {
                                                                            if (!empty($OrderData->RCartID)) {

                                                                                $OrderID = $OrderData->RCartID;
                                                                                $OrderType = '3';
                                                                                $OrderName = getOrderName('3',
                                                                                    $OrderID);
                                                                                $OrderDate = $OrderData->RC_AssignedDttm;
                                                                                $OrderTimeAgo = time_ago($OrderData->RC_AssignedDttm);
                                                                                $OrderTotal = $OrderData->RC_Total;
                                                                            }
                                                                        }
                                                                    }

                                                                    $PastOrderRecords[] = array(
                                                                        'OrderID' => $OrderID,
                                                                        'OrderType' => $OrderType,
                                                                        'OrderName' => $OrderName,
                                                                        'OrderDate' => $OrderDate,
                                                                        'OrderTimeAgo' => $OrderTimeAgo,
                                                                        'Currency' => "Rs. ",
                                                                        'OrderTotal' => getStringValue($OrderTotal),
                                                                    );
                                                                }
                                                                $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                            }


                                                            $MiscRecords = array(
                                                                'NotificationCount' => "1",
                                                            );


                                                            $Records['OngoingOrdersCount'] = $OngoingOrderRecordsCount;
                                                            $Records['OngoingOrders'] = $OngoingOrderRecords;
                                                            $Records['PastOrdersCount'] = $PastOrderRecordsCount;
                                                            $Records['PastOrders'] = $PastOrderRecords;
                                                            $Records['MiscData'] = $MiscRecords;
                                                            $result = array(
                                                                'status' => 'success', 'flag' => '1',
                                                                'message' => 'Vendor Orders Data Fetched',
                                                                'data' => $Records
                                                            );


                                                        } else {
                                                            $result = array(
                                                                'status' => 'warning', 'flag' => '3',
                                                                'message' => 'Something Went Wrong...',
                                                                'data' => (object)$Records
                                                            );
                                                        }
                                                    } else {
                                                        $result = array(
                                                            'status' => 'warning', 'flag' => '3',
                                                            'message' => 'Something Went Wrong...',
                                                            'data' => (object)$Records
                                                        );
                                                    }

                                                } else {
                                                    $result = array(
                                                        'status' => 'info', 'flag' => '4',
                                                        'message' => 'Paramater Missing'
                                                    );
                                                }


                                            } else {
                                                if (!empty($data['Action']) && $data['Action'] == 'GetOrderDetails') {


                                                    if (!empty($data['Val_Vendor']) && !empty($data['Val_Type']) && !empty($data['Val_Order'])) {


                                                        $OngoingOrderRecords = array();
                                                        $PastOrderRecords = array();
                                                        $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                        $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                        if (!empty($VendorData)) {
                                                            $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                            if (!empty($CategoryData)) {
                                                                $BusinessType = $CategoryData->C_Type;
                                                                // exit;
                                                                if ($BusinessType == '1') {

                                                                    $OrderData = $this->Cart_model->get($data['Val_Order']);
//                                                                        array('C_AssignedTo' => $data['Val_Vendor']));
                                                                    $OrderData = $OrderData[0];
                                                                } else {
                                                                    if ($BusinessType == '2') {
                                                                        $OrderData = $this->Cart_model->getProductsCart($data['Val_Order']);
//                                                                            array('PC_AssignedTo' => $data['Val_Vendor']));
                                                                        $OrderData = $OrderData[0];

                                                                    } else {
                                                                        if ($BusinessType == '3') {
                                                                            $OrderData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);
//                                                                                array('RC_AssignedTo' => $data['Val_Vendor']));
                                                                            $OrderData = $OrderData[0];
                                                                        }
                                                                    }
                                                                }


                                                                if (!empty($OrderData)) {

                                                                    if (!empty($OrderData->CartID)) {

                                                                        $OrderID = $OrderData->CartID;
                                                                        $OrderType = '1';
                                                                        $OrderName = getOrderName('1', $OrderID);
                                                                        $OrderDate = date('Y-m-d',
                                                                            strtotime($OrderData->C_BookedDttm));
                                                                        $OrderTime = date('H:i',
                                                                            strtotime($OrderData->C_BookedDttm));
                                                                        $OrderTimeAgo = time_ago($OrderData->C_AssignedDttm);
                                                                        $Username = $OrderData->C_CustomerName;
                                                                        $AddressArray = json_decode($OrderData->C_CustomerAddress);
                                                                        $AddressString = $AddressArray[1] . ',' . $AddressArray[2];

                                                                        $ItemsCount = "0";
                                                                        $ItemsData = array();
                                                                        $OptionNames = json_decode($OrderData->C_OptionNames);
                                                                        $PackageNames = json_decode($OrderData->C_PackageNames);
                                                                        $OptionPrices = json_decode($OrderData->C_OptionPrices);

                                                                        $Index = 1;
                                                                        $Key = 0;
                                                                        foreach ($OptionNames as $Option) {

                                                                            $ItemsData[] = array(
                                                                                'Index' => (string)$Index,
                                                                                'Title' => $PackageNames[$Key] . ' ( ' . $Option . ' ) ',
                                                                                'Description' => $Option,
                                                                                'Currency' => "Rs. ",
                                                                                'Price' => $OptionPrices[$Key],
                                                                            );
                                                                            $Key++;
                                                                            $Index++;
                                                                        }
                                                                        $ItemsCount = (string)count($ItemsData);

                                                                        $ServiceCharge = $OrderData->C_ServiceCharge;
                                                                        $DeliveryCharge = "0";
                                                                        $OrderTotal = $OrderData->C_Total;
                                                                        $AcceptedStatus = $OrderData->C_OrderStatus;

                                                                        if ($AcceptedStatus == '3') {
                                                                            $AcceptedStatus == '2';
                                                                        }
                                                                    } else {
                                                                        if (!empty($OrderData->PCartID)) {

                                                                            $OrderID = $OrderData->PCartID;
                                                                            $OrderType = '2';
                                                                            $OrderName = getOrderName('2', $OrderID);
                                                                            $OrderDate = date('Y-m-d',
                                                                                strtotime($OrderData->PC_BookedDttm));
                                                                            $OrderTime = date('H:i',
                                                                                strtotime($OrderData->PC_BookedDttm));
                                                                            $OrderTimeAgo = time_ago($OrderData->PC_AssignedDttm);
                                                                            $Username = $OrderData->PC_CustomerName;
                                                                            $AddressArray = json_decode($OrderData->PC_CustomerAddress);
                                                                            $AddressString = $AddressArray[1] . ',' . $AddressArray[2];


                                                                            $ItemsCount = "0";
                                                                            $ItemsData = array();
                                                                            $ProductsDetailsArray = json_decode($OrderData->PC_DetailID);
                                                                            $ProductCartItemsCount = "0";
                                                                            $Index = 1;
                                                                            $Key = 0;

                                                                            if (!empty($ProductsDetailsArray)) {
                                                                                foreach ($ProductsDetailsArray as $ProductDetail) {

                                                                                    $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);

                                                                                    $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                                                                    if (!empty($ProductDetailData->PD_AttributeID)) {
                                                                                        $ProductAttributeData = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                                                                                        $AttributeTitle = $ProductAttributeData->A_Title;
                                                                                    } else {
                                                                                        $AttributeTitle = "";
                                                                                    }
                                                                                    if (!empty($ProductDetailData->PD_AttribValueID)) {
                                                                                        $ProductAttribValueData = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                                                                                        $AttributeValueTitle = $ProductAttribValueData->V_Title;
                                                                                    } else {
                                                                                        $AttributeValueTitle = "";
                                                                                    }


                                                                                    $FeaturedImage = '';
                                                                                    $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL . $ProductData->ProductID . '/' . $ProductData->P_FeaturedImage : '');
                                                                                    $ItemsData[] = array(
                                                                                        'Index' => (string)$Index,
                                                                                        'Title' => $ProductData->P_Name . ' x ' . $ProductDetailData->PD_Quantity,
                                                                                        'Description' => $AttributeTitle . ' : ' . $AttributeValueTitle,
                                                                                        'Currency' => "Rs. ",
                                                                                        'Price' => $ProductData->P_Price,
                                                                                    );
                                                                                    $ProductCartItemsCount = $ProductCartItemsCount + $ProductDetailData->PD_Quantity;

                                                                                    $Index++;
                                                                                }
                                                                                $ItemsCount = (string)count($ItemsData);
                                                                            } else {
                                                                                //echo "Not Matching 3";
                                                                                $ProductCartItemsCount = $ProductCartItemsCount + 0;
                                                                            }


                                                                            $ServiceCharge = $OrderData->PC_ServiceCharge;
                                                                            $DeliveryCharge = $OrderData->PC_DeliveryCharge;
                                                                            $OrderTotal = $OrderData->PC_Total;
                                                                            $AcceptedStatus = $OrderData->PC_OrderStatus;

                                                                            if ($AcceptedStatus == '3') //Niranjan Need to confirm what happen
                                                                            {
                                                                                $AcceptedStatus == '2';
                                                                            }
                                                                        } else {
                                                                            if (!empty($OrderData->RCartID)) {

                                                                                $OrderID = $OrderData->RCartID;
                                                                                $OrderType = '3';
                                                                                $OrderName = getOrderName('3',
                                                                                    $OrderID);
                                                                                $OrderDate = date('Y-m-d',
                                                                                    strtotime($OrderData->RC_BookedDttm));
                                                                                $OrderTime = date('H:i',
                                                                                    strtotime($OrderData->RC_BookedDttm));
                                                                                $OrderTimeAgo = time_ago($OrderData->RC_AssignedDttm);
                                                                                $Username = $OrderData->RC_CustomerName;
                                                                                $AddressArray = json_decode($OrderData->RC_CustomerAddress);
                                                                                $AddressString = $AddressArray[1] . ',' . $AddressArray[2];

                                                                                $ItemsCount = "0";
                                                                                $ItemsData = array();
                                                                                $DetailIDsJson = $OrderData->RC_DetailID;
                                                                                $DetailIDsArray = json_decode($DetailIDsJson);

                                                                                $Index = 1;
                                                                                if (!empty($DetailIDsArray)) {
                                                                                    foreach ($DetailIDsArray as $DetailID) {
                                                                                        $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                                                                        $FoodID = '';
                                                                                        if (!empty($ExistingCartDetailData)) {
                                                                                            $FoodID = $ExistingCartDetailData->RD_FoodID;
                                                                                            $FoodData = $this->Restaurants_model->getFoods($FoodID);
                                                                                            $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL . $FoodData->RFoodID . '/' . $FoodData->F_DisplayImage : '');
                                                                                            if ($FoodData->F_Type == '1') {
                                                                                                $F_Type = 'Veg.';
                                                                                            } else {
                                                                                                if ($FoodData->F_Type == '2') {
                                                                                                    $F_Type = 'Non Veg.';
                                                                                                }
                                                                                            }
                                                                                            array_push($ItemsData,
                                                                                                array(
                                                                                                    'Index' => (string)$Index,
                                                                                                    'Title' => getStringValue('(' . $F_Type . ')' . $FoodData->F_Title . ' x ' . $ExistingCartDetailData->RD_Quantity),
                                                                                                    'Description' => getStringValue($FoodData->F_Description),
                                                                                                    'Currency' => getStringValue("Rs. "),
                                                                                                    'Price' => getStringValue($FoodData->F_Price),

                                                                                                )
                                                                                            );

                                                                                        }
                                                                                        $Index++;
                                                                                    }
                                                                                    $ItemsCount = (string)count($ItemsData);

                                                                                }


                                                                                $ServiceCharge = $OrderData->RC_ServiceCharge;
                                                                                $DeliveryCharge = $OrderData->RC_DeliveryCharge;
                                                                                $OrderTotal = $OrderData->RC_Total;
                                                                                $AcceptedStatus = $OrderData->RC_OrderStatus;

                                                                                if ($AcceptedStatus == '3') {
                                                                                    $AcceptedStatus == '2';
                                                                                }

                                                                            }
                                                                        }
                                                                    }

                                                                    $Record = array(
                                                                        'OrderID' => $OrderID,
                                                                        'OrderType' => $OrderType,
                                                                        'OrderName' => $OrderName,
                                                                        'OrderDate' => $OrderDate,
                                                                        'OrderTime' => $OrderTime,
                                                                        'OrderTimeAgo' => $OrderTimeAgo,
                                                                        'Username' => $Username,
                                                                        'Address' => $AddressString,
                                                                        'ItemsCount' => $ItemsCount,
                                                                        'ItemsData' => $ItemsData,
                                                                        'ServiceCharge' => getStringValue($ServiceCharge),

                                                                        'DeliveryCharge' => getStringValue($DeliveryCharge),
                                                                        'AcceptedStatus' => getStringValue($AcceptedStatus),
                                                                        'Currency' => "Rs. ",
                                                                        'OrderTotal' => getStringValue($OrderTotal),

                                                                    );

                                                                    $result = array(
                                                                        'status' => 'success', 'flag' => '1',
                                                                        'message' => 'Vendor Orders Data Fetched',
                                                                        'data' => $Record
                                                                    );
                                                                } else {
                                                                    $result = array(
                                                                        'status' => 'error', 'flag' => '2',
                                                                        'message' => 'Vendor Orders Data Not Found',
                                                                        'data' => (object)$Record
                                                                    );
                                                                }

                                                            } else {
                                                                $result = array(
                                                                    'status' => 'warning', 'flag' => '3',
                                                                    'message' => 'Something Went Wrong...',
                                                                    'data' => (object)$Records
                                                                );
                                                            }
                                                        } else {
                                                            $result = array(
                                                                'status' => 'warning', 'flag' => '3',
                                                                'message' => 'Something Went Wrong...',
                                                                'data' => (object)$Records
                                                            );
                                                        }


                                                    } else {
                                                        $result = array(
                                                            'status' => 'info', 'flag' => '4',
                                                            'message' => 'Paramater Missing'
                                                        );
                                                    }


                                                } else {
                                                    if (!empty($data['Action']) && $data['Action'] == 'AcceptOrder') {

                                                        if (!empty($data['Val_Type']) && !empty($data['Val_Vendor']) && !empty($data['Val_Order'])) {
                                                            unset($data['Val_Type']);

                                                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                            if (!empty($VendorData)) {
                                                                $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                                if (!empty($CategoryData)) {
                                                                    $BusinessType = $CategoryData->C_Type;

                                                                    if ($BusinessType == '1') {
                                                                        $OrderData = $this->Cart_model->get($data['Val_Order'],
                                                                            array('C_AssignedTo' => $data['Val_Vendor']));

                                                                        if (!empty($OrderData)) {
                                                                            $this->db->limit(1);
                                                                            $this->db->order_by('DeliveryBoyID','desc');
                                                                            $deliveryboy = $this->db->get('1w_tbl_delivery_boys')->row();
                                                                            $data['Val_Corderstatus'] = '2';
                                                                            $data['Val_CassignedTo'] = $VendorData->VendorID;
                                                                            $data['Val_Caccepteddttm'] = date('Y-m-d H:i:s');
                                                                            $data['Val_PCdeliveryby'] = $deliveryboy->DeliveryBoyID;
                                                                            $data['Val_PCdeliverybystatus'] ='1';
                                                                            $success = $this->Cart_model->update($data,
                                                                                $data['Val_Order']);

                                                                            if ($success) {
                                                                                $CartData = $this->Cart_model->get($data['Val_Order']);

                                                                                $Record = array(
                                                                                    'OrderID' => getStringValue($CartData->CartID),
                                                                                );
                                                                                $CustomerToken = $this->Notifications_model->getToken($CartData->C_CustomerID, 1);
                                                                                $CustomerToken = (object)$CustomerToken[0];
                                                                                sendPushNotificationAndroid($CustomerToken->M_AndroidToken, 'Your Order is Accepted');
                                                                                $result = array(
                                                                                    'status' => 'success',
                                                                                    'flag' => '1',
                                                                                    'message' => 'Order Accepted Successfully',
                                                                                    'data' => $Record
                                                                                );

                                                                            } else {
                                                                                if ($success == false) {
                                                                                    $CartData = $this->Cart_model->get($data['Val_Order']);

                                                                                    $Record = array(
                                                                                        'OrderID' => getStringValue($CartData->CartID),
                                                                                    );

                                                                                    $result = array(
                                                                                        'status' => 'success',
                                                                                        'flag' => '1',
                                                                                        'message' => 'Order is already Accepted',
                                                                                        'data' => $Record
                                                                                    );
                                                                                } else {
                                                                                    $result = array(
                                                                                        'status' => 'warning',
                                                                                        'flag' => '3',
                                                                                        'message' => 'Something Important Happened ! ',
                                                                                        'data' => (object)array()
                                                                                    );
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'warning', 'flag' => '3',
                                                                                'message' => 'Something Important Happened !! ',
                                                                                'data' => (object)array()
                                                                            );
                                                                        }


                                                                    } else {
                                                                        if ($BusinessType == '2') {
                                                                            $OrderData = $this->Cart_model->getProductsCart($data['Val_Order']);

                                                                            if (!empty($OrderData)) {
                                                                                $data['Val_PCorderstatus'] = '3';
                                                                                $data['Val_PCassginedto'] = $VendorData->VendorID;
                                                                                $data['Val_PCaccepteddttm'] = date('Y-m-d H:i:s');
                                                                                $success = $this->Cart_model->updateCartProducts($data,
                                                                                    $data['Val_Order']);

                                                                                if ($success) {
                                                                                    $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                                                                                    $Record = array(
                                                                                        'OrderID' => getStringValue($CartData->PCartID),
                                                                                    );
                                                                                    $CustomerToken = $this->Notifications_model->getToken($CartData->PC_CustomerID, 1);
                                                                                    $CustomerToken = (object)$CustomerToken[0];
                                                                                    sendPushNotificationAndroid($CustomerToken->M_AndroidToken, 'Your Order is Accepted');
                                                                                    $result = array(
                                                                                        'status' => 'success',
                                                                                        'flag' => '1',
                                                                                        'message' => 'Order Accepted Successfully',
                                                                                        'data' => $Record
                                                                                    );

                                                                                } else {
                                                                                    if ($success == false) {
                                                                                        $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                                                                                        $Record = array(
                                                                                            'OrderID' => getStringValue($CartData->PCartID),
                                                                                        );

                                                                                        $result = array(
                                                                                            'status' => 'success',
                                                                                            'flag' => '1',
                                                                                            'message' => 'Order is already Accepted',
                                                                                            'data' => $Record
                                                                                        );
                                                                                    } else {
                                                                                        $result = array(
                                                                                            'status' => 'warning',
                                                                                            'flag' => '3',
                                                                                            'message' => 'Something Important Happened !! ',
                                                                                            'data' => (object)array()
                                                                                        );
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $result = array(
                                                                                    'status' => 'warning',
                                                                                    'flag' => '3',
                                                                                    'message' => 'Something Important Happened !! ',
                                                                                    'data' => (object)array()
                                                                                );
                                                                            }
                                                                        } else {
                                                                            if ($BusinessType == '3') {
                                                                                $OrderData = $this->Cart_model->getRestaurantsCart($data['Val_Order'],
                                                                                    array('RC_AssignedTo' => $data['Val_Vendor']));

                                                                                if (!empty($OrderData)) {
                                                                                    $data['Val_RCorderstatus'] = '2';
                                                                                    $data['Val_RCassginedto'] = $VendorData->VendorID;
                                                                                    $data['Val_RCaccepteddttm'] = date('Y-m-d H:i:s');
                                                                                    $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);
                                                                                    if (!empty($CartData)) {
                                                                                        $success = $this->Cart_model->updateCartRestaurants($data,
                                                                                            $data['Val_Order']);

                                                                                        if ($success) {
                                                                                            $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                                                                                            $Record = array(
                                                                                                'OrderID' => getStringValue($CartData->RCartID),
                                                                                            );
                                                                                            $CustomerToken = $this->Notifications_model->getToken($CartData->RC_CustomerID, 1);
                                                                                            $CustomerToken = (object)$CustomerToken[0];
                                                                                            sendPushNotificationAndroid($CustomerToken->M_AndroidToken, 'Your Order is Accepted');

                                                                                            $result = array(
                                                                                                'status' => 'success',
                                                                                                'flag' => '1',
                                                                                                'message' => 'Order Accepted Successfully',
                                                                                                'data' => $Record
                                                                                            );


                                                                                        } else {
                                                                                            if ($success == false) {

                                                                                                $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                                                                                                $Record = array(
                                                                                                    'OrderID' => getStringValue($CartData->RCartID),
                                                                                                );


                                                                                                $result = array(
                                                                                                    'status' => 'success',
                                                                                                    'flag' => '1',
                                                                                                    'message' => 'Order is already Accepted',
                                                                                                    'data' => $Record
                                                                                                );
                                                                                            } else {
                                                                                                $result = array(
                                                                                                    'status' => 'warning',
                                                                                                    'flag' => '3',
                                                                                                    'message' => 'Something Important Happened !! ',
                                                                                                    'data' => (object)array()
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $result = array(
                                                                                            'status' => 'warning',
                                                                                            'flag' => '3',
                                                                                            'message' => 'Something Important Happened !! ',
                                                                                            'data' => (object)array()
                                                                                        );
                                                                                    }
                                                                                } else {
                                                                                    $result = array(
                                                                                        'status' => 'warning',
                                                                                        'flag' => '3',
                                                                                        'message' => 'Something Important Happened !! ',
                                                                                        'data' => (object)array()
                                                                                    );
                                                                                }

                                                                            }
                                                                        }
                                                                    }

                                                                } else {
                                                                    $result = array(
                                                                        'status' => 'warning', 'flag' => '3',
                                                                        'message' => 'Something Went Wrong...',
                                                                        'data' => (object)$Records
                                                                    );
                                                                }
                                                            } else {
                                                                $result = array(
                                                                    'status' => 'warning', 'flag' => '3',
                                                                    'message' => 'Something Went Wrong...',
                                                                    'data' => (object)$Records
                                                                );
                                                            }
                                                        } else {
                                                            $result = array(
                                                                'status' => 'info', 'flag' => '4',
                                                                'message' => 'Paramater Missing',
                                                                'data' => (object)array()
                                                            );
                                                        }

                                                    } else {
                                                        if (!empty($data['Action']) && $data['Action'] == 'CancelOrder') {

                                                            if (!empty($data['Val_Type']) && !empty($data['Val_Vendor']) && !empty($data['Val_Order'])) {
                                                                unset($data['Val_Type']);

                                                                $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                if (!empty($VendorData)) {
                                                                    $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                                    if (!empty($CategoryData)) {
                                                                        $BusinessType = $CategoryData->C_Type;
                                                                        if ($BusinessType == '1') {
                                                                            $OrderData = $this->Cart_model->get($data['Val_Order'],
                                                                                array('C_AssignedTo' => $data['Val_Vendor']));
                                                                            if (!empty($OrderData)) {
                                                                                $data['Val_Corderstatus'] = '1';
                                                                                $data['Val_Cassignedto'] = '0';
                                                                                $success = $this->Cart_model->update($data,
                                                                                    $data['Val_Order']);

                                                                                if ($success) {
                                                                                    $CartData = $this->Cart_model->get($data['Val_Order']);

                                                                                    $Record = array(
                                                                                        'OrderID' => getStringValue($CartData->CartID),
                                                                                    );

                                                                                    $result = array(
                                                                                        'status' => 'success',
                                                                                        'flag' => '1',
                                                                                        'message' => 'Order Cancelled Successfully',
                                                                                        'data' => $Record
                                                                                    );

                                                                                } else {
                                                                                    if ($success == false) {
                                                                                        $CartData = $this->Cart_model->get($data['Val_Order']);

                                                                                        $Record = array(
                                                                                            'OrderID' => getStringValue($CartData->CartID),
                                                                                        );

                                                                                        $result = array(
                                                                                            'status' => 'success',
                                                                                            'flag' => '1',
                                                                                            'message' => 'Order is already Cancelled',
                                                                                            'data' => $Record
                                                                                        );
                                                                                    } else {
                                                                                        $result = array(
                                                                                            'status' => 'warning',
                                                                                            'flag' => '3',
                                                                                            'message' => 'Something Important Happened ! ',
                                                                                            'data' => (object)array()
                                                                                        );
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $result = array(
                                                                                    'status' => 'warning',
                                                                                    'flag' => '3',
                                                                                    'message' => 'Something Important Happened !! ',
                                                                                    'data' => (object)array()
                                                                                );
                                                                            }


                                                                        } else {
                                                                            if ($BusinessType == '2') {
                                                                                $OrderData = $this->Cart_model->getProductsCart($data['Val_Order'],
                                                                                    array('PC_AssignedTo' => $data['Val_Vendor']));

                                                                                if (!empty($OrderData)) {
                                                                                    $data['Val_PCorderstatus'] = '1';
                                                                                    $data['Val_PCassignedto'] = null;
                                                                                    $success = $this->Cart_model->updateCartProducts($data,
                                                                                        $data['Val_Order']);

                                                                                    if ($success) {
                                                                                        $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                                                                                        $Record = array(
                                                                                            'OrderID' => getStringValue($CartData->PCartID),
                                                                                        );

                                                                                        $result = array(
                                                                                            'status' => 'success',
                                                                                            'flag' => '1',
                                                                                            'message' => 'Order Cancelled Successfully',
                                                                                            'data' => $Record
                                                                                        );

                                                                                    } else {
                                                                                        if ($success == false) {
                                                                                            $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                                                                                            $Record = array(
                                                                                                'OrderID' => getStringValue($CartData->PCartID),
                                                                                            );

                                                                                            $result = array(
                                                                                                'status' => 'success',
                                                                                                'flag' => '1',
                                                                                                'message' => 'Order is already Cancelled',
                                                                                                'data' => $Record
                                                                                            );
                                                                                        } else {
                                                                                            $result = array(
                                                                                                'status' => 'warning',
                                                                                                'flag' => '3',
                                                                                                'message' => 'Something Important Happened !! ',
                                                                                                'data' => (object)array()
                                                                                            );
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    $result = array(
                                                                                        'status' => 'warning',
                                                                                        'flag' => '3',
                                                                                        'message' => 'Something Important Happened !! ',
                                                                                        'data' => (object)array()
                                                                                    );
                                                                                }
                                                                            } else {
                                                                                if ($BusinessType == '3') {
                                                                                    $OrderData = $this->Cart_model->getRestaurantsCart($data['Val_Order'],
                                                                                        array('RC_AssignedTo' => $data['Val_Vendor']));

                                                                                    if (!empty($OrderData)) {
                                                                                        $data['Val_RCorderstatus'] = '1';
                                                                                        $data['Val_RCassignedto'] = null;
                                                                                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);
                                                                                        if (!empty($CartData)) {
                                                                                            $success = $this->Cart_model->updateCartRestaurants($data,
                                                                                                $data['Val_Order']);

                                                                                            if ($success) {
                                                                                                $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                                                                                                $Record = array(
                                                                                                    'OrderID' => getStringValue($CartData->RCartID),
                                                                                                );

                                                                                                $result = array(
                                                                                                    'status' => 'success',
                                                                                                    'flag' => '1',
                                                                                                    'message' => 'Order Cancelled Successfully',
                                                                                                    'data' => $Record
                                                                                                );


                                                                                            } else {
                                                                                                if ($success == false) {

                                                                                                    $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                                                                                                    $Record = array(
                                                                                                        'OrderID' => getStringValue($CartData->RCartID),
                                                                                                    );


                                                                                                    $result = array(
                                                                                                        'status' => 'success',
                                                                                                        'flag' => '1',
                                                                                                        'message' => 'Order is already Cancelled',
                                                                                                        'data' => $Record
                                                                                                    );
                                                                                                } else {
                                                                                                    $result = array(
                                                                                                        'status' => 'warning',
                                                                                                        'flag' => '3',
                                                                                                        'message' => 'Something Important Happened !! ',
                                                                                                        'data' => (object)array()
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            $result = array(
                                                                                                'status' => 'warning',
                                                                                                'flag' => '3',
                                                                                                'message' => 'Something Important Happened !! ',
                                                                                                'data' => (object)array()
                                                                                            );
                                                                                        }
                                                                                    } else {
                                                                                        $result = array(
                                                                                            'status' => 'warning',
                                                                                            'flag' => '3',
                                                                                            'message' => 'Something Important Happened !! ',
                                                                                            'data' => (object)array()
                                                                                        );
                                                                                    }

                                                                                }
                                                                            }
                                                                        }

                                                                    } else {
                                                                        $result = array(
                                                                            'status' => 'warning', 'flag' => '3',
                                                                            'message' => 'Something Went Wrong...',
                                                                            'data' => (object)$Records
                                                                        );
                                                                    }
                                                                } else {
                                                                    $result = array(
                                                                        'status' => 'warning', 'flag' => '3',
                                                                        'message' => 'Something Went Wrong...',
                                                                        'data' => (object)$Records
                                                                    );
                                                                }
                                                            } else {
                                                                $result = array(
                                                                    'status' => 'info', 'flag' => '4',
                                                                    'message' => 'Paramater Missing',
                                                                    'data' => (object)array()
                                                                );
                                                            }

                                                        } else {
                                                            if (!empty($data['Action']) && $data['Action'] == 'GetReviewsHistory') {

                                                                if (!empty($data['Val_Vendor'])) {
                                                                    $OngoingOrderRecords = array();
                                                                    $PastOrderRecords = array();
                                                                    $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                                    $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                                    $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                    if (!empty($VendorData)) {
                                                                        $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                                        if (!empty($CategoryData)) {
                                                                            $BusinessType = $CategoryData->C_Type;

                                                                            $ReviewsArray = $this->Vendors_model->getReviews(null,
                                                                                array('R_VendorID' => $data['Val_Vendor']));
                                                                            $ReviewsRecordsCount = "0";
                                                                            $ReviewsRecords = array();
                                                                            if (!empty($ReviewsArray)) {
                                                                                $Index = 1;
                                                                                foreach ($ReviewsArray as $ReviewArray) {
//													$ReviewData = (object)$ReviewArray;
                                                                                    $FormattedDate = date('d M,Y',
                                                                                        strtotime($ReviewArray['R_Date']));
                                                                                    $FormattedDateAgo = time_ago($ReviewArray['R_Date'] . " " . $ReviewArray['R_Time']);


                                                                                    $ReviewsRecords[] = array(
                                                                                        'ReviewID' => getStringValue($ReviewArray['ReviewID']),
                                                                                        'Index' => getStringValue($Index),
                                                                                        'Username' => getStringValue($ReviewArray['R_UserName']),
                                                                                        'Color' => getRandomColor(),
                                                                                        'Comment' => getStringValue($ReviewArray['R_Comment']),
                                                                                        'Location' => getStringValue($ReviewArray['R_Location']),
                                                                                        'Rating' => getStringValue($ReviewArray['R_Rating']),
                                                                                        'Date' => getStringValue($FormattedDate),
                                                                                        'Date2' => getStringValue($FormattedDateAgo),
                                                                                    );
                                                                                    $Index++;
                                                                                }
                                                                                $ReviewsRecordsCount = (string)count($ReviewsRecords);
                                                                            }


                                                                            $MiscRecords = array(
                                                                                'NotificationCount' => "1",
                                                                            );


                                                                            $Records['ReviewsCount'] = $ReviewsRecordsCount;
                                                                            $Records['ReviewsData'] = $ReviewsRecords;
                                                                            $Records['MiscData'] = $MiscRecords;
                                                                            $result = array(
                                                                                'status' => 'success', 'flag' => '1',
                                                                                'message' => 'Vendor Reviews Data Fetched',
                                                                                'data' => $Records
                                                                            );


                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'warning', 'flag' => '3',
                                                                                'message' => 'Something Went Wrong...',
                                                                                'data' => (object)$Records
                                                                            );
                                                                        }
                                                                    } else {
                                                                        $result = array(
                                                                            'status' => 'warning', 'flag' => '3',
                                                                            'message' => 'Something Went Wrong...',
                                                                            'data' => (object)$Records
                                                                        );
                                                                    }

                                                                } else {
                                                                    $result = array(
                                                                        'status' => 'info', 'flag' => '4',
                                                                        'message' => 'Paramater Missing'
                                                                    );
                                                                }


                                                            } else {
                                                                if (!empty($data['Action']) && $data['Action'] == 'GetAccountDetails') {

                                                                    if (!empty($data['Val_Vendor'])) {
                                                                        $OngoingOrderRecords = array();
                                                                        $PastOrderRecords = array();
                                                                        $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                                        $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                                        $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                        if (!empty($VendorData)) {
                                                                            $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                                            if (!empty($CategoryData)) {
                                                                                $BusinessType = $CategoryData->C_Type;

                                                                                $AccountArray = $this->Vendors_model->getAccounts(null,
                                                                                    array('A_VendorID' => $data['Val_Vendor']));

                                                                                if (!empty($AccountArray)) {
                                                                                    $Index = 1;

                                                                                    $AccountData = (object)$AccountArray[0];


                                                                                    $AccountRecord = array(
                                                                                        'VendorID' => getStringValue($AccountData->A_VendorID),
                                                                                        'AccountID' => getStringValue($AccountData->VAccountID),
                                                                                        'GSTName' => getStringValue($AccountData->A_GSTName),
                                                                                        'GSTNumber' => getStringValue($AccountData->A_GSTNumber),
                                                                                        'AccountName' => getStringValue($AccountData->A_AccountName),
                                                                                        'AccountType' => getStringValue($AccountData->A_AccountType),
                                                                                        'AccountNumber' => getStringValue($AccountData->A_AccountNumber),
                                                                                        'IFSCNumber' => getStringValue($AccountData->A_IFSCNumber),
                                                                                    );


                                                                                }


                                                                                $MiscRecords = array(
                                                                                    'NotificationCount' => "1",
                                                                                );


                                                                                $Records['AccountsData'] = $AccountRecord;
                                                                                $Records['MiscData'] = $MiscRecords;
                                                                                $result = array(
                                                                                    'status' => 'success',
                                                                                    'flag' => '1',
                                                                                    'message' => 'Vendor Account Data Fetched',
                                                                                    'data' => $Records
                                                                                );


                                                                            } else {
                                                                                $result = array(
                                                                                    'status' => 'warning',
                                                                                    'flag' => '3',
                                                                                    'message' => 'Something Went Wrong...',
                                                                                    'data' => (object)$Records
                                                                                );
                                                                            }
                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'warning', 'flag' => '3',
                                                                                'message' => 'Something Went Wrong...',
                                                                                'data' => (object)$Records
                                                                            );
                                                                        }

                                                                    } else {
                                                                        $result = array(
                                                                            'status' => 'info', 'flag' => '4',
                                                                            'message' => 'Paramater Missing'
                                                                        );
                                                                    }


                                                                } else {
                                                                    if (!empty($data['Action']) && $data['Action'] == 'GetProfileDetails') {


                                                                        if (!empty($data['Val_Vendor']) && $data['Val_Vendor'] != '') {

                                                                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);

                                                                            if ($VendorData) {

                                                                                $ProfileData = $this->Vendors_model->getProfile(null,
                                                                                    array('P_VendorID' => $data['Val_Vendor']));
                                                                                $AboutData = $this->Vendors_model->getAbout(null,
                                                                                    array('A_VendorID' => $data['Val_Vendor']));
                                                                                $WorksData = $this->Vendors_model->getWorks(null,
                                                                                    array('W_VendorID' => $data['Val_Vendor']));
                                                                                $LocationsData = $this->Vendors_model->getLocations(null,
                                                                                    array('L_VendorID' => $data['Val_Vendor']));


                                                                                $result = array(
                                                                                    'status' => 'success',
                                                                                    'flag' => '1',
                                                                                    'message' => 'Vendor Record Fetched',
                                                                                    'data' => $Record
                                                                                );
                                                                            } elseif ($VendorArray === false) {
                                                                                $result = array(
                                                                                    'status' => 'error', 'flag' => '2',
                                                                                    'message' => 'Vendor Record Not Fetched',
                                                                                    'data' => (object)array()
                                                                                );
                                                                            }
                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'info', 'flag' => '4',
                                                                                'message' => 'Parameters Missing.'
                                                                            );
                                                                        }


                                                                        if (!empty($data['Val_Vendor'])) {
                                                                            $OngoingOrderRecords = array();
                                                                            $PastOrderRecords = array();
                                                                            $PastOrderRecordsCount = (string)count($PastOrderRecords);
                                                                            $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
                                                                            $VendorData = $this->Vendors_model->getVendor($data['Val_Vendor']);
                                                                            if (!empty($VendorData)) {
                                                                                $CategoryData = $this->Categories_model->get($VendorData->V_CategoryID);
                                                                                if (!empty($CategoryData)) {
                                                                                    $BusinessType = $CategoryData->C_Type;

                                                                                    $ProfileData = $this->Vendors_model->getProfile(null,
                                                                                        array('P_VendorID' => $data['Val_Vendor']));
                                                                                    $AboutData = $this->Vendors_model->getAbout(null,
                                                                                        array('A_VendorID' => $data['Val_Vendor']));
                                                                                    $WorksData = $this->Vendors_model->getWorks(null,
                                                                                        array('W_VendorID' => $data['Val_Vendor']));
                                                                                    $LocationsData = $this->Vendors_model->getLocations(null,
                                                                                        array('L_VendorID' => $data['Val_Vendor']));


                                                                                    if (!empty($ProfileData)) {
                                                                                        $ProfileData = (object)$ProfileData[0];
                                                                                        $IdentityStatus = $ProfileData->P_IDCardStatus;
                                                                                        $PersonalStatus = $ProfileData->P_PersonalStatus;
                                                                                        $CurrentStatus = $ProfileData->P_CurrentStatus;
                                                                                        $TermsStatus = $ProfileData->P_TermsStatus;

                                                                                    } else {
                                                                                        $IdentityStatus = '';
                                                                                        $PersonalStatus = '';
                                                                                        $CurrentStatus = '';
                                                                                        $TermsStatus = '';
                                                                                    }

                                                                                    if ($IdentityStatus == '2' && $PersonalStatus == '2' && $CurrentStatus == '2' && $TermsStatus == '2') {
                                                                                        $IdentityVerificationStatus = '2';
                                                                                    } else {
                                                                                        $IdentityVerificationStatus = '1';
                                                                                    }


                                                                                    if (!empty($AboutData)) {
                                                                                        $AboutData = (object)$AboutData[0];
                                                                                        $AboutMeStatus = $AboutData->A_Status;

                                                                                    } else {
                                                                                        $AboutMeStatus = '';
                                                                                    }

                                                                                    if (!empty($WorksData)) {
                                                                                        $WorksData = (object)$WorksData[0];
                                                                                        $WorksStatus = $WorksData->W_Status;

                                                                                    } else {
                                                                                        $WorksStatus = '';
                                                                                    }

                                                                                    if (!empty($LocationsData)) {
                                                                                        $LocationsData = (object)$LocationsData[0];
                                                                                        $BusinessLocationStatus = $LocationsData->L_Status;

                                                                                    } else {
                                                                                        $BusinessLocationStatus = '';
                                                                                    }

                                                                                    $VendorFullName = $VendorData->V_FirstName . " " . $VendorData->V_LastName;
                                                                                    $VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL . $VendorData->VendorID . '/' . $VendorData->V_ProfileImage : "");
                                                                                    $ProfileRecord = array(
                                                                                        'VendorID' => getStringValue($VendorData->VendorID),
                                                                                        'FullName' => getStringValue($VendorFullName),
                                                                                        'FirstName' => getStringValue($VendorData->V_FirstName),
                                                                                        'LastName' => getStringValue($VendorData->V_LastName),
                                                                                        'CountryCode' => getStringValue($VendorData->V_CountryCode),
                                                                                        'MobileNumber' => getStringValue($VendorData->V_Mobile),
                                                                                        'EmailAddress' => getStringValue($VendorData->V_Email),
                                                                                        'Latitude' => getStringValue($VendorData->V_Latitude),
                                                                                        'Longitude' => getStringValue($VendorData->V_Longitude),
                                                                                        'Address' => getStringValue($VendorData->V_Address),
                                                                                        'Location' => getStringValue($VendorData->V_Location),
                                                                                        'City' => getStringValue($VendorData->V_City),
                                                                                        'Country' => getStringValue($VendorData->V_Country),
                                                                                        'ProfileImage' => $VendorProfileImage,
//											'ProfileStatus' 	=> $VendorData->V_ProfileStatus,
//											'VerificationStatus' 	=> $VendorData->V_VerificationStatus,
//											'VerificationMessage' 	=> getStringValue($VendorData->V_VerificationMessage),
                                                                                        //'IdentityVerificationStatus'	=> $IdentityVerificationStatus,
//											'AboutMeStatus'					=> $AboutMeStatus,
//											'WorksStatus'					=> $WorksStatus,
//											'BusinessLocationStatus'		=> $BusinessLocationStatus,
                                                                                    );


                                                                                    $MiscRecords = array(
                                                                                        'NotificationCount' => "1",
                                                                                    );


                                                                                    $Records['ProfileData'] = $ProfileRecord;
                                                                                    $Records['MiscData'] = $MiscRecords;
                                                                                    $result = array(
                                                                                        'status' => 'success',
                                                                                        'flag' => '1',
                                                                                        'message' => 'Vendor Profile Data Fetched',
                                                                                        'data' => $Records
                                                                                    );


                                                                                } else {
                                                                                    $result = array(
                                                                                        'status' => 'warning',
                                                                                        'flag' => '3',
                                                                                        'message' => 'Something Went Wrong...',
                                                                                        'data' => (object)$Records
                                                                                    );
                                                                                }
                                                                            } else {
                                                                                $result = array(
                                                                                    'status' => 'warning',
                                                                                    'flag' => '3',
                                                                                    'message' => 'Something Went Wrong...',
                                                                                    'data' => (object)$Records
                                                                                );
                                                                            }

                                                                        } else {
                                                                            $result = array(
                                                                                'status' => 'info', 'flag' => '4',
                                                                                'message' => 'Paramater Missing'
                                                                            );
                                                                        }


                                                                    } else {


                                                                        $result = array(
                                                                            'status' => 'info', 'flag' => '4',
                                                                            'message' => 'Paramater Missing'
                                                                        );


                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->data = $result;
        echo json_encode($this->data);


        return false;
    }

}


?>