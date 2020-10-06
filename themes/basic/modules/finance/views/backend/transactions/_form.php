<?php 
    use  yii\widgets\ActiveForm;
    use yii\helpers\Html;
	use kartik\datetime\DateTimePicker;
    $form = ActiveForm::begin(); 
?>

   <?php 
                if($model->created_at) {
                    $model->created_at = date("d.m.Y H:i", (integer) $model->created_at);
                }    
                echo $form->field($model, 'created_at')->widget(DateTimePicker::className(),[
                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Дата/время...'],
                    'convertFormat' => true,
                    'value'=> date("d.m.Y h:i",(integer) $model->created_at),
                    'pluginOptions' => [
                        'format' => 'dd.MM.yyyy H:i',
                        'autoclose'=>true,
                        'weekStart'=>1, 
                        'startDate' => '01.05.2015 00:00', 
                        'todayBtn'=>true, //снизу кнопка "сегодня"
                    ]
                ]); ?>

<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'paymentsystem_id')->dropDownList($model->getWidthrawalPaymentSystemsArray(), [
            'prompt'=>'Укажите платежную систему']);?>
<?= $form->field($model, 'type_id')->dropDownList($model->typeArray(), [
            'prompt'=>'Укажите тип операции']);?>
<?= $form->field($model, 'status')->dropDownList($model->statusArray(), [
            'prompt'=>'Укажите статус']);?>
    <div class="form-group">
        <?= Html::submitButton( $model->isNewRecord?
                Yii::t('app','Сохранить') : Yii::t('app','Редактировать'), 
                ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<style>
.field-usertransaction-created_at input{
background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    display: block;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 100%;
}
</style>



