<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends W_Model
{
    private $cart_data = array(
        'CartID' => 'Val_Cart', 'C_CustomerID' => 'Val_Customer', 'C_CustomerName' => 'Val_Ccustomername',
        'C_CustomerAddress' => 'Val_Ccustomeraddress', 'C_ServiceID' => 'Val_Service',
        'C_ServiceNames' => 'Val_Cservicenames', 'C_PackageID' => 'Val_Package',
        'C_PackageNames' => 'Val_Cpackagenames', 'C_OptionID' => 'Val_Option', 'C_OptionNames' => 'Val_Coptionnames',
        'C_OptionPrices' => 'Val_Coptionprices', 'C_AddressID' => 'Val_Address', 'C_Date' => 'Val_Cdate',
        'C_TimeslabID' => 'Val_Ctimeslab', 'C_Timeslab' => 'Val_Ctimeslabtitle',
        'C_PaymentOption' => 'Val_Cpaymentoption', 'C_ServiceCharge' => 'Val_Cservicecharge', 'C_Total' => 'Val_Ctotal',
        'C_OrderStatus' => 'Val_Corderstatus', 'C_Status' => 'Val_Cstatus', 'C_AssignedTo' => 'Val_Cassginedto',
        'C_BookedDttm' => 'Val_Cbookeddttm', 'C_AcceptedDttm' => 'Val_Caccepteddttm',
        'C_ProcessingDttm' => 'Val_Cprocessingdttm', 'C_DeliveredDttm' => 'Val_Cdelivereddttm',
        'C_CancelledDttm' => 'Val_Ccancelleddttm', 'C_AssignedDttm' => 'Val_Cassigneddttm', 'RowAddedDttm' => '',
        'RowUpdatedDttm' => ''
    );

    private $product_cart_data = array(
        'PCartID' => 'Val_Cart', 'PC_CustomerID' => 'Val_Customer', 'PC_CustomerName' => 'Val_PCcustomername',
        'PC_CustomerAddress' => 'Val_PCcustomeraddress', 'PC_ProductID' => 'Val_Product',
        'PC_ProductNames' => 'Val_PCproductnames', 'PC_Date' => 'Val_PCdate', 'PC_DetailID' => 'Val_PCdetail',
        'PC_Prices' => 'Val_PCprices', 'PC_AddressID' => 'Val_Address', 'PC_ItemTotal' => 'Val_PCitemtotal',
        'PC_DeliveryCharge' => 'Val_PCdeliverycharges', 'PC_CartTotal' => 'Val_PCcarttotal',
        'PC_PaymentOption' => 'Val_PCpaymentoption', 'PC_ServiceCharge' => 'Val_PCservicecharge',
        'PC_Total' => 'Val_PCtotal', 'PC_OrderStatus' => 'Val_PCorderstatus', 'PC_Status' => 'Val_PCstatus',
        'PC_AssignedTo' => 'Val_PCassginedto', 'PC_DeliveryBy' => 'Val_PCdeliveryby',
        'PC_DeliveryByStatus' => 'Val_PCdeliverybystatus', 'PC_BookedDttm' => 'Val_PCbookeddttm',
        'PC_AcceptedDttm' => 'Val_PCaccepteddttm', 'PC_ProcessingDttm' => 'Val_PCprocessingdttm',
        'PC_DeliveredDttm' => 'Val_PCdelivereddttm', 'PC_CancelledDttm' => 'Val_PCcancelleddttm',
        'PC_AssignedDttm' => 'Val_PCassigneddttm', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $product_cart_details_data = array(
        'CPDetailID' => 'Val_Cartdetail', 'PD_CartID' => 'Val_Cart', 'PD_ProductID' => 'Val_Product',
        'PD_Quantity' => 'Val_PDquantity', 'PD_AttributeID' => 'Val_Attribute', 'PD_AttribValueID' => 'Val_Attribvalue',
        'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );

    private $restaurant_cart_data = array(
        'RCartID' => 'Val_Cart', 'RC_CustomerID' => 'Val_Customer', 'RC_CustomerName' => 'Val_RCcustomername',
        'RC_CustomerAddress' => 'Val_RCcustomeraddress', 'RC_RestaurantID' => 'Val_Restaurant',
        'RC_RestaurantName' => 'Val_RCrestaurantname', 'RC_Date' => 'Val_RCdate', 'RC_DetailID' => 'Val_RCdetail',
        'RC_Prices' => 'Val_RCprices', 'RC_AddressID' => 'Val_Address', 'RC_ItemCount' => 'Val_RCitemcount',
        'RC_ItemTotal' => 'Val_RCitemtotal', 'RC_DeliveryCharge' => 'Val_RCdeliverycharges',
        'RC_CartTotal' => 'Val_RCcarttotal', 'RC_PaymentOption' => 'Val_RCpaymentoption',
        'RC_ServiceCharge' => 'Val_RCservicecharge', 'RC_Total' => 'Val_RCtotal',
        'RC_OrderStatus' => 'Val_RCorderstatus', 'RC_Status' => 'Val_RCstatus', 'RC_AssignedTo' => 'Val_RCassginedto',
        'RC_DeliveryBy' => 'Val_RCdeliveryby', 'RC_DeliveryByStatus' => 'Val_RCdeliverybystatus',
        'RC_BookedDttm' => 'Val_RCbookeddttm', 'RC_AcceptedDttm' => 'Val_RCaccepteddttm',
        'RC_ProcessingDttm' => 'Val_RCprocessingdttm', 'RC_DeliveredDttm' => 'Val_RCdelivereddttm',
        'RC_CancelledDttm' => 'Val_RCcancelleddttm', 'RC_AssignedDttm' => 'Val_RCassigneddttm', 'RowAddedDttm' => '',
        'RowUpdatedDttm' => ''
    );

    private $restaurant_cart_details_data = array(
        'CRDetailID' => 'Val_Cartdetail', 'RD_CartID' => 'Val_Cart', 'RD_FoodID' => 'Val_Food',
        'RD_Quantity' => 'Val_RDquantity', 'RD_Price' => 'Val_RDprice', 'RowAddedDttm' => '', 'RowUpdatedDttm' => ''
    );


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
// This function used for retrive data from Cart Service Table
    public function getCartData($cart_id)
    {

        if (!empty($cart_id)) {

            $this->db->select('*');
            $this->db->from(TBL_CART_SERVICES);
            $this->db->where('CartID', $cart_id);
            return $this->db->get()->result_array();
        } else {

            return false;
        }
    }

    // This function used for retrive data from Cart Product Table
    public function getProductCartData($cart_id)
    {

        if (!empty($cart_id)) {

            $this->db->select('*');
            $this->db->from('1w_tbl_cart_product');
            $this->db->where('PCartID', $cart_id);
            $this->db->where('(PC_Status = 1 OR PC_Status = 2)');
            return $this->db->get()->result_array();
        } else {

            return false;
        }


    }

    public function get($cart_id = '', $where = array(), $wherestring = '', $orderby = 'ASC')
    {


        if ($cart_id != '') {
            $this->db->where('CartID', $cart_id);
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }

            $query = $this->db->get(TBL_CART_SERVICES);
            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                // $this->db->select(TBL_CART_SERVICES.".*, ".TBL_PAYMENT_OPTIONS.".*");
                // $this->db->from(TBL_CART_SERVICES);
                // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_SERVICES.'.C_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');
                $this->db->select('*');
                $this->db->from(TBL_CART_SERVICES);
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('CartID', $cart_id);
                $result = $this->db->get()->result_array();

            } else {
                $result = false;
            }

            return $result;
        }

        $this->db->order_by('C_OrderStatus', $orderby);
        $query = $this->db->get(TBL_CART_SERVICES);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            // $this->db->select(TBL_CART_SERVICES.".*, ".TBL_PAYMENT_OPTIONS.".*");
            // $this->db->from(TBL_CART_SERVICES);
            // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_SERVICES.'.C_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');

            $staffID = get_staff_user_id();
            $this->db->where('Staff_ID', $staffID);
            $result = $this->db->get('staffs')->row_array();
            if (! is_null($result)){
                switch ($result->S_IsAdmin) {
                    case 0:
                        $this->db->order_by('C_OrderStatus', $orderby);
                        $result = $this->db->get('cart_service')->result_array();
                        break;
                    case 1:
                        $this->db->select('CustomerID');
                        $this->db->where('C_Area', $result->Area);
                        $result1 = $this->db->get('1w_tbl_customers')->result_array();
                        $customers = array_map(function ($item) {
                            return (int) $item['CustomerID'];
                        }, $result1);

                        $this->db->where_in('C_CustomerID', $customers);
                        $this->db->order_by('C_OrderStatus', $orderby);
                        $result = $this->db->get('cart_service')->result_array();
                        break;
                }
            }else {
                $this->db->select('*');
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }
                $result = $this->db->get(TBL_CART_SERVICES)->result_array();

            }

        } else {
            $result = false;
        }
//			$whatisthequery= $this->db->last_query();
        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public function getCartTotal($cart_id = '', $where = array(), $wherestring = '', $orderby = 'DESC')
    {

        $this->db->where($where);
        if (!empty($wherestring)) {
            $this->db->where($wherestring);
        }

        if ($cart_id != '') {
            $this->db->where('CartID', $cart_id);
            $query = $this->db->get(TBL_CART_SERVICES);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('CartID', $cart_id);
                $result = $this->db->get(TBL_CART_SERVICES)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('CartID', $orderby);
        $query = $this->db->get(TBL_CART_SERVICES);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }

            $this->db->select_sum('C_Total');
            $this->db->order_by('CartID', $orderby);
            $result = $this->db->get(TBL_CART_SERVICES)->row();
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public function getProductsCart($cart_id = '', $where = array(), $wherestring = '', $orderby = 'ASC')
    {

        if ($cart_id != '') {
            $this->db->where('PCartID', $cart_id);
            $query = $this->db->get(TBL_CART_PRODUCTS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                // $this->db->select(TBL_CART_PRODUCTS.".*, ".TBL_PAYMENT_OPTIONS.".*");
                // $this->db->from(TBL_CART_PRODUCTS);
                // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_PRODUCTS.'.PC_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');

                $this->db->select('*');
                $this->db->from(TBL_CART_PRODUCTS);
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('PCartID', $cart_id);
                $result = $this->db->get()->result();
                $whatisthequery = $this->db->last_query();
            } else {
                $result = false;
            }
            return $result;
        }


        $this->db->order_by('PC_OrderStatus', $orderby);
        $query = $this->db->get(TBL_CART_PRODUCTS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            // $this->db->select(TBL_CART_PRODUCTS.".*, ".TBL_PAYMENT_OPTIONS.".*");
            // $this->db->from(TBL_CART_PRODUCTS);
            // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_PRODUCTS.'.PC_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');
            $this->db->select('*');
            $this->db->from(TBL_CART_PRODUCTS);
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }
            $staffID = get_staff_user_id();
            $this->db->where('Staff_ID', $staffID);
            $result = $this->db->get('staffs')->row_array();
            if (! is_null($result)) {
                switch ($result->S_IsAdmin) {
                    case 0:
                        $this->db->order_by('PC_OrderStatus', $orderby);
                        $result = $this->db->get(TBL_CART_PRODUCTS)->result_array();
                        break;
                    case 1:
                        $this->db->select('CustomerID');
                        $this->db->where('C_Area', $result->Area);
                        $result1 = $this->db->get('1w_tbl_customers')->result_array();
                        $customers = array_map(function ($item) {
                            return (int) $item['CustomerID'];
                        }, $result1);
                        $this->db->where_in('PC_CustomerID', $customers);
                        $this->db->order_by('PC_OrderStatus', $orderby);
                        $result = $this->db->get(TBL_CART_PRODUCTS)->result_array();
                        break;
                }
            }else {
                $this->db->select('*');
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }
                $result = $this->db->get(TBL_CART_PRODUCTS)->result_array();

            }

        } else {
            $result = false;
        }
        $whatisthequery = $this->db->last_query();
        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public function getProductsCartTotal($cart_id = '', $where = array(), $wherestring = '', $orderby = 'DESC')
    {
        $this->db->where($where);
        if (!empty($wherestring)) {
            $this->db->where($wherestring);
        }

        if ($cart_id != '') {
            $this->db->where('CartID', $cart_id);
            $query = $this->db->get(TBL_CART_SERVICES);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('CartID', $cart_id);
                $result = $this->db->get(TBL_CART_SERVICES)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('PCartID', $orderby);
        $query = $this->db->get(TBL_CART_PRODUCTS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }

            $this->db->select_sum('PC_Total');
            $this->db->order_by('PCartID', $orderby);
            $result = $this->db->get(TBL_CART_PRODUCTS)->row();
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public
    function getProductsCartDetails(
        $cartdetail_id = '',
        $where = array(),
        $orderby = 'DESC'
    ) {
        $this->db->where($where);

        if ($cartdetail_id != '') {
            $this->db->where('CPDetailID', $cartdetail_id);
            $query = $this->db->get(TBL_CART_PRODUCTS_DETAILS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where('CPDetailID', $cartdetail_id);
                $result = $this->db->get(TBL_CART_PRODUCTS_DETAILS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('CPDetailID', $orderby);
        $query = $this->db->get(TBL_CART_PRODUCTS_DETAILS);

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('CPDetailID', $orderby);
            $result = $this->db->get(TBL_CART_PRODUCTS_DETAILS)->result_array();
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public
    function getRestaurantsCart(
        $cart_id = '',
        $where = array(),
        $wherestring = '',
        $orderby = 'DESC'
    ) {
        $this->db->where($where);
        if (!empty($wherestring)) {
            $this->db->where($wherestring);
        }
        if ($cart_id != '') {
            $this->db->where('RCartID', $cart_id);
            $query = $this->db->get(TBL_CART_RESTAURANTS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                // $this->db->select(TBL_CART_RESTAURANTS.".*, ".TBL_PAYMENT_OPTIONS.".*");
                // $this->db->from(TBL_CART_RESTAURANTS);
                // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_RESTAURANTS.'.RC_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');

                $this->db->select('*');
                $this->db->from(TBL_CART_RESTAURANTS);
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('RCartID', $cart_id);
                $result = $this->db->get()->result_array();
            } else {
                $result = false;
            }

            return $result;
        }

        $this->db->order_by('RCartID', $orderby);
        $query = $this->db->get(TBL_CART_RESTAURANTS);
        $whatisthequery = $this->db->last_query();

        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            // $this->db->select(TBL_CART_RESTAURANTS.".*, ".TBL_PAYMENT_OPTIONS.".*");
            // $this->db->from(TBL_CART_RESTAURANTS);
            // $this->db->join(TBL_PAYMENT_OPTIONS, TBL_CART_RESTAURANTS.'.RC_PaymentOption = '.TBL_PAYMENT_OPTIONS.'.POptionId');
            $this->db->select('*');
            $this->db->from(TBL_CART_RESTAURANTS);
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }
            $staffID = get_staff_user_id();
            $this->db->where('Staff_ID', $staffID);
            $result = $this->db->get('staffs')->row();
            switch ($result->S_IsAdmin) {
                case 0:
                    $this->db->order_by('RC_OrderStatus', $orderby);
                    $result = $this->db->get(TBL_CART_RESTAURANTS)->result_array();
                    break;
                case 1:
                    $this->db->select('CustomerID');
                    $this->db->where('C_Area', $result->Area);
                    $result1 = $this->db->get('1w_tbl_customers')->result_array();
                    $customers = array_map(function ($item) {
                        return (int) $item['CustomerID'];
                    }, $result1);
                    $this->db->where_in('RC_CustomerID', $customers);
                    $this->db->order_by('RC_OrderStatus', $orderby);
                    $result = $this->db->get(TBL_CART_RESTAURANTS)->result_array();
                    break;
            }
        } else {
            $result = false;
        }
//		echo $this->db->last_query();
        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public
    function getRestaurantsCartTotal(
        $cart_id = '',
        $where = array(),
        $wherestring = '',
        $orderby = 'DESC'
    ) {

        $this->db->where($where);
        if (!empty($wherestring)) {
            $this->db->where($wherestring);
        }

        if ($cart_id != '') {
            $this->db->where('CartID', $cart_id);
            $query = $this->db->get(TBL_CART_SERVICES);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where($where);
                if (!empty($wherestring)) {
                    $this->db->where($wherestring);
                }

                $this->db->where('CartID', $cart_id);
                $result = $this->db->get(TBL_CART_SERVICES)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('RCartID', $orderby);
        $query = $this->db->get(TBL_CART_RESTAURANTS);

        $rowcount = $query->num_rows();

        if ($rowcount > 0) {
            $this->db->where($where);
            if (!empty($wherestring)) {
                $this->db->where($wherestring);
            }

            $this->db->select_sum('RC_Total');
            $this->db->order_by('RCartID', $orderby);
            $result = $this->db->get(TBL_CART_RESTAURANTS)->row();
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Get categories contacts
     * @param  mixed  $category_id
     * @param  array  $where  perform where in query array('U_Status' => 1)
     * @return array
     */
    public
    function getRestaurantsCartDetails(
        $cartdetail_id = '',
        $where = array(),
        $orderby = 'DESC'
    ) {

        $this->db->where($where);

        if ($cartdetail_id != '') {
            $this->db->where('CRDetailID', $cartdetail_id);
            $query = $this->db->get(TBL_CART_RESTAURANTS_DETAILS);

            $rowcount = $query->num_rows();
            if ($rowcount > 0) {
                $this->db->where('CRDetailID', $cartdetail_id);
                $result = $this->db->get(TBL_CART_RESTAURANTS_DETAILS)->row();
            } else {
                $result = false;
            }

            return $result;
        }


        $this->db->order_by('CRDetailID', $orderby);
        $query = $this->db->get(TBL_CART_RESTAURANTS_DETAILS);
        $whatisthequery = $this->db->last_query();
        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->where($where);
            $this->db->order_by('CRDetailID', $orderby);
            $result = $this->db->get(TBL_CART_RESTAURANTS_DETAILS)->result_array();
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @param  array  $_POST  data
     * @param  category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public
    function add(
        $data
    ) {
        $cart_data = array();
        foreach ($this->cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $cart_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_category_added', $data);

        $this->db->insert(TBL_CART_SERVICES, $cart_data);

        $cartid = $this->db->insert_id();
        if ($cartid) {

            do_action('after_cart_added', $cartid);

            $_new_cart_log = $cartid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_cart_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Cart Created ['.$_new_cart_log.']', $_is_staff);
        }

        return $cartid;
    }

    /**
     * @param  array  $_POST  data
     * @param  category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public
    function addCartProducts(
        $data
    ) {
        $product_cart_data = array();
        foreach ($this->product_cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $product_cart_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_cart_product_added', $data);

        $this->db->insert(TBL_CART_PRODUCTS, $product_cart_data);

        $cartid = $this->db->insert_id();
        if ($cartid) {

            do_action('after_cart_product_added', $cartid);

            $_new_cart_log = $cartid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_cart_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Cart Product Created ['.$_new_cart_log.']', $_is_staff);
        }

        return $cartid;
    }

    /**
     * @param  array  $_POST  data
     * @param  category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public
    function addCartProductsDetails(
        $data
    ) {
        $product_cart_details_data = array();
        foreach ($this->product_cart_details_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $product_cart_details_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_cart_product_details_added', $data);

        $this->db->insert(TBL_CART_PRODUCTS_DETAILS, $product_cart_details_data);

        $cartdetailid = $this->db->insert_id();
        if ($cartdetailid) {

            do_action('after_cart_product_details_added', $cartdetailid);

            $_new_cart_log = $cartdetailid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_cart_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Cart Product Details Created ['.$_new_cart_log.']', $_is_staff);
        }

        return $cartdetailid;
    }

    /**
     * @param  array  $_POST  data
     * @param  category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public
    function addCartRestaurants(
        $data
    ) {
        $restaurant_cart_data = array();
        foreach ($this->restaurant_cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $restaurant_cart_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_cart_restaurant_added', $data);

        $this->db->insert(TBL_CART_RESTAURANTS, $restaurant_cart_data);

        $cartid = $this->db->insert_id();
        if ($cartid) {

            do_action('after_cart_restaurant_added', $cartid);

            $_new_cart_log = $cartid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_cart_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Cart Restaurant Created ['.$_new_cart_log.']', $_is_staff);
        }

        return $cartid;
    }

    /**
     * @param  array  $_POST  data
     * @param  category_request is this request from the category area
     * @return integer Insert ID
     * Add new category to database
     */
    public
    function addCartRestaurantsDetails(
        $data
    ) {
        $restaurant_cart_details_data = array();
        foreach ($this->restaurant_cart_details_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $restaurant_cart_details_data[$dbfield] = $data[$field];
            }
        }

        $data = do_action('before_cart_restaurant_details_added', $data);

        $this->db->insert(TBL_CART_RESTAURANTS_DETAILS, $restaurant_cart_details_data);

        $cartdetailid = $this->db->insert_id();
        if ($cartdetailid) {

            do_action('after_cart_restaurant_details_added', $cartdetailid);

            $_new_cart_log = $cartdetailid;

            $_is_staff = null;
            if (is_staff_logged_in()) {
                $_new_cart_log .= ' From Staff: '.get_staff_user_id();
                $_is_staff = get_staff_user_id();
            }

            logActivity('New Cart Restaurant Details Created ['.$_new_cart_log.']', $_is_staff);
        }

        return $cartdetailid;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public function update( $data, $cartid ) {
        $affectedRows = 0;
        $cart_data = array();
        foreach ($this->cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $cart_data[$dbfield] = $data[$field];
            }
        }
        //print_r($category_data);
        $this->db->where('CartID', $cartid);
        $this->db->update(TBL_CART_SERVICES, $cart_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_updated', $cartid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Info Updated ['.$cartid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function updateCartProducts(
        $data,
        $cartproductid
    ) {

        $affectedRows = 0;
        $product_cart_data = array();
        foreach ($this->product_cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $product_cart_data[$dbfield] = $data[$field];
            }
        }
die(var_dump($product_cart_data));
            //print_r($category_data);
            $this->db->where('PCartID', $cartproductid);
            $this->db->update(TBL_CART_PRODUCTS, $product_cart_data);

            if ($this->db->affected_rows() > 0) {
                $affectedRows++;

                do_action('after_cart_product_updated', $cartproductid);
            } else {
                //echo 'tes';
            }
            if ($affectedRows > 0) {
//                $this->db->limit(1);
//                $this->db->order_by('DeliveryBoyID','desc');
//                $deliveryboy = $this->db->get('1w_tbl_delivery_boys')->row();
//
//                $data = array(
//                    'PC_DeliveryBy' => $deliveryboy->DeliveryBoyID,
//                    'PC_DeliveryByStatus' => '1',
//                );
//
//                $this->db->where('PCartID', $cartproductid);
//                $r = $this->db->update(TBL_CART_PRODUCTS,$data);

                logActivity('Cart Product Info Updated [' . $cartproductid . ']');

                return true;
            }

            return false;

    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function updateCartProductsDetails(
        $data,
        $cartproductdetailid
    ) {
        $affectedRows = 0;
        $product_cart_details_data = array();
        foreach ($this->product_cart_details_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $product_cart_details_data[$dbfield] = $data[$field];
            }
        }
        //print_r($category_data);
        $this->db->where('CPDetailID', $cartproductdetailid);
        $this->db->update(TBL_CART_PRODUCTS_DETAILS, $product_cart_details_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_product_detail_updated', $cartproductdetailid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Product Detail Info Updated ['.$cartproductdetailid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function removeDeliveryBoyCartProducts(
        $cartproductid
    ) {
        $affectedRows = 0;
        $data[''] = '2';

        $product_cart_details_data = array('PC_Deliveryby' => null, 'PC_Deliverybystatus' => '0');
        //print_r($category_data);
        $this->db->where('PCartID', $cartproductid);
        $this->db->update(TBL_CART_PRODUCTS, $product_cart_details_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_product_detail_updated', $cartproductid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Product Detail Info Updated ['.$cartproductid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function updateCartRestaurants(
        $data,
        $cartrestaurantid
    ) {
        $affectedRows = 0;
        $restaurant_cart_data = array();

        foreach ($this->restaurant_cart_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $restaurant_cart_data[$dbfield] = $data[$field];
            }
        }


        $this->db->where('RCartID', $cartrestaurantid);
        $this->db->update(TBL_CART_RESTAURANTS, $restaurant_cart_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_restaurant_updated', $cartrestaurantid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Restaurant Info Updated ['.$cartrestaurantid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function updateCartRestaurantsDetails(
        $data,
        $cartrestaurantdetailid
    ) {


        $affectedRows = 0;
        $restaurant_cart_details_data = array();
        foreach ($this->restaurant_cart_details_data as $dbfield => $field) {
            if (isset($data[$field])) {
                $restaurant_cart_details_data[$dbfield] = $data[$field];
            }
        }
        //print_r($category_data);
        $this->db->where('CRDetailID', $cartrestaurantdetailid);
        $this->db->update(TBL_CART_RESTAURANTS_DETAILS, $restaurant_cart_details_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_restaurant_detail_updated', $cartrestaurantdetailid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Restaurant Detail Info Updated ['.$cartrestaurantdetailid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  array  $_POST  data
     * @param  integer ID
     * @return boolean
     * Update category informations
     */
    public
    function removeDeliveryBoyCartRestaurants(
        $cartrestaurantid
    ) {
        $affectedRows = 0;
        $data[''] = '2';

        $restaurant_cart_details_data = array('RC_Deliveryby' => null, 'RC_Deliverybystatus' => '0');
        //print_r($category_data);
        $this->db->where('RCartID', $cartrestaurantid);
        $this->db->update(TBL_CART_RESTAURANTS, $restaurant_cart_details_data);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            //echo 'asd';
            do_action('after_cart_product_detail_updated', $cartrestaurantid);
        } else {
            //echo 'tes';
        }
        if ($affectedRows > 0) {
            logActivity('Cart Restaurant Detail Info Updated ['.$cartrestaurantid.']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
    public
    function delete(
        $id
    ) {

        $affectedRows = 0;
        do_action('before_cart_deleted', $id);
        $this->db->where('CartID', $id);
        $this->db->delete(TBL_CART_SERVICES);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_cart_deleted');
            logActivity(_l('cart_deleted').' ['.$id.']');
            return true;
        }

        return false;

    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
    public
    function deleteCartProducts(
        $id
    ) {

        $affectedRows = 0;
        do_action('before_cart_product_deleted', $id);
        $this->db->where('PCartID', $id);
        $this->db->delete(TBL_CART_PRODUCTS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_cart_product_deleted');
            logActivity(_l('cart_product_deleted').' ['.$id.']');
            return true;
        }

        return false;

    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
    public
    function deleteCartProductsDetails(
        $id
    ) {

        $affectedRows = 0;
        do_action('before_cart_product_details_deleted', $id);
        $this->db->where('CPDetailID', $id);
        $this->db->delete(TBL_CART_PRODUCTS_DETAILS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_cart_product_details_deleted');
            logActivity(_l('cart_product_details_deleted').' ['.$id.']');
            return true;
        }

        return false;

    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
    public
    function deleteCartRestaurants(
        $id
    ) {

        $affectedRows = 0;
        do_action('before_cart_product_deleted', $id);
        $this->db->where('RCartID', $id);
        $this->db->delete(TBL_CART_RESTAURANTS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_cart_product_deleted');
            logActivity(_l('cart_product_deleted').' ['.$id.']');
            return true;
        }

        return false;

    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete Category
     */
    public
    function deleteCartRestaurantsDetails(
        $id
    ) {

        $affectedRows = 0;
        do_action('before_cart_product_details_deleted', $id);
        $this->db->where('CRDetailID', $id);
        $this->db->delete(TBL_CART_RESTAURANTS_DETAILS);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            do_action('after_cart_product_details_deleted');
            logActivity(_l('cart_product_details_deleted').' ['.$id.']');
            return true;
        }

        return false;

    }

// Created by Akshay and Niranjan
    public
    function getServiceOngoingOrders($id) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_service');
        $this->db->where('C_AssignedTo', $id);
        $this->db->where('(C_Status = 1 OR C_Status = 2)');
        $this->db->where('(C_OrderStatus = 0 OR C_OrderStatus = 1 OR C_OrderStatus = 2 OR C_OrderStatus = 3 OR C_OrderStatus = 6)');
        $result = $this->db->last_query();
        return $this->db->get()->result_array();
    }

    public function getAcceptableServiceOrders()
    {

    }
    public function getAcceptableProductsOrders()
    {
        
        
    }
    public function getAcceptableRestaurantOrders()
    {
        
    }


    public
    function getServicePastOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_service');
        $this->db->where('C_AssignedTo', $id);
        $this->db->where('(C_Status = 3 OR C_Status = 4)');
        $this->db->where('(C_OrderStatus = 4 OR C_OrderStatus = 5)');
        $magic = $this->db->last_query();
        return $this->db->get()->result_array();
    }


    public
    function getProductsOngoingOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
//        $this->db->where('PC_AssignedTo', $id);
        $this->db->where("(PC_Status = '1' OR PC_Status = '2')");
        $this->db->where("(PC_OrderStatus = '0' OR PC_OrderStatus = '1' OR PC_OrderStatus = '2' OR PC_OrderStatus = '3' OR PC_OrderStatus = '6')");
        $result = $this->db->last_query();
        return $this->db->get()->result_array();
    }

    public function getProductsNewOngoingOrders($id)
    {
        $this->db->where('vendor_id', $id);
        $query = $this->db->get('1w_tbl_product_vendor');
        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            $this->db->select('*');
            $this->db->from('1w_tbl_cart_product');
            $this->db->where('PC_PaymentOption= 1');
            $this->db->where('(PC_Status = 1 OR PC_Status = 2)');
            $this->db->where('(PC_OrderStatus = 0 OR PC_OrderStatus = 1)');
            return $this->db->get()->result_array();
        }
        else
        {
            return false;
        }
    }

    public
    function getProductsPastOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
        $this->db->where('PC_AssignedTo', $id);
        $this->db->where('(PC_Status = 3 OR PC_Status = 4)');
        $this->db->where('(PC_OrderStatus = 4 OR PC_OrderStatus = 5)');
        return $this->db->get()->result_array();
    }

    public
    function getRestaurantsOngoingOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
        $this->db->where('RC_AssignedTo', $id);
        $this->db->where('( RC_Status = 1 OR RC_Status = 2)');
        $this->db->where("(RC_OrderStatus = 0 OR RC_OrderStatus = 1 OR RC_OrderStatus = 2 OR RC_OrderStatus = 3 OR RC_OrderStatus = 6)");
        return $this->db->get()->result_array();
    }

    public
    function getRestaurantsPastOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
        $this->db->where('RC_AssignedTo', $id);
        $this->db->where('( RC_Status = 3 OR RC_Status = 4)');
        $this->db->where('(RC_OrderStatus = 4 OR RC_OrderStatus = 5)');
        return $this->db->get()->result_array();
    }

    public
    function getRestaurantsPastOrdersData(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
        $this->db->where('RC_AssignedTo', $id);
        $this->db->where('RC_Status', 3);
        return $this->db->get()->result_array();
    }

    public
    function getServicePastOrdersData(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_service');
        $this->db->where('C_AssignedTo', $id);
        $this->db->where(' C_Status', 3);
        return $this->db->get()->result_array();
    }

    public
    function getProductsPastOrdersData(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
        $this->db->where('PC_AssignedTo', $id);
        $this->db->where(' PC_Status', 3);
        return $this->db->get()->result_array();
    }

//Get Order Data Functionality For Delivery Boy

    public
    function getDProductsOrdersData(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
        $this->db->where('PC_DeliveryBy', $id);
        $this->db->where('(PC_Status = 1 OR PC_Status = 2)');
        $this->db->where('(PC_DeliveryByStatus = 1 OR PC_DeliveryByStatus = 2 OR PC_DeliveryByStatus = 3 OR PC_DeliveryByStatus = 4)');
        return $this->db->get()->result_array();
    }

    public
    function getDRestaurantsOrdersData(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
        $this->db->where('RC_DeliveryBy', $id);
        $this->db->where('(RC_Status = 1 OR RC_Status = 2)');
        $this->db->where('(RC_DeliveryByStatus = 1 OR RC_DeliveryByStatus = 2 OR RC_DeliveryByStatus = 3 OR RC_DeliveryByStatus = 4) ');
        return $this->db->get()->result_array();
    }

//Get Ongoing Order Data Functionality For Delivery Boy

    public
    function getDProductsOngoingOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
        $this->db->where('PC_DeliveryBy', $id);
        $this->db->where("(PC_Status = '1' OR PC_Status = '2')");
        $this->db->where("(PC_DeliveryByStatus = '1' OR PC_DeliveryByStatus = '2' OR PC_DeliveryByStatus = '3' )");
        $this->db->where("(PC_OrderStatus = '2' OR PC_OrderStatus = '3' )");
        return $this->db->get()->result_array();
    }

    public
    function getDRestaurantsOngoingOrders(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
       
        $this->db->where('(RC_Status = 1 OR RC_Status = 2)');
        $this->db->where('(RC_DeliveryByStatus = 1 OR RC_DeliveryByStatus = 2 OR RC_DeliveryByStatus = 3) ');
        $this->db->where('(RC_OrderStatus = 2 OR RC_OrderStatus = 3)');
        return $this->db->get()->result_array();
    }

//Get Order History Functionality for Delivery Boy

    public
    function getDProductsOrdersHistory(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_product');
        $this->db->where('PC_DeliveryBy', $id);
        $this->db->where('(PC_Status = 3 OR PC_Status = 4)');
        $this->db->where('(PC_DeliveryByStatus = 4 OR PC_OrderStatus = 5)');
        $this->db->where('(PC_OrderStatus = 4 OR PC_OrderStatus = 5)');
        return $this->db->get()->result_array();
    }

    public
    function getDRestaurantsOrdersHistory(
        $id
    ) {
        $this->db->select('*');
        $this->db->from('1w_tbl_cart_restaurant');
        $this->db->where('RC_DeliveryBy', $id);
        $this->db->where('(RC_Status = 3 OR RC_Status = 4)');
        $this->db->where("(RC_DeliveryByStatus = 4 OR RC_OrderStatus = 5)");
        $this->db->where('(RC_OrderStatus = 4 OR RC_OrderStatus = 5)');
        return $this->db->get()->result_array();
    }


}

?>