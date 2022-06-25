
<?php if(!$this->cart->contents()):
    echo 'You don\'t have any items yet.';
else:
?>
 
<?php echo form_open('cart/update_cart'); ?>
<table width="100%" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <td>Qty</td>
            <td>Item Description</td>
            <td>Item Price</td>
            <td>Sub-Total</td>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach($this->cart->contents() as $items): ?>
         
        <?php echo form_hidden('rowid[]', $items['rowid']); ?>
        <tr <?php if($i&1){ echo 'class="alt"'; }?>>
            <td>
                <?php echo form_input(array('name' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?>
            </td>
             
            <td><?php echo $items['name']; ?></td>
             
            <td>&euro;<?php echo $this->cart->format_number($items['price']); ?></td>
            <td>&euro;<?php echo $this->cart->format_number($items['subtotal']); ?></td>
        </tr>
         
        <?php $i++; ?>
        <?php endforeach; ?>
         
        <tr>
            <td</td>
            <td></td>
            <td><strong>Total</strong></td>
            <td>&euro;<?php echo $this->cart->format_number($this->cart->total()); ?></td>
        </tr>
    </tbody>
</table>
 
<p><?php echo form_submit('', 'Update your Cart'); echo anchor('cart/empty_cart', 'Empty Cart', 'class="empty"');?></p>
<p><small>If the quantity is set to zero, the item will be removed from the cart.</small></p>
<?php 
echo form_close(); 
endif;
?>

<?php if(!$this->cart->contents()):
    echo 'You don\'t have any items yet.';
else:
?>

<?php echo form_open('cart/update_cart'); ?>
<table width="100%" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <td>Qty</td>
            <td>Item Description</td>
            <td>Item Price</td>
            <td>Sub-Total</td>
        </tr>
    </thead>

<tbody>
    <?php $i = 1; // Keep track of the amount of loops ?> 
    <?php foreach($this->cart->contents() as $items): // We break the cart contents into parts ?>
     
    <?php echo form_hidden('rowid[]', $items['rowid']); // We added an hidden field which contains a unique id in array format, this is needed in order to update ?>
    <tr <?php if($i&1){ echo 'class="alt"'; // If $i is odd, we add the class "alt" in order to change the background color }?>>
        <td>
            <?php echo form_input(array('name' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); // Here we created an input field with the name qty[] this allows us to interact with it as an array when its posted.?>
        </td>
         
        <td><?php echo $items['name']; // Display the item name ?></td>
         
        <td>&euro;<?php echo $this->cart->format_number($items['price']); // Display the item price ?></td>
        <td>&euro;<?php echo $this->cart->format_number($items['subtotal']); // Display subtotal ?></td>
    </tr>
     
    <?php $i++; // Add 1 to $i ?>
    <?php endforeach; // End the foreach ?>
     
    <tr>
        <td</td>
        <td></td>
        <td><strong>Total</strong></td>
        <td>&euro;<?php echo $this->cart->format_number($this->cart->total()); // Display the total amount ?></td>
    </tr>
</tbody>