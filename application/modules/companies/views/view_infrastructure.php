<div class="col-md-3">
    <?php echo $this->load->view('templates/left-content'); ?>    
</div>
<div class="col-md-9">
    <div class="box">
        <div class="box-header">
            <h1><center>Infrastructure And Facilities</center></h1>
        </div>
        <div class="box-body">
            <?php echo $infrastructures['comment']; ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header">
            <h1><center>Our Gallery</center></h1>
        </div>
        <div class="box-body">



    <?php if(isset($infrastructureMedia['Image']) && count($infrastructureMedia['Image'])>0){ ?>
    <div class="row products">
    <?php 
    foreach ($infrastructureMedia['Image'] as $imageKey => $image) {
        ?>

        <div class="col-md-4 col-sm-4">
            <div class="product">
                <div class="flip-container">
                    <div class="flipper">
                        <div class="front">
                            <?php echo anchor('assets/uploads/media/'.$image['image'], img(['src'=>'assets/uploads/media/'.$image['image'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                        <div class="back">
                            <?php echo anchor('assets/uploads/media/'.$image['image'], img(['src'=>'assets/uploads/media/'.$image['image'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                    </div>
                </div>

                <!-- <a href="detail.html" class="invisible">
                    <img src="img/product1.jpg" alt="" class="img-responsive">
                </a> -->
                <div class="text">
                    <h3>
                        <?php 
                        echo $image['text']; ?>
                        <!-- <a href="detail.html">Fur coat with very but very very long name</a> -->
                            
                    </h3>
                   
                    <p class="buttons">
                        <?php 
                        echo anchor('#', 'View More', ['class'=>"btn btn-default"]);
                        
                         ?>
                    </p>
                    <?php echo $image['text']; ?>
                 <p class="muted"><?php echo $image['description']; ?></p>
                </div>
                <!-- /.text -->
                
            </div>
            <!-- /.product -->
        </div>
        <?php
    }
     ?>
    </div>
    <!-- /.products -->
    <?php } ?>

     <?php if(isset($infrastructureMedia['video']) && count($infrastructureMedia['video'])>0){ ?>
    <div class="row products">
    <?php 
    foreach ($infrastructureMedia['video'] as $videoKey => $video) {
        ?>

        <div class="col-md-4 col-sm-4">
           
                            <?php echo $video['text']; ?>
                        

                <!-- <a href="detail.html" class="invisible">
                    <img src="img/product1.jpg" alt="" class="img-responsive">
                </a> -->
                <div class="text">
                    <h3>
                        <?php 
                       // echo $video['text']; ?>
                        <!-- <a href="detail.html">Fur coat with very but very very long name</a> -->
                            
                    </h3>
                   
                    <p class="buttons">
                        <?php 
                        //echo anchor('#', 'View More', ['class'=>"btn btn-default"]);
                        
                         ?>
                    </p>
                    <?php //echo $video['text']; ?>
                 <p class="muted"><?php echo $video['description']; ?></p>
                
        </div>
    </div>
        <?php
    }
     ?>
     </div>
    </div>
    </div>
    <!-- /.products -->
    <?php } ?>

</div>
<!-- /.col-md-9 -->