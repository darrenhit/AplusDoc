<?php

/**
 * 目录操作模型
 * @author yulongwang
 *
 */
class ContentsModel extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{contents}}';
    }

    /**
     * 获取线上子目录
     * @param int $pid 父目录ID
     * @param int $level 层级数
     * @return Array 目录列表
     */
    private function _getContents($pid = 0, $level = 0)
    {
        $catalog = array();
        $list = $this->findAll('pid = :pid AND state = :state', array(
            ':pid' => $pid,
            ':state' => 'ONLINE'
        ));
        if (count($list)) {
            foreach ($list as $item) {
                $catalog[] = array_merge(array(
                    'id' => $item->id,
                    'title' => $item->title
                ), array(
                    'level' => $level,
                    'pid' => $pid
                ));
                $subLevel = $level+1;
                $sub = $this->_getContents($item->id, $subLevel);
                $catalog = array_merge($catalog, $sub);
            }
        }
        return $catalog;
    }
    
    /**
     * 获取回收站中子目录
     * @param int $pid 父目录ID
     * @return Array 目录列表
     */
    private function _getRecycleContents($pid = 0){
        $catalog = array();
        $list = $this->findAll('pid = :pid AND state = :state',array(
            ':pid' => $pid,
            ':state' => 'DELETED'
        ));
        if (count($list)){
            foreach ($list as $item){
                $catalog[] = $item;
                $sub = $this->_getRecycleContents($item->id);
                $catalog = array_merge($catalog,$sub);
            }
        }
        return $catalog;
    }
    
    // 创建左侧用目录列表缓存文件
    public function geneCatalog()
    {
        $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache';
        if (! is_dir($catalogPath)) {
            mkdir($catalogPath);
        }
        $list = $this->_getContents();
        $catalog = NULL;
        foreach ($list as $item) {
            if ($item['pid'] == 0) {
                $catalog .= '<li><a href="javascript:void(0);" onClick="catalogSelected(' . $item['id'] . ');" id="contentItem'.$item['id'].'">' . $item['title'] . '</a>';
                $catalog .= $this->_geneCatalog($list, $item['id']);
                $catalog .= '</li>';
            }
        }
        file_put_contents($catalogPath . '/catalog', $catalog);
    }

    private function _geneCatalog($list, $pid)
    {
        $result = NULL;
        foreach ($list as $item) {
            if ($item['pid'] == $pid) {
                $result .= '<li><a href="javascript:void(0);" onClick="catalogSelected(' . $item['id'] . ');" id="contentItem'.$item['id'].'">' . $item['title'] . '</a>';
                $result .= $this->_geneCatalog($list, $item['id']);
                $result .= '</li>';
            }
        }
        if ($result)
            $result = '<ul>' . $result . '</ul>';
        return $result;
    }
    
    // 创建后台创建、编辑目录、文档用目录列表缓存文件
    public function geneCatelogList()
    {
        $catalogPath = Yii::getPathOfAlias('webroot') . '/assets/cache';
        if (! is_dir($catalogPath)) {
            mkdir($catalogPath);
        }
        $list = $this->_getContents();
        $parent = array();
        foreach ($list as $item) {
            $str = '';
            for ($i = 1; $i <= $item['level']; $i ++) {
                $str .= '&nbsp;&nbsp;&nbsp;';
            }
            $parent[$item['id']] = $str . $item['title'];
        }
        $catalogListForContents = array(0=>'作为一级根目录');
        foreach($parent as $key=>$val){
            $catalogListForContents[$key] = $val;
        }
        $catalogListForContentsJson = json_encode($catalogListForContents);
        $catalogListForDocumentJson = json_encode($parent);
        file_put_contents($catalogPath . '/catalogListForContents.json', $catalogListForContentsJson);
        file_put_contents($catalogPath . '/catalogListForDocument.json', $catalogListForDocumentJson);
    }

    /**
     * @deprecated  由于功能调整，该方法已废弃
     * 获取用户自己创建的目录
     * 
     * @param string $username
     *            用户名
     * @return Array 目录记录数组
     */
    public function getOwnContents($username)
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "created_by = '$username' AND state = 'ONLINE'";
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
     * @deprecated  由于功能调整，该方法已废弃
     * 获取所有用户创建的目录（管理员才有的权限）
     * @return Array 目录记录数组
     */
    public function getAllContents(){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "state = 'ONLINE'";
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
     * 获取用户自己回收站中的目录
     * @param string $username 用户名
     * @return Array 目录记录数组
     */
    public function getOwnRecycleContents($username){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "created_by = '$username' AND state = 'DELETED'";
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
     * 获取所有用户回收站中的目录（管理员权限）
     * @return Array 目录记录数组
     */
    public function getAllRecycleContents(){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "state = 'DELETED'";
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
     * 获取在线子目录
     * @param int $parentId
     * @return Array 子目录id,title,level,pid数组
     */
    public function getChildContents($parentId){
        $contents = $this->_getContents($parentId);
        return $contents;
    }
    
    /**
     * 获取回收站中的子目录
     * @param int $parentId
     * @return Array 子目录记录数组
     */
    public function getRecycleChildContents($parentId){
        $contents = $this->_getRecycleContents($parentId);
        return $contents;
    }
    
    /**
     * 获取目录
     * @param int $id   父目录ID
     * @return array 
     */
    public function getContents($id){
        $criteria = new CDbCriteria();
        $criteria->select = array('id','title','created_by','created_on','modified_by','modified_on');
        $criteria->condition = "pid = $id AND state = 'ONLINE'";
        $criteria->order = 'UNIX_TIMESTAMP(modified_on) DESC';
        $count = $this->count($criteria);
        $pager = new CPagination($count);
        $pager->params = array('CId'=>$id,'page'=>0);
        $pager->pageSize = Yii::app()->params['pageSize'];
        $pager->applyLimit($criteria);
        $list = $this->findAll($criteria);
        return array('list'=>$list,'page'=>$pager);
    }
}

?>
