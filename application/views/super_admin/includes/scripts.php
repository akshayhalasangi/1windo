<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= assets_url('admin/libs/jquery/jquery.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/popper/popper.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/responsejs/response.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/loading-overlay/loadingoverlay.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/tether/js/tether.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/jscrollpane/jquery.jscrollpane.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/jscrollpane/jquery.mousewheel.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/flexibility/flexibility.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/noty/noty.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/velocity/velocity.min.js'); ?>"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?= assets_url('admin/scripts/common.min.js'); ?>"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<?php if(isset($listAssets)){ ?>
<script src="<?= assets_url('admin/libs/datatables-net/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/datatables-net/media/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/swiper/js/swiper.jquery.min.js'); ?>"></script> 
<script src="<?= assets_url('admin/js/custom-datatable.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/sweetalert/sweetalert.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/bootstrap-notify/bootstrap-notify.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/prism/prism.js'); ?>"></script>
<script src="<?= assets_url('admin/js/main.js'); ?>"></script>
 <?php } ?>
 
<?php if(isset($addAssets)){ ?>
<script src="<?= assets_url('admin/libs/bootstrap-notify/bootstrap-notify.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/jquery-form-validator/jquery.form-validator.min.js'); ?>"></script>
<script src="<?= assets_url('admin/js/custom.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/select2/js/select2.min.js'); ?>"></script>
<!--<script src="<?= assets_url('admin/js/custom-validation.js'); ?>"></script>-->
<script type="text/javascript">$('select.ks-select').select2();</script> 

<?php } ?>
<script src="<?= assets_url('admin/js/custom-validation.js'); ?>"></script>

<?php if(isset($viewAssets)){ ?>
 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://fengyuanchen.github.io/js/common.js"></script>
<script src="<?= assets_url('admin/plugins/image-viwer/js/viewer.js'); ?>"></script>
<script src="<?= assets_url('admin/plugins/image-viwer/js/main.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/izi-modal/js/iziModal.min.js'); ?>"></script>    
<script src="<?= assets_url('admin/js/custom-main.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/plyr/plyr.js'); ?>"></script>
<script src="<?= assets_url('admin/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<?= assets_url('admin/plugins/toast/tosty.js'); ?>"></script>
<script src="<?= assets_url('admin/js/main.js'); ?>"></script>
<script src="<?= assets_url('admin/js/custom-tooltip.js'); ?>"></script>
<script src="<?= assets_url('admin/js/custom-image-viwer.js'); ?>"></script>
<?php } ?>
<script src="<?= assets_url('admin/libs/d3/d3.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/c3js/c3.min.js'); ?>"></script>
<script src="<?= assets_url('admin/libs/maplace/maplace.min.js'); ?>"></script>