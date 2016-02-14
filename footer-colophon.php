<?php
/*
 * 欢迎来到代码世界，如果你想修改多梦主题的代码，那我猜你是有更好的主意了～
 * 那么请到多梦网络（ http://www.dmeng.net/ ）说说你的想法，数以万计的童鞋们会因此受益哦～
 * 同时，你的名字将出现在多梦主题贡献者名单中，并有一定的积分奖励～
 * 注释和代码同样重要～
 * @author 多梦 @email chihyu@aliyun.com 
 */
 ?>
</div>  <!--huoxing-body end-->
<div class="huoxing-footer" style="z-index:1500">
<footer id="leo-footer">
  <section class="container" id="colophon" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
      <div class="row">
          <div class="col-md-offset-2 col-md-4 col-sm-5 col-xs-12">
          <!-- footer-menu items -->
                <?php 
                  if(function_exists('wp_nav_menu')) {
                      wp_nav_menu( array(
            						'theme_location'    => 'footer_menu',
            						'menu_class'        => 'about'
            					)	);
                  }
                ?>
           </div> 
        
          <!-- <span><?php //echo stripslashes( get_option( 'leo_footer' ) ); ?></span> -->
          <div class="col-md-6 col-sm-7 col-xs-12">
              <p class="leo-copyright"><?php $general_setting = $GLOBALS['dmeng_general_setting'];echo $general_setting['footer_copyright']; ?> <span class="hidden-xs"><?php echo get_option( 'zh_cn_l10n_icp_num' );?></span> </p>   
          </div>
    </div>
  </section>

<?php echo do_shortcode("[Alimir_BootModal_Login]");?>

 <!--统计代码开始-->
   <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253196591'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s13.cnzz.com/z_stat.php%3Fid%3D1253196591%26' type='text/javascript'%3E%3C/script%3E"));</script>
      <!--统计代码结束-->  			
	<!-- 	</div>
	</section>  -->
</footer>
