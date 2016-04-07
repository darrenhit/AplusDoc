<div class="header clearfix" style="height:80px;">
	<a href="javascript:void(0);" onclick="parent.location.href='/'">
		<h1
			style='float: left; width: 300px; height: 50px; text-indent: 183px; line-height: 54px; background: url(/public/images/frontend/logo.png) no-repeat;'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;帮助中心
		</h1>
	</a>
	<form class="navbar-form" style="float:right;margin:10px 10px 0 0;">
		<div class="form-group">
			<input type="text" name="condition" placeholder="请输入你想了解的问题、关键词"
				class="form-control" style="width: 300px;">
			<select class="form-control" name="type" style="width: 110px;">
                <option value="title">文档标题</option>
                <option value="">文档简介/内容/参考</option>
                <option value="guid">GUID</option>
            </select>
		</div>
		<button type="button" onclick="search(this);" class="btn btn-info" style="width: 100px;">搜索</button>
	</form>
	<div class="nav clearfix">
		<a href="#" id="createContents_div" onclick="goto('createContents')"><i>新建目录</i></a><span class=""></span>
		<a href="#" id="createDocument_div" onclick="goto('createDocument')"><i>新建文档</i></a><span class=""></span>
		<a href="#" id="guidDocument_div" onclick="goto('guidDocument')"><i>GUID文档</i></a><span class=""></span>
		<?php if($isAdmin):?>
		<a href="#" id="indexManage_div" onclick="goto('indexManage')"><i>首页管理</i></a><span class=""></span>
		<a href="#" id="userManage_div" onclick="goto('userManage')"><i>用户管理</i></a><span class=""></span>
		<a href="#" id="userReply_div" onclick="goto('userReply')"><i>用户反馈</i></a><span class=""></span>
		<?php endif;?>
		<input type=hidden id="justHeadHref" value="default">
	</div>
	<div class="load">
		<strong><?php echo $username?></strong>[<a href="###"
			onclick="parent.location='/user/logout.html';">退出</a>] [<a
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