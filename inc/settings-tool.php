<?php

/*
 * 欢迎来到代码世界，如果你想修改多梦主题的代码，那我猜你是有更好的主意了～
 * 那么请到多梦网络（ http://www.dmeng.net/ ）说说你的想法，数以万计的童鞋们会因此受益哦～
 * 同时，你的名字将出现在多梦主题贡献者名单中，并有一定的积分奖励～
 * 注释和代码同样重要～
 * @author 多梦 @email chihyu@aliyun.com 
 */
 
/*
 * 主题设置页面 - 高级工具 @author 多梦 at 2014.06.23 
 * 
 */

function dmeng_options_tool_page(){
	
	$themes = wp_get_themes(array( 'errors' => false , 'allowed' => null ));

  if( isset($_POST['action']) && sanitize_text_field($_POST['action'])=='clear' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) :

	$nonce = explode("+", trim($_POST['nonce_title']));
	if( $nonce[0]==__('我确认操作','dmeng') && wp_verify_nonce( $nonce[1], 'check-captcha' ) ) {
		
		global $wpdb;

		//~ 删除主题自己建的表
		$table_message = $wpdb->prefix . 'dmeng_message';   
		$wpdb->query("DROP TABLE IF EXISTS ".$table_message);
		
		$table_meta = $wpdb->prefix . 'dmeng_meta';   
		$wpdb->query("DROP TABLE IF EXISTS ".$table_meta);
		
		$table_tracker = $wpdb->prefix . 'dmeng_tracker';   
		$wpdb->query("DROP TABLE IF EXISTS ".$table_tracker);

		//~ 清理在WordPress表格中的数据
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'dmeng_%'" );
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type LIKE 'gift'" );
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'dmeng_%'" );
		$wpdb->query( "DELETE FROM $wpdb->term_taxonomy WHERE taxonomy LIKE 'gift_tag'" );
		$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'dmeng_%'" );
		$wpdb->query( "DELETE FROM $wpdb->commentmeta WHERE meta_key LIKE 'dmeng_%'" );

		wp_cache_flush();

		//~ 切换到其他主题
		if( isset($_POST['theme']) ) {
			foreach(	$themes as $theme_name=>$theme_data ){
				if( $theme_data->stylesheet == $_POST['theme'] ){
					switch_theme( $theme_name );
					printf("<script>window.location.href='%s';</script>", admin_url('themes.php?activated=true'));
					exit;
				}
			}
		}

	}else{
		
		dmeng_settings_error('error',__('验证码有误，请重试。','dmeng'));
		
	}

  endif;
  
	if( isset($_POST['action']) && sanitize_text_field($_POST['action'])=='refresh' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
		
		dmeng_refresh_all();
		
		dmeng_settings_error('updated',__('缓存已清理','dmeng'));
	}
	
	if( isset($_POST['action']) && sanitize_text_field($_POST['action'])=='check-version' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
		
		if( dmeng_check_version_do_this_daily() ){
			dmeng_settings_error('updated', sprintf(__('版本检查成功，获取到的最新版本是：%s','dmeng'), get_option('dmeng_theme_upgrade')));
		}else{
			dmeng_settings_error('error', __('版本检查失败，请重试！','dmeng'));
		}

	}

	$tab = 'about';
	if( isset($_GET['tab'])){
		if(in_array($_GET['tab'], array('clear','about','refresh', 'version'))) $tab = $_GET['tab'];
	}
	$dmeng_tabs = array(
		'about' => __('关于', 'dmeng'),
		'refresh' => __('清理缓存', 'dmeng'),
		'clear' => __('主题数据清理', 'dmeng'),
		'version' => __('版本升级', 'dmeng')
	);
	$tab_output = '<h2 class="nav-tab-wrapper">';
	foreach( $dmeng_tabs as $tab_key=>$tab_name ){
		$tab_output .= sprintf('<a href="%s" class="nav-tab%s">%s</a>', add_query_arg('tab', $tab_key), $tab_key==$tab ? ' nav-tab-active' : '', $tab_name);
	}
	$tab_output .= '</h2>';
	

	?>
<div class="wrap">
	<h2><?php _e('多梦主题设置','dmeng');?></h2>
	<form method="post">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
		<?php echo $tab_output;?>
<div style="border:1px solid #e5e5e5;padding:15px;background:#fff;margin:15px 0;">
		<?php if($tab=='clear'){ ?>
		<input type="hidden" name="action" value="clear">
		<p><?php _e('多梦主题数据包括版权声明、幻灯片、浏览次数、投票数据、消息、积分、礼品等。这些数据属于多梦主题私有。','dmeng');?></p>
		<p><?php _e('清理范围包括： 删除 dmeng_message、dmeng_meta、dmeng_tracker 三个表，删除 options、postmeta、usermeta、commentmeta 表以 dmeng_ 开头为 key 的数据和 gift 文章类型和 gift_tag  分类法。注：多梦主题在 wordpress table 中存储的全部数据的 key 都是以 dmeng_ 开头的。','dmeng');?></p>
		<p style="color:#d98500;"><?php _e('选择清理数据后要切换到的主题。如果选择的是多梦主题，则相当于清理现有数据，重新启用多梦主题。','dmeng');?></p>
<?php

		$themes_output = '<select name="theme">';
		foreach(	$themes as $theme_name=>$theme_data ){
			$themes_output .= '<option value="'.$theme_data->stylesheet.'">'.$theme_data->stylesheet.'</option>';
		}
		$themes_output .= '</select>';
		
		echo $themes_output;
?>
		<p style="color:#0074a2;"><?php _e('如果你确定清理并停用多梦主题，请按提示输入”我确认操作”+验证字符的组合（+号也要输入），然后点击清理并停用。','dmeng');?></p>
		<p><?php
		//~ 把一段中文这样分开是防止本地化之后无法验证文字
		_e('请输入：','dmeng');
		_e('我确认操作','dmeng');
		echo '+'.wp_create_nonce('check-captcha');?></p>
		<p><input name="nonce_title" type="text" id="nonce_title" value="" class="regluar-text ltr"> <span style="color:#dd3d36;"><?php _e('请先备份数据库，以防不测。','dmeng');?></span></p>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary confirm" value="<?php _e( '清理并停用', 'dmeng' );?>"></p>
		<p><?php _e('清理WordPress冗余数据（如修订版本、回收站中的文章/垃圾评论等），推荐使用 WP Clean Up  。','dmeng');?>
		
	</form>

<script type="text/javascript">
jQuery(document).ready(function($){
	$('#nonce_title').bind("paste", function(e) {
		alert('<?php _e('为了您的数据安全，请不要直接复制粘贴！','dmeng');?>');
		e.preventDefault();
	});
	jQuery('.confirm').live('click',function(event){
		var r = confirm( '<?php _e('确定要清理吗？你备份数据库了吗？本操作不可逆！','dmeng');?>' );
		if ( r == false ) return false;
    });
});
</script>
	<?php }
	
	if($tab=='about'){ ?>
	<h3>关于我们</h3>
	<p>趣火星——有趣的智能硬件社群，传播科技的乐趣，过有趣的科技生活。</p>
	<p>更多请关注我们的网站主页：<a href="http://www.7huoxing.com/" target="_blank">http://www.7huoxing.com/</a></p>

	<h3>联系我们</h3>
	<p>QQ：338975658 邮箱：qupukeji@163.com   /  ceo@7huoxing.com</p>

	<h3>来自火星人的声明</h3>
	<p>趣火星从2014年8月25日（前身是趣普科技，2014年12月5日正式运营，改名为趣火星。我们是处女座…..）</p>
	<p>上线以来，由一个小网站渐渐茁壮，我们一直坚持与智能硬件爱好者们分享最新最酷的产品资讯和测评。</p>
    <p>我们要表达的就是【让科技有趣，让大众爱上科技】，我们不局限于专业圈，而是由大众的角度，去看那些有趣的科技产品。</p>
	<p>目前我们聚集了数百名极客、少女、智能硬件爱好者，为大众测评产品，撰写文章。</p>
	<p>可以说趣火星真正的是一个社群媒体，因为70%内容都是由意见领袖UGC产生。</p>
	<p>我们希望【让大众自己发现大众热爱的智能硬件】。而我们影响的受众，是所有将会使用智能硬件，以及将被智能硬件改变生活的数十亿普通人。</p>
	<p>我们是火星人，感谢支持我们，希望有一天可以带大家：共赴火星！</p>
	
	<?php }
	
	if($tab=='refresh'){
	?>
	<p style="color:#0074a2;"><?php _e('如果站点启用了内存对象缓存，会使用对象缓存缓存数据，否则保存成一个字段到数据库中以减少查询。建议配置 Memcached 对象缓存！','dmeng');?></p>
	<p><?php _e('该主题有以下几个自定义项目使用 Transients API 缓存数据。','dmeng');?></p>
	<ol>
		<li><?php _e('导航菜单','dmeng');?></li>
		<li><?php _e('首页分类列表','dmeng');?></li>
		<li><?php _e('小工具（最近登录用户、文章排行榜、积分排行榜、站点统计）','dmeng');?></li>
	</ol>
	<p><?php _e('一般情况下，导航菜单缓存在更新菜单时会更新，首页分类列表缓存在更新首页设置时更新，小工具缓存在更新该小工具时会更新（最近登录用户在有用户登录时也会更新缓存），除此之外，全站内容缓存会每隔一小时更新一次。所以，手动刷新缓存几乎是没有必要的，仅仅是备用。','dmeng');?></p>
	<input type="hidden" name="action" value="refresh">
	<p class="submit"><input type="submit" name="submit" id="submit" class="button" value="<?php _e( '点击清理缓存', 'dmeng' );?>"></p>
	<?php _e('清理范围包括对象缓存、 Transients、固定链接缓存等。谨慎操作！','dmeng');?>
	<?php
	}
	
	if($tab=='version'){
	?>
	

	
	<p style="color:#0074a2;"><?php 

	printf(__('当前版本是：%s ', 'dmeng'), get_option('dmeng_version') );
	
	printf(__('上次检查版本是：%s ', 'dmeng'), get_option('dmeng_theme_upgrade') );
	
	$time = wp_next_scheduled( 'dmeng_check_version_daily_event' );
	if( $time ){
		printf(__('下次自动检查时间是：%s', 'dmeng'), date('Y-m-d H:i:s', $time));
	}
	?></p>

	<p><?php _e('如果你已经升级，但还是提示升级，你可以点击这里刷新版本数据。','dmeng');?></p>
	<input type="hidden" name="action" value="check-version">
	<p><input type="submit" name="submit" id="submit" class="button" value="<?php _e( '刷新版本数据', 'dmeng' );?>"></p>
	
	

	<?php
	}
	?>
</div>
</div>
	<?php
}
