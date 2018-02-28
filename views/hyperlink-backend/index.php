<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use asinfotrack\yii2\toolbox\widgets\grid\BooleanColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedActionColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedDataColumn;
use asinfotrack\yii2\toolbox\widgets\grid\IdColumn;
use asinfotrack\yii2\hyperlinks\Module;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \asinfotrack\yii2\hyperlinks\models\search\HyperlinkSearch */

$this->title = Yii::t('app', 'Hyperlinks');

$typeFilter = [];
$query = call_user_func([Module::getInstance()->classMap['hyperlinkModel'], 'find']);
foreach ($query->select('model_type')->distinct(true)->column() as $type) {
	$typeFilter[$type] = StringHelper::basename($type);
}
array_multisort($typeFilter);
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
			'attribute'=>'subject',
			'columnWidth'=>15,
			'format'=>'html',
			'filter'=>$typeFilter,
			'value'=>function ($model, $key, $index, $column) {
				$lines = [
					Html::tag('span', StringHelper::basename($model->model_type)),
					Html::tag('code', Json::encode($model->foreign_pk))
				];
				return implode(Html::tag('br'), $lines);
			},
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
