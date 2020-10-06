<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="second_line">
            <p>Подтверждение платежа:</p>
            <p class="">Проверьте информацию в заявке на пополнение</p>
        </div>
        <?php $form = ActiveForm::begin([
            'id'=>'confirm-pay',
            'action'=>Yii::$app->params['perfectmoney_url']
        ]);?>
            <?="Номер транзакции: $model->id";?><br>
            <?="Вы пополняете счет на: $model->amount $";?><br>
            <?="Платежная система: $model->PaymentSystem";?><br>
            
            <?php echo $form->field($model,'pf_acc')->hiddenInput(['name'=>'PAYEE_ACCOUNT'])->label(false); ?>
            <?php echo $form->field($model,'pf_name')->hiddenInput(['name'=>'PAYEE_NAME'])->label(false); ?>
            <?php echo $form->field($model,'id')->hiddenInput(['name'=>'PAYMENT_ID'])->label(false); ?>
            <?php echo $form->field($model,'pf_amount')->hiddenInput(['name'=>'PAYMENT_AMOUNT'])->label(false); ?> 
            <?php echo $form->field($model,'pf_units')->hiddenInput(['name'=>'PAYMENT_UNITS'])->label(false); ?>
            <?php echo $form->field($model,'pf_status')->hiddenInput(['name'=>'STATUS_URL'])->label(false); ?>
            <?php echo $form->field($model,'pf_pay')->hiddenInput(['name'=>'PAYMENT_URL'])->label(false); ?>
            <?php echo $form->field($model,'pf_paymeth')->hiddenInput(['name'=>'PAYMENT_URL_METHOD'])->label(false); ?>
            <?php echo $form->field($model,'pf_nopay')->hiddenInput(['name'=>'NOPAYMENT_URL'])->label(false); ?>
            <?php echo $form->field($model,'pf_nopaymeth')->hiddenInput(['name'=>'NOPAYMENT_URL_METHOD'])->label(false); ?>
            <?php echo $form->field($model,'id')->hiddenInput(['name'=>'SUGGESTED_MEMO'])->label(false); ?>

            <div class="form-group">
                <?= Html::submitButton('Оплатить', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>