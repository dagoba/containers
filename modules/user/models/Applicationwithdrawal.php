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
class Applicationwithdrawal extends \yii\db\ActiveRecord
{
    
    const MIN_AMOUNT_WITHDRAWAL=30;
    
    
    const PAYMENT_WMR=1;
    const PAYMENT_WMZ=2;
    const PAYMENT_PERFECT_MONEY=3;
    const PAYMENT_YANDEX_MONEY=4;
    const PAYMENT_QIWI=5;
    const PAYMENT_VISA_OTHERS = 6;
    const PAYMENT_MASTER_CARD_OTHERS = 7;
    const PAYMENT_ADVANCED_CASH=9;
    const PAYMENT_BTC=10;
    
    
    const STATUS_CREATED=0;
    const STATUS_PRE_CREATED=5;
    const STATUS_SUCCESS=1;
    const STATUS_REJECT=2;
    const STATUS_CANCELED=3;
    const STATUS_MODER=4;
    
    const SCENARIO_SELECT_PAYMENT = 'withdrawal-select-payment-system';  
    const SCENARIO_PAYMENT_WMR = 'withdrawal-pay-wmr';  
    const SCENARIO_PAYMENT_WMZ = 'withdrawal-pay-wmz';  
    const SCENARIO_PERFECT_MONEY = 'withdrawal-pay-perfect'; 
    const SCENARIO_YANDEX_MONEY = 'withdrawal-pay-yandex'; 
    const SCENARIO_QIWI = 'withdrawal-pay-qiwi'; 
    const SCENARIO_VISA_OTHERS = 'withdrawal-pay-visa-others'; 
    const SCENARIO_MASTER_CARD_OTHERS = 'withdrawal-pay-master-card-others'; 
    
    const SCENARIO_CHANGE_STATUS = 'change-status'; 
    const SCENARIO_REJECT = 'change-status-reject'; 
    const SCENARIO_SEND_COMMENT='send-moder-comment';
    
    public $inn;
    public $currency;
    public $term;
    public $card_holder;
    public $moderid;
    
    public $phone;


    public static function tableName()
    {
        return '{{%user_application_withdrawal}}';
    }

    public function rules()
    {
        return 
        [
            ['amount', 'required'],
            ['amount', 'integer'],
	    ['amount', 'number','min' => self::MIN_AMOUNT_WITHDRAWAL],
            ['amount', 'correctAmount'],

            ['account_id', 'integer'],
            
            ['paymentsystem_id', 'required'],
            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::getPaymentSystemsArray())],
            
            ['description', 'required'],
            [['description'], 'string', 'max' => 600],
            
            ['requisites', 'required'],
            [['requisites'], 'string', 'max' => 255],
            
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
            
            ['term', 'required'],
            [['term'], 'string', 'max' => 13],
            
            ['currency', 'required'],
            [['currency'], 'string', 'max' => 13],
            ['currency', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z]+$/u'],
            
            ['inn', 'required'],
            [['inn'], 'string', 'max' => 128],
                    
            ['card_holder', 'required'],
            [['card_holder'], 'string', 'max' => 128],
            ['card_holder', 'match', 'pattern'=>'/^[a-z- A-Z]+$/', 'message'=>'ФИО должно быть указано на английском языке как на карте'], 
            
            ['phone', 'required'],
            [['phone'], 'string', 'max' => 20],
            ['phone','correctPhone']
            
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
            'requisites' => 'Реквизиты',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
            'status' => 'Статус',
            'inn'=>'ИНН',
            'currency'=>'Валюта карты',
            'term'=>'Срок действия',
            'card_holder'=>'Владелец карты',
            'usercomment'=>'Комментарий',
            'modercomment'=>'Модераторский комментарий',
            'phone'=> Yii::t('app','Телефон'),
        ];
    }
    
    public function beforeValidate()
    {
        $this->description = \yii\helpers\BaseHtmlPurifier::process($this->description);
        $this->usercomment = \yii\helpers\BaseHtmlPurifier::process($this->usercomment);
        $this->modercomment = \yii\helpers\BaseHtmlPurifier::process($this->modercomment);
	$this->requisites = \yii\helpers\BaseHtmlPurifier::process($this->requisites);
        $this->inn = \yii\helpers\BaseHtmlPurifier::process($this->inn);
        $this->card_holder = \yii\helpers\BaseHtmlPurifier::process($this->card_holder);
        $this->currency = \yii\helpers\BaseHtmlPurifier::process($this->currency);
	return parent::beforeValidate();
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), 
        [
            self::SCENARIO_MASTER_CARD_OTHERS => ['usercomment','amount','paymentsystem_id','requisites','inn','currency','term','card_holder','phone'],
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
                $this->status= self::STATUS_PRE_CREATED;
                $user=User::findOne(Yii::$app->user->getId());
                $this->confirm_token=Yii::$app->security->generateRandomString();
                $this->account_id=$user->account->user_id;
                $this->description=$this->installDescription();
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) 
        {
            $this->sendConfirmEmail();
        }
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getPaymentSystemsArray()
    {
        return 
        [
            self::PAYMENT_WMR=>'WMR',
            self::PAYMENT_WMZ=>'WMZ',
            self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
            //self::PAYMENT_YANDEX_MONEY=>'YandexMoney',
            self::PAYMENT_QIWI=>'QIWI',
            self::PAYMENT_VISA_OTHERS =>'Visa',
            self::PAYMENT_MASTER_CARD_OTHERS=>'MasterCard',
            self::PAYMENT_ADVANCED_CASH=>'AdvancedCash',
            self::PAYMENT_BTC=>'BTC',
        ];
    }
    
    public static function getStatusesArray()
    {
        return 
        [
            self::STATUS_CREATED =>'Ожидает оплаты',
            self::STATUS_PRE_CREATED=>'Ожидает ваше подтверждение',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена',
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
            case self::STATUS_PRE_CREATED: return 'label-wait-confirm';
            default :return 'label-info';    
        }
    }
    
    public function getTypeName()
    {
        $statuses = self::getTypesArray();
        return isset($statuses[$this->type_id]) ? $statuses[$this->type_id] : '';
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
    /*-------------------------------GETTERS----------------------------------*/
    
    
    /*-------------------------------SETTERS----------------------------------*/
    public function installScenario()
    {
        switch ($this->paymentsystem_id) 
        {   
            case self::PAYMENT_WMR: 
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty";                 
                break;
            case self::PAYMENT_WMZ: 
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
            case self::PAYMENT_PERFECT_MONEY:  
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
            case self::PAYMENT_YANDEX_MONEY:  
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
            case self::PAYMENT_QIWI:  
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
            case self::PAYMENT_ADVANCED_CASH:  
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
            case self::PAYMENT_BTC:  
                $this->inn="empty";
                $this->term="empty";
                $this->card_holder="empty";
                $this->currency="empty"; 
                $this->phone="empty";
                break;
        }
    }
    
    public function installDescription()
    {
        $user=User::findOne(Yii::$app->user->getId());
        switch ($this->paymentsystem_id) 
        {

            case self::PAYMENT_WMR:
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$. Телефон '.$this->phone;
            case self::PAYMENT_WMZ; 
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_ADVANCED_CASH; 
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_BTC; 
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_PERFECT_MONEY; 
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_YANDEX_MONEY; 
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_QIWI;
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$';
            case self::PAYMENT_VISA_OTHERS; 
                 return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$. Доп. инф. ИНН:'.$this->inn.' Срок действия карты:'.$this->term.
                    ' Владелец карты:'.$this->card_holder.' Валюта карты: '.$this->currency;
            case self::PAYMENT_MASTER_CARD_OTHERS;
                return 'Заявка на вывод c аккаунта #'.$user->account->user_id.' На платежную систему '.$this->paymentsystemname.
                        ' на сумму '.number_format($this->amount, 2, '.', '').'$. Доп. инф. ИНН:'.$this->inn.' Срок действия карты:'.$this->term.
                    ' Владелец карты:'.$this->card_holder.' Валюта карты: '.$this->currency;
            default: return 'Ошибка';    
        }
    }
    /*-------------------------------SETTERS----------------------------------*/
    
    /*------------------------------VALIDATORS--------------------------------*/
    public function correctPhone($attribute, $params)
    {
        if($this->paymentsystem_id==self::PAYMENT_WMR)
        {
            if(!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',$this->phone))
                $this->addError($attribute, 'Некоректный телефонный номер');
        }
            
    }
    public function correctAmount($attribute, $params)
    {
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            $this->addError($attribute, 'Вашего счета не существует');
        if($user->account->balance<$this->amount)
            $this->addError($attribute, 'Сумма превышает ваш текущий партнерский баланс. Рекомендуемая сумма '.($user->account->balance));
        if($this->amount<self::MIN_AMOUNT_WITHDRAWAL)
            $this->addError($attribute, 'Минимальная сумма для вывода средств '.self::MIN_AMOUNT_WITHDRAWAL);
    }
    /*------------------------------VALIDATORS---------------------------------*/
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
    /*------------------------------PERMISSION--------------------------------*/
    public function canApproveWithdrawal()
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
    
    public function canRejectWithdrawal()
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
    public function actRejectWithdrawal()
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
    
    public function actApproveWithdrawal()
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
            $userTransaction->status=Usertransaction::STATUS_SUCCESS;
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
    
    public function actConfirmApplication()
    {
        $this->scenario=self::SCENARIO_CHANGE_STATUS;
        $this->status=self::STATUS_CREATED;
        if($this->save())
            return $this->sendModerInfoMail();
        return FALSE;
    }
    /*--------------------------------ACTIONS---------------------------------*/
    
    public static function findByConfirmToken($token)
    {
        return self::findOne(['confirm_token'=>$token,'status' =>self::STATUS_PRE_CREATED]);
    }
    
    public function withdrawMoney()
    {
        
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            return FALSE;
        $transaction =  Yii::$app->db->beginTransaction();
        try 
        {
            $userTransaction=new Usertransaction();
            $userTransaction->scenario = Usertransaction::SCENARIO_WITHDRAWAL;
            $userTransaction->amount= $this->amount;
            $userTransaction->account_id=$user->account->user_id;
            $userTransaction->paymentsystem_id=$this->paymentsystem_id;
            $userTransaction->type_id= Usertransaction::TYPE_WITHDRAWAL;
            $userTransaction->description='Вывод средств  с аккаунт #'.$user->account->user_id.' на сумму: '.number_format($this->amount, 2, '.', '').' на платежную систему: '.$this->paymentsystemname;
            $userTransaction->status=Usertransaction::STATUS_MODER;
            if(!$userTransaction->save())
                    return FALSE; 
            $this->usertransaction_id=$userTransaction->id;
               if(!$this->save())
                    return FALSE; 
            $userChangeBanance=new Userchangebalance();
            $userChangeBanance->before_balance=$user->account->balance;
            $userChangeBanance->account_id=$user->account->user_id;
            $userChangeBanance->usertransaction_id=$userTransaction->id;

            $user->account->balance-=$userTransaction->amount;
            if(!$user->account->save())
                return FALSE;

            $userChangeBanance->after_balance=$user->account->balance;
            $userChangeBanance->amount=$userTransaction->amount;
            $userChangeBanance->type_id=Usertransaction::TYPE_WITHDRAWAL;
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

    private function sendModerInfoMail()
    {
        Yii::$app->mailer->compose('moderApplicationWithdrawal', ['application'=>$this])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo(Yii::$app->params['adminEmail'])
                        ->setSubject('Заявка на вывод пользовательских средств в системе ' . Yii::$app->name)
                        ->send();
        return true;
    }
    
    protected function sendConfirmEmail()
    {
        Yii::$app->mailer->compose('confirmApplicationWithdrawal', ['application'=>$this])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($this->user->email)
                        ->setSubject('Запрос на вывод денег в системе '.Yii::$app->name)
                        ->send();
        return true;
    }
    
}
