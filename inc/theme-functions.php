<?php
/**
 * Theme-Functions 主要函数
 * @package     LEOBLOG
 * @subpackage  Include
 * @since       2.0.2
 * @author     LEO
 */
define( 'LEO_THEME_VERSION', '2.0.2' );
/**
 * 移除仪表盘内容
 */
function remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);    //WordPress China 博客
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);  //其它WordPress新闻
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);  //近期草稿
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);//链入链接
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);     //概况
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);   //插件
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

/*添加登录后台样式*/
function custom_login() {
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'template_directory' ) . '/css/admin.css" />';
}
add_action('login_head', 'custom_login');

/*更改logo的url*/
function custom_headerurl( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'custom_headerurl' );

/*更改logo的title*/
function custom_headertitle( $title ) {
    return __( 'LeoBlog' );
}
add_filter('login_headertitle','custom_headertitle');


/*删除控制面板顶部左上角LOGO图像*/
function leo_custom_logo() {
    echo '
        <style type="text/css">
        #wp-admin-bar-wp-logo,
        #dashboard_right_now .versions p,
        #wp-version-message
        {display:none !important;}
        </style>
    ';
}
add_action( 'admin_head', 'leo_custom_logo' );

/*隐藏控制面板页脚版权信息和版本号*/
function change_footer_admin () {
    return ' Theme By:<a href="https://github.com/gtchaos" target="_blank">LeoBlog</a>';
}
add_filter( 'admin_footer_text', 'change_footer_admin', 9999 );

function change_footer_version() {
    return ' ';
}
add_filter( 'update_footer', 'change_footer_version', 9999 );
// 注册加载JS & CSS文件
add_action( 'wp_enqueue_scripts', 'leo_register_scripts' );


// 开启链接管理（包括友情链接）
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 去除WordPress版本显示
add_filter( 'the_generator', 'remove_version' );

// 隐藏admin bar
add_filter( 'show_admin_bar', '__return_false' );

// 注销系统默认小工具
add_action( 'widgets_init', 'leo_deregister_widgets' );

/**
 * 系统默认小工具注销
 *
 * @since leoblog 3.0.0
 * @return void
 */
function leo_deregister_widgets() {
  unregister_widget( 'WP_Widget_Search' );
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_Categories');
}


//使WordPress支持post thumbnail
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}
// 添加文章的显示形式
add_theme_support( 'post-formats', array( 'status', 'audio', 'video', 'image' ,'gallery', 'aside' ,'quote'));

/**
 * 注册CSS & JS文件
 *
 * @since  3.0.0
 * @return void
 */
function leo_register_scripts() {

  

  // 注册jquery.validate.js
  wp_register_script( 'validate', get_template_directory_uri() . '/js/jquery.validate.js', 'jquery', LEO_THEME_VERSION, true );


  // 注册shine.min.js
  wp_register_script( 'shine', get_template_directory_uri() . '/js/shine.min.js', 'jquery', LEO_THEME_VERSION, true );

 // 注册leo.js
  wp_register_script( 'mmenu', get_template_directory_uri() . '/js/jquery.mmenu.js', 'jquery', LEO_THEME_VERSION, true );

  // 注册leo.js
  wp_register_script( 'leo', get_template_directory_uri() . '/js/leo.js', 'jquery', LEO_THEME_VERSION, true );

  // 注册用户自定义custom.js
  wp_register_script( 'custom', get_template_directory_uri() . '/js/custom.js', 'jquery', LEO_THEME_VERSION, true );
  // 注册用户自定义custom.js
  wp_register_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', 'jquery', LEO_THEME_VERSION, true );
  

  // 注册font-awesome.min.css
  wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', '', '4.0.1' );

  // 注册leo.css
  wp_register_style( 'mmenu', get_template_directory_uri() . '/css/jquery.mmenu.all.css', '', LEO_THEME_VERSION );
  // 注册leo.css
  wp_register_style( 'leo', get_template_directory_uri() . '/css/leo.css', '', LEO_THEME_VERSION );

  // 注册用户自定义custom.css
  wp_register_style( 'custom', get_template_directory_uri() . '/css/custom.css', '', LEO_THEME_VERSION );

  // 调用加载函数
  leo_enqueue_scripts();
}

/**
 * 加载CSS & JS文件
 *
 * @since  3.0.0
 * @return void
 */
function leo_enqueue_scripts() {
  wp_enqueue_script( 'validate' );
  wp_enqueue_script( 'shine' );
  wp_enqueue_script( 'mmenu' );
  wp_enqueue_script( 'leo' );
  wp_enqueue_script( 'custom' );
  wp_enqueue_script( 'scripts' );
  wp_enqueue_style( 'fontawesome' );
  wp_enqueue_style( 'mmenu' );
  wp_enqueue_style( 'leo' );
  wp_enqueue_style( 'custom' );

}

/**
 * 获取最热文章
 *
 * @since 3.0.0
 * @return array [最热文章数组]
 */
function leo_get_hotest_posts($num) {
  $args = array(
    'posts_per_page'   => $num,
    'offset'           => 0,
    'category'         => '',
    'orderby'          => 'views',
    'order'            => 'DESC',
    'include'          => '',
    'exclude'          => '',
    'meta_key'         => 'views',
    'meta_value'       => '',
    'post_type'        => 'post',
    'post_mime_type'   => '',
    'post_parent'      => '',
    'post_status'      => 'publish',
    'suppress_filters' => true
  );

  return get_posts($args);
}

/**
 * 获取最新文章
 *
 * @since 3.0.0
 * @return array [最新文章数组]
 */
function leo_get_latest_posts($num) {
  $args = array(
    'posts_per_page'   => $num,
    'offset'           => 0,
    'category'         => '',
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'include'          => '',
    'exclude'          => '',
    'meta_key'         => '',
    'meta_value'       => '',
    'post_type'        => 'post',
    'post_mime_type'   => '',
    'post_parent'      => '',
    'post_status'      => 'publish',
    'suppress_filters' => true
  );

  return get_posts($args);
}

/**
 * 获取随机文章
 *
 * @since 3.0.2
 * @return array [随机文章数组]
 */
function leo_get_rand_posts($num) {
  $args = array(
    'posts_per_page'   => $num,
    'offset'           => 0,
    'category'         => '',
    'orderby'          => 'rand',
    'order'            => 'DESC',
    'include'          => '',
    'exclude'          => '',
    'meta_key'         => '',
    'meta_value'       => '',
    'post_type'        => 'post',
    'post_mime_type'   => '',
    'post_parent'      => '',
    'post_status'      => 'publish',
    'suppress_filters' => true
  );

  return get_posts($args);
}

/**
 * 获取最新评论（排除作者评论）
 *
 * @since 3.0.0
 * @return array [最新评论数组]
 */
function leo_get_latest_comments($num) {
  $args = array(
    'author_email' => '',
    'ID' => '',
    'karma' => '',
    'number' => $num,
    'offset' => '',
    'orderby' => 'comment_date',
    'order' => 'DESC',
    'parent' => '',
    'post_id' => 0,
    'post_author' => '',
    'post_name' => '',
    'post_parent' => '',
    'post_status' => 'publish',
    'post_type' => '',
    'status' => 'approve',
    'type' => 'comment',
    'user_id' => '',
    'search' => '',
    'count' => false,
    'meta_key' => '',
    'meta_value' => '',
    'meta_query' => '',
  ); 

  return get_comments($args);
}

/**
 * 获取文章分类列表
 *
 * @since 3.0.0
 * @return array [文章分类列表]
 */
function leo_get_posts_category($exclude) {
  $args = array(
    'show_option_all'    => '',
    'orderby'            => 'name',
    'order'              => 'ASC',
    'style'              => 'none',
    'show_count'         => 0,
    'hide_empty'         => 1,
    'use_desc_for_title' => 1,
    'child_of'           => 0,
    'feed'               => '',
    'feed_type'          => '',
    'feed_image'         => '',
    'exclude'            => $exclude,
    'exclude_tree'       => '',
    'include'            => '',
    'hierarchical'       => 1,
    'title_li'           => __( 'Categories' ),
    'show_option_none'   => '',
    'number'             => null,
    'echo'               => 1,
    'depth'              => 1,
    'current_category'   => 0,
    'pad_counts'         => 0,
    'taxonomy'           => 'category',
    'walker'             => null
  );

  return wp_list_categories($args);
}

/**
 * 获取评论列表
 *
 * @since 3.0.0
 * @return array [评论列表]
 */
function leo_get_commments_list($size) {
  $args = array(
    'walker'            => null,
    'max_depth'         => '',
    'style'             => 'ol',
    'callback'          => null,
    'end-callback'      => null,
    'type'              => 'all',
    'reply_text'        => '<span><i class="fa fa-reply"></i></span>回复',
    'page'              => '',
    'avatar_size'       => $size,
    'reverse_top_level' => null,
    'reverse_children'  => '',
    'format'            => 'html5',
    'short_ping'        => false,
    'echo'              => true 
  );

  return wp_list_comments($args);
}

/**
 * 获取评论分页
 *
 * @since 3.0.0
 * @return array [评论分页]
 */
function leo_comments_pagination() {
  $args = array(
    'prev_text'    => __( '«' ),
    'next_text'    => __( '»' )
  );

  return paginate_comments_links($args);
}


/**
 * 可支持分类的分页功能
 *
 * @since 3.0.0
 * @return void
 */
if ( !function_exists( 'paginate' ) ):
  function paginate( $args = null ) {
    $range_gap = 2;         
    if (get_option( 'leo_paginate_num' ) != '' && intval( get_option( 'leo_paginate_num' ) ) > 0) {
      $range_gap = intval( get_option( 'leo_paginate_num' ) );
    }        
    $defaults = array( 'page'=>null, 'pages'=>null, 'range'=>$range_gap, 'gap'=>$range_gap, 'anchor'=>1, 'echo'=>1 );        
    $r = wp_parse_args( $args, $defaults );
    extract($r, EXTR_SKIP);       
    if ( !$page && !$pages ) {
      global $wp_query;           
      $page = get_query_var( 'paged' );
      $page = ! empty( $page ) ? intval( $page ) : 1;            
      $posts_per_page = intval( get_query_var( 'posts_per_page' ) );
      $pages = intval( ceil( $wp_query->found_posts / $posts_per_page ) );
    }
    
    $output = "";
    if ( $pages > 1 ) {
      $ellipsis = "<li><span>...</span></li>";            
      $min_links = $range * 2 + 1;
      $block_min = min( $page - $range, $pages - $min_links );
      $block_high = max( $page + $range, $min_links );
      $left_gap = ( ( $block_min - $anchor - $gap ) > 0 ) ? true : false;
      $right_gap = ( ( $block_high + $anchor + $gap ) < $pages ) ? true : false;            
      if ( $left_gap && !$right_gap ) {
        $output .= sprintf( '%s%s%s', paginate_loop( 1, $anchor ), $ellipsis, paginate_loop( $block_min, $pages, $page ) );
      } else if ( $left_gap && $right_gap ) {
        $output .= sprintf( '%s%s%s%s%s', paginate_loop( 1, $anchor ), $ellipsis, paginate_loop( $block_min, $block_high, $page ), $ellipsis, paginate_loop( ( $pages - $anchor + 1 ), $pages ) );
      } else if ( $right_gap && !$left_gap ) {
        $output .= sprintf( '%s%s%s', paginate_loop( 1, $block_high, $page ), $ellipsis, paginate_loop( ( $pages - $anchor + 1 ), $pages ) );
      } else {
        $output .= paginate_loop( 1, $pages, $page );
      }
    }        
    if ( $echo ) {
      echo $output;
    }       
    return $output;
  }
endif;
if ( !function_exists( 'paginate_loop' ) ):
  function paginate_loop( $start, $max, $page = 0 ) {
    $output = "";
    for ( $i = $start; $i <= $max; $i++ ) {
      $output .= ( $page === intval( $i ) ) ? "<li class='active'><span>$i</span></li>" : "<li><a href='".get_pagenum_link($i)."'>$i</a></li>";
    }
    return $output;
  }
endif;
if ( !function_exists( 'show_paginate' ) ):
  function show_paginate() {
    if( get_previous_posts_link()=='' && get_next_posts_link()=='' ) return false;
?>
<!-- 桌面端分页效果 -->
<div class="leo-page hidden-xs" class="clearfix">
  <ul class="pagination pagination-leo hidden-xs">
    <?php
      echo "<li>";
      previous_posts_link( __( '&laquo;', '' ), 0 );
      echo "</li>";
      if ( function_exists( "paginate" ) )  paginate();

      echo "<li>";
      next_posts_link( __( '&raquo;', '' ), 0);
      echo "</li>";
      wp_link_pages();
    ?>
  </ul>
</div>
<!-- 手机端分页效果 -->
<div class="leo-page-mobile visible-xs" class="clearfix">
   
<div class="btn-group btn-group-justified">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default <?php if( get_previous_posts_link()=='') echo "disabled";?>"><?php if( get_previous_posts_link()=='') echo "上一页"; previous_posts_link( __( '上一页', '' ), 0 ); ?></button>
  </div>
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default <?php if( get_next_posts_link()=='') echo "disabled";?>"><?php if( get_next_posts_link()=='') echo "下一页";next_posts_link( __( '下一页', '' ), 0 ); ?></button>
  </div>
</div>
</div>
<?php
}
endif;

//显示文章浏览次数
function custom_the_views($post_id, $echo=true, $views=' views') {
    $count_key = 'views';  
    $count = get_post_meta($post_id, $count_key, true);  
    if ($count == '') {  
        delete_post_meta($post_id, $count_key);  
        add_post_meta($post_id, $count_key, '0');  
        $count = '0';  
    }  
    if ($echo)  
        echo number_format_i18n($count) . $views;  
    else  
        return number_format_i18n($count) . $views;  
}  

//设置文章浏览次数
function set_post_views() {  
    global $post;  
    $post_id = $post->ID;  
    $count_key = 'views';  
    $count = get_post_meta($post_id, $count_key, true);  
    if (is_single() || is_page()) {  
        if ($count == '') {  
            delete_post_meta($post_id, $count_key);  
            add_post_meta($post_id, $count_key, '0');  
        } else {  
            update_post_meta($post_id, $count_key, $count + 1);  
        }  
    }  
}  
add_action('get_header', 'set_post_views');  

/**
 * 去除WordPress版本信息
 *
 * @since 3.0.0
 * @return string
 */

remove_action('wp_head','rsd_link');
remove_action('wp_head','wlwmanifest_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );


/**
 *   文章存档函数
 *
 * @since Leoblog 1.0.1
 * @return archives.
 */
function leo_archives_list() {
  if( !$output = get_option( 'leo_archives_list' ) ){
    $output = '<div id="archives">';      
    $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' ); 
    $year=0; $mon=0; $i=0; $j=0;
    while ( $the_query->have_posts() ) : $the_query->the_post();
    $year_tmp = get_the_time('Y');
    $mon_tmp = get_the_time('m');
    $y=$year; $m=$mon;
    if ( $mon != $mon_tmp && $mon > 0 ) $output .= '</div></div></div>';
    if ( $year != $year_tmp && $year > 0 ) $output .= '</div>';
    if ( $year != $year_tmp ) {
      $year = $year_tmp;
      $output .= '<h3 class="leo-year">'. $year .' 年 <small>(点击月份展开)</small></h3><div class="panel-group" id="accordion">'; 
    }
    if ( $mon != $mon_tmp ) {
      $mon = $mon_tmp;
      $output .= '<div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$year. $mon .'">
                          '. $mon .' 月
                        </a>
                      </h4>
                    </div>
                    <div id="collapse'.$year. $mon .'" class="panel-collapse collapse">
                      <div class="panel-body">'; 
    }
    $output .= '<p>'. get_the_time('d日: ') .'<a class="archivesPostList" href="'. get_permalink() .'">'. get_the_title() .'</a> <span class="badge">'. get_comments_number('0', '1', '%') .'</span></p>';

    endwhile;
    wp_reset_postdata();
    $output .= '</div></div></div></div></div>';
    update_option( 'leo_archives_list', $output );
  }
  echo $output;
}
function clear_zal_cache() {
  update_option( 'leo_archives_list', '' ); 
}
add_action( 'save_post', 'clear_zal_cache' ); 


/**
 * 搜索结果只显示文章
 *
 * @since 3.0.0
 * @return string
 */
function search_filter( $query ) {
  if ( $query->is_search ) {
    $query->set( 'post_type', 'post' );
  }
  return $query;
}

/**
 * 字符串截取
 *
 * @since 3.0.1
 * @return string
 */
function leo_cut_string($string, $sublen, $start = 0, $code = 'UTF-8') {
  if($code == 'UTF-8') {
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);
    if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
    return join('', array_slice($t_string[0], $start, $sublen));
  } else {
    $start = $start * 2;
    $sublen = $sublen * 2;
    $strlen = strlen($string);
    $tmpstr = '';  

    for($i = 0; $i < $strlen; $i++) {
      if($i >= $start && $i < ($start + $sublen)) {
        if(ord(substr($string, $i, 1)) > 129) $tmpstr .= substr($string, $i, 2);
        else $tmpstr .= substr($string, $i, 1);
      } 
      if(ord(substr($string, $i, 1)) > 129) $i++;
    }
    if(strlen($tmpstr) < $strlen ) $tmpstr .= "...";
    return $tmpstr;
  }
}
/**
 * 获取当前作者有关文章
 * @param  [type] $author [description]
 * @param  [type] $num    [description]
 * @return [type]         [description]
 */
function get_author_related_posts($num) { 
global $authordata, $post;
      $args = array(
            'author' => $authordata->ID, 
            'post__not_in' => array( $post->ID ), 
            'posts_per_page' => $num 
          );
       $author_posts = get_posts($args); 
       return $author_posts;

}


/**
 * 喜欢功能
 *
 * @since 3.0.2
 * @return string
 */
add_action('wp_ajax_nopriv_leo_like', 'leo_like');
add_action('wp_ajax_leo_like', 'leo_like');

function leo_like(){
  global $wpdb,$post;
  $id = $_POST["um_id"];
  $action = $_POST["um_action"];

  if ( $action == 'ding'){
    $leo_raters = get_post_meta($id,'leo_ding',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    
    setcookie('leo_ding_'.$id,$id,$expire,'/',$domain,false);
    if (!$leo_raters || !is_numeric($leo_raters)) {
      update_post_meta($id, 'leo_ding', 1);
    } 
    else {
      update_post_meta($id, 'leo_ding', ($leo_raters + 1));
    }
    echo get_post_meta($id,'leo_ding',true);
  } 
  
  die;
}

/**
 * 当没有添加特色图像时，将自动调用文章的第一张图像
 *
 * @since 3.0.2
 * @return string
 */
function autoset_featured() {
  global $post;
  
  $already_has_thumb = has_post_thumbnail($post->ID);
  if (!$already_has_thumb)  {
    $attached_image = get_children( "order=ASC&post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
    if ($attached_image) {
      foreach ($attached_image as $attachment_id => $attachment) {
      set_post_thumbnail($post->ID, $attachment_id);
      }
    }
  }
} 
add_action('the_post', 'autoset_featured');
add_action('save_post', 'autoset_featured');
add_action('draft_to_publish', 'autoset_featured');
add_action('new_to_publish', 'autoset_featured');
add_action('pending_to_publish', 'autoset_featured');
add_action('future_to_publish', 'autoset_featured');

//用户前端投稿添加特色图片异步处理操作
function hx_fetch_featured_image(){
  $image_id = $_POST['img'];
  echo wp_get_attachment_image( $image_id, array(300,200) );
  die();
}
add_action( 'wp_ajax_hx_fetch_featured_image', 'hx_fetch_featured_image' );

//限制文章标签个数
add_filter('term_links-post_tag','limit_to_five_tags');
function limit_to_five_tags($terms) {

    return array_slice($terms,0,5,true);
}



/**
 * 格式化时间
 * @param  [type] $time [要格式化的时间戳]
 * @return [type]       [description]
 */
function time_format ($time) {
  //当前时间
  $now = time();
  //今天零时零分零秒
  $today = strtotime(date('y-m-d', $now));
  //传递时间与当前时秒相差的秒数
  $diff = $now - $time;
  $str = '';
  switch ($diff) {
    case $diff < 60 :
      $str = $diff . '刚刚';
      break;
    case $diff < 3600 :
      $str = floor($diff / 60) . '分钟前';
      break;
    case $diff < (3600*24) :
      $str = floor($diff / 3600) . '小时前';
      break;
    case $diff < (3600*24*3) : // 3天内
      $str = floor($diff / 86400).'天前';;
      break;
    default : 
      $str = date('m月d日', $time);
  }
  echo $str;
}
// determine the topmost parent of a term  
function get_term_top_most_parent($term_id, $taxonomy="category"){  
    // start from the current term  
    $parent  = get_term_by( 'id', $term_id, $taxonomy);  
    // climb up the hierarchy until we reach a term with parent = '0'  
    while ($parent->parent != '0'){  
        $term_id = $parent->parent;  
  
        $parent  = get_term_by( 'id', $term_id, $taxonomy);  
    }  
    return $parent;  
}

//给注册页面添加额外字段 
add_action( 'register_form','leo_register_fields' );
function leo_register_fields(){ 
  $captchaUrlout = sprintf("var captchaUrl = '%s';", get_template_directory_uri().'/inc/reg-captcha.php');
  echo '<script>'.$captchaUrlout.';</script>';
?>
<p>
<label for="password">密码<br/> <input id="password" class="input"
type="password" value="" name="password" /> </label> 
</p>
 <p> <label for="repeat_password">确认密码<br/> <input id="repeat_password"
class="input" type="password" value="" name="repeat_password" />
</label> 
</p> 
 <p><label for="register_captcha">验证码<input type="text" name="register_captcha" value="" size="20" class="input" tabindex="20" /></label></p>
    <p>请输入下面图片上的字符，不区分大小写<br/><img id="captchaimg" src="<?php echo get_template_directory_uri().'/inc/reg-captcha.php';?>" /></p>
<script>  
  // refresh register captcha
  var captchaImg = document.getElementById("captchaimg");
  captchaImg.onclick = function(e){
  this.src = captchaUrl+"?"+Math.random();
 }
</script>
<?php } 
 
//二、验证密码检查用户的输入，两次输入的密码是否一致
//检查用户输入 
//
add_action( 'register_post', 'leo_check_register_fields',10, 3 );
function leo_check_register_fields($login, $email, $errors)
{ 
  if ( $_POST['password'] !== $_POST['repeat_password'] ) 
    {  
      $errors->add( 'passwords_not_matched', "<strong>错误</strong>: 两次输入的密码不一致");
    } 
  if( $_POST['register_captcha'] !== $_SESSION['register_captcha'] ){
          remove_filter('authenticate', 'vcode_v', 20, 3);
          $errors->add('incorrect_captcha', '<strong>错误</strong>：验证码不正确。');
  }
}
//三、储存密码//储存用户提交的密码 
add_action( 'user_register','leo_register_extra_fields', 100 );
 function leo_register_extra_fields($user_id)
 {
    $userdata = array(); $userdata['ID'] = $user_id; 
    if ($_POST['password'] !== '' ) {
     $userdata['user_pass'] = $_POST['password']; 
    } 
    $new_user_id = wp_update_user( $userdata );
}
//四、处理文本//隐藏那句“密码将会通过电子邮件发给你”
 add_filter( 'gettext','leo_edit_password_email_text' ); 
 function leo_edit_password_email_text ( $text ) { 
  if ( $text == '密码将通过电子邮件发送给您。' ) {
      $text = ''; 
    } else if ( $text == '注册完成。请查收我们给您发的邮件。') {
        $text = '恭喜您，注册完成。'; 
    }
    return $text; 
 } 

add_action( 'init', 'custom_rss_template' );
/**
 * Register custom RSS template.
 */
function custom_rss_template() {
    add_feed( 'xiaozhi', 'xiaozhi_rss_render' );
    add_feed( 'uc', 'uc_rss_render' );
}

/**
 * xiaozhi RSS template callback.
 */
function xiaozhi_rss_render() {
    get_template_part( 'feed', 'xiaozhi' );
}

/**
 * uc RSS template callback.
 */
function uc_rss_render() {
    get_template_part( 'feed', 'uc' );
}

 //默认时区为世界协调时
date_default_timezone_set("UTC");
//根据页面类型指定每页显示的文章数

/*
// 关闭核心提示
add_filter('pre_site_transient_update_core',    create_function('$a', "return null;")); 
// 禁止 Wordpress 检查更新
remove_action('admin_init', '_maybe_update_core'); 
*/  
function custom_posts_per_page($query){
    if(is_home()){
        $query->set('posts_per_page',15);//首页每页显示15篇文章
    }
    if(is_search()){
        $query->set('posts_per_page',12);//搜索页显示所有匹配的文章,分页，每页最多12篇文章
    }
    if(is_archive()){
        $query->set('posts_per_page',12);//archive每页显示12篇文章
    }//endif
}//function
//this adds the function above to the 'pre_get_posts' action
//add_action('pre_get_posts','custom_posts_per_page');

/*匹配用户图像路径方法*/
function huoxing_get_avatar_url($get_avatar){
    preg_match('/src="(.*?)"/i', $get_avatar, $matches);
    return $matches[1];
}
/**
 * Register `team` post type
 */
function team_post_type() {
 
   // Labels
    $labels = array(
        'name' => _x("Team", "post type general name"),
        'singular_name' => _x("Team", "post type singular name"),
        'menu_name' => 'Team Profiles',
        'add_new' => _x("Add New", "team item"),
        'add_new_item' => __("Add New Profile"),
        'edit_item' => __("Edit Profile"),
        'new_item' => __("New Profile"),
        'view_item' => __("View Profile"),
        'search_items' => __("Search Profiles"),
        'not_found' =>  __("No Profiles Found"),
        'not_found_in_trash' => __("No Profiles Found in Trash"),
        'parent_item_colon' => ''
    );
 
    // Register post type
    register_post_type('team' , array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-groups',
        'rewrite' => false,
        'supports' => array('title', 'editor', 'thumbnail')
    ) );
}
add_action( 'init', 'team_post_type', 0 );



/**
 * Register `department` taxonomy
 */
function team_taxonomy() {
 
    // Labels
    $singular = 'Department';
    $plural = 'Departments';
    $labels = array(
        'name' => _x( $plural, "taxonomy general name"),
        'singular_name' => _x( $singular, "taxonomy singular name"),
        'search_items' =>  __("Search $singular"),
        'all_items' => __("All $singular"),
        'parent_item' => __("Parent $singular"),
        'parent_item_colon' => __("Parent $singular:"),
        'edit_item' => __("Edit $singular"),
        'update_item' => __("Update $singular"),
        'add_new_item' => __("Add New $singular"),
        'new_item_name' => __("New $singular Name"),
    );
 
    // Register and attach to 'team' post type
    register_taxonomy( strtolower($singular), 'team', array(
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => false,
        'labels' => $labels
    ) );
}
add_action( 'init', 'team_taxonomy', 0 );
/**
 * WordPress 后台用户列表显示注册时间
 * http://www.wpdaxue.com/display-user-registerdate.html
 */
class RRHE {
  // Register the column - Registered
  public static function registerdate($columns) {
    $columns['registerdate'] = __('注册时间', 'registerdate');
    return $columns;
  }
 
  // Display the column content
  public static function registerdate_columns( $value, $column_name, $user_id ) {
    if ( 'registerdate' != $column_name )
      return $value;
    $user = get_userdata( $user_id );
    $registerdate = get_date_from_gmt($user->user_registered);
    return $registerdate;
  }
 
  public static function registerdate_column_sortable($columns) {
    $custom = array(
      // meta column id => sortby value used in query
      'registerdate'    => 'registered',
      );
    return wp_parse_args($custom, $columns);
  }
 
  public static function registerdate_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'registerdate' == $vars['orderby'] ) {
      $vars = array_merge( $vars, array(
        'meta_key' => 'registerdate',
        'orderby' => 'meta_value'
        ) );
    }
    return $vars;
  }
 
}
 
// Actions
add_filter( 'manage_users_columns', array('RRHE','registerdate'));
add_action( 'manage_users_custom_column',  array('RRHE','registerdate_columns'), 15, 3);
add_filter( 'manage_users_sortable_columns', array('RRHE','registerdate_column_sortable') );
add_filter( 'request', array('RRHE','registerdate_column_orderby') );

//支持中文用户名注册
function ludou_sanitize_user ($username, $raw_username, $strict) {
  $username = wp_strip_all_tags( $raw_username );
  $username = remove_accents( $username );
  // Kill octets
  $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
  $username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

  // 网上很多教程都是直接将$strict赋值false，
  // 这样会绕过字符串检查，留下隐患
  if ($strict) {
    $username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
  }

  $username = trim( $username );
  // Consolidate contiguous whitespace
  $username = preg_replace( '|\s+|', ' ', $username );

  return $username;
}

add_filter ('sanitize_user', 'ludou_sanitize_user', 10, 3);
//只允许投稿者上传图片文件
function custom_upload_mimes ( $existing_mimes=array() ) {
//允许投稿者（Contributor）上传的类型
 if( current_user_can( 'edit_posts' ) && !current_user_can( 'publish_posts' ) ) {
  unset ($existing_mimes);//禁止上传任何文件
  $existing_mimes['jpg|jpeg|gif|png']='image/image';//只允许用户上传jpg,gif,png文件
 }
 return $existing_mimes; 
}
add_filter('upload_mimes', 'custom_upload_mimes');

//允许用户投稿时上传文件
function allow_contributor_uploads() {
  $role = get_role( 'contributor' );
  $role->add_cap('upload_files');
}
add_action('init', 'allow_contributor_uploads', 0);



