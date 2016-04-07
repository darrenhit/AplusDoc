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
<?php if($title == '我的文档目录回收站'):?>
<p class="text-right"><a href="<?php echo Yii::app()->createUrl('/contents/list');?>">我的文档目录</a></p>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="30%" style="text-align:center;">目录标题</td>
        <th width="10%" style="text-align:center;">创建者</td>
        <th width="18%" style="text-align:center;">创建时间</td>
        <th width="10%" style="text-align:center;">修改者</td>
        <th width="18%" style="text-align:center;">修改时间</td>
        <th width="14%" style="text-align:center;">操作</td>
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
        <td style="text-align:center;"><a href="javascript:void(0)" class="ico12" title="还原目录" onclick="resaveContents(<?php echo $item->id;?>)"></a>&nbsp;<a href="javascript:void(0);" class="ico19" title="删除目录" onclick="recycleDelContents(<?php echo $item->id;?>)"></a></td>
    </tr>
    <?php
        }
    }
    ?>
</table>
<?php elseif($title == '我的文档内容回收站'):?>
<p class="text-right"><a href="<?php echo Yii::app()->createUrl('/contents/list');?>">我的文档目录</a></p>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="24%" style="text-align:center;">文档标题</td>
        <th width="10%" style="text-align:center;">GUID文档</td>
        <th width="28%" style="text-align:center;">GUID</td>
        <th width="12%" style="text-align:center;">创建者</td>
        <th width="9%" style="text-align:center;">创建时间</td>
        <th width="9%" style="text-align:center;">修改时间</td>
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
        <td><?php echo date('Y-m-d',strtotime($item->created_on));?></td>
        <td><?php echo date('Y-m-d',strtotime($item->modified_on));?></td>
        <td style="text-align:center;"><a href="javascript:void(0);" class="ico12" title="还原文档" onclick="resaveDocument(<?php echo $item->id;?>)"></a><a href="javascript:void(0);" class="ico19" title="删除文档" onclick="recycleDelDocument(<?php echo $item->id;?>);"></a></td>
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
	if($.cookie('justHeadHref') != 'undefined'){
	    $.removeCookie('justHeadHref',{path:'/'});
	}
})
</script>