<?php if(count($sliders)>0){ ?>

<!-- basic-slider start -->
<div class="slider-section">
	<div class="slider-active owl-carousel">
		<?php foreach ($sliders as $key => $slide) { ?>
		<div class="single-slider" style="background-image: url(<?php echo content_url().'uploads/sliders/'.$slide['image']; ?>)">
			<div class="container">
				<div class="slider-content">
					<div class="slider-content hero-text text-VALUKA text-center">
						<h2><?=$slide['title_1']?></h2>
						<p><?=$slide['title_2']?></p>
						<?=anchor($slide['link'], 'Click Here', ['class'=>'btn btn-default'])?>
					</div>
				</div>
			</div>
		</div>
<?php } ?>
	</div>
</div>
	<!-- basic-slider end -->

<?php } ?>