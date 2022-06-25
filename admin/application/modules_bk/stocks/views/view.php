<div class="col-md-3">
    <?php echo $this->load->view('templates/left-content'); ?>    
</div>
<div class="col-md-9">
    <div class="box">
        
        <h1>Our Projects</h1>
        <?php echo nl2br($order['message']); ?>
    </div>

    
    <div class="row products">
	<?php //print_r($orderImages);
	foreach ($orderImages as $orderKey => $order) {
		?>

        <div class="col-md-4 col-sm-4">
            <div class="product">
                <div class="flip-container">
                    <div class="flipper">
                        <div class="front">
                            <?php echo anchor('assets/uploads/documents/'.$order['file'], img(['src'=>'assets/uploads/documents/'.$order['file'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                        <div class="back">
                        	<?php echo anchor('assets/uploads/documents/'.$order['file'], img(['src'=>'assets/uploads/documents/'.$order['file'], 'class'=>'img-responsive']), [ 'data-toggle'=>'lightbox']); ?>
                        </div>
                    </div>
                </div>
                 <?php echo anchor('orders/view/'.$order['order_code'], img(['src'=>'assets/uploads/documents/'.$order['file'], 'class'=>'img-responsive']), ['class'=>'invisible']); ?>
               
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