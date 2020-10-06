<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_cyrrency_type}}".
 *
 * @property int $id
 * @property int $name
 * @property string $code
 * @property int $country_id
 * @property string $sign
 * @property int $status
 */
class FinanceCyrrencyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_cyrrency_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'code', 'country_id', 'status'], 'required'],
            [['id', 'name', 'country_id', 'status'], 'integer'],
            [['code', 'sign'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'country_id' => 'Country ID',
            'sign' => 'Sign',
            'status' => 'Status',
        ];
    }
}
