<?php

class ReplyModel extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{reply}}';
    }

    public function getReplys()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_on');
        $criteria->condition = "state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(created_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
}

?>