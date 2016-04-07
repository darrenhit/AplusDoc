<?php
$this->pageTitle = $title . ' ' . Yii::app()->params['title'];
?>
<div class="container" style="margin-top: 50px;">
    <?php 
        $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
            'id'=>'message',
            'options'=>array(
                'title'=>'Tips',
                'autoOpen'=>true,
                'resizable'=>false,
            ),
        ));
        echo $message;
        $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
</div>