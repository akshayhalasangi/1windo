<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>
                    <?php
                        if(!empty($foodsList)){
                            echo ("Edit: ");
                        }
                        echo $restaurantName." - "._l('txt_food_list');
                    ?>                
                </h3>
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
                                        <span class="ks-text"><?= _l('txt_foods')?></span>
                                        <a href="<?= admin_url('Restaurants/Food/'.$RestaurantID); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> <?= _l('txt_add_food'); ?></a>
                                    </h5>
                                    <?php if(!empty($foodsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_food'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($foodsList as $key => $food) : ?>
                                                <tr id="tr-<?= $food['RFoodID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $food['F_Title'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($food['F_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $food['RFoodID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Food" data-id="<?= $food['RFoodID']; ?>">
                                                        <?php }  else if($food['F_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $food['RFoodID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Food" data-id="<?= $food['RFoodID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $food['RFoodID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $food['RFoodID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Restaurants/Food/'.$RestaurantID.'/'.$food['RFoodID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Food" data-id="<?= $food['RFoodID']; ?>"><?= _l('txt_delete' ); ?></a>
																
                                                                <!--a class="dropdown-item" href="<?= 'Services/Profile/'.$food['RFoodID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_foods_not_found')?></h5></div>
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