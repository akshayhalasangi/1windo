<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
       // if (!has_permission('Customers', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
         
        $data['title'] = _l('title_customers');
        $data['listAssets'] = 'true';
        $data['customersList'] = $this->Customers_model->getCustomer();

        $this->load->view( CUSTOMER_URL.'manage', $data);   
    }
    public function Customer($id = ''){

         
        $data = $this->input->post();
        if(!empty($this->input->post())){
            if(!empty($id)){
				unset($data['Val_Cmobilenumber']);
				unset($data['Val_Cemailaddress']);
                 $success = $this->Customers_model->update($data,$id);                             
                if ($success == true) {      
                    setFlashData(_l('admin_profile_update_success'),'success',_l('success'));                                            
                } else {                                                
                    setFlashData(_l('admin_profile_update_fail'),'danger',_l('fail'));                 
                }      
            } else {

                $customer = $this->Customers_model->add($data);    
                if($customer == 'Exists'){       
                    setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));                 
                } else if($customer != false){  
					$data['Val_Relation'] = $customer;
					$data['Val_Type'] = '1';
					$data['Val_Countrycode'] = $data['Val_Ccountrycode'];
					$data['Val_Mobilenumber'] = $data['Val_Cmobilenumber'];
					
					$Lsuccess = $this->Authentication_model->AppSignup($data);
                    setFlashData(_l('customer_register_succes'),'success',_l('success'));   
                    redirect('Admin/Customers');                                    
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == ''){
            $data['title'] = _l('add_new',_l('Customer'));
            $data['customer'] = '';
        } else {            
            $data['customer'] = $this->Customers_model->getCustomer($id);             
            $data['title'] = _l('txt_update_customer');
        }

        $data['addAssets'] = true;        
        $this->load->view(CUSTOMER_URL.'customer',$data);            
    }
 
    /* Delete Staffs */
    public function DeleteCustomer(){    
             
               
        $CustomerId = $this->input->post('id');              
        $Success = $this->Customers_model->deleteCustomer($CustomerId);                 
        $AuthenticationArray = $this->Authentication_model->AppGet(NULL,array('M_Type'=>'1','RelationID'=>$CustomerId));                 
		
		if(!empty($AuthenticationArray))
        {
            $AuthenticationData 	= (object)$AuthenticationArray[0];
            $ASuccess = $this->Authentication_model->AppDelete($AuthenticationData->MemberID);            
        }
		
        if($Success && $ASuccess){             
            setAjaxResponse( _l('customer_deleted_success'),'success',_l('success'));
            //setFlashData(_l('customer_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('customer_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    
    /* Update staff stauts */
    public function UpdateCustomerStatus()
    { 
     
        $data = $this->input->post();
        $CustomerID = $data['id'];
        $data['Val_Cstatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Customers_model->update($data,$CustomerID);
            
            if($Success){
                setAjaxResponse( _l('customer_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('customer_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

    public function Profile($id='')
    {        
        if(!empty($id)){
            $data['customer'] = $this->Customers_model->getCustomer($id);
            $customer = $data['customer'];
            $images = array();
        }
        $data['title'] = _l('txt_view_profile');
        $data['viewAssets'] = true;
        $this->load->view(CUSTOMER_URL.'profile',$data);
    }

    public function Comments($id='',$postid='')
    {
         if(!empty($id)){
            $data['customer'] = $this->Customers_model->getCustomer($id);
            $data['following'] = $this->followers_model->getSingleCustomerFollower($id,true);
            $data['followers'] = $this->followers_model->getSingleCustomerFollower($id,false);
            $data['post'] = $this->posts_model->getPost($postid);
            $customer = $data['customer'];
            
            /* Previous Images Start*/
            $path = CUSTOMERS_ATTACHMENTS_FOLDER.$customer->CustomerID;
             
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
                $customer = getCustomerData($comment['C_FromCustomerID']);
                if(!empty($customer->U_ProfileImage)){
                    $CProfileImage = UPLOAD_CUSTOMER_BASE_URL.$comment['C_FromCustomerID'].'/'.$customer->U_ProfileImage;
                } else {
                    $CProfileImage = UPLOAD_NO_IMAGE;
                }
                $customer = getCustomerData($comment['C_FromCustomerID']);

                $commentArray[] = array(
                    'CommentID' => $comment['Comment_ID'],
                    'Message' => $comment['C_Message'], 
                    'Date' => time_ago($comment['RowAddedDttm']),   
                    'ProfileImage' => $CProfileImage,
                    'FullName' => $customer->U_FullName,                    
                );
            }
            $data['comments'] = $commentArray;
            //echo '<pre>'; print_r($commentArray);
            /* Comments */
        }
        $data['title'] = _l('txt_view_comments');
        $data['viewAssets'] = true;
        $this->load->view(CUSTOMER_URL.'viewcomments',$data);
    }
    
}
