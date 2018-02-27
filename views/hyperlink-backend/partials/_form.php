<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \asinfotrack\yii2\hyperlinks\models\Hyperlink */

$module = \asinfotrack\yii2\hyperlinks\Module::getInstance();
$form = ActiveForm::begin([
	'enableClientValidation'=>$module->backendEnableClientValidation,
	'enableAjaxValidation'=>$module->backendEnableAjaxValidation,
]);
?>

<?= $form->errorSummary($model); ?>

<fieldset>
	<legend><?= Yii::t('app', 'Hyperlink information') ?></legend>
	<?= $form->field($model, 'title')->textInput(['maxlength'=>true]) ?>
	<?= $form->field($model, 'desc')->textarea(['rows'=>5]) ?>
</fieldset>

<fieldset>
	<legend><?= Yii::t('app', 'Hyperlink data') ?></legend>
	<?= $this->render('_form_callback_input', [
		'form'=>$form, 'model'=>$model, 'attribute'=>'url', 'callback'=>$module->urlInputCallback,
	]) ?>
</fieldset>

<fieldset>
	<legend><?= Yii::t('app', 'Settings') ?></legend>
	<?= $form->field($model, 'is_new_tab')->checkbox() ?>
</fieldset>

<div class="form-group">
	<?= Html::submitButton(Yii::t('app', 'Save'), ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
