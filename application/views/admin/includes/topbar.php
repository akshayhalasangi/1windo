 <!-- BEGIN HEADER -->
<nav class="navbar ks-navbar">
    <!-- BEGIN HEADER INNER -->
    <!-- BEGIN LOGO -->
    <div href="index.html" class="navbar-brand">
        <!-- BEGIN RESPONSIVE SIDEBAR TOGGLER -->
        <a href="javascript:;" class="ks-sidebar-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <a href="javascript:;" class="ks-sidebar-mobile-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <!-- END RESPONSIVE SIDEBAR TOGGLER -->

        <div class="ks-navbar-logo">
            <a href="<?= admin_url(); ?>" class="ks-logo"><?= _l('title_1windo'); ?></a>
 
        </div>
    </div>
    <!-- END LOGO -->

    <!-- BEGIN MENUS -->
    <div class="ks-wrapper">
        <nav class="nav navbar-nav">
            <!-- BEGIN NAVBAR MENU -->
            <div class="ks-navbar-menu"> </div>
            <!-- END NAVBAR MENU -->
            <?php $unreadNotifications = getTopListNotifications(get_staff_user_id(),'1');?>
            <!-- BEGIN NAVBAR ACTIONS -->
            <div class="ks-navbar-actions">                  
                <!-- BEGIN NAVBAR NOTIFICATIONS -->
                <div class="nav-item dropdown ks-notifications">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:;" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-bell ks-icon" aria-hidden="true">
                            <?php if(!empty($unreadNotifications)) { 
                                $count = count($unreadNotifications);
                                $path = 'javascript:;';
                            } else {
                                $count = 0;
                                $path = admin_url('Notifications');
                            }?>
                             <span class="badge badge-pill badge-info"><?= $count; ?></span> 
                        </span>
                        <span class="ks-text"><?= _l('txt_notifications')?></span>
                    </a>
                     
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <!-- <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:;" data-toggle="tab" data-target="#navbar-notifications-all" role="tab">All</a>
                            </li>                             
                        </ul> -->
                        <div class="tab-content">
                            
                            <div class="tab-pane ks-notifications-tab active" id="navbar-notifications-all" role="tabpanel">
                               <?php  if(!empty($unreadNotifications)) { ?>
                                <div class="ks-wrapper ks-scrollable">
                                    <?php foreach($unreadNotifications as $notification) { 
                                        if($notification['User_ID'] != '') {
                                          $postimage = 'javascript:;';
                                          $path = admin_url('Users/Profile/'.$notification['User_ID']);
                                            
                                        } else {
                                          $postimage = $notification['PostImage'];  
                                          $path = 'javascript:;';
                                        }

                                    ?>
                                    <a href="<?= $path; ?>" class="ks-notification">
                                        <div class="ks-avatar">
                                            <img src="<?= $notification['ProfileImage']?>" width="36" height="36">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name"><?= $notification['FullName']; ?> <span class="ks-description"><?= $notification['Subject']; ?></span></div>
                                            <div class="ks-text"> <?= $notification['Message']; ?></div>
                                            <div class="ks-datetime"><?= $notification['Date']; ?></div>
                                        </div>
                                        <?php if($notification['User_ID'] == '') { ?>
                                        <div class="">
                                            <img src="<?= $postimage; ?>" width="36" height="36">
                                        </div>
                                        <?php } ?>
                                    </a>  
                                    <?php } ?>                                  
                                </div>
                                <?php }  else { ?>
                                    <div class="ks-empty" style="padding: 140px 70px;">
                                         <?= _l('txt_notifications_not_found');?>
                                    </div>
                                <?php } ?>
                                <div class="ks-view-all">
                                    <a href="<?= admin_url('Notifications'); ?>"><?= _l('txt_show_more'); ?></a>
                                </div>
                                
                            </div>
                        
                            <div class="tab-pane ks-empty" id="navbar-notifications-comments" role="tabpanel">
                                 
                            </div>
                        </div>


                    </div>
                    
                </div>
                <!-- END NAVBAR NOTIFICATIONS -->

                <!-- BEGIN NAVBAR USER -->
                <?php $admin = getAdminData(get_staff_user_id()); 
                $ProfileImage = UPLOAD_NO_IMAGE;
                if(!empty($admin->S_ProfileImage)){
                  $ProfileImage = UPLOAD_STAFF_BASE_URL.$admin->Staff_ID.'/'.$admin->S_ProfileImage;
                }
                ?>
                <div class="nav-item dropdown ks-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:;" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-avatar">
                            <img src="<?= $ProfileImage; ?>" width="36" height="36">
                        </span>
                        <span class="ks-info">
                            <span class="ks-name"><?= FullName($admin->S_FirstName,$admin->S_LastName); ?></span>                             
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <a class="dropdown-item" href="<?= admin_url('MyProfile');?>">
                            <span class="la la-user ks-icon"></span>
                            <span><?= _l('txt_my_profile');?></span>
                        </a>
                         
                        <a class="dropdown-item" href="<?= base_url('Authentication/Logout');?>">
                            <span class="la la-sign-out ks-icon" aria-hidden="true"></span>
                            <span><?= _l('txt_logout'); ?></span>                            
                        </a>
                    </div>
                </div>
                <!-- END NAVBAR USER -->
            </div>
            <!-- END NAVBAR ACTIONS -->
        </nav>

        <!-- BEGIN NAVBAR ACTIONS TOGGLER -->
        <nav class="nav navbar-nav ks-navbar-actions-toggle">
            <a class="nav-item nav-link" href="javascript:;">
                <span class="la la-ellipsis-h ks-icon ks-open"></span>
                <span class="la la-close ks-icon ks-close"></span>
            </a>
        </nav>
        <!-- END NAVBAR ACTIONS TOGGLER -->
    </div>
    <!-- END MENUS -->
    <!-- END HEADER INNER -->
</nav>
<!-- END HEADER -->
