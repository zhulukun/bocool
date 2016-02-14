<?php
/*
* 欢迎来到代码世界，如果你想修改多梦主题的代码，那我猜你是有更好的主意了～
* 那么请到多梦网络（ http://www.dmeng.net/ ）说说你的想法，数以万计的童鞋们会因此受益哦～
* 同时，你的名字将出现在多梦主题贡献者名单中，并有一定的积分奖励～
* 注释和代码同样重要～
* @author 多梦 @email chihyu@aliyun.com
*/
?>
<!-- 特色图片展示 -->
<div class="single-post-header">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<section class="single-post-introduce clearfix">
					<div class="single-post-feature pull-left">
						<figure class="thumbnail">
							<?php the_post_thumbnail( 'large' ); ?>
							<div class="overlay"></div>
						</figure>
					</div>
					<div class="single-post-info">
						<h1 class="single-post-title">
						<?php the_title(); ?></h1>
						<p class="single-post-excerpt hidden-xs">
							<?php if(!empty($post->post_excerpt)) { ?>
							<span><i class="fa fa-quote-left fa-lg"></i></span>
							<?php echo mb_strimwidth( strip_tags( apply_filters( 'the_excerpt', $post->post_excerpt ) ), 0, 150,"&nbsp;..." ); ?>
							<span><i class="fa fa-quote-right fa-lg"></i></span>
							<?php } ?>
						</p>
						<div class="single-post-meta">
							<span class="post-author-avatar">
								<?php echo dmeng_get_avatar(get_the_author_meta( 'ID' ) , '50' , dmeng_get_avatar_type( get_the_author_meta( 'ID' ) ), false); ?>
							</span>
							<span class="author-meta"> <?php the_author_posts_link(); ?></span>
							<span>|</span>
							<span><?php  the_time( 'm月j日, Y' ); ?></span>
							<div class="post-author-introduce">
								<div class="author-avatar">
									<?php echo dmeng_get_avatar(get_the_author_meta( 'ID' ) , '70' , dmeng_get_avatar_type( get_the_author_meta( 'ID' ) ), false ); ?>
								</div>
							     <h4><?php the_author(); ?></h4>
							     <p>
							     <?php $des = get_the_author_meta( 'description' );if ($des) {echo $des;} else {echo "暂无介绍";} ?></p>
							</div>
							<script type="text/javascript">
								jQuery(".author-meta").mouseover(
									function(){jQuery(".post-author-introduce").css({"display":'block'})}
								).mouseout(
									function(){jQuery(".post-author-introduce").css({"display":'none'})}
								);
							</script>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<!-- 背景毛玻璃化 -->
	<div class="bgbanner hidden-xs">
		<span class="bgmask"></span>
		<img class="bgimg" id="bgimg" src="<?php $h = wp_get_attachment_image_src(get_post_thumbnail_id(),'full');echo $h[0]; ?>">
	</div>
</div>

<div class="modal fade" id="panel-weixin" tabindex="-1" role="dialog" aria-labelledby="weinxinModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="weixinModalLabel">打开微信“扫一扫”，打开网页后点击屏幕右上角分享按钮</h4>
			</div>
			<div class="modal-body">
				<section class="weixin-section">
					<p><img id="single_post_qr_code" src="" alt=""></p>
					<script type="text/javascript">
					jQuery(function($) {
					var qr_url = "http://s.jiathis.com/qrcode.php?url=<?php the_permalink(); ?>?via=wechat_qr";
					$("#single_post_qr_code").attr("src",qr_url);
					});
					</script>
				</section>
			</div>
		</div>
	</div>
</div>
<!-- 文章主题内容 -->
<div class="container">
	<div class="row">
		<div class="col-md-8 leo-article">
		   <div class="leo-article-top clearfix">
			 	<div class="leo-tag pull-left">
					<span><i class="fa fa-tags"></i></span>
					<?php if (get_the_tags()) the_tags('标签：',' ',' ');?>
				</div>
				<div class="pull-right share-mark hidden-xs"><span>分享到: </span>
					<a ref="nofollow" href="#panel-weixin" data-toggle="modal" class="weixin overlay"><i class="fa fa-weixin"></i></a>
					<a ref="nofollow" href="http://service.weibo.com/share/share.php?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&appkey=237935407&searchPic=true" target="_blank" class="share-by-weibo">
						<i class="fa fa-weibo"></i>
					</a>
					<a ref="nofollow" href="http://connect.qq.com/widget/shareqq/index.html?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&source=shareqq&desc=刚看到这篇文章不错，推荐给你看看～" target="_blank" class="share-by-qq">
						<i class="fa fa-qq"></i>
					</a>
				</div>
			</div>
			<article>
				<div class="leo-single-content">
					<?php the_content(); ?>
				</div>

				<!-- 文章版权信息 -->
				<div class="copyright well hidden-xs hidden-md hidden-sm hidden-lg">
					<p>
						版权属于:
						<?php
							if( get_post_meta( $post->ID, "版权属于:", true ) ) {
								echo get_post_meta( $post->ID, "版权属于:", true );
							}else {
								echo '<a href="';
										bloginfo('url');
										echo '">';
										bloginfo('name');
								echo '</a>';
							}
						?>
					</p>
					<p>
						原文地址:
						<?php
							if( get_post_meta( $post->ID, "原文地址:", true ) ) {
								echo get_post_meta( $post->ID, "原文地址:", true );
							} else {
								echo '<a href="';
										echo the_permalink().'">';
								echo the_permalink().'</a>';
							}
						?>
					</p>
					<p>转载时必须以链接形式注明原始出处及本声明。</p>
				</div>
				<!-- 文章版权信息结束 -->
				<p class="share-mark visible-xs"><span>分享到: </span>
					<a ref="nofollow" href="#panel-weixin" data-toggle="modal" class="weixin overlay"><i class="fa fa-weixin"></i></a>
					<a ref="nofollow" href="http://service.weibo.com/share/share.php?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&appkey=237935407&searchPic=true" target="_blank" class="share-by-weibo">
						<i class="fa fa-weibo"></i>
					</a>
					<a ref="nofollow" href="http://connect.qq.com/widget/shareqq/index.html?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&source=shareqq&desc=刚看到这篇文章不错，推荐给你看看～" target="_blank" class="share-by-qq">
						<i class="fa fa-qq"></i>
					</a>
				</p>
				<!-- 分页 -->
				<div clas="leo-page clearfix">
					<ul class="pager">
						<li class="previous"><?php previous_post_link( '%link', '<i class="fa fa-angle-left"></i> 上一篇', TRUE ); ?></li>
						<li class="next"><?php next_post_link( '%link', '下一篇 <i class="fa fa-angle-right"></i>', TRUE ); ?></li>
					</ul>
				</div>
				<!-- 分页结束 -->
			</article>
			<!-- 相关文章 -->
			<div class="hidden-xs" id="post-related">
				<div class="related-title"><i class="fa fa-share-alt"></i> 相关推荐</div>
				<div class="row">
					<?php
						global $post;
						$cats = wp_get_post_categories( $post->ID );
						if ( $cats ) {
							$args = array(
											'category__in' => array( $cats[0] ),
											'post__not_in' => array( $post->ID ),
											'showposts' => 3,
							);
							query_posts( $args );
							if ( have_posts()  ) {
								while ( have_posts() ) {
					the_post(); update_post_caches( $posts ); ?>
					<?php if( has_post_thumbnail() ) : ?>
					<article class="col-md-4 col-sm-4">
						<section class="post-block">
							<div class="post-feature related-post-feature">
								<figure class="thumbnail">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium' ); ?>
									</a>
									<p class="post-related-title">
										<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
									</p>
									<div class="overlay"></div>
								</figure>
							</div>
						</section>
					</article>
					<?php endif; ?>
					<?php
					}
					}
					wp_reset_query();
					}
					?>
				</div>
				<?php echo dmeng_adsense('single','bottom');?>
			</div>     <!--相关文章结束-->
			<!--评论模板-->
			<div class="<?php echo apply_filters('dmeng_comment_panel_class', 'panel panel-comment');?>" id="comments" data-no-instant>
				<?php comments_template( '', true ); ?>
			</div>
		</div>
		<?php get_sidebar();?>
	</div>
</div>