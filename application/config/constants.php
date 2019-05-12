<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If setss to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


// Used for phpass_helper
define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', FALSE);
// Admin url
define('ADMIN_URL', 'Admin');
define('SUPER_ADMIN_URL', 'SuperAdmin');
define('ASSETS_URL', 'assets');

// Front url
define('FRONT_URL', 'Web');
define('UPLOAD_THUMB','thumb-');

// Staff Attachments
define('STAFF_URL','admin/staffs/');
define('STAFF_DELETE_URL',(APPBASEURL.'Admin/Staffs/deleteStaff'));
define('UPDATE_STAFF_STATUS','Staffs/UpdateStaffStatus');
define('STAFFS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/staffs' . '/');
define('UPLOAD_STAFF_BASE_URL',APPBASEURL.'uploads/staffs'. '/');

// CRM server update url 
define('EMAIL_TEMP_IMG_URL',APPBASEURL.'assets/email-img'. '/');	
define('DEFAULT_IMG_URL',APPBASEURL.'assets/admin/img/coconut.jpg');	

// Settings attachments
define('UPLOAD_NO_IMAGE',APPBASEURL.'uploads/user_image.png');
define('UPLOAD_POST_NO_IMAGE',APPBASEURL.'uploads/No_Image.jpg');


// Customers Attatchments
define('CUSTOMER_URL','admin/customers/');
define('CUSTOMER_DELETE_URL',(APPBASEURL.'Admin/Customers/DeleteCustomer'));
define('CUSTOMER_UPDATE_STATUS','Customers/UpdateCustomerStatus');

// Vendors Attatchments
define('VENDOR_URL','admin/vendors/');
define('VENDOR_DELETE_URL',(APPBASEURL.'Admin/Vendors/DeleteVendor'));
define('VENDOR_UPDATE_STATUS','Vendors/UpdateVendorStatus');

// Vendors Attatchments
define('DELIVERYBOY_URL','admin/deliveryboys/');
define('DELIVERYBOY_DELETE_URL',(APPBASEURL.'Admin/DeliveryBoys/DeleteDeliveryBoy'));
define('DELIVERYBOY_UPDATE_STATUS','DeliveryBoys/UpdateDeliveryBoyStatus');


// Categories Attatchments
define('CATEGORY_URL','admin/categories/');
define('CATEGORY_DELETE_URL',(APPBASEURL.'Admin/Categories/DeleteCategory'));
define('CATEGORY_UPDATE_STATUS','Categories/UpdateCategoryStatus');
define('GET_CATEGORIES_BY_TYPE_LEVEL_URL','Categories/getCategories');

// Services Attatchments
define('SERVICE_URL','admin/services/');
define('SERVICE_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteService'));
define('SERVICE_UPDATE_STATUS','Services/UpdateServiceStatus');

define('FEATURE_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteFeature'));
define('FEATURE_UPDATE_STATUS','Services/UpdateFeatureStatus');

define('STEP_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteStep'));
define('STEP_UPDATE_STATUS','Services/UpdateStepStatus');

define('REVIEW_UPDATE_STATUS','Services/UpdateReviewStatus');

define('WORK_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteWork'));
define('WORK_UPDATE_STATUS','Services/UpdateWorkStatus');

define('PACKAGE_DELETE_URL',(APPBASEURL.'Admin/Services/DeletePackage'));
define('PACKAGE_UPDATE_STATUS','Services/UpdatePackageStatus');

define('OPTION_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteOption'));
define('OPTION_UPDATE_STATUS','Services/UpdateOptionStatus');

define('TIMESLAB_DELETE_URL',(APPBASEURL.'Admin/Services/DeleteTimeslab'));
define('TIMESLAB_UPDATE_STATUS','Services/UpdateTimeslabStatus');


define('GET_SERVICES_BY_TYPE_LEVEL_URL','Services/getServices');
define('GET_ATTRIBVALUES_BY_ATTRIBUTE_URL','Products/getAttributeValues');



// Products Attatchments
define('PRODUCT_URL','admin/products/');
define('PRODUCT_DELETE_URL',(APPBASEURL.'Admin/Products/DeleteProduct'));
define('PRODUCT_UPDATE_STATUS','Products/UpdateProductStatus');

define('ATTRIBUTE_DELETE_URL',(APPBASEURL.'Admin/Products/DeleteAttribute'));
define('ATTRIBUTE_UPDATE_STATUS','Products/UpdateAttributeStatus');

define('ATTRIBVALUE_DELETE_URL',(APPBASEURL.'Admin/Products/DeleteAttribValue'));
define('ATTRIBVALUE_UPDATE_STATUS','Products/UpdateAttribValueStatus');

define('REVIEW_PRODUCT_UPDATE_STATUS','Products/UpdateReviewStatus');


// Restaurants Attatchments
define('RESTAURANT_URL','admin/restaurants/');
define('RESTAURANT_DELETE_URL',(APPBASEURL.'Admin/Restaurants/DeleteRestaurant'));
define('RESTAURANT_UPDATE_STATUS','Restaurants/UpdateRestaurantStatus');

define('FOOD_DELETE_URL',(APPBASEURL.'Admin/Restaurants/DeleteFood'));
define('FOOD_UPDATE_STATUS','Restaurants/UpdateFoodStatus');

define('GET_CATEGORIES_BY_CATEGORY_URL','Categories/getSubCategories');
define('REVIEW_RESTAURANT_UPDATE_STATUS','Restaurants/UpdateReviewStatus');


// Orders Attatchments
define('ORDER_URL','admin/orders/');
define('ORDER_DELETE_URL',(APPBASEURL.'Admin/Orders/DeleteOrder'));
define('ORDER_UPDATE_STATUS','Orders/UpdateOrderStatus');


//define('REVIEW_PRODUCT_UPDATE_STATUS','Products/UpdateReviewStatus');






define('USERS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/users' . '/');
define('VENDORS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/vendors' . '/');
define('DELIVERYBOYS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/deliveryboys' . '/');
define('CATEGORIES_ATTACHMENTS_FOLDER',FCPATH . 'uploads/categories' . '/');
define('SERVICES_ATTACHMENTS_FOLDER',FCPATH . 'uploads/services' . '/');
define('SERVICES_WORKS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/services-works' . '/');
define('PRODUCTS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/products' . '/');
define('RESTAURANTS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/restaurants' . '/');
define('RESTAURANTS_FOODS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/foods' . '/');





define('UPLOAD_VENDOR_BASE_URL',APPBASEURL.'uploads/vendors'. '/');
define('UPLOAD_VENDORS_BASE_URL',APPBASEURL.'uploads/vendors'. '/');
define('UPLOAD_DELIVERYBOY_BASE_URL',APPBASEURL.'uploads/deliveryboys'. '/');
define('UPLOAD_CATEGORIES_BASE_URL',APPBASEURL.'uploads/categories'. '/');
define('UPLOAD_SERVICES_BASE_URL',APPBASEURL.'uploads/services'. '/');
define('UPLOAD_SERVICES_WORKS_BASE_URL',APPBASEURL.'uploads/services-works'. '/');
define('UPLOAD_SLIDERS_BASE_URL',APPBASEURL.'uploads/sliders'. '/');
define('UPLOAD_PRODUCTS_BASE_URL',APPBASEURL.'uploads/products'. '/');
define('UPLOAD_RESTAURANTS_BASE_URL',APPBASEURL.'uploads/restaurants'. '/');
define('UPLOAD_RESTAURANTS_FOODS_BASE_URL',APPBASEURL.'uploads/foods'. '/');
define('UPLOAD_WORKS_URL','works'. '/');



//Notification 
define('NOTIFICATION_URL','admin/notifications/');

define('NOTIFICATION_DELETE_URL',(APPBASEURL.'Admin/Notifications/DeleteNotification'));


// Posts Attatchments
define('POSTS_PAGE_URL','admin/posts/');
define('POSTS_ATTACHMENTS_FOLDER',FCPATH . 'uploads/users/posts' . '/');
define('UPLOAD_POST_BASE_URL',APPBASEURL.'uploads/users/posts'. '/');

// Firebase URL 
define('FIREBASEURL','https://messoa-4f941.firebaseio.com');  

/* Server KEY */
define('FIREBASE_API_KEY', 'AAAAChTrq94:APA91bFpKLJPeRIXMHD-KyZ453-dseZoJEh_mG1myCFdOaOWxfE_fpn4kL1mJiMquMOhO6No3Wc0xjzlK5vKUN-GBbYin9QryVJKyRAE9ogl7r8_FZh0mZSsiR6K-sEdBeBl7RCBpTxG');

//Firebase Web API Key : AIzaSyBk_29o5OgmqHOxUn4QnxTIvmI5bQNnUDE



// Table Names
define('TBL_MASTERS','masters');							
define('TBL_DELIVERYBOYS','delivery_boys');			
define('TBL_VENDORS','vendors');			
define('TBL_CUSTOMERS','customers');							
define('TBL_MEMBERS','members');							

define('TBL_CUSTOMERS_ADDRESSES','addresses');							
define('TBL_VENDORS_ADDRESSES','addresses');							

define('TBL_VENDORS_PROFILES','vendor_profiles');							
define('TBL_VENDORS_ABOUT','vendor_about');							
define('TBL_VENDORS_WORKS','vendor_works');							
define('TBL_VENDORS_LOCATIONS','vendor_locations');							
define('TBL_VENDORS_ACCOUNTS','vendor_accounts');							

define('TBL_DELIVERYBOYS_ACCOUNTS','deliveryboy_accounts');							


define('TBL_CATEGORIES','categories');							

define('TBL_SERVICES','services');							
define('TBL_SERVICES_FEATURES','services_features');							
define('TBL_SERVICES_STEPS','services_steps');							
define('TBL_SERVICES_WORKS','services_works');							
define('TBL_SERVICES_PACKAGES','services_packages');	
define('TBL_SERVICES_OPTIONS','services_packages_options');
define('TBL_SERVICES_TIMESLABS','services_timeslabs');


define('TBL_PRODUCTS','products');							
define('TBL_PRODUCTS_FEATURES','products_features');							
define('TBL_PRODUCTS_STEPS','products_steps');							
define('TBL_PRODUCTS_WORKS','products_works');							
define('TBL_PRODUCTS_ATTRIBUTES','products_attributes');	
define('TBL_PRODUCTS_ATTRIB_VALUES','products_attributes_values');
define('TBL_PRODUCTS_TIMESLABS','products_timeslabs');

define('TBL_RESTAURANTS','restaurants');							
define('TBL_RESTAURANTS_FOODS','restaurants_foods');							

define('TBL_REVIEWS','reviews');							

define('TBL_CART_SERVICES','cart_service');							
define('TBL_PAYMENT_OPTIONS','payment_options');							
define('TBL_CART_PRODUCTS','cart_product');							
define('TBL_CART_PRODUCTS_DETAILS','cart_product_details');							
define('TBL_CART_RESTAURANTS','cart_restaurant');							
define('TBL_CART_RESTAURANTS_DETAILS','cart_restaurant_details');		

// Category ID
define('SERVICE_CATEGORY_ID', 1);
define('PRODUCT_CATEGORY_ID', 2);
define('FOOD_CATEGORY_ID', 3);


