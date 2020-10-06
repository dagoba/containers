<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\user\models\Usertransaction;

?>         
<div class="row">
    <div class="col-sm-12">
        <div class="second_line">
            <p><?= Yii::t('app','Пополнение счёта в системе')?>:</p>
        </div>
        <?php $form = ActiveForm::begin([
                'id'=>'deposit-form'
                ]);?>

            <?= $form->field($model, 'amount') ?>

            <?= $form->field($model, 'paymentsystem_id')->dropDownList(Usertransaction::getPaymentSystemsArray()) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
           
        
            
