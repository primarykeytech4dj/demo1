<div class="xlg-margin"></div>
                                <div class="hot-items carousel-wrapper">
                                    <header class="content-title">
                                        <div class="title-bg">
                                            <h2 class="title">On Sale</h2></div>
                                        <!-- <p class="title-desc">Only with us you can get a new model with a discount.</p> -->
                                    </header>
                                    <div class="carousel-controls">
                                        <div id="hot-items-slider-prev" class="carousel-btn carousel-btn-prev"></div>
                                        <div id="hot-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>
                                    </div>
                                    <div class="hot-items-slider owl-carousel">

                                        <?php foreach ($saleProduct as $key => $product) {
                                           ?>
                                        <div class="item item-hover cart-product">
                                            <div class="item-image-wrapper">
                                                <figure class="item-image-container">
                                                    <?=anchor('product/'.$product['slug'], img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'item-image lazy', 'style'=>'max-height:270px']).img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'item-image-hover lazy', 'style'=>'max-height:270px']))?>
                                                </figure>
                                                <div class="item-price-container">

                                                    <?php if((int)$product['base_price'] != (int)$product['actual_price']){
                                                               
                                                             ?>
                                                                <span class="old-price"><?=$product['actual_price']?></span>
                                                            <?php } ?>
                                                    <span class="item-price"><?=$product['base_price']?></span>
                                                </div><?php if($product['is_new']){ ?><span class="new-rect">New</span><?php } ?>
                                            </div>
                                            <div class="item-meta-container">
                                                <!-- <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-result" data-result="80"></div>
                                                    </div><span class="ratings-amount">4 Reviews</span>
                                                </div> -->
                                                <h3 class="item-name">
                                                    <?=anchor('product/'.$product['slug'], $product['product'])?></h3>
                                                    <div class="item-action">
                                                    <?php echo form_open('cart/add_cart_item'); ?>
                                                    <fieldset>
                                                        <?php if(count($product['attributes'])>0){ ?>
                                                        <center>
                                                        <select id="lt_attribute_<?=$product['id']?>" class="form-control" style="width:200px" name="attribute">
                                                            <?php 
                                                            foreach($product['attributes'] as $attKey=>$att)
                                                            {
                                                                echo '<option value="'.$att['product_attribute_id'].'">'.$att['default_uom'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        </center>
                                                        <br>
                                                        <?php }
                                                        echo form_hidden('product_id', $product['id']); ?>
                                                        <button type="submit" class="item-add-btn add-to-cart" id="Save"><span class="icon-cart-text">Add to Cart</span></button>
                                                    <div class="item-action-inner"><a href="#" class="icon-button icon-like"></a> <a href="#" class="icon-button icon-compare checkout"></a></div>
                                                        <?php //echo form_submit('add', 'Add to Cart'); ?>
                                                    </fieldset>
                                                <?php echo form_close(); ?>
                                                <!-- <a href="#" class="item-add-btn add-to-cart"><span class="icon-cart-text">Add to Cart</span></a> -->
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>