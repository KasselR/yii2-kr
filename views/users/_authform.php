<?php
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ExportForm */
use kartik\helpers\Html;
use app\models\Auth;
$form->layout = 'inline';
$auth = new Auth();
$ka	= 0;
?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-responsive" id="authtable">
			<thead>
				<tr>
				<th style="width:200px;">Type</th>
				<th style="width:100px;">IP Von</th>
				<th style="width:170px;">IP Bis</th>
				<th style="width:170px;"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($model->isNewRecord === TRUE){
						$model->auth = [0];
					}
					// Anzeigen der gespeicherten Logins -------------------
					foreach($model->auth as $i => $authData){
						$auth = new Auth($authData);
						echo Html::beginTag('tr');
						echo Html::tag('td',$form->field($auth, '['.$i.']type'		)->dropDownList($auth->getTypeArr(),['style'=>'width:120px;','prompt'=>'--']));
						echo Html::tag('td',$form->field($auth, '['.$i.']allowFrom')->textInput(['style'=>'width:120px;']));
						echo Html::tag('td',$form->field($auth, '['.$i.']allowTo'	)->textInput(['style'=>'width:120px;']));
						echo Html::endTag('tr');
						#$ka++;
					}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
$js = <<<JS
var ka = "$ka";	
$(document).on('click','#btn-newauth',function(){
	ka++;	
	$.ajax({
		url:'index.php?r=users/newauthrow&rnr='+ka,
		dataType:'JSON',
		success:function(data){
			$('#authtable > tbody:last').append($(data).find("tr"));
			$('body').append($(data).filter('script:last'));		
		}
	});	
});
JS;
$this->registerJs($js);