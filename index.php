<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
// 引入PHP_CAS的类自动加载器
require_once(__DIR__ . '/protected/vendor/cas/CAS/Autoload.php');
require_once($yii);
Yii::createWebApplication($config)->run();
