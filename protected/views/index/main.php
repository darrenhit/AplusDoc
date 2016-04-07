<?php
$this->pageTitle = Yii::app()->params['title'];
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
    <?php if($model){
        $p = $this->beginWidget('CHtmlPurifier');
        $p->options = array('Attr.AllowedFrameTargets'=>array('_blank','_self','_parent','_top'),'AutoFormat.RemoveEmpty'=>true);
        echo $model->content;
        $this->endWidget();
    }?>
</div>
