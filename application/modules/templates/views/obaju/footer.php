 <!-- *** FOOTER ***
 _________________________________________________________ -->
        <div id="footer" data-animate="fadeInUp">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <h4>Pages</h4>
                            <?php echo $this->dynamic_menu->build_menu('7', 'class=""'); ?>
                        <!-- <ul>
                            <li>
                                <?php echo anchor('/', 'Home'); ?>
                            </li>
                            <li>
                                <?php echo anchor('about-us', 'About Us'); ?>
                            </li>
                            <li>
                                <?php echo anchor('product-category', 'Products'); ?>
                            </li>
                            <li>
                                <?php echo anchor('contact-us', 'Contact Us'); ?>
                            </li>
                        </ul> -->

                        <!-- <hr>
                        
                        <h4>User section</h4>
                        
                        <ul>
                            <li><a href="#" data-toggle="modal" data-target="#login-modal">Login</a>
                            </li>
                            <li><a href="register.html">Regiter</a>
                            </li>
                        </ul> -->

                        <hr class="hidden-md hidden-lg hidden-sm">

                    </div>
                    <!-- /.col-md-3 -->

                    <div class="col-md-3 col-sm-6">

                        <h4>Top categories</h4>
                        <?php 
                        echo $this->dynamic_menu->parentCategory_menu('class=""'); ?>
                        <!-- <h5>Cosmetics</h5> -->

                        <!-- <ul>
                            <li><a href="category.html">T-shirts</a>
                            </li>
                            <li><a href="category.html">Shirts</a>
                            </li>
                            <li><a href="category.html">Accessories</a>
                            </li>
                        </ul> -->

                        <!-- <h5>Auxilleries</h5>
                        <h5>Toileteries</h5> -->
                        <!-- <ul>
                            <li><a href="category.html">T-shirts</a>
                            </li>
                            <li><a href="category.html">Skirts</a>
                            </li>
                            <li><a href="category.html">Pants</a>
                            </li>
                            <li><a href="category.html">Accessories</a>
                            </li>
                        </ul> -->

                        <!-- <hr class="hidden-md hidden-lg"> -->

                    </div>
                    <!-- /.col-md-3 -->

                    <div class="col-md-3 col-sm-6">

                        <h4>Where to find us</h4>
                        <?php 
                        if(!empty($websiteAddress)){
                            //print_r($websiteAddress);
                         ?>
                        
                        <p><strong><?php echo $websiteInfo['company_name']; ?></strong>
                            <br>
                            <?php echo $websiteAddress['address_1'];
                                echo !empty($websiteAddress['address_2'])?'<br>'.$websiteAddress['address_2']:'';
                                echo "<br>".$websiteAddress['area_name'].", ".$websiteAddress['city_name'];
                                echo '<br>'.$websiteAddress['state_name'].", ".$websiteAddress['name'];
                                echo '<br>Pincode - '.$websiteAddress['pincode'];
                             ?>
                            <!-- <strong>Great Britain</strong> -->
                        </p>
                        <?php } ?>
                        <?php echo anchor('contact-us', 'Go to Contact Page'); ?>
                        <!-- <a href="contact.html">Go to contact page</a> -->

                        <hr class="hidden-md hidden-lg">

                    </div>
                    <!-- /.col-md-3 -->



                    <div class="col-md-3 col-sm-6">

                        <h4>Get the news</h4>

                        <p class="text-muted">Enter Your Email ID to subscribe our newsletter</p>

                        <form>
                            <div class="input-group">

                                <input type="text" class="form-control">

                                <span class="input-group-btn">

                <button class="btn btn-default" type="button">Subscribe!</button>

            </span>

                            </div>
                            <!-- /input-group -->
                        </form>

                        <hr>

                        <h4>Stay in touch</h4>

                        <p class="social">
                            <a href="#" class="facebook external" data-animate-hover="shake"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="twitter external" data-animate-hover="shake"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="instagram external" data-animate-hover="shake"><i class="fa fa-instagram"></i></a>
                            <a href="#" class="gplus external" data-animate-hover="shake"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="email external" data-animate-hover="shake"><i class="fa fa-envelope"></i></a>
                        </p>


                    </div>
                    <!-- /.col-md-3 -->

                </div>
                <!-- /.row -->

            </div>
            <!-- /.container -->
        </div>
        <!-- /#footer -->

        <!-- *** FOOTER END *** -->




        <!-- *** COPYRIGHT ***
 _________________________________________________________ -->
        <div id="copyright">
            <div class="container">
                <div class="col-md-6">
                    <p class="pull-left">© <?php echo date('Y'); ?> <?php echo $websiteInfo['company_name']; ?>.</p>

                </div>
                <div class="col-md-6">
                    <p class="pull-right">Designed By <?php echo anchor('http://www.primarykey.in', 'Primary Key Technologies') ?></a>
                         <!-- Not removing these links is part of the license conditions of the template. Thanks for understanding :) If you want to use the template without the attribution links, you can do so after supporting further themes development at https://bootstrapious.com/donate  -->
                    </p>
                </div>
            </div>
        </div>
        <!-- *** COPYRIGHT END *** -->



    </div>
    <!-- /#all -->

    

    <!-- *** SCRIPTS TO INCLUDE ***
 _________________________________________________________ -->
    <script src="<?php echo base_url();?>assets/obaju/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/jquery.cookie.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/waypoints.min.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/modernizr.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/bootstrap-hover-dropdown.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url();?>assets/obaju/js/front.js"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
 -->
    <!-- <script>
        function initialize() {
            var mapOptions = {
                zoom: 15,
                center: new google.maps.LatLng(19.19, 72.84),
                mapTypeId: google.maps.MapTypeId.ROAD,
                scrollwheel: false
            }
            var map = new google.maps.Map(document.getElementById('map'),
                mapOptions);

            var myLatLng = new google.maps.LatLng(19.19, 72.84);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script> -->
    <script>

    function loadMap() {
        //alert("map function");
        var uluru = {lat: 19.19, lng: 72.84};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });

      }
      $(document).ready(function(){
        if($('#map').length>0)
            loadMap();
      });
        </script> 
                                      
        <?php 
            if(isset($js)){
              //print_r($js);
                foreach ($js as $key => $jq) {
                  # code...
                    echo $jq;
                }
          }
           ?>      
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyAsVy9LaS5gKgYRbLX_bTvlmYeBaKXtA&callback=loadMap"></script>
</body>

</html>