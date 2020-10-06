<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
    <?php $form = ActiveForm::begin([
            'action'=>Url::toRoute('/user/profile/change-password'),
            'id' => 'only-modal-change-password-form',
            'enableClientValidation'=>true,
            'validateOnBlur'=>false,
            'validateOnChange'=>false,
            'enableAjaxValidation'=>true]);?>
        <div class="field-with-btn">
            <?= $form->field($model, 'currentPassword',
                [ 'template' =>'{label}<div class="password-custom">{input}<div class="btn-show-pass glyphicon glyphicon-eye-open"></div></div>{error}',])
            ->passwordInput(['maxlength' => true]) ?>
        </div>
        <?= $form->field($model, 'newPassword')
                ->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPasswordRepeat')
                ->passwordInput(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('main','Сохранить Изменения'), [
                'class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
<script type="text/javascript" data-cfasync="false">
$(document).ready(function(){
    $('.btn-show-pass').click(function(){
        var passInput=$(this).closest('.form-group').find('input');
        $(this).toggleClass('active');
        if($(this).hasClass('active')){
            $(this).addClass('glyphicon-eye-close');
            $(this).removeClass('glyphicon-eye-open');
            passInput.attr('type','text');
        }else {
            $(this).removeClass('glyphicon-eye-close');
            $(this).addClass('glyphicon-eye-open');
            passInput.attr('type','password');
        }
    });
});
</script>
