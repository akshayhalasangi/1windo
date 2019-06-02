<?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      class Home extends Admin_Controller
      {
            public function __construct()
            {
                  parent::__construct();
            }

            /* This is admin home view */
            public function index()
            { 
                  if (!is_staff_logged_in()) { 
                  redirect(admin_url());
                  }
                  $id=$this->Products_model->getVendorCategoryID(get_staff_user_id());
                  $category=$this->Categories_model->get( $id);
                  switch ( $category->C_Type)
                  {
                      case 1:
                          redirect(admin_url('Services'));
                          break;
                      case 2:
                          redirect(admin_url('Products'));
                          break;
                      case 3:
                          redirect(admin_url('Restaurants'));
                          break;
                  }

                  $data['title'] = 'Home';
                  $this->load->view('admin/home', $data);	
            }

      }