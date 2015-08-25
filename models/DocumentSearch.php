<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\data\ActiveDataProvider;
/**
 * Description of DocumentSearch
 *
 * @author KAS <kassel@weitkamper.de>
 */
class DocumentSearch extends Documents{
	
	public $filename;
	public $zs;
	public $titel;
	public $group;
	public $onlineStatus;
	
	public $r,$_pjax;
	
	public function rules() {
		return [
			[['filename','zs','titel','onlineStatus','group','r','_pjax'],'safe']
		];
	}
	
	/**
	 * Filtern und Sortieren der Documente
	 * 
	 * @param type $params
	 * @return ActiveDataProvider
	 * @author KAS <kassel@weitkamper.de>
	 */
	public function search($params){
		$query = Documents::find();
		//
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 15,
			],
		]);
		// Sortieren setzen ----------------------------------------------------
		$dataProvider->setSort([
		'defaultOrder' => ['filename'=>SORT_ASC],
        'attributes' => [
			'metadata.TITEL'=>[
				'asc'=>['metadata.TITEL'=>SORT_ASC],
				'desc'=>['metadata.TITEL'=>SORT_DESC],
			],
			'filename'=>[
				'asc'=>['filename'=> SORT_ASC],
				'desc'=>['filename'=> SORT_DESC],
			],
			'onlineStatus'=>[
				'asc'=>['onlineStatus'=> SORT_ASC],
				'desc'=>['onlineStatus'=> SORT_DESC],
			],
		]
		]);	
		// Setzen der Get Parameter --------------------------------------------
		$this->setAttributes($params);
		// Filter für Status ---------------------------------------------------
		if($this->onlineStatus !== "" AND $this->onlineStatus !== NULL){
			// Boolean aus string erzeugen (testen ob auch (bool)$this->onlineStatus gehen würde)
			$this->onlineStatus = ($this->onlineStatus === "false") ? false : true;
			$query->filterWhere([ 'onlineStatus' =>	$this->onlineStatus]);
		}
		// Sonstige Filter -----------------------------------------------------
		$query->andFilterWhere(['like', 'filename',			$this->filename]);
		$query->andFilterWhere(['like', 'metadata.ZSTITEL', $this->zs]);
		$query->andFilterWhere(['like', 'metadata.TITEL',	$this->titel]);
		$query->andFilterWhere(['like', 'groups',			$this->group]);
		//----------------------------------------------------------------------
		return $dataProvider;
	}
}
