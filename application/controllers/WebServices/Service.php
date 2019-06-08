<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Service extends W_Controller
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
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllServices'){				
			
				if( !empty($data['Val_Category'])) {
					//$CategoryData = $this->Categories_model->getCategory(NULL,array('C_Level'=>'1','C_Type'=>'1'),"ASC");

					$CategoryData = $this->Categories_model->getCategory($data['Val_Category'],array('C_Level'=>'1','C_Type'=>'1'),"ASC");
					$ServicesArray = $this->Services_model->get(NULL,array('S_CategoryID'=>$data['Val_Category'] ,'S_Type'=>'1'),"ASC");	
					$Records = array();	
						if (!empty($ServicesArray)) {	
							
							foreach($ServicesArray as $ServiceArray) {
								$DisplayImage = '';	
								$DisplayImage = (!empty($ServiceArray['S_DisplayImage']) ? UPLOAD_SERVICES_BASE_URL.$ServiceArray['ServiceID'].'/'.$ServiceArray['S_DisplayImage'] : '');	
								$Records[] = array(  
									'ServiceID' => $ServiceArray['ServiceID'],
									'Name' => $ServiceArray['S_Name'],
									'DisplayImage' => $DisplayImage,
									//'Status'=> $ServiceArray['S_Status']
									);	
								
							}								
							
		
							
						} else {		
							$Records = array();				
							//$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
						}
						$ServicesCount = (string)count($Records);
						$CategoryRecords[] 	= array(  
							'CategoryID' => $CategoryData->CategoryID,
							'Name' => $CategoryData->C_Name,
							//'DisplayImage' => $DisplayImage,
							'ServicesCount' => $ServicesCount,
							'ServicesData' => $Records,
							);	
					$CategoryData = array();
					$CategoryData['CategoriesCount'] 	= (string)count($CategoryRecords); 
					$CategoryData['CategoriesData'] 	= $CategoryRecords;
					$result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$CategoryData);
					
					
				} else {
					$CategoriesArray = $this->Categories_model->getCategory(NULL,array('C_Level'=>'1','C_Type'=>'1'),"ASC");	
					if (!empty($CategoriesArray)) {	
				
							foreach($CategoriesArray as $CategoryArray) {
						
						
								$DisplayImage = '';	
								$DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$CategoryArray['CategoryID'].'/'.$CategoryArray['C_DisplayImage'] : '');	
								
								$ServicesArray = $this->Services_model->get(NULL,array('S_CategoryID'=>$CategoryArray['CategoryID'] ,'S_Type'=>'1'),"ASC");	
								$Records = array();	
								if (!empty($ServicesArray)) {	
									
									foreach($ServicesArray as $ServiceArray) {
										$DisplayImage = '';	
										$DisplayImage = (!empty($ServiceArray['S_DisplayImage']) ? UPLOAD_SERVICES_BASE_URL.$ServiceArray['ServiceID'].'/'.$ServiceArray['S_DisplayImage'] : '');	
										$Records[] = array(  
											'ServiceID' => $ServiceArray['ServiceID'],
											'Name' => $ServiceArray['S_Name'],
											'DisplayImage' => $DisplayImage,
											//'Status'=> $ServiceArray['S_Status']
											);	
										
									}								
									
				
									
								} else {		
									$Records = array();				
									//$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
								}
								$ServicesCount = (string)count($Records);
								$CategoryRecords[] 	= array(  
									'CategoryID' => $CategoryArray['CategoryID'],
									'Name' => $CategoryArray['C_Name'],
									//'DisplayImage' => $DisplayImage,
									'ServicesCount' => $ServicesCount,
									'ServicesData' => $Records,
									);	
		
							}
							$CategoryData['CategoriesCount'] 	= (string)count($CategoryRecords); 
							$CategoryData['CategoriesData'] 	= $CategoryRecords;
							$result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$CategoryData);	
					} else {
						$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
					}
				}	
					
					
				
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetAllPrices'){				
			
				$SPricesArray = $this->Services_model->getPrices(NULL,array('S_Type'=>'1'),true);	

				if ($SPricesArray) {	
					
					foreach($SPricesArray as $SPriceArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($SPriceArray['S_DisplayImage']) ? UPLOAD_SERVICES_BASE_URL.$SPriceArray['ServiceID'].'/'.$SPriceArray['S_DisplayImage'] : '');
						
						if(!empty($SPriceArray['SP_Type']))
							{
								if($SPriceArray['SP_Type'] == '1')
									$SPriceTypeTitle = 'Installation';
								else if($SPriceArray['SP_Type'] == '2')
									$SPriceTypeTitle = 'Uninstallation';
								else
									$SPriceTypeTitle = '';
									
							}
							else
								$SPriceTypeTitle = '';
							
						$Records[] = array(  
							'ServiceID' => $SPriceArray['ServiceID'],
							'Name' => $SPriceArray['S_Name'],
							'Type' => $SPriceArray['SP_Type'],
							'TypeTitle' => $SPriceTypeTitle,
							'Amount' => $SPriceArray['SP_Amount'],
							'DisplayImage' => $DisplayImage,
							'Status'=> $SPriceArray['S_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$Records);	
				} elseif ($SPricesArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetAllUpcoming'){				
			
				$ServicesArray = $this->Services_model->get(NULL,array('S_Type'=>'2'));	

				if ($ServicesArray) {	
					
					foreach($ServicesArray as $ServiceArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($ServiceArray['S_DisplayImage']) ? UPLOAD_SERVICES_BASE_URL.$ServiceArray['ServiceID'].'/'.$ServiceArray['S_DisplayImage'] : '');	
						$Records[] = array(  
							'ServiceID' => $ServiceArray['ServiceID'],
							'Name' => $ServiceArray['S_Name'],
							'DisplayImage' => $DisplayImage,
							'Status'=> $ServiceArray['S_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$Records);	
				} elseif ($ServiceArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'GetCategories'){				
			
				$CategoriesArray = $this->Services_model->getCategories(NULL,array('ServiceID'=>$data['Val_Service']));	

				if (!empty($CategoriesArray)) {	
					foreach($CategoriesArray as $CategoryArray) {
						
						$Records[] = array(  
							'ServiceID' => $CategoryArray['S_CategoryID'],
							'Name' => $CategoryArray['SC_Name'],
							'Status'=> $CategoryArray['SC_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Service Records Fetched','data'=>$Records);	
				} elseif ($ServiceArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
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
					$ServiceFeatures =	$this->Services_model->getFeatures(NULL,array('F_ServiceID'=>$data['Val_Service'],'F_Status' => '2'),'ASC');
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

					$ServiceSteps =	$this->Services_model->getSteps(NULL,array('ST_ServiceID'=>$data['Val_Service'],'ST_Status' => '2'),'ASC');
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

						

					$ServiceWorks =	$this->Services_model->getWorks(NULL,array('W_ServiceID'=>$data['Val_Service'],'W_Status' => '2'),'ASC');
					if(!empty($ServiceWorks))
						{
							$Index = 1;
							foreach($ServiceWorks as $Work)
							{
								$DisplayImage = (!empty($Work['W_DisplayImage']) ? UPLOAD_SERVICES_WORKS_BASE_URL.$Work['SWorkID'].'/'.$Work['W_DisplayImage'] : ''); 
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


					$ServiceReviews =	$this->Services_model->getReviews(NULL,array('R_RelationID'=>$data['Val_Service']));	
					if(!empty($ServiceReviews))
						{
							$Index = 1;
							foreach($ServiceReviews as $Review)
							{
								if($Index <= 5)
									{
										$FormattedDate = date('d M,Y',strtotime($Review['R_Date']));
										array_push($ReviewsRecords,array(
															'ReviewID'=>getStringValue($Review['ReviewID']),
															'Index'=>getStringValue($Index),
															'Username'=>getStringValue($Review['R_UserName']),
															'Color'=>getRandomColor(),
															'Comment'=>getStringValue($Review['R_Comment']),
															'Location'=>getStringValue($Review['R_Location']),
															'Rating'=>getStringValue($Review['R_Rating']),
															'Date'=>getStringValue($FormattedDate),
															)
														);
									}
								
								$Index++;
							}
							
							$ReviewsTempCount = (string)count($ReviewsRecords);		
							$ReviewsCount = (string)count($ServiceReviews);		
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
		else if(!empty($data['Action']) && $data['Action'] == 'GetPackages'){				
				
				if( !empty($data['Val_Service']) )	
					{
						$Records = array();
						$PackagesArray =	$this->Services_model->getPackages(NULL,array('P_ServiceID'=>$data['Val_Service'],'P_Status' => '2'));
						$PackageData['PackagesCount'] 	= (string)count($Records); 
						$PackageData['PackagesData'] 	= $Records;
						$Index = 1;
						if (!empty($PackagesArray)) {	
					
							foreach($PackagesArray as $Pacakge) {
								
									$Records[] = array(  
											'PackageID'=>getStringValue($Pacakge['SPackageID']),
											'Index'=>getStringValue($Index),
											'Title'=>getStringValue($Pacakge['P_Title']),
											'Description'=>getStringValue($Pacakge['P_Description']),
										);	
									$Index++;
									
								}
							$PackageData['PackagesCount'] 	= (string)count($Records); 
							$PackageData['PackagesData'] 	= $Records;
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Packages Records Fetched','data'=>$PackageData);	
						} elseif ($PackagesArray == false) {
							$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$PackageData);	
						}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetOptions'){				
				
				if( !empty($data['Val_Service']) && !empty($data['Val_Package']) )	
					{
						$PackageRecords = array();
						$Packages = $data['Val_Package'];
						$PackagesArray = json_decode($Packages);


						$OptionData['PackagesCount'] 	= (string)count($PackageRecords); 
						$OptionData['PackagesData'] 	= $PackageRecords;
						
						if(!empty($PackagesArray))
							{
								$Index = 1;
								foreach($PackagesArray as $Package)
									{
										$PackageData 	=	$this->Services_model->getPackages($Package);	
										$OptionsArray 	=	$this->Services_model->getOptions(NULL,array('O_ServiceID'=>$data['Val_Service'],'O_PackageID'=>$Package));	
//										$OptionsArray =	$this->Services_model->getOptions(NULL,array('O_ServiceID'=>$data['Val_Service']));	
										$OIndex = 1;
										$Records = array();
										$OptionsCount 	= (string)count($Records); 
										$OptionsData 	= $Records;
										if (!empty($OptionsArray)) {	
									
											foreach($OptionsArray as $Option) {
												
													$Records[] = array(  
															'OptionID'=>getStringValue($Option['SPOptionID']),
															'Index'=>getStringValue($Index),
															'Title'=>getStringValue($Option['O_Title']),
															'Price'=>getStringValue($Option['O_Price']),
														);	
													$OIndex++;
													
												}
											$OptionsCount 	= (string)count($Records); 
											$OptionsData 		= $Records;
											
										} 
										$PackageRecords[] = array(  
												'PackageID'=>getStringValue($Package),
												'Title'=>getStringValue($PackageData->P_Title),
												'Index'=>getStringValue($Index),
												'OptionsCount' => $OptionsCount,
												'OptionsData' => $OptionsData
											);	
										unset($OptionsCount);	
										unset($OptionsData);											
										$OptionData['PackagesCount'] 	= (string)count($PackageRecords); 
										$OptionData['PackagesData'] 	= $PackageRecords;
										$Index++;
									}
								$result = array('status'=>'success','flag'=>'1','message'=>'Options Records Fetched','data'=>$OptionData);	
							
							} else {					
								$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$OptionData);	
							}
						
					}
				else
					{
							$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
					}	
				} 
		else if(!empty($data['Action']) && $data['Action'] == 'GetTimeslabs'){				
				
				if( !empty($data['Val_Service']) )	
					{
						$Records = array();
						$TimeslabsArray =	$this->Services_model->getTimeslabs(NULL,array('T_ServiceID'=>$data['Val_Service']));	
						$TimeslabData['TimeslabsCount'] 	= (string)count($Records); 
						$TimeslabData['TimeslabsData'] 	= $Records;
						$Index = 1;
						if (!empty($TimeslabsArray)) {	
					
							foreach($TimeslabsArray as $Timeslab) {
								
									$Records[] = array(  
											'TimeslabID'=>getStringValue($Timeslab['STimeslabID']),
											'Index'=>getStringValue($Index),
											'Title'=>getStringValue($Timeslab['T_Title']),
											'StartTime'=>getStringValue($Timeslab['T_StartTime']),
											'EndTime'=>getStringValue($Timeslab['T_EndTime']),
										);	
									$Index++;
									
								}
							$TimeslabData['TimeslabsCount'] 	= (string)count($Records); 
							$TimeslabData['TimeslabsData'] 	= $Records;
							
							$result = array('status'=>'success','flag'=>'1','message'=>'Timeslabs Records Fetched','data'=>$TimeslabData);	
						} elseif ($ServiceArray == false) {					
							$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.','data'=>$TimeslabData);	
						}
						
					}
				else
					{
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