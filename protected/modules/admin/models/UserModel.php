<?php

class UserModel extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{user}}';
    }
    
    // 验证是否登录
    public function isLogin()
    {
        if (Yii::app()->session['username']) {
            return true;
        } else {
            return false;
        }
    }
    
    // 使用普通方式登录
    public function doOrdinaryLogin($username, $password)
    {
        $password = md5(crypt($password, substr($password, 0, 2)));
        $userInfo = $this->find('username = :USERNAME AND password = :PASSWORD AND state = :state', array(
            ':USERNAME' => $username,
            ':PASSWORD' => $password,
            ':state' => 'ONLINE'
        ));
        if ($userInfo) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // 通过用户名获取用户
    public function getUser($username)
    {
        $user = $this->find('username = :username', array(
            ':username' => $username
        ));
        return $user;
    }
    
    // 通过用户名获取用户（可用用户）
    public function getUserOnline($username)
    {
        $user = $this->find('username = :username AND state = :state', array(
            ':username' => $username,
            ':state' => 'ONLINE'
        ));
        return $user;
    }

    /**
     * 获取用户列表
     */
    public function getUsers()
    {
        return $this->findAll(array(
            'select' => 'id,username,is_admin,realname,department_name',
            'condition' => 'state = :state',
            'order' => 'id ASC',
            'params' => array(
                ':state' => 'ONLINE'
            )
        ));
    }

    /**
     * 获取用户回收站列表
     */
    public function getRecycleUsers()
    {
        return $this->findAll(array(
            'select' => 'id,username,is_admin,realname,department_name',
            'condition' => 'state = :state',
            'order' => 'id ASC',
            'params' => array(
                ':state' => 'DELETED'
            )
        ));
    }

    /**
     * 通过用户名、密码向LDAP查询用户信息
     * 
     * @param string $username            
     * @param string $password            
     * @return boolean
     */
    public function getUserInfoByLDAP($username, $password)
    {
        $context = array();
        $post = array();
        $post['user'] = $username;
        $post['pass'] = $password;
        $context['http'] = array(
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded ",
            'content' => http_build_query($post, '', '&')
        );
        $ldap_login_info = file_get_contents("http://sso.aplus.idc.pplive.cn/sso_ldap.php", false, stream_context_create($context));
        $ldap_info = json_decode($ldap_login_info, true);
        if (is_array($ldap_info['result'][0]) && count($ldap_info['result'][0]) && $this->addUser($ldap_info['result'][0], $password)) {
            return true;
        }
        return FALSE;
    }
    
    // 向用户表插入用户信息
    private function addUser($ldap_info, $password)
    {
        $this->username = $ldap_info['mailnickname'][0];
        $this->password = md5(crypt($password, substr($password, 0, 2)));
        $this->is_admin = 0;
        $this->realname = $ldap_info['displayname'][0];
        $this->department_name = $ldap_info['department'][0];
        $this->email = $ldap_info['mail'][0];
        $this->state = 'ONLINE';
        $this->modified_by = 'admin';
        $this->modified_on = date('Y-m-d H:i:s', time());
        Yii::log($this->username . '于' . date('Y-m-d H:i:s') . '注册','info','system.web.user.enroll');
        return $this->insert();
    }
    
    // 退出登陆
    public function doLogout()
    {
        if ($username = Yii::app()->session['username']) {
            unset(Yii::app()->session['username']);
            Yii::log($username . '于' . date('Y-m-d H:i:s') . '登出','info','system.web.user.logout');
        }
    }

    /**
     * 验证是否是管理员
     * 
     * @param string $username
     *            用户名
     * @return boolean
     */
    public function isAdmin($username)
    {
        $userInfo = $this->find('username = :username AND state = :state', array(
            ':username' => $username,
            ':state' => 'ONLINE'
        ));
        if ($userInfo && $userInfo->is_admin) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>