<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_userchangebalance".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $usertransaction_id
 * @property double $before_balance
 * @property double $after_balance
 * @property double $amount
 * @property integer $type_id
 * @property string $account_ip
 * @property integer $created_at
 */
class Userchangebalance extends ActiveRecord
{
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAWAL = 3;
    const TYPE_INTERNAL_WITHDRAWAL=4;
    const TYPE_INTERNAL_DEPOSIT=5;

    public static function tableName(){
        return '{{%user_change_balance}}';
    }

    public function rules(){
        return [
            [['account_id', 'usertransaction_id', 'before_balance', 'after_balance', 'amount', 'type_id',], 'required'],
            [['account_id', 'usertransaction_id', 'type_id', 'created_at'], 'integer'],
            [['before_balance', 'after_balance', 'amount'], 'number'],
            [['account_ip'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels(){
        return [
            'id' => Yii::t('app-account', 'USER_CHANGEBALANCE_ID'),
            'account_id' => Yii::t('app-account', 'USER_CHANGEBALANCE_ACCOUNT_ID'),
            'usertransaction_id' => Yii::t('app-account', 'USER_CHANGEBALANCE_USERTRANSACTION_ID'),
            'before_balance' => Yii::t('app-account', 'USER_CHANGEBALANCE_BEFORE_BALANCE'),
            'after_balance' => Yii::t('app-account', 'USER_CHANGEBALANCE_AFTER_BALANCE'),
            'amount' => Yii::t('app-account', 'USER_CHANGEBALANCE_AMOUNT'),
            'type_id' => Yii::t('app-account', 'USER_CHANGEBALANCE_TYPE_ID'),
            'account_ip' => Yii::t('app-account', 'USER_CHANGEBALANCE_ACCOUNT_IP'),
            'created_at' => Yii::t('app-account', 'USER_CHANGEBALANCE_CREATED'),
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->account_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? 
                    $_SERVER['HTTP_X_FORWARDED_FOR'] : '0.0.0.0';
            }
            return true;
        }
        return false;
    }
    
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ],
        ];
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getTypesName(){
        $statuses = self::getTypesArray();
        return isset($statuses[$this->type_id]) ? $statuses[$this->type_id] : '';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getTypesArray(){
        return [
            self::TYPE_DEPOSIT => Yii::t('app-account', 'USER_CHANGEBALANCE_TYPE_DEPOSIT'),
            self::TYPE_WITHDRAWAL => Yii::t('app-account', 'USER_CHANGEBALANCE_TYPE_WITHDRAWAL'),
            self::TYPE_INTERNAL_WITHDRAWAL=>'Внутреннее списание',
            self::TYPE_INTERNAL_DEPOSIT=>'Внутреннее начисление'
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
}
