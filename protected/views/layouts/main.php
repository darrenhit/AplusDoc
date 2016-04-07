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

a,img {
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
	width: 31em;
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
<div class="header clearfix" style="height: 80px;">
	<a href="javascript:void(0);" onclick="window.location.href='/'">
		<h1
			style='float: left; width: 300px; height: 50px; text-indent: 183px; line-height: 54px; background: url(/public/images/frontend/logo.png) no-repeat;'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;帮助中心
		</h1>
	</a>
	<form class="navbar-form" style="float: right; margin: 10px 10px 0 0;">
		<div class="form-group">
			<input type="text" name="condition" placeholder="请输入你想了解的问题、关键词"
				class="form-control" style="width: 300px;"> <select
				class="form-control" name="type" style="width: 110px;">
				<option value="title">文档标题</option>
				<option value="">文档简介/内容/参考</option>
				<option value="guid">GUID</option>
			</select>
		</div>
		<button type="button" onclick="search(this);" class="btn btn-info"
			style="width: 100px;">搜索</button>
	</form>
	<div class="nav clearfix">
		<a href="#" id="createContents_div" onclick="goto('createContents')"><i>创建文档</i></a><span
			class=""></span> <!-- <a href="#" id="createDocument_div"
			onclick="goto('createDocument')"><i>新建文档</i></a><span class=""></span> -->
		<a href="#" id="guidDocument_div" onclick="goto('guidDocument')"><i>GUID文档</i></a><span
			class=""></span>
		<?php if($this->isAdmin):?>
		<a href="#" id="indexManage_div" onclick="goto('indexManage')"><i>首页管理</i></a><span
			class=""></span> <a href="#" id="userManage_div"
			onclick="goto('userManage')"><i>用户管理</i></a><span class=""></span> <a
			href="#" id="userReply_div" onclick="goto('userReply')"><i>用户反馈</i></a><span
			class=""></span>
		<?php endif;?>
	</div>
	<div class="load">
		<strong><?php echo $this->username?></strong><?php if($this->username){ ?>[<a href="###"
			onclick="parent.location='/user/logout.html';">退出</a>]<?php }?> [<a
			onmouseover="showLinkDiv()" href="#">其它平台</a>]&nbsp;
		<table id="link_div"
			style="display: none; position: absolute; height: 45px; width: 65px; margin-top: -65px; left: 180px;"
			border="0" class="box" onMouseout="this.style.display='none'"
			onMouseover="this.style.display=''">
			<tr>
				<td style="font-size: 12px; line-height: 20px;" align="center"><a
					target="_blank" href="http://admin.synacast.com">AplusCMS</a></td>
			</tr>
			<tr>
				<td height="1" bgcolor="#CCCCCC"></td>
			</tr>
			<tr>
				<td style="font-size: 12px; line-height: 20px;" align="center"><a
					target="_blank" href="http://cms3.pplive.com/cms2/default.jsp">MacroCMS</a></td>
			</tr>
			<tr>
				<td height="1" bgcolor="#CCCCCC"></td>
			</tr>
			<tr>
				<td style="font-size: 12px; line-height: 20px;" align="center"><a
					target="_blank" href="http://epg.synacast.com">EPG</a></td>
			</tr>
		</table>
	</div>
</div>
<!-- 顶部结束 -->
<div class="container">
	<div class="row" style="margin-top: 20px;">
		<div class="col-sm-3">
			<!-- 左侧菜单开始 -->
			<div class="menu"
				style="background-color: #F5F5F5; padding: 5px 0 0 5px;">
				<div>
					<a id="collapseAll">全部折叠</a> | <a id="expandAll">全部展开</a>
				</div>
				<ul class="tree">
                    <?php
                    echo $this->catalog;
                    ?>
                </ul>
			</div>
            <script
            	src="<?php echo Yii::app()->request->baseUrl; ?>/public/js/FileTreeView.js"
            	type="text/javascript"></script>
            <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/public/js/jquery.cookie.js"></script>
            <script type="text/javascript">
            $(function(){
            	$(".menu ul").fileTreeView('#expandAll', '#collapseAll', 'folder');
            	$("#collapseAll").click();
            	var content = $.cookie('managerContent');
            	if(content != 'undefined'){
            		var contentItem = $('#'+content).parentsUntil('.tree','li');
            		contentItem.each(function(){
            		    $(this).find('div').first().click();
            		});
                }
            	$('.menu').css('height',$(window.document).height()-100);
            	var justHeadHref = $.cookie('justHeadHref');
            	if(justHeadHref != 'undefined')
                	$('#'+justHeadHref+'_div').addClass('cur');
            });
            </script>
			<!-- 左侧菜单结束 -->
		</div>
		<div class="col-sm-9">
            <?php echo $content; ?>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
