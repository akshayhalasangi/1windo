<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Users_model extends W_Model
{
    private $user_data = array('U_FullName'=>'Val_FullName', 'U_Password'=>'Val_Password','U_Email'=>'Val_Email', 'U_CountryCode'=>'Val_CountryCode', 'U_Mobile'=>'Val_Mobile', 'U_Gender'=>'Val_Gender', 'U_ProfileImage'=>'Val_ProfileImage', 'U_CoverImage'=>'Val_CoverImage', 'U_ChurchImage'=>'Val_ChurchImage', 'U_City'=>'Val_City','U_Designation'=>'Designation','U_Android_Firebase_ID'=>'Val_Android_Firebase_ID','RowAddedDttm' => '', 'RowUpdatedDttm' => '');

     
    
    public function __construct()
    {
        parent::__construct();        
    }

    /* Get user data */
    public function getUser($userid='')
    {
        if(!empty($userid)){
            $this->db->where('User_ID', $userid);        
            return $this->db->get('users')->row();
        } else {
            $this->db->order_by('User_ID','DESC');
            return $this->db->get('users')->result_array();
        }
    }

    

    /* Get registered user's email
     * @param mixed $email
     * @return array
     */
    public function getEmail($email)
    {
        $this->db->where('U_Email', $email);
        return $this->db->get('users')->result_array();
    }

    public function getByMobile($mobile)
    {
        $this->db->where('U_Mobile', $mobile);
        return $this->db->get('users')->result_array();
    }
	
    /* Add new  user */
    public function add($data)
    {    
        if(!empty($data)){
            $EmailExist = count($this->getEmail($data['Val_Email']));

            if($EmailExist >= 1){
                return 'Exists';
            } else {
                $user_data = array();
                foreach ($this->user_data as $dbfield => $field) {
                    if (isset($data[$field])) {
                        $user_data[$dbfield] = $data[$field];
                    }
                }      
                $this->load->helper('phpass');
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);            
                $user_data['U_Password']             = $hasher->HashPassword($data['Val_Password']);   
				
                if(isset($data['CountryCode'])){                    
                    $user_data['U_CountryCode'] = '+'.$data['CountryCode'];
                }    
                if(isset($data['Val_CountryCode'])){
                    $code = getISOCode($data['Val_CountryCode']);            
                    $user_data['U_CountryCode'] = '+'.$code->CountryPhoneCode;
                }             
                $this->db->insert('users', $user_data);   
                            
                $userid = $this->db->insert_id();

                 $_new_user_log = null;
                if ($userid) {                 
                    $_new_user_log .= _l('from_user').' : ' . $userid;
                    $_is_user = $userid;
                     
                    logActivity(_l('txt_new_user_registered').' [' . $_new_user_log . ']', $_is_user);
                }
                return $userid;
            }
        }
        //return false;        
    }

    
    /**    
     * Generate new password key for the user to reset the password.
    */
    public function ForgotPassword($email)
    {             
        $table = 'users';
        $_id   = 'User_ID';

        $this->db->where('U_Email', $email);
        $user = $this->db->get($table)->row();
         
        if ($user) {
            if ($user->U_Status != 2) {
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
            
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, array(
                'U_Password' => $password,               
            ));
            $this->db->affected_rows(); 
            if ($this->db->affected_rows() > 0) {
               // $this->load->model('emails_model');
                $data['new_pass_key'] = $new_pass_key;
                $data['user']        = $user;
                $data['userid']       = $user->$_id;
                $send = $this->emails_model->send_enquiry_email($email,'Forgot Password','We received a request to reset your 1Windo password. Click the below button to choose a new one. Your new password is'.$new_pass_key ,'ForgotPassword',$user->U_FullName); 
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

    /* User reset password */
    public function changePassword($password,$userid)
    {
        $this->load->helper('phpass');
        $hasher   = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password = $hasher->HashPassword($password);
        $table    = 'users';
        
        $this->db->where('User_ID',$userid);        
        $this->db->update($table, array(
            'U_Password' => $password
        ));
        $user = getUserData($userid);
        if ($this->db->affected_rows() > 0) {
            logActivity('User Reseted Password [User :' . $user->U_FullName . ', IP:' . $this->input->ip_address() . ']');                     
            return true;
        }

        return null;
    }

    /* Update user informations */
    public function update($data, $userid)
    {     
 
        $affectedRows = 0;
        $user_data = array();
        $privacy_data = array();
        $location_data = array();
        foreach ($this->user_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $user_data[$dbfield] = $data[$field];
            }
        }
        
        if(isset($data['Val_Password'])){
            $user_data['U_Password'] = $data['Val_Password'];
        }
        
        if(isset($data['Val_Designation'])){
            $user_data['U_Designation'] = $data['Val_Designation'];   
        }


        if(isset($data['status'])){
            $user_data['U_Status'] = $data['status'];   
        }
        
        if (empty($user_data['U_Password'])) { 
            unset($user_data['U_Password']);
        } else { 
            $this->load->helper('phpass');
            $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $user_data['U_Password']             = $hasher->HashPassword($data['Val_Password']);           
        }   

        if(isset($data['Val_CountryCode'])){
            $code = getISOCode($data['Val_CountryCode']);            
            $user_data['U_CountryCode'] = '+'.$code->CountryPhoneCode;
        }        

        $UserData = getUserData($userid); 
        
        if(!empty($user_data) ){
            $this->db->where('User_ID', $userid);
            $result =  $this->db->update('users', $user_data);
            
            if(!isset($data['user'])){
                $profile_flag = $cover_flag = $church_flag = '';
        
                $profileIamge = handle_user_profile_image($userid); 

                if(isset($profileIamge['flag'])){
                    $profile_flag = $profileIamge['flag'];    
                }   
        
                $coverIamge = handle_user_cover_image($userid);           
                if(isset($coverIamge['flag'])){
                    $cover_flag = $coverIamge['flag'];        
                }         
            
                $churchIamge = handle_user_church_image($userid);           
                if(isset($churchIamge['flag'])){
                    $church_flag = $churchIamge['flag'];        
                }  
                $Response = array(
                    'User_ID' => $UserData->User_ID, 
                    'Profile_Flag' => $profile_flag,                              
                    'Cover_Flag' => $cover_flag, 
                    'Church_Flag' => $church_flag
                    );   
            } else {
                $Response = array(
                    'User_ID' => $UserData->User_ID,                      
                    );                        
            }

        if ($this->db->affected_rows() > 0) { 
            $affectedRows++;
            do_action('after_user_updated', $userid);
        }
    

        if ($result > 0) { 
            logActivity('User profile updated [ User : ' . $UserData->U_FullName . ']');               
        }
    } else {
        $Response = array(
            'User_ID' => $UserData->User_ID, 
        );
      if ($result > 0) { 
            logActivity('User profile updated [ User : ' . $UserData->U_FullName . ']');               
        }  
    }
        return $Response; 
    
    }
 
    /**
    * Delete user
    */
    public function deleteUser($userid){
        $affectedRows = 0;
        do_action('before_user_deleted', $userid);
        $this->db->where('User_ID', $userid);
        $this->db->delete('users');
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_user_deleted');
            logActivity(_l('user_deleted').' [' . $userid . ']');
            return true;
        }
        return false;
    }
   
}
