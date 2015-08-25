<?php
use yii\bootstrap\ActiveForm;
use kartik\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ExportForm */
?>
<div class="col-md-12">
<?php
$form = ActiveForm::begin([
		'id' => 'user-form',
		'layout' => 'horizontal',
		'method'=>'POST',
		#'enableAjaxValidation'=>false,
		#'enableClientValidation'=>false,
]);
echo $form->errorSummary($model);
?>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($model, 'fullname'	)->textInput();?>
			<?=$form->field($model, 'username'	)->textInput();?>
			<?=$form->field($model, 'email'		)->textInput();?>
			<?=$form->field($model, 'password'	)->textInput();?>
			<?=$form->field($model, 'notifications'	)->dropDownList(['0'=>'Aus','1'=>'Ein']);?>
		</div>	
		<div class="col-md-6">
			<?=$form->field($model, 'customerID')->textInput(['disabled'=>($model->isNewRecord)? false:true]);?>
			<?=$form->field($model, 'status')->dropDownList($model->getStatusValues());?>
			<?=$form->field($model, 'created_at')->textInput(['value'=>$model->createdAtStr]);?>
			<?=$form->field($model, 'updated_at')->textInput(['value'=>$model->updatedAtStr,'disabled'=>($model->isNewRecord)? false:true]);?>
			<?=$form->field($model, 'role')->dropDownList($model->getRoleValues());?>
		</div>
	</div>
	<?php echo $this->render('_authform',['form'=>$form,'model'=>$model]); ?>
	<div class="row">
		<div class="col-md-12">
		<?= Html::button('<i class="fa fa-save"></i>&nbsp;Benutzer speichern', ['type'=>'submit','class' => 'btn btn-default','style'=>"width:100%;",'encodeLabels' => false,]) ?>
	</div>
	</div>
<?php ActiveForm::end();?>
</div>

