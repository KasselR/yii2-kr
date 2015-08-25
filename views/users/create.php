<?php
use kartik\helpers\Html;
use frontend\widgets\Alert;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(\Yii::$app->session->hasFlash('success')){
	$preBody = Alert::widget();
}else{
	$preBody = NULL;
}
$btnAuth = Html::button('<i class="fa fa-plus"></i>&nbsp; Autorisierung hinzufügen',['class'=>'btn btn-warning btn-xs pull-right','id'=>'btn-newauth']);
$btnLic  = Html::button('<i class="fa fa-plus"></i>&nbsp; Lizenz hinzufügen',['class'=>'btn btn-warning btn-xs pull-right','id'=>'btn-newlic']);
echo Html::panel([
	'heading' =>"&nbsp;".$btnLic.$btnAuth,
	'preBody'=>$preBody,
	'body' => $this->render('_usersform',[
		'model'=>$model
	])
],'default',['id'=>'detail-panel',]);