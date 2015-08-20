<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\sidenav\SideNav;

#use githubjeka\rbac\assets\AppAsset;

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
    <title><?= Html::encode(\Yii::$app->name) ?></title>
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
                #'brandLabel' => 'XePS - Suite',
				'brandLabel' => '<img src="images/XSEARCHePS_black.png" style="height:40px;" />',
                'brandUrl' => yii\helpers\Url::toRoute(Yii::$app->defaultRoute),
				'renderInnerContainer' => false,
				'containerOptions'=>[
					'class'=>'',
				],
                'options' => [
                    'class' => 'navbar navbar-inverse',
                ],
            ]);
            $menuItems = [
				'<li class="nav-comments"><button type="button" onclick="" class="btn btn-comments" title="Comments for this page"><span class="icon-animated-vertical"><i class="fa fa-lg fa-envelope-o"></i></span> <span class="badge">1</span></button></li>',
				['label'=>'Frontend','url'=>'http://yii2.local','visible'=>!Yii::$app->user->isGuest],
                ['label' => '<span class="fa fa-user"></span>&nbsp;(' . $fullname . ')',
					'items'=>[
						['label'=>'<span class="fa fa-sign-out"></span>&nbsp;Abmelden','url'=>['site/logout'],'linkOptions' => ['data-method' => 'post']],
						#['label'=>'<span class="fa fa-sign-out"></span>&nbsp;API','url'=>Url::to('@api/index.html','file://C'),'linkOptions' => ['target'=>'_blank']],
					],
					'visible'=>!Yii::$app->user->isGuest,
				],
				['label'=>'<span class="fa fa-sign-in"></span>&nbsp;Anmelden','url'=>['site/login'],'visible'=>Yii::$app->user->isGuest]
            ];
			
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
				'encodeLabels'=>false,
                'items' => $menuItems,
            ]);
			echo'<div><form class="navbar-form navbar-right" action="" method="POST" id="search-form">
				<div class="input-group has-feedback">
					<input type="text" class="form-control" placeholder="Suchbegriff" id="hitengine" name="query" value=""/>
					<input type="hidden" class="form-control" name="h" value="10" />
					<span class="input-group-btn" style="width:18%;">
						<button class="btn btn-info" type="submit" id="btn-search"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form></div>';
            NavBar::end();
        ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 sidebar" >
				<?php 
				$item='';
				$userItems = [
					['label'=>'Benutzer Verwalten',	'url'=>['/users/list']],
					['label'=>'Benutzer Anlegen',	'url'=>['/users/create']],
				];
				$dokItems = [
					['label'=>'Dokumente Verwalten','url'=>['document/index']],
					// Edit zeigen wir nur an, wenn wir auch was editieren -----
					['label'=>'Dokumente Bearbeiten','url'=>['document/edit'],'visible'=>$this->context->action->id === "edit"],
					//----------------------------------------------------------
					['label'=>'Gruppen Verwalten',	'url'=>''],
					['label'=>'Upload',				'url'=>''],
				];
				$blogItems = [
					['label'=>'Blog Verwalten',		'url'=>['/blogs/list']],
					['label'=>'Eintrag Erstellen',	'url'=>['/blogs/create']],
					['label'=>'Eintrag bearbeiten',	'url'=>['/blogs/edit'],'visible'=>$this->context->action->id === "edit"],
				];
				$licItems = [
					['label'=>'Lizenzen Verwalten',	'url'=>''],
					#['label'=>'Lizenz 2','url'=>''],
					#['label'=>'Lizenz 3','url'=>''],
				];
				$priceItems = [
					['label'=>'Preise Verwalten','url'=>''],
					#['label'=>'Preis 2','url'=>''],
					#['label'=>'Preis 3','url'=>''],
				];
				$settingItems = [
					['label'=>'PDF Sicherheit',		'url'=>''],
					['label'=>'Metadaten Felder',	'url'=>''],
					['label'=>'<span class="pull-right badge">5</span> Logs', 'url'=>'','active' => ($item == 'Logs')],
					['label'=>'RBAC', 'url'=>['/rbac']],
				];
				$markItems = [
					['label'=>'Kampagnen Verwalten','url'=>''],
					#['label'=>'Metadaten Felder','url'=>''],
					#['label'=>'Preis 3','url'=>''],
				];
				$xItems = [
					['label'=>'Batch','url'=>['/xsearch/batch']],
					['label'=>'Export','url'=>['/xsearch/export']],
					#['label'=>'Preis 3','url'=>''],
				];
				$statItems = [
					['label'=>'Standard','url'=>['/xstat/default/index']],
					['label'=>'Counter','url'=>['/xstat/counter/index']],
					['label'=>'Suchen','url'=>['/xstat/query/index']],
					#['label'=>'Preis 3','url'=>''],
				];
				echo SideNav::widget([
					'type' => SideNav::TYPE_DEFAULT,
					'heading' => '<i class="fa fa-cog"></i> Verwaltung',
					'encodeLabels'=>false,
					'items' => [
						['label'=>'<i class="fa fa-tachometer"></i>&nbsp;Übersicht','url'=>['/site/dashboard'],'visible'=>!\Yii::$app->user->isGuest,],
						['label'=>'<i class="fa fa-cloud-upload"></i>&nbsp;Publizierungen','url'=>['/document/publish'],'visible'=>!\Yii::$app->user->isGuest],
						['label'=>'<i class="fa fa-users"></i>&nbsp;Benutzer','items'=>$userItems,'visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Dokumente','items'=>$dokItems,	'icon'=>'file','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Lizenzen','items'=>$licItems,	'icon'=>'tags','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Preise','items'=>$priceItems,	'icon'=>'euro','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Blog','items'=>$blogItems,		'icon'=>'book','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Marketing','items'=>$markItems,	'icon'=>'road','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'X-Search','items'=>$xItems,		'icon'=>'search','visible'=>!\Yii::$app->user->isGuest],
						['label'=>'Statistik','items'=>$statItems,	'icon'=>'stats','visible'=>!\Yii::$app->user->isGuest],
						[],
						['label'=>'Einstellungen','items'=>$settingItems,		'icon'=>'cog','visible'=>!\Yii::$app->user->isGuest],
						
						['label'=>'Über uns','url'=>'',				'icon'=>'question-sign','visible'=>Yii::$app->user->isGuest],
						['label'=>'Impressum','url'=>'',			'icon'=>'info-sign','visible'=>Yii::$app->user->isGuest],
					],
					
				]);
				?>
				
			</div>
			<div class="col-md-10">
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
