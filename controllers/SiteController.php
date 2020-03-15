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
use app\models\Plan;
use app\models\Payment;
use app\models\Article;
use app\models\ArticleSearch;

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
                        'actions' => ['login', 'error', 'signup', 'index'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionCheckout($id, $name, $price)
    {
        $params = [
            'method' => 'paypal',
            'intent' => 'sale',
            'productId' => $id,
            'order' => [
                'description' => $name,
                'total' => $price,
                'currency' => 'USD'
            ]
        ];

        // In this action you will redirect to the PayPal website to login with you buyer account and complete the payment
        Yii::$app->PayPalRestApi->checkOut($params);
    }

    public function actionMakePayment()
    {
        if (isset(Yii::$app->request->get()['success']) && Yii::$app->request->get()['success'] == 'true') {
            Yii::$app->PayPalRestApi->processPayment();
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
        $article = new Article();
        $statusActive = $article::STATUS_ACTIVE;
        $articleQuery = Yii::$app->db->createCommand("
            SELECT * FROM article WHERE article_status = '$statusActive'
        ")->queryAll();

        $articleUsers = [];
        foreach($articleQuery as $article) {
            $articleUsers[] = $article['article_user_id'];
        }

        $article = new ActiveDataProvider([
            'query' => Article::find()
                ->where(['article_status' => $statusActive])
        ]);

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->searchMain(Yii::$app->request->queryParams);

        return $this->render('index', [
            'article' => $article,
            'articleUsers' => $articleUsers,
            'articleQuery' => $articleQuery,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionPlan()
    {
        $planQuery = Yii::$app->db->createCommand('
            SELECT * FROM plan
        ')->queryAll();

        $payment = new Payment();
        $payment->payment_user_id = Yii::$app->user->identity->id;
        $paymentQuery = Yii::$app->db->createCommand("
            SELECT * FROM payment
            JOIN plan
            ON plan.plan_id = payment.payment_plan_id
            WHERE payment_user_id = '$payment->payment_user_id'
        ")->queryAll();

        return $this->render('plan', [
            'planQuery' => $planQuery,
            'paymentQuery' => $paymentQuery,
        ]);
    }

    public function actionCreate()
    {
        $model = new Payment();
        $model->payment_user_id = Yii::$app->user->identity->id;
        $model->payment_created_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Подписка оформлена!');
            return $this->redirect('/site/index');
        }

        return $this->render('createPayment', [
            'model' => $model,
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
            if ($model->signup()
                && $modelTwo->signupTwo()
            ) {
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

    protected function findModel($id)
    {
        if (($model = RecordUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelPlan($id)
    {
        if (($model = Plan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
