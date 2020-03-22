<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\SignupFormtwo;
use app\models\RecordUser;
use app\models\Payment;
use app\models\Plan;
use app\models\Article;
use app\models\ArticleSearch;
use yii\web\NotFoundHttpException;
use app\components\AuthHandler;

class SiteController extends Controller
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
                        'actions' => ['login', 'error', 'signup', 'index', 'auth'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'create', 'article', 'plan', 'checkout', 'make-payment'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    public function actionCheckout($planId, $price)
    {
        Yii::$app->PayPalRestApi->checkOut($planId, $price);
    }

    public function actionMakePayment()
    {
        if ($response = Yii::$app->PayPalRestApi->processPayment()) {
            $payment = new Payment();
            $planId = $response['transactions']['0']['custom'];
            $payment->savePayment($planId);
            Yii::$app->session->setFlash('success', 'Подписка оформлена!');
            return $this->redirect('/site/plan');
        }

        return null;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $modelArticle = new Article;
        $articleQuery = $modelArticle->getActiveArticles();
        $article = new ActiveDataProvider(['query' => $articleQuery]);
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->searchActiveArticles(Yii::$app->request->queryParams);

        return $this->render('index', [
            'article' => $article,
            'articleQuery' => $articleQuery,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionPlan()
    {
        $planQuery = Plan::find()->all();
        $modelPayment = new Payment;
        $paymentQuery = $modelPayment->getCurrentUserPayments();

        return $this->render('plan', [
            'planQuery' => $planQuery,
            'paymentQuery' => $paymentQuery,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        $modelTwo = new SignupFormtwo();
        if ($model->load(Yii::$app->request->post())
            && $modelTwo->load(Yii::$app->request->post())
        ) {
            if ($model->signup() && $modelTwo->signupTwo()) {
                Yii::$app->session->setFlash('success', 'Спасибо за регистрацию!');
                return $this->redirect('/site/login'); 
            }  
        }

        return $this->render('signup', compact('model', 'modelTwo'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Finds the RecordUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecordUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecordUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
