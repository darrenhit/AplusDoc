<?php

class DocumentModel extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{documents}}';
    }

    /**
     * 获取文档内容
     * @param int $id 文档Id
     * @return Object 单条文档记录
     */
    public function getDocumentById($id)
    {
        return $this->findByPk($id);
    }
    
    /**
     * 获取文档内容（根据目录ID）
     */
    public function getDocumentByCId($cid){
        return $this->find('cid = :CId AND state = :state',array(':CId'=>$cid,':state'=>'ONLINE'));
    }

    /**
     * 根据GUID查询文档（列表）
     * @param int $guid 文档GUID
     * @return Array 文档列表
     */
    public function getDocumentByGuid($guid)
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "guid = '$guid' AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination();
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据Title查询文档列表
     * @param string $title
     * @return Array 文档列表
     */
    public function getDocumentByTitle($title){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'ONLINE' AND title like '%$title%'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }

    /**
     * 搜索文档（列表）
     * @param string $condition 查询条件（简介、内容、参考）
     * @return Array 文档列表
     */
    public function getDocumentByCondition($condition)
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'ONLINE' AND (summary like '%$condition%' OR content like '%$condition%' OR reference like '%$condition%')";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取GUID文档
     * @param int $pageNo 页码
     * @return Array 文档列表
     */
    public function getGuidDocuments()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "guid <> '' AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
}

?>