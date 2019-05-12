<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Products_model extends W_Model
{
    private $product_data = array('ProductID'=>'Val_Product', 'P_CategoryID'=>'Val_Category',  'P_Name'=>'Val_Pname', 'P_Description'=>'Val_Pdescription', 'P_FeaturedImage'=>'Val_Pfeaturedimage','P_Gallery'=>'Val_Pgallery','P_Price'=>'Val_Pprice','P_Attributes'=>'Val_Pattributes','P_AttributeValues'=>'Val_Pattributevalues','P_Status'=>'Val_Pstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');
	
    private $reviews_data = array('ReviewID'=>'Val_Review', 'R_Type'=>'Val_Type', 'R_RelationID'=>'Val_Service',  'R_VendorID'=>'Val_Vendor','R_MemberID' => 'Val_Member', 'R_UserName'=>'Val_Rusername', 'R_Comment'=>'Val_Rcomment', 'R_Location'=>'Val_Rlocation', 'R_Rating'=>'Val_Rrating', 'R_Date'=>'Val_Rdate','R_Status'=>'Val_Rstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $attributes_data = array('PAttributeID'=>'Val_Attribute', 'A_Type'=>'Val_Atype', 'A_Title'=>'Val_Atitle', 'A_Description'=>'Val_Adescription','A_Status'=>'Val_Astatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $attributes_values_data = array('PAValueID'=>'Val_AValue', 'V_AttributeID'=>'Val_Attribute', 'V_Title'=>'Val_Vtitle', 'V_Value'=>'Val_Vvalue','V_Status'=>'Val_Vstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');



    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get products contacts
     * @param  mixed $product_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function get($product_id = '', $where = array() ,$orderby = 'DESC' )
	{
		
		$this->db->where($where);
		
		if ($product_id != '') {
			$this->db->where('ProductID', $product_id);
			$query = $this->db->get(TBL_PRODUCTS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('ProductID', $product_id);	
					$result = $this->db->get(TBL_PRODUCTS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('ProductID', $orderby);
		$query = $this->db->get(TBL_PRODUCTS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('ProductID', $orderby);
				$result = $this->db->get(TBL_PRODUCTS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		return $result;
	}
    /**
     * Get products contacts
     * @param  mixed $product_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function getProduct($product_id = '', $where = array() ,$orderby = 'DESC' )
	{
		
		$this->db->where($where);
		
		if ($product_id != '') {
			$this->db->where('ProductID', $product_id);
			$query = $this->db->get(TBL_PRODUCTS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('ProductID', $product_id);	
					$result = $this->db->get(TBL_PRODUCTS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('ProductID', $orderby);
		$query = $this->db->get(TBL_PRODUCTS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('ProductID', $orderby);
				$result = $this->db->get(TBL_PRODUCTS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	

	/**
     * Get products steps
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
     * Get products attributes
     * @param  mixed $attribute_id
     * @param  array  $where       perform where in query array('A_Status' => 1)
     * @return array
     */
	public function getAttributes($attribute_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		
		$this->db->where($where);
		
		if ($attribute_id != '') {
			$this->db->where('PAttributeID', $attribute_id);
			$query = $this->db->get(TBL_PRODUCTS_ATTRIBUTES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('PAttributeID', $attribute_id);	
					$result = $this->db->get(TBL_PRODUCTS_ATTRIBUTES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('PAttributeID', $orderby);
		$query = $this->db->get(TBL_PRODUCTS_ATTRIBUTES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('PAttributeID', $orderby);
				$result = $this->db->get(TBL_PRODUCTS_ATTRIBUTES)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	
	/**
     * Get products attributes values
     * @param  mixed $value_id
     * @param  array  $where       perform where in query array('V_Status' => 1)
     * @return array
     */
	public function getAttribValues($value_id = '', $where = array() ,$orderby = 'DESC')
	{
		$this->db->where($where);

		if ($value_id != '') {
			$this->db->where('PAValueID', $value_id);
			$query = $this->db->get(TBL_PRODUCTS_ATTRIB_VALUES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->where('PAValueID', $value_id);	
				$result = $this->db->get(TBL_PRODUCTS_ATTRIB_VALUES)->row();
			}
			else
			{
				$result = false; 
			}		
			
			return $result;
		}
		
		$this->db->order_by('PAValueID', $orderby);
		$query = $this->db->get(TBL_PRODUCTS_ATTRIB_VALUES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
		{
			$this->db->where($where);
			$this->db->order_by('PAValueID', $orderby);
			$result = $this->db->get(TBL_PRODUCTS_ATTRIB_VALUES)->result_array();
		}
		else
		{
			$result = false; 
		}	
		return $result;
	}	
	

	 /**
     * Get products contacts
     * @param  mixed $product_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function search($data, $where = array(),$orderby=NULL,$order="DESC")
	{
		
		
		$this->db->where($where);
		if(empty($orderby))
			$orderby = 'ProductID';
		
		if (!empty($data)) {
		
			$Search	= $data['Val_Search'];
			$Start	= $data['Val_Start'];
			$Limit	= $data['Val_Limit'];

			$this->db->group_start();
			$this->db->like('P_name', $Search);
			$this->db->group_end();
			

			$query = $this->db->get(TBL_PRODUCTS);
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->group_start();
					$this->db->like('P_name', $Search);
					$this->db->group_end();
					$this->db->limit($Limit, $Start);
						
					$this->db->order_by($orderby, $order);
					
					$result = $this->db->get(TBL_PRODUCTS)->result_array();
					$thisissomething= $this->db->last_query();
				}
			else
				{
					$result = FALSE; 
				}		
			
			return $result;
		}
		$this->db->order_by($orderby, $order);
		$result = $this->db->get(TBL_PRODUCTS)->result_array();
		
		return $result;
	}

    /**
     * @param array $_POST data
     * @param product_request is this request from the product area
     * @return integer Insert ID
     * Add new product to database
     */
    public function add($data)
	{      	
     
		
		$product_data = array();
		foreach ($this->product_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$product_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_product_added', $data);

        $this->db->insert(TBL_PRODUCTS, $product_data);

        $productid = $this->db->insert_id();
		if ($productid) {
          
            do_action('after_product_added', $productid);
			
			
            $_new_product_log = $data['Val_Pname'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_product_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Product Created [' . $_new_product_log . ']', $_is_staff);
        }

        return $productid;
    }

    /**
     * @param array $_POST data
     * @param product_request is this request from the product area
     * @return integer Insert ID
     * Add new product to database
     */
    public function addAttributes($data)
	{      	
     
		
		$attributes_data = array();
		foreach ($this->attributes_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$attributes_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_product_attribute_added', $data);

        $this->db->insert(TBL_PRODUCTS_ATTRIBUTES, $attributes_data);

        $attributeid = $this->db->insert_id();
		if ($attributeid) {
          
            do_action('after_product_attribute_added', $attributeid);
			
			
            $_new_product_log = $data['Val_Atitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_product_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Product Attribute Created [' . $_new_product_log . ']', $_is_staff);
        }

        return $attributeid;
    }

	/**
     * @param array $_POST data
     * @param product_request is this request from the product area
     * @return integer Insert ID
     * Add new product to database
     */
    public function addAttribValues($data)
	{      	
     
		
		$attributes_values_data = array();
		foreach ($this->attributes_values_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$attributes_values_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_product_attribute_value_added', $data);

        $this->db->insert(TBL_PRODUCTS_ATTRIB_VALUES, $attributes_values_data);

        $attribvalueid = $this->db->insert_id();
		if ($attribvalueid) {
          
            do_action('after_product_attribute_value_added', $attribvalueid);
			
			
            $_new_product_log = $data['Val_Vtitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_product_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Product Attribute Created [' . $_new_product_log . ']', $_is_staff);
        }

        return $attribvalueid;
    }

	

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update product informations
     */
    public function update($data, $productid)
    {
		$affectedRows = 0;
		$product_data = array();
		
        foreach ($this->product_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $product_data[$dbfield] = $data[$field];
            }
        }

        $this->db->where('ProductID', $productid);
        // $this->db->update(TBL_PRODUCTS, $product_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_PRODUCTS, $product_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_product_updated', $productid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Product Info Updated [' . $productid . ']');
            return true;
        }
        return false;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update product informations
     */
    public function updateAttributes($data, $attributeid)
    {
		$affectedRows = 0;
        $attributes_data = array();
        foreach ($this->attributes_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $attributes_data[$dbfield] = $data[$field];
            }
        }
		//print_r($product_data);
        $this->db->where('PAttributeID', $attributeid);
        // $this->db->update(TBL_PRODUCTS_ATTRIBUTES, $attributes_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_PRODUCTS_ATTRIBUTES, $attributes_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_product_attribute_updated', $attributeid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Product Attributes Info Updated [' . $attributeid . ']');

            return true;
        }

        return false;
    }
	/**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update product informations
     */
    public function updateAttribValues($data, $attribvalueid)
    {            
		$affectedRows = 0;
        $attributes_values_data = array();
        foreach ($this->attributes_values_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $attributes_values_data[$dbfield] = $data[$field];
            }
        }
		//print_r($product_data);
        $this->db->where('PAValueID', $attribvalueid);
        // $this->db->update(TBL_PRODUCTS_ATTRIB_VALUES, $attributes_values_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_PRODUCTS_ATTRIB_VALUES, $attributes_values_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_product_attribute_value_updated', $attribvalueid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Product Attributes Values Info Updated [' . $attribvalueid . ']');

            return true;
        }

        return false;
    }
    public function addVendorWithProducts($vendorid, $attribvalueid)
    {

        $data=['product_id'=>$attribvalueid,'vendor_id'=>$vendorid];
        $this->db->insert('1w_tbl_product_vendor', $data);
        $attribvalueid = $this->db->insert_id();

        if  ($attribvalueid) {
            logActivity('New Product Attribute Created [' . $attribvalueid . ']');

            return true;
        }

        return false;
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
            do_action('after_product_review_updated', $reviewid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Product Review Info Updated [' . $reviewid . ']');

            return true;
        }

        return false;
    }	/**
     * @param  integer ID
     * @return boolean
     * Delete Product
     */
	public function delete($id)
	{

        $affectedRows = 0;
        do_action('before_product_deleted', $id);
        $this->db->where('ProductID', $id);
        $this->db->delete(TBL_PRODUCTS);

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
	/**
     * @param  integer ID
     * @return boolean
     * Delete Product
     */
	public function deleteAttributes($id)
	{

        $affectedRows = 0;
        do_action('before_product_attribute_deleted', $id);
        $this->db->where('PAttributeID', $id);
        $this->db->delete(TBL_PRODUCTS_ATTRIBUTES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_attribute_deleted');
            logActivity(_l('product_attribute_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
	/**
     * @param  integer ID
     * @return boolean
     * Delete Product
     */
	public function deleteAttribValues($id)
	{

        $affectedRows = 0;
        do_action('before_product_attribute_value_deleted', $id);
        $this->db->where('PAValueID', $id);
        $this->db->delete(TBL_PRODUCTS_ATTRIB_VALUES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_product_attribute_value_deleted');
            logActivity(_l('product_attribute_value_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }

}
?>