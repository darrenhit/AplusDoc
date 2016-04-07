<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
?>
<?php 
    $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
        'id'=>'message',
        'options'=>array(
            'title'=>'Tips',
            'autoOpen'=>false,
            'resizable'=>false,
        ),
    ));
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- 
<div class="btn-group col-sm-offset-4" role="group" aria-label="...">
    <a href="<?php echo Yii::app()->createUrl('/contents/list');?>" class="btn btn-default">我的目录</a>
    <a href="<?php echo Yii::app()->createUrl('/document/list');?>" class="btn btn-default">我的文档</a>
</div>
-->
<?php if($title == '我的目录'):?>
<p class="text-right"><a href="<?php echo Yii::app()->createUrl('/contents/recycle');?>">文档目录回收站</a> | <a href="<?php echo Yii::app()->createUrl('/document/recycle');?>">文档内容回收站</a></p>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="26%" style="text-align:center;">目录标题</td>
        <th width="10%" style="text-align:center;">创建者</td>
        <th width="18%" style="text-align:center;">创建时间</td>
        <th width="10%" style="text-align:center;">修改者</td>
        <th width="18%" style="text-align:center;">修改时间</td>
        <th width="18%" style="text-align:center;">操作</td>
    </tr>
    <?php 
    if(count($list)){
        foreach($list as $item){
    ?>
    <tr>
        <td><?php echo $item->title;?></td>
        <td style="text-align:center;"><?php echo $item->created_by;?></td>
        <td><?php echo $item->created_on;?></td>
        <td style="text-align: center;"><?php echo $item->modified_by;?></td>
        <td><?php echo $item->modified_on;?></td>
        <td style="text-align:center;">
            <a href="javascript:void(0)" class="icoPlus" title="创建子目录" onclick="createContents(<?php echo $item->id;?>)"></a>&nbsp;
            <?php if($this->isAdmin || $item->created_by == Yii::app()->session['username']):?>
            <a href="javascript:void(0)" class="ico4" title="编辑文档目录" onclick="editContents(<?php echo $item->id;?>)"></a>&nbsp;
            <a href="javascript:void(0);" class="ico19" title="删除文档目录" onclick="deleteContents(<?php echo $item->id;?>)"></a>&nbsp;
            <a href="javascript:void(0);" class="ico1" title="编辑文档内容" onclick="showDocumentByCId(<?php echo $item->id;?>)"></a>&nbsp;
            <a href="javascript:void(0);" class="ico6" title="删除文档内容" onclick="deleteDocumentByCId(<?php echo $item->id;?>)"></a>
            <?php endif;?>
        </td>
    </tr>
    <?php
        }
    }
    ?>
</table>
<script type="text/javascript">
$(function(){
	if($('#contentItem<?php if($contentId) echo $contentId;?>')){
		$('#contentItem<?php if($contentId) echo $contentId;?>').addClass('treeSelected');
	}
});
</script>
<?php elseif($title == '我的文档'):?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="31%" style="text-align:center;">文档标题</td>
        <th width="7%" style="text-align:center;">GUID文档</td>
        <th width="14%" style="text-align:center;">GUID</td>
        <th width="10%" style="text-align:center;">创建者</td>
        <th width="15%" style="text-align:center;">创建时间</td>
        <th width="15%" style="text-align:center;">修改时间</td>
        <th width="8%" style="text-align:center;">操作</td>
    </tr>
    <?php 
    if(count($list)){
        foreach($list as $item){
            $flag = empty($item->guid) ? '否':'是';
    ?>
    <tr>
        <td><?php echo $item->title;?></td>
        <td style="text-align: center;"><?php echo $flag;?></td>
        <td><?php echo $item->guid;?></td>
        <td style="text-align:center;"><?php echo $item->created_by;?></td>
        <td><?php echo $item->created_on;?></td>
        <td><?php echo $item->modified_on;?></td>
        <td style="text-align:center;"><a href="javascript:void(0);" class="ico4" title="编辑文档内容" onclick="editDocument(<?php echo $item->id;?>)"></a>&nbsp;<a href="javascript:void(0);" class="ico6" title="删除文档" onclick="deleteDocument(<?php echo $item->id;?>);"></a></td>
    </tr>
    <?php
        }
    }
    ?>
</table>
<?php elseif($title == 'GUID文档'):?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="20%" style="text-align:center;">GUID</td>
        <th width="13%" style="text-align:center;">创建者</td>
        <th width="22%" style="text-align:center;">创建时间</td>
        <th width="13%" style="text-align:center;">修改者</td>
        <th width="22%" style="text-align:center;">修改时间</td>
        <th width="10%" style="text-align:center;">操作</td>
    </tr>
    <?php 
    if(count($list)){
        foreach($list as $item){
    ?>
    <tr>
        <td><?php echo $item->guid;?></td>
        <td style="text-align:center;"><?php echo $item->created_by;?></td>
        <td><?php echo $item->created_on;?></td>
        <td style="text-align:center;"><?php echo $item->modified_by;?></td>
        <td><?php echo $item->modified_on;?></td>
        <td style="text-align:center;"><a href="javascript:void(0);" class="ico4" title="编辑文档内容" onclick="editDocument(<?php echo $item->id;?>)"></a>&nbsp;<a href="javascript:void(0);" class="ico6" title="删除文档" onclick="deleteDocument(<?php echo $item->id;?>);"></a></td>
    </tr>
    <?php
        }
    }
    ?>
</table>
<?php endif;?>
<?php $this->widget('LinkPager',array('pages'=>$page));?>
<script type="text/javascript">
$(function(){
	if('GUID文档' != "<?php echo $title;?>" && $.cookie('justHeadHref') != 'undefined'){
	    $.removeCookie('justHeadHref',{path:'/'});
	}
});
</script>