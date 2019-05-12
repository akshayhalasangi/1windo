<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendors_model extends W_Model
{
    private $vendor_data = array(
        'VendorID' => 'Val_Vendor', 'V_FirstName' => 'Val_Vfirstname', 'V_LastName' => 'Val_Vlastname',
        'V_Email' => 'Val_Vemailaddress', 'V_CountryCode' => 'Val_Vcountrycode', 'V_Mobile' => 'Val_Vmobilenumber',
        'V_Gender' => 'Val_Vgender', 'V_ProfileImage' => 'Val_Vprofileimage', 'V_CategoryID' => 'Val_Category',
        'V_Latitude' => 'Val_Vlatitude', 'V_Longitude' => 'Val_Vlongitude', 'V_Address' => 'Val_Vaddress',
        'V_Location' => 'Val_Vlocation', 'V_City' => 'Val_Vcity', 'V_Country' => 'Val_Vcountry',
        'V_VerificationMessage' => 'Val_Vverificationmessage', 'V_ProfileStatus' => 'Val_Vprofilestatus',
        'V_VerificationStatus' => 'Val_Vverificationstatus', 'V_Status' => 'Val_Vstatus', 'RowAddedDttm' => '',
        'RowUpdatedDttm' => ''
    );

    private $profile_data = array(
        'VProfileID' => 'Val_Profile', 'P_VendorID' => 'Val_Vendor', 'P_IDCardType' => 'Val_Pidcardtype',
        'P_IDCardName' => 'Val_Pcardname', 'P_IDCardNumber' => 'Val_Pidcardnumber',
        'P_IDCardFrontImage' => 'Val_Pidcardfrontimage', 'P_IDCardBackImage' => 'Val_Pidcardbackimage',
        'P_IDCardStatus' => 'Val_Pidcardstatus', 'P_GuardianName' => 'Val_Pguardianname', 'P_Gender' => 'Val_Pgender',
        'P_BirthDate' => 'Val_Pbirthdate', 'P_PermanentBuilding' => 'Val_Ppermanentbuilding',
        'P_PermanentLocality' => 'Val_Ppermanentlocality', 'P_PermanentCity' => 'Val_Ppermanentcity',
        'P_PermanentState' => 'Val_Ppermanentstate', 'P_PermanentPincode' => 'Val_Ppermanentpincode',
        'P_PersonalStatus' => 'Val_Ppersonalstatus', 'P_CurrentBuilding' => 'Val_Pcurrentbuilding',
        'P_CurrentLocality' => 'Val_Pcurrentlocality', 'P_CurrentCity' => 'Val_Pcurrentcity',
        'P_CurrentState' => 'Val_Pcurrentstate', 'P_CurrentPincode' => 'Val_Pcurrentpincode',
        'P_CurrentStatus' => 'Val_Pcurrentstatus', 'P_TermsStatus' => 'Val_Ptermsstatus', 'P_Status' => 'Val_Pstatus',
        'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $about_data = array(
        'VAboutID' => 'Val_About', 'A_VendorID' => 'Val_Vendor', 'A_BusinessName' => 'Val_Abusinessname',
        'A_BusinessPresence' => 'Val_Abusinesspresence', 'A_ProfileLink' => 'Val_Aprofilelink',
        'A_FacebookLink' => 'Val_Afacebooklink', 'A_WorkLinks' => 'Val_Aworklinks',
        'A_PhoneNumber' => 'Val_Aphonenumber', 'A_Type' => 'Val_Atype', 'A_ExperienceYear' => 'Val_Aexperienceyear',
        'A_ExperienceMonth' => 'Val_Aexperiencemonth', 'A_Introduction' => 'Val_Aintroduction',
        'A_StartingPrice' => 'Val_Astartingprice', 'A_Specialization' => 'Val_Aspecialization',
        'A_Status' => 'Val_Astatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );


    private $work_data = array(
        'VWorkID' => 'Val_Work', 'W_VendorID' => 'Val_Vendor', 'W_WorksGallery' => 'Val_Wworksgallery',
        'W_Status' => 'Val_Wstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $location_data = array(
        'VLocationID' => 'Val_Location', 'L_VendorID' => 'Val_Vendor', 'L_Location' => 'Val_Llocation',
        'L_Latitude' => 'Val_Llatitude', 'L_Longitude' => 'Val_Llongitude', 'L_Radius' => 'Val_Lradius',
        'L_Status' => 'Val_Lstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $address_data = array(
        'AddressID' => 'Val_Address', 'A_Type' => 'Val_Type', 'A_RelationID' => 'Val_Relation', 'A_Name' => 'Val_Aname',
        'A_Address' => 'Val_Aaddress', 'A_Location' => 'Val_Alocation', 'A_Latitude' => 'Val_Alatitude',
        'A_Longitude' => 'Val_Alongitude', 'A_Status' => 'Val_Astatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $account_data = array(
        'VAccountID' => 'Val_Account', 'A_VendorID' => 'Val_Vendor', 'A_GSTName' => 'Val_Agstname',
        'A_GSTNumber' => 'Val_Agstnumber', 'A_AccountName' => 'Val_Aaccountname', 'A_AccountType' => 'Val_Aaccounttype',
        'A_AccountNumber' => 'Val_Aaccountnumber', 'A_IFSCNumber' => 'Val_Aifscnumber', 'A_Status' => 'Val_Astatus',
        'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );


    public function __construct()
    {
        parent::__construct();
    }

    /* Get vendor data */
    public function getVendor($vendorid = '')
    {
        if (!empty($vendorid)) {
            $this->db->where('VendorID', $vendorid);
            return $this->db->get(TBL_VENDORS)->row();
        } else {
            $staffID = get_staff_user_id();
            $this->db->where('Staff_ID', $staffID);
            $result = $this->db->get('staffs')->row();
            switch ($result->S_IsAdmin) {
                case 0:
                    $this->db->order_by('VendorID', 'DESC');
                    return $this->db->get(TBL_VENDORS)->result_array();
                    break;
                case 1:
                    $this->db->where('V_Area', $result->Area);
                    $this->db->order_by('VendorID', 'DESC');
                    return $this->db->get(TBL_VENDORS)->result_array();
                    break;
            }
        }
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getAddresses($address_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($address_id != '') {
            $this->db->where('AddressID', $address_id);
            $query = $this->db->get(TBL_VENDORS_ADDRESSES);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('AddressID', $address_id);
                $result = $this->db->get(TBL_VENDORS_ADDRESSES)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('AddressID', $orderby);
        $query = $this->db->get(TBL_VENDORS_ADDRESSES);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('AddressID', $orderby);
            $result = $this->db->get(TBL_VENDORS_ADDRESSES)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getProfile($profile_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($profile_id != '') {
            $this->db->where('VProfileID', $profile_id);
            $query = $this->db->get(TBL_VENDORS_PROFILES);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('VProfileID', $profile_id);
                $result = $this->db->get(TBL_VENDORS_PROFILES)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('VProfileID', $orderby);
        $query = $this->db->get(TBL_VENDORS_PROFILES);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('VProfileID', $orderby);
            $result = $this->db->get(TBL_VENDORS_PROFILES)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getAbout($about_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($about_id != '') {
            $this->db->where('VAboutID', $about_id);
            $query = $this->db->get(TBL_VENDORS_ABOUT);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('VAboutID', $about_id);
                $result = $this->db->get(TBL_VENDORS_ABOUT)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('VAboutID', $orderby);
        $query = $this->db->get(TBL_VENDORS_ABOUT);
        $sql = $this->db->last_query();

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('VAboutID', $orderby);
            $result = $this->db->get(TBL_VENDORS_ABOUT)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getWorks($work_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($work_id != '') {
            $this->db->where('VWorkID', $work_id);
            $query = $this->db->get(TBL_VENDORS_WORKS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('VWorkID', $work_id);
                $result = $this->db->get(TBL_VENDORS_WORKS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('VWorkID', $orderby);
        $query = $this->db->get(TBL_VENDORS_WORKS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('VWorkID', $orderby);
            $result = $this->db->get(TBL_VENDORS_WORKS)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getLocations($location_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($location_id != '') {
            $this->db->where('VLocationID', $location_id);
            $query = $this->db->get(TBL_VENDORS_LOCATIONS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('VLocationID', $location_id);
                $result = $this->db->get(TBL_VENDORS_LOCATIONS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('VLocationID', $orderby);
        $query = $this->db->get(TBL_VENDORS_LOCATIONS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('VLocationID', $orderby);
            $result = $this->db->get(TBL_VENDORS_LOCATIONS)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get vendor addresses
     * @param  mixed  $address_id
     * @param  array  $where  perform where in query array('F_Status' => 1)
     * @return array
     */
    public function getAccounts($account_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($account_id != '') {
            $this->db->where('VAccountID', $account_id);
            $query = $this->db->get(TBL_VENDORS_ACCOUNTS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('VAccountID', $account_id);
                $result = $this->db->get(TBL_VENDORS_ACCOUNTS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('VAccountID', $orderby);
        $query = $this->db->get(TBL_VENDORS_ACCOUNTS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('VAccountID', $orderby);
            $result = $this->db->get(TBL_VENDORS_ACCOUNTS)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /**
     * Get services steps
     * @param  mixed  $review_id
     * @param  array  $where  perform where in query array('R_Status' => 1)
     * @return array
     */
    public function getReviews($review_id = '', $where = array(), $orderby = 'DESC')
    {

        $this->db->where($where);

        if ($review_id != '') {
            $this->db->where('ReviewID', $review_id);
            $query = $this->db->get(TBL_REVIEWS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                $this->db->where('ReviewID', $review_id);
                $result = $this->db->get(TBL_REVIEWS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('ReviewID', $orderby);
        $query = $this->db->get(TBL_REVIEWS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('ReviewID', $orderby);
            $result = $this->db->get(TBL_REVIEWS)->result_array();
        } else {
            $result = false;
        }


        return $result;
    }

    /* Get registered vendor's email
     * @param mixed $email
     * @return array
     */
    public function getEmail($email)
    {
        $this->db->where('C_Email', $email);
        return $this->db->get(TBL_VENDORS)->result_array();
    }

    /**
     * Get/Check vendor by mobile number that are added
     * @param  mixed  $id  vendor id
     * @return array
     */
    public function getByMobile($countrycode, $mobile, $where = array())
    {
        $this->db->where('V_CountryCode', $countrycode);
        $this->db->where('V_Mobile', $mobile);
        $this->db->or_where($where);
        $result = $this->db->get(TBL_VENDORS)->row();
        //echo $this->db->last_query();
        return $result;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function add($data)
    {


        $vendor_data = array();
        foreach ($this->vendor_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $vendor_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_user_added', $data);

        $this->db->insert(TBL_VENDORS, $vendor_data);


        $vendorid = $this->db->insert_id();
        if ($vendorid) {

            do_action('after_vendor_added', $vendorid);
            $_new_user_log = $data['Val_Vmobilenumber'];

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_user_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Created ['.$_new_user_log.']', $_is_staff);
        }

        return $vendorid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addAddress($data)
    {
        $address_data = array();
        foreach ($this->address_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $address_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_address_added', $data);

        $this->db->insert(TBL_VENDORS_ADDRESSES, $address_data);

        $addressid = $this->db->insert_id();
        if ($addressid) {

            do_action('after_address_added', $addressid);
            $_new_address_log = $addressid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Address Created ['.$_new_address_log.']', $_is_staff);
        }

        return $addressid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addProfile($data)
    {
        $profile_data = array();
        foreach ($this->profile_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $profile_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_profile_added', $data);

        $this->db->insert(TBL_VENDORS_PROFILES, $profile_data);

        $profileid = $this->db->insert_id();
        if ($profileid) {

            do_action('after_profile_added', $profileid);
            $_new_address_log = $profileid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Profile Created ['.$_new_address_log.']', $_is_staff);
        }

        return $profileid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addAbout($data)
    {
        $about_data = array();
        foreach ($this->about_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $about_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_profile_added', $data);

        $this->db->insert(TBL_VENDORS_ABOUT, $about_data);

        $aboutid = $this->db->insert_id();
        if ($aboutid) {

            do_action('after_about_added', $aboutid);
            $_new_address_log = $aboutid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor About Created ['.$_new_address_log.']', $_is_staff);
        }

        return $aboutid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addWorks($data)
    {
        $work_data = array();
        foreach ($this->work_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $work_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_works_added', $data);

        $this->db->insert(TBL_VENDORS_WORKS, $work_data);

        $workid = $this->db->insert_id();
        if ($workid) {

            do_action('after_works_added', $workid);
            $_new_address_log = $workid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Wroks Created ['.$_new_address_log.']', $_is_staff);
        }

        return $workid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addLocations($data)
    {
        $location_data = array();
        foreach ($this->location_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $location_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_locations_added', $data);

        $this->db->insert(TBL_VENDORS_LOCATIONS, $location_data);

        $locationid = $this->db->insert_id();
        if ($locationid) {

            do_action('after_works_added', $locationid);
            $_new_address_log = $locationid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Locations Created ['.$_new_address_log.']', $_is_staff);
        }

        return $locationid;
    }

    /**
     * @param  array  $_POST  data
     * @param  user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function addAccounts($data)
    {
        $account_data = array();
        foreach ($this->account_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $account_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_account_added', $data);

        $this->db->insert(TBL_VENDORS_ACCOUNTS, $account_data);

        $accountid = $this->db->insert_id();
        if ($accountid) {

            do_action('after_account_added', $accountid);
            $_new_address_log = $accountid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Account Created ['.$_new_address_log.']', $_is_staff);
        }

        return $accountid;
    }

    /* Add new  vendor */
    /*    public function add($data)
        {
            if(!empty($data)){
                $EmailExist = count($this->getEmail($data['Val_Email']));

                if($EmailExist >= 1){
                    return 'Exists';
                } else {
                    $vendor_data = array();
                    foreach ($this->vendor_data as $dbfield => $field) {
                        if (isset($data[$field])) {
                            $vendor_data[$dbfield] = $data[$field];
                        }
                    }
                    $this->load->helper('phpass');
                    $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                    $vendor_data['C_Password']             = $hasher->HashPassword($data['Val_Password']);

                    if(isset($data['CountryCode'])){
                        $vendor_data['C_CountryCode'] = '+'.$data['CountryCode'];
                    }
                    if(isset($data['Val_CountryCode'])){
                        $code = getISOCode($data['Val_CountryCode']);
                        $vendor_data['C_CountryCode'] = '+'.$code->CountryPhoneCode;
                    }
                    $this->db->insert(TBL_VENDORS, $vendor_data);

                    $vendorid = $this->db->insert_id();

                     $_new_vendor_log = null;
                    if ($vendorid) {
                        $_new_vendor_log .= _l('from_vendor').' : ' . $vendorid;
                        $_is_vendor = $vendorid;

                        logActivity(_l('txt_new_vendor_registered').' [' . $_new_vendor_log . ']', $_is_vendor);
                    }
                    return $vendorid;
                }
            }
            //return false;
        }

      */
    /**
     * Generate new password key for the vendor to reset the password.
     */
    public function ForgotPassword($email)
    {
        $table = TBL_VENDORS;
        $_id = 'VendorID';

        $this->db->where('C_Email', $email);
        $vendor = $this->db->get($table)->row();

        if ($vendor) {
            if ($vendor->C_Status != 2) {
                return array(
                    'memberinactive' => true
                );
            }

            $this->load->helper('phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $new_pass_key = random_string(6);
            if (isset($new_pass_key)) {
                $password = $hasher->HashPassword($new_pass_key);
            }

            $this->db->where($_id, $vendor->$_id);
            $this->db->update($table, array(
                'C_Password' => $password,
            ));
            $this->db->affected_rows();
            if ($this->db->affected_rows() > 0) {
                // $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['vendor'] = $vendor;
                $data['vendorid'] = $vendor->$_id;
                $send = $this->emails_model->send_enquiry_email($email, 'Forgot Password',
                    'We received a request to reset your 1Windo password. Click the below button to choose a new one. Your new password is'.$new_pass_key,
                    'ForgotPassword', $vendor->C_FullName);
                //return true;

                if ($send) {
                    return true;
                }
                return $new_pass_key;
            }
            return false;
        }
        return false;
    }

    /* Vendor reset password */
    public function changePassword($password, $vendorid)
    {
        $this->load->helper('phpass');
        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($password);
        $table = TBL_VENDORS;

        $this->db->where('VendorID', $vendorid);
        $this->db->update($table, array(
            'C_Password' => $password
        ));
        $vendor = getVendorData($vendorid);
        if ($this->db->affected_rows() > 0) {
            logActivity('Vendor Reseted Password [Vendor :'.$vendor->C_FullName.', IP:'.$this->input->ip_address().']');
            return true;
        }

        return null;
    }

    /* Update vendor informations */
    public function update($data, $vendorid)
    {
        $affectedRows = 0;
        $vendor_data = array();
        foreach ($this->vendor_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $vendor_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VendorID', $vendorid);
        // $this->db->update(TBL_VENDORS, $vendor_data);
        //echo $this->db->last_query();
        if ($this->db->update(TBL_VENDORS, $vendor_data)) {
            $affectedRows++;
            do_action('after_vendor_updated', $vendorid);
        } else {
            //No Effected Rows / Either Same data posted
        }
        if ($affectedRows > 0) {
            logActivity(_l('log_user_info_updated', _l('user_vendor'), $vendorid));

            return true;
        }

        return false;
        /*
                $affectedRows = 0;
                $vendor_data = array();
                $privacy_data = array();
                $location_data = array();
                foreach ($this->vendor_data as $dbfield => $field) {
                    if (isset($data[$field])) {
                        $vendor_data[$dbfield] = $data[$field];
                    }
                }

                if(isset($data['Val_Password'])){
                    $vendor_data['C_Password'] = $data['Val_Password'];
                }

                if(isset($data['status'])){
                    $vendor_data['C_Status'] = $data['status'];
                }

                if (empty($vendor_data['C_Password'])) {
                    unset($vendor_data['C_Password']);
                } else {
                    $this->load->helper('phpass');
                    $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                    $vendor_data['C_Password']             = $hasher->HashPassword($data['Val_Password']);
                }

                if(isset($data['Val_Countrycode'])){
                    $code = getISOCode($data['Val_CountryCode']);
                    $vendor_data['C_CountryCode'] = '+'.$code->CountryPhoneCode;
                }

                $VendorData = getVendorData($vendorid);

                if(!empty($vendor_data) ){
                    $this->db->where('VendorID', $vendorid);
                    $result =  $this->db->update(TBL_VENDORS, $vendor_data);

                    if(!isset($data['vendor'])){
                        $profile_flag = $cover_flag = $church_flag = '';

                        $profileIamge = handle_vendor_profile_image($vendorid);

                        if(isset($profileIamge['flag'])){
                            $profile_flag = $profileIamge['flag'];
                        }

                        $coverIamge = handle_vendor_cover_image($vendorid);
                        if(isset($coverIamge['flag'])){
                            $cover_flag = $coverIamge['flag'];
                        }

                        $churchIamge = handle_vendor_church_image($vendorid);
                        if(isset($churchIamge['flag'])){
                            $church_flag = $churchIamge['flag'];
                        }
                        $Response = array(
                            'VendorID' => $VendorData->VendorID,
                            'Profile_Flag' => $profile_flag,
                            'Cover_Flag' => $cover_flag,
                            'Church_Flag' => $church_flag
                            );
                    } else {
                        $Response = array(
                            'VendorID' => $VendorData->VendorID,
                            );
                    }

                if ($this->db->affected_rows() > 0) {
                    $affectedRows++;
                    do_action('after_vendor_updated', $vendorid);
                }


                if ($result > 0) {
                    logActivity('Vendor profile updated [ Vendor : ' . $VendorData->C_FullName . ']');
                }
            } else {
                $Response = array(
                    'VendorID' => $VendorData->VendorID,
                );
              if ($result > 0) {
                    logActivity('Vendor profile updated [ Vendor : ' . $VendorData->C_FullName . ']');
                }
            }
                return $Response; */

    }

    /* Update vendor informations */
    public function updateAddress($data, $addressid)
    {
        $affectedRows = 0;
        $address_data = array();
        foreach ($this->address_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $address_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('AddressID', $addressid);
        $this->db->update(TBL_VENDORS_ADDRESSES, $address_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_address_updated', $addressid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_address_info_updated', _l('user_vendor'), $addressid));

            return true;
        }

        return false;

    }

    /* Update vendor informations */
    public function updateProfile($data, $profileid)
    {
        $affectedRows = 0;
        $profile_data = array();
        foreach ($this->profile_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $profile_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VProfileID', $profileid);
        $this->db->update(TBL_VENDORS_PROFILES, $profile_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_profile_updated', $profileid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_profile_info_updated', _l('user_vendor'), $profileid));

            return true;
        }

        return false;

    }

    /* Update vendor informations */
    public function updateAbout($data, $aboutid)
    {
        $affectedRows = 0;
        $about_data = array();
        foreach ($this->about_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $about_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VAboutID', $aboutid);
        $this->db->update(TBL_VENDORS_ABOUT, $about_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_about_updated', $aboutid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_about_info_updated', _l('user_vendor'), $aboutid));

            return true;
        }

        return false;

    }

    /* Update vendor informations */
    public function updateWorks($data, $workid)
    {
        $affectedRows = 0;
        $work_data = array();
        foreach ($this->work_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $work_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VWorkID', $workid);
        $this->db->update(TBL_VENDORS_WORKS, $work_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_works_updated', $workid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_works_info_updated', _l('user_vendor'), $workid));

            return true;
        }

        return false;

    }

    /* Update vendor informations */
    public function updateLocations($data, $locationid)
    {
        $affectedRows = 0;
        $location_data = array();
        foreach ($this->location_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $location_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VLocationID', $locationid);
        $this->db->update(TBL_VENDORS_LOCATIONS, $location_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_locations_updated', $locationid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_locations_info_updated', _l('user_vendor'), $locationid));

            return true;
        }

        return false;

    }

    /* Update vendor informations */
    public function updateAccounts($data, $accountid)
    {
        $affectedRows = 0;
        $account_data = array();
        foreach ($this->account_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $account_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('VAccountID', $accountid);
        $this->db->update(TBL_VENDORS_ACCOUNTS, $account_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;

            do_action('after_account_updated', $accountid);
        } else {
            //No Effected Rows / Either Same data posted

        }
        if ($affectedRows > 0) {
            logActivity(_l('log_account_info_updated', _l('user_vendor'), $accountid));

            return true;
        }

        return false;

    }

    /**
     * Delete vendor
     */
    public function deleteVendor($vendorid)
    {
        $affectedRows = 0;
        do_action('before_vendor_deleted', $vendorid);
        $this->db->where('VendorID', $vendorid);
        $this->db->delete(TBL_VENDORS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_deleted');
            logActivity(_l('vendor_deleted').' ['.$vendorid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteAddress($addressid)
    {
        $affectedRows = 0;
        do_action('before_address_deleted', $addressid);
        $this->db->where('AddressID', $addressid);
        $this->db->delete(TBL_VENDORS_ADDRESSES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_address_deleted');
            logActivity(_l('address_deleted').' ['.$addressid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteProfile($profileid)
    {
        $affectedRows = 0;
        do_action('before_vendor_profile_deleted', $profileid);
        $this->db->where('VProfileID', $profileid);
        $this->db->delete(TBL_VENDORS_PROFILES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_profile_deleted');
            logActivity(_l('vendor_profile_deleted').' ['.$profileid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteAbout($aboutid)
    {
        $affectedRows = 0;
        do_action('before_vendor_about_deleted', $aboutid);
        $this->db->where('VAboutID', $aboutid);
        $this->db->delete(TBL_VENDORS_ABOUT);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_about_deleted');
            logActivity(_l('vendor_about_deleted').' ['.$aboutid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteWork($workid)
    {
        $affectedRows = 0;
        do_action('before_vendor_work_deleted', $workid);
        $this->db->where('VWorkID', $workid);
        $this->db->delete(TBL_VENDORS_WORKS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_work_deleted');
            logActivity(_l('vendor_work_deleted').' ['.$workid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteLocation($locationid)
    {
        $affectedRows = 0;
        do_action('before_vendor_location_deleted', $locationid);
        $this->db->where('VLocationID', $locationid);
        $this->db->delete(TBL_VENDORS_LOCATIONS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_location_deleted');
            logActivity(_l('vendor_location_deleted').' ['.$locationid.']');
            return true;
        }
        return false;
    }

    /**
     * Delete vendor
     */
    public function deleteAccount($accountid)
    {
        $affectedRows = 0;
        do_action('before_vendor_account_deleted', $accountid);
        $this->db->where('VAccountID', $accountid);
        $this->db->delete(TBL_VENDORS_ACCOUNTS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            do_action('after_vendor_account_deleted');
            logActivity(_l('vendor_account_deleted').' ['.$accountid.']');
            return true;
        }
        return false;
    }

}
