<div id="breadcrumb-container">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><?=anchor('#', 'Home')?></li>
                        <li class="active">Shopping Cart</li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(!$this->cart->contents()):
                                    echo 'You don\'t have any items yet. '.anchor('#', 'CONTINUE SHOPPING', ['class'=>'btn btn-custom-2']);
                                else: ?>
                        <header class="content-title">
                            <h1 class="title">Shopping Cart : Final Checkout</h1>
                            <!-- <p class="title-desc">Just this week, you can use the free premium delivery.</p> -->
                        </header>
                        <div class="xs-margin"></div>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <?php
                                //print_r($this->cart->contents());
                                echo form_open('cart/update_cart');
                                    ?>
                                <table class="table cart-table">
                                    <thead>
                                        <tr>
                                            <th class="table-title">Product Name</th>
                                            <th class="table-title">Product Code</th>
                                            <th class="table-title">Unit Price</th>
                                            <th class="table-title">Quantity</th>
                                            <th class="table-title">SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 0;
                                    foreach ($this->cart->contents() as $key => $item) {

                                            ?>
                                        <tr>
                                            <td class="item-name-col cart">
                                                <figure>
                                                    <?php echo form_hidden('rowid['.$counter.']', $item['rowid']); ?>
                                                    <?=anchor('product/'.$item['slug'], img(['src'=>content_url().'uploads/products/thumbs/180x180_'.$item['image'], 'alt'=>$item['name']]))?>
                                                </figure>
                                                <header class="item-name"><?=anchor('product/'.$item['slug'], $item['name'])?></header>
                                                <?php if(isset($products[$item['rowid']])){ ?>
                                                <ul class="pull-right">
                                                    <?php  
                                                    foreach($products[$item['rowid']] as $variationKey => $variation){
                                                    ?>
                                                    <li>Select <?=$variationKey?>:
                                                        <input type="hidden" name="options[<?=$counter?>][<?=$variationKey?>]" value="<?=$item['options'][$variationKey]?>" id="<?=$item['rowid'].$variationKey?>">
                                                    <?php if(strtolower($variationKey)=='color'){ ?>
                                                        <ul class="filter-color-list clearfix active">
                                                        <?php foreach ($variation as $varKey => $varValue) {?>
                                                            <li>
                                                                
                                                                <a href="#" data-value="<?=$varValue?>" data-target="<?=$item['rowid'].$variationKey?>" class="filter-color-box variation" style="background-color: <?=$varValue?>; <?=($varValue==$item['options'][$variationKey])?'width:25px; height:25px; margin-bottom:10px':''?>;"></a>
                                                            </li>
                                                        <?php 
                                                        if(($varKey+1)%3==0)
                                                            echo '<br>';
                                                         } ?>
                                                        </ul>
                                                        <?php }else{
                                                            echo form_dropdown('data[cart][variation][]', $variation, array_search($item['options'][$variationKey], $variation), ['class'=>'form-control variation', 'data-target'=>$item['rowid'].$variationKey, 'id'=>$item['rowid'].$variationKey.$variation[0]]);
                                                    } ?>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                            </td>
                                            <td class="item-code"><?=$item['product_code']?></td>
                                            <td class="item-price-col"><span class="item-price-special"><?=$item['price']?></span></td>
                                            <td>
                                                <div class="custom-quantity-input">
                                                    <input type="number" name="qty[<?=$counter?>]" value="<?=$item['qty']?>" min="0"></div>
                                            </td>
                                            <td class="item-total-col"><span class="item-price-special"><?=$item['price']*$item['qty']?></span>
                                            </td>
                                        </tr>
                                            <?php
                                            $counter++;
                                        } ?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <p><?php echo form_submit('', 'UPDATE YOUR CART', ['class'=>'btn btn-custom']); echo anchor('cart/empty_cart', 'Empty Cart', ['class'=>"empty btn btn-custom-2"]);?></p>
                                                <p><small>If the quantity is set to zero, the item will be removed from the cart.</small></p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                    <?php
                                echo form_close();
                                
                                ?>
                            </div>
                        </div>
                        <div class="lg-margin"></div>
                        <div class="row">
                            <div class="col-md-8 col-sm-12 col-xs-12 lg-margin">
                                <!-- <div class="tab-container left clearfix">
                                    <ul class="nav-tabs">
                                        <li class="active"><a href="#shipping" data-toggle="tab">Shipping &amp; Taxes</a></li>
                                        <li><a href="#discount" data-toggle="tab">Discount Code</a></li>
                                        <li><a href="#gift" data-toggle="tab">Gift voucher</a></li>
                                    </ul>
                                    <div class="tab-content clearfix">
                                        <div class="tab-pane active" id="shipping">
                                            <form action="#" id="shipping-form">
                                                <p class="shipping-desc">Enter your destination to get a shipping estimate.</p>
                                                <div class="form-group">
                                                    <label for="select-country" class="control-label">Country&#42;</label>
                                                    <div class="input-container normal-selectbox">
                                                        <select id="select-country" name="select-country" class="selectbox">
                                                            <option value="Japan">Japan</option>
                                                            <option value="Brazil">Brazil</option>
                                                            <option value="France">France</option>
                                                            <option value="Italy">Italy</option>
                                                            <option value="Spain">Spain</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="xss-margin"></div>
                                                <div class="form-group">
                                                    <label for="select-state" class="control-label">Regison/State&#42;</label>
                                                    <div class="input-container normal-selectbox">
                                                        <select id="select-state" name="select-state" class="selectbox">
                                                            <option value="California">California</option>
                                                            <option value="Texas">Texas</option>
                                                            <option value="NewYork">NewYork</option>
                                                            <option value="Narnia">Narnia</option>
                                                            <option value="Jumanji">Jumanji</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="xss-margin"></div>
                                                <div class="form-group">
                                                    <label for="select-country" class="control-label">Post Code&#42;</label>
                                                    <div class="input-container">
                                                        <input type="text" required class="form-control" placeholder="Your fax">
                                                    </div>
                                                </div>
                                                <div class="xss-margin"></div>
                                                <p class="text-right">
                                                    <input type="submit" class="btn btn-custom-2" value="GET QUOTES">
                                                </p>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="discount">
                                            <p>Enter your discount coupon code here.</p>
                                            <form action="#">
                                                <div class="input-group">
                                                    <input type="text" required class="form-control" placeholder="Coupon code">
                                                </div>
                                                <input type="submit" class="btn btn-custom-2" value="APPLY COUPON">
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="gift">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi dignissimos nostrum debitis optio molestiae in quam dicta labore obcaecati ullam necessitatibus animi deleniti minima dolor suscipit nobis est excepturi inventore.</p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <table class="table total-table">
                                    <tbody>
                                        <tr>
                                            <td class="total-table-title">Subtotal:</td>
                                            <td><i class="fa fa-rupee"></i> <?=$this->cart->format_number($this->cart->total())?></td>
                                        </tr>
                                        <?php 
                                        $total = $this->cart->total();
                                        if(isset($otherCharges)){
                                            foreach ($otherCharges as $key => $value) {
                                                $total = $total+$value['cost'];
                                                ?>
                                                <tr>
                                                    <td class="total-table-title"><?=$key?>:</td>
                                                    <td><i class="fa fa-rupee"></i> <?=$this->cart->format_number($value['cost'])?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Total:</td>
                                            <td><i class="fa fa-rupee"></i> <?=$this->cart->format_number($total)?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="md-margin"></div>
                                    <?=anchor('#', 'CONTINUE SHOPPING', ['class'=>'btn btn-custom-2'])?>
                                    <?=anchor(custom_constants::confirm_order, 'CONFIRM ORDER', ['class'=>'btn btn-custom'])?>
                                 
                        </div>
                        <?php endif; ?>
                        <div class="md-margin2x"></div>
                        <div class="similiar-items-container carousel-wrapper">
                            <header class="content-title">
                                <div class="title-bg">
                                    <h2 class="title">Similiar Products</h2></div>
                                <p class="title-desc">Note the similar products - after buying for more than $500 you can get a discount.</p>
                            </header>
                            <div class="carousel-controls">
                                <div id="similiar-items-slider-prev" class="carousel-btn carousel-btn-prev"></div>
                                <div id="similiar-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>
                            </div>
                            <div class="similiar-items-slider owl-carousel">
                                <div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="product.html"><img src="images/products/item3.jpg" alt="item1" class="item-image"> <img src="images/products/item3-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
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
                                            <a href="product.html"><img src="images/products/item1.jpg" alt="item1" class="item-image"> <img src="images/products/item1-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$100</span></div>
                                    </div>
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
                                            <a href="product.html"><img src="images/products/item4.jpg" alt="item1" class="item-image"> <img src="images/products/item4-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
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
                                            <a href="product.html"><img src="images/products/item10.jpg" alt="item1" class="item-image"> <img src="images/products/item10-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="old-price">$120</span> <span class="item-price">$60</span></div><span class="discount-rect">-50%</span></div>
                                    <div class="item-meta-container">
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="65"></div>
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
                                            <a href="product.html"><img src="images/products/item5.jpg" alt="item1" class="item-image"> <img src="images/products/item5-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
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
                                            <a href="product.html"><img src="images/products/item7.jpg" alt="item1" class="item-image"> <img src="images/products/item7-hover.jpg" alt="item1  Hover" class="item-image-hover"></a>
                                        </figure>
                                        <div class="item-price-container"><span class="item-price">$280</span></div>
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
                        </div>
                    </div>
                </div>
            </div>