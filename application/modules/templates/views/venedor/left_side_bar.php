<div class="widget">
                                    <div class="panel-group custom-accordion sm-accordion pull-left" id="category-filter">
                                        <div class="panel">
                                            <div class="accordion-header">
                                                <div class="accordion-title"><span>Category</span></div>
                                                <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-1"></a>
                                            </div>
                                            <div id="category-list-1" class="collapse in">
                                                <div class="panel-body">

                                                    <ul class="category-filter-list jscrollpane">
                                                        <?php $categories = Modules::run('products/get_productwise_category_list');
                                                        foreach ($categories as $key => $category) {
                                                            echo '<li>'.anchor('category/'.$category['slug'], $category['category_name']." (".$category['product_count'].")").'</li>';
                                                        }
                                                         ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="accordion-header">
                                                <div class="accordion-title"><span>Brand</span></div>
                                                <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-2"></a>
                                            </div>
                                            <div id="category-list-2" class="collapse in">
                                                <div class="panel-body">
                                                    <ul class="category-filter-list jscrollpane">
                                                        <?php 
                                                        $brands = Modules::run('products/get_productwise_category_list');
                                                        foreach ($brands as $key => $brand) {
                                                            //echo '<li>'.anchor('brand/'.$brand['slug'], $brand['brand']." (".$brand['product_count'].")");
                                                        }
                                                         ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="accordion-header">
                                                <div class="accordion-title"><span>Price</span></div>
                                                <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-3"></a>
                                            </div>
                                            <div id="category-list-3" class="collapse in">
                                                <div class="panel-body">
                                                    <div id="price-range"></div>
                                                    <div id="price-range-details"><span class="sm-separator">from</span>
                                                        <input type="text" id="price-range-low" class="separator"> <span class="sm-separator">to</span>
                                                        <input type="text" id="price-range-high">
                                                    </div>
                                                    <div id="price-range-btns"><a href="#" class="btn btn-custom-2 btn-sm">Ok</a> <a href="#" class="btn btn-custom-2 btn-sm">Clear</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="accordion-header">
                                                <div class="accordion-title"><span>Color</span></div>
                                                <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-4"></a>
                                            </div>
                                            <div id="category-list-4" class="collapse in">
                                                <div class="panel-body">
                                                    <ul class="filter-color-list clearfix">
                                                        <li>
                                                            <a href="#" data-bgcolor="#fff" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#ffff33" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#ff9900" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-md">
                                                            <a href="#" data-bgcolor="#ff9999" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-lg">
                                                            <a href="#" data-bgcolor="#99cc33" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#339933" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#ff0000" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-md">
                                                            <a href="#" data-bgcolor="#ff3366" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#cc33ff" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-lg">
                                                            <a href="#" data-bgcolor="#9966cc" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#99ccff" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-md">
                                                            <a href="#" data-bgcolor="#3333cc" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#999999" class="filter-color-box"></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" data-bgcolor="#663300" class="filter-color-box"></a>
                                                        </li>
                                                        <li class="last-lg">
                                                            <a href="#" data-bgcolor="#000" class="filter-color-box"></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--div class="widget featured">
                                    <h3>Featured</h3>
                                    <div class="featured-slider flexslider sidebarslider">
                                        <ul class="featured-list clearfix">
                                            <li>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item5.jpg" alt="item5"></figure>
                                                    <h5><a href="#">Jacket Suiting Blazer</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$40</div>
                                                </div>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item1.jpg" alt="item1"></figure>
                                                    <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$18</div>
                                                </div>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item2.jpg" alt="item2"></figure>
                                                    <h5><a href="#">Women's Lauren Dress</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$30</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item3.jpg" alt="item3"></figure>
                                                    <h5><a href="#">Swiss Mobile Phone</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="64"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$39</div>
                                                </div>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item4.jpg" alt="item4"></figure>
                                                    <h5><a href="#">Zwinzed HeadPhones</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="94"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$18.99</div>
                                                </div>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item7.jpg" alt="item7"></figure>
                                                    <h5><a href="#">Kless Man Suit</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="74"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$99</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item4.jpg" alt="item4"></figure>
                                                    <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$17</div>
                                                </div>
                                                <div class="featured-product clearfix">
                                                    <figure><img src="<?=assets_url()?>venedor/images/products/thumbnails/item6.jpg" alt="item6"></figure>
                                                    <h5><a href="#">Women's Lauren Dress</a></h5>
                                                    <div class="ratings-container">
                                                        <div class="ratings">
                                                            <div class="ratings-result" data-result="84"></div>
                                                        </div>
                                                    </div>
                                                    <div class="featured-price">$30</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div-->
                                <div class="widget banner-slider-container">
                                    <div class="banner-slider flexslider">
                                        <ul class="banner-slider-list clearfix">
                                            <li>
                                                <a href="#"><img src="<?=assets_url()?>venedor/images/banner1.jpg" alt="Banner 1"></a>
                                            </li>
                                            <li>
                                                <a href="#"><img src="<?=assets_url()?>venedor/images/banner2.jpg" alt="Banner 2"></a>
                                            </li>
                                            <li>
                                                <a href="#"><img src="<?=assets_url()?>venedor/images/banner3.jpg" alt="Banner 3"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>