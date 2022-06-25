<?php //print_r($parentCategory);exit;?>
<div id="category-header" style="background: url(<?=content_url()?>uploads/product_categories/<?=(!empty($parentCategory['image_name_1']))?$parentCategory['image_name_1']:'default-banner.jpg'?>) no-repeat center center; background-size:cover">
                <div class="row">
                    <div class="container">
                        <div class="col-2">
                            <?php //echo '<pre>';print_r($parentCategory);exit; ?>
                            <div class="category-image"><img src="<?=content_url()?>uploads/product_categories/<?=(!empty($parentCategory['image_name_2']))?$parentCategory['image_name_2']:'default.jpg'?>" alt="<?=$parentCategory['category_name']?>" class="img-responsive"></div>
                        </div>
                        <div class="col-2 last">
                            <div class="category-title">
                                <h2><?=ucfirst($parentCategory['category_name'])?></h2>
                                <?=$parentCategory['description']?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="category-breadcrumb">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><?=anchor('#', 'Home')?></li>
                        <!-- <li><a href="#">Electronics</a></li> -->
                        <li class="active"><?=$parentCategory['category_name']?></li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-9 col-sm-8 col-xs-12 main-content">
                                
                                <div class="category-item-container">
                                    <div class="row">
                                        <?php 
                                        if(count($products)>0){
                                        foreach ($products as $key => $product) {

                                           ?>

                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                
                                                <div class="item item-hover cart-product">
                                                    <div class="item-image-wrapper">
                                                        <figure class="item-image-container">
                                                            <?=anchor('product/'.$product['slug'], img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/thumbs/222xx_'.$product['image_name_1'], 'alt'=>$product['product'], 'class'=>'lazy item-image', 'style'=>'max-height:300px']).img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/thumbs/222xx_'.$product['image_name_2'], 'alt'=>$product['product'], 'class'=>'lazy item-image-hover', 'style'=>'max-height:300px']))?>
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
                                                        
                                                        <h3 class="item-name">
                                                            <?=anchor('product/'.$product['slug'], $product['product'])?></h3>
                                                            <div class="item-action">
                                                            <?php echo form_open('cart/add_cart_item'); ?>
                                                            <fieldset>
                                                                <?php echo form_hidden('product_id', $product['id']); ?>
                                                                <button type="submit" class="item-add-btn add-to-cart" id="Save"><span class="icon-cart-text">Add to Cart</span></button>
                                                            <div class="item-action-inner"><a href="#" class="icon-button icon-like"></a> <a href="#" class="icon-button icon-compare checkout"></a></div>
                                                                
                                                            </fieldset>
                                                        <?php echo form_close(); ?>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                           <?php
                                           if(($key+1)%3==0){
                                            echo '</div><div class="row">';
                                           }
                                        }
                                    }else{
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                                <h1>No Product Available</h1>
                                            </div>
                                            <?php 
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