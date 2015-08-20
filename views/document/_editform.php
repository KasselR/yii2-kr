<?php
use yii\bootstrap\ActiveForm;
use kartik\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ExportForm */
?>
<?php

?>
<div class="col-md-12">
<?php
$form = ActiveForm::begin([
		'id' => 'doc-form',
		'layout' => 'horizontal',
		'method'=>'POST',
		'enableClientValidation'=>true,
]);
echo $form->errorSummary($model);
?>
	<div class="row">
		<div class="col-md-12">
			<?php 
			$form->fieldConfig = [
				'horizontalCssClasses' => [
					'label' => 'col-md-2',
					'wrapper' => 'col-md-10',
				],
			];
			?>
			<?=$form->field($model, 'metadata[TITEL]'	)->textInput()->label("Titel");?>
			<?=$form->field($model, 'metadata[UTITEL]'	)->textInput()->label("Untertitel");?>
			<?=$form->field($model, 'metadata[THEMA]'	)->textInput()->label("Thema");?>
		</div>
	</div>
	
	<div class="row">
		<?php 
			$form->fieldConfig = [
				'horizontalCssClasses' => [
					'label' => 'col-md-3',
					'wrapper' => 'col-md-9',
				],
			];
			?>
		<div class="col-md-6">
			<?php
			echo $form->field($model, 'metadata[RUBRIK]'			)->textInput()->label("Rubrik");
			echo $form->field($model, 'metadata[UNTERRUBRIK]'		)->textInput()->label("Unterrubrik");
			echo $form->field($model, 'metadata[AUTORSTR]'			)->textarea(['rows'=>5,'name'=>'Documents[metadata][AUTOR]'])->label("Autor/en");
			echo $form->field($model, 'metadata[ARTIKELAUTORSTR]'	)->textarea(['rows'=>5,'name'=>'Documents[metadata][ARTIKELAUTOR]'])->label("Artikelautor/en");
			#echo $form->field($model, 'metadata[SCHLUESSELBEGRIFFESTR]')->textarea(['rows'=>5])->label("Schlüsselbegriffe");
			?>
		</div>	
		<div class="col-md-6">
			<?=$form->field($model, 'metadata[JAHR_HEFT]')->textInput()->label("Jahr/Heft");?>
			<?=$form->field($model, 'metadata[HEFTNR]'	)->textInput()->label("Heft Nr.");?>
			<?=$form->field($model, 'metadata[FORTNR]'	)->textInput()->label("Fort Nr.");?>
			<?=$form->field($model, 'metadata[VOLUME]'	)->textInput()->label("Volume");?>
			<?=$form->field($model, 'metadata[ISSN]'	)->textInput()->label("Issn");?>
			<?=$form->field($model, 'metadata[JAHR]'	)->textInput()->label('Jahr');?>
			<?=$form->field($model, 'metadata[MONAT]'	)->textInput()->label("Monat");?>
			<?=$form->field($model, 'metadata[TAG]'		)->textInput()->label("Tag");?>
			<?=$form->field($model, 'metadata[SEITEVON]')->textInput()->label("Seite von");?>
			<?=$form->field($model, 'metadata[SEITEBIS]')->textInput()->label("Seite bis");?>
			<?=$form->field($model, 'metadata[SEITENNR]')->textInput()->label("Seiten Nr.");?>
			<?=$form->field($model, 'metadata[ZSTITEL]' )->textInput()->label("Zeitschrift");?>
			
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-12">
			<?php 
			$form->fieldConfig = [
				'horizontalCssClasses' => [
					'label' => 'col-md-2',
					'wrapper' => 'col-md-10',
				],
			];
			?>
			<?=$form->field($model, 'metadata[ZUSAMMENFASSUNG]'	)->textarea(['rows'=>5])->label("Zusammenfassung");?>
			<?=$form->field($model, 'metadata[SUMMARY]'			)->textarea(['rows'=>5])->label("Summary");?>
			<?=$form->field($model, 'metadata[RESUME]'			)->textarea(['rows'=>5])->label("Resumee");?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?= Html::button('<i class="fa fa-save"></i>&nbsp;Änderungen speichern', ['type'=>'submit','class' => 'btn btn-info','style'=>"width:100%;",'encodeLabels' => false,]) ?>
		</div>
	</div>
<?php ActiveForm::end();?>
</div>

