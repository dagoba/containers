<?php

namespace app\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use app\modules\user\models\Usertransaction;
use app\modules\user\models\Userchangebalance;

/**
 * This is the model class for table "{{%user_backend_finance_operations}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $operation_type
 * @property string $amount
 * @property string $comment
 * @property integer $moder_id
 * @property string $moder_ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserBackendFinanceOperations extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE='create';
    const ACCOUNT_MAIN=0;
    const ACCOUNT_ADVERTISER=1;
    const ACCOUNT_PARTNER=2;
    
    const OPERATION_DEPOSIT=0;
    const OPERATION_WITHDRAWAL=1;
    
    const MIN_AMOUNT=0.01;
    
    public $_user;

    public static function tableName()
    {
        return '{{%user_backend_finance_operations}}';
    }

    public function rules()
    {
        return 
        [            
            ['operation_type', 'required'],
            ['operation_type', 'integer'],
            ['operation_type', 'in', 'range' => array_keys($this->operationArray())],
            
            ['amount', 'required'],
            ['amount', 'number','min' => self::MIN_AMOUNT],
            
            
            
            ['description', 'required'],
            [['description'], 'string', 'max' => 255],

            ['paymentsystem_id', 'default', 'value' =>Usertransaction::PAYMENT_LOCAL ],
            ['paymentsystem_id', 'in', 'range' => array_keys(Usertransaction::moderPaySystemArr())],
            
            
            [['user_id','moder_id'], 'integer'],
            [['created_at'], 'string', 'max' => 255],
            [['updated_at'], 'integer'],
            
            [['moder_ip'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'operation_type' => 'Тип операции',
            'amount' => 'Сумма',
            'description' => 'Описание',
            'moder_id' => 'ID модератора',
            'moder_ip' => 'IP модератора',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
            'paymentsystem_id' => 'Платежная система',
        ];
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['amount'],
        ]);
    }
    
   
    
    public function beforeValidate(){
        $this->description = \yii\helpers\BaseHtmlPurifier::process($this->description);
	return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(empty($this->created_at)){
                $this->created_at = time();
                $this->updated_at = time();
            }else{
                 $this->created_at= strtotime($this->created_at);
                $this->updated_at= $this->created_at;
            }
            $this->user_id=$this->_user->id;
            $this->moder_id=Yii::$app->user->identity->getId();
            if(User::isSuperAdmin ()) {              
                $this->moder_ip='0.0.0.0';
            } else{
                $this->moder_ip = (Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
            }
            return true;
        }
        $this->updated_at= time();
        return false;
    }
    
    public function initUser($userID){
        if (empty($userID)) {
            throw new InvalidParamException('User ID cannot be blank.');
        }
        $this->_user = User::findOne(intval($userID));
        if (!$this->_user) {
            throw new InvalidParamException('Wrong user ID.');
        }
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    
    public function getOperationType(){
        $operationType = self::operationArray();
        return isset($operationType[$this->operation_type]) ? $operationType[$this->operation_type] : 'Неизвестно';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
    
    public function getModer(){
        return $this->hasOne(User::className(), ['id' => 'moder_id']);
    }
    
    /*------------------------------RELATIONS---------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public function operationArray(){
        return [
            self::OPERATION_DEPOSIT=>'Зачисление',
            self::OPERATION_WITHDRAWAL=>'Списание'
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    public function execute(){
        $userAccount=$this->_user->account;
        if(!$userAccount){
            return false;
        }
        $transaction =  Yii::$app->db->beginTransaction();
        try {
            if(!$this->save()){
                return false;
            }
            $userTransaction=new Usertransaction();
            switch ($this->operation_type){
                case self::OPERATION_DEPOSIT:{
                    $userTransaction->scenario = Usertransaction::SCENARIO_DEPOSIT;
                    if($this->paymentsystem_id== Usertransaction::PAYMENT_LOCAL){
                        $userTransaction->type_id= Usertransaction::TYPE_DEPOSIT_HAND; 
                    }else{
                        $userTransaction->type_id= Usertransaction::TYPE_DEPOSIT;
                    }
                    break;
                }
                case self::OPERATION_WITHDRAWAL:{
                    $userTransaction->scenario = Usertransaction::SCENARIO_WITHDRAWAL;
                    if($this->paymentsystem_id== Usertransaction::PAYMENT_LOCAL){
                        $userTransaction->type_id= Usertransaction::TYPE_WITHDRAWAL_HAND;
                    }else{
                        $userTransaction->type_id= Usertransaction::TYPE_WITHDRAWAL;
                    }
                    
                    break;
                }
            }
            $userTransaction->created_at =$this->created_at;
            $userTransaction->updated_at = $this->updated_at;
            $userTransaction->amount= $this->amount;
            $userTransaction->account_id=$userAccount->user_id;
            $userTransaction->paymentsystem_id= $this->paymentsystem_id;
            $userTransaction->description=$this->description;
            $userTransaction->status=Usertransaction::STATUS_SUCCESS;
            if(!$userTransaction->save()){
                return FALSE;
            }
            $userChangeBanance=new Userchangebalance();
            $userChangeBanance->before_balance=$userAccount->balance;
            $userChangeBanance->account_id=$userAccount->user_id;
            $userChangeBanance->usertransaction_id=$userTransaction->id;
            switch ($this->operation_type){
                case self::OPERATION_DEPOSIT:{
                    $userAccount->balance+=$userTransaction->amount;
                    $userChangeBanance->type_id=Userchangebalance::TYPE_DEPOSIT;
                    break;
                }
                case self::OPERATION_WITHDRAWAL:{
                    $userAccount->balance-=$userTransaction->amount;
                    $userChangeBanance->type_id=Userchangebalance::TYPE_WITHDRAWAL;
                    break;
                }
            }
            if(!$userAccount->save()){
                return FALSE;
            }
            $userChangeBanance->after_balance=$userAccount->balance;
            $userChangeBanance->amount=$userTransaction->amount; 
            if(!$userChangeBanance->save()){
                return FALSE;
            }
            $transaction->commit();
            $this->sendInfoMail();
            return TRUE;
        } 
        catch (Exception $e) {
            $transaction->rollBack();
            return FALSE;
        }
    }
    
    protected function sendInfoMail(){
        Yii::$app->mailer->compose('moderFinancialOperation', ['model' => $this])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Ручное зачисление/списание в системе ' . Yii::$app->name)
            ->send();
    }
}
