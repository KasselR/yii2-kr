<?php
use kartik\helpers\Html;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

$btnTest = Html::button('Funktion',['class'=>'btn btn-default btn-xs pull-right']);
$btnTest2= Html::button('Funktion2',['class'=>'btn btn-default btn-xs pull-right','id'=>'btn2']);
#Pjax::begin(['id'=>'userlist-pjax']);
echo Html::panel([
			'heading' => false,
			'body' => GridView::widget([
				'dataProvider'=>$model,
				'pager'=>[
					'options'=>[
						'class'=>'pagination pagination-sm'
					]
				],
				'layout'=>"{items}\n{pager}{summary}",
				'columns'=>[
					'fullname',
					'username',
					'email',
					'status',
					'role',
					[
						'class' => 'yii\grid\DataColumn',
						'attribute'=>'created_at',
						'value'=>function($data){return date("d.m.Y",$data->created_at->sec);},
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template'=>'{show}&nbsp;{delete}',
						'contentOptions'=>['style'=>'text-align:right;'],
						'buttons'=>[
							'show'=>function($url, $model, $key){
								return Html::a("<i class='fa fa-search'></i>",$url,['data-class'=>'show','data-pjax'=>0]);
							
							}
						]
					]
				]
			]),
			'footer'=>false,
		],null,['id'=>'gridview-panel','style'=>'font-size:12px;']
);
#Pjax::end();						
?>
<div class="row">
	<div class="col-md-12" id="user-detail">
		<?=	Html::panel([
			'heading' => '&nbsp;'.$btnTest2.$btnTest,
			'body' => '<i class="fa fa-5x fa-table"></i>',
		],null,['id'=>'placeholder-panel',]);
		?>
		
	</div>
</div>
<?php
// HereDoc Syntax (nach <<< Name darf kein Zeichen sein) -----------------------
$script = <<< JS
$('#btn2').on('click',function(){
	alert('clicked');
});
$('a[data-class=show]').on('click',function(){
		var url = $(this).attr('href');
		$.ajax({
			url:url,
			dataType:'JSON',
			success:function(data){
				$('#user-detail').html(data);
			}
		});
	return false;
});
JS;
$this->registerJs($script);