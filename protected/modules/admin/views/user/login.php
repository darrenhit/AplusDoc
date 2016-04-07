<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/public/css/login.css" />
<?php echo CHtml::beginForm(); ?>
<div class="login clearfix">
	<div class="bbg">
		<img src="/public/images/backend/bg_login.jpg" alt="" width="100%" height="100%" />
	</div>
	<div style="margin:0 auto;width:400px;height:50px;"><?php echo CHtml::errorSummary($model,'','',array('class'=>'alert alert-danger','role'=>'alert')); ?></div>
	<div class="loginbox">
		<h1><input type="image" src="/public/images/backend/login_logo.png" /></h1>
		<table>
			<tr>
				<td>用户名：</td>
				<td><?php echo CHtml::activeTextField($model,'username',array('class'=>'w165','id'=>'username','placeholder'=>'请输入用户名')) ?></td>
			</tr>
			<tr>
				<td>密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
				<td><?php echo CHtml::activePasswordField($model, 'password',array('class'=>'w165','id'=>'password','placeholder'=>'请输入密码'))?></td>
			</tr>
			<tr>
				<td> </td>
				<td><?php echo CHtml::submitButton('',array('class'=>'btn btn_login'));?></a>
				</td>
			</tr>
		</table>
		<p align="center">APlusCMS V2.0 &nbsp;&nbsp;Support by RnD Web Div</p>
	</div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
$(function(){
	$('#username').focus();
});
</script>