<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Staffs_model extends W_Model
{
    private $admin_data = array('S_FirstName'=>'Val_FirstName','S_LastName'=>'Val_LastName','S_Email'=>'Val_Email','S_Password'=>'Val_Password', 'S_IsAdmin'=>'Val_IsAdmin','S_IsActive'=>'Val_IsActive','S_IsStatus'=>'Val_IsStatus','RowAddedDttm' => '', 'RowUpdatedDttm' => '');
 
    public function __construct()
    {
        parent::__construct();
    }

    /* Get registered admin's email
     * @param mixed $email
     * @return array
     */
    public function getEmail($email)
    {
        $this->db->where('S_Email', $email);
        $this->db->where('S_Status', 2);
        return $this->db->get('staffs')->result_array();
    }

    /* Get Staff Members */
    public function getStaff()
    {
        $this->db->where('S_IsAdmin !=', '1');
        //$this->db->where('S_Status', 2);

        $this->db->order_by('Staff_ID','DESC');
        return $this->db->get('staffs')->result_array();
    }

    public function getStaffData($staff='')
    {
        $this->db->where('Staff_ID', $staff);
        return $this->db->get('staffs')->row();
    }
   /* @param array $_POST data
     * @param admin_request is this request from the client area
     * @return integer Insert ID
     * Add new admin to database
     */
    public function addMember($data)
    {    
        if(!empty($data)){
            $AEmailExist = count($this->getEmail($data['Val_Email']));

            if($AEmailExist >= 1){
                return 'Exists';
            } else {
                $admin_data = array();
                foreach ($this->admin_data as $dbfield => $field) {
                    if (isset($data[$field])) {
                        $admin_data[$dbfield] = $data[$field];
                    }
                }
                if(isset($data['Val_IsAdmin']) && $data['Val_IsAdmin'] == 'on'){
                    $admin_data['S_IsAdmin'] = '1';
                }else{
                    $admin_data['S_IsAdmin'] = '2';
                }
      
                $this->load->helper('phpass');
                $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            
                $admin_data['S_Password']             = $hasher->HashPassword($data['Val_Password']);                    
                $admin_data['S_IsActive'] = '1';
                
                
                $data = do_action('before_admin_added', $data);             
                $this->db->insert('staffs', $admin_data);                    
                $adminid = $this->db->insert_id();

                if ($adminid) {                 
                    handle_staff_profile_image($adminid); 
                    $_is_admin = null;
                    $_new_admin_log = null;
                    if (is_staff_logged_in()) {
                        $_new_admin_log .= ' From Staff: ' . get_staff_user_id();
                        $_is_admin = get_staff_user_id();
                    }
                    logActivity('New Staff Registred [' . $_new_admin_log . ']', $_is_admin);
                }
                return $adminid;
            }
        }
        return false;
        
    }


     /**
    * Delete staff
    */
    public function deleteStaff($staffid){
        $affectedRows = 0;
        do_action('before_staff_deleted', $staffid);
        $this->db->where('Staff_ID', $staffid);
        $this->db->delete('staffs');
        
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        
        if ($affectedRows > 0) {
            do_action('after_staff_deleted');
            logActivity(_l('staff_deleted').' [' . $staffid . ']');
            return true;
        }
        return false;
    }
     

     /* Update staff info
     * @param  array $data staff data
     * @param  mixed $id   staff id
     * @return boolean
     */
   public function update($data, $id)
    {     
     
        $result = 0;
        $affectedRows = 0; 
        if(!empty($data)){
            $S_EmailExist = 0;
            if(!empty($data['Val_Email'])){
                $S_EmailExist = count($this->getEmail($data['Val_Email']));
            }
            if($S_EmailExist >= 1){
                return 'Exists';
            } else {
            $admin_data = array();
            foreach ($this->admin_data as $dbfield => $field) {
                if (isset($data[$field])) {
                    $admin_data[$dbfield] = $data[$field];
                }
            }

            if(isset($data['Val_IsAdmin'])) {   
                if($data['Val_IsAdmin'] == '1'){
                    $admin_data['S_IsAdmin'] = '1';
                }
            } else {
                $admin_data['S_IsAdmin'] = '2';
            }
            
            
            if(!empty($data['Val_Password'])){
                $this->load->helper('phpass');
                $hasher                       = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $admin_data['S_Password']             = $hasher->HashPassword($data['Val_Password']);   
            }
             
            if(isset($data['type']) && isset($data['Val_status'])){
                $admin_data['S_IsActive'] = $data['Val_Status']; 
                $admin_data['S_Status'] = $data['status'];   
            } else {
                $admin_data['S_IsActive'] = 1; 
                $admin_data['S_Status'] = $data['status'];
            }

            $this->db->where('Staff_ID',$id);
            $result = $this->db->update('staffs', $admin_data);      
                       
        }

        if ($this->db->affected_rows() > 0) {            
            $affectedRows++;        
        

        if ($result > 0) {
            logActivity(_l('staff_profile_updated ').'[ '._l('staff_id') . $id . ']');
            return true;
        }
        }
        }
        return false;
    }    
}
