<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('auth')){
    echo '<script type="text/javascript">$(function(){noAuth()});</script>';
}
?>
<script type="text/javascript">
function noAuth(){
	$(":submit,:reset").attr('disabled',true);
	$('#message').html('<?php echo Yii::app()->user->getFlash('auth');?>');
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
		<?php echo CHtml::activeHiddenField($model, 'type',array('value'=>0));?>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'title',array('class'=>'col-sm-2 control-label','for'=>'title')); ?>
		<div class="col-sm-9">
		  <?php echo CHtml::activeTextField($model,'title',array('class'=>'form-control','id'=>'title','placeholder'=>'请输入文档标题'))?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'summary',array('class'=>'col-sm-2 control-label','for'=>'summary')); ?>
		<div class="col-sm-9">
		  <?php echo CHtml::activeTextArea($model,'summary',array('class'=>'form-control','id'=>'summary','placeholder'=>'请输入文档概述信息')); ?>
		</div><span class="text-danger" style="font-size: 27px;">*</span>
	</div>

	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'content',array('class'=>'col-sm-2 control-label','for'=>'context')); ?>
		<div class="col-sm-9">
		  <?php echo CHtml::activeTextArea($model,'content',array('id'=>'context')); ?>
		</div><span class="text-danger" style="font-size: 27px;">*</span>
	</div>
    	
    	<?php
            	if(empty($model->id)){
            	    $clearFlag = 'autoClearinitialContent:true,';
            	}else{
            	    $clearFlag = '';
            	}
$this->widget('ueditor.Ueditor', array(
                'getId' => 'context',
                'name' => 'content',
                'options' => "
                    Url : '/public/ueditor/',
                	UEDITOR_HOME_URL : '/public/ueditor/',
                	toolbars:[['fontfamily','fontsize','forecolor','backcolor','bold','italic','strikethrough','superscript','subscript','removeformat','formatmatch','|',
'justifyleft','justifycenter','justifyright','justifyjustify','blockquote','paragraph','lineheight','indent','|',
'insertunorderedlist','insertorderedlist','link','unlink','highlightcode','date','time','horizontal','spechars','insertimage','attachment','inserttable','deletetable','mergecells','splittocells','|','pasteplain','selectall','cleardoc','preview','searchreplace','|','fullscreen','undo','redo','source']],
                	wordCount:true,maximumWords:200000,
                	autoHeightEnabled:false,
                	elementPathEnabled:false,
                    zIndex:99,
                	imagePath:'/',
                    imageUrl:'/public/ueditor/php/imageUp.php',
                    filePath:'/',
                    fileUrl:'/public/ueditor/php/fileUp.php',
                	initialContent:'请输入文档内容',
                    $clearFlag
                	iframeCssUrl: '/public/ueditor/themes/default/iframe.css',
					codeMirrorJsUrl:'/public/ueditor/third-party/codemirror2.15/codemirror.js',
					codeMirrorCssUrl:'/public/ueditor/third-party/codemirror2.15/codemirror.css',
					highlightJsUrl:'/public/ueditor/third-party/SyntaxHighlighter/shCore.js',
					highlightCssUrl:'/public/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css',
                "
            ));
            ?>
    	
	<div class="form-group">
		<?php echo CHtml::activeLabel($model,'reference',array('class'=>'col-sm-2 control-label','for'=>'reference')); ?>
		<div class="col-sm-9">
		  <?php echo CHtml::activeTextArea($model,'reference',array('id'=>'reference')); ?>
		</div>
	</div>
    	
    	<?php
$this->widget('ueditor.Ueditor', array(
                'getId' => 'reference',
                'name' => 'reference',
                'options' => "
                    Url : '/public/ueditor/',
                	UEDITOR_HOME_URL : '/public/ueditor/',
                	toolbars:[['fontfamily','fontsize','forecolor','backcolor','bold','italic','strikethrough','superscript','subscript','removeformat','formatmatch','|',
'justifyleft','justifycenter','justifyright','justifyjustify','blockquote','paragraph','lineheight','indent','|',
'insertunorderedlist','insertorderedlist','link','unlink','highlightcode','date','time','horizontal','spechars','inserttable','deletetable','mergecells','splittocells','|','pasteplain','selectall','cleardoc','preview','searchreplace','|','fullscreen','undo','redo','source']],
                	wordCount:true,maximumWords:20000,
                	autoHeightEnabled:false,
                	elementPathEnabled:false,
                    zIndex:99,
                	imagePath:'/',
                	initialContent:'请输入相关参考',
                    $clearFlag
                    minFrameHeight:100,
                	iframeCssUrl: '/public/ueditor/themes/default/iframe.css',
					codeMirrorJsUrl:'/public/ueditor/third-party/codemirror2.15/codemirror.js',
					codeMirrorCssUrl:'/public/ueditor/third-party/codemirror2.15/codemirror.css',
					highlightJsUrl:'/public/ueditor/third-party/SyntaxHighlighter/shCore.js',
					highlightCssUrl:'/public/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css',
                "
            ));
            ?>
    	
	<div class="form-group">
	   <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-success btn-lg col-sm-offset-3'));?>
	   <?php echo CHtml::resetButton('重置',array('class'=>'btn btn-warning btn-lg col-sm-offset-1'));?>
	   <?php echo CHtml::button('返回',array('class'=>'btn btn-default btn-lg col-sm-offset-1','onClick'=>'javascript:window.history.back();'));?>
	</div>
    
    <?php echo CHtml::endForm();?>
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