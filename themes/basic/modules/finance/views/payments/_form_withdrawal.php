<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Заяка на вывод';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-8">
        <h1><?=$this->title?></h1>
        <br>
        <br>
        <p>Номер вашего счёта #<b><?=$user->account->user_id;?></b></p>
        <br>
        <br>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => false
        ]); ?>
            <?= $form->field($model, 'paymentsystem_id')
                    ->dropDownList($model->paymentSystemsArray(), [
                        'prompt'=>'Укажите платежную систему',
                    ]);?>
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'requisites')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'usercomment')->textarea() ?>
            <div id="additional-field-wmr">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                <p class="note" style="font-size: 12px;">
                    *Необходимо указать РЕАЛЬНЫЙ/ДЕЙСТВИТЕЛЬНЫЙ номер телефона.
                    В противном случае ПС не несёт ответственности за недоступность 
                    средств или неправильное зачисление.
                </p>
                <br>
            </div>
            <div id="additional-field">
                <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'card_holder')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'term')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Создать', ['class' =>'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    initStyle();
    function initStyle(){
       if($("#financeapplicationwithdrawal-paymentsystem_id").val()==="<?=$model::PAYMENT_VISA_OTHERS?>"||
          $("#financeapplicationwithdrawal-paymentsystem_id").val()==="<?=$model::PAYMENT_MASTER_CARD_OTHERS?>"){
            $('#additional-field').show();
            $('#additional-field-wmr').hide();
            $("#financeapplicationwithdrawal-phone").val("empty");
        }
        else if($("#financeapplicationwithdrawal-paymentsystem_id").val()==="<?=$model::PAYMENT_WMR?>"){
            $('#additional-field').hide();
            $('#additional-field-wmr').show();
        }
        else{
            $('#additional-field').hide();
            $('#additional-field-wmr').hide();
            $("#applicationwithdrawal-phone").val("empty");
            $("#applicationwithdrawal-inn").val("empty");
            $("#applicationwithdrawal-currency").val("empty");
            $("#applicationwithdrawal-card_holder").val("empty");
            $("#applicationwithdrawal-term").val("empty");
        } 
    }
    $("#financeapplicationwithdrawal-paymentsystem_id").change(function(){
        initStyle();
        if($(this).val()==="<?=$model::PAYMENT_VISA_OTHERS?>"||
          $(this).val()==="<?=$model::PAYMENT_MASTER_CARD_OTHERS?>"){
            $("#financeapplicationwithdrawal-inn").val("");
            $("#financeapplicationwithdrawal-phone").val("empty");
            $("#financeapplicationwithdrawal-currency").val("");
            $("#financeapplicationwithdrawal-card_holder").val("");
            $("#financeapplicationwithdrawal-term").val("");
        }
        if($("#financeapplicationwithdrawal-paymentsystem_id").val()==="<?=$model::PAYMENT_WMR?>")
            $("#financeapplicationwithdrawal-phone").val("");
    });
});
</script>
