<?php

namespace app\modules\user\models;

use app\modules\geo\models\GeoRegions;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%user_clocks}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $region_id
 */
class UserClocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%user_clocks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'required'],
             ['region_id', 'issetRegion'],
            [['user_id', 'region_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app','ID пользователя'),
            'region_id' => Yii::t('app','Регион'),
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
    
    /*-------------------------------GETTERS----------------------------------*/
    public static function regionsList($type='empty'){

        return ArrayHelper::map(GeoRegions::find()
                ->orderBy(['name_ru'=>SORT_ASC])->all(), 'id', (Yii::$app->language=='ru')? 'name_ru': 'name_en');        
    }
    
    public function getDate(){
        $defaultTimeZone=date_default_timezone_get();
        date_default_timezone_set($this->region->timezone);
        $date = [
            'H'=>date('H'),
            'i'=>date('i'),
            's'=>date('s')
        ];
        date_default_timezone_set($defaultTimeZone);
        return $date;
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getRegion(){
        return $this->hasOne(GeoRegions::className(), ['id' => 'region_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/

    /*------------------------------VALIDATORS--------------------------------*/
    public function issetRegion($attribute, $params){
        
        if(!empty($this->region_id) && self::find()->where([
                'user_id'=> Yii::$app->user->id,
                'region_id'=> intval($this->region_id)
            ])->exists()){
            $this->addError($attribute, Yii::t('app','У вас уже есть часы с данным регионом'));
        }              
    }
    /*------------------------------VALIDATORS--------------------------------*/
}
