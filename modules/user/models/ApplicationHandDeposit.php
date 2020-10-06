<?php

namespace app\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use app\modules\user\models\Usertransaction;
use app\modules\user\models\Userchangebalance;
use app\modules\user\models\User;
use app\modules\user\models\Useraccount;

/**
 * This is the model class for table "{{%applicationwithdrawal}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $amount
 * @property integer $paymentsystem_id
 * @property string $description
 * @property string $requisites
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class ApplicationHandDeposit extends \yii\db\ActiveRecord
{
    
    const MIN_AMOUNT_DEPOSIT=1;
    
    const CURRENCY_CODE_USD=840;
    const CURRENCY_CODE_RUB=643;
    
    const CURRENCY_NAME_USD='USD';
    const CURRENCY_NAME_RUB='RUB';
     
    const PAYMENT_WMR=1;
    const PAYMENT_WMZ=2;
    const PAYMENT_PERFECT_MONEY=3;
   /* 
    const PAYMENT_YANDEX_MONEY=4;
    const PAYMENT_QIWI=5;
    const PAYMENT_VISA_OTHERS = 6;
    const PAYMENT_MASTER_CARD_OTHERS = 7;*/
    
    
    const STATUS_CREATED=0;
    const STATUS_SUCCESS=1;
    const STATUS_REJECT=2;
    const STATUS_CANCELED=3;
    const STATUS_MODER=4;
    
    const SCENARIO_DEPOSIT = 'deposit';  
    const SCENARIO_UPDATE = 'update';  
    
    const SCENARIO_CHANGE_STATUS = 'change-status'; 
    const SCENARIO_REJECT = 'change-status-reject'; 
    const SCENARIO_SEND_COMMENT='send-moder-comment';
    
    public $moderid;

    public static function tableName()
    {
        return '{{%user_application_hand_deposit}}';
    }

    public function rules()
    {
        return 
        [
            ['amount', 'required'],
            ['amount', 'integer', 'when' => function($model) {
                                                                        return $model->scenario == self::SCENARIO_DEPOSIT;
                                                                    }],
	    ['amount', 'number','min' => self::MIN_AMOUNT_DEPOSIT, 'when' => function($model) {
                                                                        return $model->scenario == self::SCENARIO_DEPOSIT;
                                                                    }],
            ['amount', 'number','min' =>0.01, 'when' => function($model) {
                                                                        return $model->scenario == self::SCENARIO_UPDATE;
                                                                    }],
            ['amount', 'correctAmount'],
            
            ['paymentsystem_id', 'required'],
            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::getPaymentSystemsArray())],
            
            ['currency', 'integer'],
            ['currency', 'in', 'range' => array_keys(self::getCurrencyArray())],
            
            ['description', 'required'],
            [['description'], 'string', 'max' => 600],
            
            [['usercomment'], 'string', 'max' => 255],
               
            ['moderid', 'required'],
            ['moderid', 'integer'],
            
            ['modercomment', 'required'],
            [['modercomment'], 'string', 'max' => 255],
            
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            
            ['created_at', 'integer'],
            ['updated_at', 'integer'],
            ['usertransaction_id', 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return 
        [
            'id' => 'ID',
            'account_id' => 'Аккаунт ID',
            'amount' => 'Сумма',
            'paymentsystem_id' => 'Платежная система',
            'description' => 'Описание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
            'status' => 'Статус',
            'usercomment'=>'Комментарий',
            'modercomment'=>'Модераторский комментарий',
            'currency'=>'Валюта'
        ];
    }
    
    public function beforeValidate()
    {
        $this->description = \yii\helpers\BaseHtmlPurifier::process($this->description);
        $this->usercomment = \yii\helpers\BaseHtmlPurifier::process($this->usercomment);
        $this->modercomment = \yii\helpers\BaseHtmlPurifier::process($this->modercomment);
	return parent::beforeValidate();
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), 
        [
            self::SCENARIO_DEPOSIT => ['amount','paymentsystem_id','usercomment'],
            self::SCENARIO_UPDATE => ['amount','description'],
            self::SCENARIO_CHANGE_STATUS => ['status'],
            self::SCENARIO_SEND_COMMENT=>['modercomment','moderid'],
            self::SCENARIO_REJECT => ['status','modercomment'],
        ]);
    }
    
    public function behaviors()
    {
        return 
            [
                TimestampBehavior::className(),
            ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) 
            {
                $this->status= self::STATUS_CREATED;
                $user=User::findOne(Yii::$app->user->getId());
                $this->account_id=$user->account->user_id;
                $this->description=$this->installDescription();
            }
            return true;
        }
        return false;
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getPaymentSystemsArray()
    {
        return 
        [
            self::PAYMENT_WMR=>'WMR',
            self::PAYMENT_WMZ=>'WMZ',
            self::PAYMENT_PERFECT_MONEY=>'Perfect Money'
        ];
    }
    
    public static function getCurrencyArray()
    {
        return 
        [
            self::CURRENCY_CODE_USD=>self::CURRENCY_NAME_USD,
            self::CURRENCY_CODE_RUB=>self::CURRENCY_NAME_RUB,
        ];
    }
    
    public static function getStatusesArray()
    {
        return 
        [
            self::STATUS_CREATED =>'Ожидает оплаты',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отменена',
            self::STATUS_CANCELED => 'Отклонена',
            self::STATUS_MODER => 'На модерации',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    protected function getStatusHumor()
    {
        switch ($this->status)
        {
            case self::STATUS_CREATED: return 'label-warning';
            case self::STATUS_SUCCESS: return 'label-success';
            case self::STATUS_REJECT: return 'label-danger';
            default :return 'label-info';    
        }
    }
    
    public function getCurrencyName()
    {
        $currency = self::getCurrencyArray();
        if(in_array($this->currency, array_keys($currency)))
            return $currency[$this->currency];       
        return '<p class="label label-warning">Ошибка</p>';
    }
    
    public function getStatusName()
    {
       $statuses = self::getStatusesArray();
        if(in_array($this->status, array_keys($statuses)))
            return '<p class="has-tooltip label '.$this->statushumor.'" title="'.$statuses[$this->status].'">'.$statuses[$this->status].'</p>';       
        return '<p class="has-tooltip label label-warning" title="Ошибка">Ошибка</p>';
    }
    
    public function getPaymentSystemName()
    {
        $statuses = self::getPaymentSystemsArray();
        return isset($statuses[$this->paymentsystem_id]) ? $statuses[$this->paymentsystem_id] : '';
    }
    public function getRequisites()
    {
        switch ($this->paymentsystem_id)
        {
            case self::PAYMENT_WMR: return 'R412436129025';
            case self::PAYMENT_WMZ: return 'Z145656634628';
            case self::PAYMENT_PERFECT_MONEY: return 'U1120013';
            default : return 'Ошибка';
        }
    }

    public function getPayData()
    {
        return ['amount'=>$this->amount,'currency'=>$this->currencyname,'requisites'=>$this->requisites];
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    
    /*-------------------------------SETTERS----------------------------------*/
    
    public function installDescription()
    {
        $this->currency=$this->setCurrency();
        $user=User::findOne(Yii::$app->user->getId());
        return 'Пополнение аккаунта #'.$user->account->user_id.' на сумму:'.number_format($this->amount, 2, '.', '').' '.$this->currencyName.
                       ' через платежную систему '.$this->getPaymentSystemName();
    }
    
    public function setCurrency()
    {
        switch ($this->paymentsystem_id)
        {
            case self::PAYMENT_WMR:return self::CURRENCY_CODE_RUB;
            case self::PAYMENT_WMZ:return self::CURRENCY_CODE_USD;
            case self::PAYMENT_PERFECT_MONEY: return self::CURRENCY_CODE_USD;
            default : return 0;
        }
            
    }
    /*-------------------------------SETTERS----------------------------------*/
    
    /*------------------------------VALIDATORS--------------------------------*/
    public function correctAmount($attribute, $params)
    {
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            $this->addError($attribute, 'Вашего счета не существует');
        if($this->amount<self::MIN_AMOUNT_DEPOSIT&&$this->scenario==self::SCENARIO_DEPOSIT)
            $this->addError($attribute, 'Минимальная сумма для пополнения счета '.self::MIN_AMOUNT_DEPOSIT);
    }
    /*-----------------------------VALIDATORS---------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'account_id']);
    }
    
    public function getUseraccount()
    {
        return $this->hasOne(Useraccount::className(), ['user_id' => 'account_id']);
    }
    
    public function getUsertransaction()
    {
        return $this->hasOne(Usertransaction::className(), ['id' => 'usertransaction_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    /*------------------------------PERMISSION--------------------------------*/
    public function canApprove()
    {
        if
        (
            $this->status == self::STATUS_MODER||
            $this->status ==self::STATUS_CREATED
        )
            return true;
        else
            return false;
    }
    
    public function canReject()
    {
        if
        (
            $this->status == self::STATUS_MODER||
            $this->status ==self::STATUS_CREATED
        )
            return true;
        else
            return false;
    }
    
    public function canUpdate()
    {
        if
        (
            $this->status == self::STATUS_MODER||
            $this->status ==self::STATUS_CREATED
        )
            return true;
        else
            return false;
    }
    /*------------------------------PERMISSION--------------------------------*/
    
    /*--------------------------------ACTIONS---------------------------------*/
    public function actReject()
    {
        $transaction =  Yii::$app->db->beginTransaction();
        try 
        {
            $this->scenario=self::SCENARIO_REJECT;
            $this->status=self::STATUS_REJECT;
            if(!$this->save())
                return FALSE;
            $userTransaction=$this->usertransaction;
            $userTransaction->scenario=Usertransaction::SCENARIO_APPROVE;
            $userTransaction->status=Usertransaction::STATUS_REJECT;
            if(!$userTransaction->save())
                return FALSE;
            $transaction->commit();
            return TRUE;   
        } 
        catch (Exception $e) 
        {
            $transaction->rollBack();
            return FALSE;
        }
    }
    
    public function actApprove()
    {
        $transaction =  Yii::$app->db->beginTransaction();
        try 
        {
            $this->scenario=self::SCENARIO_CHANGE_STATUS;
            $this->status=self::STATUS_SUCCESS;
            if(!$this->save())
                return FALSE;
            $userTransaction=$this->usertransaction;
            $userTransaction->scenario=Usertransaction::SCENARIO_APPROVE;
            $userTransaction->amount=$this->amount;
            $userTransaction->description=$this->description;
            $userTransaction->status=Usertransaction::STATUS_SUCCESS;
            if(!$userTransaction->save())
                return FALSE;
            $userAccount=$this->useraccount;
            $userChangeBanance=new Userchangebalance();
            $userChangeBanance->before_balance=$userAccount->balance;
            $userChangeBanance->account_id=$userAccount->user_id;
            $userChangeBanance->usertransaction_id=$userTransaction->id;

            $userAccount->balance+=$userTransaction->amount;
            if(!$userAccount->save())
                return FALSE;

            $userChangeBanance->after_balance=$userAccount->balance;
            $userChangeBanance->amount=$userTransaction->amount;
            $userChangeBanance->type_id=Usertransaction::TYPE_DEPOSIT;
            if(!$userChangeBanance->save())
                return FALSE;
            
            $transaction->commit();
            return TRUE;   
        } 
        catch (Exception $e) 
        {
            $transaction->rollBack();
            return FALSE;
        }
    }

    /*--------------------------------ACTIONS---------------------------------*/
    public function depositMoney()
    {
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            return FALSE;
         $transaction =  Yii::$app->db->beginTransaction();
        try 
        {
            $userTransaction=new Usertransaction();
            $userTransaction->scenario = Usertransaction::SCENARIO_DEPOSIT;
            $userTransaction->amount= $this->amount;
            $userTransaction->account_id=$user->account->user_id;
            $userTransaction->paymentsystem_id=$this->paymentsystem_id;
            $userTransaction->type_id= Usertransaction::TYPE_DEPOSIT;
            $userTransaction->description=$this->installDescription();
            $userTransaction->status=Usertransaction::STATUS_CREATED;
            if(!$userTransaction->save())
                    return FALSE; 
            $this->usertransaction_id=$userTransaction->id;
            if(!$this->save())
                    return FALSE; 
            $transaction->commit();
            $this->sendClientInfoMail();
            $this->sendModerInfoMail();
                return TRUE;
        } 
        catch (Exception $e) 
        {
            $transaction->rollBack();
            return FALSE;
        }
    }
    
    private function sendClientInfoMail()
    {
        $user=$this->user;
        Yii::$app->mailer->compose('userApplicationDeposit', ['user' => $user,'requisites'=>$this->requisites, 'application'=>$this])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($user->email)
                        ->setSubject('Пополнение счета в системе ' . Yii::$app->name)
                        ->send();
    }
    
    private function sendModerInfoMail()
    {
        $user=$this->user;
        Yii::$app->mailer->compose('moderApplicationDeposit', ['user' => $user,'application'=>$this])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo(Yii::$app->params['adminEmail'])
                        ->setSubject('Заявка на ручное пополнение в системе ' . Yii::$app->name)
                        ->send();
    }
}
