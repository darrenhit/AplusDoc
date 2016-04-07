<?php

class ViewController extends CController{

    private $documentModel;

    /* public function actionIndex(){
    	$guid = Yii::app()->request->getParam('guid');
    	header("Content-type:text/html;charset=utf-8");
    	if(!preg_match('/^[\d\w]{32}$/i',$guid)){
    	    echo '参数错误';
    	    exit;
    	}
    	$this->documentModel = DocumentModel::model();
    	$documentInfo = $this->documentModel->find('guid = :guid AND state = :state',array(':guid'=>$guid,':state'=>'ONLINE'));
    	if(!$documentInfo){
    	    $hostname = Yii::app()->request->hostInfo;
    	    preg_match('/(\w*doc)\./i',$hostname,$match);
    	    $pos = strpos($hostname,$match[1]);
    	    $hostname = substr($hostname,0,$pos).'admin.'.substr($hostname,$pos);
    	    header('Location:'.$hostname.'/document/create?GUID='.$guid);
    	}else{
    	    echo $documentInfo->summary;
    	}
    	$this -> renderPartial('index');
    } */
    
    public function actionIndex(){
        $guid = Yii::app()->request->getParam('guid');
        $result = array();
        if(!preg_match('/^[\d\w]{32}$/i',$guid)){
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->find('guid = :guid AND state = :state',array(':guid'=>$guid,':state'=>'ONLINE'));
        if($documentInfo){
            $result['err'] = 0;
            $result['result'] = $documentInfo->summary;
        }else{
            $result['err'] = 1;
            $result['reuslt'] = '';
        }
        echo json_encode($result);
    }
}
