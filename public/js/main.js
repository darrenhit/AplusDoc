/**
 * JS方法库
 */
// 跳转至指定的模块
function goto(which_div)
{
	/*$(".nav").children('a').each(function(){
		$(this).removeClass('cur');
	});*/
	$.cookie('justHeadHref',which_div,{expires:1,path:'/'});
	if (which_div == 'createContents')
	{
		window.location.href = "/contents/create.html";
	}
	else if (which_div == 'createDocument')
	{
		window.location.href = "/document/create.html";
	}
	else if (which_div == 'guidDocument')
	{
		window.location.href = "/document/guidDocument.html";
	}
	else if (which_div == 'indexManage'){
		window.location.href = "/document/home.html";
	}
	else if (which_div == 'userManage')
	{
		window.location.href = '/user/index.html';
	}
	else if (which_div == 'userReply')
	{
		window.location.href = '/index/reply.html';
	}
}

// 显示其他平台列表
function showLinkDiv()
{
	document.getElementById('link_div').style.display = "";
}

// 前台展示GUID文档列表
function getGuidList(){
	window.location.href = '/document/guidList.html';
}

// 前台展示文档
function getDocument(CId){
	$.cookie('content','contentItem'+CId,{expires:1,path:'/'});
	window.location.href = '/document/show/'+CId+'.html';
}

// 前台问题反馈JS方法
function reply(){
	window.location.href = '/index/reply.html';
}

//前台搜索JS方法
function searchFront(obj){
	var form = $(obj).parent('form');
	var condition = $(form).find("input[name='condition']").val();
	var type = $(form).find("select option:selected").val();
	if(condition == ''){
		window.showMsg('请填写查询条件');
	}else if('guid' == type && condition.search(/[\d\w]{32}/i) == -1){
		window.showMsg('请填写正确的GUID值');
	}else{
		window.location.href = '/document/search?condition='+encodeURI(condition)+'&type='+type;
	}
}

// 后台创建、修改目录时切换父目录检查所有者
function checkContentsOwner(obj){
	var cid = $(obj).children('option:selected').val();
	if(cid == 0){
		return true;
	}
	$.post('/contents/checkContentsOwner.html',{CId:cid},function(data){
		if(data.err){
			$('#message').html(data.result);
			$('#message').dialog('open');
		}
	},'json');
}

// 后台创建、修改文档时切换父目录检查所有者
function checkDocumentOwner(obj){
	flag = true;
	var id = $('#DocumentForm_id').val();
	var cid = $(obj).children('option:selected').val();
	$.ajax({
		url:'/document/hasDocument.html',
		async:false,
		dataType:'json',
		type:'POST',
		data:'CId='+cid+'&DId='+id,
		success:function(data){
			if(data.err){
				$(obj).children("option[value='"+cid+"']").remove();
				$('#message').html(data.result);
				$('#message').dialog('open');
				flag = false;
			}
		}
	});
	if(flag == false) return;
	$.post('/contents/checkContentsOwner.html',{CId:cid},function(data){
		if(data.err){
			$(obj).children("option[value='"+cid+"']").remove();
			$('#message').html(data.result);
			$('#message').dialog('open');
		}
	},'json');
}

// 创建、修改文档时判断是否管理Aplus数据
function isGuidDocument(obj){
    if($(obj).val() == 1){
        $("#guid-div").show();
        $("#cid-div").hide();
        var span = $("#title-span");
        if(span) span.remove();
    }else if($(obj).val() == 0){
        $("#guid-div").hide();
        $("#cid-div").show();
        $("#title").parent('div').after('<span class="text-danger" id="title-span" style="font-size: 27px;">*</span>');
    }
}

// 后台创建、修改文档时显示Aplus数据列表
function showGuidList(){
	var list = '<div id="guidTree"></div>';
	$('#guid-list-div').html(list);
	$('#guidTree').jstree({
		'core':{
			'strings' : {
	            'Loading ...' : 'Please wait ...'
	        },
			'data':{
				'url':function(node){
					var param = node.id;
					if(param.indexOf('node') != -1){
						return '/index/getAplusNodeList';
					}else if(param.indexOf('page') != -1){
						return '/index/getAplusPageList';
					}else if(param.indexOf('segment') != -1){
						return '/index/getAplusSegmentList';
					}else if(param.indexOf('template') != -1){
						return '/index/getAplusTemplateList';
					}else if(param.indexOf('model') != -1){
						return '/index/getAplusModelList';
					}else{
						return '/index/getAplusNodeList';
					}
				},
				'type':'POST',
				'data':function(node){
					return {'Id':node.id}
				},
				/*
				 * 'success':function(data){ if(data.err == 0){
				 * $(this).text(data.result); } },
				 */
				'dataType':'json'
			},
			'multiple':false,
			'themes':{
				'responsive' : false,
				'variant' : 'small',
				'stripes' : true,
				'icons':true
			}
		},
	});
	$('#guid-list-div').dialog('open');
}

// 后台使用选中的GUID节点
function useGuid(guid){
	$('#guid').val(guid);
	$('#guid-list-div').dialog('close');
}

// 后台创建子目录JS方法
function createContents(cid){
	window.location.href = '/contents/create/'+cid+'.html';
}

// 后台编辑目录JS方法
function editContents(cid){
	$.get('/contents/checkContentsOwner.html',{CId:cid},function(data){
		if(data.err){
			$('#message').html(data.result);
			$('#message').dialog('open');
		}else{
			window.location.href = '/contents/edit/'+cid+'.html';
		}
	},'json');
}

// 后台删除目录JS方法
function deleteContents(cid){
	if(confirm('确定删除当前目录')){
		var flag = false;
		$.ajax({
			url:'/contents/delete.html',
			data:"CId="+cid,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					if(data.err == 2){
						$('#message').html(data.result);
						$('#message').dialog({buttons:[{text:"是",click:function(){confirmDeleteContents(cid);}},{text:"否",click:function(){$(this).dialog('close');}}]});
						$('#message').dialog('open');
					}else{
						$('#message').html(data.result);
						$('#message').dialog('open');
					}
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/';
	}else{
		return false;
	}
}

// 后台确认删除目录及其下所有内容
function confirmDeleteContents(cid){
	var flag = false;
	$.ajax({
		url:'/contents/confirmDelete.html',
		data:"CId="+cid,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			if(data.err){
				$('#message').html(data.result);
				$('#message').dialog('open');
			}else{
				flag = true;
			}
		}
	});
	if(flag) window.location.href = '/';
}

// 后台还原目录JS方法
function resaveContents(cid){
	var flag = false;
	$.ajax({
		url:'/contents/resave.html',
		data:"CId="+cid,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			if(data.err){
				$('#message').html(data.result);
				$('#message').dialog('open');
			}else{
				flag = true;
			}
		}
	});
	if(flag) window.location.href = '/';
}

// 后台删除回收站中目录JS方法
function recycleDelContents(cid){
	if(confirm('确定删除回收站中当前目录')){
		var flag = false;
		$.ajax({
			url:'/contents/recycleDel.html',
			data:"CId="+cid,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/contents/recycle.html';
	}else{
		return false;
	}
}

// 后台显示目录对应的文档
function showDocumentByCId(cid){
	$.ajax({
		url:'/document/getDocumentByCId.html',
		data:"CId="+cid,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			if(data.err == 1){
				$('#message').html(data.result);
				$('#message').dialog({buttons:[
				    {
				    	text:"确定",
				    	click:function(){
				    		window.location.href = '/document/create?CId='+cid;
				    	}
				    },
				    {
				    	text:"取消",
				    	click:function(){
				    		$(this).dialog('close');
				    	}
				    }
				]});
				$('#message').dialog('open');
			}else if(data.err == 2){
				$('#message').html(data.info);
				$('#message').dialog({buttons:[
				    {
				    	text:"确定",
				    	click:function(){
				    		resaveDocument(data.result);
				    		window.location.href = '/document/edit/'+data.result+'.html';
				    	}
				    },
				    {
				    	text:"取消",
				    	click:function(){
				    		$(this).dialog('close');
				    	}
				    }
				]});
				$('#message').dialog('open');
			}else{
				window.location.href = '/document/edit/'+data.result+'.html';
			}
		}
	});
}

// 后台编辑文档JS方法
function editDocument(did){
	$.get('/document/checkDocumentOwner',{DId:did},function(data){
		if(data.err){
			$('#message').html(data.result);
			$('#message').dialog('open');
		}else{
			window.location.href = '/document/edit/'+did+'.html';
		}
	},'json');
}

// 后台删除文档JS方法
function deleteDocument(did){
	if(confirm('确定删除当前文档')){
		var flag = false;
		$.ajax({
			url:'/document/delete.html',
			data:"DId="+did,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/document/guidDocument.html';
	}else{
		return false;
	}
}

// 后台根据CId删除文档JS方法
function deleteDocumentByCId(cid){
	if(confirm('确定删除文档')){
		$.ajax({
			url:'/document/deleteByCId.html',
			data:"CId="+cid,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}
			}
		});
	}else{
		return false;
	}
}

// 后台还原回收站中文档
function resaveDocument(did){
	var flag = false;
	$.ajax({
		url:'/document/resave.html',
		data:"DId="+did,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			if(data.err){
				$('#message').html(data.result);
				$('#message').dialog('open');
			}else{
				flag = true;
			}
		}
	});
	if(flag) window.location.href = '/document/list.html';
}

// 后台删除回收站中文档
function recycleDelDocument(did){
	if(confirm('确定删除回收站中当前文档')){
		var flag = false;
		$.ajax({
			url:'/document/recycleDel.html',
			data:"DId="+did,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/document/recycle.html';
	}else{
		return false;
	}
}

// 后台修改用户JS方法
function editUser(uid){
	$.post('/user/isAdmin','',function(data){
		if(data.err){
			$('#message').html(data.result);
			$('#message').dialog('open');
		}else{
			window.location.href = '/user/edit/'+uid+'.html';
		}
	},'json');
}

// 后台删除用户JS方法
function deleteUser(uid){
	if(confirm('确定删除当前用户')){
		var flag = false;
		$.ajax({
			url:'/user/delete.html',
			data:"UId="+uid,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/user/index.html';
	}else{
		return false;
	}
}

// 后台还原用户JS方法
function resaveUser(uid){
	var flag = false;
	$.ajax({
		url:'/user/resave.html',
		data:"UId="+uid,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			if(data.err){
				$('#message').html(data.result);
				$('#message').dialog('open');
			}else{
				flag = true;
			}
		}
	});
	if(flag) window.location.href = '/user/index.html';
}

// 后台删除回收站中用户JS方法
function recycleDelUser(uid){
	if(confirm('确定删除当前用户')){
		var flag = false;
		$.ajax({
			url:'/user/recycleDel.html',
			data:"UId="+uid,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				if(data.err){
					$('#message').html(data.result);
					$('#message').dialog('open');
				}else{
					flag = true;
				}
			}
		});
		if(flag) window.location.href = '/user/recycle.html';
	}else{
		return false;
	}
}

// 后台关闭用户问题反馈JS方法
function closeReply(id){
	if(confirm('确定要关闭当前反馈')){
		$.post('/index/replyClose.html',{Id:id},function(data){
			if(data.err){
				$('#message').html(data.result);
				$('#message').dialog('open');
			}else{
				window.location.href = '/index/reply.html';
			}
		},'json');
	}else{
		return false;
	}
}

// 后台搜索JS方法
function search(obj){
	var form = $(obj).parent('form');
	var condition = $(form).find("input[name='condition']").val();
	var type = $(form).find("select option:selected").val();
	//var main = parent.frames['main'];
	if(condition == ''){
		showMsg('请填写查询条件');
	}else if('guid' == type && condition.search(/[\d\w]{32}/i) == -1){
		showMsg('请填写正确的GUID值');
	}else{
		window.location.href = '/document/search?condition='+encodeURI(condition)+'&type='+type;
	}
}

// 前后台双击目录
function catalogSelected(cid){
	if(window.location.hostname.search(/admin/i) != -1){
		var div_tab = $.cookie('justHeadHref') == 'undefined' ? '' : $.cookie('justHeadHref');
		if(div_tab == 'createContents'){
			$(window.document).find("#pid option[value='"+cid+"']").attr('selected','selected');
		}else if(div_tab == 'createDocument'){
			$.ajax({
				url:'/document/hasDocument.html',
				async:false,
				dataType:'json',
				type:'POST',
				data:'CId='+cid+'&DId=',
				success:function(data){
					if(data.err){
						$('#pid').children("option[value='"+cid+"']").remove();
						$('#message').html(data.result);
						$('#message').dialog('open');
					}else{
						$(window.document).find("#pid option[value='"+cid+"']").attr('selected','selected');
					}
				}
			});
		}else{
			$.cookie('managerContent','contentItem'+cid,{expires:1,path:'/'});
			window.location.href = '/contents/list/'+cid+'.html';
		}
	}else{
		getDocument(cid);
	}
}

// 前后台显示消息
function showMsg(msg){
	$('#message').html(msg);
	$('#message').dialog('open');
}

// 前后台修改父级页面的Title
function modifyTitle(){
	parent.document.title = window.document.title;
}