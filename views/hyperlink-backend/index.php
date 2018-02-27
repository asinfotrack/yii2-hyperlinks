<?php
use yii\grid\GridView;
use asinfotrack\yii2\toolbox\widgets\grid\BooleanColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedActionColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedDataColumn;
use asinfotrack\yii2\toolbox\widgets\grid\IdColumn;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \asinfotrack\yii2\hyperlinks\models\search\HyperlinkSearch */

$this->title = Yii::t('app', 'Hyperlinks');
?>

<?= GridView::widget([
	'dataProvider'=>$dataProvider,
	'filterModel'=>$searchModel,
	'columns'=>[
		[
			'class'=>IdColumn::className(),
			'attribute'=>'id',
		],
		[
			'class'=>AdvancedDataColumn::className(),
			'attribute'=>'title',
			'columnWidth'=>20,
		],
		[
			'class'=>AdvancedDataColumn::className(),
			'attribute'=>'url',
			'format'=>'url',
		],
		'title',
		[
			'class'=>BooleanColumn::className(),
			'attribute'=>'is_new_tab',
		],
		[
			'class'=>AdvancedActionColumn::className(),
		],
	],
]); ?>
