<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Areas extends W_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Area_model');
		header('Content-Type: application/json');
	}

	public function index()
	{
		echo "Access Denied";
	}

	public function Fetch()
	{
		$data = $this->input->post();
		// API for the Countries
		if (!empty($data['Action']) && $data['Action'] == 'GetCountries') {

			$Countries = $this->Area_model->getCountry();
			if ($Countries) {
				$result = array('status' => 'success', 'flag' => '1', 'message' => 'Countries Records Fetched', 'data' => $Countries);
			} elseif ($Countries == false) {
				$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Entries Found.');
			}
		} 
		// API for the States
		else if (!empty($data['Action']) && $data['Action'] == 'GetStates') {

			if (!empty($data['Country_Id']))

				$Countries = $this->Area_model->getState($data['Country_Id']);
			if ($Countries) {
				$result = array('status' => 'success', 'flag' => '1', 'message' => 'State Records Fetched', 'data' => $Countries);
			} elseif ($Countries == false) {
				$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Entries Found.');
			}
		}
		// API for the Cities
		else if  (!empty($data['Action']) && $data['Action'] == 'GetCities') {

			if (!empty($data['State_ID']))
				$Countries = $this->Area_model->getCity($data['State_ID']);
			if ($Countries) {
				$result = array('status' => 'success', 'flag' => '1', 'message' => 'City Records Fetched', 'data' => $Countries);
			} elseif ($Countries == false) {
				$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Entries Found.');
			}
		} 
		// API for the Areas
		else if  (!empty($data['Action']) && $data['Action'] == 'GetArea') {

			if (!empty($data['City_ID']))
				$Areas = $this->Area_model->getArea($data['City_ID']);
			if ($Areas) {
				$result = array('status' => 'success', 'flag' => '1', 'message' => 'City Records Fetched', 'data' => $Areas);
			} elseif ($Areas == false) {
				$result = array('status' => 'error', 'flag' => '2', 'message' => 'No Entries Found.');
			}
		} 
		else {
			$result = array('status' => 'info', 'flag' => '4', 'message' => 'Paramater Missing');
		} 

		$this->data = $result;
		echo json_encode($this->data);
	}
}
