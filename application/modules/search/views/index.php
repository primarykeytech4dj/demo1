<div id="category-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><?=anchor('#', 'Home')?></li>
            <li class="active"><b>Search Parameter : </b><?=$param?></li>
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
              if(count($search)>0){
                foreach ($search as $key => $search) {               ?>
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="item item-hover cart-product">
                      <div class="item-image-wrapper">
                        <figure class="item-image-container">
                          <?php if($search['section']=='products'){ ?>
                            <?=anchor('product/'.$search['slug'], img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/'.$search['thumbnail_image'], 'alt'=>$search['title'], 'class'=>'lazy item-image', 'style'=>'max-height:300px']).img(['src'=>content_url().'uploads/products/progress.gif', 'data-src'=>content_url().'uploads/products/'.$search['thumbnail_image'], 'alt'=>$search['title'], 'class'=>'lazy item-image-hover', 'style'=>'max-height:300px']))?>
                          <?php }elseif($search['section']=='product_categories'){

                          } ?>
                        </figure>
                        
                      </div>
                      <div class="item-meta-container">
                        <h3 class="item-name">
                          <?=anchor('product/'.$search['slug'], $search['title'])?></h3>
                        <p class=""></p>
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
        </div>
        <aside class="col-md-3 col-sm-4 col-xs-12 sidebar">
            <?=$this->load->view('templates/venedor/left_side_bar')?>
        </aside>
      </div>
    </div>
  </div>
</div>