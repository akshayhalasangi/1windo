<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class W_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();      
                
        $this->db->reconnect();
        $timezone = get_option('default_timezone');
        if($timezone != ''){
            date_default_timezone_set($timezone);
        }
        do_action('app_init');
    }
}