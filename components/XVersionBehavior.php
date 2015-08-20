<?php
namespace backend\components;

use yii\base\Behavior;
use yii\mongodb\ActiveRecord;

use backend\models\Blog;

/**
 * Description of XVersionBehavior
 *
 * @author KAS <kassel@weitkamper.de>
 */
class XVersionBehavior extends Behavior{
	
	//put your code here
	public $create = 'created_at';
	public $update;
	public $version;
	public $editor = 'edited_by';
	
	public $attributes;
	
	public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }
	
	/**
	 * Neuer Eintrag
	 * create wird gesetzt
	 * Version wird gesetzt
	 * 
	 * @param type $event
	 * @author KAS <kassel@weitkamper.de>
	 */
	public function beforeSave($event) {
		
		if($this->owner->getIsNewRecord()){
			$this->owner->{$this->create} = new \MongoDate();
			$this->owner->{$this->version} = 1;
			$this->owner->{$this->editor}  = \Yii::$app->user->getIdentity()->fullname;
		}

	}
	
	/**
	 * Update Eintrag 
	 * update wird gesetzt
	 * version wird inkrementiert
	 * 
	 * @param type $event
	 * @author KAS <kassel@weitkamper.de> 
	 */
	public function beforeUpdate($event) {
		
		if(!$this->owner->getIsNewRecord()){
			$this->owner->{$this->update} = new \MongoDate();
			$this->owner->{$this->editor}  = \Yii::$app->user->getIdentity()->fullname;
			$this->inkrementVersion();
			$this->saveVersion();
		}
	}
		
	/**
	 * Erhöhen der Versionsnummer
	 * 
	 * @return boolean
	 */
	public function inkrementVersion(){
		$this->owner->{$this->version}+=1;
		return true;
	}
	
	/**
	 * Speichern der Vorgängerversion
	 * 
	 * @author KAS <kassel@weitkamper.de> 28.05.2015
	 */	
	public function saveVersion(){
		// MongoID holen
		$pk		= $this->owner->getPrimaryKey();
		// Holen der Vorgänger Daten
		$oldData= Blog::findOne(new \MongoId($pk));
		// holen des collections Namens
		$colName= $oldData->getCollection()->getName();
		// Passend zur Collection wird eine history Collection verwendet
		$col	= \Yii::$app->mongodb->getCollection($colName."_history");
		// Laden der alten Daten ins Model
		$blog	= new Blog($oldData);
		// ID aus dem Model löschen (kann sonst nicht gespeichert werden 
		unset($blog->_id);
		var_dump($blog->toArray());
		
		
//		// Speichern des alen Eintrags -----------------------------------------
//		$blog->titel			= $oldData->titel;
//		$blog->content			= $oldData->content;
		$blog->{$this->update}	= $oldData->{$this->update};
		$blog->parent			= $pk;
//		//----------------------------------------------------------------------
		$col->insert($blog->toArray());
	}
}
