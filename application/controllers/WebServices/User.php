<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends SS_Controller
{
    public function __construct()
    {
        parent::__construct();
		header('Content-Type: application/json');
     	$this->Val_Limit = 10; 
		
    }

    public function index()
    {
		echo "Access Denied";	
    }
	
	public function Fetch()
    {		     
		$this->load->model('Users_model');
		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetAllUsers'){				
			
			$UsersArray = $this->Users_model->get();
					
			if ($UsersArray) {

				foreach($UsersArray as $UserArray) {
						
						$UserFullName = $UserArray['U_FirstName']." ".$UserArray['U_LastName'];
						$UserProfileImage = UPLOAD_USER_BASE_URL.$UserArray['UserID'].'/'.$UserArray['U_ProfileImage'];
						$Records[] = array(  
							'TechnicianID' => $UserArray['UserID'],
							'FullName' => $UserFullName,
							'FirstName' => $UserArray['U_FirstName'],
							'LastName' => $UserArray['U_LastName'],
							'MobileNumber' => $UserArray['U_Mobile'],
							'EmailAddress' => $UserArray['U_Email'],
							'ProfileImage' => $UserProfileImage,
							'Status' => $UserArray['U_Status']
							);	

				
				}						
						
					
				$result = array('status'=>'success','flag'=>'1','message'=>'Users Records Fetched','data'=>$Records);	
			} elseif ($TechsArray === FALSE) {					
				$result = array('status'=>'error','flag'=>'2','message'=>'No entry found.');	
			}	

		

		} else if(!empty($data['Action']) && $data['Action'] == 'SingleUser'){				
				
				if( !empty($data['Val_User']) && $data['Val_User'] != '') {
					
					$UserData = $this->Users_model->get($data['Val_User']);
					
					if ($UserData) {
						
						$UserJobs = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.UserID'=>$data['Val_User']),false);
						
						if(!empty($UserJobs))
							$TotalJobs = (string)count($UserJobs);
						else
							$TotalJobs = (string)0;	
						
						$UserFullName = $UserData->U_FirstName." ".$UserData->U_LastName;
						$UserProfileImage = (!empty($UserData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : NULL);
						$Records[] = array(  
							'UserID' => $UserData->UserID,
							'FullName' => $UserFullName,
							'FirstName'=> $UserData->U_FirstName,
							'LastName'=> $UserData->U_LastName,
							'Mobile'=> $UserData->U_Mobile,
							'Email'=> $UserData->U_Email,
							'Address'=> $UserData->U_Address,
							'ProfileImage'=> $UserProfileImage,
							'TotalJobs' => $TotalJobs
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'User Record Fetched','data'=>$Records);	
					} elseif ($UserArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'User Record Not Fetched','data'=>$data['Val_User']);	
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
	
	// Edit Profile Of User ANd Service Provider
	public function Profile()
    {		     
		$data = $this->input->post();	
	
		if( !empty($data) && $data['Action'] == 'Update' ) {	
													
					$success = $this->Users_model->update($data,$data['Val_User']);	
						
					$Upload = handle_user_profile_image($data['Val_User']);
					
					$UserData = $this->Users_model->get($data['Val_User']);
					
					if ($UserData) {
							$data['Val_Relation'] = $data['Val_User'];
							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');	
							
							$UserFullName = $UserData->U_FirstName." ".$UserData->U_LastName;
							$UserProfileImage = (!empty($UserData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : NULL);
							
							$Record = array(  
										'UserID' => $UserData->UserID,
										'FullName' => $UserFullName,
										'FirstName'=> $UserData->U_FirstName,
										'LastName'=> $UserData->U_LastName,
										'Mobile'=> $UserData->U_Mobile,
										'Email'=> $UserData->U_Email,
										'Address'=> $UserData->U_Address,
										'ProfileImage'=> $UserProfileImage,						
									);		
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'User Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'User Profile Not Updated','data'=>$data['Val_User']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'AddAddress' ) {	
													
					$success = $this->Users_model->update($data,$data['Val_User']);	
						
					$UserData = $this->Users_model->get($data['Val_User']);
					
					if ($UserData) {
							$data['Val_Relation'] = $data['Val_User'];

							$UserFullName = $UserData->U_FirstName." ".$UserData->U_LastName;
							$UserProfileImage = (!empty($UserData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : NULL);
							$Record = array(  
										'UserID' => $UserData->UserID,
										'FullName' => $UserFullName,
										'FirstName'=> $UserData->U_FirstName,
										'LastName'=> $UserData->U_LastName,
										'Mobile'=> $UserData->U_Mobile,
										'Email'=> $UserData->U_Email,
										'Address'=> $UserData->U_Address,
										'Latitude'=> $UserData->U_Latitude,
										'Longitude'=> $UserData->U_Longitude,
										'ProfileImage'=> $UserProfileImage,						
									);		
					}
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'User Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'User Profile Not Updated','data'=>$data['Val_User']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
					$success = $this->Users_model->delete($data['Val_User']);	
					
					if ($success) {
							$Adata['Val_Relation'] = $data['Val_User'];
							$Adata['Val_Status'] = '1';
							$Asuccess = $this->Authentication_model->AppUpdate($Adata,$Adata['Val_Relation'],'1');	
							
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'User Profile Deleted Successfully','data'=>'Confidential');	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'User Profile Not Deleted','data'=>$data['Val_User']);	
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
	
	// Edit Profile Of User ANd Service Provider
	public function Dashboard()
	{

		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetData'){				
			
				$UserData = $this->Users_model->get($data['Val_Userid']);						
				if ($UserData) {	
					
					$UserRecord = array( 
						'status'=>'success',
						'message'=>'User Record Fetched',
						'UserID' => $UserData->UserID,
						'UserJID'=>$UserData->U_JID,
						'FullName'=> $UserData->U_FirstName." ".$UserData->U_LastName,
						'FirstName'=> $UserData->U_FirstName,
						'LastName'=> $UserData->U_LastName,
						'CountryCode'=> $UserData->U_CountryCode,
						'Mobile'=> $UserData->U_Mobile,
						'Email'=> $UserData->U_Email,
						'Country'=> $UserData->U_Country,
						'State'=> $UserData->U_State,
						'City'=> $UserData->U_City,
						'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,
						'Privacy'=> $UserData->U_Privacy
						);	

				} elseif ($UserData == false) {					
					$UserRecord = array('status'=>'error','flag'=>'2','message'=>'User Record Not Fetched.');	
				}
				
				
//				$NotificationArray = $this->Users_model->getUser();						
				$UnpublishedEventsArray = $this->Events_model->getByUser($data['Val_Userid'],array('E_Status'=>'1'));									
				$NotificationArray = $this->Notifications_model->getByUser($data['Val_Userid'],array('N_IsRead'=>'2'));									
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
				
				
				
				$Records['UserData'] = $UserRecord;
				$Records['EventsData'] = (!empty($EventRecords) ? $EventRecords : (object)$EventRecords) ;
				$Records['MiscData'] = $MiscRecords;
				
				$result = array('status'=>'success','flag'=>'1','message'=>'User Dashboard Data Fetched','data'=>$Records);	
				
		} else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	
		
		
		return false;
	}
		
	// Edit Profile Of User ANd Service Provider
	public function Contacts()
    {		     
		$this->load->model('Users_model');
		$data = $this->input->post();	
//		print_r($data);
	

		
		$Array = $data['Val_Contacts'];
		$Object = json_decode($data['Val_Contacts']);
			
		
		if( !empty($data) && $data['Action'] == 'Check' ){	
			
			if(!empty($Array))
			{
				$UserArray = $this->Users_model->getMobiles();
				
				$TotalExist = count($UserArray);	

				$k = 0;

				$TmpMatchAtrray = array();

				foreach($Object as $Record)
					{
						$CleanMobile = cleanMobile($Record->Mobile);	
						
						for($i = 0 ; $i < $TotalExist; $i++){ 	 										
							
							$FullUserMobile = $UserArray[$i]['U_CountryCode'].$UserArray[$i]['U_Mobile'];
							
							$ZeroUserMobile = '0'.$UserArray[$i]['U_Mobile'];
							
							if($CleanMobile == $UserArray[$i]['U_Mobile'] || $CleanMobile == $FullUserMobile  || $CleanMobile == $ZeroUserMobile ){

								$TmpMatchAtrray['FullName'][$k] = $Record->FullName; 

								$TmpMatchAtrray['Mobile'][$k] = $Record->Mobile;

								$TmpMatchAtrray['Userid'][$k] = $UserArray[$i]['UserID']; 

								$TmpMatchAtrray['Userimages'][$k] = $UserArray[$i]['U_ProfileImage']; 
	
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
									'UserID'=> $TmpMatchAtrray['Userid'][$Key],
									'ProfileImage'=> UPLOAD_USER_BASE_URL.$TmpMatchAtrray['Userid'][$Key].'/'.$TmpMatchAtrray['Userimages'][$Key],
								);

							} else {
	
								$NewResultArray[] = array(
									'FullName'=> $Record->FullName,
									'Mobile' => $Record->Mobile,
									'Heyoo' => '1',
									'UserID'=> NULL,
									'ProfileImage'=> NULL
								);

							}										

						} else {

							$NewResultArray[] = array(

								'FullName'=> $Record->FullName,

								'Mobile' => $Record->Mobile,

								'Heyoo' => '1',

								'UserID'=> NULL,
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