<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_orders')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <?php
                $CI =& get_instance();
                $role = $CI->session->userdata('role');
                if($role !== 'vendor') {
                    ?>
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Orders'); ?>"><?= _l('txt_listing_services_orders'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Orders/Products'); ?>"><?= _l('txt_listing_products_orders'); ?></a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Orders/Restaurants'); ?>"><?= _l('txt_listing_restaurants_orders'); ?></a>
                        </li>                         
                    </ul>
                </div>
                <?php
                }
                ?>
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_orders')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($ordersList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('txt_username'); ?></th>
                                                    <th><?= _l('txt_order'); ?></th>
                                                    <th><?= _l('txt_total'); ?></th>
                                                    <th><?= _l('txt_payment_type'); ?></th>
                                                    <th><?= _l('txt_booked'); ?></th>
                                                    <th><?= _l('txt_status'); ?></th>
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($ordersList as $key => $order) : ?>
                                                <tr id="tr-<?= $order['RCartID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
                                                        <?= $order['RC_CustomerName']; ?>
                                                    </td>
                                                    <td>

                                                        <?php
                                                            $DetailIDsJson = $order['RC_DetailID'];
                                                            $DetailIDsArray = json_decode($DetailIDsJson);

                                                            if(!empty($DetailIDsArray))
                                                            {
                                                            foreach($DetailIDsArray as $DetailID)
                                                            {
                                                                $ExistingCartDetailData	=  $this->Cart_model->getRestaurantsCartDetails($DetailID);
                                                                $FoodID = '';
                                                                if(!empty($ExistingCartDetailData))
                                                                    {
                                                                        $FoodID 		= $ExistingCartDetailData->RD_FoodID;
                                                                        $FoodData 		= $this->Restaurants_model->getFoods($FoodID);
                                                                        $DisplayImage = (!empty($FoodData->F_DisplayImage) ? UPLOAD_RESTAURANTS_FOODS_BASE_URL.$FoodData->RFoodID.'/'.$FoodData->F_DisplayImage : ''); 
                                                                        if($FoodData->F_Type == '1')
                                                                            $F_Type = 'veg';
                                                                        else if($FoodData->F_Type == '2')
                                                                            $F_Type = 'non_veg';

                                                                        echo '<img class="ks-logo foodType" src="'.base_url('assets/admin/img/'.$F_Type.'.png').'">'.$FoodData->F_Title;
                                                                    }
                                                                }
                                                            }
                                                        ?>

                                                        <?= $order['RC_CustomerName']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $order['RC_Total']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $order['PO_ShortName']; ?>
                                                    </td>
                                                    <td>
														<?= $order['RC_BookedDttm']; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($order['RC_OrderStatus'] == 0) { ?>
	                                                       <span class="badge badge-default">Is In Cart</span>
                                                        <?php }  else if($order['RC_OrderStatus'] == 1) { ?>
	                                                       <span class="badge badge-primary">Booked</span>
                                                        <?php }  else if($order['RC_OrderStatus'] == 2) { ?>
	                                                       <span class="badge badge-info">Accepted</span>
													    <?php }  else if($order['RC_OrderStatus'] == 3) { ?>
	                                                       <span class="badge badge-pink">Processing</span>
													    <?php }  else if($order['RC_OrderStatus'] == 4) { ?>
	                                                       <span class="badge badge-success	">Delivered</span>
													    <?php }  else if($order['RC_OrderStatus'] == 5) { ?>
	                                                       <span class="badge badge-danger">Cancelled</span>
													    <?php }  else if($order['RC_OrderStatus'] == 6) { ?>
	                                                       <span class="badge badge-warning">Assigned</span>
                                                        <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php if($order['RC_OrderStatus'] == 1){ ?>
                                                            <button class="chngOrderStatus btn btn-outline-success" data-id="<?= $order['RCartID']; ?>" data-type="restaurant" data-action="3" data-url="<?php echo base_url('Admin/Orders/UpdateOrderStatus') ?>">Accept</button>
                                                            <button class="chngOrderStatus btn btn-outline-danger" data-id="<?= $order['RCartID']; ?>" data-type="restaurant"  data-action="5" data-url="<?php echo base_url('Admin/Orders/UpdateOrderStatus') ?>">Cancel</button>
                                                        <?php } else if($order['RC_OrderStatus'] == 2 || $order['RC_OrderStatus'] == 3) { ?>
                                                            <button class="chngOrderStatus btn btn-outline-danger" data-id="<?= $order['RCartID']; ?>" data-type="restaurant" data-action="5" data-url="<?php echo base_url('Admin/Orders/UpdateOrderStatus') ?>">Cancel</button>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $order['RCartID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $order['RCartID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Orders/restaurantsorder/'.$order['RCartID']); ?>"><?= _l('txt_view' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="order" data-id="<?= $order['RCartID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Orders/Profile/'.$order['RCartID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_orders_not_found')?></h5></div>
                                    <?php endif; ?>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= init_tail();?>