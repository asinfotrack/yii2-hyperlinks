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
			[['model_type','foreign_pk','url','title','description'], 'safe'],
		];
	}

	public function scenarios()
	{
		return Model::scenarios();
	}

	public function search($params, $query=null)
	{
		if ($query === null) {
			$query = call_user_func([Module::getInstance()->classMap['hyperlinkModel'], 'find']);
		}

		$dataProvider = new ActiveDataProvider([
			'query'=>$query,
		]);

		if ($this->load($params) && $this->validate()) {
			$query->andFilterWhere([
				'hyperlink.id' => $this->id,
				'hyperlink.is_new_tab' => $this->is_new_tab,
				'hyperlink.created' => $this->created,
				'hyperlink.created_by' => $this->created_by,
				'hyperlink.updated' => $this->updated,
				'hyperlink.updated_by' => $this->updated_by,
			]);

			$query
				->andFilterWhere(['like', 'hyperlink.model_type', $this->model_type])
				->andFilterWhere(['like', 'hyperlink.foreign_pk', $this->foreign_pk])
				->andFilterWhere(['like', 'hyperlink.url', $this->url])
				->andFilterWhere(['like', 'hyperlink.title', $this->title])
				->andFilterWhere(['like', 'hyperlink.desc', $this->desc]);
		}

		return $dataProvider;
	}

}
