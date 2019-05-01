<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
       // if (!has_permission('Categories', '', 'HasPermission')) {             
       //   ajax_access_denied();            
       // }
    }

    public function index(){
         
        $data['title'] = _l('title_categories');
        $data['listAssets'] = 'true';

        $this->load->view( CATEGORY_URL.'view', $data);
    }

    public function Type($type = 1, $subCategory = null, $childCategory = null){
        $data['listAssets'] = 'true';

        $data['type'] = $type;

        switch($type){
            case '1': $data['title'] = _l('service_categories');
                    break;

            case '2': $data['title'] = _l('product_categories');
                    break;

            case '3': $data['title'] = _l('food_categories');
                    break;

            default: $data['title'] = _l('title_categories');
                        break;
        }

        $data['categoriesList'] = $this->Categories_model->getCategory(null, array("C_Type" => $type, "C_Level" => 1));
        $data['level'] = "M";
        $data['parentId'] = 0;
        $data['outerParentId'] = 0;

        if($subCategory != null && $childCategory == null){
            $data['categoriesList'] = $this->Categories_model->getCategory(null, array("C_Type" => $type, "C_Level" => 2, 'C_Parent' => $subCategory));
            $data['subCategoriesName'] = $this->Categories_model->getCategory($subCategory);
            $data['level'] = "S";
            $data['parentId'] = $subCategory;
        }else if($subCategory != null && $childCategory != null){
            $data['categoriesList'] = $this->Categories_model->getCategory(null, array("C_Type" => $type, "C_Level" => 3, 'C_Parent' => $childCategory));
            $data['subCategoriesName'] = $this->Categories_model->getCategory($subCategory);
            $data['childCategoriesName'] = $this->Categories_model->getCategory($childCategory);
            $data['level'] = "C";
            $data['parentId'] = $childCategory;
            $data['outerParentId'] = $subCategory;

        }

        $this->load->view( CATEGORY_URL.'manage', $data);
    }

    public function Category($type = '', $level = '', $id = '', $parentId = 0, $outerParentId = 0){

        $data = $this->input->post();

        $data['type'] = $type;
        $data['parentId'] = $parentId;
        $data['outerParentId'] = $outerParentId;
        $data['id'] = $id;

        switch($level){
            case 'M': $level = 1;
                        break;

            case 'S': $level = 2;
                        break;

            case 'C': $level = 3;
                          break;

            default: $level = 0;
                          break;
        }

        $data['level'] = $level;

        if(!empty($this->input->post())){	
            if(!empty($id) && $id != 0){
				unset($data['Val_Cmobilenumber']);
				unset($data['Val_Cemailaddress']);
                $success = $this->Categories_model->update($data,$id);
				handle_category_display_image($id); 
				handle_category_display_icon($id); 
                if ($success == true) {
                    setFlashData(_l('category_register_succes'),'success',_l('success'));

                    if($data['Val_Level'] == 3){
                        redirect(admin_url("Categories/Type/".$data['Val_Type']."/".$data['Val_OuterParent']."/".$data['Val_Parent']));
                    }else if($data['Val_Level'] == 2) {
                        redirect(admin_url("Categories/Type/".$data['Val_Type']."/".$data['Val_Parent']));
                    }else{
                        redirect(admin_url("Categories/Type/".$data['Val_Type']));
                    }

                } else {                                                
                    setFlashData(_l('category_update_failed'),'danger',_l('fail'));
                }
            } else {
                $category = $this->Categories_model->add($data);    
				handle_category_display_image($category); 
				handle_category_display_icon($category); 
                if($category != false){
                    setFlashData(_l('category_register_succes'),'success',_l('success'));
                    if($data['Val_Level'] == 3){
                        redirect(admin_url("Categories/Type/".$data['Val_Type']."/".$data['Val_OuterParent']."/".$data['Val_Parent']));
                    }else if($data['Val_Level'] == 2) {
                        redirect(admin_url("Categories/Type/".$data['Val_Type']."/".$data['Val_Parent']));
                    }else{
                        redirect(admin_url("Categories/Type/".$data['Val_Type']));
                    }
                    // redirect(admin_url("Categories/Type/".$data['Val_Type']."/$subCategoryId"));
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));               
                }            
            } 
        }
        if($id == '' || $id == 0){
            $data['title'] = _l('add_new',_l('add_category'));
            $data['category'] = '';
        } else {      
			$category			= $this->Categories_model->getCategory($id);             
            $data['category'] 	= $category;
			
			if($category->C_Level == '2')
				$Level = '1';
			else if($category->C_Level == '3')	
				$Level = '2';
			else if($category->C_Level == '1')	
				$Level = '0';
		    $data['categories'] = $this->Categories_model->getCategory(NULL,array('C_Type'=>$category->C_Type,'C_Level'=>$Level));
            $data['title'] = _l('txt_update_category');
        }

        $data['addAssets'] = true;
        $this->load->view(CATEGORY_URL.'category',$data);
    }
    
	/* Delete Get Categories Ajax */
	public function getCategories(){
		$Type 	= $this->input->post('Val_Type');              
		$Level 	= $this->input->post('Val_Level');              
        $Categories = $this->Categories_model->getCategory(NULL,array('C_Type'=>$Type,'C_Level'=>$Level),false);        

        if($Categories){            
            setAjaxResponse( _l('categories_fetched_success'),'success',_l('success'),$Categories);
        } else {
            setAjaxResponse( _l('categories_fetched_fail'),'warning',_l('fail'));
        }
	}
	/* Delete Get Categories Ajax */
	public function getSubCategories(){
		$Category 	= $this->input->post('Val_Categories');              
        $Categories = $this->Categories_model->getCategory(NULL,array('C_Parent'=>$Category,'C_Level'=>'2'),false);        

        if($Categories){            
            setAjaxResponse( _l('categories_fetched_success'),'success',_l('success'),$Categories);
        } else {
            setAjaxResponse( _l('categories_fetched_fail'),'warning',_l('fail'));
        }
	}	
    /* Delete Staffs */
    public function DeleteCategory(){    
             
               
        $CategoryId = $this->input->post('id');              
        $Success = $this->Categories_model->deleteCategory($CategoryId);                 
        
        if($Success){             
            setAjaxResponse( _l('Category Deleted Successfully'),'success',_l('success'));
            //setFlashData(_l('category_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('Category Delete Failed'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    
    /* Update staff stauts */
    public function UpdateCategoryStatus()
    {
        $data = $this->input->post();
        $CategoryID = $data['id'];
        $data['Val_Status'] = $data['status'];
		 
        if(!empty($data)){
            $Success = $this->Categories_model->update($data,$CategoryID);
            
            if($Success){
                setAjaxResponse( _l('Category Status Modified.'),'success',_l('success'));
            } else {
                setAjaxResponse( _l('Category Status Couldn\'t Modified.'),'warning',_l('fail'));
            }      
        }
    }
    
}
