<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Controller extends W_Controller
{
    //private $_current_version;

    public function __construct()
    {
        parent::__construct();
		  load_admin_language();
		  if (!is_staff_logged_in()) {
            if (strpos(current_full_url(), 'Authentication/Admin/') === FALSE) {
                $this->session->setUserData(array(
                    'red_url' => current_full_url()
                ));
            }

            redirect(site_url('Authentication/Admin/'));
        }

	}   
}
