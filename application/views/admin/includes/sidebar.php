<?php $admin = getAdminData(get_staff_user_id()); 
$ProfileImage = UPLOAD_NO_IMAGE;
if(!empty($admin->S_ProfileImage)){
  $ProfileImage = UPLOAD_STAFF_BASE_URL.$admin->Staff_ID.'/'.$admin->S_ProfileImage;
}
?>
<?php
$CurrentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="ks-page-container ks-dashboard-tabbed-sidebar-fixed-tabs">
<style>
a.nav-link.active.current{
	background:rgba(57,81,155,.06);
}

</style>    
    <!-- BEGIN DEFAULT SIDEBAR -->
<div class="ks-column ks-sidebar ks-info">
    <div class="ks-wrapper ks-sidebar-wrapper">
        <ul class="nav nav-pills nav-stacked">
			<?php 
                $Controller = $this->router->fetch_class();
    			$Method 	= $this->router->fetch_method();
			?>

            <!-- <li class="nav-item <?= ($Controller == 'Staffs') ? 'open' : ''; ?>">
                <a class="nav-link"  href="<?= admin_url('Staffs');?>" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ks-icon la la-male"></span>
                    <span><?= _l('txt_users'); ?></span>
                </a>
            </li> -->
 <?php
 $CI =& get_instance();
 $role = $CI->session->userdata('role');
 if($role !== 'vendor') {
     ?>
     <li class="nav-item <?= ($Controller == 'Customers') ? 'open' : ''; ?>">
         <a class="nav-link dropdown" href="<?= admin_url('Customers'); ?>" role="button" aria-haspopup="true"
            aria-expanded="false">
             <span class="ks-icon la la-users"></span>
             <span><?= _l('txt_customers'); ?></span>
         </a>
     </li>

     <li class="nav-item <?= ($Controller == 'Categories') ? 'open' : ''; ?>">
         <a class="nav-link" href="<?= admin_url('Categories'); ?>" role="button" aria-haspopup="true"
            aria-expanded="false">
             <span class="ks-icon la la-cubes"></span>
             <span><?= _l('txt_categories'); ?></span>
         </a>
     </li>

     <?php
 }
?>
			<li class="nav-item <?= ($Controller == 'Services') ? 'open' : ''; ?>">
                <a class="nav-link"  href="<?= admin_url('Services');?>" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ks-icon la la-fighter-jet"></span>
                    <span><?= _l('txt_services'); ?></span>
                </a>
            </li>        
			<li class="nav-item <?= ($Controller == 'Products') ? 'open' : ''; ?>">
                <a class="nav-link"  href="<?= admin_url('Products');?>" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ks-icon la la-dropbox"></span>
                    <span><?= _l('Products'); ?></span>
                </a>
            </li>   
			<li class="nav-item <?= ($Controller == 'Restaurants') ? 'open' : ''; ?>">
                <a class="nav-link"  href="<?= admin_url('Restaurants');?>" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ks-icon la la-cutlery"></span>
                    <span><?= _l('Restaurants'); ?></span>
                </a>
            </li>  
			<li class="nav-item <?= ($Controller == 'Orders') ? 'open' : ''; ?>">
                <a class="nav-link"  href="<?= admin_url('Orders');?>" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="ks-icon la la-shopping-cart"></span>
                    <span><?= _l('Orders'); ?></span>
                </a>
            </li>
            <?php
            $CI =& get_instance();
            $role = $CI->session->userdata('role');
            if($role !== 'vendor') {
                ?>
                <li class="nav-item <?= ($Controller == 'Vendors') ? 'open' : ''; ?>">
                    <a class="nav-link" href="<?= admin_url('Vendors'); ?>" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="ks-icon la la-certificate"></span>
                        <span><?= _l('Vendors'); ?></span>
                    </a>
                </li>
                <li class="nav-item <?= ($Controller == 'DeliveryBoys') ? 'open' : ''; ?>">
                    <a class="nav-link" href="<?= admin_url('DeliveryBoys'); ?>" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="ks-icon la la-motorcycle"></span>
                        <span><?= _l('Delivery Boys'); ?></span>
                    </a>
                </li>
                <li class="nav-item <?= ($Controller == 'Location') ? 'open' : ''; ?>">
                    <a class="nav-link"  href="<?= admin_url('Location');?>" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-motorcycle"></span>
                        <span><?= _l('Locations'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>

        </ul>         
    </div>
</div>
<!-- END DEFAULT SIDEBAR -->