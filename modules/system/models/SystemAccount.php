<?php

namespace app\modules\system\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%system_account}}".
 *
 * @property string $balance
 * @property integer $updated_at
 */
class SystemAccount extends \yii\db\ActiveRecord
{

    public static function tableName(){
        return '{{%system_account}}';
    }

    public function rules(){
        return [
            [['balance'], 'required'],
            [['balance'], 'number'],
            [['updated_at'], 'integer']
        ];
    }

    public function attributeLabels(){
        return [
            'balance' => 'Баланс',
            'updated_at' => 'Дата',
        ];
    }
    
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) 
                {$this->balance = '0.00';}
            return true;
        }
        return false;
    }
    
    public static function account(){
        if(($model=self::find()->one())!=NULL){
                return $model;
        }
        return false;
    }
}