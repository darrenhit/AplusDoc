<script src="<?php echo Yii::app()->request->baseUrl; ?>/public/js/FileTreeView.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/public/css/FileTreeView.css">
<script type="text/javascript">
$(function(){
	$('.menu ul').fileTreeView('#expandAll', '#collapseAll', 'folder');
	$('#collapseAll').click();
	$('.menu').css('height',$(window).height());
});
</script>
<style type="text/css">
*{margin:0;padding:0;list-style-type:none;font-size:14px;}
a{text-decoration:none;color:#000000;}
a,img{border:0;}
#files{margin:100px auto;width:400px;}
.tree,.tree ul,.tree li{list-style:none;margin:0;padding:0;zoom: 1;}
.tree li{
    display:block;/*内联对象需加*/ 
    width:31em; 
    word-break:keep-all;/* 不换行 */ 
    white-space:nowrap;/* 不换行 */ 
    overflow:hidden;/* 内容超出宽度时隐藏超出部分的内容 */ 
    text-overflow:ellipsis;/* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/ 
}
.tree ul{margin-left:8px;}
.tree li a:hover,.tree li a.tree-parent:hover,.tree li a:focus,.tree li a.tree-parent:focus,.tree li a.tree-item-active{color:#000;border:1px solid#eee;background-color:#fafafa;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;}
.tree li a:focus,.tree li a.tree-parent:focus,.tree li a.tree-item-active{border:1px solid #e2f3fb;background-color:#f2fafd;}
.tree ul.tree-group-collapsed{display:none;}
</style>
<div class="menu" style="margin-top: 20px;margin-left:45%;background-color:#F3F3F3;padding:5px 0 0 5px;">
    <div style="display: none;"><a id="collapseAll"></a></div>
    <ul class="tree">
        <?php 
            echo $catalog;
        ?>
        <li><a href="javascript:void(0);" onclick="getGuidList();">Guid/id文档</a></li>
        <li><a href="javascript:void(0);" onclick="reply();">问题反馈</a></li>
    </ul>
</div>