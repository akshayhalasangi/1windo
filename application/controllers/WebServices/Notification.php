<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Notification extends W_Controller
{
    public function __construct()
    {
				parent::__construct();
	//			$this->load()->model('notifications_model');


				header('Content-Type: application/json');

    }
	
	public function index()
    {
		echo "Access Denied";	
    }
	
	public function Fetch()
    {		     
		$data = $this->input->post();	
		
		if(!empty($data['Action']) && $data['Action'] == 'GetAllNotifications'){				
			
				$NotificationArray = $this->Notifications_model->get('',array('N_ToUserID' => $data['Val_Member']));	
				
				if ($NotificationArray) {	

					for($i = 0; $i < count($NotificationArray); $i++){
						
						$UserData = $this->Users_model->get($NotificationArray[$i]['N_FromUserID']);
						
						if(in_array($NotificationArray[$i]['N_Type'],array('1','2','4','5','7','8','12')))
							{
								$FromUserData = $this->Users_model->get($NotificationArray[$i]['N_FromUserID']);
								$Title = (!empty($FromUserData) ? $FromUserData->U_FirstName." ".$FromUserData->U_LastName : '(Removed)');
								$DisplayFullName = (!empty($FromUserData) ? $FromUserData->U_FirstName." ".$FromUserData->U_LastName : '(Removed)');
								$IMG = (!empty($UserData) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : '');
							}
						else if	(in_array($NotificationArray[$i]['N_Type'],array('6','9','10','11')))
							{
								$CalendarData = $this->Calendars_model->get($NotificationArray[$i]['N_RelationID']);
								$Title = (!empty($CalendarData) ? $CalendarData->C_Name : '(Removed)');
								$IMG = (!empty($UserData) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : '');
							}
						else if	(in_array($NotificationArray[$i]['N_Type'],array('3')))
							{
								$EventData = $this->Events_model->get($NotificationArray[$i]['N_RelationID']);
								$Title = (!empty($EventData) ? $EventData->E_Title : '(Removed)');
								$IMG = (!empty($UserData) ? UPLOAD_USER_BASE_URL.$UserData->UserID.'/'.$UserData->U_ProfileImage : '');
							}
						else{
								$Title = '';
								$IMG = '';
							}	
						
						$Clickable = rand(1,2);
						$Records[] = array(  
							'NotifyID' => $NotificationArray[$i]['NotificationID'],
							'Title' => $NotificationArray[$i]['N_Title'],
							'DisplayImage' => $IMG,
							'FullName' => $DisplayFullName,
							'Description' => $NotificationArray[$i]['N_Description'],
							);	
									

					}								
					
					

					$result = array('status'=>'success','message'=>'Notification Records Fetched.','dataCount'=>count($Records),'data'=>$Records);	
				} elseif ($NotificationArray == false) {					
					$result = array('status'=>'error','message'=>'No Notifications.');	
				}
		} 
		else if(!empty($data['Action']) && $data['Action'] == 'Update')
		{
			if(!empty($data['Val_Token']) && !empty($data['Val_UserId'])&& !empty($data['Val_Mobilenumber'])&& !empty($data['Val_Countrycode'])&& !empty($data['Val_Type'])&& !empty($data['Val_Token_Type']))
			{
				if($data['Val_Token_Type']=1)
				{
					$wheredata["M_Mobile"]= $data['Val_Mobilenumber'];
					$wheredata["RelationID"]= $data['Val_UserId'];
					$wheredata["M_Countrycode"]= $data['Val_Countrycode'];
					$wheredata["M_Type"]= $data['Val_Type'];
					$PostData["M_AndroidToken"]= $data['Val_Token'];
			
$result= $this->Notifications_model->updateToken($PostData, $wheredata);
				}
			}
		}
		
		
		
		else {
			$result = array('status'=>'warning','message'=>'Paramater Missing');	
		}
		
        $this->data = $result;
        echo json_encode($this->data);	
	}
	
	
    /* Set notification read */
  	public function Details()
    {		 
		$data = $this->input->post();
		
		if( !empty($data) && $data['Action'] === 'MarkasRead' ){	
			$data['UserID'] = $data['Val_Userid'];
			$success = $this->Notifications_model->set_notifications_read($data,$direct = true); 
			if ($success) {
				$result = array('status'=>'success','message'=>'Notifications Marked as Read Successfully','data'=>'Confidential');	
			} else if ($success == false) {
				$result = array('status'=>'error','message'=>'Notifications Marked not as Read','data'=>'Confidential');	
			} else{
				$result = array('status'=>'warning','message'=>'Something Important','data'=>$success);	
			}
		} else if( !empty($data) && $data['Action'] === 'TakeAction' ){	
			$data['UserID'] = $data['Val_Userid'];
			$data['NotifyID'] = $data['Val_Notifyid'];
			$data['Action'] = $data['Val_Action'];
			
			$success = $this->Notifications_model->set_notifications_action($data,false); 
			if ($success) {
				$result = array('status'=>'success','message'=>'Notification Updated Successfully','data'=>'Confidential');	
			} else if ($success == false) {
				$result = array('status'=>'error','message'=>'Notifications couldnt updated.','data'=>'Confidential');	
			} else{
				$result = array('status'=>'warning','message'=>'Something Important','data'=>$success);	
			}
		} else if( !empty($data) && $data['Action'] == 'Delete' ){	
													
				if (empty($data['Val_Notifyid'])) {
						$result = array('status'=>'warning','message'=>'Parameters Missing.','data'=>$data);	
					}
				else{
				
					$success = $this->Notifications_model->delete($data['Val_Notifyid']);	
					
					if ($success) {
						$result = array('status'=>'success','message'=>'Notification Deleted Successfully','data'=>$data['Val_Notifyid']);	
					} else if ($success == false) {
						$result = array('status'=>'error','message'=>'Notification was not deleted.','data'=>$data['Val_Notifyid']);	
					}
					else{
						$result = array('status'=>'warning','message'=>'Something Important','data'=>$success);	
					}
				}				
			}	

		$this->data = $result;
        echo json_encode($this->data);	
	
    }
	
}
