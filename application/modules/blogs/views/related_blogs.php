<?php foreach ($category as $categoryKey => $categoryValue) { 
                        /*echo '<pre>';
                        print_r($section);
                        echo '</pre>'; */
                        ?>
                     <div class="magazine-category">
                        <div class="magazine-category-title cat-red">
                           <h2><a href="#"
                              title="World">
                              <?php echo $categoryKey;?></a>
                           </h2>
                        </div>
                        <div class="row-articles equal-height">
                        <?php 
                        $counter = 1;
                        foreach ($categoryValue as $catgoryValueKey => $blogs) {
                           //print_r($blogs);
                           //print_r($blogs $blogs['slug'];
                           ?>
                          
                           <div class="col col-xs-12 col-sm-4 magazine-item">
                              <div class="magazine-item-media">
                                 <!-- <span class="media-mask pull-right"><?php //echo $this->pktlib->social_media_button($blogs); ?></span> -->
                                 <div class="pull-left item-image">
                                    <a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>">
                                       <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                          <img class="caption" title="<?php echo $blogs['title'];?>" src="<?php echo content_url(); ?>uploads/blogs/thumbs/<?php echo (!empty($blogs['featured_image']))?$blogs['featured_image']:'default.jpg';?>" alt="<?php echo $blogs['title'];?>" itemprop="url"/>
                                          <meta itemprop="height" content="auto" />
                                          <meta itemprop="width" content="auto" />
                                       </span>
                                    </a>
                                    <p class="img-caption"><?php echo $blogs['title'];?></p>
                                 </div>
                                 <?php if($blogs['city']){ ?>
                                 <span class="category-name  cat-red" title="Category: <?php echo $blogs['city'];?>">
                                 <a href="#" ><span itemprop="genre"><?php echo $blogs['city'];?></span></a>			</span>	
                                 <?php  } ?>
                              </div>
                              <div class="magazine-item-main">
                                 <div class="article-title">
                                    <h3 itemprop="name">
                                       <a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" itemprop="url">
                                       <?php echo $blogs['title'];?></a>
                                    </h3>
                                 </div>
                                 <aside class="article-aside clearfix">
                                    <dl class="article-info muted">
                                       <dt class="article-info-term">
                                          Details							
                                       </dt>
                                       <dd class="hidden"></dd>
                                       <dd class="createdby hasTooltip" itemprop="author" title="Written by ">
                                          <?php echo anchor('blogs/user/'.$blogs['user_id'], '<span itemprop="name">'.$blogs['user'].'</span>', ['itemprop'=>'url']); ?>
                                       </dd>

                                       <dd class="published hasTooltip" title="Published: ">
                                          <i class="icon-calendar"></i>
                                          <time datetime="<?php echo date('d F y H:i:s', strtotime($blogs['published_on']));?>">
                                             <?php echo date('d F y H:i:s', strtotime($blogs['published_on']));?>          
                                             <meta  itemprop="datePublished" content="<?php echo date('d F y H:i:s', strtotime($blogs['published_on']));?>" />
                                             <meta  itemprop="dateModified" content="<?php echo date('d F y H:i:s', strtotime($blogs['modified']));?>" />
                                          </time>
                                       </dd>
                                    </dl>
                                 </aside>
                                 <div class="magazine-item-ct">
                                    <p><?php echo $blogs['short_description'];?></p>
                                 </div>
                                 <div class="magazine-item-ct">
                                    <span class="media-mask pull-right"><?php echo $this->pktlib->social_share_button($blogs, base_url().'blogs/view/'.$blogs['slug']); ?></span>
                                 </div>
                              </div>
                           </div>
                           


                        <?php
                        if($counter%3==0) {
                           ?>
                        </div>
                        <div class="row-articles equal-height">

                       <?php }
                        $counter = $counter+1;
                         } ?>
                        </div>
                        
                     </div>
                     <?php } ?>