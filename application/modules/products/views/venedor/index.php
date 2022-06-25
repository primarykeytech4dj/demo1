            <div id="category-breadcrumb">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><?=anchor('#', 'Home')?></li>
                        <!-- <li><a href="#">Electronics</a></li> -->
                        <li class="active">Products</li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-9 col-sm-8 col-xs-12 main-content">
                                
                                <?php //print_r($categoryWiseProducts);?>
                                <div class="category-item-container">
                                    <div class="row">
                                        <?php 
                                        foreach ($products as $key => $product) {

                                           ?>

                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                
                                                <div class="item item-hover cart-product">
                                                    <div class="item-image-wrapper">
                                                        <figure class="item-image-container">
                                                            <?=anchor('product/'.$product['slug'], img(['src'=>'', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'item-image lazy', 'style'=>'max-height:300px']).img(['src'=>'', 'data-src'=>content_url().'uploads/products/'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'item-image-hover lazy', 'style'=>'max-height:300px']))?>
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
                                                            <a href="product.html"><?=$product['product']?></a></h3>
                                                            <div class="item-action">
                                                            <?php echo form_open('cart/add_cart_item'); ?>
                                                            <fieldset>
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
                                        
                                    </div>
                                </div>
                                <!-- <div class="pagination-container clearfix">
                                    <div class="pull-right">
                                        <ul class="pagination">
                                            <li class="active"><a href="#">1</a></li>
                                            <li><a href="#">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li><a href="#">4</a></li>
                                            <li><a href="#">5</a></li>
                                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="pull-right view-count-box hidden-xs"><span class="separator">view:</span>
                                        <div class="btn-group select-dropdown">
                                            <button type="button" class="btn select-btn">10</button>
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">15</a></li>
                                                <li><a href="#">30</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <aside class="col-md-3 col-sm-4 col-xs-12 sidebar">
                                <?=$this->load->view('templates/venedor/left_side_bar')?>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>