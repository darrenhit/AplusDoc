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
     * 获取用户自己的文档
     * @param string $username 用户名
     * @return array 文档列表数组+分页对象
     */
    public function getOwnDocument($username)
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','guid','created_by','created_on','modified_on');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND created_by = '$username' AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取所有用户的文档（管理员权限）
     * @return Array 文档列表数组+分页对象
     */
    public function getAllDocument(){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','guid','created_by','created_on','modified_on');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取用户回收站中的文档
     * @param string $username 用户名
     * @return Array 文档列表数组+分页对象
     */
    public function getOwnRecycleDocument($username){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','guid','created_by','created_on','modified_on');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND created_by = '$username' AND state = 'DELETED'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取所有用户回收站中的文档（管理员权限）
     * @return Array 文档列表数组+分页对象
     */
    public function getAllRecycleDocument(){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','guid','created_by','created_on','modified_on');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'DELETED'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }

    /**
     * 根据GUID查询自己的文档
     * @param string $guid GUID
     * @param string $username 用户名
     * @return array 文档列表数组+分页对象
     */
    public function getOwnDocumentByGuid($guid, $username)
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "guid = '$guid' AND state = 'ONLINE' AND created_by = '$username'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据GUID查询所有人的文档
     * @param string $guid
     * @return Array 文档列表数组+分页对象
     */
    public function getAllDocumentByGuid($guid){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "guid = '$guid' AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据Title查询用户自己的文档
     * @param string $title
     * @param string $username
     * @return array 文档列表数组+分页对象
     */
    public function getOwnDocumentByTitle($title,$username){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND created_by = '$username' AND state = 'ONLINE' AND title like '%$title%'";
        $criteria->order = "UNIX_TIMESTAMP(modified_on) DESC";
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据Title查询所有用户的文档
     * @param string $title
     * @return Array 文档列表数组+分页对象
     */
    public function getAllDocumentByTitle($title){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'ONLINE' AND title like '%$title%'";
        $criteria->order = "UNIX_TIMESTAMP(modified_on) DESC";
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据条件查询自己的文档
     * @param string $condition 查询条件（简介、内容、参考）
     * @param string $username 用户名
     * @return array 文档列表数组
     */
    public function getOwnDocumentByCondition($condition, $username){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND created_by = '$username' AND state = 'ONLINE' AND (summary like '%$condition%' OR content like '%$condition%' OR reference like '%$condition%')";
        $criteria->order = "UNIX_TIMESTAMP(modified_on) DESC";
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 根据条件查询所有用户的文档
     * @param string $condition（简介、内容、参考）
     * @return Array 文档列表数组
     */
    public function getAllDocumentByCondition($condition){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','summary');
        $criteria->condition = "(cid <> 0 OR guid <> '') AND state = 'ONLINE' AND (summary like '%$condition%' OR content like '%$condition%' OR reference like '%$condition%')";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取用户自己的GUID文档
     * @param string $username
     * @return Array 文档数组列表+分页对象
     */
    public function getOwnGuidDocument($username){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','guid','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "created_by = '$username' AND guid <> '' AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 获取所有用户的GUID文档
     * @return Array 文档数组列表+分页对象
     */
    public function getAllGuidDocument(){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','guid','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "guid <> '' AND state ='ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
    
    /**
     * 查询指定目录下是否有文档
     * @param int $cid 目录ID
     * @param int $id 文档ID（为空时是创建文档）
     * @return Object 单条文档记录
     */
    public function hasDocument($cid,$id=NULL){
        $criteria = new CDbCriteria();
        if(empty($id)){
            $criteria->condition = "cid = $cid AND state = 'ONLINE'";
        }else{
            $criteria->condition = "id <> $id AND cid = $cid AND state = 'ONLINE'";
        }
        return $this->find($criteria);
    }
    
    /**
     * 查询回收站中是否有指定目录ID的文档
     * @param int $cid
     * @return Object 单条文档记录
     */
    public function hasRecycleDocument($cid){
        $criteria = new CDbCriteria();
        $criteria->condition = "cid = $cid AND state = 'DELETED'";
        return $this->find($criteria);
    }
    
    public function getDocumentIdsByCIds($contentsArr){
        $result = array();
        foreach($contentsArr as $item){
            $documents = $this->findAll('cid = :cid AND state = :state',array(':cid'=>$item['id'],':state'=>'ONLINE'));
            if(count($documents)){
                foreach($documents as $val){
                    $result[] = $val->id;
                }
            }
        }
        return $result;
    }
}

?>