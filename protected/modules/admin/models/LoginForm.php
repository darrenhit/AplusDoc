<?php

/**
 * 用户登录表单.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required','message'=>'用户名不能为空'),
		    array('username', 'length','min'=>5,'max'=>32),
			// password needs to be authenticated
			array('password', 'required','message'=>'密码不能为空'),
		    array('password', 'length', 'min'=>5),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'用户名',
		    'password'=>'密码',
		);
	}
}
