<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeliveryBoy extends W_Controller
{
    public function __construct()
    {
				parent::__construct();
				$this->load->model('LiveTracking_model');

				header('Content-Type: application/json');
     		$this->Val_Start = 0; 
				$this->Val_Limit = 999999; 
		
    }
    public function index()
    {
		echo "Access Denied";	
    }
	
	public function Fetch()
    {		     
		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'SingleDeliveryBoy'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '') {
					
					$DeliveryBoyData = $this->Deliveryboys_model->get($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$DeliveryBoyJobs = $this->Jobs_model->getJoined(NULL,array(TBL_JOBS.'.DeliveryBoyID'=>$data['Val_Deliveryboy']),false);
						
						if(!empty($DeliveryBoyJobs))
							$TotalJobs = (string)count($DeliveryBoyJobs);
						else
							$TotalJobs = (string)0;	
						
						$DeliveryBoyFullName = $DeliveryBoyData->C_FirstName." ".$DeliveryBoyData->C_LastName;
						$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->C_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->C_ProfileImage : NULL);
						$Records[] = array(  
							'DeliveryBoyID' => $DeliveryBoyData->DeliveryBoyID,
							'FullName' => $DeliveryBoyFullName,
							'FirstName'=> $DeliveryBoyData->C_FirstName,
							'LastName'=> $DeliveryBoyData->C_LastName,
							'Mobile'=> $DeliveryBoyData->C_Mobile,
							'Email'=> $DeliveryBoyData->C_Email,
							'Address'=> $DeliveryBoyData->C_Address,
							'ProfileImage'=> $DeliveryBoyProfileImage,
							'TotalJobs' => $TotalJobs
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Record Fetched','data'=>$Records);	
					} elseif ($DeliveryBoyArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>$data['Val_Deliveryboy']);	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} else if(!empty($data['Action']) && $data['Action'] == 'GetProfile'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '') {
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
						$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : "");
						
									

						$DeliveryBoyRecord = array(  
								'DeliveryBoyID' 			=> getStringValue($DeliveryBoyData->DeliveryBoyID),
								'FullName' 						=> getStringValue($DeliveryBoyFullName),
								'FirstName'						=> getStringValue($DeliveryBoyData->DB_FirstName),
								'LastName'						=> getStringValue($DeliveryBoyData->DB_LastName),
								'CountryCode'					=> getStringValue($DeliveryBoyData->DB_CountryCode),
								'MobileNumber'				=> getStringValue($DeliveryBoyData->DB_Mobile),
								'EmailAddress'				=> getStringValue($DeliveryBoyData->DB_Email),
								'Latitude' 						=> getStringValue($DeliveryBoyData->DB_Latitude),
								'Longitude' 					=> getStringValue($DeliveryBoyData->DB_Longitude),
								'Address'							=> getStringValue($DeliveryBoyData->DB_Address),
								'Location' 						=> getStringValue($DeliveryBoyData->DB_Location),
								'ProfileImage' 				=> $DeliveryBoyProfileImage,
							);		
											
						$MiscRecords = array( 
								'NotificationCount' => "1",
									);	
			
							
						$Records['ProfileData']			= $DeliveryBoyRecord;

						$Records['MiscData'] 			= $MiscRecords;

						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Record Fetched','data'=>$Records);	
					} elseif ($DeliveryBoyData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} else if(!empty($data['Action']) && $data['Action'] == 'GetIdentity'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '') {
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$ProfileData = $this->Deliveryboys_model->getProfile(NULL,array('P_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
						if(!empty($ProfileData)){
							$ProfileData 		= (object)$ProfileData[0];
							$IdentityStatus 	= $ProfileData->P_IDCardStatus;
							$PersonalStatus 	= $ProfileData->P_PersonalStatus;
							$CurrentStatus 		= $ProfileData->P_CurrentStatus;
							$TermsStatus 		= $ProfileData->P_TermsStatus;
							$Record = array(  
									'DeliveryBoyID' 				=> getStringValue($data['Val_Deliveryboy']),
									'ProfileID' 			=> getStringValue($ProfileData->VProfileID),
									'IdentityStatus' 		=> getStringValue($ProfileData->P_IDCardStatus),
									'PersonalDetailsStatus' => getStringValue($ProfileData->P_PersonalStatus),
									'CurrentAddressStatus' 	=> getStringValue($ProfileData->P_CurrentStatus),
									'TermsStatus'			=> getStringValue($ProfileData->P_TermsStatus),
								);	
							$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Record Fetched','data'=>$Record);	

						}else{
							$IdentityStatus 	= '';
							$PersonalStatus 	= '';
							$CurrentStatus 		= '';
							$TermsStatus 		= '';
							$result = array('status'=>'success','flag'=>'1','message'=>'No Record Fetched','data'=>(object)array());	
						}
						
												
					} elseif ($DeliveryBoyData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} else if(!empty($data['Action']) && $data['Action'] == 'GetIdentityDetails'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '' ) {
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$ProfileData = $this->Deliveryboys_model->getProfile(NULL,array('P_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
						if(!empty($ProfileData)){
							$ProfileData 		= (object)$ProfileData[0];
							$IdentityStatus 	= $ProfileData->P_IDCardStatus;
							$PersonalStatus 	= $ProfileData->P_PersonalStatus;
							$CurrentStatus 		= $ProfileData->P_CurrentStatus;
							$TermsStatus 		= $ProfileData->P_TermsStatus;

							$FrontImage = (!empty($ProfileData->P_IDCardFrontImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$data['Val_Deliveryboy'].'/'.$ProfileData->P_IDCardFrontImage : '');	
							$BackImage = (!empty($ProfileData->P_IDCardBackImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$data['Val_Deliveryboy'].'/'.$ProfileData->P_IDCardBackImage : '');	

							$Record = array(  
									'DeliveryBoyID' 				=> getStringValue($data['Val_Deliveryboy']),
									'ProfileID' 			=> getStringValue($ProfileData->VProfileID),
									'IDCardType'			=> getStringValue($ProfileData->P_IDCardType),
									'IDCardName'			=> getStringValue($ProfileData->P_IDCardName),
									'IDCardNumber'			=> getStringValue($ProfileData->P_IDCardNumber),
									'IDCardFrontImage'		=> getStringValue($FrontImage),
									'IDCardBackImage'		=> getStringValue($BackImage),
									'GuardianName'			=> getStringValue($ProfileData->P_GuardianName),
									'Gender'				=> getStringValue($ProfileData->P_Gender),
									'BirthDate'				=> getStringValue($ProfileData->P_BirthDate),
									'PermanentBuilding'		=> getStringValue($ProfileData->P_PermanentBuilding),
									'PermanentLocality'		=> getStringValue($ProfileData->P_PermanentLocality),
									'PermanentCity'			=> getStringValue($ProfileData->P_PermanentCity),
									'PermanentState'		=> getStringValue($ProfileData->P_PermanentState),
									'PermanentPincode'		=> getStringValue($ProfileData->P_PermanentPincode),
									'CurrentBuilding'		=> getStringValue($ProfileData->P_CurrentBuilding),
									'CurrentLocality'		=> getStringValue($ProfileData->P_CurrentLocality),
									'CurrentCity'			=> getStringValue($ProfileData->P_CurrentCity),
									'CurrentState'			=> getStringValue($ProfileData->P_CurrentState),
									'CurrentPincode'		=> getStringValue($ProfileData->P_CurrentPincode),
									'IdentityStatus' 		=> getStringValue($ProfileData->P_IDCardStatus),
									'PersonalDetailsStatus' => getStringValue($ProfileData->P_PersonalStatus),
									'CurrentAddressStatus' 	=> getStringValue($ProfileData->P_CurrentStatus),
									'TermsStatus'			=> getStringValue($ProfileData->P_TermsStatus),
								);	
							$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Record Fetched','data'=>$Record);	

						}else{
							$IdentityStatus 	= '';
							$PersonalStatus 	= '';
							$CurrentStatus 		= '';
							$TermsStatus 		= '';
							$result = array('status'=>'success','flag'=>'1','message'=>'No Record Fetched','data'=>(object)array());	
						}
						
												
					} elseif ($DeliveryBoyData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		}  else if(!empty($data['Action']) && $data['Action'] == 'GetAboutDetails'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '' ) {
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$AboutData = $this->Deliveryboys_model->getAbout(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
						if(!empty($AboutData)){
							$AboutData 		= (object)$AboutData[0];
							$AStatus 		= $AboutData->A_Status;


							$Record = array(  
									'DeliveryBoyID' 			=> getStringValue($data['Val_Deliveryboy']),
									'AboutID' 			=> getStringValue($AboutData->VAboutID),
									'BusinessName'		=> getStringValue($AboutData->A_BusinessName),
									'BusinessPresence'	=> getStringValue($AboutData->A_BusinessPresence),
									'ProfileLink'		=> getStringValue($AboutData->A_ProfileLink),
									'FacebookLink'		=> getStringValue($AboutData->A_FacebookLink),
									'WorkLinks'			=> json_decode($AboutData->A_WorkLinks),
									'PhoneNumber'		=> getStringValue($AboutData->A_PhoneNumber),
									'Type'				=> getStringValue($AboutData->A_Type),
									'ExperienceYear'	=> getStringValue($AboutData->A_ExperienceYear),
									'ExperienceMonth'	=> getStringValue($AboutData->A_ExperienceMonth),
									'Introduction'		=> getStringValue($AboutData->A_Introduction),
									'StartingPrice'		=> getStringValue($AboutData->A_StartingPrice),
									'Specialization'	=> json_decode($AboutData->A_Specialization),
									'AboutStatus'		=> getStringValue($AStatus),
								);	
							$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy About Record Fetched','data'=>$Record);	

						}else{
							
							$result = array('status'=>'success','flag'=>'1','message'=>'No Record Fetched','data'=>(object)array());	
						}
						
												
					} elseif ($DeliveryBoyData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		} else if(!empty($data['Action']) && $data['Action'] == 'GetLocationDetails'){				
				
				if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '' ) {
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
						
						$LocationData = $this->Deliveryboys_model->getLocations(NULL,array('L_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
						if(!empty($LocationData)){
							$LocationData 		= (object)$LocationData[0];
							$LStatus 		= $LocationData->L_Status;


							$Record = array(  
									'DeliveryBoyID' 	=> getStringValue($data['Val_Deliveryboy']),
									'LocationID' 			=> getStringValue($LocationData->VLocationID),
									'Location'				=> getStringValue($LocationData->L_Location),
									'Latitude'				=> getStringValue($LocationData->L_Latitude),
									'Longitude'				=> getStringValue($LocationData->L_Longitude),
									'Radius'			    => getStringValue($LocationData->L_Radius),
									'LocationStatus'	=> getStringValue($LStatus),
								);	
							$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Location Record Fetched','data'=>$Record);	

						}else{
							
							$result = array('status'=>'success','flag'=>'1','message'=>'No Record Fetched','data'=>(object)array());	
						}
						
												
					} elseif ($DeliveryBoyData === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
					}	
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}			 							
		}
		 else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
		}
		
        $this->data =$result;
        echo json_encode($this->data);	
	}
	
	// Edit Profile Of DeliveryBoy ANd Service Provider
	public function Profile()
    {		     
		$data = $this->input->post();	
		$Record = array();

		if( !empty($data) && $data['Action'] == 'Update' ) {	
			if( !empty($data['Val_Deliveryboy'])  )	
				{
					$data['Val_DBprofilestatus'] = '2';							

					$success = $this->Deliveryboys_model->update($data,$data['Val_Deliveryboy']);	
					
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					
					if ($DeliveryBoyData) {
							$data['Val_Relation'] = $data['Val_Deliveryboy'];
//							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');	
							$PostData = array();
	
							$AccountData = $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));
							if(empty($AccountData))	
								{
									$PostData['Val_Deliveryboy'] = $data['Val_Deliveryboy'];
									
									$ACSuccess = $this->Deliveryboys_model->addAccounts($PostData);	
								}	
							
							$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
							$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : '');
							
							$Record = array(  
										'DeliveryBoyID' => getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 		=> getStringValue($DeliveryBoyFullName),
										'FirstName'		=> getStringValue($DeliveryBoyData->DB_FirstName),
										'LastName'		=> getStringValue($DeliveryBoyData->DB_LastName),
										'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
										'MobileNumber'	=> getStringValue($DeliveryBoyData->DB_Mobile),
										'EmailAddress'	=> getStringValue($DeliveryBoyData->DB_Email),
									
										'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
										'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
										'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
										// 'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
										'City' 			=> getStringValue($DeliveryBoyData->DB_City),
										'Country' 		=> getStringValue($DeliveryBoyData->DB_Country),
										
										
										'ProfileImage' => $DeliveryBoyProfileImage,
										
									);		
					}
						
//					if ($success || $Asuccess) {}
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Profile Not Updated','data'=>$data['Val_Deliveryboy']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				}	
				else{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing...','data'=>$Record);	
					}	
			
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateProfileImage' ) {	
			if( !empty($data['Val_Deliveryboy'])  )	
				{
					
					
					$success = handle_deliveryboy_profile_image($data['Val_Deliveryboy']);
						
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if ($DeliveryBoyData) {
							$data['Val_Relation'] = $data['Val_Deliveryboy'];

							$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
							$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : '');
							
							$Record = array(  
										'DeliveryBoyID' => getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 		=> getStringValue($DeliveryBoyFullName),
										'FirstName'		=> getStringValue($DeliveryBoyData->DB_FirstName),
										'LastName'		=> getStringValue($DeliveryBoyData->DB_LastName),
										'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
										'MobileNumber'	=> getStringValue($DeliveryBoyData->DB_Mobile),
										'EmailAddress'	=> getStringValue($DeliveryBoyData->DB_Email),
										'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
										'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
										'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
										'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
										
										'ProfileImage' => $DeliveryBoyProfileImage,
										
									);		
					}
//					if ($success || $Asuccess) {}
					if (!empty($success) && !is_array($success) && $success == true) {
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Image Updated Successfully','data'=>$Record);	
					} else if ((is_array($success) && $success['flag'] == '1' ) || empty($success)) {
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Profile Image Not Updated','data'=>$success);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				}	
				else{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing...','data'=>$Record);	
					}	
			
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateLocation' ) {	
			if( !empty($data['Val_Deliveryboy']))	
				{
				
					$LocationData = $this->Deliveryboys_model->getLocations(NULL,array('L_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
					if(!empty($LocationData)){
						$LocationData 		= (object)$LocationData[0];


							if( !empty($data['Val_Llocation']) && !empty($data['Val_Llatitude'])  && !empty($data['Val_Llongitude']) && !empty($data['Val_Lradius'])   )	
								{
									$data['Val_Lstatus'] = '2';
								}

				
													
							$success 			= $this->Deliveryboys_model->updateLocations($data,$LocationData->VLocationID);	
						
							$LocationData = $this->Deliveryboys_model->getLocations($LocationData->VLocationID);
							$Record = array(  
										'DeliveryBoyID' 				=> getStringValue($LocationData->L_DeliveryBoyID),
										'LocationID' 			=> getStringValue($LocationData->VLocationID),
										'LocationStatus' 		=> getStringValue($LocationData->L_Status),
									);		
									
						if ($success) {
								$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Location Updated Successfully','data'=>$Record);	
							} else if ($success == false) {
								$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Location Not Updated','data'=>$Record);	
							}
							else{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>(object)array());	
							}			

					}
					else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>(object)array());	
						}	
					
				}	
				else{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing...','data'=>$Record);	
					}	
			
		} 		
		else if( !empty($data) && $data['Action'] == 'UpdateAccount' ) {	
			if( !empty($data['Val_Deliveryboy']))	
				{
				
					$AccountData = $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));
						
					if(!empty($AccountData)){
						$AccountData 		= (object)$AccountData[0];


							if( !empty($data['Val_Aaccountname']) && !empty($data['Val_Aaccounttype']) && !empty($data['Val_Aaccountnumber']) && !empty($data['Val_Aifscnumber']) )	
								{
									$data['Val_Astatus'] = '2';
								}

				
													
							$success 			= $this->Deliveryboys_model->updateAccounts($data,$AccountData->DBAccountID);	
						
							$AccountData = $this->Deliveryboys_model->getAccounts($AccountData->DBAccountID);
							$Record = array(  
										'DeliveryBoyID' 				=> getStringValue($AccountData->A_DeliveryBoyID),
										'AccountID' 				=> getStringValue($AccountData->DBAccountID),
										'AccountStatus' 			=> getStringValue($AccountData->A_Status),
									);		
									
						if ($success) {
								$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Account Updated Successfully','data'=>$Record);	
							} else if ($success == false) {
								$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Account Not Updated','data'=>$Record);	
							}
							else{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>(object)array());	
							}			

					}
					else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>(object)array());	
						}	
					
				}	
				else{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing...','data'=>$Record);	
					}	
			
		} 		
		
		else if( !empty($data) && $data['Action'] == 'UpdateProfile' ) {	
			if( !empty($data['Val_Deliveryboy'])  )	
				{
					$data['Val_Vprofilestatus'] = '2';								
					$success = $this->Deliveryboys_model->update($data,$data['Val_Deliveryboy']);	
						
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
							$data['Val_Relation'] = $data['Val_Deliveryboy'];
//							$Asuccess = $this->Authentication_model->AppUpdate($data,$data['Val_Relation'],'1');	
							
							
							$AddressArray = $this->Deliveryboys_model->getAddresses(NULL,array('A_RelationID'=>$DeliveryBoyData->DeliveryBoyID,'A_Type'=>'2'));
										
							$AddressRecords = array();
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData 	= $AddressRecords;
							if(!empty($AddressArray)){
								
								foreach($AddressArray as $Adress)
									{
										$AddressRecords[] = array(  
													'AddressID' 	=> getStringValue($Adress['AddressID']),
													'Name' 			=> getStringValue($Adress['A_Name']),
													'Address' 		=> getStringValue($Adress['A_Address']),
													'Location' 		=> getStringValue($Adress['A_Location']),
													'Latitude' 		=> getStringValue($Adress['A_Latitude']),
													'Longitude' 	=> getStringValue($Adress['A_Longitude']),
													);
									}
							}	
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData	= $AddressRecords;		
							
							$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
							$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : '');
							
							$Record = array(  
										'DeliveryBoyID' 			=> getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 			=> getStringValue($DeliveryBoyFullName),
										'FirstName'			=> getStringValue($DeliveryBoyData->DB_FirstName),
										'LastName'			=> getStringValue($DeliveryBoyData->DB_LastName),
										'CountryCode'		=> getStringValue($DeliveryBoyData->DB_CountryCode),
										'MobileNumber'		=> getStringValue($DeliveryBoyData->DB_Mobile),
										'EmailAddress'		=> getStringValue($DeliveryBoyData->DB_Email),
									
										'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
										'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
										'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
										'AddressCount'	=> $AddressCount,
										'AddressData'	=> $AddressData,															
										'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
										'City' 			=> getStringValue($DeliveryBoyData->DB_City),
										'Country' 		=> getStringValue($DeliveryBoyData->DB_Country),
										
	//										'ProfileImage' => $CustomerProfileImage,
										'ProfileStatus' => $DeliveryBoyData->DB_ProfileStatus,
										'VerificationStatus' => $DeliveryBoyData->DB_VerificationStatus,
										'Status' 		=> $DeliveryBoyData->DB_Status,										
									);		
					}
						
//					if ($success || $Asuccess) {}
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Updated Successfully','data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Profile Not Updated','data'=>$data['Val_Deliveryboy']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				}	
				else{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing...','data'=>$Record);	
					}	
			
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateDetails' ) {	
													
					$success = $this->Deliveryboys_model->update($data,$data['Val_Deliveryboy']);	
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
							$data['Val_Relation'] = $data['Val_Deliveryboy'];

							$DeliveryBoyFullName = $DeliveryBoyData->C_FirstName." ".$DeliveryBoyData->C_LastName;
							//$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->C_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->C_ProfileImage : NULL);
							$Record = array(  
										'DeliveryBoyID' 		=> getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 			=> getStringValue($DeliveryBoyFullName),
										'FirstName'			=> getStringValue($DeliveryBoyData->C_FirstName),
										'LastName'			=> getStringValue($DeliveryBoyData->C_LastName),
										'CountryCode'		=> getStringValue($DeliveryBoyData->C_CountryCode),
										'MobileNumber'		=> getStringValue($DeliveryBoyData->C_Mobile),
										'EmailAddress'		=> getStringValue($DeliveryBoyData->C_Email),
										'Latitude'			=> getStringValue($DeliveryBoyData->C_Latitude),
										'Longitude'			=> getStringValue($DeliveryBoyData->C_Longitude),
										'Address'			=> getStringValue($DeliveryBoyData->C_Address),
										'Location'			=> getStringValue($DeliveryBoyData->C_Location),
								//		'ProfileImage'=> $DeliveryBoyProfileImage,						
									);		
					}
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_location_updated_success',_l('user_vendor')),'data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_location_updated_fail',_l('user_vendor')),'data'=>$data['Val_Deliveryboy']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>_l('msg_something_went_wrong'),'data'=>$success);	
					}
			
		} 
/*		else if( !empty($data) && $data['Action'] == 'UpdateLocation' ) {	
													
					$success = $this->Deliveryboys_model->update($data,$data['Val_Deliveryboy']);	
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					
					if ($DeliveryBoyData) {
							$data['Val_Relation'] = $data['Val_Deliveryboy'];
							
							$AddressArray = $this->Deliveryboys_model->getAddresses(NULL,array('A_DeliveryBoyID'=>$DeliveryBoyData->DeliveryBoyID));

							$AddressRecords = array();
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData 	= $AddressRecords;
							if(!empty($AddressArray)){
								foreach($AddressArray as $Adress)
									{
										$AddressRecords[] = array(  
													'AddressID' 	=> getStringValue($Adress['AddressID']),
													'Name' 			=> getStringValue($Adress['A_Name']),
													'Address' 		=> getStringValue($Adress['A_Address']),
													'Location' 		=> getStringValue($Adress['A_Location']),
													'Latitude' 		=> getStringValue($Adress['A_Latitude']),
													'Longitude' 	=> getStringValue($Adress['A_Longitude']),
													);
									}
							}	
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData	= $AddressRecords;	
							
							
							$DeliveryBoyFullName = $DeliveryBoyData->C_FirstName." ".$DeliveryBoyData->C_LastName;
							$OTPResponse = "";
							//$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->C_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->C_ProfileImage : NULL);
							$Record = array(  
										'DeliveryBoyID' 		=> getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 			=> getStringValue($DeliveryBoyFullName),
										'FirstName'			=> getStringValue($DeliveryBoyData->C_FirstName),
										'LastName'			=> getStringValue($DeliveryBoyData->C_LastName),
										'CountryCode'		=> getStringValue($DeliveryBoyData->C_CountryCode),
										'MobileNumber'		=> getStringValue($DeliveryBoyData->C_Mobile),
										'EmailAddress'		=> getStringValue($DeliveryBoyData->C_Email),
										'Latitude'			=> getStringValue($DeliveryBoyData->C_Latitude),
										'Longitude'			=> getStringValue($DeliveryBoyData->C_Longitude),
										'Address'			=> getStringValue($DeliveryBoyData->C_Address),
										'AddressCount'		=> $AddressCount,
										'AddressData'		=> $AddressData,
										'Location'			=> getStringValue($DeliveryBoyData->C_Location),
										'OTPCode'			=> getStringValue($OTPResponse),
								//		'ProfileImage'=> $DeliveryBoyProfileImage,						
									);		
					}
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_location_updated_success',_l('user_vendor')),'data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_location_updated_fail',_l('user_vendor')),'data'=>$data['Val_Deliveryboy']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>_l('msg_something_went_wrong'),'data'=>$success);	
					}
			
		} */
		else if( !empty($data) && $data['Action'] == 'AddAddress' ) {	
			
			if( !empty($data['Val_Deliveryboy']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])  ) {												
				
					$success = $this->Deliveryboys_model->addAddress($data);		
				
					$address_id = $success;
					$Record = array();
					if ($success) {
						$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
						$AddressArray = $this->Deliveryboys_model->getAddresses(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));

						$AddressRecords = array();
						$AddressCount 	= (string)count($AddressRecords); 
						$AddressData 	= $AddressRecords;
						if(!empty($AddressArray)){

							foreach($AddressArray as $Adress)
								{
									$AddressRecords[] = array(  
												'AddressID' 	=> getStringValue($Adress['AddressID']),
												'Name' 			=> getStringValue($Adress['A_Name']),
												'Address' 		=> getStringValue($Adress['A_Address']),
												'Location' 		=> getStringValue($Adress['A_Location']),
												'Latitude' 		=> getStringValue($Adress['A_Latitude']),
												'Longitude' 	=> getStringValue($Adress['A_Longitude']),
											);
								}
						}	
						$AddressCount 	= (string)count($AddressRecords); 
						$AddressData	= $AddressRecords;	
						$OTPResponse = "";
						
						$DeliveryBoyFullName = getStringValue($DeliveryBoyData->C_FirstName)." ".getStringValue($DeliveryBoyData->C_LastName);
						$Record = array(  
									'DeliveryBoyID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
									'FullName' 		=> getStringValue($DeliveryBoyFullName),
									'FirstName' 	=> getStringValue($DeliveryBoyData->C_FirstName),
									'LastName' 		=> getStringValue($DeliveryBoyData->C_LastName),
									'CountryCode'	=> getStringValue($DeliveryBoyData->C_CountryCode),
									'MobileNumber' 	=> getStringValue($DeliveryBoyData->C_Mobile),
									'EmailAddress' 	=> getStringValue($DeliveryBoyData->C_Email),
									'Latitude' 		=> getStringValue($DeliveryBoyData->C_Latitude),
									'Longitude' 	=> getStringValue($DeliveryBoyData->C_Longitude),
									'Address'		=> getStringValue($DeliveryBoyData->C_Address),
									'AddressCount'	=> $AddressCount,
									'AddressData'	=> $AddressData,
									'Location' 		=> getStringValue($DeliveryBoyData->C_Location),
									'OTPCode'		=> getStringValue($OTPResponse),
							//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
							//		'Status'=> getStatus($DeliveryBoyData->C_Status),
								);		
						
						$result = array('status'=>'success','flag'=>'1','message'=>'Address Added Successfully','data'=>$Record);
					}
					else if ($success == false) {
							$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t add address. Please try again later.(404)','data'=>$Record);	
					} else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$Record);	
					}
				
			}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
			}										
				
		} 
		else if( !empty($data) && $data['Action'] == 'UpdateAddress' ) {	
			
			if( !empty($data['Val_Deliveryboy']) && !empty($data['Val_Address']) && !empty($data['Val_Aname']) && !empty($data['Val_Alatitude']) && !empty($data['Val_Alongitude']) && !empty($data['Val_Aaddress']) && !empty($data['Val_Alocation'])  ) {												
					
					$data['Val_Type'] 		= '1';
					$data['Val_Relation'] 	= $data['Val_Deliveryboy'];
					unset($data['Val_Deliveryboy']);											
					$success = $this->Deliveryboys_model->updateAddress($data,$data['Val_Address']);	
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Relation']);
					$AddressData = $this->Deliveryboys_model->getAddresses($data['Val_Address']);
					$Record = array();
					if (!empty($DeliveryBoyData) && !empty($AddressData)) {
							
							$AddressArray = $this->Deliveryboys_model->getAddresses(NULL,array('A_RelationID'=>$DeliveryBoyData->DeliveryBoyID));

							$AddressRecords = array();
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData 	= $AddressRecords;
							if(!empty($AddressArray)){
								foreach($AddressArray as $Adress)
									{
										$AddressRecords[] = array(  
												'AddressID' 	=> getStringValue($Adress['AddressID']),
												'Name' 			=> getStringValue($Adress['A_Name']),
												'Address' 		=> getStringValue($Adress['A_Address']),
												'Location' 		=> getStringValue($Adress['A_Location']),
												'Latitude' 		=> getStringValue($Adress['A_Latitude']),
												'Longitude' 	=> getStringValue($Adress['A_Longitude']),
												);
									}
							}	
							$AddressCount 	= (string)count($AddressRecords); 
							$AddressData	= $AddressRecords;	
							
							$OTPResponse = "";
							
							$DeliveryBoyFullName = $DeliveryBoyData->C_FirstName." ".$DeliveryBoyData->C_LastName;
							//$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->C_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->C_ProfileImage : NULL);
							$Record = array(  
										'DeliveryBoyID' 		=> getStringValue($DeliveryBoyData->DeliveryBoyID),
										'FullName' 			=> getStringValue($DeliveryBoyFullName),
										'FirstName'			=> getStringValue($DeliveryBoyData->C_FirstName),
										'LastName'			=> getStringValue($DeliveryBoyData->C_LastName),
										'CountryCode'		=> getStringValue($DeliveryBoyData->C_CountryCode),
										'MobileNumber'		=> getStringValue($DeliveryBoyData->C_Mobile),
										'EmailAddress'		=> getStringValue($DeliveryBoyData->C_Email),
										'Latitude'			=> getStringValue($DeliveryBoyData->C_Latitude),
										'Longitude'			=> getStringValue($DeliveryBoyData->C_Longitude),
										'Address'			=> getStringValue($DeliveryBoyData->C_Address),
										'AddressCount'		=> $AddressCount,
										'AddressData'		=> $AddressData,
										'Location'			=> getStringValue($DeliveryBoyData->C_Location),
										'OTPCode'			=> getStringValue($OTPResponse),
								//		'ProfileImage'=> $DeliveryBoyProfileImage,						
									);		
					}
						
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_location_updated_success',_l('user_vendor')),'data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_location_updated_fail',_l('user_vendor')),'data'=>$data['Val_Deliveryboy']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>_l('msg_something_went_wrong'),'data'=>$success);	
					}
			}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
			}				
		} 
		else if( !empty($data) && $data['Action'] == 'DeleteAddress' ){	
													
					$success = $this->Deliveryboys_model->deleteAddress($data['Val_Address']);	
							
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					$AddressArray = $this->Deliveryboys_model->getAddresses(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));

					$AddressRecords = array();
					$AddressCount 	= (string)count($AddressRecords); 
					$AddressData 	= $AddressRecords;
					if(!empty($AddressArray)){
						
						foreach($AddressArray as $Adress)
							{
								$AddressRecords[] = array(  
											'AddressID' 	=> getStringValue($Adress['AddressID']),
											'Name' 			=> getStringValue($Adress['A_Name']),
											'Address' 		=> getStringValue($Adress['A_Address']),
											'Location' 		=> getStringValue($Adress['A_Location']),
											'Latitude' 		=> getStringValue($Adress['A_Latitude']),
											'Longitude' 	=> getStringValue($Adress['A_Longitude']),
										);
							}
						}									
						$AddressCount 	= (string)count($AddressRecords); 
						$AddressData	= $AddressRecords;	
						$OTPResponse = "";
						
						$DeliveryBoyFullName = getStringValue($DeliveryBoyData->C_FirstName)." ".getStringValue($DeliveryBoyData->C_LastName);
						$Record = array(  
									'DeliveryBoyID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
									'FullName' 		=> getStringValue($DeliveryBoyFullName),
									'FirstName' 	=> getStringValue($DeliveryBoyData->C_FirstName),
									'LastName' 		=> getStringValue($DeliveryBoyData->C_LastName),
									'CountryCode'	=> getStringValue($DeliveryBoyData->C_CountryCode),
									'MobileNumber' 	=> getStringValue($DeliveryBoyData->C_Mobile),
									'EmailAddress' 	=> getStringValue($DeliveryBoyData->C_Email),
									'Latitude' 		=> getStringValue($DeliveryBoyData->C_Latitude),
									'Longitude' 	=> getStringValue($DeliveryBoyData->C_Longitude),
									'Address'		=> getStringValue($DeliveryBoyData->C_Address),
									'AddressCount'	=> $AddressCount,
									'AddressData'	=> $AddressData,
									'Location' 		=> getStringValue($DeliveryBoyData->C_Location),
									'OTPCode'		=> getStringValue($OTPResponse),
							//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
							//		'Status'=> getStatus($DeliveryBoyData->C_Status),
								);
					if ($success ) {
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Address Deleted Successfully','data'=>$Record);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Address Not Deleted','data'=>$Record);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
					$success = $this->Deliveryboys_model->delete($data['Val_Deliveryboy']);	
					
					if ($success) {
							$Adata['Val_Relation'] = $data['Val_Deliveryboy'];
							$Adata['Val_Status'] = '1';
							$Asuccess = $this->Authentication_model->AppUpdate($Adata,$Adata['Val_Relation'],'1');	
							
					}
						
					if ($success || $Asuccess) {
						$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Deleted Successfully','data'=>'Confidential');	
					} else if ($success == false && $Asuccess == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Profile Not Deleted','data'=>$data['Val_Deliveryboy']);	
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
	
	// Edit Profile Of DeliveryBoy ANd Service Provider
	public function Dashboard()
	{
		$Records =array();
		$Record =array();
		$data = $this->input->post();	
			
		if(!empty($data['Action']) && $data['Action'] == 'GetData'){
						
				if(!empty($data['Val_Deliveryboy']))		
					{
						$OrderRecords 		= array();
						$OrderRecordsCount 	= (string)count($OrderRecords);
						$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
						// print_r($DeliveryBoyData);
						// exit;
						if(!empty($DeliveryBoyData))
							{
								// $CategoryData = $this->Categories_model->get($DeliveryBoyData->DB_CategoryID);
								// if(!empty($CategoryData))
								// 	{
										// $BusinessType = $CategoryData->C_Type;
										// if($BusinessType == '1'){
										// 	$OrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3'));										
										// } else if($BusinessType == '2'){
										// 	$OrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3'));
	
										// } else if($BusinessType == '3'){
										// 	$OrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3'));
										
										// }

										$OrdersArrayProducts =  $this->Cart_model->getDProductsOrdersData($data['Val_Deliveryboy']);										
										$OrdersArrayRestaurant =  $this->Cart_model->getDRestaurantsOrdersData($data['Val_Deliveryboy']);
										
										$OrdersArray = array_merge($OrdersArrayProducts,$OrdersArrayRestaurant);

											$SourceLatitude = 16.770087;
											$SourceLongitude = 74.55706299999997;
											$DestinationLatitude =16.7003192;
											$DestinationLongitude =74.2470945;

										$TotalSales = 0;
										if(!empty($OrdersArray))
											{
												foreach($OrdersArray as $OrderArray)
													{	
														$OrderData = (object)$OrderArray; 
														if(!empty($OrderData->CartID)){
															/* 04.04.2019 04:16PM */
															$TotalSales = $TotalSales + $OrderData->C_Total;
														
															$OrderID		= $OrderData->CartID;
															$OrderType		= '1';
															$OrderName		= getOrderName('1',$OrderID);
															$OrderDate		= $OrderData->C_AssignedDttm;
															$OrderTimeAgo	= time_ago($OrderData->C_AssignedDttm);
															$OrderTotal		= $OrderData->C_Total;
								
														}
														else if(!empty($OrderData->PCartID)){
															/* 04.04.2019 04:16PM */
															$TotalSales = $TotalSales + $OrderData->PC_Total;

															$OrderID		= $OrderData->PCartID;
															$OrderType		= '2';
															$OrderName		= getOrderName('2',$OrderID);
															$OrderDate		= $OrderData->PC_AssignedDttm;
															$OrderTimeAgo	= time_ago($OrderData->PC_AssignedDttm);
															$OrderTotal		= $OrderData->PC_Total;
														}
														else if(!empty($OrderData->RCartID)){

															/* 04.04.2019 04:16PM */
															$TotalSales = $TotalSales + $OrderData->RC_Total;
															
															$OrderID		= $OrderData->RCartID;
															$OrderType		= '3';
															$OrderName		= getOrderName('3',$OrderID);
															$OrderDate		= $OrderData->RC_AssignedDttm;
															$OrderTimeAgo	= time_ago($OrderData->RC_AssignedDttm);
															$OrderTotal		= $OrderData->RC_Total;
														}
														
														$OrderRecords[] = array(
																			'OrderID' 		=> $OrderID,
																			'OrderType' 	=> $OrderType,
																			'OrderName' 	=> $OrderName,
																			'OrderDate' 	=> $OrderDate,
																			'OrderTimeAgo' 	=> $OrderTimeAgo,
																			'OrderTotal'	=> "Rs. ".getStringValue($OrderTotal),
																		);
														
													}
											}
										
										
										$CurrentMonth 	= date('m');
										$LastMonth 	= date("m",strtotime("-1 month"));	
										$LastMonthYear = date("Y",strtotime("-1 month"));	
										//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
										$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );
										
										$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
										$LastMonthDate = date('Y-m-d',strtotime($StringDate));
										 //5th
										$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
										$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_1' AND  DATE(C_BookedDttm) >= '$LastMonthDate' ");
										
										 //10th
										$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
										$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_2' AND  DATE(C_BookedDttm) > '$month_line_1' ");
	
										 //15th
										$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
										$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_3' AND  DATE(C_BookedDttm) > '$month_line_2' ");
										 //20th
										$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
										$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_4' AND  DATE(C_BookedDttm) > '$month_line_3' ");
										 //25th
										$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
										$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_5' AND  DATE(C_BookedDttm) > '$month_line_4' ");
										//last day of month
										$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
										$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) <= '$month_line_last' AND  DATE(C_BookedDttm) > '$month_line_5' ");
										
										
										if(!empty($OrdersArray1))
											$Ordertotal1 	= $OrdersArray1->C_Total;
										else
											$Ordertotal1 	= number_format('0',2,'.','');
	
										if(!empty($OrdersArray2))
											$Ordertotal2 	= $OrdersArray2->C_Total;
										else
											$Ordertotal2 	= number_format('0',2,'.','');
	
										if(!empty($OrdersArray3))
											$Ordertotal3 	= $OrdersArray3->C_Total;
										else
											$Ordertotal3 	= number_format('0',2,'.','');
	
										if(!empty($OrdersArray4))
											$Ordertotal4 	= $OrdersArray4->C_Total;
										else
											$Ordertotal4 	= number_format('0',2,'.','');
	
										if(!empty($OrdersArray5))
											$Ordertotal5 	= $OrdersArray5->C_Total;
										else
											$Ordertotal5 	= number_format('0',2,'.','');
	
										if(!empty($OrdersArraylast))
											$Ordertotallast 	= $OrdersArraylast->C_Total;
										else
											$Ordertotallast 	= number_format('0',2,'.','');
	
										
										
										
										$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
										$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);
										
										$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
						
										$Records['OrderRecords']				= $OrderRecords;
										$Records['GraphRecords']['AxisValues'] 	= $AxisValues;
										$Records['GraphRecords']['PointValues'] = $PointValues;
										$Records['GraphRecords']['TotalSales'] 	= 'Rs. '.number_format($TotalSales,'2');
										$Records['MiscData'] 					= $MiscRecords;
										$Records['SourceLatitude'] = $SourceLatitude;
										$Records['SourceLongitude'] = $SourceLongitude;
										$Records['DestinationLatitude'] = $DestinationLatitude;
										$Records['DestinationLongitude'] = $DestinationLongitude;
										
										$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Dashboard Data Fetched','data'=>$Records);	
										
	
	
										
								// 	}
								// else
								// 	{
								// 		$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
								// 	}	
							}
						else
							{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
							}
						
						
					}
				else
					{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing...','data'=>(object)$Records);	
					}	
					
			} else if(!empty($data['Action']) && $data['Action'] == 'GetRevenueData'){
						
				if(!empty($data['Val_Deliveryboy']))		
					{
						$OrderRecords 		= array();
						$OrderRecordsCount 	= (string)count($OrderRecords);
						$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
						if(!empty($DeliveryBoyData))
							{
								$CategoryData = $this->Categories_model->get($DeliveryBoyData->DB_CategoryID);
								if(!empty($CategoryData))
									{
										$BusinessType = $CategoryData->C_Type;
										$Type = $data['Val_Type'];
										if($BusinessType == '1'){
											$OrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3'));
											if($Type == '1'){
											
											$CurrentDay 	= date('Y-m-d');
										
											 //5th
											$month_line_1 = date('Y-m-d', strtotime('-1 days'));
											$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_1'");
											
											 //10th
											$month_line_2 = date('Y-m-d', strtotime('-2 days'));
											$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_2'");
		
											 //15th
											$month_line_3 = date('Y-m-d', strtotime('-3 days'));
											$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_3'");
											 //20th
											$month_line_4 = date('Y-m-d', strtotime('-4 days'));
											$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_4'");
											 //25th
											$month_line_5 = date('Y-m-d', strtotime('-5 days'));
											$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_5'");
											//last day of month
											$month_line_last = date('Y-m-d', strtotime('-6 days'));
											$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_last'");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= number_format($OrdersArray1->C_Total,2,'.','');
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= number_format($OrdersArray2->C_Total,2,'.','');
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= number_format($OrdersArray3->C_Total,2,'.','');
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= number_format($OrdersArray4->C_Total,2,'.','');
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= number_format($OrdersArray5->C_Total,2,'.','');
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= number_format($OrdersArraylast->C_Total,2,'.','');
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->C_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->C_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';											
											
										
										} else if($Type == '2'){
											
											$CurrentMonth 	= date('m');
											$LastMonth 	= date("m",strtotime("-1 month"));	
											$LastMonthYear = date("Y",strtotime("-1 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );
											
											$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
											$OrdersArray1 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
											$OrdersArray2 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
											$OrdersArray3 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
											$OrdersArray4 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
											$OrdersArray5 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->PC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->PC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->PC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->PC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->PC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->PC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);
											
											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->PC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->PC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
											
										
										} else if($Type == '3'){
											
											$CurrentMonth 	= date('m');
											$Last3rdMonth 	= date("m",strtotime("-3 month"));	
											$Last3rdMonthYear = date("Y",strtotime("-3 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$Last3rdMonth,$Last3rdMonthYear );
											
											$StringDate = $Last3rdMonthYear.'-'.$Last3rdMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +15 day"));
											$OrdersArray1 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArray2 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +45 day"));
											$OrdersArray3 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate."  +2 month - 1 hour"));
											$OrdersArray4 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +75 day"));
											$OrdersArray5 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." +3 month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->RC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->RC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->RC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->RC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->RC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->RC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);


											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->RC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->RC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
									
										} 
		
										} else if($BusinessType == '2'){
											$OrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3'));
											if($Type == '1'){
											
											$CurrentDay 	= date('Y-m-d');
										
											 //5th
											$month_line_1 = date('Y-m-d', strtotime('-1 days'));
											$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_1'");
											
											 //10th
											$month_line_2 = date('Y-m-d', strtotime('-2 days'));
											$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_2'");
		
											 //15th
											$month_line_3 = date('Y-m-d', strtotime('-3 days'));
											$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_3'");
											 //20th
											$month_line_4 = date('Y-m-d', strtotime('-4 days'));
											$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_4'");
											 //25th
											$month_line_5 = date('Y-m-d', strtotime('-5 days'));
											$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_5'");
											//last day of month
											$month_line_last = date('Y-m-d', strtotime('-6 days'));
											$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_last'");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= number_format($OrdersArray1->C_Total,2,'.','');
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= number_format($OrdersArray2->C_Total,2,'.','');
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= number_format($OrdersArray3->C_Total,2,'.','');
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= number_format($OrdersArray4->C_Total,2,'.','');
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= number_format($OrdersArray5->C_Total,2,'.','');
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= number_format($OrdersArraylast->C_Total,2,'.','');
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->C_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->C_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';											
											
										
										} else if($Type == '2'){
											
											$CurrentMonth 	= date('m');
											$LastMonth 	= date("m",strtotime("-1 month"));	
											$LastMonthYear = date("Y",strtotime("-1 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );
											
											$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
											$OrdersArray1 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
											$OrdersArray2 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
											$OrdersArray3 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
											$OrdersArray4 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
											$OrdersArray5 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->PC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->PC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->PC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->PC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->PC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->PC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);
											
											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->PC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->PC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
											
										
										} else if($Type == '3'){
											
											$CurrentMonth 	= date('m');
											$Last3rdMonth 	= date("m",strtotime("-3 month"));	
											$Last3rdMonthYear = date("Y",strtotime("-3 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$Last3rdMonth,$Last3rdMonthYear );
											
											$StringDate = $Last3rdMonthYear.'-'.$Last3rdMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +15 day"));
											$OrdersArray1 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArray2 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +45 day"));
											$OrdersArray3 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate."  +2 month - 1 hour"));
											$OrdersArray4 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +75 day"));
											$OrdersArray5 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." +3 month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->RC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->RC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->RC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->RC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->RC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->RC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);


											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->RC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->RC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
									
										} 
	
										} else if($BusinessType == '3'){
											$OrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3'));
											if($Type == '1'){
											
											$CurrentDay 	= date('Y-m-d');
										
											 //5th
											$month_line_1 = date('Y-m-d', strtotime('-1 days'));
											$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_1'");
											
											 //10th
											$month_line_2 = date('Y-m-d', strtotime('-2 days'));
											$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_2'");
		
											 //15th
											$month_line_3 = date('Y-m-d', strtotime('-3 days'));
											$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_3'");
											 //20th
											$month_line_4 = date('Y-m-d', strtotime('-4 days'));
											$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_4'");
											 //25th
											$month_line_5 = date('Y-m-d', strtotime('-5 days'));
											$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_5'");
											//last day of month
											$month_line_last = date('Y-m-d', strtotime('-6 days'));
											$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_last'");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= number_format($OrdersArray1->C_Total,2,'.','');
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= number_format($OrdersArray2->C_Total,2,'.','');
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= number_format($OrdersArray3->C_Total,2,'.','');
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= number_format($OrdersArray4->C_Total,2,'.','');
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= number_format($OrdersArray5->C_Total,2,'.','');
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= number_format($OrdersArraylast->C_Total,2,'.','');
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->C_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->C_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';											
											
										
										} else if($Type == '2'){
											
											$CurrentMonth 	= date('m');
											$LastMonth 	= date("m",strtotime("-1 month"));	
											$LastMonthYear = date("Y",strtotime("-1 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );
											
											$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
											$OrdersArray1 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
											$OrdersArray2 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
											$OrdersArray3 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
											$OrdersArray4 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
											$OrdersArray5 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->PC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->PC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->PC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->PC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->PC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->PC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);
											
											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->PC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->PC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
											
										
										} else if($Type == '3'){
											
											$CurrentMonth 	= date('m');
											$Last3rdMonth 	= date("m",strtotime("-3 month"));	
											$Last3rdMonthYear = date("Y",strtotime("-3 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$Last3rdMonth,$Last3rdMonthYear );
											
											$StringDate = $Last3rdMonthYear.'-'.$Last3rdMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +15 day"));
											$OrdersArray1 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArray2 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +45 day"));
											$OrdersArray3 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate."  +2 month - 1 hour"));
											$OrdersArray4 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +75 day"));
											$OrdersArray5 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." +3 month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->RC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->RC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->RC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->RC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->RC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->RC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);


											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->RC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->RC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
									
										} 
											
										}
										
/*										if($Type == '1'){
											
											$CurrentDay 	= date('Y-m-d');
										
											 //5th
											$month_line_1 = date('Y-m-d', strtotime('-1 days'));
											$OrdersArray1 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_1'");
											
											 //10th
											$month_line_2 = date('Y-m-d', strtotime('-2 days'));
											$OrdersArray2 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_2'");
		
											 //15th
											$month_line_3 = date('Y-m-d', strtotime('-3 days'));
											$OrdersArray3 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_3'");
											 //20th
											$month_line_4 = date('Y-m-d', strtotime('-4 days'));
											$OrdersArray4 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_4'");
											 //25th
											$month_line_5 = date('Y-m-d', strtotime('-5 days'));
											$OrdersArray5 =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_5'");
											//last day of month
											$month_line_last = date('Y-m-d', strtotime('-6 days'));
											$OrdersArraylast =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$month_line_last'");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= number_format($OrdersArray1->C_Total,2,'.','');
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= number_format($OrdersArray2->C_Total,2,'.','');
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= number_format($OrdersArray3->C_Total,2,'.','');
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= number_format($OrdersArray4->C_Total,2,'.','');
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= number_format($OrdersArray5->C_Total,2,'.','');
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= number_format($OrdersArraylast->C_Total,2,'.','');
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);

											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->C_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getCartTotal(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'4','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->get(NULL,array('C_AssignedTo'=>$data['Val_Deliveryboy'],'C_OrderStatus'=>'1','C_Status'=>'3')," DATE(C_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->C_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';											
											
										
										} else if($Type == '2'){
											
											$CurrentMonth 	= date('m');
											$LastMonth 	= date("m",strtotime("-1 month"));	
											$LastMonthYear = date("Y",strtotime("-1 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$LastMonth,$LastMonthYear );
											
											$StringDate = $LastMonthYear.'-'.$LastMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +4 day"));
											$OrdersArray1 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_1' AND  DATE(PC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." +9 day"));
											$OrdersArray2 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_2' AND  DATE(PC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +14 day"));
											$OrdersArray3 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_3' AND  DATE(PC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate." +19 day"));
											$OrdersArray4 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_4' AND  DATE(PC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +24 day"));
											$OrdersArray5 =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_5' AND  DATE(PC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) <= '$month_line_last' AND  DATE(PC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->PC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->PC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->PC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->PC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->PC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->PC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);
											
											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->PC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getProductsCartTotal(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'4','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getProductsCart(NULL,array('PC_AssignedTo'=>$data['Val_Deliveryboy'],'PC_OrderStatus'=>'1','PC_Status'=>'3')," DATE(PC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->PC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
											
										
										} else if($Type == '3'){
											
											$CurrentMonth 	= date('m');
											$Last3rdMonth 	= date("m",strtotime("-3 month"));	
											$Last3rdMonthYear = date("Y",strtotime("-3 month"));	
											//echo date("Y-m-d H:i:s",strtotime("-1 month"));	
											$TotalDays = cal_days_in_month (CAL_GREGORIAN,$Last3rdMonth,$Last3rdMonthYear );
											
											$StringDate = $Last3rdMonthYear.'-'.$Last3rdMonth.'-01';
											$LastMonthDate = date('Y-m-d',strtotime($StringDate));
											 //5th
											$month_line_1 = date('Y-m-d',strtotime($LastMonthDate." +15 day"));
											$OrdersArray1 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_1' AND  DATE(RC_BookedDttm) >= '$LastMonthDate' ");
											
											 //10th
											$month_line_2 = date('Y-m-d',strtotime($LastMonthDate." next month - 1 hour"));
											$OrdersArray2 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_2' AND  DATE(RC_BookedDttm) > '$month_line_1' ");
		
											 //15th
											$month_line_3 = date('Y-m-d',strtotime($LastMonthDate." +45 day"));
											$OrdersArray3 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_3' AND  DATE(RC_BookedDttm) > '$month_line_2' ");
											 //20th
											$month_line_4 = date('Y-m-d',strtotime($LastMonthDate."  +2 month - 1 hour"));
											$OrdersArray4 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_4' AND  DATE(RC_BookedDttm) > '$month_line_3' ");
											 //25th
											$month_line_5 = date('Y-m-d',strtotime($LastMonthDate." +75 day"));
											$OrdersArray5 =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_5' AND  DATE(RC_BookedDttm) > '$month_line_4' ");
											//last day of month
											$month_line_last = date('Y-m-d',strtotime($LastMonthDate." +3 month - 1 hour"));
											$OrdersArraylast =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) <= '$month_line_last' AND  DATE(RC_BookedDttm) > '$month_line_5' ");
											
											
											if(!empty($OrdersArray1))
												$Ordertotal1 	= $OrdersArray1->RC_Total;
											else
												$Ordertotal1 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray2))
												$Ordertotal2 	= $OrdersArray2->RC_Total;
											else
												$Ordertotal2 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray3))
												$Ordertotal3 	= $OrdersArray3->RC_Total;
											else
												$Ordertotal3 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray4))
												$Ordertotal4 	= $OrdersArray4->RC_Total;
											else
												$Ordertotal4 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArray5))
												$Ordertotal5 	= $OrdersArray5->RC_Total;
											else
												$Ordertotal5 	= number_format('0',2,'.','');
		
											if(!empty($OrdersArraylast))
												$Ordertotallast 	= $OrdersArraylast->RC_Total;
											else
												$Ordertotallast 	= number_format('0',2,'.','');
		
											
											
											
											$AxisValues = array($month_line_1,$month_line_2,$month_line_3,$month_line_4,$month_line_5,$month_line_last);
											$PointValues = array($Ordertotal1,$Ordertotal2,$Ordertotal3,$Ordertotal4,$Ordertotal5,$Ordertotallast);


											$CurrentDate 	= date('Y-m-d');
											
											$TodaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											$TodaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$CurrentDate'");
											
											if(!empty($TodaysTotalOrdersArray))
												$TotalOrdersToday 	= (string)count($TodaysTotalOrdersArray);
											else
												$TotalOrdersToday 	= '0';
		
											
											if(!empty($TodaysOrdersArray))
												$OrdertotalToday 	= $TodaysOrdersArray->RC_Total;
											else
												$OrdertotalToday 	= number_format('0',2,'.','');
		
											$YesterdayDate 	= date('Y-m-d', strtotime('-1 day'));
											$YesterdaysOrdersArray =  $this->Cart_model->getRestaurantsCartTotal(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'4','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											$YesterdaysTotalOrdersArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_AssignedTo'=>$data['Val_Deliveryboy'],'RC_OrderStatus'=>'1','RC_Status'=>'3')," DATE(RC_BookedDttm) = '$YesterdayDate'");
											
											if(!empty($YesterdaysTotalOrdersArray))
												$TotalOrdersYesterday 	= (string)count($YesterdaysTotalOrdersArray);
											else
												$TotalOrdersYesterday 	= '0';
		
											
											if(!empty($YesterdaysOrdersArray))
												$OrdertotalYesterday 	= $YesterdaysOrdersArray->RC_Total;
											else
												$OrdertotalYesterday 	= number_format('0',2,'.','');
		
											
											
											
											
											$Records['GraphRecords']['AxisValues'] 			= $AxisValues;
											$Records['GraphRecords']['PointValues'] 		= $PointValues;
											$Records['GraphRecords']['TodaysRevenue'] 		= 'Rs. '.$OrdertotalToday;
											$Records['GraphRecords']['TodaysOrders'] 		= $TotalOrdersToday;
											$Records['GraphRecords']['YesterdaysRevenue'] 	= 'Rs. '.$OrdertotalYesterday;
											$Records['GraphRecords']['YesterdaysOrders'] 	= $TotalOrdersYesterday;
											$Records['GraphRecords']['TotalSales'] 			= 'Rs. 1234.00';
									
										} 
*/										
										$MiscRecords = array( 
											'NotificationCount' => "1",
										);	
						
										$Records['MiscData'] 					= $MiscRecords;
										$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Revenue Data Fetched','data'=>$Records);	
										
	
	
										
									}
								else
									{
										$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
									}	
							}
						else
							{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
							}
						
						
					}
				else
					{
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing...','data'=>(object)$Records);	
					}	
					
			} else if(!empty($data['Action']) && $data['Action'] == 'AddToCart'){
	
			if( !empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Option']) && !empty($data['Val_Address']) && !empty($data['Val_Date']) && !empty($data['Val_Timeslab']) )	
				{
					
					
					$DeliveryBoyData 	= $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					//print_r($DeliveryBoyData);
					$DeliveryBoyFullName 	= getStringValue($DeliveryBoyData->C_FirstName)." ".getStringValue($DeliveryBoyData->C_LastName);
					$DeliveryBoyAddress 	= array(getStringValue($DeliveryBoyData->C_Address),getStringValue($DeliveryBoyData->C_Location));
					
					$Options 			= $data['Val_Option'];
					$OptionsArray 		= json_decode($Options);
					$CTotal 			= 0;
					foreach($OptionsArray as $Option){
						$OptionsData 	=	$this->Services_model->getOptions($Option);	

						$ServiceID 		= $OptionsData->O_ServiceID;
						$ServiceData 	= $this->Services_model->get($ServiceID);	
						$ServiceName	= $ServiceData->S_Name;

						$PackageID[] 	= $OptionsData->O_PackageID;
						$PackageData 	= $this->Services_model->getPackages($OptionsData->O_PackageID);	
						$PackageNames[]	= $PackageData->P_Title;
						
						$OptionID[] 	= $OptionsData->SPOptionID;
						$OptionNames[]	= $OptionsData->O_Title;
						$OptionPrices[]	= $OptionsData->O_Price;
						$CTotal			= $CTotal + $OptionsData->O_Price; 
					}	
					
					$TimeslabData 		= $this->Services_model->getTimeslabs($data['Val_Timeslab']);	
					$TimeslabTitle 		= $TimeslabData->T_Title;
					
					$PostData['Val_Type']				=  $data['Val_Type'];
					$PostData['Val_Deliveryboy']			=  $data['Val_Deliveryboy'];
					$PostData['Val_Cvendorname']		=  $DeliveryBoyFullName;
					$PostData['Val_Cvendoraddress']	=  json_encode($DeliveryBoyAddress);
					$PostData['Val_Service']			=  $ServiceID;
					$PostData['Val_Cservicenames']		=  $ServiceName;
					$PostData['Val_Package']			=  json_encode($PackageID);
					$PostData['Val_Cpackagenames']		=  json_encode($PackageNames);
					
					
					$PostData['Val_Option']				=  json_encode($OptionID);
					$PostData['Val_Coptionnames']		=  json_encode($OptionNames);
					$PostData['Val_Coptionprices']		=  json_encode($OptionPrices);
					$PostData['Val_Cdate']				=  $data['Val_Date'];
					$PostData['Val_Ctimeslab']			=  $data['Val_Timeslab'];
					$PostData['Val_Ctimeslabtitle']		=  $TimeslabTitle;
					$PostData['Val_Ctotal']				=  number_format($CTotal,2,'.','');
					
					$success = $this->Cart_model->add($PostData);		
			
					if ($success) {
							$CartID		= $success;
							$CartData	= $this->Cart_model->get($CartID);
							
							
							$OptionsCount = "0";
							$OptionsData  = array();
//							$OptionNames[] = $OptionsData->O_Title;
							$Index = 0;
							foreach($OptionNames as $Option)
								{
									
									$OptionsData[] = array(
													'Title'			=>$PackageNames[$Index],
													'Description'	=>$Option,
													'Currency'		=>"Rs. ",
													'Price'			=>$OptionPrices[$Index],
													);
									$Index++;					
									}
							$OptionsCount = (string)count($OptionsData);
							$Record = array(  
										'CartID' 		=> getStringValue($CartData->CartID),
										'OptionsCount' 	=> $OptionsCount,
										'OptionsData' 	=> $OptionsData,
										'Currency'		=>"Rs. ",
										'CartTotal' 	=> $CartData->C_Total,
								//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								//		'Status'=> getStatus($DeliveryBoyData->C_Status),
									);		
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Created Successfully','data'=>$Record);	
		
							
						} 
					else if ($success == false) {
							$data['Val_ProfileImage'] = '';
							$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
					} else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
					}

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Product']) )	
				{
					
					$ExistingCartArray	=  $this->Cart_model->getProductsCart(NULL,array('PC_Status <>'=>'3','PC_Status <>'=>'4','PC_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					
					if(!empty($ExistingCartArray) && count($ExistingCartArray) == '1')
						{
							$ExistingCartData = (object)$ExistingCartArray[0];
						
							//echo "Exist";
							$ProductVal 		= $data['Val_Product'];
							$Products 			= $ExistingCartData->PC_ProductID;
							
							$ProductsArray 		= json_decode($Products);
							$PricesArray 		= json_decode($ExistingCartData->PC_Prices);
							$CTotal 			= 0;
							$ProductsTotal		= 0;
							$DeliveryCharges 	= $ExistingCartData->PC_DeliveryCharge ;
							
						
							if(!empty($ProductsArray)){
							
							
								foreach($ProductsArray as $Key => $Product){
									$ProductsData 	=	$this->Products_model->get($Product);	
			
									$ProductID[] 	= $ProductsData->ProductID;
									$ProductNames[]	= $ProductsData->P_Name;

									$ProductPrices[] = $PricesArray[$Key];
									
									$ProductsTotal 	= $ProductsTotal + $PricesArray[$Key];
									
								}
							} else {
								$ProductsTotal 		= $ExistingCartData->PC_ItemTotal ;
							}
							


							if(!in_array($ProductVal,$ProductID,true))
								{
									$ProductsData 		=	$this->Products_model->get($ProductVal);	
			
									$ProductID[] 		= $ProductsData->ProductID;
									$ProductNames[]		= $ProductsData->P_Name;
									$ProductPrices[]	= $ProductsData->P_Price;
									
									$ProductsTotal 		= $ProductsTotal + $ProductsData->P_Price;

									
									$CartTotal			= $ProductsTotal + $DeliveryCharges; 
								
									$PostData['Val_Product']			=  json_encode($ProductID);
									$PostData['Val_PCproductnames']		=  json_encode($ProductNames);
									$PostData['Val_PCprices']			=  json_encode($ProductPrices);
									$PostData['Val_PCitemtotal']		=  number_format($ProductsTotal,2,'.','');
									$PostData['Val_PCcarttotal']		=  number_format($CartTotal,2,'.','');
									$PostData['Val_PCtotal']			=  number_format($CartTotal,2,'.','');
									$PostData['Val_PCstatus']			=  '2';

			//						$PostData['Val_PCdetail']			=  json_encode(array());
			//						$PostData['Val_Address']			=  "";
				//					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
				//					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
									
									$CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,$ExistingCartData->PCartID);		
									
									if($CartAddProductStatus) {
											
											$ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
											$DetailID = $ProductsDetailsArray;
											$PostDetailData['Val_Cart']			= $ExistingCartData->PCartID;
											$PostDetailData['Val_Product']		= $ProductVal;
											$PostDetailData['Val_PDquantity']	= '1';
											$PostDetailData['Val_Attribute']	= getStringValue($data['Val_Attribute']);
											$PostDetailData['Val_Attribvalue']	= getStringValue($data['Val_Attribvalue']);
											$CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);		
											$DetailID[] = (string)$CartDetailsSuccess;
											
											
											$UpdatePostData['Val_PCdetail']			= json_encode($DetailID);
											$CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,$ExistingCartData->PCartID);		
									}		
									$CartData	= $this->Cart_model->getProductsCart($ExistingCartData->PCartID);
									
									$ProductsCount = "0";
									$ProductsData  = array();
									$ProductsDetailsArray = json_decode($CartData->PC_DetailID);
									$Index = 0;
	
									foreach($ProductsDetailsArray as $ProductDetail)
										{
											
											$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
											
											$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
											if(!empty($ProductDetailData->PD_AttributeID))
												{
													$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
													$AttributeTitle			= $ProductAttributeData->A_Title;
												}	
											else
												{
													$AttributeTitle			= "";
												}	
											if(!empty($ProductDetailData->PD_AttribValueID))
												{
													//print_r($ProductDetailData->PD_AttributeID);
													$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
													
													$AttributeValueTitle	= $ProductAttribValueData->V_Title;
												}
											else
												{
													$AttributeValueTitle	= "";
												}	
											
											
											
											$FeaturedImage = '';	
											$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
											$ProductsData[] = array(
															'DetailID'		=> $ProductDetailData->CPDetailID,
															'ProductID'		=> $ProductDetailData->PD_ProductID,
															'Title'			=> $ProductData->P_Name,
															'Attribute'		=> $AttributeTitle,
															'AttributeValue'=> $AttributeValueTitle,
															'Currency'		=> "Rs. ",
															'Price'			=> $ProductData->P_Price,
															'Quantity'		=> $ProductDetailData->PD_Quantity,
															'FeaturedImage'	=> $FeaturedImage,
															);
											$Index++;					
											}
									$ProductsCount = (string)count($ProductsData);
									$Record = array(  
												'CartID' 			=> getStringValue($CartData->PCartID),
												'ProductsCount' 		=> $ProductsCount,
												'ProductsData' 		=> $ProductsData,
												'Currency'			=>"Rs. ",
												'ItemTotal'			=> $CartData->PC_ItemTotal,
												'DeliveryCharges'	=> $CartData->PC_DeliveryCharge,
												'CartTotal'			=> $CartData->PC_CartTotal,
											);	
		
								$result = array('status'=>'success','flag'=>'1','message'=>'Product Added Successfully','data'=>$Record);
								}
							else{
							
								$CartID		= $ExistingCartData->PCartID;								
								$CartData	= $this->Cart_model->getProductsCart($CartID);
								
								$ProductsCount = "0";
								$ProductsData  = array();
								$ProductsDetailsArray = json_decode($CartData->PC_DetailID);
								$Index = 0;

								foreach($ProductsDetailsArray as $ProductDetail)
									{
										
										$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
										
										$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
										if(!empty($ProductDetailData->PD_AttributeID))
											{
												$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
												$AttributeTitle			= $ProductAttributeData->A_Title;
											}	
										else
											{
												$AttributeTitle			= "";
											}	
										if(!empty($ProductDetailData->PD_AttribValueID))
											{
												$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
												$AttributeValueTitle	= $ProductAttribValueData->V_Title;
											}
										else
											{
												$AttributeValueTitle	= "";
											}
										
										
										$FeaturedImage = '';	
										$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
										$ProductsData[] = array(
														'DetailID'		=> $ProductDetailData->CPDetailID,
														'ProductID'		=> $ProductDetailData->PD_ProductID,
														'Title'			=> $ProductData->P_Name,
														'Attribute'		=> $AttributeTitle,
														'AttributeValue'=> $AttributeValueTitle,
														'Currency'		=> "Rs. ",
														'Price'			=> $ProductData->P_Price,
														'Quantity'		=> $ProductDetailData->PD_Quantity,
														'FeaturedImage'	=> $FeaturedImage,
														);
										$Index++;					
										}
								$ProductsCount = (string)count($ProductsData);
								$Record = array(  
											'CartID' 			=> getStringValue($CartData->PCartID),
											'ProductsCount' 		=> $ProductsCount,
											'ProductsData' 		=> $ProductsData,
											'Currency'			=>"Rs. ",
											'ItemTotal'			=> $CartData->PC_ItemTotal,
											'DeliveryCharges'	=> $CartData->PC_DeliveryCharge,
											'CartTotal'			=> $CartData->PC_CartTotal,
									//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
									//		'Status'=> getStatus($DeliveryBoyData->C_Status),
										);
							
								$result = array('status'=>'success','flag'=>'1','message'=>'Product Added Successfully','data'=>$Record);	
							}	
						}
					else
						{
							
							
							$DeliveryBoyData 	= $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
							//print_r($DeliveryBoyData);
							$DeliveryBoyFullName 	= getStringValue($DeliveryBoyData->C_FirstName)." ".getStringValue($DeliveryBoyData->C_LastName);
							$DeliveryBoyAddress 	= array(getStringValue($DeliveryBoyData->C_Address),getStringValue($DeliveryBoyData->C_Location));
							
							$Product 			= $data['Val_Product'];
							//$Products 			= $data['Val_Product'];
							//$ProductsArray 		= json_decode($Products);
							$CTotal 			= 0;
							$ProductsTotal		= 0;
							$DeliveryCharges	= 15.00;
		//					if(!empty($ProductsArray)){
		//						foreach($ProductsArray as $Product){
								$ProductsData 	=	$this->Products_model->get($Product);	
		
								$ProductID[] 	= $ProductsData->ProductID;
								$ProductNames[]	= $ProductsData->P_Name;
								$ProductPrices[]= $ProductsData->P_Price;
								
								$ProductsTotal 	= $ProductsTotal + $ProductsData->P_Price;
									
		//						}	
									
								$CartTotal			= $ProductsTotal + $DeliveryCharges; 
								
								$PostData['Val_Deliveryboy']			=  $data['Val_Deliveryboy'];
								$PostData['Val_PCvendorname']		=  $DeliveryBoyFullName;
								$PostData['Val_PCvendoraddress']	=  json_encode($DeliveryBoyAddress);
								$PostData['Val_Product']			=  json_encode($ProductID);
								$PostData['Val_PCproductnames']		=  json_encode($ProductNames);
			
								$PostData['Val_PCdate']				=  date('Y-m-d');
		//						$PostData['Val_PCdetail']			=  json_encode(array());
								$PostData['Val_PCprices']			=  json_encode($ProductPrices);
		//						$PostData['Val_Address']			=  "";
								$PostData['Val_PCitemtotal']		=  number_format($ProductsTotal,2,'.','');
								$PostData['Val_PCdeliverycharges']	=  number_format($DeliveryCharges,2,'.','');
								$PostData['Val_PCcarttotal']		=  number_format($CartTotal,2,'.','');
			//					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
			//					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
								$PostData['Val_PCtotal']			=  number_format($CartTotal,2,'.','');
								
								$success = $this->Cart_model->addCartProducts($PostData);		
						
								if ($success) {
										$CartID		= $success;
										//    private $product_cart_details_data = array('CPDetailID'=>'Val_Cartdetail', 'PD_CartID'=>'Val_Cart','PD_ProductID'=>'Val_Product','PD_Quantity'=>'Val_PDquantity', 'PD_AttributeID'=>'Val_Attribute', 'PD_AttribValueID'=>'Val_Attribvalue', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
			
									//	foreach($ProductsArray as $Product){
											$PostDetailData['Val_Cart']			= $CartID;
											$PostDetailData['Val_Product']		= $Product;
											$PostDetailData['Val_PDquantity']	= '1';
											$PostDetailData['Val_Attribute']	= $data['Val_Attribute'];
											$PostDetailData['Val_Attribvalue']	= $data['Val_Attribvalue'];
											$CartDetailsSuccess = $this->Cart_model->addCartProductsDetails($PostDetailData);		
											$DetailID[] = (string)$CartDetailsSuccess;
										//}
										
										$UpdatePostData['Val_PCdetail']			= json_encode($DetailID);
										$CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,$CartID);		
										
			//							addCartProductsDetails
										$CartData	= $this->Cart_model->getProductsCart($CartID);
										//print_r($CartData);
										
										$ProductsCount = "0";
										$ProductsData  = array();
										$ProductsDetailsArray = json_decode($CartData->PC_DetailID);
										$Index = 0;
		
										foreach($ProductsDetailsArray as $ProductDetail)
											{
												
												$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
												
												$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
												
												if(!empty($ProductDetailData->PD_AttributeID))
													{
														$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
														$AttributeTitle			= $ProductAttributeData->A_Title;
													}	
												else
													{
														$AttributeTitle			= "";
													}	
												if(!empty($ProductDetailData->PD_AttributeID))
													{
														$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
														$AttributeValueTitle	= $ProductAttribValueData->V_Title;
													}
												else
													{
														$AttributeValueTitle	= "";
													}
												
												$FeaturedImage = '';	
												$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
												$ProductsData[] = array(
																'DetailID'		=> $ProductDetailData->CPDetailID,
																'ProductID'		=> $ProductDetailData->PD_ProductID,
																'Title'			=> $ProductData->P_Name,
																'Attribute'		=> $AttributeTitle,
																'AttributeValue'=> $AttributeValueTitle,
																'Currency'		=> "Rs. ",
																'Price'			=> $ProductData->P_Price,
																'Quantity'		=> $ProductDetailData->PD_Quantity,
																'FeaturedImage'	=> $FeaturedImage,
																);
												$Index++;					
												}
										$ProductsCount = (string)count($ProductsData);
										$Record = array(  
													'CartID' 			=> getStringValue($CartData->PCartID),
													'ProductsCount' 	=> $ProductsCount,
													'ProductsData' 		=> $ProductsData,
													'Currency'			=> "Rs. ",
													'ItemTotal'			=> $CartData->PC_ItemTotal,
													'DeliveryCharges'	=> $CartData->PC_DeliveryCharge,
													'CartTotal'			=> $CartData->PC_CartTotal,
											//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
											//		'Status'=> getStatus($DeliveryBoyData->C_Status),
												);		
										
										$result = array('status'=>'success','flag'=>'1','message'=>'Cart Created Successfully','data'=>$Record);	
					
										
									} 
								else if ($success == false) {
										$data['Val_ProfileImage'] = '';
										$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
								} else{
										$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
								}
							//					} else {
//								$result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);	
//						}
							
								
							//echo "No Exist";
						}	
						 
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Restaurant']) && !empty($data['Val_Food']) )	
				{
					
					$ExistingCartArray	=  $this->Cart_model->getRestaurantsCart(NULL,array('RC_Status <>'=>'3','RC_Status <>'=>'4','RC_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					
					if(!empty($ExistingCartArray) && count($ExistingCartArray) == '1')
						{
							//echo "Exist";
							$ExistingCartData = (object)$ExistingCartArray[0];
							$Restaurant 		= $data['Val_Restaurant'];
							$RestaurantData 	=	$this->Restaurants_model->get($Restaurant);
							if(!empty($RestaurantData))
								{							
									if($Restaurant == $ExistingCartData->RC_RestaurantID)
										{
											//echo "Exist2";
											$CTotal 			= 0;
											$FoodsTotal			= 0;
											$DeliveryCharges 	= $ExistingCartData->RC_DeliveryCharge ;
											$FoodPricesArray	= json_decode($ExistingCartData->RC_Prices);
											
											$FoodID				= $data['Val_Food'];
						
											$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails(NULL,array('RD_CartID'=>$ExistingCartData->RCartID,'RD_FoodID'=>$FoodID));
											if(!empty($FoodPricesArray))
												{
													foreach($FoodPricesArray as $Key => $Price){
															$FoodsTotal 	= $FoodsTotal + $Price;
														}
												} else {
													$FoodsTotal 		= $ExistingCartData->PC_ItemTotal ;
												}
											
											if(empty($ExistingCartDetailData)) //if(!in_array($ProductVal,$ProductID,true))
												{
											
													$FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);

													if(!empty($FoodData))
														{
															$FoodPricesArray[]	= $FoodData->F_Price;
															$FoodAmount  	= number_format($FoodData->F_Price,2,'.','');	
															$FoodsTotal 	= $FoodsTotal + $FoodAmount;
														}
													else
														{
															$FoodPricesArray[] 	= "";
															$FoodAmount		= number_format(0,2,'.','');
															$FoodsTotal 	= $FoodsTotal + 0;
														}	
														
														$FoodPricesArray	= array_filter($FoodPricesArray); 
														
														$CartTotal			= $FoodsTotal + $DeliveryCharges; 
														
														$ItemCount			= $ExistingCartData->RC_ItemCount + 1;

														$PostData['Val_RCprices']			=  json_encode($FoodPricesArray);
														$PostData['Val_RCitemcount']		=  getStringValue($ItemCount);
														$PostData['Val_RCitemtotal']		=  number_format($FoodsTotal,2,'.','');
														$PostData['Val_RCcarttotal']		=  number_format($CartTotal,2,'.','');
														$PostData['Val_RCtotal']			=  number_format($CartTotal,2,'.','');
														$PostData['Val_RCstatus']			=  '2';
					
														$CartAddProductStatus = $this->Cart_model->updateCartRestaurants($PostData,$ExistingCartData->RCartID);		
														$RestaurantsDetailsArray = json_decode($ExistingCartData->RC_DetailID);
														$DetailID = $RestaurantsDetailsArray;
														if($CartAddProductStatus) {
																
																
																$PostDetailData['Val_Cart']			= $ExistingCartData->RCartID;
																$PostDetailData['Val_Food']			= $FoodID;
																$PostDetailData['Val_RDquantity']	= '1';
																$PostDetailData['Val_RDprice']		= getStringValue($FoodAmount);
																$CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);		
																$DetailID[] = (string)$CartDetailsSuccess;
																
																
																$UpdatePostData['Val_RCdetail']			= json_encode($DetailID);
																$CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData,$ExistingCartData->RCartID);		
														}		
														
														$CartData	= $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);
														$ItemCount = $CartData->RC_ItemCount;

														$Record = array(  
																	'CartID' 			=> getStringValue($CartData->RCartID),
																	'Currency'			=> "Rs. ",
																	'ItemCount'			=> getStringValue($ItemCount),
																	'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
																	'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
																);	
							
													$result = array('status'=>'success','flag'=>'1','message'=>'Product Added Successfully','data'=>$Record);
														
													//echo 'Empty';
												}
											else{
											
												//Nothing Happens, Just Show Existing Data as it is
												//print_r($ExistingCartDetailData);
											
												$CartID		= $ExistingCartData->RCartID;								
												$CartData	= $this->Cart_model->getRestaurantsCart($CartID);
												$ItemCount = $CartData->RC_ItemCount;	


												$Record = array(  
															'CartID' 			=> getStringValue($CartData->RCartID),
															'Currency'			=> "Rs. ",
															'ItemCount'			=> getStringValue($ItemCount),
															'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
															'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
														);
											
												$result = array('status'=>'success','flag'=>'1','message'=>'Product Added Successfully','data'=>$Record);	
											
											}	
										}
									else
										{
											//Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
											$CartID		= $ExistingCartData->RCartID;								
											$CartData	= $this->Cart_model->getRestaurantsCart($CartID);
											
											$ItemCount = $CartData->RC_ItemCount;	

											$Record = array(  
														'CartID' 			=> getStringValue($CartData->RCartID),
														'Currency'			=> "Rs. ",
														'ItemCount'			=> getStringValue($ItemCount),
														'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
														'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
													);
										
											$result = array('status'=>'warning','flag'=>'3','message'=>'You cannot add items from this restaurant as your cart contains items from another restaurant.','data'=>$Record);	
										
										}	
								}
							else{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());
							}
						//echo "exist";	
						}
					else
						{
							
							$DeliveryBoyData 	= $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
							
							$DeliveryBoyFullName 	= getStringValue($DeliveryBoyData->C_FirstName)." ".getStringValue($DeliveryBoyData->C_LastName);
							$DeliveryBoyAddress 	= array(getStringValue($DeliveryBoyData->C_Address),getStringValue($DeliveryBoyData->C_Location));
							
							
							$Restaurant 			= $data['Val_Restaurant'];
							//$Products 			= $data['Val_Product'];
							//$ProductsArray 		= json_decode($Products);
							$CTotal 			= 0;
							$ProductsTotal		= 0;
							$DeliveryCharges	= 15.00;
							$RestaurantData 	=	$this->Restaurants_model->get($Restaurant);	
							if(!empty($RestaurantData))
								{
									$RestaurantID 		= $RestaurantData->RestaurantID;
									$RestaurantName	= $RestaurantData->R_Name;

									$FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);
									
									if(!empty($FoodData))
										{
											$ProductPrices[]= $FoodData->F_Price;
											$FoodAmount  	= number_format($FoodData->F_Price,2,'.','');	
											$ProductsTotal 	= $ProductsTotal + $FoodAmount;
										}
									else
										{
											$ProductPrices 	= array();
											$FoodAmount		= number_format(0,2,'.','');
											$ProductsTotal 	= $ProductsTotal + 0;
										}	
									
									
										
		
				/*					if(!empty($ProductsArray)){}
				//						foreach($ProductsArray as $Product){
										$RestaurantData 	=	$this->Restaurants_model->get($Restaurant);	
				
										$RestaurantID 	= $RestaurantData->RestaurantID;
										$RestaurantName	= $RestaurantData->R_Name;
										$ProductPrices[]= $RestaurantData->P_Price;
										
										$ProductsTotal 	= $ProductsTotal + $ProductsData->P_Price;
											
				//						}	*/
											
										$CartTotal			= $ProductsTotal + $DeliveryCharges; 
									
										$PostData['Val_Deliveryboy']			=  $data['Val_Deliveryboy'];
										$PostData['Val_RCvendorname']		=  $DeliveryBoyFullName;
										$PostData['Val_RCvendoraddress']	=  json_encode($DeliveryBoyAddress);
										$PostData['Val_Restaurant']			=  $RestaurantID;
										$PostData['Val_RCrestaurantname']	=  $RestaurantName;
					
										$PostData['Val_RCdate']				=  date('Y-m-d');
				//						$PostData['Val_RCdetail']			=  json_encode(array());
										$PostData['Val_RCprices']			=  json_encode($ProductPrices);
				//						$PostData['Val_Address']			=  "";
				
										$PostData['Val_RCitemcount']		=  1;
										$PostData['Val_RCitemtotal']		=  number_format($ProductsTotal,2,'.','');
										$PostData['Val_RCdeliverycharges']	=  number_format($DeliveryCharges,2,'.','');
										$PostData['Val_RCcarttotal']		=  number_format($CartTotal,2,'.','');
					//					$PostData['Val_RCpaymentoption']	=  $TimeslabTitle;
					//					$PostData['Val_RCservicecharge']	=  $TimeslabTitle;
										$PostData['Val_RCtotal']			=  number_format($CartTotal,2,'.','');
							
										$success = $this->Cart_model->addCartRestaurants($PostData);		
								
										if ($success) {
												$CartID		= $success;
		//   private $restaurant_cart_details_data = array('CRDetailID'=>'Val_Cartdetail', 'RD_CartID'=>'','RD_FoodID'=>'','RD_Quantity'=>'', 'RD_Price'=>'', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
		
												$FoodAmount;
											//	foreach($ProductsArray as $Product){
													$PostDetailData['Val_Cart']			= $CartID;
													$PostDetailData['Val_Food']			= $data['Val_Food'];
													$PostDetailData['Val_RDquantity']	= '1';
													$PostDetailData['Val_RDprice']		= $FoodAmount;
													$CartDetailsSuccess = $this->Cart_model->addCartRestaurantsDetails($PostDetailData);		
													$DetailID[] = (string)$CartDetailsSuccess;
												//}
												
												$UpdatePostData['Val_RCdetail']			= json_encode($DetailID);
												$CartUpdateStatus = $this->Cart_model->updateCartRestaurants($UpdatePostData,$CartID);		
												
					//							addCartProductsDetails
												$CartData	= $this->Cart_model->getRestaurantsCart($CartID);
												//print_r($CartData);
												
												$RestaurantsCount = "0";
												$RestaurantsData  = array();
												$RestaurantsDetailsArray = json_decode($CartData->RC_DetailID);
												$Index = 0;
				
												$Record = array(  
															'CartID' 			=> getStringValue($CartData->RCartID),
															'Currency'			=> "Rs. ",
															'ItemCount'			=> getStringValue($CartData->RC_ItemCount),
															'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
															'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
													//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
													//		'Status'=> getStatus($DeliveryBoyData->C_Status),
														);		
												
												$result = array('status'=>'success','flag'=>'1','message'=>'Cart Created Successfully','data'=>$Record);	
							
												
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>(object)array());	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
										}


								}
							else{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());
								}
									
							
							//		{			} else {
//								$result = array('status'=>'error','flag'=>'2','message'=>'Products missing !! ','data'=>$success);	
//						}
							
								
							//echo "No Exist";
						}	
						 
				}
			else 
				{
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		} else if(!empty($data['Action']) && $data['Action'] == 'UpdateCart'){
		
			
			
			if( !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail']) && !empty($data['Val_Quantity']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Product']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getProductsCart($data['Val_Cart']);
					$ExistingCartDetailData	=  $this->Cart_model->getProductsCartDetails($data['Val_Detail']);

					
//					exit;
					
					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData) )
						{
							//$ExistingCartData = (object)$ExistingCartArray[0];
						
							$ProductVal 		= $data['Val_Product'];
							$Products 			= $ExistingCartData->PC_ProductID;
							$ProductsArray 		= json_decode($Products);
							$CTotal 			= 0;
							$ProductsTotal		= 0;
							$DeliveryCharges 	= $ExistingCartData->PC_DeliveryCharge ;
							if(!empty($ProductsArray)){
								foreach($ProductsArray as $Product){
								$ProductsData 	=	$this->Products_model->get($Product);	
		
								if($ProductVal == $Product)
									{
										$ProductPrice = ($ProductsData->P_Price * $data['Val_Quantity']);				
									}
								else
									{
										$ProductPrice = $ProductsData->P_Price;
									}	
								
								$ProductPrices[] = number_format($ProductPrice,2,'.','');//$ProductPrice;
								$ProductsTotal 	= $ProductsTotal + number_format($ProductPrice,2,'.','');
									
								}
							} else {
								$ProductsTotal 		= $ExistingCartData->PC_ItemTotal ;
							}
							
							$CartTotal			= $ProductsTotal + $DeliveryCharges; 
							$PostData['Val_PCprices']			=  json_encode($ProductPrices);
							$PostData['Val_PCitemtotal']		=  number_format($ProductsTotal,2,'.','');
							$PostData['Val_PCcarttotal']		=  number_format($CartTotal,2,'.','');
							$PostData['Val_PCtotal']			=  number_format($CartTotal,2,'.','');
							$PostData['Val_PCstatus']			=  '2';

	//						$PostData['Val_PCdetail']			=  json_encode(array());
	//						$PostData['Val_Address']			=  "";
		//					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
		//					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
							
							$CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,$ExistingCartData->PCartID);		
							if($CartAddProductStatus) {
									
//											$ProductsDetailsArray = json_decode($ExistingCartData->PC_DetailID);
//											$DetailID = $ProductsDetailsArray;

									$PostDetailData['Val_PDquantity']	= $data['Val_Quantity'];
									$CartDetailsSuccess = $this->Cart_model->updateCartProductsDetails($PostDetailData,$data['Val_Detail']);		
//											$DetailID[] = (string)$CartDetailsSuccess;
									
									
//											$UpdatePostData['Val_PCdetail']			= json_encode($DetailID);
//											$CartUpdateStatus = $this->Cart_model->updateCartProducts($UpdatePostData,$ExistingCartData->PCartID);		
							}		

							$CartData	= $this->Cart_model->getProductsCart($ExistingCartData->PCartID);
							
							$ProductsCount = "0";
							$ProductsData  = array();
							$ProductsDetailsArray = json_decode($CartData->PC_DetailID);
							$Index = 0;

							foreach($ProductsDetailsArray as $ProductDetail)
								{
									
									$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
									
									$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
									
									if(!empty($ProductDetailData->PD_AttributeID))
										{
											$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
											$AttributeTitle			= $ProductAttributeData->A_Title;
										}	
									else
										{
											$AttributeTitle			= "";
										}	
									if(!empty($ProductDetailData->PD_AttributeID))
										{
											$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
											$AttributeValueTitle	= $ProductAttribValueData->V_Title;
										}
									else
										{
											$AttributeValueTitle	= "";
										}
									
									
									
									
									$FeaturedImage = '';	
									$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
									$ProductsData[] = array(
													'DetailID'		=> $ProductDetailData->CPDetailID,
													'ProductID'		=> $ProductDetailData->PD_ProductID,
													'Title'			=> $ProductData->P_Name,
													'Attribute'		=> $AttributeTitle,
													'AttributeValue'=> $AttributeValueTitle,
													'Currency'		=> "Rs. ",
													'Price'			=> $ProductData->P_Price,
													'Quantity'		=> $ProductDetailData->PD_Quantity,
													'FeaturedImage'	=> $FeaturedImage,
													);
									$Index++;					
								}
							$ProductsCount = (string)count($ProductsData);
							$Record = array(  
										'CartID' 			=> getStringValue($CartData->PCartID),
										'ProductsCount' 		=> $ProductsCount,
										'ProductsData' 		=> $ProductsData,
										'Currency'			=>"Rs. ",
										'ItemTotal'			=> $CartData->PC_ItemTotal,
										'DeliveryCharges'	=> $CartData->PC_DeliveryCharge,
										'CartTotal'			=> $CartData->PC_CartTotal,
									);	

							$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
							
						}
					else 
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
//							echo "No Exist";
						}	
						 

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Food']) && !empty($data['Val_Quantity']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Restaurant']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
					//$ExistingCartDetailData	=  $this->Cart_model->getProductsCartDetails($data['Val_Detail']);
					$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails(NULL,array('RD_CartID'=>$data['Val_Cart'],'RD_FoodID'=>$data['Val_Food']));
					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1')
						{
							$ExistingCartDetailData = (object)$ExistingCartDetailData[0];
							$RestaurantID 			= $data['Val_Restaurant'];
							$RestaurantData 		=	$this->Restaurants_model->get($RestaurantID);
							
							if(!empty($RestaurantData))
								{
									if($RestaurantID == $ExistingCartData->RC_RestaurantID)
										{
											//echo "Exist2";
											$CTotal 			= 0;
											$FoodsTotal			= 0;
											$DeliveryCharges 	= $ExistingCartData->RC_DeliveryCharge ;
											$FoodPricesArray	= json_decode($ExistingCartData->RC_Prices);
											
											$FoodID				= $data['Val_Food'];
						
											if(!empty($FoodPricesArray))
												{
													foreach($FoodPricesArray as $Key => $Price){
															$FoodsTotal 	= $FoodsTotal + $Price;
														}
												} else {
													$FoodsTotal 		= $ExistingCartData->PC_ItemTotal;
												}

											$FoodData = $this->Restaurants_model->getFoods($data['Val_Food']);
										
											if(!empty($FoodData) )
												{
													$FoodAmount  		= ($FoodData->F_Price * $data['Val_Quantity']);
													$FoodAmount  		= number_format($FoodAmount,2,'.','');	
													//$FoodPricesArray[]	= $FoodAmount;
													//$FoodsTotal 		= $FoodsTotal + $FoodAmount;
												}
											else
												{
													
													$FoodAmount		= number_format(0,2,'.','');
													$FoodPricesArray[] 	= "";
													$FoodsTotal 	= $FoodsTotal + 0;
												}
											
											
											$FoodsDetailsArray = json_decode($ExistingCartData->RC_DetailID);
											if(!empty($FoodsDetailsArray)){
												foreach($FoodsDetailsArray as $Key => $DetailID)
													{
														if($DetailID == $ExistingCartDetailData->CRDetailID)
															{
																$FoodPricesArray[$Key]	= $FoodAmount;
																$FoodsTotal 		= $FoodsTotal - $ExistingCartDetailData->RD_Price;
																$FoodsTotal 		= $FoodsTotal + $FoodAmount;
																}
														}
											}
											
											
											$ExistingCartDetailsItemsArray	=  $this->Cart_model->getRestaurantsCartDetails(NULL,array('RD_CartID'=>$data['Val_Cart'],'RD_FoodID <>'=>$FoodID));
											$ItemCount = 0;
											if(!empty($ExistingCartDetailsItemsArray))
												{
													foreach($ExistingCartDetailsItemsArray as $DetailItemArray)
													{
														$ItemCount = $ItemCount + $DetailItemArray['RD_Quantity'];
													}
												}
											else{
												$ItemCount = $ItemCount + 0;
											}
																						
											$ItemCount = $ItemCount + $data['Val_Quantity'];
											$FoodPricesArray	= array_filter($FoodPricesArray); 
											
											$CartTotal							= $FoodsTotal + $DeliveryCharges; 
											$PostData['Val_RCprices']			= json_encode($FoodPricesArray);
											$PostData['Val_RCitemcount']		= getStringValue($ItemCount);
											$PostData['Val_RCitemtotal']		= number_format($FoodsTotal,2,'.','');
											$PostData['Val_RCcarttotal']		= number_format($CartTotal,2,'.','');
											$PostData['Val_RCtotal']			= number_format($CartTotal,2,'.','');
											$PostData['Val_RCstatus']			= '2';
											
											$CartUpdateProductStatus = $this->Cart_model->updateCartRestaurants($PostData,$ExistingCartData->RCartID);		
											if($CartUpdateProductStatus) {
													$PostDetailData['Val_RDprice']	= $FoodAmount;
													$PostDetailData['Val_RDquantity']	= $data['Val_Quantity'];
													$CartDetailsSuccess = $this->Cart_model->updateCartRestaurantsDetails($PostDetailData,$ExistingCartDetailData->CRDetailID);		
											}		
											$CartData	= $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);

											$Record = array(  
														'CartID' 			=> getStringValue($CartData->RCartID),
														'Currency'			=> "Rs. ",
														'ItemCount'			=> getStringValue($ItemCount),
														'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
														'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
													);	
					
											$result = array('status'=>'success','flag'=>'1','message'=>'Product Update Successfully','data'=>$Record);
												
										}
									else
										{
											//Cannot Add to Cart as Cart Contains Food Items from Another Restaurant, Just Show Existing Data as it is
											$CartID		= $ExistingCartData->RCartID;								
											$CartData	= $this->Cart_model->getRestaurantsCart($CartID);
											
											$FoodDetailsArray = json_decode($CartData->RC_DetailID);
											
											if(!empty($FoodDetailsArray))
												$ItemCount = (string)count($FoodDetailsArray);
											else
												$ItemCount = "0";	

											$Record = array(  
														'CartID' 			=> getStringValue($CartData->RCartID),
														'Currency'			=> "Rs. ",
														'ItemCount'			=> $ItemCount,
														'ItemTotal'			=> $CartData->RC_ItemTotal,
														'CartTotal'			=> $CartData->RC_CartTotal,
													);
										
											$result = array('status'=>'warning','flag'=>'3','message'=>'You cannot add items from this restaurant as your cart contains items from another restaurant.','data'=>$Record);	
										
										}
								}
							else{
								$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());
							}	

						}
					else 
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
//							echo "No Exist";
						}	
						 

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'RemoveCart'){
		
			
			
			if( !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) && !empty($data['Val_Detail'])  && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Product']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getProductsCart($data['Val_Cart']);
					$ExistingCartDetailData	=  $this->Cart_model->getProductsCartDetails($data['Val_Detail']);

					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData) )
						{
							//$ExistingCartData = (object)$ExistingCartArray[0];
						
							$ProductVal 		= $data['Val_Product'];
							$Products 			= $ExistingCartData->PC_ProductID;
							$Prices 			= $ExistingCartData->PC_Prices;
							$ProductsArray 		= json_decode($Products);
							$PricesArray 		= json_decode($Prices);
							$CTotal 			= 0;
							$ProductsTotal		= 0;
							
							$DeliveryCharges 	= $ExistingCartData->PC_DeliveryCharge ;
							if(!empty($ProductsArray)){
								$ProductPrice=0;
								foreach($ProductsArray as $Key => $Product){
								$ProductsData 	=	$this->Products_model->get($Product);	
		
								if($ProductVal == $Product)
									{
										$ProductPrice = 0;
									}
								else
									{
										$ProductID[] 	= $ProductsData->ProductID;

										$ProductNames[]	= $ProductsData->P_Name;
										$ProductPrice = (float)$PricesArray[$Key];
										$ProductPrices[] = number_format($ProductPrice,2,'.','');//$ProductPrice;
									}

								$ProductsTotal 	= $ProductsTotal + number_format($ProductPrice,2,'.','');

								}
							} else {
								$ProductsTotal 		= $ExistingCartData->PC_ItemTotal ;
							}
							
							$DetailIdArray = json_decode($ExistingCartData->PC_DetailID);
							
							
							
							if(in_array($data['Val_Detail'],$DetailIdArray,true)){
								$Key = array_search($data['Val_Detail'],$DetailIdArray,true);
								unset($DetailIdArray[$Key]);
								$DetailIdArray = array_values($DetailIdArray);
							}
							if(!empty($ProductID))
							{
								$CartTotal			= $ProductsTotal + $DeliveryCharges; 
								$PostData['Val_Product']			=  json_encode($ProductID);
								$PostData['Val_PCproductnames']		=  json_encode($ProductNames);
								$PostData['Val_PCprices']			=  json_encode($ProductPrices);
								$PostData['Val_PCitemtotal']		=  number_format($ProductsTotal,2,'.','');
								$PostData['Val_PCcarttotal']		=  number_format($CartTotal,2,'.','');
								$PostData['Val_PCtotal']			=  number_format($CartTotal,2,'.','');
								$PostData['Val_PCstatus']			=  '2';
								$PostData['Val_PCdetail']		= json_encode($DetailIdArray);
		//						$PostData['Val_PCdetail']			=  json_encode(array());
		//						$PostData['Val_Address']			=  "";
			//					$PostData['Val_PCpaymentoption']	=  $TimeslabTitle;
			//					$PostData['Val_PCservicecharge']	=  $TimeslabTitle;
								$CartAddProductStatus = $this->Cart_model->updateCartProducts($PostData,$ExistingCartData->PCartID);		
								if($CartAddProductStatus) {
									$CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);		
								}	
								$CartData	= $this->Cart_model->getProductsCart($ExistingCartData->PCartID);
							
								$ProductsCount = "0";
								$ProductsData  = array();
								$ProductsDetailsArray = json_decode($CartData->PC_DetailID);
								$Index = 0;
	
								foreach($ProductsDetailsArray as $ProductDetail)
									{
										
										$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
										
										$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
										if(!empty($ProductDetailData->PD_AttributeID))
											{
												$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
												$AttributeTitle			= $ProductAttributeData->A_Title;
											}	
										else
											{
												$AttributeTitle			= "";
											}	
										if(!empty($ProductDetailData->PD_AttributeID))
											{
												$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
												$AttributeValueTitle	= $ProductAttribValueData->V_Title;
											}
										else
											{
												$AttributeValueTitle	= "";
											}
										$FeaturedImage = '';	
										$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
										$ProductsData[] = array(
														'DetailID'		=> $ProductDetailData->CPDetailID,
														'ProductID'		=> $ProductDetailData->PD_ProductID,
														'Title'			=> $ProductData->P_Name,
														'Attribute'		=> $AttributeTitle,
														'AttributeValue'=> $AttributeValueTitle,
														'Currency'		=> "Rs. ",
														'Price'			=> $ProductData->P_Price,
														'Quantity'		=> $ProductDetailData->PD_Quantity,
														'FeaturedImage'	=> $FeaturedImage,
														);
										$Index++;					
									}
								$ProductsCount = (string)count($ProductsData);
								$Record = array(  
											'CartID' 			=> getStringValue($CartData->PCartID),
											'ProductsCount' 		=> $ProductsCount,
											'ProductsData' 		=> $ProductsData,
											'Currency'			=>"Rs. ",
											'ItemTotal'			=> $CartData->PC_ItemTotal,
											'DeliveryCharges'	=> $CartData->PC_DeliveryCharge,
											'CartTotal'			=> $CartData->PC_CartTotal,
										);	
	
								$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
								
							}
							else{
								$CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);	
								$CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($data['Val_Detail']);		
								$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully','data'=>(object)array());
							}
//								

							
						}
					else if(empty($ExistingCartData) && empty($ExistingCartDetailData) ) { 
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Already Empty.','data'=>(object)array());	
						}	
					else 
						{
//							$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
							$result = array('status'=>'warning','flag'=>'3','message'=>'Product Already removed from cart.','data'=>(object)array());	
//							echo "No Exist";
						}	
						 

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) && !empty($data['Val_Restaurant'])  && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Food']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
					$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails(NULL,array('RD_CartID'=>$data['Val_Cart'],'RD_FoodID'=>$data['Val_Food']));
					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData) && count($ExistingCartDetailData) == '1')
						{
							$ExistingCartDetailData = (object)$ExistingCartDetailData[0];
							
							$DetailIdArray 		= json_decode($ExistingCartData->RC_DetailID);
							$PricesJson			= $ExistingCartData->RC_Prices;
							$PricesArray 		= json_decode($PricesJson);
							$FoodPricesArray	= array();
							$CTotal 			= 0;
							$FoodsTotal			= 0;
							$ItemsCount			= 0;
							$DeliveryCharges 	= $ExistingCartData->RC_DeliveryCharge ;
							$DetailIDs			= array();
							
							if(!empty($DetailIdArray)){
								foreach($DetailIdArray as $Key => $DetailID){
									
									
									$ExistingCartDetailsItemsArrayTemp	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
																				
									if($DetailID == $ExistingCartDetailData->CRDetailID)
										{
											$FoodPrice = 0;
										}
									else
										{
											$DetailIDs[] 	= $DetailID;
											$ItemsCount = $ItemsCount + $ExistingCartDetailsItemsArrayTemp->RD_Quantity;
	
											$FoodPrice = (float)$PricesArray[$Key];
											$FoodPricesArray[] = number_format($FoodPrice,2,'.','');//$ProductPrice;
										}
									$FoodsTotal 	= $FoodsTotal + number_format($FoodPrice,2,'.','');
	
									}
							}
							else {
								$FoodsTotal 		= $ExistingCartData->RC_ItemTotal ;
							}
							
							if(!empty($DetailIDs))
							{ 
								
								//$ItemsCount = $ItemCount + $data['Val_Quantity'];
								$FoodPricesArray	= array_filter($FoodPricesArray); 
								
								$CartTotal							= $FoodsTotal + $DeliveryCharges; 
								$PostData['Val_RCprices']			=  json_encode($FoodPricesArray);
								$PostData['Val_RCitemcount']		= getStringValue($ItemsCount);
								$PostData['Val_RCitemtotal']		=  number_format($FoodsTotal,2,'.','');
								$PostData['Val_RCcarttotal']		=  number_format($CartTotal,2,'.','');
								$PostData['Val_RCtotal']			=  number_format($CartTotal,2,'.','');
								$PostData['Val_RCstatus']			=  '2';
								$PostData['Val_RCdetail']			= json_encode($DetailIDs);
								
								$CartRemoveRestaurantStatus = $this->Cart_model->updateCartRestaurants($PostData,$ExistingCartData->RCartID);
										
								if($CartRemoveRestaurantStatus) {
									$CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($ExistingCartDetailData->CRDetailID);		
								}	
								$CartData	= $this->Cart_model->getRestaurantsCart($ExistingCartData->RCartID);
							
								$Record = array(  
											'CartID' 			=> getStringValue($CartData->RCartID),
											'Currency'			=> "Rs. ",
											'ItemCount'			=> getStringValue($ItemsCount),
											'ItemTotal'			=> getStringValue($CartData->RC_ItemTotal),
											'DeliveryCharges'	=> getStringValue($CartData->RC_DeliveryCharge),
											'CartTotal'			=> getStringValue($CartData->RC_CartTotal),
										);			
								$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
								
							}
							else{
							
								$CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);	
								$CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($ExistingCartDetailData->CRDetailID);		
								$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully','data'=>(object)array());
							}
							
						}
					else if( empty($ExistingCartData) && empty($ExistingCartDetailData) ) { 
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Already Empty.','data'=>(object)array());	
						}	
					else 
						{
//							$result = array('status'=>'success','flag'=>'1','message'=>'Product Updated Successfully','data'=>$Record);
							$result = array('status'=>'warning','flag'=>'3','message'=>'Product Already removed from cart.','data'=>(object)array());	
//							echo "No Exist";
						}	
						 

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'ClearCart'){
		
			
			
			if( !empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Cart']) )	
				{
					$ExistingCartData	=  $this->Cart_model->get($data['Val_Cart']);
					
					if( !empty($ExistingCartData) )
						{
							$CartSuccess = $this->Cart_model->delete($data['Val_Cart']);		
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully.','data'=>(object)array());	
						}
					else 
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Cart Already Cleared.','data'=>(object)array());	
						}	
						 
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Cart']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getProductsCart($data['Val_Cart']);
					$ExistingCartDetailData	=  $this->Cart_model->getProductsCartDetails(NULL,array('PD_CartID'=>$data['Val_Cart']));

					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData) )
						{
							$DetailIdArray = json_decode($ExistingCartData->PC_DetailID);
					
								foreach($DetailIdArray as $ProductDetail)
									{
										$CartDetailsSuccess = $this->Cart_model->deleteCartProductsDetails($ProductDetail);		
									}
								$CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);	

								$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully.','data'=>$Record);
								
						}
					else if(!empty($ExistingCartData) && empty($ExistingCartDetailData) ) { 
							$CartSuccess = $this->Cart_model->deleteCartProducts($data['Val_Cart']);	
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully.','data'=>(object)array());	
						}	
					else{
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Already Cleared.','data'=>(object)array());
						}
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Cart']) )	
				{
					$ExistingCartData	=  $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
					$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails(NULL,array('RD_CartID'=>$data['Val_Cart']));
					if(!empty($ExistingCartData) && !empty($ExistingCartDetailData))
						{
							
							$DetailIdArray 		= json_decode($ExistingCartData->RC_DetailID);
							$DetailIDs			= array();
							
							if(!empty($DetailIdArray)){
								foreach($DetailIdArray as $Key => $DetailID){
										$CartDetailsSuccess = $this->Cart_model->deleteCartRestaurantsDetails($DetailID);		
									}
							}
							
							$CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);	
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully','data'=>(object)array());
						}
					else if( !empty($ExistingCartData) && empty($ExistingCartDetailData) ) { 
							$CartSuccess = $this->Cart_model->deleteCartRestaurants($data['Val_Cart']);	
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Cleared Successfully.','data'=>(object)array());	
						}	
					else 
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Cart Already Empty.','data'=>(object)array());	
						}	
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'CartCheckout'){

			
			if( !empty($data['Val_Type']) && $data['Val_Type'] == 1 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Cart']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal']) )	
				{
					
					$data['Val_Corderstatus'] = '1';
					$data['Val_Cstatus'] = '3';
					$data['Val_Cbookeddttm'] 		= date('Y-m-d H:i:s');
					$success = $this->Cart_model->update($data,$data['Val_Cart']);		
					
					if ($success) {
							$CartData		= $this->Cart_model->get($data['Val_Cart']);
							
							
							/*$OptionsCount 	= 0;
							$OptionsData  	= array();
							$OptionNames 	= json_decode($CartData->C_OptionNames);
							$OptionPrices 	= json_decode($CartData->C_OptionPrices);
							$PackageNames 	= json_decode($CartData->C_PackageNames);
							$Index = 0;
							foreach($OptionNames as $Option)
								{
									
									$OptionsData[] = array(
													'Title'			=>$PackageNames[$Index],
													'Description'	=>$Option,
													'Currency'		=>"Rs. ",
													'Price'			=>$OptionPrices[$Index],
													);
									$Index++;					
									}
							*/
							$TimeslabData = $this->Services_model->getTimeslabs($CartData->C_TimeslabID);		
							
							
							$StartTime 	= date('h:i A',strtotime($CartData->C_Date." ".$TimeslabData->T_StartTime));
							$EndTime 	= date('h:i A',strtotime($CartData->C_Date." ".$TimeslabData->T_EndTime));
							$Date 		= date('D, j M,Y',strtotime($CartData->C_Date));
							$CartTimeText = $StartTime." - ".$EndTime." on ".$Date;
							
							$Record = array(  
										'OrderID' 		=> getStringValue($CartData->CartID),
										//'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
										'TimeText'		=> $CartTimeText,
//										'Currency'		=>"Rs. ",
//										'CartTotal' 	=> $CartData->C_Total,
										
								//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								//		'Status'=> getStatus($DeliveryBoyData->C_Status),
									);		
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Checkout Successfully','data'=>$Record);	
		
							
						} 
					else if ($success == false) {
							$CartData		= $this->Cart_model->get($data['Val_Cart']);
							
							$TimeslabData = $this->Services_model->getTimeslabs($CartData->C_TimeslabID);		
							
							
							$StartTime 	= date('h:i A',strtotime($CartData->C_Date." ".$TimeslabData->T_StartTime));
							$EndTime 	= date('h:i A',strtotime($CartData->C_Date." ".$TimeslabData->T_EndTime));
							$Date 		= date('D, j M,Y',strtotime($CartData->C_Date));
							$CartTimeText = $StartTime." - ".$EndTime." on ".$Date;
							
							$Record = array(  
										'OrderID' 		=> getStringValue($CartData->CartID),
										//'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
										'TimeText'		=> $CartTimeText,
//										'Currency'		=>"Rs. ",
//										'CartTotal' 	=> $CartData->C_Total,
										
								//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								//		'Status'=> getStatus($DeliveryBoyData->C_Status),
									);		
							

							$result = array('status'=>'success','flag'=>'1','message'=>'Cart is already Updated','data'=>$Record);	
					} else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
					}

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 2 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_Cpaymentoption']) && !empty($data['Val_Cservicecharge']) && !empty($data['Val_Ctotal']) )	
				{
					
					unset($data['Val_Type']);
					$data['Val_PCpaymentoption'] 	= $data['Val_Cpaymentoption'];
					$data['Val_PCservicecharge'] 	= $data['Val_Cservicecharge'];
					$data['Val_PCtotal'] 			= $data['Val_Ctotal'];
					$data['Val_PCorderstatus'] 		= '1';
					$data['Val_PCstatus'] 			= '3';
					$data['Val_PCbookeddttm'] 		= date('Y-m-d H:i:s');
					
					$success = $this->Cart_model->updateCartProducts($data,$data['Val_Cart']);		
					
					if ($success) {
							$CartData		= $this->Cart_model->getProductsCart($data['Val_Cart']);
							
							$Record = array(  
										'OrderID' 		=> getStringValue($CartData->PCartID),
										//'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
//										'TimeText'		=> $CartTimeText,
										'Currency'		=> "Rs. ",
										'CartTotal' 	=> $CartData->PC_CartTotal,
										'GrandTotal'	=> $CartData->PC_Total,
										
								//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								//		'Status'=> getStatus($DeliveryBoyData->C_Status),
									);		
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Checkout Successfully','data'=>$Record);	
		
							
						} 
					else if ($success == false) {
					
							$CartData		= $this->Cart_model->getProductsCart($data['Val_Cart']);
							
							$Record = array(  
										'OrderID' 		=> getStringValue($CartData->PCartID),
										//'OptionsCount' 	=> $OptionsCount,
//										'OptionsData' 	=> $OptionsData,
//										'TimeText'		=> $CartTimeText,
										'Currency'		=>"Rs. ",
										'CartTotal' 	=> $CartData->PC_CartTotal,
										'GrandTotal'	=> $CartData->PC_Total,
										
								//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								//		'Status'=> getStatus($DeliveryBoyData->C_Status),
									);		
							

							$result = array('status'=>'success','flag'=>'1','message'=>'Cart is already Updated','data'=>$Record);	
					} else{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
					}

//    private $cart_data = array( 'C_OrderStatus'=>'Val_Corderstatus', 'C_Status'=>'Val_Cstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
					
					
				}
			else if( !empty($data['Val_Type']) && $data['Val_Type'] == 3 && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Cart']) && !empty($data['Val_Address']) && !empty($data['Val_RCpaymentoption']) && !empty($data['Val_RCservicecharge']) && !empty($data['Val_RCtotal']) )	
				{
					unset($data['Val_Type']);
					$data['Val_RCorderstatus'] = '1';
					$data['Val_RCstatus'] = '3';
					$data['Val_RCbookeddttm'] 		= date('Y-m-d H:i:s');
					$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
					if(!empty($CartData))
						{
							$success = $this->Cart_model->updateCartRestaurants($data,$data['Val_Cart']);		
							
							if ($success) {
									$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
									
									$Record = array(  
												'OrderID' 		=> getStringValue($CartData->RCartID),
												'Currency'		=>"Rs. ",
												'OrderTotal' 	=> $CartData->RC_CartTotal,
											);		
									
									$result = array('status'=>'success','flag'=>'1','message'=>'Cart Checkout Successfully','data'=>$Record);	
				
									
								} 
							else if ($success == false) {
							
									$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Cart']);
									
									$Record = array(  
												'OrderID' 		=> getStringValue($CartData->RCartID),
												'Currency'		=>"Rs. ",
												'OrderTotal' 	=> $CartData->RC_CartTotal,
											);		
									
		
									$result = array('status'=>'success','flag'=>'1','message'=>'Cart is already Updated','data'=>$Record);	
							} else{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
							}						
						} else{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
							}
						


				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		

		} else if(!empty($data['Action']) && $data['Action'] == 'GetRestaurantCart'){
		
			
			
			if(  !empty($data['Val_Deliveryboy']) )	
				{
						
					$ExistingRestaurantCartArray =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_OrderStatus'=>'0','RC_Status <>'=>'3','RC_Status <>'=>'4','RC_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					$DetailIDsArray 	= array();
					
					if(!empty($ExistingRestaurantCartArray))
						{
							$ExistingCartData = (object)$ExistingRestaurantCartArray[0];
							
							
							$DetailIDsJson 			= $ExistingCartData->RC_DetailID;
							$DetailIDsArray			= json_decode($DetailIDsJson);
							
							$CartItemsCount = 0;
							$FoodsRecords	= array();
							$FoodsCount 	= 0;
							if(!empty($DetailIDsArray))
								{
									$FoodsRecords	= array();
									$FoodsCount 	= 0;
									foreach($DetailIDsArray as $DetailID)
										{
											$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
											$FoodID = '';
											if(!empty($ExistingCartDetailData))
												{
													$CartItemsCount = $CartItemsCount + $ExistingCartDetailData->RD_Quantity;
													$FoodID 		= $ExistingCartDetailData->RD_FoodID;
													
													$FoodData 		= $this->Restaurants_model->getFoods($FoodID);
													
													$DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodData->RFoodID.'/'.$FoodData->F_DisplayImage : ''); 
													array_push($FoodsRecords,array(
																	'FoodID'=>getStringValue($FoodData->RFoodID),
																	
																	'Title'=>getStringValue($FoodData->F_Title),
																	'Description'=>getStringValue($FoodData->F_Description),
																	'Currency'=>getStringValue("Rs. "),
																	'Price'=>getStringValue($FoodData->F_Price),
																	'DisplayImage'=>getStringValue($DisplayImage),
																	'Type'=>getStringValue($FoodData->F_Type),
																	'CartQuantity'=>$ExistingCartDetailData->RD_Quantity
																	)
																);
													
												}	
											else{
													$CartItemsCount = $CartItemsCount + 0;
												}	
										}
									$FoodsCount = (string)count($FoodsRecords);
									if(!empty($ExistingCartData->RC_ItemCount))
											$CartItemsCount 	= $ExistingCartData->RC_ItemCount;
										else 
											$CartItemsCount 	= $CartItemsCount;
			
									$CartItemsTotal 	= number_format($ExistingCartData->RC_ItemTotal,2);
									
								}
							else{
									//echo "Not Matching 3";
									$CartItemsCount = $CartItemsCount + 0;
								}
							
							$Record = array(  
										'CartID' 			=> getStringValue($ExistingCartData->RCartID),
										'FoodsCount'		=> $FoodsCount,
										'FoodsData' 		=> $FoodsRecords,
										'Currency' 			=> "Rs. ",
										'ItemsCount' 		=> $CartItemsCount,
										'ItemTotal' 		=> $ExistingCartData->RC_ItemTotal,
										'DeliveryCharges' 	=> $ExistingCartData->RC_DeliveryCharge,
										'CartTotal' 		=> $ExistingCartData->RC_CartTotal,

									);
							$result = array('status'=>'success','flag'=>'1','message'=>'Cart Fetched Successfully.','data'=>$Record);	
						}
					else
						{
							$result = array('status'=>'error','flag'=>'2','message'=>'Cart is empty','data'=>(object)array());
						}	
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'GetCartDetails'){
		
			
			
			if( !empty($data['Val_Deliveryboy']) )	
				{
						
					$ExistingServiceCartArray		=  $this->Cart_model->get(NULL,array('C_OrderStatus'=>'0','C_Status <>'=>'3','C_Status <>'=>'4','C_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					$ExistingProductCartArray		=  $this->Cart_model->getProductsCart(NULL,array('PC_OrderStatus'=>'0','PC_Status <>'=>'3','PC_Status <>'=>'4','PC_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					$ExistingRestaurantCartArray	=  $this->Cart_model->getRestaurantsCart(NULL,array('RC_OrderStatus'=>'0','RC_Status <>'=>'3','RC_Status <>'=>'4','RC_DeliveryBoyID'=>$data['Val_Deliveryboy']));
					
					$ServicesCart 	= '1';
					$ServicesRecords 	= (object)array();
					if(!empty($ExistingServiceCartArray))
						{
							$ExistingServiceCartData 	= (object)$ExistingServiceCartArray[0];
							$CartID						= $ExistingServiceCartArray;
							
							$OptionsCount 				= "0";
							$OptionsData 				= array();
							$OptionNames 				= json_decode($ExistingServiceCartData->C_OptionNames);
							$PackageNames 				= json_decode($ExistingServiceCartData->C_PackageNames);
							$OptionPrices 				= json_decode($ExistingServiceCartData->C_OptionPrices);
							
							$Index = 0;
							foreach($OptionNames as $Option)
								{
									
									$OptionsData[] = array(
													'Title'			=>$PackageNames[$Index],
													'Description'	=>$Option,
													'Currency'		=>"Rs. ",
													'Price'			=>$OptionPrices[$Index],
													);
									$Index++;					
									}
							$OptionsCount = (string)count($OptionsData);
							$ServicesRecords = array(  
										'CartID' 		=> getStringValue($ExistingServiceCartData->CartID),
										'OptionsCount' 	=> $OptionsCount,
										'OptionsData' 	=> $OptionsData,
										'Currency'		=>"Rs. ",
										'CartTotal' 	=> $ExistingServiceCartData->C_Total,
									);	
							$ServicesCart = '2';			
						}
					$ProductsCart 	= '1';
					$ProductsRecords 	= (object)array();
					$ProductsCartItemsCount	= 0;
					if(!empty($ExistingProductCartArray))
						{
							$ExistingProductCartData 	= (object)$ExistingProductCartArray[0];
							$CartID						= $ExistingProductCartData->PCartID;								
								
							$ProductsCount = "0";
							$ProductsData  = array();
							$ProductsDetailsArray = json_decode($ExistingProductCartData->PC_DetailID);
							$Index = 0;
							if(!empty($ProductsDetailsArray))
								{
									foreach($ProductsDetailsArray as $ProductDetail)
										{
									
											$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
											
											$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
											if(!empty($ProductDetailData->PD_AttributeID))
												{
													$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
													$AttributeTitle			= $ProductAttributeData->A_Title;
												}	
											else
												{
													$AttributeTitle			= "";
												}	
											if(!empty($ProductDetailData->PD_AttribValueID))
												{
													$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
													$AttributeValueTitle	= $ProductAttribValueData->V_Title;
												}
											else
												{
													$AttributeValueTitle	= "";
												}
											
											
											$FeaturedImage = '';	
											$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
											$ProductsData[] = array(
															'DetailID'		=> $ProductDetailData->CPDetailID,
															'ProductID'		=> $ProductDetailData->PD_ProductID,
															'Title'			=> $ProductData->P_Name,
															'Attribute'		=> $AttributeTitle,
															'AttributeValue'=> $AttributeValueTitle,
															'Currency'		=> "Rs. ",
															'Price'			=> $ProductData->P_Price,
															'Quantity'		=> $ProductDetailData->PD_Quantity,
															'FeaturedImage'	=> $FeaturedImage,
															);
											$ProductsCartItemsCount = $ProductsCartItemsCount + $ProductDetailData->PD_Quantity;				
															
											$Index++;					
										}
								}
							else{
									//echo "Not Matching 3";
									$ProductsCartItemsCount = $ProductsCartItemsCount + 0;
								}
								$ProductsCount 		= (string)count($ProductsData);
								$ProductsRecords 	= array(  
											'CartID' 			=> getStringValue($CartID),
											'ProductsCount' 	=> $ProductsCount,
											'ProductsData' 		=> $ProductsData,
											'Currency'			=>"Rs. ",
											'ItemsCount' 		=> $ProductsCartItemsCount,
											'ItemTotal'			=> $ExistingProductCartData->PC_ItemTotal,
											'DeliveryCharges'	=> $ExistingProductCartData->PC_DeliveryCharge,
											'CartTotal'			=> $ExistingProductCartData->PC_CartTotal,
									
										);
							$ProductsCart 	= '2';
						}

					$RestaurantsCart 	= '1';
					$RestaurantsRecords 	= (object)array();
					$RestaurantsCartItemsCount	= 0;
					if(!empty($ExistingRestaurantCartArray))
						{
							$ExistingCartData = (object)$ExistingRestaurantCartArray[0];
							
							$DetailIDsJson 			= $ExistingCartData->RC_DetailID;
							$DetailIDsArray			= json_decode($DetailIDsJson);
							
							$RestaurantsCartItemsCount = 0;
							$FoodsRecords	= array();
							$FoodsCount 	= 0;
							if(!empty($DetailIDsArray))
								{
									$FoodsRecords	= array();
									$FoodsCount 	= 0;
									foreach($DetailIDsArray as $DetailID)
										{
											$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
											$FoodID = '';
											if(!empty($ExistingCartDetailData))
												{
													$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + $ExistingCartDetailData->RD_Quantity;
													$FoodID 		= $ExistingCartDetailData->RD_FoodID;
													
													$FoodData 		= $this->Restaurants_model->getFoods($FoodID);
													
													$DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodData->RFoodID.'/'.$FoodData->F_DisplayImage : ''); 
													array_push($FoodsRecords,array(
																	'FoodID'=>getStringValue($FoodData->RFoodID),
																	
																	'Title'=>getStringValue($FoodData->F_Title),
																	'Description'=>getStringValue($FoodData->F_Description),
																	'Currency'=>getStringValue("Rs. "),
																	'Price'=>getStringValue($FoodData->F_Price),
																	'DisplayImage'=>getStringValue($DisplayImage),
																	'Type'=>getStringValue($FoodData->F_Type),
																	'CartQuantity'=>$ExistingCartDetailData->RD_Quantity
																	)
																);
													
												}	
											else{
													$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
												}	
										}
									$FoodsCount = (string)count($FoodsRecords);
									if(!empty($ExistingCartData->RC_ItemCount))
											$RestaurantsCartItemsCount 	= $ExistingCartData->RC_ItemCount;
										else 
											$RestaurantsCartItemsCount 	= $RestaurantsCartItemsCount;
			
									$RestaurantsCartItemsCount 	= (string)$RestaurantsCartItemsCount;
									
								}
							else{
									//echo "Not Matching 3";
									$RestaurantsCartItemsCount = $RestaurantsCartItemsCount + 0;
								}
							
							$RestaurantsRecords = array(  
										'CartID' 			=> getStringValue($ExistingCartData->RCartID),
										'RestaurantID' 		=> getStringValue($ExistingCartData->RC_RestaurantID),
										'FoodsCount'		=> $FoodsCount,
										'FoodsData' 		=> $FoodsRecords,
										'Currency' 			=> "Rs. ",
										'ItemsCount' 		=> $RestaurantsCartItemsCount,
										'ItemTotal' 		=> $ExistingCartData->RC_ItemTotal,
										'DeliveryCharges' 	=> $ExistingCartData->RC_DeliveryCharge,
										'CartTotal' 		=> $ExistingCartData->RC_CartTotal,
									);
							$RestaurantsCart 	= '2';		
						}

					$CartData['ServicesCart'] 			= $ServicesCart; 
					$CartData['ServicesCartData'] 		= $ServicesRecords; 
					$CartData['ProductsCart'] 			= $ProductsCart; 
					$CartData['ProductsCartData'] 		= $ProductsRecords; 
					$CartData['RestaurantsCart'] 		= $RestaurantsCart; 
					$CartData['RestaurantsCartData']	= $RestaurantsRecords; 
					$result = array('status'=>'success','flag'=>'1','message'=>'Cart Fetched Successfully','data'=>$CartData);	

					
						
					//print_r($ExistingServiceCartArray);
					//print_r($ExistingProductCartArray);
					//print_r($ExistingRestaurantCartArray);
				//	exit;
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'GetOrders'){
		
			if( !empty($data['Val_Deliveryboy']) )	
				{
					$OngoingOrderRecords 		= array();
					$OngoingOrderRecordsCount 	= (string)count($OngoingOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							// $OngoingOrdersArrayProduct =  $this->Cart_model->getProductsCart(NULL,array('PC_DeliveryBy'=>$data['Val_Deliveryboy'],'PC_Status'=>'3'),"( PC_DeliveryByStatus <> '4' OR PC_DeliveryByStatus <> '0') AND PC_OrderStatus = '3'");
							// $OngoingOrdersArrayRestaurant =  $this->Cart_model->getRestaurantsCart(NULL,array('RC_DeliveryBy'=>$data['Val_Deliveryboy'],'RC_Status'=>'3'),"( RC_DeliveryByStatus <> '4' OR RC_DeliveryByStatus <> '0') AND RC_OrderStatus = '3'");
							$OngoingOrdersArrayProduct =  $this->Cart_model->getDProductsOngoingOrders($data['Val_Deliveryboy']);
							$OngoingOrdersArrayRestaurant =  $this->Cart_model->getDRestaurantsOngoingOrders($data['Val_Deliveryboy']);
									
									if(!empty($OngoingOrdersArrayProduct))
										{
											foreach($OngoingOrdersArrayProduct as $OrderArray)
												{	
													$OrderData = (object)$OrderArray; 
													$VendorArray = $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$OrderData->PC_AssignedTo));
													$AboutData 		= (object)$VendorArray[0];
													
													$VendorArray1 = $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$OrderData->PC_AssignedTo));
						
						
													$LocationData 		= (object)$VendorArray1[0];
													
													
													$OrderID		= $OrderData->PCartID;
													$OrderType		= '2';
													$OrderName		= getOrderName('2',$OrderID);
													$OrderDate		= $OrderData->PC_AssignedDttm;
													$OrderTimeAgo	= time_ago($OrderData->PC_AssignedDttm);
													$VendorName		= $AboutData->A_BusinessName;
													$VendorLocation	= $LocationData->L_Location;
													$PaymentMethod	= 'Paytm';
													
													$OrderTotal		= $OrderData->PC_Total;
													$OrderStatus	= $OrderData->PC_DeliveryByStatus;
													$OngoingOrderRecords[] = array(
																		'OrderID' 		=> $OrderID,
																		'OrderType' 	=> $OrderType,
																		'OrderName' 	=> $OrderName,
																		'OrderDate' 	=> $OrderDate,
																		'OrderTimeAgo' 	=> $OrderTimeAgo,
																		'VendorName' 	=> $VendorName,
																		'VendorLocation'=> $VendorLocation,
																		'PaymentMethod' => $PaymentMethod,
																		'Currency'		=> "Rs. ",
																		'OrderStatus'	=> $OrderStatus,
																		'OrderTotal'	=> getStringValue($OrderTotal),
																	);
												}
											 $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
										}
									
									if(!empty($OngoingOrdersArrayRestaurant))
										{
											foreach($OngoingOrdersArrayRestaurant as $OrderArray)
												{	
													$OrderData = (object)$OrderArray; 
												
													if(!empty($OrderData->RCartID)){
														
														$OrderID		= $OrderData->RCartID;
														$OrderType		= '3';
														$OrderName		= getOrderName('3',$OrderID);
														$OrderDate		= $OrderData->RC_AssignedDttm;
														$OrderTimeAgo	= time_ago($OrderData->RC_AssignedDttm);
														$VendorName		= '';
														$VendorLocation	= '';
														$PaymentMethod	= 'Paytm';
														$OrderStatus	= $OrderData->RC_DeliveryByStatus;
														$OrderTotal		= $OrderData->RC_Total;
													}

													$OngoingOrderRecords[] = array(
																		'OrderID' 		=> $OrderID,
																		'OrderType' 	=> $OrderType,
																		'OrderName' 	=> $OrderName,
																		'OrderDate' 	=> $OrderDate,
																		'OrderTimeAgo' 	=> $OrderTimeAgo,
																		'VendorName' 	=> $VendorName,
																		'VendorLocation'=> $VendorLocation,
																		'PaymentMethod' => $PaymentMethod,
																		'Currency'		=> "Rs. ",
																		'OrderStatus'	=> $OrderStatus,
																		'OrderTotal'	=> getStringValue($OrderTotal),
																	);
												}
											 $OngoingOrderRecordsCount = (string)count($OngoingOrderRecords);
										}
										
										
									$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
										
									$Records['OngoingOrdersCount']	= $OngoingOrderRecordsCount;
									$Records['OngoingOrders']		= $OngoingOrderRecords;
									$Records['MiscData'] 			= $MiscRecords;
									$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Orders Data Fetched','data'=>$Records);	
									


									
								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'GetOrderHistory'){
		
			if( !empty($data['Val_Deliveryboy']) )	
				{
					$PastOrderRecords 			= array();
					$PastOrderRecordsCount 		= (string)count($PastOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							
							$PastOrdersArrayProduct =  $this->Cart_model->getDProductsOrdersHistory($data['Val_Deliveryboy']);

							$PastOrdersArrayRestaurant =  $this->Cart_model->getDRestaurantsOrdersHistory($data['Val_Deliveryboy']);
							
							
									
									if(!empty($PastOrdersArrayProduct))
										{
											foreach($PastOrdersArrayProduct as $OrderArray)
												{	
													$OrderData = (object)$OrderArray; 
													$VendorArray = $this->Vendors_model->getAbout(NULL,array('A_VendorID'=>$OrderData->PC_AssignedTo));
													$AboutData 		= (object)$VendorArray[0];
													
													$VendorArray1 = $this->Vendors_model->getLocations(NULL,array('L_VendorID'=>$OrderData->PC_AssignedTo));
						
						
													$LocationData 		= (object)$VendorArray1[0];
													
													
													$OrderID		= $OrderData->PCartID;
													$OrderType		= '2';
													$OrderName		= getOrderName('2',$OrderID);
													$OrderDate		= $OrderData->PC_AssignedDttm;
													$OrderTimeAgo	= time_ago($OrderData->PC_AssignedDttm);
													$VendorName		= $AboutData->A_BusinessName;
													$VendorLocation	= $LocationData->L_Location;
													$PaymentMethod	= 'Paytm';
													
													$OrderTotal		= $OrderData->PC_Total;
											
													$PastOrderRecords[] = array(
																		'OrderID' 		=> $OrderID,
																		'OrderType' 	=> $OrderType,
																		'OrderName' 	=> $OrderName,
																		'OrderDate' 	=> $OrderDate,
																		'OrderTimeAgo' 	=> $OrderTimeAgo,
																		'VendorName' 	=> $VendorName,
																		'VendorLocation'=> $VendorLocation,
																		'PaymentMethod' => $PaymentMethod,
																		'Currency'		=> "Rs. ",
																		'OrderTotal'	=> getStringValue($OrderTotal),
																	);
												}
											 $PastOrderRecordsCount = (string)count($PastOrderRecords);
										}
									
									if(!empty($PastOrdersArrayRestaurant))
										{
											foreach($PastOrdersArrayRestaurant as $OrderArray)
												{	
													$OrderData = (object)$OrderArray; 
												
													if(!empty($OrderData->RCartID)){
														
														$OrderID		= $OrderData->RCartID;
														$OrderType		= '3';
														$OrderName		= getOrderName('3',$OrderID);
														$OrderDate		= $OrderData->RC_AssignedDttm;
														$OrderTimeAgo	= time_ago($OrderData->RC_AssignedDttm);
														$VendorName		= '';
														$VendorLocation	= '';
														$PaymentMethod	= 'Paytm';
														$OrderTotal		= $OrderData->RC_Total;
													}

													$OngoingOrderRecords[] = array(
																		'OrderID' 		=> $OrderID,
																		'OrderType' 	=> $OrderType,
																		'OrderName' 	=> $OrderName,
																		'OrderDate' 	=> $OrderDate,
																		'OrderTimeAgo' 	=> $OrderTimeAgo,
																		'VendorName' 	=> $VendorName,
																		'VendorLocation'=> $VendorLocation,
																		'PaymentMethod' => $PaymentMethod,
																		'Currency'		=> "Rs. ",
																		'OrderTotal'	=> getStringValue($OrderTotal),
																	);
												}
											 $PastOrderRecordsCount = (string)count($PastOrderRecords);
										}
										
										
									$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
										
									$Records['PastOrdersCount']		= $PastOrderRecordsCount;
									$Records['PastOrders']			= $PastOrderRecords;	
									$Records['MiscData'] 			= $MiscRecords;
									$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Orders Data Fetched','data'=>$Records);	

								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'GetOrderDetails'){
		
							
			if( !empty($data['Val_Deliveryboy']) && !empty($data['Val_Type'])&& !empty($data['Val_Order']) )	
				{
					
					
					$OngoingOrderRecords 		= array();
					$PastOrderRecords 			= array();
					$PastOrderRecordsCount 		= (string)count($PastOrderRecords);
					$OngoingOrderRecordsCount 	= (string)count($OngoingOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
					
									$BusinessType = $data['Val_Type'];
									if($BusinessType == '2'){
										$OrderData =  $this->Cart_model->getProductsCart($data['Val_Order'],array('PC_DeliveryBy'=>$data['Val_Deliveryboy']));
										

									} else if($BusinessType == '3'){
										$OrderData =  $this->Cart_model->getRestaurantsCart($data['Val_Order'],array('RC_DeliveryBy'=>$data['Val_Deliveryboy']));
									
									}
									
									
									if(!empty($OrderData))
										{


										if(!empty($OrderData->PCartID)){
													
													$OrderID		= $OrderData->PCartID;
													$OrderType		= '2';
													$OrderName		= getOrderName('2',$OrderID);
													$OrderDate		= date('Y-m-d',strtotime($OrderData->PC_BookedDttm));
													$OrderTime		= date('H:i',strtotime($OrderData->PC_BookedDttm));
													$OrderTimeAgo	= time_ago($OrderData->PC_AssignedDttm);
													$Username		= $OrderData->PC_CustomerName;
													$AddressArray	= json_decode($OrderData->PC_CustomerAddress);
													$Mobile			= $AddressArray[0];
													$AddressString	= $AddressArray[1].','.$AddressArray[2];
													$LocationString	= $AddressArray[3].','.$AddressArray[4];
													$Latitude		= $AddressArray[3];
													$Longitude		= $AddressArray[4];
													
													$PaymentMethod	= 'Paytm';
													
													
													$ItemsCount 				= "0";
													$ItemsData 					= array();
													$ProductsDetailsArray = json_decode($OrderData->PC_DetailID);
													$ProductCartItemsCount = "0";
													$Index 	= 1;
													$Key 	= 0; 
													if(!empty($ProductsDetailsArray))
														{
															foreach($ProductsDetailsArray as $ProductDetail)
																{
															
																	$ProductDetailData		= $this->Cart_model->getProductsCartDetails($ProductDetail);
																	
																	$ProductData			= $this->Products_model->get($ProductDetailData->PD_ProductID);
																	if(!empty($ProductDetailData->PD_AttributeID))
																		{
																			$ProductAttributeData	= $this->Products_model->getAttributes($ProductDetailData->PD_AttributeID);
																			$AttributeTitle			= $ProductAttributeData->A_Title;
																		}	
																	else
																		{
																			$AttributeTitle			= "";
																		}	
																	if(!empty($ProductDetailData->PD_AttribValueID))
																		{
																			$ProductAttribValueData	= $this->Products_model->getAttribValues($ProductDetailData->PD_AttribValueID);
																			$AttributeValueTitle	= $ProductAttribValueData->V_Title;
																		}
																	else
																		{
																			$AttributeValueTitle	= "";
																		}
																	
																	
																	$FeaturedImage = '';	
																	$FeaturedImage = (!empty($ProductData->P_FeaturedImage) ? UPLOAD_PRODUCTS_BASE_URL.$ProductData->ProductID.'/'.$ProductData->P_FeaturedImage : '');
																	$ItemsData[] = array(
																					'Index'			=>(string)$Index,
																					'Title'			=> $ProductData->P_Name .' x '. $ProductDetailData->PD_Quantity,
																					'Description'	=> $AttributeTitle . ' : ' . $AttributeValueTitle,
																					'Currency'		=> "Rs. ",
																					'Price'			=> $ProductData->P_Price,
																					);
																	$ProductCartItemsCount = $ProductCartItemsCount + $ProductDetailData->PD_Quantity;				
																					
																	$Index++;					
																}
															$ItemsCount	=	(string)count($ItemsData);
														}
													else{
															//echo "Not Matching 3";
															$ProductCartItemsCount = $ProductCartItemsCount + 0;
														}
													$OrderStatus	= $OrderData->PC_DeliveryByStatus;
													$OrderTotal		= $OrderData->PC_Total;
													
												}
												else if(!empty($OrderData->RCartID)){
													
													$OrderID		= $OrderData->RCartID;
													$OrderType		= '3';
													$OrderName		= getOrderName('3',$OrderID);
													$OrderDate		= date('Y-m-d',strtotime($OrderData->RC_BookedDttm));
													$OrderTime		= date('H:i',strtotime($OrderData->RC_BookedDttm));
													$OrderTimeAgo	= time_ago($OrderData->RC_AssignedDttm);
													$Username		= $OrderData->RC_CustomerName;
													
													$AddressArray	= json_decode($OrderData->RC_CustomerAddress);
													$Mobile			= $AddressArray[0];
													$AddressString	= $AddressArray[1].','.$AddressArray[2];
													$LocationString	= $AddressArray[3].','.$AddressArray[4];
													$Latitude		= $AddressArray[3];
													$Longitude		= $AddressArray[4];
													$PaymentMethod	= 'Paytm';
													$ItemsCount 	= "0";
													$ItemsData		= array();
													$DetailIDsJson 			= $OrderData->RC_DetailID;
													$DetailIDsArray			= json_decode($DetailIDsJson);														
													
													$Index 	= 1;
													if(!empty($DetailIDsArray))
														{
														foreach($DetailIDsArray as $DetailID)
															{
																$ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
																$FoodID = '';
																if(!empty($ExistingCartDetailData))
																	{
																		$FoodID 		= $ExistingCartDetailData->RD_FoodID;
																		$FoodData 		= $this->Restaurants_model->getFoods($FoodID);
																		$DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodData->RFoodID.'/'.$FoodData->F_DisplayImage : ''); 
																		if($FoodData->F_Type == '1')
																			$F_Type = 'Veg.';
																		else if($FoodData->F_Type == '2')
																			$F_Type = 'Non Veg.';
																		array_push($ItemsData,array(
																						'Index'=>(string)$Index,
																						'Title'=>getStringValue('('.$F_Type.')'.$FoodData->F_Title . ' x ' . $ExistingCartDetailData->RD_Quantity  ),
																						'Description'=>getStringValue($FoodData->F_Description),
																						'Currency'=>getStringValue("Rs. "),
																						'Price'=>getStringValue($FoodData->F_Price),

																						)
																					);
																		
																	}	
																	$Index++;
															}
														$ItemsCount = (string)count($ItemsData);
														
													}

													
													$OrderStatus	= $OrderData->RC_DeliveryByStatus;
													$OrderTotal		= $OrderData->RC_Total;
														
												}

													$Record = array(
																		'OrderID' 		=> $OrderID,
																		'OrderType' 	=> $OrderType,
																		'OrderName' 	=> $OrderName,
																		'OrderDate' 	=> $OrderDate,
																		'OrderTime' 	=> $OrderTime,
																		'OrderTimeAgo' 	=> $OrderTimeAgo,
																		'Username' 		=> $Username,
																		'Mobile' 		=> $Mobile,
																		'Address' 		=> $AddressString,
																		'Location' 		=> $LocationString,
																		'Latitude' 		=> $Latitude,
																		'Longitude' 	=> $Longitude,
																		'ItemsCount' 	=> $ItemsCount,
																		'PaymentMethod'	=> $PaymentMethod,
																		'OrderStatus'	=> getStringValue($OrderStatus),
																		'Currency'		=> "Rs. ",
																		'OrderTotal'	=> getStringValue($OrderTotal),
																		
																	);
									
											$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Orders Data Fetched','data'=>$Record);	
										
									
									


									
								}
							else
								{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
								}	
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
								
				
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'AcceptOrder'){

			if( !empty($data['Val_Type']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Order']) )	
				{
					/* Niranjan code Start for update the livetracking table */
				$order	= $data['Val_Order'];
				$type   =	$data['Val_Type'];

					$locationArray = array(
						'curr_lat'=>$data['Val_Lat'],
						'curr_lng'=>$data['Val_Lng']
					);

					$whatthehell = $this->LiveTracking_model->updateLocation($locationArray,$order,$type);

					/* Niranjan code End or update the livetracking table */

					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							
							$BusinessType = $data['Val_Type'];
							unset($data['Val_Type']);

							if($BusinessType == '2'){
										$OrderData =  $this->Cart_model->getProductsCart($data['Val_Order']);
										$Order= (object)$OrderData[0];
										$data['Val_Deliveryby']= $data['Val_Deliveryboy'];
								
										if(!empty($OrderData))
										{
											$data['Val_PCdeliverybystatus'] 		= '2';
											
											$success = $this->Cart_model->updateCartProducts($data,$data['Val_Order']);		
											
											if ($success) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
															$CustomerToken = $this->Notifications_model->getToken($Order->PC_CustomerID, 1);
															$CustomerToken= (object)$CustomerToken[0];
										sendPushNotificationAndroid($CustomerToken->M_AndroidToken,'Your Order is updated and enroute for pickup');
													$result = array('status'=>'success','flag'=>'1','message'=>'Order Accepted Successfully','data'=>$Record);	
													
												} 
											else if ($success == false) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Accepted','data'=>$Record);	
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
										}							
									} else if($BusinessType == '3'){
										$OrderData =  $this->Cart_model->getRestaurantsCart($data['Val_Order'],array('RC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$data['Val_RCdeliverybystatus'] 		= '2';

											$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
											if(!empty($CartData))
												{
													$success = $this->Cart_model->updateCartRestaurants($data,$data['Val_Order']);		
													
													if ($success) {
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
															$result = array('status'=>'success','flag'=>'1','message'=>'Order Accepted Successfully','data'=>$Record);	
										
															
														} 
													else if ($success == false) {
													
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
								
															$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Accepted','data'=>$Record);	
													} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}						
												} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}		
																						
									}
									
								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing','data'=>(object)array());	
			}

		} else if(!empty($data['Action']) && $data['Action'] == 'PickedOrder'){

			if( !empty($data['Val_Type']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Order']) )	
				{
					
				
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							
							$BusinessType = $data['Val_Type'];
							unset($data['Val_Type']);
							if($BusinessType == '2'){
										$OrderData =  $this->Cart_model->getProductsCart($data['Val_Order'],array('PC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$data['Val_PCdeliverybystatus'] 		= '3';
											
											$success = $this->Cart_model->updateCartProducts($data,$data['Val_Order']);		
											
											if ($success) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order Picked Up Successfully','data'=>$Record);	
													
												} 
											else if ($success == false) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Picked Up','data'=>$Record);	
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
										}							
									} else if($BusinessType == '3'){
										$OrderData =  $this->Cart_model->getRestaurantsCart($data['Val_Order'],array('RC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$data['Val_RCdeliverybystatus'] 		= '3';

											$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
											if(!empty($CartData))
												{
													$success = $this->Cart_model->updateCartRestaurants($data,$data['Val_Order']);		
													
													if ($success) {
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
															$result = array('status'=>'success','flag'=>'1','message'=>'Order Picked Up Successfully','data'=>$Record);	
										
															
														} 
													else if ($success == false) {
													
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
								
															$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Picked Up','data'=>$Record);	
													} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}						
												} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}		
																						
									}
									
								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing','data'=>(object)array());	
			}

		} else if(!empty($data['Action']) && $data['Action'] == 'DeliveredOrder'){

			if( !empty($data['Val_Type']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Order']) )	
				{
					
				
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							
							$BusinessType = $data['Val_Type'];
							unset($data['Val_Type']);
							if($BusinessType == '2'){
										$OrderData =  $this->Cart_model->getProductsCart($data['Val_Order'],array('PC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$data['Val_PCdeliverybystatus'] 		= '4';
											$data['Val_PCorderstatus'] 		= '4';
											$data['Val_PCstatus'] 		= '3';
											
											$success = $this->Cart_model->updateCartProducts($data,$data['Val_Order']);		
											
											if ($success) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order Delivered Successfully','data'=>$Record);	
													
												} 
											else if ($success == false) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Delivered','data'=>$Record);	
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
										}							
									} else if($BusinessType == '3'){
										$OrderData =  $this->Cart_model->getRestaurantsCart($data['Val_Order'],array('RC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$data['Val_RCdeliverybystatus'] 		= '2';
											$data['Val_RCorderstatus'] 		= '4';
											$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
											if(!empty($CartData))
												{
													$success = $this->Cart_model->updateCartRestaurants($data,$data['Val_Order']);		
													
													if ($success) {
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
															$result = array('status'=>'success','flag'=>'1','message'=>'Order Delivered Successfully','data'=>$Record);	
										
															
														} 
													else if ($success == false) {
													
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
								
															$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Delivered','data'=>$Record);	
													} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}						
												} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}		
																						
									}
									
								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing','data'=>(object)array());	
			}

		} else if(!empty($data['Action']) && $data['Action'] == 'CancelOrder'){

			if( !empty($data['Val_Type']) && !empty($data['Val_Deliveryboy']) && !empty($data['Val_Order']) )	
				{
					
				
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
						
									$BusinessType = $data['Val_Type'];
									unset($data['Val_Type']);
									if($BusinessType == '2'){
										$OrderData =  $this->Cart_model->getProductsCart($data['Val_Order'],array('PC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
										
											$success = $this->Cart_model->removeDeliveryBoyCartProducts($data['Val_Order']);		
											
											if ($success) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order Cancelled Successfully','data'=>$Record);	
													
												} 
											else if ($success == false) {
													$CartData		= $this->Cart_model->getProductsCart($data['Val_Order']);
													
													$Record = array(  
																'OrderID' 		=> getStringValue($CartData->PCartID),
															);		
													
													$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Cancelled','data'=>$Record);	
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
										}							
									} else if($BusinessType == '3'){
										$OrderData =  $this->Cart_model->getRestaurantsCart($data['Val_Order'],array('RC_DeliveryBy'=>$data['Val_Deliveryboy']));
										
										if(!empty($OrderData))
										{
											$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
											if(!empty($CartData))
												{
													$success = $this->Cart_model->removeDeliveryBoyCartRestaurants($data['Val_Order']);		
													
													if ($success) {
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
															$result = array('status'=>'success','flag'=>'1','message'=>'Order Cancelled Successfully','data'=>$Record);	
										
															
														} 
													else if ($success == false) {
													
															$CartData		= $this->Cart_model->getRestaurantsCart($data['Val_Order']);
															
															$Record = array(  
																		'OrderID' 		=> getStringValue($CartData->RCartID),
																	);		
															
								
															$result = array('status'=>'success','flag'=>'1','message'=>'Order is already Cancelled','data'=>$Record);	
													} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened ! ','data'=>(object)array());	
													}						
												} else{
															$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
													}
											} else{
													$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>(object)array());	
											}		
																						
									}
						
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing','data'=>(object)array());	
			}

		} else if(!empty($data['Action']) && $data['Action'] == 'GetReviewsHistory'){
		
			if( !empty($data['Val_Deliveryboy']) )	
				{
					$OngoingOrderRecords 		= array();
					$PastOrderRecords 			= array();
					$PastOrderRecordsCount 		= (string)count($PastOrderRecords);
					$OngoingOrderRecordsCount 	= (string)count($OngoingOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							$CategoryData = $this->Categories_model->get($DeliveryBoyData->DB_CategoryID);
							if(!empty($CategoryData))
								{
									$BusinessType = $CategoryData->C_Type;
									
									$ReviewsArray =  $this->Deliveryboys_model->getReviews(NULL,array('R_DeliveryBoyID'=>$data['Val_Deliveryboy']));										
									
									if(!empty($ReviewsArray))
										{
											$Index = 1;
											foreach($ReviewsArray as $ReviewArray)
												{	
//													$ReviewData = (object)$ReviewArray; 
													$FormattedDate = date('d M,Y',strtotime($ReviewArray['R_Date']));
													$FormattedDateAgo = time_ago($ReviewArray['R_Date']." ".$ReviewArray['R_Time']);
												
													
													$ReviewsRecords[] = array(
																		'ReviewID'=>getStringValue($ReviewArray['ReviewID']),
																		'Index'=>getStringValue($Index),
																		'Username'=>getStringValue($ReviewArray['R_UserName']),
																		'Color'=>getRandomColor(),
																		'Comment'=>getStringValue($ReviewArray['R_Comment']),
																		'Location'=>getStringValue($ReviewArray['R_Location']),
																		'Rating'=>getStringValue($ReviewArray['R_Rating']),
																		'Date'=>getStringValue($FormattedDate),
																		'Date2'=>getStringValue($FormattedDateAgo),
																	);
													$Index++;				
												}
											 $ReviewsRecordsCount = (string)count($ReviewsRecords);
										}
									
									
									$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
										
									$Records['ReviewsCount']	= $ReviewsRecordsCount;
									$Records['ReviewsData']		= $ReviewsRecords;
									$Records['MiscData'] 		= $MiscRecords;
									$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Reviews Data Fetched','data'=>$Records);		
								}
							else
								{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
								}	
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		} else if(!empty($data['Action']) && $data['Action'] == 'GetAccountDetails'){
		
			if( !empty($data['Val_Deliveryboy']) )	
				{
					$OngoingOrderRecords 		= array();
					$PastOrderRecords 			= array();
					$PastOrderRecordsCount 		= (string)count($PastOrderRecords);
					$OngoingOrderRecordsCount 	= (string)count($OngoingOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
						
									$AccountArray =  $this->Deliveryboys_model->getAccounts(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));										
									
									if(!empty($AccountArray))
										{
											$Index = 1;
	
											$AccountData = (object)$AccountArray[0];
											
										
											
											$AccountRecord = array(
																'DeliveryBoyID'=>getStringValue($AccountData->A_DeliveryBoyID),
																'AccountID'=>getStringValue($AccountData->DBAccountID),
																'AccountName'=>getStringValue($AccountData->A_AccountName),
																'AccountType'=>getStringValue($AccountData->A_AccountType),
																'AccountNumber'=>getStringValue($AccountData->A_AccountNumber),
																'IFSCNumber'=>getStringValue($AccountData->A_IFSCNumber),
															);
											
											
										}
									
									
									$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
										
									$Records['AccountsData']		= $AccountRecord;
									$Records['MiscData'] 		= $MiscRecords;
									$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Account Data Fetched','data'=>$Records);	
									


									
								
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		}   else if(!empty($data['Action']) && $data['Action'] == 'GetProfileDetails'){
		
							
					
					if( !empty($data['Val_Deliveryboy']) && $data['Val_Deliveryboy'] != '') {
						
						$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
						
						if ($DeliveryBoyData) {
							
							$ProfileData = $this->Deliveryboys_model->getProfile(NULL,array('P_DeliveryBoyID'=>$data['Val_Deliveryboy']));
							$AboutData = $this->Deliveryboys_model->getAbout(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));
							$WorksData = $this->Deliveryboys_model->getWorks(NULL,array('W_DeliveryBoyID'=>$data['Val_Deliveryboy']));
							$LocationsData = $this->Deliveryboys_model->getLocations(NULL,array('L_DeliveryBoyID'=>$data['Val_Deliveryboy']));
		
										
													
							$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Record Fetched','data'=>$Record);	
						} elseif ($DeliveryBoyArray === FALSE) {					
							$result = array('status'=>'error','flag'=>'2','message'=>'DeliveryBoy Record Not Fetched','data'=>(object)array());	
						}	
					} else {
						$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}			 							
			
		
			if( !empty($data['Val_Deliveryboy']) )	
				{
					$OngoingOrderRecords 		= array();
					$PastOrderRecords 			= array();
					$PastOrderRecordsCount 		= (string)count($PastOrderRecords);
					$OngoingOrderRecordsCount 	= (string)count($OngoingOrderRecords);
					$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($data['Val_Deliveryboy']);
					if(!empty($DeliveryBoyData))
						{
							$CategoryData = $this->Categories_model->get($DeliveryBoyData->DB_CategoryID);
							if(!empty($CategoryData))
								{
									$BusinessType = $CategoryData->C_Type;
									
									$ProfileData = $this->Deliveryboys_model->getProfile(NULL,array('P_DeliveryBoyID'=>$data['Val_Deliveryboy']));
									$AboutData = $this->Deliveryboys_model->getAbout(NULL,array('A_DeliveryBoyID'=>$data['Val_Deliveryboy']));
									$WorksData = $this->Deliveryboys_model->getWorks(NULL,array('W_DeliveryBoyID'=>$data['Val_Deliveryboy']));
									$LocationsData = $this->Deliveryboys_model->getLocations(NULL,array('L_DeliveryBoyID'=>$data['Val_Deliveryboy']));
				
									
									if(!empty($ProfileData)){
										$ProfileData 		= (object)$ProfileData[0];
										$IdentityStatus 	= $ProfileData->P_IDCardStatus;
										$PersonalStatus 	= $ProfileData->P_PersonalStatus;
										$CurrentStatus 		= $ProfileData->P_CurrentStatus;
										$TermsStatus 		= $ProfileData->P_TermsStatus;
									
									}else{
										$IdentityStatus 	= '';
										$PersonalStatus 	= '';
										$CurrentStatus 		= '';
										$TermsStatus 		= '';
									}
									
									if($IdentityStatus == '2' && $PersonalStatus == '2' && $CurrentStatus == '2' && $TermsStatus == '2')
										{
											$IdentityVerificationStatus = '2';
										}
									else
										{
											$IdentityVerificationStatus = '1';
										}	
										
			
									if(!empty($AboutData)){
										$AboutData 		= (object)$AboutData[0];
										$AboutMeStatus 	= $AboutData->A_Status;
									
									}else{
										$AboutMeStatus 		=	'';
									}
									
									if(!empty($WorksData)){
										$WorksData 		= (object)$WorksData[0];
										$WorksStatus 	= $WorksData->W_Status;
									
									}else{
										$WorksStatus 		=	'';
									}
										
									if(!empty($LocationsData)){
										$LocationsData 			= (object)$LocationsData[0];
										$BusinessLocationStatus = $LocationsData->L_Status;
									
									}else{
										$BusinessLocationStatus =	'';
									}
											
									$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
									$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : "");
									$ProfileRecord = array(  
											'DeliveryBoyID' 			=> getStringValue($DeliveryBoyData->DeliveryBoyID),
											'FullName' 			=> getStringValue($DeliveryBoyFullName),
											'FirstName'			=> getStringValue($DeliveryBoyData->DB_FirstName),
											'LastName'			=> getStringValue($DeliveryBoyData->DB_LastName),
											'CountryCode'		=> getStringValue($DeliveryBoyData->DB_CountryCode),
											'MobileNumber'		=> getStringValue($DeliveryBoyData->DB_Mobile),
											'EmailAddress'		=> getStringValue($DeliveryBoyData->DB_Email),
											'Latitude' 			=> getStringValue($DeliveryBoyData->DB_Latitude),
											'Longitude' 		=> getStringValue($DeliveryBoyData->DB_Longitude),
											'Address'			=> getStringValue($DeliveryBoyData->DB_Address),
											'Location' 			=> getStringValue($DeliveryBoyData->DB_Location),
											'City' 				=> getStringValue($DeliveryBoyData->DB_City),
											'Country' 			=> getStringValue($DeliveryBoyData->DB_Country),
											'ProfileImage' 		=> $DeliveryBoyProfileImage,
//											'ProfileStatus' 	=> $DeliveryBoyData->DB_ProfileStatus,
//											'VerificationStatus' 	=> $DeliveryBoyData->DB_VerificationStatus,
//											'VerificationMessage' 	=> getStringValue($DeliveryBoyData->DB_VerificationMessage),
											//'IdentityVerificationStatus'	=> $IdentityVerificationStatus,
//											'AboutMeStatus'					=> $AboutMeStatus,
//											'WorksStatus'					=> $WorksStatus,
//											'BusinessLocationStatus'		=> $BusinessLocationStatus,
										);		
											
									
									
									
									
									
									$MiscRecords = array( 
											'NotificationCount' => "1",
												);	
						
										
									$Records['ProfileData']		= $ProfileRecord;
									$Records['MiscData'] 		= $MiscRecords;
									$result = array('status'=>'success','flag'=>'1','message'=>'DeliveryBoy Profile Data Fetched','data'=>$Records);	
									


									
								}
							else
								{
									$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
								}	
						}
					else
						{
							$result = array('status'=>'warning','flag'=>'3','message'=>'Something Went Wrong...','data'=>(object)$Records);	
						}
					
				}
			else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			}
		
		
		
		}   else {
		
			
			$result = array('status'=>'info','flag'=>'4','message'=>'Paramater Missing');	
			
			
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	
		
		
		return false;
	}
		 
}


?>