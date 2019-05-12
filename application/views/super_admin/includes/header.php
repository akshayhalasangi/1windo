 <!DOCTYPE html>
<html lang="en">

<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/fonts/line-awesome/css/line-awesome.min.css'); ?>">
  
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin//fonts/montserrat/styles.css'); ?>">

    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/tether/css/tether.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/jscrollpane/jquery.jscrollpane.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/flag-icon-css/css/flag-icon.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/common.min.css'); ?>"> 
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/custom-style.css'); ?>"> 
    
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <?php /* ?><link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/themes/primary.min.css'); ?>"><?php */ ?>
    <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/themes/mypurple.css'); ?>">
    
    <link class="ks-sidebar-dark-style" rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/themes/sidebar-black.min.css'); ?>">
    <!-- END THEME STYLES -->

<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/fonts/kosmo/styles.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/fonts/weather/css/weather-icons.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/c3js/c3.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/noty/noty.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/widgets/payment.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/widgets/panels.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/dashboard/tabbed-sidebar.min.css'); ?>">


<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/datatables-net/media/css/dataTables.bootstrap4.min.css'); ?>"> <!-- original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/libs/datatables-net/datatables.min.css'); ?>"> <!-- customization -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/swiper/css/swiper.min.css'); ?>"> <!-- original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/widgets/tables.min.css'); ?>">


<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/select2/css/select2.min.css'); ?>"> <!-- Original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/libs/select2/select2.min.css'); ?>"> <!-- Customization -->

<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/plyr/plyr.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/widgets/panels.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/profile/social.min.css'); ?>">
<link rel="stylesheet" href="<?= assets_url('admin/plugins/image-viwer/css/viewer.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/izi-modal/css/iziModal.min.css'); ?>"> <!-- Original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/libs/izi-modal/izi-modal.min.css'); ?>"> <!-- Original -->
<?php if(isset($viewAssets)){ ?>
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/css/lighbox.css'); ?>">
<?php } else if(isset($orderAssets)){  ?>
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/payment/order.min.css'); ?>">

<?php } ?>

<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/apps/messenger.min.css'); ?>"> <!-- Customization -->

<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/sweetalert/sweetalert.css'); ?>"> <!-- original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/libs/sweetalert/sweetalert.min.css'); ?>"> <!-- customization -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/libs/prism/prism.css'); ?>"> <!-- original -->
<link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/libs/bootstrap-notify/bootstrap-notify.min.css'); ?>"> <!-- customization -->
<link href="<?= assets_url('admin/plugins/toastr/toastr.min.css'); ?>" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" href="<?= assets_url('admin/styles/profile/settings.min.css'); ?>">
<?= js_variables(); ?>
</head>
<!-- END HEAD -->


<!-- <body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-primary ks-page-loading"> --> <!-- remove ks-page-header-fixed to unfix header -->
    <body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-pink ks-page-loading">
    