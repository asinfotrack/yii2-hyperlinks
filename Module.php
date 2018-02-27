<?php
namespace asinfotrack\yii2\hyperlinks;

use InvalidArgumentException;
use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use asinfotrack\yii2\hyperlinks\behaviors\HyperlinkBehavior;
use asinfotrack\yii2\toolbox\helpers\ComponentConfig;

/**
 * Main class for the hyperlink module
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license AS infotrack AG license / MIT, see provided license file
 */
class Module extends \yii\base\Module
{

	/**
	 * @var array array containing the classes to use for the individual model components.
	 */
	public $classMap = [
		'hyperlinkModel'=>'asinfotrack\yii2\hyperlink\models\Hyperlink',
		'hyperlinkSearchModel'=>'asinfotrack\yii2\hyperlink\models\search\HyperlinkSearch',
	];

	/**
	 * @var callable an optional callback to create the input field for the file upload
	 * of a model. Use this callback to implement an external file upload widget. Remember to reconfigure
	 * the validators of the hyperlink model as well!
	 *
	 * The callback should have the signature as of the following example and return a string
	 * containing the form code of the input field.
	 *
	 * ```php
	 * function ($form, $model, $attribute, $module, $view) {
	 *     return $form->field($model, $attribute)->widget(MyFileUpload::className(), []);
	 * }
	 * ```
	 *
	 * If not set, the file upload of yii2 be rendered.
	 *
	 * @see \asinfotrack\yii2\hyperlinks\Module::defaultFileInput()
	 * @see \asinfotrack\yii2\hyperlinks\models\Hyperlink::rules()
	 */
	public $urlInputCallback;

	/**
	 * @var callable an optional callback for the user relations as used by the two models
	 * within their blameable behaviors. This callback needs to be set, to use the `createdBy`
	 * and `changedBy` relations of the hyperlink model.
	 *
	 * The callback needs to have the signature `function ($model, $attribute)`, where `$model`
	 * is the instance of the hyperlink and `$attribute` is the field to build
	 * the relation upon (created_by or updated_by). The function should return an `ActiveQuery`
	 * the same way a regular relation is specified within yii2.
	 *
	 * Example for a callback:
	 *
	 * ```php
	 * function ($model, $attribute) {
	 *     return $model->hasOne(User::className(), ['id'=>$attribute]);
	 * }
	 * ```
	 */
	public $userRelationCallback;

	/**
	 * @var bool whether or not to enable client validation in backend forms
	 */
	public $backendEnableClientValidation = false;

	/**
	 * @var bool whether or not to enable ajax validation in backend forms
	 */
	public $backendEnableAjaxValidation = false;

	/**
	 * @var array configuration for the access control of the hyperlink controller.
	 * If set, the config will be added to the behaviors of the controller.
	 */
	public $backendAccessControl = [
		'class'=>'yii\filters\AccessControl',
		'rules'=>[
			[
				'allow'=>true,
				'roles'=>['@'],
			],
		],
	];

	/**
	 * @var array array holding the views which will be used for the hyperlink backend. The
	 * array is indexed by the action name and the values will be used to get the views. By
	 * default the views of the module will be used.
	 *
	 * To use a local view, use the corresponding view syntax. Usually two slashes are used
	 * to reference your root view path (eg `//my-folder/my-view`).
	 *
	 * See the hyperlink backend controller for the variables passed to the corresponding views.
	 * @see \asinfotrack\yii2\hyperlinks\controllers\HyperlinkBackendController
	 */
	public $backendViews = [
		'index'=>'index',
		'view'=>'view',
		'update'=>'update',
	];

	/**
	 * @inheritdoc
	 */
	public function __construct($id, $parent=null, $config=[])
	{
		//load the default config for the module
		$localDefaultConfig = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
		$config = ArrayHelper::merge($localDefaultConfig, $config);

		parent::__construct($id, $parent, $config);
	}

	/**
	 * This method will be called to create the url input, when no custom
	 * callback is set in the module config
	 *
	 * @param \yii\bootstrap\ActiveForm $form the form instance
	 * @param \asinfotrack\yii2\hyperlinks\models\Hyperlink $model the hyperlink model instance
	 * @param string $attribute name of the attribute
	 * @param \asinfotrack\yii2\hyperlinks\Module $module the module instance
	 * @param \yii\web\View $view the active view
	 * @return string the resulting form code for the input
	 */
	public static function defaultUrlInput($form, $model, $attribute, $module, $view)
	{
		return $form->field($model, $attribute)->fileinput();
	}


	/**
	 * Validates a subject model
	 *
	 * @param \yii\db\ActiveRecord $subject the subject to check
	 * @param bool $throwException if set to true, an exception will be thrown if not a valid subject
	 * @return bool true if valid
	 */
	public static function validateSubject($subject, $throwException=true)
	{
		if (!($subject instanceof \yii\db\ActiveRecord)) {
			if (!$throwException) return false;
			$msg = Yii::t('app', 'Only classes extending ActiveRecord allowed');
			throw new InvalidArgumentException($msg);
		}
		if ($subject->isNewRecord) {
			if (!$throwException) return false;
			$msg = Yii::t('app', 'Can not add links to unsaved subjects');
			throw new InvalidCallException($msg);
		}
		if (!ComponentConfig::hasBehavior($subject, HyperlinkBehavior::className())) {
			$msg = Yii::t('app', 'Subjects of links need to have the `HyperlinkBehavior` attached');
			throw new InvalidConfigException($msg);
		}

		return true;
	}


}
