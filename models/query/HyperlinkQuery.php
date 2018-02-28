<?php
namespace asinfotrack\yii2\hyperlinks\models\query;

use asinfotrack\yii2\toolbox\helpers\PrimaryKey;
use yii\helpers\Json;

/**
 * Query class for the hyperlinks providing the most common named scopes
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license AS infotrack AG license / MIT, see provided license file
 */
class HyperlinkQuery extends \yii\db\ActiveQuery
{

	/**
	 * Named scope to filter by subject
	 *
	 * @param \yii\db\ActiveRecord $model the subject model
	 * @return \asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery self for chaining
	 */
	public function subject($model)
	{
		$this->modelTypes($model);
		$this->andWhere(['hyperlink.foreign_pk'=>Json::encode($model->getPrimaryKey(true))]);
		return $this;
	}

	/**
	 * Named scope to filter by model classes or active record instances.
	 * Entries can be a mix of strings containing class names or instances directly
	 *
	 * @param string|string[]|\yii\db\ActiveRecord|\yii\db\ActiveRecord[] $models the model class or their class names
	 * @return \asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery self for chaining
	 */
	public function modelTypes($models)
	{
		//get actual types
		if (!is_array($models)) $models = [$models];
		$types = [];
		foreach ($models as $model) {
			$type = $model instanceof \yii\db\ActiveRecord ? $model::className() : $model;
			if (!in_array($type, $types)) $types[] = $type;
		}

		$this->andWhere(['hyperlink.model_type'=>$types]);
		return $this;
	}

	/**
	 * Named scope to filter only result files
	 *
	 * @param boolean $isNewTab whether or not to filter hyperlinks opening in a new tab
	 * @return \asinfotrack\yii2\hyperlinks\models\query\HyperlinkQuery self for chaining
	 */
	public function isNewTab($isNewTab=true)
	{
		$this->andWhere(['hyperlink.is_new_tab'=>$isNewTab ? 1 : 0]);
		return $this;
	}

}
