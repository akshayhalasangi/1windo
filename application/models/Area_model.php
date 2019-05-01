<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Area_model extends W_Model
{
 
    public function __construct()
    {
        parent::__construct();
    }

    /* Get registered admin's email
     * @param mixed $email
     * @return array
     */
   public function getCountry()
   {
        $this->db->select('*');
        $this->db->from('1w_tbl_country');
       return $this->db->get()->result_array();
   }
   public function getState($country_id)
   {
        $this->db->select('*');
        $this->db->from('1w_tbl_states');
        $this->db->where('country_id',$country_id);
       return $this->db->get()->result_array();
   }

   public function getCity($state_id)
   {
        $this->db->select('*');
        $this->db->from('1w_tbl_cities');
        $this->db->where('state_id',$state_id);
       return $this->db->get()->result_array();
   }

   public function getArea($city_id)
   {
        $this->db->select('*');
        $this->db->from('1w_tbl_area');
        $this->db->where('city_id',$city_id);
       return $this->db->get()->result_array();
   }
}
