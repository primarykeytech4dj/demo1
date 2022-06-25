<?php
if(count($sliders)>0){
 ?>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <?php 
            foreach ($sliders as $key => $slide) {
              $active = ($key==0)?'active':'';
              echo '<li data-target="#carousel-example-generic" data-slide-to="'.$key.'" class="'.$active.'"></li>';
            } ?>
            <!-- <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li> -->
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <?php
            foreach ($sliders as $key => $slide) {
                $active = ($key==0)?'active':'';
              ?>
            <div class="item <?php echo $active ?>">
              <?php if($slide['type']=='image'){
                ?>
                  <img src="<?php echo content_url();?>uploads/sliders/<?php echo $slide['image']; ?>" alt="" class="img-responsive">
                <?php
              }else{
                ?>
                  <?=$slide['image']?>
                <?php
              } ?>
              <div class="hero-caption">
                <div class="slider-content hero-text text-VALUKA text-center">
                  <h2><?=$slide['title_1']?></h2>
                  <p><?=$slide['title_2']?></p>
                  <?=anchor($slide['link'], 'Click Here', ['class'=>'btn btn-default'])?>
                  <!-- <a class="btn" href="about.html">About Us</a> -->
                </div>
              </div>
              <!-- <div class="carousel-caption">
                <h2><?=$slide['title_1']?></h2>
                <h4><?=$slide['title_2']?></h4>
              </div> -->
            </div>
            <?php } ?>
          </div>

          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <?php } ?>