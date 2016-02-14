<?php 
/**
 * [getFollowerNum ]
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
function getFollowNum($uid, $key){
    $count = get_user_meta($uid, $key, true);
	if($count==''){
		$count = 0;
	    delete_user_meta($uid, $key);
	    add_user_meta($uid, $key, $count);
	    return $count;
	}
    return $count;
}
/**
 * [incFollowerNum ]
 * @param [type] $uid [description]
 */
function incFollowNum($uid, $key) {
	$count = get_user_meta($uid, $key, true);
	if($count==''){
		$count = 0;
		delete_user_meta($uid, $key);
		add_user_meta($uid, $key, $count);
	}else{
		$count++;
		update_user_meta($uid, $key, $count);
	}
}
/**
 * [decFollowerNum ]
 * @param [type] $uid [description]
 */
function decFollowNum($uid, $key) {
	$count = get_user_meta($uid, $key, true);
	if($count == '' || $count < 1){
		$count = 0;
		delete_user_meta($uid,$key);
		add_user_meta($uid, $key, $count);
	} else {
		$count--;
		update_user_meta($uid, $key, $count);
	}
}
/**
 * [isfollow judge that whether or not two people have relation]
 * @param  [type] $uid [description]
 * @param  [type] $fid [description]
 * @return [type]      [description]
 */
function isfollow ($uid, $fid) {
	global $wpdb;
	$check = $wpdb->get_var( "SELECT count(*) FROM wp_hx_follow where user_id = $uid and follow_id = $fid" );
    return isset($check) ? $check : 0;
}
add_action( 'wp_ajax_ajaxfollow', 'huoxing_ajax_follow' );
add_action( 'wp_ajax_nopriv_ajaxfollow', 'huoxing_ajax_follow' );
add_action( 'wp_ajax_ajaxunfollow', 'huoxing_ajax_unfollow' );
add_action( 'wp_ajax_nopriv_ajaxunfollow', 'huoxing_ajax_unfollow' );

function huoxing_ajax_follow()
{  
	$uid = intval($_POST['uid']);
	$fid = intval($_POST['fid']);
	if($uid < 1 || $fid <1 ) die('illegal request');
	if (! wp_verify_nonce($_POST['_wpnonce'], 'hx_follow_'.$uid.'_'.$fid) ) die(' illegal request');
	global $wpdb;
	$res = $wpdb->get_var( "SELECT count(*) FROM wp_hx_follow where user_id = $uid and follow_id = $fid" );
	if($res)
	{
		echo json_encode(array('status'=>false, 'action'=>'unfollow'));
	}else {
	    $hx_post_data = array( 'user_id' => $uid, 'follow_id' => $fid );
	    if($wpdb->insert( 'wp_hx_follow',$hx_post_data, array( '%d', '%d' ,'%d') )){
	    	//local user's following increase
	    	incFollowNum($uid, 'hx_following');
	        //object user's follower increase
	    	incFollowNum($fid, 'hx_follower');
		    echo json_encode(array('status'=>true, 'action'=>'follow'));
	    } else {
			echo json_encode(array('status'=>false, 'action'=>'follow'));
	    }
	}
	die;
}

/**
 * [huoxing_ajax_unfollow description]
 * @return [type] [description]
 */
function huoxing_ajax_unfollow()
{
	$uid = intval($_POST['uid']);
	$fid = intval($_POST['fid']);
	if($uid < 1 || $fid <1 ) die('illegal request');
	if (! wp_verify_nonce($_POST['_wpnonce'], 'hx_follow_'.$uid.'_'.$fid) ) die('Security check');
	global $wpdb;
	$hx_del = array('user_id'=>$uid,'follow_id' => $fid);
	$res = $wpdb->get_var( "SELECT count(*) FROM wp_hx_follow where user_id = $uid and follow_id = $fid" );
	if(!$res)
	{
		echo json_encode(array('status'=>false, 'action'=>'unfollow'));
	}else {
		if($wpdb->delete('wp_hx_follow', $hx_del)){
	    	//local user's following decrease
	    	decFollowNum($uid, 'hx_following');
	        //object user's follower decrease
	    	decFollowNum($fid, 'hx_follower');
		    echo json_encode(array('status'=>true, 'action'=>'unfollow'));
	    }else{
			echo json_encode(array('status'=>false, 'action'=>'unfollow'));
	    } 
	}
	die;
}

/**
 * [huoxing_follow_list_info description]
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
function huoxing_follow_list_info( $uid, $key, $limit=0, $offset=0 ) {  
    global $wpdb;
    $user_list_info = array ();
    if(trim($key) == 'follow'){
    	$result=$wpdb->get_results("SELECT follow_id AS uid FROM wp_hx_follow WHERE user_id = $uid ORDER BY id DESC LIMIT $offset,$limit" );
    }else {
    	$result=$wpdb->get_results("SELECT user_id AS uid FROM wp_hx_follow WHERE follow_id = $uid ORDER BY id DESC LIMIT $offset,$limit" );
    }
	
	foreach ($result as $item) {
		$user = get_userdata( $item->uid );
		$user_list_info[] = array(
            'id' => $user->ID,
          	'name' => $user->display_name,
			'url' => esc_url( get_author_posts_url( $user->ID ) ),
			'time' => $user->user_registered
		);
	}
    return  $user_list_info;  
} 
