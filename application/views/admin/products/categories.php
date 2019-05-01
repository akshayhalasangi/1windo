<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_products_categories')?></h3>
                <button class="btn btn-dark mt-2 x2 btn-highlight" onclick="window.location.href='<?= admin_url('Products/Attributes') ?>'"><i class="fa fa-code-fork fa-2x"></i> <h5><?= _l('txt_products_attibutes')." (Add / Modify)"; ?></h5></button>
            </section>
        </div>
        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row serviceCategories">

                            <?php                             
                                $productGroups = $productCategoryList;

                                if(count($productGroups) > 0 && $productGroups != null)
                                {
                                    $subCatFlag = false;
                                    foreach($productGroups['Categories'] as $key => $categories) {

                                        if($key == "MainCategory"){
                                            echo "<h3> Main Product Categories </h3><div class='col-md-12 divider'><div class='row'>";
                                            foreach($categories as $catName => $catDetails){
                                                displayCategories($catDetails, $catName, $key, 0);
                                            }
                                            echo "</div></div>";
                                        }

                                        if($key == "subCategory"){
                                        ?>
                                        <div class='col-md-6 col-lg-6'>
                                            <div class='row subLevelSlipDisplay'>
                                            <h3 style='width: 100%; margin-bottom: 20px;'> Sub Categories </h3>
                                            <?php
                                                $index = 0;
                                                foreach($categories as $catName => $subCatDetails){
                                                    $catName = str_replace(' ', '', $catName);
                                                    ?>
                                                    <div class='accordion' id='<?= $catName ?>'>
                                                        <div class='card'>
                                                            <div class="card-header" id="headingOne">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?= $catName.$index; ?>" aria-expanded="false" aria-controls="collapseOne">
                                                                    <?php if(isset($subCatDetails['hierarchy']))echo $subCatDetails['hierarchy']; ?>
                                                                    <i class="fa fa-plus text-right"></i>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="<?= $catName.$index; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#<?= $catName; ?>">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php
                                                                        foreach($subCatDetails['details'] as $catDetails){
                                                                            displayCategories($catDetails, $catName, $key, $index);
                                                                            $index++;
                                                                        }
                                                                        ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            echo "</div></div>";
                                        }

                                        if($key == "childCategory"){
                                        ?>
                                        
                                        <div class='col-md-6 col-lg-6'>
                                            <div class='row subLevelSlipDisplay'>
                                            <h3 style='width: 100%; margin-bottom: 20px;'> Child Categories </h3>
                                            <?php
                                                $index = 0;
                                                foreach($categories as $catName => $subCatDetails){
                                                    $catName = str_replace(' ', '', $catName);
                                                    // echo "<h4 class='subHeader'>".$key."</h4>";
                                                    ?>
                                                    <div class='accordion' id='<?= $catName ?>'>
                                                        <div class='card'>
                                                            <div class="card-header" id="headingOne">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?= $catName.$index; ?>" aria-expanded="false" aria-controls="collapseOne">
                                                                        <?php if(isset($subCatDetails['hierarchy']))echo $subCatDetails['hierarchy']; ?>
                                                                        <i class="fa fa-plus text-right"></i>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="<?= $catName.$index; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#<?= $catName; ?>">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php
                                                                            foreach($subCatDetails['details'] as $catDetails){
                                                                                displayCategories($catDetails, $catName, $key, $index);
                                                                                $index++;
                                                                            }
                                                                        ?>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            echo "</div></div>";
                                        }
                                    }
                                }else{
                                    echo "<h2>"._l('txt_no_categories_found')."</h2>";
                                }

                                function displayCategories($catDetails, $catName, $key, $index){
                                    ?>
                                        <div class="col-md-2 services">
                                            <a href="<?= admin_url('Products/ProductList/'.$catDetails['CategoryID']);?>">
                                                <?php if(strlen(trim($catDetails['C_DisplayIcon'])) <= 0){ ?>
                                                    <img src="<?= DEFAULT_IMG_URL; ?>" class="avatar" alt="Products">
                                                <?php } else { ?>
                                                    <img src="<?= UPLOAD_CATEGORIES_BASE_URL.$catDetails['CategoryID']."/".$catDetails['C_DisplayIcon']; ?>" class="avatar" alt="Products">
                                                <?php } ?>
                                                <h4><?= $catDetails['C_Name'];?></h4>
                                            </a>
                                        </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= init_tail();?>