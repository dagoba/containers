<?php
namespace app\modules\googleauth\components;

use app\modules\googleauth\models\GAConnect;

class GoogleauthManager extends \yii\base\Component {

    public function init() {  
    }

    public function isActive($user_id) {
        $connect = GAConnect::findOne($user_id);
        if ($connect !== null && $connect->status == GAConnect::STATUS_ON) {
            return true;
        }
        return false;
    }
}
