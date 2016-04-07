<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('auth')){
    echo '<script type="text/javascript">$(function(){noAuth();});</script>';
}
?>
<script type="text/javascript">
function noAuth(){
	$('#message').html('<?php echo Yii::app()->user->getFlash('auth');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    parent.location.href = '/';
	}});
}
</script>
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
<?php if(count($list)):?>
    <?php foreach($list as $key=>$item){?>
    <a href="<?php echo Yii::app()->createUrl('/index/replyDetail',array('Id'=>$item->id));?>" class="list-group-item <?php if($key == 0) echo 'active';?>">
		<h4 class="list-group-item-heading"><?php echo $item->title?></h4>
		<p class="list-group-item-text"><?php echo $item->created_on;?></p>
	</a>
  <?php }?>
<?php $this->widget('LinkPager',array('pages'=>$page,'htmlOptions'=>array('style'=>'margin:20px 0 0 0;')))?>
<?php endif;?>