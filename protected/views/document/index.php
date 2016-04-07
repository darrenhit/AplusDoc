<?php
$this -> pageTitle = Yii::app() -> params['title'];
if(Yii::app()->user->hasFlash('err')){
    echo '<script type="text/javascript">$(function(){hasErr();});</script>';
}//elseif (empty($model)){
//    echo '<script type="text/javascript">$(function(){hasNoDocument();});</script>';
//}
?>
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
    <?php 
        if($model):
        $p = $this->beginWidget('CHtmlPurifier');
        $p->options = array('Attr.AllowedFrameTargets'=>array('_blank','_self','_parent','_top'),'AutoFormat.RemoveEmpty'=>true);
    ?>
    <?php if($model->guid){?>
        <p class="text-center">关联Aplus相关数据的GUID：<strong><?php echo $model->guid;?></strong></p>
    <?php }else{?>
        <div class="page-header text-center">
          <h1 style="font-weight: bolder"><?php echo $model->title?></h1>
        </div>
    <?php }?>
        <p class="well"><?php
            if($highLightStr){
                echo str_replace($highLightStr,'<font style="background-color:#fcf8e3;padding:0.2em;">'.$highLightStr.'</font>',strip_tags($model->summary));
            }else{
                echo strip_tags($model->summary);
            }
        ?></p>
        <p><?php
            if($highLightStr){
                echo str_replace($highLightStr,'<font style="background-color:#fcf8e3;padding:0.2em;">'.$highLightStr.'</font>',$model->content);
            }else{
                echo $model->content;
            }
        ?></p>
        <?php if($model->reference){?>
        <hr/><h3>相关参考：</h3>
        <p><?php
            if($highLightStr){
                echo str_replace($highLightStr,'<font style="background-color:#fcf8e3;padding:0.2em;">'.$highLightStr.'</font>',$model->reference);
            }else{
                echo $model->reference;
            }
        ?></p>
        <?php }?>
    <?php
        $this->endWidget();
        endif;
    ?>
</div>
<script type="text/javascript">
function hasNoDocument(){
	$('#message').html('当前目录下无相关文档');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/index/main.html';
	}});
}
function hasErr(){
	$('#message').html('<?php echo Yii::app()->user->getFlash('err');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/index/main.html';
	}});
}
$(function(){
	if($('#contentItem<?php if($contentId) echo $contentId;?>')){
		$('#contentItem<?php if($contentId) echo $contentId;?>').addClass('treeSelected');
	}
});
</script>
