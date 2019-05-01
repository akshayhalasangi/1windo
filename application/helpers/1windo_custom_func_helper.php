<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * String starts with
 * @param  string $haystack
 * @param  string $needle
 * @return boolean
 */
if (!function_exists('Send_iOSPushNotification')) {
    function Send_iOSPushNotification($message, $deviceToken)
    {
		
		if(!empty($deviceToken) && $deviceToken != 'Token Value')
			{
		//		$apnsHost = 'gateway.sandbox.push.apple.com';
				$apnsHost = 'gateway.push.apple.com';
				
				$apnsPort = 2195;
				$apnsCert = dirname(__FILE__).'/../../apns-production.pem';
				
				
				$streamContext = stream_context_create();
				stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
				//stream_context_set_option($streamContext, 'ssl', 'passphrase', '123456');
				
				$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
				
				if ($apns === FALSE) {
					die("Error while getting stream socket ($error): $errorString");
				}
				
				$alertString = $message;
				
				$payload['aps'] = array('alert' => $alertString , 'badge' => 1, 'sound' => 'default');
				$payload['type'] = 'HeyooNotify';
				
				$payload = json_encode($payload);
				
				$deviceToken = $deviceToken;
				
				$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
				fwrite($apns, $apnsMessage);
				
				//socket_close($apns);
				@socket_close($apns);
				fclose($apns);
			}

    }
}
/**
 * String starts with
 * @param  string $haystack
 * @param  string $needle
 * @return boolean
 */
if (!function_exists('Send_AndroidPushNotification')) {

	// function Send_AndroidPushNotification($title,$message, $deviceToken)
	function Send_AndroidPushNotification()
    {
		$CI = & get_instance();
		
// 		if(!empty($deviceToken) && $deviceToken != 'Token Value')
// 			{
// 				//Your firebase url 
// 				$DEFAULT_URL = 'https://heyoo-apis.firebaseio.com/';
// 				//Importing firebase libraries 
// 				$CI->firebase->initialize(array(
// 					'app_path'   => $DEFAULT_URL,
// 					'app_key'  => 'taPHYJrThWfQuF4EO8Ks8PvUjgFdTQmDMc1v8eN1',
// 				));
				
// 				//getting the unique id from the result 
// 				$uniqueid = $deviceToken;

// 				$alertTitle = $title;
// 				$alertString = $message;

// 				//changing the msg of the selected person on firebase with the message we want to send 
				// $CI->firebase->set($uniqueid.'/msg', $alertString);
				// $CI->firebase->set($uniqueid.'/title', $alertTitle);
				// $CI->firebase->set($uniqueid.'/data/type', 'HeyooNotify');

				$token='f148MOuCMxI:APA91bEcCgc_Jt83lrK5rpX38alx4zYGo_6Yj145QyuEzArvmqjvThePqyye_5s7F58TddNzXDJ06n-_zMPMg9D-PYRqp2JTuXJtjsJD-_CqgQIGdk8R8cObiHy69an2UaHW6PcClna0';
				//$token='cv5zVuDRGsM:APA91bGkTOudtiE6HdDZhgU6K1ZdQG_XEJh7g_Rqfoa4kmcYlKRAFUh5t9HoWZDd8-RKrj8Pb4nzJCDMXXorIT6E5le3Wm385gFEV51mQN1cNghhBGGxgyYcPUU6Whh_gK-MeqTlhhCb';
				$message="Order Booked Successfully !!!! ";
				$API_SERVER_KEY = 'AIzaSyBv8FV84Y0_3_gzduiy7MT-Jj_UkKg3Ows';
				$abc="hi";

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





// /*
// data
// dialogID: "5a7bf5eca28f9a432ebd65af"
// eventID: "2"
// type: "Event"
// */
				 
// 			}

    }
}
/**
 * String starts with
 * @param  string $haystack
 * @param  string $needle
 * @return boolean
 */
if (!function_exists('sendOTP')) {
    function sendOTP($countrycode,$mobilenumber)
    {
	
		$CI = & get_instance();
		$CI->load->library('PHPRequests');   
	
		//Your authentication key
		$APIKeyGlobal 		= get_option('sms_api_key_global');
		//Sender ID,While using route4 sender id should be 6 characters long.
		$SENDERID 			= get_option('sms_api_senderid');

		$SMSAPIURL 			= get_option('sms_api_url');

		$SMSAPIMETHOD 		= get_option('sms_method');
		
		$MobileNumber 		= $countrycode.$mobilenumber;

		$OTP = rand(1000, 9999);

		//Your message to send, Add URL encoding here.
		$message = urlencode("Your Verification Code is: $OTP");

		//Define route 
		$ROUTE = get_option('sms_api_route');

		//Prepare you post parameters
		$postData = array(
			'api_key' => $APIKeyGlobal,
			'to' => $MobileNumber,
			'message' => $message,
			'sender' => $SENDERID,
			'method' => $ROUTE
		);
		$url 				= $SMSAPIURL;
		$response 	= Requests::post($url,array(),$postData);
		$headers = array('Accept' => 'application/json');
		
		//$options = array('auth' => array('user', 'pass'));
//		$SMSGATEWAYURL = $SMSAPIURL.'api_key='.$APIKeyGlobal.'&to='.$MobileNumber.'&message='.$message.'&sender='.$SENDERID.'&method='.$SMSAPIMETHOD;
//		$request = Requests::post($SMSGATEWAYURL, $headers, $options);
//https://alerts.solutionsinfini.com/api/v4/?api_key=Ab7a73d3268ee7a8cf6ba76abb43a6871&to=919409406924&message=Testing SMS&sender=CLIENT&method=sms
/*		
		$Body = $response->body;
		$OutputJson = json_decode($Body);
		print_r($Body);
		$OutputJson = json_decode($Body);
		$OutputJson->data[0]->status;
		
		if($OutputJson->data[0]->status == 'AWAITED-DLR')
			{
				echo 'Message has been sent';	
			}
		else if($OutputJson->data[0]->status == 'INV-NUMBER')	
			{
				echo 'It seems like you have entered wrong contact number. Please correct it.';	
			}
		else
			{
				echo 'It seems like there is some error with our scripts. Please try again later.';	
			}
	
*/		
//		$Body = json_decode($Body,true);



		$Body = $response->body;
		$Body = json_decode($Body);
		

		if($Body->data[0]->status == 'AWAITED-DLR')
			{ 
				$Body = $OTP;
			}
		else
			{
				$Body = json_decode($response->body,true);											
			
			}	
        return $Body;
    }
}
