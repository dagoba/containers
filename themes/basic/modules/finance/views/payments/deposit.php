<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\user\models\Usertransaction;
$this->title= Yii::t('app','Пополнение счёта в системе');
$this->params['breadcrumbs'][] = $this->title;
?>  

<div class="row">
<div class="col-md-12">
            <h1><?=Yii::t('app','Пополнение счёта в системе')?>:</h1>
            <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <?php $form = ActiveForm::begin([
                'id'=>'deposit-form'
                ]);?>

            <?= $form->field($model, 'amount') ?>

            <?= $form->field($model, 'paymentsystem_id')->dropDownList(Usertransaction::getPaymentSystemsArray()) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
           
        
            
