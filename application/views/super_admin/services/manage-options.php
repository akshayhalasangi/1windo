<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_services_options')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Options/').$ServiceID; ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Services/Option/').$ServiceID.'/'.$PackageID; ?>"><?= _l('txt_add_service_option'); ?></a>
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
                                        <span class="ks-text"><?= _l('txt_services_options')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($optionsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_service'); ?></th>                                                     
                                                    <th><?= _l('txt_option'); ?></th>
                                                    <th><?= _l('txt_price'); ?></th>
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php 
												 foreach($optionsList as $key => $option) : ?>
                                                <tr id="tr-<?= $option['SPOptionID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name">
														<?php 
															$ServiceData 	= $this->Services_model->get( $option['O_ServiceID'] );
															$Service 		= (!empty($ServiceData) ? $ServiceData->S_Name : '');
														?>
														<?= $Service;?></span>
                                                    </td><td>
														<span class="ks-name"><?= $option['O_Title'];?></span>
                                                    </td>
													<td>
														<span class="ks-name"><?= $option['O_Price'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($option['O_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $option['SPOptionID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Option" data-id="<?= $option['SPOptionID']; ?>">
                                                        <?php }  else if($option['O_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $option['SPOptionID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Option" data-id="<?= $option['SPOptionID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $option['SPOptionID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $option['SPOptionID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Services/Option/'.$ServiceID.'/'.$PackageID.'/'.$option['SPOptionID']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Option" data-id="<?= $option['SPOptionID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Services/Profile/'.$option['SOptionID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_options_not_found')?></h5></div>
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