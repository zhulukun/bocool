<section class="post-block clearfix" id="home-page">
    <p class="post-meta visible-xs">
      <span class="pull-right"> <?php  echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ); ?>前</span>
      <span class="pull-right"> <?php the_author_posts_link(); ?></span>
    </p>
   <div class="post-feature index-post-feature">
      <figure class="thumbnail">
        <a href="<?php the_permalink() ?>" target="_blank">
          <?php the_post_thumbnail( 'medium' ); ?>
          <?php if(get_post_format()=="video" ) { ?>
            <div class="link-overlay fa fa-play"></div>
          <?php } ?>
        </a>
       <?php if(in_category("case")) {?>
          <?php  $cat=get_category_by_slug('case'); //获取分类别名为girls(火星少女)的分类数据   ?>
           <div class="post-is-in-catogary home post-in-0-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
                <?php echo $cat->name; ?>
             </a>
           </div>
          <?php } else if(in_category("video")) {?>
          <?php  $cat=get_category_by_slug('video'); //获取分类别名为video(火星TV)的分类数据   ?>
           <div class="post-is-in-catogary home post-in-1-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
                <?php echo $cat->name; ?>
             </a>
           </div>
          <?php } else if(in_category("ways")) {?>
          <?php  $cat=get_category_by_slug('ways'); //获取分类别名为insider(业内)的分类数据   ?>
           <div class="post-is-in-catogary home post-in-2-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
               <?php echo $cat->name; ?>
             </a>
           </div>
        <?php } else if(in_category("trend")) { ?>
          <?php  $cat=get_category_by_slug('trend'); //获取分类别名为test(测评)的分类数据   ?>
           <div class="post-is-in-catogary home post-in-3-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
               <?php echo $cat->name; ?>
            </a>
           </div>

       <?php } else if(in_category("other")) {?>
          <?php  $cat=get_category_by_slug('other'); //获取默认分类别名的分类数据   ?>
           <div class="post-is-in-catogary home">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
                <?php echo $cat->name; ?>
             </a>
           </div>
       <?php } ?>

      </figure>
    </div> <!-- post-feature is end -->
    <div class="home-post-content-area">
      <div class="home-post-title">
         <a href="<?php the_permalink() ?>"  target="_blank"><strong><?php the_title(); ?></strong></a>

      </div>

       <!-- 文章摘要信息 -->
      <div class="hidden-xs index-describe">
            <?php if ( !empty($post->post_excerpt) ) { ?>
              <?php echo mb_strimwidth( strip_tags( apply_filters( 'the_excerpt', $post->post_excerpt ) ), 0, 130,"..." ); ?>
            <?php } else {?>
              <?php echo mb_strimwidth( strip_tags( apply_filters( 'the_content', $post->post_content ) ), 0, 130,"..." ); ?>
            <?php } ?>
            <?php edit_post_link('编辑', '<span class="edit-post-link">', '</span>'); ?>

      </div>
      <p class="hidden-xs pull-right" style="color: #A9A9A9;">
            <span><?php  the_time( 'm月j日, Y' ); ?></span>
      </p>
    </div>
</section>

