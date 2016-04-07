<?php
/**
 * 文档表单模型
 * @author yulongwang
 *
 */
class DocumentForm extends CFormModel
{
    public $id;
    public $source;
    public $type = 0;
    public $title;
    public $guid;
    public $cid;
    public $summary;
    public $content;
    public $reference;
    
    /**
     * 表单验证规则
     * @see CModel::rules()
     */
    public function rules(){
        return array(
            array('id','safe'),
	    array('source','safe'),
            array('type','required','message'=>'请选择是否关联Aplus数据'),
            array('title','length','max'=>64,'tooLong'=>'标题过长，最大长度{max}字符'),
            array('guid','length','is'=>32,'message'=>'GUID长度要求为{length}字符'),
            array('guid','match','pattern'=>'/^[\d\w]{32}$/i','message'=>'GUID类型不正确'),
            array('cid','numerical','message'=>'目录内容填写有误'),
            array('summary','required','message'=>'请填写文档概述（列表展示和搜索时会展示）'),
            array('summary','length','max'=>300,'tooLong'=>'文档概述过长，最大长度{max}字符','encoding'=>'UTF-8'),
            array('content','length','max'=>200000,'encoding'=>'UTF-8','tooLong'=>'文档内容过长，最大长度{max}字符'),
            array('reference','length','max'=>20000,'tooLong'=>'参考内容过长，最大长度{max}字符','encoding'=>'UTF-8'),
        );
    }
    
    /**
     * 表单标签
     * @see CModel::attributeLabels()
     */
    public function attributeLabels(){
        return array(
            'type'=>'是否关联Aplus中相关数据',
            'title'=>'文档标题',
            'guid'=>'文档所关联的Aplus数据的GUID',
            'cid'=>'所在目录',
            'summary'=>'文档概述',
            'content'=>'文档内容',
            'reference'=>'相关参考',
        );
    }

}

?>
