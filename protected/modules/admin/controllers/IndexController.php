<?php

class IndexController extends Controller
{

    private $userModel;

    private $contentsModel;

    private $documentModel;

    private $replyModel;

    private $cachePath;

    public $isAdmin;

    public $username;
    
    public $catalog;

    protected function beforeAction($action)
    {
        if ('reply' == $action->id || 'replyDetail' == $action->id) {
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
     * 首页
     */
    public function actionIndex()
    {
        // $this->renderPartial('index');
        $this->redirect($this->createUrl('/index/main'));
    }

    /**
     * 页头
     */
    public function actionHeader()
    {
        $this->layout = "//layouts/header-layout";
        $this->userModel = UserModel::model();
        $isAdmin = $this->userModel->isAdmin(Yii::app()->session['username']);
        $this->render('header', array(
            'username' => Yii::app()->session['username'],
            'isAdmin' => $isAdmin
        ));
    }

    /**
     * 右侧首页
     */
    public function actionMain()
    {
        $this->redirect($this->createUrl('/contents/list'));
    }

    /**
     * 页面底部
     */
    public function actionFooter()
    {
        $this->render('footer');
    }

    /**
     * 用户问题反馈列表
     */
    public function actionReply()
    {
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            Yii::app()->user->setFlash('auth', '您没有操作权限');
            $replyResult = array(
                'list' => array(),
                'page' => NULL
            );
        } else {
            $this->replyModel = ReplyModel::model();
            $replyResult = $this->replyModel->getReplys();
        }
        $this->render('reply', array(
            'list' => $replyResult['list'],
            'page' => $replyResult['page'],
            'title' => '用户问题反馈列表'
        ));
    }

    /**
     * 用户问题反馈详情页
     */
    public function actionReplyDetail()
    {
        $replyId = Yii::app()->request->getParam('Id');
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            Yii::app()->user->setFlash('auth', '您没有操作权限');
            $replyInfo = NULL;
        } elseif (! is_numeric($replyId)) {
            Yii::app()->user->setFlash('param', '参数错误');
            $replyInfo = NULL;
        } else {
            $this->replyModel = ReplyModel::model();
            $replyInfo = $this->replyModel->findByPk($replyId);
        }
        $this->render('replyDetail', array(
            'model' => $replyInfo,
            'title' => '用户反馈详情页'
        ));
    }

    /**
     * 关闭用户反馈操作
     */
    public function actionReplyClose()
    {
        $replyId = Yii::app()->request->getParam('Id');
        $result = array();
        if (! is_numeric($replyId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 1;
            $result['result'] = '您没有操作权限';
            exit(json_encode($result));
        }
        $replyModel = ReplyModel::model();
        $replyInfo = $replyModel->findByPk($replyId);
        if ($replyInfo) {
            $replyInfo->state = 'CLOSED';
            if ($replyInfo->save()) {
                $result['err'] = 0;
                $result['result'] = '该问题反馈已关闭';
                exit(json_encode($result));
            }
        }
        $result['err'] = 1;
        $result['result'] = '该问题反馈关闭失败';
        echo json_encode($result);
    }
    
    // 展示图片
    public function actionGetImage()
    {
        $filename = Yii::app()->request->getParam('name');
        $mimeType = CFileHelper::getMimeTypeByExtension($filename);
        header('Content-type: ' . $mimeType);
        echo file_get_contents(Yii::app()->params['uploadPath'] . 'image/' . $filename);
    }
    
    // 展示文件
    public function actionGetFile()
    {
        $filename = Yii::app()->request->getParam('name');
        $mimeType = CFileHelper::getMimeTypeByExtension($filename);
        header('Content-type: ' . $mimeType); // 指定下载文件类型
        header('Content-Disposition: attachment; filename="' . $filename . '"'); // 指定下载文件的描述
        echo file_get_contents(Yii::app()->params['uploadPath'] . 'file/' . $filename);
    }

    /**
     * 获取Aplus节点GUID列表
     */
    public function actionGetAplusNodeList()
    {
        $id = Yii::app()->request->getParam('Id');
        $this->cachePath = Yii::getPathOfAlias('webroot') . '/assets/cache/node/';
        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, TRUE);
        }
        if (file_exists($this->cachePath . $id) && (filemtime($this->cachePath . $id) + 3600 * 24 > time())) {
            $list = file_get_contents($this->cachePath . $id);
            $listJson = json_decode($list, true);
        } else {
            $nId = $id == '#' ? '' : str_replace('node-', '', $id);
            $params = array(
                'id' => $nId
            );
            $listArr = $this->_getAplusData(Yii::app()->params['aplusHost'] . '/node_tree', $params);
            $listJson = array();
            if ('#' !== $id) {
                // 除根节点外都先返回三个子目录（页面，模板，模型）
                $listJson = array(
                    array(
                        'id' => 'page-' . $nId,
                        'parent' => $id,
                        'text' => '页面',
                        'children' => true,
                        'icon' => 'glyphicon glyphicon-book'
                    ),
                    array(
                        'id' => 'template-' . $nId,
                        'parent' => $id,
                        'text' => '模板',
                        'children' => true,
                        'icon' => 'glyphicon glyphicon-comment'
                    ),
                    array(
                        'id' => 'model-' . $nId,
                        'parent' => $id,
                        'text' => '模型',
                        'children' => true,
                        'icon' => 'glyphicon glyphicon-hdd'
                    )
                );
            }
            if (count($listArr)) {
                // 存在子节点
                foreach ($listArr as $item) {
                    $guid = $item['guid'];
                    $listJson[] = array(
                        'id' => 'node-' . $guid,
                        'parent' => $id,
                        'text' => $item['title'],
                        'children' => true,
                        'a_attr' => array(
                            'onClick' => "useGuid('$guid');",
                            'href' => 'javascript:void(0);'
                        )
                    );
                }
            }
            $list = json_encode($listJson);
            file_put_contents($this->cachePath . $id, $list);
        }
        if (count($listJson)) {
            $result = array(
                'err' => 0,
                'result' => $listJson
            );
        } else {
            $result = array(
                'err' => 1,
                'result' => '获取节点列表失败'
            );
        }
        echo json_encode($result['result']);
    }

    /**
     * 获取Aplus页面GUID列表
     */
    public function actionGetAplusPageList()
    {
        $id = Yii::app()->request->getParam('Id');
        $this->cachePath = Yii::getPathOfAlias('webroot') . '/assets/cache/page/';
        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, TRUE);
        }
        if (file_exists($this->cachePath . $id) && filemtime($this->cachePath . $id) + 3600 * 24 > time()) {
            $list = file_get_contents($this->cachePath . $id);
            $listJson = json_decode($list, TRUE);
        } else {
            $nId = str_replace('page-', '', $id);
            $params = array(
                'node_id' => $nId,
                'type' => 'page'
            );
            $listArr = $this->_getAplusData(Yii::app()->params['aplusHost'] . '/page_tree', $params);
            $listJson = array();
            if (count($listArr)) {
                foreach ($listArr as $item) {
                    $guid = $item['guid'];
                    $listJson[] = array(
                        'id' => 'segment-' . $guid,
                        'parent' => $id,
                        'text' => $item['title'],
                        'children' => true,
                        'a_attr' => array(
                            'onClick' => "useGuid('$guid');",
                            'href' => 'javascript:void(0);'
                        )
                    );
                }
                $list = json_encode($listJson);
                file_put_contents($this->cachePath . $id, $list);
            }
        }
        if (count($listJson)) {
            $result['err'] = 0;
            $result['result'] = $listJson;
        } else {
            $result['err'] = 1;
            $result['result'] = '获取页面列表失败';
        }
        echo json_encode($result['result']);
    }

    /**
     * 获取Aplus碎片GUID列表
     */
    public function actionGetAplusSegmentList()
    {
        $id = Yii::app()->request->getParam('Id');
        $this->cachePath = Yii::getPathOfAlias('webroot') . '/assets/cache/segment/';
        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, TRUE);
        }
        if (file_exists($this->cachePath . $id) && filemtime($this->cachePath . $id) + 3600 * 24 > time()) {
            $list = file_get_contents($this->cachePath . $id);
            $listJson = json_decode($list, TRUE);
        } else {
            $nId = str_replace('segment-', '', $id);
            $params = array(
                'parent_id' => $nId
            );
            $listArr = $this->_getAplusData(Yii::app()->params['aplusHost'] . '/page_list', $params);
            $listJson = array();
            if (count($listArr)) {
                foreach ($listArr as $item) {
                    $guid = $item['guid'];
                    $listJson[] = array(
                        'id' => 'segment-' . $guid,
                        'parent' => $id,
                        'text' => $item['title'],
                        'children' => true,
                        'a_attr' => array(
                            'onClick' => "useGuid('$guid');",
                            'href' => 'javascript:void(0);'
                        )
                    );
                }
                $list = json_encode($listJson);
                file_put_contents($this->cachePath . $id, $list);
            }
        }
        if (count($listJson)) {
            $result['err'] = 0;
            $result['result'] = $listJson;
        } else {
            $result['err'] = 1;
            $result['result'] = '获取碎片列表失败';
        }
        echo json_encode($result['result']);
    }

    /**
     * 获取Aplus模板GUID列表
     */
    public function actionGetAplusTemplateList()
    {
        $id = Yii::app()->request->getParam('Id');
        $this->cachePath = Yii::getPathOfAlias('webroot') . '/assets/cache/template/';
        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, TRUE);
        }
        if (file_exists($this->cachePath . $id) && filemtime($this->cachePath . $id) + 3600 * 24 > time()) {
            $list = file_get_contents($this->cachePath . $id);
            $listJson = json_decode($list, TRUE);
        } else {
            $nId = str_replace('template-', '', $id);
            $params = array(
                'node_id' => $nId
            );
            $listArr = $this->_getAplusData(Yii::app()->params['aplusHost'] . '/template_tree', $params);
            $listJson = array();
            if (count($listArr)) {
                foreach ($listArr as $item) {
                    $guid = $item['guid'];
                    $listJson[] = array(
                        'id' => 'template-' . $guid,
                        'parent' => $id,
                        'text' => $item['title'],
                        'children' => false,
                        'icon' => 'jstree-file',
                        'a_attr' => array(
                            'onClick' => "useGuid('$guid');",
                            'href' => 'javascript:void(0);'
                        )
                    );
                }
                $list = json_encode($listJson);
                file_put_contents($this->cachePath . $id, $list);
            }
        }
        if (count($listJson)) {
            $result['err'] = 0;
            $result['result'] = $listJson;
        } else {
            $result['err'] = 1;
            $result['result'] = '获取模板列表失败';
        }
        echo json_encode($result['result']);
    }

    /**
     * 获取Aplus模型GUID列表
     */
    public function actionGetAplusModelList()
    {
        $id = Yii::app()->request->getParam('Id');
        $this->cachePath = Yii::getPathOfAlias('webroot') . '/assets/cache/model/';
        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, TRUE);
        }
        if (file_exists($this->cachePath . $id) && filemtime($this->cachePath . $id) + 3600 * 24 > time()) {
            $list = file_get_contents($this->cachePath . $id);
            $listJson = json_decode($list, TRUE);
        } else {
            $nId = str_replace('model-', '', $id);
            $params = array(
                'node_id' => $nId
            );
            $listArr = $this->_getAplusData(Yii::app()->params['aplusHost'] . '/model_tree', $params);
            $listJson = array();
            if (count($listArr)) {
                foreach ($listArr as $item) {
                    $guid = $item['guid'];
                    $listJson[] = array(
                        'id' => 'model-' . $guid,
                        'parent' => $id,
                        'text' => $item['title'],
                        'children' => false,
                        'icon' => 'jstree-file',
                        'a_attr' => array(
                            'onClick' => "useGuid('$guid');",
                            'href' => 'javascript:void(0);'
                        )
                    );
                }
                $list = json_encode($listJson);
                file_put_contents($this->cachePath . $id, $list);
            }
        }
        if (count($listJson)) {
            $result['err'] = 0;
            $result['result'] = $listJson;
        } else {
            $result['err'] = 1;
            $result['result'] = '获取模型列表失败';
        }
        echo json_encode($result['result']);
    }

    /**
     * 获取Aplus中数据的基础方法
     *
     * @param string $url            
     * @param array $params            
     */
    private function _getAplusData($url, $params)
    {
        $cookie = Yii::app()->request->cookies[Yii::app()->params['aplusCookieName']];
        if (empty($cookie)) {
            $token = $this->_aplusLogin();
            $cookie = new CHttpCookie(Yii::app()->params['aplusCookieName'], $token);
            $cookie->expire = 3600 * 24;
            Yii::app()->request->cookies[Yii::app()->params['aplusCookieName']] = $cookie;
        }
        $listJson = Yii::app()->curl->setOption(CURLOPT_COOKIE, 'PHPSESSID=' . $cookie)->get($url, $params);
        $listArr = json_decode($listJson, true);
        if ('100200' == $listArr['code']) {
            return $listArr['data'];
        } else {
            return array();
        }
    }

    private function _aplusLogin()
    {
        $url = Yii::app()->params['aplusHost'] . '/nativeLogin';
        $params = array(
            'username' => 'web_auto_write',
            'password' => 'web_auto_write123'
        );
        $loginJson = Yii::app()->curl->post($url, $params);
        $loginArr = json_decode($loginJson, true);
        if ($loginArr['code'] == '101200') {
            return $loginArr['data']['token'];
        } else {
            return FALSE;
        }
    }
}

?>