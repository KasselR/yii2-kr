<?php
namespace app\components;

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
/**
 * XActionColumn erweitert die ActionColumn um ein Filterfeld mit Reset Link
 *
 * @author KAS <kassel@weitkamper.de> 30.07.2015
 */
class XActionColumn extends ActionColumn{
	
	/**
	 * @inherit
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * Wir rendern einen Reset Link als Button im filterfeld des GridViews
	 * @return string
	 * @author KAS <kassel@weitkamper.de> 30.07.2015
	 */
	public function renderFilterCellContent(){
		return Html::a(
				'<i class="fa fa-close"></i>',
				Url::toRoute("document/index"),
				[
					'class'=>'btn btn-default btn-xs',
					'data-toggle'=>'tooltip',
					'title'=>'Filter löschen',
					'id'=>'filter-reset'
				]
		);
	}
}
