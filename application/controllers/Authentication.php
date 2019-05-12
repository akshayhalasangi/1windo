<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Authentication extends W_Controller
{
    public function __construct()
    { 	
        parent::__construct();
//        echo _l('warning');
		$this->load->model('Authentication_model');
		$data['dashboard_assets'] = true;       
    } 

 	public function SignUp(){
 		$data = $this->input->post();
		 $data['flag'] = '';
 		if(!empty($this->input->post())){
 			$admin = $this->Authentication_model->signUp($data);	
            if($admin == 'Exists'){
                $data['flag'] = '0';                
                setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));   
            } else if($admin != false){
                $data['flag'] = '1';                
                setFlashData(_l('admin_register_succes'),'success',_l('success'));   
            } else {
                $data['flag'] = '2';                
                setFlashData(_l('please_fill_all_fields'),'danger',_l('fail')); 
            }
 			
 		}   
        $data['title'] = _l('title_signup');
        $this->load->view('authentication/signup',$data);    
        
 	}

	public function index()
	  {	
	  	if (is_staff_logged_in()) {			
            redirect(getRedirectUrl());
        } 
		
        $data = $this->input->post();
		$data['flag'] = '';
		 
	  	if(	!empty($data) && $data != '' && $data['flag'] != ''){
			
			if($data['Password']){			
				$admin = get_admin(get_staff_user_id());
				$this->load->helper('phpass');
				$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					
				if ($hasher->CheckPassword($data['password'], $admin->S_Password)) { 
					$data['flag'] = '9';
					setFlashData(_l('admin_login_succes'),'success',_l('success')); 	
				} else {  
					$data['flag'] = '10';
					setFlashData(_l('admin_auth_invalid_password'),'warning',_l('warning')); 					 					
				}						
			} else {
				$data['flag'] = '11';
				setFlashData(_l('something_went_wrong'),'warning',_l('warning'));   
			} 				
		}			 
		$data['title'] = _l('title_login');	 
	    $this->load->view('authentication/login',$data);	  
	  }

    /* Update admin profile */
    public function MyProfile()
    {

        $data = $this->input->post(); 
        
        if (!empty($this->input->post())) {  
            $success = $this->Authentication_model->updateProfile($data,get_staff_user_id()); 
                
            $data['title'] = _l('admin_edit_profile');
            
            if ($success == true) {      
                setFlashData(_l('admin_profile_update_success'),'success',_l('success'));                                            
            } else {                                                
                setFlashData(_l('admin_profile_update_fail'),'danger',_l('fail'));                 
            }        
        }  
        $data['addAssets'] = true;
        $data['title'] = _l('txt_my_profile');
        $data['admin'] = $this->Authentication_model->getAdmin(get_staff_user_id());
         
        $this->load->view(camelToSnake(getRedirectUrl()).'/staffs/staff',$data);
    }

	
	public function Admin()
    { 
        if (is_staff_logged_in()) {
            redirect(getRedirectUrl());
        }  

        $data = $this->input->post(); 
		$data['flag'] = '';
        if (!empty($this->input->post())) {  
        	$Remember = '';
        	if(isset($data['Remember'])){
        		$Remember = $data['Remember'];
        	}	
            $success = $this->Authentication_model->signIn($data['Email'],$data['Password'],true,$Remember);
             
			$data['title'] = _l('admin_auth_login_heading');
		
                if (is_array($success) && isset($success['memberinactive'])) {  
					$data['flag'] = '9';
                	setFlashData(_l('admin_auth_inactive_account'),'warning',_l('warning')); 					                    
                } else if ($success == false) {    
					$data['flag'] = '10';
					setFlashData(_l('admin_auth_invalid_email_or_password'),'danger',_l('fail')); 
					//redirect(base_url('Authentication'));		
					
                } else {					
                	setFlashData(_l('admin_login_succes'),'success',_l('success'));                 	 
					redirect(base_url('Authentication'));					  
				}
			$this->load->view('authentication/login',$data);
           
        } else {
        	redirect(base_url('Authentication'));	
        }
       
    }
    
  
    
	  
    public function ResetPassword()
    {

        $data = $this->input->post(); 
         
		$data['flag'] = '';	

			if($this->input->post() != '' && !empty($this->input->post()) )  { 	        
                $success = $this->Authentication_model->setPassword($data);
                
                if (is_array($success) && $success['expired'] == true) {                  	                                                            
                    //setAjaxResponse( _l('password_reset_key_expired'),'danger',_l('fail'));     
                    setFlashData(_l('password_reset_key_expired'),'danger',_l('fail'));                	 
                } else if ($success == true) {                                    	               
                    //setAjaxResponse(_l('password_reset_message'),'success',_l('success')); 
                    setFlashData(_l('password_reset_message'),'success',_l('success'));
                } else {                       
                	//setAjaxResponse(_l('password_reset_message_fail'),'danger',_l('fail'));                 	 
                    setFlashData(_l('password_reset_message_fail'),'danger',_l('fail'));
                } 				  
            }  
            $data['addAssets'] = true;
            $data['adminuser'] = $this->Authentication_model->getAdmin(get_staff_user_id());      
            $data['title']= _l('txt_reset_password');
		    if($this->uri->segment(1) == 'Authentication'){				
                $this->load->view('authentication/resetpassword',$data);        
            } else {
                $this->load->view(camelToSnake(getRedirectUrl()).'/resetpassword',$data);
            }
		
  }
  
  public function ForgotPassword()
    {
        if (is_staff_logged_in()) {
            redirect(getRedirectUrl());
        }

		$data = $this->input->post();	
		$data['flag'] = '';	
        if (!empty($data['Email'])) {          
            $success = $this->Authentication_model->ForgotPassword($data['Email'], true);

            if (is_array($success) && isset($success['memberinactive'])) { 
            	$data['flag'] = '3';
            	setFlashData(_l('admin_auth_inactive_account'),'danger',_l('warning'));              				    				
            } else if ($success == true) {                				
            	$data['flag'] = '4';
            	setFlashData(_l('check_email_for_reseting_password'),'success',_l('success'));            	
            } else {              
            	$data['flag'] = '5';   
            	setFlashData(_l('something_went_wrong'),'danger',_l('fail'));            					 
			}				 
        } 
        $data['title'] = _l('title_forgot_password');
        $this->load->view('authentication/forgot',$data);
    }
	
	public function Logout()
    {
        $this->Authentication_model->logOut();
        do_action('after_user_logout');
        redirect(site_url('Authentication/'));
    }

    /* User Permission */
    public function Permissions($staffid=''){  
        $data['title'] = _l('title_staff_permission');     
         
        $data['permissionList'] = $this->Authentication_model->getPermission($staffid);
        $data['StaffID'] = get_staff_user_id();
        $data['modulesList'] = get_admin_controllers_lists();
        $data['staffsList'] = $this->staffs_model->getStaff();

        $this->load->view( STAFF_URL.'permission', $data);   
    }

     /* Add Permission */
    public function AddPermission(){  
        $data = $this->input->post();
        if(!empty($data)){
            $exist = count($this->Authentication_model->checkPermission($data['Staff']));
            if($exist > 0){
                $data['addPermission'] = $this->Authentication_model->updatePermission($data,$data['Staff']);    
                setFlashData(_l('permission_updated_succes'),'success',_l('success'));                  
                redirect('Admin/Permissions/'.$data['Staff']);       
            } else {
                $data['addPermission'] = $this->Authentication_model->addPermission($data);        
                setFlashData(_l('permission_added_succes'),'success',_l('success')); 
                redirect('Admin/Permissions/'.$data['Staff']);
            }
        }                    
    $data['modulesList'] = get_admin_controllers_lists();
        $data['staffsList'] = $this->staffs_model->getStaff();
        $data['title'] = _l('title_staff_permission');     
 
        $this->load->view( STAFF_URL.'permission', $data); 


    }

 
}