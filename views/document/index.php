<?php
use kartik\helpers\Html;
use yii\grid\GridView;
#use backend\models\Zstitel;
use yii\widgets\Pjax;
use app\components\XActionColumn;

$zs = [];#new Zstitel;
Pjax::begin(['id'=>'docpicker-pjax']);
echo Html::panel([
	'heading' => 'Summary',
	'headingTitle' => true,
	'body' => GridView::widget([
		'dataProvider'=>$model,
		'filterModel'=>$searchModel,
		'tableOptions'=>['class'=>'table table-condensed table-bordered table-hover'],
		'summary'=>"<div class='summary'>{begin}-{end} von {totalCount} Dokumenten gefiltert</div>",
		'pager'=>[
			'options'=>[
				'class'=>'pagination pagination-sm'
			]
		],
		'columns'=>[
			'cb'=>[
				'class' => 'yii\grid\CheckboxColumn',
				'options'=>['style'=>'width:20px;'],
				'multiple'=>false,
				
			],
			'eye'=>[
				'class' => 'app\components\XActionColumn',
				'options'=>['style'=>'width:20px'],
				'buttonOptions'=>['style'=>'color:green;'],
				'template'=>'{view}',
			],
			'f'=>[
				'label'=>'Dateiname',
				'attribute'=>'filename',
				'value'=>'filenamePlain',
				'enableSorting'=>true,
				'filter'=>Html::textInput('filename',Yii::$app->request->get('filename'),['class'=>'form-control input-sm','placeholder'=>'*Filter']),
				'options'=>['style'=>'width:240px;']
			],
			#'metadata.ZSTITEL'=>[
			#	'label'=>'Zeitschrift',
			#	'attribute'=>'metadata.ZSTITEL',
			#	'filter'=>Html::dropDownList('zs',  Yii::$app->request->get('zs'),  $zs->reverseFields(),['class'=>'form-control input-sm','prompt'=>'- Filter -']),
			#	'options'=>['style'=>'width:140px;']
			#],
			'titel'=>[
				'label'=>'Titel',
				'attribute'=>'metadata.TITEL',
				'filter'=>Html::textInput('titel',Yii::$app->request->get('titel'),['class'=>'form-control input-sm','placeholder'=>'*Filter']),
				'enableSorting'=>true,
			],
			'groups'=>[
				'label'=>'Gruppe/n',
				'attribute'=>'groupstr',
				'filter'=>Html::dropDownList('group',  Yii::$app->request->get('group'),  app\models\Documents::getAllgroups(),['class'=>'form-control input-sm','prompt'=>'- Filter -']),
				'enableSorting'=>true,
				'options'=>['style'=>'width:110px;']
			],
			'status'=>[
				'label'=>'akt. Status',
				'attribute'=>'onlineStatus',
				'value'=>'onlineStatus_str',
				'options'=>['style'=>'width:80px;'],
				'filter'=>Html::dropDownList('onlineStatus',  Yii::$app->request->get('onlineStatus'),['true'=>'Online','false'=>'Offline'],['class'=>'form-control input-sm','prompt'=>'- Filter -']),
				'contentOptions'=>['style'=>'text-align:center;'],
				'enableSorting'=>true,
				'format'=>'raw',
			],	
			[
				'class' => 'yii\grid\ActionColumn',
				'content'=>'foo',
				'template'=>'{edit}&nbsp;{delete}',
				'filterOptions'=>['style'=>'text-align:right;'],
				'contentOptions'=>['style'=>'text-align:right;width:40px;'],
				'buttons'=>[
					'edit'=>function($url, $model, $key){
						return Html::a("<i class='fa fa-pencil'></i>",$url,['data-class'=>'edit']);
					}
				]
			]
		],			
	]),
	'footer'=>'<small><i>* mit Tabulator/Enter Taste Filter bestätigen</i></small>'					
						
],'default',['style'=>'font-size:12px;','id'=>'docpicker']);
Pjax::end();
//
// Verkleinern der Filterfelder und ausblenden der Summary ---------------------
$css = <<<CSS
#docpicker .input-sm{
	height: 23px;	
}
#docpicker select.input-sm{
	height: 23px;
	padding: 3px 3px;	
}
.summary{
	display:none;	
}
#docpicker table thead tr,
#docpicker table thead th{
	border:0px;
}
CSS;
$this->registerCss($css);
//
// setzen der Summary in den Panel-Title----------------------------------------
$js = <<<JS
$(document).on('ready pjax:complete',function(){
	var html = $('div.grid-view .summary').html();
	$('#docpicker .panel-title').html(html);	
});		
JS;
$this->registerJs($js);
		