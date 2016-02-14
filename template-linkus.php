<?php
/**
*  Template Name:联系我们
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<style type="text/css">
  #container{height:300px;overflow: visible;}    
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=0GXdHQQsE8O00D51GNtMzhAo"></script>

<section id="leo-bodyer">
  <div class="container">
    <div class="row" id="top-main">

      <!-- page右侧内容显示区域 -->
      <div class="col-md-12" id="mainstay">
        <article class="leo-page-content">
          <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
            <?php the_content(); ?>
          <?php endwhile; ?>
         
        </article>
      </div>
    </div>
  </div>
</section>

<?php get_footer('colophon'); ?>
<?php get_footer(); ?>