<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends SS_Controller
{
    public function __construct()
    {
        parent::__construct();
		header('Content-Type: application/json');
     	$this->Val_Limit = 10; 
		
    }

    public function index()
    {
		$result = array('status'=>'info','flag'=>'4','message'=>'Access Denied');	
		$this->data = $result;
        echo json_encode($this->data);
    }
	
	public function Fetch()
    {		     
		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetAllAdmins'){
			$result = array('status'=>'info','flag'=>'4','message'=>'Access Denied');	

		} else if(!empty($data['Action']) && $data['Action'] == 'SingleAdmin'){				
				
				if( !empty($data['Val_Admin']) && $data['Val_Admin'] != '') {
					
					$MasterData = $this->Masters_model->get($data['Val_Admin']);
					
					if ($MasterData) {
						
						$MasterFullName = $MasterData->M_FirstName." ".$MasterData->M_LastName;
						$MasterProfileImage = (!empty($MasterData->M_ProfileImage) ? UPLOAD_MASTER_BASE_URL.$MasterData->MasterID.'/'.$MasterData->M_ProfileImage : NULL);
						
						$TechsArray = $this->Technicians_model->get();
						
						if(!empty($TechsArray )) 
							$TechsCount = (string)count($TechsArray);
						else
							$TechsCount = "0";
								
						
						$Records[] = array(  
							'AdminID' => $MasterData->MasterID,
							'FullName' => $MasterFullName,
							'FirstName'=> $MasterData->M_FirstName,
							'LastName'=> $MasterData->M_LastName,
							'Mobile'=> $MasterData->M_Mobile,
							'Email'=> $MasterData->M_Email,
							'ProfileImage'=> $MasterProfileImage,
							'Notification'=> $MasterData->M_Notification,
							'Technicians'=> $TechsCount,
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Admin Record Fetched','data'=>$Records);	
					} elseif ($MasterData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'Admin Record Not Fetched','data'=>$data['Val_Admin']);	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	// Edit Profile Of Master ANd Service Provider
	public function Profile()
    {		     
		$data = $this->input->post();	
	
		if( !empty($data) && $data['Action'] == 'Update' ) {	
													
					$success = $this->Masters_model->update($data,$data['Val_Admin']);	
						
					$Upload = handle_master_profile_image($data['Val_Admin']);
					
					$MasterData = $this->Masters_model->get($data['Val_Admin']);
					
					if ($MasterData) {
					
							$data['Val_Relation'] = $data['Val_Admin'];
							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'3');	
							
							$MasterFullName = $MasterData->M_FirstName." ".$MasterData->M_LastName;
							$MasterProfileImage = (!empty($MasterData->M_ProfileImage) ? UPLOAD_USER_BASE_URL.$MasterData->MasterID.'/'.$MasterData->M_ProfileImage : NULL);
							
							$Record = array(  
										'MasterID' => $MasterData->MasterID,
										'FullName' => $MasterFullName,
										'FirstName'=> $MasterData->M_FirstName,
										'LastName'=> $MasterData->M_LastName,
										'Mobile'=> $MasterData->M_Mobile,
										'Email'=> $MasterData->M_Email,
										'ProfileImage'=> $MasterProfileImage,						
									);		
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Master Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Master Profile Not Updated','data'=>$data['Val_Admin']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
					$success = $this->Masters_model->delete($data['Val_Master']);	
					
					if ($success) {
							$Adata['Val_Relation'] = $data['Val_Master'];
							$Adata['Val_Status'] = '1';
							$Asuccess = $this->Authentication_model->AppUpdate($Adata,$Adata['Val_Relation'],'1');	
							
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Master Profile Deleted Successfully','data'=>'Confidential');	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Master Profile Not Deleted','data'=>$data['Val_Master']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
		}
        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	// Edit Profile Of Master ANd Service Provider
	public function Dashboard()
	{

		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetData'){				
			
				$MasterData = $this->Masters_model->get($data['Val_Masterid']);						
				if ($MasterData) {	
					
					$MasterRecord = array( 
						'status'=>'success',
						'message'=>'Master Record Fetched',
						'MasterID' => $MasterData->MasterID,
						'MasterJID'=>$MasterData->M_JID,
						'FullName'=> $MasterData->M_FirstName." ".$MasterData->M_LastName,
						'FirstName'=> $MasterData->M_FirstName,
						'LastName'=> $MasterData->M_LastName,
						'CountryCode'=> $MasterData->M_CountryCode,
						'Mobile'=> $MasterData->M_Mobile,
						'Email'=> $MasterData->M_Email,
						'Country'=> $MasterData->M_Country,
						'State'=> $MasterData->M_State,
						'City'=> $MasterData->M_City,
						'ProfileImage'=> UPLOAD_USER_BASE_URL.$MasterData->MasterID.'/'.$MasterData->M_ProfileImage,
						'Privacy'=> $MasterData->M_Privacy
						);	

				} elseif ($MasterData == false) {					
					$MasterRecord = array('status'=>'error','flag'=>'2','message'=>'Master Record Not Fetched.');	
				}
				
				
//				$NotificationArray = $this->Masters_model->getMaster();						
				$UnpublishedEventsArray = $this->Events_model->getByMaster($data['Val_Masterid'],array('E_Status'=>'1'));									
				$NotificationArray = $this->Notifications_model->getByMaster($data['Val_Masterid'],array('N_IsRead'=>'2'));									
//				print_r($NotificationArray);
				if ( !empty($UnpublishedEventsArray) && !empty($NotificationArray) ) {	
					
					$MiscRecords = array( 
						'status'=>'success',
						'message'=>'Misc Records Fetched',
						'NotificationCount' => count($NotificationArray),
						'UnpublishedCount' => count($UnpublishedEventsArray),
						);	

				} else if (empty($UnpublishedEventsArray) && empty($NotificationArray)) {					
					$MiscRecords = array(
						'status'=>'error',
						'message'=>'Misc Records not Found',
						'NotificationCount' => 0,
						'UnpublishedCount' => 0,
						);	
				} else if (!empty($UnpublishedEventsArray)) {					
					$MiscRecords = array(
						'status'=>'info',
						'message'=>'Events Fetched',
						'NotificationCount' => 0,
						'UnpublishedCount' => count($UnpublishedEventsArray),
						);	
				} else if (!empty($NotificationArray)) {					
					$MiscRecords = array(
						'status'=>'info',
						'message'=>'Notifications Fetched',
						'NotificationCount' => count($NotificationArray),
						'UnpublishedCount' => 0,
						);	
				}
				
				
				
				$Records['MasterData'] = $MasterRecord;
				$Records['EventsData'] = (!empty($EventRecords) ? $EventRecords : (object)$EventRecords) ;
				$Records['MiscData'] = $MiscRecords;
				
				$result = array('status'=>'success','flag'=>'1','message'=>'Master Dashboard Data Fetched','data'=>$Records);	
				
		} else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	
		
		
		return false;
	}
		
	// Edit Profile Of Master ANd Service Provider
	public function Contacts()
    {		     
		$this->load->model('Masters_model');
		$data = $this->input->post();	
//		print_r($data);
	

		
		$Array = $data['Val_Contacts'];
		$Object = json_decode($data['Val_Contacts']);
			
		
		if( !empty($data) && $data['Action'] == 'Check' ){	
			
			if(!empty($Array))
			{
				$MasterArray = $this->Masters_model->getMobiles();
				
				$TotalExist = count($MasterArray);	

				$k = 0;

				$TmpMatchAtrray = array();

				foreach($Object as $Record)
					{
						$CleanMobile = cleanMobile($Record->Mobile);	
						
						for($i = 0 ; $i < $TotalExist; $i++){ 	 										
							
							$FullMasterMobile = $MasterArray[$i]['M_CountryCode'].$MasterArray[$i]['M_Mobile'];
							
							$ZeroMasterMobile = '0'.$MasterArray[$i]['M_Mobile'];
							
							if($CleanMobile == $MasterArray[$i]['M_Mobile'] || $CleanMobile == $FullMasterMobile  || $CleanMobile == $ZeroMasterMobile ){

								$TmpMatchAtrray['FullName'][$k] = $Record->FullName; 

								$TmpMatchAtrray['Mobile'][$k] = $Record->Mobile;

								$TmpMatchAtrray['Masterid'][$k] = $MasterArray[$i]['MasterID']; 

								$TmpMatchAtrray['Masterimages'][$k] = $MasterArray[$i]['M_ProfileImage']; 
	
								$k++;																			
	
							
							}
							
							
						} 	
						
						$i++;	
					}				
				foreach($Object as $Record)
					{		

						if(isset($TmpMatchAtrray) && !empty($TmpMatchAtrray) )	{

							if( in_array($Record->Mobile,$TmpMatchAtrray['Mobile']) ) 	{

								$Key = array_search($Record->Mobile,$TmpMatchAtrray['Mobile'],true);
	
								$NewResultArray[] = array(
									'FullName'=> $Record->FullName,
									'Mobile' => $Record->Mobile,
									'Heyoo' => '2',
									'MasterID'=> $TmpMatchAtrray['Masterid'][$Key],
									'ProfileImage'=> UPLOAD_USER_BASE_URL.$TmpMatchAtrray['Masterid'][$Key].'/'.$TmpMatchAtrray['Masterimages'][$Key],
								);

							} else {
	
								$NewResultArray[] = array(
									'FullName'=> $Record->FullName,
									'Mobile' => $Record->Mobile,
									'Heyoo' => '1',
									'MasterID'=> NULL,
									'ProfileImage'=> NULL
								);

							}										

						} else {

							$NewResultArray[] = array(

								'FullName'=> $Record->FullName,

								'Mobile' => $Record->Mobile,

								'Heyoo' => '1',

								'MasterID'=> NULL,
							);

						}										

					}

				$result = array('status'=>'success','flag'=>'1','message'=>'Contact List Retrieved Successfully.','data'=>$NewResultArray);	
			} else {

				$result = array('status'=>'error','flag'=>'2','message'=>'Contacts Not Found');
			}
			
		} 
		else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
		}
        $this->data = $result;
        echo json_encode($this->data);	
	}
		 
}


?>