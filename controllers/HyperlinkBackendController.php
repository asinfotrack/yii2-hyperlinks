<?php
namespace asinfotrack\yii2\hyperlinks\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use asinfotrack\yii2\hyperlinks\Module;
use asinfotrack\yii2\hyperlinks\models\Hyperlink;
use asinfotrack\yii2\hyperlinks\models\search\HyperlinkSearch;

/**
 * Controller to manage hyperlinks in the backend
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license AS infotrack AG license / MIT, see provided license file
 */
class HyperlinkBackendController extends \yii\web\Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		//default filters
		$behaviors = [
			'verbs'=>[
				'class'=>VerbFilter::className(),
				'actions'=>[
					'delete'=>['post'],
				],
			],
		];

		//access control filter if provided by module
		$module = Module::getInstance();
		if (!empty($module->backendAccessControl)) {
			$behaviors['access'] = $module->backendAccessControl;
		}

		return $behaviors;
	}

	public function actionIndex()
	{
		$searchModel = Yii::createObject(Module::getInstance()->classMap['hyperlinkSearchModel']);
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render(Module::getInstance()->backendViews['index'], [
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}

	public function actionView($id)
	{
		$model = $this->findModel($id);

		return $this->render(Module::getInstance()->backendViews['view'], [
			'model'=>$model,
		]);
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$loaded = $model->load(Yii::$app->request->post());

		if ($loaded && $model->save()) {
			return $this->redirect(['hyperlink-backend/view', 'id'=>$model->id]);
		}

		return $this->render(Module::getInstance()->backendViews['update'], [
			'model'=>$model,
		]);
	}

	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		return $this->redirect(['hyperlink-backend/index']);
	}

	/**
	 * Finds the Hyperlink model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return \asinfotrack\yii2\hyperlinks\models\Hyperlink the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$model = call_user_func([Module::getInstance()->classMap['hyperlinkModel'], 'findOne'], $id);
		if ($model === null) {
			$msg = Yii::t('app', 'No hyperlink found with `{value}`', ['value'=>$id]);
			throw new NotFoundHttpException($msg);
		}
		return $model;
	}

}
