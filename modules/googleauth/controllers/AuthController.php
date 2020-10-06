<?php
namespace app\modules\googleauth\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\modules\googleauth\components\GoogleAuthenticator;
use app\modules\googleauth\models\GAConnect;
use app\modules\user\models\User;
use app\modules\googleauth\forms\GAConnectForm;
use Yii;

class AuthController extends Controller 
{   
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['connect','disconnect'],
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

    public function actionConnect() {
        $ga = new GoogleAuthenticator();
        if(($connect = GAConnect::findOne(Yii::$app->user->id)) == null){
            $connect = new GAConnect();
            $connect->user_id = Yii::$app->user->id;
            $connect->value = $ga->createSecret();
            if (!$connect->save()) {
                Yii::$app->getSession()->setFlash('warning', 'Произошла ошибка!');
                return $this->redirect(['/user/profile/index']);
            }
        } else if($connect->status == GAConnect::STATUS_ON) {
            Yii::$app->getSession()->setFlash('warning', 
                'У Вас активирована двухэтапная аутентификация!');
            return $this->redirect(['/user/profile/index']);
        }
        $email = '';
        if (($user = User::findOne($connect->user_id)) !== null) {
            $email = $user->email;
        }
        $model = new GAConnectForm();
        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
                if ($checkResult = $ga->verifyCode($connect->value, $model->code, 0)) {
                    $connect->status = GAConnect::STATUS_ON;
                    $connect->update(['status']);
                    Yii::$app->getSession()->setFlash('warning',
                        'Двухэтапная аутентификация подключена!');
                    $this->redirect(['/user/profile/index']);
                } else {
                    Yii::$app->getSession()->setFlash('warning','Коды не совпадают!');
                }
        }
        return $this->render('connect', [
            'model' => $model,
            'qrCodeUrl' => $ga->getQRCodeGoogleUrl(Yii::$app->name.":$email",
            $connect->value, Yii::$app->name),
        ]);
    }

    public function actionDisconnect() {
        if (!$this->module->googleauthManager->isActive(Yii::$app->user->id)) {
                Yii::$app->getSession()->setFlash('warning',
                    'У Вас не активирована двухэтапная аутентификация!');
        } else {
            $connect = GAConnect::findOne(Yii::$app->user->id);
            if ($connect->delete()) {
                Yii::$app->getSession()->setFlash('success',
                    'Двухэтапная аутентификация отключена.');
            } else {
                 Yii::$app->getSession()->setFlash('warning',
                    'Не удалось отключить двухэтапную аутентификацию!');
            }
        }
        $this->redirect(['/user/profile/index']);
    }

}
