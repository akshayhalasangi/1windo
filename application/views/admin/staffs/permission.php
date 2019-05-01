<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_staffs')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Staffs'); ?>"><?= _l('txt_listing'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Staffs/Staff'); ?>"><?= _l('txt_add_staff'); ?></a>
                        </li>                         
                    </ul>
                </div>
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 ks-panels-column-section">
                                <?php include_once(APPPATH . 'views/admin/includes/alerts.php');   ?>                                           
                                <div class="card panel ks-information ks-lightc ks-permission">
                                    <h5 class="card-header">
                                        <span class="ks-text"><?= _l('txt_permissions')?></span>
                                        <!-- <a href="javascript:;" class="btn btn-outline-primary ks-light">New Product</a> -->
                                    </h5>
                                    
                                   <form action="<?= admin_url('AddPermission');?>" id="permission-form" onsubmit="return false;" method="POST">
                                    <div class="form-group">
                                        <label for="select2-id-label-single">      
                                                                                                   
                                            <select id="select2-id-label-single"  name="Staff" id="Staff" class="form-control ks-select">
                                                 
                                                    <option value=""><?= _l('select_staff');?></option>
                                                    <?php  
                                                    if(!empty($staffsList)) { 
                                                        foreach($staffsList as $staff) {
                                                            $StaffID = $this->uri->segment(3);
                                                            
                                                            $select = '';
                                                            if($StaffID == $staff['Staff_ID']){
                                                                $select = 'selected';
                                                            } 
                                                                                                                        
                                                    ?>
                                                    <option value="<?php echo $staff['Staff_ID']; ?>" <?= $select; ?>><?= FullName($staff['S_FirstName'],$staff['S_LastName']); ?></option>
                                                    <?php }  }?>                                                 
                                            </select>
                                        </label>
                                        <span class="help-block form-error" id="select_validate"></span>
                                    </div> 
                                    <?php if(!empty($modulesList)) : ?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_module'); ?></th>                                                     
                                                    <th><?= _l('txt_has_permission'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php $key = 0; foreach($modulesList as $i => $module) : ?>
                                                <tr>
                                                    <td>
                                                        <?= $module[0]; ?>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <?php $checked = '';
                                                        if(!empty($permissionList)){

                                                            if($permissionList[$key]['P_HasPermission'] == 1){
                                                                $checked = 'checked';
                                                            }
                                                        } ?>
                                                        <input id="P_HasPermission<?= $i; ?>" type="checkbox" value="<?= $module[0]; ?>" <?= $checked; ?> name="P_HasPermission[]" class="custom-control-input">
                                                        <label class="custom-control-label" for="P_HasPermission<?= $i; ?>"></label>
                                                        </div>
                                                    </td>                                                    
                                                </tr>
                                                <?php $key++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>                                      
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary model-popup" id="permission-btn"><?= _l('btn_submit'); ?></button>
                                        <button type="reset" class="btn btn-outline-primary ks-light"><?= _l('btn_reset'); ?></button>
                                    </div>
                                </form>
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
