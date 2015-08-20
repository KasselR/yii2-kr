<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
#use yii\widgets\Breadcrumbs;
use kartik\sidenav\SideNav;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/fontawesome/css/font-awesome.css" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="background-color: #ddd;">
	<div class="wrap">
		 
    <?php $this->beginBody() ?>
        <?php
			$fullname = NULL;
			if(!Yii::$app->user->isGuest){
				$fullname= Yii::$app->user->getIdentity()->fullname;
			}
            NavBar::begin([
                'brandLabel' => 'XePS - Suite',
                'brandUrl' => yii\helpers\Url::toRoute(Yii::$app->defaultRoute),
				'renderInnerContainer' => false,
				'containerOptions'=>[
					'class'=>'',
				],
                'options' => [
                    'class' => 'navbar navbar-inverse ',
                ],
            ]);
            $menuItems = [
				#'<li class="nav-comments"><button type="button" onclick="" class="btn btn-comments" title="Comments for this page"><span class="icon-animated-vertical"><i class="fa fa-lg fa-envelope-o"></i></span> <span class="badge">1</span></button></li>',
				#['label'=>'Frontend','url'=>'http://yii2.local'],
                #['label' => '<span class="fa fa-user"></span>&nbsp;(' . $fullname . ')',
				#	'items'=>[
				#		['label'=>'<span class="fa fa-sign-out"></span>&nbsp;Abmelden','url'=>['site/logout'],'linkOptions' => ['data-method' => 'post']],
				#	],
				#	'visible'=>!Yii::$app->user->isGuest,
				#],
				['label'=>'<span class="fa fa-sign-in"></span>&nbsp;Anmelden','url'=>['site/login'],'visible'=>Yii::$app->user->isGuest]
            ];
			
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
				'encodeLabels'=>false,
                'items' => $menuItems,
            ]);
//			echo'<div><form class="navbar-form navbar-right" action="" method="POST" id="search-form">
//				<div class="input-group has-feedback">
//					<input type="text" class="form-control" placeholder="Suchbegriff" id="hitengine" name="query" value=""/>
//					<input type="hidden" class="form-control" name="h" value="10" />
//					<span class="input-group-btn" style="width:18%;">
//						<button class="btn btn-info" type="submit" id="btn-search"><i class="fa fa-search"></i></button>
//					</span>
//				</div>
//			</form></div>';
            NavBar::end();
        ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<?= $content ?>
			</div>
		</div>
	</div>
	</div> <!-- warp -->
    <footer class="footer">
        <div class="container-fluid">
			<p class="pull-left">
				&copy; XePS Publishing System: <a href="http://www.weitkamper.de" target="_blank">Weitkämper Technology GmbH</a>
			</p>
        </div>
    </footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
