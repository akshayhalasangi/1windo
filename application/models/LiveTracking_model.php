<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class LiveTracking_model extends W_Model
{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* Get registered admin's email
     * @param mixed $email
     * @return array
     */
   public function getLocation($order_id)
   {
        $this->db->select('*');
        $this->db->from('1w_tbl_livetracking');
        $this->db->where('order_id',$order_id);
       return $this->db->get()->result_array();
   }

   public function updateLocation($data,$order_id,$val_type)
   {
        $locationArray = array(
            'curr_lat'=>$data['curr_lat'],
            'curr_lng'=>$data['curr_lng']
        );
        $this->db->where('order_id',$order_id,'AND');
        $this->db->where('val_type',$val_type);
        $this->db->update('1w_tbl_livetracking', $locationArray);
        
        // $this->db->set('curr_lng',$lng);

        $whatistheproblem= $this->db->last_query();
        return true;
   }

//    public function inser($)
//    {
//         $this->db->select('*');
//         $this->db->from('1w_tbl_livetracking');
//         $this->db->where('order_id',$order_id);
//        return $this->db->get()->result_array();
//    }
   
}
