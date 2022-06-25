<div class="col-md-3">
    <!-- *** MENUS AND FILTERS ***
_________________________________________________________ -->
    <?php echo $this->load->view('templates/left-content-page'); ?>
    
</div>

<div class="col-md-9">


    <div class="box" id="contact">


<!-- <div class="box" id="contact"> -->
        <h1>Contact Us</h1>

        <!-- <p class="lead">Are you curious about something? Do you have some kind of problem with our products?</p>
            <p>Please feel free to contact us, our customer service center is working for you 24/7.</p> -->
        <p class="lead">
            Please let us know with your enquiries or feedback and we will get back to you shortly
        </p>

        <?php echo $contact;?>
        <hr>   
        <div id="map" data-id = "<?php echo $companyDetail['id'];?>"></div>
            <hr>
        <div class="row">
        <?php
            foreach($address as $key =>$add) {
                //print_r($key);
               //print_r($add);?>
            <div class="col-sm-4">
                <h3><i class="fa fa-map-marker"></i> <?php echo $add['site_name'];?></h3>
            
                <p><!-- 13/25 New Avenue
                    <br>New Heaven
                    <br>45Y 73J
                    <br>England
                    <br> -->
                    <?php echo $add['address_1'];?><br>
                    <?php echo $add['address_2'];?><br>
                    <?php echo $add['area_name'];?><br>
                    <?php echo $add['city_name'];?><br>
                    <?php echo $add['state_name'];?><br>

                     
                    <strong><?php echo $add['name'];?></strong>
                </p>
            </div>
                <?php } ?>
            <!-- /.col-sm-4 -->
            <div class="col-sm-4">
            

                <h3><i class="fa fa-phone"></i> Phone Numbers</h3>
                <!-- <p class="text-muted">This number is toll free if calling from Great Britain otherwise we advise you to use the electronic form of communication.</p> -->
                <!-- <p><strong>+33 555 444 333</strong> -->
                <p><strong><?php echo $companyDetail['contact_1']; ?></strong>

                </p>
            
            </div>
               
            <!-- /.col-sm-4 -->
            <div class="col-sm-4">
                <h3><i class="fa fa-envelope"></i> Electronic support</h3>
               <!--  <p class="text-muted">Please feel free to write an email to us or to use our electronic ticketing system.</p> -->
                <ul>
                    <li><strong><a href="mailto:"><?php echo $companyDetail['primary_email']; ?></a></strong>
                    </li>
                    <?php if(!empty($companyDetail['secondary_email']) && $companyDetail['secondary_email']!=$companyDetail['primary_email']){?>

                    <li><?php echo $companyDetail['secondary_email']; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <!-- /.col-sm-4 -->
        </div>
        <!-- /.row -->
    </div>


</div>
    <!-- /.col-md-9 -->           