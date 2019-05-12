<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Staffs extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!has_permission('Staffs', '', 'HasPermission')) {
            ajax_access_denied();
        }
    }

    public function index(){
         
        $data['title'] = _l('title_staffs');
        $data['listAssets'] = 'true';
        $data['staffsList'] = $this->staffs_model->getStaff();

        $this->load->view( STAFF_URL.'manage', $data);   
    }
    public function Staff($id = ''){
        
        $data = $this->input->post();
        if(!empty($this->input->post())){
            if(!empty($id)){
                 $success = $this->Authentication_model->updateProfile($data,$id); 
                
                $data['title'] = _l('admin_edit_profile');
                
                if ($success == true) {      
                    setFlashData(_l('admin_profile_update_success'),'success',_l('success'));                                            
                } else {
                    setFlashData(_l('admin_profile_update_fail'),'danger',_l('fail'));                 
                }      
            } else {

                $admin = $this->staffs_model->addMember($data);    
                if($admin == 'Exists'){       
                    setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));                 
                } else if($admin != false){  
                    setFlashData(_l('staff_register_succes'),'success',_l('success'));   
                    redirect('SuperAdmin/Staffs');
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if(!empty($id)){
            $data['member'] = $this->staffs_model->getStaffData($id); 
                   
            $data['title'] = _l('txt_update_staff');
        } else {
            $data['title'] = _l('add_new',_l('add_staff'));
        }
        $data['addAssets'] = true;
        $CI =& get_instance();
        $CI->load->model('area_model');
        $data['areas'] = $this->area_model->getAllAreas();
        $this->load->view(camelToSnake(getRedirectUrl()).'/staffs/staff',$data);
    }
 
    /* Delete Staffs */
    public function DeleteStaff(){    
             
                
        $StaffId = $this->input->post('id');              
        $Success = $this->staffs_model->deleteStaff($StaffId);                 
        if($Success){             
            setAjaxResponse( _l('staff_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    
    /* Update staff stauts */
    public function UpdateStaffStatus()
    { 
     
        $data = $this->input->post();
        $StaffID = $data['id'];
         
        if(!empty($data)){
            $Success = $this->staffs_model->update($data,$StaffID);
            
            if($Success){
                setAjaxResponse( _l('staff_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('staff_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
    
}