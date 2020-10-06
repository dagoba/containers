<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-sm-4">
        <?= $model->account->balance?>
    </div>
    <div class="col-sm-4">
        <?= Html::a('Пополнить счет',['account/deposit'],['class'=>'btn btn-success'])?>
    </div>
    <div class="col-sm-4">
        <?= Html::a('Вывести средства',[''],['class'=>'btn btn-primary'])?>
    </div>
</div>