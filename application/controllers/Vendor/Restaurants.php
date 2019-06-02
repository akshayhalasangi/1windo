<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Restaurants extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('1windo_view_hierarchy');
       // if (!has_permission('Services', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
         
        $data['title'] = _l('title_restaurants');
        $data['listAssets'] = 'true';

        // $this->load->model('Categories_model');
        // $CategoryList = $this->Categories_model->getCategory(null, array("C_Type" => FOOD_CATEGORY_ID), 'ASC', 'C_Parent');
        // $CategoryHierarchy = $this->Categories_model->getCategoryHierarchy(FOOD_CATEGORY_ID);

        // $data['restaurantCategoryList']['Categories'] = displayCategoryHierarchy($CategoryList, $CategoryHierarchy);

        // $this->load->view( RESTAURANT_URL.'categories', $data);

        $data['restaurantsList'] = $this->Restaurants_model->getRestaurant();
        $this->load->view( RESTAURANT_URL.'manage', $data);
        
    }
	public function Foods($restaurantid){
        $data['title'] = _l('title_restaurants_foods');
        $data['listAssets'] = 'true';
        $data['foodsList'] = $this->Restaurants_model->getFoods(NULL,array('F_RestaurantID'=>$restaurantid));
        $data['restaurantName'] = ($this->Restaurants_model->getRestaurant($restaurantid)->R_Name);
		$data['RestaurantID'] = $restaurantid;      		
        $this->load->view( RESTAURANT_URL.'manage-foods', $data);   
    }
	
	public function Reviews($restaurantid){
        $data['title'] = _l('title_restaurants_reviews');
        $data['listAssets'] = 'true';
        $data['reviewsList'] = $this->Restaurants_model->getReviews(NULL,array('R_Type'=>'3','R_RelationID'=>$restaurantid));
        $data['restaurantName'] = ($this->Restaurants_model->getRestaurant($restaurantid)->R_Name);
		$data['RestaurantID'] = $restaurantid;
        $this->load->view( RESTAURANT_URL.'manage-reviews', $data);   
    }
    public function Restaurant($id = ''){
        $data = $this->input->post();
        if(!empty($this->input->post())){
            if($data['Val_RCountry'] == "IN"){
                $data['Val_FoodCategory'] = json_encode($data['Val_FoodCategory']);
                if(!empty($id)){
                    $fileRes = handle_restaurant_featured_image($id);
                    // echo "File Res: <br>";var_dump($fileRes);exit;
                    if($fileRes === true){
                        $success = $this->Restaurants_model->update($data,$id);                             
                        if ($success == true) {      
                            setFlashData(_l('restaurant_update_success'),'success',_l('success'));    
                            redirect('Admin/Restaurants');                                        
                        } else {
                            setFlashData(_l('restaurant_update_fail'),'danger',_l('fail'));                 
                        }
                    }else{
                        if(is_array($fileRes) && count($fileRes)){
                            setFlashData($fileRes['message'],'danger',_l('fail'));
                        }else{
                            setFlashData(_l('file_upload_failed'),'danger',_l('fail'));
                        }
                    }
                } else {
                    $fileRes = handle_restaurant_featured_image($restaurant);
                    if($fileRes === true){
                        $restaurant = $this->Restaurants_model->add($data);
                    
                        if($restaurant != false){  
                            setFlashData(_l('restaurant_register_succes'),'success',_l('success'));   
                            redirect('Admin/Restaurants');
                        } else {
                            setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                        }
                    }else{
                        if(is_array($fileRes) && count($fileRes)){
                            setFlashData($fileRes,'danger',_l('fail'));
                        }else{
                            setFlashData(_l('file_upload_failed'),'danger',_l('fail'));
                        }
                    }           
                } 
            }else{
                setFlashData(_l('no_service_in_the_country'),'danger',_l('fail'));
            }
        }
        if($id == ''){
            $data['title'] = _l('add_new',_l('add_restaurant'));
            $data['restaurant'] = '';
        } else {
			$restaurant				= $this->Restaurants_model->getRestaurant($id);             
            $data['restaurant'] 	= $restaurant;
            $data['title'] 			= _l('txt_update_restaurant');
        }

		$data['categories'] 	= $this->Categories_model->getCategory(NULL,array('C_Type'=>'3','C_Level'=>'1'),'ASC');

        $data['addAssets'] = true;        
        $this->load->view(RESTAURANT_URL.'restaurant',$data);            
    }

	public function Food($restaurantid,$id = ''){
        $data = $this->input->post();
        if(!empty($this->input->post())){
	
            if(!empty($id)){
                $success = $this->Restaurants_model->updateFoods($data,$id);                             
				handle_food_display_image($id); 

                if ($success == true) {      
                    setFlashData(_l('restaurant_food_update_success'),'success',_l('success'));
                    redirect('Admin/Restaurants/Foods/'.$restaurantid);
                } else {                                                
                    setFlashData(_l('restaurant_food_update_fail'),'danger',_l('fail'));                 
                }   
   
            } else {

                $food = $this->Restaurants_model->addFoods($data);    
				handle_food_display_image($food); 
			
                if($food != false){  
				    setFlashData(_l('restaurant_food_register_succes'),'success',_l('success'));   
                    redirect('Admin/Restaurants/Foods/'.$restaurantid);
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == ''){
            $data['title'] = _l('add_new',_l('add_restaurant_food'));
            $data['food'] = '';
        } else {      
			$food			= $this->Restaurants_model->getFoods($id);             
            $data['food'] 	= $food;
            $data['title'] = _l('txt_update_service_feature');
        }
        $restaurantData = $this->Restaurants_model->getRestaurant($restaurantid);

        $restaurantFoodCategories = json_decode($restaurantData->R_FoodCategoryID);

        $data['restaurantName'] = ($restaurantData->R_Name);
		$data['RestaurantID'] = $restaurantid;
		$data['Restaurants']  = $this->Restaurants_model->getRestaurant();
		$data['SubCategories'] 	= $this->Categories_model->getCategory(NULL,array('C_Type'=>'3','C_Level'=>'1'),'ASC','CategoryID',$restaurantFoodCategories);
		$data['addAssets'] = true;        
        $this->load->view(RESTAURANT_URL.'food',$data);            
    }
	
    /* Delete Staffs */
    public function DeleteRestaurant(){    
        $RestaurantId = $this->input->post('id');              
        $Success = $this->Restaurants_model->deleteRestaurant($RestaurantId);                 
        
        if($Success){             
            setAjaxResponse( _l('restaurant_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('restaurant_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    /* Delete Staffs */
    public function DeleteFood(){    
        $FoodId = $this->input->post('id');              
        $Success = $this->Restaurants_model->deleteFoods($FoodId);                 
        
        if($Success){             
            setAjaxResponse( _l('restaurant_food_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('restaurant_food_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
	
    /* Update staff stauts */
    public function UpdateRestaurantStatus()
    { 
        $data = $this->input->post();
        $RestaurantID = $data['id'];
        $data['Val_Rstatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Restaurants_model->update($data,$RestaurantID);
            
            if($Success){
                setAjaxResponse( _l('restaurant_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('restaurant_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

	 /* Update staff stauts */
    public function UpdateFoodStatus()
    {
        $data = $this->input->post();
        $FoodID = $data['id'];
        $data['Val_Fstatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Restaurants_model->updateFoods($data,$FoodID);
            
            if($Success){
                setAjaxResponse( _l('restaurant_food_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('restaurant_food_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }

    /* Update staff stauts */
    public function UpdateReviewStatus()
    { 
        $data = $this->input->post();
        $ReviewID = $data['id'];
        $data['Val_Rstatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Restaurants_model->updateReviews($data,$ReviewID);
            
            if($Success){
                setAjaxResponse( _l('restaurant_review_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('restaurant_review_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
}
