<?php
use yii\helpers\Url;
use asinfotrack\yii2\toolbox\widgets\Button;

/* @var $this \yii\web\View */
/* @var $model \asinfotrack\yii2\hyperlinks\models\Hyperlink */

$this->title = Yii::t('app', 'Update hyperlink');
?>

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
	'icon'=>'eye',
	'label'=>Yii::t('app', 'Hyperlink details'),
	'options'=>[
		'href'=>Url::to(['hyperlink-backend/view', 'id'=>$model->id]),
		'class'=>'btn btn-primary',
	],
]) ?>

<?= $this->render('partials/_form', ['model'=>$model]) ?>
