<?php

namespace app\modules\user\controllers;

use app\modules\user\models\EmailConfirmForm;
use app\modules\user\models\LoginForm;
use app\modules\user\models\PasswordResetRequestForm;
use app\modules\user\models\PasswordResetForm;
use app\modules\user\models\SignupForm;
use app\modules\user\models\ResendActivationForm;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use app\modules\user\models\UserChangeEmail;
use app\modules\user\models\UserEntryStatistics;
use yii\web\Response;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','simple-signup'],
                'rules' => [
                    [
                        'actions' => ['signup','simple-signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
		'maxLength' => 4,
		'minLength'=> 4,
		'foreColor'=>0x474747,
            ],
        ];
    }
    
    public function actionLogin(){
        if(!Yii::$app->request->isAjax ) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && 
                $model->validate()) {
            if(Yii::$app->hasModule('googleauth')&& 
                Yii::$app->getModule('googleauth')->googleauthManager
                ->isActive($model->user->id)){
                Yii::$app->session->set('user.username',$model->username);
                Yii::$app->session->set('user.rememberMe',$model->rememberMe);
                $this->redirect(['/googleauth/login']);
            } else{
                $model->login();
                UserEntryStatistics::create();
                if(key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))=='moder'){
                    return $this->redirect(['/user/backend/users']);
                }else{
                    return $this->redirect(['/cabinet']);  
                }
                
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
     

    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup(){
        $model = new SignupForm();
        $model->scenario=SignupForm::SCENARIO_SINGUP_ORDINARY;
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->getSession()->setFlash('success', 
                'Вам на почту выслано активационное письмо. Подтвердите ваш электронный адрес.');
            return $this->goHome();
        }
        return $this->render('signup', ['model' => $model,]);
    }

    public function actionSimpleSignup(){
        if(!Yii::$app->request->isAjax ){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_SINGUP_SIMPLE;
        if ($model->load(Yii::$app->request->post())&& $model->signup()) {
            Yii::$app->getSession()->setFlash('success', 'Вам на почту выслано активационное письмо. Подтвердите ваш электронный адрес.');
            return $this->goHome();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionConfirmEmail($token){
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 
                    'Спасибо! Ваш Email успешно подтверждён.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }
        return $this->goHome();
    }
    
    public function actionChangeEmailConfirmation($token){
        if(($model=UserChangeEmail::findByResetToken($token))==NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->actChangeEmail()){
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно изменен.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка смены Email.');
        }
        return $this->redirect(['/user/profile']);
    }

    public function actionRequestPasswordReset(){
        if(!Yii::$app->request->isAjax ){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!Yii::$app->user->isGuest){ 
            return $this->goHome();
        }
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 
                    'Спасибо! На ваш Email было отправлено письмо со ссылкой на восстановление пароля.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 
                    'Извините. У нас возникли проблемы с отправкой.');
                return $this->goHome();
            }
        } 
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionResetPassword($token){
        try {
            $model = new PasswordResetForm($token);
        } 
        catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && 
                $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');
            return $this->goHome();
        }
        return $this->render('resetPassword', ['model' => $model]);
    }

    public function actionResendActivation(){
	$model = new ResendActivationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 
                    'Вам на почту выслано активационное письмо. Подтвердите ваш электронный адрес.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 
                    'Извините. Возникли проблемы с отправкой.');
            }
        }
        return $this->render('resendActivationToken', ['model' => $model]);
    }
}
