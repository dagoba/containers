<?php

namespace app\modules\user\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\user\models\User;
use app\modules\user\models\Useraccount;
use app\modules\user\models\Usertransaction;
use app\modules\user\models\Userchangebalance;
use app\modules\system\models\SystemAccount;
use app\modules\system\models\SystemChangeBalance;
use app\modules\system\models\SystemTransaction;

/**
 * This is the model class for table "{{%user_carriage_contract}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $creator_id
 * @property string $amount
 * @property string $description
 * @property string $сontainer
 * @property string $route_description
 * @property string $route_sid
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class UserCarriageContract extends \yii\db\ActiveRecord
{
    
    const SCENARIO_CREATE_BY_MODER = 'create-by-moder';
    const SCENARIO_SHANGE_STATUS = 'shange-status';
    const SCENARIO_SHANGE_INFO = 'shange-info'
    ;
    const SCENARIO_MAKE_ACTIVE = 'make-active';
    
    const STATUS_CREATED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED = 4;

    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%user_carriage_contract}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],

            [['creator_id'], 'integer'],
            
            [['amount'], 'required'],
            [['amount'], 'number'],
            
            [['receivable_amount'], 'required'],
            [['receivable_amount'], 'number'],
            
            [['description'], 'required'],
            [['description'], 'string', 'max' => 1000],
            
            [['сontainer'], 'required'],
            [['сontainer'], 'string', 'max' => 255],
            
            [['route_description'], 'required'],
            [['route_description'], 'string', 'max' => 1000],
            
            [['route_sid'], 'required'],
            [['route_sid'], 'string', 'max' => 1000],
            
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys($this->statusArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app','Пользователь'),
            'creator_id' => Yii::t('app','Менеджер'),
            'amount' => Yii::t('app','Стоимость аренды'),
            'receivable_amount' => Yii::t('app','Сумма к получению'),
            'description' => Yii::t('app','Описание'),
            'сontainer' => Yii::t('app','Контейнер'),
            'route_description' => Yii::t('app','Описание маршрута'),
            'route_sid' => Yii::t('app','SID маршрута'),
            'created_at' => Yii::t('app','Дата создания'),
            'updated_at' => Yii::t('app','Дата редактирования'),
            'status' => Yii::t('app','Статус'),
        ];
    }
    
    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->creator_id = Yii::$app->user->id;
                $this->status = self::STATUS_CREATED;
            }
            return true;
        }
        return false;
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CREATE_BY_MODER => ['user_id','amount','description','сontainer','receivable_amount'],
            self::SCENARIO_SHANGE_STATUS => ['status'],
            self::SCENARIO_SHANGE_INFO => ['amount','description','сontainer','receivable_amount'],
            self::SCENARIO_MAKE_ACTIVE => ['route_description','route_sid','status'],

        ]);
    }
    
    public function beforeValidate(){
        $this->description = \yii\helpers\BaseHtmlPurifier::process($this->description);
        $this->route_description = \yii\helpers\BaseHtmlPurifier::process($this->description);
	return parent::beforeValidate();
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    
    public function getStatus_name(){
        $arr = $this->statusArray();
        return isset($arr[$this->status]) ? $arr[$this->status] : Yii::t('app','Ошибка');
    }
    
    protected function getMood_label()
    {
        switch ($this->status)
        {
            case self::STATUS_CREATED: return 'label-info';
            case self::STATUS_ACTIVE: return 'label-warning';
            case self::STATUS_COMPLETED: return 'label-success';
            case self::STATUS_CANCELED: return 'label-danger';
            default :return 'label-info';    
        }
    }
    
    
    public function getMood_status(){
        $arr = $this->statusArray();
        if(in_array($this->status, array_keys($arr)))
            return '<p title="'.
                        $arr[$this->status].'" class="has-tooltip label '.
                        $this->Mood_label.'">'.$arr[$this->status].'</p>';       
        return '<p  title="'.$arr[$this->status].'"class="label label-warning has-tooltip">'.
                    Yii::t('app','Ошибка').
                '</p>';
    }
    
    

    /*-------------------------------GETTERS----------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
    
    public function getCreator(){
        return $this->hasOne(User::className(),['id' => 'creator_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public function statusArray(){
        return [
            self::STATUS_CREATED => Yii::t('app','Создана'),
            self::STATUS_ACTIVE => Yii::t('app','Активна'),
            self::STATUS_COMPLETED => Yii::t('app','Выполнена'),
            self::STATUS_CANCELED => Yii::t('app','Отменена'),
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*------------------------------PERMISSION--------------------------------*/
    public function canEdit(){
       return ($this->status == self::STATUS_CREATED);
    }
    
    public function canActive(){
        return ($this->status == self::STATUS_CREATED);
    }
    
    public function canComplete(){
        return ($this->status == self::STATUS_ACTIVE);
    }
    
    public function canCanceled(){
        return ($this->status == self::STATUS_CREATED);
    }
    
    public function canDelete(){
        return (in_array($this->status, [self::STATUS_CREATED, self::STATUS_CANCELED]));
    }
    /*------------------------------PERMISSION--------------------------------*/
    
    /*--------------------------------ACTIONS---------------------------------*/
    public function actActive(){
        $transaction =  Yii::$app->db->beginTransaction();
        try {
            $this->status=self::STATUS_ACTIVE;
            if(!$this->save()){
                return false;
            }
            $userTransaction = new Usertransaction();
            $userTransaction->scenario = Usertransaction::SCENARIO_WITHDRAWAL;
            $userTransaction->status = Usertransaction::STATUS_SUCCESS;
            
            $userTransaction->paymentsystem_id =Usertransaction:: PAYMENT_LOCAL;
            $userTransaction->amount =  $this->amount;
            $userTransaction->account_id = $this->user_id;
            $userTransaction->description = 'Оплата заявки #'.$this->id.' "Услуга аренды контейнера" на сумму '.number_format($this->amount, 2, '.', '').'$';
            $userTransaction->type_id = Usertransaction::TYPE_INTERNAL_WITHDRAWAL;
            if(!$userTransaction->save()){
                return false;
            }
            $userAccount=$this->user->account;
            $userChangeBanance=new Userchangebalance();
            $userChangeBanance->before_balance=$userAccount->balance;
            $userChangeBanance->account_id=$userAccount->user_id;
            $userChangeBanance->usertransaction_id=$userTransaction->id;

            $userAccount->balance-=$userTransaction->amount;
            if(!$userAccount->save()){
                return false;
            }
            $userChangeBanance->after_balance=$userAccount->balance;
            $userChangeBanance->amount=$userTransaction->amount;
            $userChangeBanance->type_id=Usertransaction::TYPE_INTERNAL_WITHDRAWAL;
            if(!$userChangeBanance->save()){
                return false;
            }           
            $systemTransaction = new SystemTransaction();
            $systemTransaction->scenario = SystemTransaction::SCENARIO_DEPOSIT;
            $systemTransaction->status = SystemTransaction::STATUS_SUCCESS;
            $systemTransaction->amount =  $this->amount;
            $systemTransaction->account_id = $this->user_id;
            $systemTransaction->description = 'Начисление средств в размере '.number_format($this->amount, 2, '.', '').'$ за оплату заявки #'.$this->id.' "Услуга аренды контейнера" пользователем id:'.$this->user_id;
            $systemTransaction->type_id = SystemTransaction::TYPE_DEPOSIT;
            if(!$systemTransaction->save()){
                return false;
            }
            $systemAccount= SystemAccount::account();
            $systemChangeBanance=new SystemChangeBalance();
            $systemChangeBanance->before_balance=$systemAccount->balance;
            $systemChangeBanance->transaction_id=$systemTransaction->id;

            $systemAccount->balance+=$systemTransaction->amount;
            if(!$systemAccount->save()){
                return false;
            }
            $systemChangeBanance->after_balance=$systemAccount->balance;
            $systemChangeBanance->amount=$systemAccount->balance;
            $systemChangeBanance->type_id= SystemTransaction::TYPE_DEPOSIT;
            if(!$systemChangeBanance->save()){
                return false;
            }
            $transaction->commit();
            return true;   
        } 
        catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
    
    public function actComplete(){
        $this->scenario=self::SCENARIO_SHANGE_STATUS;
        $this->status=self::STATUS_COMPLETED;
        return $this->save();
    }
    
    public function actCanceled(){
        $this->scenario=self::SCENARIO_SHANGE_STATUS;
        $this->status=self::STATUS_CANCELED;
        return $this->save();
    }
    /*--------------------------------ACTIONS---------------------------------*/

    public function checkUserMoneyForPay(){
        if(($account = (new yii\db\Query())
            ->select('balance')
            ->from(Useraccount::tableName())
            ->where(['user_id'=>$this->user_id])
            ->one())==null){
            return false;
        }
        if($account['balance']<$this->amount){
            return false;
        }
        return true;
    }
}

