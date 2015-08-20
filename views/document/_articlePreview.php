<?php
use yii\bootstrap\Button;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use kartik\helpers\Html;
use frontend\widgets\XButtonsRow;

/* @var $this yii\web\View */
/* @var $model frontend\models\Article */
$smallImg	= $model->getImage();
$bigImg		= $model->getImageBig();
?>
<article class="panel" style="display:none;">
	<div class="media" style="">
		<div class="media-left">
			<a href="#" data-imgpath="<?=$bigImg;?>" class='ximage' data-toggle='modal'>
				<?=Html::img($smallImg,['class'=>'pull-left','height'=>'180px']);?>
			</a>
		</div>
		<div class="media-body">
			<h2 style="font-size:1.1em;font-style: italic;margin:0px;line-height: 22px;" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">	
				<?=$model->getAutorstr()?>
			</h2>
			<h1 class="media-heading" style="margin-bottom:10px;font-size:1.33em;line-height:19.8px;margin-top:10px;"><?=$model->getTitel();?></h1>
			<h3 style="margin-top:0px;text-weight: bold;font-size:1.4em;"><?=$model->getSubtitel();?></h3>
			<h4 style="font-size:0.9em;margin:0px;"><?=$model->getArticleinfo();?></h4>
			<?php
			// Buttons erzeugen ------------------------------------------------
			echo XButtonsRow::widget([
				'containerOptions'=>['style'=>'position:relative; bottom:-20px;'],
				'items'=>[
					[
						'label'=>'PDF',
						'id'=>'download',
						'visible'=>(\Yii::$app->user->isGuest),
						'url'=>Url::toRoute(['xsearch/addbasket'])
					],
					[
						'label'=>'Download PDF',
						'id'=>'download',
						'visible'=>(\Yii::$app->user->isGuest),
						'url'=>Url::toRoute(['xsearch/addbasket']),
						'icon'=>'fa-download',
					],
					[
						'label'=>'Vorschau',
						'id'=>'preview',
						'visible'=>true,
						'icon'=>'fa-search',
						'url'=>''
					],
				],
				'btnOptions'=>[
					'style'=>'width:180px;'
				]
			]);
			//------------------------------------------------------------------
			?>
		</div>
	</div>
	<br/>
	<div class="abstract" id='tabs-detail'>
		<?=Tabs::widget([
			'options'=>['class'=>'nav-justified'],
			'items'=>[
				['label'=>'Zusammenfassung','content'=>$model->file->file['metadata']['ZUSAMMENFASSUNG'],'options'=>['class'=>'panel-body']],
				['label'=>'Summary','content'=>'Inhalt'],
				['label'=>'Resume','content'=>'Inhalt'],
			]
		]);?>
	</div>
</article>
<!-- modal-dialog -->
<div class="modal fade" id="photo-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<img src="" style="max-width: 550px;"/>
	</div>
</div>
<!-- /.modal-dialog -->
<?php
$this->registerJs("
$('a.ximage').click(function(e){
	e.preventDefault();
	var imgPath = $(this).data('imgpath');
	$('#photo-modal img').attr('src', imgPath);
	$('#photo-modal').modal('show');
});
// Schliessen des Modalfenster wenn das Bild offen ist ---------------------
$('img').on('click', function(){
	$('#photo-modal').modal('hide')
});	
");

?>