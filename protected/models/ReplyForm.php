<?php

class ReplyForm extends CFormModel
{
    public $title;
    public $description;
    public $telephone;
    public $email;
    public $verifyCode;
    
    /**
     * 设置表单规则
     * @see CModel::rules()
     */
    public function rules(){
        return array(
            array('title','required','message'=>'请填写问题标题'),
            array('title','length','max'=>128,'tooLong'=>'问题标题过长，最大长度为128个字节'),
            array('description','required','message'=>'请填写问题描述信息'),
            array('telephone','required','message'=>'请填写联系方式'),
            array('telephone','match','pattern'=>'/(\d{3,4}-?\d{6,8})|(\d{11})/','allowEmpty'=>FALSE,'message'=>'联系方式格式不正确'),
            array('email','required','message'=>'请填写邮箱地址'),
            array('email','email','message'=>'邮箱地址格式不正确'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'message'=>'验证码错误')
        );
    }
    
    /**
     * 设置表单标签
     * @see CModel::attributeLabels()
     */
    public function attributeLabels(){
        return array(
            'title'=>'问题标题',
            'description'=>'问题描述',
            'telephone'=>'联系电话',
            'email'=>'邮箱地址',
            'verifyCode'=>'验证码',
        );
    }
}

?>