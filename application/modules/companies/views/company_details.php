<!-- basic-breadcrumb start -->
<div class="breadcrumb-2-area pos-relative bg-2 bg-black-alfa-40">
    <div class="hero-caption">
        <div class="hero-text">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h1 class="text-uppercase color-VALUKA breadcrumb-2">Contact Us</h1>
                        <p class="lead color-VALUKA ">Would you like to contact Us? We will be happy to contact you. Kindly leave your details below</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- basic-breadcrumb end -->

<div class="about-us-area ptb-120">
    <div class="container">

        <div class="col-md-6 col-md-offset-1 col-sm-7 col-xs-12">

            <div class="widget">
                <?php //echo '<pre>';print_r($address);echo '</pre>';//exit;
                ?>
                <h3 class="widget-title style5">Our Details</h3>
                <ul class="clearfix">
                    <li>
                        <p><span class="fa fa-envelope-o"></span>Email: <a href="mailto:pbp8456@gmail.com"><?php echo $companyDetail['primary_email'];?></a></p>
                    </li>
                    <li>
                        <p><span class="fa fa-phone"></span>Phone: <?php echo $companyDetail['contact_1']." / ".$companyDetail['contact_2'];?></p>
                    </li>
                    <li>
                        <p><?php 
                       // echo '<pre>';
                        foreach ($address as $addressKey => $value) {

                            ?>
                            <div class="col-md-6">
                              <div class="service-features">
                              <div class="img-container white-text">
                                <span class="fa fa-map-marker"></span> <?php echo $value['site_name'];?>
                              </div>
                                <p pull-left><?php echo $value['address_1'].'<br>'; ?>
                                <?php if(!empty($value['address_2'])){?>
                                <?php echo $value['address_2'].'<br>'; ?>

                                    <?php } ?>
                                <?php if(!empty($value['area_name'])){?>
                                <?php echo $value['area_name'].'<br>'; ?>

                                    <?php } ?>
                                <?php if(!empty($value['city_name'])){?>
                                <?php echo $value['city_name']; ?>/

                                <?php } ?>
                                <?php if(!empty($value['state_name'])){?>
                                <?php echo $value['state_name']; ?>/

                                    <?php } ?>
                                    <?php if(!empty($value['name'])){?>
                                <?php echo $value['name']; ?>.</p>

                                    <?php } ?>
                              </div>
                            </div>
                       <?php } ?></p>
                    </li>
                </ul>
            </div>
            <!-- widget -->

        </div>
                    <!-- col-md-7 -->

        <div class="contact-form-full col-md-4 col-sm-4 col-xs-11">
            <?php echo $contact; ?>
              
        </div>
    </div>
</div>
<!-- wrapper -->
<div class="map-area">
    <div class="container">
        <div id="map"></div>
    </div>
</div>