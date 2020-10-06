<?php

namespace app\modules\system\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%system_change_balance}}".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property double $before_balance
 * @property double $after_balance
 * @property double $amount
 * @property integer $type_id
 * @property string $account_ip
 * @property integer $created_at
 */
class SystemChangeBalance extends \yii\db\ActiveRecord
{
    const TYPE_DEPOSIT = 1;
    const TYPE_DEPOSIT_FOR_CLICK = 2;
    const TYPE_WITHDRAWAL = 3;
    const TYPE_WITHDRAWAL_FOR_CLICK = 4;
    
    public static function tableName(){
        return '{{%system_change_balance}}';
    }

    public function rules(){
        return 
        [
            ['before_balance', 'required'],
            ['before_balance', 'number'],
            
            ['after_balance', 'required'],
            ['after_balance', 'number'],
            
            ['amount', 'required'],
            ['amount', 'number','min' => 0.0001],
            
            ['type_id','required'],
            ['type_id','integer'],
            ['type_id', 'in', 'range' => array_keys(self::getTypesArray())],
            
            [['account_ip'], 'string', 'max' => 255],
            
            ['transaction_id','required'],
            ['transaction_id','integer'],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'before_balance' => 'Before Balance',
            'after_balance' => 'After Balance',
            'amount' => 'Amount',
            'type_id' => 'Type ID',
            'account_ip' => 'Account Ip',
            'created_at' => 'Created At',
        ];
    }
    
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ],
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->account_ip = (Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
            }
            return true;
        }
        return false;
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getTypesName(){
        $statuses = self::getTypesArray();
        return isset($statuses[$this->type_id]) ? $statuses[$this->type_id] : '';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getTypesArray(){
        return 
        [
            self::TYPE_DEPOSIT => 'Пополнение',
            self::TYPE_DEPOSIT_FOR_CLICK => 'Пополнение за клик',
            self::TYPE_WITHDRAWAL => 'Вывод',
            self::TYPE_WITHDRAWAL_FOR_CLICK => 'Снятие за клик'
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
}
