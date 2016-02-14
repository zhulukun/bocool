<?php
if ( post_password_required() ) { 
?>
	<div class="panel-body"><?php _e('This post is password protected. Enter the password to view comments.'); ?></div>
<?php

}else{

if ( comments_open() ) { 
	
	?>
<div id="respond">
	<div id="reply-title"><i class="fa fa-pencil"></i><?php comment_form_title( __('Leave a Reply'), __('Leave a Reply to %s' ) ); ?> <small id="cancel-comment-reply"><?php cancel_comment_reply_link() ?></small></div>
<?php 
if ( get_option('comment_registration') && !is_user_logged_in() ){ 

?><p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() . "#respond" )); ?></p>
<?php 

}else{

if( !empty($_GET['replytocom']) && is_numeric($_GET['replytocom']) ){
	//~ echo $_GET['replytocom'];
	wp_list_comments( 'type=comment&callback=dmeng_comment' , array( get_comment( $_GET['replytocom'] ) ) );
}
?><form action="<?php echo site_url(); ?>/wp-comments-post.php" method="post" id="commentform" class="form-horizontal" role="form">
	<?php if( get_option('comment_registration') && !is_user_logged_in() ) { ?>
		<p class="help-block"><a href="<?php echo wp_login_url( get_permalink() . "#respond" );?>"><?php _e('Log in to Reply');?></a></p>
	<?php } ?>
	<?php do_action( 'comment_form_top' );?>
	<div id="comment-user" data-user-id="<?php echo get_current_user_id();?>">
		<?php if ( is_user_logged_in() ) { ?>
			<p class="logged-in-help"><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_edit_user_link(), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e('Log out of this account'); ?>"><?php _e('Log out &raquo;'); ?></a></p>
		<?php } else { ?>
			<div class="form-group">
				<label for="author" class="col-sm-2 control-label"><?php _e('名称','dmeng'); ?></label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php if ($req) _e('（必填）','dmeng'); ?>" <?php if ($req) echo "aria-required='true' required"; ?> />
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label"><?php _e('电子邮件','dmeng'); ?></label>
				<div class="col-sm-10">
					<input class="form-control" type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php if ($req) _e('（必填）','dmeng'); _e('（不会被公开）','dmeng');  ?>" <?php if ($req) echo "aria-required='true' required"; ?> />
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-sm-2 control-label"><?php _e('站点','dmeng'); ?></label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="url" id="url" value="<?php echo  esc_attr($comment_author_url); ?>" />
				</div>
			</div>
		<?php } ?>
	</div>
	<textarea class="form-control" rows="3" name="comment" id="comment" required></textarea>
	<div id="comment-action" class="btn-toolbar clearfix" role="toolbar"><p class="help-block"><?php _e('请注意保护个人隐私，敏感内容请使用隐私内容按钮或标签插入，如[pem]隐私内容[/pem]。','dmeng'); ?></p>
			<div class="btn-group">
				<span class="btn btn-default look-toggle"><span class="glyphicon glyphicon-eye-open"></span> <?php _e('表情','dmeng'); ?></span>
				<span class="btn btn-default"  id="open-privacy-action"><span class="glyphicon glyphicon-exclamation-sign"></span> <?php _e('隐私内容','dmeng'); ?></span>
				<div id="looks-image" class="hide">
					<ul class="clearfix">
			<?php
						$looks = dmeng_get_look('file');
						foreach( $looks['file'] as $lk=>$lf ){
							$look_title = str_replace(array('[',']'), '', $looks['text'][$lk]);
							echo '<li title="'.$look_title.'"><img src="'.dmeng_script_uri('look') . $lf.'" alt="'.$look_title.'" width="22" height="22" /></li>';
						}
					?>
						</ul>
					</div>
			</div>
			<div class="btn-group">
				<button class="btn btn-default" name="submit" type="submit" id="commentsubmit"><?php esc_attr_e('Submit Comment'); ?></button>
			</div>
	</div>
	<div class="input-group has-warning" id="privacy-action" style="display:none">
		<span class="input-group-addon"><?php echo  '<abbr class="pem" title="'.__('只有评论/文章作者或更高权限的用户才可见','dmeng').'">'.__('隐私内容','dmeng').'</abbr>'; ?></span>
		<input type="text" class="form-control" id="privacy-comment">
		<span class="input-group-btn">
			<button class="btn btn-warning" type="button" id="add-privacy-comment"><?php _e('添加到评论','dmeng'); ?></button>
		</span>
    </div>
	<div id="comment-error-alert" class="alert alert-warning" style="display:none;" role="alert"></div>
	<?php comment_id_fields(); ?>
	<?php do_action('comment_form', $post->ID); ?>
</form>
<?php } // If registration required and not logged in ?>
</div>

<?php }else{  ?>
	<div class="panel-body"><?php _e('Comments are closed.'); ?></div>
<?php

} // if comment open 

if ( have_comments() ) { ?>
	<ul class="list-group commentlist">
		<?php
		$s_comments = get_comments(array(
				'status' => 'approve',
				'post_id'=> $post->ID,
				'meta_key' => 'dmeng_sticky_comment',
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
			));

		$s_comments_list = wp_list_comments( "type=comment&callback=dmeng_comment&max_depth=1&echo=0&per_page=0", $s_comments );
		if($s_comments_list || ( get_current_user_id()==get_the_author_meta('ID') || current_user_can('moderate_comments') ) ){
		?>
		<ul id="sticky-comments">
			<li class="list-group-item sticky-title <?php if(!$s_comments_list) echo 'hide';?>"><?php echo get_option('dmeng_sticky_comment_title', __('置顶评论','dmeng')); ?></li>
			<?php echo $s_comments_list;?>
		</ul>
		<?php } ?>
		<li class="list-group-item respond-title"><?php	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', '<span itemprop="interactionCount">'.get_comments_number().'</span>' ), number_format_i18n( get_comments_number() ), '&#8220;' . get_the_title() . '&#8221;' ); ?></li>
		<ul id="thread-comments">
			<?php 
				//~ 强制评论嵌套，最少两级
				$depth = get_option('thread_comments_depth');
				$depth = intval($depth)<2 ? 2 : $depth;
				$depth = wp_is_mobile() ? 2 : $depth;
				wp_list_comments( "callback=dmeng_comment&style=ul&max_depth=$depth" );
				
				$paginate = dmeng_paginate_comments();
				if($paginate) echo '<li class="list-group-item text-center">'.$paginate.'</li>';
			?>
		</ul>
	</ul>
	
<?php } // if have comments?>
<?php } // if post_password_required?>
