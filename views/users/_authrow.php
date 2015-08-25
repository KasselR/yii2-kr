<?php
use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$form = ActiveForm::begin([
	'id' => 'user-form',
	'layout' => 'inline',
	'method'=>'POST',
	#'enableAjaxValidation'=>true,
	#'enableClientValidation'=>true,
]);
$ka = $rowNr;
echo Html::beginTag('table');
	echo Html::beginTag('tr',['data-row'=>'1']);
		echo Html::tag('td',$form->field($auth, '['.$ka.']type'		)->dropDownList($auth->getTypeArr(),['style'=>'width:120px;','prompt'=>'--']));
		echo Html::tag('td',$form->field($auth, '['.$ka.']allowFrom')->textInput(['style'=>'width:120px;']));
		echo Html::tag('td',$form->field($auth, '['.$ka.']allowTo'	)->textInput(['style'=>'width:120px;']));
	echo Html::endTag('tr');
echo Html::endTag('table');
$form->end();