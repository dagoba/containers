<?php

namespace app\modules\system\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%system_transaction}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $type_id
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $description
 * @property integer $status
 */
class SystemTransaction extends ActiveRecord
{
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAWAL = 3;
    
    const STATUS_CREATED = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_REJECT = 3;
    const STATUS_CANCELED = 5;
    const STATUS_MODER = 7;
    
    const SCENARIO_DEPOSIT = 'deposit-transaction';
    const SCENARIO_WITHDRAWAL = 'withdrawal-transaction';
    
    const SCENARIO_APPROVE = 'approve-transaction';
    
    public static function tableName(){
        return '{{%system_transaction}}';
    }

    public function rules(){
        return [
            ['amount', 'required'],
	    ['amount', 'number','min' => 0.0001],

            ['account_id', 'required'],
            ['account_id', 'integer'],
            
            ['status', 'required'],
            ['status', 'default', 'value' => self::STATUS_CREATED],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys($this->statusArray())],
            
            ['type_id', 'required'],
            ['type_id', 'integer'],
            ['type_id', 'in', 'range' => array_keys($this->typeArray())],
            
            ['description', 'required'],
            ['description', 'string', 'max' => 256],

            [['created_at','updated_at'], 'integer']
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'account_id' => Yii::t('app','Аккаунт'),
            'type_id' => Yii::t('app','Тип'),
            'amount' => Yii::t('app','Сумма'),
            'created_at' => Yii::t('app','Дата создания'),
            'updated_at' => Yii::t('app','Дата изменения'),
            'description' => Yii::t('app','Описание'),
            'status' => Yii::t('app','Статус'),
        ];
    }
    
    public function behaviors(){
        return[TimestampBehavior::className()];
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), 
        [
            self::SCENARIO_DEPOSIT => ['amount','description','type_id','status','account_id'],
            self::SCENARIO_WITHDRAWAL => ['amount','description','type_id','status','account_id'], 
           
            self::SCENARIO_APPROVE => ['status']
        ]);
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getType_name(){
        $arr = self::getTypesArray();
        return isset($arr[$this->type_id]) ? $arr[$this->type_id] : 
            Yii::t('app','Ошибка');
    }
    
    public function getStatus_name(){
        $arr = $this->statusArray();
        return isset($arr[$this->status]) ? $arr[$this->status]: 
            Yii::t('app','Ошибка');
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public function typeArray(){
        return [
            self::TYPE_DEPOSIT => Yii::t('app','Начисление'),
            self::TYPE_WITHDRAWAL => Yii::t('app','Снятие'),
        ];
    }
    
    public function statusArray(){
        return [
            self::STATUS_CREATED => Yii::t('app','Создана'),
            self::STATUS_SUCCESS => Yii::t('app','Подтверждена'),
            self::STATUS_REJECT => Yii::t('app','Отклонена'),
            self::STATUS_CANCELED => Yii::t('app','Отменена'),
            self::STATUS_MODER => Yii::t('app','На модерации'),
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/  
}
