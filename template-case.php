<?php
/**
*  
*Template Name:案例
*/
?>

<?php get_header(); ?>
<?php get_header('masthead'); ?>

<?php if ( function_exists( 'meteor_slideshow' ) ) { meteor_slideshow(); } ?>
<div style="margin-top:60px;">
            <?php query_posts('showposts=20&category_name=case'); ?>
              <?php while (have_posts()) : the_post(); ?>
                 <div class="row" style="color:#000">
                    <div class="col-md-12">
                        <div class="col-md-4" style="padding:0 50px;"><?php the_post_thumbnail(array(145,50));?></div>
                        <div class="col-md-8 second-content"><?php the_content(); ?></div>
                    </div>
                 </div>

              <?php endwhile;?> <?php wp_reset_query(); ?>

</div>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>
