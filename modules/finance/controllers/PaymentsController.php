<?php

namespace app\modules\finance\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use app\modules\user\models\User;
use app\modules\finance\models\FinanceApplicationWithdrawal;
use app\modules\finance\models\FinanceApplicationWithdrawalSearch;
use app\modules\user\models\UsertransactionSearch;
use app\modules\user\models\Usertransaction;


class PaymentsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'deposit-application',
                            'create-deposit-application',
                            'withdrawal-application',
                            'create-withdrawal-application',
                            'transactions'
                            ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
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

    public function actionDepositApplication(){
        $searchModel = new UsertransactionSearch();
        $searchModel->type_id = UsertransactionSearch::TYPE_DEPOSIT; 
        $dataProvider = $searchModel->userSearch(Yii::$app->request->queryParams);
        return $this->render('deposit_application/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreateDepositApplication(){
        $user=User::findOne(Yii::$app->user->getId());
        if($user==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        if(!$user->account)
            throw new NotFoundHttpException('The requested page does not exist.');
        $model = new Usertransaction();
        $model->scenario = Usertransaction::SCENARIO_DEPOSIT;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->account_id = $user->account->user_id;
            $model->type_id = Usertransaction::TYPE_DEPOSIT;
            $model->description = Yii::t('app','Пополнение счета в системе');
            $model->status = Usertransaction::STATUS_CREATED;
            if($model->paymentsystem_id == Usertransaction::PAYMENT_BTC)
                $model->curs = Usertransaction::btcCurs();
            if($model->save())
            {
                switch($model->paymentsystem_id)
                {
                    case Usertransaction::PAYMENT_PERFECT_MONEY:
                        $model->makeDataForPM();
                        return $this->render('deposit_application/confirmdeposit', ['model' => $model]);
                    case Usertransaction::PAYMENT_ADVANCED_CASH:
                        $model->makeDataForAdv();
                        return $this->render('deposit_application/confirmadv', ['model' => $model]);
                    case Usertransaction::PAYMENT_BTC:
                        $model->makeDataForBTC();
                        return $this->render('deposit_application/confirmbtc', ['model' => $model]);
                    default : $this->render('deposit_application/noconfirm', ['model' => $model]);
                }
                
            }
        }
        return $this->render('deposit', ['model'=>$model, ]);
    }
    
    public function actionWithdrawalApplication(){
        $searchModel = new FinanceApplicationWithdrawalSearch();
        $dataProvider = $searchModel->userSearch(Yii::$app->request->queryParams);
        return $this->render('withdrawal_application/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreateWithdrawalApplication(){
        if(($user=User::findOne(Yii::$app->user->getId()))==null||!$user->account){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
     
        if($user->account->balance < FinanceApplicationWithdrawal::MIN_AMOUNT_WITHDRAWAL){
            Yii::$app->getSession()->setFlash('warning',
                'Вы не можете подать заявку на вывод. Минимальная сумма вывода с основного счета '.
                FinanceApplicationWithdrawal::MIN_AMOUNT_WITHDRAWAL.'$.');
            return $this->redirect(['withdrawal-application']);
        }
        $model = new FinanceApplicationWithdrawal();
        $model->scenario=FinanceApplicationWithdrawal::SCENARIO_MASTER_CARD_OTHERS;
        if($model->load(Yii::$app->request->post())){
            $model->installScenario();
            if($model->validate()){
      
                if($model->withdrawMoney()){
                    Yii::$app->getSession()->setFlash('showModalWindow',
                        ' Спасибо, ваша заявка создана. На ваш Email была выслана '.
                        'ссылка для подтверждения. После подтверждения она будет '.
                        'обработана в течение трех рабочих дней.');
                } else{
                    Yii::$app->getSession()->setFlash('warning','Возникла ошибка');
                }
                return $this->redirect(['withdrawal-application']);
            }
 
        }
        return $this->render('_form_withdrawal',[
                    'user'=>$user,
                    'model' => $model,
                ]);
    }
    
    public function actionTransactions(){
    	$user=User::findOne(Yii::$app->user->getId());
        $searchModel = new UsertransactionSearch();
        $dataProvider = $searchModel->userSearch(Yii::$app->request->queryParams);
        return $this->render('transactions/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user,
        ]);
    }
    
    
}
