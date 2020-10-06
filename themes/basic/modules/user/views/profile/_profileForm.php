<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\geo\models\GeoCountry;
use app\modules\user\models\UserChangeEmail;
?>
    <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
    </div>
        <?=$this->render('_changeAvatar',['model'=> \app\modules\user\models\User::findOne(Yii::$app->user->id)])?>
<br>
<hr>
<br>
    <?=$this->render('_changeEmail',['model'=>new UserChangeEmail(),'email'=>$model->email])?>
    <?php $form = ActiveForm::begin([
                'action'=>Url::toRoute('/user/profile/change-personal-data'),
                'id' => 'only-modal-change-personal-data-form',
                'enableClientValidation'=>true,
                'validateOnBlur'=>false,
                'validateOnChange'=>false,
                'enableAjaxValidation'=>true]);?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
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
            <?= Html::submitButton(Yii::t('main','Сохранить Изменения'), [
                'class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
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

