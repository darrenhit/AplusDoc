<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('param')){
    echo '<script type="text/javascript">$(function(){paramErr();});</script>';
}
if(Yii::app()->user->hasFlash('auth')){
    echo '<script type="text/javascript">$(function(){noAuth();});</script>';
}
?>
<script type="text/javascript">
function noAuth(){
	$("#btnClose").attr('disabled',true);
	$('#message').html('<?php echo Yii::app()->user->getFlash('auth');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    parent.location.href = '/';
	}});
}
function paramErr(){
	$('#message').html('<?php echo Yii::app()->user->getFlash('param');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    parent.location.href = '/index/reply';
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
<?php if($model):?>
<div class="row">
    <div class="page-header text-center">
      <h1 style="font-weight: bolder"><?php echo $model->title?></h1>
    </div>
</div>
<div class="row">
    <div class="col-sm-offset-5">
        <p><span class="label label-default">Tel:</span>&nbsp;<?php echo $model->telephone;?></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-offset-5">
        <p><span class="label label-default">Email:</span>&nbsp;<?php echo $model->email;?></p>
    </div>
</div>
<div class="row">
    <?php $this->beginWidget('CHtmlPurifier');?>
    <p><?php echo $model->description;?></p>
    <?php $this->endWidget();?>
</div>
<div class="row" style="padding:15px;"></div>
<div class="row">
    <p class="text-center">
        <?php echo CHtml::button('关闭',array('class'=>"btn btn-primary",'id'=>'btnClose','onClick'=>'closeReply('.$model->id.');'));?>
        <?php echo CHtml::button('返回',array('class'=>"btn btn-default",'id'=>'btnBack','onClick'=>'window.history.back();'));?>
    </p>
</div>
<?php endif;?>