<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Notifications_model extends W_Model
{
    private $notification_data = array('Notification_ID'=>'Notification_ID','N_Type'=>'Val_Type','N_Action_Type'=>'Val_Action_Type','N_IsRead'=>'Val_IsRead', 'N_Description'=>'Val_Description','N_Subject'=>'Val_Subject','N_FromUserID'=> 'Val_FromUserID', 'N_ToUserID'=>'Val_ToUserID','N_UserType'=>'Val_UserType','N_Date'=>'Val_Date', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
 
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get testimonial details    
    */
    public function getNotification($notification_id = '' )
    {
        $this->db->where('N_IsRead','2');
        if ($notification_id != '') {            
            $this->db->where('Notification_ID', $notification_id);
            $result = $this->db->get('notifications')->row();
        } else {            
            $this->db->order_by('Notification_ID', 'DESC');
            $result = $this->db->get('notifications')->result_array();      
        } 
        return $result;
    }	
   
    /* Delete notification */
    public function deleteNotification($notificationid){
        $affectedRows = 0;
        do_action('before_notification_deleted', $notificationid);
        $this->db->where('Notification_ID', $notificationid);
        $this->db->delete('notifications');
     //  echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_notification_deleted');
            logActivity(_l('notification_deleted').' [' . $notificationid . ']');
            return true;
        }
        return false;
    }  

    /* Delete notification */
    public function deleteNotificationByPost($postid){
        $affectedRows = 0;
        do_action('before_notification_deleted', $postid);
        $this->db->where("N_Type IN ('1','2')");
        $this->db->where("N_Action_Type IN ('5','6')");
        $this->db->where('N_RelationID', $postid);
        $this->db->delete('notifications');
                
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_notification_deleted');
            logActivity(_l('notification_deleted').' [' . $postid . ']');
            return true;
        }
        return false;
    }  

    /* Make Read Notiifacation*/
    public function getReadNotification()
    {
        $Notification['N_IsRead'] = '1';
        $this->db->where('N_IsRead','2');
        $this->db->where('N_UserType','1');
        $this->db->update('members',$Notification);
    }

    
    public function getListOfNotifications($userid,$usertype='')
    {
        $this->db->where('N_ToUserID',$userid);
        $this->db->where('N_UserType',$usertype);
        //$this->db->where('N_IsRead','2');
        $result = $this->db->get('notifications')->result_array();
        return $result;
    } 

    /* Today Notifications */
    public function todayNotification()
    {        
        $this->db->where('N_UserType','2');
        $this->db->where('DATE(`N_Date`) = CURDATE()');
        $this->db->order_by('Notification_ID','DESC');
        return $result = $this->db->get('notifications')->result_array();
    }

    /* This week Notifications */
    public function thisWeekNotification()
    {                
        $this->db->where('N_UserType','2');
        $this->db->where('N_Date > DATE_SUB(NOW(), INTERVAL 1 WEEK)');
        $this->db->order_by('Notification_ID','DESC');
        return $result = $this->db->get('notifications')->result_array();
    }

    /* This last Notifications */
    public function lastWeekNotification()
    {                
        $this->db->where('N_UserType','2');
        $this->db->where('YEARWEEK(`N_Date`, 1) = YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1)');
        $this->db->order_by('Notification_ID','DESC');
        return $result = $this->db->get('notifications')->result_array();
    }
   
     /* This month Notifications */
    public function thisMonthNotification()
    {                
        $this->db->where('N_UserType','2');
        $this->db->where('MONTH(N_Date) = MONTH(CURRENT_DATE()) AND YEAR(N_Date) = YEAR(CURRENT_DATE())');
        $this->db->order_by('Notification_ID','DESC');
       return  $result = $this->db->get('notifications')->result_array();
    }

    public function updateToken($data, $where)
    {
        $affectedRows = 0;
        $this->db->where($where);
        $this->db->from('notifications');
        if ($this->db->update(TBL_MEMBERS, $data)) {
            $affectedRows++;
        }
        $query = $this->db->last_query();
        if($affectedRows>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    
     

    }
}
