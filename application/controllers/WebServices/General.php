<?php
defined('BASEPATH') or exit('No direct script access allowed');
class General extends W_Controller
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
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllReviews'){				
			
				if( !empty($data['Val_Type']) && !empty($data['Val_Relation']) )	
					{
						$Records = array();
						$ServiceReviewsArray =	$this->Services_model->getReviews(NULL,array('R_Type'=>$data['Val_Type'],'R_RelationID'=>$data['Val_Relation']));	
						$ReviewData['ReviewsCount'] 	= (string)count($Records); 
						$ReviewData['ReviewsData'] 	= $Records;
						$Index = 1;
						if (!empty($ServiceReviewsArray)) {	
							foreach($ServiceReviewsArray as $Review) {

									$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
									$Records[] = array(  
										'ReviewID'=>getStringValue($Review['ReviewID']),
										'Index'=>getStringValue($Index),
										'Username'=>getStringValue($Review['R_UserName']),
										'Color'=>getRandomColor(),
										'Comment'=>getStringValue($Review['R_Comment']),
										'Location'=>getStringValue($Review['R_Location']),
										'Rating'=>getStringValue($Review['R_Rating']),
										'Date'=>getStringValue($FormattedDate),
									);	
									$Index++;
								}
							$ReviewData['ReviewsCount'] 	= (string)count($Records); 
							$ReviewData['ReviewsData'] 	= $Records;
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Reviews Records Fetched','data'=>$ReviewData);	
						} elseif ($ServiceReviewsArray == false) {					
							$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$ReviewData);	
						}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				
				
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetSingleService'){				
				
				if( !empty($data['Val_Service'])) {

					$FeaturesRecords = array();
					$StepsRecords = array();
					$WorksRecords = array();
					$ReviewsRecords = array();
					$FeaturesCount = "0" ;
					$StepsCount = "0" ;
					$WorksCount = "0" ;
					$ReviewsCount = "0" ;
					
/*					
					
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($ServiceData->UserID);
					
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
					
*/

					$ServiceData = $this->Services_model->get($data['Val_Service'],array());
					$ServiceFeatures =	$this->Services_model->getFeatures(NULL,array('F_ServiceID'=>$data['Val_Service']),'ASC');	
					if(!empty($ServiceFeatures))
						{
							$Index = 1;
							foreach($ServiceFeatures as $Feature)
							{
								array_push($FeaturesRecords,array(
													'FeatureID'=>getStringValue($Feature['SFeatureID']),
													'Index'=>getStringValue($Index),
													'Title'=>getStringValue($Feature['F_Title']),
													'Description'=>getStringValue($Feature['F_Description']),
													)
												);
								$Index++;
											
							}
							
							$FeaturesCount = (string)count($FeaturesRecords);		
						}
					else{
								$FeaturesCount = "0" ;
							}	

					$ServiceSteps =	$this->Services_model->getSteps(NULL,array('ST_ServiceID'=>$data['Val_Service']),'ASC');	
					if(!empty($ServiceSteps))
						{
							$Index = 1;
							foreach($ServiceSteps as $Step)
							{
								array_push($StepsRecords,array(
													'StepID'=>getStringValue($Step['SStepID']),
													'Index'=>getStringValue($Index),
													'Title'=>getStringValue($Step['ST_Title']),
													'Description'=>getStringValue($Step['ST_Description']),
													)
												);
								
											
								$Index++;
							}
							
							$StepsCount = (string)count($StepsRecords);		
						}
					else{
								$StepsCount = "0" ;
							}	

						

					$ServiceWorks =	$this->Services_model->getWorks(NULL,array('W_ServiceID'=>$data['Val_Service']),'ASC');	
					if(!empty($ServiceWorks))
						{
							$Index = 1;
							foreach($ServiceWorks as $Work)
							{
								$DisplayImage = (!empty($Work['W_DisplayImage']) ? UPLOAD_SERVICES_BASE_URL.$Work['W_ServiceID'].'/'.UPLOAD_WORKS_URL.$Work['SWorkID'].'/'.$Work['W_DisplayImage'] : ''); 
								array_push($WorksRecords,array(
													'WorkID'=>getStringValue($Work['SWorkID']),
													'Index'=>getStringValue($Index),
													'Title'=>getStringValue($Work['W_Title']),
													'DisplayImage'=>getStringValue($DisplayImage),
													)
												);
								
											
								$Index++;
							}
							
							$WorksCount = (string)count($WorksRecords);		
						}
					else{
								$WorksCount = "0" ;
							}	


					$ServiceReviews =	$this->Services_model->getReviews(NULL,array('R_ServiceID'=>$data['Val_Service']),'ASC');	
					if(!empty($ServiceReviews))
						{
							$Index = 1;
							foreach($ServiceReviews as $Review)
							{
								array_push($ReviewsRecords,array(
													'ReviewID'=>getStringValue($Review['SReviewID']),
													'Index'=>getStringValue($Index),
													'Username'=>getStringValue($Review['R_UserName']),
													'Color'=>getRandomColor(),
													'Comment'=>getStringValue($Review['R_Comment']),
													'Location'=>getStringValue($Review['R_Location']),
													'Rating'=>getStringValue($Review['R_Rating']),
													'Date'=>getStringValue($Review['R_Date']),
													)
												);
								
											
								$Index++;
							}
							
							$ReviewsCount = (string)count($ReviewsRecords);		
						}
					else{
								$ReviewsCount = "0" ;
							}	
					
					if ($ServiceData) {
					
//						$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','ServiceID'=>$data['Val_Serviceid']))) ;

//						$ServiceName = ($ServiceData->UserID == $data['Val_Userid']) ? $ServiceData->C_Name : $ServiceData->C_Name. " (Shared)" ;				
						
//						$Admin = ($ServiceData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
						$DisplayImage = (!empty($ServiceData->S_DisplayImage) ? UPLOAD_SERVICES_BASE_URL.$ServiceData->ServiceID.'/'.$ServiceData->S_DisplayImage : ''); 
						$Records = array(  
							'ServiceID' => $ServiceData->ServiceID,
//							'UserID' => $ServiceData->UserID,
							'Name'=> getStringValue($ServiceData->S_Name),
							'Description'=> getStringValue($ServiceData->S_Description),
							'DisplayImage'=> $DisplayImage,
//							'OriginalName'=> $ServiceData->C_Name,							
//							'Color'=> $ServiceData->C_Color,
//							'Shared'=> $ServiceData->C_Shared,
//							'Admin'=> $Admin,
							'FeaturesCount'=> $FeaturesCount,
							'FeaturesData'=> $FeaturesRecords,
							'StepsCount'=> $StepsCount,
							'StepsData'=> $StepsRecords,
							'WorksCount'=> $WorksCount,
							'WorksData'=> $WorksRecords,
							'ReviewsCount'=> $ReviewsCount,
							'ReviewsData'=> $ReviewsRecords,
//							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Record Fetched','data'=>$Records);	
					} elseif ($ServiceArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'Service Record Not Fetched','data'=>$data['Val_Serviceid']);	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} 
		else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	

	
	// Edit Details Of Service 
	public function Details()
    {		     
	
		$data = $this->input->post();	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$data['Val_Type'] = '2';
					$data['Val_Status'] = '2';
					$success = $this->Services_model->add($data);	
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
			
		} 
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Serviceid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Services_model->update($data,$data['Val_Serviceid']);	
					
					$ServiceData = $this->Services_model->get($data['Val_Serviceid'],array(),true);
					$ServiceShares = $this->Services_model->getServiceShares($data['Val_Serviceid']);
				
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($ServiceData->UserID);
					
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
					
								
					if(!empty($ServiceShares))
						{
							foreach($ServiceShares as $Users)
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
						$ServiceCount = count($ServiceShares);		
						}
					else{
						$ServiceCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','ServiceID'=>$data['Val_Serviceid']))) ;						
						
					$ServiceName = ($ServiceData->UserID == $data['Val_Userid']) ? $ServiceData->C_Name : $ServiceData->C_Name. " (Shared)" ;				

					$Admin = ($ServiceData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$ServiceCount = count($ServiceShares);
						
					$Records[] = array(  
							'ServiceID' => $ServiceData->ServiceID,
							'UserID' => $ServiceData->UserID,
							'Name'=> $ServiceName,
							'OriginalName'=> $ServiceData->C_Name,							
							'Color'=> $ServiceData->C_Color,
							'Shared'=> $ServiceData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$ServiceCount,
							'MemberCount'=> (string)($ServiceCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($ServiceData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$ServiceData->UserID.'/'.$ServiceData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service Information Not Updated','data'=>$data['Val_Serviceid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
											
				if (!$data['Val_Serviceid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$ServiceData = $this->Services_model->get($data['Val_Serviceid']);
				
					$success = $this->Services_model->delete($data['Val_Serviceid']);	
//					$success = true;
					if ($success) {
					
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Serviceid']));
										
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
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Service Deleted Successfully','data'=>$data['Val_Serviceid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Service was not deleted.','data'=>$data['Val_Serviceid']);	
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