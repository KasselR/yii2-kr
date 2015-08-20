<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use backend\models\Documents;
use backend\models\Metadata;

/**
 * Description of DocumentController
 *
 * @author KAS <kassel@weitkamper.de>
 */
class DocumentController extends Controller{
	
	public $layout = "rows";
	
	/**
	 * Dokumenten Verwaltung (DokPicker)
	 * 
	 * @return \yii\web\View
	 */
	public function actionIndex(){
		Yii::$app->view->params['headline'] = 'Dokumenten Verwaltung';
		
		$searchModel = new \backend\models\DocumentSearch();
		
		$model = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index',[
			'model'=>$model,
			'searchModel'=>$searchModel
		]);
	}
	
	/**
	 * Übersicht der Dokumente welche zur Veröffentlichung anstehen
	 * 
	 * @return \yii\web\View
	 */
	public function actionPublish(){
		error_reporting(E_ALL);
		Yii::$app->view->params['headline'] = 'Ausstehende Publizierungen';
		$model = new ActiveDataProvider([
			'query'=>Documents::find()->where(['regex','updated_by','/./'])->orderBy("filename"),
			'pagination' => [
				'pageSize' => 15,
			],
		]);
		
		return $this->render('publish',[
			'model'=>$model
		]);
	}
	
	/**
	 * Bearbeiten eines Dokuments (metadaten)
	 * 
	 * @param string $id
	 * @return \yii\web\View
	 * @author KAS <kassel@weitkamper.de> 06.08.2015
	 */
	public function actionEdit($id=NULL){
		Yii::$app->view->params['headline'] = 'Dokument bearbeiten';
		// Wir laden das gewählte Document
		$model = Documents::findOne(new \MongoId($id));
		// Wir machen aus dem Array ein Object ---------------------------------
		$model->metadata = new Metadata($model->metadata);
		// Nur wenn auch ein POST gesendet wurde -------------------------------
		if(\Yii::$app->request->isPost){
			$post = \Yii::$app->request->post();
			$meta = new Metadata($post['Documents']['metadata']);
			// Hier werden die Daten für Mongo angepasst(convertiert) ----------
			$meta->load($post['Documents']['metadata']);
			//------------------------------------------------------------------
			// wir belassen die originalen Metadaten
			$model->metadata    = $model->metadata->toArray();
			// Änderungen werden in metadatanew abgelegt
			$model->metadatanew = $meta->toArray();
			#var_dump($model);
			// Speichern der Daten 
			$model->update();
			// daten refreshen und neu zuordnen --------------------------------
			$model->refresh();
			$model->metadata = new Metadata($model->metadata);
		}
		//----------------------------------------------------------------------
		// Wenn bereits neue Daten vorhanden sind, 
		// laden wir diese für die Anzeige
		if($model->metadatanew !== NULL){
			$model->metadata = new Metadata($model->metadatanew);
		}
		return $this->render('edit',[
			'model'=>$model
		]);
	}
	
}
