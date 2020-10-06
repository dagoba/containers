<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div style="height: 50px;"></div>
<div class="row">
<div class="col-sm-12">
<div class="col-sm-2"></div>
    <div class="col-sm-8 user_panel">
    <h1 class="contact_h1">Подтверждение платежа</h1>
        <div class="second_line" style="margin-top: 20px;">
        <div class="alert alert-info" role="alert">
          <p class="">Проверьте информацию в заявке на пополнение</p>
        </div>
            
        </div>
        <?php $form = ActiveForm::begin([
            'id'=>'confirm-pay',
            'action'=>Yii::$app->params['advancedcash_url']
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
    <div class="col-sm-2"></div>
    </div>
</div>