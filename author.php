<?php

/*
 * 欢迎来到代码世界，如果你想修改多梦主题的代码，那我猜你是有更好的主意了～
 * 那么请到多梦网络（ http://www.dmeng.net/ ）说说你的想法，数以万计的童鞋们会因此受益哦～
 * 同时，你的名字将出现在多梦主题贡献者名单中，并有一定的积分奖励～
 * 注释和代码同样重要～
 * @author 多梦 @email chihyu@aliyun.com 
 */

global $wp_query, $wp_version;
$curauth = $wp_query->get_queried_object();
$user_name = filter_var($curauth->user_url, FILTER_VALIDATE_URL) ? '<a href="'.$curauth->user_url.'" target="_blank" rel="external">'.$curauth->display_name.'</a>' : $curauth->display_name;
$follower_count = getFollowNum($curauth->ID,'hx_follower');
$following_count = getFollowNum($curauth->ID,'hx_following');
$posts_count =  $wp_query->found_posts;
$comments_count = get_comments( array('status' => '1', 'user_id'=>$curauth->ID, 'count' => true) );
$user_info = get_userdata($curauth->ID);
$credit = intval($user_info->dmeng_credit);
$credit_void = intval($user_info->dmeng_credit_void);

$is_gift_open = intval(get_option('dmeng_is_gift_open', 0));
$gifts_num = $is_gift_open ? get_dmeng_user_gifts($curauth->ID, true) : 0;

$up_posts_count = intval(get_dmeng_user_vote($curauth->ID, true, 'post', 'up'));
$up_comments_count = intval(get_dmeng_user_vote($curauth->ID, true, 'comment', 'up'));
$up_count = $up_posts_count+$up_comments_count;

$current_user = wp_get_current_user();
$oneself = $current_user->ID == $curauth->ID ? 1 : 0;

$featured_img_html = '';
$post_statuses = get_post_statuses();
unset($post_statuses['private']);
if( current_user_can('publish_posts')==false ) unset($post_statuses['publish']);

$tabs = array(
		'post' => __('<span class="label">文章</span><span class="value">', 'dmeng')."$posts_count".__('</span>'),
		'comment' => __('<span class="label">评论</span><span class="value">', 'dmeng')."$comments_count".__('</span>'),
		'like' => __('<span class="label">赞</span><span class="value">', 'dmeng')."$up_count".__('</span>'),
		//'credit' => __('积分', 'dmeng')."($credit)",
		//'gift' => __('礼品', 'dmeng')."($gifts_num)",
		'message' => __('消息', 'dmeng'),
		'profile' => __('资料', 'dmeng'),
		'follow' =>__('<span class="label">关注</span><span class="value">', 'dmeng')."$following_count".__('</span>'),
		'fans' =>__('<span class="label">粉丝</span><span id="fans_count" class="value">', 'dmeng')."$follower_count".__('</span>'),
);

foreach( $tabs as $tab_key=>$tab_value ){
	if( $tab_key=='gift' && $is_gift_open==0 ) $tab_key = '';
	if( $tab_key ) $tab_array[] = $tab_key;
}

$get_tab = isset($_GET['tab']) && in_array($_GET['tab'], $tab_array) ? $_GET['tab'] : 'post';

	//~ 提示
	$message = $pages = '';
	
	if($get_tab=='profile' && ($current_user->ID!=$curauth->ID && current_user_can('edit_users')) ) $message = sprintf(__('你正在查看的是%s的资料，修改请慎重！', 'dmeng'), $curauth->display_name);
	
	//~ 积分start
	
	if( isset($_POST['creditNonce']) && current_user_can('edit_users') ){
		if ( ! wp_verify_nonce( $_POST['creditNonce'], 'credit-nonce' ) ) {
			$message = __('安全认证失败，请重试！','dmeng');
		}else{
			$c_user_id =  $curauth->ID;
			if( isset($_POST['creditChange']) && sanitize_text_field($_POST['creditChange'])=='add' ){
				$c_do = 'add';
				$c_do_title = __('增加','dmeng');
			}else{
				$c_do = 'cut';
				$c_do_title = __('减少','dmeng');
			}

			$c_num =  intval($_POST['creditNum']);
			$c_desc =  sanitize_text_field($_POST['creditDesc']);
			
			$c_desc = empty($c_desc) ? '' : __('备注','dmeng') . ' : '. $c_desc;

			update_dmeng_credit( $c_user_id , $c_num , $c_do , 'dmeng_credit' , sprintf(__('%1$s将你的积分%2$s %3$s 分。%4$s','dmeng') , $current_user->display_name, $c_do_title, $c_num, $c_desc) );
			
			$message = sprintf(__('操作成功！已将%1$s的积分%2$s %3$s 分。','dmeng'), $user_name, $c_do_title, $c_num);
		}
	}
	
	//~ 积分end
	
	//~ 私信start
	$get_pm = isset($_POST['pm']) ? trim($_POST['pm']) : '';
	if( isset($_POST['pmNonce']) && $get_pm && is_user_logged_in() ){
		if ( ! wp_verify_nonce( $_POST['pmNonce'], 'pm-nonce' ) ) {
			$message = __('安全认证失败，请重试！','dmeng');
		}else{
			$pm_title = json_encode(array(
				'pm' => $curauth->ID,
				'from' => $current_user->ID
			));
			if( add_dmeng_message( $curauth->ID, 'unrepm', '', $pm_title, wp_strip_all_tags($get_pm) ) ){
				//~ 发邮件通知
				if(is_email($curauth->dmeng_verify_email)){
					$m_headline = sprintf(__('%1$s给你发来了私信','dmeng'), $current_user->display_name);
					$m_content = '<h3>'.sprintf( __('%1$s，你好！','dmeng'), $curauth->display_name ).'</h3><p>'.sprintf( __('%1$s给你发来了<a href="%2$s" target="_blank">私信</a>，快去看看吧：<br> %3$s','dmeng'), $current_user->display_name, htmlspecialchars( add_query_arg('tab', 'message', get_author_posts_url( $curauth->ID )) ), $get_pm).'</p>';
					dmeng_send_email( $curauth->dmeng_verify_email, $m_headline, $m_content );
				}
				$message = __('发送成功！','dmeng');
			}
		}
	}
	
	//~ 私信end

//~ 页码start
$paged = max( 1, get_query_var('page') );
//每页显示的条目数
$number = 15;
$offset = ($paged-1)*$number;
//~ 页码end
$item_html = '<li class="tip">'.__('没有找到相关记录','dmeng').'</li>';

	//~ 个人资料
if( $oneself ){
	
	$user_id = $curauth->ID;
	$avatar = $user_info->dmeng_avatar;
	$qq = dmeng_is_open_qq();
	$weibo = dmeng_is_open_weibo();
	
	$is_auto_userlogin = false;
	if( dmeng_check_auto_userlogin('qq', $user_id, $curauth->user_login) || dmeng_check_auto_userlogin('weibo', $user_id, $curauth->user_login) ) $is_auto_userlogin = true;

	if( isset($_POST['update']) && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
		
		$message = __('没有发生变化。','dmeng');
		
		$update = sanitize_text_field($_POST['update']);
		
		if($update=='info'){
			
			$new_user_info = array(
				'ID' => $user_id,
				'description' => wp_trim_words($_POST['description'], 140, '……')
			 );

			 if(filter_var($_POST['url'], FILTER_VALIDATE_URL))
				$new_user_info['user_url'] = esc_url($_POST['url']);

			$new_display_name = (!empty($_POST['display_name']) && sanitize_text_field($_POST['display_name'])) ? sanitize_text_field($_POST['display_name']) : '';
			if($new_display_name){
				$new_user_info['nickname'] = $new_display_name;
				$new_user_info['display_name'] = $new_display_name;
			}

			$new_user_login = '';
			
			if(!empty($_POST['user_login']))
				$new_user_login = sanitize_user(trim($_POST['user_login']));

			if($is_auto_userlogin &&
				$new_user_login && 
				$new_user_login!=$curauth->user_login && 
				dmeng_check_auto_userlogin('qq', $user_id, $new_user_login)==false && 
				dmeng_check_auto_userlogin('weibo', $user_id, $new_user_login)==false
				){

				//~ 黑名单检查
				if($new_user_login!=false)
					$new_user_login = dmeng_check_blacklist( $new_user_login ) ? false : $new_user_login;

				//~ 重复用户名检查
				if($new_user_login!=false)
					$new_user_login = username_exists( $new_user_login ) ? false : $new_user_login;

				if($new_user_login){
					global $wpdb;
					$wpdb->update($wpdb->users, array('user_login' => $new_user_login), array('ID' => $user_id));
					$new_user_info['user_nicename'] = $new_user_login;
				}
			}
			
			$update_user_id = wp_update_user( $new_user_info );
			$update_user_avatar = update_user_meta( $user_id , 'dmeng_avatar', sanitize_text_field($_POST['avatar']) );
			
			if ( ! is_wp_error( $update_user_id ) || $update_user_avatar ) $message = __('基本信息已更新','dmeng');
			
			if($is_auto_userlogin && $new_user_login==false) $message .= sprintf(__('（用户名没有更新，因为 %s 是非法用户名。（可能已经被其他用户使用或已被添加到黑名单）','dmeng'), sanitize_user(trim($_POST['user_login'])) );
		}
		
		if($update=='pass'){
			$pass_update = dmeng_profile_pass_update( $user_id, $_POST['email'], $_POST['pass1'], $_POST['pass2'] );
			if( $pass_update ) $message = $pass_update;
		}
		
		if( $update=='destroy-sessions' && version_compare($wp_version, '4.0.0', '>=') ){
			wp_destroy_other_sessions();
			$message = __('其他会话已清理。','dmeng');
		}
		
		$message .= ' <a href="'.dmeng_get_user_url($get_tab, $curauth->ID).'">'.__('点击刷新','dmeng').'</a>';
		
		$user_info = get_userdata($curauth->ID);

	}
}
	
//~ 个人资料end

//~ 投稿start

if( isset($_GET['action']) && in_array($_GET['action'], array('new', 'edit')) && $oneself ){
	
	if( isset($_GET['id']) && is_numeric($_GET['id']) && get_post($_GET['id']) && intval(get_post($_GET['id'])->post_author) === get_current_user_id() ){
		$action = 'edit';
		$the_post = get_post($_GET['id']);
		$post_title = $the_post->post_title;
		$post_content = $the_post->post_content;
		$tags 				= wp_get_post_tags( $_GET['id'], array( 'fields' => 'names' ) );
		$featured_img_id 		= get_post_thumbnail_id( $_GET['id'] );
		$featured_img_html 	= (!empty($featured_img_id))?wp_get_attachment_image( $featured_img_id, array(300,200) ):'';
		if(isset($tags) && is_array($tags)) $post_tags = implode(', ', $tags);
	}else{
		$action = 'new';
		$post_title = !empty($_POST['post_title']) ? $_POST['post_title'] : '';
		$post_content = !empty($_POST['post_content']) ? $_POST['post_content'] : '';
	    $post_tags = !empty($_POST['post_tags']) ? $_POST['post_tags'] : '';
	    $featured_img_id = !empty($_POST['featured_img_id']) ? $_POST['featured_img_id'] : '-1';
	    $featured_img_html 	= ($featured_img_id !=-1 )?wp_get_attachment_image( $_POST['featured_img_id'], array(300,200) ):'';
	}
  
	if( isset($_POST['action']) && trim($_POST['action'])=='update' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {

		$title = sanitize_text_field($_POST['post_title']);
		$content = $_POST['post_content'];
		$cat = (!empty($_POST['post_cat'])) ? $_POST['post_cat'] : '';
		$cat = in_array($cat, (array)json_decode(get_option('dmeng_can_post_cat')) ) ? $cat : '';
		$tags = sanitize_text_field( $_POST['post_tags'] );
		$featured_img_id = intval($_POST['featured_img_id']);
		if( $title && $content ){
			
			$content_length = mb_strlen($content,'utf8');
			$content_min_length = intval(get_option('dmeng_post_min_strlen', 140));
			$content_max_length = intval(get_option('dmeng_post_max_strlen', 12000));
			
			if( $content_length<$content_min_length || $content_length>$content_max_length ){
				
				$message = sprintf(__('发表失败，文章内容至少%1$s字，最多%2$s字。','dmeng'), $content_min_length, $content_max_length );
				
			}else if ($featured_img_id == -1) {
				$message = __('发表失败，文章缺少特色图片，请添加^_^。','dmeng');
			}else{
				
				$status = sanitize_text_field($_POST['post_status']);
				
				if( $action==='edit' ){

					$new_post_id = wp_update_post( array(
						'ID' => intval($_GET['id']),
						'post_title'    => $title,
						'post_content'  => $content,
						'post_status'   => ( isset($post_statuses[$status]) ? $status : 'draft' ),
						'post_author'   => get_current_user_id(),
						'post_category' => $cat,
						'tags_input'  	=> $tags
					) );


				}else{

					$new_post_id = wp_insert_post( array(
						  'post_title'    => $title,
						  'post_content'  => $content,
						  'post_status'   => ( isset($post_statuses[$status]) ? $status : 'draft' ),
						  'post_author'   => get_current_user_id(),
						  'post_category' => $cat,
						  'tags_input'    => $tags
						) );

				}
				
				if( is_wp_error( $new_post_id ) ){
					$message = __('操作失败，请重试或联系管理员。','dmeng');
				}else{
					//设置特色图片
					
			        set_post_thumbnail( $new_post_id, $featured_img_id );
					
					update_post_meta( $new_post_id, 'dmeng_copyright_content', htmlspecialchars($_POST['post_copyright']) );
					
					wp_redirect(dmeng_get_user_url('post'));
				}

			}
		}else{
			$message = __('发表失败，文章标题和内容不能为空！','dmeng');
		}
	}
}
//~ 投稿end
	
	

get_header();

get_header('masthead'); 

?>

<section id="leo-bodyer">
<div id="main" class="container">
	<div class="row">

	<div id="content" class="col-md-12 col-sm-12" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
			<?php echo dmeng_adsense('author','top');?>
	  <div class="<?php echo apply_filters('dmeng_archive_panel_class', 'author-page');?>">
		<div class="panel-body hx-panel-body hx-top-panel-body">
			<div class="author-page-header" id="author-page-header" itemscope itemtype="http://schema.org/Person">
				<p class="author-photo-wrap">
					<?php echo dmeng_get_avatar( $curauth->ID , '100' , dmeng_get_avatar_type($curauth->ID) ); ?>
				</p>
			</div>
			<div class="author-page-description">
			  <div class="text-muted">
				<h3 class="user-display-name"><?php echo '<span itemprop="name">'.$user_name.'</span>';?></h3>
				<p class="small user-personal-description"><?php
				$description = $curauth->description;
						echo $description ? $description : __('没有个人说明','dmeng');
				 ?></p>
                 <div class="hx_opt_box">
                   <div class="hx_box clearfix">
	                 	<div class="hx_box_d  pull-left">            	   
						   	<input id="followUserId" type="hidden" value="<?php echo $curauth->ID ?> "/>
							<?php $nonce = wp_create_nonce('hx_follow_'.$current_user->ID.'_'.$curauth->ID); ?>
							<input id="hx_nonce" type="hidden" value="<?php echo $nonce; ?>" />
							<?php if(!is_user_logged_in()) {?> 
 							     <a href="javascript:void(0);" data-toggle="modal" data-target="#login_huoxing" class="hx_btn_c follow"><i class="fa fa-user-plus"></i>关注</a>
							<?php } else if ( !$oneself && !isfollow($current_user->ID, $curauth->ID) ) { ?>
								<a href="javascript:void(0);" data-action="ajaxfollow" class="isfollow hx_btn_c follow" ><i class="fa fa-user-plus"></i>关注</a>
							<?php } else if(!$oneself && isfollow($current_user->ID, $curauth->ID)){ ?>
								<a href="javascript:void(0);" data-action="ajaxunfollow" class="isfollow hx_btn_c unfollow"><i class="fa fa-user-times"></i>已关注</a>
							<?php } ?>
	                 	</div>
	                 	<div class="hx_box_d  pull-left">
	                 	    <?php if( !is_user_logged_in() ) {?> 
	                 	    	<small class="pm"><a class="hx_btn_d" href="javascript:void(0);" data-toggle="modal" data-target="#login_huoxing"><i class="fa fa-paper-plane-o"></i>私信</a></small>
					        <?php }else if( !$oneself ) { ?>
					        <small class="pm"><a class="hx_btn_d" href="<?php echo  add_query_arg('tab', 'message', get_author_posts_url( $curauth->ID )); ?>"><i class="fa fa-paper-plane-o"></i>私信</a></small>
					        <?php }?>

					    </div>
						 <input id="currentUserId" type="hidden" value="<?php echo $current_user->ID ?>"/>
				    </div>
                 </div> 
                 	
			  </div>
			</div>
<?php

	$tab_output = '';
	foreach( $tab_array as $tab_term ){
		if( $tab_term=="message" || $tab_term=="profile") continue;
		$class = $get_tab==$tab_term ? ' class="active" ' : '';
		$tab_output .= sprintf('<li%s><a href="%s">%s</a></li>', $class, add_query_arg('tab', $tab_term, esc_url( get_author_posts_url( $curauth->ID ) )), $tabs[$tab_term]);
	}
	echo '<div class="author-tab clearfix"><ul>'.$tab_output.'</ul></div></div><div class="hx-panel-body">';
	if($message) echo '<div class="alert alert-success" role="alert">'.$message.'</div>'; 
	
//关注或粉丝列表start
if($get_tab=='follow' || $get_tab=='fans'){
	if ($get_tab =='follow') {
		$total_f = $following_count;
	}else {
		$total_f = $follower_count; 
	}

	$pages = ceil($total_f/$number);

	$follow_list = huoxing_follow_list_info($curauth->ID, $get_tab, $number, $offset);
	if(!empty($follow_list)){
		echo "<div class=\"panel-body\"><div class=\"row\" id=\"author-follow-list\">";
		foreach ($follow_list as $key => $value) {
			echo "<div class=\"col-md-4 follow-list-item\">";
			echo  "<a class=\"follow-avatar\" href='".$value['url']."'>".dmeng_get_avatar( $value['id'] , '80' , dmeng_get_avatar_type($value['id']) )."</a>";
			echo "<div class=\"follow-list-container\">";
			echo "<h3 class=\"follow-list-name\"><span><a href='".$value['url']."'>".$value['name']."</a><span></h3>";
			echo "<p class=\"follow-list-info\"><span>注册于<span>".date( __('Y.m.d','dmeng'), strtotime( $value['time'] ) )."</a>";
			?>
		<div class="pull-left">
			<input id="followUserId" type="hidden" value="<?php echo $value['id']; ?>"/>
			<?php $nonce = wp_create_nonce('hx_follow_'.$current_user->ID.'_'.$value['id']); ?>
			<input id="hx_nonce" type="hidden" value="<?php echo $nonce; ?>" />
			<?php if(!is_user_logged_in()) {?> 
					 <a href="javascript:void(0);" data-toggle="modal" data-target="#login_huoxing" class="hx_btn_e follow"><i class="fa fa-user-plus"></i>关注</a>
			<?php } else if ( !isfollow($current_user->ID, $value['id']) && $current_user->ID != $value['id'] ) { ?>
				<a  href="javascript:void(0);" data-action="ajaxfollow" class="isfollow hx_btn_e follow" ><i class="fa fa-user-plus"></i>关注</a>
			<?php } else if( isfollow($current_user->ID, $value['id']) && $current_user->ID != $value['id'] ){ ?>
				<a href="javascript:void(0);" data-action="ajaxunfollow" class="isfollow hx_btn_e unfollow"><i class="fa fa-user-times"></i>已关注</a>
			<?php } ?>
		</div>

		<?php    
			echo "</div></div>";
		}
		echo "</div></div>";
		$item_html = "";
		if($pages > 1) {
			$item_html = '<li class="hx-special-li">'.dmeng_pager($paged, $pages).'</li><li class="bottom-tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','dmeng'),$paged, $pages, $number).'</li>';
	        echo '<ul id="author-message">'.$item_html.'</ul>';
	    }
	} else{
		echo '<ul id="author-message">'.$item_html.'</ul>';
	}

	
}
//关注 或粉丝列表end	
		
//~ 默认文章列表start
if( $get_tab=='post' ) {
	
	$can_post_cat = json_decode(get_option('dmeng_can_post_cat'));
	$cat_count = count($can_post_cat);

	if( isset($_GET['action']) && in_array($_GET['action'], array('new', 'edit')) && $cat_count && is_user_logged_in() && $oneself && !current_user_can('subscriber') ){
		
		echo '<ul id="author-message"><li class="tip">'.__('请发表您自己的文章', 'dmeng').'</ul></li>';
?>
			<article class="panel panel-default archive" role="main">
					<div class="panel-body">
						<h3 class="page-header hx-page-header"><?php _e('让写作，从现在开始...','dmeng');?></h3>
						<form role="form" method="post" class="publish-post-form">
							<div class="form-group">
								<input type="text" class="form-control" name="post_title" placeholder="<?php _e('请在此输入文章标题','dmeng');?>" value="<?php echo $post_title;?>" aria-required='true' required>
							</div>
							<div class="form-group">
								<?php wp_editor(  wpautop($post_content), 'post_content', array('media_buttons'=>true, 'quicktags'=>false,  'editor_class'=>'form-control', 'editor_css'=>'<style>.wp-editor-container{border:1px solid #ddd;}</style>' ) ); ?>
							</div>
							<div class="form-group">
								<div id="hx-featured-image">
									<div id="hx-featured-image-container" style="margin-bottom:10px;"><?php echo $featured_img_html; ?></div>
									<a id="hx-featured-image-link" href="#" class="btn btn-primary">添加特色图片</a>
									<input type="hidden" name="featured_img_id" id="hx-featured-image-id" value="<?php echo $featured_img_id; ?>"/>
								</div>
							</div>
							<div class="form-group">
						<?php
							$can_post_cat = json_decode(get_option('dmeng_can_post_cat'));
							if($can_post_cat){
								$post_cat_output = '<p class="help-block">'.__('选择文章分类', 'dmeng').'</p>';
								$post_cat_output .= '<select name="post_cat[]" class="form-control">';
								foreach ( $can_post_cat as $term_id ) {
									$category = get_category( $term_id );
									$post_cat_output .= '<option value="'.$category->term_id.'">'. $category->name.'</option>';
								}
								$post_cat_output .= '</select>';
								echo $post_cat_output;
							}
						?>
							</div>
							<div class="form-group">
							    <p class="help-block">输入文章标签</p>
							    <input type="text" class="form-control" name="post_tags" placeholder="<?php _e('多个标签请用英文逗号（,）分开','dmeng');?>" value="<?php echo $post_tags ? $post_tags : '';?>" aria-required='true' required>
							</div>
							<!--<div class="form-group">
								<p class="help-block"><?php //_e('文章来源或版权信息，{link}代表文章链接，{title}代表文章标题，{url}代表本站首页地址，{name}代表本站名称', 'dmeng');?></p>
								<?php
								//$cc = '';
								//if(isset($_GET['id'])) $cc = get_post_meta( intval($_GET['id']), 'dmeng_copyright_content', true );
								//$copyright_content = $cc ? $cc : get_option('dmeng_copyright_content_default',sprintf(__('原文链接：%s，转发请注明来源！','dmeng'),'<a href="{link}" rel="author">{title}</a>'));
								?>
								<textarea name="post_copyright" rows="1" cols="50" class="form-control"><?php //echo stripcslashes(htmlspecialchars_decode($copyright_content));?></textarea>
							</div> -->
							<div class="form-group text-right">
								<select name="post_status">
									<?php
									foreach( $post_statuses as $post_status_key=>$post_status_title ){
										echo '<option value ="'.$post_status_key.'">'.$post_status_title.'</option>';
									}
									?>
								</select>
								<input type="hidden" name="action" value="update">
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
								<button type="submit" class="btn btn-success"><?php _e('发布','dmeng');?></button>
							</div>	
						</form>
					</div>
			 </article>
<?php
		
	}else{

		if($cat_count){

			$item_html = sprintf( __('现有%s个分类接受投稿。', 'dmeng'), $cat_count );
			
			if( is_user_logged_in() && current_user_can('subscriber') ){
					
					$item_html .= __('遗憾的是，你现在登录的账号没有投稿权限！', 'dmeng');
					
				}else{
					
					$item_html .= '<a href="'.( is_user_logged_in() ? add_query_arg(array('tab'=>'post','action'=>'new'), get_author_posts_url($current_user->ID)) : wp_login_url(get_author_posts_url($curauth->ID)) ).'" target="_blank">'.__('点击投稿', 'dmeng').'</a>';
			}
		}else{

			if( have_posts() ) $item_html = sprintf( __('发表了 %s 篇文章', 'dmeng'), $posts_count );
		}
		
	echo '<ul id="author-message"><li class="tip">'.$item_html.'</ul></li>';
	echo "</div><div class=\"archive author-post-archive\" id=\"mainstay\">";
	echo "<div id=\"article-list\" class=\"row\">";
	global $wp_query;
	$args = is_user_logged_in() && $oneself ? array_merge( $wp_query->query_vars, array( 'post_status' => array( 'publish', 'pending', 'draft' ) ) ) : $wp_query->query_vars;
	query_posts( $args );
	while ( have_posts() ) : the_post();
		get_template_part('content','archive');
	endwhile; // end of the loop. 
    echo "</div>";
	//分页开始
    if ( function_exists( 'show_paginate' ) ) { leo_paginate(); }
    //分页结束
       
	
	}
}
//~ 默认文章列表end

//~ 评论start
if( $get_tab=='comment' ) {

	$comments_status = $oneself ? '' : 'approve';
	
	$all = get_comments( array('status' => '', 'user_id'=>$curauth->ID, 'count' => true) );
	$approve = get_comments( array('status' => '1', 'user_id'=>$curauth->ID, 'count' => true) );
	
	$pages = $oneself ? ceil($all/$number) : ceil($approve/$number);

	$comments = get_comments(array(
		'status' => $comments_status,
		'order' => 'DESC',
		'number' => $number,
		'offset' => $offset,
		'user_id' => $curauth->ID
	));

	if($comments){
		$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 条评论，其中 <em>%2$s</em> 条已获准， <em>%3$s</em> 条待审核。','dmeng'),$all, $approve, $all-$approve) . '</li>';
		foreach( $comments as $comment ){
			$item_html .= ' <li>';
			if($comment->comment_approved!=1) $item_html .= '<small class="text-danger">'.__( '这条评论正在等待审核','dmeng' ).'</small>';
			$item_html .= '<div class="message-content">' . get_comment_text($comment->comment_ID) . '</div>';
			$item_html .= '<a class="info" href="'.htmlspecialchars( get_comment_link( $comment->comment_ID) ).'">'.sprintf(__('%1$s 发表在 %2$s','dmeng'),$comment->comment_date,get_the_title($comment->comment_post_ID)).'</a>';
			$item_html .= '</li>';
		}
		if($pages>1) $item_html .= '<li class="hx-special-li">'.dmeng_pager($paged, $pages).'</li><li class="bottom-tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','dmeng'),$paged, $pages, $number).'</li>';
	}

	echo '<ul id="author-message">'.$item_html.'</ul>';
}
//~ 评论end

//~ 赞start
if( $get_tab=='like' ) {

	if($up_count){

		$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 次赞，其中包括 <em>%2$s</em> 篇文章， <em>%3$s</em> 条评论。','dmeng'),$up_count, $up_posts_count, $up_comments_count) . '</li>';
	
		$up_result = get_dmeng_user_vote($curauth->ID, false, '', 'up', $number, $offset);
		
		$up_data = array();
		
		foreach( $up_result as $up_meta ){
			$up_key = explode('_', $up_meta->meta_key);
			
			if($up_key[1]=='post'){
					
				$post_info = get_post( $up_key[2] );
				$up_data[] = array(
							'title' => $post_info->post_title,
							'url' => esc_url( get_permalink( $post_info->ID ) ),
							'excerpt' => apply_filters( 'the_excerpt', $post_info->post_excerpt ),
							'type' => 'post',
							'type_label' => __('文章', 'dmeng'),
							'id' => $post_info->ID
						);
				
					
			}
			
			if($up_key[1]=='comment'){
				
				$comment = get_comment( $up_key[2] ); 
				
						$up_data[] = array(
							'title' => sprintf( __('%s的评论', 'dmeng'), $comment->comment_author),
							'url' => get_comment_link($comment->comment_ID),
							'excerpt' => get_comment_text($comment->comment_ID),
							'type' => 'comment',
							'type_label' => __('评论', 'dmeng'),
							'id' => $comment->comment_ID
						);
				
			}

		}
		
		if( $up_data ){
			foreach( $up_data as $up_term ){
				$item_html .= '<li class="up"><div class="up_content">'.$up_term['excerpt'].'</div><p class="info">['.$up_term['type_label'].']<a class="up_title" href="'.$up_term['url'].'">'.$up_term['title'].'</a> <span class="glyphicon glyphicon-thumbs-up"></span>'.intval(get_metadata($up_term['type'], $up_term['id'], 'dmeng_votes_up', true)).'</p></li>';
			}
		}
	
	}
	
	$pages = ceil($up_count/$number);
	
	if($pages>1) $item_html .= '<li class="hx-special-li">'.dmeng_pager($paged, $pages).'</li><li class="bottom-tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','dmeng'),$paged, $pages, $number).'</li>';
	
	echo '<ul id="author-message">'.$item_html.'</ul>';
	
}
//~ 赞end

//~ 消息start
if( $get_tab=='message' ) {

	if($current_user->ID==$curauth->ID){
		
		$norealpass = 0;
		
		if( isset($_POST['action']) && trim($_POST['action'])=='empty_message' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {

			if(wp_check_password( $_POST['pass'], $curauth->data->user_pass, $curauth->ID)){
				
				global $wpdb;
				$wpdb->delete( $wpdb->prefix . 'dmeng_message', array( 'user_id' => $curauth->ID,  'msg_type' => 'read' ) );

			}else{
				
				$norealpass = 1;
			}
		
		}


		?>
		<form id="emptyform" <?php echo $norealpass ? '' : 'style="display:none"';?> role="form" method="post">
			<input type="hidden" name="action" value="empty_message">
			<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<p class="help-block"><?php echo $norealpass ? __('密码错误，请重新输入！', 'dmeng') : __('本操作不可逆，请谨慎操作。确定清空全部已读消息（不包括私信）请输入用户密码并回车', 'dmeng');?></p>
			<input type="password" class="form-control" id="pass" name="pass" required>
		</form>
		
	<script>
		(function($){
			$(document).on("click","#empty_message",function(){
				$('#emptyform').toggle();
			});
		})(jQuery);
	</script>

		<?php
		
		$all_sql = "( msg_type='read' OR msg_type='unread' OR msg_type='repm' OR msg_type='unrepm' )";

		$all = get_dmeng_message($curauth->ID, 'count', $all_sql);
		$pages = ceil($all/$number);
		
		$unread = intval(get_dmeng_message($curauth->ID, 'count', "msg_type='unread'"));

		$mLog = get_dmeng_message($curauth->ID, '', $all_sql, $number,$offset);
		
		if($mLog){
			$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 条消息，其中 %2$s 条是新消息（绿色标注）。','dmeng'), $all, $unread) . '<button type="button" id="empty_message" class="close hide" title="'.__('清空全部已读消息（不包括私信）','dmeng').'" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></li>';
			foreach( $mLog as $log ){
				$unread_tip = $unread_class = '';
				if(in_array($log->msg_type, array('unread', 'unrepm'))){
					$unread_tip = '<span class="tag">'.__('新！', 'dmeng').'</span>';
					$unread_class = ' unread';
					update_dmeng_message_type( $log->msg_id, $curauth->ID , ltrim($log->msg_type, 'un') );
				}
				$msg_title =  $log->msg_title;
				if(in_array($log->msg_type, array('repm', 'unrepm'))){
					$msg_title_data = json_decode($log->msg_title);
					$msg_title = get_the_author_meta('display_name', intval($msg_title_data->from));
					$msg_title = sprintf(__('%s发来的私信','dmeng'), $msg_title).' <a href="'.add_query_arg('tab', 'message', get_author_posts_url(intval($msg_title_data->from))).'#'.$log->msg_id.'">'.__('查看对话','dmeng').'</a>';
				}
				$item_html .= '<li class="msg'.$unread_class.'"><button type="button" class="close hide" title="'.__('删除这条消息','dmeng').'" data-id="'.$log->msg_id.'" data-nonce="'.wp_create_nonce( $log->msg_id ).'" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><div class="message-content">'.strip_tags(htmlspecialchars_decode($log->msg_content), '<a><br><p>').' </div><p class="info">'.$unread_tip.'  '.$msg_title.'  '.$log->msg_date.'</p></li>';
			}
			if($pages>1) $item_html .= '<li class="hx-special-li">'.dmeng_pager($paged, $pages).'</li><li class="bottom-tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','dmeng'),$paged, $pages, $number).'</li>';
		}
		
	}else{
		
		if( is_user_logged_in() ){
			
			$item_html = '<li class="tip">'.sprintf(__('与 %s 对话','dmeng'), $user_info->display_name).'</li><li><form id="pmform" role="form" method="post"><input type="hidden" name="pmNonce" value="'.wp_create_nonce( 'pm-nonce' ).'" ><p><textarea class="form-control" rows="3" name="pm" required></textarea></p><p class="clearfix"><a class="btn btn-link pull-left" href="'.add_query_arg('tab', 'message', get_author_posts_url($current_user->ID)).'">'.__('查看我的消息','dmeng').'</a><button type="submit" class="btn btn-primary pull-right">'.__('确定发送','dmeng').'</button></p></form></li>';
			
			$all = get_dmeng_pm( $curauth->ID, $current_user->ID, true );
			$pages = ceil($all/$number);
			
			$pmLog = get_dmeng_pm( $curauth->ID, $current_user->ID, false, false, $number, $offset );
			if($pmLog){
				foreach( $pmLog as $log ){
					$pm_data = json_decode($log->msg_title);
					if( $pm_data->from==$curauth->ID ){
						update_dmeng_message_type( $log->msg_id, $curauth->ID , 'repm' );
					}
					$item_html .= '<li class="msg" id="'.$log->msg_id.'"><div class="message-content clearfix"><a class="'.( $pm_data->from==$current_user->ID ? 'pull-right' : 'pull-left' ).'" href="'.get_author_posts_url($pm_data->from).'">'.dmeng_get_avatar( $pm_data->from , '34' , dmeng_get_avatar_type($pm_data->from), false ).'</a><div class="pm-box"><div class="pm-content'.( $pm_data->from==$current_user->ID ? '' : ' highlight' ).'">'.
					'<button type="button" class="close hide" title="'.__('删除这条消息','dmeng').'" data-id="'.$log->msg_id.'" '.( $pm_data->from==$current_user->ID ? ' data-pm="'.$pm_data->pm.'" ' : '') .'data-nonce="'.wp_create_nonce( $log->msg_id ).'" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.
					wpautop(wp_strip_all_tags(htmlspecialchars_decode($log->msg_content))).'</div><p class="pm-date">'.date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime($log->msg_date)).'</p></div></div></li>';
				}
			}
			
			if($pages>1) $item_html .= '<li class="hx-special-li">'.dmeng_pager($paged, $pages).'</li><li class="bottom-tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','dmeng'),$paged, $pages, $number).'</li>';

		}else{
			$item_html = '<li class="tip">'.sprintf(__('私信功能需要<a href="%s">登录</a>才可使用！'), wp_login_url( dmeng_get_current_page_url() ) ).'</li>';
		}
	}
	
	echo '<ul id="author-message">'.$item_html.'</ul>';

}
//~ 消息end

//~ 资料start
if( $get_tab=='profile' ) {

		$avatar_type = array(
			'default' => __('默认头像', 'dmeng'),
			'qq' => __('腾讯QQ头像', 'dmeng'),
			'weibo' => __('新浪微博头像', 'dmeng'),
		);
		
		$author_profile = array(
			__('头像来源','dmeng') => $avatar_type[dmeng_get_avatar_type($user_info->ID)],
			__('用户名','dmeng') => $user_info->user_login,
			__('昵称','dmeng') => $user_info->display_name,
			__('个人主页','dmeng') => $user_info->user_url,
			__('个人说明','dmeng') => $user_info->description
		);
		
		$profile_output = '';
		foreach( $author_profile as $pro_name=>$pro_content ){
			$profile_output .= '<tr><td class="title">'.$pro_name.'</td><td>'.$pro_content.'</td></tr>';
		}
		
		$days_num = round(( strtotime(date('Y-m-d')) - strtotime( $user_info->user_registered ) ) /3600/24);
		
		echo '<ul id="author-message"><li class="tip">'.sprintf(__('%s来%s已经%s天了', 'dmeng') , $user_info->display_name, get_bloginfo('name'), ( $days_num>1 ? $days_num : 1 ) ).'</li></ul>';
		//echo '<table id="author-profile"><tbody>'.$profile_output.'</tbody></table>';
		
	if( $oneself ){
		
	?>

<form id="info-form" class="form-horizontal" role="form" method="post">
	<input type="hidden" name="update" value="info">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="info"><?php _e('基本信息','dmeng');?> <small><?php _e('公开资料','dmeng');?></small></h3>
			</div>

	
	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('头像','dmeng');?></label>
		<div class="col-sm-9">

			<div class="radio">
			<?php echo dmeng_get_avatar( $user_info->ID , '40' ); ?>
			  <label>
				<input type="radio" name="avatar"  value="default" <?php if( ($avatar!='qq' || dmeng_is_open_qq($user_info->ID)===false) && ($avatar!='weibo' || dmeng_is_open_weibo($user_info->ID)===false) ) echo 'checked';?>> 默认头像
			  </label>
			</div>

			<?php if(dmeng_is_open_qq($user_info->ID)){ ?>
			<div class="radio">
			<?php echo dmeng_get_avatar( $user_info->ID , '40' , 'qq' ); ?>
			  <label>
			    <input type="radio" name="avatar" value="qq" <?php if($avatar=='qq') echo 'checked';?>> <?php _e('QQ头像', 'dmeng');?>
			  </label>
			</div>
			<?php } ?>

			<?php if(dmeng_is_open_weibo($user_info->ID)){ ?>
			<div class="radio">
			<?php echo dmeng_get_avatar( $user_info->ID , '40' , 'weibo' ); ?>
			  <label>
			    <input type="radio" name="avatar" value="weibo" <?php if($avatar=='weibo') echo 'checked';?>> <?php _e('微博头像', 'dmeng');?>
			  </label>
			</div>
			<?php } ?>

		</div>
    </div>

	<div class="form-group">

	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('用户名','dmeng');?></label>
		<div class="col-sm-9">
			<?php
				if($is_auto_userlogin){
			?>
				<p><input type="text" class="form-control" id="user_login" name="user_login" value="<?php echo $user_info->user_login;?>"></p>
				<span class="help-block"><?php _e('请慎重，只能修改一次！通常以英文或数字，不能包含中文和特殊符号。','dmeng');?></span>
			<?php }else{ ?>
				<p class="form-control-static"><?php echo $user_info->user_login;?></p>
			<?php } ?>
			
		</div>
	</div>
	
	<div class="form-group">
		<label for="display_name" class="col-sm-3 control-label"><?php _e('昵称','dmeng');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo $user_info->display_name;?>">
		</div>
	</div>

	<div class="form-group">
		<label for="url" class="col-sm-3 control-label"><?php _e('站点','dmeng');?></label>
		<div class="col-sm-9">
			<p><input type="text" class="form-control" id="url" name="url" value="<?php echo $user_info->user_url;?>"></p>
			<span class="help-block"><?php _e('必须是正确的网址且以 http 开头','dmeng');?></span>
		</div>
	</div>
	
	<div class="form-group">
		<label for="description" class="col-sm-3 control-label"><?php _e('个人说明','dmeng');?></label>
		<div class="col-sm-9">
			<textarea class="form-control" rows="3" name="description" id="description"><?php echo $user_info->description;?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php _e('保存更改','dmeng');?></button>
		</div>
	</div>
</form>
<!-- user upload self avatar -->
	<?php echo do_shortcode("[avatar_upload]"); ?>
<!-- user upload self avatar end -->
<?php if( $qq || $weibo ) { ?>
<form id="open-form" class="form-horizontal" role="form" method="post">
			<div class="page-header">
				<h3 id="open"><?php _e('绑定账号','dmeng');?> <small><?php _e('可用于直接登录','dmeng');?></small></h3>
			</div>
			
	<?php if($qq){ ?>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php _e('QQ账号','dmeng');?></label>
			<div class="col-sm-9">
		<?php  if(dmeng_is_open_qq($user_info->ID)) { ?>
			<span class="help-block"><?php _e('已绑定','dmeng');?> <a href="<?php echo dmeng_get_open_login_url('qq', 'logout', get_edit_profile_url()); ?>" data-no-instant><?php _e('点击解绑','dmeng');?></a></span>
			<?php echo dmeng_get_avatar( $user_info->ID , '100' , 'qq' ); ?>
		<?php }else{ ?>
			<a class="btn btn-primary" href="<?php echo dmeng_get_open_login_url('qq', 'login', get_edit_profile_url()); ?>" data-no-instant><?php _e('绑定QQ账号','dmeng');?></a>
		<?php } ?>
			</div>
		</div>
	<?php } ?>

	<?php if($weibo){ ?>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php _e('微博账号','dmeng');?></label>
			<div class="col-sm-9">
		<?php if(dmeng_is_open_weibo($user_info->ID)) { ?>
			<span class="help-block"><?php _e('已绑定','dmeng');?> <a href="<?php echo dmeng_get_open_login_url('weibo', 'logout', get_edit_profile_url()); ?>" data-no-instant><?php _e('点击解绑','dmeng');?></a></span>
			<?php echo dmeng_get_avatar( $user_info->ID , '100' , 'weibo' ); ?>
		<?php }else{ ?>
			<a class="btn btn-danger" href="<?php echo dmeng_get_open_login_url('weibo', 'login', get_edit_profile_url()); ?>" data-no-instant><?php _e('绑定微博账号','dmeng');?></a>
		<?php } ?>
			</div>
		</div>
	<?php } ?>
</form>
<?php } ?>
<form id="pass-form" class="form-horizontal" role="form" method="post">
	<input type="hidden" name="update" value="pass">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="pass"><?php _e('账号安全','dmeng');?> <small><?php _e('仅自己可见','dmeng');?></small></h3>
			</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label"><?php _e('电子邮件 (必填)','dmeng');?></label>
		<div class="col-sm-9">
			<p id="email_tips" style="color:#d44950;"><?php
		$transient_key = 'dmeng_email_verify_'.$user_info->ID;
		$transient = (array)json_decode(get_transient( $transient_key ));
		if( $transient && isset($transient['email']) ){
			if( $transient['email'] != $user_info->dmeng_verify_email ){
				printf(__('你申请了验证邮箱（%1$s），从 %2$s 开始五分钟内有效，请尽快打开邮件中的链接验证。', 'dmeng'), $transient['email'], $transient['time'] );
			}
		}
			?></p>
			<div class="input-group">
				<input type="text" class="form-control" id="email" name="email" value="<?php echo $user_info->user_email;?>" autocomplete="off" aria-required='true' required>
				<span class="input-group-btn"><button class="btn btn-default" type="button" id="get_verify_email"><span class="get"><?php _e('获取验证邮件','dmeng');?></span><span class="wait" style="display:none"><?php _e('获取中请稍候','dmeng');?></span></button></span>
			</div>
			<p class="help-block small"><?php 
			if( !empty($user_info->dmeng_verify_email) && is_email($user_info->dmeng_verify_email) ){
				printf(__('你已验证邮箱（%1$s），所有通知都将发送到这个邮箱。','dmeng'), $user_info->dmeng_verify_email);
			}else{
				echo '<span style="color:#d44950;">'.__('请验证一个邮箱地址，否则你将无法接收邮件通知！', 'dmeng').'</span>';
			}
			?></p>
		</div>
	</div>
	<div class="form-group">
		<label for="pass1" class="col-sm-3 control-label"><?php _e('新密码','dmeng');?></label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="pass1" name="pass1" >
			<span class="help-block"><?php _e('如果您想修改您的密码，请在此输入新密码。不然请留空。','dmeng');?></span>
		</div>
	</div>
	<div class="form-group">
		<label for="pass2" class="col-sm-3 control-label"><?php _e('重复新密码','dmeng');?></label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="pass2" name="pass2" >
			<span class="help-block"><?php _e('再输入一遍新密码。 提示：您的密码最好至少包含8个字符。为了保证密码强度，使用大小写字母、数字和符号（例如! " ? $ % ^ & )）。','dmeng');?></span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php _e('保存更改','dmeng');?></button> 
		</div>
	</div>
</form>
<?php
//~ 4.0.0 版本新增的清理会话
if ( version_compare($wp_version, '4.0.0', '>=') && $current_user->ID==$curauth->ID ) {
?>
<form id="sessions-form" class="form-horizontal" role="form" method="post">
	<input type="hidden" name="update" value="destroy-sessions">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="sessions"><?php _e('会话安全','dmeng');?> <small><?php _e('登录状态','dmeng');?></small></h3>
			</div>
	<div class="form-group">
		<label for="destroy-sessions" class="col-sm-3 control-label"><?php _e('清理会话','dmeng');?></label>
		<div class="col-sm-9">
			<?php
				$count_sessions = count( wp_get_all_sessions() );
				if( $count_sessions > 1){
			?>
				<button type="submit" class="btn btn-default"><?php printf(__( '共有 %1$s 个会话，点击登出其他会话', 'dmeng' ), $count_sessions ); ?></button>
			<?php
				}else{
			?>
				<button type="button" class="btn btn-default" disabled><?php _e( '无需清理，您只有在此处登入。', 'dmeng' ); ?></button>
			<?php
				}
				
				echo '<a href="'.wp_logout_url().'" title="'.esc_attr__('Log out of this account').'" class="btn btn-default" data-no-instant>' .	__('Log out &raquo;') . '</a></li>';
			?>
		</div>
	</div>
</form>
<?php } ?>
<script>
jQuery(document).ready(function($) {
	function get_verify_email(e){
		var v = $('#get_verify_email');
		v.addClass('disabled');
		v.children('.get').hide();
		v.children('.wait').show();
		$.ajax({
			type: 'POST', 
			url: ajaxurl,
			data: {
				'action' : 'dmeng_email_verify',
				'email' : e,
				'wp_nonce' : dmengGetCookie('dmeng_check_nonce')
			},
			success: function(response) {
				if($.trim(response)==='NonceIsInvalid'){
					set_dmeng_nonce();
					get_verify_email(e);
				}else{
					$('#email_tips').html(response).fadeIn();
					v.removeClass('disabled');
					v.children('.wait').hide();
					v.children('.get').show();
				}
			},
			error: function(){
				get_verify_email(e);
			}
		});
	}

	$("#get_verify_email").click(function(){
		var e = $('#email').val();
		console.log(e);
		if( e == '' ){
			$('#email_tips').html('<?php _e('邮箱地址不能为空', 'dmeng');?>').fadeIn();
			return false;
		}
		get_verify_email(e);
	});

	
})
</script>
	<?php
	} //oneself end
} 
//~ 资料end

			?>
				</div><!-- #hx-panel-body or author-post-archive end -->
			</div><!-- #author-page end-->
			<?php echo dmeng_adsense('author','bottom');?>
		 </div><!-- #content -->
		<?php //get_sidebar();?>
	</div>
 </div><!-- #main -->
 </section>
<div class="alert alert-dismissible fade in" role="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <strong id="msg"></strong>
</div> 
	<!-- Modal -->
<div class="modal fade hx-modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">       
           <p>确定不再关注此人？</p>
      </div>
      <div class="modal-footer">
        <a  id="del" class="hx_btn_b hx_btn" data-dismiss="modal">取消</a>
        <a  id="sure" class="hx_btn_a hx_btn">确认</a>
      </div>
    </div>
  </div>
</div>	
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>
