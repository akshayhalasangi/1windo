<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Location_Model extends W_Model
{
    private $Country_data = array('id' => 'Val_id', 'name' => 'Val_name','iso2' => 'Val_iso2', 'currency' => 'Val_currency','iso3' => 'Val_iso3','phone_code' => 'Val_phone_code','capital' => 'Val_capital');

    private $states_data = array('country_id' => 'Val_country_id', 'id' => 'Val_id', 'name' => 'Val_name');

    private $cities_data = array('id' => 'Val_id', 'name' => 'Val_name', 'state_id' => 'Val_state_id', 'country_id' => 'Val_country_id');

    private $area_data = array('id' => 'Val_id', 'name' => 'Val_name', 'city_id' => 'Val_city_id');


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get products contacts
     * @param mixed $product_id
     * @param array $where perform where in query array('U_Status' => 1)
     * @return array
     */
    public function getLocations()
    {

        $result = $this->db->get('1w_tbl_country')->result_array();
        return $result;
    }

    public function getStates()
    {

        $result = $this->db->get('1w_tbl_states')->result_array();

        return $result;


    }
    public function getCities()
    {
        $result = $this->db->get('1w_tbl_cities')->result_array();

        return $result;


    }    public function getArea()
    {
        $result = $this->db->get('1w_tbl_area')->result_array();

        return $result;


    }
    public function getAreadetail($id = '')
    {

        if ($id != '') {
            $this->db->where('id', $id);
            $query = $this->db->get('1w_tbl_area')->row();

            if($query)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
    }
    public function getCitydetail($id = '')
    {

        if ($id != '') {
            $this->db->where('id', $id);
            $query = $this->db->get('1w_tbl_cities')->row();

            if($query)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
    }
    public function getStatedetail($id = '')
    {

        if ($id != '') {
            $this->db->where('id', $id);
            $query = $this->db->get('1w_tbl_states')->row();

            if($query)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
    }

    public function getCoutryDetail($id = '')
    {

        if ($id != '') {
            $this->db->where('id', $id);
            $query = $this->db->get('1w_tbl_country')->row();

            if($query)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
    }
    public function add($data)
    {

        $Country_data = array();

        foreach ($this->Country_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $Country_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before country addes', $data);

        $this->db->insert('1w_tbl_country', $Country_data);

        $productid = $this->db->insert_id();
        if ($productid) {

            do_action('after country added', $productid);


            $_new_product_log = $data['Val_name'];


            logActivity('New country Created [' . $_new_product_log . ']');
        }

        return $productid;
    }
    public function update($data, $id)
    {
        $affectedRows = 0;
        $Country_data = array();

        foreach ($this->Country_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $Country_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('id', $id);
        if ($this->db->update('1w_tbl_country', $Country_data)) {
            $affectedRows++;

            do_action('after Country updated', $id);
        }
        else{
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Country Info Updated [' . $id . ']');
            return true;
        }
        return false;
    }
    public function addState($data)
    {

        $states_data = array();

        foreach ($this->states_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $states_data[$dbfield] = $data[$field];
            }
        }


        $data = do_action('before country added', $data);


        $i=$this->db->insert('1w_tbl_states', $states_data);

        $productid = $this->db->insert_id();

        if ($productid) {

            do_action('after states added', $productid);


            $_new_product_log = $data['Val_name'];


            logActivity('New states Created [' . $_new_product_log . ']');
        }

        return $productid;
    }
    public function updatesate($data, $id)
    {
        $affectedRows = 0;
        $states_data = array();

        foreach ($this->states_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $states_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('id', $id);
        if ($this->db->update('1w_tbl_states', $states_data)) {
            $affectedRows++;

            do_action('after Country updated', $id);
        }
        else{
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Country Info Updated [' . $id . ']');
            return true;
        }
        return false;
    }



    public function addcity($data)
    {

        $cities_data = array();

        foreach ($this->cities_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $cities_data[$dbfield] = $data[$field];
            }
        }


        $data = do_action('before City added', $data);


        $i=$this->db->insert('1w_tbl_cities', $cities_data);

        $productid = $this->db->insert_id();

        if ($productid) {

            do_action('after City added', $productid);


            $_new_product_log = $data['Val_name'];


            logActivity('New City Created [' . $_new_product_log . ']');
        }

        return $productid;
    }
    public function updatecity($data, $id)
    {
        $affectedRows = 0;
        $cities_data = array();

        foreach ($this->cities_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $cities_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('id', $id);
        if ($this->db->update('1w_tbl_cities', $cities_data)) {
            $affectedRows++;

            do_action('after City updated', $id);
        }
        else{
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('City Info Updated [' . $id . ']');
            return true;
        }
        return false;
    }
    public function updatearea($data, $id)
    {
        $affectedRows = 0;
        $area_data = array();

        foreach ($this->area_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $area_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('id', $id);
        if ($this->db->update('1w_tbl_area', $area_data)) {
            $affectedRows++;

            do_action('after Area updated', $id);
        }
        else{
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('area Info Updated [' . $id . ']');
            return true;
        }
        return false;
    }
    public function DeleteCountry($id)
    {

        $affectedRows = 0;
        do_action('before_product_deleted', $id);
        $this->db->where('id', $id);
        $this->db->delete('1w_tbl_country');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_deleted');
            logActivity(_l('product_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
    public function DeleteState($id)
    {

        $affectedRows = 0;
        do_action('before_product_deleted', $id);
        $this->db->where('id', $id);
        $this->db->delete('1w_tbl_states');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_deleted');
            logActivity(_l('product_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
    public function DeleteCity($id)
    {

        $affectedRows = 0;
        do_action('before_product_deleted', $id);
        $this->db->where('id', $id);
        $this->db->delete('1w_tbl_cities');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_deleted');
            logActivity(_l('product_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
    public function DeleteArea($id)
    {

        $affectedRows = 0;
        do_action('before_product_deleted', $id);
        $this->db->where('id', $id);
        $this->db->delete('1w_tbl_area');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_deleted');
            logActivity(_l('product_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }

}
