<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_restaurants')?></h3>
                <!-- <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Restaurants') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button> -->
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
                                        <span class="ks-text"><?= _l('txt_restaurants')?></span>
                                        <a href="<?= admin_url('Restaurants/Restaurant'); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> <?= _l('txt_add_restaurant'); ?></a>
                                    </h5>
                                    <?php if(!empty($restaurantsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('txt_restaurant'); ?></th>
                                                    <th><?= _l('txt_status_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($restaurantsList as $key => $restaurant) : ?>
                                                <tr id="tr-<?= $restaurant['RestaurantID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $restaurant['R_Name'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                    <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($restaurant['R_Status'] == 1) { ?>
                                                            <input type="checkbox" id="Switch<?= $restaurant['RestaurantID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Restaurant" data-id="<?= $restaurant['RestaurantID']; ?>">
                                                        <?php }  else if($restaurant['R_Status'] == 2) { ?>
                                                            <input type="checkbox" id="Switch<?= $restaurant['RestaurantID']; ?>" data-status="1" value="1" class="tbl-status" name="Val_Status" checked data-type="Restaurant" data-id="<?= $restaurant['RestaurantID']; ?>">       
                                                        <?php }?>
                                                            <span class="ks-indicator"></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $restaurant['RestaurantID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $restaurant['RestaurantID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Restaurants/Restaurant/'.$restaurant['RestaurantID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Restaurant" data-id="<?= $restaurant['RestaurantID']; ?>"><?= _l('txt_delete' ); ?></a>
																<a class="dropdown-item" href="<?= admin_url('Restaurants/Foods/'.$restaurant['RestaurantID']); ?>"><?= _l('txt_view_foods' ); ?></a>
																<a class="dropdown-item" href="<?= admin_url('Restaurants/Reviews/'.$restaurant['RestaurantID']); ?>"><?= _l('txt_view_reviews' ); ?></a>
																
																<!--a class="dropdown-item" href="<?= 'Restaurants/Profile/'.$restaurant['RestaurantID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_restaurants_not_found')?></h5></div>
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