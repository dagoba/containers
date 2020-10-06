<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\geo\models\GeoCountry;
use yii\helpers\Url;

$this->title = 'Редактирование информации о пользователе';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи системы', 'url' => ['/user/backend/users']];
$this->params['breadcrumbs'][] = ['label' => 'Подробная информация', 'url' => ['/user/backend/user-detailed-information','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <div class="left form-attributes" style="width: 410px;">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <div class="internal-form">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray());?>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'country_id')->dropDownList(GeoCountry::dropDownList(), [
                'prompt'=>'Укажите страну',
                'onchange'=>
                '$.post("'.Yii::$app->urlManager->createUrl('user/profile/get-regions-list?country_id=').'"+$(this).val(), 
                    function(data){$("#user-region_id").html(data);});'
            ]);?>
            <?= $form->field($model, 'region_id')->dropDownList([],[
                'prompt'=>'Укажите регион',
                'onchange'=>
                '$.post("'.Yii::$app->urlManager->createUrl('user/profile/get-cities-list?region_id=').'"+$(this).val(), 
                    function(data){$("#user-city_id").html(data);});'
            ]);?>
            <?= $form->field($model, 'city_id')->dropDownList([], [
                'prompt'=>'Укажите город']);?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
if($model->country_id!=NULL)
    echo
    '<script>
        $.ajax({
                 async:false,
                 type: "GET",
                 url: "'.Url::toRoute('/user/profile/get-regions-list').'",
                 data: "country_id='.$model->country_id.'&selected='.$model->region_id.'",
                 success: function(data)
                 { 
                    $("#user-region_id").html(data);
                    ';
if($model->city_id!="")
    echo
        '$.ajax({
            async:false,
            type: "GET",
            url: "'.Url::toRoute('/user/profile/get-cities-list').'",
            data: "region_id='.$model->region_id.'&selected='.$model->city_id.'",
            success: function(data){ $("#user-city_id").html(data);},
            error:function(){alert("Error");}
         });'; 
if($model->country_id!=NULL)
    echo
        '},
        error:function(){alert("Error");}
     }); 
</script>';
