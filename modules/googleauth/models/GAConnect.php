<?php
namespace app\modules\googleauth\models;

use app\modules\user\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%applicationwithdrawal}}".
 *
 * @property integer $user_id
 * @property string $value
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */

class GAConnect extends \yii\db\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName(){
        return '{{%googleauth_connect}}';
    }

    public function rules() {
        return [
            [['user_id', 'value'], 'required'],
            ['user_id', 'unique'],
            [['value'], 'string', 'max' => 250],
            [['user_id', 'status','created_at', 'updated_at'], 'integer']
        ];
    }
    
    public function attributeLabels(){
        return [
            'user_id'       => 'Пользователь',
            'value'         => 'Код',
            'status'        => 'Статус',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата обновления',
        ];
    }
    
    public function behaviors(){
        return [TimestampBehavior::className()];
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert)  {
                $this->status = self::STATUS_OFF;
            }
            return true;
        }
        return false;
    }
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function statusArray(){
        return [
            self::STATUS_OFF =>'Выключена',
            self::STATUS_ON => 'Включена',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getStatus_name(){
        $status = self::statusArray();
        return isset($status[$this->status]) ? $status[$this->status] : 'error';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
}
