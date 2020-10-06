<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;
use app\modules\user\models\Usertransaction;
use app\modules\user\models\Userchangebalance;
use app\modules\user\models\User;

class Payment extends Model
{
    const PAYMENT_LOCAL = 0;
    const PAYMENT_WMR=1;
    const PAYMENT_WMZ=2;
    const PAYMENT_PERFECT_MONEY=3;
    const PAYMENT_YANDEX_MONEY=4;
    const PAYMENT_QIWI=5;
    const PAYMENT_VISA_MASTER_CARD=6;
    
    const SCENARIO_DEPOSIT = 'deposit-transaction';
    const SCENARIO_WITHDRAWAL = 'withdrawal-transaction';  
    
    public $amount;
    public $paymentsystem_id;
    public $account_id;
    public $comment;

    public function rules()
    {
        return 
        [
            ['amount', 'required'],
	    ['amount', 'number','min' => 1],
            
            ['payment_system', 'required'],
            ['payment_system', 'default', 'value' => self::PAYMENT_LOCAL],
            ['payment_system', 'integer'],
            ['payment_system', 'in', 'range' => array_keys(self::getPaymentSystemsArray())],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'amount'=>'Сумма',
            'paymentsystem_id'=>'Платежная система'
        ];
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), 
        [
            self::SCENARIO_DEPOSIT => ['paymentsystem_id','amount','account_id'],
            self::SCENARIO_WITHDRAWAL => ['paymentsystem_id','amount','account_id']
        ]);
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getPaymentSystemsArray()
    {
        return 
        [
            self::PAYMENT_LOCAL =>'Локально',
            self::PAYMENT_WMR=>'WMR',
            self::PAYMENT_WMZ=>'WMZ',
            self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
            self::PAYMENT_YANDEX_MONEY=>'YandexMoney',
            self::PAYMENT_QIWI=>'QIWI',
            self::PAYMENT_VISA_MASTER_CARD=>'Visa/MasterCard',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public function createTransactions()
    {
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            return FALSE;
        $transaction= new Usertransaction();
        $transaction->scenario = Usertransaction::SCENARIO_DEPOSIT;
        $transaction->amount=$this->amount;
        $transaction->paymentsystem_id=$this->payment_system;
        $transaction->account_id=$user->id;
        $transaction->type_id= Usertransaction::TYPE_DEPOSIT;
        $transaction->description='Пополнение аккаунта #'.$user->id.' на сумму - '.$this->amount;
        $transaction->status=Usertransaction::STATUS_CREATED;
        if($transaction->save())
        {
            if($this->payment_system==self::PAYMENT_LOCAL)
                $this->moneyTransfer($transaction->id);
            return TRUE;
        }
        return FALSE;
    }
    
    public function moneyTransfer($transactionID)
    {
        $transaction=  Usertransaction::find()->where([
                                                        'id'=>intval($transactionID),
                                                        'status'=>Usertransaction::STATUS_CREATED
                                                    ])->one();
        if($transaction==NULL)
            return FALSE;
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            return FALSE;
        $changeBanance=new Userchangebalance();
        $changeBanance->before_balance=$user->account->balance;
        $user->account->balance+=$transaction->amount;
        if($user->account->save())
        {
            $transaction->scenario = Usertransaction::SCENARIO_APPROVE;
            $transaction->status=Usertransaction::STATUS_SUCCESS;
            $transaction->save();
            $changeBanance->account_id=$user->account->user_id;
            $changeBanance->usertransaction_id=$transaction->id;
            $changeBanance->after_balance=$user->account->balance;
            $changeBanance->amount=$transaction->amount;
            $changeBanance->type_id=Userchangebalance::TYPE_DEPOSIT;
            $changeBanance->save();
            return TRUE;
        }
        return FALSE; 
    }

}
