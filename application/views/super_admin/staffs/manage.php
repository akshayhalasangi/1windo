<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_users')?></h3>
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
                                        <span class="ks-text"><?= _l('txt_users')?></span>
                                        <a href="<?= admin_url('Staffs/Staff'); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i><?= " "._l("txt_new_user") ?></a>
                                    </h5>
                                    <?php if(!empty($staffsList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_member'); ?></th>
                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($staffsList as $key => $staff) : ?>
                                                <tr id="tr-<?= $staff['Staff_ID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    
                                                    <td><?php 
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($staff['S_ProfileImage'])){
                                                          $ProfileImage = UPLOAD_STAFF_BASE_URL.$staff['Staff_ID'].'/'.$staff['S_ProfileImage'];
                                                        } ?>
                                                        <div class="ks-user">
                                                            <img class="ks-avatar" src="<?= $ProfileImage; ?>" width="24" height="24">
                                                            <span class="ks-name"><?= FullName($staff['S_FirstName'],$staff['S_LastName']);?></span>
                                                        </div>
                                                    </td>
                                                     
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($staff['S_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $staff['Staff_ID']; ?>" data-status='2' value="2" class="tbl-status" name="Val_status"  data-type="Staff" data-id="<?= $staff['Staff_ID']; ?>">
                                                        <?php }  else if($staff['S_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $staff['Staff_ID']; ?>"  data-status='1' value="1" class="tbl-status" name="Val_status" checked data-type="Staff" data-id="<?= $staff['Staff_ID']; ?>">       
                                                        <?php }?>


                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $staff['Staff_ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $staff['Staff_ID']; ?>">
                                                                <a class="dropdown-item" href="<?= 'Staffs/Staff/'.$staff['Staff_ID']; ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Staff" data-id="<?= $staff['Staff_ID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                 <a class="dropdown-item"   href="<?= admin_url('Permissions/'.$staff['Staff_ID']); ?>"><?= _l('txt_permissions' ); ?></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_staff_not_found')?></h5></div>
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