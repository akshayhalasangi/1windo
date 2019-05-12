<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_users')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Users'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Users/User'); ?>"><?= _l('txt_add_user'); ?></a>
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
                                        <span class="ks-text"><?= _l('txt_users')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    <?php if(!empty($usersList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>                                                     
                                                    <th><?= _l('txt_user'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($usersList as $key => $user) : ?>
                                                <tr id="tr-<?= $user['User_ID']; ?>">
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    
                                                    <td><?php 
                                                        $ProfileImage = UPLOAD_NO_IMAGE;
                                                        if(!empty($user['U_ProfileImage'])){
                                                          $ProfileImage = UPLOAD_USER_BASE_URL.$user['User_ID'].'/'.$user['U_ProfileImage'];
                                                        } ?>
                                                        <div class="ks-user">
                                                            <img class="ks-avatar" src="<?= $ProfileImage; ?>" width="24" height="24">
                                                            <?php if($user['U_Status'] != 4) { ?>
                                                            <span class="ks-name"><?= $user['U_FullName'];?></span>
                                                            <?php } else { ?>
                                                            <span class="ks-name ks-color-danger"><?= $user['U_FullName'];?></span>    
                                                            <span class="badge ks-circle badge-danger"></span>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                     
                                                    <td>
                                                         
                                                         <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($user['U_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $user['User_ID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="User" data-id="<?= $user['User_ID']; ?>">
                                                        <?php }  else if($user['U_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $user['User_ID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="User" data-id="<?= $user['User_ID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $user['User_ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $user['User_ID']; ?>">
                                                                <a class="dropdown-item" href="<?= 'Users/User/'.$user['User_ID']; ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="User" data-id="<?= $user['User_ID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <a class="dropdown-item" href="<?= 'Users/Profile/'.$user['User_ID']; ?>"><?= _l('txt_view' ); ?></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_users_not_found')?></h5></div>
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