<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Authentication extends W_Controller
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
	
	// Sign Up For User And Service Provider 
	public function SignUp()
    {		  
		$data = $this->input->post();
		
		if(!empty($data) && $data != ''){

			$UserData = $this->Users_model->getByMobile($data['Val_Mobilenumber']);

			if(empty($UserData)){

				$data['Val_Status'] = '2';
				$success = $this->Users_model->add($data);		
				
				$user_id = $success;
				
				if ($success) {
					
					$data['Val_Relation'] = $user_id;
					$data['Val_Type'] = '1';
					$Lsuccess = $this->Authentication_model->AppSignup($data);		

					$ProfileImage = handle_user_profile_image($user_id);			
					$UserData = $this->Users_model->get($user_id);
					$Record = array(  
								'UserID' => $UserData->UserID,
								'FirstName'=> $UserData->U_FirstName,
								'LastName'=> $UserData->U_LastName,
								'MobileNumber'=> $UserData->U_Mobile,
								'Email'=> $UserData->U_Email,
								'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
								'Status'=> getStatus($UserData->U_Status),
							);		
					
					$result = array('status'=>'success','flag'=>'1','message'=>'User Created Successfully','data'=>$Record);	

					
				} else if ($success == false) {
					$data['Val_ProfileImage'] = '';
					$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$data);	
				} else{
					$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
				}
			} else{
					$result = array('status'=>'error','flag'=>'2','message'=>'User mobile number already registered. Try to login with same number. ');	
				}			
		} else {
			$result = array('status'=>'warning','message'=>'Parameter Missing');	
		}

        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	// User And Service Providers Login
	public function UserMobile()
    {
       	$data = $this->input->post();	
		
		if(!empty($data) && $data['Action'] = 'UserCheckMobile'){	
			
			if(!empty($data['Val_Emailaddress']))
				$UserData = $this->Users_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobilenumber'],array('U_Email'=>$data['Val_Emailaddress']));
			else	
				$UserData = $this->Users_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobilenumber']);
				
			if(!empty($UserData)){
				$UserArray = $UserData;
				
				$Record = array(  
							'UserID' => $UserArray->UserID,
							'FirstName'=> $UserArray->U_FirstName,
							'LastName'=> $UserArray->U_LastName,
							'MobileNumber'=> $UserArray->U_Mobile,
							'Email'=> $UserArray->U_Email,
							'Country'=> $UserArray->U_Country,
							'State'=> $UserArray->U_State,
							'City'=> $UserArray->U_City,
							'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserArray->UserID.'/'.$UserArray->U_ProfileImage,						
							'Status'=> getStatus($UserArray->U_Status)
							);		
			
					$result = array('status'=>'error','flag'=>'2','message'=>'The mobile number/email address you entered already exists.','data'=>$Record);					

				} else {
					
					//$Postdata['Val_OTP'] = $OTP;
				//	$udpate = $this->Users_model->update($Postdata,$UserData->UserID);
					
					$UserArray = $UserData;
	
					$OTPResponse = sendOTP($data['Val_Countrycode'],$data['Val_Mobilenumber']);
					
					if(!empty($OTPResponse)){
						if(is_array($OTPResponse))
							{
								$result = array('status'=>'error','flag'=>'2','message'=>'There is some issue in sending One Time Password to your mobile number. Please try again later.','data'=>(object)$OTPResponse);														
							}
						else
							{
								$Record = array( 
											'OTP'=> (string)$OTPResponse);		

								$result = array('status'=>'success','flag'=>'1','message'=>'You are good to go for signup.','data'=>$Record);		
							
							}
						}
					else
						{
							$result = array('status'=>'error','flag'=>'2','message'=>$data['error'],'data'=>'Confidential');														
						
						}			
				}
			} else {
				$result = array('status'=>'error','flag'=>'2','message'=>'Parameters Missing. Please Check Again. Contact your API developer.','data'=>$data);		
			}
	   
		do_action('after_user_login'); 
  		
        $this->data        = $result;
        echo json_encode($this->data);
		
	}
	
	// User And Service Providers Login
	public function SignIn()
    {
		//Generating a 4 Digits OTP or verification code 
       	$data = $this->input->post();	
		$Record = array(  
						'CustomerID' 	=> "",
						'FullName' 		=> "",
						'FirstName' 	=> "",
						'LastName' 		=> "",
						'CountryCode' 	=> "",
						'MobileNumber' 	=> "",
						'EmailAddress' 	=> "",
						'Latitude' 		=> "",
						'Longitude' 	=> "",
						'Address'		=> "",
						'AddressCount'	=> "",
						'AddressData'	=> "",
						'Location' 		=> "",
						// 'Country'       =>"",
						// 'State'			=> "",
						// 'City' 			=> "",
						// 'Area' 			=> "",
						'OTPCode' 		=> "",
						);	
	if(!empty($data) && $data['Action'] = 'Request'){	
			
			$UserType = $data['Val_Type'];
			
		$LoginStatus = $this->Authentication_model->AppSignin($data['Val_Countrycode'],$data['Val_Mobilenumber'], $data['Val_Type'], $data['Val_Language']);
		
		if($UserType == '1')
			$OTPResponse = sendOTP($data['Val_Countrycode'],$data['Val_Mobilenumber']);
		else			
			$OTPResponse = '1234';

			if(!empty($OTPResponse)){
				
				if(is_array($OTPResponse))
					{
						$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_sms_otp_sending_issue'),'data'=>(object)array());														
					}
				else
					{
						 /* Customer SignUp and SignIn Functionality start */		
						if($UserType == '1') {
				
							if (is_array($LoginStatus) && isset($LoginStatus['inactive'])) {
								
								$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_inactive_account'),'data'=>$Record);
							
							} else if ($LoginStatus == false) {

								// $Record['OTPCode'] = getStringValue($OTPResponse);  /* 20.04.2019 9:00 PM */
								
								$data['Val_Cstatus'] 		= '2';
								$data['Val_Status'] 		= '2';
								$data['Val_Ccountrycode'] 	= $data['Val_Countrycode'];
								$data['Val_Cmobilenumber'] 	= $data['Val_Mobilenumber'];
								
										$success = $this->Customers_model->add($data);
										
										$customer_id = $success;
					
										if ($success) {
												

												$data['Val_Relation'] = $customer_id;
												$data['Val_Type'] = '1';

												$Lsuccess = $this->Authentication_model->AppSignup($data);		
							
												//$ProfileImage = handle_user_profile_image($user_id);			
												
												$CustomerData = $this->Customers_model->getCustomer($customer_id);
												$AddressArray = $this->Customers_model->getAddresses(NULL,array('A_RelationID'=>$customer_id,'A_Type'=>'1'));

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
												
												$CustomerFullName = getStringValue($CustomerData->C_FirstName)." ".getStringValue($CustomerData->C_LastName);
												$Record = array(  
															'CustomerID' 	=> getStringValue($CustomerData->CustomerID),
															'FullName' 		=> getStringValue($CustomerFullName),
															'FirstName' 	=> getStringValue($CustomerData->C_FirstName),
															'LastName' 		=> getStringValue($CustomerData->C_LastName),
															'CountryCode'	=> getStringValue($CustomerData->C_CountryCode),
															'MobileNumber' 	=> getStringValue($CustomerData->C_Mobile),
															'EmailAddress' 	=> getStringValue($CustomerData->C_Email),
															'Latitude' 		=> getStringValue($CustomerData->C_Latitude),
															'Longitude' 	=> getStringValue($CustomerData->C_Longitude),
															'Address'		=> getStringValue($CustomerData->C_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,
															'Location' 		=> getStringValue($CustomerData->C_Location),
															// 'City' 			=> getStringValue($CustomerData->C_City),
															// 'Area' 			=> getStringValue($CustomerData->C_Area),
															'OTPCode'		=> getStringValue($OTPResponse),
													//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
													//		'Status'=> getStatus($CustomerData->C_Status),
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
							
												
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}

								
								
								//$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_user_doesnt_exist',_l('user_customer')),'data'=>$Record);
							
							} else if ($LoginStatus == true) {

								$CustomerData = $this->Customers_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobilenumber']);
							

								if(!empty($CustomerData)|| $CustomerData!=false)
									{
										$customer_id = $CustomerData[0]['CustomerID'];
										$AddressArray = $this->Customers_model->getAddresses(NULL,array('A_RelationID'=>$customer_id,'A_Type'=>'1'));
										
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
										//$CustomerProfileImage = UPLOAD_USER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage;
										$CustomerFullName = $CustomerData[0]['C_FirstName']." ".$CustomerData[0]['C_LastName'];
			
										if(empty($CustomerData->C_Latitude) || empty($CustomerData->C_Longitude) || empty($CustomerData->C_Location))
											{
												$Record = array(  
															'CustomerID' 	=> getStringValue($CustomerData[0]['CustomerID']),
															'FullName' 		=> getStringValue($CustomerFullName),
															'FirstName' 	=> getStringValue($CustomerData[0]['C_FirstName']),
															'LastName' 		=> getStringValue($CustomerData[0]['C_LastName']),
															'CountryCode'	=> getStringValue($CustomerData[0]['C_CountryCode']),
															'MobileNumber' 	=> getStringValue($CustomerData[0]['C_Mobile']),
															'EmailAddress' 	=> getStringValue($CustomerData[0]['C_Email']),
															'Latitude' 		=> getStringValue($CustomerData[0]['C_Latitude']),
															'Longitude' 	=> getStringValue($CustomerData[0]['C_Longitude']),
															'Address'		=> getStringValue($CustomerData[0]['C_Address']),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($CustomerData[0]['C_Location']),
															'OTPCode'		=> getStringValue($OTPResponse),
															//'ProfileImage' => $CustomerProfileImage,
															//'Status' => $CustomerData->C_Status,
															);		
												$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_data_fetched_success',_l('user_customer')),'data'=>$Record);														
											}
										else
											{
												$Record = array(  
															'CustomerID' 	=> getStringValue($CustomerData->CustomerID),
															'FullName' 		=> getStringValue($CustomerFullName),
															'FirstName' 	=> getStringValue($CustomerData->C_FirstName),
															'LastName' 		=> getStringValue($CustomerData->C_LastName),
															'CountryCode'	=> getStringValue($CustomerData->C_CountryCode),
															'MobileNumber' 	=> getStringValue($CustomerData->C_Mobile),
															'EmailAddress' 	=> getStringValue($CustomerData->C_Email),
															'Latitude' 		=> getStringValue($CustomerData->C_Latitude),
															'Longitude' 	=> getStringValue($CustomerData->C_Longitude),
															'Address'		=> getStringValue($CustomerData->C_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($CustomerData->C_Location),
															// 'City' 			=> getStringValue($CustomerData->C_City),
															// 'Area' 			=> getStringValue($CustomerData->C_Area),
															'OTPCode'		=> getStringValue($OTPResponse),
															//'ProfileImage' => $CustomerProfileImage,
															//'Status' => $CustomerData->C_Status,
															);		
												$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_data_fetched_success',_l('user_customer')),'data'=>$Record);														
											
											}						
									}
								else {
								
										$data['Val_Cstatus'] 		= '2';
										$data['Val_Status'] 		= '2';
										$data['Val_Ccountrycode'] 	= $data['Val_Countrycode'];
										$data['Val_Cmobilenumber'] 	= $data['Val_Mobilenumber'];
										
										$success = $this->Customers_model->add($data);		
				
										$customer_id = $success;
					
										if ($success) {
												
												$data['Val_Relation'] = $customer_id;
												$data['Val_Type'] = '1';
												$Lsuccess = $this->Authentication_model->AppSignup($data);		
												$AddressArray = $this->Customers_model->getAddresses(NULL,array('A_RelationID'=>$customer_id,'A_Type'=>'1'));		
												
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
												//$ProfileImage = handle_user_profile_image($user_id);			
												$CustomerData = $this->Customers_model->getCustomer($customer_id);
												//Amol
												$CustomerFullName = $CustomerData->C_FirstName." ".$CustomerData->C_LastName;
												$Record = array(  
															'CustomerID' 	=> getStringValue($CustomerData->CustomerID),
															'FullName' 		=> getStringValue($CustomerFullName),
															'FirstName' 	=> getStringValue($CustomerData->C_FirstName),
															'LastName' 		=> getStringValue($CustomerData->C_LastName),
															'CountryCode'	=> getStringValue($CustomerData->C_CountryCode),
															'MobileNumber' 	=> getStringValue($CustomerData->C_Mobile),
															'EmailAddress' 	=> getStringValue($CustomerData->C_Email),
															'Latitude' 		=> getStringValue($CustomerData->C_Latitude),
															'Longitude' 	=> getStringValue($CustomerData->C_Longitude),
															'Address'		=> getStringValue($CustomerData->C_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($CustomerData->C_Location),
															// 'City' 			=> getStringValue($CustomerData->C_City),
															// 'Area' 		=> getStringValue($CustomerData->C_Area),
															'OTPCode'		=> getStringValue($OTPResponse),
															//'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
															//'Status'=> getStatus($CustomerData->C_Status),
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
							
												
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$data);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}

									$Record['OTPCode'] = (string)$OTPResponse;
									//$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_user_doesnt_exist',_l('user_customer')),'data'=>$Record);		
								}	
							}
						} 
						/* Vendor SignUp and SignIn Functionality start */
						else if($UserType == '2') {
						
							if (is_array($LoginStatus) && isset($LoginStatus['inactive'])) {
								$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_inactive_account'),'data'=>$Record);
							} else if ($LoginStatus == false) {
								
								
								//$Record['OTPCode']			= getStringValue($OTPResponse); /* 20.04.2019 9:00 PM */
								$data['Val_Vstatus'] 		= '2';
								$data['Val_Vcountrycode'] 	= $data['Val_Countrycode'];
								$data['Val_Vmobilenumber'] 	= $data['Val_Mobilenumber'];
								
										$success = $this->Vendors_model->add($data);		
				
										$vendor_id = $success;
					
										if ($success) {

												
												$data['Val_Relation'] = $vendor_id;
												$data['Val_Type'] = '2';
												$Lsuccess = $this->Authentication_model->AppSignup($data);		
							
												//$ProfileImage = handle_user_profile_image($user_id);			
												
												$VendorData = $this->Vendors_model->getVendor($vendor_id);
												$AddressArray = $this->Vendors_model->getAddresses(NULL,array('A_RelationID'=>$vendor_id,'A_Type'=>'2'));

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
												
												$VendorFullName = getStringValue($VendorData->V_FirstName)." ".getStringValue($VendorData->V_LastName);
												$VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL.$VendorData->VendorID.'/'.$VendorData->V_ProfileImage : '');
												
												$Record = array(  
															'VendorID' 		=> getStringValue($VendorData->VendorID),
															'FullName' 		=> getStringValue($VendorFullName),
															'FirstName' 	=> getStringValue($VendorData->V_FirstName),
															'LastName' 		=> getStringValue($VendorData->V_LastName),
															'CountryCode'	=> getStringValue($VendorData->V_CountryCode),
															'MobileNumber' 	=> getStringValue($VendorData->V_Mobile),
															'EmailAddress' 	=> getStringValue($VendorData->V_Email),
															'Latitude' 		=> getStringValue($VendorData->V_Latitude),
															'Longitude' 	=> getStringValue($VendorData->V_Longitude),
															'Address'		=> getStringValue($VendorData->V_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($VendorData->V_Location),
															'City' 			=> getStringValue($VendorData->V_City),
															'Country' 		=> getStringValue($VendorData->V_Country),
															'OTPCode'		=> getStringValue($OTPResponse),

															'ProfileImage' 	=> $VendorProfileImage,
															'ProfileStatus' 		=> $VendorData->V_ProfileStatus,
															'VerificationStatus' 	=> $VendorData->V_VerificationStatus,
															'VerificationMessage' 	=> $VendorData->V_VerificationMessage,
															'Status' 				=> $VendorData->V_Status,
															
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
														//print-r($result);
												
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}

								
								
								//$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_user_doesnt_exist',_l('user_vendor')),'data'=>$Record);
							} else if ($LoginStatus == true) {
								
								$VendorData = $this->Vendors_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobilenumber']);

								if(!empty($VendorData))
									{

										$AddressArray = $this->Vendors_model->getAddresses(NULL,array('A_RelationID'=>$VendorData->VendorID,'A_Type'=>'2'));
										
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
									
										$AndroidToken = Send_AndroidPushNotification();	
										$AddressCount 	= (string)count($AddressRecords); 
										$AddressData	= $AddressRecords;								
										//$CustomerProfileImage = UPLOAD_USER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage;
										$VendorFullName = $VendorData->V_FirstName." ".$VendorData->V_LastName;
										$VendorProfileImage = (!empty($VendorData->V_ProfileImage) ? UPLOAD_VENDOR_BASE_URL.$VendorData->VendorID.'/'.$VendorData->V_ProfileImage : '');
										/*
										if(empty($VendorData->V_Latitude) || empty($VendorData->V_Longitude) || empty($VendorData->V_Location))
											{
												$Record = array(  
															'VendorID' 		=> getStringValue($VendorData->VendorID),
															'FullName' 		=> getStringValue($VendorFullName),
															'FirstName' 	=> getStringValue($VendorData->V_FirstName),
															'LastName' 		=> getStringValue($VendorData->V_LastName),
															'CountryCode'	=> getStringValue($VendorData->V_CountryCode),
															'MobileNumber' 	=> getStringValue($VendorData->V_Mobile),
															'EmailAddress' 	=> getStringValue($VendorData->V_Email),
															'Latitude' 		=> getStringValue($VendorData->V_Latitude),
															'Longitude' 	=> getStringValue($VendorData->V_Longitude),
															'Address'		=> getStringValue($VendorData->V_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($VendorData->V_Location),
															'OTPCode'		=> getStringValue($OTPResponse),
															'ProfileImage' => $CustomerProfileImage,
															'Status' => $CustomerData->C_Status,
															);		
												$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_data_fetched_success',_l('user_vendor')),'data'=>$Record);														
											
											}
										else
											{*/
												$Record = array(  
															'VendorID' 		=> getStringValue($VendorData->VendorID),
															'FullName' 		=> getStringValue($VendorFullName),
															'FirstName' 	=> getStringValue($VendorData->V_FirstName),
															'LastName' 		=> getStringValue($VendorData->V_LastName),
															'CountryCode'	=> getStringValue($VendorData->V_CountryCode),
															'MobileNumber' 	=> getStringValue($VendorData->V_Mobile),
															'EmailAddress' 	=> getStringValue($VendorData->V_Email),
															'Latitude' 		=> getStringValue($VendorData->V_Latitude),
															'Longitude' 	=> getStringValue($VendorData->V_Longitude),
															'Address'		=> getStringValue($VendorData->V_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($VendorData->V_Location),
															'City' 			=> getStringValue($VendorData->V_City),
															'Country' 		=> getStringValue($VendorData->V_Country),
															'OTPCode'		=> getStringValue($OTPResponse),
															'Token'			=> $AndroidToken,
															'ProfileImage' => $VendorProfileImage,
															'ProfileStatus' => $VendorData->V_ProfileStatus,
															'VerificationStatus' => $VendorData->V_VerificationStatus,
															'Status' 		=> $VendorData->V_Status,
															);		
												$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_data_fetched_success',_l('user_vendor')),'data'=>$Record);														
											
											//}	
															
									}
								else {
								
										$data['Val_Vstatus'] 		= '2';
										$data['Val_Status'] 		= '2';
										$data['Val_Vcountrycode'] 	= $data['Val_Countrycode'];
										$data['Val_Vmobilenumber'] 	= $data['Val_Mobilenumber'];
										$success = $this->Vendors_model->add($data);		
				
										$vendor_id = $success;
					
										if ($success) {
												
												$data['Val_Relation'] = $vendor_id;
												$data['Val_Type'] = '2';
												$Lsuccess = $this->Authentication_model->AppSignup($data);		
												$AddressArray = $this->Vendors_model->getAddresses(NULL,array('A_RelationID'=>$vendor_id,'A_Type'=>'2'));		
												
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
												$AndroidToken = Send_AndroidPushNotification();
												$AddressCount 	= (string)count($AddressRecords); 
												$AddressData	= $AddressRecords;																														
												//$ProfileImage = handle_user_profile_image($user_id);			
												$VendorData = $this->Vendors_model->getVendor($vendor_id);
												$Record = array(  
															'VendorID' 		=> getStringValue($VendorData->VendorID),
														//	'FullName' 		=> getStringValue($VendorFullName),
															'FirstName' 	=> getStringValue($VendorData->V_FirstName),
															'LastName' 		=> getStringValue($VendorData->V_LastName),
															'CountryCode'	=> getStringValue($VendorData->V_CountryCode),
															'MobileNumber' 	=> getStringValue($VendorData->V_Mobile),
															'EmailAddress' 	=> getStringValue($VendorData->V_Email),
															'Latitude' 		=> getStringValue($VendorData->V_Latitude),
															'Longitude' 	=> getStringValue($VendorData->V_Longitude),
															'Address'		=> getStringValue($VendorData->V_Address),
															'AddressCount'	=> $AddressCount,
															'AddressData'	=> $AddressData,															
															'Location' 		=> getStringValue($VendorData->V_Location),
															'OTPCode'		=> getStringValue($OTPResponse),
															'Token'         => getStringValue($AndroidToken),
													//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
													//		'Status'=> getStatus($CustomerData->C_Status),
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
							
												//print_r($result);
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$data);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}

								
								
								
									$Record['OTPCode'] = (string)$OTPResponse;
									//$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_user_doesnt_exist',_l('user_customer')),'data'=>$Record);		
								}	
							}
						} 
						/* DeliveryBoy SignUp and SignIn Functionality start */
						else if($UserType == '3') {
				
							if (is_array($LoginStatus) && isset($LoginStatus['inactive'])) {

								$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_inactive_account'),'data'=>$Record);

							} else if ($LoginStatus == false) {

									//$Record['OTPCode'] = getStringValue($OTPResponse); /* 20.04.2019 9:00 PM */
									$data['Val_DBstatus'] 			= '1';
									$data['Val_DBprofilestatus'] 	= '1';
									$data['Val_DBcountrycode'] 		= $data['Val_Countrycode'];
									$data['Val_DBmobilenumber'] 	= $data['Val_Mobilenumber'];
								
										$success = $this->Deliveryboys_model->add($data);		
				
										$deliveryboy_id = $success;
										if ($success) {

											
												$AndroidToken = Send_AndroidPushNotification();
												$data['Val_Relation'] = $deliveryboy_id;
												$data['Val_Type'] = '3';
												$Lsuccess = $this->Authentication_model->AppSignup($data);		
							
												//$ProfileImage = handle_user_profile_image($user_id);			
												
												$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($deliveryboy_id);

												$DeliveryBoyFullName = getStringValue($DeliveryBoyData->DB_FirstName)." ".getStringValue($DeliveryBoyData->DB_LastName);
												$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : '');
												$Record = array(  
															'CustomerID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
															'FullName' 		=> getStringValue($DeliveryBoyFullName),
															'FirstName' 	=> getStringValue($DeliveryBoyData->DB_FirstName),
															'LastName' 		=> getStringValue($DeliveryBoyData->DB_LastName),
															'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
															'MobileNumber' 	=> getStringValue($DeliveryBoyData->DB_Mobile),
															'EmailAddress' 	=> getStringValue($DeliveryBoyData->DB_Email),
															'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
															'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
															'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
															'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
															'OTPCode'		=> getStringValue($OTPResponse),
															'ProfileImage'	=> $DeliveryBoyProfileImage,
													//		'Status'=> getStatus($CustomerData->C_Status),
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
											}
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}
								
								//$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_user_doesnt_exist',_l('user_customer')),'data'=>$Record);
							} else if ($LoginStatus == true) {
								$DeliveryBoyData = $this->Deliveryboys_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobilenumber']);
							

								if(!empty($DeliveryBoyData))
									{
										$AndroidToken = Send_AndroidPushNotification();
										//$CustomerProfileImage = UPLOAD_USER_BASE_URL.$CustomerData->CustomerID.'/'.$CustomerData->C_ProfileImage;
										$DeliveryBoyFullName = $DeliveryBoyData->DB_FirstName." ".$DeliveryBoyData->DB_LastName;
										$DeliveryBoyProfileImage = (!empty($DeliveryBoyData->DB_ProfileImage) ? UPLOAD_DELIVERYBOY_BASE_URL.$DeliveryBoyData->DeliveryBoyID.'/'.$DeliveryBoyData->DB_ProfileImage : '');
										
										if($DeliveryBoyData->DB_ProfileStatus == '3' )
										{
											$Record = array(  
													'CustomerID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
													'FullName' 		=> getStringValue($DeliveryBoyFullName),
													'FirstName' 	=> getStringValue($DeliveryBoyData->DB_FirstName),
													'LastName' 		=> getStringValue($DeliveryBoyData->DB_LastName),
													'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
													'MobileNumber' 	=> getStringValue($DeliveryBoyData->DB_Mobile),
													'EmailAddress' 	=> getStringValue($DeliveryBoyData->DB_Email),
													'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
													'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
													'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
													'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
													'OTPCode'		=> getStringValue($OTPResponse),
													'Token'			=> $AndroidToken,
													'ProfileImage' 	=> $DeliveryBoyProfileImage,
													//'Status' => $CustomerData->C_Status,
													);		
											$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_data_fetched_success',_l('user_deliveryboy')),'data'=>$Record);														
										} else if($DeliveryBoyData->DB_ProfileStatus == '1' )
										{
											$Record = array(  
													'CustomerID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
													'FullName' 		=> getStringValue($DeliveryBoyFullName),
													'FirstName' 	=> getStringValue($DeliveryBoyData->DB_FirstName),
													'LastName' 		=> getStringValue($DeliveryBoyData->DB_LastName),
													'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
													'MobileNumber' 	=> getStringValue($DeliveryBoyData->DB_Mobile),
													'EmailAddress' 	=> getStringValue($DeliveryBoyData->DB_Email),
													'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
													'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
													'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
													'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
													'OTPCode'		=> getStringValue($OTPResponse),
													'ProfileImage' 	=> $DeliveryBoyProfileImage,
													//'Status' => $CustomerData->C_Status,
													);		
											$result = array('status'=>'info','flag'=>'4','message'=>_l('msg_data_fetched_success',_l('user_deliveryboy')),'data'=>$Record);														
										} else if($DeliveryBoyData->DB_ProfileStatus == '4' )
										{
											$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_data_fetched_success',_l('user_deliveryboy')),'data'=>(object)array());														
										}
										else{
											$Record = array(  
													'CustomerID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
													'FullName' 		=> getStringValue($DeliveryBoyFullName),
													'FirstName' 	=> getStringValue($DeliveryBoyData->DB_FirstName),
													'LastName' 		=> getStringValue($DeliveryBoyData->DB_LastName),
													'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
													'MobileNumber' 	=> getStringValue($DeliveryBoyData->DB_Mobile),
													'EmailAddress' 	=> getStringValue($DeliveryBoyData->DB_Email),
													'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
													'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
													'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
													'Location' 		=> getStringValue($DeliveryBoyData->DB_Location),
													'OTPCode'		=> getStringValue($OTPResponse),
													'ProfileImage' 	=> $DeliveryBoyProfileImage,
													//'Status' => $CustomerData->C_Status,
													);		
												$result = array('status'=>'warning','flag'=>'3','message'=>_l('msg_data_fetched_success',_l('user_deliveryboy')),'data'=>$Record);														
											}
																
									}
								else {

									//$Record['OTPCode'] = getStringValue($OTPResponse); /* 20.04.2019 9:00PM */
									
									$data['Val_DBstatus'] 			= '1';
									$data['Val_DBprofilestatus'] 	= '1';
									$data['Val_DBcountrycode'] 		= $data['Val_Countrycode'];
									$data['Val_DBmobilenumber'] 	= $data['Val_Mobilenumber'];
								
										$success = $this->Deliveryboys_model->add($data);		
				
										$deliveryboy_id = $success;
					
										if ($success) {
												
												$data['Val_Relation'] = $deliveryboy_id;
												$data['Val_Type'] = '3';
												$Lsuccess = $this->Authentication_model->AppSignup($data);		
							
												//$ProfileImage = handle_user_profile_image($user_id);			
												
												$DeliveryBoyData = $this->Deliveryboys_model->getDeliveryBoy($deliveryboy_id);

												$DeliveryBoyFullName = getStringValue($DeliveryBoyData->DB_FirstName)." ".getStringValue($DeliveryBoyData->DB_LastName);
												$Record = array(  
															'DeliveryBoyID' 	=> getStringValue($DeliveryBoyData->DeliveryBoyID),
															'FullName' 		=> getStringValue($DeliveryBoyFullName),
															'FirstName' 	=> getStringValue($DeliveryBoyData->DB_FirstName),
															'LastName' 		=> getStringValue($DeliveryBoyData->DB_LastName),
															'CountryCode'	=> getStringValue($DeliveryBoyData->DB_CountryCode),
															'MobileNumber' 	=> getStringValue($DeliveryBoyData->DB_Mobile),
															'Latitude' 		=> getStringValue($DeliveryBoyData->DB_Latitude),
															'Longitude' 	=> getStringValue($DeliveryBoyData->DB_Longitude),
															'Address'		=> getStringValue($DeliveryBoyData->DB_Address),
															// 'Location' 		=> getStringValue($CustomerData->C_Location),
															'OTPCode'		=> getStringValue($OTPResponse),
													//		'ProfileImage'=> UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage,						
													//		'Status'=> getStatus($CustomerData->C_Status),
														);		
												
												$result = array('status'=>'info','flag'=>'4','message'=>'User Created Successfully','data'=>$Record);	
							
												
											} 
										else if ($success == false) {
												$data['Val_ProfileImage'] = '';
												$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t register you. Please try again later.(404)','data'=>$Record);	
										} else{
												$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important Happened !! ','data'=>$success);	
										}
	
									$Record['OTPCode'] = (string)$OTPResponse;
									//$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_user_doesnt_exist',_l('user_customer')),'data'=>$Record);		
								}		
							}
						}
						else{
							$result = array('status'=>'error','flag'=>'2','message'=>'User not found. Check again User Type','data'=>$data);		
						} 
					}
				}
			else
				{
					$result = array('status'=>'error','flag'=>'2','message'=>$data['error'],'data'=>'Confidential');														
				
				}			
				
			} else {
				$result = array('status'=>'error','flag'=>'2','message'=>'Parameters Missing. Please Check Again. Contact your API developer.','data'=>$data);		
			}
	   
		do_action('after_user_login'); 
        $this->data        = $result;
        echo json_encode($this->data);
		
	}

	// User ResendOTP
	public function ResendOTP()
    {

		//Generating a 4 Digits OTP or verification code 
 		$OTP = rand(1000, 9999);
		$Record = array( 
						'OTPCode'=> ""
						);		
       	$data = $this->input->post();	
		if(!empty($data) && $data['Action'] = 'Send'){	
			
//			$UserData = $this->Users_model->getByMobile($data['Val_Countrycode'],$data['Val_Mobile']);

//			$mobileExist = count($UserData);			

//			if($mobileExist > 0){
				
//				if($UserData->U_OTPTrial < 3)
//					{
//						$Postdata['Val_OTP'] = $OTP;
//						$Postdata['Val_OTPTrial'] = (int)$UserData->U_OTPTrial + 1;
//						$udpate = $this->Users_model->update($Postdata,$UserData->UserID);
		
									
//						$UserArray = $UserData;

					$OTPResponse = sendOTP($data['Val_Countrycode'],$data['Val_Mobilenumber']);
					
					if(!empty($OTPResponse)){
						if(is_array($OTPResponse))
							{
								$result = array('status'=>'error','flag'=>'2','message'=>_l('msg_sms_otp_sending_issue'),'data'=>(object)$OTPResponse);														
							}
						else
							{
								$Record['OTPCode'] = (string)$OTPResponse;
								$result = array('status'=>'success','flag'=>'1','message'=>_l('msg_sms_otp_generated_success'),'data'=>$Record);		
							
							}
						}
					else
						{
							$result = array('status'=>'error','flag'=>'2','message'=>$data['error'],'data'=>$Record);														
						
						}			

//					} else {
//						$result = array('status'=>'error','flag'=>'2','message'=>'Your OTP Count expired.','data'=>'Confidential');		
//					}
					
							
//				} else {
//					$result = array('status'=>'error','flag'=>'2','message'=>'Please Sign up as a new user. Your mobile number doesnt exist. Thanks.','data'=>$data);		
//				}	
			} else {
				$result = array('status'=>'error','flag'=>'2','message'=>'Parameters Missing. Please Check Again. Contact your API developer.','data'=>$data);		
			}
	   
		do_action('after_user_login'); 
  		
        $this->data        = $result;
        echo json_encode($this->data);
		
	}
	// Sign Up For User And Service Provider 
	public function Forgot()
    {		  
		
		$data = $this->input->post();	
		
		if(!empty($data['Action']) && $data['Action'] == 'GenerateOTP') {
		
			if(!empty($data['Val_Type']) && !empty($data['Val_Mobilenumber'])) {							
			
				$UserData = $this->Users_model->getByMobile($data['Val_Mobilenumber']);
			
				if(!empty($UserData)){
				
					$OTPResponse = sendOTP($data['Val_Countrycode'],$data['Val_Mobilenumber']);
						
					if(is_array($OTPResponse)) {
						$OTPStatus = $OTPResponse['data'][0]['status'];
		
						if($OTPStatus == 'INV-NUMBER')	
							{
								$Message = 'It seems like you have entered wrong contact number. Please correct it.';	

							}
						else
							{
								$Message = 'It seems like there is some error with our scripts. Please try again later.';	
							}
						$result = array('status'=>'error','flag'=>'2','message'=>$Message,'data'=>'Confidential');	
					}	
					else {
						$OTP = (string)$OTPResponse;	
						$Record = array(  
									'UserID' => $UserData->UserID,
									'OTP'=> $OTP,
									'Status'=> getStatus($UserData->U_Status),
								);		
						$result = array('status'=>'success','flag'=>'1','message'=>'OTP Generated Successfully','data'=>$Record);	
					}	
				} else {
						$result = array('status'=>'error','flag'=>'2','message'=>'User mobile number not found. Try to register with same number. ');	
				}		
			} else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
			}	
		} else if(!empty($data['Action']) && $data['Action'] == 'ResetPassword') {
		
			if(!empty($data['Val_Type']) && !empty($data['Val_Member']) && !empty($data['Val_Password']) ) {							
			
				$UserData = $this->Users_model->get($data['Val_Member']);
			
				if(!empty($UserData)){
					
					$UpdateStatus = $this->Authentication_model->AppUpdate($data, $data['Val_Member'], $data['Val_Type']);
					
					if($UpdateStatus)
						{
	
							$Record = array(  
										'UserID' => $UserData->UserID,
										'Status'=> $UserData->U_Status,
									);		
							$result = array('status'=>'success','flag'=>'1','message'=>'Password reset successfully.','data'=>$Record);	
						}	
					else 
						{
							$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t update password.Please try again after some time.','data'=>'confidential');	
						}		
				} else {
						$result = array('status'=>'error','flag'=>'2','message'=>'User doesn\'t exist. please check again.','data'=>'confidential');	
				}		
			} else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing1');	
			}	
		} else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
		}

        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	// Sign Up For User And Service Provider 
	public function Settings()
    {		  
		
		$data = $this->input->post();	
		
		if(!empty($data['Action']) && $data['Action'] == 'RegisterDevice') {
		
			if(!empty($data['Val_Type']) && !empty($data['Val_Relation'])) {							

				$UserData = $this->Authentication_model->AppGet(NULL,array('RelationID' => $data['Val_Relation'],'M_Type' => $data['Val_Type']));
				if(!empty($UserData)){
				
					$UpdateStatus = $this->Authentication_model->AppUpdate($data, $data['Val_Relation'], $data['Val_Type']);
				
					$Record = array(  
//						'UserID' => $UserData->MemberID,
					);		
					$result = array('status'=>'success','flag'=>'1','message'=>'Device Token Updated successfully.','data'=>'Confidential');	

				} else {
					$result = array('status'=>'error','flag'=>'2','message'=>'User doesn\'t exist.');	
				}		
			} else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
			}	
		} else if(!empty($data['Action']) && $data['Action'] == 'ResetPassword') {
		
			if(!empty($data['Val_Type']) && !empty($data['Val_User']) && !empty($data['Val_Password']) ) {							
			
				$UserData = $this->Users_model->get($data['Val_User']);
			
				if(!empty($UserData)){
					
					$UpdateStatus = $this->Authentication_model->AppUpdate($data, $data['Val_User'], $data['Val_Type']);
					
					if($UpdateStatus)
						{
	
							$Record = array(  
										'UserID' => $UserData->UserID,
										'Status'=> $UserData->U_Status,
									);		
							$result = array('status'=>'success','flag'=>'1','message'=>'Password reset successfully.','data'=>$Record);	
						}	
					else 
						{
							$result = array('status'=>'error','flag'=>'2','message'=>'We couldn\'t update password.Please try again after some time.','data'=>'confidential');	
						}		
				} else {
						$result = array('status'=>'error','flag'=>'2','message'=>'User doesn\'t exist. please check again.','data'=>'confidential');	
				}		
			} else {
				$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
			}	
		} else {
			$result = array('status'=>'info','flag'=>'4','message'=>'Parameter Missing');	
		}

        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	
	// User And Service Providers Login
	public function SignOut()
    {
       	$data = $this->input->post();	
		if(!empty($data) && $data['Action'] = 'UserSignOut'){	
			
			$UserData = $this->Users_model->get($data['Val_Userid']);

			if(!empty($UserData)){
						$UserData->UserID;
						$Postdata['Val_Google'] = '1';
						$Postdata['Val_GoogleToken'] = '';
						$Postdata['Val_iOSToken'] = '';
						$Postdata['Val_AndroidToken'] = '';
						$udpate = $this->Users_model->update($Postdata,$UserData->UserID);
					
						$result = array('status'=>'success','flag'=>'1','message'=>'User Sign Out Successfully','data'=>'Confidential');														
				} else {
					$result = array('status'=>'error','flag'=>'2','message'=>'You are not registered user.	','data'=>$data);		
				}	
			} else {
				$result = array('status'=>'error','flag'=>'2','message'=>'Parameters Missing. Please Check Again. Contact your API developer.','data'=>$data);		
			}
	   
		do_action('after_user_login');
  		
        $this->data        = $result;
        echo json_encode($this->data);
		
	}
	
}
