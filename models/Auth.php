<?php
namespace app\models;
use yii\base\Model;

/**
 * embedded document für einen Autorisierungstyp
 * wird dem User Document hinzugefügt
 *
 * @package xeps
 * @author KAS <kassel@weitkamper.de>
 */
class Auth extends Model
{
	/**
	 * Wie darf sich ein Benutzer anmelden?
	 * normal|ip|iprange
	 * 
	 * @var string 
	 */
	public $type; 
	
	/**
	 * IP Adresse
	 * @var string IP Von
	 */
	public $allowFrom;
	
	/**
	 * IP Adresse
	 * @var string IP Bis
	 */
	public $allowTo;
	
	/**
	 * Regeln zur Validierung der Modeldaten
	 * - wir matchen auf eine Ip Adresse
	 * 
	 * @return array
	 * @author KAS <kassel@weitkamper.de> 28.07.2015
	 */
	public function rules(){
		return [
			[['allowFrom','allowTo'],'match','pattern'=>"/^(\d{1,3}\.){3,3}\d{1,3}$/",'message'=>'{attribute} ist keine gültige IP Adresse'],
		];
	}
	
	/**
	 * Array mit den Autorisierung Typen
	 * @return array
	 * @author KAS <kassel@weitkamper.de> 28.07.2015
	 */
	public function getTypeArr(){
		return [
			'normal' =>'Normal',
			'ip'	 =>'IP',
			'iprange'=>'IP Bereich'
		];
	}
	
	/**
	 * Nach dem Validieren wandeln wir die Ip Adressen in einen String um
	 * 
	 * @author KAS <kassel@weitkamper.de> 28.07.2015
	 */
	public function afterValidate() {
		if($this->allowFrom !== ""){
			$res	= preg_split("/\./",$this->allowFrom);
			$this->allowFrom= sprintf("%03d",$res[0]).".".sprintf("%03d",$res[1]).".".sprintf("%03d",$res[2]).".".sprintf("%03d",$res[3]);
		}else{
			$this->allowFrom = NULL;
		}
		//----------------------------------------------------------------------
		$res	= NULL;
		// ---------------------------------------------------------------------
		if($this->allowTo !== ""){
			$res	= preg_split("/\./",$this->allowTo);
			$this->allowTo	= sprintf("%03d",$res[0]).".".sprintf("%03d",$res[1]).".".sprintf("%03d",$res[2]).".".sprintf("%03d",$res[3]);
		}else{
			$this->allowTo = NULL;
		}
		//----------------------------------------------------------------------
		parent::afterValidate();
	}

}
