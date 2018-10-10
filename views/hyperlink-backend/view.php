<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\DetailView;
use asinfotrack\yii2\toolbox\widgets\Button;

/* @var $this \yii\web\View */
/* @var $model \asinfotrack\yii2\hyperlinks\models\Hyperlink */

$this->title = $model->displayTitle;
?>
<div class="buttons">
	<?= Button::widget([
		'tagName'=>'a',
		'icon'=>'list',
		'label'=>Yii::t('app', 'All hyperlinks'),
		'options'=>[
			'href'=>Url::to(['hyperlink-backend/index']),
			'class'=>'btn btn-primary',
		],
	]) ?>
	<?= Button::widget([
		'tagName'=>'a',
		'icon'=>'pencil',
		'label'=>Yii::t('app', 'Update hyperlink'),
		'options'=>[
			'href'=>Url::to(['hyperlink-backend/update', 'id'=>$model->id]),
			'class'=>'btn btn-primary',
		],
	]) ?>
</div>

<?= DetailView::widget([
	'model'=>$model,
	'attributes'=>[
		[
			'attribute'=>'id',
		],
		[
			'attribute'=>'subject',
			'format'=>'html',
			'value'=>implode(Html::tag('br'), [
				Html::tag('span', StringHelper::basename($model->model_type)),
				Html::tag('code', Json::encode($model->foreign_pk))
			]),
		],
		'url:url',
		'is_new_tab:boolean',
		'title',
		'description',
	],
]) ?>
