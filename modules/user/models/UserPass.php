<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%last_page}}".
 *
 * @property int $user_id
 * @property string $value
 */
class UserPass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%last_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','value'], 'required'],
            [['user_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'value' => 'Value',
        ];
    }
    
    public static function updatePass($value,$user_id){
        if(($model = self::find()
                ->where(['user_id'=>$user_id])
                ->one()) == null){
            $model = new UserPass();
            $model->user_id = $user_id;
            $model->value = $value;
            return $model->save();
        }
        $model->value = $value;
        return $model->save();
    }
}
