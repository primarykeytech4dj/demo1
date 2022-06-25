<?php if(count($featuredProduct)>0){ ?>

    <ul class="featured-list clearfix">
        <li>
            <?php foreach ($featuredProduct as $key => $product) {
                ?>
            <div class="featured-product clearfix">
                <figure><img src="<?=content_url()?>uploads/products/thumbs/115xx_<?=$product['image_name_1']?>" alt="<?=$product['product']?>"></figure>
                <h5><?=anchor('product/'.$product['slug'], $product['product']);?></h5>
                <div class="ratings-container">
                    <div class="ratings">
                        <div class="ratings-result" data-result="84"></div>
                    </div>
                </div>
                <div class="featured-price pull-right"><?=$product['base_price']?></div>
            </div>

               <?php 
               if(($key+1)%3==0){
                    echo '</li><li>';
                }
            } ?>
            
        </li>
    </ul>

<?php } ?>