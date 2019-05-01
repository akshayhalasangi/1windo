<?= init_front_head();?>

 <div class="banner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="crtve">
                                <h1>CLEAN<span>.</span></h1>
                                <h1>CREATIVE<span>.</span></h1>
                                <h1>POWERFUL<span>.</span></h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cust_form">
                                <div class="wrapper">
                                    <form class="form-signin" id="enquiry-form"  method="POST" onsubmit="return false;">
                                        <h2 class="form-signin-heading">Be Our Client</h2>
                                        <p>More than 5 Years we build brands and help them succeed.</p>
                                        <p class="join_details" id="success-message"></p> 
                                        <input type="name" class="form-control" name="Name" id="Name" placeholder="Name*" required="" />
                                        <input type="email" class="form-control" name="Email" id="Email" placeholder="Email*" required="" autofocus />
                                        <input type="hidden" name="Type" value="1" />
                                        <button class="btn btn-lg btn-primary btn-block" id="enquiry-btn" type="submit">Submit Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--container End-->
            </div>
            <!--banner End-->
        </header>

        <div class="content_main">
            <?php  if(!empty($Services)) : ?> 
            <section class="services">
                <div class="container">
                    <p class="pr_header">
                        With our open minded approach and creative flair infused
                        <br>with skills and understanding of digital media.
                    </p>
                    <div class="all_services">
                        <div class="">
                            <ul class="serices_inner">
                                <?php  
                                                                              
                                    foreach($Services as $Service) :                                                 
                                        if(!empty($Service['SIcon'])){
                                            $Path = UPLOAD_SERVICES_BASE_URL.$Service['ServiceID'].'/'.$Service['SIcon'];
                                        } else {                                                    
                                            $Path = UPLOAD_NO_IMAGE;
                                        }
                                ?>
                                <li>
                                    <a href="<?= front_url('Services/'.$Service['PageID'].'/'.$Service['ServiceID'])?>" class="active">
                                        <div class="svg_img">
                                            <img src="<?= $Path; ?>">
                                        </div>
                                        <h2><?= ucfirst($Service['SName']);?></h2>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                                 
                            </ul>
                        </div>

                        <div class="clearfix"></div>
                      <a href="<?= front_url('Services/'.$Service['PageID'])?>">  <button class="btn btn-lg btn-primary cust_btn1">Find Our More...</button></a>
                    </div>
                    <!--all_services End-->
                </div>
                <!--container End-->
            </section>
            <?php endif; ?>
            <!--services End-->
            <?php  if(!empty($FeaturedProject)){ ?>
            <section class="design_manage">
                <div class="">
                    <div class="row">
                        <div class="left_bar col-sm-12 col-xs-12">
                            <p class="design_heading">
                                Design-driven project management
                            </p>
                            <p class="design_content">
                                Manage your project screens and statuses from one single location, quickly see unread comments, preview screens, and notify team members when changes to screen status are made.
                            </p>
                           <a href="<?= front_url('Portfolio/'.$FeaturedProject->PageID);?>"> <button class="btn btn-lg btn-primary cust_btn1">Find Out More...</button></a>
                        </div>
                        <div class="right_bar">
                            <?php 
                            if(!empty($FeaturedProject->PFeaturedImage)){
                                $Image = $FeaturedProject->PFeaturedImage;
                                $Path = UPLOAD_PROJECTS_BASE_URL.$FeaturedProject->ProjectID.'/'.$Image;
                            } else {                                                    
                                $Path = UPLOAD_NO_IMAGE;
                            }
                            ?>
                            <img src="<?= $Path ;?>">
                        </div>
                    </div>
                </div>
            </section>
            <?php } ?>
            <!--design_manage End-->
            <?php if(!empty($Visions)) : ?>
            <section class="vision">
                <div class="container">
                    <p class="sctn_hdng">
                        Vision & Mission
                    </p>
                    <div class="vision_inner text-left">
                        <div class="row">
                            <div id="owl_about_main_slider4" class="owl-carousel">
                            <?php $i = 1; foreach($Visions as $Vision) :?>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <p class="nmbrs"><?php if($i < 10 ) { echo '0'.$i; } else { echo $i;} ?>.</p>
                                <h4 class="title"><?= $Vision['VTitle']; ?></h4>
                                <p class="disc"><?= $Vision['VDescription']; ?></p>
                                <span></span>
                            </div>
                            <?php $i++; endforeach; ?> 
                        </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
            <!--Vision End-->

            <section class="testi">
                <p class="sctn_hdng">
                    Testimonials
                </p>
                <div id="testimonial4" class="carousel slide testimonial4_indicators testimonial4_control_button thumb_scroll_x swipe_x" data-ride="carousel">
                    <!-- <div class="testimonial4_header">
				<h4>what our clients are saying</h4>
			</div> -->
                    <ol class="carousel-indicators">
                        <?php foreach($Testimonials as $Key => $Testimonial) : ?>
                            <?php if($Key==0){
                                $class="active";
                            } else {  
                                $class="";
                            } ?>
                        <li data-target="#testimonial4" data-slide-to="<?= $Key; ?>" class="<?= $class; ?>"></li>
                        <?php endforeach;?>
                        
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <?php foreach($Testimonials as $Key => $Testimonial) :
                            if(!empty($Testimonial['TClientLogo'])){
                                $Path = UPLOAD_TESTIMONIALS_BASE_URL.$Testimonial['TestimonialID'].'/'.$Testimonial['TClientLogo'];
                            } else {                                                    
                                $Path = UPLOAD_NO_IMAGE;
                            }

                            if(!empty($Testimonial['TClientProfileImage'])){
                                $Profile = UPLOAD_TESTIMONIALS_BASE_URL.$Testimonial['TestimonialID'].'/'.$Testimonial['TClientProfileImage'];
                            } else {                                                    
                                $Profile = UPLOAD_NO_IMAGE;
                            }
                             ?>
                             <?php if($Key==0){?>
                                <div class="item active">
                                <?php } else { ?>
                                <div class="item ">
                                <?php } ?>
                         
                            <div class="testimonial4_slide">
                                <img src="<?=  $Path; ?>" class="img-responsive" />
                                <p><?=  $Testimonial['TClientDescription']; ?>
                                </p>
                                <div class="img_slider"><img src="<?= $Profile; ?>" height="70px" width="70px" class="img-responsive img-circle">
                                    <h4><?=  $Testimonial['TClientName']; ?>, <?=  $Testimonial['TClientDesignation']; ?></h4></div>
                            </div>
                        </div>
                    <?php endforeach;?>
                        
                    </div>
                    <!--corousel innerEnd-->
                    <a class="left carousel-control" href="#testimonial4" role="button" data-slide="prev">
                        <span class="fa fa-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#testimonial4" role="button" data-slide="next">
                        <span class="fa fa-chevron-right"></span>
                    </a>
                </div>
                <!--Testimonial-->
            </section>
            <!--Testi End-->
            <?php if(!empty($Blogs)) :  ?>
            <section class="blog">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 blog_left">
                            <p class="design_heading">
                                Latest news from our blog
                            </p>
                            <p class="design_content">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer.
                            </p>

                            <a href="<?= front_url('Blogs/'.$PageID);?>"><button class="btn btn-lg btn-primary cust_btn1">Find Out More...</button></a>
                        </div>
                        <div class="col-md-8 blog_right">
                            <div class="row">
                                <div id="owl_about_main_slider" class="owl-carousel">
                                <?php foreach($Blogs as $Blog) :
                                $Images = explode(',',$Blog['BImage']);
                                if(!empty($Blog['BImage'])){
                                    $Path = UPLOAD_BLOGS_BASE_URL.$Blog['BlogID'].'/'.$Images[0];
                                } else {                                                    
                                    $Path = UPLOAD_NO_IMAGE;
                                }

                                /* Platform */
                                 $Platform = '';

                                if($Blog['ServiceID'] == 0){

                                    $PlatformId = $Blog['TechnologyID'];
                                    $technology = $this->service_technologies_model->getTechnology($PlatformId);     
                                    if(!empty($technology)){
                                        $Platform = $technology->TName;     
                                    }                           
                                } else if($Blog['TechnologyID'] == 0){            
                                    $PlatformId = $Blog['ServiceID'];
                                    $service = $this->services_model->getService($PlatformId);     
                                    if(!empty($service)){
                                        $Platform = $service->SName;
                                    }
                                } else {
                                    $PlatformId = $Blog['ServiceID'];
                                    $service = $this->services_model->getService($PlatformId);     
                                    if(!empty($service)){
                                        $Platform = $service->SName;
                                    }
                                }
                                
                                 ?>
                                <div class="col-md-6 col-sm-6">
                                    <div class="blog_box">
                                        <div class="blog_content_img">
                                            <img src="<?= $Path; ?>" class="img-responsive">
                                        </div>
                                        <p class="blog_content-heading"><?= date("n M Y", strtotime($Blog['BDate'])); ?> <span class="title_right"><?= $Platform; ?></span></p>
                                        <p class="blog_content_pr"><?= $Blog['BTitle']; ?></p>
                                        <a href="<?= front_url('Blogs/'.$PageID.'/'.$Blog['BlogID']);?>">Read More &#8594;</a>
                                    </div>
                                </div>
                            <?php endforeach;?>

                                
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif;?>
            <!--blog End-->
 
            <?php if(!empty($Projects)) : ?>
            <section class="latest_pro">
                <div class="container">
                    <p class="sctn_hdng">
                        Our Latest Project
                    </p>
                    <div class="latest_inner">
                        <div class="row">
                            <div id="owl_about_main_slider2" class="owl-carousel">
                            <?php foreach($Projects as $Project) :
                            if(!empty($Project['PBannerImage'])){
                                $Path = UPLOAD_PROJECTS_BASE_URL.$Project['ProjectID'].'/'.$Project['PBannerImage'];
                            } else {                                                    
                                $Path = UPLOAD_NO_IMAGE;
                            } ?>
                            <div class="col-md-4 col-sm-4 box_pro">
                                <a href="<?= front_url('Portfolio/'.$Project['PageID'].'/'.$Project['ProjectID']);?>"><div class="box_img">
                                    <img src="<?= $Path?>" class="img-responsive">
                                </div></a>
                                <div class="box_content">
                                    <p class="blog_content-heading"><?= $Project['PName']?></p>
                                     
                                    <a href="javascript:;"><?php 
                                    $Technologies = json_decode($Project['PSkills']);
                                    
                                    $name = "";
                                    for($i = 0; $i < count($Technologies); $i++){
                                        $Skills = TechnologiesByID($Technologies[$i]);
                                        $name .= $Skills->TName . ' & ';
                                        $nameList  = rtrim($name,' & ');
                                    } ?>                                             
                                    <?= $nameList; ?>
                                                          
                                    </a> 
                                </div>
                            </div>
                             <?php endforeach;?> 
                             
                        </div>
                        </div>
                        <a href="<?= front_url('Portfolio/'.$PageID);?>"><button class="btn btn-lg btn-primary cust_btn1">Find Our More...</button></a> 
                    </div>
                </div>
            </section>
            <?php endif;?>
            <!--latest_pro End-->

           
            <?php if(!empty($Plans)) : ?>
            <section class="pricing">
                <div class="container">
                    <p class="sctn_hdng">
                        Plans & Pricing
                    </p>
                    <div class="pricing_inner">
                        <div id="owl_about_main_slider3" class="owl-carousel">
                        <?php foreach($Plans as $Plan) : ?>
                        <div class="col-md-4 col-sm-4">
                            <div class="price">
                                <p class="header"><?= $Plan['PTitle']; ?></p>
                                <p class="plan">$<?= $Plan['PPrice']; ?><span class="grey">/hr</span> </p>

                                <ul>
                                    <?php $Description = explode('<li>',$Plan['PDescription']);
                                    for($i = 1; $i <= 3; $i++){?>                                                     
                                        <li><span class="glyphicon glyphicon-ok"></span><?= $Description[$i];?></li>
                                    <?php continue;}  ?>
                                </ul>
                                <div class="btm_btn"> <a href="javascript:;" class="button pricing-plan-detail" data-id="<?= $Plan['PlanID']; ?>">Read More</a></div>
                            </div>
                        </div>
                    <?php endforeach;?>                       
                    </div>                    
                    </div>                    
                </div>
                <a href="<?= front_url('Packages/'.$PlanPageID)?>"><button class="btn btn-lg btn-primary cust_btn1">Find Our More...</button></a>
            </section>
            <?php endif;?>
            <!--pricing End-->

        <?php if(!empty(get_option('testimonial_images'))) :?>
           <div class="clint_slider">
                <div class="container">
                    <section class="our_client">
                        <div class="clints_img">
                            <?php $Image = (!empty(get_option('testimonial_images')) ? UPLOAD_TESTIMONIALS_BASE_URL.get_option('testimonial_images') : '');?>
                            <img src="<?= base_url('assets/front/images/All_Logo.svg');?>" class="img-responsive">
                        </div>
                    </section>
                </div>
            </div>
        <?php endif; ?>
            
            <?= init_front_newsletter();?>
        </div>
        <!--content_main End-->
<div class="modal fade in" id="modal-pricing-plan" tabindex="-1" role="dialog" ></div>
<?= init_front_tail();?>
