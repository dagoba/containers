<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin([
            'action'=>Url::toRoute('/finance/backend/reject-withdrawal-application'),
            'id' => 'reject-userapplication-form',
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>false,
        ]); ?>
    <?= $form->field($model, 'modercomment')->textInput(['maxlength' => true]); ?>
    <?= $form->field($model, 'moderid')->hiddenInput()->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить',['class' =>'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
