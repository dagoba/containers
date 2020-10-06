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
            'action'=>Yii::$app->params['advancedcash_url']
        ]);?>
            <?="Номер транзакции: $model->id";?><br>
            <?="Вы пополняете счет на: $model->amount $";?><br>
            <?="Платежная система: $model->PaymentSystem";?><br>
            
            <?php echo $form->field($model,'adv_acc')->hiddenInput(['name'=>'ac_account_email'])->label(false); ?>
            <?php echo $form->field($model,'adv_name')->hiddenInput(['name'=>'ac_sci_name'])->label(false); ?>
            <?php echo $form->field($model,'id')->hiddenInput(['name'=>'ac_order_id'])->label(false); ?>
            <?php echo $form->field($model,'adv_amount')->hiddenInput(['name'=>'ac_amount'])->label(false); ?> 
            <?php echo $form->field($model,'adv_currency')->hiddenInput(['name'=>'ac_currency'])->label(false); ?>
            <?php echo $form->field($model,'adv_hash')->hiddenInput(['name'=>'ac_sign'])->label(false); ?>
            
            <div class="form-group">
                <?= Html::submitButton('Оплатить', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>