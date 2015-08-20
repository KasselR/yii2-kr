<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

/**
 * Benutzer Model
 * definiert die Felder in der MongoDB
 * und stellt Methoden bereit, die Benutzer anzuzeigen, speichern, bearbeiten
 * und löschen.
 * Alles was mit Benutzern gemacht werden muss, kommt hier rein.
 * 
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * 
 * @package xeps
 * @author KAS <kassel@weitkamper.de> 27.07.2015
 */
class User extends ActiveRecord implements IdentityInterface
{
	
	/**
	 * Benutzer Status
	 */
	const STATUS_CLOSED		= "closed";
    const STATUS_ACTIVE		= "active";
    const STATUS_PENDING	= "pending";
	
	/**
	 * RBAC Rolen
	 */
	const ROLE_USERADMIN		= "UserAdmin";
	const ROLE_GROUPADMIN		= "GroupAdmin";
	const ROLE_DOCUMENTADMIN	= "DocumentAdmin";
	const ROLE_ADMIN			= "Admin";
	const ROLE_SUPERADMIN		= "SuperAdmin";
	const ROLE_ANWENDER			= "Normal";
	
	/**
	 * IP Überprüfung aktivieren
	 */	
	const CHECKIP		= false;
	
	/**
	 * IP Range Überprüfung aktivieren
	 */
	const CHECKIPRANGE	= false;
	
	/**
	 * Login erzwingen aktivieren
	 */
	const FORCELOGIN	= true;

	
    /**
     * @inheritdoc
     */
    public static function CollectionName()
    {
		return ['yii2', 'user'];
    }

    /**
     * @inheritdoc
     
    public function behaviors()
    {
        return [
            'class' => \app\components\MongoDateBehavior::className(),
        ];
    }*/
	
	/**
     * @inheritdoc
     */
	public function attributes() {
		return [
			'_id',
			'username',
			'fullname',
			'password',
			'status',
			'password_hash',
			'email',
			'auth_key',
			'created_at',
			'updated_at',
			
			'license',
			'auth',
			'adress',
			'customerID',
			'type',
			'role',
			
			'notifications',
			'auth',
		];
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['username','email'],'required','message'=>'{attribute} ist erforderlich'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'default', 'value' => self::ROLE_ANWENDER],
			[[
				'_id',
				'username',
				'fullname',
				'status',
				'role',
				'email',
				'auth',
				'adress',
				'customerID',
				'notifications',
			],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id){
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

	public function getRole(){
		return $this->role;
	}
	
	/**
	 * Prüfung auf SingleIP
	 * @return type
	 * 
	 * @author KAS <kassel@weitkamper.de> 01.06.2015
	 */
	public static function checkIp(  ){
		$remoteIP = self::convertIP2String(Yii::$app->getRequest()->getUserIP());
		return static::hasSingleIp($remoteIP);
	}
	
	/**
	 * Prüfung auf IP Range
	 * @return type
	 * 
	 * @author KAS <kassel@weitkamper.de> 01.06.2015
	 */
	public static function checkIpRange(  ){
		$remoteIP = static::convertIP2String(Yii::$app->getRequest()->getUserIP());
		return static::hasIpRange($remoteIP);
	}
	
	/**
	 * Umwandlung einer IP einen String 127.000.000.001
	 * @param type $ip
	 * @return string
	 * 
	 * @author KAS <kassel@weitkamper.de> 01.06.2015
	 */	
	public static function convertIP2String($ip=''){
		$res	= preg_split("/\./",$ip);
		$newip  = sprintf("%03d",$res[0]).".".sprintf("%03d",$res[1]).".".sprintf("%03d",$res[2]).".".sprintf("%03d",$res[3]);
		return $newip;
	}
	
	/**
	 * Mongo Query auf die IP
	 * 
	 * @param type $remoteIP
	 * @return \common\models\User
	 * 
	 * @author KAS <kassel@weitkamper.de> 01.06.2015
	 */
	public static function hasSingleIp($remoteIP){
		$query = array(
			#'status'=>'active',
			#'license.license_name'=>$licName,
			#'license.status'=>'active',
			'auth'=>array(
				'$elemMatch'=>array(
					'type'=>'ip',
					'allowFrom'=>$remoteIP
				)
			)
		);
		$res = self::findOne($query);
		if($res instanceof User){
			return $res;
		}else{
			Yii::info('no IP for:'.$remoteIP);
			return NULL;
		}
	}
	
	/**
	 * Mongo Query auf IP Range 
	 * @param type $remoteIP
	 * @return \common\models\User
	 * 
	 * @author KAS <kassel@weitkamper.de> 01.06.2015
	 */
	public static function hasIpRange($remoteIP){
		$query = array(
			'status'=>'active',
			#'license.license_name'=>$licName,
			#'license.status'=>'active',
			'auth'=>array(
				'$elemMatch'=>array(
					'type'=>'iprange',
					'allowFrom'=>array('$lte'=>$remoteIP),
					'allowTo'=>array('$gte'=>$remoteIP),
				)
			)

		);
		$res = self::findOne($query);
		if($res instanceof User){
			return $res;
		}else{
			Yii::info('no IPRange for:'.$remoteIP);
			return NULL;
		}
	}
	
	/**
	 * Erzeugt Datum als String
	 * @return string
	 */
	public function getCreatedAtStr(){
		\Yii::$app->formatter->locale = 'de_DE';
		return \Yii::$app->formatter->asDate($this->created_at->sec,"php:d.m.Y H:i");
	}
	
	/**
	 * Update Datum als String
	 * @return string
	 */
	public function getUpdatedAtStr(){
		\Yii::$app->formatter->locale = 'de_DE';
		return \Yii::$app->formatter->asDate($this->updated_at->sec,"php:d.m.Y H:i");
	}
	
	/**
	 * Werte fuer den status
	 * 
	 * @return array
	 */
	public function getStatusValues(){
		return [
			'active'=>'aktiv',
			'pending'=>'wartend',
			'locked'=>'gesperrt',
			'new'=>'neu',
		];
	}
	
	/**
	 * Werte fuer die Role
	 * 
	 * @return array
	 */
	public function getRoleValues(){
		return [
			'Superadmin'=>'SuperAdmin',
			'Admin'=>'Administrator',
			'DocumentAdmin'=>'Dokumenten Admin',
			'UserAdmin'=>'Benutzer Admin',
			'Normal'=>'Anwender',
			
		];
	}
	
}
