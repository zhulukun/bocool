<?php
/**
 * LeoBlog 小工具函数加载与操作
 *
 * @package 	  LeoBlog
 * @subpackage  Include
 */



/**
 * LeoBlog 幻灯片组件
 *
 * @package    LeoBlog
 * @subpackage Widget
 */

class Leo_Slide extends WP_Widget {

  // 设定小工具信息
  function Leo_Slide() {
    $widget_options = array(
          'name'        => '幻灯片组件（LeoBlog）',
          'description' => 'LeoBlog 幻灯片组件'
    );
    parent::WP_Widget( false, false, $widget_options );
  }
  // 设定小工具结构
  function widget( $args, $instance ) {
    extract( $args );
    @$id = $instance['id'] ? $instance['id'] : '';
    @$post_id = $instance['pid'] ? $instance['pid'] : '';
    @$title = $instance['title'] ? $instance['title'] : '幻灯片标题';
    $slide_posts = new WP_Query( array(
      'post_type'       => array( 'post' ),
      'showposts'       => 3,
      'cat'         => $id,
      'post_not_in' => array($post_id),
      'ignore_sticky_posts' => true,
    ) );
    if(!empty($post_id)) $post_res = get_post($post_id, ARRAY_A);
    echo $before_widget;
    ?>
      <!--  首页幻灯片部分-->
      <div class="carousel-body">
        <div id="hello-carousel" class="scarousel sslide" data-ride="scarousel">
           <!-- Indicators -->
         <!--  <ol class="carousel-indicators">
           <li data-target="#hello-carousel" data-slide-to="0" class="active"></li>
           <li data-target="#hello-carousel" data-slide-to="1"></li>
           <li data-target="#hello-carousel" data-slide-to="2"></li>
         </ol> -->
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
               <?php $i = 1; echo '<div class="item active">'; while ($slide_posts->have_posts()): $slide_posts->the_post(); ?>
               <?php if( !empty($post_res) && $i ==1 ) { ?>
           <div class="left-slide-area">
                              <a href="<?php echo get_permalink( $post_id ); ?>"  target="_blank">
                                  <img class="left-slide-img" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ); ?>">
                              </a>

                              <div class="left-slide-title"><a href="<?php  echo get_permalink( $post_id ); ?>" target="_blank"><h2><?php echo get_the_title( $post_id ); ?></h2></a></div>
                   <div class="overlay"></div>
                   </div>
         <?php $i++; continue; }?>
         <?php if ( $i % 3 ==1 ) {?>
                          <div class="left-slide-area">
                              <a href="<?php the_permalink() ?>"  target="_blank">
                                  <img class="left-slide-img" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id());?>">
                              </a>

                              <div class="left-slide-title"><a href="<?php the_permalink() ?>" target="_blank"><h2><?php the_title(); ?></h2></a></div>
                               <div class="overlay"></div>
                           </div>
              <?php } else { ?>
                     <div class="right-slide-area">
                          <a href="<?php the_permalink() ?>"   target="_blank">
                              <img class="right-slide-img" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id());?>">
                          </a>
                          <div class="right-slide-title"><a href="<?php the_permalink() ?>"  target="_blank"><h3><?php the_title(); ?></h3></a></div>
                          <div class="overlay"></div>
                     </div>
               <?php } ?>
               <?php if($i == 9) break; ?>
              <?php if($i % 3 == 0) { echo '</div><div class="item">'; } $i++; endwhile; echo '</div>'; ?>
          </div>

            <!-- Controls -->
           <!--  <a class="left carousel-control" href="#hello-carousel" role="button" data-slide="prev">
           <span class="fa fa-angle-left"></span>
             <span class="sr-only">Previous</span>
           </a>
           <a class="right carousel-control" href="#hello-carousel" role="button" data-slide="next">
             <span class="fa fa-angle-right"></span>
             <span class="sr-only">Next</span>
           </a> -->
       </div> <!-- 幻灯片内容结束 -->
       </div>
    <?php
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
     return $new_instance;
  }

  function form( $instance ) {
    @$title = esc_attr( $instance['title'] );
    @$id = esc_attr( $instance['id'] );
	  @$post_id = esc_attr( $instance['pid'] );
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">
          标题（默认为幻灯片标题）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'id' ); ?>">
          推荐分类：
		  <?php wp_dropdown_categories( array( 'name' => $this->get_field_name("id"), 'selected' => $instance["id"], 'show_option_all' => 'All', 'show_count' => true ) ); ?>

        </label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'pid' ); ?>">
          推荐文章ID(仅支持输入一篇文章ID)：
		   <input class="widefat" id="<?php echo $this->get_field_id( 'pid' ); ?>" name="<?php echo $this->get_field_name( 'pid' ); ?>" type="text" value="<?php echo $post_id; ?>" />

        </label>
    </p>
    <?php
  }
}

register_widget( 'Leo_Slide' );


/**
 * LeoBlog 最新评论组件
 *
 * @package    LeoBlog
 * @subpackage Widget
 */

class Leo_Latest_Comments extends WP_Widget {

  // 设定小工具信息
  function Leo_Latest_Comments() {
    $widget_options = array(
          'name'        => '最新评论组件（LeoBlog）',
          'description' => 'LeoBlog 最新评论组件'
    );
    parent::WP_Widget( false, false, $widget_options );
  }

  // 设定小工具结构
  function widget($args, $instance) {
  	extract($args);
    @$title = $instance[ 'title' ] ? $instance[ 'title' ] : '最新评论';
    @$num = $instance[ 'num' ] ? $instance[ 'num' ] : 5;
    echo $before_widget;
    ?>
        <div class="panel-heading widget-title">
          <?php echo $title; ?>
        </div>
        <div class="panel-body">
          <ul class="list-group">
            <?php
				$comments = leo_get_latest_comments( $num );
				foreach ( $comments as $comment ) :
				$author_url = $comment->comment_author_url;

				$display_name = $comment->user_id ? get_the_author_meta( 'display_name', $comment->user_id ) : '';
				if($display_name){
					$author_url = get_author_posts_url( $comment->user_id );
					$author_link = get_the_author_meta( 'user_url', $comment->user_id );
					$author_link = $author_link ? $author_link : $author_url;
					$author_link = '<a href="'.$author_link.'" rel="external nofollow" target="_blank"><span class="comment-author">'.$display_name.'</span></a>';

				}else{
					$author_link = $comment->comment_author_url ? '<a href="'.$comment->comment_author_url.'" rel="external nofollow" target="_blank"><span class="comment-author">'.$comment->comment_author.'</span></a>' : '<span class="comment-author">'.$comment->comment_author.'</span>';
				}

				if(empty($author_link)) $author_link = __('匿名','dmeng');
				$author_link = apply_filters( 'get_comment_author_link', $author_link );
            ?>
              <li class="leo-list">
               <div class="who-comment-for">
                <!-- <div class="sidebar-avatar">
                  <?php //echo get_avatar($comment->comment_author_email, 36); ?>
                </div> -->

                <h6><?php echo $author_link;?>
                  <span>&bull;<?php comment_time('H:i:s'); ?> 评论于：</span><a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>
                 </h6>
              </div>
                <div class="sidebar-comment">

                    <?php echo mb_strimwidth(strip_tags(apply_filters('comment_content', $comment->comment_content ) ), 0, 180, "..." ); ?>

                </div>
              </li>
            <?php
              endforeach;
            ?>
          </ul>
        </div>
    <?php
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
     return $new_instance;
  }

  function form( $instance ) {
    @$title = esc_attr($instance['title']);
    @$num = esc_attr($instance['num']);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">
          标题（默认最新评论）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'num' ); ?>">
          评论显示条数（默认显示5条）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo $num; ?>" />
        </label>
      </p>
    <?php
  }
}

register_widget( 'Leo_Latest_Comments' );

/**
 * LeoBlog 最热文章组件
 *
 * @package    LeoBlog
 * @subpackage Widget
 */

class Leo_Hotest_Posts extends WP_Widget {

  // 设定小工具信息
  function Leo_Hotest_Posts() {
    $widget_options = array(
          'name'        => '最热文章组件（LeoBlog）',
          'description' => 'LeoBlog 最热文章组件'
    );
    parent::WP_Widget( false, false, $widget_options );
  }

  // 设定小工具结构
  function widget( $args, $instance ) {
  	extract($args);
    @$title = $instance['title'] ? $instance['title'] : '最热文章';
    @$num = $instance['num'] ? $instance['num'] : 5;
    echo $before_widget;
    ?>
       <div class="panel-heading widget-title">
         <?php echo $title; ?>
        </div>
        <div class="panel-body">
          <ul class="list-group">
            <?php
              // 设置全局变量，实现post整体赋值
              global $post; $k = 0;
              $posts = leo_get_hotest_posts( $num );
              foreach ( $posts as $post ) :
              setup_postdata( $post );
            ?>
            <?php if ($k==0) {?>
                <li class="widget-first-thumb">
                  <figure class="thumbnail">
                    <?php the_post_thumbnail( "medium"); ?>
                    <div class="widget-first-post-title">
                      <a href="<?php the_permalink();?>">
                         <h5><?php the_title();?></h5>
                      </a>
                    </div>
                  </figure>
              </li>
              <?php } else { ?>
              <li class="leo-list clearfix">
                <figure class="thumbnail leo-thumb">
                  <?php the_post_thumbnail( array(75,75) ); ?>
                </figure>
                <a href="<?php the_permalink();?>">
                 <h5><?php the_title();?></h5>
                </a>
              </li>
            <?php } $k++; endforeach;
              wp_reset_postdata();
            ?>
          </ul>
        </div>
    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
     return $new_instance;
  }

  function form($instance) {
    @$title = esc_attr( $instance['title'] );
    @$num = esc_attr( $instance['num'] );
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">
          标题（默认最热文章）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'num' ); ?>">
          显示文章条数（默认显示5条）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo $num; ?>" />
        </label>
      </p>
    <?php
  }
}

register_widget( 'Leo_Hotest_Posts' );

/**
 * LeoBlog 最新文章组件
 *
 * @package    LeoBlog
 * @subpackage Widget
 */

class Leo_Latest_Posts extends WP_Widget {

  // 设定小工具信息
  function Leo_Latest_Posts() {
    $widget_options = array(
          'name'        => '最新文章组件（LeoBlog）',
          'description' => 'LeoBlog 最新文章组件'
    );
    parent::WP_Widget( false, false, $widget_options );
  }

  // 设定小工具结构
  function widget( $args, $instance ) {
  	extract($args);
    @$title = $instance['title'] ? $instance['title'] : '最新文章';
    @$num = $instance['num'] ? $instance['num'] : 5;
    echo $before_widget;
    ?>
        <h3 class="panel-heading widget-title">
           <?php echo $title; ?>
        </h3>
        <div class="panel-body">
          <ul class="list-group">
            <?php
              // 设置全局变量，实现post整体赋值
              global $post; $k = 0;
              $posts = leo_get_latest_posts( $num );
              foreach ( $posts as $post ) :
              setup_postdata($post);
            ?>
              <?php if ($k==0) {?>
                <li class="widget-first-thumb">
                  <figure class="thumbnail">
                    <?php the_post_thumbnail( "medium"); ?>
                    <div class="widget-first-post-title">
                      <a href="<?php the_permalink();?>">
                         <h5><?php the_title();?></h5>
                      </a>
                    </div>
                  </figure>
              </li>
              <?php } else { ?>
              <li class="leo-list clearfix">
                <figure class="thumbnail leo-thumb">
                  <?php the_post_thumbnail( array(75,75) ); ?>
                </figure>
                <a href="<?php the_permalink();?>">
                 <h5><?php the_title();?></h5>
                </a>
              </li>
            <?php } $k++;endforeach;
              wp_reset_postdata();
            ?>
          </ul>
        </div>
    <?php
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
     return $new_instance;
  }

  function form( $instance ) {
    @$title = esc_attr( $instance['title'] );
    @$num = esc_attr( $instance['num'] );
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">
          标题（默认最新文章）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'num' ); ?>">
          显示文章条数（默认显示5条）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo $num; ?>" />
        </label>
      </p>
    <?php
  }
}

register_widget( 'Leo_Latest_Posts' );

/**
 * LeoBlog 集合组件
 *
 * @package    LeoBlog
 * @subpackage Widget
 */

class Leo_Sets extends WP_Widget {

  // 设定小工具信息
  function Leo_Sets() {
    $widget_options = array(
          'name'        => '集合组件（LeoBlog）',
          'description' => 'LeoBlog 集合组件，包含最热文章、最新文章、随机文章'
    );
    parent::WP_Widget( false, false, $widget_options );
  }

  // 设定小工具结构
  function widget( $args, $instance ) {
  	extract($args);

    @$title1 = $instance['title1'] ? $instance['title1'] : '最热文章';
    @$title2 = $instance['title2'] ? $instance['title2'] : '最新文章';
    @$title3 = $instance['title3'] ? $instance['title3'] : '随机文章';
    @$num = $instance['num'] ? $instance['num'] : 10;
    echo $before_widget;
    ?>

    <div class="widget-sets">
      <ul class="nav nav-pills pills-leo">
        <li class="active"><a href="#sidebar-hot" data-toggle="tab" title="<?php echo $title1; ?>"><i class="fa fa-star"></i></a></li>
        <li><a href="#sidebar-new" data-toggle="tab" title="<?php echo $title2; ?>"><i class="fa fa-clock-o"></i> </a></li>
        <li><a href="#sidebar-rand" data-toggle="tab" title="<?php echo $title3; ?>"><i class="fa fa-random"></i></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active nav bs-sidenav fade in" id="sidebar-hot">
          <ul class="list-group">
            <?php
              // 设置全局变量，实现post整体赋值
              global $post;
              $posts = leo_get_hotest_posts( $num );
              foreach ( $posts as $post ) :
              setup_postdata( $post );
            ?>
              <li class="list-group-item clearfix">
                <a href="<?php the_permalink();?>">
                  <?php the_title();?>
                </a>

              </li>
            <?php
              endforeach;
              wp_reset_postdata();
            ?>
          </ul>
        </div>
        <div class="tab-pane fade" id="sidebar-new">
          <ul class="list-group">
            <?php
              // 设置全局变量，实现post整体赋值
              global $post;
              $posts = leo_get_latest_posts( $num );
              foreach ( $posts as $post ) :
              setup_postdata($post);
            ?>
              <li class="list-group-item clearfix">
                <a href="<?php the_permalink();?>">
                  <?php the_title(); ?>
                </a>
              </li>
            <?php
              endforeach;
              wp_reset_postdata();
            ?>
          </ul>
        </div>
        <div class="tab-pane nav bs-sidenav fade" id="sidebar-rand">
          <ul class="list-group">
            <?php
              // 设置全局变量，实现post整体赋值
              global $post;
              $posts = leo_get_rand_posts( $num );
              foreach ( $posts as $post ) :
              setup_postdata($post);
            ?>
              <li class="list-group-item clearfix">
                <a href="<?php the_permalink();?>">
                  <?php the_title(); ?>
                </a>
              </li>
            <?php
              endforeach;
              wp_reset_postdata();
            ?>
          </ul>
        </div>
      </div>
    </div>
    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    return $new_instance;
  }

  function form($instance) {
    @$title1 = esc_attr( $instance['title1'] );
    @$title2 = esc_attr( $instance['title2'] );
    @$title3 = esc_attr( $instance['title3'] );
    @$num = esc_attr( $instance['num'] );
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title1' ); ?>">
          标题（默认最热文章）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title1' ); ?>" name="<?php echo $this->get_field_name( 'title1' ); ?>" type="text" value="<?php echo $title1; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'title2' ); ?>">
          标题（默认最新文章）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title2' ); ?>" name="<?php echo $this->get_field_name( 'title2' ); ?>" type="text" value="<?php echo $title2; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'title3' ); ?>">
          标题（默认随机文章）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'title3' ); ?>" name="<?php echo $this->get_field_name( 'title3' ); ?>" type="text" value="<?php echo $title3; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'num' ); ?>">
          显示文章条数（默认显示10条）：
          <input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo $num; ?>" />
        </label>
      </p>
    <?php
  }
}

register_widget( 'Leo_Sets' );
