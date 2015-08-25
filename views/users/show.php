<?php
use kartik\helpers\Html;

$btnTest = Html::button('<i class="fa fa-trash"></i>&nbsp;Löschen',['class'=>'btn btn-warning btn-xs pull-right']);

echo Html::panel([
	'heading' => '<i class="fa fa-user"></i>&nbsp;'.$model->fullname.$btnTest,
	'body' => $this->render('_usersform',[
		'model'=>$model
	])
],'default',['id'=>'detail-panel']);
?>
