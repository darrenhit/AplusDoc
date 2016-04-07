<?php

/**
 * 目录操作类
 * @author yulongwang
 *
 */
class ContentsController extends Controller
{

    private $contentsModel;

    private $userModel;

    private $documentModel;

    public $isAdmin;

    public $username;
    
    public $catalog;

    protected function beforeAction($action)
    {
        if ('create' == $action->id || 'edit' == $action->id || 'list' == $action->id || 'recycle' == $action->id) {
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
     * 左侧目录列表
     */
    public function actionIndex()
    {
        $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalog';
        if (file_exists($catalogPath) && (filemtime($catalogPath) < time() - 3600 * 24)) {
            $catalog = file_get_contents($catalogPath);
        } else {
            $this->contentsModel = ContentsModel::model();
            $this->contentsModel->geneCatalog();
            $catalog = file_get_contents($catalogPath);
        }
        $this->render('index', array(
            'catalog' => $catalog
        ));
    }

    /**
     * 创建目录
     */
    public function actionCreate()
    {
        $model = new ContentsForm();
        $this->contentsModel = new ContentsModel();
        if (isset($_POST['ContentsForm'])) {
            $model->attributes = $_POST['ContentsForm'];
            if ($model->validate()) {
                $this->contentsModel->title = $model->title;
                $this->contentsModel->pid = $model->pid;
                $this->contentsModel->explanation = $model->explanation;
                $this->contentsModel->state = 'ONLINE';
                $this->contentsModel->created_by = Yii::app()->session['username'];
                $this->contentsModel->created_on = date('Y-m-d H:i:s', time());
                $this->contentsModel->modified_by = Yii::app()->session['username'];
                $this->contentsModel->modified_on = date('Y-m-d H:i:s', time());
                if ($this->contentsModel->save()) {
                    $this->contentsModel->geneCatalog();
                    $this->contentsModel->geneCatelogList();
                    $this->redirect('/document/create?CId='.$this->contentsModel->attributes['id']);
                }
            }
        }
        $catalogListPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalogListForContents.json';
        if (! file_exists($catalogListPath)) {
            $this->contentsModel->geneCatelogList();
        }
        $catalogListJson = file_get_contents($catalogListPath);
        if (empty($catalogListJson)) {
            $this->contentsModel->geneCatelogList();
            $catalogListJson = file_get_contents($catalogListPath);
        }
        $pid = Yii::app()->request->getParam('CId');
        if ($pid) {
            $model->pid = $pid;
        }
        $catalogList = json_decode($catalogListJson, TRUE);
        $this->render('create-edit', array(
            'model' => $model,
            'parent' => $catalogList,
            'title' => '创建文档目录'
        ));
    }

    /**
     * 编辑目录
     */
    public function actionEdit()
    {
        $model = new ContentsForm();
        $this->contentsModel = ContentsModel::model();
        $cid = Yii::app()->request->getParam('CId');
        if ($cid) {
            $contentsInfo = $this->contentsModel->findByPk($cid);
            $this->userModel = UserModel::model();
            if (! ($this->userModel->isAdmin(Yii::app()->session['username']) || $contentsInfo->created_by == Yii::app()->session['username'])) {
                $model->addError('auth', '您没有编辑该目录的权限，请联系该目录的创建者');
                Yii::app()->user->setFlash('err', '非本人创建，无权修改');
            }
        } else {
            $model->addError('argument-err', '参数错误');
        }
        if (isset($_POST['ContentsForm'])) {
            $model->attributes = $_POST['ContentsForm'];
            if ($model->validate()) {
                $contentsInfo->id = $model->id;
                $contentsInfo->title = $model->title;
                $contentsInfo->pid = $model->pid;
                $contentsInfo->explanation = $model->explanation;
                $contentsInfo->state = 'ONLINE';
                $contentsInfo->modified_by = Yii::app()->session['username'];
                $contentsInfo->modified_on = date('Y-m-d H:i:s', time());
                if ($contentsInfo->save()) {
                    $this->contentsModel->geneCatalog();
                    $this->contentsModel->geneCatelogList();
                    $this->redirect($this->createUrl('/index/main'));
                }
            }
        }
        $catalogListPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalogListForContents.json';
        if (! file_exists($catalogListPath)) {
            $this->contentsModel->geneCatelogList();
        }
        $catalogListJson = file_get_contents($catalogListPath);
        if (empty($catalogListJson)) {
            $this->contentsModel->geneCatelogList();
            $catalogListJson = file_get_contents($catalogListPath);
        }
        $catalogList = json_decode($catalogListJson, TRUE);
        $model->id = $contentsInfo->id;
        $model->title = $contentsInfo->title;
        $model->pid = $contentsInfo->pid;
        $model->explanation = $contentsInfo->explanation;
        $this->render('create-edit', array(
            'model' => $model,
            'parent' => $catalogList,
            'title' => '编辑文档目录'
        ));
    }

    /**
     * 删除目录
     */
    public function actionDelete()
    {
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($cid)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($cid);
        $this->userModel = UserModel::model();
        if (! (($contentsInfo && $contentsInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权删除';
            exit(json_encode($result));
        }
        // 删除前先判断是否存在子目录和文档
        $hasChildOrDocument = FALSE;
        $childContents = $this->contentsModel->getChildContents($cid);
        $this->documentModel = DocumentModel::model();
        if (count($childContents)) {
            $hasChildOrDocument = '当前目录下存在子目录';
            $documents = $this->documentModel->getDocumentIdsByCIds($childContents);
            if (count($documents)) {
                $hasChildOrDocument = '当前目录下存在子目录和文档';
            }
        } else {
            $documentInfo = $this->documentModel->hasDocument($cid);
            if ($documentInfo) {
                $hasChildOrDocument = '当前目录下有文档';
            }
        }
        if ($hasChildOrDocument) {
            $result['err'] = 2;
            $result['result'] = $hasChildOrDocument . '，是否一并删除';
            exit(json_encode($result));
        }
        $contentsInfo->state = 'DELETED';
        $contentsInfo->modified_by = Yii::app()->session['username'];
        $contentsInfo->modified_on = date('Y-m-d H:i:s', time());
        if ($contentsInfo->save()) {
            $this->contentsModel->geneCatalog();
            $this->contentsModel->geneCatelogList();
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }

    /**
     * 还原目录
     */
    public function actionResave()
    {
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($cid)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($cid);
        $this->userModel = UserModel::model();
        if (! (($contentsInfo && $contentsInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权还原，请联系创建者';
            exit(json_encode($result));
        }
        if ($contentsInfo->pid) {
            $parentContents = $this->contentsModel->findByPk($contentsInfo->pid);
        } else {
            $parentContents = NULL;
        }
        if (($contentsInfo->pid && ! $parentContents) || ($parentContents && 'ONLINE' != $parentContents->state)) {
            $result['err'] = 1;
            $result['result'] = '当前目录的父目录已被删除，请先恢复其父目录';
            exit(json_encode($result));
        }
        $contentsInfo->state = 'ONLINE';
        $contentsInfo->modified_by = Yii::app()->session['username'];
        $contentsInfo->modified_on = date('Y-m-d H:i:s', time());
        if ($contentsInfo->save()) {
            $this->contentsModel->geneCatalog();
            $this->contentsModel->geneCatelogList();
            $result['err'] = 0;
            $result['result'] = '还原成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '还原失败';
        }
        echo json_encode($result);
    }

    public function actionRecycleDel()
    {
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($cid)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($cid);
        $this->userModel = UserModel::model();
        if (! (($contentsInfo && $contentsInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权删除';
            exit(json_encode($result));
        }
        $flag = TRUE;
        $children = $this->contentsModel->getRecycleChildContents($cid);
        $this->documentModel = DocumentModel::model();
        $transaction = $this->contentsModel->dbConnection->beginTransaction();
        try {
            if (count($children)) {
                foreach ($children as $child) {
                    $documentInfo = $this->documentModel->hasRecycleDocument($child->id);
                    if ($documentInfo) {
                        $documentInfo->delete();
                    }
                    $child->delete();
                }
            }
            $documentInfo = $this->documentModel->hasRecycleDocument($cid);
            if ($documentInfo) {
                $documentInfo->delete();
            }
            $this->contentsModel->deleteByPk($cid);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            $flag = FALSE;
        }
        if ($flag) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }

    /**
     * 检查目录的所有者（管理员亦返回真）
     */
    public function actionCheckContentsOwner()
    {
        $id = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($id)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
        }
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($id);
        $this->userModel = UserModel::model();
        if (($contentsInfo && $contentsInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 0;
        } else {
            $result['err'] = 1;
            $result['result'] = '当前选择的父目录非本人创建，其下的子目录有被创建者' . $contentsInfo->created_by . '删除的可能';
        }
        echo json_encode($result);
    }

    public function actionList()
    {
        $id = Yii::app()->request->getParam('CId');
        if(empty($id)){
            $id = 0;
        }
        $this->contentsModel = ContentsModel::model();
        $this->userModel = UserModel::model();
        /* if ($this->userModel->isAdmin(Yii::app()->session['username'])) {
            $contentsResult = $this->contentsModel->getAllContents();
        } else {
            $contentsResult = $this->contentsModel->getOwnContents(Yii::app()->session['username']);
        } */
        $contentsResult = $this->contentsModel->getContents($id);
        $this->render('/index/main', array(
            'list' => $contentsResult['list'],
            'page' => $contentsResult['page'],
            'title' => '我的目录',
            'contentId' => $id
        ));
    }

    public function actionRecycle()
    {
        $this->contentsModel = ContentsModel::model();
        $this->userModel = UserModel::model();
        if ($this->userModel->isAdmin(Yii::app()->session['username'])) {
            $contentsResult = $this->contentsModel->getAllRecycleContents();
        } else {
            $contentsResult = $this->contentsModel->getOwnRecycleContents(Yii::app()->session['username']);
        }
        $this->render('/index/recycle', array(
            'list' => $contentsResult['list'],
            'page' => $contentsResult['page'],
            'title' => '我的文档目录回收站'
        ));
    }

    public function actionConfirmDelete()
    {
        $cid = Yii::app()->request->getParam('CId');
        $result = array();
        if (! is_numeric($cid)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->contentsModel = ContentsModel::model();
        $contentsInfo = $this->contentsModel->findByPk($cid);
        $this->userModel = UserModel::model();
        if (! (($contentsInfo && $contentsInfo->created_by == Yii::app()->session['username']) || $this->userModel->isAdmin(Yii::app()->session['username']))) {
            $result['err'] = 1;
            $result['result'] = '非本人创建，无权删除';
            exit(json_encode($result));
        }
        $flag = TRUE;
        $contents = $this->contentsModel->getChildContents($cid);
        $this->documentModel = DocumentModel::model();
        $transaction = $this->contentsModel->dbConnection->beginTransaction();
        try {
            if (count($contents)) {
                foreach ($contents as $item) {
                    $contentsItem = $this->contentsModel->findByPk($item['id']);
                    $contentsItem->state = 'DELETED';
                    $contentsItem->modified_by = Yii::app()->session['username'];
                    $contentsItem->modified_on = date('Y-m-d H:i:s', time());
                    $contentsItem->save();
                    $documentInfo = $this->documentModel->hasDocument($item['id']);
                    if ($documentInfo) {
                        $documentInfo->state = 'DELETED';
                        $documentInfo->modified_by = Yii::app()->session['username'];
                        $documentInfo->modified_on = date('Y-m-d H:i:s', time());
                        $documentInfo->save();
                    }
                }
            }
            $documentInfo = $this->documentModel->hasDocument($cid);
            if ($documentInfo) {
                $documentInfo->state = 'DELETED';
                $documentInfo->modified_by = Yii::app()->session['username'];
                $documentInfo->modified_on = date('Y-m-d H:i:s', time());
                $documentInfo->save();
            }
            $contentsInfo->state = 'DELETED';
            $contentsInfo->modified_by = Yii::app()->session['username'];
            $contentsInfo->modified_on = date('Y-m-d H:i:s', time());
            $contentsInfo->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            $flag = FALSE;
        }
        if ($flag) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
            $this->contentsModel->geneCatalog();
            $this->contentsModel->geneCatelogList();
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        /*
         * if(count($contents)){
         * foreach($contents as $item){
         * $contentsItem = $this->contentsModel->findByPk($item['id']);
         * $contentsItem->state = 'DELETED';
         * $contentsItem->modified_by = Yii::app()->session['username'];
         * $contentsItem->modified_on = date('Y-m-d H:i:s',time());
         * if(!$contentsItem->save()){
         * $flag = false;
         * }
         * $documentInfo = $this->documentModel->hasDocument($item['id']);
         * if($documentInfo){
         * $documentInfo->state = 'DELETED';
         * $documentInfo->modified_by = Yii::app()->session['username'];
         * $documentInfo->modified_on = date('Y-m-d H:i:s',time());
         * if(!$documentInfo->save()){
         * $flag = false;
         * }
         * }
         * }
         * }
         * $documentInfo = $this->documentModel->hasDocument($cid);
         * if($documentInfo){
         * $documentInfo->state = 'DELETED';
         * $documentInfo->modified_by = Yii::app()->session['username'];
         * $documentInfo->modified_on = date('Y-m-d H:i:s',time());
         * if(!$documentInfo->save()){
         * $flag = false;
         * }
         * }
         * if(!$flag){
         * $result['err'] = 1;
         * $result['result'] = '删除子目录和文档失败';
         * exit(json_encode($result));
         * }
         *
         * $contentsInfo->state = 'DELETED';
         * $contentsInfo->modified_by = Yii::app()->session['username'];
         * $contentsInfo->modified_on = date('Y-m-d H:i:s',time());
         * if ($contentsInfo->save()) {
         * $this->contentsModel->geneCatalog();
         * $this->contentsModel->geneCatelogList();
         * $result['err'] = 0;
         * $result['result'] = '删除成功';
         * } else {
         * $result['err'] = 1;
         * $result['result'] = '删除失败';
         * }
         */
        echo json_encode($result);
    }
}

?>