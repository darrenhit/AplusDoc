<?php
/**
 * 目录类
 * @author yulongwang
 *
 */
class ContentsController extends Controller
{
    private $contentsModel = NULL;
    
    public function actionIndex(){
        $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache/catalog';
        if (file_exists($catalogPath) && filemtime($catalogPath) > time() - 3600*24) {
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
}

?>