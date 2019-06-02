<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= $categoryName." "._l('txt_services')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Services') ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
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
                                        <span class="ks-text"><?= _l('txt_services')?></span>
                                        <?php
                                        $CI =& get_instance();
                                        $role = $CI->session->userdata('role');
                                        if($role !== 'vendor') {
                                            ?>
                                            <a href="<?= admin_url('Services/Service/0/' . $serviceID); ?>" class="btn btn-outline-primary ks-light"><i
                                                        class="fa fa-plus"></i> New Service</a>
                                            <?php
                                        }
                                            ?>
                                            </h5>
                                                                            <?php if(!empty($servicesList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('txt_service'); ?></th>
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($servicesList as $key => $service) : ?>
                                                <tr id="tr-<?= $service['ServiceID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $service['S_Name'];?></span>
                                                    </td>
                                                    <td>
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($service['S_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $service['ServiceID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Service" data-id="<?= $service['ServiceID']; ?>">
                                                        <?php }  else if($service['S_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $service['ServiceID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Service" data-id="<?= $service['ServiceID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>

                                                    </td>
        <?php
        $CI =& get_instance();
        $role = $CI->session->userdata('role');
        if($role !== 'vendor') {
            ?>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $service['ServiceID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $service['ServiceID']; ?>">                                                                
                                                                <a class="dropdown-item" href="<?= admin_url('Services/Service/'.$service['ServiceID'].'/'.$serviceID) ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item" href="<?= admin_url('Services/Features/'.$service['ServiceID']) ?>"><?= _l('txt_view_edit_details' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Service" data-id="<?= $service['ServiceID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                            </div>
                                                        </div>
                                                    </td>
            <?php
        }
        ?>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_services_not_found')?></h5></div>
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