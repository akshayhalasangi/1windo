<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Handles uploads error with translation texts
 * @param  mixed $error type of error
 * @return mixed
 */ 
function _1windo_upload_error($error)
{
    $phpFileUploadErrors = array(
        0 => _l('file_uploaded_success'),
        1 => _l('file_exceeds_max_filesize'),
        2 => _l('file_exceeds_maxfile_size_in_form'),
        3 => _l('file_uploaded_partially'),
        4 => _l('file_not_uploaded'),
        6 => _l('file_missing_temporary_folder'),
        7 => _l('file_failed_to_write_to_disk'),
        8 => _l('file_php_extension_blocked'),
    );

    if (isset($phpFileUploadErrors[$error]) && $error != 0) {
        return $phpFileUploadErrors[$error];
    }

    return false;
}
 

function deleteAll($str) {  
    //It it's a file.
    if (is_file($str)) { 
        //Attempt to delete it.
        return unlink($str);
    }
    //If it's a directory.
    elseif (is_dir($str)) {  
        //Get a list of the files in this directory.
        $scan = glob(rtrim($str,'/').'/*');
        //Loop through the list of files.
        foreach($scan as $index=>$path) {
            //Call our recursive function.
            deleteAll($path);
        }
        //Remove the directory itself.
        return @rmdir($str);
    }
}

//call our function

function delete_folder($action = true,$userid ){
		if($action == true)		
			$path        = get_upload_path_by_type('client') . $userid;	  
		else
			$path        = get_upload_path_by_type('escorts') . $userid;	  

		deleteAll($path);	
	}

 
function handle_company_logo_upload($logo = ''){
 	  
	if (!empty($_FILES['CompanyLogo']['name']) && $_FILES['CompanyLogo']['name'] != '') {    			 		 
		$path        = get_upload_path_by_type('settings');
		
		// Get the temp file path
		$tmpFilePath = $_FILES['CompanyLogo']['tmp_name'];
		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["CompanyLogo"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',', get_option('image_allowed_files'));
 
		    $allowed_extensions = array_map('trim', $allowed_extensions);

		    // Check for all cases if this extension is allowed
		    if (!in_array('.'.$extension, $allowed_extensions)) {
		        return 'Not_Supported';
		    }
			 
			// Setup our new file path
			
			if (!file_exists($path)) {
				mkdir($path);
				fopen($path . '/index.html', 'w');
			}
			$image_name = date('dmYHis').'.'.$extension;  
			$filename    = unique_filename($path, 'SI-Logo-'.$image_name);

			$newFilePath = $path . '/' . $filename;
			 
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();			
				update_option('company_logo', $filename);
				return true;		  						
			}

			return false;
		}
	}
}



/**
 * user profile image upload
 * @return boolean
 */
function handle_staff_profile_image($staffid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Staff_Profile_Image']['name']) && $_FILES['Val_Staff_Profile_Image']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('staffs');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('staffs') . $staffid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Staff_Profile_Image']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Staff_Profile_Image"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $staffid.'-SI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('Staff_ID', $staffid);
				$CI->db->update('staffs', array(
					'S_ProfileImage' => $ImageName
				));
				 
				rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_category_display_image($categoryid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Displayimage']['name']) && $_FILES['Val_Displayimage']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('categories');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('categories') . $categoryid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Displayimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Displayimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $categoryid.'-CI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('CategoryID', $categoryid);
				$CI->db->update(TBL_CATEGORIES, array(
					'C_DisplayImage' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}

/**
 * user profile image upload
 * @return boolean
 */
function handle_category_display_icon($categoryid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Displayicon']['name']) && $_FILES['Val_Displayicon']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('categories');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('categories') . $categoryid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Displayicon']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Displayicon"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $categoryid.'-CI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('CategoryID', $categoryid);
				$CI->db->update(TBL_CATEGORIES, array(
					'C_DisplayIcon' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_service_display_image($serviceid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Sdisplayimage']['name']) && $_FILES['Val_Sdisplayimage']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('services');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('services') . $serviceid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Sdisplayimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Sdisplayimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $serviceid.'-SI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('ServiceID', $serviceid);
				$CI->db->update(TBL_SERVICES, array(
					'S_DisplayImage' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}

/**
 * user profile image upload
 * @return boolean
 */
function handle_product_featured_image($productid)
{
	$CI =& get_instance();

	if (isset($_FILES['Val_Pfeaturedimage']['name']) && $_FILES['Val_Pfeaturedimage']['name'] != '') {
		do_action('before_upload_product_featured_image');

		$path = get_upload_path_by_type('products');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('products') . $productid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Pfeaturedimage']['tmp_name'];
		
		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Pfeaturedimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> "<b>.".$extension."</b> "._l('file_php_extension_blocked'),'flag' => 1);
			}

			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}

			$ImageName    = unique_filename($profileimagepath, $productid.'-PFI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;

			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();

				$CI->db->where('ProductID', $productid);
				$CI->db->update(TBL_PRODUCTS, array(
					'P_FeaturedImage' => $ImageName
				));

				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return true; // $ImageName;
			}
			return false;
		}
	}
	return true; // If there is no image associated with the product / service / Food
}

/**
 * user profile image upload
 * @return boolean
 */
function handle_restaurant_featured_image($restaurantid)
{
	$CI =& get_instance();
 	 
	if (isset($_FILES['Val_Rfeaturedimage']['name']) && $_FILES['Val_Rfeaturedimage']['name'] != '') {	
		do_action('before_upload_product_featured_image');

		$path = get_upload_path_by_type('restaurants');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('restaurants') . $restaurantid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Rfeaturedimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Rfeaturedimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $restaurantid.'-RFI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('RestaurantID', $restaurantid);
				$CI->db->update(TBL_RESTAURANTS, array(
					'R_FeaturedImage' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return true;//$ImageName;
			}
			return false;
		}
	}
	return true;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_food_display_image($foodid)
{
	$CI =& get_instance();
 	 
	if (isset($_FILES['Val_Fdisplayimage']['name']) && $_FILES['Val_Fdisplayimage']['name'] != '') {	
		do_action('before_upload_food_display_image');

		$path = get_upload_path_by_type('foods');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('foods') . $foodid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Fdisplayimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Fdisplayimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName   = unique_filename($profileimagepath, $foodid.'-FDI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('RFoodID', $foodid);
				$CI->db->update(TBL_RESTAURANTS_FOODS, array(
					'F_DisplayImage' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_service_works_display_image($workid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Wdisplayimage']['name']) && $_FILES['Val_Wdisplayimage']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('services-works');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('services-works') . $workid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Wdisplayimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Wdisplayimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $workid.'-SWI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				 

				$CI->db->where('SWorkID', $workid);
				$CI->db->update(TBL_SERVICES_WORKS, array(
					'W_DisplayImage' => $ImageName
				));
				 
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_user_profile_image($userid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_ProfileImage']['name']) && $_FILES['Val_ProfileImage']['name'] != '') {	
		do_action('before_upload_user_profile_image');

		$path = get_upload_path_by_type('users');
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('users') . $userid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_ProfileImage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_ProfileImage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $userid.'-PI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				$CI->load->library('image_lib');
				$config                   = array();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'thumb-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 320;
				$config['height']         = 320;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'small-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 160;
				$config['height']         = 160;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();

				$CI->db->where('User_ID', $userid);
				$CI->db->update('users', array(
					'U_ProfileImage' => $ImageName
				));
				rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_vendor_profile_image($vendorid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_ProfileImage']['name']) && $_FILES['Val_ProfileImage']['name'] != '') {	
		do_action('before_upload_vendor_profile_image');

		$path = get_upload_path_by_type('vendors');
		
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('vendors') . $vendorid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_ProfileImage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_ProfileImage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $vendorid.'-VPI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				$CI->load->library('image_lib');
				$config                   = array();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'thumb-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 320;
				$config['height']         = 320;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'small-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 160;
				$config['height']         = 160;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();

				$CI->db->where('VendorID', $vendorid);
				$CI->db->update(TBL_VENDORS, array(
					'V_ProfileImage' => $ImageName
				));
				rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_deliveryboy_profile_image($deliveryboyid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_ProfileImage']['name']) && $_FILES['Val_ProfileImage']['name'] != '') {	
		do_action('before_upload_deliveryboy_profile_image');

		$path = get_upload_path_by_type('deliveryboys');
		
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('deliveryboys') . $deliveryboyid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_ProfileImage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_ProfileImage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $deliveryboyid.'-DBPI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$CI =& get_instance();
				/*/$CI->load->library('image_lib');
				$config                   = array();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'thumb-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 320;
				$config['height']         = 320;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'small-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 160;
				$config['height']         = 160;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
*/
				$CI->db->where('DeliveryBoyID', $deliveryboyid);
				$CI->db->update(TBL_DELIVERYBOYS, array(
					'DB_ProfileImage' => $ImageName
				));
				rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_vendor_identity_front_image($vendorid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Pidcardfrontimage']['name']) && $_FILES['Val_Pidcardfrontimage']['name'] != '') {	
		do_action('before_upload_vendor_profile_image');

		$path = get_upload_path_by_type('vendors');
		
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('vendors') . $vendorid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Pidcardfrontimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Pidcardfrontimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $vendorid.'-VIFI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				/*$CI =& get_instance();
				$CI->load->library('image_lib');
				$config                   = array();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'thumb-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 320;
				$config['height']         = 320;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'small-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 160;
				$config['height']         = 160;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
*/
				$CI->db->where('P_VendorID', $vendorid);
				$CI->db->update(TBL_VENDORS_PROFILES, array(
					'P_IDCardFrontImage' => $ImageName
				));
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_vendor_identity_back_image($vendorid)
{
	$CI =& get_instance();
 	  
	if (isset($_FILES['Val_Pidcardbackimage']['name']) && $_FILES['Val_Pidcardbackimage']['name'] != '') {	
		do_action('before_upload_vendor_profile_image');

		$path = get_upload_path_by_type('vendors');
		
		if (!file_exists($path)) {
			mkdir($path);
			fopen($path . '/index.html', 'w');
		}

		$profileimagepath        = get_upload_path_by_type('vendors') . $vendorid . '/';
		// Get the temp file path
		$tmpFilePath = $_FILES['Val_Pidcardbackimage']['tmp_name'];

		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			// Getting file extension
			$path_parts         = pathinfo($_FILES["Val_Pidcardbackimage"]["name"]);
			$extension          = $path_parts['extension'];
			$allowed_extensions = explode(',',get_option('image_allowed_files'));

			if (!in_array('.'.$extension, $allowed_extensions)) {  
				//set_alert('warning', _l('file_php_extension_blocked'));
				return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
			}
			 
			// Setup our new file path
			if (!file_exists($profileimagepath)) {
				mkdir($profileimagepath);
				fopen($profileimagepath . '/index.html', 'w');
			}
			
			$ImageName    = unique_filename($profileimagepath, $vendorid.'-VIBI-' .date('dmYHis'). '.' . $extension);
	        $newFilePath = $profileimagepath .$ImageName;  
			$newFilePath = $profileimagepath . '/' . $ImageName;
			// Upload the file into the company uploads dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				/*$CI =& get_instance();
				$CI->load->library('image_lib');
				$config                   = array();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'thumb-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 320;
				$config['height']         = 320;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $newFilePath;
				$config['new_image']      = 'small-' . $ImageName;
				$config['maintain_ratio'] = TRUE;
				$config['width']          = 160;
				$config['height']         = 160;
				$CI->image_lib->initialize($config);
				$CI->image_lib->resize();
				$CI->image_lib->clear();
*/
				$CI->db->where('P_VendorID', $vendorid);
				$CI->db->update(TBL_VENDORS_PROFILES, array(
					'P_IDCardBackImage' => $ImageName
				));
				//rename( $newFilePath, $profileimagepath . '/' .$ImageName);
				return $ImageName;
			}
			return false;
		}
	}
	return false;	 	 
}
/**
 * user profile image upload
 * @return boolean
 */
function handle_vendor_works_images($vendorid)
{

	$CI =& get_instance();
 	$uploaded_files = array();
	
	if (isset($_FILES['Val_Wworksgallery']['name']) && $_FILES['Val_Wworksgallery']['name'] != '') {
		
		if(is_array($_FILES['Val_Wworksgallery']['name']))
		{
			for ($i = 0; $i < count($_FILES['Val_Wworksgallery']['name']); $i++) {
				do_action('before_upload_vendor_works_gallery', $vendorid);
			
				$path        = get_upload_path_by_type('vendors');
				if (!file_exists($path)) {
					mkdir($path);
					fopen($path . '/index.html', 'w');
				}
				$imagepath   = get_upload_path_by_type('vendors') . $vendorid . '/';
		

					// Get the temp file path
					$tmpFilePath = $_FILES['Val_Wworksgallery']['tmp_name'][$i];
				
				
					// Make sure we have a filepath
					if (!empty($tmpFilePath) && $tmpFilePath != '') {
						// Getting file extension
						$path_parts         = pathinfo($_FILES["Val_Wworksgallery"]["name"][$i]);
						$extension          = $path_parts['extension'];
						//$extension 			= strtolower($extension);
						$allowed_extensions = explode(',',get_option('image_allowed_files'));
						//$allowed_extensions = array_map('trim', $allowed_extensions);
			
						if (!in_array('.'.$extension, $allowed_extensions)) {  
							//set_alert('warning', _l('file_php_extension_blocked'));
							return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
						}
						 
						// Setup our new file path
						if (!file_exists($imagepath)) {
							mkdir($imagepath);
							fopen($imagepath . '/index.html', 'w');
						}
						
						$ImageName    = unique_filename($imagepath, $vendorid.'-VWGI-' .date('dmYHis'). '.' . $extension);
						$newFilePath = $imagepath .$ImageName;  
						$newFilePath = $imagepath . '/' . $ImageName;
						// Upload the file into the company uploads dir
						if (move_uploaded_file($tmpFilePath, $newFilePath)) {
							
							array_push($uploaded_files, $ImageName	);
						}
						// Getting file extension
					}
			}
		}
		else
		{
					
				do_action('before_upload_vendor_profile_image');
		
				$path = get_upload_path_by_type('vendors');
				
				if (!file_exists($path)) {
					mkdir($path);
					fopen($path . '/index.html', 'w');
				}
		
				$profileimagepath        = get_upload_path_by_type('vendors') . $vendorid . '/';
				// Get the temp file path
				$tmpFilePath = $_FILES['Val_Wworksgallery']['tmp_name'];
		
				// Make sure we have a filepath
				if (!empty($tmpFilePath) && $tmpFilePath != '') {
					// Getting file extension
					$path_parts         = pathinfo($_FILES["Val_Wworksgallery"]["name"]);
					$extension          = $path_parts['extension'];
					$extension = strtolower($extension);
					$allowed_extensions = explode(',',get_option('image_allowed_files'));
						$allowed_extensions = array_map('trim', $allowed_extensions);
		
					if (!in_array('.'.$extension, $allowed_extensions)) {  
						//set_alert('warning', _l('file_php_extension_blocked'));
						return array('message'=> _l('file_php_extension_blocked'),'flag' => 1);
					}
					 
					// Setup our new file path
					if (!file_exists($profileimagepath)) {
						mkdir($profileimagepath);
						fopen($profileimagepath . '/index.html', 'w');
					}
					
					$ImageName    = unique_filename($profileimagepath, $vendorid.'-VWGI-' .date('dmYHis'). '.' . $extension);
					$newFilePath = $profileimagepath .$ImageName;  
					$newFilePath = $profileimagepath . '/' . $ImageName;
					// Upload the file into the company uploads dir
					if (move_uploaded_file($tmpFilePath, $newFilePath)) {
							array_push($uploaded_files, $ImageName);
					}

				}
		
				
			
		}
		
	if (count($uploaded_files) > 0) {
				$ImageJson = json_encode($uploaded_files);
				$CI->db->where('W_VendorID', $vendorid);
						$CI->db->update(TBL_VENDORS_WORKS, array(
							'W_WorksGallery' => $ImageJson
						));
	
			return $uploaded_files;
		}
	}	
	else{
		return false;
	
	}  
	return false;	 	 
}
/**
 * Function that return full path for upload based on passed type
 * @param  string $type
 * @return string
 */
function get_upload_path_by_type($type)
{
    switch ($type) {        
        case 'users':
            return USERS_ATTACHMENTS_FOLDER;
        break;   
        case 'vendors':
            return VENDORS_ATTACHMENTS_FOLDER;
        break;   
        case 'deliveryboys':
            return DELIVERYBOYS_ATTACHMENTS_FOLDER;
        break;   
        case 'categories':
            return CATEGORIES_ATTACHMENTS_FOLDER;
        break;
        case 'services':
            return SERVICES_ATTACHMENTS_FOLDER;
        break;
        case 'services-works':
            return SERVICES_WORKS_ATTACHMENTS_FOLDER;
        break;
        case 'products':
            return PRODUCTS_ATTACHMENTS_FOLDER;
        break;
        case 'restaurants':
            return RESTAURANTS_ATTACHMENTS_FOLDER;
        break;
        case 'foods':
            return RESTAURANTS_FOODS_ATTACHMENTS_FOLDER;
        break;
        case 'staffs':
            return STAFFS_ATTACHMENTS_FOLDER;
        break;
                   
        default:
        return false;
    }
} 


 