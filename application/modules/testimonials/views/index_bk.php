    <!-- *** GET INSPIRED ***
 _________________________________________________________ -->
            <div class="container" data-animate="fadeInUpBig">
                <div class="col-md-12">
                    <div class="box slideshow">
                        <h3>Testimonials</h3>
                            <div id="get-inspired" class="owl-carousel owl-theme row">
                                <?php foreach ($testimonials as $testimonialKey => $testimonial) { //print_r($testimonial['image']); ?>
                                    
                                    <div class="item">
                                        <a href="#">
                                            <img src=<?php echo base_url()?>assets/uploads/testimonials/<?php echo $testimonial['image'];?> alt="<?php echo $testimonial['name']." ".$testimonial['company'];?>" class="img-responsive">
                                        </a>
                                    </div>
                                    
                                <?php } ?>
                            </div> 
                       
                    </div>
                </div>
            </div>
            <!-- *** GET INSPIRED END *** -->

            <!-- *** BLOG HOMEPAGE ***
 _________________________________________________________ -->

           