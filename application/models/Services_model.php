<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Services_model extends W_Model
{
    private $service_data = array('ServiceID'=>'Val_Service', 'S_CategoryID'=>'Val_Category',  'S_Name'=>'Val_Sname', 'S_Description'=>'Val_Sdescription', 'S_DisplayImage'=>'Val_Sdisplayimage','S_Type'=>'Val_Stype','S_Status'=>'Val_Sstatus', 'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $features_data = array('SFeatureID'=>'Val_Feature', 'F_ServiceID'=>'Val_Service', 'F_Title'=>'Val_Ftitle', 'F_Description'=>'Val_Fdescription','F_Status'=>'Val_Fstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $steps_data = array('SStepID'=>'Val_Step', 'ST_ServiceID'=>'Val_Service', 'ST_Title'=>'Val_STtitle', 'ST_Description'=>'Val_STdescription','ST_Status'=>'Val_STstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $works_data = array('SWorkID'=>'Val_Work', 'W_ServiceID'=>'Val_Service', 'W_Title'=>'Val_Wtitle', 'W_DisplayImage'=>'Val_Wdisplayimage','W_Status'=>'Val_Wstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $reviews_data = array('ReviewID'=>'Val_Review', 'R_Type'=>'Val_Type', 'R_RelationID'=>'Val_Service', 'R_VendorID'=>'Val_Vendor','R_MemberID' => 'Val_Member', 'R_UserName'=>'Val_Rusername', 'R_Comment'=>'Val_Rcomment', 'R_Location'=>'Val_Rlocation', 'R_Rating'=>'Val_Rrating', 'R_Date'=>'Val_Rdate','R_Status'=>'Val_Rstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $packages_data = array('SPackageID'=>'Val_Package', 'P_ServiceID'=>'Val_Service', 'P_Title'=>'Val_Ptitle', 'P_Description'=>'Val_Pdescription','P_Status'=>'Val_Pstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $options_data = array('SPOptionID'=>'Val_Option', 'O_ServiceID'=>'Val_Service', 'O_PackageID'=>'Val_Package', 'O_Title'=>'Val_Otitle', 'O_Price'=>'Val_Oprice','O_Status'=>'Val_Ostatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    private $timeslabs_data = array('STimeslabID'=>'Val_Timeslab', 'T_ServiceID'=>'Val_Service', 'T_Title'=>'Val_Ttitle', 'T_StartTime'=>'Val_Tstarttime', 'T_EndTime'=>'Val_Tendtime', 'T_Status'=>'Val_Tstatus' ,'RowAddedDttm' => '', 'RowUpdatedDttm' => '');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get services contacts
     * @param  mixed $service_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function get($service_id = '', $where = array() ,$orderby = 'DESC' )
	{
		$this->db->where($where);
		
		if ($service_id != '') {
			$this->db->where('ServiceID', $service_id);
			$query = $this->db->get(TBL_SERVICES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('ServiceID', $service_id);	
					$result = $this->db->get(TBL_SERVICES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('ServiceID', $orderby);
		$query = $this->db->get(TBL_SERVICES);

		$rowcount = $query->num_rows();
		if($rowcount > 0)
        {
            $this->db->where($where);
            $this->db->order_by('ServiceID', $orderby);
            $result = $this->db->get(TBL_SERVICES)->result_array();
        }
		else
        {
            $result = false; 
        }

		return $result;
	}
	 /**
     * Get services contacts
     * @param  mixed $service_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function getService($service_id = '', $where = array() ,$orderby = 'DESC' )
	{
		$this->db->where($where);
		
		if ($service_id != '') {
			$this->db->where('ServiceID', $service_id);
			$query = $this->db->get(TBL_SERVICES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where('ServiceID', $service_id);	
					$result = $this->db->get(TBL_SERVICES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('ServiceID', $orderby);
		$query = $this->db->get(TBL_SERVICES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('ServiceID', $orderby);
				$result = $this->db->get(TBL_SERVICES)->result_array();
			}
		else
			{
				$result = false; 
            }
		
		return $result;
	}
	
	/**
     * Get services features
     * @param  mixed $feature_id
     * @param  array  $where       perform where in query array('F_Status' => 1)
     * @return array
     */
	public function getFeatures($feature_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($feature_id != '') {
			$this->db->where('SFeatureID', $feature_id);
			$query = $this->db->get(TBL_SERVICES_FEATURES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('SFeatureID', $feature_id);	
					$result = $this->db->get(TBL_SERVICES_FEATURES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('SFeatureID', $orderby);
		$query = $this->db->get(TBL_SERVICES_FEATURES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('SFeatureID', $orderby);
				$result = $this->db->get(TBL_SERVICES_FEATURES)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	
	/**
     * Get services steps
     * @param  mixed $step_id
     * @param  array  $where       perform where in query array('S_Status' => 1)
     * @return array
     */
	public function getSteps($step_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($step_id != '') {
			$this->db->where('SStepID', $step_id);
			$query = $this->db->get(TBL_SERVICES_STEPS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('SStepID', $step_id);	
					$result = $this->db->get(TBL_SERVICES_STEPS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('SStepID', $orderby);
		$query = $this->db->get(TBL_SERVICES_STEPS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('SStepID', $orderby);
				$result = $this->db->get(TBL_SERVICES_STEPS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}

	/**
     * Get services works
     * @param  mixed $work_id
     * @param  array  $where       perform where in query array('W_Status' => 1)
     * @return array
     */
	public function getWorks($work_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($work_id != '') {
			$this->db->where('SWorkID', $work_id);
			$query = $this->db->get(TBL_SERVICES_WORKS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('SWorkID', $work_id);	
					$result = $this->db->get(TBL_SERVICES_WORKS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('SWorkID', $orderby);
		$query = $this->db->get(TBL_SERVICES_WORKS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('SWorkID', $orderby);
				$result = $this->db->get(TBL_SERVICES_WORKS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}

	/**
     * Get services steps
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
     * Get services packages
     * @param  mixed $package_id
     * @param  array  $where       perform where in query array('P_Status' => 1)
     * @return array
     */
	public function getPackages($package_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($package_id != '') {
			$this->db->where('SPackageID', $package_id);
			$query = $this->db->get(TBL_SERVICES_PACKAGES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('SPackageID', $package_id);	
					$result = $this->db->get(TBL_SERVICES_PACKAGES)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('SPackageID', $orderby);
		$query = $this->db->get(TBL_SERVICES_PACKAGES);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('SPackageID', $orderby);
				$result = $this->db->get(TBL_SERVICES_PACKAGES)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	
	/**
     * Get services options
     * @param  mixed $option_id
     * @param  array  $where       perform where in query array('P_Status' => 1)
     * @return array
     */
	public function getOptions($option_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($option_id != '') {
			$this->db->where('SPOptionID', $option_id);
			$query = $this->db->get(TBL_SERVICES_OPTIONS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('SPOptionID', $option_id);	
					$result = $this->db->get(TBL_SERVICES_OPTIONS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('SPOptionID', $orderby);
		$query = $this->db->get(TBL_SERVICES_OPTIONS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('SPOptionID', $orderby);
				$result = $this->db->get(TBL_SERVICES_OPTIONS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}	
	
	
	/**
     * Get services timeslabs
     * @param  mixed $timeslab_id
     * @param  array  $where       perform where in query array('T_Status' => 1)
     * @return array
     */
	public function getTimeslabs($timeslab_id = '', $where = array() ,$orderby = 'DESC'  )
	{
		$this->db->where($where);
		
		if ($timeslab_id != '') {
			$this->db->where('STimeslabID', $timeslab_id);
			$query = $this->db->get(TBL_SERVICES_TIMESLABS);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->where('STimeslabID', $timeslab_id);	
					$result = $this->db->get(TBL_SERVICES_TIMESLABS)->row();
				}
			else
				{
					$result = false; 
				}		
			
			return $result;
		}
		
		
		$this->db->order_by('STimeslabID', $orderby);
		$query = $this->db->get(TBL_SERVICES_TIMESLABS);
		
		$rowcount = $query->num_rows();
		if($rowcount > 0)
			{
				$this->db->where($where);
				$this->db->order_by('STimeslabID', $orderby);
				$result = $this->db->get(TBL_SERVICES_TIMESLABS)->result_array();
			}
		else
			{
				$result = false; 
			}		
		
		
		return $result;
	}
	
	

	 /**
     * Get services contacts
     * @param  mixed $service_id
     * @param  array  $where       perform where in query array('U_Status' => 1)
     * @return array
     */
	public function search($data, $where = array() )
	{
		$this->db->where($where);
		
		if (!empty($data)) {
		
			$Search	= $data['Val_Search'];
			$Start	= $data['Val_Start'];
			$Limit	= $data['Val_Limit'];

			$this->db->group_start();
			$this->db->like('S_Name', $Search);
			$this->db->group_end();
			
			$query = $this->db->get(TBL_SERVICES);
			
			$rowcount = $query->num_rows();
			if($rowcount > 0)
				{
					$this->db->where($where);
					$this->db->group_start();
					$this->db->like('S_Name', $Search);
					$this->db->group_end();
					$this->db->limit($Limit, $Start);
					$result = $this->db->get(TBL_SERVICES)->result_array();
				}
			else
				{
					$result = FALSE; 
				}		
			
			return $result;
		}
//		$this->db->order_by('ServiceID', 'DESC');
//		$result = $this->db->get(TBL_SERVICES)->result_array();
		
		return $result;
	}

    /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function add($data)
	{
		$service_data = array();
		foreach ($this->service_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$service_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_added', $data);

        $this->db->insert(TBL_SERVICES, $service_data);

        $serviceid = $this->db->insert_id();
		if ($serviceid) {
          
            do_action('after_service_added', $serviceid);
			$data['Val_Service'] = $serviceid;
			
			
            $_new_service_log = $data['Val_Servicename'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $serviceid;
    }
	 /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addFeatures($data)
	{
		$features_data = array();
		foreach ($this->features_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$features_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_feature_added', $data);

        $this->db->insert(TBL_SERVICES_FEATURES, $features_data);

        $featureid = $this->db->insert_id();
		if ($serviceid) {
          
            do_action('after_service_feature_added', $featureid);
			
            $_new_service_log = $data['Val_Ftitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Feature Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $featureid;
    }
	 /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addSteps($data)
	{
		$steps_data = array();
		foreach ($this->steps_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$steps_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_step_added', $data);

        $this->db->insert(TBL_SERVICES_STEPS, $steps_data);

        $stepid = $this->db->insert_id();
		if ($stepid) {
          
            do_action('after_service_step_added', $stepid);
			
            $_new_service_log = $data['Val_STtitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Step Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $stepid;
    }
	 /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addWorks($data)
	{
		$works_data = array();
		foreach ($this->works_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$works_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_work_added', $data);

        $this->db->insert(TBL_SERVICES_WORKS, $works_data);

        $workid = $this->db->insert_id();
		if ($workid) {
          
            do_action('after_service_work_added', $workid);
			
            $_new_service_log = $data['Val_Wtitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Work Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $workid;
    }
	
	 /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addPackages($data)
	{
		$packages_data = array();
		foreach ($this->packages_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$packages_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_package_added', $data);

        $this->db->insert(TBL_SERVICES_PACKAGES, $packages_data);

        $packageid = $this->db->insert_id();
		if ($packageid) {
          
            do_action('after_service_package_added', $packageid);
			
            $_new_service_log = $data['Val_Ptitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Pacakge Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $packageid;
    }
	 /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addOptions($data)
	{
		$options_data = array();
		foreach ($this->options_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$options_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_option_added', $data);

        $this->db->insert(TBL_SERVICES_OPTIONS, $options_data);

        $optionid = $this->db->insert_id();
		if ($optionid) {
          
            do_action('after_service_option_added', $optionid);
			
            $_new_service_log = $data['Val_Otitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Option Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $optionid;
    } /**
     * @param array $_POST data
     * @param service_request is this request from the service area
     * @return integer Insert ID
     * Add new service to database
     */
    public function addTimeslabs($data)
	{
		$timeslabs_data = array();
		foreach ($this->timeslabs_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$timeslabs_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_timeslab_added', $data);

        $this->db->insert(TBL_SERVICES_TIMESLABS, $timeslabs_data);

        $timeslabid = $this->db->insert_id();
		if ($timeslabid) {
          
            do_action('after_service_timeslab_added', $timeslabid);
			
            $_new_service_log = $data['Val_Otitle'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Timeslab Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $timeslabid;
    }
	
	 public function addCategories($data)
	{
		$service_data = array();
		foreach ($this->service_data as $dbfield => $field) {
			if (isset($data[$field])) {
				$service_data[$dbfield] = $data[$field];
			}
		}
	
		$data                = do_action('before_service_added', $data);

        $this->db->insert(TBL_SERVICES, $service_data);

        $serviceid = $this->db->insert_id();
        if ($serviceid) {
          
            do_action('after_service_added', $serviceid);
            $_new_service_log = $data['Val_Mobilenumber'];
         
            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_service_log .= ' From Staff: ' . get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Service Created [' . $_new_service_log . ']', $_is_staff);
        }

        return $serviceid;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update service informations
     */
    public function update($data, $serviceid)
    {
		$affectedRows = 0;
        $service_data = array();
        foreach ($this->service_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $service_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('ServiceID', $serviceid);
        // $this->db->update(TBL_SERVICES, $service_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES, $service_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_updated', $serviceid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Info Updated [' . $serviceid . ']');

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
    public function updateFeatures($data, $featureid)
    {
		$affectedRows = 0;
        $features_data = array();
        foreach ($this->features_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $features_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('SFeatureID', $featureid);
        // $this->db->update(TBL_SERVICES_FEATURES, $features_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_FEATURES, $features_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_feature_updated', $featureid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Feature Info Updated [' . $featureid . ']');

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
    public function updateSteps($data, $stepid)
    {
		$affectedRows = 0;
        $steps_data = array();
        foreach ($this->steps_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $steps_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('SStepID', $stepid);
        // $this->db->update(TBL_SERVICES_STEPS, $steps_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_STEPS, $steps_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_step_updated', $stepid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Step Info Updated [' . $stepid . ']');

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
    public function updateWorks($data, $workid)
    {
		$affectedRows = 0;
        $works_data = array();
        foreach ($this->works_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $works_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('SWorkID', $workid);
        // $this->db->update(TBL_SERVICES_WORKS, $works_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_WORKS, $works_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_work_updated', $workid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Work Info Updated [' . $workid . ']');

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
            do_action('after_service_review_updated', $reviewid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Review Info Updated [' . $reviewid . ']');

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
    public function updatePackages($data, $packageid)
    {
		$affectedRows = 0;
        $packages_data = array();
        foreach ($this->packages_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $packages_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('SPackageID', $packageid);
        // $this->db->update(TBL_SERVICES_PACKAGES, $packages_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_PACKAGES, $packages_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_package_updated', $packageid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Package Info Updated [' . $packageid . ']');

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
    public function updateOptions($data, $optionid)
    {
		$affectedRows = 0;
        $options_data = array();
        foreach ($this->options_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $options_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('SPOptionID', $optionid);
        // $this->db->update(TBL_SERVICES_OPTIONS, $options_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_OPTIONS, $options_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_option_updated', $optionid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Option Info Updated [' . $optionid . ']');

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
    public function updateTimeslabs($data, $timeslabid)
    {
		$affectedRows = 0;
        $timeslabs_data = array();
        foreach ($this->timeslabs_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $timeslabs_data[$dbfield] = $data[$field];
            }
        }
		//print_r($service_data);
        $this->db->where('STimeslabID', $timeslabid);
        // $this->db->update(TBL_SERVICES_TIMESLABS, $timeslabs_data);
		//echo $this->db->last_query();
        if ($this->db->update(TBL_SERVICES_TIMESLABS, $timeslabs_data)) {
            $affectedRows++;
			//echo 'asd';
            do_action('after_service_timeslab_updated', $timeslabid);
        }
		else{
			//echo 'tes';
		}
        if ($affectedRows > 0) {
            logActivity('Service Timeslab Info Updated [' . $timeslabid . ']');

            return true;
        }

        return false;
    }	
	
	
	/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function delete($id)
	{
        $affectedRows = 0;
        do_action('before_service_deleted', $id);
        $this->db->where('ServiceID', $id);
        $this->db->delete(TBL_SERVICES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_deleted');
            logActivity(_l('service_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
	/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteService($id)
	{

        $affectedRows = 0;
        do_action('before_service_deleted', $id);
        $this->db->where('ServiceID', $id);
        $this->db->delete(TBL_SERVICES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_deleted');
            logActivity(_l('service_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
	/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteFeature($id)
	{

        $affectedRows = 0;
        do_action('before_service_feature_deleted', $id);
        $this->db->where('SFeatureID', $id);
        $this->db->delete(TBL_SERVICES_FEATURES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_feature_deleted');
            logActivity(_l('service_feature_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
	/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteStep($id)
	{

        $affectedRows = 0;
        do_action('before_service_step_deleted', $id);
        $this->db->where('SStepID', $id);
        $this->db->delete(TBL_SERVICES_STEPS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_step_deleted');
            logActivity(_l('service_step_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }

/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteWork($id)
	{

        $affectedRows = 0;
        do_action('before_service_work_deleted', $id);
        $this->db->where('SWorkID', $id);
        $this->db->delete(TBL_SERVICES_WORKS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_work_deleted');
            logActivity(_l('service_work_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deletePackage($id)
	{

        $affectedRows = 0;
        do_action('before_service_package_deleted', $id);
        $this->db->where('SPackageID', $id);
        $this->db->delete(TBL_SERVICES_PACKAGES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_package_deleted');
            logActivity(_l('service_package_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteOption($id)
	{

        $affectedRows = 0;
        do_action('before_service_option_deleted', $id);
        $this->db->where('SPOptionID', $id);
        $this->db->delete(TBL_SERVICES_OPTIONS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_option_deleted');
            logActivity(_l('service_option_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }
/**
     * @param  integer ID
     * @return boolean
     * Delete Service
     */
	public function deleteTimeslab($id)
	{

        $affectedRows = 0;
        do_action('before_service_timeslab_deleted', $id);
        $this->db->where('STimeslabID', $id);
        $this->db->delete(TBL_SERVICES_TIMESLABS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_service_timeslab_deleted');
            logActivity(_l('service_timeslab_deleted').' [' . $id . ']');
            return true;
        }

        return false;

    }

}
?>