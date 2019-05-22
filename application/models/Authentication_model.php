<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Authentication_model extends W_Model
{
    private $staff_data = array('S_FirstName'=>'FirstName', 'S_LastName'=>'LastName', 'S_Email'=>'Email', 'S_Password'=>'Password', 'S_IsAdmin'=>'IsAdmin', 'S_Status'=>'Status', 'S_IsActive'=>'IsActive', 'S_LastIP'=>'LastIP', 'S_LastLogin'=>'LastLogin','ALastPasswordChange'=>'LastPasswordChange', 'S_NewPassKey'=>'NewPassKey','S_NewPassKeyRequested'=>'NewPassKeyRequested','RowAddedDttm' => '', 'RowUpdatedDttm' => '');
    private $member_data = array('MemberID'=>'Val_Member', 'M_CountryCode'=>'Val_Countrycode', 'M_Mobile'=>'Val_Mobilenumber', 'M_Password'=>'Val_Password', 'M_Type'=>'Val_Type','RelationID'=>'Val_Relation','M_DeviceToken' => 'Val_Devicetoken', 'M_Status'=>'Val_Status', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
    private $perm_statements = array('P_HasPermission');
    
    public function __construct()
    {
        parent::__construct();
       // $this->load->model('userAutoLogin');
       // $this->autologin();
    }

    /* Get registered staff's email
     * @param mixed $email
     * @return array
     */
	function AppSignin($countrycode, $mobile, $type, $language = '1')
    {		
        if ((!empty($countrycode)) AND (!empty($mobile)) AND (!empty($type)) ) {
          	
			$_id   = 'MemberID';
            
            $this->db->where('M_CountryCode', $countrycode);
            $this->db->where('M_Mobile', $mobile);
            $this->db->where('M_Type', $type);
            $member = $this->db->get(TBL_MEMBERS)->row();


            if ($member) {
				
				return true;
				
                // Email is okey lets check the password now
                $this->load->helper('phpass');
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				
                if (!$hasher->CheckPassword($password, $member->M_Password)) {					
                    // Password failed, return
                    return false;
                }
            } else {
                logActivity(_l('failed_login_attempt').'['._l('mobile').':' . $mobile . ', '._l('user_type').':' . $type . ', IP:' . $this->input->ip_address() . ']');
                return false;
            }
            if ($member->M_Status == 1) {
                logActivity(_l('inactive_user_tried_to_login').' ['._l('mobile').':' . $mobile . ', '._l('user_type').':' . $type . ', IP:' . $this->input->ip_address() . ']');
                return array(
                    'inactive' => true
                );
            }

//            $this->update_login_info($user->$_id, '1');
            return true;
        }
        return false;
    }
	
	
    /**
     * @param array $_POST data
     * @param user_request is this request from the user area
     * @return integer Insert ID
     * Add new user to database
     */
    public function AppSignup($data)
	{      	
		$member_data = array();
		foreach ($this->member_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$member_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_member_added', $data);

        $member_data['M_Type'] = 2;
        $member_data['M_Status'] = 2;
		$member_data['M_Password'] = '123456';
		$this->load->helper('phpass');
        $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($member_data['M_Password']);

		$member_data['M_Password'] = $password;
        $this->db->insert(TBL_MEMBERS, $member_data);

        $memberid = $this->db->insert_id();
        if ($memberid) {
          
            do_action('after_member_added', $memberid);
			
            $_new_user_log = $data['Val_Mobilenumber'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_user_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Member Created [' . $_new_user_log . ']', $_is_staff);
        }

        return $memberid;
    }
	
	/**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update user informations
     */

    public function AppUpdate($data, $relationid, $relationtype)
    {            
		$affectedRows = 0;
        $member_data = array();
        foreach ($this->member_data as $dbfield => $field) {
            if (isset($data[$field])) {
				
				if($field == 'Val_Password')	
					{
						$this->load->helper('phpass');
						$hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
						$password = $hasher->HashPassword($data[$field]);
				
						$member_data[$dbfield] = $password;
					
					}
				else			
	                $member_data[$dbfield] = $data[$field];
            }
        }
		
        $this->db->where('RelationID', $relationid);
        $this->db->where('M_Type', $relationtype);
        $this->db->update(TBL_MEMBERS, $member_data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            do_action('after_member_updated', $relationid);
        }
		else{
 			return true;
		}
        if ($affectedRows > 0) {
            logActivity('Member Info Updated [' . $relationid . ']');

            return true;
        }

        return false;
    }
	
	 /**
     * Get jobs contacts
     * @param  mixed $job_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function AppGet($member_id = '', $where = array() )
	{
		
		$this->db->where($where);
		
		if ($member_id != '') {
			$this->db->where('MemberID', $member_id);
			$query = $this->db->get(TBL_MEMBERS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('MemberID', $member_id);	
					$result = $this->db->get(TBL_MEMBERS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('MemberID', 'DESC');
		$query = $this->db->get(TBL_MEMBERS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('MemberID', 'DESC');
				$result = $this->db->get(TBL_MEMBERS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		return $result;
	}

     /**
    * Delete customer
    */
    public function AppDelete($memberid){
        $affectedRows = 0;
        do_action('before_member_deleted', $memberid);
        $this->db->where('MemberID', $memberid);
        $this->db->delete(TBL_MEMBERS);
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_member_deleted');
            logActivity(_l('member_deleted').' [' . $memberid . ']');
            return true;
        }
        return false;
    }
    /**
     * @param  integer ID to create autologin
     * @param  boolean Is Laboratory or Staff
     * @return boolean
     */
    private function create_autologin($user_id, $staff = '1')
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->user_autologin->delete($user_id, $key, $staff);
        if ($this->user_autologin->set($user_id, md5($key), $staff)) {
            set_cookie(array(
                'name' => 'autologin',
                'value' => serialize(array(
                    'user_id' => $user_id,
                    'key' => $key
                )),
                'expire' => 60 * 60 * 24 * 31 * 2 // 2 months
            ));
            return true;
        }
        return false;
    }
    /**
     * @param  boolean Is Laboratory or Staff
     * @return none
     */
    private function delete_autologin($staff = '1')
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            $this->user_autologin->delete($data['user_id'], md5($data['key']), $staff);
            delete_cookie('autologin', 'aal');
        }
    }
    /**
     * @return boolean
     * Check if autologin found
     */
    public function autologin()
    {
        if (!is_logged_in()) {
            $this->load->helper('cookie');
            if ($cookie = get_cookie('autologin', true)) {
                $data = unserialize($cookie);
                if (isset($data['key']) AND isset($data['user_id'])) {
                    if (!is_null($user = $this->user_autologin->get($data['user_id'], md5($data['key'])))) {
                        // Login user

                        if ($user->usertype == '1') {
                            $user_data = array(
                                'staff_user_id' => $user->id,
                                'staff_logged_in' => true,
                                'is_logged_in' => true
                            );

							$this->session->set_userdata($user_data);
							// Renew users cookie to prevent it from expiring
							set_cookie(array(
								'name' => 'autologin',
								'value' => $cookie,
								'expire' => 60 * 60 * 24 * 31 * 2 // 2 months
							));
							$this->update_login_info($user->id, $user->usertype);
							return true;
							
                        } 
                    }
                }
            }
        }
        return false;
    }
    /**
     * @param  integer ID
     * @param  boolean Is Laboratory or Staff
     * @return none
     * Update login info on autologin
     */
    private function update_login_info($user_id, $staff = '1')
    {
        $table = 'masters';
        $_id   = 'MasterID';
        
        $this->db->set('last_ip', $this->input->ip_address());
        $this->db->set('last_login', date('Y-m-d H:i:s'));
        $this->db->where($_id, $user_id);
        $this->db->update($table);
    }
	
   
    /**
     * @param  string Email from the user
     * @param  Is Laboratory or Staff
     * @return boolean
     * Generate new password key for the user to reset the password.
     */
    public function forgot_password($email, $staff = '1' )
    {
		$table = 'MasterID';
		$_id   = 'MasterID';
	
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();

        if ($user) {
            if ($user->active == 0) {
                return array(
                    'memberinactive' => true
                );
            }

            $new_pass_key = md5(rand() . microtime());
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, array(
                'new_pass_key' => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s')
            ));

            if ($this->db->affected_rows() > 0) {
				
                $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['usertype']     = $staff;
                $data['userid']       = $user->$_id;
                $send                 = $this->emails_model->send_email($user->email, _l('password_reset_email_subject', get_option('companyname')), 'forgot-password', $data);
				
                if ($send) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
    public function set_password($staff = '1', $userid, $new_pass_key, $password)
    {
        if (!$this->can_set_password($staff, $userid, $new_pass_key)) {
            return array(
                'expired' => true
            );
        }
        $this->load->helper('phpass');
        $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($password);
        
	  
            $table = 'masters';
            $_id   = 'MasterID';
      
        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, array(
            'password' => $password
        ));
        if ($this->db->affected_rows() > 0) {
            logActivity(_l('user_set_password').' [Member ID:' . $userid . ', Staff:' . $staff . ', IP:' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', NULL);
            $this->db->set('new_pass_key_requested', NULL);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);
            return true;
        }
        return null;
    }
    /**
     * @param  boolean Is Laboratory or Staff
     * @param  integer ID
     * @param  string
     * @param  string
     * @return boolean
     * Member reset password after successful validation of the key
     */
    public function reset_password($staff = '1', $userid, $new_pass_key, $password)
    {
        if (!$this->can_reset_password($staff, $userid, $new_pass_key)) {
            return array(
                'expired' => true
            );
        }
        $this->load->helper('phpass');
        $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($password);
        $table    = 'tblcontacts';
        $_id      = 'id';
       
            $table = 'masters';
            $_id   = 'MasterID';
      

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, array(
            'password' => $password
        ));
        if ($this->db->affected_rows() > 0) {
            logActivity(_l('user_reset_password').' [Member ID:' . $userid . ', Staff:' . $staff . ', IP:' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', NULL);
            $this->db->set('new_pass_key_requested', NULL);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);
            $this->load->model('emails_model');
            $this->db->where($_id, $userid);
            $user          = $this->db->get($table)->row();
            $data['email'] = $user->email;
            $this->emails_model->send_email($user->email, _l('password_changed_email_subject'), 'reset-password', $data);
            return true;
        }
        return null;
    }

	 
	 
    public function getEmail($email)
    {
        $this->db->where('S_Email', $email);
        return $this->db->get('staffs')->result_array();
    }
	
    /* @param array $_POST data
     * @param staff_request is this request from the client area
     * @return integer Insert ID
     * Add new staff to database
     */
    public function signUp($data)
    {    
        if(!empty($data)){
            $S_EmailExist = count($this->getEmail($data['Email']));

            if($S_EmailExist >= 1){
                return 'Exists';
            } else {
                $staff_data = array();
                foreach ($this->staff_data as $dbfield => $field) {
                    if (isset($data[$field])) {
                        $staff_data[$dbfield] = $data[$field];
                    }
                }
      
                $this->load->helper('phpass');
                $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            
                $staff_data['S_Password']             = $hasher->HashPassword($data['Password']);   
				if(isset($data['staff'])){
					$staff_data['S_IsActive'] = '1';
					$staff_data['S_IsAdmin'] = '1';
				}
                $data                = do_action('before_staff_added', $data);             
                $this->db->insert('staffs', $staff_data);                    
                $staffid = $this->db->insert_id();

                if ($staffid) {                 
                    $_is_staff = null;
                    $_new_staff_log = null;
                    if (is_staff_logged_in()) {
                        $_new_staff_log .= ' From Staff: ' . get_staff_user_id();
                        $_is_staff = get_staff_user_id();
                    }
                    logActivity(_l('new_staff_registered').' [' . $_new_staff_log . ']', $_is_staff);
                }
                return $staffid;
            }
        }
        return false;
        
    }

    /**
     * @param  string Email address for login
     * @param  string User Password
     * @param  boolean Set cookies for user if remember me is checked
     * @param  boolean Is staff Or Client
     * @return boolean if not redirect url found, if found redirect to the url
     */
    public function signIn($email,$password,$staff, $remember='')
    {

        if ((!empty($email)) and (!empty($password))) {
            if ($staff == true) {
                $table = 'staffs';
                $_id   = 'Staff_ID';
            }

            if(is_numeric($email))
            {
                $table='members';
                $this->db->where('M_Mobile', $email);
                $this->db->where('M_Type', 2);
                $this->db->where('M_Status', 2);
                $user = $this->db->get($table)->row();
                $_id   = 'RelationID';
                $user->S_IsActive = 1;
                $user->S_Password=$user->M_Password;
                $user->role ="vendor";

            }
            else
            {
                $this->db->where('S_Email', $email);
                $this->db->where('S_Status', 2);
                $user = $this->db->get($table)->row();
                $user->role ="other";
            }


            if ($user) {
                // Email is okey lets check the password now
                $this->load->helper('phpass');
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					if (!$hasher->CheckPassword($password, $user->S_Password)) {                        
                    // Password failed, return
                    return false;
                }
            } else { 
                logActivity('Failed Login Attempt [Email:' . $email . ','. _l('is_admin_member') . ($staff == true ? 'Yes' : 'No') . ', IP:' . $this->input->ip_address() . ']');

                return false;
            }
             
            if ($user->S_IsActive == 2) {
				
                logActivity('Inactive User Tried to Login [Email:' . $email . ','. _l('is_admin_member') . ($staff == true ? 'Yes' : 'No') . ', IP:' . $this->input->ip_address() . ']');

                return array(
                    'memberinactive' => true
                );
            }
            if ($staff == true) {
                do_action('before_staff_login', array(
                    'email' => $email,
                    'userid' => $user->$_id
                ));
                $user_data = array(
                    'staff_user_id' => $user->$_id,
                    'staff_logged_in' => true,
                    'role'=> $user->role
                );
                 
            } else {
                do_action('before_client_login', array(
                    'email' => $email,
                    'userid' => $user->userid,
                    'contact_user_id' => $user->$_id
                ));

                $user_data = array(
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true
                );
            }
            $this->session->setUserData($user_data);
            if ($remember) {
                $this->createAutoLogin($user->$_id, $staff);
            }
            $this->updateLoginInfo($user->$_id, $staff);

            // This is only working for staff members
            if ($this->session->has_userdata('red_url') && $staff == true) {
                $red_url = $this->session->userdata('red_url');
                $this->session->unset_userdata('red_url');
                redirect($red_url);
            }
            return true;
        }
        return false;
    }
/**
     * @param  boolean If Client or staff
     * @return none
     */
    function logOut($staff = true)
    {
        $this->delete_autologin($staff);
//		deleteAutoLogin
		do_action('before_staff_logout', get_staff_user_id());
		$this->session->unset_userdata(array(
			'staff_user_id' => '',
			'staff_logged_in' => ''
		));
//		 do_action('before_staff_logout', get_client_user_id());
//            $this->session->unset_userdata('staff_user_id');
//            $this->session->unset_userdata('staff_logged_in');
        $this->session->sess_destroy();
    }
    

    /**
     * @param  integer ID to create autologin
     * @param  boolean Is Client or staff
     * @return boolean
     */
    private function createAutoLogin($user_id, $staff)
    {

        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->User_autologin->delete($user_id, $key, $staff);
        if ($this->User_autologin->set($user_id, md5($key), $staff)) {
            set_cookie(array(
                'name' => 'autologin',
                'value' => serialize(array(
                    'user_id' => $user_id,
                    'key' => $key
                )),
                'expire' => 60 * 60 * 24 * 31 * 2 // 2 months
            ));
            return true;
        }
        return false;
    }

    /**
     * @param  boolean Is Client or staff
     * @return none
     */
    private function deleteAutoLogin($staff)
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            $this->User_autologin->delete($data['user_id'], md5($data['key']), $staff);
            delete_cookie('autologin', 'aal');
        }
    }

    
    /**
     * @param  integer ID
     * @param  boolean Is Client or staff
     * @return none
     * Update login info on autologin
     */
    private function updateLoginInfo($user_id, $staff)
    {
        
        if ($staff == true) {
            $table = 'staffs';
            $_id   = 'Staff_ID';
        }
        $this->db->set('S_LastIP', $this->input->ip_address());
        $this->db->set('S_LastLogin', date('Y-m-d H:i:s'));
        $this->db->where($_id, $user_id);
        $this->db->update($table);
    }

    /**
     * Send set password email
     * @param string $email
     * @param boolean $staff is staff of contact
     */
    public function set_password_email($email, $staff)
    { 
        if ($staff == true) {
            $table = 'staffs';
            $_id   = 'Staff_ID';
        }
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();
        if ($user) {
            if ($user->active == 0) {
                return array(
                    'memberinactive' => true
                );
            }
            $new_pass_key = md5(rand() . microtime());
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, array(
                'new_pass_key' => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s')
            ));
            if ($this->db->affected_rows() > 0) {
                $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['usertype']        = $staff;
                $data['userid']       = $user->$_id;
                $data['email']        = $email;

                $merge_fields = array();
                if ($staff == false) {
                    $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($user->userid, $user->$_id));
                } else {
                    $merge_fields = array_merge($merge_fields, get_admin_merge_fields($user->$_id));
                }
                $merge_fields = array_merge($merge_fields, get_password_merge_field($data, $staff, 'set'));
                $send         = $this->emails_model->send_email_template('contact-set-password', $user->email, $merge_fields);

                if ($send) {
                    return true;
                }

                return false;
            }

            return false;
        }

        return false;
    }
	
/*	 public function set_password_email($email, $staff = '1')
    {
   
		$table = 'masters';
		$_id   = 'MasterID';
      
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();
        if ($user) {
            if ($user->active == 0) {
                return array(
                    'memberinactive' => true
                );
            }
            $new_pass_key = md5(rand() . microtime());
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, array(
                'new_pass_key' => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s')
            ));
            if ($this->db->affected_rows() > 0) {
                $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['usertype']     = $staff;
                $data['userid']       = $user->$_id;
                $data['email']        = $email;
                $send                 = $this->emails_model->send_email($user->email, _l('password_set_email_subject', get_option('companyname')), 'set-password', $data);
                if ($send) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }*/
	/**
     * @param  string Email from the user
     * @param  Is Laboratory or staff
     * @return boolean
     * Generate new password key for the user to reset the password.
    */
    public function ForgotPassword($email, $staff = false)
    {     
        if ($staff == true) {
            $table = 'staffs';
            $_id   = 'Staff_ID';
        }
        $this->db->where('S_Email', $email);
        $user = $this->db->get($table)->row();
		 
        if ($user) {
            if ($user->S_IsActive == 2) {
                return array(
                    'memberinactive' => true
                );
            }

            $new_pass_key = md5(rand() . microtime());
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, array(
                'S_NewPassKey' => $new_pass_key,
                'S_NewPassKeyRequested' => date('Y-m-d H:i:s')
            ));
               $this->db->affected_rows(); 
            if ($this->db->affected_rows() > 0) {
               // $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['staff']        = $staff;
                $data['userid']       = $user->$_id;
                $send                 = $this->emails_model->send_enquiry_email($email,'Forgot Password','We received a request to reset your Intorque password. Click the below button to choose a new one.' ,'ForgotPassword',FullName($user->S_FirstName,$user->S_LastName));
			 //return true;
                  
                if ($send) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }



   
    
    /**
     * @param  integer Is Client or staff
     * @param  integer ID
     * @param  string Password reset key
     * @return boolean
     * Check if the key is not expired or not exists in database
     */
    public function can_set_password($staff, $userid, $new_pass_key)
    {       
        if ($staff == true) {
            $table = 'staffs';
            $_id   = 'Staff_ID';
        }
        $this->db->where($_id, $userid);
         $this->db->where('S_Status', 2);
        $this->db->where('new_pass_key', $new_pass_key);
        $user = $this->db->get($table)->row();
        if ($user) {
            $timestamp_now_minus_48_hour = time() - (3600 * 48);
            $new_pass_key_requested      = strtotime($user->new_pass_key_requested);
            if ($timestamp_now_minus_48_hour > $new_pass_key_requested) {
                return false;
            }

            return true;
        }

        return false;
    }
	
	/*Get staff data*/
    public function getAdmin($id){
        // $this->db->where('S_Status', 2);
        if(!empty($id)){
            $this->db->where('Staff_ID',$id);    
            return $this->db->get('staffs')->row();
        }        
        return $this->db->get('staffs')->result_array();
    }
    /**
     * Update staff info
     * @param  array $data staff data
     * @param  mixed $id   staff id
     * @return boolean
     */
   public function updateProfile($data, $id)
    {               
        $result = 0;
        $affectedRows = 0; 
        if(!empty($data)){
            $staff_data = array();

            $this->load->helper('phpass');
            $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);        
              
            if(isset($data['S_Password'])){
                $staff_data['S_Password'] = $hasher->HashPassword($data['Val_Password']);
            }

            $staff_data['S_FirstName'] = $data['Val_FirstName'];
            $staff_data['S_LastName'] = $data['Val_LastName'];

            handle_staff_profile_image($id);

            $this->db->where('Staff_ID',$id);
            $result = $this->db->update('staffs', $staff_data);    
                         
        }

        if ($this->db->affected_rows() > 0) {            
            $affectedRows++;        
        }

        if ($result > 0) {
            logActivity('Staff profile detail updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

	/**
     * Update user password from forgot password feature or set password
     * @param boolean $staff        is staff or contact
     * @param mixed $userid
     * @param string $new_pass_key the password generate key
     * @param string $password     new password
     */
    public function setPassword($data)
    {
       /* if (!$this->can_set_password($staff, $userid, $new_pass_key)) {
            return array(
                'expired' => true
            );
        }*/ 
        if(!empty($data['CPassword']) || (!empty($data['Password']))){
            $this->load->helper('phpass');
        
            if(isset($data['Action']) && $data['Action'] == 'Reset'){
                $pass = $data['CPassword'];                
            } else {
                $pass = $data['NewPassword'];        
            }

            $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $password = $hasher->HashPassword($pass);
        
            $table    = 'staffs';
            $_id      = 'Staff_ID';
    		$emailid  = 'S_Email';
    		$userpassword  = 'S_Password';

            if(isset($data['Action']) && $data['Action'] == 'Reset'){
                $this->db->where($_id, get_staff_user_id());    
            } else {
                $this->db->where($emailid, $data['Email']);    
            }
 
            $this->db->update($table, array(
                'S_Password' => $password
            ));	 
            if ($this->db->affected_rows() > 0) {
                if(isset($data['Action']) && $data['Action'] == 'Reset'){
                    logActivity('Password Updated [User Email:' . get_staff_user_id() . ']');
                } else {                
                    logActivity('Password Updated [User Email:' . $data['Email'] . ']');  
                }
                
                return true;
            }
        }

        return false;
    }
	
     /* Get Permissions */
    public function getPermission($staffid){
        $this->db->where('P_StaffID',$staffid);
        return $this->db->get('permissions')->result_array();
    }

    /* Add permissions */
    public function addPermission($data)
    {            
        $permission_data = array();
        if(!empty($data)){    

            if (isset($data['P_HasPermission'])) {
                $permission_data['P_HasPermission'] = $data['P_HasPermission'];
                unset($data['P_HasPermission']);
            }
           
            $permissions =  get_admin_controllers_lists(true);
            foreach(array_filter($permissions) as $permission){
            
                $this->db->insert('permissions', array(
                    'P_Permission' => $permission[0],
                    'P_StaffID' => $data['Staff'],
                    'P_HasPermission' => 0,                                            
                )); 
            }
             
            foreach ($this->perm_statements as $c) {
                foreach ($permission_data as $key => $p) {                                   
                    if ($key == $c) {
                        foreach ($p as $perm) {                              
                            $this->db->where('P_StaffID', $data['Staff']);
                            $this->db->where('P_Permission', $perm);
                            $this->db->update('permissions', array(
                                 'P_HasPermission' => 1
                            ));                         
                        }
                    }
                }                 
            }
            logActivity('New permission added [Staff: ' . $data['Staff'] . ']');
            return $data['Staff'];             
        }
        return false;    
    }

    public function checkPermission($staffid='')
    {
        $this->db->where('P_StaffID',$staffid);
        return $this->db->get('permissions')->result_array();
    }

    /* Update staff permissions */
    public function updatePermission($data,$staffid)
    {    
        $permission_data = array();
        if(!empty($data)){                 
            if (isset($data['P_HasPermission'])) {
                $permission_data['P_HasPermission'] = $data['P_HasPermission'];
                unset($data['P_HasPermission']);
            }
           
            $all_permissions =  get_admin_controllers_lists(true);
          
            $permissionsList = $this->getPermission($staffid);
            if (count($permissionsList) == 0) {
                foreach ($all_permissions as $p) {
                    $_ins                 = array();
                    $_ins['P_StaffID']       = $staffid;
                    $_ins['P_Permission'] = $p[0];
                    $this->db->insert('permissions', $_ins);
                }
            } elseif (count($permissionsList) != count($all_permissions)) {
                foreach ($all_permissions as $p) {
                    if (total_rows('permissions', array(
                        'P_StaffID' => $staffid,
                        'P_Permission' => $p[0]
                    )) == 0) {
                        $_ins                 = array();
                        $_ins['P_StaffID']       = $staffid;
                        $_ins['P_Permission'] = $p[0];
                        $this->db->insert('permissions', $_ins);
                    }
                }
            } 
              

            $_permission_restore_affected_rows = 0;
            foreach ($all_permissions as $permission) {
                foreach ($this->perm_statements as $c) {
                    $this->db->where('P_StaffID', $staffid);
                    $this->db->where('P_Permission', $permission[0]);
                    $this->db->update('permissions', array(
                        'P_HasPermission' => 0
                    ));
                    if ($this->db->affected_rows() > 0) {
                        $_permission_restore_affected_rows++;
                    }
                }
            }

            $_new_permissions_added_affected_rows = 0;
            foreach ($permission_data as $key => $val) {
                foreach ($val as $p) {
                    $this->db->where('P_StaffID', $staffid);
                    $this->db->where('P_Permission', $p);
                    $this->db->update('permissions', array(
                        'P_HasPermission' => 1
                    ));
                    if ($this->db->affected_rows() > 0) {
                        $_new_permissions_added_affected_rows++;
                    }
                }
            }
            logActivity('Role permission updated [RoleID: ' . $staffid . ']');
            return $staffid;             
        }
        return false;    
    }

}
