<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if (!count($list)) {
    echo '<script type="text/javascript">$(function(){hasNoDocument();});</script>';
}
?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'message',
    'options' => array(
        'title' => 'Tips',
        'autoOpen' => false,
        'resizable'=>false,
    )
));
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<?php if(count($list)):?>
<div class="list-group">
    <?php foreach($list as $key=>$item){?>
    <a href="<?php echo Yii::app()->createUrl('/document/edit',array('DId'=>$item->id));?>" class="list-group-item <?php if($key == 0) echo 'active';?>">
		<h4 class="list-group-item-heading"><?php echo $item->title?></h4>
		<p class="list-group-item-text"><?php echo $item->summary;?></p>
	</a>
    <?php }?>
</div>
<?php $this->widget('LinkPager',array(
    'pages' => $pages,
));?>
<?php endif;?>
<script type="text/javascript">
function hasNoDocument(){
	$('#message').html('不存在相关文档');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/index/main.html';
	}});
}
$(function(){
	if($.cookie('justHeadHref') != 'undefined'){
	    $.removeCookie('justHeadHref',{path:'/'});
	}
})
</script>