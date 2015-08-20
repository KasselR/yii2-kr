<?php 
use kartik\helpers\Html;

$this->beginContent('@app/views/layouts/main.php'); 
?>
<!-- Left / Content Rows Layout -->
<h2><?= $this->params['headline']; ?></h2>
<div class="col-md-12">
	<?= $content ?>
</div>
<?php $this->endContent(); ?>

