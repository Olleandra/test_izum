<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Goods;
use app\models\GoodsSearch;
use app\models\Stock;

class GoodsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    
    public function actionIndex()
    {
		$searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGroup()
    {
		$searchModel = new GoodsSearch();
        $return = $searchModel->searchGroup();
        $returnNot = $searchModel->searchNotGroup();
		
		if(!$return || $return == null || empty($return))
			$return = 'Не удалось выполнить запрос';
		
		if(!$returnNot || $returnNot == null || empty($returnNot))
			$returnNot = 'Не удалось выполнить запрос';

        return $this->render('index', [
            'flag' => true,
            'return' => $return,
            'returnNot' => $returnNot,
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$data = $this->findStock($id);
		$model->count = $data->count;
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();
        $data = new Stock();

        if ($model->load(Yii::$app->request->post()) && $data->load(Yii::$app->request->post())) {
			$model->count = $data->count;
			if ($model->save())
				return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'data' => $data,
            ]);
        }
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$data = $this->findStock($id);
		
        if ($model->load(Yii::$app->request->post()) && $data->load(Yii::$app->request->post())) {
			$model->count = $data->count;
			if ($model->save())
				return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'data' => $data,
            ]);
        }
    }

    /**
     * Deletes an existing Goods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Данный товар не обнаружен.');
        }
    }
	
    protected function findStock($id)
    {
        if (($model = Stock::find()->where(['goods_id' => $id])->one()) != null) {
            return $model;
        } else {
            $model = new Stock;
			return $model;
        }
    }
}
