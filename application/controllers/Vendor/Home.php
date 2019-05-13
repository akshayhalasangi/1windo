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

                  redirect(admin_url('Products'));
                  $data['title'] = 'Home';
                  $this->load->view('admin/home', $data);	
            }

      }