<?php

namespace app\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%userchangepassword}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $useragent
 * @property integer $created_at
 */
class UserChangePassword extends \yii\db\ActiveRecord
{
    public static function tableName(){
        return '{{%user_change_password}}';
    }

    public function rules(){
        return 
        [
            ['user_ip','ip', 'negation' => true],
            
            [['user_id', 'created_at'], 'integer'],
            [['user_ip', 'useragent'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_ip' => 'User Ip',
            'useragent' => 'Useragent',
            'created_at' => 'Created At',
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            if($insert) 
            {
                $this->user_id=Yii::$app->user->getId();
                $this->useragent=getenv("HTTP_USER_AGENT");
                $this->user_ip= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '127.0.0.1';
            }
            return true;
        }
        return false;
    }
    
    public function behaviors()
    {
        return 
        [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ],
        ];
    }
    
    public static function createStat()
    {
        $model= new UserChangePassword();
        return $model->save();
    }
}
