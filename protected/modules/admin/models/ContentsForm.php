<?php

class ContentsForm extends CFormModel
{
    public $id;
    public $title;
    public $pid;
    public $explanation;
    
    /**
     * 表单验证规则
     * @see CModel::rules()
     */
    public function rules(){
        return array(
            array('id','safe'),
            array('title','required','message'=>'目录名称不能为空'),
            array('title','length','max'=>32),
            array('pid','required','message'=>'父目录不能为空'),
            array('pid','numerical','message'=>'父目录填写内容有误'),
            array('explanation','safe'),
        );
    }
    
    /**
     * 表单标签
     * @see CModel::attributeLabels()
     */
    public function attributeLabels(){
        return array(
            'title'=>'目录名称',
            'pid'=>'所在父目录',
            'explanation'=>'相关说明',
        );
    }

}

?>