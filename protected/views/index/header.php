<div class="header clearfix">
    <a href="javascript:void(0);" onclick="parent.location.href='/'">
	   <h1 style='float:left;width:400px;height:50px;text-indent:183px;line-height:54px;background:url(/public/images/frontend/logo.png) no-repeat;'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;帮助中心 </h1>
    </a>
    <div style="float: right;margin:15px 20px 0 0;">[<a href="http://admin.doc.synacast.com/user/login" target="_blank">登录</a>]</div>
    <form class="navbar-form">
        <div class="form-group">
          <input type="text" name="condition" placeholder="请输入你想了解的问题、关键词" class="form-control" style="width:400px;">
          <select class="form-control" name="type" style="width: 150px;">
            <option value="title">文档标题</option>
            <option value="">文档简介/内容/参考</option>
            <option value="guid">GUID</option>
          </select>
        </div>
        <button type="button" onclick="search(this);" class="btn btn-info" style="width:100px;">搜索</button>
    </form>
</div>