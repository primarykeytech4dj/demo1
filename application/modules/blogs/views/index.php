<div class="breadcrumb-2-area pos-relative bg-2 bg-black-alfa-40">
	<div class="hero-caption">
		<div class="hero-text">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center">
						<h1 class="text-uppercase color-VALUKA breadcrumb-2">What's New</h1>
						<p class="lead color-VALUKA ">Latest news in town</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- basic-blog-area -->
<div class="basic-blo-area gray-bg pb-80">
	<div class="container">
		<div class="row blog-top">
			<?php foreach ($blogs as $key => $value) {
          ?>
          	<div class="col-md-4 blog-item mb-40">
				<div class="blog-wrapper blog-column">
					<div class="blog-thumb">
						<?php echo anchor('blogs/view/'.$value['slug'], img(['src'=>content_url().'uploads/blogs/'.$value['featured_image'], 'alt'=>$value['title']])); ?>
					</div>
					<div class="blog-content">
						<h2 class="blog-title"><?=anchor('blogs/view/'.$value['slug'], $value['title'])?></h2>
						<p style="text-align: justify;"><?=word_limiter($value['short_description'], 20)?></p>
					</div>
					<div class="meta-info">
						<ul>
							<li class="posts-author">by <a title="Author" href="#"><?=$value['user']?></a></li>
							<li class="posts-time"><?=date('F d, Y', strtotime($value['published_on']))?></li>
							<li class="comments-count"><?=$this->pktlib->social_share_button($value, base_url().'blogs/view/'.$value['slug']); ?></li>
						</ul>
					</div>
				</div>
			</div>
        <?php
       } ?>
		</div>
	</div>
</div>
<!-- basic-blog-area end -->