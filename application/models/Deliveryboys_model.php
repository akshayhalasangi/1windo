<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Deliveryboys_model extends W_Model
{ 
    private $deliveryboy_data = array('DeliveryBoyID'=>'Val_DBendor', 'DB_FirstName'=>'Val_DBfirstname', 'DB_LastName'=>'Val_DBlastname', 'DB_Email'=>'Val_DBemailaddress', 'DB_CountryCode'=>'Val_DBcountrycode', 'DB_Mobile'=>'Val_DBmobilenumber', 'DB_ProfileImage'=>'Val_DBprofileimage', 'DB_Latitude'=>'Val_DBlatitude', 'DB_Longitude'=>'Val_DBlongitude', 'DB_Address'=>'Val_DBaddress', 'DB_Location'=>'Val_DBlocation', 'DB_City'=>'Val_DBcity', 'DB_Country'=>'Val_DBcountry','DB_ProfileStatus'=>'Val_DBprofilestatus','DB_Status'=>'Val_DBstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
 

	private $profile_data = array('VProfileID'=>'Val_Profile','P_DBendorID'=>'Val_DBendor', 'P_IDCardType'=>'Val_Pidcardtype', 'P_IDCardName'=>'Val_Pcardname', 'P_IDCardNumber'=>'Val_Pidcardnumber', 'P_IDCardFrontImage'=>'Val_Pidcardfrontimage', 'P_IDCardBackImage'=>'Val_Pidcardbackimage', 'P_IDCardStatus'=>'Val_Pidcardstatus', 'P_GuardianName'=>'Val_Pguardianname', 'P_Gender'=>'Val_Pgender', 'P_BirthDate' => 'Val_Pbirthdate', 'P_PermanentBuilding' => 'Val_Ppermanentbuilding','P_PermanentLocality'=>'Val_Ppermanentlocality', 'P_PermanentCity' => 'Val_Ppermanentcity', 'P_PermanentState' => 'Val_Ppermanentstate','P_PermanentPincode'=>'Val_Ppermanentpincode', 'P_PersonalStatus'=>'Val_Ppersonalstatus', 'P_CurrentBuilding' => 'Val_Pcurrentbuilding', 'P_CurrentLocality' => 'Val_Pcurrentlocality','P_CurrentCity'=>'Val_Pcurrentcity', 'P_CurrentState' => 'Val_Pcurrentstate', 'P_CurrentPincode' => 'Val_Pcurrentpincode', 'P_CurrentStatus'=>'Val_Pcurrentstatus', 'P_TermsStatus'=>'Val_Ptermsstatus','P_Status'=>'Val_Pstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
	private $location_data = array('VLocationID'=>'Val_Location','L_DBendorID'=>'Val_DBendor', 'L_Location'=>'Val_Llocation', 'L_Latitude'=>'Val_Llatitude', 'L_Longitude'=>'Val_Llongitude', 'L_Radius'=>'Val_Lradius','L_Status'=>'Val_Lstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

	private $account_data = array('DBAccountID'=>'Val_Account','A_DeliveryBoyID'=>'Val_Deliveryboy', 'A_AccountName'=>'Val_Aaccountname', 'A_AccountType'=>'Val_Aaccounttype', 'A_AccountNumber'=>'Val_Aaccountnumber', 'A_IFSCNumber'=>'Val_Aifscnumber', 'A_Status'=>'Val_Astatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
     
    
    public function __construct()
    {
        parent::__construct();        
    }

    /* Get vendor data */
    public function getDeliveryBoy($deliveryboyid='')
    {
        if(!empty($deliveryboyid)){
            $this->db->where('DeliveryBoyID', $deliveryboyid);        
            return $this->db->get(TBL_DELIVERYBOYS)->row();
        } else {
            $staffID = get_staff_user_id();
            $this->db->where('Staff_ID', $staffID);
            $result = $this->db->get('staffs')->row();
            switch ($result->S_IsAdmin) {
                case 0:
                    $this->db->order_by('DeliveryBoyID','DESC');
                    return $this->db->get(TBL_DELIVERYBOYS)->result_array();
                    break;
                case 1:
                    $this->db->where('DB_Area', $result->Area);
                    $this->db->order_by('DeliveryBoyID','DESC');
                    return $this->db->get(TBL_DELIVERYBOYS)->result_array();
                    break;
            }
            $this->db->order_by('DeliveryBoyID','DESC');
            return $this->db->get(TBL_DELIVERYBOYS)->result_array();
        }
    }
	
	/**
     * Get vendor addresses
     * @param  mixed $address_id
     * @param  array  $where       perform where in query array('F_Status' => 1)
     * @return array
     */
	public function getLocations($location_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		
		$this->db->where($where);
		
		if ($location_id != '') {
			$this->db->where('VLocationID', $location_id);
			$query = $this->db->get(TBL_DELIVERYBOYS_LOCATIONS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('VLocationID', $location_id);	
					$result = $this->db->get(TBL_DELIVERYBOYS_LOCATIONS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('VLocationID', $orderby);
		$query = $this->db->get(TBL_DELIVERYBOYS_LOCATIONS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('VLocationID', $orderby);
				$result = $this->db->get(TBL_DELIVERYBOYS_LOCATIONS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	/**
     * Get vendor addresses
     * @param  mixed $address_id
     * @param  array  $where       perform where in query array('F_Status' => 1)
     * @return array
     */
	public function getAccounts($account_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		
		$this->db->where($where);
		
		if ($account_id != '') {
			$this->db->where('DBAccountID', $account_id);
			$query = $this->db->get(TBL_DELIVERYBOYS_ACCOUNTS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('DBAccountID', $account_id);	
					$result = $this->db->get(TBL_DELIVERYBOYS_ACCOUNTS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('DBAccountID', $orderby);
		$query = $this->db->get(TBL_DELIVERYBOYS_ACCOUNTS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('DBAccountID', $orderby);
				$result = $this->db->get(TBL_DELIVERYBOYS_ACCOUNTS)->result_array();
			}
		else
			{
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
        $this->db->where('DB_Email', $email);
        return $this->db->get(TBL_DELIVERYBOYS)->result_array();
    }
	/**
     * Get/Check vendor by mobile number that are added 
     * @param  mixed $id vendor id
     * @return array
     */
    public function getByMobile($countrycode,$mobile,$where = array())
    {
        $this->db->where('DB_CountryCode', $countrycode);
        $this->db->where('DB_Mobile', $mobile);
        $this->db->or_where( $where);
		$result = $this->db->get(TBL_DELIVERYBOYS)->row();
		//echo $this->db->last_query();
        return $result;
    }
	
	/**
     * @param array $_POST data
     * @param user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function add($data)
	{      	
     
		
		$deliveryboy_data = array();
		foreach ($this->deliveryboy_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$deliveryboy_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_deliveryboy_added', $data);

        $this->db->insert(TBL_DELIVERYBOYS, $deliveryboy_data);

        $deliveryboyid = $this->db->insert_id();
        if ($deliveryboyid) {
          
            do_action('after_vendor_added', $deliveryboyid);
            
            $_new_user_log = $data['Val_DBmobilenumber'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_user_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Created [' . $_new_user_log . ']', $_is_staff);
        }

        return $deliveryboyid;
    }

	
/**
     * @param array $_POST data
     * @param user_request is this request from the user area
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
	
		$data                = do_action('before_locations_added', $data);

        $this->db->insert(TBL_DELIVERYBOYS_LOCATIONS, $location_data);

        $locationid = $this->db->insert_id();
        if ($locationid) {
          
            do_action('after_works_added', $locationid);
            $_new_address_log = $locationid;
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Locations Created [' . $_new_address_log . ']', $_is_staff);
        }

        return $locationid;
    }	


/**
     * @param array $_POST data
     * @param user_request is this request from the user area
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
	
		$data                = do_action('before_account_added', $data);

        $this->db->insert(TBL_DELIVERYBOYS_ACCOUNTS, $account_data);

        $accountid = $this->db->insert_id();
        if ($accountid) {
          
            do_action('after_account_added', $accountid);
            $_new_address_log = $accountid;
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Vendor Account Created [' . $_new_address_log . ']', $_is_staff);
        }

        return $accountid;
    }	

    /* Update vendor informations */
    public function update($data, $deliveryboyid)
    {     
		$affectedRows = 0;
        $deliveryboy_data = array();
        foreach ($this->deliveryboy_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $deliveryboy_data[$dbfield] = $data[$field];
            }
        }
	//	TBL_DELIVERYBOYS, $
        $this->db->where('DeliveryBoyID', $deliveryboyid);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_DELIVERYBOYS, $deliveryboy_data)) {
            $affectedRows++;		
            do_action('after_vendor_updated', $deliveryboyid);
        }
		else{
			//No Effected Rows / Either Same data posted			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_user_info_updated',_l('user_deliveryboy'),$deliveryboyid));

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

        $VendorData = getVendorData($deliveryboyid); 
        
        if(!empty($vendor_data) ){
            $this->db->where('DeliveryBoyID', $deliveryboyid);
            $result =  $this->db->update(TBL_DELIVERYBOYS, $vendor_data);
            
            if(!isset($data['vendor'])){
                $profile_flag = $cover_flag = $church_flag = '';
        
                $profileIamge = handle_vendor_profile_image($deliveryboyid); 

                if(isset($profileIamge['flag'])){
                    $profile_flag = $profileIamge['flag'];    
                }   
        
                $coverIamge = handle_vendor_cover_image($deliveryboyid);           
                if(isset($coverIamge['flag'])){
                    $cover_flag = $coverIamge['flag'];        
                }         
            
                $churchIamge = handle_vendor_church_image($deliveryboyid);           
                if(isset($churchIamge['flag'])){
                    $church_flag = $churchIamge['flag'];        
                }  
                $Response = array(
                    'DeliveryBoyID' => $VendorData->DeliveryBoyID, 
                    'Profile_Flag' => $profile_flag,                              
                    'Cover_Flag' => $cover_flag, 
                    'Church_Flag' => $church_flag
                    );   
            } else {
                $Response = array(
                    'DeliveryBoyID' => $VendorData->DeliveryBoyID,                      
                    );                        
            }

        if ($this->db->affected_rows() > 0) { 
            $affectedRows++;
            do_action('after_vendor_updated', $deliveryboyid);
        }
    

        if ($result > 0) { 
            logActivity('Vendor profile updated [ Vendor : ' . $VendorData->C_FullName . ']');               
        }
    } else {
        $Response = array(
            'DeliveryBoyID' => $VendorData->DeliveryBoyID, 
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
        $this->db->update(TBL_DELIVERYBOYS_ADDRESSES, $address_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_address_updated', $addressid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_address_info_updated',_l('user_vendor'),$addressid));

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
        $this->db->update(TBL_DELIVERYBOYS_PROFILES, $profile_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_profile_updated', $profileid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_profile_info_updated',_l('user_vendor'),$profileid));

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
        $this->db->update(TBL_DELIVERYBOYS_ABOUT, $about_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_about_updated', $aboutid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_about_info_updated',_l('user_vendor'),$aboutid));

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
        $this->db->update(TBL_DELIVERYBOYS_WORKS, $work_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_works_updated', $workid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_works_info_updated',_l('user_vendor'),$workid));

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
        $this->db->update(TBL_DELIVERYBOYS_LOCATIONS, $location_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_locations_updated', $locationid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_locations_info_updated',_l('user_vendor'),$locationid));

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
		
        $this->db->where('DBAccountID', $accountid);
        $this->db->update(TBL_DELIVERYBOYS_ACCOUNTS, $account_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_account_updated', $accountid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_account_info_updated',_l('user_vendor'),$accountid));

            return true;
        }

        return false;

    }	

    /**
    * Delete vendor
    */
    public function deleteDeliveryBoy($deliveryboyid){
        $affectedRows = 0;
        do_action('before_deliveryboy_deleted', $deliveryboyid);
        $this->db->where('DeliveryBoyID', $deliveryboyid);
        $this->db->delete(TBL_DELIVERYBOYS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_deliveryboy_deleted');
            logActivity(_l('deliveryboy_deleted').' [' . $deliveryboyid . ']');
            return true;
        }
        return false;
    }
    /**
    * Delete vendor
    */
    public function deleteAddress($addressid){
        $affectedRows = 0;
        do_action('before_address_deleted', $addressid);
        $this->db->where('AddressID', $addressid);
        $this->db->delete(TBL_DELIVERYBOYS_ADDRESSES);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_address_deleted');
            logActivity(_l('address_deleted').' [' . $addressid . ']');
            return true;
        }
        return false;
    }
	 /**
    * Delete vendor
    */
    public function deleteProfile($profileid){
        $affectedRows = 0;
        do_action('before_vendor_profile_deleted', $profileid);
        $this->db->where('VProfileID', $profileid);
        $this->db->delete(TBL_DELIVERYBOYS_PROFILES);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_vendor_profile_deleted');
            logActivity(_l('vendor_profile_deleted').' [' . $profileid . ']');
            return true;
        }
        return false;
    }
	
	/**
    * Delete vendor
    */
    public function deleteAbout($aboutid){
        $affectedRows = 0;
        do_action('before_vendor_about_deleted', $aboutid);
        $this->db->where('VAboutID', $aboutid);
        $this->db->delete(TBL_DELIVERYBOYS_ABOUT);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_vendor_about_deleted');
            logActivity(_l('vendor_about_deleted').' [' . $aboutid . ']');
            return true;
        }
        return false;
    }
   /**
    * Delete vendor
    */
    public function deleteWork($workid){
        $affectedRows = 0;
        do_action('before_vendor_work_deleted', $workid);
        $this->db->where('VWorkID', $workid);
        $this->db->delete(TBL_DELIVERYBOYS_WORKS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_vendor_work_deleted');
            logActivity(_l('vendor_work_deleted').' [' . $workid . ']');
            return true;
        }
        return false;
    }
	/**
    * Delete vendor
    */
    public function deleteLocation($locationid){
        $affectedRows = 0;
        do_action('before_vendor_location_deleted', $locationid);
        $this->db->where('VLocationID', $locationid);
        $this->db->delete(TBL_DELIVERYBOYS_LOCATIONS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_vendor_location_deleted');
            logActivity(_l('vendor_location_deleted').' [' . $locationid . ']');
            return true;
        }
        return false;
    }
	/**
    * Delete vendor
    */
    public function deleteAccount($accountid){
        $affectedRows = 0;
        do_action('before_vendor_account_deleted', $accountid);
        $this->db->where('VAccountID', $accountid);
        $this->db->delete(TBL_DELIVERYBOYS_ACCOUNTS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_vendor_account_deleted');
            logActivity(_l('vendor_account_deleted').' [' . $accountid . ']');
            return true;
        }
        return false;
    }	
	
}
