<?php
/**
*  Template Name:关于我们
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=0GXdHQQsE8O00D51GNtMzhAo"></script>

<?php

?>
<style type="text/css">
.aboutus-banner {
    background-image: url("<?php echo get_template_directory_uri(); ?>/images/banner_bg.png");
    height: 500px;
    line-height: 500px;
}
  #container{height:300px;overflow: visible;}    
  #leo-bodyer{margin-top: 0px;};
</style>

<!-- 特色图片展示 -->
  <div class="aboutus-banner">
    
  </div>
<section id="leo-bodyer">
  <div class="container">
    <div class="row" id="top-main">

      <!-- page右侧内容显示区域 -->
      <div class="col-md-12" id="mainstay">
        <article class="about-content">
          <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
<!--           <h3 class="leo-page-title"><?php the_title(); ?></h3>
 -->          <?php the_content(); ?>
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
<script type="text/javascript"> 
var map = new BMap.Map("container");    
var point = new BMap.Point(116.486859,40.018624);    
map.centerAndZoom(point, 15);    
window.setTimeout(function(){  
    map.panTo(new BMap.Point(116.486859,40.018624));    
}, 2000);
var marker = new BMap.Marker(point);        // 创建标注    
map.addOverlay(marker);                     // 将标注添加到地图中
</script>  
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>