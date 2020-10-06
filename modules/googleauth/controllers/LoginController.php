<?php
namespace app\modules\googleauth\controllers;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\user\models\User;
use app\modules\googleauth\models\GAConnect;
use app\modules\googleauth\components\GoogleAuthenticator;
use app\modules\googleauth\forms\GAConnectForm;
use app\modules\user\models\LoginForm;
use app\modules\user\models\UserEntryStatistics;
use Yii;

class LoginController extends Controller {

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        $username =   Yii::$app->session->get('user.username');
        $remember =Yii::$app->session->get('user.rememberMe');
        $user = User::find()
                ->where(['status'=>User::STATUS_ACTIVE])
                ->andWhere($this->isEmail($username)? ['email'=>$username]: ['username'=>$username])
                ->one();
         if ($user === null || $username === null || $remember === null ||
                ($user !== null && !$this->module->googleauthManager->isActive($user->id))){
            Yii::$app->user->logout();
            return $this->redirect(['/#login']);
        }
        $connect = GAConnect::findOne($user->id);
        $ga = new GoogleAuthenticator();
        $model = new GAConnectForm();
        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            $checkResult = $ga->verifyCode($connect->value, $model->code, 0);
            if ($checkResult) {
                $formLogin = new LoginForm();
                $formLogin->username = $username;
                $formLogin->password = $model->code;
                $formLogin->rememberMe = $remember;
                    Yii::$app->user->login($user, 60*15);
                    Yii::$app->getSession()->setFlash('success', 'Вы успешно авторизировались!');
                    Yii::$app->session->set('user.username',null);
                    Yii::$app->session->set('user.rememberMe',null);
                    UserEntryStatistics::create();
                    return $this->redirect(['/user/profile/index']);
            } else {
                Yii::$app->getSession()->setFlash('warning', 'Коды не совпадают!');
            }
        }
        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    protected function isEmail($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }
    

}
