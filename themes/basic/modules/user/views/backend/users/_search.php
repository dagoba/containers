<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
?>
<div class="row">
    <div class="col-sm-12">
    <?php $form = ActiveForm::begin([
        'method' => 'get']); ?>
        <div class="row">
            <div class="col-sm-1" >
                <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-2" >
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]);?>
            </div>
            <div class="col-sm-2" >
                 <?= $form->field($model, 'email')->textInput(['maxlength' => true]);?>
            </div>
            <div class="col-sm-2" >
                <label class="control-label" for="usersearch-date_from">Дата С</label>
                <?= DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'date_from',
                    'language' => 'ru',
                    'options'=>[
                        'style'=>'width:100%;',
                        'class'=>'form-control'
                    ],
                    'dateFormat' => 'dd.MM.yyyy',
                ]);?>
            </div>
            <div class="col-sm-2" >
                <label class="control-label" for="usersearch-date_to">Дата По</label>
                <?= DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'date_to',
                    'language' => 'ru',
                    'options'=>[
                        'style'=>'width:100%;',
                        'class'=>'form-control'
                    ],
                    'dateFormat' => 'dd.MM.yyyy',
                ]);?>
        
            </div>
            <div class="col-sm-3">
                <?= Html::submitButton('Фильтровать', [
                    'class' => 'btn btn-primary',
                    'style'=>'margin-right:10px;']) ?>
                <?= Html::a('Сбросить',[''],['class' => 'btn btn-default']);?>
            </div>
        </div>
   <?php ActiveForm::end(); ?>
</div>

