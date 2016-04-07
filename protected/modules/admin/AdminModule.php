<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			// 进行后台登录验证
		    if(empty(Yii::app()->session['username'])){
		        $ignoreList = array('login','nativeLogin','supply');
		        if($controller->id != 'user' || ($controller->id == 'user' && !in_array($action->id,$ignoreList))){
		            $forward = urlencode('/' . $controller->id . '/' . $action->id . '?' . Yii::app()->request->queryString);
		            Yii::app()->controller->redirect(Yii::app()->createUrl('/user/login?forward='.$forward));
		        }
		    }
			return true;
		}
		else
			return false;
	}
}
