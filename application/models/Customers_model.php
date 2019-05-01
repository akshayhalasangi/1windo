<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Customers_model extends W_Model
{
    private $customer_data = array('CustomerID'=>'Val_Customer', 'C_FirstName'=>'Val_Cfirstname', 'C_LastName'=>'Val_Clastname', 'C_Email'=>'Val_Cemailaddress', 'C_CountryCode'=>'Val_Ccountrycode', 'C_Mobile'=>'Val_Cmobilenumber', 'C_Gender'=>'Val_Cgender', 'C_ProfileImage'=>'Val_Cprofileimage', 'C_Latitude'=>'Val_Clatitude', 'C_Longitude'=>'Val_Clongitude', 'C_Address'=>'Val_Caddress', 'C_Location'=>'Val_Clocation', 'C_City'=>'Val_Ccity', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $address_data = array('AddressID'=>'Val_Address', 'A_Type'=>'Val_Type', 'A_RelationID'=>'Val_Relation', 'A_Name'=>'Val_Aname', 'A_Address'=>'Val_Aaddress', 'A_Location'=>'Val_Alocation', 'A_Latitude'=>'Val_Alatitude', 'A_Longitude'=>'Val_Alongitude', 'A_Status'=>'Val_Astatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

     
    
    public function __construct()
    {
        parent::__construct();        
    }

    /* Get customer data */
    public function getCustomer($customerid='')
    {
        if(!empty($customerid)){
            $this->db->where('CustomerID', $customerid);        
            return $this->db->get(TBL_CUSTOMERS)->row();
        } else {
            $this->db->order_by('CustomerID','DESC');
            return $this->db->get(TBL_CUSTOMERS)->result_array();
        }
    }
	/**
     * Get customer addresses
     * @param  mixed $address_id
     * @param  array  $where       perform where in query array('F_Status' => 1)
     * @return array
     */
	public function getAddresses($address_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		
		$this->db->where($where);
		
		if ($address_id != '') {
			$this->db->where('AddressID', $address_id);
			$query = $this->db->get(TBL_CUSTOMERS_ADDRESSES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('AddressID', $address_id);	
					$result = $this->db->get(TBL_CUSTOMERS_ADDRESSES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('AddressID', $orderby);
		$query = $this->db->get(TBL_CUSTOMERS_ADDRESSES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('AddressID', $orderby);
				$result = $this->db->get(TBL_CUSTOMERS_ADDRESSES)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}

    

    /* Get registered customer's email
     * @param mixed $email
     * @return array
     */
    public function getEmail($email)
    {
        $this->db->where('C_Email', $email);
        return $this->db->get(TBL_CUSTOMERS)->result_array();
    }
	/**
     * Get/Check customer by mobile number that are added 
     * @param  mixed $id customer id
     * @return array
     */
    public function getByMobile($countrycode,$mobile)
    {
        $this->db->select('*');
        $this->db->from('1w_tbl_customers');
        $this->db->where('C_CountryCode', $countrycode);
        $this->db->where('C_Mobile', $mobile);
        return $this->db->get()->result_array();
		//$result = $this->db->get(TBL_CUSTOMERS)->row();
		//echo $this->db->last_query();
        //return $result;
    }
	
	/**
     * @param array $_POST data
     * @param user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function add($data)
	{      	
     
		
		$customer_data = array();
		foreach ($this->customer_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$customer_data[$dbfield] = $data[$field];
			}
		}
	
        $data = do_action('before_user_added', $data);
        
        if(empty($data['Val_Cemailaddress']))
        {

        }
        else
        {
		$EmailExist = count($this->getEmail($data['Val_Cemailaddress']));
		if($EmailExist >= 1)
			return 'Exists';
        }
		$MobileExist = count($this->getByMobile($data['Val_Ccountrycode'],$data['Val_Cmobilenumber']));
		if($MobileExist >= 1)
			return 'Exists';	
		
        $this->db->insert(TBL_CUSTOMERS, $customer_data);

        $customerid = $this->db->insert_id();
        if ($customerid) {
          
            do_action('after_customer_added', $customerid);
            $_new_user_log = $data['Val_Mobilenumber'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_user_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Customer Created [' . $_new_user_log . ']', $_is_staff);
        }

        return $customerid;
    }

	/**
     * @param array $_POST data
     * @param user_request is this request from the user area
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
	
		$data                = do_action('before_address_added', $data);

        $this->db->insert(TBL_CUSTOMERS_ADDRESSES, $address_data);

        $addressid = $this->db->insert_id();
        if ($addressid) {
          
            do_action('after_address_added', $addressid);
            $_new_address_log = $addressid;
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_address_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Address Created [' . $_new_address_log . ']', $_is_staff);
        }

        return $addressid;
    }

    /* Add new  customer */
/*    public function add($data)
    {    
        if(!empty($data)){
            $EmailExist = count($this->getEmail($data['Val_Email']));

            if($EmailExist >= 1){
                return 'Exists';
            } else {
                $customer_data = array();
                foreach ($this->customer_data as $dbfield => $field) {
                    if (isset($data[$field])) {
                        $customer_data[$dbfield] = $data[$field];
                    }
                }      
                $this->load->helper('phpass');
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);            
                $customer_data['C_Password']             = $hasher->HashPassword($data['Val_Password']);   
				
                if(isset($data['CountryCode'])){                    
                    $customer_data['C_CountryCode'] = '+'.$data['CountryCode'];
                }    
                if(isset($data['Val_CountryCode'])){
                    $code = getISOCode($data['Val_CountryCode']);            
                    $customer_data['C_CountryCode'] = '+'.$code->CountryPhoneCode;
                }             
                $this->db->insert(TBL_CUSTOMERS, $customer_data);   
                            
                $customerid = $this->db->insert_id();

                 $_new_customer_log = null;
                if ($customerid) {                 
                    $_new_customer_log .= _l('from_customer').' : ' . $customerid;
                    $_is_customer = $customerid;
                     
                    logActivity(_l('txt_new_customer_registered').' [' . $_new_customer_log . ']', $_is_customer);
                }
                return $customerid;
            }
        }
        //return false;        
    }

  */  
    /**    
     * Generate new password key for the customer to reset the password.
    */
    public function ForgotPassword($email)
    {             
        $table = TBL_CUSTOMERS;
        $_id   = 'CustomerID';

        $this->db->where('C_Email', $email);
        $customer = $this->db->get($table)->row();
         
        if ($customer) {
            if ($customer->C_Status != 2) {
                return array(
                    'memberinactive' => true
                );
            }
           
            $this->load->helper('phpass');
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);        
            $new_pass_key = random_string(6);
            if(isset($new_pass_key)){
                $password = $hasher->HashPassword($new_pass_key);
            }
            
            $this->db->where($_id, $customer->$_id);
            $this->db->update($table, array(
                'C_Password' => $password,               
            ));
            $this->db->affected_rows(); 
            if ($this->db->affected_rows() > 0) {
               // $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['customer']        = $customer;
                $data['customerid']       = $customer->$_id;
                $send = $this->emails_model->send_enquiry_email($email,'Forgot Password','We received a request to reset your 1Windo password. Click the below button to choose a new one. Your new password is'.$new_pass_key ,'ForgotPassword',$customer->C_FullName); 
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

    /* Customer reset password */
    public function changePassword($password,$customerid)
    {
        $this->load->helper('phpass');
        $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($password);
        $table    = TBL_CUSTOMERS;
        
        $this->db->where('CustomerID',$customerid);        
        $this->db->update($table, array(
            'C_Password' => $password
        ));
        $customer = getCustomerData($customerid);
        if ($this->db->affected_rows() > 0) {
            logActivity('Customer Reseted Password [Customer :' . $customer->C_FullName . ', IP:' . $this->input->ip_address() . ']');                     
            return true;
        }

        return null;
    }

    /* Update customer informations */
    public function update($data, $customerid)
    {     
		$affectedRows = 0;
        $customer_data = array();
        foreach ($this->customer_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $customer_data[$dbfield] = $data[$field];
            }
        }
		
        $this->db->where('CustomerID', $customerid);
        $this->db->update(TBL_CUSTOMERS, $customer_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_customer_updated', $customerid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_user_info_updated',_l('user_customer'),$customerid));

            return true;
        }

        return false;
/* 
        $affectedRows = 0;
        $customer_data = array();
        $privacy_data = array();
        $location_data = array();
        foreach ($this->customer_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $customer_data[$dbfield] = $data[$field];
            }
        }
        
        if(isset($data['Val_Password'])){
            $customer_data['C_Password'] = $data['Val_Password'];
        }
        
        if(isset($data['status'])){
            $customer_data['C_Status'] = $data['status'];   
        }
        
        if (empty($customer_data['C_Password'])) { 
            unset($customer_data['C_Password']);
        } else { 
            $this->load->helper('phpass');
            $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $customer_data['C_Password']             = $hasher->HashPassword($data['Val_Password']);           
        }   

        if(isset($data['Val_Countrycode'])){
            $code = getISOCode($data['Val_CountryCode']);            
            $customer_data['C_CountryCode'] = '+'.$code->CountryPhoneCode;
        }        

        $CustomerData = getCustomerData($customerid); 
        
        if(!empty($customer_data) ){
            $this->db->where('CustomerID', $customerid);
            $result =  $this->db->update(TBL_CUSTOMERS, $customer_data);
            
            if(!isset($data['customer'])){
                $profile_flag = $cover_flag = $church_flag = '';
        
                $profileIamge = handle_customer_profile_image($customerid); 

                if(isset($profileIamge['flag'])){
                    $profile_flag = $profileIamge['flag'];    
                }   
        
                $coverIamge = handle_customer_cover_image($customerid);           
                if(isset($coverIamge['flag'])){
                    $cover_flag = $coverIamge['flag'];        
                }         
            
                $churchIamge = handle_customer_church_image($customerid);           
                if(isset($churchIamge['flag'])){
                    $church_flag = $churchIamge['flag'];        
                }  
                $Response = array(
                    'CustomerID' => $CustomerData->CustomerID, 
                    'Profile_Flag' => $profile_flag,                              
                    'Cover_Flag' => $cover_flag, 
                    'Church_Flag' => $church_flag
                    );   
            } else {
                $Response = array(
                    'CustomerID' => $CustomerData->CustomerID,                      
                    );                        
            }

        if ($this->db->affected_rows() > 0) { 
            $affectedRows++;
            do_action('after_customer_updated', $customerid);
        }
    

        if ($result > 0) { 
            logActivity('Customer profile updated [ Customer : ' . $CustomerData->C_FullName . ']');               
        }
    } else {
        $Response = array(
            'CustomerID' => $CustomerData->CustomerID, 
        );
      if ($result > 0) { 
            logActivity('Customer profile updated [ Customer : ' . $CustomerData->C_FullName . ']');               
        }  
    }
        return $Response; */
    
    }
 
    /* Update customer informations */
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
        $this->db->update(TBL_CUSTOMERS_ADDRESSES, $address_data);
		//echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            $affectedRows++;
		
            do_action('after_address_updated', $addressid);
        }
		else{
			//No Effected Rows / Either Same data posted
			
		}
        if ($affectedRows > 0) {
            logActivity(_l('log_address_info_updated',_l('user_customer'),$addressid));

            return true;
        }

        return false;

    }
 
    /**
    * Delete customer
    */
    public function deleteCustomer($customerid){
        $affectedRows = 0;
        do_action('before_customer_deleted', $customerid);
        $this->db->where('CustomerID', $customerid);
        $this->db->delete(TBL_CUSTOMERS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_customer_deleted');
            logActivity(_l('customer_deleted').' [' . $customerid . ']');
            return true;
        }
        return false;
    }
    /**
    * Delete address
    */
    public function deleteAddress($addressid){
        $affectedRows = 0;
        do_action('before_address_deleted', $addressid);
        $this->db->where('AddressID', $addressid);
        $this->db->delete(TBL_CUSTOMERS_ADDRESSES);
        
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
   
}
