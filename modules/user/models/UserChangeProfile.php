<?php

namespace app\modules\user\models;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "{{%userchangeprofile}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_agent
 * @property string $user_ip
 * @property string $before
 * @property string $after
 * @property integer $created_at
 */
class UserChangeProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_change_profile}}';
    }

    public function rules()
    {
        return [
            ['user_ip','ip', 'negation' => true],

            [['user_id', 'created_at'], 'integer'],
            [['before', 'after'], 'string'],
            [['user_agent', 'user_ip'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_agent' => 'User Agent',
            'user_ip' => 'User Ip',
            'before' => 'Before',
            'after' => 'After',
            'created_at' => 'Created At',
        ];
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
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            if($insert) 
            {
                $this->user_id=Yii::$app->user->getId();
                $this->user_agent=getenv("HTTP_USER_AGENT");
                $this->user_ip= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '127.0.0.1';
            }
            return true;
        }
        return false;
    }
    
    
    public static function saveChanges($before,$after)
    {
        $model=new UserChangeProfile();
        $model->before=$before;
        $model->after=$after;
        $model->save();
    }
}
