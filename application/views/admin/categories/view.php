<?= init_head();?>  

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_categories')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 ks-panels-column-section">
                            <div class="ks-information ks-light">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div id="mainCategories" class="row">
                                            <div class="col-md-4 text-center">
                                                <a href="<?= admin_url('Categories/Type/1');?>">
                                                    <img src="<?= APPBASEURL.'assets/admin/img/categories/services.png'; ?>" class="ks-avatar rounded-circle" alt="Services">
                                                    <h4><?= _l('title_services');?></h4>
                                                </a>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <a href="<?= admin_url('Categories/Type/2');?>">
                                                    <img src="<?= APPBASEURL.'assets/admin/img/categories/products.png'; ?>" class="ks-avatar rounded-circle" alt="Products">
                                                    <h4><?= _l('title_products');?></h4>
                                                </a>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <a href="<?= admin_url('Categories/Type/3');?>">
                                                    <img src="<?= APPBASEURL.'assets/admin/img/categories/food.png'; ?>" class="ks-avatar rounded-circle"  alt="Food">
                                                    <h4><?= _l('title_food');?></h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
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