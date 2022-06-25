<button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
    <span class="cart-menu-icon"></span> <?=$this->cart->total_items()?> item(s)
    <span class="drop-price">- <i class="fa fa-rupee"></i> <?=$this->cart->format_number($this->cart->total())?></span>
</button>
<div class="dropdown-menu dropdown-cart-menu pull-right clearfix" role="menu">

<?php 
$total = $this->cart->total();
if(!$this->cart->contents()):
        echo 'No Items in Cart';
    else: ?>
<p class="dropdown-cart-description">Recently added item(s).</p>
<ul class="dropdown-cart-product-list" style="max-height: 300px; overflow-x: auto; overflow-y: auto">
    <?php foreach ($this->cart->contents() as $key => $item) {
        //$total = $total+($item['price']*$item['qty']);
     ?>
    <li class="item clearfix">
        <?=anchor('cart', '<i class="fa fa-pencil"></i>', ['class'=>'edit-item'])?>
        <?=anchor('#', '<i class="fa fa-times"></i>', ['class'=>'delete-item', 'title'=>'Remove item', 'data-id'=>$item['rowid']])?>
        <figure>
            <?=anchor('product/'.$item['slug'], img(['src'=>content_url().'uploads/products/'.$item['image'], 'alt'=>$item['name']]))?>
        </figure>
        <div class="dropdown-cart-details">
            <p class="item-name">
                 <?=anchor('product/'.$item['slug'], $item['name'])?>
            </p>
            <p><?=$item['qty']?>x
                <span class="item-price"> <i class="fa fa-rupee"></i>  <?=$item['price']?></span>
            </p>
        </div>
    </li>

    <?php } ?>
    
</ul>
<ul class="dropdown-cart-total" style="text-align: left">
    <li><span class="dropdown-cart-total-title">SubTotal:</span> <i class="fa fa-rupee"></i>  <?=number_format($this->cart->total(), 2)?></li>
    <?php 
    if(isset($otherCharges)){
        foreach ($otherCharges as $key => $charge) {
            
            $total = $total+$charge['cost'];
            ?>
            <li><span class="dropdown-cart-total-title"><?=ucfirst($key)?>:</span><i class="fa fa-rupee"></i>  <?=$this->cart->format_number($charge['cost'])?></li>
        <?php
        }
    } ?>
    <li><span class="dropdown-cart-total-title">Total:</span> <i class="fa fa-rupee"></i>  <?=number_format($total, 2)?></li>
</ul>
<div class="dropdown-cart-action">
    <p><?=anchor('cart', 'Cart', ['class'=>'btn btn-custom-2 btn-block'])?></p>
    <p><?=anchor('checkout', 'Checkout', ['class'=>'btn btn-custom btn-block'])?></p>
</div>
<?php endif; ?>
</div>