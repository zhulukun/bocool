<?php
/**
*  Template Name:首页
*
*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="UTF-8">
    <title>首页 | 博酷科技官方网站</title>
    <link rel="stylesheet" id="mmenu-css" href="<?php echo get_template_directory_uri(); ?>/css/jquery.mmenu.all.css?ver=2.0.2" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leo.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/huoxing-1.0.1.css">

    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mousewheel.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.scrollpage.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/index.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mmenu.js"></script>
    <style type="text/css">
        .huoxing-header {
    height: 67px;
    width: 100%;
    position: fixed;
    z-index: 19861107;
    text-align: center;
    color: #333;
    line-height: 67px;
    text-shadow: 0 1px 2px 2px #FFF;
    background: #FEFEFE;
    box-shadow: 0 4px 2px 0 #666;
}
    </style>
</head>
<body>
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
     <li>
         <a href="javascript:void(0);">查看更多</a>
          <?php  wp_tag_cloud( 'format=list&smallest=16&largest=16&orderby=count&unit=px&number=12&order=DESC' ); ?>
     </li>
    <?php if (!is_user_logged_in()) { ?>

    <li class="hx-people"><a class="hx-people-login" href="<?php echo wp_login_url( dmeng_get_current_page_url() ); ?>">登录&nbsp;<i class="fa fa-rocket"></i>&nbsp;<?php echo bloginfo('name'); ?></a></li>

    <?php } else { $current_user = wp_get_current_user();?>
    <li class="hx-people">
        <a class="hx-people-avatar" href="<?php echo esc_url( get_author_posts_url( $current_user->ID ) );?>" title="<?php echo $current_user->display_name;?>">                         
           <?php   $avatar_url = dmeng_get_avatar( $current_user->ID , '50' , dmeng_get_avatar_type($current_user->ID), false ); echo $avatar_url;?>
           <div class="hx-people-name"><?php echo mb_strimwidth( strip_tags( $current_user->display_name ), 0, 10,"..." ); ?></div> 
        </a>

        <ul class="text-center hx-people-center" role="menu">
            <?php $profileli = '<li class="clearfix">'.sprintf(__('<a href="%1$s" class="name"><i class="glyphicon glyphicon-home"></i>主页</a></li>', 'dmeng'), get_author_posts_url( $current_user->ID )) . 
                '<li><a href="'.htmlspecialchars( add_query_arg('tab', 'message', get_author_posts_url( $current_user->ID )) ).'"><i class="glyphicon glyphicon-bullhorn"></i>消息</a></li>'.
                ( !current_user_can( 'subscriber' ) ? '<li><a href="'.htmlspecialchars( add_query_arg('action','new',dmeng_get_user_url('post')) ).'">'.__('<i class="glyphicon glyphicon-edit"></i>投稿','dmeng').'</a></li>' : '') . 
                '<li><a href="'.get_edit_profile_url($current_user->ID).'"><i class="glyphicon glyphicon-cog"></i>设置</a></li>'.
                ( current_user_can( 'publish_pages' ) ? '<li><a href="'.admin_url().'" target="_blank">'.__('<i class="glyphicon glyphicon-wrench"></i>管理','dmeng').'</a></li>' : '') . 
                '<li><a href="'.wp_logout_url(dmeng_get_current_page_url()).'" title="'.esc_attr__('Log out of this account').'" data-no-instant>' .
                __('<i class="glyphicon glyphicon-off"></i>登出','dmeng') . 
                '</a></li>';
                echo $profileli;

             ?>       
            <div class="clearfix"> </div>
        </ul>
    </li>

     <?php }?>
       <div class="clearfix"> </div>
    </ul>

  </nav>
<div class="huoxing-header">
 
<header id="masthead" itemscope itemtype="http://schema.org/WPHeader">
<div class="navbar top-navbar" id="leo-header" role="banner">
  <div class="container">
    <div class="navbar-header">
      <a id="gtchaos" href="#menu-left"><span></span></a>
      <div class="navbar-header-search-shopping">
            <div class="toggle-search"><i class="fa fa-search"></i></div>
            <div class="search-expand">
              <div class="search-expand-inner">
                <form method="get" class="searchform" action="<?php bloginfo( 'url' ); ?>">
                    <div>
                      <input type="text" class="search" name="s" onblur="if(this.value=='')this.value='请输入关键词...';" onfocus="if(this.value=='请输入关键词...')this.value='';" value="请输入关键词...">
                       <span class="search-remove fa fa-times"></span>
                    </div>
               </form>
             </div>
            </div>
      </div>
      <a href="<?php echo home_url(); ?>">
        <img class="navbar-logo" src="<?php echo get_stylesheet_directory_uri() ?>/images/logo.png" alt="">
      </a>
    </div>
    <nav class="navbar-collapse bs-navbar-collapse collapse" role="navigation" aria-expanded="true">

        <ul class="nav navbar-nav navbar-self-right">

            <!-- top_menu items -->
                <?php
                  if(function_exists('wp_nav_menu')) {
                    wp_nav_menu( array(
                        'menu'              => 'header_menu',
                        'theme_location'    => 'header_menu',
                        'depth'             => 0,
                        'container'         => '',
                        'container_class'   => '',
                        'items_wrap'        => '%3$s',
                        'walker'            => new Leo_Nav_Menu()
                    )   );
                  }
                ?>

        </ul>

         

            <!-- 用户入口结束 -->
        </ul>
    </nav>  <!-- 首页导航结束 -->
</div>
</div>

</header>
</div>
<div class="scroll_page">
    <div class="page first">
        <div class="content">
            <p>this is first page-1</p>
        </div>
        <div class="control">
            <a href="#" class="next">Next</a>
        </div>
    </div>
    <div class="page second">
        <div class="content">
            <p>this is second page--2</p>
        </div>
        <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div>
    </div>
    <div class="page third">
        <div class="content">
            <p>this is third page---3</p>
        </div>
        <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div>
    </div>
    <div class="page fourth">
        <div class="content">
            <p>this is fourth page----4</p>
        </div>
        <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div>
    </div>
    <div class="page fifth">
        <div class="content">
            <p>this is fifth page-----5</p>
        </div>
        <div class="control">
            <a href="#" class="prev">Prev</a>
        </div>
    </div>
</div>
</body>
</html>
