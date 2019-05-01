<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Advertise extends SS_Controller
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
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllAdvertises'){				
			
				$AdvertisesArray = $this->Advertises_model->get();	

				if ($AdvertisesArray) {	
					
					foreach($AdvertisesArray as $AdvertiseArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($AdvertiseArray['A_DisplayImage']) ? UPLOAD_ADVERTISE_BASE_URL.$AdvertiseArray['AdvertiseID'].'/'.$AdvertiseArray['A_DisplayImage'] : '');	
						$Records[] = array(  
							'AdvertiseID' => $AdvertiseArray['AdvertiseID'],
							'Name' => $AdvertiseArray['A_Name'],
							'DisplayImage' => $DisplayImage,
							'Status'=> $AdvertiseArray['A_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Advertise Records Fetched','data'=>$Records);	
				} elseif ($AdvertiseArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'SingleAdvertise'){				
				
				if( !empty($data['Val_Advertise'])) {
					
					$AdvertiseData = $this->Advertises_model->get($data['Val_Advertiseid'],array(),true);
					$AdvertiseShares = $this->Advertises_model->getAdvertiseShares($data['Val_Advertiseid']);
					
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($AdvertiseData->UserID);
					
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
					
								
					if(!empty($AdvertiseShares))
						{
							foreach($AdvertiseShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName ,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['A_Admin'],
													'Status'=>$Users['A_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['A_Admin'] === '2')
									{
										$SAdmin = $Users['A_Admin'];
									}
									
											
							}
							
						$AdvertiseCount = count($AdvertiseShares);		
						}
					else{
								$AdvertiseCount = 0 ;
							}	
						

					
					
					if ($AdvertiseData) {
					
						$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','AdvertiseID'=>$data['Val_Advertiseid']))) ;

						$AdvertiseName = ($AdvertiseData->UserID == $data['Val_Userid']) ? $AdvertiseData->C_Name : $AdvertiseData->C_Name. " (Shared)" ;				
						
						$Admin = ($AdvertiseData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
						
						$Records[] = array(  
							'AdvertiseID' => $AdvertiseData->AdvertiseID,
							'UserID' => $AdvertiseData->UserID,
							'Name'=> $AdvertiseName,
							'OriginalName'=> $AdvertiseData->C_Name,							
							'Color'=> $AdvertiseData->C_Color,
							'Shared'=> $AdvertiseData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$AdvertiseCount,
							'MemberCount'=> (string)($AdvertiseCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($AdvertiseData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$AdvertiseData->UserID.'/'.$AdvertiseData->U_ProfileImage : ''),
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Advertise Record Fetched','data'=>$Records);	
					} elseif ($AdvertiseArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'Advertise Record Not Fetched','data'=>$data['Val_Advertiseid']);	
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
	// Edit Details Of Advertise 
	public function Details()
    {		     
	
		$data = $this->input->post();	
	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$success = $this->Advertises_model->add($data);	
						
					if ($success) {
						
						$result = array('status'=>'success','flag'=>'1','message'=>'Advertise Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Advertise Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Advertiseid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Advertises_model->update($data,$data['Val_Advertiseid']);	
					
					$AdvertiseData = $this->Advertises_model->get($data['Val_Advertiseid'],array(),true);
					$AdvertiseShares = $this->Advertises_model->getAdvertiseShares($data['Val_Advertiseid']);
					
			
					/*if(!empty($AdvertiseShares))
						{
							foreach($AdvertiseShares as $Users)
							{
								array_push($Shares,array('UserID'=>$Users['UserID'],'ProfileImage'=>UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage']));
							}
						}

					$AdvertiseCount = count($AdvertiseShares);	

					$Records[] = array(  
						'AdvertiseID' => $AdvertiseArray->AdvertiseID,
						'UserID' => $AdvertiseArray->UserID,
						'Name'=> $AdvertiseArray->C_Name ,
						'Color'=> $AdvertiseArray->C_Color,
						'Shared'=> $AdvertiseArray->C_Shared,
						'SharedCount'=> (string)$AdvertiseCount,
						'SharedUsers'=> $Shares,
						);
						
						*/
					
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($AdvertiseData->UserID);
					
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
					
								
					if(!empty($AdvertiseShares))
						{
							foreach($AdvertiseShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['A_Admin'],
													'Status'=>$Users['A_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['A_Admin'] === '2')
									{
										$SAdmin = $Users['A_Admin'];
									}
									
											
							}
						$AdvertiseCount = count($AdvertiseShares);		
						}
					else{
						$AdvertiseCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','AdvertiseID'=>$data['Val_Advertiseid']))) ;						
						
					$AdvertiseName = ($AdvertiseData->UserID == $data['Val_Userid']) ? $AdvertiseData->C_Name : $AdvertiseData->C_Name. " (Shared)" ;				

					$Admin = ($AdvertiseData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$AdvertiseCount = count($AdvertiseShares);
						
					$Records[] = array(  
							'AdvertiseID' => $AdvertiseData->AdvertiseID,
							'UserID' => $AdvertiseData->UserID,
							'Name'=> $AdvertiseName,
							'OriginalName'=> $AdvertiseData->C_Name,							
							'Color'=> $AdvertiseData->C_Color,
							'Shared'=> $AdvertiseData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$AdvertiseCount,
							'MemberCount'=> (string)($AdvertiseCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($AdvertiseData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$AdvertiseData->UserID.'/'.$AdvertiseData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Advertise Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Advertise Information Not Updated','data'=>$data['Val_Advertiseid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
				if (!$data['Val_Advertiseid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$AdvertiseShares = $this->Advertises_model->getAdvertiseShares($data['Val_Advertiseid']);
					
					$AdvertiseData = $this->Advertises_model->get($data['Val_Advertiseid']);
				
					$success = $this->Advertises_model->delete($data['Val_Advertiseid']);	
					if ($success) {
					
						if(!empty($AdvertiseShares))
							{
								foreach($AdvertiseShares as $SharedUser)
									{
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Advertiseid']));
										
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
					
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Advertise Deleted Successfully','data'=>$data['Val_Advertiseid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Advertise was not deleted.','data'=>$data['Val_Advertiseid']);	
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