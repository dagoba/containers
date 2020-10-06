<?php

namespace app\modules\user\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%user_carriage_ticket}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property int $created_at
 * @property int $status
 */
class UserCarriageTicket extends \yii\db\ActiveRecord
{
    
    const STATUS_CREATED = 1;
    const STATUS_REJECT = 2;
    const STATUS_PROCESSED =3;
    
    const SCENARIO_CHANGE_STATUS = 'change-status';
    const SCENARIO_CREATE_APPLICATION = 'create-application';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_carriage_ticket}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::statusArr())],
            
            
            
            [['user_id', 'created_at'], 'integer'],
             [['description'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('main', 'Пользователь'),
            'description' => Yii::t('main', 'Описание'),
            'created_at' => Yii::t('main', 'Дата'),
            'status' => Yii::t('main', 'Статус'),
        ];
    }
    
     public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CHANGE_STATUS => ['status'], 
            self::SCENARIO_CREATE_APPLICATION => [], 
        ]);
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
                $this->status = self::STATUS_CREATED;
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
    
    /*-------------------------------GETTERS----------------------------------*/
    protected function getStatusHumor(){
        switch ($this->status){
            case self::STATUS_CREATED: return 'label-warning';
            case self::STATUS_PROCESSED: return 'label-success';
            case self::STATUS_REJECT: return 'label-danger';
            default :return 'label-info';    
        }
    }
    
    public function getStatus_name(){
        $statuses = self::statusArr();
        if(in_array($this->status, array_keys($statuses)))
            return '<p title="'.$statuses[$this->status].'" class="has-tooltip label '.$this->statushumor.'">'.$statuses[$this->status].'</p>';       
        return '<p  title="'.$statuses[$this->status].'"class="label label-warning has-tooltip">'.
                Yii::t('main', 'Ошибка').'</p>';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    public static function statusArr(){
        return [
            self::STATUS_CREATED => Yii::t('main', 'Создан'),
            self::STATUS_PROCESSED => Yii::t('main', 'Обработан'),
            self::STATUS_REJECT => Yii::t('main', 'Отклонен'),
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
     /*------------------------------PERMISSION--------------------------------*/
    public function canApprove(){
        return ($this->status == self::STATUS_CREATED);
    }
    
    public function canCancel(){
        return ($this->status == self::STATUS_CREATED);
    }
    
    /*------------------------------PERMISSION--------------------------------*/
    
    /*--------------------------------ACTIONS---------------------------------*/
    public function actApprove(){
        $this->scenario = self::SCENARIO_CHANGE_STATUS;
        $this->status = self::STATUS_PROCESSED;
        return $this->save();
    }
    
    public function actCancel(){
        $this->scenario = self::SCENARIO_CHANGE_STATUS;
        $this->status = self::STATUS_REJECT;
        return $this->save();
    }
    
    /*--------------------------------ACTIONS---------------------------------*/
    
    public function sendModerInfoMail(){
        Yii::$app->mailer->compose('moderNewTicket', ['application'=>$this])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo(Yii::$app->params['adminEmail'])
                        ->setSubject('Новая заявка на заключения контракта в системе ' . Yii::$app->name)
                        ->send();
        return true;
    }
}
