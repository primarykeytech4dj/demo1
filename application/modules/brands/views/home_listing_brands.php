<div class="lg-margin"></div>
                        <div id="brand-slider-container" class="carousel-wrapper">
                            <header class="content-title">
                                <div class="title-bg">
                                    <h2 class="title">Manufacturers</h2></div>
                            </header>
                            <div class="carousel-controls">
                                <div id="brand-slider-prev" class="carousel-btn carousel-btn-prev"></div>
                                <div id="brand-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>
                            </div>
                            <div class="sm-margin"></div>
                            <div class="row">
                                <div class="brand-slider owl-carousel">
                                    <?php foreach($brandProduct as $key => $brand){ ?>
                                    <a href="#"><img src="<?=content_url()?>uploads/brands/thumbs/165x124_<?=$brand['brand_logo']?>" alt="<?=$brand['text']?>"></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>