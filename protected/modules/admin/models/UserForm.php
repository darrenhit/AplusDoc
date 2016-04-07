<?php

class UserForm extends CFormModel
{
    public $id;
    public $username;
    public $isAdmin = 0;
    public $oldPwd;
    public $newPwd;
    public $confirmPwd;
    
    /**
     * 设置验证规则
     * @see CModel::rules()
     */
    public function rules(){
        return array(
            array('id','safe'),
            array('username','safe'),
            array('isAdmin','safe'),
            array('oldPwd','length','min'=>5,'max'=>'32','tooShort'=>'原始密码不能少于{min}个字节','tooLong'=>'原始密码不能长于{max}个字节'),
            array('newPwd','length','min'=>5,'max'=>'32','tooShort'=>'新密码不能少于{min}个字节','tooLong'=>'新密码不能长于{max}个字节'),
            array('newPwd','authenticate'),
            array('confirmPwd','compare','compareAttribute'=>'newPwd','message'=>'两次密码不一致'),
        );
    }
    
    public function authenticate(){
        if(!empty($this->oldPwd) && !empty($this->newPwd) && ($this->newPwd == $this->oldPwd)){
            $this->addError('newPwd', '新密码不能与原始密码相同');
        }
    }
    
    /**
     * 设置表单标签
     * @see CModel::attributeLabels()
     */
    public function attributeLabels(){
        return array(
            'username'=>'用户名',
            'isAdmin'=>'是否管理员',
            'oldPwd'=>'原始密码',
            'newPwd'=>'新密码',
            'confirmPwd'=>'确认密码',
        );
    }
}

?>