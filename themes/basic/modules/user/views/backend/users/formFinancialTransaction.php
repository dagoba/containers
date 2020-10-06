<?php
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\user\models\Usertransaction;
$this->title = 'Зачисление/Списание по пользователю "'.$user->username.'"';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи системы', 'url' => ['/user/backend/users']];
$this->params['breadcrumbs'][] = 'Зачисление/Списание по пользователю';
?>
<div class="internal-pages">
    <div class="left form-attributes" style="width: 410px;">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <br>
        <br>
        <br>
        <div class="internal-form">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'operation_type')->dropDownList($model->operationArray());?>
            <?=$form->field($model, 'paymentsystem_id')->dropDownList(Usertransaction::moderPaySystemArr());?>
            <?=$form->field($model, 'amount')->textInput(['maxlength' => true]);?> 
            <?= $form->field($model, 'description')->textarea() ?>
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
                        'format' => 'dd.MM.yyyy hh:i',
                        'autoclose'=>true,
                        'weekStart'=>1, 
                        'startDate' => '01.05.2015 00:00', 
                        'todayBtn'=>true, //снизу кнопка "сегодня"
                    ]
                ]); ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
