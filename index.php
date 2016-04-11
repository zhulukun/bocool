<?php get_header(); ?>
    
<?php get_header('masthead'); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css" media="screen" type="text/css" />
<!--显示在小型设备-->
<div class="container hidden-md hidden-lg visible-xs visible-sm" style="background-color:#FFFFFF;padding-top:30px;padding-bottom:30px;text-align:center">
  <div class="row">
    <div class="col-sm-6 col-xs-6" style="text-align:center"><a href="http://www.bocool.net/?page_id=62"><div class="div-border">首页</div></a></div>
    <div class="col-sm-6 col-xs-6" style="text-align:center"><a href="http://www.bocool.net/?page_id=13"><div class="div-border">产品</div></a></div>
  </div>
  <div class="row" style="margin-top:30px;margin-bottom:50px;">
    <div class="col-sm-6 col-xs-6" style="text-align:center"><a href="http://www.bocool.net/?page_id=47"><div class="div-border">案例</div></a></div>
    <div class="col-sm-6 col-xs-6" style="text-align:center"><a href="http://www.bocool.net/?page_id=125"><div class="div-border">关于我们</div></a></div>
  </div>
   <p style="font-size:24px;font-weight:800;color:#31708f">体验评价&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp数据至上</p>

</div>
<div class="scroll_page hidden-xs hidden-sm">
    <div class="page first">
      <div style="margin-top:350px;">
        <div class="content" style="margin-top:350px">
          <p style="font-size:42px;font-weight:800;">体验评价&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp数据至上</p>
        </div>
      </div>
        <div class="row news" style="display: block;">
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
        <div class="content" style="margin-top:67px;height:100%;width:100%;background-image:url(<?php echo get_template_directory_uri(); ?>/images/bg.png);background-repeat:repeat-x">

           <div class="row">
              <?php query_posts('showposts=1&category_name=index-2'); ?>
              <?php while (have_posts()) : the_post(); ?>
              <div class="col-md-4" style="font-weight:300;font-size:16px;text-align:center;margin-top:250px;padding:0px 0px 0px 150px"><?php the_content();?>
              </div>
              <div class="col-md-8" style="padding:0px 150px 0px 0px;text-align:left"><?php the_post_thumbnail('full');?></div>
              <?php endwhile;?> <?php wp_reset_query(); ?>
              
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
