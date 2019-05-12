<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_products_reviews')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Products/ProductList/'.$categoryId) ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
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
                                        <span class="ks-text"><?= _l('txt_products_reviews')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($reviewsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_product'); ?></th>                                                     
                                                    <th><?= _l('txt_username'); ?></th>                                                     
                                                    <th><?= _l('txt_comment'); ?></th>                                                     
                                                    <th><?= _l('txt_location'); ?></th>                                                     
                                                    <th><?= _l('txt_rating'); ?></th>                                                     
                                                    <th><?= _l('txt_date'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    
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
															$ProductData 	= $this->Products_model->get( $review['R_RelationID'] );
															$Product 		= (!empty($ProductData) ? $ProductData->P_Name : '');
														?>
														<?= $Product;?></span>
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
                                                        <input type="checkbox" id="Switch<?= $review['ReviewID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="PReview" data-id="<?= $review['ReviewID']; ?>">
                                                            <?php }  else if($review['R_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $review['ReviewID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="PReview" data-id="<?= $review['ReviewID']; ?>">
                                                            <?php }?>
                                                          <span class="ks-indicator"></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <?php /*?><td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $review['ReviewID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $review['ReviewID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Products/review/'.$ProductID.'/'.$review['ReviewID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Review" data-id="<?= $review['ReviewID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Products/Profile/'.$review['ReviewID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td><?php */?>
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