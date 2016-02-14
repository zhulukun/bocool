<?php
/**
*  Template Name:全屏地图
*
*/
?>
<style type="text/css">
	#container{height:100%;overflow: visible;}    
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=0GXdHQQsE8O00D51GNtMzhAo"></script>


<div id="container"></div>
<script type="text/javascript"> 
var map = new BMap.Map("container");    
var point = new BMap.Point(116.486859,40.018624);    
map.centerAndZoom(point, 15);    
window.setTimeout(function(){  
    map.panTo(new BMap.Point(116.486859,40.018624));    
}, 2000);
var marker = new BMap.Marker(point);        // 创建标注    
map.addOverlay(marker);                     // 将标注添加到地图中
</script>  


