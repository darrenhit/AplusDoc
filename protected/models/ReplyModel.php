<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ReplyModel extends CActiveRecord
{
	public static function model($className = __CLASS__){
	    return parent::model($className);
	}
	
	public function tableName(){
	    return '{{reply}}';
	}
}