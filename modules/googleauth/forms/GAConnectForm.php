<?php
namespace app\modules\googleauth\forms;

class GAConnectForm extends \yii\base\Model
{
    public $code = null;

    public function rules() {
        return [
            ['code', 'required'],
            [['code'], 'string', 'max' => 250],
        ];
    }

    public function attributeLabels() {
        return [
            'code' => 'Сгенерированный код',
        ];
    }

}
