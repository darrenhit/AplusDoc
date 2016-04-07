<?php
$this->pageTitle = $title . ' ' . Yii::app()->params['title'];
if (!count($list)) {
    echo '<script type="text/javascript">$(function(){hasNoDocument();});</script>';
}
?>
<script type="text/javascript">
function hasNoDocument(){
	$('#message').html('不存在相关文档');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    window.location.href = '/index/main.html';
	}});
}
</script>
<div>
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'message',
        'options' => array(
            'title' => 'Tips',
            'autoOpen' => false,
            'resizable'=>false,
        )
    ));
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <?php if(count($list)):?>
    <div class="list-group" id="list-div">
        <?php foreach($list as $key=>$item){
        if($title == '文档搜索结果' && $type != 'guid' && $type != 'title'){
        ?>
        <a href="<?php echo Yii::app()->createUrl('/document/index',array('DId'=>$item->id,'string'=>$condition));?>" class="list-group-item <?php if($key == 0) echo 'active';?>">
        <?php }else{?>
        <a href="<?php echo Yii::app()->createUrl('/document/index',array('DId'=>$item->id));?>" class="list-group-item <?php if($key == 0) echo 'active';?>">
        <?php }?>
			<h4 class="list-group-item-heading"><?php echo $item->title?></h4>
			<p class="list-group-item-text"><?php echo $item->summary;?></p>
		</a>
      <?php }?>
    </div>
    <?php $this->widget('LinkPager',array('pages'=>$page))?>
    <?php endif;?>
</div>
