<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!has_permission('Users', '', 'HasPermission')) {             
            ajax_access_denied();            
        }
    }

    public function index(){
         
        $data['title'] = _l('title_users');
        $data['listAssets'] = 'true';
        $data['usersList'] = $this->users_model->getUser();

        $this->load->view( USER_URL.'manage', $data);   
    }
    public function User($id = ''){

         
        $data = $this->input->post();
        if(!empty($this->input->post())){
            if(!empty($id)){
                 $success = $this->users_model->update($data,$id);                             
                if ($success == true) {      
                    setFlashData(_l('admin_profile_update_success'),'success',_l('success'));                                            
                } else {                                                
                    setFlashData(_l('admin_profile_update_fail'),'danger',_l('fail'));                 
                }      
            } else {

                $user = $this->users_model->add($data);    
                if($user == 'Exists'){       
                    setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));                 
                } else if($user != false){  
                    setFlashData(_l('user_register_succes'),'success',_l('success'));   
                    redirect('Admin/Users');                                    
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == ''){
            $data['title'] = _l('add_new',_l('add_user'));
            $data['user'] = '';
        } else {            
            $data['user'] = $this->users_model->getUser($id);             
            $data['title'] = _l('txt_update_user');
        }

        $data['addAssets'] = true;        
        $this->load->view(USER_URL.'user',$data);            
    }
 
    /* Delete Staffs */
    public function DeleteUser(){    
             
               
        $UserId = $this->input->post('id');              
        $Success = $this->users_model->deleteUser($UserId);                 
        if($Success){             
            setAjaxResponse( _l('user_deleted_success'),'success',_l('success'));
            //setFlashData(_l('user_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('user_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    
    /* Update staff stauts */
    public function UpdateUserStatus()
    { 
     
        $data = $this->input->post();
        $UserID = $data['id'];
         
        if(!empty($data)){
            $Success = $this->users_model->update($data,$UserID);
            
            if($Success){
                setAjaxResponse( _l('user_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('user_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

    public function Profile($id='')
    {        
        if(!empty($id)){
            $data['user'] = $this->users_model->getUser($id);
            $data['following'] = $this->followers_model->getSingleUserFollower($id,true);
            $data['followers'] = $this->followers_model->getSingleUserFollower($id,false);
            $data['posts'] = $this->posts_model->getPostOfUsers($id);
            $user = $data['user'];
            $images = array();
            /* Previous Images Start*/
            $path = USERS_ATTACHMENTS_FOLDER.$user->User_ID;
            if(file_exists($path)){ 
            $dh  = opendir($path);
            $files = array();
            if(!empty($dh)) {
                while (false !== ($filename = readdir($dh))) {
                    if($filename != "." && $filename != ".."){
                        $img = explode('-',$filename);
                        if($img[0] != 'index.html' && $img[0] != 'posts' && $img[0] != 'stories'){
                            if($img[1] == 'PI'){
                                $files[] = $filename;    
                            }
                        }
                    } 
                }
            }
            $images=preg_grep ('/\.jpg$/i', $files);
        }
        
            $data['previousImages'] = $images;
            /* Previous Images End */

            /* Stories Start */
            $data['stories'] = $this->stories_model->getStoriesOfUsers($id,true);
            $data['storiesDate'] = $this->stories_model->getStoriesOfUsersDate($id);

            $stories = $data['stories'];
            $storiesDate = $data['storiesDate'];
            $storyImage = array();
            $dateArray = array();                
            $storiesArray = $storiesDateArray = array(); 

            foreach($stories as $key => $story){
                
                $DateArray = explode(' ',$story['RowAddedDttm']);
                $Date = $DateArray[0];
 
                if(!empty($story['S_Image'])){
                    $storyImage =  UPLOAD_USER_BASE_URL.$story['S_UserID'].'/stories/'.$story['S_Image'];
                } else {
                    $storyImage = UPLOAD_POST_NO_IMAGE;
                }
                $dateObject[] = array(
                    'Date' => $Date
                );
 
                if(in_array($Date,$storiesDate)){                
                    $Exists = array_key_exists($Date, $storiesDateArray);                     
                    if($Exists == true){ 
                        $Images = $Time = array();                        
                        $storiesDateArray[$Date]=$Date;
                        $Images = array($storyImage);
                        $Time = array(time_ago($storyImage));
                        if(!empty($storiesArray))   
                        {

                            foreach ($storiesArray as $key => $value) {
                                if($value['Date'] == $Date)
                                    {
                                        array_push($storiesArray[$key]['Images'], $storyImage);

                                        $Counter = count($storiesArray[$key]['Images']);
                                        $storiesArray[$key]['Count'] = $Counter;
                                    }
                            }
                        }
                        else
                            array_push($storiesArray, array('Date'=>$Date,'Images'=>$Images));                       
                    } else {  
                        $Images = $Time= array();     
                        $storiesDateArray[$Date]=$Date;
                        $Images = array($storyImage);
                        array_push($storiesArray, array('Date'=>$Date,'Images'=>$Images,'Count'=>'$Images'));
                                
                    }                       
                }                 
            }
            $data['storiesArray'] = $storiesArray;
            /* Stories End */
        }
        $data['title'] = _l('txt_view_profile');
        $data['viewAssets'] = true;
        $this->load->view(USER_URL.'profile',$data);
    }

    public function Comments($id='',$postid='')
    {
         if(!empty($id)){
            $data['user'] = $this->users_model->getUser($id);
            $data['following'] = $this->followers_model->getSingleUserFollower($id,true);
            $data['followers'] = $this->followers_model->getSingleUserFollower($id,false);
            $data['post'] = $this->posts_model->getPost($postid);
            $user = $data['user'];
            
            /* Previous Images Start*/
            $path = USERS_ATTACHMENTS_FOLDER.$user->User_ID;
             
            $dh  = opendir($path);
            $files = array();
            while (false !== ($filename = readdir($dh))) {
                if($filename != "." && $filename != ".."){
                    $img = explode('-',$filename);
                    if($img[0] != 'index.html' && $img[0] != 'posts' && $img[0] != 'stories'){
                        if($img[1] == 'PI'){
                            $files[] = $filename;    
                        }
                    }
                } 
            }
            $images=preg_grep ('/\.jpg$/i', $files);

            $data['previousImages'] = $images;
            /* Previous Images End */

            /* Comments */
            $myComments = $this->posts_model->getCommentsByPost($postid);
            $commentArray = array(); 
            foreach($myComments as $comment){
                $user = getUserData($comment['C_FromUserID']);
                if(!empty($user->U_ProfileImage)){
                    $CProfileImage = UPLOAD_USER_BASE_URL.$comment['C_FromUserID'].'/'.$user->U_ProfileImage;
                } else {
                    $CProfileImage = UPLOAD_NO_IMAGE;
                }
                $user = getUserData($comment['C_FromUserID']);

                $commentArray[] = array(
                    'CommentID' => $comment['Comment_ID'],
                    'Message' => $comment['C_Message'], 
                    'Date' => time_ago($comment['RowAddedDttm']),   
                    'ProfileImage' => $CProfileImage,
                    'FullName' => $user->U_FullName,                    
                );
            }
            $data['comments'] = $commentArray;
            //echo '<pre>'; print_r($commentArray);
            /* Comments */
        }
        $data['title'] = _l('txt_view_comments');
        $data['viewAssets'] = true;
        $this->load->view(USER_URL.'viewcomments',$data);
    }
    
}
