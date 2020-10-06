<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_curs_change".
 *
 * @property int $id
 * @property int $currency
 * @property string $before
 * @property string $after
 * @property int $created_at
 */
class CursChange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_curs_change';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency', 'before', 'after', 'created_at'], 'required'],
            [['currency', 'created_at'], 'integer'],
            [['before', 'after'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency' => 'Currency',
            'before' => 'Before',
            'after' => 'After',
            'time' => 'Time',
        ];
    }
//    public function behaviors()
//    {
//        return [
//            TimestampBehavior::className(),
//        ];
//    }
}
