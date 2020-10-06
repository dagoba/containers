<?php
namespace app\modules\user\controllers;

use app\modules\user\models\Useraccount;
use app\modules\user\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\user\models\Applicationwithdrawal;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['deposit','withdrawal','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex(){
        $user=User::findOne(Yii::$app->user->id);
        if($user==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        if(!$user->account)
            throw new NotFoundHttpException('The requested page does not exist.');
        return $this->render('index', ['model'=>$user ]);
    }

    public function actionDeposit(){
        $user=User::findOne(Yii::$app->user->getId());
        if($user==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        if(!$user->account)
            throw new NotFoundHttpException('The requested page does not exist.');
        return $this->render('infoDeposit', ['user'=>$user, ]);
    }
    
    //application-withdrawal-confirmation
    public function actionApplicationWithdrawalConfirmation($token)
    {
        if(($model=  Applicationwithdrawal::findByConfirmToken($token))==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        if($model->actConfirmApplication())
            Yii::$app->getSession()->setFlash('success', 'Ваша заявка подтверждена.');
        else
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения.');
        if(!Yii::$app->user->isGuest) {
            return $this->redirect(['/balance/default/balance']);
        }
        return $this->redirect(['/']);
    }
    
    public function actionWithdrawal()
    {
        $user=User::findOne(Yii::$app->user->getId());
        if($user==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        if(!$user->account)
            throw new NotFoundHttpException('The requested page does not exist.');
        if($user->account->balance<Applicationwithdrawal::MIN_AMOUNT_WITHDRAWAL){
            Yii::$app->getSession()->setFlash('warning','Вы не можете подать заявку на вывод. Минимальная сумма вывода с основного счета '.Applicationwithdrawal::MIN_AMOUNT_WITHDRAWAL.'$.');
            return $this->redirect(['/balance/default/balance']);
        }
        $model = new Applicationwithdrawal();
        $model->scenario=Applicationwithdrawal::SCENARIO_MASTER_CARD_OTHERS;
        if($model->load(Yii::$app->request->post())){
            $model->installScenario();
            if($model->validate()){
                if($model->withdrawMoney())
                    Yii::$app->getSession()->setFlash('showModalWindow',' Спасибо, ваша заявка создана. На ваш Email была выслана ссылка для подтверждения. После подтверждения она будет обработана в течение трех рабочих дней.');
                else
                    Yii::$app->getSession()->setFlash('warning','Возникла ошибка');
                return $this->redirect(['/balance/default/balance']);
            }
            return $this->render('_formWithdrawal', 
                                [
                                    'user'=>$user,
                                    'model' => $model,
                                ]);
            
        } else {
            return $this->render('_formWithdrawal', [
                        'user'=>$user,
                        'model' => $model,
                    ]);
        }
    }
   
    private function findModel(){
        return Useraccount::findOne(Yii::$app->user->identity->getId());
    }
}
