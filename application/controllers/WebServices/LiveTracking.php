<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LiveTracking extends W_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LiveTracking_model');
		header('Content-Type: application/json');
	}

	public function index()
	{
		echo "Access Denied";
	}

	public function Fetch()
	{
		$data = $this->input->post();
		// API for the Live Tracking Location
		if (!empty($data['Action']) && $data['Action'] == 'GetLocation') {

            if(!empty($data['Order_Id']))
			    $location = $this->LiveTracking_model->getLocation($data['Order_Id']);
			if ($location) {
				$result = array('status' => 'success', 'flag' => '1', 'message' => 'Location Fetched Successfully', 'data' => $location);
			} elseif ($location == false) {
				$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Location Found.');
			}
		} 
		
		else {
			$result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
		} 

		$this->data = $result;
		echo json_encode($this->data);
	}

	public function Update()
	{
		$data = $this->input->post();

		// API for the Update Live Tracking Location

		if (!empty($data['Action']) && $data['Action'] == 'UpdateLocation') {

			if(!empty($data['Order_Id']))
			{
				$locationArray = array(
					'curr_lat'=>$data['Val_Lat'],
					'curr_lng'=>$data['Val_Lng']
				);


			    $location = $this->LiveTracking_model->updateLocation($locationArray,$data['Order_Id'],$data['Val_Type']);
			
				if ($location) {
					$result = array('status' => 'success', 'flag' => '1', 'message' => 'Location updated Successfully', 'data' => $location);
				} elseif ($location == false) {
					$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Location Found.');
				}
			}
			else{
				$result = array('status' => 'error', 'flag' => '3', 'message' => 'Missing Data');
			}
		} 
		else {
			$result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
		} 

		$this->data = $result;
		echo json_encode($this->data);
	}
}
