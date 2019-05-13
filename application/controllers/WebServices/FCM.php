<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FCM extends W_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('FCM_Model');
    
    }

    public function FetchToken(){

    }

    public function sendPushNotificationAndroid($userID, $userType, $token, $message)
    {
       // $token='AAAAyoUyYI8:APA91bEVe9lSspzqhXIdSAU_jE2BZV0Hm_5tvtX3LbU76iwKILwqIPbZQH4CejiXO4jffbrcF0DYHpuVcpq-i1Itfugn5ykHIYNdJXJhwJUK-x6Wg9g1y1gkfbKSLcJBXvfeXrv2d_oy';
        //$token='cv5zVuDRGsM:APA91bGkTOudtiE6HdDZhgU6K1ZdQG_XEJh7g_Rqfoa4kmcYlKRAFUh5t9HoWZDd8-RKrj8Pb4nzJCDMXXorIT6E5le3Wm385gFEV51mQN1cNghhBGGxgyYcPUU6Whh_gK-MeqTlhhCb';
      
        $API_SERVER_KEY = 'AIzaSyAqTKrE2lBvPyaTufCpTc-M5UVTaVO_LoQ';
        // $jsonString = $this->FCM_Model->sendPushNotificationToFCMSever($token, $message);  
        // $jsonObject = json_decode($jsonString);
        // print_r ($jsonObject);

        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            // 'registration_ids' => $token,
            'to'=> $token,
            'notification' => array('title' => '1Windo', 'body' =>  $message ),
            'data' =>array('data' => array('title' => 'Bhandara', 'message' =>  $abc ))
        );
        $headers = array(
            'Authorization: key=' . $API_SERVER_KEY,
            'Content-Type:application/json'
        );  
        
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        // Close connection      
        curl_close($ch);
        return $result;

    }

}

?>