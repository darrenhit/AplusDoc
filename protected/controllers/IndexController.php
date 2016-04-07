<?php

class IndexController extends Controller
{
    public $layout = "//layouts/front-main";
    public $catalog = '';
    private $contentsModel;
    
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>'0xFFFFFF',
                'height'=>35,
            ),
        );
    }
    
    protected function beforeAction($action){
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
    
    /**
     * 首页框架页
     */
    public function actionIndex(){
        //$this -> renderPartial('index');
        $this->redirect($this->createUrl('/index/main'));
    }
    
    /**
     * 头部页
     */
    public function actionHeader(){
        $this -> layout = '//layouts/header-layout';
        $this -> render('header');
    }
    
    /**
     * 右侧首页
     */
    public function actionMain(){
        $documentModel = DocumentModel::model();
        $documentInfo = $documentModel->find('cid = 0');
        $this -> render('main',array('model'=>$documentInfo));
    }
    
    /**
     * 底部页
     */
    public function actionFooter(){
        $this -> render('footer');
    }
    
    /**
     * 问题反馈
     */
    public function actionReply(){
        $model = new ReplyForm();
        if(isset($_POST['ReplyForm'])){
            $model->attributes = $_POST['ReplyForm'];
            if($model->validate()){
                $replyModel = new ReplyModel;
                $replyModel->title = $model->title;
                $replyModel->description = $model->description;
                $replyModel->telephone = $model->telephone;
                $replyModel->email = $model->email;
                $replyModel->state = 'ONLINE';
                $replyModel->created_on = date('Y-m-d H:i:s',time());
                if($replyModel->save()){
                    Yii::app()->user->setFlash('reply','问题反馈已提交成功');
                    sleep(3);
                    $this->refresh();
                }
            }
        }
        $this->render('reply',array('model'=>$model,'title'=>'问题反馈'));
    }
    
    /**
     * 错误页
     */
    public function actionError(){
        $error = Yii::app()->errorHandler->error;
        $result = array();
        if($error['code'] == '404'){
            $result['title'] = '页面不存在';
            $result['message'] = '您所访问的页面不存在';
        }elseif ($error['code'] == '500'){
            $result['title'] = '错误提示';
            $result['message'] = '访问发生错误，请联系管理员处理<br/>'.$error['message'];
        }
        $this->render('message',$result);
    }
    
    // 展示图片
    public function actionGetImage(){
        $filename = Yii::app()->request->getParam('name');
        $mimeType = CFileHelper::getMimeTypeByExtension($filename);
        header('Content-type: ' . $mimeType);
        echo file_get_contents(Yii::app()->params['uploadPath'] . 'image/' . $filename);
    }
    
    // 展示文件
    public function actionGetFile(){
        $filename = Yii::app()->request->getParam('name');
        $mimeType = CFileHelper::getMimeTypeByExtension($filename);
        header('Content-type: ' . $mimeType); //指定下载文件类型
        header('Content-Disposition: attachment; filename="'.$filename.'"'); //指定下载文件的描述
        echo file_get_contents(Yii::app()->params['uploadPath'] . 'file/' . $filename);
    }
}

?>