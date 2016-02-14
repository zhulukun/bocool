<?php
/*
 * 欢迎来到代码世界，如果你想修改多梦主题的代码，那我猜你是有更好的主意了～
 * 那么请到多梦网络（ http://www.dmeng.net/ ）说说你的想法，数以万计的童鞋们会因此受益哦～
 * 同时，你的名字将出现在多梦主题贡献者名单中，并有一定的积分奖励～
 * 注释和代码同样重要～
 * @author 多梦 @email chihyu@aliyun.com 
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta property="qc:admins" content="71565640576705701676375" />
<meta property="wb:webmaster" content="2a3a9d4966f0860c" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="Cache-Control" content="no-transform " /> 
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/index.css">


<?php echo stripslashes(htmlspecialchars_decode(dmeng_setting('head_code')));?>
<!--[if lte IE 8]><script>window.location.href='http://cdn.dmeng.net/upgrade-your-browser.html?referrer='+location.href;</script><![endif]-->
<!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/ui/js/modernizr.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/ui/js/respond.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/ui/js/html5shiv.js"></script>
<![endif]-->
<title>
  <?php
    global $page, $paged;
    $site_description = get_bloginfo( 'description', 'display' );
    if ($site_description && ( is_home() || is_front_page() )) {
      bloginfo('name');
      echo " - $site_description";
    } else if ( is_category() ) {
 
    single_cat_title(); echo " | ";  bloginfo('name'); //这里判定如果是分类目录，标题就显示：分类目录名称 | 随便自定义
 
} else if (is_single() || is_page() ) {
 
    single_post_title();  echo ' | ' ; bloginfo('name'); //这里判定如果是文章页，标题就显示：文章标题 | 随便自定义
 
}  else {
      echo wp_title( '&#45;', true, 'right' ); 
    }
  ?>
</title>

<link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="canonical" href="<?php echo dmeng_canonical_url();?>">
<?php

if(dmeng_setting('speedup'))
	echo '<link rel="dns-prefetch" href="'.dmeng_speed_url().'"> ';

wp_head();

?>
</head>
<body <?php body_class(); ?>>
<div id="gtchaos-page">
  <nav id="menu-left" class="gtmenu">
    <ul>
     <?php 
        if(function_exists('wp_nav_menu')) {
            wp_nav_menu( array(
              'menu'              => '',
              'theme_location'    => 'header_menu',
              'depth'             => 0,
              'container'         => '',
              'container_class'   => '',
              'items_wrap'    => '%3$s',
              'walker'            => new Leo_Nav_Menu()
            ) );
        }
     ?>
     
       <div class="clearfix"> </div>
    </ul>

  </nav>
</div>