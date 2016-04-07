<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/header-layout'); ?>
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/public/css/FileTreeView.css">
<style type="text/css">
* {
	margin: 0;
	padding: 0;
	list-style-type: none;
	font-size: 14px;
}

.tree li a {
	text-decoration: none;
	/*color: #000000;*/
}

.tree li a,.tree li img {
	border: 0;
}

#files {
	margin: 100px auto;
	width: 400px;
}

.tree,.tree ul,.tree li {
	list-style: none;
	margin: 0;
	padding: 0;
	zoom: 1;
}

.tree li {
	display: block; /*内联对象需加*/
	word-break: keep-all; /* 不换行 */
	white-space: nowrap; /* 不换行 */
	overflow: hidden; /* 内容超出宽度时隐藏超出部分的内容 */
	text-overflow: ellipsis;
	/* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/
}

.tree ul {
	margin-left: 8px;
}

.tree li a:hover,.tree li a.tree-parent:hover,.tree li a:focus,.tree li a.tree-parent:focus,.tree li a.tree-item-active
	{
	color: #000;
	border: 1px solid #eee;
	background-color: #fafafa;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}

.tree li a:focus,.tree li a.tree-parent:focus,.tree li a.tree-item-active
	{
	border: 1px solid #e2f3fb;
	background-color: #f2fafd;
}

.tree ul.tree-group-collapsed {
	display: none;
}

.treeSelected {
	color: #F00;
}
</style>
<!-- 顶部开始 -->
<div class="header clearfix">
	<a href="javascript:void(0);" onclick="window.location.href='/'">
		<h1
			style='float: left; width: 400px; height: 50px; text-indent: 183px; line-height: 54px; background: url(/public/images/frontend/logo.png) no-repeat;'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;帮助中心
		</h1>
	</a>
	<div style="float: right; margin: 15px 20px 0 0;">
		[<a href="http://admin.doc.synacast.com/user/login" target="_blank">登录</a>]
	</div>
	<form class="navbar-form">
		<div class="form-group">
			<input type="text" name="condition" placeholder="请输入你想了解的问题、关键词"
				class="form-control" style="width: 400px;"> <select
				class="form-control" name="type" style="width: 150px;">
				<option value="title">文档标题</option>
				<option value="">文档简介/内容/参考</option>
				<option value="guid">GUID</option>
			</select>
		</div>
		<button type="button" onclick="searchFront(this);"
			class="btn btn-info" style="width: 100px;">搜索</button>
	</form>
</div>
<!-- 顶部结束 -->
<!-- 左侧菜单开始 -->
<div class="container">
	<div class="row" style="margin-top: 20px;">
		<div class="col-sm-3">
			<div class="menu"
				style="background-color: #F5F5F5; padding: 5px 0 0 5px;">
				<div>
					<a id="collapseAll">全部折叠</a> | <a id="expandAll">全部展开</a>
				</div>
				<ul class="tree">
            <?php
            echo $this->catalog;
            ?>
            <li><a href="javascript:void(0);" onclick="getGuidList();">Guid/id文档</a></li>
					<li><a href="javascript:void(0);" onclick="reply();">问题反馈</a></li>
				</ul>
			</div>
		</div>
        <script
        	src="<?php echo Yii::app()->request->baseUrl; ?>/public/js/FileTreeView.js"
        	type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/public/js/jquery.cookie.js"></script>
        <script type="text/javascript">
            $(function(){
            	$('.menu ul').fileTreeView('#expandAll', '#collapseAll', 'folder');
            	$("#collapseAll").click();
            	var content = $.cookie('content');
            	if(content != 'undefined'){
            		var contentItem = $('#'+content).parentsUntil('.tree','li');
            		contentItem.each(function(){
            		    $(this).find('div').first().click();
            		});
                }
            	$('.menu').css('height',$(window.document).height()-74);
            });
        </script>
		<!-- 左侧菜单结束 -->
		<!-- 右侧正文开始 -->
		<div class="col-sm-9">
    	   <?php echo $content; ?>
        </div>
		<!-- 右侧正文结束 -->
	</div>
</div>
<?php $this->endContent(); ?>
