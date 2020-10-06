<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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
    public function beforeAction($action)
    {
         $this->enableCsrfValidation = false;
        
         return parent :: beforeAction($action);
    }

    /**
     * @inheritdoc
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
    public function actionCurs()
    {
        \app\models\Curs::changecurs();
    }
    public function actionLang($lang="en"){
        $language=$language=\yii\helpers\BaseHtmlPurifier::process($lang);
        SetCookie("language",$language,time()+86400*30,"/");
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionIndex(){
        return $this->render('index');
    }
    public function actionSuccess(){
        return $this->render('success');
    }
    public function actionErrorpay(){
        return $this->render('errorpay');
    }
    public function actionPayreturn($ps = null,$result = null) {
        $text = '';
        if($result == "ok")
            return $this->redirect(['site/success']);
        else
            return $this->redirect(['site/errorpay']);
    }
    public function actionLogin(){
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

    public function actionContact(){
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionRoute(){
        return $this->render('route');
    }
}
