<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DeliveryBoys extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
       // if (!has_permission('Vendors', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
         
        $data['title'] = _l('Delivery Boys');
        $data['listAssets'] = 'true';
        $data['deliveryboysList'] = $this->Deliveryboys_model->getDeliveryBoy();
        
        $this->load->view( DELIVERYBOY_URL.'manage', $data);
    }
    public function DeliveryBoy($id = ''){

        if($id != ''){
            $data = $this->input->post();

            if(!empty($this->input->post())){
                if(!empty($id)){
                    unset($data['Val_DBmobilenumber']);
                    unset($data['Val_DBemailaddress']);
                    if(!empty($data['Val_Status']))
                        $data['Val_DBprofilestatus'] = $data['Val_Status'];
                        
                    $success = $this->Deliveryboys_model->update($data,$id);   
                    handle_deliveryboy_profile_image($id);                          
                    if ($success == true) {      
                        setFlashData(_l('Delivery Boy Profile updated'),'success',_l('success'));                                            
                    } else {                                                
                        setFlashData(_l('Delivery Boy Profile Update Failed'),'danger',_l('fail'));                 
                    }      
                } else {
                    $data['Val_DBprofilestatus'] = '2';
                    $deliveryboy = $this->Deliveryboys_model->add($data); 
                    handle_deliveryboy_profile_image($deliveryboy);   
                    if($deliveryboy == 'Exists'){       
                        setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));                 
                    } else if($deliveryboy != false){  
                        $data['Val_Relation'] = $deliveryboy;
                        $data['Val_Type'] = '2';
                        $data['Val_Countrycode'] = $data['Val_DBcountrycode'];
                        $data['Val_Mobilenumber'] = $data['Val_DBmobilenumber'];
                        
                        $Lsuccess = $this->Authentication_model->AppSignup($data);
                        
                        $AccountData = $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$deliveryboy));
                        if(empty($AccountData))	
                        {
                            $PostData['Val_Deliveryboy'] = $deliveryboy;
                            $ACSuccess = $this->Deliveryboys_model->addAccounts($PostData);	
                        }
                        
                        setFlashData(_l('Delivery Boy Registered Successfully'),'success',_l('success'));   
                        redirect('Admin/DeliveryBoys');                                    
                    } else {
                        setFlashData(_l('Please fill all the fields'),'danger',_l('fail'));               
                    }            
                }
            }
            if($id == ''){
                // $data['title'] = _l('Add New Delivery Boy');
                $data['title'] = _l('Delivery');
                $data['deliveryboy'] = '';
            } else {            
                $AccountData = $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$id));
        
                //$data['Val_DBprofilestatus'] = '2';
                //$success = $this->Deliveryboys_model->update($data,$id);                             

                $data['deliveryboy'] = $this->Deliveryboys_model->getDeliveryBoy($id);             
                // $data['title'] = _l('Delivery Boy Update');
                $data['title'] = _l('Delivery');
            }

            $data['addAssets'] = true;

            $AccountArray =  $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$id));  
            $AccountData = (object)$AccountArray[0];
            $data['Account'] = $AccountData;

            $this->load->view( DELIVERYBOY_URL.'view', $data);
            // $this->load->view(DELIVERYBOY_URL.'deliveryboy',$data);
        }else{
            redirect('Admin/DeliveryBoys');
        }
    }
 	
	public function Account($deliveryboyid){

        $AccountArray =  $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$deliveryboyid));  
		$AccountData = (object)$AccountArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			$data['Val_Astatus'] 			= '2';
			$success = $this->Deliveryboys_model->updateAccounts($data,$AccountData->DBAccountID);
			
			if ($success == true) {      
				setFlashData('Delivery Boy Account Updated','success',_l('success'));                                            
				redirect('Admin/DeliveryBoys');
			} else {                                                
				setFlashData(_l('Delivery Boy Account Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/DeliveryBoys');
			}      
			
        }
		
		
		$data['Account'] = $AccountData;             
		$data['title'] = 'Update Delivery Boy Account Details';
  
        $data['addAssets'] = true;        
        $this->load->view(DELIVERYBOY_URL.'account',$data);            
    }
    /* Delete Staffs */
    public function DeleteDeliveryBoy(){    
             
               
        $DeliveryBoyId = $this->input->post('id');              
        $Success = $this->Deliveryboys_model->deleteDeliveryBoy($DeliveryBoyId);                 
	    $AuthenticationArray = $this->Authentication_model->AppGet(NULL,array('M_Type'=>'3','RelationID'=>$DeliveryBoyId));                 
		
		if(!empty($AuthenticationArray))
			{
				$AuthenticationData 	= (object)$AuthenticationArray[0];
		        $ASuccess = $this->Authentication_model->AppDelete($AuthenticationData->MemberID);                 
				
			}			
				
        if($Success){             
            setAjaxResponse( _l('Delivery Boy Deleted Success'),'success',_l('success'));
            //setFlashData(_l('vendor_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('Delivery Boy Deleted Failure'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    
    /* Update staff stauts */
    public function UpdateDeliveryBoyStatus()
    {     
        $data = $this->input->post();
        $DeliveryBoyID = $data['id'];
        $data['Val_DBstatus'] = $data['status'];
        //  print_r($data);
        //  exit;
        if(!empty($data)){
            $Success = $this->Deliveryboys_model->update($data,$DeliveryBoyID);
            // return $success;
            // exit;
            if($Success){
                setAjaxResponse( _l('vendor_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('vendor_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

}