<?php
namespace asinfotrack\yii2\hyperlinks\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use asinfotrack\yii2\hyperlinks\Module;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedDataColumn;
use asinfotrack\yii2\toolbox\widgets\grid\BooleanColumn;
use asinfotrack\yii2\toolbox\widgets\grid\IdColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedActionColumn;

/**
 * Renders a list of a models hyperlinks
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license MIT
 */
class HyperlinkList extends \yii\base\Widget
{

	/**
	 * @var \yii\data\ActiveDataProvider the data provider used in the grid
	 */
	protected $dataProvider;

	/**
	 * @var \asinfotrack\yii2\hyperlinks\models\search\HyperlinkSearch the search model
	 */
	protected $searchModel;

	/**
	 * @var \yii\db\ActiveRecord|\asinfotrack\yii2\hyperlinks\behaviors\HyperlinkBehavior either
	 * a subject model having the `HyperlinkBehavior` attached or an instance of `HyperlinkQuery`
	 */
	public $subject;

	/**
	 * @var callable optional callback to make further adjustments on the query, before it
	 * gets passed to the data provider. If specified, the callback should have the following
	 * signature: `function ($query)` and return the query object itself
	 */
	public $queryCallback;

	/**
	 * @var array|callable either a column configuration in the form of an array or a callable returning
	 * such one. If a callback is defined, it should use the following signature `function ($columns, $widget)`
	 * and return a proper column config as required by the GridView. The attribute `$columns` holds the default
	 * configuration of the columns and `$widget` the widget instance.
	 *
	 * By default the static method `generateDefaultColumnConfig()` will be called and a default config generated.
	 */
	public $columnConfig;

	/**
	 * @var bool whether or not to use pjax for the grid
	 */
	public $enablePjax = true;

	/**
	 * @var array the options which will be passed to the grid view
	 */
	public $options = [];

	/**
	 * @inheritdoc
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init()
	{
		parent::init();

		//validate subject
		if ($this->subject === null || !Module::validateSubject($this->subject)) {
			$msg = Yii::t('app', 'No or invalid subject specified');
			throw new InvalidConfigException($msg);
		}

		//prepare query
		$query = $this->subject->getHyperlinkQuery();
		if (is_callable($this->queryCallback)) {
			$query = call_user_func($this->queryCallback, $query);
		}

		//prepare data provider and search model
		$this->searchModel = Yii::createObject(Module::getInstance()->classMap['hyperlinkSearchModel']);
		$this->dataProvider = $this->searchModel->search(Yii::$app->request->getQueryParams(), $query);

		//prepare options
		$this->options['id'] = $this->id;
		Html::addCssClass($this->options, 'grid-view widget-hyperlink-list');
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->enablePjax) Pjax::begin(['id'=>sprintf('pjax-%s', $this->id)]);
		echo GridView::widget([
			'dataProvider'=>$this->dataProvider,
			'filterModel'=>$this->searchModel,
			'options'=>$this->options,
			'columns'=>$this->resolveColumnConfig(),
		]);
		if ($this->enablePjax) Pjax::end();
	}

	/**
	 * Prepares the final column configuration for the grid view
	 *
	 * @return array the final column configuration
	 */
	protected function resolveColumnConfig()
	{
		if (is_array($this->columnConfig)) {
			return $this->columnConfig;
		}

		$defaultConfig = static::generateDefaultColumnConfig();
		if (is_callable($this->columnConfig)) {
			return call_user_func($this->columnConfig, $defaultConfig, $this);
		} else {
			return $defaultConfig;
		}
	}

	/**
	 * Generates the default column config for the grid view
	 *
	 * @return array the column array
	 */
	protected static function generateDefaultColumnConfig()
	{
		return [
			[
				'class'=>IdColumn::class,
				'attribute'=>'id',
			],
			[
				'class'=>AdvancedDataColumn::class,
				'attribute'=>'title',
				'columnWidth'=>20,
			],
			[
				'attribute'=>'url',
				'format'=>'raw',
				'value'=>function ($model, $key, $index, $column) {
					return Html::a($model->url, Url::to($model->url), ['target'=>$model->is_new_tab ? '_blank' : '']);
				},
			],
			'title',
			[
				'class'=>BooleanColumn::class,
				'attribute'=>'is_new_tab',
			],
			[
				'class'=>AdvancedActionColumn::class,
			],
		];
	}

}
