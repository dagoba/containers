<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_bitcoin".
 *
 * @property int $tr_id
 * @property int $count
 * @property int $confirmations
 * @property int $value
 * @property string $value_pc
 * @property string $transaction_hash
 * @property string $address
 * @property int $created_at
 * @property int $updated_at
 */
class Bitcoin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bitcoin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_id', 'count', 'confirmations', 'value', 'value_pc', 'transaction_hash', 'address'], 'required'],
            [['tr_id', 'count', 'confirmations', 'value', 'created_at', 'updated_at'], 'integer'],
            [['value_pc'], 'number'],
            [['transaction_hash', 'address'], 'string', 'max' => 500],
            [['tr_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [TimestampBehavior::className()];
    }
    public function attributeLabels()
    {
        return [
            'tr_id' => 'Tr ID',
            'count' => 'Count',
            'confirmations' => 'Confirmations',
            'value' => 'Value',
            'value_pc' => 'Value Pc',
            'transaction_hash' => 'Transaction Hash',
            'address' => 'Address',
            'created_at' => 'Create Time',
            'updated_at' => 'Update Time',
        ];
    }
    public static function confirmation($params) {
        $this->count += 1;
        $this->confirmations = (int) $params['confirmations'];
        $this->transaction_hash = $params['transaction_hash'];
        $this->address = $params['address'];
        if ($this->save()) {
            return $this->count;
        }
        return 0;
    }
    
    public static function createConfirm($params) {
        $model = new self();
        $model->tr_id = (int) $params['id'];
        $model->count = 1;
        $model->confirmations = (int) $params['confirmations'];
        $model->value = (int) $params['value'];
        $model->value_pc = round($params['value_pc'], 2);
        $model->transaction_hash = $params['transaction_hash'];
        $model->address = $params['address'];
        if ($model->save()) {
            return true;
        }
        return false;
    }
}
