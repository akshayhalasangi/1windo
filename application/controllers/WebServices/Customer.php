<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customer extends W_Controller
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

        if (!empty($data['Action']) && $data['Action'] == 'GetAllCustomers') {

            $CustomersArray = $this->Customers_model->get();

            if ($CustomersArray) {

                foreach ($CustomersArray as $CustomerArray) {

                    $CustomerFullName = $CustomerArray['C_FirstName'] . " " . $CustomerArray['C_LastName'];
                    $CustomerProfileImage = UPLOAD_CUSTOMER_BASE_URL . $CustomerArray['CustomerID'] . '/' . $CustomerArray['C_ProfileImage'];
                    $Records[] = array(
                        'TechnicianID' => $CustomerArray['CustomerID'],
                        'FullName' => $CustomerFullName,
                        'FirstName' => $CustomerArray['C_FirstName'],
                        'LastName' => $CustomerArray['C_LastName'],
                        'MobileNumber' => $CustomerArray['C_Mobile'],
                        'EmailAddress' => $CustomerArray['C_Email'],
                        'ProfileImage' => $CustomerProfileImage,
                        'Status' => $CustomerArray['C_Status'],
                    );

                }

                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customers Records Fetched', 'data' => $Records);
            } elseif ($TechsArray === false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => 'No entry found.');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'SingleCustomer') {

            if (!empty($data['Val_Customer']) && $data['Val_Customer'] != '') {

                $CustomerData = $this->Customers_model->get($data['Val_Customer']);

                if ($CustomerData) {

                    $CustomerJobs = $this->Jobs_model->getJoined(null, array(TBL_JOBS . '.CustomerID' => $data['Val_Customer']), false);

                    if (!empty($CustomerJobs)) {
                        $TotalJobs = (string) count($CustomerJobs);
                    } else {
                        $TotalJobs = (string) 0;
                    }

                    $CustomerFullName = $CustomerData->C_FirstName . " " . $CustomerData->C_LastName;
                    $CustomerProfileImage = (!empty($CustomerData->C_ProfileImage) ? UPLOAD_CUSTOMER_BASE_URL . $CustomerData->CustomerID . '/' . $CustomerData->C_ProfileImage : null);
                    $Records[] = array(
                        'CustomerID' => $CustomerData->CustomerID,
                        'FullName' => $CustomerFullName,
                        'FirstName' => $CustomerData->C_FirstName,
                        'LastName' => $CustomerData->C_LastName,
                        'Mobile' => $CustomerData->C_Mobile,
                        'Email' => $CustomerData->C_Email,
                        'Address' => $CustomerData->C_Address,
                        'ProfileImage' => $CustomerProfileImage,
                        'TotalJobs' => $TotalJobs,
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customer Record Fetched', 'data' => $Records);
                } elseif ($CustomerArray === false) {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Customer Record Not Fetched', 'data' => $data['Val_Customer']);
                }
            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameters Missing.');
            }
        } else {
            $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
        }

        $this->data = $result;
        echo json_encode($this->data);
    }

    // Edit Profile Of Customer ANd Service Provider
    public function Profile()
    {
        $data = $this->input->post();
        $Record = array();
        if (!empty($data) && $data['Action'] == 'Update') {
            if (!empty($data['Val_Customer'])) {

                $success = $this->Customers_model->update($data, $data['Val_Customer']);

                $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);

                if ($CustomerData) {
                    $data['Val_Relation'] = $data['Val_Customer'];
//                            $Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');

                    $CustomerFullName = $CustomerData->C_FirstName . " " . $CustomerData->C_LastName;
                    $CustomerProfileImage = (!empty($CustomerData->C_ProfileImage) ? UPLOAD_CUSTOMER_BASE_URL . $CustomerData->CustomerID . '/' . $CustomerData->C_ProfileImage : null);

                    $Record = array(
                        'CustomerID' => getStringValue($CustomerData->CustomerID),
                        'FullName' => getStringValue($CustomerFullName),
                        'FirstName' => getStringValue($CustomerData->C_FirstName),
                        'LastName' => getStringValue($CustomerData->C_LastName),
                        'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                        'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                        'EmailAddress' => getStringValue($CustomerData->C_Email),
                    );
                }

//                    if ($success || $Asuccess) {}
                if ($success) {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customer Profile Updated Successfully', 'data' => $Record);
                } else if ($success == false && $Asuccess == false) {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Customer Profile Not Updated', 'data' => $data['Val_Customer']);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important', 'data' => $success);
                }
            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing...', 'data' => $Record);
            }

        } else if (!empty($data) && $data['Action'] == 'UpdateDetails') {

            $success = $this->Customers_model->update($data, $data['Val_Customer']);
            $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);

            if ($CustomerData) {
                $data['Val_Relation'] = $data['Val_Customer'];

                $CustomerFullName = $CustomerData->C_FirstName . " " . $CustomerData->C_LastName;
                //$CustomerProfileImage = (!empty($CustomerData->C_ProfileImage) ? UPLOAD_CUSTOMER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage : NULL);
                $Record = array(
                    'CustomerID' => getStringValue($CustomerData->CustomerID),
                    'FullName' => getStringValue($CustomerFullName),
                    'FirstName' => getStringValue($CustomerData->C_FirstName),
                    'LastName' => getStringValue($CustomerData->C_LastName),
                    'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                    'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                    'EmailAddress' => getStringValue($CustomerData->C_Email),
                    'Latitude' => getStringValue($CustomerData->C_Latitude),
                    'Longitude' => getStringValue($CustomerData->C_Longitude),
                    'Address' => getStringValue($CustomerData->C_Address),
                    'Location' => getStringValue($CustomerData->C_Location),
                    //        'ProfileImage'=> $CustomerProfileImage,
                );
            }

            if ($success) {
                $result = array('status' => 'success', 'flag' => '1', 'message' => _l('msg_location_updated_success', _l('user_customer')), 'data' => $Record);
            } else if ($success == false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => _l('msg_location_updated_fail', _l('user_customer')), 'data' => $data['Val_Customer']);
            } else {
                $result = array('status' => 'warning', 'flag' => '3', 'message' => _l('msg_something_went_wrong'), 'data' => $success);
            }

        } else if (!empty($data) && $data['Action'] == 'UpdateLocation') {

            $success = $this->Customers_model->update($data, $data['Val_Customer']);
            $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);

            if ($CustomerData) {
                $data['Val_Relation'] = $data['Val_Customer'];

                $AddressArray = $this->Customers_model->getAddresses(null, array('A_RelationID' => $CustomerData->CustomerID, 'A_Type' => '1'));

                $AddressRecords = array();
                $AddressCount = (string) count($AddressRecords);
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
                $AddressCount = (string) count($AddressRecords);
                $AddressData = $AddressRecords;

                $CustomerFullName = $CustomerData->C_FirstName . " " . $CustomerData->C_LastName;
                $OTPResponse = "";
                //$CustomerProfileImage = (!empty($CustomerData->C_ProfileImage) ? UPLOAD_CUSTOMER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage : NULL);
                $Record = array(
                    'CustomerID' => getStringValue($CustomerData->CustomerID),
                    'FullName' => getStringValue($CustomerFullName),
                    'FirstName' => getStringValue($CustomerData->C_FirstName),
                    'LastName' => getStringValue($CustomerData->C_LastName),
                    'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                    'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                    'EmailAddress' => getStringValue($CustomerData->C_Email),
                    'Latitude' => getStringValue($CustomerData->C_Latitude),
                    'Longitude' => getStringValue($CustomerData->C_Longitude),
                    'Address' => getStringValue($CustomerData->C_Address),
                    'AddressCount' => $AddressCount,
                    'AddressData' => $AddressData,
                    'Location' => getStringValue($CustomerData->C_Location),
                    'OTPCode' => getStringValue($OTPResponse),
                    //        'ProfileImage'=> $CustomerProfileImage,
                );
            }

            if ($success) {
                $result = array('status' => 'success', 'flag' => '1', 'message' => _l('msg_location_updated_success', _l('user_customer')), 'data' => $Record);
            } else if ($success == false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => _l('msg_location_updated_fail', _l('user_customer')), 'data' => $data['Val_Customer']);
            } else {
                $result = array('status' => 'warning', 'flag' => '3', 'message' => _l('msg_something_went_wrong'), 'data' => $success);
            }

        } else if (!empty($data) && $data['Action'] == 'AddAddress') {

            if (!empty($data['Val_Customer']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])) {

                $data['Val_Type'] = '1';
                $data['Val_Relation'] = $data['Val_Customer'];
                unset($data['Val_Customer']);
                $success = $this->Customers_model->addAddress($data);

                $address_id = $success;
                $Record = array();
                if ($success) {
                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Relation']);
                    $AddressArray = $this->Customers_model->getAddresses(null, array('A_RelationID' => $data['Val_Relation'], 'A_Type' => '1'));

                    $AddressRecords = array();
                    $AddressCount = (string) count($AddressRecords);
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
                    $AddressCount = (string) count($AddressRecords);
                    $AddressData = $AddressRecords;
                    $OTPResponse = "";

                    $CustomerFullName = getStringValue($CustomerData->C_FirstName) . " " . getStringValue($CustomerData->C_LastName);
                    $Record = array(
                        'CustomerID' => getStringValue($CustomerData->CustomerID),
                        'FullName' => getStringValue($CustomerFullName),
                        'FirstName' => getStringValue($CustomerData->C_FirstName),
                        'LastName' => getStringValue($CustomerData->C_LastName),
                        'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                        'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                        'EmailAddress' => getStringValue($CustomerData->C_Email),
                        'Latitude' => getStringValue($CustomerData->C_Latitude),
                        'Longitude' => getStringValue($CustomerData->C_Longitude),
                        'Address' => getStringValue($CustomerData->C_Address),
                        'AddressCount' => $AddressCount,
                        'AddressData' => $AddressData,
                        'Location' => getStringValue($CustomerData->C_Location),
                        'OTPCode' => getStringValue($OTPResponse),
                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Address Added Successfully', 'data' => $Record);
                } else if ($success == false) {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'We couldn\'t add address. Please try again later.(404)', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $Record);
                }

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing');
            }

        } else if (!empty($data) && $data['Action'] == 'UpdateAddress') {

            if (!empty($data['Val_Customer']) && !empty($data['Val_Address']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])) {

                $data['Val_Type'] = '1';
                $data['Val_Relation'] = $data['Val_Customer'];
                unset($data['Val_Customer']);
                $success = $this->Customers_model->updateAddress($data, $data['Val_Address']);
                $CustomerData = $this->Customers_model->getCustomer($data['Val_Relation']);
                $AddressData = $this->Customers_model->getAddresses($data['Val_Address']);
                $Record = array();
                if (!empty($CustomerData) && !empty($AddressData)) {

                    $AddressArray = $this->Customers_model->getAddresses(null, array('A_RelationID' => $CustomerData->CustomerID));

                    $AddressRecords = array();
                    $AddressCount = (string) count($AddressRecords);
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
                    $AddressCount = (string) count($AddressRecords);
                    $AddressData = $AddressRecords;

                    $OTPResponse = "";

                    $CustomerFullName = $CustomerData->C_FirstName . " " . $CustomerData->C_LastName;
                    //$CustomerProfileImage = (!empty($CustomerData->C_ProfileImage) ? UPLOAD_CUSTOMER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage : NULL);
                    $Record = array(
                        'CustomerID' => getStringValue($CustomerData->CustomerID),
                        'FullName' => getStringValue($CustomerFullName),
                        'FirstName' => getStringValue($CustomerData->C_FirstName),
                        'LastName' => getStringValue($CustomerData->C_LastName),
                        'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                        'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                        'EmailAddress' => getStringValue($CustomerData->C_Email),
                        'Latitude' => getStringValue($CustomerData->C_Latitude),
                        'Longitude' => getStringValue($CustomerData->C_Longitude),
                        'Address' => getStringValue($CustomerData->C_Address),
                        'AddressCount' => $AddressCount,
                        'AddressData' => $AddressData,
                        'Location' => getStringValue($CustomerData->C_Location),
                        'OTPCode' => getStringValue($OTPResponse),
                        //        'ProfileImage'=> $CustomerProfileImage,
                    );
                }

                if ($success) {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => _l('msg_location_updated_success', _l('user_customer')), 'data' => $Record);
                } else if ($success == false) {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => _l('msg_location_updated_fail', _l('user_customer')), 'data' => $data['Val_Customer']);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => _l('msg_something_went_wrong'), 'data' => $success);
                }
            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing');
            }
        } else if (!empty($data) && $data['Action'] == 'DeleteAddress') {

            $success = $this->Customers_model->deleteAddress($data['Val_Address']);

            $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
            $AddressArray = $this->Customers_model->getAddresses(null, array('A_RelationID' => $data['Val_Customer'], 'A_Type' => '1'));

            $AddressRecords = array();
            $AddressCount = (string) count($AddressRecords);
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
            $AddressCount = (string) count($AddressRecords);
            $AddressData = $AddressRecords;
            $OTPResponse = "";

            $CustomerFullName = getStringValue($CustomerData->C_FirstName) . " " . getStringValue($CustomerData->C_LastName);
            $Record = array(
                'CustomerID' => getStringValue($CustomerData->CustomerID),
                'FullName' => getStringValue($CustomerFullName),
                'FirstName' => getStringValue($CustomerData->C_FirstName),
                'LastName' => getStringValue($CustomerData->C_LastName),
                'CountryCode' => getStringValue($CustomerData->C_CountryCode),
                'MobileNumber' => getStringValue($CustomerData->C_Mobile),
                'EmailAddress' => getStringValue($CustomerData->C_Email),
                'Latitude' => getStringValue($CustomerData->C_Latitude),
                'Longitude' => getStringValue($CustomerData->C_Longitude),
                'Address' => getStringValue($CustomerData->C_Address),
                'AddressCount' => $AddressCount,
                'AddressData' => $AddressData,
                'Location' => getStringValue($CustomerData->C_Location),
                'OTPCode' => getStringValue($OTPResponse),
                //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                //        'Status'=> getStatus($CustomerData->C_Status),
            );
            if ($success) {
                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customer Address Deleted Successfully', 'data' => $Record);
            } else if ($success == false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => 'Customer Address Not Deleted', 'data' => $Record);
            } else {
                $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important', 'data' => $success);
            }

        } else if (!empty($data) && $data['Action'] == 'Delete') {

            $success = $this->Customers_model->delete($data['Val_Customer']);

            if ($success) {
                $Adata['Val_Relation'] = $data['Val_Customer'];
                $Adata['Val_Status'] = '1';
                $Asuccess = $this->Authentication_model->AppUpdate($Adata, $Adata['Val_Relation'], '1');

            }

            if ($success || $Asuccess) {
                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customer Profile Deleted Successfully', 'data' => 'Confidential');
            } else if ($success == false && $Asuccess == false) {
                $result = array('status' => 'error', 'flag' => '2', 'message' => 'Customer Profile Not Deleted', 'data' => $data['Val_Customer']);
            } else {
                $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important', 'data' => $success);
            }

        } else {
            $result = array('status' => 'info', 'flag' => '4', 'message' => 'Parameter Missing');
        }
        $this->data = $result;
        echo json_encode($this->data);
    }

    // Edit Profile Of Customer ANd Service Provider
    public function Dashboard()
    {
        $Record = array();
        $data = $this->input->post();

        if (!empty($data['Action']) && $data['Action'] == 'GetData') {

            $groceryName='Groceries'; 
            $vegetableName='Vegetables';

            $GroceriesArray=$this->Categories_model->getDataOne($groceryName);
            $VegetablesArray=$this->Categories_model->getDataTwo($vegetableName);

            // $GroceriesArray=$this->Categories_model->get(null, array('C_Level' => '1', 'C_Type' => '2'), "ASC");
            // $VegetablesArray=$this->Categories_model->get(null, array('C_Level' => '1', 'C_Type' => '2'), "ASC");

            $CategoriesArray = $this->Categories_model->getAppCategory(null, array('C_Level' => '1', 'C_Type' => '1'), "ASC");
            $ProductCategoriesArray = $this->Categories_model->getAppCategory(null, array('C_Level' => '1', 'C_Type' => '2'), "ASC");
            $FeaturedServicesArray = $this->Categories_model->getAppCategory(null, array('C_Featured' => '2'), "DESC");
            $FeaturedProductsArray = $this->Products_model->get(null, array('P_Featured' => '2'), "ASC");
//                $ServicesArray = $this->Services_model->get(NULL,array('S_Type'=>'1'));

            if (!empty($GroceriesArray)) {
                foreach ($GroceriesArray as $GroceriesArrayData) {
                    $DisplayImage = '';
                    $DisplayImage = (!empty($GroceriesArrayData['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL . $GroceriesArrayData['CategoryID'] . '/' . $GroceriesArrayData['C_DisplayImage'] : '');
                    $DisplayIcon = '';
                    $DisplayIcon = (!empty($GroceriesArrayData['C_DisplayIcon']) ? UPLOAD_CATEGORIES_BASE_URL . $GroceriesArrayData['CategoryID'] . '/' . $GroceriesArrayData['C_DisplayIcon'] : '');

                    $GroceriesData[] = array(
                                        'CategoryID' => $GroceriesArrayData['CategoryID'],
                                        'Name' =>$GroceriesArrayData['C_Name'],
                                        'DisplayImage' => $DisplayImage,
                                        'DisplayIcon' => $DisplayIcon,
                                        );
                }   
            } else {
                $GroceriesData= array();
            }


            if (!empty($VegetablesArray)) {
                foreach ($VegetablesArray as $VegetablesArrayData) {
                    $DisplayImage = '';
                    $DisplayImage = (!empty($VegetablesArrayData['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL . $VegetablesArrayData['CategoryID'] . '/' . $VegetablesArrayData['C_DisplayImage'] : '');
                    $DisplayIcon = '';
                    $DisplayIcon = (!empty($VegetablesArrayData['C_DisplayIcon']) ? UPLOAD_CATEGORIES_BASE_URL . $VegetablesArrayData['CategoryID'] . '/' . $VegetablesArrayData['C_DisplayIcon'] : '');

                    $VegetablesData[] = array(
                                        'CategoryID' => $VegetablesArrayData['CategoryID'],
                                        'Name' =>$VegetablesArrayData['C_Name'],
                                        'DisplayImage' => $DisplayImage,
                                        'DisplayIcon' => $DisplayIcon,
                                        );
                }   
            } else {
                $VegetablesData= array();
            }




            if (!empty($CategoriesArray)) {

                foreach ($CategoriesArray as $CategoryArray) {

                    $DisplayImage = '';
                    $DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayImage'] : '');
                    $DisplayIcon = '';
                    $DisplayIcon = (!empty($CategoryArray['C_DisplayIcon']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayIcon'] : '');

                    $ServiceCategoriesData[] = array(
                        'CategoryID' => $CategoryArray['CategoryID'],
                        'Name' => $CategoryArray['C_Name'],
                        'DisplayImage' => $DisplayImage,
                        'DisplayIcon' => $DisplayIcon,
                    );
            
                }
                $ServiceCategoriesData[] = array(
                    'Name' => "See More",
                    'Image' => $DisplayIcon,
                );
                //$CategoryData['CategoriesCount']     = (string)count($CategoryRecords);
                //                        $CategoryData['CategoriesData']     = $CategoryRecords;
                //                        $result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$CategoryData);

            } else {
                $ServiceCategoriesData = array();
            }
/*                $ServiceCategoriesData     =  array(
array( 'CategoryID' => "1",'CategoryName' => "1Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'1/product.png') ,
array( 'CategoryID' => "2",'CategoryName' => "2Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'2/product.png') ,
array( 'CategoryID' => "3",'CategoryName' => "3Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'3/product.png') ,
array( 'CategoryID' => "4",'CategoryName' => "4Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'4/product.png') ,
array( 'CategoryID' => "5",'CategoryName' => "5Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'5/product.png') ,
array( 'CategoryID' => "6",'CategoryName' => "6Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'6/product.png') ,
array( 'CategoryID' => "7",'CategoryName' => "7Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'7/product.png') ,
array( 'CategoryID' => "8",'CategoryName' => "8Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'8/product.png') ,
array( 'CategoryID' => "9",'CategoryName' => "9Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'9/product.png') ,
array( 'CategoryID' => "10",'CategoryName' => "10Beauty",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'10/product.png'),
);*/
            if (!empty($ProductCategoriesArray)) {

                foreach ($ProductCategoriesArray as $CategoryArray) {
                    $DisplayImage = '';
                    $DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayImage'] : '');

                    $DisplayIcon = '';
                    $DisplayIcon = (!empty($CategoryArray['C_DisplayIcon']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayIcon'] : '');

                    $ProductCategoriesData[] = array(
                        'CategoryID' => $CategoryArray['CategoryID'],
                        'Name' => $CategoryArray['C_Name'],
                        'DisplayImage' => $DisplayImage,
                        'DisplayIcon' => $DisplayIcon,
                    );

                }
                $ProductCategoriesData[] = array(
                    'Name' => "See More",
                    'Image' => $DisplayIcon,
                );
                //$CategoryData['CategoriesCount']     = (string)count($CategoryRecords);
                //                        $CategoryData['CategoriesData']     = $CategoryRecords;
                //                        $result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$CategoryData);

            } else {
                $ProductCategoriesData = array();
            }
            /*$ProductCategoriesData     =  array(
            array( 'CategoryID' => "1",'Name' => "1Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'1/product.png') ,
            array( 'CategoryID' => "2",'Name' => "2Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'2/product.png') ,
            array( 'CategoryID' => "3",'Name' => "3Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'3/product.png') ,
            array( 'CategoryID' => "4",'Name' => "4Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'4/product.png') ,
            array( 'CategoryID' => "5",'Name' => "5Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'5/product.png') ,
            array( 'CategoryID' => "6",'Name' => "6Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'6/product.png') ,
            array( 'CategoryID' => "7",'Name' => "7Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'7/product.png') ,
            array( 'CategoryID' => "8",'Name' => "8Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'8/product.png') ,
            array( 'CategoryID' => "9",'Name' => "9Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'9/product.png') ,
            array( 'CategoryID' => "10",'Name' => "10Product",'DisplayIcon' => UPLOAD_CATEGORIES_BASE_URL.'10/product.png') ,
            );
             */

            /*
            1 - Service
            2 - Product
            3 - Food
             */
            $SliderData = array(
                array('SliderID' => "1", 'Type' => "1", 'RelationID' => "1", 'DisplayImage' => UPLOAD_SLIDERS_BASE_URL . '1/banner_offer.jpg'),
                array('SliderID' => "2", 'Type' => "2", 'RelationID' => "1", 'DisplayImage' => UPLOAD_SLIDERS_BASE_URL . '2/banner_offer.jpg'),
                array('SliderID' => "3", 'Type' => "3", 'RelationID' => "1", 'DisplayImage' => UPLOAD_SLIDERS_BASE_URL . '3/banner_offer.jpg'),
            );

            if (!empty($FeaturedServicesArray)) {

                foreach ($FeaturedServicesArray as $CategoryArray) {

                    $DisplayImage = '';
                    $DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayImage'] : '');
                    $DisplayIcon = '';
                    $DisplayIcon = (!empty($CategoryArray['C_DisplayIcon']) ? UPLOAD_CATEGORIES_BASE_URL . $CategoryArray['CategoryID'] . '/' . $CategoryArray['C_DisplayIcon'] : '');

                    $FeaturedServicesData[] = array(
                        'CategoryID' => $CategoryArray['CategoryID'],
                        'Name' => $CategoryArray['C_Name'],
                        'Description' => "Description Text",
                        'DisplayImage' => $DisplayImage,
                    );

                }
                //$CategoryData['CategoriesCount']     = (string)count($CategoryRecords);
                //                        $CategoryData['CategoriesData']     = $CategoryRecords;
                //                        $result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$CategoryData);

            } else {
                $FeaturedServicesData = array();
            }

/*                $FeaturedServicesData     =  array(
array( 'CategoryID' => "1",'Name' => "1Beauty",'Description' => "50% off",'DisplayImage' => UPLOAD_CATEGORIES_BASE_URL.'1/p1.jpg') ,
array( 'CategoryID' => "2",'Name' => "2Product",'Description' => "50% off",'DisplayImage' => UPLOAD_CATEGORIES_BASE_URL.'2/p1.jpg') ,
array( 'CategoryID' => "3",'Name' => "3Product",'Description' => "50% off",'DisplayImage' => UPLOAD_CATEGORIES_BASE_URL.'3/p1.jpg') ,
array( 'CategoryID' => "4",'Name' => "4Beauty",'Description' => "50% off",'DisplayImage' => UPLOAD_CATEGORIES_BASE_URL.'4/p1.jpg') ,
);

 */
            if (!empty($FeaturedProductsArray)) {

                foreach ($FeaturedProductsArray as $ProductArray) {

                    $ProductReviews = $this->Products_model->getReviews(null, array('R_Type' => '2', 'R_RelationID' => $ProductArray['ProductID']));
                    if (!empty($ProductReviews)) {
                        $ReviewsRecords = array();
                        $Index = 1;
                        foreach ($ProductReviews as $Review) {
                            if ($Index <= 5) {
                                $FormattedDate = date('d M,Y', strtotime($Review['R_Date']));
                                $FormattedDateAgo = time_ago($Review['R_Date'] . " " . $Review['R_Time']);

                                array_push($ReviewsRecords, array(
                                    'ReviewID' => getStringValue($Review['ReviewID']),
                                    'Index' => getStringValue($Index),
                                    'Username' => getStringValue($Review['R_UserName']),
                                    'Color' => getRandomColor(),
                                    'Comment' => getStringValue($Review['R_Comment']),
                                    'Location' => getStringValue($Review['R_Location']),
                                    'Rating' => getStringValue($Review['R_Rating']),
                                    'Date' => getStringValue($FormattedDate),
                                    'Date2' => getStringValue($FormattedDateAgo),
                                )
                                );
                            }

                            $Index++;
                        }

                        $ReviewsTempCount = (string) count($ReviewsRecords);
                        $ReviewsCount = (string) count($ProductReviews);
                    } else {
                        $ReviewsRecords = array();
                        $ReviewsCount = "0";
                    }

                    if (!empty($ProductArray['P_Attributes'])) {
                        $ProductAttribute = $this->Products_model->getAttributes($ProductArray['P_Attributes']);

                        if (!empty($ProductAttribute)) {
                            $AttributeType = $ProductAttribute->A_Type;
                        } else {
                            $AttributeType = "3";
                        }
                    } else {
                        $AttributeType = "3";
                    }

                    $ProductAttribValuesArray = getAttribValuesArray($ProductArray['P_AttributeValues']);

                    $FeaturedImage = '';
                    $FeaturedImage = (!empty($ProductArray['P_FeaturedImage']) ? UPLOAD_PRODUCTS_BASE_URL . $ProductArray['ProductID'] . '/' . $ProductArray['P_FeaturedImage'] : '');

                    $GalleryImages = json_decode($ProductArray['P_Gallery']);
                    if (!empty($GalleryImages)) {
                        foreach ($GalleryImages as &$value) {
                            $value = UPLOAD_PRODUCTS_BASE_URL . $ProductArray['ProductID'] . '/' . trim($value);
                        }
                    } else {
                        $GalleryImages = array();
                    }
                    $GalleryCount = (string) count($GalleryImages);

                    $FeaturedProductsData[] = array(
                        'ProductID' => getStringValue($ProductArray['ProductID']),
                        'Name' => getStringValue($ProductArray['P_Name']),
                        'Description' => getStringValue($ProductArray['P_Description']),
                        'Currency' => getStringValue("Rs. "),
                        'Price' => getStringValue($ProductArray['P_Price']),
                        'FeaturedImage' => $FeaturedImage,
                        'GalleryCount' => $GalleryCount,
                        'GalleryImages' => $GalleryImages,
                        'ReviewsCount' => $ReviewsCount,
                        'ReviewsData' => $ReviewsRecords,
                        'AttributeType' => getStringValue($AttributeType),
                        'AttributesCount' => $ProductAttribValuesArray['ValuesCount'],
                        'AttributesData' => $ProductAttribValuesArray['ValuesData'],

                    );

                }

            } else {
                $FeaturedProductsData = array();
            }
/*                $FeaturedProductsData     =  array(
array( 'ProductID' => "1",'Name' => "1Maybelline",'DisplayImage' => UPLOAD_PRODUCTS_BASE_URL.'1/p3.jpg') ,
array( 'ProductID' => "2",'Name' => "2Maybelline",'DisplayImage' => UPLOAD_PRODUCTS_BASE_URL.'2/p3.jpg') ,
array( 'ProductID' => "3",'Name' => "3Maybelline",'DisplayImage' => UPLOAD_PRODUCTS_BASE_URL.'3/p3.jpg') ,
array( 'ProductID' => "4",'Name' => "4Maybelline",'DisplayImage' => UPLOAD_PRODUCTS_BASE_URL.'4/p3.jpg') ,
);
 */

            $MiscRecords = array(
                'NotificationCount' => "1",
            );


            $Records['GroceriesData'] = $GroceriesData;
            $Records['VegetablesData'] = $VegetablesData;
            $Records['ServiceCategoriesData'] = $ServiceCategoriesData;
            //$Records['ServiceCategoriesData'] =$ServiceCategoriesDataNew;
            $Records['ProductCategoriesData'] = $ProductCategoriesData;
            $Records['SliderData'] = $SliderData;
            $Records['FeaturedServicesData'] = $FeaturedServicesData;
            $Records['FeaturedProductsData'] = $FeaturedProductsData;
            $Records['MiscData'] = $MiscRecords;

            //$Records['EventsData'] = (!empty($EventRecords) ? $EventRecords : (object)$EventRecords) ;
            //                $Records['MiscData'] = $MiscRecords;

            $result = array('status' => 'success', 'flag' => '1', 'message' => 'Customer Dashboard Data Fetched', 'data' => $Records);

        } else if (!empty($data['Action']) && $data['Action'] == 'SearchData') {

            if (!empty($data['Val_Search']) && !empty(trim($data['Val_Search']))) {
                $Start = $this->Val_Start = 0;
                $Limit = $this->Val_Limit = 999999;

                $ServiceSearchRequestData['Val_Search'] = $data['Val_Search'];
                $ServiceSearchRequestData['Val_Start'] = '0';
                $ServiceSearchRequestData['Val_Limit'] = '999999';

                $ProductSearchRequestData['Val_Search'] = $data['Val_Search'];
                $ProductSearchRequestData['Val_Start'] = '0';
                $ProductSearchRequestData['Val_Limit'] = '999999';

                $RestaurantSearchRequestData['Val_Search'] = $data['Val_Search'];
                $RestaurantSearchRequestData['Val_Start'] = '0';
                $RestaurantSearchRequestData['Val_Limit'] = '999999';

                $ServicesArray = $this->Services_model->search($ServiceSearchRequestData);
                $ProductsArray = $this->Products_model->search($ProductSearchRequestData);
                $RestaurantsArray = $this->Restaurants_model->search($RestaurantSearchRequestData);

                $SearchRecords = array();

                if (!empty($ServicesArray)) {
                    foreach ($ServicesArray as $ServiceArray) {

                        $SearchRecords[] = array(
                            'Type' => '1',
                            'RelationID' => $ServiceArray['ServiceID'],
                            'RelationName' => $ServiceArray['S_Name'],

                        );

                    }

                }

                if (!empty($ProductsArray)) {
                    foreach ($ProductsArray as $ProductArray) {

                        $SearchRecords[] = array(
                            'Type' => '2',
                            'RelationID' => $ProductArray['ProductID'],
                            'RelationName' => $ProductArray['P_Name'],

                        );

                    }
                }

                if (!empty($RestaurantsArray)) {
                    foreach ($RestaurantsArray as $RestaurantArray) {

                        $SearchRecords[] = array(
                            'Type' => '3',
                            'RelationID' => $RestaurantArray['RestaurantID'],
                            'RelationName' => $RestaurantArray['R_Name'],

                        );

                    }
                }

                $SearchData['SearchCount'] = (string) count($SearchRecords);
                $SearchData['SearchData'] = $SearchRecords;

                if (!empty($ServicesArray) || !empty($ProductsArray) || !empty($RestaurantsArray)) {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Search Data Fetched', 'data' => $SearchData);
                } else if (empty($ServicesArray) && empty($ProductsArray) && empty($RestaurantsArray)) {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'No Data Found.', 'data' => (object) array());
                } else {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'No Data Found.', 'data' => (object) array());
                }
            } else {
                $result = array('status' => 'error', 'flag' => '2', 'message' => 'Parameter Missing.', 'data' => (object) array());
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'AddToCart') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Customer']) && !empty($data['Val_Option']) && !empty($data['Val_Address']) && !empty($data['Val_Date']) && !empty($data['Val_Timeslab'])) {
                
                $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
              
                $AddressData = $this->Customers_model->getAddresses($data['Val_Address']);
               
                $CustomerFullName = getStringValue($AddressData->A_Name);
                $CustomerAddress = array(getStringValue($CustomerData->C_CountryCode . $CustomerData->C_Mobile), getStringValue($AddressData->A_Address), getStringValue($AddressData->A_Location), getStringValue($AddressData->A_Latitude), getStringValue($AddressData->A_Longitude));

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
                $PostData['Val_Customer'] = $data['Val_Customer'];
                $PostData['Val_Ccustomername'] = $CustomerFullName;
                $PostData['Val_Ccustomeraddress'] = json_encode($CustomerAddress);
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
                $PostData['Val_Corderstatus'] = '0';
                $PostData['Val_Cstatus'] = '1';
                // print-r($PostData);
                // exit;

                $success = $this->Cart_model->add($PostData);

                if ($success) {
                    $CartID = $success;
                    $CartData = $this->Cart_model->getCartData($CartID);

                    $OptionsCount = "0";
                    $OptionsData = array();

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
                    $OptionsCount = (string) count($OptionsData);
                    // $CardIndexAt0= $CartData[0]['CartID'];
                    // $CartIDofData= $CartData[0]['C_Total'];

                    $Record = array(
                        'CartID' => getStringValue($CartData[0]['CartID']),
                        'OptionsCount' => $OptionsCount,
                        'OptionsData' => $OptionsData,
                        'Currency' => "Rs. ",
                        'CartTotal' =>  $CartData[0]['C_Total'],
                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );
                    // $CartDataValue= $CartData[0];

                    // $CartDataValue['OptionsData']=$OptionsData;
                    // $CartDataValue['OptionsCount']=$OptionsCount;

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Created Successfully', 'data' => $Record);

                } else if ($success == false) {
                    $data['Val_ProfileImage'] = '';
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'We couldn\'t register you. Please try again later.(404)', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $success);
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } 
            // Product Add To Cart functionlity
            else if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Customer']) && !empty($data['Val_Product'])) {

                // $ExistingCartArray = $this->Cart_model->getProductsCart(null, array('PC_CustomerID' => $data['Val_Customer']), "PC_Status <> 3 OR PC_Status <> 4");
                $ExistingCartArray = $this->Cart_model->getProductsCart(null, array('PC_CustomerID' => $data['Val_Customer']), "PC_Status NOT IN (3,4)");

                if (!empty($ExistingCartArray) && count($ExistingCartArray) == '1') {
                    
                    $ExistingCartData = (object) $ExistingCartArray[0];  //[0];

                    //echo "Exist";
                    $ProductVal = $data['Val_Product'];
                    $Products= $ExistingCartData->PC_ProductID;

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

                        //                        $PostData['Val_PCdetail']            =  json_encode(array());
                        //                        $PostData['Val_Address']            =  "";
                        //                    $PostData['Val_PCpaymentoption']    =  $TimeslabTitle;
                        //                    $PostData['Val_PCservicecharge']    =  $TimeslabTitle;

                        $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData, $ExistingCartData->PCartID);

                        if ($CartAddProductStatus) {

                            $ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
                            $DetailID = $ProductsDetailsArray;

                            $PostDetailData['Val_Cart'] = $ExistingCartData->PCartID;
                            $PostDetailData['Val_Product'] = $ProductVal;
                            $PostDetailData['Val_PDquantity'] = '1';
                            $PostDetailData['Val_Attribute'] = getStringValue($data['Val_Attribute']);
                            $PostDetailData['Val_Attribvalue'] = getStringValue($data['Val_Attribvalue']);

                            $CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);
                            $DetailID[] = (string) $CartDetailsSuccess;

                            $UpdatePostData['Val_PCdetail'] = json_encode($DetailID);
                            $CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData, $ExistingCartData->PCartID);
                        }

                        $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);

                        $CartData1=(object)$CartData[0];

                        $ProductsCount = "0";
                        $ProductsData = array();
                        $ProductsDetailsArray = json_decode($CartData1->PC_DetailID);
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
                        $ProductsCount = (string) count($ProductsData);
                        $Record = array(
                            'CartID' => getStringValue($CartData1->PCartID),
                            'ProductsCount' => $ProductsCount,
                            'ProductsData' => $ProductsData,
                            'Currency' => "Rs. ",
                            'ItemTotal' => $CartData1->PC_ItemTotal,
                            'DeliveryCharges' => $CartData1->PC_DeliveryCharge,
                            'CartTotal' => $CartData1->PC_CartTotal,
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully', 'data' => $Record);
                    } else {

                        $CartID = $ExistingCartData->PCartID;
                        $CartData = $this->Cart_model->getProductsCart($CartID);

                        $ProductsCount = "0";
                        $ProductsData = array();
                        $CartData1=(object)$CartData[0];
                        $ProductsDetailsArray = json_decode($CartData1->PC_DetailID);
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
                        $ProductsCount = (string) count($ProductsData);
                        $Record = array(
                            'CartID' => getStringValue($CartData1->PCartID),
                            'ProductsCount' => $ProductsCount,
                            'ProductsData' => $ProductsData,
                            'Currency' => "Rs. ",
                            'ItemTotal' => $CartData1->PC_ItemTotal,
                            'DeliveryCharges' => $CartData1->PC_DeliveryCharge,
                            'CartTotal' => $CartData1->PC_CartTotal,
                            //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                            //        'Status'=> getStatus($CustomerData->C_Status),
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully', 'data' => $Record);
                    }
                } else {

                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                    //print_r($CustomerData);
                    $CustomerFullName = getStringValue($CustomerData->C_FirstName) . " " . getStringValue($CustomerData->C_LastName);
                    //$CustomerAddress     = array(getStringValue($CustomerData->C_Address),getStringValue($CustomerData->C_Location));

                    $Product = $data['Val_Product'];
                    //$Products             = $data['Val_Product'];
                    //$ProductsArray         = json_decode($Products);
                    $CTotal = 0;
                    $ProductsTotal = 0;
                    $DeliveryCharges = 15.00;
                    //                    if(!empty($ProductsArray)){
                    //                        foreach($ProductsArray as $Product){
                    $ProductsData = $this->Products_model->get($Product);
										//print_r($ProductsData);
                    $ProductID[] = $ProductsData->ProductID;
                    $ProductNames[] = $ProductsData->P_Name;
                    $ProductPrices[] = $ProductsData->P_Price;

                    $ProductsTotal = $ProductsTotal + $ProductsData->P_Price;

                    //                        }

                    $CartTotal = $ProductsTotal + $DeliveryCharges;

                    $PostData['Val_Customer'] = $data['Val_Customer'];
                    $PostData['Val_PCcustomername'] = $CustomerFullName;
                    //$PostData['Val_PCcustomeraddress']    =  json_encode($CustomerAddress);
										$PostData['Val_Product'] = json_encode($ProductID);
										
                    $PostData['Val_PCproductnames'] = json_encode($ProductNames);

                    $PostData['Val_PCdate'] = date('Y-m-d');
                    //                        $PostData['Val_PCdetail']            =  json_encode(array());
                    $PostData['Val_PCprices'] = json_encode($ProductPrices);
                    //                        $PostData['Val_Address']            =  "";
                    $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                    $PostData['Val_PCdeliverycharges'] = number_format($DeliveryCharges, 2, '.', '');
                    $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                    //                    $PostData['Val_PCpaymentoption']    =  $TimeslabTitle;
                    //                    $PostData['Val_PCservicecharge']    =  $TimeslabTitle;
                    $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');
                    $PostData['Val_PCorderstatus'] ='0';
                    $PostData['Val_PCstatus'] = '1';
										//print_r($PostData);
                    $success = $this->Cart_model->addCartProducts($PostData);
										//print_r($success);
                    if ($success) {
											//echo $success;
											$CartID = $success;
											//echo $CartID;
                        //    private $product_cart_details_data = array('CPDetailID'=>'Val_Cartdetail', 'PD_CartID'=>'Val_Cart','PD_ProductID'=>'Val_Product','PD_Quantity'=>'Val_PDquantity', 'PD_AttributeID'=>'Val_Attribute', 'PD_AttribValueID'=>'Val_Attribvalue', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

                        //    foreach($ProductsArray as $Product){
                        $PostDetailData['Val_Cart'] = $CartID;
                        $PostDetailData['Val_Product'] = $Product;
                        $PostDetailData['Val_PDquantity'] = '1';
                        $PostDetailData['Val_Attribute'] = $data['Val_Attribute'];
						$PostDetailData['Val_Attribvalue'] = $data['Val_Attribvalue'];
												//print_r($PostDetailData);
                        $CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);
												$DetailID[] = (string) $CartDetailsSuccess;
												//print_r($DetailID);
                        //}

												$UpdatePostData['Val_PCdetail'] = json_encode($DetailID);
												
                        $CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData, $CartID);
												//print_r($CartUpdateStatus);
												//addCartProductsDetails
												
												//$CartData = $this->Cart_model->getProductsCart($CartID); commented by pranav and added below line on 29/03/2019
                        $CartData = $this->Cart_model->getProductsCart($CartID,array('PC_CustomerID' => $data['Val_Customer']), "PC_Status <> 3 OR PC_Status <> 4");
                        
                        //print_r($CartData);
                        $ProductsCount = "0";
                        $ProductsData = array();
                        
                        $count=count($CartData);   // Niranjan 28.04.2019 12.14 AM
                        
											//$ProductsDetailsArray = (object)($CartData['PC_DetailID']); //commented by pranav and added below line on 29/03/2019
                                            $CartData1=(object)$CartData[$count-1];
                                            
											$ProductsDetailsArray = json_decode($CartData1->PC_DetailID);
                                                // print_r($ProductsDetailsArray);
                                                // exit;
													//echo $CartData['PC_DetailID'];
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
                            'CartID' => getStringValue($CartData1->PCartID), // $CartData['PCartID'] commented by pranav and added below line on 29/03/2019
                            'ProductsCount' => $ProductsCount,
                            'ProductsData' => $ProductsData,
                            'Currency' => "Rs. ",
                            'ItemTotal' => $CartData1->PC_ItemTotal , // $CartData['PC_ItemTotal'] commented by pranav and added below line on 29/03/2019
                            'DeliveryCharges' => $CartData1->PC_DeliveryCharge, //  $CartData['PC_DeliveryCharge'] commented by pranav and added below line on 29/03/2019
                            'CartTotal' => $CartData1->PC_CartTotal, // $CartData['PC_CartTotal'] commented by pranav and added below line on 29/03/2019
                            //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                            //        'Status'=> getStatus($CustomerData->C_Status),
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Created Successfully', 'data' => $Record);

                    } else if ($success == false) {
                        $data['Val_ProfileImage'] = '';
                        $result = array('status' => 'error', 'flag' => '2', 'message' => 'We couldn\'t register you. Please try again later.(404)', 'data' => $Record);
                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $success);
                    }
                    //                    } else {
                    //                                $result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);
                    //                        }

                    //echo "No Exist";
                }

            } 
            // Restaurant Food Add To Cart Functionality
            else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Customer']) && !empty($data['Val_Restaurant']) && !empty($data['Val_Food'])) {

                //$ExistingCartArray    =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_Status <>'=>'3','RC_Status <>'=>'4','RC_CustomerID'=>$data['Val_Customer']));
                $ExistingCartArray1 = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer']), "RC_Status NOT IN (3,4)");

                $ExistingCartArray = end($ExistingCartArray1);
                $RC_Status = $ExistingCartArray['RC_Status'];
                $RestaurantID = $ExistingCartArray['RC_RestaurantID'];
             
                if (!empty($ExistingCartArray) && $RC_Status == '1'){

                /*if (!empty($ExistingCartArray)){*/    
                    
                    //echo "Exist";
                    $ExistingCartData=(object)$ExistingCartArray; //[0]

                    $Restaurant = $data['Val_Restaurant'];

                    $RestaurantData = $this->Restaurants_model->get($Restaurant);

                    if (!empty($RestaurantData)) {

                        // When food cart is belonging from same Restaurant then its working 

                        if ($Restaurant == $ExistingCartData->RC_RestaurantID) {
                            //echo "Exist2";
                            $CTotal = 0;
                            $FoodsTotal = 0;
                            $DeliveryCharges = $ExistingCartData->RC_DeliveryCharge;
                            $FoodPricesArray = json_decode($ExistingCartData->RC_Prices);

                            $FoodID = $data['Val_Food'];

                            $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null, array('RD_CartID' => $ExistingCartData->RCartID, 'RD_FoodID' => $FoodID));
                           
                            if (!empty($FoodPricesArray)) {
                                foreach ($FoodPricesArray as $Key => $Price) {
                                    $FoodsTotal = $FoodsTotal + $Price;
                                }
                            } else {
                                $FoodsTotal = $ExistingCartData->PC_ItemTotal;
                            }

                            if (!empty($ExistingCartDetailData)) //if(!in_array($ProductVal,$ProductID,true))
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
                               

                                $CartAddProductStatus = $this->Cart_model->updateCartRestaurants($PostData, $ExistingCartData->RCartID);
                                $RestaurantsDetailsArray = json_decode($ExistingCartData->RC_DetailID);
                                $DetailID = $RestaurantsDetailsArray;
                                if ($CartAddProductStatus) {

                                    $PostDetailData['Val_Cart'] = $ExistingCartData->RCartID;
                                    $PostDetailData['Val_Food'] = $FoodID;
                                    $PostDetailData['Val_RDquantity'] = '1';
                                    $PostDetailData['Val_RDprice'] = getStringValue($FoodAmount);
                                    $CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);
                                    $DetailID[]= (string) $CartDetailsSuccess;

                                    $UpdatePostData['Val_RCdetail'] = json_encode($DetailID);
                                    $CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData, $ExistingCartData->RCartID);
                                }

                                $CartData = $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);

                                $CartData1=(object)$CartData[0];

                                $ItemCount = $CartData1->RC_ItemCount;

                                $Record = array(
                                    'CartID' =>$CartData1->RCartID,
                                    'Currency' => "Rs. ",
                                    'ItemCount' => $ItemCount,
                                    'ItemTotal' => $CartData1->RC_ItemTotal,
                                    'CartTotal' => $CartData1->RC_CartTotal,
                                );

                                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully', 'data' => $Record);

                                //echo 'Empty';
                            } else {

                                //Nothing Happens, Just Show Existing Data as it is
                                //print_r($ExistingCartDetailData);

                                $CartID = $ExistingCartData->RCartID;

                                $CartData = $this->Cart_model->getRestaurantsCart($CartID);

                                $CartData1= (object)$CartData[0];

                                $ItemCount = $CartData1->RC_ItemCount;

                                $Record = array(
                                    'CartID' => getStringValue($CartData1->RCartID),
                                    'Currency' => "Rs. ",
                                    'ItemCount' => getStringValue($ItemCount),
                                    'ItemTotal' => getStringValue($CartData1->RC_ItemTotal),
                                    'CartTotal' => getStringValue($CartData1->RC_CartTotal),
                                );

                                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully', 'data' => $Record);

                            }
                        } else {
                            //Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
                            $CartID = $ExistingCartData->RCartID;
                            $RestaurantData = $this->Restaurants_model->get($Restaurant);
                            $DeliveryCharges = 15.00;
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
        
                                /*if(!empty($ProductsArray)){}
                                //                        foreach($ProductsArray as $Product){
                                $RestaurantData     =    $this->Restaurants_model->get($Restaurant);
        
                                $RestaurantID     = $RestaurantData->RestaurantID;
                                $RestaurantName    = $RestaurantData->R_Name;
                                $ProductPrices[]= $RestaurantData->P_Price;
        
                                $ProductsTotal     = $ProductsTotal + $ProductsData->P_Price;
        
                                //                        }    */
        
                                $CartTotal = $ProductsTotal + $DeliveryCharges;
        
                                $PostData['Val_Customer'] = $data['Val_Customer'];
                                $PostData['Val_RCcustomername'] = $CustomerFullName;
                                //$PostData['Val_RCcustomeraddress']    =  json_encode($CustomerAddress);
                                $PostData['Val_Restaurant'] = $RestaurantID;
                                $PostData['Val_RCrestaurantname'] = $RestaurantName;
        
                                $PostData['Val_RCdate'] = date('Y-m-d');
                                //                        $PostData['Val_RCdetail']            =  json_encode(array());
                                $PostData['Val_RCprices'] = json_encode($ProductPrices);
                                //                        $PostData['Val_Address']            =  "";
        
                                $PostData['Val_RCitemcount'] = 1;
                                $PostData['Val_RCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                                $PostData['Val_RCdeliverycharges'] = number_format($DeliveryCharges, 2, '.', '');
                                $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                                //                    $PostData['Val_RCpaymentoption']    =  $TimeslabTitle;
                                //                    $PostData['Val_RCservicecharge']    =  $TimeslabTitle;
                                $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');
                                $PostData['Val_RCstatus'] = '1';
                                $PostData['Val_RCorderstatus'] ='0';
        
        
                                $CartAddProductStatus = $this->Cart_model->updateCartRestaurants($PostData, $ExistingCartData->RCartID);
                                $PostDetailData['Val_Cart'] = $CartID;
                            $PostDetailData['Val_Food'] = $data['Val_Food'];
                            $PostDetailData['Val_RDquantity'] = '1';
                            $PostDetailData['Val_RDprice'] = $FoodAmount;
                            $CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);
                            $DetailID[]=(string)  $CartDetailsSuccess;
                            //}

                            $UpdatePostData['Val_RCdetail'] = json_encode($DetailID);
                            $CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData, $CartID);
                            
                            //addCartProductsDetails
                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);
                            //print_r($CartData);

                            $RestaurantsCount = "0";
                            $RestaurantsData = array();
                            $thisCartData= (object)$CartData[0];
                            $RestaurantsDetailsArray = json_decode($thisCartData->RC_DetailID);
                            $Index = 0;

                            $Record = array(
                                'CartID' => $thisCartData->RCartID,
                                'Currency' => "Rs. ",
                                'ItemCount' => $thisCartData->RC_ItemCount,
                                'ItemTotal' => $thisCartData->RC_ItemTotal,
                                'CartTotal' => $thisCartData->RC_CartTotal,
                                //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                //        'Status'=> getStatus($CustomerData->C_Status),
                            );

                                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Added Successfully', 'data' => $Record);
                            }
                        }

                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                    }
                } else {

                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);

                    $CustomerFullName = getStringValue($CustomerData->C_FirstName) . " " . getStringValue($CustomerData->C_LastName);
                    //$CustomerAddress     = array(getStringValue($CustomerData->C_Address),getStringValue($CustomerData->C_Location));

                    $Restaurant = $data['Val_Restaurant'];
                    //$Products             = $data['Val_Product'];
                    //$ProductsArray         = json_decode($Products);
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

                        /*if(!empty($ProductsArray)){}
                        //                        foreach($ProductsArray as $Product){
                        $RestaurantData     =    $this->Restaurants_model->get($Restaurant);

                        $RestaurantID     = $RestaurantData->RestaurantID;
                        $RestaurantName    = $RestaurantData->R_Name;
                        $ProductPrices[]= $RestaurantData->P_Price;

                        $ProductsTotal     = $ProductsTotal + $ProductsData->P_Price;

                        //                        }    */

                        $CartTotal = $ProductsTotal + $DeliveryCharges;

                        $PostData['Val_Customer'] = $data['Val_Customer'];
                        $PostData['Val_RCcustomername'] = $CustomerFullName;
                        //$PostData['Val_RCcustomeraddress']    =  json_encode($CustomerAddress);
                        $PostData['Val_Restaurant'] = $RestaurantID;
                        $PostData['Val_RCrestaurantname'] = $RestaurantName;

                        $PostData['Val_RCdate'] = date('Y-m-d');
                        //                        $PostData['Val_RCdetail']            =  json_encode(array());
                        $PostData['Val_RCprices'] = json_encode($ProductPrices);
                        //                        $PostData['Val_Address']            =  "";

                        $PostData['Val_RCitemcount'] = 1;
                        $PostData['Val_RCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                        $PostData['Val_RCdeliverycharges'] = number_format($DeliveryCharges, 2, '.', '');
                        $PostData['Val_RCcarttotal'] = number_format($CartTotal, 2, '.', '');
                        //                    $PostData['Val_RCpaymentoption']    =  $TimeslabTitle;
                        //                    $PostData['Val_RCservicecharge']    =  $TimeslabTitle;
                        $PostData['Val_RCtotal'] = number_format($CartTotal, 2, '.', '');
                        $PostData['Val_RCstatus'] = '1';
                        $PostData['Val_RCorderstatus'] ='0';


                        $success = $this->Cart_model->addCartRestaurants($PostData);

                        if ($success) {
                            $CartID = $success;
                            //   private $restaurant_cart_details_data = array('CRDetailID'=>'Val_Cartdetail', 'RD_CartID'=>'','RD_FoodID'=>'','RD_Quantity'=>'', 'RD_Price'=>'', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

                            $FoodAmount;
                            //    foreach($ProductsArray as $Product){
                            $PostDetailData['Val_Cart'] = $CartID;
                            $PostDetailData['Val_Food'] = $data['Val_Food'];
                            $PostDetailData['Val_RDquantity'] = '1';
                            $PostDetailData['Val_RDprice'] = $FoodAmount;
                            $CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);
                            $DetailID[]=(string)  $CartDetailsSuccess;
                            //}

                            $UpdatePostData['Val_RCdetail'] = json_encode($DetailID);
                            $CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData, $CartID);
                            
                            //addCartProductsDetails
                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);
                            //print_r($CartData);

                            $RestaurantsCount = "0";
                            $RestaurantsData = array();
                            $thisCartData= (object)$CartData[0];
                            $RestaurantsDetailsArray = json_decode($thisCartData->RC_DetailID);
                            $Index = 0;

                            $Record = array(
                                'CartID' => $thisCartData->RCartID,
                                'Currency' => "Rs. ",
                                'ItemCount' => $thisCartData->RC_ItemCount,
                                'ItemTotal' => $thisCartData->RC_ItemTotal,
                                'CartTotal' => $thisCartData->RC_CartTotal,
                                //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                                //        'Status'=> getStatus($CustomerData->C_Status),
                            );

                            $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Created Successfully', 'data' => $Record);

                        } else if ($success == false) {
                            $data['Val_ProfileImage'] = '';
                            $result = array('status' => 'error', 'flag' => '2', 'message' => 'We couldn\'t register you. Please try again later.(404)', 'data' => (object) array());
                        } else {
                            $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                        }

                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !!! ', 'data' => (object) array());
                    }

                    //        {            } else {
                    //                                $result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);
                    //                        }

                    //echo "No Exist";
                }

/* //Start: - Functionality used for the Insert Data In LiveTracking Table 22.04.2019 11.58AM 

                $BusinessType=$data['Val_Type'];
                if($BusinessType=="2"){

                    $livetrack[]=array(
                                        'cust_lat'  =>  $CustomerData->C_Latitude,
                                        'cust_long' =>  $CustomerData->C_Longitude,
                                        'cart_id'   =>  $CartData->PCartID,
                                        'busi_type' =>   $BusinessType
                                    );

                    $success=$this->LiveTracking_model->insert($livetrack);

                }else if($BusinessType=="3"){

                    $livetrack[]=array(
                        'cust_lat'  =>  $CustomerData->C_Latitude,
                        'cust_long' =>  $CustomerData->C_Longitude,
                        'cart_id'   =>  $CartData->RCartID,
                        'busi_type' =>   $BusinessType
                    );

                    $success=$this->LiveTracking_model->insert($livetrack);
                }
  
                else{
                    $result = array('status' => 'info', 'flag' => '4', 'message' => 'Live Tracking Paramater Missing !');
                }
// End: - Functionality used for the Insert Data In LiveTracking Table 22.04.2019 11.58AM             */
            
                } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } 
        
        else if (!empty($data['Action']) && $data['Action'] == 'UpdateCart') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail']) && !empty($data['Val_Quantity']) && !empty($data['Val_Customer']) && !empty($data['Val_Product'])) {
                $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails($data['Val_Detail']);

//                    exit;

                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {


                    $ExistingCartData = (object)$ExistingCartData[0];
    $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);
$CartData= (object)$CartData[0];
                    $ProductsCount = "0";
                    $ProductsData = array();
                    $ProductsDetailsArray = json_decode($CartData->PC_DetailID);

                    $ProductVal = $data['Val_Product'];
                    $Products = $ExistingCartData->PC_ProductID;
                    $ProductsArray = json_decode($Products);
                    $CTotal = 0;
                    $ProductsTotal = 0;
                    $DeliveryCharges = $ExistingCartData->PC_DeliveryCharge;
                    if (!empty($ProductsArray)) {
                     
                            foreach ($ProductsDetailsArray as $ProductDetail) {

                                $ProductDetailData = $this->Cart_model->getProductsCartDetails($ProductDetail);
        
                                $ProductData = $this->Products_model->get($ProductDetailData->PD_ProductID);
                                $ProductDetailData =(object)$ProductDetailData;
                          
                            if ($ProductVal == $ProductDetailData->PD_ProductID) {
                                $ProductPrice = ($ProductData->P_Price * $data['Val_Quantity']);
                            } else {
                                $ProductPrice = $ProductData->P_Price * $ProductDetailData->PD_Quantity;
                            }

                            $ProductPrices[] = number_format($ProductPrice, 2, '.', ''); //$ProductPrice;
                            $ProductsTotal = $ProductsTotal + number_format($ProductPrice, 2, '.', '');

                        }
                    } else {
                        $ProductsTotal = $ExistingCartData->PC_ItemTotal;
                    }

                    $CartTotal = $ProductsTotal + $DeliveryCharges;
                    $CartData->PC_CartTotal= $CartTotal;
                    $CartData->PC_ItemTotal= $ProductsTotal;
                    $PostData['Val_PCprices'] = json_encode($ProductPrices);
                    $PostData['Val_PCitemtotal'] = number_format($ProductsTotal, 2, '.', '');
                    $PostData['Val_PCcarttotal'] = number_format($CartTotal, 2, '.', '');
                    $PostData['Val_PCtotal'] = number_format($CartTotal, 2, '.', '');
                    $PostData['Val_PCstatus'] = '2';

                    //                        $PostData['Val_PCdetail']            =  json_encode(array());
                    //                        $PostData['Val_Address']            =  "";
                    //                    $PostData['Val_PCpaymentoption']    =  $TimeslabTitle;
                    //                    $PostData['Val_PCservicecharge']    =  $TimeslabTitle;

                    $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData, $ExistingCartData->PCartID);
                    if ($CartAddProductStatus) {

//                                            $ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
                        //                                            $DetailID = $ProductsDetailsArray;

                        $PostDetailData['Val_PDquantity'] = $data['Val_Quantity'];
                        $CartDetailsSuccess = $this->Cart_model->updateCartProductsDetails($PostDetailData, $data['Val_Detail']);
//                                            $DetailID[] = (string)$CartDetailsSuccess;

//                                            $UpdatePostData['Val_PCdetail']            = json_encode($DetailID);
                        //                                            $CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,$ExistingCartData->PCartID);
                    }

                
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
                    $testthisvalue= $CartData->PCartID;
                    $ProductsCount = (string) count($ProductsData);
                    $Record = array(
                        'CartID' => getStringValue($CartData->PCartID),
                        'ProductsCount' => $ProductsCount,
                        'ProductsData' => $ProductsData,
                        'Currency' => "Rs. ",
                        'ItemTotal' => $CartData->PC_ItemTotal,
                        'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                        'CartTotal' => $CartData->PC_CartTotal,
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Updated Successfully', 'data' => $Record);

                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
//                            echo "No Exist";
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Food']) && !empty($data['Val_Quantity']) && !empty($data['Val_Customer']) && !empty($data['Val_Restaurant'])) {
                $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                //$ExistingCartDetailData    =  $this->Cart_model->getProductsCartDetails($data['Val_Detail']);
                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null, array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID' => $data['Val_Food']));
                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1') {
                    $ExistingCartDetailData = (object) $ExistingCartDetailData[0];
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
                                //$FoodPricesArray[]    = $FoodAmount;
                                //$FoodsTotal         = $FoodsTotal + $FoodAmount;
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

                            $ExistingCartDetailsItemsArray = $this->Cart_model->getRestaurantsCartDetails(null, array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID <>' => $FoodID));
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

                            $CartUpdateProductStatus = $this->Cart_model->updateCartRestaurants($PostData, $ExistingCartData->RCartID);
                            if ($CartUpdateProductStatus) {
                                $PostDetailData['Val_RDprice'] = $FoodAmount;
                                $PostDetailData['Val_RDquantity'] = $data['Val_Quantity'];
                                $CartDetailsSuccess = $this->Cart_model->updateCartRestaurantsDetails($PostDetailData, $ExistingCartDetailData->CRDetailID);
                            }
                            $CartData = $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);

                            $Record = array(
                                'CartID' => getStringValue($CartData->RCartID),
                                'Currency' => "Rs. ",
                                'ItemCount' => getStringValue($ItemCount),
                                'ItemTotal' => getStringValue($CartData->RC_ItemTotal),
                                'CartTotal' => getStringValue($CartData->RC_CartTotal),
                            );

                            $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Update Successfully', 'data' => $Record);

                        } else {
                            //Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
                            $CartID = $ExistingCartData->RCartID;
                            $CartData = $this->Cart_model->getRestaurantsCart($CartID);

                            $FoodDetailsArray = json_decode($CartData->RC_DetailID);

                            if (!empty($FoodDetailsArray)) {
                                $ItemCount = (string) count($FoodDetailsArray);
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

                            $result = array('status' => 'warning', 'flag' => '3', 'message' => 'You cannot add items from this restaurant as your cart contains items from another restaurant.', 'data' => $Record);

                        }
                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                    }

                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
//                            echo "No Exist";
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'RemoveCart') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail']) && !empty($data['Val_Customer']) && !empty($data['Val_Product'])) {
                $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails($data['Val_Detail']);

                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {
                    
                    $ExistingCartData = (object)$ExistingCartData[0];

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
                                $ProductPrice = (float) $PricesArray[$Key];
                                $ProductPrices[] = number_format($ProductPrice, 2, '.', ''); //$ProductPrice;
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
                        //                        $PostData['Val_PCdetail']            =  json_encode(array());
                        //                        $PostData['Val_Address']            =  "";
                        //                    $PostData['Val_PCpaymentoption']    =  $TimeslabTitle;
                        //                    $PostData['Val_PCservicecharge']    =  $TimeslabTitle;
                        $CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData, $ExistingCartData->PCartID);
                        if ($CartAddProductStatus) {
                            $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);
                        }
                        $CartData = $this->Cart_model->getProductsCart($ExistingCartData->PCartID);
                        $CartData = (object)  $CartData[0];
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
                        $ProductsCount = (string) count($ProductsData);
                        $Record = array(
                            'CartID' => getStringValue($CartData->PCartID),
                            'ProductsCount' => $ProductsCount,
                            'ProductsData' => $ProductsData,
                            'Currency' => "Rs. ",
                            'ItemTotal' => $CartData->PC_ItemTotal,
                            'DeliveryCharges' => $CartData->PC_DeliveryCharge,
                            'CartTotal' => $CartData->PC_CartTotal,
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Updated Successfully', 'data' => $Record);

                    } else {
                        $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);
                        $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);
                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully', 'data' => (object) array());
                    }
//

                } else if (empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Already Empty.', 'data' => (object) array());
                } else {
//                            $result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Product Already removed from cart.', 'data' => (object) array());
//                            echo "No Exist";
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Restaurant']) && !empty($data['Val_Customer']) && !empty($data['Val_Food'])) {
                $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null, array('RD_CartID' => $data['Val_Cart'], 'RD_FoodID' => $data['Val_Food']));
                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1') {
                    $ExistingCartDetailData = (object) $ExistingCartDetailData[0];

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

                                $FoodPrice = (float) $PricesArray[$Key];
                                $FoodPricesArray[] = number_format($FoodPrice, 2, '.', ''); //$ProductPrice;
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

                        $CartRemoveRestaurantStatus = $this->Cart_model->updateCartRestaurants($PostData, $ExistingCartData->RCartID);

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
                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Product Updated Successfully', 'data' => $Record);

                    } else {

                        $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                        $CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($ExistingCartDetailData->CRDetailID);
                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully', 'data' => (object) array());
                    }

                } else if (empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Already Empty.', 'data' => (object) array());
                } else {
//                            $result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Product Already removed from cart.', 'data' => (object) array());
//                            echo "No Exist";
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'ClearCart') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Cart'])) {
                $ExistingCartData = $this->Cart_model->get($data['Val_Cart']);

                if (!empty($ExistingCartData)) {
                    $CartSuccess = $this->Cart_model->delete($data['Val_Cart']);
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully.', 'data' => (object) array());
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Cart Already Cleared.', 'data' => (object) array());
                }

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart'])) {
                $ExistingCartData = $this->Cart_model->getProductsCart($data['Val_Cart']);
                $ExistingCartDetailData = $this->Cart_model->getProductsCartDetails(null, array('PD_CartID' => $data['Val_Cart']));

                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {
                    $DetailIdArray = json_decode($ExistingCartData->PC_DetailID);

                    foreach ($DetailIdArray as $ProductDetail) {
                        $CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($ProductDetail);
                    }
                    $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully.', 'data' => $Record);

                } else if (!empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                    $CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully.', 'data' => (object) array());
                } else {
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Already Cleared.', 'data' => (object) array());
                }

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart'])) {
                $ExistingCartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails(null, array('RD_CartID' => $data['Val_Cart']));
                if (!empty($ExistingCartData) && !empty($ExistingCartDetailData)) {

                    $DetailIdArray = json_decode($ExistingCartData->RC_DetailID);
                    $DetailIDs = array();

                    if (!empty($DetailIdArray)) {
                        foreach ($DetailIdArray as $Key => $DetailID) {
                            $CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($DetailID);
                        }
                    }

                    $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully', 'data' => (object) array());
                } else if (!empty($ExistingCartData) && empty($ExistingCartDetailData)) {
                    $CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Cleared Successfully.', 'data' => (object) array());
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Cart Already Empty.', 'data' => (object) array());
                }

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'CartCheckout') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Customer']) && !empty($data['Val_Cart']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal'])) {

                $data['Val_Corderstatus'] = '1';
                $data['Val_Cstatus'] = '2';
                $data['Val_Cbookeddttm'] = date('Y-m-d H:i:s');
                
                $success = $this->Cart_model->update($data, $data['Val_Cart']);

                if ($success) {
                    
                    $CartData = $this->Cart_model->get($data['Val_Cart']);
                    /*$OptionsCount     = 0;
                    $OptionsData      = array();
                    $OptionNames     = json_decode($CartData->C_OptionNames);
                    $OptionPrices     = json_decode($CartData->C_OptionPrices);
                    $PackageNames     = json_decode($CartData->C_PackageNames);
                    $Index = 0;
                    foreach($OptionNames as $Option)
                    {

                    $OptionsData[] = array(
                    'Title'            =>$PackageNames[$Index],
                    'Description'    =>$Option,
                    'Currency'        =>"Rs. ",
                    'Price'            =>$OptionPrices[$Index],
                    );
                    $Index++;
                    }
                     */

                    $TimeslabData = $this->Services_model->getTimeslabs($CartData[0]['C_TimeslabID']);
                    
                    $StartTime = date('h:i A', strtotime($CartData[0]['C_Date'] . " " . $TimeslabData->T_StartTime));
                    $EndTime = date('h:i A', strtotime($CartData[0]['C_Date'] . " " . $TimeslabData->T_EndTime));
                    $Date = date('D, j M,Y', strtotime($CartData[0]['C_Date']));
                    $CartTimeText = $StartTime . " - " . $EndTime . " on " . $Date;

                    $Record = array(
                        'OrderID' => getStringValue($CartData[0]['CartID']),
                        //'OptionsCount'     => $OptionsCount,
                        //                                        'OptionsData'     => $OptionsData,
                        'TimeText' => $CartTimeText,
//                                        'Currency'        =>"Rs. ",
                        //                                        'CartTotal'     => $CartData->C_Total,

                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Checkout Successfully', 'data' => $Record);

                } else if ($success == false) {
                    $CartData = $this->Cart_model->get($data['Val_Cart']);

                    $TimeslabData = $this->Services_model->getTimeslabs($CartData[0]['C_TimeslabID']);
                    
                    $StartTime = date('h:i A', strtotime($CartData[0]['C_Date'] . " " . $TimeslabData->T_StartTime));
                    $EndTime = date('h:i A', strtotime($CartData[0]['C_Date'] . " " . $TimeslabData->T_EndTime));
                    $Date = date('D, j M,Y', strtotime($CartData[0]['C_Date']));
                    $CartTimeText = $StartTime . " - " . $EndTime . " on " . $Date;

                    $Record = array(
                        'OrderID' => getStringValue($CartData[0]['CartID']),
                        //'OptionsCount'     => $OptionsCount,
                        //                                        'OptionsData'     => $OptionsData,
                        'TimeText' => $CartTimeText,
//                                        'Currency'        =>"Rs. ",
                        //                                        'CartTotal'     => $CartData->C_Total,

                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart is already Updated', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $success);
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Customer']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal'])) {

                unset($data['Val_Type']);

                $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                $AddressData = $this->Customers_model->getAddresses($data['Val_Address']);
                //["+919409406924","Synergy Towers,","Ahmedabad,Gujarat,India","23.00290395306084","72.50233765647236"]
                $CustomerFullName = getStringValue($AddressData->A_Name);
                $CustomerAddress = array(getStringValue($CustomerData->C_CountryCode . $CustomerData->C_Mobile), getStringValue($AddressData->A_Address), getStringValue($AddressData->A_Location), getStringValue($AddressData->A_Latitude), getStringValue($AddressData->A_Longitude));

                $data['Val_PCcustomername'] = $CustomerFullName;
                $data['Val_PCcustomeraddress'] = json_encode($CustomerAddress);
                $data['Val_PCpaymentoption'] = $data['Val_Cpaymentoption'];
                $data['Val_PCservicecharge'] = $data['Val_Cservicecharge'];
                $data['Val_PCtotal'] = $data['Val_Ctotal'];
                $data['Val_PCorderstatus'] = '1';
                $data['Val_PCstatus'] = '2';
                $data['Val_PCbookeddttm'] = date('Y-m-d H:i:s');

                $success = $this->Cart_model->updateCartProducts($data, $data['Val_Cart']);

                if ($success) {
                    $CartData = $this->Cart_model->getProductsCart($data['Val_Cart']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->PCartID),
                        //'OptionsCount'     => $OptionsCount,
                        //                                        'OptionsData'     => $OptionsData,
                        //                                        'TimeText'        => $CartTimeText,
                        'Currency' => "Rs. ",
                        'CartTotal' => $CartData->PC_CartTotal,
                        'GrandTotal' => $CartData->PC_Total,

                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Checkout Successfully', 'data' => $Record);

                } else if ($success == false) {

                    $CartData = $this->Cart_model->getProductsCart($data['Val_Cart']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->PCartID),
                        //'OptionsCount'     => $OptionsCount,
                        //                                        'OptionsData'     => $OptionsData,
                        //                                        'TimeText'        => $CartTimeText,
                        'Currency' => "Rs. ",
                        'CartTotal' => $CartData->PC_CartTotal,
                        'GrandTotal' => $CartData->PC_Total,

                        //        'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
                        //        'Status'=> getStatus($CustomerData->C_Status),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart is already Updated', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $success);
                }

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Customer']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_RCpaymentoption']) && !empty($data['Val_RCservicecharge']) && !empty($data['Val_RCtotal'])) {
                unset($data['Val_Type']);
                $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                $AddressData = $this->Customers_model->getAddresses($data['Val_Address']);
                //["+919409406924","Synergy Towers,","Ahmedabad,Gujarat,India","23.00290395306084","72.50233765647236"]
                $CustomerFullName = getStringValue($AddressData->A_Name);
                $CustomerAddress = array(getStringValue($CustomerData->C_CountryCode . $CustomerData->C_Mobile), getStringValue($AddressData->A_Address), getStringValue($AddressData->A_Location), getStringValue($AddressData->A_Latitude), getStringValue($AddressData->A_Longitude));

                $data['Val_RCcustomername'] = $CustomerFullName;
                $data['Val_RCcustomeraddress'] = json_encode($CustomerAddress);
                $data['Val_RCorderstatus'] = '1';
                $data['Val_RCstatus'] = '2';
                $data['Val_RCbookeddttm'] = date('Y-m-d H:i:s');
                $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
                if (!empty($CartData)) {
                    $success = $this->Cart_model->updateCartRestaurants($data, $data['Val_Cart']);

                    if ($success) {
                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);

                        $Record = array(
                            'OrderID' => getStringValue($CartData->RCartID),
                            'Currency' => "Rs. ",
                            'OrderTotal' => $CartData->RC_CartTotal,
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Checkout Successfully', 'data' => $Record);

                    } else if ($success == false) {

                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Cart']);

                        $Record = array(
                            'OrderID' => getStringValue($CartData->RCartID),
                            'Currency' => "Rs. ",
                            'OrderTotal' => $CartData->RC_CartTotal,
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart is already Updated', 'data' => $Record);
                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => $success);
                    }
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                }

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'CancelOrder') {

            if (!empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Customer']) && !empty($data['Val_Order'])) {
                unset($data['Val_Type']);

                $data['Val_Corderstatus'] = '5';
                $data['Val_Cstatus'] = '4';

                $data['Val_Ccancelleddttm'] = date('Y-m-d H:i:s');
                $success = $this->Cart_model->update($data, $data['Val_Order']);

                if ($success) {
                    $CartData = $this->Cart_model->get($data['Val_Order']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->CartID),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Cancelled Successfully', 'data' => $Record);

                } else if ($success == false) {
                    $CartData = $this->Cart_model->get($data['Val_Order']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->CartID),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order is already Cancelled', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                }

            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Customer']) && !empty($data['Val_Order'])) {
                unset($data['Val_Type']);
                $data['Val_PCorderstatus'] = '5';
                $data['Val_PCstatus'] = '4';
                $data['Val_PCcancelleddttm'] = date('Y-m-d H:i:s');

                $success = $this->Cart_model->updateCartProducts($data, $data['Val_Order']);

                if ($success) {
                    $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->PCartID),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Cancelled Successfully', 'data' => $Record);

                } else if ($success == false) {
                    $CartData = $this->Cart_model->getProductsCart($data['Val_Order']);

                    $Record = array(
                        'OrderID' => getStringValue($CartData->PCartID),
                    );

                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order is already Cancelled', 'data' => $Record);
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                }
            } else if (!empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Customer']) && !empty($data['Val_Order'])) {
                unset($data['Val_Type']);
                $data['Val_RCorderstatus'] = '5';
                $data['Val_RCstatus'] = '4';
                $data['Val_RCcancelleddttm'] = date('Y-m-d H:i:s');
                $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);
                if (!empty($CartData)) {
                    $success = $this->Cart_model->updateCartRestaurants($data, $data['Val_Order']);

                    if ($success) {
                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                        $Record = array(
                            'OrderID' => getStringValue($CartData->RCartID),
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Cancelled Successfully', 'data' => $Record);

                    } else if ($success == false) {

                        $CartData = $this->Cart_model->getRestaurantsCart($data['Val_Order']);

                        $Record = array(
                            'OrderID' => getStringValue($CartData->RCartID),
                        );

                        $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order is already Cancelled', 'data' => $Record);
                    } else {
                        $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                    }
                } else {
                    $result = array('status' => 'warning', 'flag' => '3', 'message' => 'Something Important Happened !! ', 'data' => (object) array());
                }

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing', 'data' => (object) array());
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'GetRestaurantCart') {

            if (!empty($data['Val_Customer'])) {
                $RestaurantsRecords = array();
                $ExistingRestaurantCartArray = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer']), "RC_Status IN (1,2) AND RC_OrderStatus IN (0,1)");
                
                if (!empty($ExistingRestaurantCartArray)) {

                   
                     $ExistingCartData = $ExistingRestaurantCartArray[0];
                    
                        // $RCartID = $ExistingCartData['RCartID'];
                        // $RDCartIDs = $this->Cart_model->getRestaurantsCartDetails($RCartID);
                        $DetailIDsJson = $ExistingCartData['RC_DetailID'];
                        $DetailIDsArray = json_decode($DetailIDsJson);

                        $RestaurantsCartItemsCount = 0;
                        $FoodsRecords = array();
                        $FoodsCount = 0;

                        /*if (!empty($RDCartIDs)) {*/

                        if (!empty($DetailIDsArray)) {

                            $FoodsRecords = array();
                            $FoodsCount = 0;

                            foreach ($DetailIDsArray as $DetailID) {

                                /*foreach ($RDCartIDs as $DetailID) {*/

                                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                
                                $FoodID = '';

                                if (!empty($ExistingCartDetailData)) {

                                    $Quantity=$ExistingCartDetailData->RD_Quantity;
                                    $RestaurantsCartItemsCount = $RestaurantsCartItemsCount +$Quantity ;
                                    $FoodID = $ExistingCartDetailData->RD_FoodID;
    
                                    $FoodData = $this->Restaurants_model->getFoods($FoodID);
    
                                    $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL . $FoodData->RFoodID . '/' . $FoodData->F_DisplayImage : '');
                                    
                                        array_push($FoodsRecords, array(

                                            'FoodID'        => getStringValue($FoodData->RFoodID),
                                            'Title'         => getStringValue($FoodData->F_Title),
                                            'Description'   => getStringValue($FoodData->F_Description),
                                            'Currency'      => getStringValue("Rs. "),
                                            'Price'         => getStringValue($FoodData->F_Price),
                                            'DisplayImage'  => getStringValue($DisplayImage),
                                            'Type'          => getStringValue($FoodData->F_Type),
                                            'CartQuantity'  => $ExistingCartDetailData->RD_Quantity,
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

                            $RestaurantsCartItemsCount = (string) $RestaurantsCartItemsCount;
    
                        } else {
                            //echo "Not Matching 3";
                            $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                        }
    
                        $RestaurantRecord = array(
                            'CartID' => getStringValue($ExistingCartData['RCartID']),
                            'FoodsCount' => $FoodsCount,
                            'FoodsData' => $FoodsRecords,
                            'Currency' => "Rs. ",
                            'ItemsCount' => $RestaurantsCartItemsCount,
                            'ItemTotal' => $ExistingCartData['RC_ItemTotal'],
                            'DeliveryCharges' => $ExistingCartData['RC_DeliveryCharge'],
                            'CartTotal' => $ExistingCartData['RC_CartTotal'],
                        );
                       
                      $RestaurantsCart = '2';
                    

                    
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Fetched Successfully.', 'data' => $RestaurantRecord);
                } else {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Cart is empty', 'data' => (object) array());
                }
            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }                                                                                                                          

        } else if (!empty($data['Action']) && $data['Action'] == 'GetCartDetails') {

            if (!empty($data['Val_Customer'])) {

                //$ExistingServiceCartArray = $this->Cart_model->get(null, array('C_CustomerID' => $data['Val_Customer']), "(C_Status = 1 OR C_Status = 0 OR C_Status = 2) AND(C_OrderStatus=0 OR C_OrderStatus=1)");
                //$ExistingProductCartArray = $this->Cart_model->getProductsCart(null, array( 'PC_CustomerID' => $data['Val_Customer']), "(PC_Status = 1 OR PC_Status = 0 OR PC_Status = 2)AND(PC_OrderStatus=0 OR PC_OrderStatus=1)");
                //$ExistingRestaurantCartArray = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer']), "(RC_Status = 1 OR RC_Status = 0 OR RC_Status = 2)AND (RC_OrderStatus=0 OR RC_OrderStatus=1 )");

                $ExistingServiceCartArray = $this->Cart_model->get(null, array('C_CustomerID' => $data['Val_Customer']), "C_Status IN (1,2) AND C_OrderStatus IN (0,1)");
                $ExistingProductCartArray = $this->Cart_model->getProductsCart(null, array( 'PC_CustomerID' => $data['Val_Customer']), "PC_Status IN (1,2) AND PC_OrderStatus IN (0,1)");
               
                $ExistingRestaurantCartArray = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer']), "RC_Status IN (1,2) AND RC_OrderStatus IN (0,1)");
                
                $ServicesCart = '1';
                $ServicesRecords=  array();                                        

                if (!empty($ExistingServiceCartArray)) {
                    
                    foreach($ExistingServiceCartArray as $ExistingServiceCartData)
                    {
                        $CartID = $ExistingServiceCartData['CartID'];
                        $OptionsCount = "0";
                        $OptionsData = array();
                        $OptionNames = json_decode($ExistingServiceCartData['C_OptionNames']);
                        $PackageNames = json_decode($ExistingServiceCartData['C_PackageNames']);
                        $OptionPrices = json_decode($ExistingServiceCartData['C_OptionPrices']);

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
                        $OptionsCount =count($OptionsData);
                        $ServiceRecord = array(
                            'CartID' => getStringValue($ExistingServiceCartData['CartID']),
                            'OptionsCount' => $OptionsCount,
                            'OptionsData' => $OptionsData,
                            'Currency' => "Rs. ",
                            'CartTotal' => $ExistingServiceCartData['C_Total'],
                        );
                        array_push($ServicesRecords, $ServiceRecord);
                        $ServicesCart = '2';
                    }
                }
                else{
                    $ServiceRecord = array('status' => 'info', 'flag' => '4', 'message' => 'Service Cart is Empty');
                    array_push($ServicesRecords, $ServiceRecord); 
                }
                
                $ProductsCart = '1';
                $ProductsRecords =  array();
                $ProductsCartItemsCount = 0;

                if (!empty($ExistingProductCartArray)) {
               
                    foreach($ExistingProductCartArray as $ExistingProductCartData)
                    {
                        $CartID = $ExistingProductCartData;

                        $ProductsCount = "0";
                        $ProductsData = array();
                        $ProductsDetailsArray = json_decode($ExistingProductCartData['PC_DetailID']);
                       
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
    
                                //$Index++;
                            }
                        } else {
                            //echo "Not Matching 3";
                            $ProductsCartItemsCount = $ProductsCartItemsCount + 0;
                        }
                        $ProductsCount = (string) count($ProductsData);
                        $ProductRecord = array(
                            'CartID' => getStringValue($CartID['PCartID']),
                            'ProductsCount' => $ProductsCount,
                            'ProductsData' => $ProductsData,
                            'Currency' => "Rs. ",
                            'ItemsCount' => $ProductsCartItemsCount,
                            'ItemTotal' => $ExistingProductCartData['PC_ItemTotal'],
                            'DeliveryCharges' => $ExistingProductCartData['PC_DeliveryCharge'],
                            'CartTotal' => $ExistingProductCartData['PC_CartTotal'],
    
                        );
                        array_push($ProductsRecords, $ProductRecord);
                       $ProductsCart = '2';
                    }
               
                   
                }
                else{
                    $ProductRecord = array('status' => 'info', 'flag' => '4', 'message' => 'Product Cart is Empty');
                    array_push($ProductsRecords, $ProductRecord);
                   
                }

                $RestaurantsCart = '1';
                $RestaurantsRecords = array();
                $RestaurantsCartItemsCount = 0;

                if (!empty($ExistingRestaurantCartArray)) {

                   
                    foreach($ExistingRestaurantCartArray as $ExistingCartData)
                    {
                        // $RCartID = $ExistingCartData['RCartID'];
                        // $RDCartIDs = $this->Cart_model->getRestaurantsCartDetails($RCartID);
                        $DetailIDsJson = $ExistingCartData['RC_DetailID'];
                        $DetailIDsArray = json_decode($DetailIDsJson);

                        $RestaurantsCartItemsCount = 0;
                        $FoodsRecords = array();
                        $FoodsCount = 0;

                        /*if (!empty($RDCartIDs)) {*/

                        if (!empty($DetailIDsArray)) {

                            $FoodsRecords = array();
                            $FoodsCount = 0;

                            foreach ($DetailIDsArray as $DetailID) {

                                /*foreach ($RDCartIDs as $DetailID) {*/

                                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                
                                $FoodID = '';

                                if (!empty($ExistingCartDetailData)) {

                                    $Quantity=$ExistingCartDetailData->RD_Quantity;
                                    $RestaurantsCartItemsCount = $RestaurantsCartItemsCount +$Quantity ;
                                    $FoodID = $ExistingCartDetailData->RD_FoodID;
    
                                    $FoodData = $this->Restaurants_model->getFoods($FoodID);
    
                                    $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL . $FoodData->RFoodID . '/' . $FoodData->F_DisplayImage : '');
                                    
                                        array_push($FoodsRecords, array(

                                            'FoodID'        => getStringValue($FoodData->RFoodID),
                                            'Title'         => getStringValue($FoodData->F_Title),
                                            'Description'   => getStringValue($FoodData->F_Description),
                                            'Currency'      => getStringValue("Rs. "),
                                            'Price'         => getStringValue($FoodData->F_Price),
                                            'DisplayImage'  => getStringValue($DisplayImage),
                                            'Type'          => getStringValue($FoodData->F_Type),
                                            'CartQuantity'  => $ExistingCartDetailData->RD_Quantity,
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

                            $RestaurantsCartItemsCount = (string) $RestaurantsCartItemsCount;
    
                        } else {
                            //echo "Not Matching 3";
                            $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                        }
    
                        $RestaurantRecord = array(
                            'CartID' => getStringValue($ExistingCartData['RCartID']),
                            'RestaurantID' => getStringValue($ExistingCartData['RC_RestaurantID']),
                            'FoodsCount' => $FoodsCount,
                            'FoodsData' => $FoodsRecords,
                            'Currency' => "Rs. ",
                            'ItemsCount' => $RestaurantsCartItemsCount,
                            'ItemTotal' => $ExistingCartData['RC_ItemTotal'],
                            'DeliveryCharges' => $ExistingCartData['RC_DeliveryCharge'],
                            'CartTotal' => $ExistingCartData['RC_CartTotal'],
                        );
                        array_push($RestaurantsRecords, $RestaurantRecord);
                      $RestaurantsCart = '2';
                    }

                  
                }
                else{
                    $RestaurantRecord= array('status' => 'info', 'flag' => '4', 'message' => 'Restaurant Cart is Empty', 'restaurantCartArray'=>$ExistingRestaurantCartArray);
                    array_push($RestaurantsRecords, $RestaurantRecord);
                }

                //Display The Cart Details on the Customer App -> GetCartDetails

                $CartData['ServicesCart'] = $ServicesCart;
                $CartData['ServicesCartData'] = $ServicesRecords;
                $CartData['ProductsCart'] = $ProductsCart;
                $CartData['ProductsCartData'] = $ProductsRecords;
                $CartData['RestaurantsCart'] = $RestaurantsCart;
                $CartData['RestaurantsCartData'] = $RestaurantsRecords;

                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Cart Fetched Successfully', 'data' => $CartData);

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'GetOrderHistory') {

            if (!empty($data['Val_Customer'])) {

//                    $ServicesOrdersArray        =  $this->Cart_model->get(NULL,array('C_OrderStatus <>'=>'0','C_OrderStatus <>'=>'4','C_OrderStatus <>'=>'5','C_Status'=>'3','C_CustomerID'=>$data['Val_Customer']));
                $ServicesOrdersArray = $this->Cart_model->get(null,array('C_CustomerID' => $data['Val_Customer'],'C_OrderStatus'=>'1','C_Status'=>'2'));
//                    $ProductsOrdersArray        =  $this->Cart_model->getProductsCart(NULL,array('PC_OrderStatus <>'=>'0','PC_OrderStatus <>'=>'4','PC_OrderStatus <>'=>'5','PC_Status'=>'3','PC_CustomerID'=>$data['Val_Customer']));

                $ProductsOrdersArray = $this->Cart_model->getProductsCart(null, array('PC_CustomerID' => $data['Val_Customer'],'PC_OrderStatus'=>'1','PC_Status'=>'2'));

//                    $RestaurantsOrdersArray        =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_OrderStatus <>'=>'0','RC_OrderStatus <>'=>'4','RC_OrderStatus <>'=>'5','RC_Status'=>'3','RC_CustomerID'=>$data['Val_Customer']));
                $RestaurantsOrdersArray = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer'],'RC_OrderStatus'=>'1','rC_Status'=>'2'));

                $OngoingOrders = array();
                $OngoingOrdersCount = '0';

                $ServicesOrdersRecords = array();

                if (!empty($ServicesOrdersArray)) {

                    foreach ($ServicesOrdersArray as $ServiceOrderArray) {

                        $ServiceOrderData = (object) $ServiceOrderArray;
                        $CartID = $ServiceOrderData->CartID;

                        $OptionsCount = "0";
                        $OptionsData = array();
                        $OptionNames = json_decode($ServiceOrderData->C_OptionNames);
                        $PackageNames = json_decode($ServiceOrderData->C_PackageNames);
                        $OptionPrices = json_decode($ServiceOrderData->C_OptionPrices);

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
                        $OptionsCount = (string) count($OptionsData);
                        $ServicesOrdersRecords = array(
                            'OrderID' => getStringValue($ServiceOrderData->CartID),
                            'OrderName' => getStringValue(getOrderName('1', $ServiceOrderData->CartID)),
                            'Type' => '1',
                            //    'OptionsCount'     => $OptionsCount,
                            //    'OptionsData'     => $OptionsData,
                            'Currency' => "Rs. ",
                            'OrderTotal' => $ServiceOrderData->C_Total,
                            'OrderDate' => $ServiceOrderData->C_BookedDttm,
                        );
                        array_push($OngoingOrders, $ServicesOrdersRecords);
                    }
                    //print_r($ServicesOrdersRecords);
                    //                            array_push($OngoingOrders,$ServicesOrdersRecords);
                    //                            $OngoingOrders        = $ServicesOrdersRecords;
                }

                $ProductsOrdersRecords = array();
                //$ProductsCartItemsCount    = 0;
                if (!empty($ProductsOrdersArray)) {
                    foreach ($ProductsOrdersArray as $ProductOrderArray) {
                        $ProductOrderData = (object) $ProductOrderArray;
                        $CartID = $ProductOrderData->PCartID;

                        $ProductsCount = "0";
                        $ProductsData = array();
                        $ProductsDetailsArray = json_decode($ProductOrderData->PC_DetailID);
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
                                //    $ProductsCartItemsCount = $ProductsCartItemsCount + $ProductDetailData->PD_Quantity;

                                $Index++;
                            }
                        } else {
                            //echo "Not Matching 3";
                            //$ProductsCartItemsCount = $ProductsCartItemsCount + 0;
                        }
                        $ProductsCount = (string) count($ProductsData);
                        $ProductsOrdersRecords = array(
                            'OrderID' => getStringValue($CartID),
                            'OrderName' => getStringValue(getOrderName('2', $CartID)),
                            'Type' => '2',
                            //'ProductsCount'     => $ProductsCount,
                            //'ProductsData'         => $ProductsData,
                            'Currency' => "Rs. ",
                            //'ItemsCount'         => $ProductsCartItemsCount,
                            //'ItemTotal'            => $ProductOrderData->PC_ItemTotal,
                            //'DeliveryCharges'    => $ProductOrderData->PC_DeliveryCharge,
                            'OrderTotal' => $ProductOrderData->PC_Total,
                            'OrderDate' => $ProductOrderData->PC_BookedDttm,
                        );
                        array_push($OngoingOrders, $ProductsOrdersRecords);
                    }
                    //print_r($ProductsOrdersRecords);
                    //array_push($OngoingOrders,$ProductsOrdersRecords);
                }

                $RestaurantsOrdersRecords = array();
                //$RestaurantsCartItemsCount    = 0;
                if (!empty($RestaurantsOrdersArray)) {
                    foreach ($RestaurantsOrdersArray as $RestaurantOrderArray) {
                        $RestaurantOrderData = (object) $RestaurantOrderArray;

                        $DetailIDsJson = $RestaurantOrderData->RC_DetailID;
                        $DetailIDsArray = json_decode($DetailIDsJson);

                        //$RestaurantsCartItemsCount = 0;
                        $FoodsRecords = array();
                        $FoodsCount = 0;
                        if (!empty($DetailIDsArray)) {
                            $FoodsRecords = array();
                            $FoodsCount = 0;
                            foreach ($DetailIDsArray as $DetailID) {
                                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                $FoodID = '';
                                if (!empty($ExistingCartDetailData)) {
                                    //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + $ExistingCartDetailData->RD_Quantity;
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
                                        'CartQuantity' => $ExistingCartDetailData->RD_Quantity,
                                    )
                                    );

                                } else {
                                    //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                                }
                            }
                            $FoodsCount = (string) count($FoodsRecords);
                            /*if(!empty($ExistingCartData->RC_ItemCount))
                        $RestaurantsCartItemsCount     = $ExistingCartData->RC_ItemCount;
                        else
                        $RestaurantsCartItemsCount     = $RestaurantsCartItemsCount;

                        $RestaurantsCartItemsCount     = (string)$RestaurantsCartItemsCount;*/

                        } else {
                            //echo "Not Matching 3";
                            //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                        }

                        $RestaurantsOrdersRecords = array(
                            'OrderID' => getStringValue($RestaurantOrderData->RCartID),
                            'OrderName' => getStringValue(getOrderName('3', $RestaurantOrderData->RCartID)),
                            'Type' => "3",
                            //'RestaurantID'         => getStringValue($RestaurantOrderData->RC_RestaurantID),
                            //'FoodsCount'        => $FoodsCount,
                            //'FoodsData'         => $FoodsRecords,
                            'Currency' => "Rs. ",
                            //'ItemsCount'         => $RestaurantsCartItemsCount,
                            //'ItemTotal'         => $RestaurantOrderData->RC_ItemTotal,
                            //'DeliveryCharges'     => $RestaurantOrderData->RC_DeliveryCharge,
                            'OrderTotal' => $RestaurantOrderData->RC_Total,
                            'OrderDate' => $RestaurantOrderData->RC_BookedDttm,
                        );
                        array_push($OngoingOrders, $RestaurantsOrdersRecords);

                    }

                }

                //print_r($OngoingOrders);
                //usort($OngoingOrders, 'date_compare');
                foreach ($OngoingOrders as $key => $part) {
                    $OngoingOrdersSort[$key] = strtotime($part['OrderDate']);
                }
                if (!empty($OngoingOrders)) {
                    array_multisort($OngoingOrdersSort, SORT_DESC, $OngoingOrders);
                    $OngoingOrdersCount = (string) count($OngoingOrders);

                }

//                    $ServicesPastOrdersArray        =  $this->Cart_model->get(NULL,array('C_OrderStatus'=>'4','C_OrderStatus'=>'5','C_Status'=>'3','C_Status'=>'4','C_CustomerID'=>$data['Val_Customer']));
                $ServicesPastOrdersArray = $this->Cart_model->get(null, array('C_CustomerID' => $data['Val_Customer']), "C_Status NOT IN (1,2) AND C_OrderStatus NOT IN (1,2,3)");
//                    $ProductsPastOrdersArray        =  $this->Cart_model->getProductsCart(NULL,array('PC_OrderStatus'=>'4','PC_OrderStatus'=>'5','PC_Status'=>'3','PC_Status'=>'4','PC_CustomerID'=>$data['Val_Customer']));
                $ProductsPastOrdersArray = $this->Cart_model->getProductsCart(null, array('PC_CustomerID' => $data['Val_Customer']), "PC_Status NOT IN (1,2) AND PC_OrderStatus NOT IN (1,2,3)");

//                    $RestaurantsPastOrdersArray        =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_OrderStatus'=>'4','RC_OrderStatus'=>'5','RC_Status'=>'3','RC_Status'=>'4','RC_CustomerID'=>$data['Val_Customer']));
                $RestaurantsPastOrdersArray = $this->Cart_model->getRestaurantsCart(null, array('RC_CustomerID' => $data['Val_Customer']), "RC_Status NOT IN (1,2) AND RC_OrderStatus NOt IN (1,2,3)");

                $PastOrders = array();
                $PastOrdersCount = '0';
                $ServicesPastOrdersRecords = array();

                if (!empty($ServicesPastOrdersArray)) {

                    foreach ($ServicesPastOrdersArray as $ServicePastOrderArray) {

                        $ServicePastOrderData = (object) $ServicePastOrderArray;
                        $CartID = $ServicePastOrderData->CartID;

                        $OptionsCount = "0";
                        $OptionsData = array();
                        $OptionNames = json_decode($ServicePastOrderData->C_OptionNames);
                        $PackageNames = json_decode($ServicePastOrderData->C_PackageNames);
                        $OptionPrices = json_decode($ServicePastOrderData->C_OptionPrices);

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
                        $OptionsCount = (string) count($OptionsData);
                        $ServicesPastOrdersRecords = array(
                            'OrderID' => getStringValue($ServicePastOrderData->CartID),
                            'OrderName' => getStringValue(getOrderName('1', $ServicePastOrderData->CartID)),
                            'Type' => '1',
                            //    'OptionsCount'     => $OptionsCount,
                            //    'OptionsData'     => $OptionsData,
                            'Currency' => "Rs. ",
                            'OrderTotal' => $ServicePastOrderData->C_Total,
                            'OrderDate' => $ServicePastOrderData->C_BookedDttm,
                        );
                        array_push($PastOrders, $ServicesPastOrdersRecords);
                    }
                    //print_r($ServicesOrdersRecords);
                    //                            array_push($OngoingOrders,$ServicesOrdersRecords);
                    //                            $OngoingOrders        = $ServicesOrdersRecords;
                }

                $ProductsPastOrdersRecords = array();
                //$ProductsCartItemsCount    = 0;
                if (!empty($ProductsPastOrdersArray)) {
                    foreach ($ProductsPastOrdersArray as $ProductPastOrderArray) {
                        $ProductPastOrderData = (object) $ProductPastOrderArray;
                        $CartID = $ProductPastOrderData->PCartID;

                        $ProductsCount = "0";
                        $ProductsData = array();
                        $ProductsDetailsArray = json_decode($ProductPastOrderData->PC_DetailID);
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
                                //    $ProductsCartItemsCount = $ProductsCartItemsCount + $ProductDetailData->PD_Quantity;

                                $Index++;
                            }
                        } else {
                            //echo "Not Matching 3";
                            //$ProductsCartItemsCount = $ProductsCartItemsCount + 0;
                        }
                        $ProductsCount = (string) count($ProductsData);
                        $ProductsPastOrdersRecords = array(
                            'OrderID' => getStringValue($CartID),
                            'OrderName' => getStringValue(getOrderName('2', $ProductPastOrderData->PCartID)),
                            'Type' => '2',
                            //'ProductsCount'     => $ProductsCount,
                            //'ProductsData'         => $ProductsData,
                            'Currency' => "Rs. ",
                            //'ItemsCount'         => $ProductsCartItemsCount,
                            //'ItemTotal'            => $ProductOrderData->PC_ItemTotal,
                            //'DeliveryCharges'    => $ProductOrderData->PC_DeliveryCharge,
                            'OrderTotal' => $ProductPastOrderData->PC_Total,
                            'OrderDate' => $ProductPastOrderData->PC_BookedDttm,
                        );
                        array_push($PastOrders, $ProductsPastOrdersRecords);
                    }
                    //print_r($ProductsOrdersRecords);
                    //array_push($OngoingOrders,$ProductsOrdersRecords);
                }

                $RestaurantsPastOrdersRecords = array();
                //$RestaurantsCartItemsCount    = 0;
                if (!empty($RestaurantsPastOrdersArray)) {
                    foreach ($RestaurantsPastOrdersArray as $RestaurantPastOrderArray) {
                        $RestaurantPastOrderData = (object) $RestaurantPastOrderArray;

                        $DetailIDsJson = $RestaurantPastOrderData->RC_DetailID;
                        $DetailIDsArray = json_decode($DetailIDsJson);

                        //$RestaurantsCartItemsCount = 0;
                        $FoodsRecords = array();
                        $FoodsCount = 0;
                        if (!empty($DetailIDsArray)) {
                            $FoodsRecords = array();
                            $FoodsCount = 0;
                            foreach ($DetailIDsArray as $DetailID) {
                                $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                $FoodID = '';
                                if (!empty($ExistingCartDetailData)) {
                                    //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + $ExistingCartDetailData->RD_Quantity;
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
                                        'CartQuantity' => $ExistingCartDetailData->RD_Quantity,
                                    )
                                    );

                                } else {
                                    //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                                }
                            }
                            $FoodsCount = (string) count($FoodsRecords);
                            /*if(!empty($ExistingCartData->RC_ItemCount))
                        $RestaurantsCartItemsCount     = $ExistingCartData->RC_ItemCount;
                        else
                        $RestaurantsCartItemsCount     = $RestaurantsCartItemsCount;

                        $RestaurantsCartItemsCount     = (string)$RestaurantsCartItemsCount;*/

                        } else {
                            //echo "Not Matching 3";
                            //$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                        }

                        $RestaurantsPastOrdersRecords = array(
                            'OrderID' => getStringValue($RestaurantPastOrderData->RCartID),
                            'OrderName' => getStringValue(getOrderName('3', $RestaurantPastOrderData->RCartID)),
                            'Type' => "3",
                            //'RestaurantID'         => getStringValue($RestaurantOrderData->RC_RestaurantID),
                            //'FoodsCount'        => $FoodsCount,
                            //'FoodsData'         => $FoodsRecords,
                            'Currency' => "Rs. ",
                            //'ItemsCount'         => $RestaurantsCartItemsCount,
                            //'ItemTotal'         => $RestaurantOrderData->RC_ItemTotal,
                            //'DeliveryCharges'     => $RestaurantOrderData->RC_DeliveryCharge,
                            'OrderTotal' => $RestaurantPastOrderData->RC_Total,
                            'OrderDate' => $RestaurantPastOrderData->RC_BookedDttm,
                        );
                        array_push($PastOrders, $RestaurantsPastOrdersRecords);

                    }

                }

                //print_r($OngoingOrders);
                //usort($OngoingOrders, 'date_compare');
                foreach ($PastOrders as $key => $part) {
                    $PastOrdersSort[$key] = strtotime($part['OrderDate']);
                }

                if (!empty($PastOrders)) {

                    array_multisort($PastOrdersSort, SORT_DESC, $PastOrders);
                    $PastOrdersCount = (string) count($PastOrders);
                }

                //print_r($OngoingOrders);
                //                    $OngoingOrders        = $ServicesOrderRecords;
                //print_r($OngoingOrders);
                //exit;

                /*
                $ProductsCart     = '1';
                $ProductsRecords     = (object)array();
                $ProductsCartItemsCount    = 0;
                if(!empty($ExistingProductCartArray))
                {
                $ExistingProductCartData     = (object)$ExistingProductCartArray[0];
                $CartID                        = $ExistingProductCartData->PCartID;

                $ProductsCount = "0";
                $ProductsData  = array();
                $ProductsDetailsArray = json_decode($ExistingProductCartData->PC_DetailID);
                $Index = 0;
                if(!empty($ProductsDetailsArray))
                {
                foreach($ProductsDetailsArray as $ProductDetail)
                {

                $ProductDetailData        = $this->Cart_model->getProductsCartDetails($ProductDetail);

                $ProductData            = $this->Products_model->get($ProductDetailData->PD_ProductID);
                if(!empty($ProductDetailData->PD_AttributeID))
                {
                $ProductAttributeData    = $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
                $AttributeTitle            = $ProductAttributeData->A_Title;
                }
                else
                {
                $AttributeTitle            = "";
                }
                if(!empty($ProductDetailData->PD_AttribValueID))
                {
                $ProductAttribValueData    = $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
                $AttributeValueTitle    = $ProductAttribValueData->V_Title;
                }
                else
                {
                $AttributeValueTitle    = "";
                }

                $FeaturedImage = '';
                $FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
                $ProductsData[] = array(
                'DetailID'        => $ProductDetailData->CPDetailID,
                'ProductID'        => $ProductDetailData->PD_ProductID,
                'Title'            => $ProductData->P_Name,
                'Attribute'        => $AttributeTitle,
                'AttributeValue'=> $AttributeValueTitle,
                'Currency'        => "Rs. ",
                'Price'            => $ProductData->P_Price,
                'Quantity'        => $ProductDetailData->PD_Quantity,
                'FeaturedImage'    => $FeaturedImage,
                );
                $ProductsCartItemsCount = $ProductsCartItemsCount + $ProductDetailData->PD_Quantity;

                $Index++;
                }
                }
                else{
                //echo "Not Matching 3";
                $ProductsCartItemsCount = $ProductsCartItemsCount + 0;
                }
                $ProductsCount         = (string)count($ProductsData);
                $ProductsRecords     = array(
                'OrderID'             => getStringValue($CartID),
                'ProductsCount'     => $ProductsCount,
                'ProductsData'         => $ProductsData,
                'Currency'            =>"Rs. ",
                'ItemsCount'         => $ProductsCartItemsCount,
                'ItemTotal'            => $ExistingProductCartData->PC_ItemTotal,
                'DeliveryCharges'    => $ExistingProductCartData->PC_DeliveryCharge,
                'CartTotal'            => $ExistingProductCartData->PC_CartTotal,

                );
                $ProductsCart     = '2';
                }

                $RestaurantsCart     = '1';
                $RestaurantsRecords     = (object)array();
                $RestaurantsCartItemsCount    = 0;
                if(!empty($ExistingRestaurantCartArray))
                {
                $ExistingCartData = (object)$ExistingRestaurantCartArray[0];

                $DetailIDsJson             = $ExistingCartData->RC_DetailID;
                $DetailIDsArray            = json_decode($DetailIDsJson);

                $RestaurantsCartItemsCount = 0;
                $FoodsRecords    = array();
                $FoodsCount     = 0;
                if(!empty($DetailIDsArray))
                {
                $FoodsRecords    = array();
                $FoodsCount     = 0;
                foreach($DetailIDsArray as $DetailID)
                {
                $ExistingCartDetailData    =  $this->Cart_model->getRestaurantsCartDetails($DetailID);
                $FoodID = '';
                if(!empty($ExistingCartDetailData))
                {
                $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + $ExistingCartDetailData->RD_Quantity;
                $FoodID         = $ExistingCartDetailData->RD_FoodID;

                $FoodData         = $this->Restaurants_model->getFoods($FoodID);

                $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodData->RFoodID.'/'.$FoodData->F_DisplayImage : '');
                array_push($FoodsRecords,array(
                'FoodID'=>getStringValue($FoodData->RFoodID),

                'Title'=>getStringValue($FoodData->F_Title),
                'Description'=>getStringValue($FoodData->F_Description),
                'Currency'=>getStringValue("Rs. "),
                'Price'=>getStringValue($FoodData->F_Price),
                'DisplayImage'=>getStringValue($DisplayImage),
                'Type'=>getStringValue($FoodData->F_Type),
                'CartQuantity'=>$ExistingCartDetailData->RD_Quantity
                )
                );

                }
                else{
                $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                }
                }
                $FoodsCount = (string)count($FoodsRecords);
                if(!empty($ExistingCartData->RC_ItemCount))
                $RestaurantsCartItemsCount     = $ExistingCartData->RC_ItemCount;
                else
                $RestaurantsCartItemsCount     = $RestaurantsCartItemsCount;

                $RestaurantsCartItemsCount     = (string)$RestaurantsCartItemsCount;

                }
                else{
                //echo "Not Matching 3";
                $RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
                }

                $RestaurantsRecords = array(
                'OrderID'             => getStringValue($ExistingCartData->RCartID),
                'RestaurantID'         => getStringValue($ExistingCartData->RC_RestaurantID),
                'FoodsCount'        => $FoodsCount,
                'FoodsData'         => $FoodsRecords,
                'Currency'             => "Rs. ",
                'ItemsCount'         => $RestaurantsCartItemsCount,
                'ItemTotal'         => $ExistingCartData->RC_ItemTotal,
                'DeliveryCharges'     => $ExistingCartData->RC_DeliveryCharge,
                'CartTotal'         => $ExistingCartData->RC_CartTotal,
                );
                $RestaurantsCart     = '2';
                }
                 */

/*                    $CartData['ServicesCart']             = $ServicesCart;
$CartData['ServicesCartData']         = $ServicesRecords;
$CartData['ProductsCart']             = $ProductsCart;
$CartData['ProductsCartData']         = $ProductsRecords;
$CartData['RestaurantsCart']         = $RestaurantsCart;
$CartData['RestaurantsCartData']    = $RestaurantsRecords;
 */

                $OrderData['OngoingOrdersCount'] = $OngoingOrdersCount;
                $OrderData['OngoingOrders'] = $OngoingOrders;
                $OrderData['PastOrdersCount'] = $PastOrdersCount;
                $OrderData['PastOrders'] = $PastOrders;
                $result = array('status' => 'success', 'flag' => '1', 'message' => 'Orders Fetched Successfully.', 'data' => $OrderData);

                //print_r($ExistingServiceCartArray);
                //print_r($ExistingProductCartArray);
                //print_r($ExistingRestaurantCartArray);
                //    exit;

            } else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else if (!empty($data['Action']) && $data['Action'] == 'GetOrderDetails') {

            if (!empty($data['Val_Customer']) && !empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Order'])) {
                $ServiceOrderArray = $this->Cart_model->get($data['Val_Order'], array('C_CustomerID' => $data['Val_Customer']));

                $OngoingOrders = array();
                $OngoingOrdersCount = '0';

                $ServiceOrderRecord = array();
                if (!empty($ServiceOrderArray)) {
                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                    $ServiceOrderData =(object) $ServiceOrderArray[0];
                    $CartID = $ServiceOrderData->CartID;

                    $OptionsCount = "0";
                    $OptionsData = array();
                    $OptionNames = json_decode($ServiceOrderData->C_OptionNames);
                    $PackageNames = json_decode($ServiceOrderData->C_PackageNames);
                    $OptionPrices = json_decode($ServiceOrderData->C_OptionPrices);

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
                    $OptionsCount = (string) count($OptionsData);

                    $AddressData = $this->Customers_model->getAddresses($ServiceOrderData->C_AddressID, array('A_RelationID' => $data['Val_Customer'], 'A_Type' => '1'));

                    $AddressID = "";
                    $AddressName = "";
                    $AddressLine = "";
                    $AddressLocation = "";
                    $AddressLatitude = "";
                    $AddressLongitude = "";

                    if (!empty($AddressData)) {
                        $AddressID = $AddressData->AddressID;
                        $AddressName = $AddressData->A_Name;
                        $AddressLine = $AddressData->A_Address;
                        $AddressLocation = $AddressData->A_Location;
                        $AddressLatitude = $AddressData->A_Latitude;
                        $AddressLongitude = $AddressData->A_Longitude;
                        $MobileNumber = $CustomerData->C_CountryCode . $CustomerData->C_Mobile;
                    } else {
                        $CustomerAddress = json_decode($ServiceOrderData->C_CustomerAddress);

                        $AddressID = $ServiceOrderData->C_AddressID;
                        $AddressName = $ServiceOrderData->C_CustomerName;
                        $AddressLine = $CustomerAddress[1];
                        $AddressLocation = $CustomerAddress[2];
                        $AddressLatitude = $CustomerAddress[3];
                        $AddressLongitude = $CustomerAddress[4];
                        $MobileNumber = $CustomerAddress[0];

                    }

                    if ($ServiceOrderData->C_PaymentOption == '1') {
                        $PaymentMethod = 'Paytm';
                    } else {
                        $PaymentMethod = 'KSA';
                    }

                    $ServiceOrderRecord = array(
                        'OrderID' => getStringValue($ServiceOrderData->CartID),
                        'OrderName' => getStringValue(getOrderName('1', $ServiceOrderData->CartID)),
                        'Type' => '1',
                        'OptionsCount' => $OptionsCount,
                        'OptionsData' => $OptionsData,
                        'MobileNumber' => getStringValue($MobileNumber),
                        'AddressID' => getStringValue($AddressID),
                        'AddressName' => getStringValue($AddressName),
                        'AddressLine' => getStringValue($AddressLine),
                        'AddressLocation' => getStringValue($AddressLocation),
                        'AddressLatitude' => getStringValue($AddressLatitude),
                        'AddressLongitude' => getStringValue($AddressLongitude),
                        'PaymentMethod' => getStringValue($PaymentMethod),
                        'Currency' => "Rs. ",
                        'ServiceCharges' => $ServiceOrderData->C_ServiceCharge,
                        'DeliveryCharges' => getStringValue(""),
                        'OrderTotal' => $ServiceOrderData->C_Total,
                        'OrderDate' => $ServiceOrderData->C_BookedDttm,
                    );
                    //print_r($ServicesOrdersRecords);
                    //                            array_push($OngoingOrders,$ServicesOrdersRecords);
                    //                            $OngoingOrders        = $ServicesOrdersRecords;
                    $OrderDetailArray = $ServiceOrderRecord;
                    $OrderData = $OrderDetailArray;
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Fetched Successfully.', 'data' => $OrderData);
                } else {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Order data not found.', 'data' => (object) array());
                }
            } else if (!empty($data['Val_Customer']) && !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Order'])) {
                $ProductOrderArray = $this->Cart_model->getProductsCart($data['Val_Order'], array('PC_CustomerID' => $data['Val_Customer']));
                $ProductOrderRecord = array();
                //$ProductsCartItemsCount    = 0;
                if (!empty($ProductOrderArray)) {
                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                    $ProductOrderData = $ProductOrderArray[0];
                    $CartID = $ProductOrderData->PCartID;

                    $ProductsCount = "0";
                    $ProductsData = array();
                    $ProductsDetailsArray = json_decode($ProductOrderData->PC_DetailID);
                    $ProductCartItemsCount = "0";
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
                            $ProductCartItemsCount = $ProductCartItemsCount + $ProductDetailData->PD_Quantity;

                            $Index++;
                        }
                    } else {
                        //echo "Not Matching 3";
                        $ProductCartItemsCount = $ProductCartItemsCount + 0;
                    }

                    $ProductCartItemsCount = (string) $ProductCartItemsCount;
                    $AddressData = $this->Customers_model->getAddresses($ProductOrderData->PC_AddressID, array('A_RelationID' => $data['Val_Customer'], 'A_Type' => '1'));

                    $AddressID = "";
                    $AddressName = "";
                    $AddressLine = "";
                    $AddressLocation = "";
                    $AddressLatitude = "";
                    $AddressLongitude = "";

                    if (!empty($AddressData)) {
                        $AddressID = $AddressData->AddressID;
                        $AddressName = $AddressData->A_Name;
                        $AddressLine = $AddressData->A_Address;
                        $AddressLocation = $AddressData->A_Location;
                        $AddressLatitude = $AddressData->A_Latitude;
                        $AddressLongitude = $AddressData->A_Longitude;
                        $MobileNumber = $CustomerData->C_CountryCode . $CustomerData->C_Mobile;
                    } else {
                        $CustomerAddress = json_decode($ProductOrderData->PC_CustomerAddress);

                        $AddressID = $ProductOrderData->PC_AddressID;
                        $AddressName = $ProductOrderData->PC_CustomerName;
                        $AddressLine = $CustomerAddress[1];
                        $AddressLocation = $CustomerAddress[2];
                        $AddressLatitude = $CustomerAddress[3];
                        $AddressLongitude = $CustomerAddress[4];
                        $MobileNumber = $CustomerAddress[0];

                    }
                    if ($ProductOrderData->PC_PaymentOption == '1') {
                        $PaymentMethod = 'Paytm';
                    } else {
                        $PaymentMethod = 'KSA';
                    }

                    $ProductsCount = (string) count($ProductsData);
                    $ProductOrderRecord = array(
                        'OrderID' => getStringValue($CartID),
                        'OrderName' => getStringValue(getOrderName('2', $CartID)),
                        'Type' => '2',
                        'ProductsCount' => $ProductsCount,
                        'ProductsData' => $ProductsData,
                        'MobileNumber' => getStringValue($MobileNumber),
                        'AddressID' => getStringValue($AddressID),
                        'AddressName' => getStringValue($AddressName),
                        'AddressLine' => getStringValue($AddressLine),
                        'AddressLocation' => getStringValue($AddressLocation),
                        'AddressLatitude' => getStringValue($AddressLatitude),
                        'AddressLongitude' => getStringValue($AddressLongitude),
                        'Currency' => "Rs. ",
                        'ItemsCount' => $ProductCartItemsCount,
                        'ItemTotal' => $ProductOrderData->PC_ItemTotal,
                        'PaymentMethod' => getStringValue($PaymentMethod),
                        'DeliveryCharges' => $ProductOrderData->PC_DeliveryCharge,
                        'ServiceCharges' => $ProductOrderData->PC_ServiceCharge,
                        'OrderTotal' => $ProductOrderData->PC_Total,
                        'OrderDate' => $ProductOrderData->PC_BookedDttm,
                    );
                    //array_push($OngoingOrders,$ProductsOrdersRecords);

                    //print_r($ProductsOrdersRecords);
                    //array_push($OngoingOrders,$ProductsOrdersRecords);
                    $OrderDetailArray = $ProductOrderRecord;
                    $OrderData = $OrderDetailArray;
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Fetched Successfully.', 'data' => $OrderData);

                } else {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Order data not found.', 'data' => (object) array());
                }

            } else if (!empty($data['Val_Customer']) && !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Order'])) {
                $RestaurantOrderArray = $this->Cart_model->getRestaurantsCart($data['Val_Order'], array('RC_CustomerID' => $data['Val_Customer']));
                $RestaurantOrderRecords = array();
                $RestaurantOrderItemsCount = 0;
                if (!empty($RestaurantOrderArray)) {
                    $CustomerData = $this->Customers_model->getCustomer($data['Val_Customer']);
                    $RestaurantOrderData = $RestaurantOrderArray[0];
                 //   $DetailIDsJson = $RestaurantOrderData->RC_DetailID;
                    $DetailIDsArray = json_decode($RestaurantOrderData['RC_DetailID']);

                    $RestaurantOrderItemsCount = 0;
                    $FoodsRecords = array();
                    $FoodsCount = 0;
                    if (!empty($DetailIDsArray)) {
                        $FoodsRecords = array();
                        $FoodsCount = 0;
                        foreach ($DetailIDsArray as $DetailID) {
                            $ExistingCartDetailData = $this->Cart_model->getRestaurantsCartDetails($DetailID);
                            $FoodID = '';
                            if (!empty($ExistingCartDetailData)) {
                                $RestaurantOrderItemsCount = $RestaurantOrderItemsCount + $ExistingCartDetailData->RD_Quantity;
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
                                    'CartQuantity' => $ExistingCartDetailData->RD_Quantity,
                                )
                                );

                            } else {
                                $RestaurantOrderItemsCount = $RestaurantOrderItemsCount + 0;
                            }
                        }
                        $FoodsCount = (string) count($FoodsRecords);
                        if (!empty($ExistingCartData->RC_ItemCount)) {
                            $RestaurantOrderItemsCount = $ExistingCartData->RC_ItemCount;
                        } else {
                            $RestaurantOrderItemsCount = $RestaurantOrderItemsCount;
                        }

                        $RestaurantOrderItemsCount = (string) $RestaurantOrderItemsCount;

                    }
                    $AddressData = $this->Customers_model->getAddresses($RestaurantOrderData['RC_AddressID'], array('A_RelationID' => $data['Val_Customer'], 'A_Type' => '1'));

                    $AddressID = "";
                    $AddressName = "";
                    $AddressLine = "";
                    $AddressLocation = "";
                    $AddressLatitude = "";
                    $AddressLongitude = "";

                    if (!empty($AddressData)) {
                        $AddressID = $AddressData->AddressID;
                        $AddressName = $AddressData->A_Name;
                        $AddressLine = $AddressData->A_Address;
                        $AddressLocation = $AddressData->A_Location;
                        $AddressLatitude = $AddressData->A_Latitude;
                        $AddressLongitude = $AddressData->A_Longitude;
                        $MobileNumber = $CustomerData->C_CountryCode . $CustomerData->C_Mobile;
                    } else {
                        $CustomerAddress = json_decode($RestaurantOrderData['RC_CustomerAddress']);

                        $AddressID = $RestaurantOrderData['RC_AddressID'];
                        $AddressName = $RestaurantOrderData['RC_CustomerName'];
                        $AddressLine = $CustomerAddress[1];
                        $AddressLocation = $CustomerAddress[2];
                        $AddressLatitude = $CustomerAddress[3];
                        $AddressLongitude = $CustomerAddress[4];
                        $MobileNumber = $CustomerAddress[0];

                    }
                    if ($RestaurantOrderData['RC_PaymentOption'] == '1') {
                        $PaymentMethod = 'Paytm';
                    } else {
                        $PaymentMethod = 'KSA';
                    }

                    $RestaurantOrderRecord = array(
                        'OrderID' => getStringValue($RestaurantOrderData['RCartID']),
                        'OrderName' => getStringValue(getOrderName('3', $RestaurantOrderData['RCartID'])),
                        'Type' => "3",
                        'RestaurantID' => getStringValue($RestaurantOrderData['RC_RestaurantID']),
                        'FoodsCount' => $FoodsCount,
                        'FoodsData' => $FoodsRecords,
                        'MobileNumber' => getStringValue($MobileNumber),
                        'AddressID' => getStringValue($AddressID),
                        'AddressName' => getStringValue($AddressName),
                        'AddressLine' => getStringValue($AddressLine),
                        'AddressLocation' => getStringValue($AddressLocation),
                        'AddressLatitude' => getStringValue($AddressLatitude),
                        'AddressLongitude' => getStringValue($AddressLongitude),
                        'PaymentMethod' => getStringValue($PaymentMethod),
                        'Currency' => 'Rs. ',
                        'DeliveryCharges' => $RestaurantOrderData['RC_DeliveryCharge'],
                        'ServiceCharges' => $RestaurantOrderData['RC_ServiceCharge'],
                        'OrderTotal' => $RestaurantOrderData['RC_Total'],
                        'OrderDate' => $RestaurantOrderData['RC_BookedDttm'],
                    );
                    //array_push($OngoingOrders,$RestaurantsOrdersRecords);
                    $OrderDetailArray = $RestaurantOrderRecord;
                    $OrderData = $OrderDetailArray;
                    $result = array('status' => 'success', 'flag' => '1', 'message' => 'Order Fetched Successfully.', 'data' => $OrderData);
                } else {
                    $result = array('status' => 'error', 'flag' => '2', 'message' => 'Order data not found.', 'data' => (object) array());
                }

            }

            //print_r($ExistingServiceCartArray);
            //print_r($ExistingProductCartArray);
            //print_r($ExistingRestaurantCartArray);
            //    exit;
            else {
                $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
            }

        } else {

            $result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');

        }

        $this->data = $result;
        echo json_encode($this->data);

        return false;
    }

}
