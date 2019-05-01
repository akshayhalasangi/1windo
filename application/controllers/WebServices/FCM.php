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


    public function PushNotifications(){

        // if(!empty($data['Action']) && $data['Action'] == 'PushNotifications'){

        //     if( !empty($data['Token'])) {
                $token='f1nEg4pYvZM:APA91bGrAn40LiX7Imt-FlRQnqeSnxPF7LNkq0ymLHmAvQkkMVcLyz9bHG_PfdNncLKHnLGZTD0o0fFg9eHeKgUOIneB-NIW0L5S1qPIfjxD0-INxU2i0iAYZQ0X6IFRnK8WtOK8bfgi';
                $message="Order Booked Successfully !!!! ";
                $API_SERVER_KEY = 'AIzaSyDHUWvm0FRmlMYn_W2ODvje2RxMA0-6lNQ';

                // $jsonString = $this->FCM_Model->sendPushNotificationToFCMSever($token, $message);  
                // $jsonObject = json_decode($jsonString);
                // print_r ($jsonObject);

                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'registration_ids' => $token,
                    'notification' => array('title' => 'CodeCastra', 'body' =>  $message ),
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

        //     }
        //     else{

        //     }
        // }
        // else{

        // }
}


}

?>