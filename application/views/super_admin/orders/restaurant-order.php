 <?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_orders')?></h3>
				<button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Orders/Restaurants') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">             
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
							<div class="row">
                                <div class="col-lg-12">
                                     <h4 id="form-validation-basic-demo"><?= _l('txt_view_order'); ?></h4>
                                    <br/>                                  
                                    <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                    <div class="card panel">
                                        <div class="card-block">
                                            <div class="ks-order-page ks-compact">
												<div class="ks-info">
													<?php
														$AddressArray = json_decode($order->RC_CustomerAddress);
													 ?>
													<div class="ks-header">
														<img class="ks-logo" src="<?php echo base_url('assets/admin/img/logo.png'); ?> " height="60">
														<span class="ks-status-paid"><span class="ks-icon la la-check"></span>
															<?php if($order->RC_OrderStatus == 0) { ?>
															   Is In Cart
															<?php }  else if($order->RC_OrderStatus == 1) { ?>
															   Booked
															<?php }  else if($order->RC_OrderStatus == 2) { ?>
															   Accepted
															<?php }  else if($order->RC_OrderStatus == 3) { ?>
															   Processing
															<?php }  else if($order->RC_OrderStatus == 4) { ?>
															   Delivered
															<?php }  else if($order->RC_OrderStatus == 5) { ?>
															   Cancelled
															<?php }  else if($order->RC_OrderStatus == 6) { ?>
															   Assigned
															<?php }?>	
														
														</span>
													</div>
													<div class="ks-body">
														<div class="ks-column">
															<h5>Billing</h5>
															<span><?= $order->RC_CustomerName?></span>
															<span><?= $customer->C_Email; ?></span>
															<span><?= $AddressArray[0]; ?></span>
														</div>
														<div class="ks-column">
															<h5>Product</h5>
															<span></span>
														</div>
													</div>
													<div class="ks-footer">
														<div class="ks-column">
															<h5>Address</h5>
															<span><?= $AddressArray[1]; ?></span>
															<span><?= $AddressArray[2]; ?></span>
															<!-- <span><?= $AddressArray[3]; ?></span>
															<span><?= $AddressArray[4]; ?></span> -->
														</div>
														<div class="ks-column">
															<h5>Payment Type</h5>
															<span><?= $order->PO_ProviderName ?></span>
														</div>
														<div class="ks-column">
															<h5>Restaurant</h5>
															<?php if(!empty($order->RC_AssignedTo)) {
															?>
															<span><?= $vendor->V_FirstName." ".$vendor->V_LastName; ?></span>
															<span><?= $vendor->V_Email; ?></span>
															<span><?= $vendor->V_CountryCode.$vendor->V_Mobile; ?></span>
															<span><?= $vendor->V_Address; ?></span>
															<span><?= $vendor->V_Location; ?></span>
															
															<?php 
															} else {
															?>
															<span>Not Assigned Yet</span>
															
															<?php 
															}
															?>
															
														</div>
													</div>
												</div>
												<div class="ks-details">
													<h4>Order <?= getOrderName('1',$order->RCartID);?></h4>
					
													<table class="ks-table">
														<thead>
														<tr>
															<th>Item</th>
															<th class="ks-quantity">Quantity</th>
															<th>Price</th>
															<th width="100" class="ks-unit-price">Unit Price</th>
															<th width="100" class="ks-subtotal">Subtotal</th>
														</tr>
														</thead>
														<tbody>
														
														<?php 
																$ItemsCount 	= "0";
																$ItemsData		= array();
																$DetailIDsJson 			= $order->RC_DetailID;
																$DetailIDsArray			= json_decode($DetailIDsJson);														
																
																$Index 	= 1;
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
																					array_push($ItemsData,array(
																									'Index'=>(string)$Index,
																									'Title'=>'<img class="ks-logo foodType" src="'.base_url('assets/admin/img/'.$F_Type.'.png').'">'.$FoodData->F_Title,
																									'Quantity'=>$ExistingCartDetailData->RD_Quantity,
																									'Description'=>getStringValue($FoodData->F_Description),
																									'Currency'=>getStringValue("Rs. "),
																									'Price'=>getStringValue($FoodData->F_Price),
			
																									)
																								);
																					
																				}	
																				$Index++;
																		}
																	$ItemsCount = (string)count($ItemsData);
																	
																}
																

																
														?>
														<?php 
														if(!empty($ItemsData))
															{
																foreach($ItemsData as $ItemData)
																	{
																	
														?>
																		<tr>
																			<td><?= $ItemData['Title']; ?></td>
																			<td class="ks-quantity"><?= $ItemData['Quantity']; ?></td>
																			<td><?= $ItemData['Currency'].( $ItemData['Quantity'] * $ItemData['Price']); ?></td>
																			<td class="ks-unit-price"><?= $ItemData['Currency'].$ItemData['Price']; ?></td>
																			<td class="ks-subtotal"><?= $ItemData['Currency'].( $ItemData['Quantity'] * $ItemData['Price']); ?></td>
																		</tr>
														<?php
																	}
															}
														
														?>
														</tbody>
													</table>
					
													<div class="ks-total">
														<div class="ks-shipping-method">
															<span class="ks-header"></span>
															<span class="ks-text"></span>
														</div>
														<div class="ks-tracking-number">
															<span class="ks-header"></span>
															<span class="ks-text"></span>
														</div>
														<table>
															<tr>
																<td>Subtotal</td>
																<td><?= number_format($order->RC_ItemTotal,'2'); ?></td>
															</tr>
															<tr>
																<td>Delivery Charge</td>
																<td><?= number_format($order->RC_DeliveryCharge,'2'); ?></td>
															</tr>
															<tr>
																<td>Service Charge</td>
																<td><?= number_format($order->RC_ServiceCharge,'2'); ?></td>
															</tr>
															
															<tr>
																<td class="ks-text-info">Total</td>
																<td class="ks-text-info"><?= number_format($order->RC_Total,'2'); ?></td>
															</tr>
														</table>
													</div>
												</div>
				                       		</div>
                                            
                                        </div>
                                    </div>
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