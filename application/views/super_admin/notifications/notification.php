<?= init_head();?>  
    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3><?= _l('txt_notifications')?></h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                    

                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 ks-panels-column-section">
                                <div class="ks-dashboard-tabbed-sidebar-widgets">      
                                  <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card ks-card-widget ks-widget-messages-inbox ks-scrollable">
                                            <h5 class="card-header">
                                                <?= _l('txt_notifications'); ?> 
                                            </h5>
                                            <div class="card-block">
                                              <?php if(!empty($notifications)) { ?>
                                                <div class="ks-widget-messages-inbox-items">
                                                  <?php foreach($notifications as $notification) { 
                                                    if($notification['ActionType'] != '3'){
                                                      $path = admin_url('Users/Profile/'.$notification['User_ID']);
                                                      $postimage = 'javascript:;';
                                                  
                                                    } else {
                                                      $path = admin_url('Users/Profile/'.$notification['User_ID']);
                                                      $postimage = $notification['PostImage'];
                                                    }

                                                    ?>
                                                    <a href="<?= $path; ?>" class="ks-widget-messages-inbox-item" id="noti-<?= $notification['Notification_ID']; ?>">
                                                        <div class="ks-widget-messages-inbox-item-avatar ks-avatar">
                                                            <img src="<?= $notification['ProfileImage']?>" width="36" height="36">
                                                        </div>
                                                        <div class="ks-widget-messages-inbox-item-body">
                                                            <div class="ks-widget-messages-inbox-item-body-name-and-time">
                                                                <span class="ks-name"><?= $notification['FullName']; ?></span>
                                                                <span class="ks-time"><?= $notification['Date']; ?></span>
                                                            </div>
                                                            <p class="ks-widget-messages-inbox-item-body-message">
                                                                <?= $notification['Message']; ?>
                                                            </p>
                                                                                                                       
                                                        </div>
                                                        <?php if($notification['ActionType'] == '3'){ ?>
                                                        <div class="ks-widget-messages-inbox-item-avatar">
                                                            <img src="<?= $postimage; ?>" width="36" height="36">
                                                        </div>

                                                      <?php } else {?>
                                                            <div class="ks-widget-messages-inbox-item-avatar">
                                                           
                                                        </div>
                                                      <?php } ?>
                                                      <div class="button-content">
                                                       <button class="btn btn-outline-primary ks-no-text tbl-delete sweet-5"  data-act="Notification"  id="notibody-<?= $notification['Notification_ID']; ?>" data-id="<?= $notification['Notification_ID']; ?>"><span class="la la-trash-o ks-icon"></span></button></div>
                                                        
                                                    </a>
                                                    
                                                    <?php } ?>
                                                </div>
                                              <?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                
                                  </div>
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