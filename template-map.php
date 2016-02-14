<?php
/**
*  Template Name:地图
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<style type="text/css">
	#container{height:500px;overflow: visible;}    
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=0GXdHQQsE8O00D51GNtMzhAo"></script>


<div id="container"></div>
<script type="text/javascript"> 
var map = new BMap.Map("container");    
var point = new BMap.Point(116.404, 39.915);    
map.centerAndZoom(point, 15);    
window.setTimeout(function(){  
    map.panTo(new BMap.Point(116.409, 39.918));    
}, 2000);
var marker = new BMap.Marker(point);        // 创建标注    
map.addOverlay(marker);                     // 将标注添加到地图中
</script>  
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>

