<?php
/**
*  Template Name:团队介绍
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<section id="leo-bodyer">
  <div class="container">
    <div class="row" id="top-main">
      <!-- page左侧导航菜单区域-->
      <div class="col-md-3 hidden-xs hidden-sm" id="page_menu_left">
        <?php
        if(function_exists('wp_nav_menu')) {
        wp_nav_menu(array( 'theme_location' => 'page_menu') );
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
          <!-- 团队介绍 -->
          <?php
          $team_posts = get_posts( array(
          'post_type' => 'team',
          'posts_per_page' => -1, // Unlimited posts
          'orderby' => 'date', // Order alphabetically by name
          'order'  =>  'asc'
          ) );
          ?>
          <div class="team-introduce">
            <div class="row">
              <?php if ( $team_posts ): ?>
              <?php
              foreach ( $team_posts as $post ):
              setup_postdata($post);
              
              // Resize and CDNize thumbnails using Automattic Photon service
              $thumb_src = null;
              if ( has_post_thumbnail($post->ID) ) {
              $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
              $thumb_src = $src[0];
              }
              ?>
              <article class="col-md-3 col-sm-6 col-xs-6">
                <div class="profile">
                  <div class="profile-header">
                    <?php if ( $thumb_src ): ?>
                    <img src="<?php echo $thumb_src; ?>" alt="<?php the_title(); ?>" class="profile-img">
                    <?php endif; ?>
                  </div>
                  <h4 class="profile-name"><?php the_title(); ?></h4>
                  <div class="profile-intro"><?php the_content(); ?></div>
                </div>
              </article>
              <?php endforeach; ?>
              <?php endif; ?>
              
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>