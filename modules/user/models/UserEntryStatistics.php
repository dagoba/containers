<?php

namespace app\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user_entry_statistics}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $created_at
 */
class UserEntryStatistics extends \yii\db\ActiveRecord
{

    public static function tableName(){
        return '{{%user_entry_statistics}}';
    }

    public function rules(){
        return [
            [['user_id', 'created_at'], 'integer'],
            [['ip'], 'string', 'max' => 100],
            [['user_agent'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'created_at' => 'Дата',
            'location'=> 'Местоположение',
            'user'=>'Пользователь'
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
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if(User::isSuperAdmin ())   {
                    $this->user_agent = 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; rv:1.7.3) Gecko/20040913 Firefox/0.10';
                    $this->ip='0.0.0.0';
                }
                else{
                    
                    $this->user_agent = getenv("HTTP_USER_AGENT");
                    $this->ip = (Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
                }
                $this->user_id=Yii::$app->user->getId();
            }
            return true;
        }
        return false;
    }
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){  
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    public static function create(){
        $model= new UserEntryStatistics();
        return $model->save();
    }
    
    public function getLocation(){
        if(Yii::$app->hasModule('geo')){
            if($geo=Yii::$app->getModule('geo')->sypexgeoManager->get($this->ip)){
                return 
                (($geo['country'])? $geo['country']['name_ru']:'').' '.
                (($geo['region'])? $geo['region']['name_ru']:'').' '.
                (($geo['city'])? $geo['city']['name_ru']:'');
            }else{
                return '-';
            }
            
        } else{
            return '-';
        }
    }
}
