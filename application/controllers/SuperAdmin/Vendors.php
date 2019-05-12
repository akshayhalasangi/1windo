<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendors extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
       // if (!has_permission('Vendors', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
         
        $data['title'] = _l('Vendors');
        $data['listAssets'] = 'true';
		$data['vendorsList'] = $this->Vendors_model->getVendor();
		$data['Categories'] = $this->Categories_model->get();

        $this->load->view( VENDOR_URL.'manage', $data);   
    }
    public function Vendor($id = ''){
		
		if($id != ''){
			$ProfileData = $this->Vendors_model->getProfile(NULL,array('P_VendorID'=>$id));
			$AboutData = $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$id));
			$WorkData = $this->Vendors_model->getWorks(NULL,array('W_VendorID'=>$id));
			$LocationData = $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$id));
			$AccountData = $this->Vendors_model->getAccounts(NULL,array('A_VendorID'=>$id));
			
			if($ProfileData[0]['P_IDCardStatus'] == '2' && $ProfileData[0]['P_PersonalStatus'] == '2' && $ProfileData[0]['P_CurrentStatus'] == '2' && $ProfileData[0]['P_TermsStatus'] == '2' && $AboutData[0]['A_Status'] == '2' && $WorkData[0]['W_Status'] == '2' && $LocationData[0]['L_Status'] == '2'  && $AccountData[0]['A_Status'] == '2'){
					$data['Val_Vprofilestatus'] = '2';
					$data['Val_Vverificationstatus'] = '2';
					$success = $this->Vendors_model->update($data,$id);
			}

			$data['vendor'] = $this->Vendors_model->getVendor($id);
			$data['title'] = _l('Vendor Update');

			$data['Categories'] = $this->Categories_model->get();
			$data['addAssets'] = true;

			//Vendor Business Details
			$AboutArray =  $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$id));
			$AboutData = (object)$AboutArray[0];
			$data['About'] = $AboutData;

			//Vendor Profile Details
			$ProfileArray =  $this->Vendors_model->getProfile(NULL,array('P_VendorID'=>$id));  
			$ProfileData = (object)$ProfileArray[0]; 
			$data['Profile'] = $ProfileData;

			//Vendor Location / Address Information
			$LocationArray =  $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$id));  
			$LocationData = (object)$LocationArray[0];
			$data['Location'] = $LocationData;

			//Vendor Work Information
			$WorkArray =  $this->Vendors_model->getWorks(NULL,array('W_VendorID'=>$id));  
			$WorkData = (object)$WorkArray[0];
			$data['Work'] = $WorkData;

			//Vendor Account Information
			$AccountArray =  $this->Vendors_model->getAccounts(NULL,array('A_VendorID'=>$id));
			$AccountData = (object)$AccountArray[0];
			$data['Account'] = $AccountData;

			$this->load->view(VENDOR_URL.'vendor',$data);
		}       
	}
	public function Vendor_edit_bckup($id = ''){ //Vendor Edit Functionality which will not be used for now. Its for CRUD Operation of vendor
         
        $data = $this->input->post();

        if(!empty($this->input->post())){
            if(!empty($id)){
				unset($data['Val_Cmobilenumber']);
				unset($data['Val_Cemailaddress']);
				if(!empty($data['Val_Status']))
					$data['Val_Vverificationstatus'] = $data['Val_Status'];
                $success = $this->Vendors_model->update($data,$id);                             
				handle_vendor_profile_image($id);
                if ($success == true) {      
                    setFlashData(_l('admin_profile_update_success'),'success',_l('success'));                                            
                } else {                                                
                    setFlashData(_l('admin_profile_update_fail'),'danger',_l('fail'));                 
                }      
            } else {
				
				$data['Val_Vprofilestatus'] = '2';
                $vendor = $this->Vendors_model->add($data);    
				$success = handle_vendor_profile_image($vendor);
                if($vendor == 'Exists'){       
                    setFlashData(_l('you_are_alread_registred'),'warning',_l('warning'));                 
                } else if($vendor != false){  
					$data['Val_Relation'] = $vendor;
					$data['Val_Type'] = '2';
					$data['Val_Countrycode'] = $data['Val_Vcountrycode'];
					$data['Val_Mobilenumber'] = $data['Val_Vmobilenumber'];
					
					$Lsuccess = $this->Authentication_model->AppSignup($data);
					
					$ProfileData = $this->Vendors_model->getProfile(NULL,array('P_VendorID'=>$vendor));
					if(empty($ProfileData))	
						{
							$PostData['Val_Vendor'] = $vendor;
							$PSuccess = $this->Vendors_model->addProfile($PostData);	
						}
					
					$AboutData = $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$vendor));
					if(empty($AboutData))	
						{
							$PostData['Val_Vendor'] = $vendor;
							$ASuccess = $this->Vendors_model->addAbout($PostData);	
						}
					
					$WorkData = $this->Vendors_model->getWorks(NULL,array('W_VendorID'=>$vendor));
					if(empty($WorkData))	
						{
							$PostData['Val_Vendor'] = $vendor;
							$WSuccess = $this->Vendors_model->addWorks($PostData);	
						}	
					
					$LocationData = $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$vendor));
					if(empty($LocationData))	
						{
							$PostData['Val_Vendor'] = $vendor;
							$LSuccess = $this->Vendors_model->addLocations($PostData);	
						}	
					
					$AccountData = $this->Vendors_model->getAccounts(NULL,array('A_VendorID'=>$vendor));
					if(empty($AccountData))	
						{
							$PostData['Val_Vendor'] = $vendor;
							$ACSuccess = $this->Vendors_model->addAccounts($PostData);	
						}
					
                    setFlashData(_l('vendor_register_succes'),'success',_l('success'));   
                    redirect('Admin/Vendors');                                    
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == ''){
            $data['title'] = _l('Add New Vendor');
            $data['vendor'] = '';
        } else {            
			$ProfileData = $this->Vendors_model->getProfile(NULL,array('P_VendorID'=>$id));
			$AboutData = $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$id));
			$WorkData = $this->Vendors_model->getWorks(NULL,array('W_VendorID'=>$id));
			$LocationData = $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$id));
			$AccountData = $this->Vendors_model->getAccounts(NULL,array('A_VendorID'=>$id));
			
			if($ProfileData[0]['P_IDCardStatus'] == '2' && $ProfileData[0]['P_PersonalStatus'] == '2' && $ProfileData[0]['P_CurrentStatus'] == '2' && $ProfileData[0]['P_TermsStatus'] == '2' && $AboutData[0]['A_Status'] == '2' && $WorkData[0]['W_Status'] == '2' && $LocationData[0]['L_Status'] == '2'  && $AccountData[0]['A_Status'] == '2'){
			
			
					$data['Val_Vprofilestatus'] = '2';
					$data['Val_Vverificationstatus'] = '2';
					$success = $this->Vendors_model->update($data,$id);                             
			}

            $data['vendor'] = $this->Vendors_model->getVendor($id);             
            $data['title'] = _l('Vendor Update');



        }

		$data['Categories'] = $this->Categories_model->get();	
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'vendor',$data);            
    }
 	public function About($vendorid){

        $AboutArray =  $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$vendorid));  
		$AboutData = (object)$AboutArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			$data['Val_Aspecialization'] 	= json_encode($data['Val_Aspecialization']);
			$data['Val_Aworklinks'] 		= json_encode($data['Val_Aworklinks']);
			$data['Val_Astatus'] 			= '2';
			$success = $this->Vendors_model->updateAbout($data,$AboutData->VAboutID);                             
			if ($success == true) {      
				setFlashData('Vendor About Updated','success',_l('success'));                                            
				redirect('Admin/Vendors');                                    
			} else {                                                
				setFlashData(_l('Vendor About Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/Vendors');                                    
			}      
			
        }
		
		
		$data['About'] = $AboutData;             
		$data['title'] = 'Update Vendor About Details';
  
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'about',$data);            
    }
	public function Profile($vendorid){

        $ProfileArray =  $this->Vendors_model->getProfile(NULL,array('P_VendorID'=>$vendorid));  
		$ProfileData = (object)$ProfileArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			$data['Val_Pidcardstatus'] 			= '2';
			$data['Val_Ppersonalstatus'] 		= '2';
			$data['Val_Pcurrentstatus'] 		= '2';
			$data['Val_Ptermsstatus'] 			= '2';
			$success = $this->Vendors_model->updateProfile($data,$ProfileData->VProfileID);
			
			$FrontImageStatus 	= handle_vendor_identity_front_image($vendorid);
			$BackImageStatus 	= handle_vendor_identity_back_image($vendorid);                     
			if ($success == true) {      
				setFlashData('Vendor Profile Updated','success',_l('success'));                                            
				redirect('Admin/Vendors');                                    
			} else {                                                
				setFlashData(_l('Vendor Profile Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/Vendors');                                    
			}      
			
        }
		
		
		$data['Profile'] = $ProfileData;             
		$data['title'] = 'Update Vendor Profile Details';
  
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'profile',$data);            
    }
	public function Location($vendorid){

        $LocationArray =  $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$vendorid));  
		$LocationData = (object)$LocationArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			$data['Val_Lstatus'] 			= '2';
			$success = $this->Vendors_model->updateLocations($data,$LocationData->VLocationID);
			
			if ($success == true) {      
				setFlashData('Vendor Location Updated','success',_l('success'));                                            
				redirect('Admin/Vendors');                                    
			} else {                                                
				setFlashData(_l('Vendor Location Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/Vendors');                                    
			}      
			
        }
		
		
		$data['Location'] = $LocationData;             
		$data['title'] = 'Update Vendor Location Details';
  
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'location',$data);            
    }
	public function Work($vendorid){

        $WorkArray =  $this->Vendors_model->getWorks(NULL,array('W_VendorID'=>$vendorid));  
		$WorkData = (object)$WorkArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			
			$Gallery 			= handle_vendor_works_images($vendorid);
			$data['Val_Wstatus'] 			= '2';
			$success = $this->Vendors_model->updateWorks($data,$WorkData->VWorkID);
			
			if ($success == true) {      
				setFlashData('Vendor Work Updated','success',_l('success'));                                            
				redirect('Admin/Vendors');                                    
			} else {                                                
				setFlashData(_l('Vendor Work Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/Vendors');                                    
			}      
			
        }
		
		
		$data['Work'] = $WorkData;             
		$data['title'] = 'Update Vendor Work Details';
  
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'works',$data);            
    }
	public function Account($vendorid){

        $AccountArray =  $this->Vendors_model->getAccounts(NULL,array('A_VendorID'=>$vendorid));  
		$AccountData = (object)$AccountArray[0]; 
        $data = $this->input->post();
        if(!empty($this->input->post())){
			$data['Val_Astatus'] 			= '2';
			$success = $this->Vendors_model->updateAccounts($data,$AccountData->VAccountID);
			
			if ($success == true) {      
				setFlashData('Vendor Account Updated','success',_l('success'));                                            
				redirect('Admin/Vendors');                                    
			} else {                                                
				setFlashData(_l('Vendor Account Update Fail'),'danger',_l('fail'));                 
				redirect('Admin/Vendors');                                    
			}      
			
        }
		
		
		$data['Account'] = $AccountData;             
		$data['title'] = 'Update Vendor Account Details';
  
        $data['addAssets'] = true;        
        $this->load->view(VENDOR_URL.'account',$data);            
    }
    /* Delete Staffs */
    public function DeleteVendor(){    
             
               
        $VendorId = $this->input->post('id');              
        $Success = $this->Vendors_model->deleteVendor($VendorId);                 
	    $AuthenticationArray = $this->Authentication_model->AppGet(NULL,array('M_Type'=>'2','RelationID'=>$VendorId));                 
		
		if(!empty($AuthenticationArray))
			{
				$AuthenticationData 	= (object)$AuthenticationArray[0];
		        $ASuccess = $this->Authentication_model->AppDelete($AuthenticationData->MemberID);                 
				
			}			
				
        if($Success){             
            setAjaxResponse( _l('Vender Deleted Success'),'success',_l('success'));
            //setFlashData(_l('vendor_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('Vender Deleted Failure'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    
    /* Update staff stauts */
    public function UpdateVendorStatus()
    {     
        $data = $this->input->post();
        $VendorID = $data['id'];
        $data['Val_Vstatus'] = $data['status'];
// print_r($data);
// exit;
        if(!empty($data)){
			$Success = $this->Vendors_model->update($data,$VendorID);
			// return "Hello";
			// exit;
            if($Success){
                setAjaxResponse( _l('vendor_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('vendor_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

}