<?php
use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form kartik\helpers\Html */
$form = Html::beginForm();
?>
<div class="modal fade" id="publish-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<?php
		echo Html::panel([
			'heading' => '<i class="fa fa-cloud-upload"></i>&nbsp;Daten publizieren',
			'headingTitle' => true,
			'body' =>Html::checkbox('sendNotification',false,['label'=>'Benachrichtigung an die Benutzer senden?']),
			'footer'=>"&nbsp;".Html::button('Abbrechen',['class'=>'btn btn-default btn-sm','data-dismiss'=>'modal'])."&nbsp;".Html::button('Daten jetzt publizieren',['class'=>'btn btn-info btn-sm','data-dismiss'=>'modal']),
		]);
		?>
	</div>
</div>
<?php
Html::endForm();