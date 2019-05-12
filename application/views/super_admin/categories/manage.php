<?= init_head();?>

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3 class="float-left">
                    <?= $title ?>
                    <span class="hirerchy">

                    <?php
                        if(isset($subCategoriesName)){
                            $backLink = admin_url("Categories/Type/$type");
                            if($level == "C"){
                                echo " <i class='fa fa-angle-right'></i> <a href='".admin_url("Categories/Type/$type/$outerParentId")."'>".$subCategoriesName->C_Name."</a>";
                            }else{
                                echo " <i class='fa fa-angle-right'></i> <a href='".admin_url("Categories/Type/$type/$parentId")."'>".$subCategoriesName->C_Name."</a>";
                            }
                        }

                        if(isset($childCategoriesName)){
                            $backLink = admin_url("Categories/Type/$type/$outerParentId");
                            echo " <i class='fa fa-angle-right'></i> <a href='".admin_url("Categories/Type/$type/$outerParentId/$parentId")."'>".$childCategoriesName->C_Name."</a>";
                        }
                    ?>

                    </span>

                </h3>

            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <h6 class="categories"><i class="ks-icon la la-chevron-right"></i><?= _l('txt_category_types'); ?></h6>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link <?php if($type == 1)echo "active"?>" href="<?= admin_url('Categories/Type/1');?>"><?= _l('title_services'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($type == 2)echo "active"?>" href="<?= admin_url('Categories/Type/2');?>"><?= _l('title_products'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($type == 3)echo "active"?>" href="<?= admin_url('Categories/Type/3');?>"><?= _l('title_food'); ?></a>
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
                                        <span class="ks-text"><?= _l('txt_categories')?></span>
                                        <?php if($level == "C"){ ?>
                                            <a href="<?= admin_url('Categories/Category/'.$type.'/'.$level.'/0/'.$parentId.'/'.$outerParentId); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> New Category</a>
                                        <?php } else { ?>
                                            <a href="<?= admin_url('Categories/Category/'.$type.'/'.$level.'/0/'.$parentId); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> New Category</a>
                                        <?php } ?>
                                    </h5>
                                    <?php if(!empty($categoriesList)) :?>
                                    <div class="card-block ks-datatable">
                                        <table id="ks-sales-datatable" class="table table-bordered" style="width:100%" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?= _l('txt_sr_no'); ?></th>
                                                    <th><?= _l('txt_category'); ?></th>                                                     
                                                    <th><?= _l('txt_action'); ?></th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableRowActions">
                                                <?php foreach($categoriesList as $key => $category) : 
                                                if($level == "M"){ ?>
                                                    <tr id="tr-<?= $category['CategoryID']; ?>" <?php if($type != 3){ ?> class="hierarchy" <?php } ?> data-url="<?= admin_url('Categories/Type/'.$type.'/'.$category['CategoryID']); ?>">
                                                <?php }else if($level == "S"){ ?>
                                                    <tr id="tr-<?= $category['CategoryID']; ?>" <?php if($type != 3){ ?> class="hierarchy" <?php } ?> data-url="<?= admin_url('Categories/Type/'.$type.'/'.$category['C_Parent'].'/'.$category['CategoryID']); ?>">
                                                <?php }else if($level == "C"){ ?>
                                                    <tr id="tr-<?= $category['CategoryID']; ?>">
                                                <?php } ?>
                                                    <td>
                                                        <?= $key + 1; ?>
                                                    </td>
                                                    <td>
														<span class="ks-name"><?= $category['C_Name'];?></span>
                                                    </td>
                                                    <td>
                                                         
                                                    <label class="ks-checkbox-slider ks-primary">
                                                        <?php if($category['C_Status'] == 1) { ?>
                                                        <input type="checkbox" id="Switch<?= $category['CategoryID']; ?>" data-status = "2" value="2" class="tbl-status" name="Val_Status"  data-type="Category" data-id="<?= $category['CategoryID']; ?>">
                                                        <?php }  else if($category['C_Status'] == 2) { ?>
                                                         <input type="checkbox" id="Switch<?= $category['CategoryID']; ?>" value="1" data-status="1" class="tbl-status" name="Val_Status" checked data-type="Category" data-id="<?= $category['CategoryID']; ?>">       
                                                        <?php }?>
                                                          <span class="ks-indicator" ></span>
                                                    </label>
                                                         
                                                    </td>
                                                        
                                                    <td class="ks-controls">
                                                        <div class="dropdown">
                                                            <a class="btn btn-link" id="dropdownMenu<?= $category['CategoryID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="la la-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $category['CategoryID']; ?>">
                                                                <a class="dropdown-item" href="<?= admin_url('Categories/Category/'.$type.'/'.$level.'/'.$category['CategoryID'].'/'.$parentId).'/'.$outerParentId; ?>"><?= _l('txt_edit' ); ?></a>
                                                                <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Category" data-id="<?= $category['CategoryID']; ?>"><?= _l('txt_delete' ); ?></a>
                                                                <!--a class="dropdown-item" href="<?= 'Categories/Profile/'.$category['CategoryID']; ?>"><?= _l('txt_view' ); ?></a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <?php else : ?>                                         
                                        <div class="ks-no-record"><h5 class="ks-no-record"><?= _l('txt_categories_not_found')?></h5></div>
                                    <?php endif; ?>

                                </div>

                                <?php if($level != "M"){?>
                                    <button class="btn btn-outline-primary ks-light mt-2" onclick="window.location.href='<?= $backLink ?>'"><?= _l('btn_back'); ?></button>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= init_tail();?>