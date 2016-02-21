<?php get_header(); ?>
    
<?php get_header('masthead'); ?>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css" media="screen" type="text/css" />
<div class="scroll_page">
    <div class="page first">
      <div>
        <div class="content">
          <?php query_posts('showposts=1&category_name=index-1'); ?>
<?php while (have_posts()) : the_post(); ?>
          <p style="font-size:36px;font-weight:800;margin-bottom:50px;"><?php the_title(); ?>
</p>
<p><?php the_content(); ?></p>
          <?php endwhile;?> <?php wp_reset_query(); ?>

        </div>
      </div>
        <div class="row news hidden-xs hidden-sm" style="display: block;">
           <!--    <div class="row">
            <div class="col-md-12 hidden-xs hidden-sm" id="qiniu-news">
              <span class="icon icon-sound"></span>
              <ul class="news-list">
              
                  <li style="display: none;"><a href="#" target="_blank">三丰智合bocool</a></li>
              
                  <li style="display: none;"><a href="#" title="「七牛架构师实践日」这里只谈架构" target="_blank">这里只谈架构</a></li>
              
                  <li style="display: list-item;"><a href="#" title="「青葱创业计划」国内第一个针对大学生创业的技术扶持计划" target="_blank">国内第一个针对大学生创业的技术扶持计划</a></li>
              
              </ul>
              <ul class="news-icon" id="li-bottom">
                
                  
                    <li class="active"></li>
                  
                
                  
                    <li class=""></li>
                  
                
                  
                    <li class=""></li>
                  
                
              </ul>
            </div>
          </div> -->
        </div>

        <!-- <div class="control">
            <a href="#" class="next">Next</a>
        </div> -->

    </div>
   
    <div class="page second">
        <div class="content">
           

          <div class="row" style="color:#000">
            <p>敬请期待</p>

          </div>

        </div>

       <!--  <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div> -->
    </div>
    <div class="page third">
       <div class="content">
           

          <div class="row" style="color:#000">
            <?php query_posts('showposts=3&category_name=index-3'); ?>
              <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-4" style="padding:0 50px;"><p><?php the_post_thumbnail(array(200,200));?> </p><p class="second-title"><?php the_title(); ?></p><div class="second-content"><?php the_content(); ?></div></div>
              <?php endwhile;?> <?php wp_reset_query(); ?>

          </div>

        </div>
       <!--  <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div> -->
  
</div>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mousewheel.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.scrollpage.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/index.js"></script>
     <script src='<?php echo get_template_directory_uri(); ?>/js/prettify.js'></script>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>
