<?php 
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use app\modules\user\models\UserContactType;
    $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'contact_id')->dropDownList(UserContactType::dataList(), [
            'prompt'=>Yii::t('app','Выберите тип контакта'),
            'onchange'=>
            '$.post("'.Yii::$app->urlManager->createUrl('user/profile/get-contact-hint?id=').'"+$(this).val(), 
                function(data){$("#usercontact-value").attr("placeholder", data);});'
        ]);?>
    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>