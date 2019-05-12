<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= $serviceName." - "._l('txt_packages')?></h3>
                <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= admin_url('Services/serviceList/'.$ServiceID) ?>'"><i class="fa fa-chevron-left"></i> <?= _l('btn_go_back'); ?></button>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Features/').$ServiceID; ?>"><?= _l('txt_features'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Steps/').$ServiceID; ?>"><?= _l('txt_steps'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Works/').$ServiceID; ?>"><?= _l('txt_works'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Reviews/').$ServiceID; ?>"><?= _l('txt_reviews'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= admin_url('Services/Packages/').$ServiceID; ?>"><?= _l('txt_packages'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Timeslabs/').$ServiceID; ?>"><?= _l('txt_timeslabs'); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>
                                <div class="card panel ks-information ks-light">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_packages')?></span>
                                        <a href="<?= admin_url('Services/Package/').$ServiceID; ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> New Package</a>
                                    </h5>
                                    <?php if(!empty($packagesList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('txt_package'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($packagesList as $key => $package) : ?>
                                                <tr id="tr-<?= $package['SPackageID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $package['P_Title'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($package['P_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $package['SPackageID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Package" data-id="<?= $package['SPackageID']; ?>">
                                                        <?php }  else if($package['P_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $package['SPackageID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Package" data-id="<?= $package['SPackageID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $package['SPackageID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $package['SPackageID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Services/package/'.$ServiceID.'/'.$package['SPackageID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Package" data-id="<?= $package['SPackageID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Services/Profile/'.$package['SPackageID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_packages_not_found')?></h5></div>
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