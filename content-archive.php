<article class="<?php  if (is_author()) {echo "col-md-4";} else {echo "col-md-6";} ?>  col-sm-6">
<section class="post-block clearfix">
    <!-- <div class="post-mobile-title visible-xs">
      <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
    </div> --> 
    <p class="post-meta visible-xs">
      
      <span class="pull-right"> <?php  echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ); ?>前</span>
      <span class="pull-right"> <?php the_author_posts_link(); ?></span>
  
    </p> 
   <div class="post-feature"> 
      <figure class="thumbnail">
        <a href="<?php the_permalink() ?>" target="_blank">
          <?php the_post_thumbnail( 'medium' ); ?>   
          <?php if(get_post_format()=="video" ) { ?>
            <div class="link-overlay fa fa-play"></div>
          <?php } ?>
        </a>
       <?php if(in_category("case")) {?>
          <?php  $cat=get_category_by_slug('case'); //获取分类别名为girls(火星少女)的分类数据   ?> 
           <div class="post-is-in-catogary post-in-0-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>"> 
                <?php echo $cat->name; ?>  
             </a>  
           </div>
          <?php } else if(in_category("video")) {?>
          <?php  $cat=get_category_by_slug('video'); //获取分类别名为video(火星TV)的分类数据   ?> 
           <div class="post-is-in-catogary post-in-1-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>"> 
                <?php echo $cat->name; ?>   
             </a> 
           </div> 
          <?php } else if(in_category("ways")) {?>
          <?php  $cat=get_category_by_slug('ways'); //获取分类别名为insider(业内)的分类数据   ?> 
           <div class="post-is-in-catogary post-in-2-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>"> 
               <?php echo $cat->name; ?>  
             </a> 
           </div>         
        <?php } else if(in_category("trend")) { ?>
          <?php  $cat=get_category_by_slug('trend'); //获取分类别名为test(测评)的分类数据   ?> 
           <div class="post-is-in-catogary post-in-3-cate">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>"> 
               <?php echo $cat->name; ?>   
            </a>  
           </div>
      
       <?php } else if(in_category("other")) {?>
          <?php  $cat=get_category_by_slug('other'); //获取默认分类别名的分类数据   ?> 
           <div class="post-is-in-catogary">
             <a title="<?php echo $cat->name; ?>" href="<?php echo get_category_link($cat->term_id); ?>"> 
                <?php echo $cat->name; ?>  
             </a>  
           </div>
       <?php } ?>
        <div class="post-title"><a href="<?php the_permalink() ?>"  target="_blank"><strong><?php the_title(); ?></strong></a></div> 
      </figure>   
    </div> <!-- post-feature is end -->      
    <!-- 文章meta信息 -->
    <div class="post-feature-bottom">
    <div class="post-meta hidden-xs">
      <span class="post-author-avatar pull-left"><?php echo dmeng_get_avatar(  get_the_author_meta( 'ID' ) , '40' , dmeng_get_avatar_type( get_the_author_meta( 'ID' ) ) ); ?></span>
      <span class="pull-left"> <?php the_author_posts_link(); ?></span>
      <span class="pull-right"> <?php  the_time( 'm月j日, Y' ); ?></span>
      <!-- <span><i class="fa fa-eye"></i> <?php //if( function_exists( 'the_views' ) ) { the_views(); print '次'; } ?></span> -->
      <!-- <span><i class="fa fa-comment"></i> <a href="<?php the_permalink() ?>#comments"><?php comments_number( '0', '1', '%' ); ?></a></span> -->   
    </div>    
     <!-- 文章摘要信息 -->
    <div class="well hidden-xs">
          <?php if ( !empty($post->post_excerpt) ) { ?>
            <?php echo mb_strimwidth( strip_tags( apply_filters( 'the_excerpt', $post->post_excerpt ) ), 0, 130,"..." ); ?>
          <?php } else {?>  
            <?php echo mb_strimwidth( strip_tags( apply_filters( 'the_content', $post->post_content ) ), 0, 130,"..." ); ?>
          <?php } ?>    
          <?php edit_post_link('编辑', '<span class="edit-post-link">', '</span>'); ?> 
    </div> 
   <!-- <a class="btn-leo-read btn-block hidden-xs" href="<?php the_permalink() ?>"  target="_blank" title="详细阅读 <?php the_title(); ?>">阅读全文</span></a>  -->
    </div>
  </section>
</article>
