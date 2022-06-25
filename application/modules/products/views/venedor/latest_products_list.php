<?php 
                                        foreach ($products as $key => $product) {

                                           ?>

                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                
                                                <div class="item item-hover cart-product">
                                                    <div class="item-image-wrapper">
                                                        <figure class="item-image-container">
                                                            <?=anchor('product/'.$product['slug'], img(['src'=>'', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'lazy item-image'], 'style'=>'max-height:300px').img(['src'=>'', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'lazy item-image-hover', 'style'=>'max-height:300px']))?>
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
                                                            <?=anchor('product/'.$product['slug'], $product['product'])?></a></h3>
                                                            <div class="item-action">
                                                            <?php echo form_open('cart/add_cart_item'); ?>
                                                            <fieldset>
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
                                                                <?php echo form_hidden('product_id', $product['id']); ?>
                                                                <button type="submit" class="item-add-btn add-to-cart" id="Save"><span class="icon-cart-text">Add to Cart</span></button>
                                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like"></a> <a href="#" class="icon-button icon-compare checkout"></a></div>
                                                                <?php //echo form_submit('add', 'Add to Cart'); ?>
                                                            </fieldset>
                                                        <?php echo form_close(); ?>
                                                        <!-- <a href="#" class="item-add-btn add-to-cart"><span class="icon-cart-text">Add to Cart</span></a> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                           <?php
                                           if(($key+1)%3==0){
                                            echo '</div><div class="row">';
                                           }
                                        } ?>