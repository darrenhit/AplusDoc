<?php
Yii::import('application.vendor.*');
require_once ('cas/CAS.php');

class UserController extends Controller
{

    public $userModel = NULL;

    private $cas_host = 'sso-cas.pplive.cn';

    private $cas_context = '/cas';

    private $cas_port = 443;
    
    public $isAdmin;
    
    public $username;
    
    public $contentsModel;
    
    public $catalog;
    
    protected function beforeAction($action){
        $methods = array('index','recycle','supply','edit');
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
     * 用户列表
     */
    public function actionIndex()
    {
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            Yii::app()->user->setFlash('auth', '您没有操作权限');
            $userList = array();
        }
        $userList = $this->userModel->getUsers();
        $this->render('list', array(
            'list' => $userList,
            'title' => '用户列表'
        ));
    }

    /**
     * 用户回收站
     */
    public function actionRecycle()
    {
        $this->userModel = UserModel::model();
        $userList = $this->userModel->getRecycleUsers();
        $this->render('list', array(
            'list' => $userList,
            'title' => '用户回收站列表'
        ));
    }
    
    // CAS自动登陆
    public function actionLogin()
    {
        $forward = Yii::app()->request->getParam('forward', '/');
        
        phpCAS::setDebug();
        phpCAS::client(CAS_VERSION_2_0, $this->cas_host, $this->cas_port, $this->cas_context);
        phpCAS::setNoCasServerValidation();
        phpCAS::handleLogoutRequests();
        phpCAS::forceAuthentication();
        
        $username = phpCAS::getUser();
        $arr = explode('@', $username);
        $this->userModel = UserModel::model();
        if (! $this->userModel->getUser($arr[0])) {
            $this->redirect($this->createUrl('/user/supply'));
        } elseif (! $this->userModel->getUserOnline($arr[0])) {
            $userInfo = $this->userModel->getUser($arr[0]);
            $userInfo->state = 'ONLINE';
            $userInfo->save();
        }
        unset(Yii::app()->session['loged_error']);
        Yii::app()->session['username'] = $arr[0];
        Yii::log($arr[0] . '于' . date('Y-m-d H:i:s') . '登录','info','system.web.user.login');
        $this->redirect($forward);
    }
    
    // 常规登陆模式
    public function actionNativeLogin()
    {
        $model = new LoginForm();
        $this->userModel = UserModel::model();
        
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate()) {
                if ($this->userModel->doOrdinaryLogin($model->username, $model->password)) {
                    Yii::app()->session['username'] = $model->username;
                    Yii::log($model->username . '于' . date('Y-m-d H:i:s') . '登录','info','system.web.user.login');
                    $this->redirect('/');
                } else {
                    $model->addError('password', '用户名或密码错误');
                }
            }
        }
        // 显示登录表单
        $this->layout = '//layouts/header-layout';
        $this->render('login', array(
            'model' => $model,
            'title' => '用户登录'
        ));
    }
    
    // 退出登陆
    public function actionLogout()
    {
        $this->userModel = UserModel::model();
        $this->userModel->doLogout();
        phpCAS::setDebug();
        phpCAS::client(CAS_VERSION_2_0, $this->cas_host, $this->cas_port, $this->cas_context);
        phpCAS::logoutWithRedirectService('http://' . $_SERVER['HTTP_HOST']);
        
        $this->redirect(Yii::app()->homeUrl);
    }
    
    // 首次登陆需要输入用户名、密码以便通过LDAP获取用户信息
    public function actionSupply()
    {
        $model = new LoginForm();
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // 验证用户登录表单并执行登录操作
            if ($model->validate()) {
                $this->userModel = new UserModel();
                if ($this->userModel->getUserInfoByLDAP($model->username, $model->password)) {
                    Yii::app()->session['username'] = $model->username;
                    Yii::log($model->username . '于' . date('Y-m-d H:i:s') . '登录','info','system.web.user.login');
                    $this->redirect('/');
                } else {
                    $model->addError('Err', '域账户的用户名或密码错误');
                }
            }
        }

        $this->layout = '//layouts/header-layout';
        $this->render('login', array(
            'model' => $model,
            'title' => '用户授权'
        ));
    }

    /**
     * 检测是否是管理员
     */
    public function actionIsAdmin()
    {
        $this->userModel = UserModel::model();
        $result = array();
        if ($this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 0;
            $result['result'] = '当前用户是管理员';
        } else {
            $result['err'] = 1;
            $result['result'] = '当前用户非管理员';
        }
        echo json_encode($result);
    }

    /**
     * 编辑用户
     */
    public function actionEdit()
    {
        $model = new UserForm();
        $userId = Yii::app()->request->getParam('UId');
        if (! is_numeric($userId)) {
            $model->addError('id', '参数错误');
            Yii::app()->user->setFlash('paramErr', '参数错误');
        } else {
            $this->userModel = UserModel::model();
            $userInfo = $this->userModel->findByPk($userId);
            if (isset($_POST['UserForm'])) {
                $model->attributes = $_POST['UserForm'];
                if ($model->validate()) {
                    $userInfo->id = $model->id;
                    $userInfo->is_admin = $model->isAdmin;
                    $flag = TRUE;
                    if ($model->newPwd || $model->oldPwd || $model->confirmPwd) {
                        if (empty($model->oldPwd)) {
                            $model->addError('oldPwd', '原始密码不能为空');
                            $flag = FALSE;
                        } elseif (empty($model->newPwd)) {
                            $model->addError('newPwd', '新密码不能为空');
                            $flag = FALSE;
                        } elseif ($userInfo->password != md5(crypt($model->oldPwd, substr($model->oldPwd, 0, 2)))) {
                            $model->addError('oldPwd', '原始密码错误');
                            $flag = FALSE;
                        } elseif ($userInfo->password == md5(crypt($model->newPwd, substr($model->newPwd, 0, 2)))) {
                            $model->addError('newPwd', '新密码不能与原始密码相同');
                            $flag = FALSE;
                        } else {
                            $userInfo->password = md5(crypt($model->newPwd, substr($model->newPwd, 0, 2)));
                        }
                    }
                    $userInfo->modified_by = Yii::app()->session['username'];
                    $userInfo->modified_on = date('Y-m-d H:i:s', time());
                    if ($flag && $userInfo->save()) {
                        $this->redirect($this->createUrl('/user/index'));
                    }
                }
            }
            $model->id = $userInfo->id;
            $model->username = $userInfo->username;
            $model->isAdmin = $userInfo->is_admin;
        }
        $this->render('edit', array(
            'model' => $model,
            'title' => '用户编辑'
        ));
    }

    /**
     * 删除用户
     */
    public function actionDelete()
    {
        $userId = Yii::app()->request->getParam('UId');
        $this->userModel = UserModel::model();
        $result = array();
        if (! is_numeric($userId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 1;
            $result['result'] = '无操作权限';
            exit(json_encode($result));
        }
        $userInfo = $this->userModel->findByPk($userId);
        if ($userInfo->username == Yii::app()->session['username']) {
            $result['err'] = 1;
            $result['result'] = '您正在使用该用户登录，请勿删除';
            exit(json_encode($result));
        }
        $userInfo->state = 'DELETED';
        if ($userInfo->save()) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }

    /**
     * 还原用户
     */
    public function actionResave()
    {
        $userId = Yii::app()->request->getParam('UId');
        $result = array();
        if (! is_numeric($userId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 1;
            $result['result'] = '无操作权限';
            exit(json_encode($result));
        }
        $userInfo = $this->userModel->findByPk($userId);
        $userInfo->state = 'ONLINE';
        if ($userInfo->save()) {
            $result['err'] = 0;
            $result['result'] = '还原成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '还原失败';
        }
        echo json_encode($result);
    }

    /**
     * 删除回收站内用户
     */
    public function actionRecycleDel()
    {
        $userId = Yii::app()->request->getParam('UId');
        $result = array();
        if (! is_numeric($userId)) {
            $result['err'] = 1;
            $result['result'] = '参数错误';
            exit(json_encode($result));
        }
        $this->userModel = UserModel::model();
        if (! $this->userModel->isAdmin(Yii::app()->session['username'])) {
            $result['err'] = 1;
            $result['result'] = '无操作权限';
            exit(json_encode($result));
        }
        if ($this->userModel->deleteByPk($userId)) {
            $result['err'] = 0;
            $result['result'] = '删除成功';
        } else {
            $result['err'] = 1;
            $result['result'] = '删除失败';
        }
        echo json_encode($result);
    }
}

?>
