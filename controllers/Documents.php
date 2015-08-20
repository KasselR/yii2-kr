<?php
namespace backend\models;

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\mongodb\file\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use common\components\MongoDateBehavior;
/**
 * Description of Documents
 *
 * @property array $metadata Metadaten
 * @property array $metadatanew Metadaten
 * 
 * @author KAS <kassel@weitkamper.de>
 */
class Documents extends ActiveRecord{
	
	public $imageSmall;
	public $Title;
	
	public function behaviors() {
		return [
			[
				'class'=>  MongoDateBehavior::className(),
				'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
			],
			[
				'class' => BlameableBehavior::className(),
                'updatedByAttribute' => 'updated_by',
				'value'=>[$this,'getEditorName']
			]
		];
	}

	public function getEditorName(){
		return \Yii::$app->user->getIdentity()->fullname;
	}
	
	public function rules(){
		return [
			#[['metadata'],'required'],
			['metadata', 'common\validators\EmbedDocValidator', 'model'=>'backend\models\Metadata'],
		];
	}
	

	public static function collectionName() {
		return 'fs';
	}

	public function attributes()
    {
		return array_merge(
			parent::attributes(),
			[
				'filename',
				'metadata',
				'metadatanew',
				'uploadDate',
				'groups',
				'onlineStatus',
				// Behaviors
				'updated_by',
				'updated_at'
				
			]
		);
    }
	
	public function getFilenameplain(){
		return basename($this->filename);
	}
	
	public function getDe_uploadDate(){
		\Yii::$app->formatter->locale = 'de_DE';
		return \Yii::$app->formatter->asDate($this->uploadDate->sec,"php:d.m.y H:i");
	}
	
	public function getDe_updated_at(){
		\Yii::$app->formatter->locale = 'de_DE';
		if($this->updated_at instanceof \MongoDate){
			return \Yii::$app->formatter->asDate($this->updated_at->sec,"php:d.m.y H:i");
		}else{
			return null;
		}
	}
	
	public function getGroupstr(){
		return join(", ",$this->groups);
	}
	
	public static function getAllgroups(){
		$arr = Documents::find()->distinct('groups');
		return array_combine(array_values($arr), array_values($arr));
	}
	
	public function getOnlinestatus_str(){
		return $this->onlineStatus ? 
			Html::tag('span',"Online",['class'=>'btn btn-xs btn-success']): 
			Html::tag('span',"Offline",['class'=>'btn btn-xs btn-danger']);
	}
	
	/**
	 * Titel
	 * @return string
	 */
	public function getTitel(){
		$titel  = $this->metadata['TITEL'];
		return $titel;
	}
	
	/**
	 * Untertitel
	 * @return string
	 */
	public function getSubtitel(){
		$subTitel = $this->metadata['UTITEL'];
		return $subTitel;
	}
	
	/**
	 * Erzeugt einen String mit Autoren und Links
	 * 
	 * @return string
	 * @author KAS <kassel@weitkamper.de> 07.07.2015
	 */
	public function getAutorstr(){
		$autorArr = $this->metadata['AUTOR'];
		$autorStr = join("; ",array_map([$this,"arr2str"],$autorArr));
		return $autorStr;
	}
	
	/**
	 * Callback in getautrostr
	 * Gibt einen Link mit Namen des Autoren zurück
	 * 
	 * @param type $arr
	 * @return string
	 * 
	 * @author KAS <kassel@weitkamper.de> 07.07.2015
	 */
	protected function arr2str($arr){
		return Html::a(
				$arr['value'],
				Url::toRoute(["xsearch/search",'facet[autoren]'=>$arr['value']]),
				['itemprop'=>"name"]
		);
	}
	
	/**
	 * Erzeugt die ImageSrc aus dem Dateinamen
	 * 
	 * @return string
	 * @author KAS <kassel@weitkamper.de> 07.07.2015
	 */
	public function getImage(){
		// TXT mit jpg ersetzen
		$tmp	= preg_replace("/\.pdf$/i",".jpg",$this->filename);
		// Teil nach Daten extrahieren
		$short	= preg_replace("/.*\\\daten\\\(.*)/", "$1", $tmp);
		// Jahrgang und heftnr aus dem Dateinamen ermitteln (ps_2015_03_150)
		$bild	= preg_replace("/_[^_]+(_\d\d)?_[^_]+_[^_]+(\.[^\.]+)$/", "$1_150$2", $short);
		// Zwischenspeichern des Paths 
		$this->imageSmall = $bild;
		// Path vom Frontend hinzufügen ----------------------------------------
		$bild   = "/thumbs/big/".$bild;
		// Wir überprüfen ob die Bilddatei auch vorhanden ist ------------------
		if(file_exists(\Yii::getAlias("@frontend")."/web".$bild) === false){
			$bild = "http://yii2.local/thumbs/no-image.gif";
		}
		// Rückgabe ist der Bildpath -------------------------------------------
		return 'http://yii2.local/'.$bild;
	}
	
	/**
	 * Erzeugt den Bildpath zum großen Bild
	 * 
	 * @return string
	 * @author KAS <kassel@weitkamper.de> 07.07.2015
	 */
	public function getImageBig(){
		$img = preg_replace("/_150/","",$this->imageSmall);
		return "http://yii2.local/thumbs/big/".$img;
	}
	
	/**
	 * Erzeugt einen String mit den Article Infos
	 * - HeftNr
	 * - Datum
	 * - Volume
	 * - Seiten
	 * 
	 * @return string
	 * 
	 * @author KAS <kassel@weitkamper.de> 07.07.2015
	 */
	public function getArticleinfo(){
		// Erzeugen des Ausgabedatums ------------------------------------------
		\Yii::$app->formatter->locale = 'de_DE';
		if($this->metadata !== NULL){
			$dateStr  = $this->metadata['JAHR']."-".$this->metadata['MONAT'];
		}else{
			$dateStr  = "2015-07";
		}
		$datum	  = \Yii::$app->formatter->asDate($dateStr,"php:F Y");
		//----------------------------------------------------------------------
		$infoStr  = Html::a("Heft ".$this->metadata['HEFTNR'],NULL);
		$infoStr .= ",&nbsp;";
		$infoStr .= Html::tag('span',$datum,['itemprop'=>"datePublished"]);
		$infoStr .= ",&nbsp;";
		$infoStr .= $this->metadata['VOLUME'].". Jahrgang";
		$infoStr .= ",&nbsp;";
		$infoStr .= "pp ".$this->metadata['SEITEVON']."-".$this->metadata['SEITEBIS'];
		//----------------------------------------------------------------------
		return $infoStr;
	}
}
