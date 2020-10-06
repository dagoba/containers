<?php

namespace app\modules\user\models;
use yii\behaviors\TimestampBehavior;
use app\modules\user\models\User;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "{{%userchangeemail}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $before_email
 * @property string $after_email
 * @property string $reset_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class UserChangeEmail extends \yii\db\ActiveRecord
{
    
    const MAX_TRY_COUNT=5;
    const TRY_PERIOD_DAYS=1;
    const LINK_PERIOD_HOUR=1;
    
    const STATUS_WAIT=0;
    const STATUS_APPROVED=1;
    
    const SCENARIO_CREATE='create';
    const SCENARIO_APPROVE='approve';
    
    public static function tableName(){
        return '{{%user_change_email}}';
    }

    public function rules(){
        return [
            ['user_id', 'required'],
            ['user_id', 'integer'],
            
            ['before_email', 'required'],
            ['before_email', 'email'],
            ['before_email', 'string', 'max' => 255],
            
            ['after_email', 'required'],
            ['after_email', 'email'],
            ['after_email', 'unique', 'targetClass' => User::className(),'targetAttribute' => 'email', 'message' =>'Email занят.'],
            ['after_email', 'string', 'max' => 255],
            
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],

            ['created_user_ip','ip', 'negation' => true],
            ['created_user_ip', 'string', 'max' => 255],
            
            ['confirm_user_ip', 'ip', 'negation' => true],
            ['confirm_user_ip', 'string', 'max' => 255],
            
            ['confirm_user_agent', 'string', 'max' => 255],
            
            ['created_user_agent', 'string', 'max' => 255],
            
            ['created_at', 'integer'],
            ['updated_at', 'integer'],
            
            ['reset_token', 'string', 'max' => 255]
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'before_email' => 'Before Email',
            'after_email' => 'Email',
            'reset_token' => 'Reset Token',
            'created_at' => 'Создано',
            'updated_at' => 'Отредактировано',
            'status' => 'Статус',
        ];
    }
    
    public function behaviors(){
        return [TimestampBehavior::className()];
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['after_email'],
            self::SCENARIO_APPROVE => ['status','confirm_user_ip','confirm_user_agent'],
        ]);
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if($insert) {
                $user=User::findOne(Yii::$app->user->getId());
                $this->user_id=$user->id;
                $this->before_email=$user->email;
                $this->reset_token=Yii::$app->security->generateRandomString();
                $this->created_user_agent=getenv("HTTP_USER_AGENT");
                $this->created_user_ip= (Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
                $this->status= self::STATUS_WAIT;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
           $this->sendConfirmEmail();
        }
    }
    /*-----------------------------VALIDATORS---------------------------------*/
    public function validateIP($attribute, $params){
        if(!empty($attribute)&&!filter_var($attribute, FILTER_VALIDATE_IP))
            $this->addError($attribute, 'Введены некорректные данные');  
    } 
    /*-----------------------------VALIDATORS---------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function getStatusesArray(){
        return [
            self::STATUS_WAIT =>'Ожидает',
            self::STATUS_APPROVED =>'Подтвержден',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getStatusName(){
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*-------------------------------ACTIONS----------------------------------*/
    public function actChangeEmail(){
        if(!($user=$this->user)){
            return FALSE;
        }
        if($user->email!=$this->before_email){
            return false;
        }
        $transaction =  Yii::$app->db->beginTransaction();
        try {
            $this->scenario = self::SCENARIO_APPROVE;
            $this->confirm_user_agent=getenv("HTTP_USER_AGENT");
            $this->confirm_user_ip=(Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
            $this->status=self::STATUS_APPROVED;
            if(!$this->save()){
                return false;
            }
            $user->scenario = User::SCENARIO_PROFILE;
            $user->email=$this->after_email;
            if(!$user->save()){
                return false;
            }
            $transaction->commit();
            return true;
        } 
        catch (Exception $e) {
            $transaction->rollBack();
            return FALSE;
        }
    }
    /*-------------------------------ACTIONS----------------------------------*/
    
    public static function checkTryLimit(){ 
        if( self::find()
                ->where(['user_id'=>Yii::$app->user->getId(),'status'=>self::STATUS_WAIT])
                        ->andWhere(['>=','created_at',time()-(60*60*24*self::TRY_PERIOD_DAYS)])
                ->count()>=self::MAX_TRY_COUNT
          ){
            return false;
        }
        return true;
    }

    public static function findByResetToken($reset_token){   
        return self::find()->where(['reset_token'=>$reset_token,'status' => self::STATUS_WAIT])
                    ->andWhere(['>=','created_at',time()-(60*60*self::LINK_PERIOD_HOUR)])
                    ->one();
    }
    
    protected function sendConfirmEmail(){
        Yii::$app->mailer->compose('changeEmail', ['reset_token'=>$this->reset_token])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($this->before_email)
                        ->setSubject('Смена Email на ' . Yii::$app->name)
                        ->send();
        return true;
    }
}
