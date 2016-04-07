<?php
/**
 * 文档操作类
 * @author yulongwang
 *
 */
class DocumentController extends Controller
{
    private $documentModel = NULL;
    public $layout = "//layouts/front-main";
    public $catalog = '';
    private $contentsModel;
    
    public function beforeAction($action){
        $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalog';
        if (file_exists($catalogPath) && filemtime($catalogPath) > time() - 3600*24) {
            $catalog = file_get_contents($catalogPath);
        } else {
            $this->contentsModel = ContentsModel::model();
            $this->contentsModel->geneCatalog();
            $catalog = file_get_contents($catalogPath);
        }
        $this->catalog = $catalog;
        return TRUE;
    }
    
    //  根据DID展示单个文档
    public function actionIndex(){
        $this->documentModel = DocumentModel::model();
        $documentId = Yii::app()->request->getParam('DId');
        $highLightStr = Yii::app()->request->getParam('string');
        if(!is_numeric($documentId)){
            Yii::app()->user->setFlash('err','参数错误');
        }
        $documentInfo = $this->documentModel->getDocumentById($documentId);
        $this->render('index',array('model'=>$documentInfo,'highLightStr'=>$highLightStr));
    }
    
    // 根据CID展示单个文档
    public function actionShow(){
        $this->documentModel = DocumentModel::model();
        $contentId = Yii::app()->request->getParam('CId');
        if(!is_numeric($contentId)){
            Yii::app()->user->setFlash('err','参数错误');
        }
        $documentInfo = $this->documentModel->getDocumentByCId($contentId);
        $this -> render('index',array('model'=>$documentInfo,'highLightStr'=>NULL,'contentId'=>$contentId));
    }
    
    /**
     * 搜索文档
     */
    public function actionSearch(){
        $this->documentModel = DocumentModel::model();
        $condition = Yii::app()->request->getParam('condition');
        $type = Yii::app()->request->getParam('type');
        $result = array();
        if($type == 'guid'){
            $result = $this->documentModel->getDocumentByGuid($condition);
        }elseif ('title' == $type){
            $result = $this->documentModel->getDocumentByTitle($condition);
        }else{
            $result = $this->documentModel->getDocumentByCondition($condition);
        }
        $this -> render('list',array('list'=>$result['list'],'page'=>$result['page'],'title'=>'文档搜索结果','type'=>$type,'condition'=>$condition));
    }
    
    /**
     * 获取GUID文档
     */
    public function actionGuidList(){
        $this->documentModel = DocumentModel::model();
        $result = $this->documentModel->getGuidDocuments();
        $this -> render('list',array('list'=>$result['list'],'page'=>$result['page'],'title'=>"GUID文档列表"));
    }
    
    /**
     * 获取单个GUID文档
     */
    public function actionGuid(){
        $this->documentModel = DocumentModel::model();
        $guid = Yii::app()->request->getParam('guid');
        if(!preg_match('/^[\d\w]{32}$/i',$guid)){
            Yii::app()->user->setFlash('err','参数错误');
        }
        $documentInfo = $this->documentModel->find('guid = :guid AND state = :state',array(':guid'=>$guid,':state'=>'ONLINE'));
        if(!$documentInfo){
            $hostname = Yii::app()->request->hostInfo;
            preg_match('/(\w*doc)\./i',$hostname,$match);
            $pos = strpos($hostname,$match[1]);
            $hostname = substr($hostname,0,$pos).'admin.'.substr($hostname,$pos);
            header('Location:'.$hostname.'/document/create?GUID='.$guid);
        }
        $this -> render('index',array('model'=>$documentInfo,'highLightStr'=>NULL));
    }
}

?>
