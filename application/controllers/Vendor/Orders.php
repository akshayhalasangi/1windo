<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
       // if (!has_permission('Services', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
        $data['title'] = _l('title_orders');
        $data['listAssets'] = 'true';
        $id=$this->Products_model->getVendorCategoryID(get_staff_user_id());
        $category=$this->Categories_model->get( $id);
        switch ( $category->C_Type)
        {
            case 1:
                $data['ordersList'] = $this->Cart_model->get();
                $this->load->view( ORDER_URL.'manage', $data);
                break;
            case 2:
                 $this->Products();
                break;
            case 3:
                $this->Restaurants();
                break;
        }

    }
	public function Products(){
        $data['title'] = _l('title_orders');
        $data['listAssets'] = 'true';
        $data['ordersList'] = $this->Cart_model->getProductsCart();

        $this->load->view( ORDER_URL.'manage-products', $data);   
    }
	public function Restaurants(){
        $data['title'] = _l('title_orders');
        $data['listAssets'] = 'true';
        $data['ordersList'] = $this->Cart_model->getRestaurantsCart();

        $this->load->view( ORDER_URL.'manage-restaurants', $data);   
    }
	public function Reviews($productid){
        $data['title'] = _l('title_products_reviews');
        $data['listAssets'] = 'true';
        $data['reviewsList'] = $this->Products_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$productid));
		$data['ProductID'] = $productid;
        $this->load->view( PRODUCT_URL.'manage-reviews', $data);   
    }
    public function Order($id = ''){
        $data = $this->input->post();

        if($id == ''){
            $data['title'] = _l('add_new',_l('add_order'));
            $data['product'] = '';
        } else {      
			$order			= $this->Cart_model->get($id);

			$vendor			= $this->Vendors_model->getVendor($order->C_AssignedTo);             
			
			$data['order'] 		= $order;
			$data['customer'] 	= $this->Customers_model->getCustomer($order->C_CustomerID);
			$data['vendor'] 	= $vendor;
            $data['title'] 		= _l('txt_view_order');
			
        }

//		$data['categories'] = $this->Categories_model->getCategory(NULL,array('C_Type'=>'2','C_Level'=>'1'),'ASC');
//		$data['attributes'] = $this->Products_model->getAttributes(NULL,array('A_Status'=>'2'),'ASC');
		
        $data['addAssets'] = true;        
        $data['orderAssets'] = true;        
        $this->load->view(ORDER_URL.'order',$data);            
    }
	
	 public function ProductsOrder($id = ''){

        $data = $this->input->post();

        if($id == ''){
            $data['title'] = _l('add_new',_l('add_order'));
            $data['product'] = '';
        } else {      
			$order			= $this->Cart_model->getProductsCart($id);    
//			         print_r()
			$vendor			= $this->Vendors_model->getVendor($order->PC_AssignedTo);             
			
			$data['order'] 		= $order;
			$data['customer'] 	= $this->Customers_model->getCustomer($order->PC_CustomerID);
			$data['vendor'] 	= $vendor;
            $data['title'] 		= _l('txt_view_order');
			
        }

//		$data['categories'] = $this->Categories_model->getCategory(NULL,array('C_Type'=>'2','C_Level'=>'1'),'ASC');
//		$data['attributes'] = $this->Products_model->getAttributes(NULL,array('A_Status'=>'2'),'ASC');
		
        $data['addAssets'] = true;        
        $data['orderAssets'] = true;        
        $this->load->view(ORDER_URL.'product-order',$data);            
    }
	 public function RestaurantsOrder($id = ''){

        $data = $this->input->post();

        if($id == ''){
            $data['title'] = _l('add_new',_l('add_order'));
            $data['product'] = '';
        } else {      
			$order			= $this->Cart_model->getRestaurantsCart($id);    
//			         print_r()
			$vendor			= $this->Vendors_model->getVendor($order->RC_AssignedTo);             
			
			$data['order'] 		= $order;
			$data['customer'] 	= $this->Customers_model->getCustomer($order->RC_CustomerID);
			$data['vendor'] 	= $vendor;
            $data['title'] 		= _l('txt_view_order');
			
        }

//		$data['categories'] = $this->Categories_model->getCategory(NULL,array('C_Type'=>'2','C_Level'=>'1'),'ASC');
//		$data['attributes'] = $this->Products_model->getAttributes(NULL,array('A_Status'=>'2'),'ASC');
		
        $data['addAssets'] = true;        
        $data['orderAssets'] = true;        
        $this->load->view(ORDER_URL.'restaurant-order',$data);            
    }
    /* Delete Staffs */
    public function DeleteOrder(){
             
               
        $OrderId = $this->input->post('id');              
        $Success = $this->Cart_model->delete($OrderId);                 
        
        if($Success){             
            setAjaxResponse( _l('order_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('order_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    
    /* Update staff stauts */
    public function UpdateOrderStatus()
    {
        $data = $this->input->post();
        $OrderID = $data['id'];
        $orderType = $data['type'];

        $currentDtTime = date('Y-m-d H:i:s');

        switch($data['status']){
            case 2: $data['Val_Caccepteddttm'] = $currentDtTime;
                    $data['Val_Cprocessingdttm'] = $currentDtTime;
                    break;

            case 3: $data['Val_Caccepteddttm'] = $currentDtTime;
                    $data['Val_Cprocessingdttm'] = $currentDtTime;
                    break;

            case 4: $data['Val_Cdelivereddttm'] = $currentDtTime;
                    break;

            case 5: $data['Val_Ccancelleddttm'] = $currentDtTime;
                    break;

            case 6: $data['Val_Cassigneddttm'] = $currentDtTime;
                    break;
        }

        // $data['Val_Cstatus'] = $data['status'];
		 
        if(!empty($data)){

            $Success = false;

            switch($orderType){
                case "service": $data['Val_Corderstatus'] = $data['status'];
                                $Success = $this->Cart_model->update($data,$OrderID);
                                break;
                case "product": $data['Val_PCorderstatus'] = $data['status'];
                                $Success = $this->Cart_model->updateCartProducts($data,$OrderID);
                                break;
                case "restaurant":  $data['Val_RCorderstatus'] = $data['status'];
                                    $Success = $this->Cart_model->updateCartRestaurants($data,$OrderID);
                                    break;
                default: break;
            }

            if($Success){
                setAjaxResponse( _l('order_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('order_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
	
}
