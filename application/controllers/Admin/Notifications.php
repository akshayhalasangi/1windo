<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(){        
        $data['title'] = _l('title_notifications');
        $data['listAssets'] = 'true';
        $data['notificationsList'] = $this->notifications_model->getListOfNotifications(get_staff_user_id(),'1');
 
        $notiArray = array();
        foreach($data['notificationsList'] as $notification){
            $post = $user = '';
            $ProfileImage = UPLOAD_NO_IMAGE;
            $PostImage = UPLOAD_POST_NO_IMAGE;

            if($notification['N_Action_Type'] != '3'){
                $user = getUserData($notification['N_RelationID']);
                $UserID = $user->User_ID;
                $UserName = $user->U_FullName ;   

                if(!empty($user->U_ProfileImage)){
                    $ProfileImage = UPLOAD_USER_BASE_URL.$user->User_ID.'/'.$user->U_ProfileImage;
                } else {
                    $ProfileImage = UPLOAD_NO_IMAGE;
                }
                $PostID =  ''; 

            } else {
                $user = getUserData($notification['N_FromUserID']);

                $post = $this->posts_model->getPost($notification['N_RelationID'],true); 
                
                if(!empty($post->P_Image)){
                    $PostImage = UPLOAD_USER_BASE_URL.$post->P_UserID.'/posts/'.$post->P_Image;
                } 

                $UserID = $user->User_ID;
                $UserName = $user->U_FullName ;   

                if(!empty($user->U_ProfileImage)){
                    $ProfileImage = UPLOAD_USER_BASE_URL.$user->User_ID.'/'.$user->U_ProfileImage;
                } else {
                    $ProfileImage = UPLOAD_NO_IMAGE;
                }
                $PostID =  $post->Post_ID;  
            }
            

            $notiArray[] = array(
                'Notification_ID' => $notification['Notification_ID'],
                'Subject' => $notification['N_Subject'],
                'Message' => $notification['N_Description'],
                'User_ID' => $UserID,
                'FullName' => $UserName,
                'ProfileImage' => $ProfileImage,
                'Date' => time_ago($notification['N_Date']),
                'PostID' => $PostID,
                'PostImage' => $PostImage,
                'ActionType' => $notification['N_Action_Type'] 
            );

        }
        $data['notifications'] = $notiArray;
        $markNoticeAsReade = $this->notifications_model->getReadNotification();
        $this->load->view( NOTIFICATION_URL.'notification', $data);   
    }
 
 
    /* Delete Staffs */
    public function DeleteNotification(){    
             
        if (!has_permission('User', '', 'Delete')) {             
            //setAjaxResponse( _l('dont_have_permission_to_delete_record'),'warning',_l('warning')); 
            setFlashData(_l('dont_have_permission_to_delete_record'),'warning',_l('warning'));          
        }        
        $NoticeId = $this->input->post('id');              
        $Success = $this->notifications_model->deleteNotification($NoticeId);                 
        if($Success){             
            setAjaxResponse( _l('user_deleted_success'),'success',_l('success'));
            
        } else {
            setAjaxResponse( _l('user_deleted_fail'),'warning',_l('fail'));
            
        }
    }
 
    
    public function GetUnreadNotifications($value='')
    {
        $data['Notifications'] = $this->notifications_model->getTopListNotifications(get_staff_user_id(),'1');
        $notiArray = array();
        foreach($data['Notifications'] as $notification){
            $post = $user = '';
            $ProfileImage = UPLOAD_NO_IMAGE;
            $PostImage = UPLOAD_POST_NO_IMAGE;

            if($notification['N_Action_Type'] != '3'){
                $user = getUserData($notification['N_RelationID']);
                $UserID = $user->User_ID;
                $UserName = $user->U_FullName ;   

                if(!empty($user->U_ProfileImage)){
                    $ProfileImage = UPLOAD_USER_BASE_URL.$user->User_ID.'/'.$user->U_ProfileImage;
                } else {
                    $ProfileImage = UPLOAD_NO_IMAGE;
                }
                 $PostID =  ''; 

            } else {
                $post = $this->posts_model->getPost($notification['N_RelationID']); 
                
                if(!empty($post->P_Image)){
                    $PostImage = UPLOAD_USER_BASE_URL.$post->P_UserID.'/posts/'.$post->P_Image;
                } 

                $UserID = '';
                $UserName = ''; 
                $PostID =  $post->Post_ID;  
            }
            

            $notiArray[] = array(
                'Notification_ID' => $notification['Notification_ID'],
                'Subject' => $notification['N_Subject'],
                'Message' => $notification['N_Description'],
                'User_ID' => $user->User_ID,
                'FullName' => $user->U_FullName,
                'ProfileImage' => $ProfileImage,
                'Date' => time_ago($notification['N_Date']),
                'PostID' => $PostID,
                'PostImage' => $PostImage,
            );

        }
        $data['unreadNotifications'] = $notiArray;
        $this->load->view( NOTIFICATION_URL.'notification', $data);  
    }
}
