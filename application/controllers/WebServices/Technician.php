<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Technician extends SS_Controller
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
		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetAllTechnicians'){				

			if(!empty($data['Val_Service']))
				$TechsArray = $this->Technicians_model->get(NULL,array('ServiceID'=>$data['Val_Service']));
			else 	
				$TechsArray = $this->Technicians_model->get();
					
			if ($TechsArray) {

				foreach($TechsArray as $TechArray) {
						
						$TechFullName = $TechArray['T_FirstName']." ".$TechArray['T_LastName'];
						$ProfileImage = '';	
						$ProfileImage = (!empty($TechArray['T_ProfileImage']) ? UPLOAD_TECHNICIAN_BASE_URL.$TechArray['TechnicianID'].'/'.$TechArray['T_ProfileImage'] : '');	
						$TechReviews = $this->Reviews_model->get(NULL,array('TechnicianID'=>$TechArray['TechnicianID']));
						
						$TechJobs = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$TechArray['TechnicianID']));

						if(!empty($TechReviews)) {
							$Ratings = 0;
							foreach($TechReviews as $TechReview)
								{
									$Ratings += $TechReview['R_Rating'];
								}

							$ReviewsCount = (string)count($TechReviews);
							$AverageRatings = (string)($Ratings/$ReviewsCount);
						} else {
							$ReviewsCount = (string)0;
							$AverageRatings = (string)0;
						}
						
						if(!empty($TechJobs)) {
							$itr = 1;
							foreach($TechJobs as $TechJob)
								{
								
									if($TechJob['J_Type'] == '1')
										$JobType = 'Installation';
									else		
										$JobType = 'Uninstallation';
										
									$JobTitle = $TechJob['S_Name']." ".$JobType;	
									$JobRecords[] = array( 
										'SrNo' => (string)$itr,
										'Title' => $JobTitle,
										'Ratings' => $TechJob['R_Rating'],
									);
									$itr++;
								}

						} else {
							$JobRecords = array();
						}
						
						$CompletedJobs		 = $this->Jobs_model->get(NULL,array('TechnicianID'=>$TechArray['TechnicianID'],'J_Status'=>'5'));
						if(!empty($CompletedJobs))
							$CompletedJobs = (string)count($CompletedJobs);
						else	
							$CompletedJobs = (string)0;

						$AcceptedJobs		 = $this->Jobs_model->get(NULL,array('TechnicianID'=>$TechArray['TechnicianID'],'J_Status'=>'3'));
						
						if(!empty($AcceptedJobs))
							$AcceptedJobs = (string)count($AcceptedJobs);
						else	
							$AcceptedJobs = (string)0;
						
						$Records[] = array(  
							'TechnicianID' => $TechArray['TechnicianID'],
							'FullName' => $TechFullName,
							'FirstName' => $TechArray['T_FirstName'],
							'LastName' => $TechArray['T_LastName'],
							'CountryCode' => $TechArray['T_CountryCode'],
							'MobileNumber' => $TechArray['T_Mobile'],
							'EmailAddress' => $TechArray['T_Email'],
							'AddressLine1' => $TechArray['T_Address1'],
							'AddressLine2' => $TechArray['T_Address2'],
							'City' => $TechArray['T_City'],
							'ProfileImage' => $ProfileImage,
							'Notification' => $TechArray['T_Notification'],
							//'Notification' => $TechArray['T_Notification'],
							'Skill' => $TechArray['T_Skill'],
							'TotalReviews' => $ReviewsCount ,
							'AverageRating' => $AverageRatings,
							'Wallet'=> $TechArray['T_Wallet'],
							'CompletedJobs'=> $CompletedJobs,
							'AcceptedJobs'=> $AcceptedJobs,
							'Status' => $TechArray['T_Status'],
							'JobHistory' => $JobRecords
							);	

				
				}						
						
					
				$result = array('status'=>'success','flag'=>'1','message'=>'Technicians Records Fetched','data'=>$Records);	
			} elseif ($TechsArray === FALSE) {					
				$result = array('status'=>'error','flag'=>'2','message'=>'No entry found.');	
			}	

		} else if(!empty($data['Action']) && $data['Action'] == 'SingleTechnician'){				
				
				if( !empty($data['Val_Technician']) && $data['Val_Technician'] != '') {
					
					$TechData = $this->Technicians_model->get($data['Val_Technician']);
					
					if ($TechData) {
						
						$TechFullName = $TechData->T_FirstName." ".$TechData->T_LastName;
						$ProfileImage = '';	
						$ProfileImage = (!empty($TechData->T_ProfileImage) ? UPLOAD_TECHNICIAN_BASE_URL.$TechData->TechnicianID.'/'.$TechData->T_ProfileImage : '');	

						$CompletedJobs		 = $this->Jobs_model->get(NULL,array('TechnicianID'=>$data['Val_Technician'],'J_Status'=>'5'));
						
						if(!empty($CompletedJobs))
							$CompletedJobs = count($CompletedJobs);
						else	
							$CompletedJobs = 0;

						$AcceptedJobs		 = $this->Jobs_model->get(NULL,array('TechnicianID'=>$data['Val_Technician'],'J_Status'=>'3'));
						
						if(!empty($AcceptedJobs))
							$AcceptedJobs = count($AcceptedJobs);
						else	
							$AcceptedJobs = 0;
						
						$TechJobs = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$data['Val_Technician']),false);
						
						$TechReviews = $this->Reviews_model->get(NULL,array('TechnicianID'=>$data['Val_Technician']));
						
						if(!empty($TechReviews)) {
							$Ratings = 0;
							foreach($TechReviews as $TechReview)
								{
									$Ratings += $TechReview['R_Rating'];
								}

							$ReviewsCount = (string)count($TechReviews);
							$AverageRatings = (string)($Ratings/$ReviewsCount);
						} else {
							$ReviewsCount = (string)0;
							$AverageRatings = (string)0;
						}
						
					
						if(!empty($TechJobs)) {
							$itr = 1;
							foreach($TechJobs as $TechJob)
								{
								
									if($TechJob['J_Type'] == '1')
										$JobType = 'Installation';
									else		
										$JobType = 'Uninstallation';
										
									$JobTitle = $TechJob['S_Name']." ".$JobType;	
									$UserName = $TechJob['U_FirstName']." ".$TechJob['U_LastName'];
									
									$JobReview = $this->Reviews_model->getByJob($TechJob['JobID']);

									if(!empty($JobReview)) {
									
										$JobComment = (string)$JobReview->R_Comment;
										$JobRating  = (string)$JobReview->R_Rating;
									} else {
										$JobComment = "";
										$JobRating = "";
									}

									
									$JobRecords[] = array( 
										'SrNo' => $itr,
										'JobID' => $TechJob['JobID'],
										'Title' => $JobTitle,
										'UserName' => $UserName,
										'Address' => $TechJob['U_Address'],
										'Amount' => $TechJob['J_Amount'],
										'Ratings' => $JobRating,
									);
									$itr++;
								}

						} else {
							$JobRecords = array();
						}

						
						
						$Records[] = array(  
							'TechnicianID' => $TechData->TechnicianID,
							'FullName' => $TechFullName,
							'FirstName'=> $TechData->T_FirstName,
							'LastName'=> $TechData->T_LastName,
							'MobileNumber'=> $TechData->T_Mobile,
							'EmailAddress'=> $TechData->T_Email,
							'ProfileImage' => $ProfileImage,
							'AddressLine1'=> $TechData->T_Address1,
							'AddressLine2'=> $TechData->T_Address2,
							'City'=> $TechData->T_City,
							'Notification'=> $TechData->T_Notification,
							'Availability'=> $TechData->T_Availability,
							'AvailabilityFrom'=> $TechData->T_AvailabilityFrom,
							'AvailabilityTo'=> $TechData->T_AvailabilityTo,
							'Skill'=> $TechData->T_Skill,
							'Wallet'=> $TechData->T_Wallet,
							'TotalReviews' => $ReviewsCount,
							'AverageRating' => $AverageRatings,
							'CompletedJobs'=> $CompletedJobs,
							'AcceptedJobs'=> $AcceptedJobs,
							'JobHistory' => $JobRecords,
							'Status' => $TechData->T_Status,
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Technician\'s Record Fetched','data'=>$Records);	
					} elseif ($UserArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'User Record Not Fetched','data'=>$data['Val_Userid']);	
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
	
		if( !empty($data) && $data['Action'] == 'Update' ){	
													
					$success = $this->Technicians_model->update($data,$data['Val_Technician']);	
						
					$Upload = handle_technician_profile_image($data['Val_Technician']);
					
					$TechData = $this->Technicians_model->get($data['Val_Technician']);
					
					if ($TechData) {
					
						$data['Val_Relation'] = $data['Val_Technician'];
						$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'2');	
						
					
						$TechFullName = $TechData->T_FirstName." ".$TechData->T_LastName;
						$ProfileImage = '';	
						$ProfileImage = (!empty($TechData->T_ProfileImage) ? UPLOAD_TECHNICIAN_BASE_URL.$TechData->TechnicianID.'/'.$TechData->T_ProfileImage : '');	
						
						$Record = array(  
								'TechnicianID' => $TechData->TechnicianID,
								'FullName' => $TechFullName,
								'FirstName'=> $TechData->T_FirstName,
								'LastName'=> $TechData->T_LastName,
								'MobileNumber'=> $TechData->T_Mobile,
								'EmailAddress'=> $TechData->T_Email,
								'ProfileImage' => $ProfileImage,
								'AddressLine1'=> $TechData->T_Address1,
								'AddressLine2'=> $TechData->T_Address2,
								'City'=> $TechData->T_City,
								'Notification'=> $TechData->T_Notification,
								'Availability'=> $TechData->T_Availability,
								'AvailabilityFrom'=> $TechData->T_AvailabilityFrom,
								'AvailabilityTo'=> $TechData->T_AvailabilityTo,
								'Skill'=> $TechData->T_Skill,
								'Wallet'=> $TechData->T_Wallet,
								'Status' => $TechData->T_Status,				
								
								);		
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Technician Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Technician Profile Not Updated','data'=>$data['Val_Userid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateAvailability' ){	
													
			$success = $this->Technicians_model->updateAvailability($data,$data['Val_Technician']);	
				
			if ($success) {
				$result = array('status'=>'success','flag'=>'1','message'=>'Technician Availability Updated Successfully','data'=>$data);	
			} else if ($success == false) {
				$result = array('status'=>'error','flag'=>'2','message'=>'Technician Availability Not Updated','data'=>$data	);	
			} else {
				$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
			}
			
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateGoogle' ){	
													
			$success = $this->Users_model->updateGoogle($data,$data['Val_Userid']);	
				
			if ($success) {
				$result = array('status'=>'success','flag'=>'1','message'=>'Google account updated successfully.','data'=>$data['Val_Userid']);	
			} else if ($success == false) {
				$result = array('status'=>'error','flag'=>'2','message'=>'Google account not updated.','data'=>$data['Val_Userid']);	
			} else {
				$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
			}
			
		} else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
					$success = $this->Technicians_model->delete($data['Val_Technician']);	
					
					if ($success) {
							$Adata['Val_Relation'] = $data['Val_Technician'];
							$Adata['Val_Status'] = '1';
							$Asuccess = $this->Authentication_model->AppUpdate($Adata,$Adata['Val_Relation'],'2');	
							
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Technician Profile Deleted Successfully','data'=>'Confidential');	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Technician Profile Not Deleted','data'=>$data['Val_Technician']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} else {
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
						'MobileNumber'=> $UserData->U_Mobile,
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