<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php //echo Modules::run("templates/default_breadcrumb", array()); ?>

<!-- <?php '<pre>';print_r($blogs['title']);'</pre>'; ?> -->
<div class="main">
    <div id="t3-mainbody" class="container t3-mainbody mainbody-magazine">
        <div class="row equal-height">
            <div id="t3-content" class="col t3-content col-md-12">
                <div class="item-row row-main">
                    <div class="article-main">
                        <article class="article" itemscope itemtype="http://schema.org/Article">
                            <?php //echo Modules::run("templates/default_pagination", array('previous'=>$previous, 'next'=>$next)); ?>
                            <header class="article-header clearfix">
                                <h1 class="article-title" itemprop="headline">
                                    <?php echo $blogs['title'];?>        
                                    <meta itemprop="url" content="http://ja-teline-v.demo.joomlart.com/index.php/en/world/21-middle-east/234-ft.-trace-falling-nixon-recovery-sacred" />
                                 </h1>

            
                            </header>
                            <aside class="article-aside article-aside-full">
                             <dl class="article-info muted">        
                                <dt class="article-info-term"></dt>
                                    <dd class="hidden"></dd>
                                   
                                    <dd class="published hasTooltip" title="Published: ">
                                     <i class="icon-calendar"></i>
                                         <time datetime="2017-10-11T03:23:03+00:00">
                                            <?php echo date('d F,y', strtotime($blogs['published_on'])); ?>         
                                            <meta  itemprop="datePublished" content="2017-10-11T03:23:03+00:00" />
                                            <meta  itemprop="dateModified" content="2017-10-11T03:23:03+00:00" />
                                        </time>
                                    </dd>  
                        </dl>
            
                    </aside>
                    <section class="article-intro-media">
                             <!-- <section class="article-full has-article-tools" style="padding-left: 0;">  -->
                            	<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="https://google.com/article" />
	                            <meta itemprop="inLanguage" content="en-GB" />
	                            <meta itemprop="url" content="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" />
                                
                                <!-- <span class="media-mask pull-right"><?php //echo $this->pktlib->social_media_button($blogs); ?></span> -->
                                <div class="article-content-main">
                                	<div class="pull-left item-image">
                                        <?php if(NULL!==$blogs['city']){ ?>
                                    <span class="category-name cat-red" title="Category: "><a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" ><span itemprop="genre"><?php echo $blogs['city'];?></span></a>
                                    </span>
                                <?php } ?> 
                                        <a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" title="<?php echo $blogs['title']; ?>">

                                          <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                            <?php if(!empty($blogs['featured_image'])){ ?>

                                           <img class="caption" title="<?php echo $blogs['title']; ?>" src="<?php echo !empty($blogs['featured_image'])?content_url('uploads/blogs/'.$blogs['featured_image']):content_url('uploads/blogs/default.jpg'); ?>" alt="<?php echo $blogs['title']; ?>" itemprop="url" style="max-width: 1200px"/>
                                         <meta itemprop="height" content="auto" />
                                         <meta itemprop="width" content="auto" />
                                            <?php } ?>
                                        </span>

                                      </a>
                                    
                                    <p class="img-caption"><?php echo $blogs['title'];?></p>
                                    </div>
                                     Read Count: <?php echo $blogs['read_count']; ?>
                                     <span class="media-mask pull-right"><?php echo $this->pktlib->social_share_button($blogs, base_url().'blogs/view/'.$blogs['slug']); ?></span>
                                        <!-- <?php if(NULL!==$blogs['city']){ ?>
                                    <span class="category-name cat-red" title="Category: "><a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" ><span itemprop="genre"><?php echo $blogs['city'];?></span></a>
                                    </span> 
                                <?php } ?> --> 
                              <!-- </section><section> -->
                                        <?php echo Modules::run("templates/default_pagination", array('previous'=>$previous, 'next'=>$next)); ?>
                                    <!-- <?php if(NULL!==$blogs['city']){ ?>
                                    <span class="category-name cat-red" title="Category: "><a href="<?php echo base_url('blogs/view/'.$blogs['slug']); ?>" ><span itemprop="genre"><?php echo $blogs['city'];?></span></a>
                                    </span>
                                <?php } ?> -->
                                    <blockquote class="article-intro" itemprop="description">
                                        <p><?php echo $blogs['short_description']; ?></p>
                                    </blockquote>
                                    <section class="article-content" itemprop="articleBody">
                                        <?php echo $blogs['content']; ?>
                                    </section>
                                </div>
                            </section>

                            <?php echo Modules::run("templates/default_pagination", array('previous'=>$previous, 'next'=>$next)); ?>
                        </article>
                    </div>
                </div>
                <div class="row magazine-list list-view equal-height">
                    <div class="col col-md-12 magazine-categories">
                        <div class="magazine-section-heading">
                            <h4>Related blogs</h4>
                            <!-- <div class="magazine-section-tools">
                                <label>View as:</label>
                                <a href="#" class="btn" title="Grid View" data-action="switchClass" data-target=".magazine-list"
                                data-value="grid-view" data-key="acm117" data-default="1"><i class="fa fa-th-large"></i> Grid</a>
                                <a href="#" class="btn" title="List View" data-action="switchClass" data-target=".magazine-list"
                                data-value="list-view" data-key="acm117"><i class="fa fa-list"></i> List</a>
                            </div> -->
                        </div>
                        <?php
                        echo $relatedBlogs;
                            /*$query = Modules::run('blogs/get_blogs_details', $blogs['blogs_category_id']);
                            //print_r($query);
                            if(count($query)>0){
                                foreach ($query as $key => $v) {
                                  //echo $v['slug'];
                                  echo Modules::run('blogs/related_blogs', $v['slug']);
                                } 
                            }*/
                        ?>
                    </div>
                </div>
            </div>

            <!-- <div class="col t3-sidebar t3-sidebar-right col-md-3">
                <?php echo Modules::run("templates/default_inner_sidebar_right", array()); ?>
            </div> -->
        </div>
    </div>
</div>

<?php /* ?>
<div id="t3-mainbody" class="container t3-mainbody">
    <!-- Main content -->
    <!-- <section class="content"> -->
      <div class="row content">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <div class="box pages">

                <h1 class="box-title text-center"><?php echo $blogs['title']; ?></h1>
            <?php if(!empty($blogs['featured_image'])){ 
            	echo img(content_url().'uploads/blogs/'.$blogs['featured_image']);
              //echo '<pre>';print_r($blogs);echo '</pre>';
              ?>
              
              <!--div class="box-header" style="border: none;
              padding: 25px;
              background-image: url('<?php echo content_url().'uploads/blogs/'.$blogs['featured_image']; ?>'); background-repeat: no-repeat; background-size: 100% 100%;padding-left: 11em; padding-right: 20em; padding-bottom: 5em; height: auto;">

              </div-->
              <?php }?>
                <h3 class="box-title"><?php echo $blogs['short_description']; ?></h3>
                Read Count: <?php echo $blogs['read_count']; ?>
            <!-- /.box-header -->
            <div class="box-body">
              <?php echo $blogs['content']; ?>
               <?php echo $blogs['content']; ?>
              <?php $query = Modules::run('blogs/get_blogs_details', $blogs['blogs_category_id']) ;
             //echo count($query);?>
             <?php if(count($query>0)){ ?>
              <h1>Related blogs</h1>
                <?php foreach ($query as $key => $v) {
                  echo Modules::run('blogs/category_wise_blogs', $v['slug']);
                  } 
                }?>
              

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-1">
        </div>
        <!-- /.col -->
      </div>
</div>
      <!-- /.row -->
    <!-- </section> -->
    <!-- /.content -->
    <?php */ ?>