<?php
$this->pageTitle= $title . ' ' . Yii::app()->params['adminTitle'];
if(Yii::app()->user->hasFlash('auth')){
    echo '<script type="text/javascript">$(function(){noAuth();});</script>';
}
?>
<script type="text/javascript">
function noAuth(){
	$('#message').html('<?php echo Yii::app()->user->getFlash('auth');?>');
	$('#message').dialog('open');
	$('#message').dialog({close:function(){
	    parent.location.href = '/';
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
<table class="table table-striped table-bordered table-hover table-condensed">
    <?php if($title == '用户列表'):?>
    <tr>
        <p class="text-right"><a href="/user/recycle.html">用户回收站</a></p>
    </tr>
    <?php endif;?>
    <tr>
        <th width="5%" style="text-align:center;">ID</td>
        <th width="15%" style="text-align:center;">用户名</td>
        <th width="10%" style="text-align:center;">角色</td>
        <th width="17%" style="text-align:center;">姓名</td>
        <th width="28%" style="text-align:center;">部门</td>
        <th width="15%" style="text-align:center;">操作</td>
    </tr>
    <?php foreach($list as $item):
        $role = empty($item->is_admin) ? '普通用户' : '管理员';
    ?>
    <tr>
        <td style="text-align: center;"><?php echo $item->id;?></td>
        <td style="text-align: center;"><?php echo $item->username;?></td>
        <td style="text-align: center;"><?php echo $role;?></td>
        <td style="text-align: center;"><?php echo $item->realname;?></td>
        <td style="text-align: center;"><?php echo $item->department_name;?></td>
        <?php if($title == '用户列表'):?>
        <td style="text-align: center;"><a class="ico4" href="#" onclick="editUser(<?php echo $item->id;?>)"></a>&nbsp;&nbsp;&nbsp;<a class="ico6" href="#" onclick="deleteUser(<?php echo $item->id;?>);"></a></td>
        <?php elseif ($title == '用户回收站列表'):?>
        <td style="text-align: center;"><a class="ico12" href="#" onclick="resaveUser(<?php echo $item->id;?>)"></a>&nbsp;&nbsp;&nbsp;<a class="ico6" href="#" onclick="recycleDelUser(<?php echo $item->id;?>);"></a></td>
        <?php endif;?>
    </tr>
    <?php endforeach;?>
</table>