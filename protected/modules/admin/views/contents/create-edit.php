<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('err')){
    echo '<script type="text/javascript">$(function(){noAuth()});</script>';
}
?>
<script type="text/javascript">
function noAuth(){
	$(":submit,:reset").attr('disabled',true);
	$('#message').html('<?php echo Yii::app()->user->getFlash('err');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/index/main.html';
	}});
}
</script>
<h1><?php echo $title?></h1>
<div class="form form-horizontal">
<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($model,'Tips：','',array('class'=>'alert alert-danger','role'=>'alert')); ?>
	
	<div class="form-group">
	   <?php echo CHtml::activeHiddenField($model, 'id');?>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'title',array('class'=>'col-sm-2 control-label','for'=>'title')); ?>
		<div class="col-sm-7">
		  <?php echo CHtml::activeTextField($model,'title',array('class'=>'form-control','id'=>'title','placeholder'=>'请输入目录名称')) ?>
		</div><span class="text-danger" style="font-size: 27px;">*</span>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'pid',array('class'=>'col-sm-2 control-label','for'=>'pid')); ?>
		<div class="col-sm-7">
    		<?php echo CHtml::activeDropDownList($model, 'pid', $parent,array('class'=>'form-control','id'=>'pid','prompt'=>'--请选择父目录--','encode'=>false,'onchange'=>'checkContentsOwner(this)'))?>
		</div><span class="text-danger" style="font-size: 27px;">*</span>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'explanation',array('class'=>'col-sm-2 control-label','for'=>'explanation')); ?>
		<div class="col-sm-7">
    		<?php echo CHtml::activeTextArea($model,'explanation',array('rows'=>5,'class'=>'form-control','id'=>'explanation','placeholder'=>'请输入相关说明')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton('保存',array('class'=>'btn btn-success btn-lg col-sm-offset-3')); ?>
		<?php echo CHtml::resetButton('重置',array('class'=>'btn btn-warning btn-lg col-sm-offset-1'));?>
		<?php echo CHtml::button('返回',array('class'=>'btn btn-default btn-lg col-sm-offset-1','onClick'=>'javascript:window.history.back();'));?>
	</div>

<?php echo CHtml::endForm(); ?>
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
</div><!-- form -->