<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Category extends W_Controller
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
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllCategories'){	

			//$CategoriesArray = $this->Categories_model->get();

				if(!empty($data['Val_Type'])) {
					
					$BusinessType=$data['Val_Type'];

					if($BusinessType == '1' || $BusinessType == '2' || $BusinessType == '3'){


						$CatArray=$this->Categories_model->getCategoriesByType($BusinessType);
				
					
					
					foreach($CatArray as $CategoryArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$CategoryArray['CategoryID'].'/'.$CategoryArray['C_DisplayImage'] : '');	
						$Records[] = array(  
							'CategoryID' => $CategoryArray['CategoryID'],
							'Name' => $CategoryArray['C_Name'],
							'Status'=> $CategoryArray['C_Status']
							);	

					}								
					
				
					$result = array('status'=>'success','flag'=>'1','message'=>'Category Records Fetched','data'=>$Records);	
					}
				} elseif ($CategoryArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}
		
		else if(!empty($data['Action']) && $data['Action'] == 'SearchCategoriesByType'){	

			$CategoriesArray = $this->Categories_model->get();

				if(!empty($data['Val_Type'])) {
					
					$BusinessType=$data['Val_Type'];

					if($BusinessType == '1' || $BusinessType == '2' || $BusinessType == '3'){


						$CatArray=$this->Categories_model->getCategoriesByType($BusinessType);
				
					
					
					foreach($CatArray as $CategoryArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$CategoryArray['CategoryID'].'/'.$CategoryArray['C_DisplayImage'] : '');	
						$Records[] = array(  
							'CategoryID' => $CategoryArray['CategoryID'],
							'Name' => $CategoryArray['C_Name'],
							'Status'=> $CategoryArray['C_Status']
							);	

					}								
					
				
					$result = array('status'=>'success','flag'=>'1','message'=>'Category Records Fetched','data'=>$Records);	
					}
				} elseif ($CategoryArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}

		else if(!empty($data['Action']) && $data['Action'] == 'SearchCategories'){				
			
				
				$CategoriesArray = $this->Categories_model->search($data);	

				if ($CategoriesArray) {	
					
					foreach($CategoriesArray as $CategoryArray) {
						
						$DisplayImage = '';	
						$DisplayImage = (!empty($CategoryArray['C_DisplayImage']) ? UPLOAD_CATEGORIES_BASE_URL.$CategoryArray['CategoryID'].'/'.$CategoryArray['C_DisplayImage'] : '');	
						$Records[] = array(  
							'CategoryID' => $CategoryArray['CategoryID'],
							'Name' => $CategoryArray['C_Name'],
							'Status'=> $CategoryArray['C_Status']
							);	

					}								
					

					$result = array('status'=>'success','flag'=>'1','message'=>'Category Records Fetched','data'=>$Records);	
				} elseif ($CategoriesArray == false) {					
					$result = array('status'=>'error','flag'=>'2','message'=>'No Entries Found.');	
				}
		}
		else if(!empty($data['Action']) && $data['Action'] == 'SingleCategory'){				
				
				if( !empty($data['Val_Category'])) {
					
					$CategoryData = $this->Categories_model->get($data['Val_Categoryid'],array(),true);
					$CategoryShares = $this->Categories_model->getCategoryShares($data['Val_Categoryid']);
					
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($CategoryData->UserID);
					
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
					
								
					if(!empty($CategoryShares))
						{
							foreach($CategoryShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName ,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['C_Admin'],
													'Status'=>$Users['C_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['C_Admin'] === '2')
									{
										$SAdmin = $Users['C_Admin'];
									}
									
											
							}
							
						$CategoryCount = count($CategoryShares);		
						}
					else{
								$CategoryCount = 0 ;
							}	
						

					
					
					if ($CategoryData) {
					
						$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','CategoryID'=>$data['Val_Categoryid']))) ;

						$CategoryName = ($CategoryData->UserID == $data['Val_Userid']) ? $CategoryData->C_Name : $CategoryData->C_Name. " (Shared)" ;				
						
						$Admin = ($CategoryData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
						
						$Records[] = array(  
							'CategoryID' => $CategoryData->CategoryID,
							'UserID' => $CategoryData->UserID,
							'Name'=> $CategoryName,
							'OriginalName'=> $CategoryData->C_Name,							
							'Color'=> $CategoryData->C_Color,
							'Shared'=> $CategoryData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$CategoryCount,
							'MemberCount'=> (string)($CategoryCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($CategoryData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$CategoryData->UserID.'/'.$CategoryData->U_ProfileImage : ''),
							);
											
												
						$result = array('status'=>'success','flag'=>'1','message'=>'Category Record Fetched','data'=>$Records);	
					} elseif ($CategoryArray === FALSE) {					
						$result = array('status'=>'error','flag'=>'2','message'=>'Category Record Not Fetched','data'=>$data['Val_Categoryid']);	
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
	// Edit Details Of Category 
	public function Details()
    {		     
	
		$data = $this->input->post();	
	
		if( !empty($data) && $data['Action'] == 'Add' ){	
					$success = $this->Categories_model->add($data);	
						
					if ($success) {
						
						$result = array('status'=>'success','flag'=>'1','message'=>'Category Details Added Successfully','data'=>'Confidential');	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Category Details Not Updated','data'=>'Confidential');	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
			
		} 
		else if( !empty($data) && $data['Action'] == 'Update' ){	
		
			if( !empty($data['Val_Categoryid']) && !empty($data['Val_Userid'])) {

					$data['Val_Status'] = '2';
					$success = $this->Categories_model->update($data,$data['Val_Categoryid']);	
					
					$CategoryData = $this->Categories_model->get($data['Val_Categoryid'],array(),true);
					$CategoryShares = $this->Categories_model->getCategoryShares($data['Val_Categoryid']);
					
			
					/*if(!empty($CategoryShares))
						{
							foreach($CategoryShares as $Users)
							{
								array_push($Shares,array('UserID'=>$Users['UserID'],'ProfileImage'=>UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage']));
							}
						}

					$CategoryCount = count($CategoryShares);	

					$Records[] = array(  
						'CategoryID' => $CategoryArray->CategoryID,
						'UserID' => $CategoryArray->UserID,
						'Name'=> $CategoryArray->C_Name ,
						'Color'=> $CategoryArray->C_Color,
						'Shared'=> $CategoryArray->C_Shared,
						'SharedCount'=> (string)$CategoryCount,
						'SharedUsers'=> $Shares,
						);
						
						*/
					
					$Members = array();
					$SAdmin = '1';
					
					$UserData = $this->Users_model->get($CategoryData->UserID);
					
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
					
								
					if(!empty($CategoryShares))
						{
							foreach($CategoryShares as $Users)
							{
								$FullName = ($Users['UserID'] != $data['Val_Userid']) ? $Users['U_FirstName']." ".$Users['U_LastName'] : 'You' ;
								array_push($Members,array(
													'UserID'=>$Users['UserID'],
													'UserJID'=>$Users['U_JID'],
													'FullName'=>$FullName,
													'FirstName'=>$Users['U_FirstName'],
													'LastName'=>$Users['U_LastName'],
													'Admin'=> $Users['C_Admin'],
													'Status'=>$Users['C_Status'],
													'ProfileImage'=>(!empty($Users['U_ProfileImage'])) ? UPLOAD_USER_BASE_URL.$Users['UserID'].'/'.$Users['U_ProfileImage'] : '' 
													)
												);
								
								if($data['Val_Userid'] === $Users['UserID'] && $Users['C_Admin'] === '2')
									{
										$SAdmin = $Users['C_Admin'];
									}
									
											
							}
						$CategoryCount = count($CategoryShares);		
						}
					else{
						$CategoryCount = 0;		
					}	
					
					$UnpublishedEventsCount = count($this->Events_model->get('',array('E_Status'=>'1','CategoryID'=>$data['Val_Categoryid']))) ;						
						
					$CategoryName = ($CategoryData->UserID == $data['Val_Userid']) ? $CategoryData->C_Name : $CategoryData->C_Name. " (Shared)" ;				

					$Admin = ($CategoryData->UserID === $data['Val_Userid']) ? '2' : $SAdmin;				
					
				//	$CategoryCount = count($CategoryShares);
						
					$Records[] = array(  
							'CategoryID' => $CategoryData->CategoryID,
							'UserID' => $CategoryData->UserID,
							'Name'=> $CategoryName,
							'OriginalName'=> $CategoryData->C_Name,							
							'Color'=> $CategoryData->C_Color,
							'Shared'=> $CategoryData->C_Shared,
							'Admin'=> $Admin,
							'SharedCount'=> (string)$CategoryCount,
							'MemberCount'=> (string)($CategoryCount + 1),
							'Members'=> $Members,
							'UnpublishedCount'=> (string)$UnpublishedEventsCount,
							'UserProfileImage'=> (!empty($CategoryData->U_ProfileImage) ? UPLOAD_USER_BASE_URL.$CategoryData->UserID.'/'.$CategoryData->U_ProfileImage : ''),
							);	
	
					if ($success) {
						$result = array('status'=>'success','flag'=>'1','message'=>'Category Information Updated Successfully','data'=>$Records);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Category Information Not Updated','data'=>$data['Val_Categoryid']);	
					}
					else{
						$result = array('status'=>'warning','flag'=>'3','message'=>'Something Important','data'=>$success);	
					}
				} else {
					$result = array('status'=>'info','flag'=>'4','message'=>'Parameters Missing.');		
				}	
		} 
		else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
				if (!$data['Val_Categoryid']) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
					$CategoryShares = $this->Categories_model->getCategoryShares($data['Val_Categoryid']);
					
					$CategoryData = $this->Categories_model->get($data['Val_Categoryid']);
				
					$success = $this->Categories_model->delete($data['Val_Categoryid']);	
					if ($success) {
					
						if(!empty($CategoryShares))
							{
								foreach($CategoryShares as $SharedUser)
									{
										$NotificationsData = $this->Notifications_model->get('',array('N_ToUserID'=>$SharedUser['UserID'],'N_Type'=>'1','N_RelationType'=>'1','N_RelationID'=>$data['Val_Categoryid']));
										
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
					
					
						$result = array('status'=>'success','flag'=>'1','message'=>'Category Deleted Successfully','data'=>$data['Val_Categoryid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','flag'=>'2','message'=>'Category was not deleted.','data'=>$data['Val_Categoryid']);	
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