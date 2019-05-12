<?php
defined('BASEPATH') or exit('No direct script access allowed');
//header('Content-Type: text/html; charset=utf-8');
/**
 * @deprecated
 */
/**
 * Function that will read file and will replace string
 * @param  string $path    path to file
 * @param  string $find    find string
 * @param  string $replace replace string
 * @return boolean
 */
function replace_in_file($path, $find, $replace)
{
    $CI =& get_instance();
    $CI->load->helper('file');
    @chmod($path, FILE_WRITE_MODE);
    $file = read_file($path);
    $file = trim($file);
    $file = str_replace($find, $replace, $file);
    $file = trim($file);
    if (!$fp = fopen($path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
        return false;
    }
    flock($fp, LOCK_EX);
    fwrite($fp, $file, strlen($file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($path, FILE_READ_MODE);

    return true;
}

/**
 * Generate encryption key for app-config.php
 * @return stirng
 */
function generate_encryption_key()
{
    $CI =& get_instance();
    // In case accessed from my_functions_helper.php
    $CI->load->library('encryption');
    $key = bin2hex($CI->encryption->create_key(16));

    return $key;
}
/**
 * Function used to validate all recaptcha from google reCAPTCHA feature
 * @param  string $str
 * @return boolean
 */
function do_recaptcha_validation($str = '')
{
    $CI =& get_instance();
    $CI->load->library('form_validation');
    $google_url = "https://www.google.com/recaptcha/api/siteverify";
    $secret     = get_option('recaptcha_secret_key');
    $ip         = $CI->input->ip_address();
    $url        = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
    $curl       = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res, true);
    //reCaptcha success check
    if ($res['success']) {
        return true;
    } else {
        $CI->form_validation->set_message('recaptcha', _l('recaptcha_error'));

        return false;
    }
}
/**
 * Get current date format from options
 * @return string
 */
function get_current_date_format($php = false)
{
    $format = get_option('dateformat');
    $format = explode('|', $format);

    $hook_data = do_action('get_current_date_format', array(
        'format' => $format,
        'php' => $php
    ));

    $format = $hook_data['format'];
    $php    = $php;

    if ($php == false) {
        return $format[1];
    } else {
        return $format[0];
    }
}
/**
 * Check if current user is staff
 * @param  mixed $adminid
 * @return boolean if user is not staff
 */
function is_admin($staffid = '')
{
    $_staffid = get_staff_user_id();
    if (is_numeric($staffid)) {
        $_staffid = $staffid;
    }
    $CI =& get_instance();
    $CI->db->select('1');
    $CI->db->where('S_IsAdmin', 1);
    $CI->db->where('S_Status', 2);
    $CI->db->where('Staff_ID', $_staffid);
    $staff = $CI->db->get('staffs')->row();
    
     
    if ($staff) {  
        return true;
    }
 
    return false;
}


/**
 * Check if current user is staffid
 * @param  mixed $staffid
 * @return boolean if user is not staffid
 */
function get_admin($staffid = '')
{
    $_staffid = get_staff_user_id();
    if (is_numeric($staffid)) {
        $_staffid = $staffid;
    }
    $CI =& get_instance();
   // $CI->db->select('1');
    $CI->db->where('S_IsAdmin', 1);
    $CI->db->where('S_Status', 2);
    $CI->db->where('Staff_ID', $_staffid);
    $staff = $CI->db->get('staffs')->row();
     
    if ($staff) {   
        return $staff;
    }
 
    return false;
}

/**
 * Check if current user is staffid
 * @param  mixed $staffid
 * @return boolean if user is not staffid
 */
function getIsAdmin()
{ 
    $CI =& get_instance();
    $CI->db->where('S_IsAdmin', 1); 
    $CI->db->where('S_Status', 2);  
    $staff = $CI->db->get('staffs')->row();
    
    if ($staff) {  
        return $staff;
    }
 
    return '0';
}

/**
 * Check if current user is staff
 * @param  mixed $staffid
 * @return boolean if user is not staff
 */
function getAdminData($staffid = '')
{
    $_staffid = get_staff_user_id();
    if (is_numeric($staffid)) {
        $_staffid = $staffid;
    }
    $CI =& get_instance();
   // $CI->db->select('1');
    $CI->db->where('S_IsActive', 1);
    $CI->db->where('S_Status', 2);
    $CI->db->where('Staff_ID', $_staffid);
    $staff = $CI->db->get('staffs')->row();
     
    if ($staff) {   
        return $staff;
    }
 
    return false;
}

function getRedirectUrl($staffid = '')
{
    $_staffid = get_staff_user_id();
    if (is_numeric($staffid)) {
        $_staffid = $staffid;
    }
    $CI =& get_instance();
    // $CI->db->select('1');
    $CI->db->where('S_IsActive', 1);
    $CI->db->where('S_Status', 2);
    $CI->db->where('Staff_ID', $_staffid);
    $staff = $CI->db->get('staffs')->row();

    if ($staff->S_IsAdmin == 0) {
        return SUPER_ADMIN_URL ;
    }
    if ($staff->S_IsAdmin == 1) {
        return ADMIN_URL;
    }

    return ADMIN_URL;
}



function camelToSnake($string){
    $snake = preg_replace('/[A-Z]/', '_$0', $string);
    $snake = strtolower($snake);
    return ltrim($snake, '_');
}
/**
 * Is user logged in
 * @return boolean
 */
function is_logged_in()
{
    $CI =& get_instance();
    if (!$CI->session->has_userdata('client_logged_in') && !$CI->session->has_userdata('staff_logged_in')) {
        return false;
    }

    return true;
}
/**
 * Is client logged in
 * @return boolean
 */
function is_client_logged_in()
{
    $CI =& get_instance();
    if ($CI->session->has_userdata('client_logged_in')) {
        return true;
    }

    return false;
}

/**
 * Is staff logged in
 * @return boolean
 */
function is_staff_logged_in()
{
    $CI =& get_instance();
    if ($CI->session->has_userdata('staff_logged_in')) {  
        return true;
    }

    return false;
}
/**
 * Return logged staff User ID from session
 * @return mixed
 */
function get_staff_user_id()
{
    $CI =& get_instance();
    if (!$CI->session->has_userdata('staff_logged_in')) {
        return false;
    }

    return $CI->session->userdata('staff_user_id');
}
/**
 * Return logged client User ID from session
 * @return mixed
 */
function get_client_user_id()
{
    $CI =& get_instance();
    if (!$CI->session->has_userdata('client_logged_in')) {
        return false;
    }

    return $CI->session->userdata('client_user_id');
}


/**
 * Get staff url
 * @param string url to append (Optional)
 * @return string staff url
 */
function admin_url($url = '')
{
    $admin_url = getRedirectUrl();

    if ($url == '' || $url == '/') {
        if ($url == '/') {
            $url = '';
        }
        return site_url($admin_url) . '/';
    } else {        
        return site_url($admin_url . '/' . $url);
    }
    
}

/**
 * Get assets url
 * @param string url to append (Optional)
 * @return string assets url
 */
function assets_url($url = '')
{
    $assets_url = ASSETS_URL;

    if ($url == '' || $url == '/') {
        if ($url == '/') {
            $url = '';
        }
        return site_url($assets_url) . '/';
    } else {        
        return site_url($assets_url . '/' . $url);
    }
    
}

/**
 * Outputs language string based on passed line
 * @since  Version 1.0.1
 * @param  string $line  language line string
 * @param  string $label sprint_f label
 * @return string        formatted language
 */
function _l($line, $label = '', $log_errors = true)
{

    $CI =& get_instance();

    $hook_data = do_action('before_get_language_text', array('line'=>$line, 'label'=>$label));
    $line = $hook_data['line'];
    $label = $hook_data['label'];

    if (is_array($label) && count($label) > 0) { 
        $_line = vsprintf($CI->lang->line(trim($line), $log_errors), $label);

    } else {
        $_line = @sprintf($CI->lang->line(trim($line), $log_errors), $label);

   } 
 
    $hook_data = do_action('after_get_language_text', array('line'=>$line, 'label'=>$label, 'formatted_line'=>$_line));
    $_line = $hook_data['formatted_line'];
    $line = $hook_data['line'];
        
    if ($_line != '') {
        if (preg_match('/"/', $_line) && !is_html($_line)) {
            $_line = htmlspecialchars($_line, ENT_COMPAT);
        }

        return $CI->encoding_lib->toUTF8($_line);
    }

    if (mb_strpos($line, '_db_') !== false) {
        return 'db_translate_not_found';
    }

    return $CI->encoding_lib->toUTF8($line);
}

/* Full Name*/
function FullName($Fname,$Lname)
{
    return $FullName = $Fname .' '. $Lname;
}

/**
 * Load language in admin area
 * @param  string $staff_id
 * @return string return loaded language
 */
function load_admin_language($staff_id = '')
{
    $CI =& get_instance();

    $CI->lang->is_loaded = array();
    $CI->lang->language  = array();

    $language = get_option('active_language');
    if (is_staff_logged_in() || $staff_id != '') {
        $staff_language = get_staff_default_language($staff_id);
        if (!empty($staff_language)) {
            if (file_exists(APPPATH . 'language/' . $staff_language)) {
                $language = $staff_language;
            }
        }
    }
    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    $language = do_action('after_load_admin_language', $language);

    return $language;
}


/**
 * Format date to selected dateformat
 * @param  date $date Valid date
 * @return date/string
 */
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    if (strpos($date, ' ') !== false) {
        return _dt($date);
    }
    $format = get_current_date_format();
    $date   = strftime($format, strtotime($date));

    return do_action('after_format_date', $date);
}
/**
 * Format datetime to selected datetime format
 * @param  datetime $date datetime date
 * @return datetime/string
 */
function _dt($date, $is_timesheet = false)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00 00:00:00') {
        return '';
    }
    $format = get_current_date_format();
    $hour12 = (get_option('time_format') == 24 ? false : true);

    if ($is_timesheet == false) {
        $date = strtotime($date);
    }

    if ($hour12 == false) {
        $tf = '%H:%M:%S';
        if ($is_timesheet == true) {
            $tf = '%H:%M';
        }
        $date   = strftime($format . ' ' . $tf, $date);
    } else {
        $date = date(get_current_date_format(true). ' g:i A', $date);
    }

    return do_action('after_format_datetime', $date);
}
/**
 * Convert string to sql date based on current date format from options
 * @param  string $date date string
 * @return mixed
 */
function to_sql_date($date, $datetime = false)
{
    if ($date == '') {
        return null;
    }

    $to_date     = 'Y-m-d';
    $from_format = get_current_date_format(true);


    $hook_data['date']        = $date;
    $hook_data['from_format'] = $from_format;
    $hook_data['datetime']    = $datetime;

    $hook_data = do_action('before_sql_date_format', $hook_data);

    $date        = $hook_data['date'];
    $from_format = $hook_data['from_format'];

    if ($datetime == false) {
        return date_format(date_create_from_format($from_format, $date), $to_date);
    } else {
        if (strpos($date, ' ') === false) {
            $date .= ' 00:00:00';
        } else {
            $hour12 = (get_option('time_format') == 24 ? false : true);
            if ($hour12 == false) {
                $_temp = explode(' ', $date);
                $time  = explode(':', $_temp[1]);
                if (count($time) == 2) {
                    $date .= ':00';
                }
            } else {
                $tmp = _simplify_date_fix($date, $from_format);
                $time = date("G:i", strtotime($tmp));
                $tmp = explode(' ', $tmp);
                $date = $tmp[0]. ' ' . $time.':00';
            }
        }

        $date = _simplify_date_fix($date, $from_format);
        $d = strftime('%Y-%m-%d %H:%M:%S', strtotime($date));

        return do_action('to_sql_date_formatted', $d);
    }
}
function _simplify_date_fix($date, $from_format)
{
    if ($from_format == 'd/m/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    } elseif ($from_format == 'm/d/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm.d.Y') {
        $date = preg_replace('#(\d{2}).(\d{2}).(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm-d-Y') {
        $date = preg_replace('#(\d{2})-(\d{2})-(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    }

    return $date;
}
/**
 * Check if passed string is valid date
 * @param  string  $date
 * @return boolean
 */
function is_date($date)
{
    if (strlen($date) < 10) {
        return false;
    }

    return (bool) strtotime($date);
}
/**
 * Get locale key by system language
 * @param  string $language language name from (application/languages) folder name
 * @return string
 */
function get_locale_key($language = 'english')
{
    $locale = 'en';
    if ($language == '') {
        return $locale;
    }

    $locales = get_locales();

    if (isset($locales[$language])) {
        $locale = $locales[$language];
    } elseif (isset($locales[ucfirst($language)])) {
        $locale = $locales[ucfirst($language)];
    } else {
        foreach ($locales as $key => $val) {
            $key      = strtolower($key);
            $language = strtolower($language);
            if (strpos($key, $language) !== false) {
                $locale = $val;
                // In case $language is bigger string then $key
            } elseif (strpos($language, $key) !== false) {
                $locale = $val;
            }
        }
    }

    $locale = do_action('before_get_locale', $locale);

    return $locale;
}
/**
 * Check if staff user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $adminid if you want to check for particular staff
 * @return boolean
 */
function has_permission($permission, $staffid = '', $can = '')
{
    $CI =& get_instance();

    /**
     * Maybe permission is function?
     * Example is_admin or is_staff_member
     */
    if (function_exists($permission) && is_callable($permission)) {
        return call_user_func($permission, $staffid);
    }

    /**
     * If user is staff return true
     * staff have all permissions
     */
    if (is_admin($staffid)) {
        return true;
    }

    $staffid = ($staffid == '' ? get_staff_user_id() : $staffid);
    $can = ($can == '' ? '' : $can);
    $permissions = null;

    /**
     * Stop making query if we are doing checking for current user
     * Current user is stored in $GLOBALS including the permissions
     */
    if ((string) $staffid === (string) get_staff_user_id()) {
        $permissions = $CI->staffs_model->getStaffData(get_staff_user_id());
    }
    
    $Staff_ID = $permissions->Staff_ID;
    $permissionsList = $CI->Authentication_model->getPermission($Staff_ID);

    $hasPermission = false;

    /**
     * Based on permissions staff object check if user have permission
     */
    if(isset($permissionsList)){
    foreach ($permissionsList as $permObject) {
        if ($permObject['P_Permission'] == $permission
            && $permObject['P_'.$can] == '1') {
            $hasPermission = true;
            break;
        }
    }
}

    return $hasPermission;
}
 
function ajax_access_denied()
{
 
    $CI =& get_instance();    
    echo _l('access_denied');
    die;
}

/**
 * Load language in staff area
 * @param  string $staff_id
 * @return string return loaded language
 */
function load_staff_language($staff_id = '')
{
    $CI =& get_instance();

    $CI->lang->is_loaded = array();
    $CI->lang->language  = array();

    $language = get_option('active_language');    
    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }
    $language = do_action('after_load_staff_language', $language);

    return $language;
}


/**
 * Load clients area language
 * @param  string $client_id
 * @return string return loaded language
 */
function load_user_language($language)
{
    $CI =& get_instance();
    
    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    $language = do_action('after_load_client_language', $language);

    return $language;
}
/**
 * Get current url with query vars
 * @return string
 */
function current_full_url()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());

    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}
function value_exists_in_array_by_key($array, $key, $val)
{
    foreach ($array as $item) {
        if (isset($item[$key]) && $item[$key] == $val) {
            return true;
        }
    }

    return false;
}

 
function getGender($GenderID)
{
    $CI =& get_instance();
    $Gender = '';
    if($GenderID == '1') {
        $Gender = 'Male';   
    } else if($GenderID == '2') {
        $Gender = 'Female'; 
    } else  if($GenderID == '3') {
        $Gender = 'Other';  
    }
    return $Gender;
}


function getStatus($StatusID)
{
    $CI =& get_instance();
    $Status = '';
    if($StatusID == '1') {
        $Status = 'Enable';    
    } else if($StatusID == '2') {
        $Status = 'Disable';   
    }
    return $Status;
}


function getDateFormat($date)
{
    $Result = date("d M, Y", strtotime($date));

    if(!empty($date) && $date != "0000-00-00 00:00:00"){
        return $Result;
    } 
    return "0";

}   
 

function getTimeFormat($time){
    $Result = date('h:i A', strtotime($time));
    
    if(!empty($time) && $time != "00:00:00"){
        return $Result;
    } 
    return "0";
}
 

function get_admin_controllers_lists()
{
 
    $controllers_lists =  array(        
        'Users' => array('Users'),
        'Staffs' => array('Staffs'),                         
    );

    return $controllers_lists;
}


/* Get notifications*/
function get_user_notifications(){
    $CI =& get_instance();
    return $CI->notifications_model->get_user_notifications(2);
}   

/* Get unread notifications */
function getTopListNotifications($userid,$usertype='')
{
     $CI =& get_instance();
    $CI->db->where('N_ToUserID',$userid);
    $CI->db->where('N_UserType',$usertype);
    $CI->db->where('N_IsRead','2');
    $Notifications = $CI->db->get('notifications')->result_array();
    $notiArray = array();
        foreach($Notifications as $notification){
            $post = $user = '';
            $ProfileImage = UPLOAD_NO_IMAGE;
            $PostImage = UPLOAD_POST_NO_IMAGE;

            if($notification['N_Action_Type'] != '3'){
                $user = getUserData($notification['N_RelationID']);
                
                $UserID = $user->User_ID;
                $UserName = $user->U_FullName ;   

                if(!empty($user->U_ProfileImage)){
                    $ProfileImage = UPLOAD_USER_BASE_URL.$user->User_ID.'/'.$user->U_ProfileImage;
                } else {
                    $ProfileImage = UPLOAD_NO_IMAGE;
                }
                 $PostID =  ''; 

            } else {
                $post = $CI->posts_model->getPost($notification['N_RelationID'],true); 
                 
                if(!empty($post->P_Image)){
                    $PostImage = UPLOAD_USER_BASE_URL.$post->P_UserID.'/posts/'.$post->P_Image;
                } 

                $UserID = '';
                $UserName = ''; 
                $PostID =  $post->Post_ID;  
            }
            

            $notiArray[] = array(
                'Notification_ID' => $notification['Notification_ID'],
                'Subject' => $notification['N_Subject'],
                'Message' => $notification['N_Description'],
                'User_ID' => $UserID,
                'FullName' => $UserName,
                'ProfileImage' => $ProfileImage,
                'Date' => time_ago($notification['N_Date']),
                'PostID' => $PostID,
                'PostImage' => $PostImage,
            );

        }
         
    return $notiArray;
}

function getISOCode($ISOcode){
    $CI = & get_instance();
    $CI->db->where('CountryISO3',$ISOcode);
    return $CI->db->get('countries')->row();
}

/**
 * Short Time ago function
 * @param  datetime $time_ago
 * @return mixed
 */
function time_ago($time_ago)
{
    $time_ago     = strtotime($time_ago);
    $cur_time     = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds      = $time_elapsed;
    $minutes      = round($time_elapsed / 60);
    $hours        = round($time_elapsed / 3600);
    $days         = round($time_elapsed / 86400);
    $weeks        = round($time_elapsed / 604800);
    $months       = round($time_elapsed / 2600640);
    $years        = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {  
        return _l('time_ago_just_now');
    }
    //Minutes
    else if ($minutes <= 60) {  
        if ($minutes == 1) {
            return _l('time_ago_minute');
        } else {
            return _l('time_ago_minutes', $minutes);
        }
    }
    //Hours
    else if ($hours <= 24) {   
        if ($hours == 1) {
            return _l('time_ago_hour');
        } else {
            return _l('time_ago_hours', $hours);
        }
    }
    //Days
    else if ($days <= 7) { 
        if ($days == 1) { 
            return _l('time_ago_yesterday');
        } else {
            return _l('time_ago_days', $days);
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {  
        if ($weeks == 1) {
            return _l('time_ago_week');
        } else {
            return _l('time_ago_weeks', $weeks);
        }
    }
    //Months
    else if ($months <= 12) {  
        if ($months == 1) {
            return _l('time_ago_month');
        } else {
            return _l('time_ago_months', $months);
        }
    }
    //Years
    else {   
        if ($years == 1) {
            return _l('time_ago_year');
        } else {
            return _l('time_ago_years', $years);
        }
    }
}

/* Get All Notificatios*/
function notificationsList(){
    $CI =& get_instance();  
    return $CI->notifications_model->getNotification();
}


/* Get List of dates between start date and end date*/
function DatesBeteen($fromdate, $todate) {
    $fromdate = \DateTime::createFromFormat('d/m/Y', $fromdate);
    $todate = \DateTime::createFromFormat('d/m/Y', $todate);

    return new \DatePeriod(
        $fromdate,
        new \DateInterval('P1D'),
        $todate->modify('+1 day')
    );
}

/* Convert Image in Base64 format */
function convertBase64($path) {

    $type = pathinfo($path, PATHINFO_EXTENSION);
    
    $data = file_get_contents($path);

    return $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); 
    
}
 
function setFlashData($message,$class,$title){
    $CI =& get_instance(); 
    $data = $CI->session->set_flashdata(
        array(
            'message' => $message,
            'title'=> $title,
            'alertclass' => $class,
            'display' => 'active',
        )
    );    
 
	return $data;
} 
 
/* Json response of Ajax*/ 
function setAjaxResponse($message,$class,$status,$data='',$extra=''){    
    exit(json_encode(array(
            'Msg' => $message,
            'Class' => $class,            
            'Status' => $status,
            'Data' => $data,
            'Extra' => $extra,
        )));    
} 

function js_variables(){    
     $js_vars     = array(
        'site_url' => site_url(),
        'admin_url' => admin_url(),
        'is_admin'=>is_admin(), 
        'assets_url' => assets_url(),            
		'redirect' => current_full_url(),
        'staff_delete_url' => STAFF_DELETE_URL,
        'notification_delete_url' => NOTIFICATION_DELETE_URL,
        'staff_update_status_url' => UPDATE_STAFF_STATUS,

        'customer_delete_url' => CUSTOMER_DELETE_URL,
        'customer_update_status_url' => CUSTOMER_UPDATE_STATUS,

        'vendor_delete_url' => VENDOR_DELETE_URL,
        'vendor_update_status_url' => VENDOR_UPDATE_STATUS,

        'deliveryboy_delete_url' => DELIVERYBOY_DELETE_URL,
        'deliveryboy_update_status_url' => DELIVERYBOY_UPDATE_STATUS,

        'category_delete_url' => CATEGORY_DELETE_URL,
        'category_update_status_url' => CATEGORY_UPDATE_STATUS,
        'category_based_on_type_level_url' => GET_CATEGORIES_BY_TYPE_LEVEL_URL,

        'service_delete_url' => SERVICE_DELETE_URL,
        'service_update_status_url' => SERVICE_UPDATE_STATUS,

        'feature_delete_url' => FEATURE_DELETE_URL,
        'feature_update_status_url' => FEATURE_UPDATE_STATUS,

        'step_delete_url' => STEP_DELETE_URL,
        'step_update_status_url' => STEP_UPDATE_STATUS,

        'work_delete_url' => WORK_DELETE_URL,
        'work_update_status_url' => WORK_UPDATE_STATUS,

        'review_update_status_url' => REVIEW_UPDATE_STATUS,

        'package_delete_url' => PACKAGE_DELETE_URL,
        'package_update_status_url' => PACKAGE_UPDATE_STATUS,

        'option_delete_url' => OPTION_DELETE_URL,
        'option_update_status_url' => OPTION_UPDATE_STATUS,

        'timeslab_delete_url' => TIMESLAB_DELETE_URL,
        'timeslab_update_status_url' => TIMESLAB_UPDATE_STATUS,

        'product_delete_url' => PRODUCT_DELETE_URL,
        'product_update_status_url' => PRODUCT_UPDATE_STATUS,

        'attribute_delete_url' => ATTRIBUTE_DELETE_URL,
        'attribute_update_status_url' => ATTRIBUTE_UPDATE_STATUS,

        'attribvalue_delete_url' => ATTRIBVALUE_DELETE_URL,
        'attribvalue_update_status_url' => ATTRIBVALUE_UPDATE_STATUS,

        'review_product_update_status_url' => REVIEW_PRODUCT_UPDATE_STATUS,

		'attribvalues_based_on_attribute_url' => GET_ATTRIBVALUES_BY_ATTRIBUTE_URL,
		
        'restaurant_delete_url' => RESTAURANT_DELETE_URL,
        'restaurant_update_status_url' => RESTAURANT_UPDATE_STATUS,

        'food_delete_url' => FOOD_DELETE_URL,
        'food_update_status_url' => FOOD_UPDATE_STATUS,

        'category_based_on_category_url' => GET_CATEGORIES_BY_CATEGORY_URL,

		'review_restaurant_update_status_url' => REVIEW_RESTAURANT_UPDATE_STATUS,
    );
         $js_vars     = do_action('before_render_js_vars_master', $js_vars);        

            echo '<script>';
            foreach ($js_vars as $var => $val) {   
                echo 'var ' . $var . '="' . $val . '";';
            } 
            echo '</script>';
        
} 
 

 function find_all_files($dir) 
{ 
    $root = scandir($dir); 
    foreach($root as $value) 
    { 
        if($value === '.' || $value === '..') {continue;} 
        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;} 
        foreach(find_all_files("$dir/$value") as $value) 
        { 
            $result[]=$value; 
        } 
    } 
    return $result; 
} 
 

/**
 * Get country code        
 */
function getCountryCode($id = '')
{
    $CI = & get_instance();
    if(!empty($id)){
        $CI->db->where('CountryID',$id);
        return $CI->db->get('countries')->row();
    } else {
        return $CI->db->get('countries')->result_array();    
    }
    
}   

function getCountryName($code)
{
    $CI = & get_instance();
    $CI->db->select('CountryName');
    $CI->db->where('CountryPhoneCode',$code);
    return $CI->db->get('countries')->row();
}  

function getCountryISO($code)
{
    $CI = & get_instance();
    $CI->db->select('CountryISO');
    $CI->db->where('CountryPhoneCode',$code);
    return $CI->db->get('countries')->row();
}  

    

function headerClasses(){
    $CI = & get_instance();
    $default_page = $CI->uri->segment(1); 
    $active_page = $CI->uri->segment(2); 
    $current_page = $CI->uri->segment(3);  
    $detail_page = $CI->uri->segment(4); 
    
    if($active_page == 'Portfolio' && $detail_page != ''){
        $class="portfolio_page_inner";
    } else {
        $class="main_page";
    }

    return $class;
}

 

function GetSettings()
{
    $CI = & get_instance();
    //SELECT * FROM `itq_options` WHERE `name` LIKE '%countrycode_%' ORDER BY `id` ASC
    $CI->db->where(" `name` LIKE '%semail_%'  " );
    $CI->db->order_by('ID','DESC');
    $CI->db->limit(2);
    $Result = $CI->db->get('options')->result_array();
    $getEmailsRepeater = array();
    
    foreach($Result as $key => $value){
        $Email[$key] = $value['value'];
    }

    return $Email;
}

 
function CountryCodeAPI(){
    $data = file_get_contents('https://restcountries.eu/rest/v2/all');
    
    $Result = json_decode($data);
    $List = array();
    foreach($Result as $ListData){
        $List[] = array(
            'Flag' =>$ListData->flag,
            'Code' => $ListData->callingCodes
        );    
    }
    return $List;    
} 

/* Get lat long from country name */
function GeoCode(){
    $CI = & get_instance();
    $Clients = $CI->testimonials_model->getTestimonial();
     
    $Coordinates = array();
    foreach ($Clients as $key => $value) {
        $CountryID = $value['CountryID'];
        $Country = getCountryCode($CountryID);
    
        $Geocode = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Country->CountryNiceName)."&sensor=false&key=AIzaSyCSmZN7muFjwhEcotD6rSaJOA7MpILVY1c");
        $Output = json_decode($Geocode);

        $LatLng = $Output->results[0]->geometry->location;

        $Lat = $LatLng->lat;
        $Lng = $LatLng->lng;
    
        $Coordinates[] = array('lat' => $Lat, 'lng' => $Lng, );
    }
    return json_encode($Coordinates);        
}

 /* Get location by IP address */
function getLocationInfoByIp(){
    $ip_address=$_SERVER['REMOTE_ADDR'];
    /*Get user ip address details with geoplugin.net*/
    $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip_address;
    $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
    /*Get City name by return array*/
    
    $city = $addrDetailsArr['geoplugin_city'];
    /*Get Country name by return array*/
    $country = $addrDetailsArr['geoplugin_countryName'];
    $state = $addrDetailsArr['geoplugin_regionName'];
    $result['country'] = $country;
    $result['state'] = $state;
    $result['city'] = $city;
    return $result;
}

function getUserData($UserID)
{
    $CI =& get_instance();
    $CI->load->model('Users_model');
    $UserData = $CI->Users_model->getUser($UserID); 
    
    return $UserData;
}