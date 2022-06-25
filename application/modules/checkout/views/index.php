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
                    echo form_hidden('request_url', 'checkout');
                        ?>
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th class="table-title">Product Name</th>
                                <th class="table-title">Product Code</th>
                                <th class="table-title">Unit Price</th>
                                <th class="table-title">In Stock</th>
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
                                        <?=anchor('product/'.$item['slug'], img(['src'=>content_url().'uploads/products/thumbs/180xx_'.$item['image'], 'alt'=>$item['name']]))?>
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
                                        <input type="number" name="qty[<?=$counter?>]" value="<?=$item['qty']?>" min="0" max="<?=$stock[$item['id']]?>"></div>
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
                                <td colspan="2"><b>Delivery Address :</b>  
                                
                                <?php
                                $deliveryAddress = $this->session->userdata('address');
                                if(count($deliveryAddress)>1){
                                    ?>
                                    <select name="data[orders][shipping_address_id]" class="form-control" id="shipping_address_id">    
                                    <?php
                                    
                                    foreach($deliveryAddress as $key=> $address){
                                        $checked = ($address['is_default']?'selected="selected"':'');
                                        echo '<option value="'.$address['id'].'"  '.$checked.'>'.$address['site_name'].':'.$deliveryAddress[0]['address_1'].', '.$deliveryAddress[0]['address_2'].', '.$deliveryAddress[0]['area_name'].', '.$deliveryAddress[0]['city_name'].' - '.$deliveryAddress[0]['pincode'].', '.$deliveryAddress[0]['state_name'].', '.$deliveryAddress[0]['country'].'</option>';
                                    }
                                    ?>
                                    </select>
                                    <?php
                                }else{
                                    echo form_hidden('data[orders][shipping_address_id]', $deliveryAddress[0]['id']);
                                    //print_r($deliveryAddress[0]);
                                    echo $deliveryAddress[0]['address_1'].',<br> '.$deliveryAddress[0]['address_2'].', <br>'.$deliveryAddress[0]['area_name'].', '.$deliveryAddress[0]['city_name'].' - '.$deliveryAddress[0]['pincode'].', <br>'.$deliveryAddress[0]['state_name'].', '.$deliveryAddress[0]['country'];
                                    
                                }
                                ?>
                            </td>
                                <td colspan="3">
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
                        <?=anchor(custom_constants::confirm_order, 'CONFIRM ORDER', ['class'=>'btn btn-custom confirm-order'])?>
                     
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.confirm-order').on('click', function(e){
        /*e.preventDefault();
        alert("hii");

        return false;*/
    })
</script>