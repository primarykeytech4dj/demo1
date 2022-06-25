<div class="divider col-sm-12 col-xs-12 col-md-12">
      <div class="header-text text-center" >Our <span>Testimonials</span></div>
    </div>
<section class="testimonial">
  <div class="col-md-12 testimonial-blog">
    <div id="wrapper">
      <div class="testimonials-slider">
        <?php 
        foreach ($testimonials as $key => $testimonial) {
          ?>
          <div class="slide">
                  <div class="testimonials-carousel-thumbnail"><img width="120" alt="" src="<?php echo content_url();?>uploads/testimonials/<?php echo $testimonial['image'];?>"></div>
                    <div class="testimonials-carousel-context">
                      <div class="testimonials-name">
                        <?php echo $testimonial['name'];?> <span></span>
                      </div>
                      <div class="testimonials-carousel-content">
                        <p><?php echo $testimonial['comment'];?></p>
                      </div>
                    </div>
                  </div>
                      <?php } ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</section>