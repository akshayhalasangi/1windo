<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Admin_Controller
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

//        $id=10;
        if(isset($id)){
            $data['productsList'] = $this->Products_model->getProduct(null, array("P_CategoryID" => $id));
            $data['productID'] = $id;
            $data['listAssets'] = 'true';

            $this->load->model('Categories_model');
            $categoryName = $this->Categories_model->getCategory($id);
            $categoryName = $categoryName->C_Name;

            $data['title'] = $categoryName._l('txt_products');
            $data['categoryName'] =  $categoryName;
            $this->load->view(camelToSnake(getRedirectUrl()).'/products/' . 'manage', $data);
        }else{
            redirect('Vendor/Products');
        }
    }
	public function Attributes(){
        $data['title'] = _l('title_products_attributes');
        $data['listAssets'] = 'true';
        $data['attributesList'] = $this->Products_model->getAttributes();

        $this->load->view( camelToSnake(getRedirectUrl()).'/products/'.'manage-attributes', $data);
    }
	public function AttribValues($attributeid){
        $data['title'] = _l('title_products_attributes_values');
        $data['listAssets'] = 'true';
        $data['attribvaluesList'] = $this->Products_model->getAttribValues(NULL,array('V_AttributeID'=>$attributeid));
        $data['AttributeID'] = $attributeid;
        
        $data['attributeName'] = $this->Products_model->getAttributes($attributeid);
        $data['attributeName'] = $data['attributeName']->A_Title;

        $this->load->view( camelToSnake(getRedirectUrl()).'/products/'.'manage-attribvalues', $data);
    }
	public function Reviews($productid, $categoryId = ''){
        $data['title'] = _l('title_products_reviews');
        $data['listAssets'] = 'true';
        $data['reviewsList'] = $this->Products_model->getReviews(NULL,array('R_Type'=>'2','R_RelationID'=>$productid));
        $data['ProductID'] = $productid;
        $data['categoryId'] = $categoryId;
        $this->load->view( camelToSnake(getRedirectUrl()).'/products/'.'manage-reviews', $data);
    }
    public function Product($id = '', $categoryId = ''){

        $data = $this->input->post();
        if(!empty($this->input->post())){
            if(array_key_exists('Val_Pattributevalues', $data)){
                $data['Val_Pattributevalues'] = json_encode($data['Val_Pattributevalues']);
            }
            if(!empty($id)){
                $fileRes = handle_product_featured_image($id);
                if($fileRes === true){
                    $success = $this->Products_model->update($data,$id);

                    if ($success == true) {
                        setFlashData(_l('product_update_success'),'success',_l('success'));
                        redirect('Admin/Products/ProductList/'.$data['Val_Category']);
                    } else {                                                
                        setFlashData(_l('product_update_fail'),'danger',_l('fail'));
                    }
                }else{
                    if(is_array($fileRes) && count($fileRes)){
                        setFlashData(ucwords($fileRes['message']),'danger',_l('fail'));
                    }else{
                        setFlashData(_l('file_upload_failed'),'danger',_l('fail'));
                    }
                }
   
            } else {
                $fileRes = handle_product_featured_image($product);
                if($fileRes === true){
                    $product = $this->Products_model->add($data);
                
                    if($product != false){  
                        setFlashData(_l('product_register_succes'),'success',_l('success'));   
                        redirect('Admin/Products/ProductList/'.$data['Val_Category']);
                    } else {
                        setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                    }
                }else{
                    if(is_array($fileRes) && count($fileRes)){
                        setFlashData($fileRes['message'],'danger',_l('fail'));
                    }else{
                        setFlashData(_l('file_upload_failed'),'danger',_l('fail'));
                    }
                }
            } 
        }

        $data['çategoryId'] = $categoryId;

        $data['listAssets'] = 'true';
        if($id == '' || $id == 0){
            $data['title'] = _l('add_new',_l('add_product'));
            $data['product'] = '';
        } else {      
			$product			= $this->Products_model->getProduct($id);             
            $data['product'] 	= $product;
            $data['title'] = _l('txt_update_product');
            $data['attribvalues'] = $AttribValues = $this->Products_model->getAttribValues(NULL,array('V_AttributeID'=>$product->P_Attributes),false);
        }

		$data['categories'] = $this->Categories_model->getCategory(NULL,array('C_Type'=>'2','C_Level'=>'1'),'ASC');
		$data['attributes'] = $this->Products_model->getAttributes(NULL,array('A_Status'=>'2'),'ASC');
		
        $data['addAssets'] = true;        
        $this->load->view(camelToSnake(getRedirectUrl()).'/products/'.'product',$data);
    }
	public function Attribute($id = ''){

        $data = $this->input->post();
        if(!empty($this->input->post())){
	
            if(!empty($id)){
                $success = $this->Products_model->updateAttributes($data,$id);				

                if ($success == true) {      
                    setFlashData(_l('product_attribute_update_success'),'success',_l('success'));
                    redirect('Admin/Products/Attributes/');
                } else {                                                
                    setFlashData(_l('product_attribute_update_fail'),'danger',_l('fail'));                 
                }   
   
            } else {

                $attribute = $this->Products_model->addAttributes($data);    
			
                if($attribute != false){  
				    setFlashData(_l('product_attribute_register_succes'),'success',_l('success'));   
                    redirect('Admin/Products/Attributes/');
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == ''){
            $data['title'] = _l('add_new',_l('add_product_attribute'));
            $data['attribute'] = '';
        } else {      
			$attribute			= $this->Products_model->getAttributes($id);
            $data['attribute'] 	= $attribute;
            $data['title'] = _l('txt_update_service_feature');
        }
        $data['listAssets'] = 'true';
		$data['addAssets'] = true;        
        $this->load->view(camelToSnake(getRedirectUrl()).'/products/'.'attribute',$data);
    }

	public function AttribValue($attributeid, $id = ''){

        $data = $this->input->post();
        if(!empty($this->input->post())){
	
            if(!empty($id)){
                $success = $this->Products_model->updateAttribValues($data,$id);

                if ($success == true) {
                    setFlashData(_l('product_attribute_value_update_success'),'success',_l('success'));
                    redirect('Admin/Products/Attributes');
                } else {                                                
                    setFlashData(_l('product_attribute_value_update_fail'),'danger',_l('fail'));                 
                }
            } else {

                $attribvalue = $this->Products_model->addAttribValues($data);    
			
                if($attribvalue != false){  
				    setFlashData(_l('product_attribute_register_succes'),'success',_l('success'));   
                    redirect('Admin/Products/Attributes');
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }
            }
        }

        $data['attributeName'] = $this->Products_model->getAttributes($attributeid);
        $data['attributeName'] = $data['attributeName']->A_Title;

        if($id == ''){
            $data['title'] = _l('add_new',_l('add_product_attribute_value'));
            $data['attribvalue'] = '';
        } else {      
			$attribvalue			= $this->Products_model->getAttribValues($id);             
            $data['attribvalue'] 	= $attribvalue;
            $data['title'] = _l('txt_update_product_attribute_value');
            $data['Attributes']			= $this->Products_model->getAttributes();
        }
		
		$data['AttributeID'] = $attributeid;      
		$data['addAssets'] = true;        
        $this->load->view(camelToSnake(getRedirectUrl()).'/products/'.'attribvalue',$data);
    }

	/* Delete Get Services Ajax */
	public function getAttributeValues(){
		$Attribute 	= $this->input->post('Val_Attribute');              
        $AttribValues = $this->Products_model->getAttribValues(NULL,array('V_AttributeID'=>$Attribute),false);

        if($AttribValues){            
            setAjaxResponse( _l('attribute_values_fetched_success'),'success',_l('success'),$AttribValues);
            // setAjaxResponse( null, null, null,$AttribValues);
        } else {
            setAjaxResponse( _l('attribute_values_fetched_fail'),'warning',_l('fail'));
        }
	}
    /* Delete Staffs */
    public function DeleteProduct(){    
             
               
        $ProductId = $this->input->post('id');              
        $Success = $this->Products_model->deleteProduct($ProductId);                 
        
        if($Success){             
            setAjaxResponse( _l('product_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('product_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    /* Delete Staffs */
    public function DeleteAttribute(){    
             
               
        $AttributeId = $this->input->post('id');              
        $Success = $this->Products_model->deleteAttributes($AttributeId);                 
        
        if($Success){             
            setAjaxResponse( _l('product_attribute_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('product_attribute_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
	 /* Delete Staffs */
    public function DeleteAttribValue(){    
             
               
        $AttribValueId = $this->input->post('id');              
        $Success = $this->Products_model->deleteAttribValues($AttribValueId);                 
        
        if($Success){             
            setAjaxResponse( _l('product_attribute_value_deleted_success'),'success',_l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('product_attribute_value_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Update staff stauts */
    public function UpdateProductStatus()
    {
        $data = $this->input->post();
        $ProductID = $data['id'];
        $data['Val_Pstatus'] = $data['status'];
		 
        if(!empty($data)){
            $CI =& get_instance();
            $role = $CI->session->userdata('role');
            if($role === 'vendor')
            {

                $Success = $this->Products_model->addVendorWithProducts(get_staff_user_id(),$ProductID);


            }
//            else{
//                $Success = $this->Products_model->update($data,$ProductID);
//            }

            if($Success){
                setAjaxResponse( _l('product_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('product_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
	 /* Update staff stauts */
    public function UpdateAttributeStatus()
    {     
        $data = $this->input->post();
        $AttributeID = $data['id'];
        $data['Val_Astatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Products_model->updateAttributes($data,$AttributeID);
            
            if($Success){
                setAjaxResponse( _l('product_attribute_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('product_attribute_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
	 /* Update staff stauts */
    public function UpdateAttribValueStatus()
    {     
        $data = $this->input->post();
        $AttribValueID = $data['id'];
        $data['Val_Vstatus'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Products_model->updateAttribValues($data,$AttribValueID);
            
            if($Success){
                setAjaxResponse( _l('product_attribute_value_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('product_attribute_value_status_update_fail'),'warning',_l('fail'));
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
            $Success = $this->Products_model->updateReviews($data,$ReviewID);
            
            if($Success){
                setAjaxResponse( _l('product_review_status_update_success'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('product_review_status_update_fail'),'warning',_l('fail'));
            }      
        }
    }
}
