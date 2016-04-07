<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('paramErr')){
    echo '<script type="text/javascript">$(function(){paramErr();});</script>';
}
?>
<script type="text/javascript">
function paramErr(){
	$(":submit,:reset").attr('disabled',true);
	$('#message').html('<?php echo Yii::app()->user->getFlash('paramErr');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/user/index.html';
	}});
}
</script>
<div class="form form-horizontal">
    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($model,'Tips：','',array('class'=>'alert alert-danger','role'=>'alert')); ?>
    
	<div class="form-group">
	   <?php echo CHtml::activeHiddenField($model, 'id');?>
	</div>
	
	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'username',array('class'=>'col-sm-3 control-label','for'=>'username')); ?>
		<div class="col-sm-6">
		  <?php echo CHtml::activeTextField($model,'username',array('class'=>'form-control','id'=>'username','readonly'=>true));?>
		</div>
	</div>
	
    <div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<div class="checkbox">
				<label>
					  <?php echo CHtml::activeRadioButtonList($model, 'isAdmin', array(0=>'普通用户',1=>'管理员'),array('template'=>'{input}{label}','separator'=>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'onclick'=>'isGuidDocument(this);'));?>
					  <?php echo CHtml::activeLabel($model, 'isAdmin');?>
				</label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'oldPwd',array('class'=>'col-sm-3 control-label','for'=>'oldPwd')); ?>
		<div class="col-sm-6">
		  <?php echo CHtml::activePasswordField($model,'oldPwd',array('class'=>'form-control','id'=>'oldPwd','placeholder'=>'请输入原始密码'))?>
		</div>
	</div>
	
	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'newPwd',array('class'=>'col-sm-3 control-label','for'=>'newPwd')); ?>
		<div class="col-sm-6">
		  <?php echo CHtml::activePasswordField($model,'newPwd',array('class'=>'form-control','id'=>'newPwd','placeholder'=>'请输入新密码'))?>
		</div>
	</div>
	
	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'confirmPwd',array('class'=>'col-sm-3 control-label','for'=>'confirmPwd')); ?>
		<div class="col-sm-6">
		  <?php echo CHtml::activePasswordField($model,'confirmPwd',array('class'=>'form-control','id'=>'confirmPwd','placeholder'=>'请输入确认密码'))?>
		</div>
	</div>
	
	<div class="form-group">
	   <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-success btn-lg col-sm-offset-3'));?>
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
</div>