<?php
namespace asinfotrack\yii2\hyperlinks\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use asinfotrack\yii2\hyperlinks\Module;

class HyperlinkBehavior extends \yii\base\Behavior
{

	/**
	 * @var \yii\db\ActiveRecord the owner of the behavior
	 */
	public $owner;

	/**
	 * @var int max number of hyperlinks for the owner. `0` means no limit.
	 */
	public $maxNumHyperlinks = 0;

	/**
	 * @inheritdoc
	 */
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_DELETE=>[$this, 'handleDelete'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attach($owner)
	{
		if (!($owner instanceof \yii\db\ActiveRecord)) {
			$msg = Yii::t('app', 'The `HyperlinkBehavior` can only be attached to classes extending `ActiveRecord`');
			throw new InvalidConfigException($msg);
		}

		parent::attach($owner);
	}

	/**
	 * Creates a preconfigured query instance
	 *
	 * @return \asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery the query
	 */
	public function getHyperlinkQuery()
	{
		return call_user_func([Module::getInstance()->classMap['hyperlinkModel'], 'find'])->subject($this->owner);
	}

	/**
	 * Returns the model instances of the assigned hyperlinks
	 *
	 * @return \asinfotrack\yii2\hyperlinks\models\Hyperlink[] the hyperlink models
	 */
	public function getHyperlinks()
	{
		return $this->getHyperlinkQuery()->all();
	}

	/**
	 * Returns the number of hyperlinks
	 *
	 * @return int number of assigned hyperlinks
	 */
	public function getNumHyperlinks()
	{
		return $this->getHyperlinkQuery()->count();
	}

	/**
	 * Returns whether or not there are assigned hyperlinks
	 *
	 * @return bool true if there are any
	 */
	public function hasHyperlinks()
	{
		return $this->getNumHyperlinks() > 0;
	}

	/**
	 * Event handler for delete events which also deletes all related hyperlinks
	 *
	 * @param \yii\base\ModelEvent $event the event object
	 */
	public function handleDelete($event)
	{
		foreach ($this->getHyperlinks() as $hyperlink) {
			if (!$hyperlink->delete()) {
				$event->isValid = false;
				break;
			}
		}
	}

}
