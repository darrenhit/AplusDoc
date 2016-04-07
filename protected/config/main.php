<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Aplus CMS系统帮助中心',
    'timeZone'=>'Asia/Shanghai',
    'aliases'=>array(
        'bootstrap'=>dirname(__FILE__).'/../extensions/bootstrap',
        'ueditor'=>dirname(__FILE__).'/../extensions/ueditor',
    ),

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
    
    'defaultController'=>'index',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.*.*'),
		),
		*/
		'admin'=>array(
			'class'=>'application.modules.admin.AdminModule',
		    'defaultController'=>'index',
		),
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
		    'showScriptName' => false,
		    'urlSuffix' => '.html',
			'rules'=>array(
				//'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				//'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			    'http://admin.<second:\w+>.<domain:\w+>.com' => 'admin',
			    'http://admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>' => 'admin/<controller>/index',
			    'http://admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
			    'http://admin.<second:\w+>.<domain:\w+>.com/contents/<action:\w+>/<CId:\d+>' => 'admin/contents/<action>',
			    'http://admin.<second:\w+>.<domain:\w+>.com/document/<action:\w+>/<DId:\d+>' => 'admin/document/<action>',
			    'http://admin.<second:\w+>.<domain:\w+>.com/user/<action:\w+>/<UId:\d+>' => 'admin/user/<action>',
			    'http://admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>/<action:\w+>/<Id:\d+>' => 'admin/<controller>/<action>',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com' => 'admin',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>' => 'admin/<controller>/index',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/contents/<action:\w+>/<CId:\d+>' => 'admin/contents/<action>',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/document/<action:\w+>/<DId:\d+>' => 'admin/document/<action>',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/user/<action:\w+>/<UId:\d+>' => 'admin/user/<action>',
			    'http://<user:\w+>.admin.<second:\w+>.<domain:\w+>.com/<controller:\w+>/<action:\w+>/<Id:\d+>' => 'admin/<controller>/<action>',
			    'document/show/<CId:\d+>' => 'document/show',
			    'contents/<action:\w+>/<CId:\d+>' => 'contents/<action>',
			    'document/<action:\w+>/<DId:\d+>' => 'document/<action>',
			    'user/<action:\w+>/<UId:\d+>' => 'user/<action>',
			    '<controller:\w+>/<action:\w+>/<Id:\d+>' => '<controller>/<action>',
			),
		),
	    
	    'request'=>array(
	        'enableCookieValidation'=>true,
	    ),
		

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'/index/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
			    array(
			        'class'=>'CFileLogRoute',
			        'levels'=>'info',
			        'categories'=>'system.web.user.login',
			        'logFile'=>'login.log',
			        'logPath'=>'/home/logs/apps'
			    ),
			    array(
			        'class'=>'CFileLogRoute',
			        'levels'=>'info',
			        'categories'=>'system.web.user.enroll',
			        'logFile'=>'enroll.log',
			        'logPath'=>'/home/logs/apps'
			    ),
			    array(
			        'class'=>'CFileLogRoute',
			        'levels'=>'info',
			        'categories'=>'system.web.user.logout',
			        'logFile'=>'logout.log',
			        'logPath'=>'/home/logs/apps'
			    ),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				    'logPath'=>'/home/logs/apps'
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	    
	    'session'=>array(
	        'sessionName'=>'APLUSDOC',
	        'savePath'=>'/home/pplive/gfs/session',
	        'gCProbability'=>10,
	    ),
	    
	    //Curl请求
	    'curl'=>array(
	        'class'=>'ext.curl.Curl',
	    ),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	/* 'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	), */
    'params'=>require(dirname(__FILE__).'/params.php'),
);
