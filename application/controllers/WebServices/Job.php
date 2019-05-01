<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Job extends SS_Controller
{
    public function __construct()
    {
        parent::__construct();
		header('Content-Type: application/json');
      
    }

    public function index()
    {
		echo "Access Denied";	
    }
	
	public function Fetch()
    {		     
		$data = $this->input->post();	
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllJobs'){				
			
				if(!empty($data['Val_Status']))
					$JobsArray = $this->Jobs_model->getJoined(NULL,array('J_Status'=>$data['Val_Status']),false);	
				else	
					$JobsArray = $this->Jobs_model->getJoined(NULL,array(),false);	
			

				if ($JobsArray) {	
					
					foreach($JobsArray as $JobArray) {

						
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';

						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$UserName = $JobArray['U_FirstName']." ".$JobArray['U_LastName'];
						
						$ProfileImage = '';	
						$ProfileImage = (!empty($JobArray['U_ProfileImage']) ? UPLOAD_USER_BASE_URL.$JobArray['UserID'].'/'.$JobArray['U_ProfileImage'] : '');	
						
						$TechName = $JobArray['T_FirstName']." ".$JobArray['T_LastName'];
						
						
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Service' => $JobArray['ServiceID'],
							'ServiceTitle' => $JobArray['S_Name'],
							'ServiceCategory' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'UserName' => $UserName,
							'Address' => $JobArray['U_Address'],
							'Latitude' => $JobArray['U_Latitude'],
							'Longitude' => $JobArray['U_Longitude'],
							'ProfileImage' => $ProfileImage,
							'TechnicianName' => $TechName,
							'TechnicianFirstName' => $JobArray['T_FirstName'],
							'TechnicianLastName' => $JobArray['T_LastName'],
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetAdminLeads'){				
				$JobsArray = $this->Jobs_model->getJoined(NULL,array(),false,'J_Status',array('1','2','3'));	

				if ($JobsArray) {	
					
					foreach($JobsArray as $JobArray) {

						
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';

						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$UserName = $JobArray['U_FirstName']." ".$JobArray['U_LastName'];
						
						$ProfileImage = '';	
						$ProfileImage = (!empty($JobArray['U_ProfileImage']) ? UPLOAD_USER_BASE_URL.$JobArray['UserID'].'/'.$JobArray['U_ProfileImage'] : '');	
						
						$TechName = $JobArray['T_FirstName']." ".$JobArray['T_LastName'];
						
						
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Service' => $JobArray['ServiceID'],
							'ServiceTitle' => $JobArray['S_Name'],
							'ServiceCategoryID' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'UserName' => $UserName,
							'Address' => $JobArray['U_Address'],
							'Latitude' => $JobArray['U_Latitude'],
							'Longitude' => $JobArray['U_Longitude'],
							'ProfileImage' => $ProfileImage,
							'TechnicianName' => $TechName,
							'TechnicianFirstName' => $JobArray['T_FirstName'],
							'TechnicianLastName' => $JobArray['T_LastName'],
							'TechnicianMobileNumber' => $JobArray['T_Mobile'],
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetUserJobs'){				
			
				$JobsArray = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.UserID'=>$data['Val_User']),false);

				if ($JobsArray) {	
					
					foreach($JobsArray as $JobArray) {
					
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';
							
						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$TechName = $JobArray['T_FirstName']." ".$JobArray['T_LastName'];


						$TechReviews = $this->Reviews_model->get(NULL,array('TechnicianID'=>$JobArray['TechnicianID']));
						
						$TechJobs = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$JobArray['TechnicianID']));
						
						if(!empty($TechReviews)) {
							$Ratings = 0;
							foreach($TechReviews as $TechReview)
								{
									$Ratings += $TechReview['R_Rating'];
								}

							$ReviewsCount = (string)count($TechReviews);
							$AverageRatings = (string)($Ratings/$ReviewsCount);
						} else {
							$ReviewsCount = 0;
							$AverageRatings = 0;
						}
						$JobRecords = array();
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

						$JobReview = $this->Reviews_model->getByJob($JobArray['JobID']);
						
						if(!empty($JobReview)) {

							$JobComment = (string)$JobReview->R_Comment;
							$JobRating  = (string)$JobReview->R_Rating;
						} else {
							$JobComment = "";
							$JobRating = "";
						}
						
						$TechProfileImage = '';	
						$TechProfileImage = (!empty($JobArray['T_ProfileImage']) ? UPLOAD_TECHNICIAN_BASE_URL.$JobArray['TechnicianID'].'/'.$JobArray['T_ProfileImage'] : '');	
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'TechnicianName' => $TechName,
							'TechnicianMobileNumber' => $JobArray['T_Mobile'],
							'TechnicianProfileImage' => $TechProfileImage,
							'TechnicianReviews' => $ReviewsCount,
							'TechnicianRating' => $AverageRatings,
							'JobHistory' => $JobRecords,
							'Service' => $JobArray['ServiceID'],
							'ServiceTitle' => $JobArray['S_Name'],
							'ServiceCategory' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Payment' => $JobArray['J_Payment'],
							'Comment' => $JobComment,
							'Rating' => $JobRating,
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobsArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetTechnicianJobs'){				
			
				if(!empty($data['Val_Status']))
				//	$JobsArray = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$data['Val_Technician']));
					$result = array('status'=>'info','flag'=>'4','message'=>'WRONG PARAMETERS REQUESTED BY YOU.','data'=>$data);	
				else
					{
						$data['Val_Status'] = '2';
						$JobsArray = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$data['Val_Technician'],'J_Status'=>$data['Val_Status']),false);
					}	

				if ($JobsArray) {	
					
					
					foreach($JobsArray as $JobArray) {
					
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';
							
						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$UserName = $JobArray['U_FirstName']." ".$JobArray['U_LastName'];

						$DisplayImage = '';	
						$DisplayImage = (!empty($JobArray['U_ProfileImage']) ? UPLOAD_USER_BASE_URL.$JobArray['UserID'].'/'.$JobArray['U_ProfileImage'] : '');	
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'UserName' => $UserName,
							'MobileNumber' => $JobArray['U_Mobile'],
							'DisplayImage' => $DisplayImage,
							'Address' => $JobArray['U_Address'],
							'Latitude' => $JobArray['U_Latitude'],
							'Longitude' => $JobArray['U_Longitude'],
							'ServiceCategory' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobsArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetAcceptedJobs'){				
			
				$data['Val_Status'] = '3';
				$JobsArray = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$data['Val_Technician'],'J_Status'=>$data['Val_Status']),false);
				
				
				if ($JobsArray) {	
					foreach($JobsArray as $JobArray) {
						
						
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';
							
						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$UserName = $JobArray['U_FirstName']." ".$JobArray['U_LastName'];
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($JobArray['U_ProfileImage']) ? UPLOAD_USER_BASE_URL.$JobArray['UserID'].'/'.$JobArray['U_ProfileImage'] : '');	
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'UserName' => $UserName,
							'MobileNumber' => $JobArray['U_Mobile'],
							'DisplayImage' => $DisplayImage,
							'Address' => $JobArray['U_Address'],
							'Latitude' => $JobArray['U_Latitude'],
							'Longitude' => $JobArray['U_Longitude'],
							'ServiceCategory' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobsArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetCompletedJobs'){				
			
				$data['Val_Status'] = '5';
				$JobsArray = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.TechnicianID'=>$data['Val_Technician'],'J_Status'=>$data['Val_Status']),false);
				
				
				if ($JobsArray) {	
					foreach($JobsArray as $JobArray) {
						
						
						if($JobArray['J_Type'] == '1')
							$JobType = 'Installation';
						else		
							$JobType = 'Uninstallation';
							
						$JobTitle = $JobArray['S_Name']." ".$JobType;
						$UserName = $JobArray['U_FirstName']." ".$JobArray['U_LastName'];
						
						
						
						$JobReview = $this->Reviews_model->getByJob($JobArray['JobID']);

						if(!empty($JobReview)) {
						
							$JobComment = (string)$JobReview->R_Comment;
							$JobRating  = (string)$JobReview->R_Rating;
						} else {
							$JobComment = "";
							$JobRating = "";
						}

						
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($JobArray['U_ProfileImage']) ? UPLOAD_USER_BASE_URL.$JobArray['UserID'].'/'.$JobArray['U_ProfileImage'] : '');	
						$Records[] = array(  
							'JobID' => $JobArray['JobID'],
							'TechnicianID' => $JobArray['TechnicianID'],
							'UserID' => $JobArray['UserID'],
							'Title' => $JobTitle,
							'UserName' => $UserName,
							'MobileNumber' => $JobArray['U_Mobile'],
							'DisplayImage' => $DisplayImage,
							'Address' => $JobArray['U_Address'],
							'Latitude' => $JobArray['U_Latitude'],
							'Longitude' => $JobArray['U_Longitude'],
							'ServiceCategory' => $JobArray['S_CategoryID'],
							'ServiceCategoryTitle' => $JobArray['SC_Name'],
							'Type' => $JobArray['J_Type'],
							'TypeTitle' => $JobType,
							'Comment' => $JobComment,
							'Rating' => $JobRating,
							'Status'=> $JobArray['J_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Job Records Fetched','data'=>$Records);	
				} elseif ($JobsArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'SingleJob'){				
			$result = array('status'=>'warning','flag'=>'3','message'=>'Parameters Missing.');		
		} 
		else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	// Edit Details Of Job 
	public function Details()
    {		     
		$data = $this->input->post();	
	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$data['Val_Status'] = '1';
					$success = $this->Jobs_model->add($data);	
						
					if ($success) {
						
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'AddReview' ){	

					$success = $this->Reviews_model->add($data);	
						
					if ($success) {
						
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Review Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job Review Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		
		else if( !empty($data) && $data['Action'] == 'TakeAction' ){	
		
			if( !empty($data['Val_Job']) && !empty($data['Val_Technician'])) {

					$success = $this->Jobs_model->update($data,$data['Val_Job']);	
					
					$JobData = $this->Jobs_model->get($data['Val_Job']);

					$Records[] = array(  
							'JobID' => $JobData->JobID,
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job Information Not Updated','data'=>$data['Val_Jobid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Assign' ){	
		
			if( !empty($data['Val_Job']) && !empty($data['Val_Technician'])) {

					$data['Val_Status'] = '2';
					$success = $this->Jobs_model->update($data,$data['Val_Job']);	
					
					$JobData = $this->Jobs_model->get($data['Val_Job']);

					$Records[] = array(  
							'JobID' => $JobData->JobID,
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Information Assigned Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job Information Not Assigned','data'=>$data['Val_Jobid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Jobid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Jobs_model->update($data,$data['Val_Jobid']);	
					
					$JobData = $this->Jobs_model->get($data['Val_Jobid'],array(),true);
					$JobShares = $this->Jobs_model->getJobShares($data['Val_Jobid']);
					
			
					/*if(!empty($JobShares))
						{
							foreach($JobShares as $Users)
							{
								array_push($Shares,array('UserID'=>$Users['UserID'],'ProfileImage'=>UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage']));
							}
						}

					$JobCount = count($JobShares);	

					$Records[] = array(  
						'JobID' => $JobArray->JobID,
						'UserID' => $JobArray->UserID,
						'Name'=> $JobArray->C_Name ,
						'Color'=> $JobArray->C_Color,
						'Shared'=> $JobArray->C_Shared,
						'SharedCount'=> (string)$JobCount,
						'SharedUsers'=> $Shares,
						);
						
						*/
					
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($JobData->UserID);
					
					$FullName = ($UserData->UserID != $data['Val_Userid']) ? $UserData->U_FirstName." ".$UserData->U_LastName : 'You' ;
					
					array_push($Members,array(
								'UserID'=>$UserData->UserID,
								'UserJID'=>$UserData->U_JID,
								'FullName'=>$FullName. " (Creator)" ,
								'FirstName'=>$UserData->U_FirstName,
								'LastName'=>$UserData->U_LastName,
								'Admin'=>'2',
								'Status'=>'2',
								'ProfileImage'=>(!empty($UserData->U_ProfileImage)) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : '' 
							));
					
								
					if(!empty($JobShares))
						{
							foreach($JobShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['S_Admin'],
													'Status'=>$Users['S_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['S_Admin'] === '2')
									{
										$SAdmin = $Users['S_Admin'];
									}
									
											
							}
						$JobCount = count($JobShares);		
						}
					else{
						$JobCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','JobID'=>$data['Val_Jobid']))) ;						
						
					$JobName = ($JobData->UserID == $data['Val_Userid']) ? $JobData->C_Name : $JobData->C_Name. " (Shared)" ;				

					$Admin = ($JobData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$JobCount = count($JobShares);
						
					$Records[] = array(  
							'JobID' => $JobData->JobID,
							'UserID' => $JobData->UserID,
							'Name'=> $JobName,
							'OriginalName'=> $JobData->C_Name,							
							'Color'=> $JobData->C_Color,
							'Shared'=> $JobData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$JobCount,
							'MemberCount'=> (string)($JobCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($JobData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$JobData->UserID.'/'.$JobData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job Information Not Updated','data'=>$data['Val_Jobid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
		
				if (!$data['Val_Jobid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$JobShares = $this->Jobs_model->getJobShares($data['Val_Jobid']);
					
					$JobData = $this->Jobs_model->get($data['Val_Jobid']);
				
					$success = $this->Jobs_model->delete($data['Val_Jobid']);	
//					$success = true;
					if ($success) {
					
						if(!empty($JobShares))
							{
								foreach($JobShares as $SharedUser)
									{
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Jobid']));
										
										if(!empty($NotificationsData))
										{
											foreach($NotificationsData as $NotificationData)
												{
													$NotifyData['Val_Action'] 		= '4';
													$NotifyData['Val_Responsetype'] = '4';
													$NotifyData['Val_Isread'] 		= '1';
													$this->Notifications_model->update($NotifyData,$NotificationData['NotificationID']);
												}	
										
										}									
									
									}
							}		
					
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Job Deleted Successfully','data'=>$data['Val_Jobid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Job was not deleted.','data'=>$data['Val_Jobid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
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