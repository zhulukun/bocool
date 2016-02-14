jQuery(document).ready(function($) {
//follow or unfollow handle
var RefreshIcon = '<span class="glyphicon glyphicon-refresh rotate"></span>';
function isFollowAuthor(c){
    var action = c.attr("data-action");
	var curUserID = $("#currentUserId").val();
    //currentUserId is 0 means logout
   //if login, send ajax request
   if(c.hasClass("follow")){
	    c.addClass('disabled').html("关注中").append(RefreshIcon);
   }else if(c.hasClass("unfollow")) {
	    c.addClass('disabled').html("取消关注中").append(RefreshIcon);
   }

	$.ajax({
		url:ajaxurl,
		type: 'POST',
		dataType: 'json',
		cache: false,
		data:{
			'action': action,
			'uid': curUserID,
			'fid':c.parent().find("#followUserId").val(),
			'_wpnonce':c.parent().find("#hx_nonce").val()
		},
		success:function(data){

			if (data.action=="follow" && data.status) {
				
				c.attr("data-action","ajaxunfollow");
				c.removeClass("follow").addClass("unfollow");
				c.html("<i class='fa fa-user-times'></i>已关注");
				
			}else if(data.action=="unfollow" && data.status){
				c.attr("data-action","ajaxfollow");
				c.removeClass("unfollow").addClass("follow");
				c.html("<i class='fa fa-user-plus'></i>关注");
				
			}else {
				return false;
			}
		},
		error:function(data){
			return false;
		}
	});
}

$(".isfollow").click(function(){ 
var c = $(this);
	if(!isUserLoggedIn){
    	window.location.href = loginUrl;//if logout,first to login
		return false;
    }
    if(c.attr("data-action") == "ajaxunfollow") {
    	$('#myModal').modal({backdrop:false,show:true});
		$("#sure").click(function(){
		    $('#myModal').modal('hide');
			isFollowAuthor(c);   
			return false;
		});
    }
    if(c.attr("data-action") == "ajaxfollow") {
	    isFollowAuthor(c);
	    return false;
    }	
    return false;
});
	
	
})
