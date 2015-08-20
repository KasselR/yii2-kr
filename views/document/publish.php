<?php
use kartik\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
$btnTest = Html::button('Dokumente publizieren',['class'=>'btn btn-warning btn-xs pull-right','id'=>'btn-publish']);
echo Html::panel([
			'heading' => 'Summary'.$btnTest,
			'headingTitle' => true,
			'body' => GridView::widget([
				'dataProvider'=> $model,
				'summary'=>"<div class='summary'><i class=\"fa fa-cloud-upload\"></i>&nbsp;{begin}-{end} von {totalCount} Dokumente können publiziert werden</div>",
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
					'filename'=>[
						'label'=>'Dateiname',
						'attribute'=>'filename',
						'value'=>'filenamePlain',
					],
					'Title'=>[
						'label'=>'Titel',
						'attribute'=>'Titel',
					],
					'f'=>[
						'label'=>'Bearbeitet durch:',
						'attribute'=>'updated_by',
						'options'=>['style'=>'width:140px;']
					],
					'updated_at'=>[
						'label'=>'Bearbeitet am:',
						'attribute'=>'updated_at',
						'value'=>'de_updated_at',
						'options'=>['style'=>'width:100px;']
					],
					'status'=>[
						'label'=>'akt. Status',
						'attribute'=>'onlineStatus',
						'value'=>'onlineStatus_str',
						'options'=>['style'=>'width:80px;'],
						'contentOptions'=>['style'=>'text-align:center;'],
						'format'=>'raw',
					],			
					[
						'class' => 'yii\grid\ActionColumn',
						'content'=>'foo',
						'template'=>'{edit}',
						'filterOptions'=>['style'=>'text-align:right;'],
						'contentOptions'=>['style'=>'text-align:right;width:45px;'],
						'buttons'=>[
							'edit'=>function($url, $model, $key){
								return Html::a("<i class='fa fa-pencil'></i>",$url,['data-class'=>'edit']);
							}
						]
					]
				],
			]),
			'footer'=>false,
		],'default',['style'=>'font-size:12px;','id'=>'publish-grid']);
		?>
<?php
// Publish Modal rendern (hidden) ----------------------------------------------
echo $this->render('_publishModal');
//
// HereDoc Syntax (nach <<< Name darf kein Zeichen sein) -----------------------
$js = <<<JS
$(document).on('ready pjax:complete',function(){
	var html = $('div.grid-view .summary').html();
	var btn = $('#publish-grid .panel-title button');
	$('#publish-grid .panel-title').html(html).append(btn);	
});
$(document).on('click','#btn-publish',function(){
	$('#publish-modal').modal();
});
JS;
$this->registerJs($js);
// Verkleinern der Filterfelder und ausblenden der Summary ---------------------
$css = <<<CSS
.summary{
	display:none;	
}
CSS;
$this->registerCss($css);