<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Restaurants_model extends W_Model
{
    // private $restaurant_data = array('RestaurantID'=>'Val_Restaurant', 'R_FoodCategoryID'=>'Val_FoodCategory',  'R_Name'=>'Val_Rname', 'R_Description'=>'Val_Rdescription', 'R_FeaturedImage'=>'Val_Rfeaturedimage', 'R_Latitude' =>'Val_RLatitude', 'R_Longitude' =>'Val_RLongitude', 'R_Address' =>'Val_RAddress', 'R_City' =>'Val_RCity', 'R_Area' =>'Val_RArea', 'R_Neighborhood' =>'Val_RNeighborhood', 'R_Country' =>'Val_RCountry', 'R_Search_Area' =>'Val_RSearch_Area', 'R_DeliveryTime'=>'Val_Rdeliverytime','R_PriceforTwo'=>'Val_Rpricefortwo','R_Sales'=>'Val_Rsales','R_Rating'=>'Val_Rrating','R_Status'=>'Val_Rstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
    private $restaurant_data = array('RestaurantID'=>'Val_Restaurant', 'R_FoodCategoryID'=>'Val_FoodCategory',  'R_Name'=>'Val_Rname', 'R_Description'=>'Val_Rdescription', 'R_FeaturedImage'=>'Val_Rfeaturedimage', 'R_Latitude' =>'Val_RLatitude', 'R_Longitude' =>'Val_RLongitude', 'R_Address' =>'Val_RAddress', 'R_City' =>'Val_RCity', 'R_Area' =>'Val_RArea', 'R_Neighborhood' =>'Val_RNeighborhood', 'R_Country' =>'Val_RCountry', 'R_DeliveryTime'=>'Val_Rdeliverytime','R_PriceforTwo'=>'Val_Rpricefortwo','R_Sales'=>'Val_Rsales','R_Rating'=>'Val_Rrating','R_Status'=>'Val_Rstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $foods_data = array('RFoodID'=>'Val_Feature', 'F_CategoryID'=>'Val_Category', 'F_RestaurantID'=>'Val_Restaurant', 'F_Title'=>'Val_Ftitle', 'F_Description'=>'Val_Fdescription', 'F_Price'=>'Val_Fprice', 'F_DisplayImage'=>'Val_Fdisplayimage', 'F_Type'=>'Val_Ftype', 'F_Recommended'=>'Val_Frecommended','F_Status'=>'Val_Fstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $reviews_data = array('ReviewID'=>'Val_Review', 'R_Type'=>'Val_Type', 'R_RelationID'=>'Val_Restaurant', 'R_VendorID'=>'Val_Vendor', 'R_MemberID' => 'Val_Member', 'R_UserName'=>'Val_Rusername', 'R_Comment'=>'Val_Rcomment', 'R_Location'=>'Val_Rlocation', 'R_Rating'=>'Val_Rrating', 'R_Date'=>'Val_Rdate','R_Status'=>'Val_Rstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get restaurants contacts
     * @param  mixed $restaurant_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function get($restaurant_id = '', $where = array() ,$orderby = 'DESC' )
	{
		
		$this->db->where($where);
		
		if ($restaurant_id != '') {
			$this->db->where('RestaurantID', $restaurant_id);
			$query = $this->db->get(TBL_RESTAURANTS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('RestaurantID', $restaurant_id);	
					$result = $this->db->get(TBL_RESTAURANTS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		$this->db->order_by('RestaurantID', $orderby);
		$query = $this->db->get(TBL_RESTAURANTS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('RestaurantID', $orderby);
				$result = $this->db->get(TBL_RESTAURANTS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		return $result;
	}
	/**
     * Get restaurants contacts
     * @param  mixed $restaurant_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function getRestaurant($restaurant_id = '', $where = array() ,$orderby = 'DESC' )
	{
		$this->db->where($where);
		
		if ($restaurant_id != '') {
			$this->db->where('RestaurantID', $restaurant_id);
			$query = $this->db->get(TBL_RESTAURANTS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('RestaurantID', $restaurant_id);	
					$result = $this->db->get(TBL_RESTAURANTS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		$this->db->order_by('RestaurantID', $orderby);
		$query = $this->db->get(TBL_RESTAURANTS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('RestaurantID', $orderby);
				$result = $this->db->get(TBL_RESTAURANTS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		return $result;
	}
	
	/**
     * Get restaurants foods
     * @param  mixed $feature_id
     * @param  array  $where       perform where in query array('F_Status' => 1)
     * @return array
     */
	public function getFoods($food_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($food_id != '') {
			$this->db->where('RFoodID', $food_id);
			$query = $this->db->get(TBL_RESTAURANTS_FOODS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('RFoodID', $food_id);	
					$result = $this->db->get(TBL_RESTAURANTS_FOODS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		$this->db->order_by('RFoodID', $orderby);
		$query = $this->db->get(TBL_RESTAURANTS_FOODS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('RFoodID', $orderby);
				$result = $this->db->get(TBL_RESTAURANTS_FOODS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		return $result;
	}
	
	/**
     * Get restaurants steps
     * @param  mixed $review_id
     * @param  array  $where       perform where in query array('R_Status' => 1)
     * @return array
     */
	public function getReviews($review_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		
		$this->db->where($where);
		
		if ($review_id != '') {
			$this->db->where('ReviewID', $review_id);
			$query = $this->db->get(TBL_REVIEWS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('ReviewID', $review_id);	
					$result = $this->db->get(TBL_REVIEWS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		$this->db->order_by('ReviewID', $orderby);
		$query = $this->db->get(TBL_REVIEWS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
		{
			$this->db->where($where);
			$this->db->order_by('ReviewID', $orderby);
			$result = $this->db->get(TBL_REVIEWS)->result_array();
		}
		else
		{
			$result = false; 
		}
		return $result;
	}
	
	 /**
     * Get restaurants contacts
     * @param  mixed $restaurant_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function search($data, $where = array(),$orderby=NULL,$order="DESC")
	{
		$this->db->where($where);
		if(empty($orderby))
			$orderby = 'RestaurantID';
		
		if (!empty($data)) {
			$Search	= $data['Val_Search'];
			$Start	= $data['Val_Start'];
			$Limit	= $data['Val_Limit'];

			$this->db->group_start();
			$this->db->like('R_name', $Search);
			$this->db->group_end();

			$query = $this->db->get(TBL_RESTAURANTS);
			$rowcount = $query->num_rows();
			if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->group_start();
				$this->db->like('R_name', $Search);
				$this->db->group_end();
				$this->db->limit($Limit, $Start);
				$this->db->order_by($orderby, $order);
				$result = $this->db->get(TBL_RESTAURANTS)->result_array();
				//echo $this->db->last_query();
			}
			else
			{
				$result = FALSE; 
			}		
			return $result;
		}
		$this->db->order_by($orderby, $order);
		$result = $this->db->get(TBL_RESTAURANTS)->result_array();
		
		return $result;
	}

    /**
     * @param array $_POST data
     * @param restaurant_request is this request from the restaurant area
     * @return integer Insert ID
     * Add new restaurant to database
     */
    public function add($data)
	{      	
		$restaurant_data = array();
		foreach ($this->restaurant_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$restaurant_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_restaurant_added', $data);

        $this->db->insert(TBL_RESTAURANTS, $restaurant_data);

        $restaurantid = $this->db->insert_id();
		if ($restaurantid) {
          
            do_action('after_restaurant_added', $restaurantid);
			
            $_new_restaurant_log = $data['Val_Restaurantname'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_restaurant_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Restaurant Created [' . $_new_restaurant_log . ']', $_is_staff);
        }
        return $restaurantid;
    }
	
	/**
     * @param array $_POST data
     * @param restaurant_request is this request from the restaurant area
     * @return integer Insert ID
     * Add new restaurant to database
     */
    public function addFoods($data)
	{      	
		$foods_data = array();
		foreach ($this->foods_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$foods_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_restaurant_food_added', $data);

        $this->db->insert(TBL_RESTAURANTS_FOODS, $foods_data);

        $foodid = $this->db->insert_id();
		if ($foodid) {
          
            do_action('after_restaurant_food_added', $foodid);
			
            $_new_restaurant_log = $data['Val_Ftitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_restaurant_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Restaurant Food Created [' . $_new_restaurant_log . ']', $_is_staff);
        }
        return $foodid;
    }
	
	 /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update service informations
     */
    public function updateReviews($data, $reviewid)
    {            
		$affectedRows = 0;
        $reviews_data = array();
        foreach ($this->reviews_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $reviews_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('ReviewID', $reviewid);
        // $this->db->update(TBL_REVIEWS, $reviews_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_REVIEWS, $reviews_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_restaurant_review_updated', $reviewid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Restaurant Review Info Updated [' . $reviewid . ']');

            return true;
        }
        return false;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update restaurant informations
     */
    public function update($data, $restaurantid)
    {

		// print_r($data);
		// exit;

		$affectedRows = 0;
        $restaurant_data = array();
        foreach ($this->restaurant_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $restaurant_data[$dbfield] = $data[$field];
            }
        }
		//print_r($restaurant_data);
        $this->db->where('RestaurantID', $restaurantid);
        // $this->db->update(TBL_RESTAURANTS, $restaurant_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_RESTAURANTS, $restaurant_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_restaurant_updated', $restaurantid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Restaurant Info Updated [' . $restaurantid . ']');

            return true;
        }
        return false;
    }
	/**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update restaurant informations
     */
    public function updateFoods($data, $foodid)
    {            
		$affectedRows = 0;
        $foods_data = array();
        foreach ($this->foods_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $foods_data[$dbfield] = $data[$field];
            }
        }
		//print_r($restaurant_data);
        $this->db->where('RFoodID', $foodid);
        // $this->db->update(TBL_RESTAURANTS_FOODS, $foods_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_RESTAURANTS_FOODS, $foods_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_restaurant_food_updated', $foodid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Restaurant Food Info Updated [' . $foodid . ']');

            return true;
        }
        return false;
    }

	/**
     * @param  integer ID
     * @return boolean
     * Delete Restaurant
     */
	public function deleteRestaurant($id)
	{
        $affectedRows = 0;
        do_action('before_restaurant_deleted', $id);
        $this->db->where('RestaurantID', $id);
        $this->db->delete(TBL_RESTAURANTS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_restaurant_deleted');
            logActivity(_l('restaurant_deleted').' [' . $id . ']');
            return true;
        }
        return false;
    }

	/**
     * @param  integer ID
     * @return boolean
     * Delete Restaurant
     */
	public function deleteFoods($id)
	{
        $affectedRows = 0;
        do_action('before_restaurant_food_deleted', $id);
        $this->db->where('RFoodID', $id);
        $this->db->delete(TBL_RESTAURANTS_FOODS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_restaurant_food_deleted');
            logActivity(_l('restaurant_food_deleted').' [' . $id . ']');
            return true;
        }
        return false;
    }	

}
?>