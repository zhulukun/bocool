<?php
/**
*  Template Name:产品
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<section id="leo-bodyer">
  <div class="container">
    <div class="row" id="top-main">
      <!-- page左侧导航菜单区域-->
      <div class="col-xs-12 col-sm-12 col-md-12" id="page_menu_left">
        <?php
        if(function_exists('wp_nav_menu')) {
        wp_nav_menu(array( 'theme_location' => 'page_menu') );
        }
        ?>
      </div>
     
    </div>
    <div class="col-md-12" id="mainstay">
            <?php query_posts('showposts=4&category_name=mdt'); ?>
                      <div class="row" style="color:#000">

              <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-6" style="padding:0 50px;"><?php the_post_thumbnail(array(428,324));?></div><div class="col-md-6"><div class="second-content"><?php the_content(); ?></div><p class="second-title"><?php the_title(); ?></p></div>
             </div>
              <?php endwhile;?> <?php wp_reset_query(); ?>

          </div>
  </div>
</section>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>