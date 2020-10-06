<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_useraccount".
 *
 * @property integer $user_id
 * @property string $balance
 * @property integer $updated_at
 */
class Useraccount extends ActiveRecord
{
    public static function tableName(){
        return '{{%user_account}}';
    }

    public function rules(){
        return [
            [['user_id'], 'required'],
            [['user_id', 'updated_at'], 'integer'],
            [['balance'], 'number'],
            [['user_id'], 'unique']
        ];
    }

    public function attributeLabels(){
        return [
            'user_id' => Yii::t('app-account', 'USER_ACCOUNT_USER_ID'),
            'balance' => Yii::t('app-account', 'USER_ACCOUNT_BALANCE'),
            'updated_at' => Yii::t('app-account', 'USER_ACCOUNT_UPDATED'),
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
            if ($insert) {
                $this->balance = '0.00';
            }
            return true;
        }
        return false;
    }
}
