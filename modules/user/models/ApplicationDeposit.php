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
class ApplicationDeposit extends \yii\db\ActiveRecord
{
    
    const MIN_AMOUNT_DEPOSIT=1;

    const PAYMENT_INTERKASSA=8;
   /* 
    const PAYMENT_WMR=1;
    const PAYMENT_WMZ=2;
    const PAYMENT_PERFECT_MONEY=3;
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
    
    const SCENARIO_CHANGE_STATUS = 'change-status'; 
    const SCENARIO_REJECT = 'change-status-reject'; 
    const SCENARIO_SEND_COMMENT='send-moder-comment';
    

    public static function tableName(){
        return '{{%user_application_deposit}}';
    }

    public function rules(){
        return 
        [
            ['amount', 'required'],
            ['amount', 'integer'],
	    ['amount', 'number','min' => self::MIN_AMOUNT_DEPOSIT],
            ['amount', 'correctAmount'],
            
            ['paymentsystem_id', 'required'],
            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::getPaymentSystemsArray())],
            
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
    
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'account_id' => 'Аккаунт ID',
            'amount' => 'Сумма, USD',
            'paymentsystem_id' => 'Платежная система',
            'description' => 'Описание',
            'created_at' => 'Дата созадния',
            'updated_at' => 'Дата редактирования',
            'status' => 'Статус',
            'usercomment'=>'Комментарий',
            'modercomment'=>'Модераторский комментарий'
        ];
    }
    
    public function beforeValidate(){
        $this->description = \yii\helpers\BaseHtmlPurifier::process($this->description);
        $this->usercomment = \yii\helpers\BaseHtmlPurifier::process($this->usercomment);
        $this->modercomment = \yii\helpers\BaseHtmlPurifier::process($this->modercomment);
	return parent::beforeValidate();
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), 
        [
            self::SCENARIO_DEPOSIT => ['amount','paymentsystem_id'],
            self::SCENARIO_CHANGE_STATUS => ['status'],
            self::SCENARIO_SEND_COMMENT=>['modercomment','moderid'],
            self::SCENARIO_REJECT => ['status','modercomment'],
        ]);
    }
    
    public function behaviors(){
        return [
                TimestampBehavior::className(),
            ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert)  {
                $this->status = self::STATUS_CREATED;
                $this->account_id = Yii::$app->user->getId();
                $this->description = $this->installDescription();
            }
            return true;
        }
        return false;
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getPaymentSystemsArray(){
        return [
            self::PAYMENT_INTERKASSA=>'Interkassa',
        ];
    }
    
    public static function getStatusesArray(){
        return [
            self::STATUS_CREATED =>'Ожидает оплаты',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена',
            self::STATUS_CANCELED => 'Отклонена',
            self::STATUS_MODER => 'На модерации',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    protected function getStatusHumor(){
        switch ($this->status){
            case self::STATUS_CREATED: return 'label-warning';
            case self::STATUS_SUCCESS: return 'label-success';
            case self::STATUS_REJECT: return 'label-danger';
            default :return 'label-info';    
        }
    }
    
    public function getStatusName(){
        $statuses = self::getStatusesArray();
        if(in_array($this->status, array_keys($statuses)))
            return '<p title="'.$statuses[$this->status].'" class="has-tooltip label '.$this->statushumor.'">'.$statuses[$this->status].'</p>';       
        return '<p  title="'.$statuses[$this->status].'"class="label label-warning has-tooltip">Ошибка</p>';
    }
    
    public function getPaymentSystemName(){
        $statuses = self::getPaymentSystemsArray();
        return isset($statuses[$this->paymentsystem_id]) ? $statuses[$this->paymentsystem_id] : '';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*-------------------------------SETTERS----------------------------------*/
    
    public function installDescription()
    {
        $user=User::findOne(Yii::$app->user->getId());
        return 'Пополнение аккаунта #'.$user->account->user_id.' на сумму:'.number_format($this->amount, 2, '.', '').'$'.
                       ' через платежную систему '.$this->getPaymentSystemName();;   
    }
    /*-------------------------------SETTERS----------------------------------*/
    
    /*------------------------------VALIDATORS--------------------------------*/
    public function correctAmount($attribute, $params){
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            $this->addError($attribute, 'Вашего счета не существует');
        if($this->amount<self::MIN_AMOUNT_DEPOSIT)
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
   /* public function actRejectWithdrawal()
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
    }*/
    public function actAutoApproveDeposit()
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
    public function depositMoney(){
        $user=User::findOne(Yii::$app->user->getId());
        if(!$user->account)
            return FALSE;
        $transaction =  Yii::$app->db->beginTransaction();
        try {
            $userTransaction=new Usertransaction();
            $userTransaction->scenario = Usertransaction::SCENARIO_DEPOSIT;
            $userTransaction->amount= $this->amount;
            $userTransaction->account_id=$user->account->user_id;
            $userTransaction->paymentsystem_id=$this->paymentsystem_id;
            $userTransaction->type_id= Usertransaction::TYPE_DEPOSIT;
            $userTransaction->description=$this->installDescription();
            $userTransaction->status=Usertransaction::STATUS_MODER;
            if(!$userTransaction->save())
                    return FALSE; 
            $this->usertransaction_id=$userTransaction->id;
            if(!$this->save())
                    return FALSE; 
            $transaction->commit();
                return TRUE;
        } catch (Exception $e) {
            $transaction->rollBack();
            return FALSE;
        }
    }
}
