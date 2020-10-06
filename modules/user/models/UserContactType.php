<?php

namespace app\modules\user\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user_contact_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $position
 * @property integer $status
 * @property string $pattern
 */
class UserContactType extends \yii\db\ActiveRecord
{
    
    const STATUS_VISIBLE=1;
    const STATUS_HIDDEN=0;

    public static function tableName()
    {
        return '{{%user_contact_type}}';
    }

    public function rules()
    {
        return 
        [
            ['name', 'required'],
            [['name'], 'string', 'max' => 256],
            
            ['position', 'required'],
            [['position'], 'integer'],
            
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            
            [['pattern'], 'string'],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Название',
            'position' => 'Позиция',
            'status' => 'Статус',
            'pattern' => 'Pattern',
        ];
    }
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getStatusName(){
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : 'Ошибка';
    }
     
    public static function dataList($type='empty'){
        return ArrayHelper::map(self::find()
                ->where(['status'=>self::STATUS_VISIBLE])->all(), 'id', 'name');        
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/    
    public static function getStatusesArray(){
        return [ 
            self::STATUS_VISIBLE => 'доступно',
            self::STATUS_HIDDEN => 'скрыто',
        ];
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
}
