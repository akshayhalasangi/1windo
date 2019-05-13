<?= init_head();?>
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Countries</h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Location'); ?>">Countries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Location/States'); ?>">States</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Location/Cities'); ?>">Cities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= admin_url('Location/Area'); ?>">Area</a>
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
                                            <span class="ks-text">Area List</span>
                                            <a href="<?= admin_url('Location/areas/0'); ?>" class="btn btn-outline-primary ks-light"><i class="fa fa-plus"></i> New Area</a>
                                        </h5>

                                        <?php if(!empty($country)) : ?>

                                            <div class="card-block ks-datatable">
                                                <table id="ks-sales-datatable" class="table table-bordered"  style="width:100%" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Sr no</th>
                                                        <th>Area Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $key = 0; foreach($country as $i => $module) : ?>
                                                        <tr>
                                                            <td>
                                                                <?= $key+1; ?>
                                                            </td>
                                                            <td>
                                                                <?= $module['name']; ?>
                                                            </td>
                                                            <td class="ks-controls">
                                                                <div class="dropdown">
                                                                    <a class="btn btn-link" id="dropdownMenu<?= $module['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <span class="la la-ellipsis-h"></span>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $module['id']; ?>">
                                                                        <a class="dropdown-item" href="<?= admin_url('Location/areas/'.$module['id']); ?>"><?= _l('txt_edit' ); ?></a>
                                                                        <a class="dropdown-item tbl-delete sweet-5" href="javascript:;"  data-act="Country" data-id="<?= $module['id']; ?>"><?= _l('txt_delete' ); ?></a>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $key++; endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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