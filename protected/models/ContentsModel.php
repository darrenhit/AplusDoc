<?php

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
                $subLevel = $level + 1;
                $sub = $this->_getContents($item->id, $subLevel);
                $catalog = array_merge($catalog, $sub);
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
}

?>
