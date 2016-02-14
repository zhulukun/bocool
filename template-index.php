<?php
/**
*  Template Name:首页
*
*/
?>
<?php get_header(); ?>
    
<?php get_header('masthead'); ?>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css" media="screen" type="text/css" />
<div class="scroll_page">
    <div class="page first">
      <div>
        <div class="content">
          <p style="font-size:42px;font-weight:800">体验评价&nbsp &nbsp &nbsp &nbsp 数据至上</p>
        </div>
      </div>
        <div class="row news hidden-xs hidden-sm" style="display: block;">
           <!--    <div class="row">
            <div class="col-md-12 hidden-xs hidden-sm" id="qiniu-news">
              <span class="icon icon-sound"></span>
              <ul class="news-list">
              
                  <li style="display: none;"><a href="#" target="_blank">三丰智合bocool</a></li>
              
                  <li style="display: none;"><a href="#" title="「七牛架构师实践日」这里只谈架构" target="_blank">这里只谈架构</a></li>
              
                  <li style="display: list-item;"><a href="#" title="「青葱创业计划」国内第一个针对大学生创业的技术扶持计划" target="_blank">国内第一个针对大学生创业的技术扶持计划</a></li>
              
              </ul>
              <ul class="news-icon" id="li-bottom">
                
                  
                    <li class="active"></li>
                  
                
                  
                    <li class=""></li>
                  
                
                  
                    <li class=""></li>
                  
                
              </ul>
            </div>
          </div> -->
        </div>


        <!-- <div class="control">
            <a href="#" class="next">Next</a>
        </div> -->

    </div>
   
    <div class="page second">
        <div class="content">
          <div class="row" style="color:#000">
            <div class="col-md-4" style="padding:0 50px;"><p class="second-title">自动化测试</p><p class="second-content">自主研发的测试平台；采用先进高精度的机器臂；自动化的测试；自动化结果存储以及处理。</p></div>
            <div class="col-md-4" style="padding:0 50px;"><p class="second-title">客观数据</p><p class="second-content">有别于主观使用评价，博酷科技采用客观的测试数据分析，评价智能电子的用户体验。</p></div>
            <div class="col-md-4" style="padding:0 50px;"><p class="second-title">全程服务</p><p class="second-content">服务于智能电子的整个生命周期；在研发、生产、用户使用等不同阶段，为厂家和用户提供全程服务</p></div>

          </div>
        </div>

       <!--  <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div> -->
    </div>
    <div class="page third">
        <div class="content">
            <div style="text-align:center">
              <p style="font-size:28px;color:#000000"><b>高效的测试框架，稳定的测试脚本，分布式测试系统</b></p>
              <p style="height:60px;line-height:60px;font-size:16px;color:#ccc">颠覆传统自动化测试模式</p>
              <a class="btn-register" href="http://localhost/web/?page_id=62">查看产品</a>
          </div>
        </div>
       <!--  <div class="control">
            <a href="#" class="next">Next</a>
            <a href="#" class="prev">Prev</a>
        </div> -->
  
</div>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mousewheel.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.scrollpage.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/index.js"></script>
     <script src='<?php echo get_template_directory_uri(); ?>/js/prettify.js'></script>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>
