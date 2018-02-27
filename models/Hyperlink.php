<?php
namespace asinfotrack\yii2\hyperlinks\models;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidCallException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;
use asinfotrack\yii2\hyperlinks\Module;
use asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery;
use asinfotrack\yii2\toolbox\helpers\PrimaryKey;

/**
 * This is the model class for table "hyperlink".
 *
 * @property integer $id
 * @property string $model_type
 * @property string $foreign_pk
 * @property bool $is_new_tab
 * @property string $url
 * @property string $title
 * @property string $desc
 * @property integer $created
 * @property integer $created_by
 * @property integer $updated
 * @property integer $updated_by
 *
 * @property \yii\db\ActiveRecord $subject readonly
 *
 * @property \yii\web\IdentityInterface $createdBy
 * @property \yii\web\IdentityInterface $updatedBy
 */
class Hyperlink extends \yii\db\ActiveRecord
{

	/**
	 * @var \yii\db\ActiveRecord|\asinfotrack\yii2\hyperlinks\behaviors\HyperlinkBehavior holds the subject for this hyperlink
	 */
	protected $subject;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'hyperlink';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
    {
        return [
        	'timestamp'=>[
	    		'class'=>TimestampBehavior::className(),
	    		'createdAtAttribute'=>'created',
	    		'updatedAtAttribute'=>'updated',
        	],
        	'blameable'=>[
	    		'class'=>BlameableBehavior::className(),
	    		'createdByAttribute'=>'created_by',
	    		'updatedByAttribute'=>'updated_by',
        	],
        ];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['model_type','foreign_pk','url','title','desc'], 'trim'],
			[['model_type','foreign_pk','url','title','desc'], 'default'],
			[['is_new_tab'], 'default', 'value'=>false],

			[['model_type','foreign_pk','url'], 'required'],

			[['model_type','foreign_pk','url','title'], 'string', 'max'=>255],
			[['desc'], 'string'],
			[['is_new_tab'], 'boolean'],
			[['url'], 'url'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'=>Yii::t('app', 'ID'),
			'model_type'=>Yii::t('app', 'Table name'),
			'foreign_pk'=>Yii::t('app', 'Foreign PK'),
			'is_new_tab'=>Yii::t('app', 'New tab'),
			'url'=>Yii::t('app', 'URL'),
			'title'=>Yii::t('app', 'Title'),
			'desc'=>Yii::t('app', 'Description'),
			'created'=>Yii::t('app', 'Created'),
			'created_by'=>Yii::t('app', 'Created by'),
			'updated'=>Yii::t('app', 'Updated'),
			'updated_by'=>Yii::t('app', 'Updated by'),

			'subject'=>Yii::t('app', 'Subject'),
		];
	}

	/**
	 * Returns an instance of the query-type for this model
	 *
	 * @return \asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery
	 */
	public static function find()
	{
		return new HyperlinkQuery(get_called_class());
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		$this->foreign_pk = Json::decode($this->foreign_pk);
		parent::afterFind();
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}

		$this->foreign_pk = Json::encode($this->foreign_pk);
		return true;
	}

	/**
	 * Returns either the title (if set) or the filename
	 *
	 * @return string the display title
	 */
	public function getDisplayTitle()
	{
		return empty($this->title) ? $this->url : $this->title;
	}

	/**
	 * Getter for the subject model
	 *
	 * @return \yii\db\ActiveRecord the subject of this hyperlink
	 * @throws \yii\base\ErrorException
	 */
	public function getSubject()
	{
		if (!$this->isNewRecord && $this->subject === null) {
			$this->subject = call_user_func([$this->model_type, 'findOne'], $this->foreign_pk);
			if ($this->subject === null) {
				$msg = Yii::t('app', 'Could not find model for hyperlink `{hyperlink}`', [
					'hyperlink'=>$this->id
				]);
				throw new ErrorException($msg);
			}
		}

		return $this->subject;
	}

	/**
	 * Sets the subject-model for this hyperlink
	 *
	 * @param \yii\db\ActiveRecord $subject the subject model
	 */
	public function setSubject($subject)
	{
		Module::validateSubject($subject);

		$this->model_type = $subject->className();
		$this->foreign_pk = PrimaryKey::asJson($subject);
		$this->subject = $subject;
	}

	/**
	 * Returns the user who created the instance. This relation only works when
	 * `userRelationCallback` is properly configured within the module config.
	 *
	 * @return \yii\db\ActiveQuery the active query of the relation
	 * @throws \yii\base\InvalidCallException when `userRelationCallback is not properly configured
	 */
	public function getCreatedBy()
	{
		$callback = Module::getInstance()->userRelationCallback;
		if (!is_callable($callback)) {
			$msg = Yii::t('app', 'No or invalid `userRelationCallback` specified in Module config');
			throw new InvalidCallException($msg);
		}

		return call_user_func($callback, $this, 'created_by');
	}

	/**
	 * Returns the user who updated the instance. This relation only works when
	 * `userRelationCallback` is properly configured within the module config.
	 *
	 * @return \yii\db\ActiveQuery the active query of the relation
	 * @throws \yii\base\InvalidCallException when `userRelationCallback is not properly configured
	 */
	public function getUpdatedBy()
	{
		$callback = Module::getInstance()->userRelationCallback;
		if (!is_callable($callback)) {
			$msg = Yii::t('app', 'No or invalid `userRelationCallback` specified in Module config');
			throw new InvalidCallException($msg);
		}

		return call_user_func($callback, $this, 'updated_by');
	}

}
