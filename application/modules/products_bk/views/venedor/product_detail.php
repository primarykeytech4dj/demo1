<div id="breadcrumb-container">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li class="active">Product</li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                                    <?php /*echo '<pre>';
                                    print_r($productImages);
                                    echo '</pre>';*/
                                     ?>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12 product-viewer clearfix">
                                <div id="product-image-carousel-container">
                                    <ul id="product-carousel" class="celastislide-list">
                                        <?php foreach ($productImages as $key => $images) {?>
                                        <li class="<?=($key==0)?'active-slide':''?>">
                                            <a data-rel="prettyPhoto[product]" href="<?=content_url()?>uploads/products/<?=$images['image_name_1']?>" data-image="<?=content_url()?>uploads/products/thumbs/404xx_<?=$images['image_name_1']?>" data-zoom-image="<?=content_url()?>uploads/products/<?=$images['image_name_1']?>" class="product-gallery-item">
                                                <img src="<?=content_url()?>uploads/products/thumbs/92xx_<?=$images['image_name_1']?>" alt="<?=$product['product']?>">
                                            </a>
                                        </li>
                                        <li>
                                            <a data-rel="prettyPhoto[product]" href="<?=content_url()?>uploads/products/<?=$images['image_name_2']?>" data-image="<?=content_url()?>uploads/products/thumbs/404xx_<?=$images['image_name_2']?>" data-zoom-image="<?=content_url()?>uploads/products/<?=$images['image_name_2']?>" class="product-gallery-item">
                                                <img src="<?=content_url()?>uploads/products/thumbs/92xx_<?=$images['image_name_2']?>" alt="<?=$product['product']?>">
                                            </a>
                                        </li>
                                           
                                       <?php } ?>
                                    </ul>
                                </div>
                                <div id="product-image-container">
                                    <figure><img src="<?=content_url()?>uploads/products/thumbs/404xx_<?=$product['image_name_1']?>" data-zoom-image="<?=content_url()?>uploads/products/<?=$product['image_name_1']?>" alt="<?=$product['product']?>" id="product-image">
                                        <figcaption class="item-price-container">
                                            <?php if((int)$product['base_price'] != (int)$product['actual_price']){
                                                               
                                             ?>
                                                <span class="old-price"><?=$product['actual_price']?></span>
                                            <?php } ?>
                                            <span class="item-price"><?=$product['base_price']?></span></figcaption>
                                    </figure>
                                </div>
                            </div>
                            <?php echo form_open('cart/add_cart_item'); ?>
                            <div class="col-md-6 col-sm-12 col-xs-12 product">
                                <div class="lg-margin visible-sm visible-xs"></div>
                                <h1 class="product-name"><?=$product['product']?></h1>
                                <!-- <div class="ratings-container">
                                    <div class="ratings separator">
                                        <div class="ratings-result" data-result="40"></div>
                                    </div>
                                    <span class="ratings-amount separator">3 Review(s)</span> 
                                    <span class="separator">|</span> <a href="#review" class="rate-this">Add Your Review</a>
                                </div> -->
                                <ul class="product-list">
                                    <li><span>Availability:</span>In Stock (<?=$product['in_stock_qty']?> pcs)</li>
                                    <li><span>Product Code:</span><?=$product['product_code']?></li>
                                    <!-- <li><span>Brand:</span>Apple</li> -->
                                </ul>
                                <hr>
                                <?php if(count($productVariation)>0){ ?>
                                <div class="product-color-filter-container">
                                                <ul>
                                                    <?php  
                                                    $counter = 0;
                                                    foreach($productVariation as $variationKey => $variation){
                                                        //print_r($variation);
                                                    ?>
                                                    <li>Select <?=$variationKey?>:
                                                        <input type="hidden" name="options[<?=$variationKey?>]" value="<?=$variation[0]?>" id="<?=$variationKey?>">
                                                    <?php if(strtolower($variationKey)=='color'){ ?>
                                                        <ul class="filter-color-list clearfix active">
                                                        <?php foreach ($variation as $varKey => $varValue) {?>
                                                            <li>
                                                                
                                                                <a href="#" data-value="<?=$varValue?>" data-target="<?=$variationKey?>" class="filter-color-box variation" style="background-color: <?=$varValue?>;"></a>
                                                            </li>
                                                        <?php 
                                                        
                                                         } ?>
                                                        </ul>
                                                        <?php }else{
                                                        //foreach ($variation as $varKey => $varValue) {
                                                            echo form_dropdown('data[cart][variation][]', $variation, $variation[0], ['class'=>'form-control variation', 'data-target'=>$variationKey, 'id'=>$variationKey.$variation[0], 'style'=>'width:50%']);
                                                        //} 
                                                    } ?>
                                                    </li>
                                                    <?php } ?>
                                                    <!-- <li>Color: White</li>
                                                    <li>Size: SM</li> -->
                                                </ul>
                                </div>
                                <hr>
                                <?php } ?>
                                <div class="product-add clearfix">
                                    <div class="custom-quantity-input">
                                        <input type="number" name="quantity" value="1" min="1" max="<?=$product['in_stock_qty']?>"> 
                                    </div>

                                        <?php echo form_hidden('product_id', $product['id']); ?>
                                        
                                    <button class="btn btn-custom-2 add-to-cart" data-id="<?=$product['id']?>">ADD TO CART</button>
                                </div>
                                <?php echo form_close(); ?>
                                <div class="md-margin"></div>
                                <div class="product-extra clearfix">
                                    <div class="product-extra-box-container clearfix">
                                        <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                    </div>
                                    <div class="md-margin visible-xs"></div>
                                    <div class="share-button-group">
                                        <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                                            <a class="addthis_button_facebook"></a>
                                            <a class="addthis_button_twitter"></a>
                                            <a class="addthis_button_email"></a>
                                            <a class="addthis_button_print"></a>
                                            <a class="addthis_button_compact"></a>
                                            <a class="addthis_counter addthis_bubble_style"></a>
                                        </div>
                                        <script type="text/javascript">
                                            var addthis_config = {
                                                data_track_addressbar: !0
                                            };
                                        </script>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg-margin2x"></div>
                        <div class="row">
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="tab-container left product-detail-tab clearfix">
                                    <ul class="nav-tabs">
                                        <li class="active"><a href="#description" data-toggle="tab">Description</a></li>
                                        <li><a href="#review" data-toggle="tab">Review</a></li>
                                        <li><a href="#video" data-toggle="tab">Video</a></li>
                                    </ul>
                                    <div class="tab-content clearfix">
                                        <div class="tab-pane active" id="description">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <?=$product['description']?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="review">
                                            <p>Sed volutpat ac massa eget lacinia. Suspendisse non purus semper, tellus vel, tristique urna.</p>
                                            <p>Cumque nihil facere itaque mollitia consectetur saepe cupiditate debitis fugiat temporibus soluta maxime doloremque alias enim officia aperiam at similique quae vel sapiente nulla molestiae tenetur deleniti architecto ratione accusantium.</p>
                                        </div>
                                        <!-- <div class="tab-pane" id="additional"><strong>Additional Informations</strong>
                                            <p>Quae eum placeat reiciendis enim at dolorem eligendi?</p>
                                            <hr>
                                            <ul class="product-details-list">
                                                <li>Lorem ipsum dolor sit quam</li>
                                                <li>Consectetur adipisicing elit</li>
                                                <li>Illum autem tempora officia</li>
                                                <li>Amet id odio architecto explicabo</li>
                                                <li>Voluptatem laborum veritatis</li>
                                                <li>Quae laudantium iste libero</li>
                                            </ul>
                                        </div> -->
                                        <div class="tab-pane" id="video">
                                            <div class="video-container"><strong>A Video about Product</strong>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur adipisci esse.</p>
                                                <hr>
                                                <iframe width="560" height="315" src="http://www.youtube.com/embed/Z0MNVFtyO30?rel=0"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lg-margin visible-xs"></div>
                            </div>
                            <div class="lg-margin2x visible-sm visible-xs"></div>
                            <div class="col-md-3 col-sm-12 col-xs-12 sidebar">
                                <div class="widget related">
                                    <h3>Related</h3>
                                    <div class="related-slider flexslider sidebarslider">
                                        <ul class="related-list clearfix">
                                            <li>
                                                <?php foreach ($relatedProducts as $key => $related) {?>

                                                <div class="related-product clearfix">
                                                    <figure><img src="<?=content_url()?>uploads/products/thumbs/115xx_<?=$related['image_name_1']?>" alt="<?=$related['product']?>"></figure>
                                                    <h5><?=anchor('product/'.$related['slug'], $related['product']);?></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="related-price pull-right">
                                                        <?=$related['base_price']?></div>
                                                </div>

                                           <?php 
                                           if(($key+1)%3==0){
                                                echo '</li><li>';
                                            } 
                                        } ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="lg-margin2x"></div>
                        <div class="purchased-items-container carousel-wrapper">
                            <header class="content-title">
                                <div class="title-bg">
                                    <h2 class="title">Also Purchased</h2></div>
                                <p class="title-desc">Note the similar products - after buying for more than $500 you can get a discount.</p>
                            </header>
                            <div class="carousel-controls">
                                <div id="purchased-items-slider-prev" class="carousel-btn carousel-btn-prev"></div>
                                <div id="purchased-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>
                            </div>
                            <div class="purchased-items-slider owl-carousel">
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item7.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item7-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$160<span class="sub-price">.99</span></span>
                                        </div><span class="new-rect">New</span></div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="80"></div>
                                            </div><span class="ratings-amount">5 Reviews</span></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item8.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item8-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$100</span></div><span class="new-rect">New</span></div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="99"></div>
                                            </div><span class="ratings-amount">4 Reviews</span></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item9.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item9-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="old-price">$100</span> <span class="item-price">$80</span></div><span class="discount-rect">-20%</span></div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="75"></div>
                                            </div><span class="ratings-amount">2 Reviews</span></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item6.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item6-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$99</span></div><span class="new-rect">New</span></div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="40"></div>
                                            </div><span class="ratings-amount">3 Reviews</span></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item7.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item7-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$280</span></div>
                                    </div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container"></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><!-- <a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a> -
                                            <?=anchor();?>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="<?=assets_url()?>venedor/images/products/item10.jpg" alt="item1" class="item-image"> <img src="<?=assets_url()?>venedor/images/products/item10-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="old-price">$150</span> <span class="item-price">$120</span></div>
                                    </div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container"></div>
                                        <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                        <div class="item-action"><a href="#" class="item-add-btn"><span class="icon-cart-text">Add to Cart</span></a>
                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like">Favourite</a> <a href="#" class="icon-button icon-compare">Checkout</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>