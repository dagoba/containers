<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="second_line" style="margin-top: 20px;">
        <div class="alert alert-info" role="alert">
          <p class="">Проверьте информацию в заявке на пополнение</p>
        </div>
            
        </div>
        <?php $form = ActiveForm::begin([
            'id'=>'confirm-pay',
            'action'=>Yii::$app->params['perfectmoney_url']
        ]);?>
            <div class="panel panel-default">
              <!-- Default panel contents -->
              <div class="panel-heading">Подтверждение платежа:</div>

              <!-- Table -->
                  <table class="table">
                    <tr><td>1</td><td>Номер транзакции:</td><td><?=$model->id;?></td></tr>
                    <tr><td>2</td><td>Вы пополняете счет на: </td><td><?=$model->amount;?>$</td></tr>
                    <tr><td>3</td><td>Платежная система:</td><td><?=$model->PaymentSystem;?></td></tr>
                  </table>
            </div>
            
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