
<?php 
//echo "<pre>";print_r($sliders);exit;
?>
<div id="slider-rev-container">
    <div id="slider-rev">
        <ul>
            <?php foreach ($sliders as $key => $slider) {
                    //print_r($slider);
                ?>
                 <li data-transition="fade" data-saveperformance="on" data-title="Men Collection">
                    <img src="<?=content_url()?>uploads/sliders/<?=$slider['image']?>" alt="<?=$slider['title_1']?>">
                    <div class="tp-caption lfb ltb" data-x="100" data-y="50" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                        <!--a href="#"><img src="<?=content_url()?>uploads/sliders/<?=$slider['image']?>" alt="slide2_1"></a-->
                    </div>
                    <div class="tp-caption rev-title skewfromleft stt" data-x="610" data-y="150" data-speed="800" data-start="900" data-easing="Power3.easeIn" data-endspeed="300"><?=$slider['title_1']?></div>
                    <div class="tp-caption rev-subtitle skewfromleft stt" data-x="610" data-y="205" data-speed="800" data-start="900" data-easing="Power3.easeIn" data-endspeed="300"><?=$slider['title_2']?></div>
                    <div class="tp-caption rev-text sfl stl" data-x="610" data-y="275" data-speed="800" data-start="1300" data-easing="Power3.easeIn" data-endspeed="300"><?=$slider['short_description']?></div>
                    <div class="tp-caption sfb stb" data-x="610" data-y="395" data-speed="1200" data-start="1800" data-easing="Power3.easeIn" data-endspeed="300"><?=anchor($slider['link'], 'Click Here', ['class'=>'btn btn-custom-2'])?></div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>