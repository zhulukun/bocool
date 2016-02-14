<?php get_header(); ?>
<?php get_header('masthead'); ?>
<section id="leo-bodyer">
    <div class="container">
    <div class="row" id="top-main">
         <!-- page左侧导航菜单区域-->
        <div class="col-md-3 hidden-xs hidden-sm" id="page_menu_left">
           <?php 
                  if(function_exists('wp_nav_menu')) {
                    wp_nav_menu( array(
						'theme_location'    => 'page_menu'
					)	);
                  }
                ?>
        </div>
          <!-- page右侧内容显示区域 -->
        <div class="col-md-9" id="mainstay">
           <article class="leo-page-content">
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<h3 class="leo-page-title"><?php the_title(); ?></h3>
					 <?php the_content(); ?>
				<?php endwhile; ?>
			</article>
        </div>
      </div>
  </div>
</section>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>
