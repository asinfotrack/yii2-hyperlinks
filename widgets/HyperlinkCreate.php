<?php
namespace asinfotrack\yii2\hyperlinks\widgets;

use asinfotrack\yii2\hyperlinks\models\Hyperlink;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use rmrevin\yii\fontawesome\FA;
use asinfotrack\yii2\hyperlinks\Module;
use asinfotrack\yii2\toolbox\widgets\Button;

/**
 * The form required for hyperlinks, either as a regular form or wrapped
 * within a modal. There is also a method to generate the button to show
 * the modal
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license MIT
 */
class HyperlinkCreate extends \yii\base\Widget
{

	/**
	 * @var \yii\bootstrap\ActiveForm the form instance
	 */
	protected $form;

	/**
	 * @var \yii\bootstrap\Modal the modal instance
	 */
	protected $modal;

	/**
	 * @var \asinfotrack\yii2\hyperlinks\models\Hyperlink holds the actual hyperlink model
	 */
	public $model;

	/**
	 * @var \yii\db\ActiveRecord the subject for the hyperlink
	 */
	public $subject;

	/**
	 * @var string the form view to use
	 */
	public $formView = '@vendor/asinfotrack/yii2-hyperlinks/views/hyperlink-backend/partials/_form';

	/**
	 * @var bool whether or not to use a modal (defaults to true)
	 */
	public $useModal = true;

	/**
	 * @var string the if for the modal (if rendered with modal)
	 */
	public $modalId;

	/**
	 * @var bool  whether or not to show the modal immediately
	 */
	public $showModalImmediately = false;

	/**
	 * @var bool  whether or not to show categories
	 */
	public $showCategories = false;

	/**
	 * @var string the title of the modal
	 */
	public $modalTitle;

	/**
	 * @var string holds the content of the modal footer. Defaults to
	 * a regular submit-button for the form
	 */
	public $modalFooter;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		//validate model and subject
		if ($this->model === null || !($this->model instanceof Hyperlink)) {
			$msg = Yii::t('app', 'No or invalid hyperlink-model specified');
			throw new InvalidConfigException($msg);
		}
		if ($this->subject === null || !Module::validateSubject($this->subject)) {
			$msg = Yii::t('app', 'No or invalid subject specified');
			throw new InvalidConfigException($msg);
		}

		//set default settings
		if ($this->useModal) {
			if (empty($this->modalId)) {
				$subjectClass = StringHelper::basename($this->subject->className());
				$widgetClass = StringHelper::basename($this->className());
				$this->modalId = Inflector::camel2id($subjectClass . $widgetClass);
			}
			if (empty($this->modalTitle)) {
				$this->modalTitle = FA::icon('paperclip') . Html::tag('span', Yii::t('app', 'Create hyperlink'));
			}
			if (empty($this->modalFooter)) {
				$btn = Html::submitButton(Yii::t('app', 'Save'), ['class'=>'btn btn-primary']);
				$this->modalFooter = $btn;
			}
		}
	}

	/**
	 * @inheritdoc
	 * @param bool $showCategories
	 */
	public function run()
	{
		if ($this->useModal) $this->renderModalBegin();
		echo $this->renderContent();
		if ($this->useModal) $this->renderModalEnd();
	}

	/**
	 * Generates the trigger-button for the modal
	 *
	 * @param string $label optional label, defaults to modal title
	 * @param array $options options for the button
	 * @return string the html-code of the trigger-button
	 */
	public function generateShowModalButton($label=null, $options=[]) {
		Html::addCssClass($options, 'btn-primary');
		$options['data']['toggle'] = 'modal';
		$options['data']['target'] = '#'.$this->modalId;

		return Button::widget([
			'icon'=>'paperclip',
			'label'=>$label === null ? Yii::t('app', 'Create hyperlink') : $label,
			'encodeLabel'=>false,
			'options'=>$options,
		]);
	}

	/**
	 * Renders the actual form
	 *
	 * @return string the content of the modal
	 */
	protected function renderContent()
	{
		return $this->view->render($this->formView, [
			'model'=>$this->model,
		]);
	}

	/**
	 * Begins and configures the modal
	 */
	protected function renderModalBegin()
	{
		$modalOptions = [];
		$modalClientOptions = $this->showModalImmediately ? ['show'=>true] : [];

		$this->modal = Modal::begin([
			'id'=>$this->modalId,
			'options'=>$modalOptions,
			'clientOptions'=>$modalClientOptions,
			'header'=>Html::tag('h4', $this->modalTitle),
			'footer'=>$this->modalFooter,
		]);
	}

	/**
	 * Ends the modal
	 */
	protected function renderModalEnd()
	{
		$this->modal->end();
	}

}
