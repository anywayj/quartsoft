<?php

namespace app\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use app\models\Payment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $article = new Article();
        $article->article_id = Yii::$app->user->identity->id;

        $articleQuery = Yii::$app->db->createCommand("
             SELECT * FROM article 
                JOIN user ON article.article_user_id = user.id
                JOIN record_user ON record_user.id = user.id
             WHERE article.article_user_id = '$article->article_id' 
        ")->queryAll();

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'articleQuery' => $articleQuery,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->defaultSettings($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->article_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->article_status = Article::STATUS_ACTIVE;
        $model->article_updated_at = date('Y-m-d H:i:s');

        $payment = new Payment();
        $payment->payment_user_id = Yii::$app->user->identity->id;
        $paymentQuery = Yii::$app->db->createCommand("
            SELECT * FROM payment
            JOIN plan
            ON plan.plan_id = payment.payment_plan_id
            WHERE payment_user_id = '$payment->payment_user_id'
        ")->queryAll();

        $article = new Article();
        $article->article_id = Yii::$app->user->identity->id;
        $articleQuery = Yii::$app->db->createCommand("
             SELECT * FROM article JOIN user
             ON article.article_user_id = user.id
             WHERE article.article_user_id = '$article->article_id' 
        ")->queryAll();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->article_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'paymentQuery' => $paymentQuery,
            'articleQuery' => $articleQuery,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
