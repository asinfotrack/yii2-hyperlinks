<?php
namespace asinfotrack\yii2\hyperlinks\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use asinfotrack\yii2\hyperlinks\Module;

/**
 * The search model for the hyperlinks
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license AS infotrack AG license / MIT, see provided license file
 */
class HyperlinkSearch extends \asinfotrack\yii2\hyperlinks\models\Hyperlink
{

	public function rules()
	{
		return [
			[['id','created','created_by','updated','updated_by'], 'integer'],
			[['is_new_tab'], 'boolean'],
			[['model_type','foreign_pk','url','title','desc'], 'safe'],
		];
	}

	public function scenarios()
	{
		return Model::scenarios();
	}

	public function search($params, $query=null)
	{
		if ($query === null) {
			$query = call_user_func(Module::getInstance()->classMap['attachmentModel'], 'find');
		}
		$dataProvider = new ActiveDataProvider([
			'query'=>$query,
			'sort'=>[
				'defaultOrder'=>['hyperlink.created'=>SORT_DESC],
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id'=>$this->id,
			'is_new_tab'=>$this->is_new_tab,
			'created'=>$this->created,
			'created_by'=>$this->created_by,
			'updated'=>$this->updated,
			'updated_by'=>$this->updated_by,
		]);

		$query
			->andFilterWhere(['like', 'model_type', $this->model_type])
			->andFilterWhere(['like', 'foreign_pk', $this->foreign_pk])
			->andFilterWhere(['like', 'url', $this->url])
			->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'desc', $this->desc]);

		return $dataProvider;
	}

}
