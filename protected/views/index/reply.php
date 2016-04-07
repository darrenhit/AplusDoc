<?php
$this->pageTitle = $title . ' ' . Yii::app()->params['title'];
if(Yii::app()->user->hasFlash('reply')){
    echo '<script type="text/javascript">$(function(){});</script>';
}
?>
<script type="text/javascript">
function showTips(){
	$('#message').html('<?php echo Yii::app()->user->getFlash('reply');?>');
	$('#message').dialog('open');
}
</script>
<div>
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
		<div class="form form-horizontal">
        <?php echo CHtml::beginForm(); ?>
            <?php echo CHtml::errorSummary($model,'Tips：','',array('class'=>'alert alert-danger','role'=>'alert')); ?>
            
            <div class="form-group">
        		<?php echo CHtml::activeLabel($model,'title',array('class'=>'col-sm-2 control-label','for'=>'title')); ?>
        		<div class="col-sm-8">
        		  <?php echo CHtml::activeTextField($model,'title',array('class'=>'form-control','id'=>'title','placeholder'=>'请输入问题标题')) ?>
        		</div><span class="text-danger" style="font-size: 27px;">*</span>
        	</div>
        	
        	<div class="form-group">
        		<?php echo CHtml::activeLabel($model,'description',array('class'=>'col-sm-2 control-label','for'=>'description')); ?>
        		<div class="col-sm-8">
            		<?php echo CHtml::activeTextArea($model,'description',array('id'=>'description')); ?>
        		</div><span class="text-danger" style="font-size: 27px;">*</span>
        	</div>
        	
        	<?php
                $this->widget('ueditor.Ueditor', array(
                'getId' => 'description',
                'name' => 'description',
                'options' => '
                    Url : "/public/ueditor/",
                	UEDITOR_HOME_URL : "/public/ueditor/",
                	toolbars:[["fontfamily","fontsize","forecolor","backcolor","bold","italic","strikethrough","superscript","subscript","removeformat","formatmatch","|",
"justifyleft","justifycenter","justifyright","justifyjustify","blockquote","paragraph","lineheight","indent","|",
"insertunorderedlist","insertorderedlist","link","unlink","highlightcode","date","time","horizontal","spechars","insertimage","attachment","inserttable","deletetable","mergecells","splittocells","|","pasteplain","selectall","cleardoc","preview","searchreplace","|","fullscreen","undo","redo","source"]],
                	wordCount:true,maximumWords:300,
                	autoHeightEnabled:false,
                	elementPathEnabled:false,
                	imagePath:"/",
                    imageUrl:"/public/ueditor/php/imageUp.php",
                    filePath:"/",
                    fileUrl:"/public/ueditor/php/fileUp.php",
                	initialContent:"请输入问题描述信息",
                    autoClearinitialContent:true,
                    minFrameHeight:200,
                	iframeCssUrl: "/public/ueditor/themes/default/iframe.css",
					codeMirrorJsUrl:"/public/ueditor/third-party/codemirror2.15/codemirror.js",
					codeMirrorCssUrl:"/public/ueditor/third-party/codemirror2.15/codemirror.css",
					highlightJsUrl:"/public/ueditor/third-party/SyntaxHighlighter/shCore.js",
					highlightCssUrl:"/public/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css",
                '
            ));
            ?>
            
            <div class="form-group">
        		<?php echo CHtml::activeLabel($model,'telephone',array('class'=>'col-sm-2 control-label','for'=>'telephone')); ?>
        		<div class="col-sm-8">
        		  <?php echo CHtml::activeTelField($model,'telephone',array('class'=>'form-control','id'=>'telephone','placeholder'=>'请输入联系电话')) ?>
        		</div><span class="text-danger" style="font-size: 27px;">*</span>
        	</div>
        	
        	<div class="form-group">
        		<?php echo CHtml::activeLabel($model,'email',array('class'=>'col-sm-2 control-label','for'=>'email')); ?>
        		<div class="col-sm-8">
        		  <?php echo CHtml::activeEmailField($model,'email',array('class'=>'form-control','id'=>'email','placeholder'=>'请输入邮箱地址')) ?>
        		</div><span class="text-danger" style="font-size: 27px;">*</span>
        	</div>
        	
        	<div class="form-group">
        		<?php echo CHtml::activeLabel($model,'verifyCode',array('class'=>'col-sm-2 control-label','for'=>'verifyCode')); ?>
        		<div class="col-sm-2">
        		  <?php echo CHtml::activeTextField($model,'verifyCode',array('class'=>'form-control','id'=>'verifyCode','placeholder'=>'请输入验证码')) ?>
        		</div>
        		<div class="col-sm-2">
        		  <?php $this->widget('CCaptcha',array('clickableImage'=>true,'showRefreshButton'=>false)); ?>
        		</div>
        	</div>
        	
        	<div class="form-group">
        	   <?php echo CHtml::submitButton('提交',array('class'=>'btn btn-success btn-lg col-sm-offset-5'));?>
        	</div>
        <?php echo CHtml::endForm();?>
	</div>