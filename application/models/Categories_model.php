<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Categories_model extends W_Model
{
    private $category_data = array('CategoryID'=>'Val_Category',  'C_Name'=>'Val_Categoryname', 'C_DisplayImage'=>'Val_Displayimage', 'C_DisplayIcon'=>'Val_Displayicon','C_Type'=>'Val_Type','C_Level'=>'Val_Level','C_Parent'=>'Val_Parent','C_Status'=>'Val_Status', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get categories contacts
     * @param  mixed $category_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
    
    public function getDataOne($groceryName){

        $this->db->select('*');
        $this->db->from('1w_tbl_categories');
        $this->db->where('C_Type',2);
        $this->db->where('C_Level',1);
        $this->db->where('C_Name',$groceryName);
        return $this->db->get()->result_array();
        

    } 

       public function getDataTwo($vegetableName){

        $this->db->select('*');
        $this->db->from('1w_tbl_categories');
        $this->db->where('C_Type',2);
        $this->db->where('C_Level',1);
        $this->db->where('C_Name',$vegetableName);
        return $this->db->get()->result_array();
    } 

	public function get($category_id = '', $where = array() ,$orderby = 'DESC')
	{		
       // return $this->getAppCategory($category_id, $where, $orderby);
        return $this->getCategory($category_id, $where, $orderby);
    }
    

    public function getCategoriesByType($typeID)
    {
        $this->db->select('*');
        $this->db->from('1w_tbl_categories');
        $this->db->where('C_Type',$typeID);
        $result = $this->db->last_query();
        return $this->db->get()->result_array();
    }
	
	 /**
     * Get categories contacts
     * @param  mixed $category_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function getCategory($category_id = '', $where = array() ,$orderby = 'DESC', $orderby_col = 'CategoryID', $where_in = array())
	{		
        $this->db->where($where);
        if(count($where_in) > 0){
            $this->db->where_in('CategoryID', $where_in);
        }
		
		if ($category_id != '') {
            $this->db->where('CategoryID', $category_id);
            if(count($where_in) > 0){
                $this->db->where_in('CategoryID', $where_in);
            }
            //$this->db->limit(5);
			$query = $this->db->get(TBL_CATEGORIES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
                    $this->db->where('CategoryID', $category_id);	
                    if(count($where_in) > 0){
                        $this->db->where_in('CategoryID', $where_in);
                    }
                    //$this->db->limit(5);
					$result = $this->db->get(TBL_CATEGORIES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}		
		
		$this->db->order_by($orderby_col, $orderby);
		$query = $this->db->get(TBL_CATEGORIES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
                $this->db->where($where);
                if(count($where_in) > 0){
                    $this->db->where_in('CategoryID', $where_in);
                }
                $this->db->order_by($orderby_col, $orderby);
                //$this->db->limit(5);
				$result = $this->db->get(TBL_CATEGORIES)->result_array();
			}
		else
			{
				$result = false; 
            }
		return $result;
    }
    
    /*  Niranjan 22.04.2019 03:44PM
     *  Get App categories contacts 
     */
	public function getAppCategory($category_id = '', $where = array() ,$orderby = 'DESC', $orderby_col = 'CategoryID', $where_in = array())
	{		
        $this->db->where($where);
        if(count($where_in) > 0){
            $this->db->where_in('CategoryID', $where_in);
        }
		
		if ($category_id != '') {
            $this->db->where('CategoryID', $category_id);
            if(count($where_in) > 0){
                $this->db->where_in('CategoryID', $where_in);
            }
            $this->db->limit(5);
			$query = $this->db->get(TBL_CATEGORIES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
                    $this->db->where('CategoryID', $category_id);	
                    if(count($where_in) > 0){
                        $this->db->where_in('CategoryID', $where_in);
                    }
                    $this->db->limit(5);
					$result = $this->db->get(TBL_CATEGORIES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}		
		
		$this->db->order_by($orderby_col, $orderby);
		$query = $this->db->get(TBL_CATEGORIES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
                $this->db->where($where);
                if(count($where_in) > 0){
                    $this->db->where_in('CategoryID', $where_in);
                }
                $this->db->order_by($orderby_col, $orderby);
                $this->db->limit(5);
				$result = $this->db->get(TBL_CATEGORIES)->result_array();
			}
		else
			{
				$result = false; 
            }
		return $result;
    }


    /**
     * Get categories Hierarchy
     * @return array
     */
    public function getCategoryHierarchy($categoryType = 1){

        $result = $this->db->query("SELECT DISTINCT c2.CategoryID as subParentID, c1.C_Level as level, c2.C_Name as SubParent, c3.C_Name as ParentName from `1w_tbl_categories` as c1 LEFT OUTER JOIN `1w_tbl_categories` c2 ON c1.C_Parent = c2.CategoryID LEFT OUTER JOIN `1w_tbl_categories` c3 ON c2.C_Parent = c3.CategoryID where c1.C_Type = $categoryType and c1.C_Level = 3 group by c3.CategoryID order by c1.C_Level");

        $rowcount = $result->num_rows();
        if($rowcount > 0)
        {
            return $result->result_array();
        }else{
            return false;
        }		
        
    }
	
	 /**
     * Get categories contacts
     * @param  mixed $category_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function search($data, $where = array() )
	{
		$this->db->where($where);
		
		if (!empty($data)) {
		
			$Search	= $data['Val_Search'];

			$this->db->group_start();
			$this->db->like('C_Name', $Search);
			$this->db->group_end();			

			$query = $this->db->get(TBL_CATEGORIES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->group_start();
				$this->db->like('C_Name', $Search);
				$this->db->group_end();
				
				$result = $this->db->get(TBL_CATEGORIES)->result_array();
			}
			else
			{
				$result = FALSE; 
			}
			
			return $result;
		}
//		$this->db->order_by('CategoryID', 'DESC');
//		$result = $this->db->get(TBL_CATEGORIES)->result_array();
		
		return $result;
	}

    /**
     * @param array $_POST data
     * @param category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public function add($data)
	{
		$category_data = array();
		foreach ($this->category_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$category_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_category_added', $data);

        $this->db->insert(TBL_CATEGORIES, $category_data);

        $categoryid = $this->db->insert_id();
		if ($categoryid) {
          
            do_action('after_category_added', $categoryid);
			$data['Val_Category'] = $categoryid;
			$_new_category_log = $data['Val_Categoryname'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_category_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Category Created [' . $_new_category_log . ']', $_is_staff);
        }

        return $categoryid;
    }
	
	 public function addCategories($data)
	{
		$category_data = array();
		foreach ($this->category_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$category_data[$dbfield] = $data[$field];
			}
		}
	
		$data = do_action('before_category_added', $data);

        $this->db->insert(TBL_CATEGORIES, $category_data);

        $categoryid = $this->db->insert_id();
        if ($categoryid) {
          
            do_action('after_category_added', $categoryid);
            $_new_category_log = $data['Val_Mobilenumber'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_category_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Category Created [' . $_new_category_log . ']', $_is_staff);
        }

        return $categoryid;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public function update($data, $categoryid)
    {
		$affectedRows = 0;
        $category_data = array();
        foreach ($this->category_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $category_data[$dbfield] = $data[$field];
            }
        }
        $this->db->where('CategoryID', $categoryid);
        if ($this->db->update(TBL_CATEGORIES, $category_data)) {
            do_action('after_category_updated', $categoryid);
            logActivity('Category Info Updated [' . $categoryid . ']');
            return true;
        }
		else{
            // print_r($this->db->error());
            logActivity('Category Updation Error: '.json_encode($this->db->error()));
            return false;
		}
    }

	/**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
	public function deleteCategory($id)
	{
        $affectedRows = 0;
        do_action('before_category_deleted', $id);
        $this->db->where("CategoryID = $id OR C_Parent = $id");
        $this->db->delete(TBL_CATEGORIES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_category_deleted');
            logActivity(_l('category_deleted').' [' . $id . ']');
            return true;
        }

        return false;
    }

}
?>