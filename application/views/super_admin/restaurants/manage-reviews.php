<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= $restaurantName." - "._l('txt_restaurants_reviews')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Restaurants') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>
        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_restaurants_reviews')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Restaurant</a> -->
                                    </h5>
                                    <?php if(!empty($reviewsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_restaurant'); ?></th>                                                     
                                                    <th><?= _l('txt_username'); ?></th>                                                     
                                                    <th><?= _l('txt_comment'); ?></th>                                                     
                                                    <th><?= _l('txt_location'); ?></th>                                                     
                                                    <th><?= _l('txt_rating'); ?></th>                                                     
                                                    <th><?= _l('txt_date'); ?></th>                                                     
                                                    <th><?= _l('txt_status_action'); ?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($reviewsList as $key => $review) : ?>
                                                <tr id="tr-<?= $review['ReviewID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name">
														<?php 
															$RestaurantData 	= $this->Restaurants_model->get( $review['R_RelationID'] );
															$Restaurant 		= (!empty($RestaurantData) ? $RestaurantData->R_Name : '');
														?>
														<?= $Restaurant;?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $review['R_UserName'];?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $review['R_Comment'];?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $review['R_Location'];?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $review['R_Rating'];?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $review['R_Date']." ".$review['R_Time'];?></span>
                                                    </td>
                                                    <td>
                                                        <label class="ks-checkbox-slider ks-primary">
                                                            <?php if($review['R_Status'] == 1) { ?>
                                                            <input type="checkbox" id="Switch<?= $review['ReviewID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="RReview" data-id="<?= $review['ReviewID']; ?>">
                                                            <?php }  else if($review['R_Status'] == 2) { ?>
                                                            <input type="checkbox" id="Switch<?= $review['ReviewID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="RReview" data-id="<?= $review['ReviewID']; ?>">       
                                                            <?php }?>
                                                            <span class="ks-indicator" ></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_reviews_not_found')?></h5></div>
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