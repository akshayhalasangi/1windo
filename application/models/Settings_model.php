<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Settings_model extends W_Model
{
    private $encrypted_fields = array('smtp_password');

    public function __construct()
    {
        parent::__construct();
     
    }

    /**
     * Update all settings
     * @param  array $data all settings
     * @return integer
     */
    public function update($data)
    {     
               
        $affectedRows = 0;
        $data         = do_action('before_settings_updated', $data);
         
        $all_settings_looped = array();
          
        if(!empty($data)) {
                $TermsName = 'terms_of_use';
                $PrivacyPolicyName = 'privacy_policy'; 
                // Check if the option exists
                
                $this->db->where('name', $PrivacyPolicyName);
                $pvcpolicy = $this->db->count_all_results('options');

                $this->db->where('name', $TermsName);
                $terms = $this->db->count_all_results('options');
                 
                if ($terms == 0 && !empty($data['terms_of_use'])) {                       
                    $this->db->insert('options', array(
                        'value' => $data['terms_of_use'],
                        'name' => $TermsName
                    )); 
                }
                if ($pvcpolicy == 0 && !empty($data['privacy_policy'])) {                       
                    $this->db->insert('options', array(
                        'value' => $data['privacy_policy'],
                        'name' => $PrivacyPolicyName,
                    ));  
                }
                if(!empty($data['privacy_policy'])){
                    $this->db->where('name', $PrivacyPolicyName);
                    $this->db->update('options', array(
                        'value' => $data['privacy_policy']
                    ));
                } 
                if(!empty($data['terms_of_use'])){
                    $this->db->where('name', $TermsName);
                    $this->db->update('options', array(
                        'value' => $data['terms_of_use']
                    ));
                } 
                
                if ($this->db->affected_rows() > 0) {
                    $affectedRows++;
                }                          
          return true;
      }
        return false;
    }

      

    /**
     * Update all settings
     * @param  array $data all settings
     * @return integer
     */
    public function updateEmail($data)
    {        
        $affectedRows = 0;
        $data         = do_action('before_settings_updated', $data);
         
        $all_settings_looped = array();
             
        if(!empty($data)) {
            foreach ($data['semail_'] as $name => $val) {   
                // Check if the option exists
                $this->db->where('name', 'semail_'.$data['emailindex'][$name]);
                $exists = $this->db->count_all_results('options');
                
                if ($exists == 0 && $data['semail_'][$name] != '') {                       
                       $this->db->insert('options', array(
                        'value' => $val,
                        'name' => 'semail_'.$data['emailindex'][$name]
                    ));  
                  
                }
 
                $this->db->where('name', 'semail_'.$data['emailindex'][$name]);
                $this->db->update('options', array(
                    'value' => $val
                ));
                 
                if ($this->db->affected_rows() > 0) {
                    $affectedRows++;
                }                  
          }
          return true;
      }
        return false;
    }     
}
