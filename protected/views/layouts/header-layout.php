<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="zh-CN">
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/public/css/style.css" />
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl;?>/public/js/main.js"></script>
	<link href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" mce_href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" rel="bookmark" type="image/x-icon" /> 
	<link href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" mce_href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" rel="icon" type="image/x-icon" /> 
	<link href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" mce_href="<?php echo Yii::app()->request->baseUrl;?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<title><?php echo CHtml::encode($this->pageTitle);?></title>
	<script type="text/javascript">
    $(function(){
        if(location.pathname.search(/(index\/header)|(index\/footer)|(contents\/index)$/i) == -1){
            modifyTitle();
        }
    })
	</script>
</head>
<?php echo $content; ?>
</html>
