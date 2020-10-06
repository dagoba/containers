<?php
namespace app\components\widgets\MultiLang;

use yii\helpers\Html;

class MultiLang extends \yii\bootstrap\Widget
{
    public $cssClass;
    public function init(){}

    public function run() {

        echo $this->render('view', [
            'cssClass' => $this->cssClass,
        ]);

    }
}