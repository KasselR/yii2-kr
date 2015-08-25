<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;
use app\models\Auth;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\base\Model;

/**
 * Site controller
 */
class UsersController extends Controller
{
    public $layout = "rows";
	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','show', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['list','create','newauthrow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionList()
    {
        Yii::$app->view->params['headline'] = 'Benutzer Verwalten';
		// alle Benutzer holen -------------------------------------------------
		$model = new ActiveDataProvider([
			'query'=>User::find(),
			'pagination' => [
				'pageSize' => 5,
			],
		]);
		//----------------------------------------------------------------------
		return $this->render('list',[
			'model'=>$model
		]);
    }
	
	/**
	 * Ajax Call
	 * Zeigt die Details des Benutzer an
	 * 
	 * @param type $id
	 * 
	 * @author KAS <kassel@weitkamper.de> 17.06.2015
	 */
	public function actionShow($id) {
		#Yii::$app->view->params['headline'] = 'Benutzer Verwalten';
		// Daten des Users
		$model		= User::findIdentity($id);
		// Ansicht rendern im JSON Format
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return $this->renderAjax('show',[
			'model'=>$model
		]);
	}
	
	/**
	 * Anlegen eines Benutzers
	 * 
	 * @return \yii\web\View
	 * @author KAS <kassel@weitkamper.de> 28.07.2015
	 */	
    public function actionCreate()
    {
        Yii::$app->view->params['headline'] = 'Benutzer anlegen';
		$model = new User();
		//----------------------------------------------------------------------
		$post = \Yii::$app->request->post();
		if ($model->load($post)) {
			$authArr = [];
			foreach($post['Auth'] as $authData){
				$authArr[] = new Auth($authData);
			}
			// Daten Validieren und Zuordnen -----------------------------------
			if(Model::loadMultiple($authArr, $post) && Model::validateMultiple($authArr)){
				// aus den Auth Objekten machen wir arrays, 
				// damit wir das in die Mongo speichern können
				$model->auth = array_map(function($a){ 
					return $a->toArray();
				},$authArr);	
				// Speichern ---------------------------------------------------
				$model->save();
				// Benutzer benachrichtigen ------------------------------------
				\Yii::$app->session->setFlash('success','Benutzer wurde erfolgreich angelegt!', TRUE);
				// Neue Daten laden, da wir in den Models Veränderungen vornehmen
				$model->refresh();
			}
		}
		// Defaultwerte festlegen ----------------------------------------------
		$model->created_at = new \MongoDate();
		$model->updated_at = new \MongoDate();
		$model->role	   = "Normal";
		//----------------------------------------------------------------------
		return $this->render('create',[
			'model'=>$model
		]);
    }
	
	/**
	 * Ajax Call: Erzeugt eine neue Tabellenzeile mit den Formfelder
	 * 
	 * @param string $rnr RowNummer
	 * @return \yii\web\View
	 * @author KAS <kassel@weitkamper.de> 28.07.2015
	 * @todo Das JS welches die Validerung macht, wird zwar erzeugt und per JS 
	 * an den Body angehängt. Aber es findet keine Validierung statt! Prüfen warum?
	 * 
	 */
	public function actionNewauthrow($rnr=null){
		$ka = 1;
		$auth = new Auth();
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return $this->renderAjax('_authrow',[
			'auth'=>$auth,
			'rowNr'=>$rnr,
		]);
	}
	
    public function actionUser3()
    {
        Yii::$app->view->params['headline'] = 'Benutzer 3';
		return $this->render('user3');
    }
}
