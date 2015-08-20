<?php
use kartik\helpers\Html;
use frontend\widgets\Alert;

if(\Yii::$app->session->hasFlash('success')){
	$preBody = Alert::widget();
}else{
	$preBody = $this->render('_articlePreview',['model'=>$model]);
}

$btnTest = Html::button('Artikel Vorschau',['class'=>'btn btn-info btn-xs pull-right','id'=>'btn-preview']);
echo Html::panel([
	'heading' =>"&nbsp;".$btnTest,
	'preBody'=>$preBody,
	'body' => $this->render('_editform',[
		'model'=>$model
	])
],'default',['id'=>'detail-panel',]);

$css = <<<CSS
#detail-panel input.form-control{
	height: 23px;	
}
#detail-panel select.input-sm{
	height: 23px;
	padding: 3px 3px;	
}
#detail-panel .form-group{
	margin-bottom:5px;
}
#detail-panel .panel-body {
    background-color: #EFEFEF;
}		
@media (min-width: 992px){
	#detail-panel .col-md-2 {
		width: 12.5%;
	}	
	#detail-panel .col-md-10{
		width: 87.5%;
   }	
}
CSS;
$this->registerCss($css);
$js = <<<JS
$('#btn-preview').on('click',function(){
	$('article').toggle();	
});		
JS;
$this->registerJs($js);