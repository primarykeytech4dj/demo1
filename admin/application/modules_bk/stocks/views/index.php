<div class="col-md-3">
    <?php echo $this->load->view('templates/left-content'); ?>    
</div>
<div class="col-md-9">
    <div class="box">
        
        <h1>Our Projects</h1>
    </div>

    
    <div class="row products">
	<?php 
	foreach ($orders as $orderKey => $order) {
		?>

        <div class="col-md-4 col-sm-4">
            <div class="product">
                <div class="flip-container">
                    <div class="flipper">
                        <div class="front">
                            <?php echo anchor('assets/uploads/documents/'.$order['coverimage'], img(['src'=>'assets/uploads/documents/'.$order['coverimage'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                        <div class="back">
                        	<?php echo anchor('assets/uploads/documents/'.$order['coverimage'], img(['src'=>'assets/uploads/documents/'.$order['coverimage'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                    </div>
                </div>
                 <?php echo anchor('project-detail/'.$order['order_code'], img(['src'=>'assets/uploads/documents/'.$order['coverimage'], 'class'=>'img-responsive']), ['class'=>'invisible']); ?>
                <!-- <a href="detail.html" class="invisible">
                    <img src="img/product1.jpg" alt="" class="img-responsive">
                </a> -->
                <div class="text">
                    <h3>
						<?php 
						echo anchor('project-detail/'.$order['order_code'], $order['project_name']); ?>
                    	<!-- <a href="detail.html">Fur coat with very but very very long name</a> -->
                    		
                    </h3>
                   
                    <p class="buttons">
                    	<?php 
                    	echo anchor('project-detail/'.$order['order_code'], 'View More', ['class'=>"btn btn-default"]);
                    	
                    	 ?>
                        
                    </p>
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

</div>
<!-- /.col-md-9 -->