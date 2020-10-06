<?php

namespace app\modules\finance\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%finance_pay_system_type}}".
 *
 * @property int $id
 * @property string $name
 * @property string $logo
 * @property int $currency_id
 * @property string $pattern
 * @property string $help
 * @property int $status
 * @property int $created_at
 */
class FinancePaySystemType extends \yii\db\ActiveRecord
{
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_pay_system_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'currency_id', 'pattern', 'status', 'created_at'], 'required'],
            [['id', 'currency_id', 'status', 'created_at'], 'integer'],
            [['pattern', 'help'], 'string'],
            [['name', 'logo'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'logo' => 'Логотип',
            'currency_id' => 'ID валюты',
            'pattern' => 'Патерн',
            'help' => 'Помощь',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function behaviors(){
        return 
        [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ],
        ];
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/

    public static function statusArray(){
        return [
            self::STATUS_VISIBLE =>'Доступен',
            self::STATUS_HIDDEN  => 'Скрыт',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    
    public function getStatusName(){
        $status = self::statusArray();
        return (in_array($this->status, array_keys($status)))?
            $status[$this->status] : 'Ошибка';
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    
  
    /*------------------------------VALIDATORS--------------------------------*/
    /*-----------------------------VALIDATORS---------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    /*------------------------------RELATIONS---------------------------------*/
    
}
