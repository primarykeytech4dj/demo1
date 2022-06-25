<div id="t3-mainbody" class="container t3-mainbody">
   <div class="row magazine-list list-view equal-height">
    <div class="col col-md-9 magazine-categories">
                     <div class="magazine-section-heading">
                        <h4>Blogs</h4>
                        <div class="magazine-section-tools">
                           <label>View as:</label>
                           <a href="#" class="btn" title="Grid View" data-action="switchClass" data-target=".magazine-list"
                              data-value="grid-view" data-key="acm117" data-default="1"><i class="fa fa-th-large"></i> Grid</a>
                           <a href="#" class="btn" title="List View" data-action="switchClass" data-target=".magazine-list"
                              data-value="list-view" data-key="acm117"><i class="fa fa-list"></i> List</a>
                        </div>
                     </div>
                  <!-- MAGAZINE LISTING -->
                  <div class="col col-md-12 magazine-categories">
                  <?php if(count($category)>0){
                     foreach ($category as $categoryKey => $categoryValue) { 
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
                           ?>
                          
                           <div class="col col-xs-12 col-sm-4 magazine-item">
                              <div class="magazine-item-media">
                                 <span class="media-mask pull-right"><?php echo $this->pktlib->social_share_button($blogs, base_url().'blogs/view/'.$blogs['slug']); ?></span>
                                 <div class="pull-left item-image">
                                    <a href="<?php echo base_url(); ?>blogs/view/<?php echo $blogs['slug']; ?>" title="<?php echo $blogs['title'] ?>">
                                       <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                          <img class="caption" title="<?php echo $blogs['title'];?>" src="<?php echo content_url(); ?>uploads/blogs/<?php echo (!empty($blogs['featured_image']))?$blogs['featured_image']:'default.jpg';?>" alt="<?php echo $blogs['title'];?>" itemprop="url"/>
                                          <meta itemprop="height" content="auto" />
                                          <meta itemprop="width" content="auto" />
                                       </span>
                                    </a>
                                    <p class="img-caption"><?php echo $blogs['title'];?></p>
                                 </div>
                                 <?php if($blogs['city']){ ?>
                                 <span class="category-name  cat-red" title="<?php echo $blogs['state_name'] ?>: <?php echo $blogs['city'];?>">
                                 <a href="#" ><span itemprop="genre"><?php echo $blogs['city'];?></span></a>			</span>	
                                 <?php  } ?>
                              </div>
                              <div class="magazine-item-main">
                                 <div class="article-title">
                                    <h3 itemprop="name">
                                       <a href="<?php echo base_url(); ?>blogs/view/<?php echo $blogs['slug']; ?>" title="<?php echo $blogs['title'] ?>" itemprop="url">
                                       <?php echo $blogs['title'];?></a>
                                    </h3>
                                 </div>
                                 <aside class="article-aside clearfix">
                                    <dl class="article-info muted">
                                       <dt class="article-info-term">
                                          Details							
                                       </dt>
                                       <dd class="hidden"></dd>
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
                     <?php }
                     }else{
                        ?>
                        <!-- <div class="col col-md-12 magazine-categories">
                           <div class="magazine-category">
                              No Data Found
                           </div>
                        </div> -->
                        <?php
                     }

                      ?>
                     
                     
                  </div>
               </div>
               <div class="col t3-sidebar t3-sidebar-right col-md-3">
                <?php echo Modules::run("templates/default_inner_sidebar_right", array()); ?>
            </div>
        </div>
    </div>