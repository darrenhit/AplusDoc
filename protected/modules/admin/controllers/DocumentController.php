<?php

/**
 * 文档操作类
 * @author yulongwang
 *
 */
class DocumentController extends Controller
{

    private $contentsModel = NULL;

    private $documentModel = NULL;

    private $userModel = NULL;
    
    public $isAdmin;
    
    public $username;
    
    public $catalog;
    
    protected function beforeAction($action)
    {
        $methods = array('create','edit','search','list','recycle','home','guidDocument');
        if (in_array($action->id,$methods)) {
            $this->userModel = UserModel::model();
            $this->isAdmin = $this->userModel->isAdmin(Yii::app()->session['username']);
            $this->username = Yii::app()->session['username'];
            $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalog';
            if (file_exists($catalogPath) && (filemtime($catalogPath) < time() - 3600 * 24)) {
                $this->catalog = file_get_contents($catalogPath);
            } else {
                $this->contentsModel = ContentsModel::model();
                $this->contentsModel->geneCatalog();
                $this->catalog = file_get_contents($catalogPath);
            }
        }
        return TRUE;
    }

    /**
     * 创建文档
     */
    public function actionCreate()
    {
        $model = new DocumentForm();
        $this->documentModel = new DocumentModel();
        if (isset($_POST['DocumentForm'])) {
            $model->attributes = $_POST['DocumentForm'];
            if (intval($model->type) == 0 && $model->cid == '') {
                $model->addError('cid', '请选择文档所在的目录');
            } elseif (intval($model->type) == 0 && $model->title == '') {
                $model->addError('title', '请填写文档标题');
            } elseif (intval($model->type) && $model->guid == '') {
                $model->addError('guid', '请填写文档所关联Aplus中相关数据的GUID');
            } elseif (!empty($model->guid) && $this->documentModel->find('guid = :guid AND state = :state',array(':guid'=>$model->guid,':state'=>'ONLINE'))){
                $model->addError('guid', '系统中已存在GUID为'.$model->guid.'的文档，请勿重复创建');
            } elseif ($model->validate()) {
                $this->documentModel->title = $model->title;
                $this->documentModel->cid = empty($model->cid) ? 0 : $model->cid;
                $this->documentModel->summary = $model->summary;
                $this->documentModel->content = preg_replace('/<p.*>请输入文档内容<\/p>/','',$model->content);
                $this->documentModel->reference = preg_replace('/<p.*>请输入相关参考<\/p>/','',$model->reference);
                $this->documentModel->state = 'ONLINE';
                $this->documentModel->created_by = empty(Yii::app()->session['username']) ? 'admin' : Yii::app()->session['username'];
                $this->documentModel->created_on = date('Y-m-d H:i:s', time());
                $this->documentModel->modified_by = empty(Yii::app()->session['username']) ? 'admin' : Yii::app()->session['username'];
                $this->documentModel->modified_on = date('Y-m-d H:i:s', time());
                $this->documentModel->guid = $model->guid;
                if ($this->documentModel->save()) {
        		    if($model->source){
        			$hostname = Yii::app()->request->hostInfo;
        			$hostname = str_replace('admin.','',$hostname);
        			header('Location:'.$hostname.'/document/guid?guid='.$model->guid);
        		    }else{
                        	$this->redirect($this->createUrl('/index/main'));
        		    }
                }
            }
        }
        $guid = Yii::app()->request->getParam('GUID');
        if ($guid) {
            if(strpos($guid,'.html')){
                $guid = str_replace('.html','',$guid);
            }
            $model->type = 1;
            $model->guid = $guid;
        }
        $cid = Yii::app()->request->getParam('CId');
        if ($cid){
            $model->type = 0;
            $model->cid = $cid;
        }
        $this->contentsModel = ContentsModel::model();
        $catalogListPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalogListForDocument.json';
        if (! file_exists($catalogListPath)) {
            $this->contentsModel->geneCatelogList();
        }
        $catalogListJson = file_get_contents($catalogListPath);
        if (empty($catalogListJson)) {
            $this->contentsModel->geneCatelogList();
            $catalogListJson = file_get_contents($catalogListPath);
        }
        $contentsList = json_decode($catalogListJson, TRUE);
        $this->render('create-edit', array(
            'model' => $model,
            'clist' => $contentsList,
            'title' => '创建文档内容'
        ));
    }

    /**
     * 编辑文档
     */
    public function actionEdit()
    {
        $model = new DocumentForm();
        $this->documentModel = DocumentModel::model();
        $id = Yii::app()->request->getParam('DId');
        if (! is_numeric($id)) {
            $model->addError('argument-err', '参数错误');
        } else {
            $documentInfo = $this->documentModel->findByPk($id);
            $this->userModel = UserModel::model();
            if (! ($this->userModel->isAdmin(Yii::app()->session['username']) || ($documentInfo && $documentInfo->created_by == Yii::app()->session['username']))) {
                $model->addError('auth', '您没有编辑该文档的权限，请联系该文档的创建者');
                Yii::app()->user->setFlash('err', '非本人创建，无权修改');
            }
        }
        if (isset($_POST['DocumentForm'])) {
            $model->attributes = $_POST['DocumentForm'];
            if (intval($model->type) == 0 && $model->cid == '') {
                $model->addError('cid', '请选择文档所在的目录');
            } elseif (intval($model->type) == 0 && $model->title == '') {
                $model->addError('title', '请填写文档标题');
            } elseif (intval($model->type) && $model->guid == '') {
                $model->addError('guid', '请填写文档所关联Aplus中相关数据的GUID');
            } elseif (!empty($model->guid) && $this->documentModel->find('id <> :id AND guid = :guid AND state = :state',array(':id'=>$model->id,':guid'=>$model->guid,':state'=>'ONLINE'))){
                $model->addError('guid', '系统已存在GUID为' . $model->guid . '的文档，其他文档不能再使用该GUID');
            } elseif ($model->validate()) {
                $documentInfo->id = $model->id;
                $documentInfo->title = $model->title;
                $documentInfo->cid = empty($model->cid) ? 0 : $model->cid;
                $documentInfo->summary = $model->summary;
                $documentInfo->content = preg_replace('/<p.*>请输入文档内容<\/p>/','',$model->content);
                $documentInfo->reference = preg_replace('/<p.*>请输入相关参考<\/p>/','',$model->reference);
                $documentInfo->state = 'ONLINE';
                $documentInfo->modified_by = empty(Yii::app()->session['username']) ? 'admin' : Yii::app()->session['username'];
                $documentInfo->modified_on = date('Y-m-d H:i:s', time());
                $documentInfo->guid = $model->guid;
                if ($documentInfo->save()) {
                    $this->redirect($this->createUrl('/index/main'));
                }
            }
        }
        $this->contentsModel = ContentsModel::model();
        $catalogListPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalogListForDocument.json';
        if (! file_exists($catalogListPath)) {
            $this->contentsModel->geneCatelogList();
        }
        $catalogListJson = file_get_contents($catalogListPath);
        if (empty($catalogListJson)) {
            $this->contentsModel->geneCatelogList();
            $catalogListJson = file_get_contents($catalogListPath);
        }
        $contentsList = json_decode($catalogListJson, TRUE);
        if ($documentInfo->guid) {
            $model->type = 1;
        }
        $model->id = $documentInfo->id;
        $model->title = $documentInfo->title;
        $model->cid = $documentInfo->cid;
        $model->guid = $documentInfo->guid;
        $model->summary = $documentInfo->summary;
        $model->content = $documentInfo->content;
        $model->reference = $documentInfo->reference;
        $this->render('create-edit', array(
            'model' => $model,
            'clist' => $contentsList,
            'title' => '编辑文档内容'
        ));
    }

    /**
     * 删除文档
     */
    public function actionDelete()
    {
        $did = Yii::app()->request->getParam('DId');
        $result = array();
        if (! is_numeric($did)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->findByPk($did);
        $this->userModel = UserModel::model();
        if (! (($documentInfo && $documentInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权删除';
            exit(json_encode($result));
        }
        $documentInfo->state = 'DELETED';
        $documentInfo->modified_by = Yii::app()->session['username'];
        $documentInfo->modified_on = date('Y-m-d H:i:s', time());
        if ($documentInfo->save()) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }
    
    /**
     * 根据CId删除文档
     */
    public function actionDeleteByCId(){
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($cid)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->find('cid = :cid AND state = :state',array(':cid'=>$cid,':state'=>'ONLINE'));
        if(!$documentInfo){
            $result['err'] = 1;
            $result['result'] = '当前目录下未找到相关文档';
            exit(json_encode($result));
        }
        $this->userModel = UserModel::model();
        if (! (($documentInfo && $documentInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权删除';
            exit(json_encode($result));
        }
        $documentInfo->state = 'DELETED';
        $documentInfo->modified_by = Yii::app()->session['username'];
        $documentInfo->modified_on = date('Y-m-d H:i:s', time());
        if ($documentInfo->save()) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }

    /**
     * 还原文档
     */
    public function actionResave()
    {
        $did = Yii::app()->request->getParam('DId');
        $result = array();
        if (! is_numeric($did)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->findByPk($did);
        $this->userModel = UserModel::model();
        if (! (($documentInfo && $documentInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权还原';
            exit(json_encode($result));
        }
        // 判断所在的目录状态
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($documentInfo->cid);
        if(!$contentsInfo || ($contentsInfo && 'ONLINE' != $contentsInfo->state)){
            $result['err'] = 1;
            $result['result'] = '所在目录已被删除，请先还原目录';
            exit(json_encode($result));
        }
        $documentInfo->state = 'ONLINE';
        $documentInfo->modified_by = Yii::app()->session['username'];
        $documentInfo->modified_on = date('Y-m-d H:i:s', time());
        if ($documentInfo->save()) {
            $result['err'] = 0;
            $result['result'] = '还原成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '还原失败';
        }
        echo json_encode($result);
    }

    /**
     * 删除回收站中文档
     */
    public function actionRecycleDel()
    {
        $did = Yii::app()->request->getParam('DId');
        $result = array();
        if (! is_numeric($did)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->findByPk($did);
        $this->userModel = UserModel::model();
        if (! (($documentInfo && $documentInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建文档，无权删除';
            exit(json_encode($result));
        }
        if ($this->documentModel->deleteByPk($did)) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }

    /**
     * 检查文档的创建者
     */
    public function actionCheckDocumentOwner()
    {
        $result = array();
        $documentId = Yii::app()->request->getParam('DId');
        if (! is_numeric($documentId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->findByPk($documentId);
        $this->userModel = UserModel::model();
        if (($documentInfo && $documentInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 0;
        } else {
            $result['err'] = 1;
            $result['result'] = $documentInfo->created_by;
        }
        echo json_encode($result);
    }

    /**
     * 检查当前目录下是否有文档
     */
    public function actionHasDocument()
    {
        $contentsId = Yii::app()->request->getParam('CId');
        $documentId = Yii::app()->request->getParam('DId');
        $result = array(
            'err' => 0
        );
        if (! is_numeric($contentsId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->hasDocument($contentsId, $documentId);
        if ($documentInfo) {
            $result['err'] = 1;
            $result['result'] = '当前目录下已有编号为：'.$documentInfo->id.'的文档，请勿重复创建';
        } else {
            $result['result'] = NULL;
        }
        echo json_encode($result);
    }

    /**
     * 后台搜索文档
     */
    public function actionSearch()
    {
        $this->documentModel = DocumentModel::model();
        $condition = Yii::app()->request->getParam('condition');
        $type = Yii::app()->request->getParam('type');
        $result = array();
        $this->userModel = UserModel::model();
        if($this->userModel->isAdmin(Yii::app()->session['username'])){
            if ('guid' == $type){
                $result = $this->documentModel->getAllDocumentByGuid($condition);
            }elseif ('title' == $type){
                $result = $this->documentModel->getAllDocumentByTitle($condition);
            }else{
                $result = $this->documentModel->getAllDocumentByCondition($condition);
            }
        }else{
            if ('guid' == $type) {
                $result = $this->documentModel->getOwnDocumentByGuid($condition, Yii::app()->session['username']);
            }elseif ('title' == $type){
                $result = $this->documentModel->getOwnDocumentByTitle($condition, Yii::app()->session['username']);
            } else {
                $result = $this->documentModel->getOwnDocumentByCondition($condition, Yii::app()->session['username']);
            }
        }
        $this->render('search', array(
            'list' => $result['list'],
            'pages' => $result['page'],
            'title' => '文档搜索结果'
        ));
    }

    /**
     * 我的文档列表
     */
    public function actionList()
    {
        $this->documentModel = DocumentModel::model();
        $this->userModel = UserModel::model();
        if($this->userModel->isAdmin(Yii::app()->session['username'])){
            $documentResult = $this->documentModel->getAllDocument();
        }else{
            $documentResult = $this->documentModel->getOwnDocument(Yii::app()->session['username']);
        }
        $this->render('/index/main', array(
            'list' => $documentResult['list'],
            'page' => $documentResult['page'],
            'title' => '我的文档'
        ));
    }

    /**
     * 我的文档回收站列表
     */
    public function actionRecycle()
    {
        $this->documentModel = DocumentModel::model();
        $this->userModel = UserModel::model();
        if($this->userModel->isAdmin(Yii::app()->session['username'])){
            $documentResult = $this->documentModel->getAllRecycleDocument();
        }else{
            $documentResult = $this->documentModel->getOwnRecycleDocument(Yii::app()->session['username']);
        }
        $this->render('/index/recycle', array(
            'list' => $documentResult['list'],
            'page' => $documentResult['page'],
            'title' => '我的文档内容回收站'
        ));
    }
    
    /**
     * 首页编辑（首页文档默认其目录为0）
     */
    public function actionHome(){
        $model = new DocumentForm();
        $this->userModel = UserModel::model();
        $this->documentModel = new DocumentModel();
        $documentInfo = $this->documentModel->find('cid = 0 AND guid = ""');
        if(!$documentInfo){
            $documentInfo = $this->documentModel;
            $flag = false;
        }else{
            $flag = true;
        }
        if(!$this->userModel->isAdmin(Yii::app()->session['username'])){
            $model->addError('auth', '您不是管理员，没有相关操作权限');
            Yii::app()->user->setFlash('auth','您没有操作权限');
        }else{
            if(isset($_POST['DocumentForm'])){
                $model->attributes = $_POST['DocumentForm'];
                if($model->validate()){
                    if($flag){
                        $documentInfo->id = $model->id;
                    }else {
                        $documentInfo->created_by = Yii::app()->session['username'];
                        $documentInfo->created_on = date('Y-m-d H:i:s',time());
                    }
                    $documentInfo->title = $model->title;
                    $documentInfo->cid = 0;
                    $documentInfo->summary = $model->summary;
                    $documentInfo->content = $model->content;
                    $documentInfo->reference = $model->reference;
                    $documentInfo->state = 'ONLINE';
                    $documentInfo->modified_by = Yii::app()->session['username'];
                    $documentInfo->modified_on = date('Y-m-d H:i:s',time());
                    $documentInfo->guid = '';
                    if($documentInfo->save()){
                        $this->redirect($this->createUrl('/index/main'));
                    }
                    
                }
            }
        }
        if($flag) $model->id = $documentInfo->id;
        $model->type = 0;
        $model->title = $documentInfo->title;
        $model->cid = 0;
        $model->summary = $documentInfo->summary;
        $model->content = $documentInfo->content;
        $model->reference = $documentInfo->reference;
        $this->render('home',array('model'=>$model,'title'=>'首页编辑'));
    }
    
    /**
     * 根据目录CId获取对应的文档
     */
    public function actionGetDocumentByCId(){
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if(!is_numeric($cid)){
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->documentModel = DocumentModel::model();
        $documentInfo = $this->documentModel->hasDocument($cid);
        if($documentInfo){
            $result['err'] = 0;
            $result['result'] = $documentInfo->id;
        }else{
            $documentInfo = $this->documentModel->find('cid = :cid',array(':cid'=>$cid));
            if($documentInfo){
                $result['err'] = 2;
                $result['info'] = '该目录下文档已被删除，是否还原';
                $result['result'] = $documentInfo->id;
            }else{
                $result['err'] = 1;
                $result['result'] = '该目录下尚未创建文档，是否需要创建';
            }
        }
        echo json_encode($result);
    }
    
    public function actionGuidDocument(){
        $this->documentModel = DocumentModel::model();
        $this->userModel = UserModel::model();
        if($this->userModel->isAdmin(Yii::app()->session['username'])){
            $documentResult = $this->documentModel->getAllGuidDocument();
        }else{
            $documentResult = $this->documentModel->getOwnGuidDocument(Yii::app()->session['username']);
        }
        $this->render('/index/main', array(
            'list' => $documentResult['list'],
            'page' => $documentResult['page'],
            'title' => 'GUID文档'
        ));
    }

}

?>
