<?php

namespace app\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\user\models\User;
use app\modules\user\models\UserContactType;


/**
 * This is the model class for table "{{%user_contact}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $contact_id
 * @property string $value
 * @property integer $created_at
 */
class UserContact extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_contact}}';
    }
    public function rules(){
        return [
            [['contact_id', 'value'], 'required'],
            
            ['contact_id', 'required'],
            ['contact_id', 'integer'],
            ['contact_id', 'in', 'range' => array_keys(UserContactType::dataList())],
            
            ['value', 'required'],
            ['value', 'correctContact'],
            ['value', 'string', 'min' => 2, 'max' => 255],
            
            [['user_id', 'contact_id', 'created_at'], 'integer'],
            [['value'], 'string', 'max' => 255]
        ];
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
    
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app','ID пользователя'),
            'contact_id' => Yii::t('app','Тип контакта'),
            'value' => Yii::t('app','Контакт'),
            'created_at' => Yii::t('app','Дата создания'),
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert)  {
                $this->user_id = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getContacttype(){
        return $this->hasOne(UserContactType::className(), 
                                ['id' => 'contact_id']);
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), 
                                ['id' => 'user_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    /*------------------------------VALIDATORS--------------------------------*/
    public function correctContact($attribute, $params){
        if(($contactType= UserContactType::find()
                ->where([
                    'status'=>UserContactType::STATUS_VISIBLE,
                    'id'=>  intval($this->contact_id)
                  ])->one())== null){
            $this->addError($attribute, 'Не верный тип контакта');
        }
        if($contactType->pattern!=null&&!preg_match($contactType->pattern, $this->value)){
            $this->addError('value', 'Не верный формат');
        }                
    }
    /*------------------------------VALIDATORS--------------------------------*/
    
    /*------------------------------PERMISSION--------------------------------*/
    public function canEdit(){
        return true;
    }
    
    public function canDelete(){
        return true;
    }
    /*------------------------------PERMISSION--------------------------------*/
}
